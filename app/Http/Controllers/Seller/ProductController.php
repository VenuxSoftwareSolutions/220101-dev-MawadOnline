<?php

namespace App\Http\Controllers\Seller;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\BusinessInformation;
use App\Models\Category;
use App\Models\Color;
use App\Models\ColorGroup;
use App\Models\ColorGroupColor;
use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Models\ProductAttributeValues;
use App\Models\ProductCategory;
use App\Models\Review;
use App\Models\Shipper;
use App\Models\ShippersArea;
use App\Models\Shipping;
use App\Models\Shop;
use App\Models\StockSummary;
use App\Models\Tour;
use App\Models\Unity;
use App\Models\UploadProducts;
use App\Models\Warehouse;
use App\Rules\NoPricingOverlap;
use App\Services\ProductFlashDealService;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\ProductTaxService;
use App\Services\ProductUploadsService;
use Artisan;
use Auth;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

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
        seller_lease_creation($user = Auth::user());

        $search = null;
        $products = Product::where('user_id', Auth::user()->owner_id)->where(function ($query) {
            $query->where('is_draft', '=', 1)
                ->where('parent_id', 0)
                ->orWhere(function ($query) {
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
        $tour_steps = Tour::orderBy('step_number')->get();

        return view('seller.product.products.index', compact('products', 'search', 'tour_steps'));
    }

    public function delete_image(Request $request)
    {
        $image = UploadProducts::find($request->id);
        if ($image != null) {
            if (file_exists(public_path($image->path))) {
                unlink(public_path($image->path));
            }

            $image->delete();

            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
            ]);
        }

    }

    public function delete_pricing(Request $request)
    {
        $pricing = PricingConfiguration::find($request->id);
        if ($pricing != null) {
            $pricing->delete();

            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
            ]);
        }
    }

    public function create(Request $request)
    {
        // if (addon_is_activated('seller_subscription')) {
        //     if (!seller_package_validity_check()) {
        //         flash(translate('Please upgrade your package.'))->warning();
        //         return back();
        //     }
        // }

        $vat_user = BusinessInformation::where('user_id', Auth::user()->owner_id)->first();
        $categories = Category::where('level', 1)
            ->with('childrenCategories')
            ->get();
        //dd($categories);

        $shippers = Shipper::all();
        $supported_shippers = [];
        if (count($shippers) > 0) {
            foreach ($shippers as $shipper) {
                $shipper_areas = ShippersArea::where('shipper_id', $shipper->id)->get();

                if (count($shipper_areas) > 0) {
                    foreach ($shipper_areas as $area) {
                        $warhouses = Warehouse::where('user_id', Auth::user()->owner_id)->where('emirate_id', $area->emirate_id)->where('area_id', $area->area_id)->get();
                        if (count($warhouses) > 0) {
                            if (! array_key_exists($shipper->id, $supported_shippers)) {
                                $supported_shippers[$shipper->id] = $shipper;
                            }
                        }
                    }
                }

            }
        }

        return view('seller.product.products.create', compact('categories', 'vat_user', 'supported_shippers'));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        $product = $this->productService->store($request->except([
            'photosThumbnail', 'main_photos', 'product', 'documents',
            'document_names', '_token', 'sku', 'choice', 'tax_id',
            'tax', 'tax_type', 'flash_deal_id', 'flash_discount',
            'flash_discount_type',
        ]));

        $request->merge(['product_id' => $product->id]);

        //Product categories
        if ($product->is_parent == 1) {
            $products = Product::where('parent_id', $product->id)->get();
            if (count($products) > 0) {
                foreach ($products as $child) {
                    $child->categories()->attach($request->parent_id);
                }
            }
        }

        $product->categories()->attach($request->parent_id);

        //Upload documents, images and thumbnails
        if ($request->document_names) {
            $data['document_names'] = $request->document_names;
            $data['documents'] = $request->documents;
            $data['product'] = $product;
            $data['main_photos'] = $request->main_photos;
            $data['photosThumbnail'] = $request->photosThumbnail;
            $update = false;
            $this->productUploadsService->store_uploads($data, $update);
        }

        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        if ($product->is_draft == 1) {
            return redirect()->route('seller.products.edit', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]);
        } else {
            if ($product->stock_after_create) {
                return redirect()->route('seller.stocks.index');
            } else {
                return redirect()->route('seller.products');
            }
        }
    }

    public function store_draft(Request $request)
    {
        //dd($request->all());
        $parent = Product::find($request->product_id);
        if ($parent != null) {
            $product = $this->productService->draft($request->except([
                'category_ids', 'photosThumbnail', 'main_photos', 'product', 'documents', 'document_names', '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type',
            ]), $parent);

            //Product categories
            if ($product->is_parent == 1) {
                $products = Product::where('parent_id', $product->id)->get();
                if (count($products) > 0) {
                    foreach ($products as $child) {
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
            $update = false;
            $this->productUploadsService->store_uploads($data, $update);

            flash(translate('Product has been inserted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            if ($product->is_draft == 1) {
                return redirect()->route('seller.products.edit', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]);
            } else {
                return redirect()->route('seller.products');
            }

        } else {
            return redirect()->back();
        }

    }

    public function getAttributeCategorie(Request $request)
    {
        $current_categorie = Category::find($request->id);
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

        $attributes_ids = [];
        $attributes = [];
        $html = '';
        $html_attributes_generale = '';
        if (count($parents) > 0) {
            $attributes_ids = DB::table('categories_has_attributes')->whereIn('category_id', $parents)->pluck('attribute_id')->toArray();
            if (count($attributes_ids) > 0) {
                $attributes = Attribute::whereIn('id', $attributes_ids)->get();
                if (count($attributes) > 0) {
                    $html .= '<select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple disabled>';
                    foreach ($attributes as $key => $attribute) {
                        $html .= "<option  value='".$attribute->id."'>".$attribute->getTranslation('name').'</option>';
                        $html_attributes_generale .= '<div class="row attribute-variant-'.$attribute->id.' mb-3">
                        <label class="col-md-2 col-from-label">'.$attribute->getTranslation('name').'</label>';

                        switch ($attribute->type_value) {
                            case 'text':
                                $html_attributes_generale .= '<div class="col-md-10">
                                            <input type="text" class="form-control attributes" name="attribute_generale-'.$attribute->id.'">
                                        </div>';
                                break;
                            case 'list':
                                $values = $attribute->attribute_values_list(app()->getLocale());
                                $options = '<div class="col-md-10"><select class="form-control" data-live-search="true" data-selected-text-format="count" name="attribute_generale-'.$attribute->id.'">';
                                foreach ($values as $key => $value) {
                                    $options .= "<option  value='".$value->id."'>".$value->value.'</option>';
                                }
                                $options .= '</select></div>';
                                $html_attributes_generale .= $options;
                                break;
                            case 'color':
                                $colors = Color::orderBy('name', 'asc')->get();
                                $html_attributes_generale .= '<div class="col-md-10">
                                <select class="form-control attributes aiz-selectpicker" name="attribute_generale-'.$attribute->id.'[]" data-type="color" data-live-search="true" data-selected-text-format="count" multiple>';
                                foreach ($colors as $key => $color) {
                                    $groups_ids = ColorGroupColor::where('color_id', $color->id)->pluck('color_group_id')->toArray();
                                    $groups = [];
                                    if (count($groups_ids) > 0) {
                                        $groups = ColorGroup::whereIn('id', $groups_ids)->pluck('name')->toArray();
                                    }
                                    if (count($groups) > 0) {
                                        $names = implode(' ', $groups);
                                        $html_attributes_generale .= '<option value="'.$color->code.'" data-content="<span><span class=\'size-15px d-inline-block mr-2 rounded border\' style=\'background:'.$color->code.'\'></span><span>'.$color->name.'<span style=\'display:none;\'>'.$names.'</span>'.'</span></span>"></option>';
                                    } else {
                                        $html_attributes_generale .= '<option value="'.$color->code.'" data-content="<span><span class=\'size-15px d-inline-block mr-2 rounded border\' style=\'background:'.$color->code.'\'></span><span>'.$color->name.'</span></span>"></option>';
                                    }

                                }
                                $html_attributes_generale .= '</select></div>';
                                break;
                            case 'numeric':
                                $units_id = $attribute->get_attribute_units();
                                $units = Unity::whereIn('id', $units_id)->get();
                                $options = '<select class="form-control attributes-units" name="unit_attribute_generale-'.$attribute->id.'" data-live-search="true" data-selected-text-format="count">';
                                foreach ($units as $key => $unit) {
                                    $options .= "<option  value='".$unit->id."'>".$unit->name.'</option>';
                                }
                                $options .= '</select>';
                                $html_attributes_generale .= '<div class="col-md-10"><div class="row"><div class="col-6">
                                            <input type="number" step="0.1" class="form-control attributes" name="attribute_generale-'.$attribute->id.'"></div><div class="col-6">'.$options.'
                                        </div></div></div>';
                                break;
                            case 'boolean':
                                $html_attributes_generale .= '<div class="col-md-10" style="padding-top: 10px">
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
                    $html .= '</select>';
                }
            }
        }

        return response()->json([
            'html' => $html,
            'html_attributes_generale' => $html_attributes_generale,
        ]);

    }

    public function getAttributes(Request $request)
    {
        if ($request->ids != null) {
            $attributes = Attribute::whereIn('id', $request->ids)->get();
        } else {
            $attributes = [];
        }
        if ($request->selected != null) {
            $attributes_not_selected = array_diff($request->allValues, $request->selected);
        } else {
            $attributes_not_selected = array_diff($request->allValues, []);
        }

        $attributes_generale = Attribute::whereIn('id', $attributes_not_selected)->get();

        $html = '';
        $html_attributes_generale = '';
        if (count($attributes) > 0) {
            foreach ($attributes as $attribute) {
                $html .= '<div class="row mb-3 attribute-variant-'.$attribute->id.'">
                <label class="col-md-2 col-from-label">'.translate($attribute->getTranslation('name')).'</label>';

                switch ($attribute->type_value) {
                    case 'text':
                        $html .= '<div class="col-md-10">
                                    <input type="text" class="form-control attributes" data-id_attributes="'.$attribute->id.'">
                                </div>';
                        break;
                    case 'list':
                        $values = $attribute->attribute_values_list(app()->getLocale());
                        $options = '<div class="col-md-10"><select class="form-control attributes" data-id_attributes="'.$attribute->id.'" data-live-search="true" data-selected-text-format="count" >';
                        foreach ($values as $key => $value) {
                            $options .= "<option  value='".$value->id."'>".$value->value.'</option>';
                        }
                        $options .= '</select></div>';
                        $html .= $options;
                        break;
                    case 'color':
                        $colors = Color::orderBy('name', 'asc')->get();
                        $html .= '<div class="col-md-10">
                        <select class="form-control attributes color aiz-selectpicker" data-id_attributes="'.$attribute->id.'" data-type="color" data-live-search="true" data-selected-text-format="count" multiple>';
                        foreach ($colors as $key => $color) {
                            $groups_ids = ColorGroupColor::where('color_id', $color->id)->pluck('color_group_id')->toArray();
                            $groups = [];

                            if (count($groups_ids) > 0) {
                                $groups = ColorGroup::whereIn('id', $groups_ids)->pluck('name')->toArray();
                            }

                            if (count($groups) > 0) {
                                $names = implode(' ', $groups);
                                $html .= '<option value="'.$color->code.'" data-content="<span><span class=\'size-15px d-inline-block mr-2 rounded border\' style=\'background:'.$color->code.'\'></span><span>'.$color->name.'<span style=\'display:none;\'>'.$names.'</span>'.'</span></span>"></option>';
                            } else {
                                $html .= '<option value="'.$color->code.'" data-content="<span><span class=\'size-15px d-inline-block mr-2 rounded border\' style=\'background:'.$color->code.'\'></span><span>'.$color->name.'</span></span>"></option>';
                            }

                        }
                        $html .= '</select></div>';
                        break;
                    case 'numeric':
                        $units_id = $attribute->get_attribute_units();
                        $units = Unity::whereIn('id', $units_id)->get();
                        $options = '<select class="form-control attributes-units" data-id_attributes="'.$attribute->id.'" data-live-search="true" data-selected-text-format="count">';
                        foreach ($units as $key => $unit) {
                            $options .= "<option  value='".$unit->id."'>".$unit->name.'</option>';
                        }
                        $options .= '</select>';
                        $html .= '<div class="col-md-10"><div class="row"><div class="col-6">
                                    <input type="number" step="0.1" class="form-control attributes" data-id_attributes="'.$attribute->id.'"></div><div class="col-6">'.$options.'
                                </div></div>';
                        break;
                    case 'boolean':
                        $html .= '<div class="col-md-10" style="padding-top: 10px">
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

        if (count($attributes_generale) > 0) {
            foreach ($attributes_generale as $attribute_generale) {
                $html_attributes_generale .= '<div class="row mb-3">
                        <div class="col-md-4 attribute-variant-'.$attribute_generale->id.'">
                            <input type="text" class="form-control" value="'.translate($attribute_generale->getTranslation('name')).'" disabled>
                        </div>';

                switch ($attribute_generale->type_value) {
                    case 'text':
                        $html_attributes_generale .= '<div class="col-md-8 attribute-variant-'.$attribute_generale->id.'">
                                    <input type="text" class="form-control attributes" name="attribute_generale-'.$attribute_generale->id.'">
                                </div>';
                        break;
                    case 'list':
                        $values = $attribute_generale->attribute_values_list(app()->getLocale());
                        $options = '<div class="col-md-8 attribute-variant-'.$attribute_generale->id.'"><select class="form-control" data-live-search="true" data-selected-text-format="count" name="attribute_generale-'.$attribute_generale->id.'">';
                        foreach ($values as $key => $value) {
                            $options .= "<option  value='".$value->id."'>".$value->value.'</option>';
                        }
                        $options .= '</select></div>';
                        $html_attributes_generale .= $options;
                        break;
                    case 'color':
                        $colors = Color::orderBy('name', 'asc')->get();
                        $html_attributes_generale .= '<div class="col-md-8 attribute-variant-'.$attribute_generale->id.'">
                        <select class="form-control attributes aiz-selectpicker" name="attribute_generale-'.$attribute_generale->id.'" data-type="color" data-live-search="true" data-selected-text-format="count">';
                        foreach ($colors as $key => $color) {
                            $html_attributes_generale .= '<option value="'.$color->code.'" data-content="<span><span class=\'size-15px d-inline-block mr-2 rounded border\' style=\'background:'.$color->code.'\'></span><span>'.$color->name.'</span></span>"></option>';
                        }
                        $html_attributes_generale .= '</select></div>';
                        break;
                    case 'numeric':
                        $units_id = $attribute_generale->get_attribute_units();
                        $units = Unity::whereIn('id', $units_id)->get();
                        $options = '<select class="form-control attributes-units" name="unit_attribute_generale-'.$attribute_generale->id.'" data-live-search="true" data-selected-text-format="count">';
                        foreach ($units as $key => $unit) {
                            $options .= "<option  value='".$unit->id."'>".$unit->name.'</option>';
                        }
                        $options .= '</select>';
                        $html_attributes_generale .= '<div class="col-md-8 attribute-variant-'.$attribute_generale->id.'"><div class="row"><div class="col-6">
                                    <input type="number" step="0.1" class="form-control attributes" name="attribute_generale-'.$attribute_generale->id.'"></div><div class="col-6">'.$options.'
                                </div></div>';
                        break;
                    case 'boolean':
                        $html_attributes_generale .= '<div class="col-md-8 attribute-variant-'.$attribute_generale->id.'" style="padding-top: 10px">
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
            'html_attributes_generale' => $html_attributes_generale,
        ]);
    }

    public function edit(Request $request, $id)
    {
        //$product = Product::findOrFail($id);

        $product = Product::find($id);
        if (Auth::user()->id != $product->user_id) {
            abort(404);
        }
        $colors = Color::orderBy('name', 'asc')->get();
        $product_category = ProductCategory::where('product_id', $id)->first();
        $vat_user = BusinessInformation::where('user_id', Auth::user()->owner_id)->first();
        if ($product_category != null) {
            $categorie = Category::find($product_category->category_id);
        } else {
            $categorie = null;
        }

        $attributes = [];
        $children = [];
        $children_ids = [];
        $variants_attributes = [];
        $general_attributes = [];
        $variants_attributes_ids_attributes = [];
        $general_attributes_ids_attributes = [];
        $chargeable_weight = 0;
        $chargeable_weight_sample = 0;

        $shippers = Shipper::all();
        $supported_shippers = [];
        if (count($shippers) > 0) {
            foreach ($shippers as $shipper) {
                $shipper_areas = ShippersArea::where('shipper_id', $shipper->id)->get();

                if (count($shipper_areas) > 0) {
                    foreach ($shipper_areas as $area) {
                        $warehouses = Warehouse::where('user_id', Auth::user()->owner_id)->where('emirate_id', $area->emirate_id)->where('area_id', $area->area_id)->get();
                        if (count($warehouses) > 0) {
                            if (! array_key_exists($shipper->id, $supported_shippers)) {
                                $supported_shippers[$shipper->id] = $shipper;
                            }
                        }
                    }
                }

            }
        }

        if ($product != null) {
            if ($product->activate_third_party == 1) {
                $volumetric_weight = getProductVolumetricWeight($product->length, $product->height, $product->weight);
                if ($volumetric_weight > $product->weight) {
                    $chargeable_weight = $volumetric_weight;
                } else {
                    $chargeable_weight = $product->weight;
                }

                if ($product->unit_weight == 'pounds') {
                    $chargeable_weight *= 2.2;
                }
            }

            if ($product->activate_third_party_sample == 1) {
                $volumetric_weight_sample = getProductVolumetricWeight($product->length_sample, $product->height_sample, $product->width_sample);
                if ($volumetric_weight_sample > $product->package_weight_sample) {
                    $chargeable_weight_sample = $volumetric_weight_sample;
                } else {
                    $chargeable_weight_sample = $product->package_weight_sample;
                }

                if ($product->unit_weight == 'pounds') {
                    $chargeable_weight_sample *= 2.2;
                }
            }

            if ($product->is_parent == 1) {
                $children = Product::where('parent_id', $id)->get();
                $children_ids = Product::where('parent_id', $id)->pluck('id')->toArray();
                $variants_attributes = ProductAttributeValues::whereIn('id_products', $children_ids)->where('is_variant', 1)->get();

                $variants_attributes_ids_attributes = ProductAttributeValues::whereIn('id_products', $children_ids)->where('is_variant', 1)->pluck('id_attribute')->toArray();

            }
            $general_attributes = ProductAttributeValues::where('id_products', $id)->where('is_general', 1)->get();
            $general_attributes_ids_attributes = ProductAttributeValues::where('id_products', $id)->where('is_general', 1)->pluck('id_attribute')->toArray();
            $data_general_attributes = [];

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
                }
            }

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
                        $all_general_attributes = Attribute::whereIn('id', $attributes_ids)->whereNotIn('id', $variants_attributes_ids_attributes)->whereNotIn('id', $general_attributes_ids_attributes)->get();
                        if (count($all_general_attributes) > 0) {
                            foreach ($all_general_attributes as $attribute) {
                                $data_general_attributes[$attribute->id] = $attribute;
                                if ($attribute->type_value == 'color') {
                                    if (array_key_exists($attribute->id, $data_general_attributes)) {
                                        $data_general_attributes[$attribute->id] = [null];
                                    } else {
                                        $data_general_attributes[$attribute->id] = [null];
                                    }
                                } else {
                                    $data_general_attributes[$attribute->id] = $attribute;
                                }

                                if (! in_array($attribute->id, $general_attributes_ids_attributes)) {
                                    array_push($general_attributes_ids_attributes, $attribute->id);
                                }
                            }
                        }

                    }
                }
            }

            if ($product->is_draft == 1) {
                return view('seller.product.products.draft', [
                    'product' => $product,
                    'vat_user' => $vat_user,
                    'categorie' => $categorie,
                    'product_category' => $product_category,
                    'attributes' => $attributes,
                    'childrens' => $children,
                    'childrens_ids' => $children_ids,
                    'variants_attributes' => $variants_attributes,
                    'variants_attributes_ids_attributes' => $variants_attributes_ids_attributes,
                    'general_attributes_ids_attributes' => $general_attributes_ids_attributes,
                    'general_attributes' => $data_general_attributes,
                    'colors' => $colors,
                    'supported_shippers' => $supported_shippers,
                    'chargeable_weight' => $chargeable_weight,
                    'chargeable_weight_sample' => $chargeable_weight_sample,
                ]);
            } else {
                return view('seller.product.products.edit', [
                    'product' => $product,
                    'vat_user' => $vat_user,
                    'categorie' => $categorie,
                    'product_category' => $product_category,
                    'attributes' => $attributes,
                    'childrens' => $children,
                    'childrens_ids' => $children_ids,
                    'variants_attributes' => $variants_attributes,
                    'variants_attributes_ids_attributes' => $variants_attributes_ids_attributes,
                    'general_attributes_ids_attributes' => $general_attributes_ids_attributes,
                    'general_attributes' => $data_general_attributes,
                    'colors' => $colors,
                    'supported_shippers' => $supported_shippers,
                    'chargeable_weight' => $chargeable_weight,
                    'chargeable_weight_sample' => $chargeable_weight_sample,
                ]);
            }
        } else {
            abort(404);
        }

        return view('seller.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function delete_shipping(Request $request)
    {
        $shipping = Shipping::find($request->id);
        if ($shipping != null) {
            $shipping->delete();

            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
            ]);
        }
    }

    public function validateRequest(Request $request)
    {
        $is_shipping = true;

        $rules = [
            'from' => [
                'required', 'array',
                new NoPricingOverlap($request->input('from'), $request->input('to')),
            ],
            'to' => ['required', 'array'],
            'from.*' => 'numeric',
            'to.*' => 'numeric',
            'from_shipping' => [
                'required', 'array',
                new NoPricingOverlap($request->input('from_shipping'), $request->input('to_shipping'), $is_shipping),
            ],
            'to_shipping' => ['required', 'array'],
            'from_shipping.*' => 'numeric',
            'to_shipping.*' => 'numeric',
        ];

        $variantsPricing = collect($request->all())
            ->filter(function ($value, $key) {
                return preg_match('/^variant_pricing-from\d+$/', $key);
            });

        if ($variantsPricing->count() > 0) {
            foreach ($variantsPricing as $key => $variant) {
                $index = str_replace('variant_pricing-from', '', $key);
                $from = $variant['from'];
                $to = $variant['to'];

                $rules[$key] = [
                    new NoPricingOverlap($from, $to, false, $index)
                ];
            }
        }

        $request->validate($rules);
    }

    public function update(Request $request)
    {
        $this->validateRequest($request);

        $parent = Product::find($request->product_id);

        if ($parent != null) {
            $product = $this->productService->update($request->except([
                'photosThumbnail', 'main_photos', 'product',
                'documents', 'document_names', '_token', 'sku',
                'choice', 'tax_id', 'tax', 'tax_type',
                'flash_deal_id', 'flash_discount', 'flash_discount_type',
            ]), $parent);

            //Product categories
            if ($product->is_parent == 1) {
                $products = Product::where('parent_id', $product->id)->get();
                if (count($products) > 0) {
                    foreach ($products as $child) {
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
            $update = true;
            $this->productUploadsService->store_uploads($data, $update);

            flash(translate('Product has been updated successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return redirect()->route('seller.products');
        } else {
            flash(translate('Error while updating product'))->error();
            return redirect()->back();
        }
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
                $data = [];
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = (new CombinationService)->generate_combination($options);

        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function delete_variant(Request $request)
    {
        $product = Product::find($request->id_variant);
        if ($product != null) {
            $uploads = UploadProducts::where('id_product', $request->id_variant)->get();
            if (count($uploads) > 0) {
                if (file_exists(public_path('/upload_products/Product-'.$request->id_variant))) {
                    File::deleteDirectory(public_path('/upload_products/Product-'.$request->id_variant));
                }

                UploadProducts::where('id_product', $request->id_variant)->delete();
            }

            $pricing = PricingConfiguration::where('id_products', $request->id_variant)->delete();
            $attributes = ProductAttributeValues::where('id_products', $request->id_variant)->delete();
            $product_to_delete = Product::where('id', $request->id_variant)->delete();

            return response()->json([
                'status' => 'done',
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
            ]);
        }
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
                $data = [];
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = (new CombinationService)->generate_combination($options);

        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
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

    public function updatePublished(Request $request)
    {
        $product = Product::find($request->id);
        if ($product != null) {
            $product->published = $request->status;
            $product->save();

            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
            ]);
        }
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
            if (! seller_package_validity_check()) {
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

        //     if (Auth::user()->id != $product->user_id) {
        //         flash(translate('This product is not yours.'))->warning();
        //         return back();
        //     }

        //     $product->product_translations()->delete();
        //     $product->categories()->detach();
        //     $product->stocks()->delete();
        //     $product->taxes()->delete();

        //     if (Product::destroy($id)) {
        //         Cart::where('product_id', $id)->delete();
        //         Wishlist::where('product_id', $id)->delete();

        //         flash(translate('Product has been deleted successfully'))->success();

        //         Artisan::call('view:clear');
        //         Artisan::call('cache:clear');

        //         return back();
        //     } else {
        //         flash(translate('Something went wrong'))->error();
        //         return back();
        //     }

        if (Auth::user()->id == $product->user_id) {
            if (count($product->getChildrenProducts()) > 0) {
                foreach ($product->getChildrenProducts() as $children) {
                    $children->delete();
                }
            }
            Product::destroy($id);

            return back();
        } else {
            abort(404);
        }
    }

    public function bulk_product_delete(Request $request)
    {
        try {
            if ($request->id) {
                foreach ($request->id as $product_id) {
                    $this->destroy($product_id);
                }
            }

            return 1;
        } catch(\Exception $e) {
            \Log::error("Error while bulk delete products, with message: {$e->getMessage()}");
            return response()->json(["error" => true, "message" => __("There's an error")], 500);
        }
    }

    private function extractAttributes($variants)
    {
        $attributes = [];

        foreach ($variants as $attributeId => $values) {
            // Remove duplicates from values
            $uniqueValues = array_unique($values);

            $attribute = Attribute::find($attributeId);
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

    public function generateSku($name)
    {
        // Convert the name to a slug (lowercase and hyphenated)
        $sku = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

        // Optionally, you can add a unique identifier, like a timestamp or an incrementing number
        return $sku;
    }

    public function prepareDetailedProductData($data)
    {

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
        $outStock = false;
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
        // $produitVariationImage=false ;
        // // dd($numeric_keys) ;
        // foreach ($numeric_keys as $numeric_key) {
        //     // Access corresponding values
        //     if (isset($data["photos_variant-$numeric_key"]) && is_array($data["photos_variant-$numeric_key"]) && !$produitVariationImage ) {
        //                 // $storedFilePaths = $this->saveMainPhotos($data["photos_variant-$numeric_key"]);
        //                 $produitVariationImage=true ;

        //     }
        // }
        $storedFilePaths = [];

        // if(!$produitVariationImage) {
        if (isset($data['main_photos']) && is_array($data['main_photos'])) {
            // Process and save main photos
            $storedFilePaths = $this->saveMainPhotos($data['main_photos']);
        }

        if (isset($data['product_id'])) {
            $upload_products_db = UploadProducts::where('id_product', $data['product_id'])->pluck('path')->toArray();
            $upload_products_db = array_filter($upload_products_db, function ($path) {
                return ! strpos($path, 'thumbnails');
            });
            $storedFilePaths = array_merge($storedFilePaths, $upload_products_db);

        }

        // }
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
                if (count($storedFilePaths) > 0) {
                    // If you want to merge main photo paths with variation photo paths
                    $variations[$variationId]['storedFilePaths'] = array_merge(
                        $variations[$variationId]['storedFilePaths'],
                        $storedFilePaths
                    );
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
                $variations[$variationId]['variant_pricing-from']['discount'] = [
                    'type' => $data['variant']['discount_type'][$variationId] ?? null,
                    'amount' => $data['variant']['discount_amount'][$variationId] ?? null,
                    'percentage' => $data['variant']['discount_percentage'][$variationId] ?? null,
                    'date' => $data['variant']['date_range_pricing'][$variationId] ?? null,
                ];
                $variations[$variationId]['storedFilePaths'] = $upload_products_db;

                if (count($storedFilePaths) > 0) {
                    // If you want to merge main photo paths with variation photo paths
                    $variations[$variationId]['storedFilePaths'] = array_merge(
                        $variations[$variationId]['storedFilePaths'],
                        $storedFilePaths
                    );
                }
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

        if (isset($data['variant']['photo']) && is_array($data['variant']['photo'])) {
            foreach ($data['variant']['photo'] as $variationId => $photos) {
                $upload_products_db = $this->saveMainPhotos($photos);
                $productPhotoDb = $variations[$variationId]['storedFilePaths'];

                $variations[$variationId]['storedFilePaths'] = array_merge($productPhotoDb, $upload_products_db);

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

        if (isset($data['variant']['sku'])) {
            foreach ($data['variant']['sku'] as $variationId => $sku) {
                $variations[$variationId]['sku'] = $sku;
            }
        } else {
            foreach ($variations as $keyVar => $variation) {
                if (isset($data['sku-'.$keyVar])) {
                    $variations[$keyVar]['sku'] = $data['sku-'.$keyVar];

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
            // dd($lastItem,$variationId,$variations) ;
            if (isset($lastItem['variant_pricing-from']['to'], $lastItem['variant_pricing-from']['from']) &&
            ! empty($lastItem['variant_pricing-from']['to']) && ! empty($lastItem['variant_pricing-from']['from'])) {
                $max = max($lastItem['variant_pricing-from']['to']);
                $min = min($lastItem['variant_pricing-from']['from']);
                if (isset($data['product_id'])) {
                    $product_stock = StockSummary::where('variant_id', $variationId)->sum('current_total_quantity');
                    if ($product_stock < $min) {
                        $outStock = true;
                    }

                }

            }
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
                if (isset($data['product_id'])) {
                    $product_stock = StockSummary::where('variant_id', $data['product_id'])->sum('current_total_quantity');

                    if ($product_stock < $min) {
                        $outStock = true;
                    }
                }

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
        if (count($variations) == 0) {
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
        }

        $shop = Shop::where('user_id', Auth::user()->owner_id)->first();
        if ($shop != null) {
            $shop_name = $shop->name;
        } else {
            $shop_name = null;
        }

        if (isset($data['product_id']) && ! empty($data['product_id'])) {
            // Retrieve existing documents for the given product ID
            $existingDocuments = UploadProducts::where('id_product', $data['product_id'])
                ->where('type', 'documents')
                ->get();
        } else {
            // If product_id is not set or is empty, return an empty collection
            $existingDocuments = collect();
        }

        $newDocuments = [];
        // Check if the documents were uploaded
        if (isset($data['documents'])) {
            foreach ($data['documents'] as $document) {

                // Generate a unique name for the file using the current timestamp
                $fileName = time().'_'.$document->getClientOriginalExtension();

                // Create a path for storing the document
                $path = $document->storeAs('/upload_products/Product-temp/documents', $fileName);

                // Add document info to the newDocuments array
                $newDocuments[] = [

                    'path' => '/'.$path,
                    'extension' => $document->extension(),
                    'document_name' => $document->getClientOriginalName(),
                    'type' => 'documents',
                    // You can add created_at and updated_at if needed
                ];
            }
        }

        // Combine existing documents with new ones
        $existingDocumentsArray = $existingDocuments->toArray();
        $allDocuments = collect($existingDocumentsArray)->merge($newDocuments);

        // dd($data , $variationId) ;
        // Prepare detailed product data
        $detailedProduct = [
            'shop_name' => $shop_name,
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
            'variationId' => isset($data['product_id']) && isset($variationId) ? $variationId : null,
            // 'variationId' => $variationId ?? null,

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
            'percent' => $percent ?? null,
            'product_id' => $data['product_id'] ?? null,
            'category' => isset($data['parent_id']) && ! empty($data['parent_id']) ? optional(Category::find($data['parent_id']))->name : null,
            'sku' => $lastItem['sku'] ?? $data['product_sk'] ?? null,
            'tags' => $data['tags'],

            'ratingPercentages' => 0,
            'documents' => $allDocuments,
            'previewCreate' => true,
            'unit_of_sale' => $data['unit'] ?? null,
            'outStock' => $outStock,

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

        // Add a flag to indicate that this is a preview
        $isPreview = true;

        return view('frontend.product_details', compact('previewData', 'isPreview'));
        // return view('frontend.product_details.preview', compact('previewData'));
    }

    public function updatePricePreview(Request $request)
    {
        return $this->productService->updatePrice($request);
    }

    private function renderStarRating($rating, $maxRating = 5)
    {
        $fullStar = "<i class = 'las la-star active'></i>";
        $halfStar = "<i class = 'las la-star half'></i>";
        $emptyStar = "<i class = 'las la-star'></i>";
        $rating = $rating <= $maxRating ? $rating : $maxRating;

        $fullStarCount = (int) $rating;
        $halfStarCount = ceil($rating) - $fullStarCount;
        $emptyStarCount = $maxRating - $fullStarCount - $halfStarCount;

        $html = str_repeat($fullStar, $fullStarCount);
        $html .= str_repeat($halfStar, $halfStarCount);
        $html .= str_repeat($emptyStar, $emptyStarCount);

        return $html;
    }

    public function ProductCheckedAttributes(Request $request)
    {

        $outStock = false;

        $data = $request->session()->get('productPreviewData', null);

        $variations = $data['detailedProduct']['variations'];

        $checkedAttributes = $request->checkedAttributes; // Checked attribute and its value
        // dd($variations,$checkedAttributes) ;
        $matchedImages = [];
        $availableAttributes = [];
        $anyMatched = false;
        $pickedAnyVariation = false;
        $maximum = 1;
        $minimum = 1;
        // $totalDiscount = 0 ;
        $discountedPrice = 0;
        foreach ($variations as $variationIdKey => $variation) {

            $matchesCheckedAttributes = true;

            // Check if the variation matches the checked attributes
            // foreach ($checkedAttributes as $attributeId => $value) {
            //     if (!isset($variation[$attributeId]) || $variation[$attributeId] !== $value) {
            //         $matchesCheckedAttributes = false;
            //         break;
            //     }
            // }
            foreach ($checkedAttributes as $attributeId => $value) {
                $valueString = '';
                if (isset($variation[$attributeId]) && is_array($variation[$attributeId])) {
                    // Join array elements with a hyphen
                    $valueString = implode('-', $variation[$attributeId]);

                }

                // If the attribute doesn't exist in variation or the values don't match
                if (! isset($variation[$attributeId]) ||
                    (is_array($variation[$attributeId]) && $valueString !== $value) ||
                    (! is_array($variation[$attributeId]) && $variation[$attributeId] !== $value)) {

                    $matchesCheckedAttributes = false;
                    break;
                }
            }

            // If the variation matches the checked attributes, collect other attributes
            if ($matchesCheckedAttributes) {
                $anyMatched = true;
                if (isset($variation['storedFilePaths']) && is_array($variation['storedFilePaths']) && count($matchedImages) == 0) {
                    foreach ($variation['storedFilePaths'] as $image) {
                        $matchedImages[] = $image;
                    }

                }
                if ($pickedAnyVariation == false) {
                    $variationId = $variationIdKey;
                    if (isset($data['detailedProduct']['product_id'])) {

                        $reviewStats = Review::where('product_id', $variationId)
                            ->selectRaw('COUNT(*) as total, SUM(rating) as sum')
                            ->first();

                        $totalRating = $reviewStats->total;
                        $totalSum = $reviewStats->sum;

                        if ($totalRating > 0) {
                            $avgRating = $totalSum / $totalRating;
                            $renderStarRating = $this->renderStarRating($totalSum / $totalRating);
                        } else {

                            $renderStarRating = $this->renderStarRating(0);
                        }
                    }
                    $sku = $variation['sku'] ?? null;
                    $quantity = $variation['variant_pricing-from']['from'][0] ?? '';
                    $price = $variation['variant_pricing-from']['unit_price'][0] ?? '';
                    $total = isset($variation['variant_pricing-from']['from'][0]) && isset($variation['variant_pricing-from']['unit_price'][0]) ? $variation['variant_pricing-from']['from'][0] * $variation['variant_pricing-from']['unit_price'][0] : '';

                    if (isset($variation['variant_pricing-from']['discount']['date']) && is_array($variation['variant_pricing-from']['discount']['date']) && ! empty($variation['variant_pricing-from']['discount']['date'][0])) {
                        // Extract start and end dates from the first date interval

                        $dateRange = $variation['variant_pricing-from']['discount']['date'][0];
                        [$startDate, $endDate] = explode(' to ', $dateRange);

                        // Convert date strings to DateTime objects for comparison
                        $currentDate = new DateTime; // Current date/time
                        $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                        $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                        // Check if the current date/time is within the specified date interval
                        if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                            // Assuming $lastItem is your array containing the pricing information
                            $unitPrice = $variation['variant_pricing-from']['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                            // Calculate the total price based on quantity and unit price
                            $variantPricing = $unitPrice;

                            if ($variation['variant_pricing-from']['discount']['type'][0] == 'percent') {
                                $percent = $variation['variant_pricing-from']['discount']['percentage'][0];
                                if ($percent) {

                                    // Calculate the discount amount based on the given percentage
                                    $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                    $discountAmount = ($variantPricing * $discountPercent) / 100;

                                    // Calculate the discounted price
                                    $discountedPrice = $variantPricing - $discountAmount;

                                }
                            } elseif ($variation['variant_pricing-from']['discount']['type'][0] == 'amount') {
                                // Calculate the discount amount based on the given amount
                                $amount = $variation['variant_pricing-from']['discount']['amount'][0];

                                if ($amount) {
                                    $discountAmount = $amount;
                                    // Calculate the discounted price
                                    $discountedPrice = $variantPricing - $discountAmount;

                                }

                            }
                        }
                    }
                    if (isset($discountedPrice) && $discountedPrice > 0 && isset($variation['variant_pricing-from']['from'][0])) {
                        $totalDiscount = $variation['variant_pricing-from']['from'][0] * $discountedPrice;
                    }

                    // Convert array values to integers
                    $valuesFrom = array_map('intval', $variation['variant_pricing-from']['from']);
                    $valuesMax = array_map('intval', $variation['variant_pricing-from']['to']);
                    // Get the maximum value
                    if (! empty($valuesMax)) {
                        $maximum = max($valuesMax);
                    }
                    // Get the minimum value
                    if (! empty($valuesFrom)) {
                        $minimum = min($valuesFrom);
                    }
                    $pickedAnyVariation = true;
                }

                // foreach ($variation as $attributeId => $value) {
                //     if (!isset($checkedAttributes[$attributeId])) {
                //         if (!isset($availableAttributes[$attributeId])) {
                //             $availableAttributes[$attributeId] = [];
                //         }
                //         if (!in_array($value, $availableAttributes[$attributeId])) {
                //             $availableAttributes[$attributeId][] = $value;
                //         }
                //     }
                // }
                // dd($variation) ;
                foreach ($variation as $attributeId => $value) {
                    if (! isset($checkedAttributes[$attributeId])) {
                        if (! isset($availableAttributes[$attributeId])) {
                            $availableAttributes[$attributeId] = [];
                        }
                        // Check if $value is an array and does not contain any arrays inside it
                        if (is_array($value) && ! array_filter($value, 'is_array')) {
                            // Join array elements with a hyphen
                            $valueString = implode('-', $value);
                        } else {
                            $valueString = $value;  // Treat $value as a string if it's not an array
                        }

                        if (! in_array($valueString, $availableAttributes[$attributeId])) {
                            $availableAttributes[$attributeId][] = $valueString;
                        }
                    }
                }
                if (isset($data['detailedProduct']['product_id'])) {
                    $product_stock = StockSummary::where('variant_id', $variationId)->sum('current_total_quantity');
                    if ($product_stock < $minimum) {
                        $outStock = true;
                    }
                }

            }
        }

        if (isset($data['detailedProduct']['catalog'])) {
            $response = [
                'availableAttributes' => $availableAttributes,
                'anyMatched' => $anyMatched,
                'matchedImages' => $matchedImages,
                'variationId' => $variationId ?? null,
                'quantity' => $quantity ?? null,
                'price' => $price ?? null,
                'total' => $totalDiscount ?? $total ?? null,
                'maximum' => $maximum,
                'minimum' => $minimum,
                'discountedPrice' => $discountedPrice ?? null,
                'totalDiscount' => $totalDiscount ?? null,
                'percent' => $percent ?? null,
                'totalRating' => 0,
                'renderStarRating' => $this->renderStarRating(0),
                'avgRating' => 0,
                'sku' => $sku ?? null,
                'outStock' => false,
            ];
        } else {

            $response = [
                'availableAttributes' => $availableAttributes,
                'anyMatched' => $anyMatched,
                'matchedImages' => $matchedImages,
                'variationId' => $variationId ?? null,
                'quantity' => $quantity ?? null,
                'price' => $price ?? null,
                'total' => $totalDiscount ?? $total ?? null,
                'maximum' => $maximum,
                'minimum' => $minimum,
                'discountedPrice' => $discountedPrice ?? null,
                'totalDiscount' => $totalDiscount ?? null,
                'percent' => $percent ?? null,
                'totalRating' => $totalRating ?? 0,
                'renderStarRating' => $renderStarRating ?? $this->renderStarRating(0),
                'avgRating' => $avgRating ?? 0,
                'sku' => $sku ?? null,
                'outStock' => $outStock,
            ];
        }
        // dd($availableAttributes) ;

        // Add matchesCheckedAttributes to the response

        // return response()->json($availableAttributes);
        return response()->json($response);

    }
}
