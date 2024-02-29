<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryTranslation;
use App\Utility\CategoryUtility;
use Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Cache;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Log;

class CategoryController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_product_categories'])->only('index');
        $this->middleware(['permission:add_product_category'])->only('create');
        $this->middleware(['permission:edit_product_category'])->only('edit');
        $this->middleware(['permission:delete_product_category'])->only('destroy');
    }

    public function jstreeSearch(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $formattedCategories = Cache::get('categories_tree');

        if (isset($formattedCategories)) {
            $data = $this->searchAndHighlightCategoryJstree($formattedCategories, $searchTerm);
        } else {
            $categories = Category::where('parent_id', 0)->get();

            $data = $this->jsTreeFormat($categories);
        }
        // Assuming you fetch your categories from a database or another source
        // Highlight and filter categories based on the search term
        $highlightedCategories = $this->searchAndHighlightCategoryJstree($data, $searchTerm);

        // Return the search result
        return response()->json($highlightedCategories);
    }


    public function jstreeFormat($categories = null, $level = 0, $parentId = 0)
    {
        $formatted = [];
        foreach ($categories as $category) {
            // Check if the category has children
            $hasChildren = $category->childrenCategories()->exists();

            // Initialize the formatted category
            $formattedCategory = [
                'id' => (string)$category->id,
                'text' => $category->name,
                'level' => $level,
                'children' => [] // Add a 'children' key for nesting
            ];

            // If the category has children, fetch and format them
            if ($hasChildren) {
                $children = $category->childrenCategories()->get();
                $formattedCategory['children'] = $this->jstreeFormat($children, $level + 1, $category->id);
            }

            $formatted[] = $formattedCategory;
        }

        // Optionally, you might want to cache the top-level categories separately to avoid caching the whole tree every time.
        if ($level === 0) {
            Cache::put('categories_tree', $formatted, 60 * 24 * 15); // Adjust the cache key/name as necessary
        }

        return $formatted;
    }

    public function searchAndHighlightCategoryJstree($categories, $searchTerm, $highlightStartTag = '<mark>', $highlightEndTag = '</mark>')
    {
        $result = [];
        foreach ($categories as $category) {
            $hasMatch = false;
            if (stripos($category['text'], $searchTerm) !== false) {
                $category['text'] = preg_replace("/($searchTerm)/i", "$highlightStartTag$1$highlightEndTag", $category['text']);
                $hasMatch = true;
            }

            if (!empty($category['children'])) {
                $searchedChildren = $this->searchAndHighlightCategoryJstree($category['children'], $searchTerm, $highlightStartTag, $highlightEndTag);
                if (!empty($searchedChildren)) {
                    $category['children'] = $searchedChildren;
                    $hasMatch = true;
                }
            }

            if ($hasMatch) {
                $result[] = $category;
            }
        }
        return $result;
    }

    public function jstree(Request $request)
    {
        $parentId = $request->input('id');

        if ($parentId == '#' || is_null($parentId)) {
            // Fetch top-level nodes. For instance, where 'parent_id' is null or 0
            $nodes = Category::where('parent_id', 0)->get();
        } else {
            // Fetch children of $parentId
            $nodes = Category::where('parent_id', $parentId)->get();
        }

        // Map the nodes to the format required by JSTree
        $data = $nodes->map(function ($node) {
            return [
                'id' => $node->id,
                'text' => $node->name,
                'children' => $node->childrenCategories()->count() > 0 // or any logic to determine if the node has children
            ];
        })->toArray();


        // Return the response
        return response()->json($data);
    }


    /**
     * Fetches categories based on the provided request.
     *
     * @param Request $request the request object
     * @return JsonResponse
     */
    public function fetchCategories(Request $request)
    {
        $formattedCategories = Cache::get('categories_children');
        if ($request->has("search") && !empty($request->search['value'])) {
            $searchResults = $this->searchAndHighlightCategory($formattedCategories, $request->search['value']);
            return response()->json(['data' => $searchResults,'recordsTotal' => Category::all()->count()]);
        }
        if (isset($formattedCategories)) {
            return response()->json(['data' => $formattedCategories,'recordsTotal' => Category::all()->count()]);
        } else {
            $parentId = $request->input('parent_id', 0); // Default to fetching top-level categories

            $categories = Category::where('parent_id', $parentId)->get();

            $formattedCategories = $this->formatCategories($categories);

            return response()->json(['data' => $formattedCategories,'recordsTotal' => $categories->all()->count()]);
        }
    }

    /**
     * Search and highlight the specified search term within the given categories.
     *
     * @param array $categories The array of categories to search within.
     * @param string $searchTerm The term to search for within the categories.
     * @param string $highlightStartTag The starting tag for highlighting the search term (default: '<mark>').
     * @param string $highlightEndTag The ending tag for highlighting the search term (default: '</mark>').
     * @return array The array of categories with the search term highlighted.
     */
    public function searchAndHighlightCategory($categories, $searchTerm, $highlightStartTag = '<mark>', $highlightEndTag = '</mark>')
    {
        $result = [];

        foreach ($categories as $category) {
            $hasMatch = false;
            // Check if current category name contains the search term
            if (stripos($category['name'], $searchTerm) !== false) {
                // Highlight the search term within the name
                $category['name'] = preg_replace("/($searchTerm)/i", "$highlightStartTag$1$highlightEndTag", $category['name']);
                $hasMatch = true;
            }

            // If the category has children, search within the children recursively
            if (!empty($category['children'])) {
                $searchedChildren = $this->searchAndHighlightCategory($category['children'], $searchTerm, $highlightStartTag, $highlightEndTag);
                if (!empty($searchedChildren)) {
                    $category['children'] = $searchedChildren;
                    $hasMatch = true; // There's a match in the children
                }
            }

            // If a match is found in the current category or any of its children, add it to the result
            if ($hasMatch) {
                $result[] = $category;
            }
        }

        return $result;
    }

    /**
     * Format the categories for display in a hierarchical structure.
     *
     * @param array $categories The categories to be formatted
     * @param int $level The nesting level of the categories
     * @param int $parentId The parent category ID
     * @return array The formatted categories
     */

    protected function formatCategories($categories, $level = 0, $parentId = 0)
    {
        $formatted = [];
        foreach ($categories as $category) {
            // Check if the category has children
            $hasChildren = $category->childrenCategories()->exists();
            $parentCategoryName = $category->parent ? $category->parent->name : null;

            // Check permissions for the current user on this category
            $canEdit = auth()->user()->can('edit_product_category', $category);
            $canDelete = auth()->user()->can('delete_product_category', $category);

            // Generate URLs for edit and delete actions
            $editUrl = $canEdit ? route('categories.edit', ['id' => $category->id, 'lang' => app()->getLocale()]) : null;
            $deleteUrl = $canDelete ? route('categories.destroy', $category->id) : null;

            // Initialize the formatted category
            $formattedCategory = [
                'DT_RowId' => (string)$category->id,
                'id' => (string)$category->id,
                'level' => $level,
                'key' => (string)$category->id,
                'parent' => (string)$parentId,
                'order_level' => $category->order_level,
                'cover_image' => $category->cover_image,
                'thumbnail_image' => $category->thumbnail_image,
                'commision_rate' => $category->commision_rate,
                'cat_level' => $category->level,
                'featured' => $category->featured,
                'parentName' => $parentCategoryName,
                'name' => $category->name,
                'value' => 0,
                'hasChildren' => $hasChildren,
                'can_edit' => $canEdit,
                'can_delete' => $canDelete,
                'edit_url' => $editUrl,
                'delete_url' => $deleteUrl,
                'children' => [] // Add a 'children' key for nesting
            ];

            // If the category has children, fetch and format them
            if ($hasChildren) {
                $children = $category->childrenCategories()->get();
                $formattedCategory['children'] = $this->formatCategories($children, $level + 1, $category->id);
            }

            $formatted[] = $formattedCategory;
        }

        // Optionally, you might want to cache the top-level categories separately to avoid caching the whole tree every time.
        if ($level === 0) {
            Cache::put('categories_children', $formatted, 60 * 24 * 15); // Adjust the cache key/name as necessary
        }

        return $formatted;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('backend.product.categories.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,)
    {
        $lang = app()->getLocale();

        return view('backend.product.categories.create', compact('lang'));
    }

    public function fetch_category_attribute(Request $request)
    {
        $parent = Category::find($request->category_id);
        $expected_ids = [];
        $expected_ids = $this->getexpected_ids($parent);
        
        return Attribute::whereNotIn('id', $expected_ids)->get();
    }

    public function fetch_parent_attribute(Request $request)
    {
        $parent = Category::find($request->category_id);
        $expected_ids = [];
        
        $expected_ids = $this->getexpected_ids($parent);
        
        return Attribute::whereIn('id', $expected_ids)->get();
    }

    public function getexpected_ids($categorie)
    {
        $ids = $categorie?->categories_attributes()->pluck('attribute_id')->toArray() ?? [];
        if ($categorie?->parent_id != "0" && $categorie) {
            $cat = Category::find($categorie?->parent_id);
            $ids = array_merge(
                $ids,
                $this->getexpected_ids($cat)
            );
        }
        return $ids;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $lang = app()->getLocale();
        try {
            $category = new Category;
            $category->name = $request['name_' . $lang];
            $category->order_level = $request->order_level ?? 0;
            $category->digital = $request->digital;

            if ($request->hasFile('thumbnail_image')) {
                $path = $request->file('thumbnail_image')->store('categories');
                $category->thumbnail_image = $path;
            }

            if ($request->hasFile('cover_image')) {
                $path_cover = $request->file('cover_image')->store('categories');
                $category->cover_image = $path_cover;
            }

            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;
            $category->parent_id = $request->parent_id;
            $category->featured = $request->featured == "on" ? 1 : 0;

            $category->slug = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request['name_' . $lang])));

            $category->commision_rate = $request->commision_rate ?? 0;

            $category->save();

            $category->attributes()->sync($request->filtering_attributes);
            $category->categories_attributes()->sync($request->category_attributes);

            foreach (get_all_active_language() as $key => $language) {
                $langPrefix = '_' . $language->code;
                $categoryTranslation = CategoryTranslation::firstOrNew(['lang' => $language->code, 'category_id' => $category->id]);
                $categoryTranslation->name = $request['name' . $langPrefix];
                $categoryTranslation->description = $request['description' . $langPrefix];
                $categoryTranslation->meta_title = $request['meta_title' . $langPrefix];
                $categoryTranslation->meta_description = $request['meta_description' . $langPrefix];
                $categoryTranslation->save();
            }

            Cache::forget('categories_children');
            Cache::forget('categories_tree');

            flash(translate('Category has been inserted successfully'))->success();
            return redirect()->route('categories.index');
        } catch (QueryException $e) {
            // Handle database related errors
            Log::error($e->getMessage());
            return back()->withErrors('There was a problem saving the category. Please try again.')->withInput();
        } catch (Exception $e) {
            // Handle general errors
            Log::error($e->getMessage());
            return back()->withErrors('An unexpected error occurred. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $category = Category::findOrFail($id);

        // Get all parents
        $breadcrumbs = $category->parents();
        // Add current category to the end
        $breadcrumbs->push($category);
        $category_attributes = $category->categories_attributes()->pluck('attribute_id');
        $category_filtring_attributes = $category->attributes()->pluck('attribute_id');
        return view('backend.product.categories.edit', compact('category', 'lang', 'category_attributes', 'category_filtring_attributes','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $lang = $request->lang;

        try {
            // Fetch the existing category using the provided ID
            $category = Category::findOrFail($id);

            if($lang == env("DEFAULT_LANGUAGE")){
                $category->name = $request->name;
                // Update slug for the category
                $category->slug = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)));
            }
            $category->order_level = $request->order_level ?? $category->order_level;
            $category->digital = $request->digital;

            // Update thumbnail_image if a new file is provided
            if ($request->hasFile('thumbnail_image')) {
                $path = $request->file('thumbnail_image')->store('categories');
                $category->thumbnail_image = $path;
            }

            // Update cover_image if a new file is provided
            if ($request->hasFile('cover_image')) {
                $path_cover = $request->file('cover_image')->store('categories');
                $category->cover_image = $path_cover;
            }

            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;

            if($category->id != 1){
                $category->parent_id = $request->parent_id;
            }
            $category->featured = $request->featured == "on" ? 1 : 0;

        

            $category->commision_rate = $request->commision_rate ?? $category->commision_rate;

            // Save the updated category
            $category->save();

            // Sync attributes if provided
            if ($request->has('filtering_attributes')) {
                $category->attributes()->sync($request->filtering_attributes);
            }

            if ($request->has('category_attributes')) {
                $category->categories_attributes()->sync($request->category_attributes);
            }

            // Update translations for all active languages
            $category_translation = CategoryTranslation::firstOrNew(['lang' => $request->lang, 'category_id' => $category->id]);
            $category_translation->name = $request->name;
            $category_translation->description = $request->description;
            $category_translation->meta_title = $request->meta_title;
            $category_translation->meta_description = $request->meta_description;
            $category_translation->save();

            // Clear relevant caches
            Cache::forget('categories_children');
            Cache::forget('categories_tree');
            Cache::forget('featured_categories');

            // Flash success message
            flash(translate('Category has been updated successfully'))->success();
            return redirect()->route('categories.index');
        } catch (QueryException $e) {
            // Handle database related errors
            Log::error($e->getMessage());
            return back()->withErrors('There was a problem updating the category. Please try again.')->withInput();
        } catch (Exception $e) {
            // Handle general errors
            Log::error($e->getMessage());
            return back()->withErrors('An unexpected error occurred. Please try again.')->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->attributes()->detach();

        // Category Translations Delete
        foreach ($category->category_translations as $key => $category_translation) {
            $category_translation->delete();
        }

        foreach (Product::where('category_id', $category->id)->get() as $product) {
            $product->category_id = null;
            $product->save();
        }

        CategoryUtility::delete_category($id);
        Cache::forget('featured_categories');
        Cache::forget('categories_children');
        Cache::forget('categories_tree');

        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('categories.index');
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        $category->save();
        Cache::forget('featured_categories');
        Cache::forget('categories_children');
        Cache::forget('categories_tree');

        return 1;
    }

    public function categoriesByType(Request $request)
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', $request->digital)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.categories.categories_option', compact('categories'));
    }



    public function getCategoriesTree()
    {
        $categories = Category::select('categories.id', 'categories.name', 'categories.parent_id as parentId', 'parent.name as parentName', 'categories.icon', 'categories.order_level', 'categories.level', 'categories.featured')
            ->leftJoin('categories as parent', 'categories.parent_id', '=', 'parent.id')
            ->get()
            ->each(function ($category) {
                // Assuming you have a method to get the URL for the icon
                $category->iconUrl = $category->icon ? url('path/to/icons/' . $category->icon) : null;

                // Check permissions for each category
                $category->canEdit = Auth::user()->can('edit_product_category', $category);
                $category->canDelete = Auth::user()->can('delete_product_category', $category);
            });

        return response()->json($categories);
    }
}
