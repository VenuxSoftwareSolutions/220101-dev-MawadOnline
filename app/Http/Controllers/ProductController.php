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
use App\Mail\ApprovalProductMail;
use App\Mail\SellerStaffMail;
use App\Models\Brand;
use App\Models\Unity;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Attribute;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Revision;
use App\Models\Color;
use App\Models\User;
use App\Notifications\ShopProductNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
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
use DateTime;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Models\ProductCatalog;
use App\Models\UploadProductCatalog;
use Illuminate\Support\Facades\File;
use App\Models\ProductAttributeValueCatalog;

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
            $query->where(function ($query) {
                $query->where('is_draft',0)
                    ->where('parent_id', 0)
                    ->where('is_parent', 0);
            })
            ->orWhere(function ($query) {
                $query->where('is_draft',0)
                    ->where('is_parent', 1);
            });
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
                $images_ids = UploadProducts::whereIn('id_product', $childrens_ids)->where('type', 'images')->orWhere('type', 'thumbnails')->pluck('id')->toArray();
                $historique_images_revisions = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $images_ids)->where('revisionable_type', 'App\Models\UploadProducts')->pluck('revisionable_id')->toArray();
                $historique_images = array_merge($historique_images, $historique_images_revisions);

                //Historique attributes of variants
                $variants_ids = ProductAttributeValues::whereIn('id_products', $childrens_ids)->where('is_variant', 1)->pluck('id')->toArray();
                $historique_children = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $variants_ids)->where('revisionable_type', 'App\Models\ProductAttributeValues')->get();

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

            $historique_parent = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $general_attributes_ids_values)->where('revisionable_type', 'App\Models\ProductAttributeValues')->get();
            $data_general_attributes = [];
            if(count($general_attributes) > 0){
                foreach ($general_attributes as $general_attribute){
                    $data_general_attributes[$general_attribute->id_attribute] = $general_attribute;
                    if(count($historique_parent) > 0){
                        foreach($historique_parent as $historique){
                            if($general_attribute->id == $historique->revisionable_id){
                                if($general_attribute->id == $historique->revisionable_id){
                                    $general_attribute->key = $historique->key;
                                    if($historique->key == "add_attribute"){
                                        $general_attribute->added = true;
                                    }else{
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
            $general_informations_data = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_id', $id)->where('revisionable_type', 'App\Models\Product')->get();

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
            $historique_documents = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $documents_ids)->where('revisionable_type', 'App\Models\UploadProducts')->get();
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
                        $new_value = json_decode($historique_document->new_value, true);
                        $old_value = json_decode($historique_document->old_value, true);
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
            $historique_images_revisions = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $images_ids)->where('revisionable_type', 'App\Models\UploadProducts')->pluck('revisionable_id')->toArray();
            $historique_images = array_merge($historique_images, $historique_images_revisions);

            $chargeable_weight = 0;
            if($product->activate_third_party == 1){
                $volumetric_weight = ($product->length * $product->height * $product->width) / 5000;
                if($volumetric_weight > $product->weight){
                    $chargeable_weight = $volumetric_weight;
                }else{
                    $chargeable_weight = $product->weight;
                }

                if($product->unit_weight == "pounds"){
                    $chargeable_weight *= 2.2;
                }
            }

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
                'historique_images' => $historique_images,
                'chargeable_weight' => $chargeable_weight
            ]);
        }else{
            abort(404);
        }

    }

    public function copy_changes_product_in_catalog($id){
        $existingProduct = Product::find($id);

        $product_catalog_exist = ProductCatalog::where('product_id', $id)->first();
        if($product_catalog_exist != null){
            $childrens_catalog = ProductCatalog::where('parent_id', $product_catalog_exist->id)->pluck('id')->toArray();
            if(count($childrens_catalog) > 0){
                ProductAttributeValueCatalog::whereIn('catalog_id', $childrens_catalog)->delete();
                UploadProductCatalog::whereIn('catalog_id', $childrens_catalog)->delete();
                ProductCatalog::where('parent_id', $product_catalog_exist->id)->delete();
                foreach($childrens_catalog as $children_catalog_id){
                    $path_children = public_path('/upload_products/Product-'.$children_catalog_id);
                    File::deleteDirectory($path_children);
                }
            }

            $path_parent = public_path('/upload_products/Product-'.$product_catalog_exist->id);
            File::deleteDirectory($path_parent);

            ProductAttributeValueCatalog::where('catalog_id', $product_catalog_exist->id)->delete();
            UploadProductCatalog::where('catalog_id', $product_catalog_exist->id)->delete();
            ProductCatalog::where('id', $product_catalog_exist->id)->delete();


        }


        if (!$existingProduct) {
            // Handle the case where the product with the specific ID doesn't exist
            return redirect()->back()->with('error', 'Product not found');
        }

        $data = $existingProduct->attributesToArray();
        // Make necessary updates to the attributes (if any)
        unset($data['id']);
        $data['product_id'] = $id;
        $newProduct = ProductCatalog::insertGetId($data);

        $path = public_path('/upload_products/Product-'.$id);
        $destinationFolder = public_path('/upload_products_catalog/Product-'.$newProduct);
        if (!File::isDirectory($destinationFolder)) {
            File::makeDirectory($destinationFolder);
        }

        if (File::isDirectory($path)) {
            File::copyDirectory($path, $destinationFolder);
        }

        $uploads = UploadProducts::where('id_product', $id)->get();
        $new_records = [];
        if(count($uploads) > 0){
            foreach($uploads as $file){
                $current_file = [];
                $newPath = str_replace("/upload_products/Product-{$id}", "/upload_products_catalog/Product-{$newProduct}", $file->path);

                $current_file['catalog_id'] = $newProduct;
                $current_file['path'] = $newPath;
                $current_file['extension'] = $file->extension;
                $current_file['document_name'] = $file->document_name;
                $current_file['type'] = $file->type;

                array_push($new_records, $current_file);
            }

            if(count($new_records) > 0){
                UploadProductCatalog::insert($new_records);
            }
        }

        $attributes = ProductAttributeValues::where('id_products', $id)->get();

        $new_records_attributes = [];

        if(count($attributes) > 0){
            foreach($attributes as $attribute){
                $current_attribute = [];
                $current_attribute['catalog_id'] = $newProduct;
                $current_attribute['id_attribute'] = $attribute->id_attribute;
                $current_attribute['id_units'] = $attribute->id_units;
                $current_attribute['id_values'] = $attribute->id_values;
                $current_attribute['id_colors'] = $attribute->id_colors;
                $current_attribute['value'] = $attribute->value;
                $current_attribute['is_variant'] = $attribute->is_variant;
                $current_attribute['is_general'] = $attribute->is_general;

                array_push($new_records_attributes, $current_attribute);
            }

            if(count($new_records_attributes) > 0){
                ProductAttributeValueCatalog::insert($new_records_attributes);
            }
        }

        if(count($existingProduct->getChildrenProducts()) > 0){
            foreach($existingProduct->getChildrenProducts() as $children){
                $data = $children->attributesToArray();
                // Make necessary updates to the attributes (if any)
                unset($data['id']);
                $data['parent_id'] = $newProduct;
                $data['product_id'] = $children->id;
                $newProductChildren = ProductCatalog::insertGetId($data);

                $path = public_path('/upload_products/Product-'.$children->id);
                $destinationFolder = public_path('/upload_products_catalog/Product-'.$newProductChildren);
                if (!File::isDirectory($destinationFolder)) {
                    File::makeDirectory($destinationFolder);
                }

                if (File::isDirectory($path)) {
                    File::copyDirectory($path, $destinationFolder);
                }

                $uploads = UploadProducts::where('id_product', $children->id)->get();
                $new_records = [];
                if(count($uploads) > 0){
                    foreach($uploads as $file){
                        $current_file = [];
                        $newPath = str_replace("/upload_products/Product-{$children->id}", "/upload_products_catalog/Product-{$newProductChildren}", $file->path);

                        $current_file['catalog_id'] = $newProductChildren;
                        $current_file['path'] = $newPath;
                        $current_file['extension'] = $file->extension;
                        $current_file['document_name'] = $file->document_name;
                        $current_file['type'] = $file->type;

                        array_push($new_records, $current_file);
                    }

                    if(count($new_records) > 0){
                        UploadProductCatalog::insert($new_records);
                    }
                }

                $attributes = ProductAttributeValues::where('id_products', $children->id)->get();
                $new_records_attributes = [];

                if(count($attributes) > 0){
                    foreach($attributes as $attribute){
                        $current_attribute = [];
                        $current_attribute['catalog_id'] = $newProductChildren;
                        $current_attribute['id_attribute'] = $attribute->id_attribute;
                        $current_attribute['id_units'] = $attribute->id_units;
                        $current_attribute['id_values'] = $attribute->id_values;
                        $current_attribute['id_colors'] = $attribute->id_colors;
                        $current_attribute['value'] = $attribute->value;
                        $current_attribute['is_variant'] = $attribute->is_variant;
                        $current_attribute['is_general'] = $attribute->is_general;

                        array_push($new_records_attributes, $current_attribute);
                    }

                    if(count($new_records_attributes) > 0){
                        ProductAttributeValueCatalog::insert($new_records_attributes);
                    }
                }
            }
        }
    }

    public function approve_action(Request $request){

        $product = Product::find($request->id_variant);
        if($product != null){
            if(count($product->getChildrenProducts())){
                foreach ($product->getChildrenProducts() as $children){
                    //Attribute section
                    $attributes_id = DB::table('product_attribute_values')->where('id_products', $children->id)->pluck('id')->toArray();
                    if(($request->status != 1) && ($request->status != 4)){
                        $historique_attributes = Revision::where('revisionable_type', 'App\Models\ProductAttributeValues')->whereIn('revisionable_id', $attributes_id)->get();
                        if(count($historique_attributes) > 0){
                            foreach($historique_attributes as $attribute_history){
                                $update = [];
                                switch ($attribute_history->key) {
                                    case 'value':
                                        $update['value'] = $attribute_history->old_value;
                                        break;
                                    case 'id_units':
                                        $update['id_units'] = $attribute_history->old_value;
                                        break;
                                    case 'id_values':
                                        $update['id_values'] = $attribute_history->old_value;
                                        break;
                                    case 'id_colors':
                                        $update['id_colors'] = $attribute_history->old_value;
                                        break;
                                }

                                $attribute_value = DB::table('product_attribute_values')->where('id',$attribute_history->revisionable_id)->update($update);
                            }
                        }
                    }

                    $historique_attributes = Revision::where('revisionable_type', 'App\Models\ProductAttributeValues')->whereIn('revisionable_id', $attributes_id)->delete();

                    //Product section

                    if(($request->status != 1) && ($request->status != 4)){
                        $historique_product_informations = Revision::where('revisionable_type', 'App\Models\Product')->where('revisionable_id', $children->id)->get();
                        if(count($historique_product_informations) > 0){
                            $data = [];
                            foreach($historique_product_informations as $product_history){
                                $data[$product_history->key] = $product_history->old_value;
                            }
                            $children_product = DB::table('products')->where('id', $children->id)->update($data);
                        }
                    }

                    $historique_product_informations = Revision::where('revisionable_type', 'App\Models\Product')->where('revisionable_id', $children->id)->delete();

                    //Images section & thumbnails
                    $images_ids = DB::table('upload_products')->where('id_product', $children->id)->where('type', 'images')->orWhere('type', 'thumbnails')->pluck('id')->toArray();
                    if(($request->status != 1) && ($request->status != 4)){
                        $historique_images = Revision::whereIn('revisionable_id', $images_ids)->where('revisionable_type', 'App\Models\UploadProducts')->get();
                        if(count($historique_images) > 0){
                            foreach($historique_images as $image){
                                $uploaded = DB::table('upload_products')->where('id', $image->new_value)->first();

                                if(file_exists(public_path($uploaded->path))){
                                    unlink(public_path($uploaded->path));
                                }

                                $uploaded = DB::table('upload_products')->where('id', $image->new_value)->delete();
                            }
                        }
                    }

                    $historique_images = Revision::whereIn('revisionable_id', $images_ids)->where('revisionable_type', 'App\Models\UploadProducts')->delete();
                    $children->approved = $request->status;
                    if(($request->status == 1) || ($request->status == 3)){
                        $children->last_version = 0;
                    }
                    $children->save();
                }
            }

            $attributes_id = DB::table('product_attribute_values')->where('id_products', $product->id)->pluck('id')->toArray();
            if(($request->status != 1) && ($request->status != 4)){
                $historique_attributes = Revision::where('revisionable_type', 'App\Models\ProductAttributeValues')->whereIn('revisionable_id', $attributes_id)->get();
                if(count($historique_attributes) > 0){
                    foreach($historique_attributes as $attribute_history){
                        $update = [];
                        switch ($attribute_history->key) {
                            case 'value':
                                $update['value'] = $attribute_history->old_value;
                                break;
                            case 'id_units':
                                $update['id_units'] = $attribute_history->old_value;
                                break;
                            case 'id_values':
                                $update['id_values'] = $attribute_history->old_value;
                                break;
                            case 'id_colors':
                                $update['id_colors'] = $attribute_history->old_value;
                                break;
                        }

                        $attribute_value = DB::table('product_attribute_values')->where('id',$attribute_history->revisionable_id)->update($update);
                    }
                }
            }
            $historique_attributes = Revision::where('revisionable_type', 'App\Models\ProductAttributeValues')->whereIn('revisionable_id', $attributes_id)->delete();

            //Product section
            if(($request->status != 1) && ($request->status != 4)){
                $historique_product_informations = Revision::where('revisionable_type', 'App\Models\Product')->where('revisionable_id', $product->id)->get();
                if(count($historique_product_informations) > 0){
                    $data = [];
                    foreach($historique_product_informations as $product_history){
                        $data[$product_history->key] = $product_history->old_value;
                    }
                    $producted_update = DB::table('products')->where('id', $product->id)->update($data);
                }
            }

            $historique_product_informations = Revision::where('revisionable_type', 'App\Models\Product')->where('revisionable_id', $product->id)->delete();

            //Images section & thumbnails
            $images_ids = DB::table('upload_products')->where('id_product', $product->id)->where('type', 'images')->orWhere('type', 'thumbnails')->pluck('id')->toArray();
            if(($request->status != 1) && ($request->status != 4)){
                $historique_images = Revision::whereIn('revisionable_id', $images_ids)->where('revisionable_type', 'App\Models\UploadProducts')->get();
                if(count($historique_images) > 0){
                    foreach($historique_images as $image){
                        $uploaded = DB::table('upload_products')->where('id', $image->new_value)->first();

                        if(file_exists(public_path($uploaded->path))){
                            unlink(public_path($uploaded->path));
                        }

                        $uploaded = DB::table('upload_products')->where('id', $image->new_value)->delete();
                    }
                }
            }

            $historique_images = Revision::whereIn('revisionable_id', $images_ids)->where('revisionable_type', 'App\Models\UploadProducts')->delete();

            //Documents section
            $documents_ids = DB::table('upload_products')->where('id_product', $product->id)->where('type', 'documents')->pluck('id')->toArray();
            if(($request->status != 1) && ($request->status != 4)){
                $historique_documents = Revision::whereIn('revisionable_id', $documents_ids)->where('revisionable_type', 'App\Models\UploadProducts')->get();
                if(count($historique_documents) > 0){
                    foreach($historique_documents as $document){
                        $uploaded = DB::table('upload_products')->where('id', $document->revisionable_id)->first();
                        if($document->key == "add_document"){
                            if(file_exists(public_path($uploaded->path))){
                                unlink(public_path($uploaded->path));
                            }

                            $uploaded = DB::table('upload_products')->where('id', $document->revisionable_id)->delete();
                        }else{
                            $new_value = json_decode($document->new_value, true);
                            $old_value = json_decode($document->old_value, true);

                            if(file_exists(public_path($new_value['new_path']))){
                                unlink(public_path($new_value['new_path']));
                            }

                            $data = [];
                            $data['path'] = $old_value['old_path'];
                            $data['document_name'] = $old_value['old_document_name'];
                            $uploaded = DB::table('upload_products')->where('id', $document->revisionable_id)->update($data);
                        }

                    }
                }
            }

            $historique_documents = Revision::whereIn('revisionable_id', $documents_ids)->where('revisionable_type', 'App\Models\UploadProducts')->delete();

            //check if status is Revision Required or Rejected to set the rejection reason
            if(($request->status == 2) || ($request->status == 3)){
                if($request->status == 2){
                    $status = 'Revision Required for ' . $product->name . ' Listing';
                    $text = 'Dear Mr/Mrs,
                    <br>We hope this message finds you well. Our team has reviewed the listing for <b>' . $product->name . '</b> on our marketplace and identified areas that require revision.
                    <br>Please note the necessary correction(s):<br> ' . $request->reason . '<br>Kindly make the appropriate changes to ensure that the listing meets our marketplace standards. <br>We appreciate your prompt attention to this matter.
                    Thank you for your cooperation.
                    <br>Best regards,
                    <br>MAWAD team.';
                }else{
                    $status = 'Rejection Notification for Product Listing';
                    $text = 'Dear Mr/Mrs,
                    <br>I hope this email finds you well. <br>After careful review, we regret to inform you that the listing for <b>' . $product->name . '</b> on our marketplace has been rejected.
                    <br>The reason for rejection is as follows:<br> ' . $request->reason . '<br>We understand that this may be disappointing, and we encourage you to review our marketplace guidelines to ensure future submissions meet our requirements.
                    <br>Thank you for your understanding.
                    <br>Best regards,
                    <br>MAWAD team.';
                }

                $user = User::find($product->user_id);
                Mail::to($user->email)->send(new ApprovalProductMail($status, $text));

                $product->rejection_reason = $request->reason;
            }else{
                $product->rejection_reason = null;
            }

            $product->approved = $request->status;
            if(($request->status == 1) || ($request->status == 3)){
                $product->last_version = 0;
            }
            $product->save();

            if($request->status == 1){
                $this->copy_changes_product_in_catalog($product->id);
            }


            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'failed'
            ]);
        }
    }

    public function search(){
        return view('backend.product.catalog.search');
    }

    public function tempStore(Request $request)
    {
        // return response()->json([$request->all()]);

           // Assuming you have a method to prepare or simulate data needed for the preview
        $detailedProduct = $this->prepareDetailedProductData($request->all());
        // return response()->json(['data'=>['slug'=>gettype($detailedProduct)],'success' => true]);
        $product_queries = []; // Simulate or prepare this data
        $total_query = 0; // Calculate or simulate this
        $reviews = []; // Simulate or prepare this data
        $review_status = false; // Determine this based on your logic

        // Store all necessary data in the session for preview
        $request->session()->put('productPreviewData', compact('detailedProduct', 'product_queries', 'total_query', 'reviews', 'review_status'));


        $slug = $request->name;
        return response()->json(['data'=>['slug'=>$slug],'success' => true]);
    }

    public function getYoutubeVideoId($videoLink) {
        // Parse the YouTube video URL to extract the video ID
        $videoId = '';
        parse_str(parse_url($videoLink, PHP_URL_QUERY), $queryParams);
        if (isset($queryParams['v'])) {
            $videoId = $queryParams['v'];
        }
        return $videoId;
    }
    public function getVimeoVideoId($videoLink) {
        // Parse the Vimeo video URL to extract the video ID
        $videoId = '';
        $regex = '/(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/';
        if (preg_match($regex, $videoLink, $matches)) {
            $videoId = $matches[1];
        }
        return $videoId;
    }

    public function prepareDetailedProductData($data){
        // dd($data) ;
        // Check if main_photos has files
        // if (isset($data['photos_variant-0']) && is_array($data['photos_variant-0'])) {
        //     // Process and save main photos
        //     $storedFilePaths = $this->saveMainPhotos($data['photos_variant-0']);
        // }
        // else {
        //     if (isset($data['main_photos']) && is_array($data['main_photos'])) {
        //         // Process and save main photos
        //         $storedFilePaths = $this->saveMainPhotos($data['main_photos']);
        //     } else {
        //         // If no main photos are provided, set an empty array
        //         $storedFilePaths = [];
        //     }
        // }

        // dd($data) ;
        // Retrieve the brand information

        $brand = Brand::find($data['brand_id']);

        $numeric_keys = [];

        foreach ($data as $key => $value) {
            // Extract numeric part from the key
            $numeric_part = substr($key, strrpos($key, '-') + 1);
            // Check if the extracted part is numeric and not already added
            if (is_numeric($numeric_part) && !in_array($numeric_part, $numeric_keys)) {
                // Add to the array of numeric keys
                $numeric_keys[] = $numeric_part;
            }
        }
        $produitVariationImage=false ;
        // dd($numeric_keys) ;
        foreach ($numeric_keys as $numeric_key) {
            // Access corresponding values
            if (isset($data["photos_variant-$numeric_key"]) && is_array($data["photos_variant-$numeric_key"]) && !$produitVariationImage ) {
                        // $storedFilePaths = $this->saveMainPhotos($data["photos_variant-$numeric_key"]);
                        $produitVariationImage=true ;


            }
        }
        if(!$produitVariationImage) {
            if (isset($data['main_photos']) && is_array($data['main_photos'])) {
                // Process and save main photos
                $storedFilePaths = $this->saveMainPhotos($data['main_photos']);
            } else {
                // If no main photos are provided, set an empty array
                $storedFilePaths = [];
            }
        }
        // if (isset($variationId)) {
        //     if (isset($data["variant_pricing-from$variationId"]) && is_array($data["variant_pricing-from$variationId"])) {
        //         $firstValue = $data["variant_pricing-from$variationId"]['from'][0];
        //         dd($firstValue) ;
        //     } else {
        //         // If no main photos are provided, set an empty array
        //         $storedFilePaths = [];
        //     }
        // }

        // $produitVariationprice=false ;
        // // dd($numeric_keys) ;
        // foreach ($numeric_keys as $numeric_key) {
        //     // Access corresponding values
        //     if (isset($data["variant_pricing-from$numeric_key"]) && is_array($data["variant_pricing-from$numeric_key"]) && !$produitVariationprice ) {
        //         $fromPrice = $data["variant_pricing-from$numeric_key"]['from'][0];
        //         $toPrice = $data["variant_pricing-from$numeric_key"]['to'][0];
        //         $unitPrice = $data["variant_pricing-from$numeric_key"]['unit_price'][0];
        //         $total = isset($fromPrice) && isset($toPrice) ? $fromPrice * $unitPrice : "";
        //         $produitVariationprice=true ;
        //     }
        // }

       // Now $numeric_keys array contains the unique numeric parts
       $attributesArray = [];
        foreach ($numeric_keys as $numeric_key) {
            // Access corresponding values
            if (isset($data["attribute_generale-$numeric_key"])) {
                // Value is set, you can do something with it here
                $attribute = Attribute::find($numeric_key) ;

                $value = $data["attribute_generale-$numeric_key"];
            // Add attribute name and value to the array
            if ($attribute) {
                if (isset($data["unit_attribute_generale-$numeric_key"])){
                    $unit = Unity::find($data["unit_attribute_generale-$numeric_key"]) ;
                    if ($unit)
                        $attributesArray[$attribute->id] = $value.' '.$unit->name;
                }
                else
                         $attributesArray[$attribute->id] = $value;
            }
         }
        }
        // dd($data) ;
        // $variants = [];

        // foreach ($data as $key => $value) {
        //     // Split the key to extract variant ID and attribute ID
        //     $parts = explode('-', $key);

        //     // Ensure the key format is valid
        //     if (count($parts) === 3 && $parts[0] === 'attributes') {
        //         $variantId = $parts[1];
        //         $attributeId = $parts[2];

        //         // Initialize variant if not exists
        //         if (!isset($variants[$variantId])) {
        //             $variants[$variantId] = [];
        //         }

        //         // Add attribute value to the variant
        //         $variants[$variantId][$attributeId] = $value;
        //     }
        // }


        // dd($data) ;
       // Extract unique attribute IDs and their values
    //    $attributes = $this->extractAttributes($variants);
       $variations = [];

       foreach ($data as $key => $value) {
           if (strpos($key, 'attributes') === 0 && (strpos($key, 'attributes_units') === false)) {
               // Extract the attribute number and variation id
               $parts = explode('-', $key);
               $variationId = $parts[2];
               $attributeId = $parts[1];
               // Initialize the variation if not exists
               if (!isset($variations[$variationId])) {
                   $variations[$variationId] = [];
               }
               // Add attribute to variation
               if (isset($data["attributes_units-$attributeId-$variationId"])){
                $unit = Unity::find($data["attributes_units-$attributeId-$variationId"]) ;
                if ($unit)
                    $variations[$variationId][$attributeId] = $value.' '.$unit->name;
                }
                else
                    $variations[$variationId][$attributeId] = $value;

               if (isset($data["photos_variant-$variationId"]) && is_array($data["photos_variant-$variationId"])) {
                $variations[$variationId]['storedFilePaths'] = $this->saveMainPhotos($data["photos_variant-$variationId"]);

               }
               else {
                $variations[$variationId]['storedFilePaths']= [] ;
               }
               if (isset($data["variant_pricing-from$variationId"]) && is_array($data["variant_pricing-from$variationId"])) {
                $variations[$variationId]['variant_pricing-from']['from'] =$data["variant_pricing-from$variationId"]['from'] ?? [] ;
                $variations[$variationId]['variant_pricing-from']['to'] =$data["variant_pricing-from$variationId"]['to'] ?? [] ;
                $variations[$variationId]['variant_pricing-from']['unit_price'] =$data["variant_pricing-from$variationId"]['unit_price'] ?? [] ;
                $variations[$variationId]['variant_pricing-from']['discount'] =[
                    'type' => $data["variant_pricing-from$variationId"]['discount_type']?? null,
                    'amount' => $data["variant_pricing-from$variationId"]['discount_amount']?? null,
                    'percentage' => $data["variant_pricing-from$variationId"]['discount_percentage']?? null,
                    'date' => $data["variant_pricing-from$variationId"]['discount_range']?? null,
                ] ;
               } elseif (isset($data["variant-pricing-$variationId"]) && $data["variant-pricing-$variationId"] == 1 ){
                    $variations[$variationId]['variant_pricing-from']['from'] =$data['from'] ?? []  ;
                    $variations[$variationId]['variant_pricing-from']['to'] =$data['to']  ?? [] ;
                    $variations[$variationId]['variant_pricing-from']['unit_price'] =$data['unit_price'] ?? []  ;
                    $variations[$variationId]['variant_pricing-from']['discount'] =[
                        'type' => $data['discount_type']?? null,
                        'amount' => $data['discount_amount']?? null,
                        'percentage' => $data['discount_percentage']?? null,
                        'date' => $data['date_range_pricing']?? null,
                    ] ;
               }
            //    if (isset($variations[$variationId]['variant_pricing-from'])) {
            //     // Sorting each array if it's not empty
            //     foreach ($variations[$variationId]['variant_pricing-from'] as &$subArray) {
            //         if (!empty($subArray)) {
            //             sort($subArray);
            //         }
            //     }
            //     unset($subArray); // Unset the reference to avoid potential side-effects
            //    }

           }
       }

    //    dd($data['variant']['attributes']) ;
       if (isset($data['variant']['attributes']))
        foreach ($data['variant']['attributes'] as $variationId=>$variations_db) {
            foreach ($variations_db as $attributeId=>$attribute) {
                if (!isset($variations[$variationId])) {
                    $variations[$variationId] = [];
                }
                if(isset($data['unit_variant'][$variationId][$attributeId])){
                    $unit = Unity::find($data['unit_variant'][$variationId][$attributeId]) ;
                    if ($unit)
                        $variations[$variationId][$attributeId] = $attribute.' '.$unit->name;
                 }
                else
                    $variations[$variationId][$attributeId] = $attribute;
            }

        }

        if (isset($data['variant']['from']))
        foreach ($data['variant']['from'] as $variationId=>$variations_db_from) {

                if (!isset($variations[$variationId])) {
                    $variations[$variationId] = [];
                }
                $variations[$variationId]['variant_pricing-from']['from'] = $variations_db_from;
                $variations[$variationId]['variant_pricing-from']['to'] = $data['variant']['to'][$variationId] ?? [];
                $variations[$variationId]['variant_pricing-from']['unit_price'] = $data['variant']['unit_price'][$variationId] ?? [];
                $upload_products_db = UploadProducts::where('id_product',$variationId)->pluck('path')->toArray() ;
                $variations[$variationId]['storedFilePaths'] = $upload_products_db ;
                // if (isset($variations[$variationId]['variant_pricing-from'])) {
                //     // Sorting each array if it's not empty
                //     foreach ($variations[$variationId]['variant_pricing-from'] as &$subArray) {
                //         if (!empty($subArray)) {
                //             sort($subArray);
                //         }
                //     }
                //     unset($subArray); // Unset the reference to avoid potential side-effects
                //    }

        }


       $attributes = [];

       foreach ($variations as $variation) {
           foreach ($variation as $attributeId => $value) {
               if ($attributeId != "storedFilePaths" && $attributeId != "variant_pricing-from" ) {
                if (!isset($attributes[$attributeId])) {
                    $attributes[$attributeId] = [];
                }
                // Add value to the unique attributes array if it doesn't already exist
                if (!in_array($value, $attributes[$attributeId])) {
                    $attributes[$attributeId][] = $value;
                }
               }

           }
       }



    //    dd($variations) ;
    //    $attributeAvailable= [] ;
    //    $attributeId = 6; // Example attribute ID
    //    $attributeValue = 5 ;
    //    foreach ($variations as $key => $variation) {
    //         foreach($variation as $key=>$attribute) {
    //             if ($key ==$attributeId && $attribute==$attributeValue ) {
    //                 $attributeAvailable[] = array_keys($variation) ;
    //                 break ;
    //             }
    //         }

    //    }
    //    dd($attributeAvailable) ;

        if ($data["video_provider"] === "youtube") {
             $getYoutubeVideoId=$this->getYoutubeVideoId($data["video_link"]) ;

        }
        else {
            $getVimeoVideoId=$this->getVimeoVideoId($data["video_link"]) ;
        }
        if (is_array($variations) && !empty($variations)) {
            $lastItem  = end($variations);
            $variationId = key($variations); // Get the key (variation ID) of the last item
            // sort($lastItem['variant_pricing-from']['from']) ;
            // sort($lastItem['variant_pricing-from']['unit_price']) ;
            // sort($lastItem['variant_pricing-from']['to']) ;

            $max =max($lastItem['variant_pricing-from']['to']) ;
            $min =min($lastItem['variant_pricing-from']['from']) ;
        }


        // if (isset($data['from']) && is_array($data['from']) && !empty($data['from'])) {
        //     sort($data['from']);
        // }

        // if (isset($data['unit_price']) && is_array($data['unit_price']) && !empty($data['unit_price'])) {
        //     sort($data['unit_price']);
        // }
        if (isset($data['from']) && is_array($data['from']) && count($data['from']) > 0) {
            // sort($data['from']);
            if(!isset($min))
                $min = min($data['from']) ;
        }

        // if (isset($data['unit_price']) && is_array($data['unit_price']) && count($data['unit_price']) > 0) {
        //     sort($data['unit_price']);
        // }

        if (isset($data['to']) && is_array($data['to']) && count($data['to']) > 0) {
            // sort($data['to']);
            if(!isset($max))
                $max = max($data['to']) ;
        }

        $total = isset($data['from'][0]) && isset($data['unit_price'][0]) ? $data['from'][0] * $data['unit_price'][0] : "";
        // return response()->json(['status', $attributesArray]);
        if( isset($lastItem['variant_pricing-from']['discount']['date']) && is_array($lastItem['variant_pricing-from']['discount']['date']) && !empty($lastItem['variant_pricing-from']['discount']['date']) && $lastItem['variant_pricing-from']['discount']['date'][0] !== null){
        // Extract start and end dates from the first date interval

        $dateRange = $lastItem['variant_pricing-from']['discount']['date'][0];
        list($startDate, $endDate) = explode(' to ', $dateRange);

        // Convert date strings to DateTime objects for comparison
        $currentDate = new DateTime(); // Current date/time
        $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
        $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

            // Check if the current date/time is within the specified date interval
            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                // Assuming $lastItem is your array containing the pricing information
                $unitPrice = $lastItem['variant_pricing-from']['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                // Calculate the total price based on quantity and unit price
                $variantPricing = $unitPrice;

                if($lastItem['variant_pricing-from']['discount']['type'][0] == "percent") {
                    $percent = $lastItem['variant_pricing-from']['discount']['percentage'][0] ;
                    if ($percent) {


                        // Calculate the discount amount based on the given percentage
                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                        $discountAmount = ($variantPricing * $discountPercent) / 100;

                        // Calculate the discounted price
                        $discountedPrice = $variantPricing - $discountAmount;

                    }
                }else if($lastItem['variant_pricing-from']['discount']['type'][0] == "amount"){
                    // Calculate the discount amount based on the given amount
                    $amount = $lastItem['variant_pricing-from']['discount']['amount'][0] ;

                    if ($amount) {
                        $discountAmount = $amount;
                        // Calculate the discounted price
                        $discountedPrice = $variantPricing - $discountAmount;

                    }

                }
            }
        }
        if (isset($discountedPrice) && $discountedPrice > 0 && isset($lastItem['variant_pricing-from']['from'][0])) {
            $totalDiscount=$lastItem['variant_pricing-from']['from'][0]*$discountedPrice;
        }

        if( isset($data['date_range_pricing']) && is_array($data['date_range_pricing']) && !empty($data['date_range_pricing']) && $data['date_range_pricing'][0] !== null){
            // Extract start and end dates from the first date interval

            $dateRange = $data['date_range_pricing'][0];
            list($startDate, $endDate) = explode(' to ', $dateRange);

            // Convert date strings to DateTime objects for comparison
            $currentDate = new DateTime(); // Current date/time
            $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
            $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                // Check if the current date/time is within the specified date interval
                if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                    // Assuming $lastItem is your array containing the pricing information
                    $unitPrice = $data['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                    // Calculate the total price based on quantity and unit price
                    $variantPricing = $unitPrice;

                    if($data['discount_type'][0] == "percent") {
                        $percent = $data['discount_percentage'][0] ;
                        if ($percent) {


                            // Calculate the discount amount based on the given percentage
                            $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                            $discountAmount = ($variantPricing * $discountPercent) / 100;

                            // Calculate the discounted price
                            $discountedPrice = $variantPricing - $discountAmount;

                        }
                    }else if($data['discount_type'][0] == "amount"){
                        // Calculate the discount amount based on the given amount
                        $amount = $data['discount_amount'][0] ;

                        if ($amount) {
                            $discountAmount = $amount;
                            // Calculate the discounted price
                            $discountedPrice = $variantPricing - $discountAmount;

                        }

                    }
                }
            }
            if (isset($discountedPrice) && $discountedPrice > 0 && isset($data['from'][0])) {
                $totalDiscount=$data['from'][0]*$discountedPrice;
            }
        // Prepare detailed product data
        $detailedProduct = [
            'name' => $data['name'],
            'brand' => $brand ? $brand->name : "",
            'unit' => $data['unit'],
            'description' => $data['description'],
            'main_photos' => $lastItem['storedFilePaths'] ?? $storedFilePaths, // Add stored file paths to the detailed product data
            // 'quantity' => isset($data['from'][0]) ? $data['from'][0] : "" ,
            // 'price' => isset($data['unit_price'][0]) ? $data['unit_price'][0] : "",
            // 'quantity' => isset($fromPrice) ? $fromPrice  : $data['from'][0] ,
            // 'price' => isset($unitPrice) ? $unitPrice  : $data['unit_price'][0],
            // 'total' => $total,
            'quantity' => $lastItem['variant_pricing-from']['from'][0] ?? $data['from'][0] ?? '',
            'price' => $lastItem['variant_pricing-from']['unit_price'][0] ?? $data['unit_price'][0] ?? '',
            'total' => $totalDiscount ??(isset($lastItem['variant_pricing-from']['from'][0]) && isset($lastItem['variant_pricing-from']['unit_price'][0]) ? $lastItem['variant_pricing-from']['from'][0] * $lastItem['variant_pricing-from']['unit_price'][0] : $total) ,
            'max' =>$max ?? 1 ,
            'min' =>$min ?? 1 ,
            'general_attributes' =>$attributesArray,
            'attributes' =>$attributes ?? [] ,
            'description' =>$data['description'] ,
            'from' =>$data['from'] ?? [] ,
            'to' =>$data['to']  ?? [],
            'unit_price' =>$data['unit_price'] ?? [] ,
            'variations' =>$variations,
            'variationId' => $variationId ?? null,
            'lastItem' => $lastItem ?? [],
            'catalog' => false,
            'video_provider'  => $data["video_provider"] ,
            'getYoutubeVideoId' =>$getYoutubeVideoId ?? null ,
            'getVimeoVideoId' => $getVimeoVideoId ?? null,
            'discountedPrice' => $discountedPrice ?? null,
            'totalDiscount' => $totalDiscount ?? null,
            'date_range_pricing' =>  $data['date_range_pricing']  ?? null,
            'discount_type' => $data['discount_type'] ?? null ,
            'discount_percentage' => $data['discount_percentage'],
            'discount_amount'=> $data['discount_amount'],
        ];



        return $detailedProduct;
    }

    private function saveMainPhotos($photos){

        $storedFilePaths = [];

        foreach ($photos as $photo) {
            // Generate a unique filename
            $filename = uniqid('main_photo_') . '.' . $photo->getClientOriginalExtension();

            // Store the file to the desired location (e.g., public storage)
            $storedPath = $photo->storeAs('preview_products', $filename);

            // Add the stored file path to the array
            $storedFilePaths[] = $storedPath;
        }

        return $storedFilePaths;
    }


    public function preview(Request $request)
    {
        $previewData = $request->session()->get('productPreviewData', null);

        // dd($previewData);
        if (!$previewData) {
            return redirect()->back()->withErrors('No preview data found.');
        }

        // Extract all variables required for the view
        extract($previewData);

        return view('frontend.product_details.preview', compact('previewData'));
    }

    public function updatePricePreview(Request $request) {

        $data=$request->session()->get('productPreviewData', null) ;
        $variations = $data['detailedProduct']['variations'] ;

        // Given value
        $qty = $request->quantity;
        $totalDiscount = 0 ;
        $discountPrice = 0 ;
        // Iterate through the ranges
        $unitPrice = null;
        if($request->variationId != null) {

            foreach ($variations[$request->variationId]['variant_pricing-from']['from'] as $index => $from) {
                $to = $variations[$request->variationId]['variant_pricing-from']['to'][$index];

                if ($qty >= $from && $qty <= $to) {
                     $unitPrice = $variations[$request->variationId]['variant_pricing-from']['unit_price'][$index];
                     if( isset($variations[$request->variationId]['variant_pricing-from']['discount']['date'][$index]) && ($variations[$request->variationId]['variant_pricing-from']['discount']['date'][$index])){
                        // Extract start and end dates from the first date interval

                        $dateRange = $variations[$request->variationId]['variant_pricing-from']['discount']['date'][$index];
                        list($startDate, $endDate) = explode(' to ', $dateRange);

                        // Convert date strings to DateTime objects for comparison
                        $currentDate = new DateTime(); // Current date/time
                        $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                        $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                            // Check if the current date/time is within the specified date interval
                            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {


                                if($variations[$request->variationId]['variant_pricing-from']['discount']['type'][$index] == "percent") {
                                    $percent = $variations[$request->variationId]['variant_pricing-from']['discount']['percentage'][$index] ;
                                    if ($percent) {


                                        // Calculate the discount amount based on the given percentage
                                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                        $discountAmount = ($unitPrice * $discountPercent) / 100;

                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;

                                    }
                                }else if($variations[$request->variationId]['variant_pricing-from']['discount']['type'][$index] == "amount"){
                                    // Calculate the discount amount based on the given amount
                                    $amount = $variations[$request->variationId]['variant_pricing-from']['discount']['amount'][$index] ;

                                    if ($amount) {
                                        $discountAmount = $amount;
                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;

                                    }

                                }
                            }
                        }
                    break; // Stop iterating once the range is found
                }
            }

        }
        else {
            foreach ($data['detailedProduct']['from'] as $index => $from) {
                $to = $data['detailedProduct']['to'][$index];
                if ($qty >= $from && $qty <= $to) {
                    $unitPrice = $data['detailedProduct']['unit_price'][$index];

                    if( isset($data['detailedProduct']['date_range_pricing'][$index]) && ($data['detailedProduct']['date_range_pricing'][$index])){
                        // Extract start and end dates from the first date interval

                        $dateRange = $data['detailedProduct']['date_range_pricing'][$index];
                        list($startDate, $endDate) = explode(' to ', $dateRange);

                        // Convert date strings to DateTime objects for comparison
                        $currentDate = new DateTime(); // Current date/time
                        $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                        $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                            // Check if the current date/time is within the specified date interval
                            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {


                                if($data['detailedProduct']['discount_type'][$index] == "percent") {
                                    $percent = $data['detailedProduct']['discount_percentage'][$index] ;

                                    if ($percent) {


                                        // Calculate the discount amount based on the given percentage
                                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                        $discountAmount = ($unitPrice * $discountPercent) / 100;

                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;

                                    }
                                }else if($data['detailedProduct']['discount_type'][$index] == "amount"){
                                    // Calculate the discount amount based on the given amount
                                    $amount = $data['discount_amount'][$index] ;

                                    if ($amount) {
                                        $discountAmount = $amount;
                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;

                                    }

                                }
                            }
                        }
                    break; // Stop iterating once the range is found
                }
            }
        }
        $maximum = 1 ;
        $minimum = 1 ;
        if($request->variationId != null) {
            // Convert array values to integers
            $valuesFrom = array_map('intval', $variations[$request->variationId]['variant_pricing-from']['from']);
            $valuesMax = array_map('intval', $variations[$request->variationId]['variant_pricing-from']['to']);
        } else {
            $valuesFrom = array_map('intval', $data['detailedProduct']['from']);
            $valuesMax = array_map('intval', $data['detailedProduct']['to']);
        }
            // Get the maximum value
            if (!empty($valuesMax))
                $maximum = max($valuesMax);
            // Get the minimum value
            if (!empty($valuesFrom))
                $minimum = min($valuesFrom);


        $total=$qty*$unitPrice;
        if (isset($discountPrice) && $discountPrice > 0) {
            $totalDiscount=$qty*$discountPrice;
        }
     // Return the unit price as JSON response
     return response()->json(['unit_price' => $unitPrice,"qty"=>$qty,'total'=>$total,'maximum'=>$maximum,'minimum'=>$minimum,'totalDiscount'=>$totalDiscount,'discountPrice'=>$discountPrice]);
    }

    public function ProductCheckedAttributes(Request $request) {
        // dd($request->all()) ;
        $data=$request->session()->get('productPreviewData', null) ;
        $variations = $data['detailedProduct']['variations'] ;
        $checkedAttributes = $request->checkedAttributes ; // Checked attribute and its value
        // dd($variations,$checkedAttributes) ;
        $matchedImages = [];
        $availableAttributes = [];
        $anyMatched = false ;
        $pickedAnyVariation = false ;
        $maximum = 1 ;
        $minimum = 1 ;
        $totalDiscount = 0 ;
        $discountedPrice = 0 ;
        foreach ($variations as $variationIdKey =>$variation) {

            $matchesCheckedAttributes = true;

            // Check if the variation matches the checked attributes
            foreach ($checkedAttributes as $attributeId => $value) {
                if (!isset($variation[$attributeId]) || $variation[$attributeId] !== $value) {
                    $matchesCheckedAttributes = false;
                    break;
                }
            }

            // If the variation matches the checked attributes, collect other attributes
            if ($matchesCheckedAttributes) {
                $anyMatched = true ;
                if (isset($variation['storedFilePaths']) && is_array($variation['storedFilePaths']) && count($matchedImages) == 0  ) {
                        foreach ($variation['storedFilePaths'] as $image) {
                            $matchedImages[] = $image;
                        }

                 }
                 if ($pickedAnyVariation == false) {
                    $variationId = $variationIdKey ;
                    $quantity = $variation['variant_pricing-from']['from'][0] ?? "" ;
                    $price = $variation['variant_pricing-from']['unit_price'][0] ?? "" ;
                    $total =  isset($variation['variant_pricing-from']['from'][0]) && isset($variation['variant_pricing-from']['unit_price'][0]) ? $variation['variant_pricing-from']['from'][0] * $variation['variant_pricing-from']['unit_price'][0] : "" ;
                    if( isset($variation['variant_pricing-from']['discount']['date']) && is_array($variation['variant_pricing-from']['discount']['date'])){
                        // Extract start and end dates from the first date interval

                        $dateRange = $variation['variant_pricing-from']['discount']['date'][0];
                        list($startDate, $endDate) = explode(' to ', $dateRange);

                        // Convert date strings to DateTime objects for comparison
                        $currentDate = new DateTime(); // Current date/time
                        $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                        $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                            // Check if the current date/time is within the specified date interval
                            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                                // Assuming $lastItem is your array containing the pricing information
                                $unitPrice = $variation['variant_pricing-from']['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                                // Calculate the total price based on quantity and unit price
                                $variantPricing = $unitPrice;

                                if($variation['variant_pricing-from']['discount']['type'][0] == "percent") {
                                    $percent = $variation['variant_pricing-from']['discount']['percentage'][0] ;
                                    if ($percent) {


                                        // Calculate the discount amount based on the given percentage
                                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                        $discountAmount = ($variantPricing * $discountPercent) / 100;

                                        // Calculate the discounted price
                                        $discountedPrice = $variantPricing - $discountAmount;

                                    }
                                }else if($variation['variant_pricing-from']['discount']['type'][0] == "amount"){
                                    // Calculate the discount amount based on the given amount
                                    $amount = $variation['variant_pricing-from']['discount']['amount'][0] ;

                                    if ($amount) {
                                        $discountAmount = $amount;
                                        // Calculate the discounted price
                                        $discountedPrice = $variantPricing - $discountAmount;

                                    }

                                }
                            }
                        }
                        if (isset($discountedPrice) && $discountedPrice > 0 && isset($variation['variant_pricing-from']['from'][0])) {
                            $totalDiscount=$variation['variant_pricing-from']['from'][0]*$discountedPrice;
                        }

                    // Convert array values to integers
                    $valuesFrom = array_map('intval', $variation['variant_pricing-from']['from']);
                    $valuesMax = array_map('intval', $variation['variant_pricing-from']['to']);
                    // Get the maximum value
                    if (!empty($valuesMax))
                        $maximum = max($valuesMax);
                    // Get the minimum value
                    if (!empty($valuesFrom))
                         $minimum = min($valuesFrom);
                    $pickedAnyVariation = true ;
                 }

                foreach ($variation as $attributeId => $value) {
                    if (!isset($checkedAttributes[$attributeId])) {
                        if (!isset($availableAttributes[$attributeId])) {
                            $availableAttributes[$attributeId] = [];
                        }
                        if (!in_array($value, $availableAttributes[$attributeId])) {
                            $availableAttributes[$attributeId][] = $value;
                        }
                    }
                }
            }
        }

        // Add matchesCheckedAttributes to the response
        $response = [
            'availableAttributes' => $availableAttributes,
            'anyMatched' => $anyMatched,
            'matchedImages' => $matchedImages,
            'variationId' => $variationId ?? null,
            'quantity' => $quantity ?? null  ,
            'price' => $price ?? null ,
            'total' => $totalDiscount ?? $total ?? null,
            'maximum' => $maximum ,
            'minimum' => $minimum ,
            'discountedPrice' => $discountedPrice ?? null,
            'totalDiscount' => $totalDiscount ?? null,

        ];
        // return response()->json($availableAttributes);
        return response()->json($response);

    }


}
