<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Search;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Shop;
use App\Models\Unity;
use App\Models\Attribute;
use App\Models\ColorGroup;
use App\Models\Discount;
use App\Models\PricingConfiguration;
use App\Models\AttributeCategory;
use App\Utility\CategoryUtility;
use Illuminate\Support\Facades\App;
use App\Services\ProductService;
use Illuminate\Support\Facades\Cache;
use Debugbar;

use DB;

class SearchController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request, $category_id = null, $brand_id = null)
    {
        $language = App::getLocale();
        $query = $request->keyword;
        $category = [];
        $categories = [];
        $cacheKey = $this->generateCacheKey($request);
        $cachedData = Cache::get($cacheKey);
        if ($cachedData) {
            return response()->json($cachedData);
        }
    
        if ($request->category_id) {
            $category_id = $request->category_id;
        }
        if ($request->category_id === 0 || $request->category_id === '0') {
            $category_id = null;
        }

        $products = Product::IsApprovedPublished()->nonAuction()->with('pricingConfiguration');
        Debugbar::info($products);

        $attributes = Cache::remember('attributes', 3600, function () {
            return Attribute::select('id', 'name', 'type_value')->get();
        });
        $id_products = [];

        //retrieve minimum and maximum price

        $productIds = $products->pluck('products.id')->toArray();

        $discountedPrices = $this->productService->getDiscountedPrices($products);

        // Calculate the min and max prices
        $prices = $discountedPrices->pluck('discounted_price')->toArray();
        $max_all_price = !empty($prices) ? max($prices) : 1;
        $min_all_price = !empty($prices) ? min($prices) : 0;

        //Filter products By Category
        ['products' => $products, 'attributes' => $attributes, 'category_ids' => $category_ids, 'category_parents_ids' => $category_parents_ids, 'category' => $category] = $this->productService->filterProductsAndAttributesByCategory($category_id, $query);

        $baseQuery = clone $products;

        $brandQuery = clone $baseQuery;

        $brands = $brandQuery->join('brands', 'brands.id', '=', 'products.brand_id')
        ->where('brands.approved', '=', 1)  
        ->whereNull('products.deleted_at')  
        ->select('brands.*')
        ->distinct()
        ->get();
        
    
        $brand_ids = $this->productService->filterProductsByBrand($request, $brand_id, $products);



        //filter by vendor
        $shopQuery = clone $baseQuery;

        $shops = $request->shops;

        $shops = $shopQuery->join('users', 'users.id', '=', 'products.user_id')->join('shops', 'shops.user_id', '=', 'users.id')->where('users.banned', '!=', 1)->where('shops.verification_status', '!=', 0)->select('shops.*')->distinct()->get();

        $vender_user_ids = $this->productService->filterProductsByShop($request, $products);


        //filter by Rating
        $rating = $request->rating;
        $products = $this->productService->filterProductsByRating($rating, $products);

        //filter by creation date
        $sort_by = $request->sort_by;
        $products = $this->productService->applySorting($products, $sort_by);

        //filter by price range
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $products = $this->productService->applyPriceFilter($products, $min_price, $max_price);

        // filter product by name
        if ($query) {
            $products->whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", ['+' . $query . '*']);
        }
                
        //filter product by category
        if ($category_id != null) {
            $products->whereIn('category_id', $category_ids);
        }

        $conditions = [];

        $conditions = array_merge($conditions, ['categories' => $category_ids, 'query' => $query]);

        //filter product by color
        $request_all = request()->input();
        $colors = ColorGroup::all();

        //get the category hierarchy
        [$category_parent, $category_parent_parent] = $this->productService->getCategoryHierarchy($category);

        //filter product by other attributes
        $products = $this->productService->filterProductsByAttributes($products, $request_all);
        $selected_attribute_values = $this->productService->getSelectedAttributeValues($attributes);
        $products = $products->select([
            'id',
            'slug',
            'name',             
            'auction_product',
            'discount',          
            'wholesale_product',
            'featured',
            'category_id',
            'brand_id',
            'user_id',
            'created_at'
        ])
        ->paginate(6);
        if ($request->ajax()) {
            $html = '';
            foreach ($products as $product) {
                $html_product = view('frontend.' . get_setting('homepage_select') . '.partials.product_box_1', ['product' => $product])->render();
                $html .= '<div class="col border-right border-bottom has-transition hov-shadow-out z-1">' . $html_product . '</div>';
            }
            $pagination = str_replace('href', 'data-href', $products->appends($request->input())->links()->render());
            
            $filter = view('frontend.product_listing_filter', [
                'conditions' => $conditions,
                'request_all' => $request_all,
                'max_all_price' => $max_all_price,
                'min_all_price' => $min_all_price,
                'shops' => $shops,
                'vender_user_ids' => $vender_user_ids,
                'max_price' => $max_price,
                'min_price' => $min_price,
                'brands' => $brands,
                'rating' => $rating,
                'brand_ids' => $brand_ids,
                'products' => $products,
                'query' => $query,
                'category' => $category,
                'category_parent' => $category_parent,
                'category_parent_parent' => $category_parent_parent,
                'category_parents_ids' => $category_parents_ids,
                'categories' => $categories,
                'category_id' => $category_id,
                'brand_id' => $brand_id,
                'sort_by' => $sort_by,
                'min_price' => $min_price,
                'max_price' => $max_price,
                'attributes' => $attributes,
                'selected_attribute_values' => $selected_attribute_values,
                'colors' => $colors,
            ])->render();

            $list_categories =
                '<li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                            <a class="text-reset" href="' .
                route('home') .
                '">' .
                translate('Home') .
                '</a>
                            </li>
                            <li class="breadcrumb-item opacity-50 hov-opacity-100">
                                <a class="text-reset" href="' .
                route('search') .
                ' ">' .
                translate('All Categories') .
                '</a>
                            </li>';

            if ($category_parent_parent && $category_parent_parent->level != 0) {
                $list_categories .= '<li class="text-dark fw-600 breadcrumb-item">"' . $category_parent_parent->getTranslation('name') . '"</li>';
            }

            if ($category_parent && $category_parent->level != 0) {
                $list_categories .= '<li class="text-dark fw-600 breadcrumb-item">"' . $category_parent->getTranslation('name') . '"</li>';
            }

            if ($category) {
                $list_categories .= '<li class="text-dark fw-600 breadcrumb-item">"' . $category->getTranslation('name') . '"</li>';
                $title_category = $category->getTranslation('name');
            } else {
                $title_category = null;
            }
            $selected_values = [
                'numeric_attributes' => [],
                'boolean_attributes' => [],
                'list_attributes' => [],
                'color_attributes' => [],
            ];

            if (isset($request_all['attributes']) && is_array($request_all['attributes'])) {
                foreach ($request_all['attributes'] as $attribute_id => $attribute_value) {
                    $attribute = Attribute::find($attribute_id);
                    if ($attribute) {
                        if ($attribute->type_value == 'numeric') {
                            $selected_values['numeric_attributes'][$attribute_id] = [
                                'min' => $attribute_value['min'] ?? null,
                                'max' => $attribute_value['max'] ?? null,
                            ];
                        } elseif ($attribute->type_value == 'boolean') {
                            $selected_values['boolean_attributes'][$attribute_id] = in_array('yes', $attribute_value);
                        } elseif ($attribute->type_value == 'text' || $attribute->type_value == 'list') {
                            $selected_values['list_attributes'][$attribute_id] = $attribute_value;
                        } elseif ($attribute->type_value == 'color') {
                            $selected_values['color_attributes'][$attribute_id] = $attribute_value;
                        }
                    }
                }
            }
            $responseData = [
                'request_all' => request()->input(),
                'html' => $html,
                'pagination' => $pagination,
                'filter' => $filter,
                'list_categories' => $list_categories,
                'title_category' => $title_category,
                'selected_values' => $selected_values,
            ];
            Cache::put($cacheKey, $responseData, now()->addMinutes(10));

            return response()->json($responseData);
        }

        return view('frontend.product_listing', compact('conditions', 'max_all_price', 'min_all_price', 'request_all', 'shops', 'vender_user_ids', 'max_price', 'min_price', 'brands', 'rating', 'brand_ids', 'products', 'query', 'category', 'category_parent', 'category_parent_parent', 'category_parents_ids', 'categories', 'category_id', 'brand_id', 'sort_by', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors'));
    }

   
    /**
     * Generate a unique cache key based on request parameters.
     */
    private function generateCacheKey(Request $request)
    {
        $keyParts = [
            $request->fullUrl(),
            json_encode($request->all())
        ];
        return 'products:' . md5(implode('|', $keyParts));
    }

    public function listing(Request $request)
    {
        return $this->index($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->index($request, $category->id);
        }
        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        $brand = Brand::where('slug', $brand_slug)->first();
        if ($brand != null) {
            return $this->index($request, null, $brand->id);
        }
        abort(404);
    }

    //Suggestional Search
    public function ajax_search(Request $request)
    {
        $keywords = [];
        $query = $request->search;
        $products = Product::where('published', 1)
            ->where('tags', 'like', '%' . $query . '%')
            ->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $query) !== false) {
                    if (sizeof($keywords) > 3) {
                        break;
                    } else {
                        if (!in_array(strtolower($tag), $keywords)) {
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products_query = Product::query();

        $products_query = $products_query
            ->where('approved', 1)
            ->where('published', 1)
            ->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });
        $case1 = $query . '%';
        $case2 = '%' . $query . '%';

        //vulnerable code
        // $products_query->orderByRaw("CASE
        //         WHEN name LIKE '$case1' THEN 1
        //         WHEN name LIKE '$case2' THEN 2
        //         ELSE 3
        //         END");
        $products_query->orderByRaw(
            "CASE
        WHEN name LIKE ? THEN 1
        WHEN name LIKE ? THEN 2
        ELSE 3
        END",
            [$query . '%', '%' . $query . '%'],
        );

        $products = $products_query->limit(3)->get();

        $categories = Category::where('name', 'like', '%' . $query . '%')
            ->get()
            ->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())
            ->where('name', 'like', '%' . $query . '%')
            ->get()
            ->take(3);

        if (sizeof($keywords) > 0 || sizeof($categories) > 0 || sizeof($products) > 0 || sizeof($shops) > 0) {
            return view('frontend.' . get_setting('homepage_select') . '.partials.search_content', compact('products', 'categories', 'keywords', 'shops'));
        }
        return '0';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $search = Search::where('query', $request->keyword)->first();
        if ($search != null) {
            $search->count = $search->count + 1;
            $search->save();
        } else {
            $search = new Search();
            $search->query = $request->keyword;
            $search->save();
        }
    }

   
}
