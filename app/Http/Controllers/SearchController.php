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
use DB;

class SearchController extends Controller
{
    public function index(Request $request, $category_id = null, $brand_id = null)
    {
        $language = App::getLocale();
        $query = $request->keyword;
        $category = [];
        $categories = [];
        if($request->category_id){
            $category_id = $request->category_id;
        }
        if($request->category_id === 0 || $request->category_id === "0"){
            $category_id = null ;
        }

        $products = Product::IsApprovedPublished()->nonAuction();
        $attributes = Attribute::all();
        $id_products = [];

       
        //retrieve minimum and maximum price
        $baseQuery = clone $products;
        $priceQuery = clone $baseQuery;
        $priceQuery->join('pricing_configurations', 'products.id', '=', 'pricing_configurations.id_products');

        $max_all_price = $priceQuery->max('pricing_configurations.unit_price') ?? 1;
        $min_all_price = $priceQuery->min('pricing_configurations.unit_price') ?? 0;
        
        //Filter products By Category
        ['products' => $products,'attributes' => $attributes,'category_ids' => $category_ids,'category_parents_ids' => $category_parents_ids,'category' => $category] = $this->filterProductsAndAttributesByCategory($category_id, $query);
      
        //Filter by Brand
        $brandQuery = clone $baseQuery;
        $brands = $brandQuery->join('brands', 'brands.id', '=', 'products.brand_id')->select('brands.*')->distinct()->get();
        $brand_ids = $this->filterProductsByBrand($request, $brand_id, $products);


       //filter by vendors 
       $shopQuery = clone $baseQuery;
       $shops = $request->shops;
       $shops = $shopQuery->join('users', 'users.id', '=', 'products.user_id')->join('shops', 'shops.user_id', '=', 'users.id')->where('users.banned', '!=', 1)->where('shops.verification_status', '!=', 0)->select('shops.*')->distinct()->get();
       $vender_user_ids = $this->filterProductsByShop($request, $products);


       //filter by Rating 
       $rating = $request->rating;
       $products = $this->filterProductsByRating($rating,$products);
        
       //filter by creation date 
        $sort_by = $request->sort_by;
        $products = $this->applySorting($products, $sort_by);

        //filter by price range 
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $products = $this->applyPriceFilter($products, $min_price, $max_price);

         // filter product by name 
         if ($query) {
            $products->where('products.name', 'like', '%' . $query . '%');
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
        list($category_parent, $category_parent_parent) = $this->getCategoryHierarchy($category);

        //filter product by other attributes
        $products = $this->filterProductsByAttributes($products, $request_all);
        $selected_attribute_values = $this->getSelectedAttributeValues($attributes);
        $products = $products->paginate(6);


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
            
            
        
            return response()->json([
                'request_all' => $request_all,
                'html' => $html,
                'pagination' => $pagination,
                'filter' => $filter,
                'list_categories' => $list_categories,
                'title_category' => $title_category,
                'selected_values' => $selected_values, 

            ]);
        }

        return view('frontend.product_listing', compact('conditions', 'max_all_price', 'min_all_price', 'request_all', 'shops', 'vender_user_ids', 'max_price', 'min_price', 'brands', 'rating', 'brand_ids', 'products', 'query', 'category', 'category_parent', 'category_parent_parent', 'category_parents_ids', 'categories', 'category_id', 'brand_id', 'sort_by',  'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', ));
    }
    
    
    protected function applySorting($products, $sort_by){
        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->select('products.*')->orderBy('min_price_order', 'asc');
                break;
            case 'price-desc':
                $products->select('products.*')->orderBy('min_price_order', 'desc');
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }
    
        return $products;
    
    }

    protected function applyPriceFilter($products, $min_price, $max_price)
    {
        if ($min_price || $max_price) {
            $products->whereHas('pricingConfiguration', function ($query) use ($min_price, $max_price) {
                $query->whereRaw(
                    "
                    (pricing_configurations.unit_price - 
                        (pricing_configurations.unit_price * 
                            COALESCE((
                                SELECT discount_percentage 
                                FROM discounts 
                                WHERE discounts.product_id = pricing_configurations.id_products 
                                ORDER BY discounts.created_at DESC 
                                LIMIT 1
                            ), 0)
                        )
                    ) BETWEEN ? AND ?
                    ",
                    [$min_price ?? 0, $max_price ?? PHP_INT_MAX]
                );
            });
        }

        return $products;
    }
    protected function filterProductsAndAttributesByCategory($category_id, $query)
    {
        $products = Product::IsApprovedPublished()->nonAuction();
        $category = null ; 
            if ($category_id) {
                $category_ids = CategoryUtility::children_ids($category_id);
                $category_ids[] = $category_id;
                $products->whereIn('category_id', $category_ids);

                $category = Category::with('childrenCategories')->find($category_id);
                $category_parents_ids = $category->parents_ids()->toArray();
                $category_parents_ids[] = $category_id;
                
                $attribute_ids = DB::table('categories_has_attributes')->whereIn('category_id', $category_parents_ids)->pluck('attribute_id')->toArray();
                $attributes = Attribute::whereIn('id', $attribute_ids)->get();
            } else {
                $category_ids = [];
                $categories = Category::with('childrenCategories', 'coverImage')->where('level', 1)->orderBy('order_level', 'desc')->get();
                
                if ($query) {
                    $products->where('products.name', 'like', "%$query%");
                }
                
                $category_parents_ids = [];
                $attributes = get_category_attributes(1) ?? collect();
            }
            
            return compact('products', 'attributes', 'category_ids', 'category_parents_ids','category');
    }
    protected function filterProductsByBrand(Request $request, $brand_id, &$products)
    {
        $brand_ids = [];
        if ($brand_id != null) {
            $brand_ids[] = $brand_id;
        }
        if ($request->has('brand') && is_array($request->brand)) {
            $slug_brand_ids = Brand::whereIn('slug', $request->brand)
                ->pluck('id')
                ->toArray();

            $brand_ids = array_merge($brand_ids, $slug_brand_ids);
        }
        $brand_ids = array_unique(array_filter($brand_ids));
        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }

        return $brand_ids;
    }
    protected function filterProductsByShop(Request $request, &$products)
    {
        $vender_user_ids = [];
        if ($request->shops) {
            $vender_user_ids = Shop::whereIn('slug', $request->shops)
                ->pluck('user_id')
                ->toArray();
        }

        if (!empty($vender_user_ids)) {
            $products->whereIn('products.user_id', $vender_user_ids);
        }
        return $vender_user_ids;
    }
    protected function filterProductsByRating($rating, &$products)
    {
        if ($rating && $rating > 0) {
            $products->whereHas('reviews', function ($query) use ($rating) {
                $query
                    ->selectRaw('AVG(reviews.rating) as avg_rating')
                    ->groupBy('reviews.product_id')
                    ->havingRaw('AVG(reviews.rating) >= ?', [$rating]);
            });
        }
        return $products;
    }
  
    protected function filterProductsByAttributes($products, $request_all)
    {
        if (!isset($request_all['attributes']) || !is_array($request_all['attributes'])) {
            return $products; 
        }
    
        foreach ($request_all['attributes'] as $attribute_id => $attribute_value) {
            $attribute = Attribute::find($attribute_id);

            if (!$attribute) {
                continue; 
            }
    
            $units_id = $request_all['units_' . $attribute->id] ?? null;
            $unit = Unity::find($units_id);
           
    
            $attribute_type_value = $attribute->type_value;
            // Handle unit conversion if an old unit value exists
            if (isset($request_all['units_old_' . $attribute_id])) {
                $request_all['new_min_value_' . $attribute_id] = $attribute_value['min'] ;
                $request_all['new_max_value_' . $attribute_id] = $attribute_value['max'] ;
            }
    
            // Apply filtering to products
            $products->when($attribute, function ($query) use ($attribute_type_value, $attribute_id, $attribute_value, $units_id) {
                $query->whereHas('productAttributeValues', function ($q) use ($attribute_type_value, $attribute_id, $attribute_value, $units_id) {
                    $this->applyAttributeFilter($q, $attribute_type_value, $attribute_id, $attribute_value, $units_id);
                });
            });
        }
        
        return $products;
    
    
    }

    protected function applyAttributeFilter($query, $attribute_type_value, $attribute_id, $attribute_value, $units_id)
    {
        switch ($attribute_type_value) {
            case 'text':
                $query->where('id_attribute', $attribute_id)->whereIn('value', $attribute_value);
                break;

            case 'color':
                $color_ids = Color::whereHas('colorGroups', function ($q) use ($attribute_value) {
                    $q->whereIn('color_group_id', $attribute_value);
                })->pluck('id')->toArray();       
                $color_codes = Color::whereIn('id', $color_ids)->pluck('code')->toArray();
                $query->where('id_attribute', $attribute_id)->whereIn('value', $color_codes);
                break;

            case 'list':
                $query->where('id_attribute', $attribute_id)->whereIn('id_values', $attribute_value);
                break;

            case 'numeric':
                $this->applyNumericFilter($query, $attribute_id, $attribute_value);
                break;
                case 'boolean':
                    
                    if (is_array($attribute_value) && count($attribute_value) === 1 && $attribute_value[0] !== "yes") {
                        return;
                    }
                    $query->where('id_attribute', $attribute_id)->where('value', 'yes');
                    break;
        }
    }

    protected function applyNumericFilter($query, $attribute_id, $attribute_value)
    {
        $unit_ids = \DB::table('attributes_units')
        ->where('attribute_id', $attribute_id)
        ->pluck('unite_id');

        $default_unit = \App\Models\Unity::whereIn('id', $unit_ids)
                ->whereColumn('id', 'default_unit')
                ->first();
        $unit_active_model = $default_unit;
        $unit_active = $unit_active_model ? $unit_active_model->id : null;
        $conditions = [];

        $attribute = attribute::find($attribute_id);
        $min_attribute_value = $attribute->max_min_value($conditions, $unit_active)['min'];
        $max_attribute_value = $attribute->max_min_value($conditions, $unit_active)['max'];

        $minValue = $attribute_value['min'] ?? null;
        $maxValue = $attribute_value['max'] ?? null;
        if($minValue !=  $min_attribute_value || $maxValue != $max_attribute_value ){
            $query->where('id_attribute', $attribute_id)
            ->where(function ($q) use ($minValue, $maxValue) {
                if ($minValue !== null && $maxValue !== null) {
                    $q->whereRaw("CAST(value AS DECIMAL(10,2)) BETWEEN ? AND ?", [ $minValue, (float) $maxValue]);
                } elseif ($minValue !== null) {
                    $q->whereRaw("CAST(value AS DECIMAL(10,2)) >= ?", [(float) $minValue]);
                } elseif ($maxValue !== null) {
                    $q->whereRaw("CAST(value AS DECIMAL(10,2)) <= ?", [(float) $maxValue]);
                }
            });
        }
    }
    

    protected function getCategoryHierarchy($category)
    {   
        $category_parent = null;
        $category_parent_parent = null;

        if ($category && $category->parent_id) {
            $category_parent = Category::with('childrenCategories')->find($category->parent_id);
        }

        if ($category_parent && $category_parent->parent_id) {
            $category_parent_parent = Category::with('childrenCategories')->find($category_parent->parent_id);
        }

        return [$category_parent, $category_parent_parent];
    }
    protected function getSelectedAttributeValues($attributes)
    {
        $selected_attribute_values = [];

        foreach ($attributes as $attribute) {
            $selected_attribute_values[$attribute->id] = DB::table('attribute_values')
                ->where('attribute_id', $attribute->id)
                ->orderBy('value', 'asc')
                ->pluck('value')
                ->toArray();
        }

        return $selected_attribute_values;
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

    public function getProductsInPriceRange($minPrice, $maxPrice): array
    {
        // Retrieve all products with their pricing configurations
        $products = Product::with('pricingConfiguration')->get();

        $filteredProducts = [];

        foreach ($products as $product) {
            // Get the unit price from the pricing configuration
            $unitPrice = $product->pricingConfiguration->unit_price;

            // Initialize the final price with the unit price
            $finalPrice = $unitPrice;

            try {
                // Check if there is a discount for the product
                $discountInfo = Discount::getDiscountPercentage($product->id);

                // Calculate the final price if a discount is applicable
                $discountPercentage = $discountInfo['discount_percentage'];
                $maxDiscountAmount = $discountInfo['max_discount_amount'];

                $discountAmount = min(($unitPrice * $discountPercentage) / 100, $maxDiscountAmount);
                $finalPrice = $unitPrice - $discountAmount;
            } catch (\Exception $e) {
                // No discount applicable, use the unit price as the final price
                $finalPrice = $unitPrice;
            }

            // Check if the final price is within the specified range
            if ($finalPrice >= $minPrice && $finalPrice <= $maxPrice) {
                $filteredProducts[] = [
                    'product' => $product,
                    'final_price' => $finalPrice,
                ];
            }
        }

        return $filteredProducts;
    }
}
