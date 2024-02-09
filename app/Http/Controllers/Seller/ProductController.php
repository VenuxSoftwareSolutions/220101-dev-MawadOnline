<?php

namespace App\Http\Controllers\Seller;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Color;
use App\Models\Category;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Unity;
use App\Models\ProductTax;
use App\Models\BusinessInformation;
use App\Models\ProductTranslation;
use App\Models\ProductAttributeValues;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\Attribute;
use App\Notifications\ShopProductNotification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Combinations;
use Artisan;
use Auth;
use Str;

use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use App\Services\ProductUploadsService;
use App\Services\ProductPricingService;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    protected $productService;
    protected $productCategoryService;
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
    }

    public function index(Request $request)
    {
        $search = null;
        // $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->where('auction_product', 0)->where('wholesale_product', 0)->orderBy('created_at', 'desc');
        // if ($request->has('search')) {
        //     $search = $request->search;
        //     $products = $products->where('name', 'like', '%' . $search . '%');
        // }
        // $products = $products->paginate(10);
        $products = Product::where('user_id', Auth::user()->id)->where(function ($query) {
            $query->where('is_draft', '=', 1)
            ->where('parent_id', 0);
        })->orWhere(function ($query) {
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

        return view('seller.product.products.index', compact('products', 'search'));
    }

    public function draft($id){
        $product = Product::find($id);
        $colors = Color::orderBy('name', 'asc')->get();
        $product_category = ProductCategory::where('product_id', $id)->first();
        $vat_user = BusinessInformation::where('user_id', Auth::user()->id)->first();
        $categories = Category::where('level', 1)
            ->with('childrenCategories')
            ->get();
        $attributes = [];
        $childrens = [];
        $childrens_ids = [];
        $variants_attributes = [];
        $general_attributes = [];
        $variants_attributes_ids_attributes = [];
        $general_attributes_ids_attributes = [];
        if($product->is_parent = 1){
            $childrens = Product::where('parent_id', $id)->get();
            $childrens_ids = Product::where('parent_id', $id)->pluck('id')->toArray();
            $variants_attributes = ProductAttributeValues::whereIn('id_products', $childrens_ids)->where('is_variant', 1)->get();
            $general_attributes = ProductAttributeValues::where('id_products', $id)->where('is_general', 1)->get();
            $variants_attributes_ids_attributes = ProductAttributeValues::whereIn('id_products', $childrens_ids)->where('is_variant', 1)->pluck('id_attribute')->toArray();
            $general_attributes_ids_attributes = ProductAttributeValues::where('id_products', $id)->where('is_general', 1)->pluck('id_attribute')->toArray();
            $data_general_attributes = [];
            if(count($general_attributes) > 0){
                foreach ($general_attributes as $general_attribute){
                    $data_general_attributes[$general_attribute->id_attribute] = $general_attribute;
                }
            }
        }
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
        
        return view('seller.product.products.draft', [
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
            'colors' => $colors
        ]);
    }

    public function create(Request $request)
    {
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return back();
            }
        }

        $vat_user = BusinessInformation::where('user_id', Auth::user()->id)->first();
        $categories = Category::where('level', 1)
            ->with('childrenCategories')
            ->get();
            //dd($categories);
        return view('seller.product.products.create', compact('categories', 'vat_user'));
    }

    public function store(Request $request)
    {
       // dd($request->all());
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return redirect()->route('seller.products');
            }
        }

        $product = $this->productService->store($request->except([
            'photosThumbnail', 'main_photos', 'product', 'documents', 'document_names', '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]));

        $request->merge(['product_id' => $product->id]);

        //Product categories
        if($product->is_parent == 1){
            $products = Product::where('parent_id', $product->id)->get();
            if(count($products) > 0){
                foreach($products as $child){
                    $child->categories()->attach($request->category_ids);
                }
            }
        }
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
        // $this->productPricingService->store([
        //     "from" => $request->from,
        //     "to" => $request->to,
        //     "unit_price" => $request->unit_price,
        //     "date_range_pricing" => $request->date_range_pricing,
        //     "discount_type" => $request->discount_type,
        //     "discount_amount" => $request->discount_amount,
        //     "discount_percentage" => $request->discount_percentage,
        //     "product" => $product
        // ]);

        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang', 'name', 'unit', 'description', 'product_id'
        ]));

        if (get_setting('product_approve_by_admin') == 1) {
            $users = User::findMany([auth()->user()->id, User::where('user_type', 'admin')->first()->id]);
            Notification::send($users, new ShopProductNotification('physical', $product));
        }

        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('seller.products');
    }

    public function getAttributeCategorie(Request $request){
        $current_categorie = Category::find($request->id);
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

        $attributes_ids = [];
        $attributes = [];
        $html = "";
        $html_attributes_generale = "";
        if(count($parents) > 0){
            $attributes_ids = DB::table('categories_has_attributes')->whereIn('category_id', $parents)->pluck('attribute_id')->toArray();
            if(count($attributes_ids) > 0){
                $attributes = Attribute::whereIn('id',$attributes_ids)->get();
                if(count($attributes) > 0){
                    $html .= '<select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple disabled>';
                    foreach ($attributes as $key=>$attribute){
                        $html .= "<option  value='".$attribute->id."'>". $attribute->getTranslation('name') ."</option>";
                        $html_attributes_generale .= '<div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="'.translate($attribute->getTranslation('name')).'" disabled>
                        </div>';

                        switch ($attribute->type_value) {
                            case "text":
                                $html_attributes_generale .= '<div class="col-md-8">
                                            <input type="text" class="form-control attributes" name="attribute_generale-'.$attribute->id.'">
                                        </div>';
                                break;
                            case "list":
                                $values = $attribute->attribute_values_list(app()->getLocale());
                                $options = '<div class="col-md-8"><select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="attribute_generale-'.$attribute_generale->id.'">';
                                foreach ($values as $key=>$value){
                                    $options .= "<option  value='".$value->id."'>". $value->value ."</option>";
                                }
                                $options .= "</select></div>";
                                $html_attributes_generale .= $options;
                                break;
                            case "color":
                                $colors = Color::orderBy('name', 'asc')->get();
                                $html_attributes_generale .= '<div class="col-md-8">
                                <select class="form-control attributes aiz-selectpicker" name="attribute_generale-'.$attribute->id.'" data-type="color" data-live-search="true" data-selected-text-format="count">';
                                    foreach ($colors as $key => $color){
                                        $html_attributes_generale .= '<option value="' . $color->code . '" data-content="<span><span class=\'size-15px d-inline-block mr-2 rounded border\' style=\'background:' . $color->code . '\'></span><span>' . $color->name . '</span></span>"></option>';
                                    }
                                $html_attributes_generale .= '</select></div>';
                                break;
                            case "numeric":
                                $units_id = $attribute->get_attribute_units();
                                $units = Unity::whereIn('id', $units_id)->get();
                                $options = '<select class="form-control attributes-units aiz-selectpicker" name="unit_attribute_generale-'.$attribute->id.'" data-live-search="true" data-selected-text-format="count">';
                                foreach ($units as $key=>$unit){
                                    $options .= "<option  value='".$unit->id."'>". $unit->name ."</option>";
                                }
                                $options .= "</select>";
                                $html_attributes_generale .= '<div class="col-md-8"><div class="row"><div class="col-6">
                                            <input type="number" class="form-control attributes" name="attribute_generale-'.$attribute->id.'"></div><div class="col-6">'.$options.'
                                        </div></div>';
                                break;
                            case "boolean":
                                $html_attributes_generale .= '<div class="col-md-8" style="padding-top: 10px">
                                            <label style="margin-right: 15px">
                                                <input type="radio" class="attributes" name="attribute_generale-'.$attribute->id.'" name="boolean" value="yes">Yes
                                            </label>
                                            <label>
                                                <input type="radio" class="attributes" name="attribute_generale-'.$attribute->id.'" name="boolean" value="no"> No
                                            </label>
                                        </div>';
                                break;
                        }

                        $html_attributes_generale .= '</div>';

                    }
                    $html .= "</select>";
                }
            }
        }

        return response()->json([
            "html" => $html,
            'html_attributes_generale' => $html_attributes_generale
        ]);

    }

    public function getAttributes(Request $request){
        if($request->ids != null){
            $attributes = Attribute::whereIn('id', $request->ids)->get();
            $attributes_not_selected = array_diff($request->allValues, $request->ids);
        }else{
            $attributes = [];
            $attributes_not_selected = array_diff($request->allValues, []);
        }


        $attributes_generale = Attribute::whereIn('id', $attributes_not_selected)->get();

        $html = '';
        $html_attributes_generale = '';
        if(count($attributes) > 0){
            foreach($attributes as $attribute){
                $html .= '<div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="'.translate($attribute->getTranslation('name')).'" disabled>
                        </div>';

                switch ($attribute->type_value) {
                    case "text":
                        $html .= '<div class="col-md-8">
                                    <input type="text" class="form-control attributes" data-id_attributes="'.$attribute->id.'">
                                </div>';
                        break;
                    case "list":
                        $values = $attribute->attribute_values_list(app()->getLocale());
                        $options = '<div class="col-md-8"><select class="form-control attributes aiz-selectpicker" data-id_attributes="'.$attribute->id.'" data-live-search="true" data-selected-text-format="count" >';
                        foreach ($values as $key=>$value){
                            $options .= "<option  value='".$value->id."'>". $value->value ."</option>";
                        }
                        $options .= "</select></div>";
                        $html .= $options;
                        break;
                    case "color":
                        $colors = Color::orderBy('name', 'asc')->get();
                        $html .= '<div class="col-md-8">
                        <select class="form-control attributes color aiz-selectpicker" data-id_attributes="'.$attribute->id.'" data-type="color" data-live-search="true" data-selected-text-format="count">';
                            foreach ($colors as $key => $color){
                                $html .= '<option value="' . $color->code . '" data-content="<span><span class=\'size-15px d-inline-block mr-2 rounded border\' style=\'background:' . $color->code . '\'></span><span>' . $color->name . '</span></span>"></option>';
                            }
                        $html .= '</select></div>';
                        break;
                    case "numeric":
                        $units_id = $attribute->get_attribute_units();
                        $units = Unity::whereIn('id', $units_id)->get();
                        $options = '<select class="form-control attributes-units aiz-selectpicker" data-id_attributes="'.$attribute->id.'" data-live-search="true" data-selected-text-format="count">';
                        foreach ($units as $key=>$unit){
                            $options .= "<option  value='".$unit->id."'>". $unit->name ."</option>";
                        }
                        $options .= "</select>";
                        $html .= '<div class="col-md-8"><div class="row"><div class="col-6">
                                    <input type="number" class="form-control attributes" data-id_attributes="'.$attribute->id.'"></div><div class="col-6">'.$options.'
                                </div></div>';
                        break;
                    case "boolean":
                        $html .= '<div class="col-md-8" style="padding-top: 10px">
                                    <label style="margin-right: 15px">
                                        <input type="radio" class="attributes" data-id_attributes="'.$attribute->id.'" name="boolean" value="yes">Yes
                                    </label>
                                    <label>
                                        <input type="radio" class="attributes" data-id_attributes="'.$attribute->id.'" name="boolean" value="no"> No
                                    </label>
                                </div>';
                        break;
                }

                $html .= '</div>';
            }
        }

        if(count($attributes_generale) > 0){
            foreach($attributes_generale as $attribute_generale){
                $html_attributes_generale .= '<div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="'.translate($attribute_generale->getTranslation('name')).'" disabled>
                        </div>';

                switch ($attribute_generale->type_value) {
                    case "text":
                        $html_attributes_generale .= '<div class="col-md-8">
                                    <input type="text" class="form-control attributes" name="attribute_generale-'.$attribute_generale->id.'">
                                </div>';
                        break;
                    case "list":
                        $values = $attribute_generale->attribute_values_list(app()->getLocale());
                        $options = '<div class="col-md-8"><select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="attribute_generale-'.$attribute_generale->id.'">';
                        foreach ($values as $key=>$value){
                            $options .= "<option  value='".$value->id."'>". $value->value ."</option>";
                        }
                        $options .= "</select></div>";
                        $html_attributes_generale .= $options;
                        break;
                    case "color":
                        $colors = Color::orderBy('name', 'asc')->get();
                        $html_attributes_generale .= '<div class="col-md-8">
                        <select class="form-control attributes aiz-selectpicker" name="attribute_generale-'.$attribute_generale->id.'" data-type="color" data-live-search="true" data-selected-text-format="count">';
                            foreach ($colors as $key => $color){
                                $html_attributes_generale .= '<option value="' . $color->code . '" data-content="<span><span class=\'size-15px d-inline-block mr-2 rounded border\' style=\'background:' . $color->code . '\'></span><span>' . $color->name . '</span></span>"></option>';
                            }
                        $html_attributes_generale .= '</select></div>';
                        break;
                    case "numeric":
                        $units_id = $attribute_generale->get_attribute_units();
                        $units = Unity::whereIn('id', $units_id)->get();
                        $options = '<select class="form-control attributes-units aiz-selectpicker" name="unit_attribute_generale-'.$attribute_generale->id.'" data-live-search="true" data-selected-text-format="count">';
                        foreach ($units as $key=>$unit){
                            $options .= "<option  value='".$unit->id."'>". $unit->name ."</option>";
                        }
                        $options .= "</select>";
                        $html_attributes_generale .= '<div class="col-md-8"><div class="row"><div class="col-6">
                                    <input type="number" class="form-control attributes" name="attribute_generale-'.$attribute_generale->id.'"></div><div class="col-6">'.$options.'
                                </div></div>';
                        break;
                    case "boolean":
                        $html_attributes_generale .= '<div class="col-md-8" style="padding-top: 10px">
                                    <label style="margin-right: 15px">
                                        <input type="radio" class="attributes" name="attribute_generale-'.$attribute_generale->id.'" name="boolean" value="yes">Yes
                                    </label>
                                    <label>
                                        <input type="radio" class="attributes" name="attribute_generale-'.$attribute_generale->id.'" name="boolean" value="no"> No
                                    </label>
                                </div>';
                        break;
                }

                $html_attributes_generale .= '</div>';
            }
        }

        return response()->json([
            'html' => $html,
            'html_attributes_generale' => $html_attributes_generale
        ]);
    }

    public function edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('seller.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

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

        //VAT & Tax
        if ($request->tax_id) {
            $product->taxes()->delete();
            $request->merge(['product_id' => $product->id]);
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

        return back();
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
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
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
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
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

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;
        if (addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (!seller_package_validity_check()) {
                return 2;
            }
        }
        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->seller_featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function duplicate($id)
    {
        $product = Product::find($id);

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }

        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return back();
            }
        }

        //Product
        $product_new = $this->productService->product_duplicate_store($product);

        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);

        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);

        flash(translate('Product has been duplicated successfully'))->success();
        return redirect()->route('seller.products');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }

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
}
