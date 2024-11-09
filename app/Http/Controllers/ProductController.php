<?php

namespace App\Http\Controllers;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Requests\ProductRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\BusinessInformation;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductAttributeValueCatalog;
use App\Models\ProductAttributeValues;
use App\Models\ProductCatalog;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use App\Models\Unity;
use App\Models\UploadProductCatalog;
use App\Models\UploadProducts;
use App\Models\User;
use App\Models\Wishlist;
use App\Notifications\ShopProductNotification;
use App\Services\ProductFlashDealService;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\ProductTaxService;
use App\Services\ProductUploadsService;
use Artisan;
use Cache;
use Carbon\Carbon;
use CoreComponentRepository;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
            $query->where('is_draft', 0)
                ->where('parent_id', 0)
                ->where('is_parent', 0);
        })->orWhere(function ($query) {
            $query->where('is_draft', 0)
                ->where('is_parent', 1);
        })->orderBy('id', 'desc');

        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%'.$search.'%');
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
                ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(',', $request->type);
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
                $query->where('is_draft', 0)
                    ->where('parent_id', 0)
                    ->where('is_parent', 0);
            })
                ->orWhere(function ($query) {
                    $query->where('is_draft', 0)
                        ->where('is_parent', 1);
                });
        })->orderBy('id', 'desc');

        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%'.$search.'%');
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
            $html .= '<option value="'.$row->value.'">'.$row->value.'</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = $this->productService->store($request->except([
            'photosThumbnail', 'main_photos', 'product', 'documents', 'document_names', 'discount_percentage', '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type', 'from', 'to', 'unit_price', 'discount_type', 'discount_amount', 'discount_amount',
        ]));

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->attach($request->category_ids);

        //Upload documents, images and thumbnails
        if ($request->document_names) {
            $data['document_names'] = $request->document_names;
            $data['documents'] = $request->documents;
            $data['product'] = $product;
            $data['main_photos'] = $request->main_photos;
            $data['photosThumbnail'] = $request->photosThumbnail;
            $this->productUploadsService->store_uploads($data);
        }

        //Pricing configuration
        $this->productPricingService->store([
            'from' => $request->from,
            'to' => $request->to,
            'unit_price' => $request->unit_price,
            'date_range_pricing' => $request->date_range_pricing,
            'discount_type' => $request->discount_type,
            'discount_amount' => $request->discount_amount,
            'discount_percentage' => $request->discount_percentage,
            'product' => $product,
        ]);

        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id',
            ]));
        }

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type',
        ]), $product);

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id',
        ]), $product);

        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang', 'name', 'unit', 'description', 'product_id',
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
            return redirect('admin/digitalproducts/'.$id.'/edit');
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
            return redirect('digitalproducts/'.$id.'/edit');
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
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type',
        ]), $product);

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->sync($request->category_ids);

        //Product Stock
        $product->stocks()->delete();
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id',
        ]), $product);

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type',
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            $product->taxes()->delete();
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id',
            ]));
        }

        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang', 'product_id',
            ]),
            $request->only([
                'name', 'unit', 'description',
            ])
        );

        flash(translate('Product has been updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        if ($request->has('tab') && $request->tab != null) {
            return Redirect::to(URL::previous().'#'.$request->tab);
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
        if ($request->type == 'In House') {
            return redirect()->route('products.admin');
        } elseif ($request->type == 'Seller') {
            return redirect()->route('products.seller');
        } elseif ($request->type == 'All') {
            return redirect()->route('products.all');
        }
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

        $product_type = $product->digital == 0 ? 'physical' : 'digital';
        $status = $request->approved == 1 ? 'approved' : 'rejected';
        $users = User::findMany([User::where('user_type', 'admin')->first()->id, $product->user_id]);
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
        $options = [];
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
                $name = 'choice_options_'.$no;
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = [];
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }

        $combinations = (new CombinationService)->generate_combination($options);

        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = [];
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
                $name = 'choice_options_'.$no;
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = [];
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }

        $combinations = (new CombinationService)->generate_combination($options);

        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

    public function approve($id)
    {
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

        if ($product != null) {
            if ($product->is_parent == 1) {
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

                if (count($historique_children) > 0) {
                    foreach ($historique_children as $historique_child) {
                        foreach ($variants_attributes as $variant) {
                            if ($variant->id == $historique_child->revisionable_id) {
                                $variant->key = $historique_child->key;
                                if ($historique_child->key == 'id_units') {
                                    $unit = Unity::find($historique_child->old_value);
                                    if ($unit != null) {
                                        $variant->old_value = $unit->name;
                                    } else {
                                        $variant->old_value = '';
                                    }
                                } else {
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
            $data_general_attributes_color_added = [];
            if (count($general_attributes) > 0) {
                foreach ($general_attributes as $general_attribute) {
                    // $data_general_attributes[$general_attribute->id_attribute] = $general_attribute;

                    if ($general_attribute->id_colors != null) {
                        if (array_key_exists($general_attribute->id_attribute, $data_general_attributes)) {
                            array_push($data_general_attributes[$general_attribute->id_attribute], $general_attribute->id_colors);
                        } else {
                            $data_general_attributes[$general_attribute->id_attribute] = [$general_attribute->id_colors];
                        }
                    } else {
                        $data_general_attributes[$general_attribute->id_attribute] = $general_attribute;
                    }

                    if (count($historique_parent) > 0) {
                        foreach ($historique_parent as $historique) {
                            if ($general_attribute->id == $historique->revisionable_id) {
                                if ($general_attribute->id == $historique->revisionable_id) {
                                    $general_attribute->key = $historique->key;
                                    if ($historique->key == 'add_attribute') {
                                        $general_attribute->added = true;
                                        array_push($data_general_attributes_color_added, $historique->new_value);
                                    } else {
                                        if ($historique->key == 'id_units') {
                                            $unit = Unity::find($historique->old_value);
                                            if ($unit != null) {
                                                $general_attribute->old_value = $unit->name;
                                            } else {
                                                $general_attribute->old_value = '';
                                            }
                                        } else {
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
            if ($product_category != null) {
                $categorie = Category::find($product_category->category_id);
                $current_categorie = $categorie;

                $parents = [];
                if ($current_categorie->parent_id == 0) {
                    array_push($parents, $current_categorie->id);
                } else {
                    array_push($parents, $current_categorie->id);
                    while ($current_categorie->parent_id != 0) {
                        $parent = Category::where('id', $current_categorie->parent_id)->first();
                        array_push($parents, $parent->id);
                        $current_categorie = $parent;
                    }
                }

                if (count($parents) > 0) {
                    $attributes_ids = DB::table('categories_has_attributes')->whereIn('category_id', $parents)->pluck('attribute_id')->toArray();
                    if (count($attributes_ids) > 0) {
                        $attributes = Attribute::whereIn('id', $attributes_ids)->get();
                    }
                }
            }

            //Historique Product informations
            $general_informations = [];
            $general_informations_data = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_id', $id)->where('revisionable_type', 'App\Models\Product')->get();

            if (count($general_informations_data) > 0) {
                foreach ($general_informations_data as $general_information) {
                    switch ($general_information->key) {
                        case 'brand_id':
                            $brand = Brand::find($general_information->old_value);
                            if ($brand != null) {
                                $general_informations[$general_information->key] = $brand->name;
                            } else {
                                $general_informations[$general_information->key] = '';
                            }
                            break;
                        case 'category_id':
                            $path = '';
                            $current_category = Category::find($general_information->old_value);
                            if ($current_category != null) {
                                while ($current_category->parent_id != 0) {
                                    if ($path == '') {
                                        $path = $current_category->name;
                                    } else {
                                        $path = $current_category->name.' > '.$path;
                                    }
                                    $current_category = Category::find($current_category->parent_id);
                                }
                                if ($current_category->parent_id == 0) {
                                    if ($path == '') {
                                        $path = $current_category->name;
                                    } else {
                                        $path = $current_category->name.' > '.$path;
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
            if (count($historique_documents) > 0) {
                foreach ($historique_documents as $historique_document) {
                    $current_status = [];
                    if ($historique_document->key == 'add_document') {
                        $current_status['border_color'] = 'green';
                        $current_status['action'] = 'add';
                    } else {
                        $current_status['border_color'] = 'red';
                        $current_status['action'] = 'update';
                        $new_value = json_decode($historique_document->new_value, true);
                        $old_value = json_decode($historique_document->old_value, true);
                        if (array_key_exists('new_document_name', $new_value)) {
                            $current_status['document_name'] = $old_value['old_document_name'];
                        }

                        if (array_key_exists('new_path', $new_value)) {
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
            if ($product->activate_third_party == 1) {
                $volumetric_weight = ($product->length * $product->height * $product->width) / 5000;
                if ($volumetric_weight > $product->weight) {
                    $chargeable_weight = $volumetric_weight;
                } else {
                    $chargeable_weight = $product->weight;
                }

                if ($product->unit_weight == 'pounds') {
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
                'chargeable_weight' => $chargeable_weight,
                'data_general_attributes_color_added' => $data_general_attributes_color_added,
            ]);
        } else {
            abort(404);
        }

    }

    public function approve_action(Request $request)
    {
        return $this->productService->approveProduct($request);
    }

    public function search()
    {
        $catalogs = ProductCatalog::whereHas('product', function ($query) {
            $query->whereNull('deleted_at');
        })->orderBy('created_at', 'desc')->paginate(12);

        return view('backend.product.catalog.search', [
            'catalogs' => $catalogs,
        ]);
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

        return response()->json(['data' => ['slug' => $slug], 'success' => true]);
    }

    public function getYoutubeVideoId($videoLink)
    {
        // Parse the YouTube video URL to extract the video ID
        $videoId = '';
        parse_str(parse_url($videoLink, PHP_URL_QUERY), $queryParams);
        if (isset($queryParams['v'])) {
            $videoId = $queryParams['v'];
        }

        return $videoId;
    }

    public function getVimeoVideoId($videoLink)
    {
        // Parse the Vimeo video URL to extract the video ID
        $videoId = '';
        $regex = '/(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/';
        if (preg_match($regex, $videoLink, $matches)) {
            $videoId = $matches[1];
        }

        return $videoId;
    }

    public function prepareDetailedProductData($data)
    {
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
            if (is_numeric($numeric_part) && ! in_array($numeric_part, $numeric_keys)) {
                // Add to the array of numeric keys
                $numeric_keys[] = $numeric_part;
            }
        }
        $produitVariationImage = false;
        // dd($numeric_keys) ;
        foreach ($numeric_keys as $numeric_key) {
            // Access corresponding values
            if (isset($data["photos_variant-$numeric_key"]) && is_array($data["photos_variant-$numeric_key"]) && ! $produitVariationImage) {
                // $storedFilePaths = $this->saveMainPhotos($data["photos_variant-$numeric_key"]);
                $produitVariationImage = true;

            }
        }
        if (! $produitVariationImage) {
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
                $attribute = Attribute::find($numeric_key);

                $value = $data["attribute_generale-$numeric_key"];
                // Add attribute name and value to the array
                if ($attribute) {
                    if (isset($data["unit_attribute_generale-$numeric_key"])) {
                        $unit = Unity::find($data["unit_attribute_generale-$numeric_key"]);
                        if ($unit) {
                            $attributesArray[$attribute->id] = $value.' '.$unit->name;
                        }
                    } else {
                        $attributesArray[$attribute->id] = $value;
                    }
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
                if (! isset($variations[$variationId])) {
                    $variations[$variationId] = [];
                }
                // Add attribute to variation
                if (isset($data["attributes_units-$attributeId-$variationId"])) {
                    $unit = Unity::find($data["attributes_units-$attributeId-$variationId"]);
                    if ($unit) {
                        $variations[$variationId][$attributeId] = $value.' '.$unit->name;
                    }
                } else {
                    $variations[$variationId][$attributeId] = $value;
                }

                if (isset($data["photos_variant-$variationId"]) && is_array($data["photos_variant-$variationId"])) {
                    $variations[$variationId]['storedFilePaths'] = $this->saveMainPhotos($data["photos_variant-$variationId"]);

                } else {
                    $variations[$variationId]['storedFilePaths'] = [];
                }
                if (isset($data["variant_pricing-from$variationId"]) && is_array($data["variant_pricing-from$variationId"])) {
                    $variations[$variationId]['variant_pricing-from']['from'] = $data["variant_pricing-from$variationId"]['from'] ?? [];
                    $variations[$variationId]['variant_pricing-from']['to'] = $data["variant_pricing-from$variationId"]['to'] ?? [];
                    $variations[$variationId]['variant_pricing-from']['unit_price'] = $data["variant_pricing-from$variationId"]['unit_price'] ?? [];
                    $variations[$variationId]['variant_pricing-from']['discount'] = [
                        'type' => $data["variant_pricing-from$variationId"]['discount_type'] ?? null,
                        'amount' => $data["variant_pricing-from$variationId"]['discount_amount'] ?? null,
                        'percentage' => $data["variant_pricing-from$variationId"]['discount_percentage'] ?? null,
                        'date' => $data["variant_pricing-from$variationId"]['discount_range'] ?? null,
                    ];
                } elseif (isset($data["variant-pricing-$variationId"]) && $data["variant-pricing-$variationId"] == 1) {
                    $variations[$variationId]['variant_pricing-from']['from'] = $data['from'] ?? [];
                    $variations[$variationId]['variant_pricing-from']['to'] = $data['to'] ?? [];
                    $variations[$variationId]['variant_pricing-from']['unit_price'] = $data['unit_price'] ?? [];
                    $variations[$variationId]['variant_pricing-from']['discount'] = [
                        'type' => $data['discount_type'] ?? null,
                        'amount' => $data['discount_amount'] ?? null,
                        'percentage' => $data['discount_percentage'] ?? null,
                        'date' => $data['date_range_pricing'] ?? null,
                    ];
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
        if (isset($data['variant']['attributes'])) {
            foreach ($data['variant']['attributes'] as $variationId => $variations_db) {
                foreach ($variations_db as $attributeId => $attribute) {
                    if (! isset($variations[$variationId])) {
                        $variations[$variationId] = [];
                    }
                    if (isset($data['unit_variant'][$variationId][$attributeId])) {
                        $unit = Unity::find($data['unit_variant'][$variationId][$attributeId]);
                        if ($unit) {
                            $variations[$variationId][$attributeId] = $attribute.' '.$unit->name;
                        }
                    } else {
                        $variations[$variationId][$attributeId] = $attribute;
                    }
                }

            }
        }

        if (isset($data['variant']['from'])) {
            foreach ($data['variant']['from'] as $variationId => $variations_db_from) {

                if (! isset($variations[$variationId])) {
                    $variations[$variationId] = [];
                }
                $variations[$variationId]['variant_pricing-from']['from'] = $variations_db_from;
                $variations[$variationId]['variant_pricing-from']['to'] = $data['variant']['to'][$variationId] ?? [];
                $variations[$variationId]['variant_pricing-from']['unit_price'] = $data['variant']['unit_price'][$variationId] ?? [];
                $upload_products_db = UploadProducts::where('id_product', $variationId)->pluck('path')->toArray();
                $variations[$variationId]['storedFilePaths'] = $upload_products_db;
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
        }

        $attributes = [];

        foreach ($variations as $variation) {
            foreach ($variation as $attributeId => $value) {
                if ($attributeId != 'storedFilePaths' && $attributeId != 'variant_pricing-from') {
                    if (! isset($attributes[$attributeId])) {
                        $attributes[$attributeId] = [];
                    }
                    // Add value to the unique attributes array if it doesn't already exist
                    if (! in_array($value, $attributes[$attributeId])) {
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

        if ($data['video_provider'] === 'youtube') {
            $getYoutubeVideoId = $this->getYoutubeVideoId($data['video_link']);

        } else {
            $getVimeoVideoId = $this->getVimeoVideoId($data['video_link']);
        }
        if (is_array($variations) && ! empty($variations)) {
            $lastItem = end($variations);
            $variationId = key($variations); // Get the key (variation ID) of the last item
            // sort($lastItem['variant_pricing-from']['from']) ;
            // sort($lastItem['variant_pricing-from']['unit_price']) ;
            // sort($lastItem['variant_pricing-from']['to']) ;

            $max = max($lastItem['variant_pricing-from']['to']);
            $min = min($lastItem['variant_pricing-from']['from']);
        }

        // if (isset($data['from']) && is_array($data['from']) && !empty($data['from'])) {
        //     sort($data['from']);
        // }

        // if (isset($data['unit_price']) && is_array($data['unit_price']) && !empty($data['unit_price'])) {
        //     sort($data['unit_price']);
        // }
        if (isset($data['from']) && is_array($data['from']) && count($data['from']) > 0) {
            // sort($data['from']);
            if (! isset($min)) {
                $min = min($data['from']);
            }
        }

        // if (isset($data['unit_price']) && is_array($data['unit_price']) && count($data['unit_price']) > 0) {
        //     sort($data['unit_price']);
        // }

        if (isset($data['to']) && is_array($data['to']) && count($data['to']) > 0) {
            // sort($data['to']);
            if (! isset($max)) {
                $max = max($data['to']);
            }
        }

        $total = isset($data['from'][0]) && isset($data['unit_price'][0]) ? $data['from'][0] * $data['unit_price'][0] : '';
        // return response()->json(['status', $attributesArray]);
        if (isset($lastItem['variant_pricing-from']['discount']['date']) && is_array($lastItem['variant_pricing-from']['discount']['date']) && ! empty($lastItem['variant_pricing-from']['discount']['date']) && isset($lastItem['variant_pricing-from']['discount']['date'][0]) && $lastItem['variant_pricing-from']['discount']['date'][0] !== null) {
            // Extract start and end dates from the first date interval

            $dateRange = $lastItem['variant_pricing-from']['discount']['date'][0];
            [$startDate, $endDate] = explode(' to ', $dateRange);

            // Convert date strings to DateTime objects for comparison
            $currentDate = new DateTime; // Current date/time
            $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
            $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

            // Check if the current date/time is within the specified date interval
            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                // Assuming $lastItem is your array containing the pricing information
                $unitPrice = $lastItem['variant_pricing-from']['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                // Calculate the total price based on quantity and unit price
                $variantPricing = $unitPrice;

                if ($lastItem['variant_pricing-from']['discount']['type'][0] == 'percent') {
                    $percent = $lastItem['variant_pricing-from']['discount']['percentage'][0];
                    if ($percent) {

                        // Calculate the discount amount based on the given percentage
                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                        $discountAmount = ($variantPricing * $discountPercent) / 100;

                        // Calculate the discounted price
                        $discountedPrice = $variantPricing - $discountAmount;

                    }
                } elseif ($lastItem['variant_pricing-from']['discount']['type'][0] == 'amount') {
                    // Calculate the discount amount based on the given amount
                    $amount = $lastItem['variant_pricing-from']['discount']['amount'][0];

                    if ($amount) {
                        $discountAmount = $amount;
                        // Calculate the discounted price
                        $discountedPrice = $variantPricing - $discountAmount;

                    }

                }
            }
        }
        if (isset($discountedPrice) && $discountedPrice > 0 && isset($lastItem['variant_pricing-from']['from'][0])) {
            $totalDiscount = $lastItem['variant_pricing-from']['from'][0] * $discountedPrice;
        }

        if (isset($data['date_range_pricing']) && is_array($data['date_range_pricing']) && ! empty($data['date_range_pricing']) && isset($data['date_range_pricing'][0]) && $data['date_range_pricing'][0] !== null) {
            // Extract start and end dates from the first date interval

            $dateRange = $data['date_range_pricing'][0];
            [$startDate, $endDate] = explode(' to ', $dateRange);

            // Convert date strings to DateTime objects for comparison
            $currentDate = new DateTime; // Current date/time
            $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
            $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

            // Check if the current date/time is within the specified date interval
            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                // Assuming $lastItem is your array containing the pricing information
                $unitPrice = $data['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                // Calculate the total price based on quantity and unit price
                $variantPricing = $unitPrice;

                if ($data['discount_type'][0] == 'percent') {
                    $percent = $data['discount_percentage'][0];
                    if ($percent) {

                        // Calculate the discount amount based on the given percentage
                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                        $discountAmount = ($variantPricing * $discountPercent) / 100;

                        // Calculate the discounted price
                        $discountedPrice = $variantPricing - $discountAmount;

                    }
                } elseif ($data['discount_type'][0] == 'amount') {
                    // Calculate the discount amount based on the given amount
                    $amount = $data['discount_amount'][0];

                    if ($amount) {
                        $discountAmount = $amount;
                        // Calculate the discounted price
                        $discountedPrice = $variantPricing - $discountAmount;

                    }

                }
            }
        }
        if (isset($discountedPrice) && $discountedPrice > 0 && isset($data['from'][0])) {
            $totalDiscount = $data['from'][0] * $discountedPrice;
        }
        // Prepare detailed product data
        $detailedProduct = [
            'name' => $data['name'],
            'brand' => $brand ? $brand->name : '',
            'unit' => $data['unit'],
            'description' => $data['description'],
            'short_description' => $data['short_description'],

            'main_photos' => $lastItem['storedFilePaths'] ?? $storedFilePaths, // Add stored file paths to the detailed product data
            // 'quantity' => isset($data['from'][0]) ? $data['from'][0] : "" ,
            // 'price' => isset($data['unit_price'][0]) ? $data['unit_price'][0] : "",
            // 'quantity' => isset($fromPrice) ? $fromPrice  : $data['from'][0] ,
            // 'price' => isset($unitPrice) ? $unitPrice  : $data['unit_price'][0],
            // 'total' => $total,
            'quantity' => $lastItem['variant_pricing-from']['from'][0] ?? $data['from'][0] ?? '',
            'price' => $lastItem['variant_pricing-from']['unit_price'][0] ?? $data['unit_price'][0] ?? '',
            'total' => $totalDiscount ?? (isset($lastItem['variant_pricing-from']['from'][0]) && isset($lastItem['variant_pricing-from']['unit_price'][0]) ? $lastItem['variant_pricing-from']['from'][0] * $lastItem['variant_pricing-from']['unit_price'][0] : $total),
            'max' => $max ?? 1,
            'min' => $min ?? 1,
            'general_attributes' => $attributesArray,
            'attributes' => $attributes ?? [],
            'description' => $data['description'],
            'from' => $data['from'] ?? [],
            'to' => $data['to'] ?? [],
            'unit_price' => $data['unit_price'] ?? [],
            'variations' => $variations,
            'variationId' => $variationId ?? null,
            'lastItem' => $lastItem ?? [],
            'catalog' => false,
            'video_provider' => $data['video_provider'],
            'getYoutubeVideoId' => $getYoutubeVideoId ?? null,
            'getVimeoVideoId' => $getVimeoVideoId ?? null,
            'discountedPrice' => $discountedPrice ?? null,
            'totalDiscount' => $totalDiscount ?? null,
            'date_range_pricing' => $data['date_range_pricing'] ?? null,
            'discount_type' => $data['discount_type'] ?? null,
            'discount_percentage' => $data['discount_percentage'],
            'discount_amount' => $data['discount_amount'],

        ];

        return $detailedProduct;
    }

    private function saveMainPhotos($photos)
    {

        $storedFilePaths = [];

        foreach ($photos as $photo) {
            // Generate a unique filename
            $filename = uniqid('main_photo_').'.'.$photo->getClientOriginalExtension();

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
        if (! $previewData) {
            return redirect()->back()->withErrors('No preview data found.');
        }

        // Extract all variables required for the view
        extract($previewData);

        return view('frontend.product_details.preview', compact('previewData'));
    }

    public function updatePricePreview(Request $request) {}

    public function ProductCheckedAttributes(Request $request) {}
}
