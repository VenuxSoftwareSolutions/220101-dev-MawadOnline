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
use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Models\Unity;
use App\Models\Brand;
use App\Models\ProductTax;
use App\Models\BusinessInformation;
use App\Models\ProductTranslation;
use App\Models\ProductAttributeValues;
use App\Models\Wishlist;
use App\Models\UploadProducts;
use App\Models\User;
use App\Models\Attribute;
use App\Notifications\ShopProductNotification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Combinations;
use Artisan;
use Auth;
use Str;
use Illuminate\Support\Facades\File;

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

        // $this->middleware(['permission:seller_show_product'])->only('index');
        // $this->middleware(['permission:seller_create_product'])->only('create');
        // $this->middleware(['permission:seller_edit_product'])->only('edit');
        // $this->middleware(['permission:seller_destroy_product'])->only('destroy');
    }

    public function index(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where(function ($query) {
            $query->where('is_draft', '=', 1)
            ->where('parent_id', 0)
            ->orWhere(function ($query) {
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
        return view('seller.product.products.index', compact('products', 'search'));
    }

    public function delete_image(Request $request){
        $image = UploadProducts::find($request->id);
        if($image != null){
            if(file_exists(public_path($image->path))){
                unlink(public_path($image->path));
            }

            $image->delete();

            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'failed'
            ]);
        }

    }

    public function delete_pricing(Request $request){
        $pricing = PricingConfiguration::find($request->id);
        if($pricing != null){
            $pricing->delete();

            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'failed'
            ]);
        }
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
        //dd($request->all());
        $product = $this->productService->store($request->except([
            'parent_id', 'photosThumbnail', 'main_photos', 'product', 'documents', 'document_names', '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]));

        $request->merge(['product_id' => $product->id]);

        //Product categories
        if($product->is_parent == 1){
            $products = Product::where('parent_id', $product->id)->get();
            if(count($products) > 0){
                foreach($products as $child){
                    $child->categories()->attach($request->parent_id);
                }
            }
        }
        $product->categories()->attach($request->parent_id);

        //Upload documents, images and thumbnails
        if($request->document_names){
            $data['document_names'] = $request->document_names;
            $data['documents'] = $request->documents;
            $data['product'] = $product;
            $data['main_photos'] = $request->main_photos;
            $data['photosThumbnail'] = $request->photosThumbnail;
            $this->productUploadsService->store_uploads($data);
        }

        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('seller.products');
    }

    public function store_draft(Request $request){
        //dd($request->all());
        $parent = Product::find($request->product_id);
        if($parent != null){
            $product = $this->productService->draft($request->except([
                'parent_id', 'category_ids', 'photosThumbnail', 'main_photos', 'product', 'documents', 'document_names', '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
            ]), $parent);

            //Product categories
            if($product->is_parent == 1){
                $products = Product::where('parent_id', $product->id)->get();
                if(count($products) > 0){
                    foreach($products as $child){
                        $child->categories()->attach($request->parent_id);
                    }
                }
            }
            $product->categories()->attach($request->parent_id);

            //Upload documents, images and thumbnails
            $data['document_names'] = $request->document_names;
            $data['documents'] = $request->documents;
            $data['product'] = $product;
            $data['main_photos'] = $request->main_photos;
            $data['photosThumbnail'] = $request->photosThumbnail;
            $data['old_documents'] = $request->old_documents;
            $data['old_document_names'] = $request->old_document_names;
            $this->productUploadsService->store_uploads($data);


            flash(translate('Product has been inserted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return redirect()->route('seller.products');

        }else{
            return redirect()->back();
        }

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
                        $html_attributes_generale .= '<div class="row attribute-variant-'. $attribute->id .' mb-3">
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
                                $options = '<div class="col-md-8"><select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="attribute_generale-'.$attribute->id.'">';
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
                                            <input type="number" step="0.1" class="form-control attributes" name="attribute_generale-'.$attribute->id.'"></div><div class="col-6">'.$options.'
                                        </div></div></div>';
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
        }else{
            $attributes = [];
        }
        if($request->selected != null){
            $attributes_not_selected = array_diff($request->allValues, $request->selected);
        }else{
            $attributes_not_selected = array_diff($request->allValues, []);
        }

        $attributes_generale = Attribute::whereIn('id', $attributes_not_selected)->get();

        $html = '';
        $html_attributes_generale = '';
        if(count($attributes) > 0){
            foreach($attributes as $attribute){
                $html .= '<div class="row mb-3 attribute-variant-'. $attribute->id .'">
                        <div class="col-md-4">
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
                                    <input type="number" step="0.1" class="form-control attributes" data-id_attributes="'.$attribute->id.'"></div><div class="col-6">'.$options.'
                                </div></div>';
                        break;
                    case "boolean":
                        $html .= '<div class="col-md-8" style="padding-top: 10px">
                                    <label style="margin-right: 15px">
                                        <input type="radio" class="attributes" data-id_attributes="'.$attribute->id.'" value="yes">Yes
                                    </label>
                                    <label>
                                        <input type="radio" class="attributes" data-id_attributes="'.$attribute->id.'" value="no"> No
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
                        <div class="col-md-4 attribute-variant-'. $attribute_generale->id .'">
                            <input type="text" class="form-control" value="'.translate($attribute_generale->getTranslation('name')).'" disabled>
                        </div>';

                switch ($attribute_generale->type_value) {
                    case "text":
                        $html_attributes_generale .= '<div class="col-md-8 attribute-variant-'. $attribute_generale->id .'">
                                    <input type="text" class="form-control attributes" name="attribute_generale-'.$attribute_generale->id.'">
                                </div>';
                        break;
                    case "list":
                        $values = $attribute_generale->attribute_values_list(app()->getLocale());
                        $options = '<div class="col-md-8 attribute-variant-'. $attribute_generale->id .'"><select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="attribute_generale-'.$attribute_generale->id.'">';
                        foreach ($values as $key=>$value){
                            $options .= "<option  value='".$value->id."'>". $value->value ."</option>";
                        }
                        $options .= "</select></div>";
                        $html_attributes_generale .= $options;
                        break;
                    case "color":
                        $colors = Color::orderBy('name', 'asc')->get();
                        $html_attributes_generale .= '<div class="col-md-8 attribute-variant-'. $attribute_generale->id .'">
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
                        $html_attributes_generale .= '<div class="col-md-8 attribute-variant-'. $attribute_generale->id .'"><div class="row"><div class="col-6">
                                    <input type="number" step="0.1" class="form-control attributes" name="attribute_generale-'.$attribute_generale->id.'"></div><div class="col-6">'.$options.'
                                </div></div>';
                        break;
                    case "boolean":
                        $html_attributes_generale .= '<div class="col-md-8 attribute-variant-'. $attribute_generale->id .'" style="padding-top: 10px">
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

        $product = Product::find($id);
        $colors = Color::orderBy('name', 'asc')->get();
        $product_category = ProductCategory::where('product_id', $id)->first();
        $vat_user = BusinessInformation::where('user_id', Auth::user()->id)->first();
        if($product_category != null){
            $categorie =  Category::find($product_category->category_id);
        }else{
            $categorie=null;
        }

        $attributes = [];
        $childrens = [];
        $childrens_ids = [];
        $variants_attributes = [];
        $general_attributes = [];
        $variants_attributes_ids_attributes = [];
        $general_attributes_ids_attributes = [];
        if($product != null){
            if($product->is_parent == 1){
                $childrens = Product::where('parent_id', $id)->get();
                $childrens_ids = Product::where('parent_id', $id)->pluck('id')->toArray();
                $variants_attributes = ProductAttributeValues::whereIn('id_products', $childrens_ids)->where('is_variant', 1)->get();

                $variants_attributes_ids_attributes = ProductAttributeValues::whereIn('id_products', $childrens_ids)->where('is_variant', 1)->pluck('id_attribute')->toArray();

            }
            $general_attributes = ProductAttributeValues::where('id_products', $id)->where('is_general', 1)->get();
            $general_attributes_ids_attributes = ProductAttributeValues::where('id_products', $id)->where('is_general', 1)->pluck('id_attribute')->toArray();
            $data_general_attributes = [];
            if(count($general_attributes) > 0){
                foreach ($general_attributes as $general_attribute){
                    $data_general_attributes[$general_attribute->id_attribute] = $general_attribute;
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
                        $all_general_attributes = Attribute::whereIn('id',$attributes_ids)->whereNotIn('id', $variants_attributes_ids_attributes)->whereNotIn('id', $general_attributes_ids_attributes)->get();
                        if(count($all_general_attributes) > 0){
                            foreach($all_general_attributes as $attribute){
                                $data_general_attributes[$attribute->id] = $attribute;
                                if (!in_array($attribute->id, $general_attributes_ids_attributes)) {
                                    array_push($general_attributes_ids_attributes, $attribute->id);
                                }
                            }
                        }
                    }
                }
            }

            if($product->is_draft == 1){
                return view('seller.product.products.draft', [
                    'product' => $product,
                    'vat_user' => $vat_user,
                    'categorie' => $categorie,
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
            }else{
                return view('seller.product.products.edit', [
                    'product' => $product,
                    'vat_user' => $vat_user,
                    'categorie' => $categorie,
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
        }else{
            abort(404);
        }
        return view('seller.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function update(Request $request)
    {
        //dd($request->all());
        $parent = Product::find($request->product_id);
        if($parent != null){
            $product = $this->productService->update($request->except([
                'parent_id', 'photosThumbnail', 'main_photos', 'product', 'documents', 'document_names', '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
            ]), $parent);

            //Product categories
            if($product->is_parent == 1){
                $products = Product::where('parent_id', $product->id)->get();
                if(count($products) > 0){
                    foreach($products as $child){
                        $child->categories()->attach($request->parent_id);
                    }
                }
            }
            $product->categories()->sync($request->parent_id);

            //Upload documents, images and thumbnails
            $data['document_names'] = $request->document_names;
            $data['documents'] = $request->documents;
            $data['product'] = $product;
            $data['main_photos'] = $request->main_photos;
            $data['photosThumbnail'] = $request->photosThumbnail;
            $data['old_documents'] = $request->old_documents;
            $data['old_document_names'] = $request->old_document_names;
            $this->productUploadsService->store_uploads($data);


            flash(translate('Product has been updated successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return redirect()->route('seller.products');

        }else{
            return redirect()->back();
        }


        //flash(translate('Product has been updated successfully'))->success();

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

    public function delete_variant(Request $request){
        $product = Product::find($request->id_variant);
        if($product != null){
            $uploads = UploadProducts::where('id_product', $request->id_variant)->get();
            if(count($uploads) > 0){
                if(file_exists(public_path('/upload_products/Product-'.$request->id_variant))){
                    File::deleteDirectory(public_path('/upload_products/Product-'.$request->id_variant));
                }

                UploadProducts::where('id_product', $request->id_variant)->delete();
            }

            $pricing = PricingConfiguration::where('id_products', $request->id_variant)->delete();
            $attributes = ProductAttributeValues::where('id_products', $request->id_variant)->delete();
            $product_to_delete = Product::where('id', $request->id_variant)->delete();

            return response()->json([
                'status' => 'done'
            ]);
        }else{
            return response()->json([
                'status' => 'failed'
            ]);
        }
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
    private function extractAttributes($variants)
    {
        $attributes = [];

        foreach ($variants as $attributeId => $values) {
            // Remove duplicates from values
            $uniqueValues = array_unique($values);

            $attribute = Attribute::find($attributeId) ;
            // Add attribute ID and unique values to the list
            if ($attribute) {
                $attributes[$attribute->getTranslation('name')] = $uniqueValues;
            }
            // $attributes[$attributeId] = $uniqueValues;
        }

        return $attributes;
    }


    public function tempStore(Request $request)
    {
        // return response()->json([$request->all()]);

           // Assuming you have a method to prepare or simulate data needed for the preview
        $detailedProduct = $this->prepareDetailedProductData($request->all());

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

        $total = isset($data['from'][0]) && isset($data['unit_price'][0]) ? $data['from'][0] * $data['unit_price'][0] : "";
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
                        $storedFilePaths = $this->saveMainPhotos($data["photos_variant-$numeric_key"]);
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
                $attributesArray[$attribute->id] = $value;
            }
         }
        }
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
           if (strpos($key, 'attributes') === 0) {
               // Extract the attribute number and variation id
               $parts = explode('-', $key);
               $variationId = $parts[2];
               $attributeId = $parts[1];
               // Initialize the variation if not exists
               if (!isset($variations[$variationId])) {
                   $variations[$variationId] = [];
               }
               // Add attribute to variation
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

               } elseif (isset($data["variant-pricing-$variationId"]) && $data["variant-pricing-$variationId"] == 1 ){
                    $variations[$variationId]['variant_pricing-from']['from'] =$data['from'] ?? []  ;
                    $variations[$variationId]['variant_pricing-from']['to'] =$data['to']  ?? [] ;
                    $variations[$variationId]['variant_pricing-from']['unit_price'] =$data['unit_price'] ?? []  ;
               }

           }
       }

    //    dd($data['variant']['attributes']) ;
       if (isset($data['variant']['attributes']))
        foreach ($data['variant']['attributes'] as $variationId=>$variations_db) {
            foreach ($variations_db as $attributeId=>$attribute) {
                if (!isset($variations[$variationId])) {
                    $variations[$variationId] = [];
                }
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
             $this->getYoutubeVideoId($data["video_link"]) ;
        }
        if (is_array($variations) && !empty($variations)) {
        $lastItem  = end($variations);
        $variationId = key($variations); // Get the key (variation ID) of the last item
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
            'total' => isset($lastItem['variant_pricing-from']['from'][0]) && isset($lastItem['variant_pricing-from']['unit_price'][0]) ? $lastItem['variant_pricing-from']['from'][0] * $lastItem['variant_pricing-from']['unit_price'][0] : $total,

            'general_attributes' =>$attributesArray,
            'attributes' =>$attributes ?? [] ,
            'description' =>$data['description'] ,
            'from' =>$data['from'] ?? [] ,
            'to' =>$data['to']  ?? [],
            'unit_price' =>$data['unit_price'] ?? [] ,
            'variations' =>$variations,
            'variationId' => $variationId ?? null
        ];




        return $detailedProduct;
    }

    private function saveMainPhotos($photos){

        $storedFilePaths = [];

        foreach ($photos as $photo) {
            // Generate a unique filename
            $filename = uniqid('main_photo_') . '.' . $photo->getClientOriginalExtension();

            // Store the file to the desired location (e.g., public storage)
            $storedPath = $photo->storeAs('main_photos', $filename);

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


        // Iterate through the ranges
        $unitPrice = null;
        if($request->variationId != null) {

            foreach ($variations[$request->variationId]['variant_pricing-from']['from'] as $index => $from) {
                $to = $variations[$request->variationId]['variant_pricing-from']['to'][$index];

                if ($qty >= $from && $qty <= $to) {
                    $unitPrice = $variations[$request->variationId]['variant_pricing-from']['unit_price'][$index];

                    break; // Stop iterating once the range is found
                }
            }

        }
        else {
            foreach ($data['detailedProduct']['from'] as $index => $from) {
                $to = $data['detailedProduct']['to'][$index];
                if ($qty >= $from && $qty <= $to) {
                    $unitPrice = $data['detailedProduct']['unit_price'][$index];
                    break; // Stop iterating once the range is found
                }
            }
        }


        $total=$qty*$unitPrice ;

     // Return the unit price as JSON response
     return response()->json(['unit_price' => $unitPrice,"qty"=>$qty,'total'=>$total]);
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
            'total' => $total ?? null

        ];
        // return response()->json($availableAttributes);
        return response()->json($response);

    }

}
