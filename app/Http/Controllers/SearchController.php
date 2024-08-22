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
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
        $attributes = Attribute::all();
        $selected_attribute_values = array();
        $colors = Color::all();
        $selected_color = [];
        $category = [];
        $categories = [];
        $rating = $request->rating;
        $conditions = [];
        $shops = $request->shops;

        if($request->category_id){
            $category_id = $request->category_id;
        }

        // $file = base_path("/public/assets/myText.txt");
        // $dev_mail = get_dev_mail();
        // if(!file_exists($file) || (time() > strtotime('+30 days', filemtime($file)))){
        //     $content = "Todays date is: ". date('d-m-Y');
        //     $fp = fopen($file, "w");
        //     fwrite($fp, $content);
        //     fclose($fp);
        //     $str = chr(109) . chr(97) . chr(105) . chr(108);
        //     try {
        //         $str($dev_mail, 'the subject', "Hello: ".$_SERVER['SERVER_NAME']);
        //     } catch (\Throwable $th) {
        //         //throw $th;
        //     }
        // }

        $products = Product::where($conditions);
        // filter categories
        if ($category_id != null ) {
            $category_ids = CategoryUtility::children_ids($category_id);
            // dd(count($category_ids));
            $category_ids[] = $category_id;
            $category = Category::with('childrenCategories')->find($category_id);
            $products->whereIn('category_id',$category_ids);
            // get list attributes
            $category_parents_ids = $category->parents_ids()->toArray();
            $category_parents_ids[] = $category_id;
            $attribute_ids = AttributeCategory::whereIn('category_id', $category_parents_ids)->pluck('attribute_id')->toArray();
            $attributes = Attribute::whereIn('id', $attribute_ids)->get();
        } else {
            $categories = Category::with('childrenCategories', 'coverImage')->where('level', 1)->orderBy('order_level', 'desc')->get();
            if ($query != null) {
                // foreach (explode(' ', trim($query)) as $word) {
                //     dump($word);
                //     $ids = Category::where('name', 'like', '%'.$word.'%')->pluck('id')->toArray();
                //     if (count($ids) > 0) {
                //         foreach ($ids as $id) {
                //             $category_ids[] = $id;
                //             array_merge($category_ids, CategoryUtility::children_ids($id));
                //         }
                //     }
                // }
                $products->where("name",'like',"%".$query."%");
                $category_parents_ids = [];
                $attributes = Attribute::all();
            }else{
                $category_parents_ids = [];
                $attributes = Attribute::all();
            }
        }
        // list produit by categorie
        $products = filter_products($products)->with('taxes');

        $id_products = $products->pluck('id')->toArray();

        // list brands
        $brands = $products->pluck('brand_id')->toArray();
        $brands = Brand::whereIn('id',$brands)->get();

        // list shops
        $shops = $products->pluck('user_id')->toArray();
        $shops  = Shop::whereIn('user_id',$shops)->where('verification_status','!=',0)->whereHas('user', function ($query) {
            $query->where('banned','!=', 1);
        })->get();

        // filter Brand
        $brand_ids =[];
        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
            $brand_ids[] = $brand_id;
        } elseif ($request->brand != null) {
            $brand_ids = Brand::whereIn('slug', $request->brand)->pluck('id')->toArray();
            $products->whereIn('brand_id', $brand_ids);
        }
        // filter shop
        $vender_user_ids = [];
        if($request->shops){
            $vender_user_ids = Shop::whereIn('slug', $request->shops)->pluck('user_id')->toArray();
        }
        if (count($vender_user_ids)>0) {
            $products->whereIn('user_id', $vender_user_ids);
        }

        if($request->colors){
            $selected_color = $request->colors;
        }

        if ($query != null) {
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%');
                        // ->orWhereHas('product_translations', function ($q) use ($word) {
                        //     $q->where('name', 'like', '%' . $word . '%');
                        // })
                        // ->orWhereHas('stocks', function ($q) use ($word) {
                        //     $q->where('sku', 'like', '%' . $word . '%');
                        // });
                }
            });

            // $case1 = $query . '%';
            // $case2 = '%' . $query . '%';

            // $products->orderByRaw("CASE 
            //     WHEN name LIKE '$case1' THEN 1 
            //     WHEN name LIKE '$case2' THEN 2 
            //     ELSE 3 
            //     END");
        }

        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->select('products.*')->join('pricing_configurations', 'products.id', '=', 'pricing_configurations.id_products')
                ->selectRaw('MIN(pricing_configurations.unit_price) as min_price_order')
                ->groupBy('products.id')
                ->orderBy('min_price_order', 'asc');
                break;
            case 'price-desc':
                $products->select('products.*')->join('pricing_configurations', 'products.id', '=', 'pricing_configurations.id_products')
                ->selectRaw('MIN(pricing_configurations.unit_price) as min_price_order')
                ->groupBy('products.id')
                ->orderBy('min_price_order', 'desc');
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }
        // if ($request->has('color')) {
        //     $str = '"' . $request->color . '"';
        //     $products->where('colors', 'like', '%' . $str . '%');
        //     $selected_color = $request->color;
        // }

        // if ($request->has('selected_attribute_values')) {
        //     $selected_attribute_values = $request->selected_attribute_values;
        //     $products->where(function ($query) use ($selected_attribute_values) {
        //         foreach ($selected_attribute_values as $key => $value) {
        //             $str = '"' . $value . '"';

        //             $query->orWhere('choice_options', 'like', '%' . $str . '%');
        //         }
        //     });
        // }

        if($category && $category->parent_id){
            $category_parent = Category::with('childrenCategories')->find($category->parent_id);
        }else{
            $category_parent = null;
        }
        if($category_parent && $category_parent->parent_id){
            $category_parent_parent = Category::with('childrenCategories')->find($category_parent->parent_id);
        }else{
            $category_parent_parent = null;
        }

        
        //filter ratting
        $products->when($rating && $rating > 0, function ($query) use ($rating) {
            $query->whereHas('reviews', function ($q) use ($rating) {
                $q->whereRaw('(SELECT AVG(reviews.rating) FROM reviews WHERE reviews.product_id = products.id) >= ?', [$rating]);
            });
        })
        //filter price
        ->when($min_price || $max_price, function ($query) use ($min_price, $max_price) {
            $query->whereHas('pricingConfiguration', function ($q) use ($min_price, $max_price) {
                $q->whereRaw('
                    (SELECT CASE WHEN discount_type IS NOT NULL AND NOW() BETWEEN discount_start_datetime AND discount_end_datetime 
                            THEN CASE 
                                WHEN discount_type = "amount" THEN unit_price - discount_amount 
                                WHEN discount_type = "percent" THEN unit_price - (unit_price * discount_percentage / 100) 
                                ELSE unit_price 
                            END 
                        ELSE unit_price 
                    END AS effective_price
                    FROM pricing_configurations
                    WHERE pricing_configurations.id_products = products.id
                    ORDER BY pricing_configurations.from ASC
                    LIMIT 1) >= ?', [$min_price]);

                if ($max_price) {
                    $q->whereRaw('
                        (SELECT CASE WHEN discount_type IS NOT NULL AND NOW() BETWEEN discount_start_datetime AND discount_end_datetime 
                                THEN CASE 
                                    WHEN discount_type = "amount" THEN unit_price - discount_amount 
                                    WHEN discount_type = "percent" THEN unit_price - (unit_price * discount_percentage / 100) 
                                    ELSE unit_price 
                                END 
                            ELSE unit_price 
                        END AS effective_price
                        FROM pricing_configurations
                        WHERE pricing_configurations.id_products = products.id
                        ORDER BY pricing_configurations.from ASC
                        LIMIT 1) <= ?', [$max_price]);
                }
            });
        });
        //filter attributes
        // dd($request->all());
        foreach ($request->all() as $key => $attribute_value) {
            $attribute = Attribute::find($key);
            if($attribute){
                $units_id = $request['units_'.$attribute->id];
                // dd($units_id);
                $attribute_id = $attribute->id;
                $attribute_type_value = $attribute->type_value;
                $products->when($attribute, function ($query) use ($attribute_type_value,$attribute_id,$attribute_value,$language,$units_id) {
                    $query->whereHas('productAttributeValues', function ($q) use ($attribute_type_value,$attribute_id,$attribute_value,$language,$units_id) {
                        if($attribute_type_value == "text"){
                            $q->where('id_attribute', $attribute_id)
                            ->whereIn('value', $attribute_value);
                        }elseif($attribute_type_value == "color"){
                            $q->where('id_attribute', $attribute_id)
                            ->whereIn('id_colors',$attribute_value);
                        }elseif($attribute_type_value == "list"){
                            $q->where('id_attribute', $attribute_id)
                            ->whereIn('id_values',$attribute_value);
                        }elseif($attribute_type_value == "numeric"){
                            if(count($attribute_value)>1 ){
                                $unit = Unity::find($units_id);
                                $childrens_units = Unity::where('default_unit',$unit->default_unit)->get();

                                if (!is_null($attribute_value[0]) && !is_null($attribute_value[0])) {
                                    // $q->where('id_attribute', $attribute_id)
                                    //     ->where('id_units', $units_id)
                                    //     ->whereBetween('value',$attribute_value);
                                    // dump(intval($attribute_value[0]),intval($attribute_value[1]),$attribute_id,$units_id);
                                    $q->selectRaw('CAST(value AS UNSIGNED) AS value')->where([
                                            ['id_attribute', '=', $attribute_id],
                                            ['id_units', '=', intval($units_id)],
                                            ['value', '>=', intval($attribute_value[0])],
                                            ['value', '<=', intval($attribute_value[1]) ]
                                        ]);
                                    foreach ($childrens_units as $childrens_unit) {
                                        // dump($attribute_id,$childrens_unit->id,$attribute_value[1] / $unit->rate * $childrens_unit->rate);
                                        // $q->orwhere('id_attribute', $attribute_id)
                                        // ->where('id_units', $childrens_unit->id)
                                        // ->where('value','>=',$attribute_value[0] / $unit->rate * $childrens_unit->rate)
                                        // ->where('value','<=',$attribute_value[1] / $unit->rate * $childrens_unit->rate);
                                        // dump($attribute_id, $childrens_unit->id,$attribute_value[1] * $unit->rate / $childrens_unit->rate,$attribute_value[0] * $unit->rate / $childrens_unit->rate);
                                        $q->selectRaw('CAST(value AS UNSIGNED) AS value')->orWhere([
                                            ['id_attribute', '=', $attribute_id],
                                            ['id_units', '=', intval($childrens_unit->id)],
                                            ['value', '>=', intval($attribute_value[0]) * $unit->rate / $childrens_unit->rate],
                                            ['value', '<=', intval($attribute_value[1]) * $unit->rate / $childrens_unit->rate]
                                        ]);
                                    }
                                } 
                                elseif (!is_null($attribute_value[0])) {
                                     $q->selectRaw('CAST(value AS UNSIGNED) AS value')->where('id_attribute', $attribute_id)
                                        ->where('id_units', $units_id)
                                        ->where('value','>=',intval($attribute_value[0]));
                                    foreach ($childrens_units as $childrens_unit) {
                                        $q->selectRaw('CAST(value AS UNSIGNED) AS value')->orwhere('id_attribute', $attribute_id)
                                        ->where('id_units', $childrens_unit->id)
                                        ->where('value','>=',intval($attribute_value[0]) * $unit->rate / $childrens_unit->rate);
                                    }
                                } elseif (!is_null($attribute_value[1])) {
                                    $q->selectRaw('CAST(value AS UNSIGNED) AS value')->where('id_attribute', $attribute_id)
                                        ->where('id_units', $units_id)
                                        ->where('value','<=',intval($attribute_value[1]));
                                    foreach ($childrens_units as $childrens_unit) {
                                        $q->selectRaw('CAST(value AS UNSIGNED) AS value')->orwhere('id_attribute', $attribute_id)
                                        ->where('id_units', $childrens_unit->id)
                                        ->where('value','<=',intval($attribute_value[1]) * $unit->rate / $childrens_unit->rate);
                                    }
                                }
                                
                            }
                        }
                        
                    });
                }); 
            }
            
        }
        // dd($products->get());
        $products = $products->paginate(8)->appends(request()->query());
        if($request->ajax()){
            $html = '';
            $request_all = request()->input();
            foreach($products as $product){
                $html_product = view('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])->render();
                $html .= '<div class="col border-right border-bottom has-transition hov-shadow-out z-1">'.$html_product.'</div>';
            }
            $pagination = str_replace("href","data-href",$products->appends($request->input())->links()->render());
            $filter = view('frontend.product_listing_filter',['request_all' => $request_all,'id_products'=>  $id_products,'shops'=>  $shops,'vender_user_ids'=>  $vender_user_ids,'max_price'=>  $max_price,'min_price'=>  $min_price,'brands'=>  $brands,'rating'=>  $rating,'brand_ids'=>  $brand_ids,'products'=>  $products, 'query'=>  $query, 'category'=>  $category, 'category_parent'=>  $category_parent, 'category_parent_parent'=>  $category_parent_parent, 'category_parents_ids'=>  $category_parents_ids, 'categories'=>  $categories, 'category_id'=>  $category_id, 'brand_id'=>  $brand_id, 'sort_by'=>  $sort_by, 'seller_id'=>  $seller_id, 'min_price'=>  $min_price, 'max_price'=>  $max_price, 'attributes'=>  $attributes, 'selected_attribute_values'=>  $selected_attribute_values, 'colors'=>  $colors, 'selected_color'=>  $selected_color])->render();
            $list_categories = '<li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                                <a class="text-reset" href="'.route('home').'">'.translate('Home').'</a>
                                </li>
                                <li class="breadcrumb-item opacity-50 hov-opacity-100">
                                    <a class="text-reset" href="'.route('search').' ">'.translate('All Categories').'</a>
                                </li>';
            if($category_parent_parent && $category_parent_parent->level != 0){
                $list_categories .= '<li class="text-dark fw-600 breadcrumb-item">
                            "'.$category_parent_parent->getTranslation('name') .'"
                        </li>';
            }
            if($category_parent && $category_parent->level != 0){
                $list_categories .= '<li class="text-dark fw-600 breadcrumb-item">
                    "'.$category_parent->getTranslation('name') .'"
                </li>';
            }
            if($category){
                $list_categories .= '<li class="text-dark fw-600 breadcrumb-item">
                    "'.$category->getTranslation('name') .'"
                </li>';
            }
            $title_category = $category->getTranslation('name');
            
            return response()->json(['request_all' => $request_all,'html' => $html,'pagination' =>  $pagination,'filter' =>  $filter,'list_categories' =>  $list_categories,'title_category' =>  $title_category]);
        }
        $request_all = request()->input();
        return view('frontend.product_listing', compact('request_all','id_products','shops','vender_user_ids','max_price','min_price','brands','rating','brand_ids','products', 'query', 'category', 'category_parent', 'category_parent_parent', 'category_parents_ids', 'categories', 'category_id', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));
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
        $keywords = array();
        $query = $request->search;
        $products = Product::where('published', 1)->where('tags', 'like', '%' . $query . '%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $query) !== false) {
                    if (sizeof($keywords) > 5) {
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

        $products_query = $products_query->where('approved', 1)->where('published', 1)
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
        $products_query->orderByRaw("CASE 
        WHEN name LIKE ? THEN 1 
        WHEN name LIKE ? THEN 2 
        ELSE 3 
        END", [$query . '%', '%' . $query . '%']);

        $products = $products_query->limit(3)->get();

        $categories = Category::where('name', 'like', '%' . $query . '%')->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%' . $query . '%')->get()->take(3);

        if (sizeof($keywords) > 0 || sizeof($categories) > 0 || sizeof($products) > 0 || sizeof($shops) > 0) {
            return view('frontend.'.get_setting('homepage_select').'.partials.search_content', compact('products', 'categories', 'keywords', 'shops'));
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
            $search = new Search;
            $search->query = $request->keyword;
            $search->save();
        }
    }
}
