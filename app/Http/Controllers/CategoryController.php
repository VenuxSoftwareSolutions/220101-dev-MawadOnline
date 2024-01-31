<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryTranslation;
use App\Utility\CategoryUtility;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Cache;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $intermidiateparent = [];
        $mycategory_ids = [];
        $sort_search = null;
        if (!$request->has('search') || $request->search == '') {
            $categories = Category::where('parent_id', '=', 0)->with(['childrenCategories'])->orderBy('order_level', 'asc');
            $categories = $categories->paginate(50);
        } else {
            $sort_search = $request->search;
            $mycategories = Category::where('name', 'like', '%' . $sort_search . '%')->with(['childrenCategories'])->orderBy('order_level', 'asc');
            
            $mycategories = $mycategories->get();
            foreach ($mycategories as  $category) {

                array_push($intermidiateparent, $category->parent_id);
                $intermidiateparent = array_merge($intermidiateparent, $this->categorygetparents($category->parent_id));
                foreach ($category->childrenCategories as $child) {

                    array_push($mycategory_ids, $child->id);
                }
                array_push($mycategory_ids, $category->id);
            }
            // dd( $intermidiateparent);
            $categories = Category::whereIn('id', $intermidiateparent)->where('parent_id', '=', 1)->paginate(100);
        }

        $intermidiateparent_tostring = implode(',', $intermidiateparent);
        $mycategory_ids_tostring = implode(',', $mycategory_ids);
        return view('backend.product.categories.index', compact('categories', 'sort_search', 'intermidiateparent_tostring', 'mycategory_ids_tostring'));
    }
    public function categorygetparents($id)
    {
        $category = Category::find($id);
        $emptyarray = [];
        if ($category->parent_id != 0) {
            array_push($emptyarray, $category->parent_id);
            return array_merge($emptyarray,    $this->categorygetparents($category->parent_id));
        }
        return [];
    }
    public function getsubcategories(Request $request)
    {
        $keyword = $request->searchablestring;
        if ($request->searchablestring == '')
            $categories =  Category::where('parent_id', '=', $request->id)->with('childrenCategories')->get();
        else {
            $mycategory_ids = explode(',', $request->mycategory_ids);
            $myintermidiateparent = explode(',', $request->intermidiateparent);

            $categories = Category::where('parent_id', '=', $request->id)
                ->whereIn('id', array_merge($myintermidiateparent, $mycategory_ids))

                ->with('childrenCategories') //,function($q)use($mycategory_ids,$myintermidiateparent){$q->whereIn('id',array_merge($myintermidiateparent,$mycategory_ids));})
                ->get() ->map(function ($row) use ($keyword) {
                    $row->name = preg_replace('/(' . $keyword . ')/i', "<b style=background-color:yellow>$1</b>", $row->name);
                    return $row;
                });
        }
        $classes = $request->classes;
        $level = 0;
        if (count($categories) > 0) {
            $level = $this->getlevelcategory($categories[0]);
        }
        return view('backend.product.categories.list-subcategories', ['categories' => $categories, 'level' => $level, 'classes' => $classes]);
    }
    private function getlevelcategory($category)
    {
        $level = 0;
        if ($category->parent_id != 0) {
            $level++;
            $level += $this->getlevelcategory(Category::find($category->parent_id));
        }
        return $level;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,)
    {
        $lang = $request->lang;

        $categories = Cache::get('categories_children');
        if (isset($categories)) {
            return view('backend.product.categories.create', compact('categories', 'lang'));
        } else {
            $categories = Category::where('parent_id', 1)
                ->where('digital', 0)
                ->with('childrenCategories')
                ->get();

            return view('backend.product.categories.create', compact('categories', 'lang'));
        }
    }

    public function fetch_category_attribute(Request $request)
    {
        $parent = Category::find($request->category_id);
        $expected_ids = [];
        if ($parent?->parent_id != "0") {
            $expected_ids = $this->getexpected_ids($parent);
        }
        /* if($parent){
            $parent->categories_attributes
        }
        $category_attributes =Attribute::where(function())*/
        return Attribute::whereNotIn('id', $expected_ids)->get();
    }
    public function fetch_parent_attribute(Request $request)
    {
        $parent = Category::find($request->category_id);
        $expected_ids = [];
        if ($parent?->parent_id != "0") {
            $expected_ids = $this->getexpected_ids($parent);
        }
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
    public function store(Request $request)
    {
        $validationaray = [];
        foreach (get_all_active_language() as $key => $language) {
            $langcode =  $language->code == 'en' ? '' : '_' . $language->code;
            $length =  $language->code == 'en' ? 60 : 110;
            $validationaray = array_merge(
                $validationaray,
                ['name' . $langcode => 'required|unique:category_translations,name|max:' . $length, 'description' . $langcode  => 'required']
            );
        }
        array_merge($validationaray, ['digital' => 'required']);
        if ($request->featured == 'on') {
            $validationaray = array_merge($validationaray, ['cover_image' => 'required', 'parent_id' => 'not_in:0']);
        } else {
            $validationaray = array_merge($validationaray, ['parent_id' => 'not_in:0']);
        }

        $request->validate($validationaray);

        $category = new Category;
        $category->name = $request->name;
        $category->order_level = 0;
        if ($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->digital = $request->digital;
        $category->banner = $request->cover_image; //$request->banner;
        $category->icon = $request->cover_image; //$request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        }

        if ($request->slug != null) {
            $category->slug = strtolower(reg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)));
        } else {
            $category->slug = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)));
        }
        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        $category->attributes()->sync($request->filtering_attributes);
        $category->categories_attributes()->sync($request->category_attributes);

        foreach (get_all_active_language() as $key => $language) {
            $category_translation = CategoryTranslation::firstOrNew(['lang' => $language->code, 'category_id' => $category->id]);
            $prefixlang = $language->code == env('DEFAULT_LANGUAGE') ? '' : '_' . $language->code;
            $attribute = 'name' . $prefixlang;
            $category_translation->name = $request->$attribute;
            $attribute = 'description' . $prefixlang;
            $category_translation->description = $request->$attribute;
            $attribute = 'meta_title' . $prefixlang;
            $category_translation->meta_title = $request->$attribute;
            $attribute = 'meta_description' . $prefixlang;
            $category_translation->meta_description = $request->$attribute;
            $category_translation->save();
        }
        /*$category_translation = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->description = $request->description;
        $category_translation->meta_title = $request->meta_title;
        $category_translation->meta_description = $request->meta_description;
        $category_translation->save();
        $category_translation = CategoryTranslation::firstOrNew(['lang' => 'sa', 'category_id' => $category->id]);
        $category_translation->name = $request->name_sa;
        $category_translation->description = $request->description_sa;
        $category_translation->meta_title = $request->meta_title_ar;
        $category_translation->meta_description = $request->meta_description_ar;
        $category_translation->save();*/

        flash(translate('Category has been inserted successfully'))->success();
        return redirect()->route('categories.index');
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
        $categories = Category::where('parent_id', 0)
            ->where('digital', $category->digital)
            ->with('childrenCategories')
            ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=', $category->id)
            ->orderBy('name', 'asc')
            ->get();

        $category_attributes = $category->categories_attributes()->pluck('attribute_id');
        $category_filtring_attributes = $category->attributes()->pluck('attribute_id');
        return view('backend.product.categories.edit', compact('category', 'categories', 'lang', 'category_attributes', 'category_filtring_attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $category->name = $request->name;
        }
        if ($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        $previous_level = $category->level;

        if ($request->parent_id != "0" && $request->parent_id != null) {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        } else {
            $category->parent_id = 0;
            $category->level = 0;
        }

        if ($category->level > $previous_level) {
            CategoryUtility::move_level_down($category->id);
        } elseif ($category->level < $previous_level) {
            CategoryUtility::move_level_up($category->id);
        }

        if ($request->slug != null) {
            $category->slug = strtolower($request->slug);
        } else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . Str::random(5);
        }


        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        $category->attributes()->sync($request->filtering_attributes);
        $category->categories_attributes()->sync($request->category_attributes);

        $category_translation = CategoryTranslation::firstOrNew(['lang' => $request->lang, 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->description = $request->description;
        $category_translation->meta_title = $request->meta_title;
        $category_translation->meta_description = $request->meta_description;
        $category_translation->save();

        Cache::forget('featured_categories');
        flash(translate('Category has been updated successfully'))->success();
        return back();
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

        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('categories.index');
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        $category->save();
        Cache::forget('featured_categories');
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

        $categories = Cache::get('categories_children');
        if (isset($categories)) {
            return response()->json($categories);
        } else {
            $categories = Category::with('childrenCategories')->where('parent_id', 0)->get();
            return response()->json($this->formatCategoriesForSelect2($categories));
        }
    }


    private function formatCategoriesForSelect2($categories, $depth = 0)
    {
        $formatted = [];
        foreach ($categories as $category) {
            $formattedCategory = [
                'id' => $category->id,
                'text' => str_repeat('-', $depth) . $category->name,
            ];
            if ($category->childrenCategories->isNotEmpty()) {
                $formattedCategory['children'] = $this->formatCategoriesForSelect2($category->childrenCategories, $depth + 1);
            }

            $formatted[] = $formattedCategory;
        }

        Cache::put('categories_children', $formatted, 60 * 24 * 15);

        return $formatted;
    }
}
