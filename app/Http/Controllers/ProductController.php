<?php

namespace App\Http\Controllers;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Category;
use App\Models\ProductTax;
use App\Models\AttributeValue;
use App\Models\ProductCategory;
use App\Models\PricingConfiguration;
use App\Models\BusinessInformation;
use App\Models\ProductAttributeValues;
use App\Models\UploadProducts;
use App\Models\Brand;
use App\Models\Unity;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Attribute;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Color;
use App\Models\User;
use App\Notifications\ShopProductNotification;
use Carbon\Carbon;
use Combinations;
use CoreComponentRepository;
use Artisan;
use Cache;
use Str;
use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use App\Services\ProductUploadsService;
use App\Services\ProductPricingService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;
    protected $productUploadsService;
    protected $productPricingService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService,
        ProductUploadsService $productUploadsService,
        ProductPricingService $productPricingService,
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;
        $this->productUploadsService = $productUploadsService;
        $this->productPricingService = $productPricingService;

        // Staff Permission Check
        $this->middleware(['permission:add_new_product'])->only('create');
        $this->middleware(['permission:show_all_products'])->only('all_products');
        $this->middleware(['permission:show_in_house_products'])->only('admin_products');
        $this->middleware(['permission:show_seller_products'])->only('seller_products');
        $this->middleware(['permission:product_edit'])->only('admin_product_edit', 'seller_product_edit');
        $this->middleware(['permission:product_duplicate'])->only('duplicate');
        $this->middleware(['permission:product_delete'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_products(Request $request)
    {
        // CoreComponentRepository::instantiateShopRepository();

        // $type = 'In House';
        // $col_name = null;
        // $query = null;
        // $sort_search = null;

        // $products = Product::where('added_by', 'admin')->where('auction_product', 0)->where('wholesale_product', 0);

        // if ($request->type != null) {
        //     $var = explode(",", $request->type);
        //     $col_name = $var[0];
        //     $query = $var[1];
        //     $products = $products->orderBy($col_name, $query);
        //     $sort_type = $request->type;
        // }
        // if ($request->search != null) {
        //     $sort_search = $request->search;
        //     $products = $products
        //         ->where('name', 'like', '%' . $sort_search . '%')
        //         ->orWhereHas('stocks', function ($q) use ($sort_search) {
        //             $q->where('sku', 'like', '%' . $sort_search . '%');
        //         });
        // }

        // $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);

        $search = null;
        dd('ok');
        $products = Product::where(function ($query) {
            $query->where('is_draft',0)
                ->where('parent_id', 0)
                ->where('is_parent', 0);
        })->orWhere(function ($query) {
            $query->where('is_draft',0)
                ->where('is_parent', 1);
        })->orderBy('id','desc');

        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        dd($product->get());
        $products = $products->paginate(10);

        return view('backend.product.products.index', compact('products', 'search'));
        //return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'sort_search'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_products(Request $request, $product_type)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('added_by', 'seller')->where('auction_product', 0)->where('wholesale_product', 0);
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        $products = $product_type == 'physical' ? $products->where('digital', 0) : $products->where('digital', 1);
        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        $type = 'Seller';

        if ($product_type == 'digital') {
            return view('backend.product.digital_products.index', compact('products', 'sort_search', 'type'));
        }
        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    public function all_products(Request $request)
    {
        $search = null;
        $products = Product::where(function ($query) {
            $query->where('is_draft',0)
                ->where('parent_id', 0)
                ->where('is_parent', 0);
        })->orWhere(function ($query) {
            $query->where('is_draft',0)
                ->where('is_parent', 1);
        })->orderBy('id','desc');

        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(10);

        return view('backend.product.products.index', compact('products', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // CoreComponentRepository::initializeCache();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.create', compact('categories'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $product = $this->productService->store($request->except([
            'photosThumbnail', 'main_photos', 'product', 'documents', 'document_names', 'discount_percentage', '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type','from', 'to', 'unit_price', 'discount_type', 'discount_amount', 'discount_amount'
        ]));

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->attach($request->category_ids);

        //Upload documents, images and thumbnails
        if($request->document_names){
            $data['document_names'] = $request->document_names;
            $data['documents'] = $request->documents;
            $data['product'] = $product;
            $data['main_photos'] = $request->main_photos;
            $data['photosThumbnail'] = $request->photosThumbnail;
            $this->productUploadsService->store_uploads($data);
        }

        //Pricing configuration
        $this->productPricingService->store([
            "from" => $request->from,
            "to" => $request->to,
            "unit_price" => $request->unit_price,
            "date_range_pricing" => $request->date_range_pricing,
            "discount_type" => $request->discount_type,
            "discount_amount" => $request->discount_amount,
            "discount_percentage" => $request->discount_percentage,
            "product" => $product
        ]);

        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang', 'name', 'unit', 'description', 'product_id'
        ]));

        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('products.admin');
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
    public function admin_product_edit(Request $request, $id)
    {
        // CoreComponentRepository::initializeCache();

        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('admin/digitalproducts/' . $id . '/edit');
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('digitalproducts/' . $id . '/edit');
        }
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        // $categories = Category::all();
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        //Product
        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->sync($request->category_ids);

        //Product Stock
        $product->stocks()->delete();
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            $product->taxes()->delete();
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang', 'product_id'
            ]),
            $request->only([
                'name', 'unit', 'description'
            ])
        );

        flash(translate('Product has been updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        if($request->has('tab') && $request->tab != null){
            return Redirect::to(URL::previous() . "#" . $request->tab);
        }
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
        $product = Product::findOrFail($id);

        $product->product_translations()->delete();
        $product->categories()->detach();
        $product->stocks()->delete();
        $product->taxes()->delete();

        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();
            Wishlist::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->destroy($product_id);
            }
        }

        return 1;
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request, $id)
    {
        $product = Product::find($id);

        //Product
        $product_new = $this->productService->product_duplicate_store($product);

        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);

        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);

        flash(translate('Product has been duplicated successfully'))->success();
        if ($request->type == 'In House')
            return redirect()->route('products.admin');
        elseif ($request->type == 'Seller')
            return redirect()->route('products.seller');
        elseif ($request->type == 'All')
            return redirect()->route('products.all');
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        $product->save();
        Cache::forget('todays_deal_products');
        return 1;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    public function updateProductApproval(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->approved = $request->approved;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        $product_type   = $product->digital ==  0 ? 'physical' : 'digital';
        $status         = $request->approved == 1 ? 'approved' : 'rejected';
        $users          = User::findMany([User::where('user_type', 'admin')->first()->id, $product->user_id]);
        Notification::send($users, new ShopProductNotification($product_type, $product, $status));

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

    public function approve($id){
        $product = Product::findOrFail($id);
        
        $product = Product::find($id);
        $colors = Color::orderBy('name', 'asc')->get();
        $product_category = ProductCategory::where('product_id', $id)->first();
        $vat_user = BusinessInformation::where('user_id', $product->user_id)->first();
        $categories = Category::where('level', 1)
            ->with('childrenCategories')
            ->get();
        $attributes = [];
        $childrens = [];
        $childrens_ids = [];
        $variants_attributes = [];
        $general_attributes = [];
        $general_attributes_ids_attributes = [];
        $variants_attributes_ids_attributes = [];
        $historique_images = [];
        
        if($product != null){
            if($product->is_parent == 1){
                $childrens = Product::where('parent_id', $id)->get();
                $childrens_ids = Product::where('parent_id', $id)->pluck('id')->toArray();
                $variants_attributes = ProductAttributeValues::whereIn('id_products', $childrens_ids)->where('is_variant', 1)->get();
                $variants_attributes_ids_attributes = ProductAttributeValues::whereIn('id_products', $childrens_ids)->where('is_variant', 1)->pluck('id_attribute')->toArray();

                //Histroique images of variants
                $images_ids = UploadProducts::where('id_product', $childrens_ids)->where('type', 'images')->orWhere('type', 'thumbnails')->pluck('id')->toArray();
                $historique_images_revisions = DB::table('revisions')->whereIn('revisionable_id', $images_ids)->where('revisionable_type', 'App\Models\UploadProducts')->pluck('revisionable_id')->toArray();
                $historique_images = array_merge($historique_images, $historique_images_revisions);

                //Historique attributes of variants
                $variants_ids = ProductAttributeValues::whereIn('id_products', $childrens_ids)->where('is_variant', 1)->pluck('id')->toArray();
                $historique_children = DB::table('revisions')->whereIn('revisionable_id', $variants_ids)->where('revisionable_type', 'App\Models\ProductAttributeValues')->get();
                if(count($historique_children) > 0){
                    foreach($historique_children as $historique_child){
                        foreach($variants_attributes as $variant){
                            if($variant->id == $historique_child->revisionable_id){
                                $variant->key = $historique_child->key;
                                if($historique_child->key == 'id_units'){
                                    $unit = Unity::find($historique_child->old_value);
                                    if($unit != null){
                                        $variant->old_value = $unit->name;
                                    }else{
                                        $variant->old_value = '';
                                    }
                                }else{
                                    $variant->old_value = $historique_child->old_value;
                                }
                            }
                        }
                    }
                }
            }

            //Histroique General attributes
            $general_attributes = ProductAttributeValues::where('id_products', $id)->where('is_general', 1)->get();
            $general_attributes_ids_attributes = ProductAttributeValues::where('id_products', $id)->where('is_general', 1)->pluck('id_attribute')->toArray();
            $general_attributes_ids_values = ProductAttributeValues::where('id_products', $id)->where('is_general', 1)->pluck('id')->toArray();
            
            $historique_parent = DB::table('revisions')->whereIn('revisionable_id', $general_attributes_ids_values)->where('revisionable_type', 'App\Models\ProductAttributeValues')->get();
            $data_general_attributes = [];
            if(count($general_attributes) > 0){
                foreach ($general_attributes as $general_attribute){
                    $data_general_attributes[$general_attribute->id_attribute] = $general_attribute;
                    if(count($historique_parent) > 0){
                        foreach($historique_parent as $historique){
                            if($general_attribute->id == $historique->revisionable_id){
                                $general_attribute->key = $historique->key;
                                if($historique->key == 'id_units'){
                                    $unit = Unity::find($historique->old_value);
                                    if($unit != null){
                                        $general_attribute->old_value = $unit->name;
                                    }else{
                                        $general_attribute->old_value = '';
                                    }
                                }else{
                                    $general_attribute->old_value = $historique->old_value;
                                }
                            }
                        }
                    }
                }
            }

            //Get attribute of category selected and her path
            if($product_category != null){
                $categorie = Category::find($product_category->category_id);
                $current_categorie = $categorie;
    
                $parents = [];
                if($current_categorie->parent_id == 0){
                    array_push($parents, $current_categorie->id);
                }else{
                    array_push($parents, $current_categorie->id);
                    while($current_categorie->parent_id != 0) {
                        $parent = Category::where('id',$current_categorie->parent_id)->first();
                        array_push($parents, $parent->id);
                        $current_categorie = $parent;
                    }
                }
    
                if(count($parents) > 0){
                    $attributes_ids = DB::table('categories_has_attributes')->whereIn('category_id', $parents)->pluck('attribute_id')->toArray();
                    if(count($attributes_ids) > 0){
                        $attributes = Attribute::whereIn('id',$attributes_ids)->get();
                    }
                }
            }

            //Historique Product informations 
            $general_informations = [];
            $general_informations_data = DB::table('revisions')->where('revisionable_id', $id)->where('revisionable_type', 'App\Models\Product')->get();

            if(count($general_informations_data) > 0){
                foreach($general_informations_data as $general_information){
                    switch ($general_information->key) {
                        case 'brand_id':
                            $brand = Brand::find($general_information->old_value);
                            if($brand != null){
                                $general_informations[$general_information->key] = $brand->name;
                            }else{
                                $general_informations[$general_information->key] = '';
                            }
                            break;
                        case 'category_id':
                            $path = '';
                            $current_category = Category::find($general_information->old_value);
                            if($current_category != null){
                                while($current_category->parent_id != 0){
                                    if($path == ''){
                                        $path = $current_category->name;
                                    }else{
                                        $path = $current_category->name . ' > '  . $path;
                                    }
                                    $current_category = Category::find($current_category->parent_id);
                                }
                                if($current_category->parent_id == 0){
                                    if($path == ''){
                                        $path = $current_category->name;
                                    }else{
                                        $path = $current_category->name . ' > '  . $path;
                                    }
                                }
                            }
                            
                            $general_informations[$general_information->key] = $path;
                            break;
                        
                        default:
                            $general_informations[$general_information->key] = $general_information->old_value;
                            break;
                    }
                }
            }

            //Historique Documents
            $documents_ids = UploadProducts::where('id_product', $id)->where('type', 'documents')->pluck('id')->toArray();
            $historique_documents = DB::table('revisions')->whereIn('revisionable_id', $documents_ids)->where('revisionable_type', 'App\Models\UploadProducts')->get();
            $data_historique_documents = [];
            if(count($historique_documents) > 0){
                foreach($historique_documents as $historique_document){
                    $current_status = [];
                    if($historique_document->key == "add_document"){
                        $current_status['border_color'] = 'green';
                        $current_status['action'] = 'add';
                    }else{
                        $current_status['border_color'] = 'red';
                        $current_status['action'] = 'update';
                        $new_value = $array = json_decode($historique_document->new_value, true);
                        $old_value = $array = json_decode($historique_document->old_value, true);
                        if(array_key_exists('new_document_name', $new_value)){
                            $current_status['document_name'] = $old_value['old_document_name'];
                        }

                        if(array_key_exists('new_path', $new_value)){
                            $current_status['path'] = $old_value['old_path'];
                        }
                    }

                    
                    $data_historique_documents[$historique_document->revisionable_id] = $current_status;
                }
            }

            //Historique Image 
            $images_ids = UploadProducts::where('id_product', $id)->where('type', 'images')->orWhere('type', 'thumbnails')->pluck('id')->toArray();
            $historique_images_revisions = DB::table('revisions')->whereIn('revisionable_id', $images_ids)->where('revisionable_type', 'App\Models\UploadProducts')->pluck('revisionable_id')->toArray();
            $historique_images = array_merge($historique_images, $historique_images_revisions);

            return view('backend.product.products.approve', [
                'product' => $product,
                'vat_user' => $vat_user,
                'categories' => $categories,
                'product_category' => $product_category,
                'attributes' => $attributes,
                'childrens' => $childrens,
                'childrens_ids' => $childrens_ids,
                'variants_attributes' => $variants_attributes,
                'variants_attributes_ids_attributes' => $variants_attributes_ids_attributes,
                'general_attributes_ids_attributes' => $general_attributes_ids_attributes,
                'general_attributes' => $data_general_attributes,
                'colors' => $colors,
                'general_informations' => $general_informations,
                'data_historique_documents' => $data_historique_documents, 
                'historique_images' => $historique_images
            ]);                
        }else{
            abort(404);
        }

    }

    public function approve_action(Request $request){
        $product = Product::find($request->id_variant);
        if($product != null){
            $product->approved = $request->status;
            //check if status is Revision Required or Rejected to set the rejection reason
            if(($request->status == 2) || ($request->status == 3)){
                $product->rejection_reason = $request->reason;
            }else{
                $product->rejection_reason = null;
            }
            $product->save();

            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'failed'
            ]);
        }
    }
}
