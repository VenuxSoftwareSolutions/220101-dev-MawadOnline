@extends('seller.layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<style>
    .table th{
        font-size: 12px !important;
    }

    .swal2-icon .swal2-icon-content {
        display: flex;
        align-items: center;
        font-size: 0.75em !important;
    }
    .button-container {
        position: fixed;
        top: 12%;
        right: 2%;
        z-index: 5;
    }
    .preview-button {
        background-color: #cb774b !important; /* Green background */
        border: none;
        color: white;
        padding: 15px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;

        cursor: pointer;
        border-radius: 8px;
    }

</style>

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Add Your Product') }}</h1>
            </div>
        </div>
    </div>

    <!-- Error Meassages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- <button type="button" onclick="submitForm()">Preview Product</button> --}}
    <div class="button-container">
        <button type="button" class="preview-button" onclick="submitForm()">Preview Product</button>
    </div>


    <form class="" action="{{route('seller.products.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
        @csrf
        <div class="row gutters-5">
            <div class="col-lg-12">
                @csrf
                {{-- Bloc Product Information --}}
                <input type="hidden" id="published_after_approve" value="0" name="published_modal">
                <input type="hidden" id="create_stock" value="0" name="create_stock">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Information')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Product Name')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ translate('Product Name') }}" >
                            </div>
                        </div>
                        <div class="form-group row" id="brand">
                            <label class="col-md-3 col-from-label">{{translate('Brand')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">
                                    <option value="">{{ translate('Select Brand') }}</option>
                                    @foreach (\App\Models\Brand::all() as $brand)
                                    <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>{{ $brand->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Unit of Sale')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="unit" value="{{ old('unit') }}" placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Country of origin')}}</label>
                            <div class="col-md-8">
                                <div class="form-item">
                                    <input id="country_selector" type="text">
                                    <label for="country_selector" style="display:none;">Select a country here...</label>
                                </div>
                                <div class="form-item" style="display:none;">
                                    <input type="text" id="country_selector_code" name="country_code" data-countrycodeinput="1" readonly="readonly" placeholder="Selected country code will appear here" />
                                    <label for="country_selector_code">...and the selected country code will be updated here</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Manufacturer')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="manufacturer" value="{{ old('manufacturer') }}" placeholder="Manufacturer" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Tags')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control aiz-tag-input" name="tags[]" placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                <small class="text-muted">{{translate('This is used for search. Input those words by which cutomer can find this product.')}}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Short description')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="short_description" id="short_description"></textarea>
                                <div id="charCountShortDescription">Remaining characters: 512</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Show Stock Quantity')}}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="stock_visibility_state" value="1" checked="checked">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Published')}}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="published" checked value="1">
                                    <span></span>
                                </label>
                            </div>
                        </div> --}}

                        @if (addon_is_activated('pos_system'))
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Barcode')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="barcode" value="{{ old('barcode') }}" placeholder="{{ translate('Barcode') }}">
                            </div>
                        </div>
                        @endif

                        @if (addon_is_activated('refund_request'))
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Refundable')}}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="refundable" checked value="1">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                {{-- Bloc Product images --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Images')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Gallery Images')}} <small>(1280x1280)</small></label>
                            <div class="col-md-8" id="bloc_photos">
                                <input type="file" class="dropify" name="main_photos[]" id="photoUpload" accept=".jpeg, .jpg, .png" multiple />
                                <div id="dropifyUploadedFiles"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(400x400)</small></label>
                            <div class="col-md-8" id="bloc_thumbnails">
                                <input type="file" class="dropify" name="photosThumbnail[]" id="photoUploadThumbnail" accept=".jpeg, .jpg, .png" multiple />
                                <div id="dropifyUploadedFilesThumbnail"></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Pricing configuration --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Default Pricing Configuration')}}</h5>
                    </div>
                    <div class="card-body">
                        {{-- <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="vat" @if($vat_user->vat_registered == 1) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <hr> --}}
                        <div>
                            <table class="table" id="table_pricing_configuration" class="bloc_pricing_configuration_variant">
                                <thead>
                                    <tr>
                                        <th>{{translate('From Quantity')}}</th>
                                        <th>{{translate('To Quantity')}}</th>
                                        <th>{{translate('Unit Price (VAT Exclusive)')}}</th>
                                        <th>{{translate('Discount(Start/End)')}}</th>
                                        <th>{{translate('Discount Type')}}</th>
                                        <th>{{translate('Discount Amount')}}</th>
                                        <th>{{translate('Discount Percentage')}}</th>
                                        <th>{{translate('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody id="bloc_pricing_configuration">
                                    <tr>
                                        <td><input type="number" name="from[]" class="form-control min-qty" id=""></td>
                                        <td><input type="number" name="to[]" class="form-control max-qty" id=""></td>
                                        <td><input type="number" name="unit_price[]" class="form-control unit-price-variant" id=""></td>
                                        <td><input type="text" class="form-control aiz-date-range discount-range" name="date_range_pricing[]" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                        <td>
                                            <select class="form-control discount_type" name="discount_type[]">
                                                <option value="" selected>{{translate('Choose type')}}</option>
                                                <option value="amount" @selected(old('discount_type') == 'amount')>{{translate('Flat')}}</option>
                                                <option value="percent" @selected(old('discount_type') == 'percent')>{{translate('Percent')}}</option>
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control discount_amount" name="discount_amount[]"></td>
                                        <td><input type="number" class="form-control discount_percentage" name="discount_percentage[]"></td>
                                        <td>
                                            <i class="las la-plus btn-add-pricing" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                        </td>
                                    </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>
                {{-- Bloc Default shipping configuration --}}
                <div class="card" id="shipping_configuration_box">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Default Shipping Configuration')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="bloc-default-shipping-style">
                            <h5>{{ translate('Mawadonline 3rd party configuration') }}</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{translate('Do you want to activate MawadOnline 3rd Party ?')}}" disabled>
                                    <small style="color:red">Fill all required fields for shippers to confirm delivery ability.</small>
                                </div>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" id="third_party_activate" name="activate_third_party">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <table class="table" id="table_third_party_configuration" class="bloc_third_configuration_variant">
                                    <thead>
                                        <tr>
                                            <th>{{translate('Length Package (Cm)')}}</th>
                                            <th>{{translate('Width Package (Cm)')}}</th>
                                            <th>{{translate('Height Package (Cm)')}}</th>
                                            <th>{{translate('Weight Package')}}</th>
                                            <th>{{translate('Weight unit')}}</th>
                                            <th>{{translate('Breakable')}}</th>
                                            <th>{{translate('Unit')}}</th>
                                            <th>{{translate('Min')}}</th>
                                            <th>{{translate('Max')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bloc_third_party">
                                        <tr>
                                            <td><input type="number" name="length" class="form-control" id="length" step="0.1" readonly></td>
                                            <td><input type="number" name="width" class="form-control" id="width" step="0.1" readonly></td>
                                            <td><input type="number" name="height" class="form-control" id="height" step="0.1" readonly></td>
                                            <td><input type="number" name="weight" class="form-control" id="weight" step="0.1" readonly></td>
                                            <td>
                                                <select class="form-control calculate" id="weight_unit" name="unit_weight" disabled>
                                                    <option value="kilograms">{{translate('Kilograms')}}</option>
                                                    <option value="pounds">{{translate('Pounds')}}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control calculate" id="breakable" name="breakable" disabled>
                                                    <option value=""></option>
                                                    <option value="yes">{{translate('Yes')}}</option>
                                                    <option value="no">{{translate('No')}}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control calculate" id="unit_third_party" name="unit_third_party" disabled>
                                                    <option value="celsius">{{translate('Celsius')}}</option>
                                                    <option value="kelvin">{{translate('Kelvin')}}</option>
                                                    <option value="fahrenheit">{{translate('Fahrenheit')}}</option>
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control" name="min_third_party" id="min_third_party" step="0.1" readonly></td>
                                            <td><input type="number" class="form-control" name="max_third_party" id="max_third_party" step="0.1" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="result_calculate_third_party">
    
                                </div>
                            </div>
                        </div>
                        <div class="bloc-default-shipping-style" style="margin-top: 22px;">
                            <h5>{{ translate('Shipping configuration') }}</h5>
                            <div>
                                <table class="table" id="table_shipping_configuration" class="bloc_shipping_configuration_variant">
                                    <thead>
                                        <tr>
                                            <th>{{translate('From Quantity')}}</th>
                                            <th>{{translate('To Quantity')}}</th>
                                            <th>{{translate('Shipper')}}</th>
                                            <th>{{translate('Estimated Order Preparation Days')}}</th>
                                            <th>{{translate('Estimated Shipping Days')}}</th>
                                            <th>{{translate('Paid by')}}</th>
                                            {{-- <th>{{translate('VAT')}}</th> --}}
                                            <th>{{translate('Shipping Charge')}}</th>
                                            <th>{{translate('Flat-rate Amount')}}</th>
                                            <th>{{translate('Charge per Unit of Sale')}}</th>
                                            <th>{{translate('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bloc_shipping_configuration">
                                        <tr>
                                            <td><input type="number" name="from_shipping[]" class="form-control min-qty-shipping" id=""></td>
                                            <td><input type="number" name="to_shipping[]" class="form-control max-qty-shipping" id=""></td>
                                            <td>
                                                <select multiple class="form-control shipper" name="shipper[0][]">
                                                    <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                                    <option value="third_party" @selected(old('shipper') == 'third_party')>{{translate('MawadOnline 3rd Party Shippers')}}</option>
                                                </select>
                                            </td>
    
                                            <div id="results"></div>
    
                                            <td><input type="number" class="form-control estimated_order" name="estimated_order[]"></td>
                                            <td><input type="number" class="form-control estimated_shipping" name="estimated_shipping[]"></td>
                                            <td>
                                                <select class="form-control paid" name="paid[]">
                                                    <option value="" selected>{{translate('Choose option')}}</option>
                                                    <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                                    <option value="buyer" @selected(old('shipper') == 'buyer')>{{translate('Buyer')}}</option>
                                                </select>
                                            </td>
                                            {{-- <td>
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input value="1" type="checkbox" class="vat_shipping" name="vat_shipping[]" @if($vat_user->vat_registered == 1) checked @endif>
                                                    <span></span>
                                                </label>
                                            </td> --}}
                                            <td>
                                                <select class="form-control shipping_charge" name="shipping_charge[]">
                                                    <option value="" selected>{{translate('Choose shipping charge')}}</option>
                                                    <option value="flat" @selected(old('shipping_charge') == 'flat')>{{translate('Flat-rate regardless of quantity')}}</option>
                                                    <option value="charging" @selected(old('shipping_charge') == 'charging')>{{translate('Charging per Unit of Sale')}}</option>
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control flat_rate_shipping" name="flat_rate_shipping[]" readonly></td>
                                            <td><input type="number" class="form-control charge_per_unit_shipping" name="charge_per_unit_shipping[]" readonly></td>
                                            <td>
                                                <i class="las la-plus btn-add-shipping" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Sample pricing configuration --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Default Sample Pricing Configuration')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            {{-- <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="vat_sample" @if($vat_user->vat_registered == 1) checked @endif>
                                    <span></span>
                                </label>
                            </div> --}}
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('Sample description')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" name="sample_description"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('Sample price')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="sample_price">
                            </div>
                        </div>
                        <table class="table" id="table_sample_configuration" class="bloc_sample_configuration_variant">
                            <thead>
                                <tr>
                                    <th>{{translate('Shipping-by')}}</th>
                                    <th>{{translate('Estimated Sample Preparation Days')}}</th>
                                    <th>{{translate('Estimated Shipping Days')}}</th>
                                    <th>{{translate('Paid by')}}</th>
                                    {{-- <th>{{translate('VAT')}}</th> --}}
                                    <th>{{translate('Shipping amount')}}</th>
                                </tr>
                            </thead>
                            <tbody id="bloc_sample_configuration">
                                <tr>
                                    <td>
                                        <select class="form-control shipper_sample" name="shipper_sample">
                                            <option value="" selected>{{translate('Choose shipper')}}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{translate('MawadOnline 3rd Party Shippers')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control estimated_sample" name="estimated_sample"></td>
                                    <td><input type="number" class="form-control estimated_shipping_sample" name="estimated_shipping_sample"></td>
                                    <td>
                                        <select class="form-control paid_sample" name="paid_sample">
                                            <option value="" selected>{{translate('Choose paid by')}}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{translate('Buyer')}}</option>
                                        </select>
                                    </td>
                                    {{-- <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="1" type="checkbox" class="vat_sample" name="vat_sample" @if($vat_user->vat_registered == 1) checked @endif>
                                            <span></span>
                                        </label>
                                    </td> --}}
                                    <td><input type="number" class="form-control shipping_amount" name="shipping_amount" step="0.1" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Bloc Product videos --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Videos')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Video Provider')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                                    <option value="youtube" @selected(old('video_provider') == 'youtube')>{{translate('Youtube')}}</option>
                                    <option value="vimeo" @selected(old('video_provider') == 'vimeo')>{{translate('Vimeo')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Video Link')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="video_link" value="{{ old('video_link') }}" placeholder="{{ translate('Video Link') }}">
                                <small class="text-muted">{{translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Product Category --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Category') }}</h5>
                        <h6 class="float-right fs-13 mb-0">
                            <span id="message-category"><span>
                            {{ translate('Select Main') }}
                            <span class="position-relative main-category-info-icon">
                                <i class="las la-question-circle fs-18 text-info"></i>
                                <span class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate('This will be used for commission based calculations and homepage category wise product Show.') }}</span>
                            </span>

                        </h6>
                    </div>
                    <input type="hidden" id="selected_parent_id" name="parent_id" value="">

                    <div class="card-body">

                        <div class="tree_main">

                            <input type="text" id="search_input" class="form-control" placeholder="Search">
                            <div class="h-300px overflow-auto c-scrollbar-light">

                                <div id="jstree"></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc variant & attributes --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Variation')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row gutters-5">
                            <div class="col-md-4">
                                <input type="text" class="form-control mb-2" value="{{translate('Activate variant option')}}" disabled>
                                <small style="color: red">Activate this option to select attribute</small>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="activate_attributes">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row gutters-5">
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>
                            </div>
                            <div class="col-md-8" id="attributes_bloc">
                                <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple disabled data-placeholder="{{ translate('Choose Attributes') }}">

                                </select>
                            </div>
                        </div>
                        <div>
                            <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>
                            <br>
                        </div>
                        <div id="variant_informations">
                            <h3 class="mb-3">Variant informations</h3>
                            <hr>
                            {{-- <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" value="{{translate('Variant SKU')}}" disabled>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control sku" id="sku">
                                </div>
                            </div> --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{translate('Variant Photos')}}" disabled>
                                </div>
                                <div class="col-md-8">
                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input photos_variant" id="photos_variant" accept=".jpeg, .jpg, .png" multiple>
                                        <label class="custom-file-label" for="photos_variant">Choose files</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{translate('Use default pricing configuration')}}" disabled>
                                </div>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-pricing" checked>
                                        <span></span>
                                    </label>
                                </div>
                                <div id="bloc_pricing_configuration_variant">

                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{translate('Use default Shipping')}}" disabled>
                                </div>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-shipping" data-type="variant-information" checked>
                                        <span></span>
                                    </label>
                                </div>

                                <div class="col-12 mt-3" id="bloc_default_shipping">

                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{translate('Sample Available?')}}" disabled>
                                </div>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-sample-available">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{translate('Use default sample pricing configuration')}}" disabled>
                                </div>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-sample-pricing" checked disabled>
                                        <span></span>
                                    </label>
                                </div>
                                <div id="bloc_sample_pricing_configuration_variant" class="bloc_sample_pricing_configuration_variant">
                                    {{-- <div class="row mb-3">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input value="1" class="vat_sample" type="checkbox" @if($vat_user->vat_registered == 1) checked @endif>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div> --}}
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" value="{{translate('Sample description')}}" disabled>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea class="form-control sample_description"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" value="{{translate('Sample price')}}" disabled>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control sample_price">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{translate('Use default sample Shipping')}}" disabled>
                                </div>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-sample-shipping" checked disabled>
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-12 mt-3" id="bloc-sample-shipping">

                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{translate('Low-Stock Warning')}}" disabled>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control stock-warning" id="low_stock_warning">
                                </div>
                            </div>
                            <div id="bloc_attributes">

                            </div>
                        </div>
                        <div class="row div-btn">
                            <button type="button" name="button" class="btn btn-primary" id="btn-create-variant">Create variant</button>
                        </div>
                        <div id="bloc_variants_created">

                        </div>
                    </div>
                </div>
                {{-- Bloc General Attributes --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('General Attributes')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div id="general_attributes"></div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Product Description --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Description')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Description')}}</label>
                            <div class="col-md-8">
                                <textarea class="aiz-text-editor" name="description"></textarea>
                                <input type="hidden" id="hidden_value" value="">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Product Documents --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('PDF Specification')}}<i class="las la-info-circle ml-2" data-toggle="tooltip" data-html="true" title="The maximum size permitted in a single document: <span style='red'>15 MB</span><br>Max size allowed for all documents: <span style='red'>25 MB</span>"></i>
                    </div>
                    <div class="card-body" id="documents_bloc">
                        <div class="row">
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Document name</label>
                                    <input type="text" class="form-control" name="document_names[]">
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Document</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                          <input type="file" name="documents[]" accept=".pdf,.png,.jpg,.pln,.dwg,.dxf,.gsm,.stl,.rfa,.rvt,.ifc,.3ds,.max,.obj,.fbx,.skp,.rar,.zip" class="custom-file-input file_input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
                                          <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <i class="las la-plus add_document" style="margin-left: 5px; margin-top: 40px;" title="Add another document"></i>
                                <i class="las la-trash trash_document" style="margin-left: 5px; margin-top: 40px;" title="Delete this document"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc SEO Meta --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('SEO Meta Tags')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Meta Title')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}" placeholder="{{ translate('Meta Title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Description')}}</label>
                            <div class="col-md-8">
                                <textarea name="meta_description" rows="8" class="form-control">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Meta Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="meta_img" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="mar-all text-right mb-2">
                    <button type="submit" name="button" value="draft" class="btn btn-success">Save as draft</button>
                    <button type="submit" name="button" value="publish" class="btn btn-primary">Upload Product</button>
                    <input type="hidden" name="submit_button" id="submit_button">
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
<!-- Treeview js -->
<script src="{{ static_asset('assets/js/countrySelect.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script src="{{ static_asset('assets/js/filter-multi-select-bundle.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
    var previewUrlBase  = "{{ route('seller.product.preview', ['slug' => 'PLACEHOLDER']) }}";
    </script>

<!--- category parent tree -->
<script type="text/javascript">
    function submitForm() {
        const formData = new FormData(document.getElementById('choice_form'));

        fetch('{{route("seller.product.tempStore")}}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Replace 'PLACEHOLDER' with the actual slug from the response
                var previewUrl = previewUrlBase.replace('PLACEHOLDER', data.data.slug);

                // Open the URL in a new tab
                window.open(previewUrl, '_blank');
            }
        });
    }

    $(document).ready(function() {
        $('#variant_informations').hide();
        $('#btn-create-variant').hide();
        $('body #bloc_pricing_configuration_variant').hide();
        $('body #bloc_sample_pricing_configuration_variant').hide();
        $('body .btn-variant-pricing').hide();
        var numbers_variant = 0;
        var today = moment().startOf("day");
        var initial_attributes = [];
        //$('.shipper').multiselect();

        // var shippers = $('.shipper').filterMultiSelect({
        //     placeholderText: "{{ translate('click to select shippers') }}",
        //     filterText: "{{ translate('Search') }}",
        //     caseSensitive: false,
        // })

        Array.prototype.diff = function(a) {
            return this.filter(function(i) {return a.indexOf(i) < 0;});
        };

        $('body input[name="activate_attributes"]').on('change', function() {
            if (!$('body input[name="activate_attributes"]').is(':checked')) {
                $('body #attributes').val('');
                $('body #attributes').prop('disabled', true);
                $('#variant_informations').hide();
                $('#btn-create-variant').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            } else {
                var category_choosen = $("#selected_parent_id").val();
                if (category_choosen != "1") {
                    if ($('#attributes option').length > 0) {
                        $('body #attributes').prop('disabled', false);
                        $('#variant_informations').show();
                        $('#btn-create-variant').show();
                        $('.div-btn').show();
                        AIZ.plugins.bootstrapSelect('refresh');
                    } else {
                        $('body input[name="activate_attributes"]').prop('checked', false);
                        swal(
                            'Cancelled',
                            "You are unable to enable the variant option because the selected category lacks any attributes.",
                            'error'
                        )
                    }
                } else {
                    $('body input[name="activate_attributes"]').prop('checked', false);
                    swal(
                            'Cancelled',
                            'Select a category before activating the variant option.',
                            'error'
                        )
                }
            }
        });


        $('#short_description').on('keyup', function(event) {
            var currentLength = $(this).val().length;
            var maxCharacters = 512;
            let charactersLeft = maxCharacters - currentLength;

            // Check if the length is greater than the limit
            if (currentLength > maxCharacters) {
                event.preventDefault();
                // Trim the text to the allowed limit
                var trimmedText = $(this).val().substr(0, maxCharacters);
                $(this).val(trimmedText);
            }else{
                var message = "<p>Remaining characters: <span style='color: red'>" + charactersLeft + "</span></p>"
                $('#charCountShortDescription').html(message);
            }
        });

        $('body').on('change', '#attributes', function() {
            var ids_attributes = $(this).val();

            var clicked = ids_attributes.diff( initial_attributes );
            if(clicked.length == 0){
                clicked = initial_attributes.diff( ids_attributes );
            }

            if(initial_attributes.includes(clicked[0])){
                initial_attributes.splice(initial_attributes.indexOf(clicked[0]), 1);
            }else{
                initial_attributes.push(clicked[0]);
            }

            var allValues = $('#attributes option').map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:"GET",
                url:'{{ route('seller.products.getAttributes') }}',
                data:{
                    ids: clicked,
                    id_variant: numbers_variant,
                    selected: ids_attributes,
                    allValues: allValues
                },
                success: function(data) {
                    var attribute_variant_exist = $('#bloc_attributes > .attribute-variant-' + clicked[0]).length
                    var numberOfChildren = $('#general_attributes > div').length;

                    if (numberOfChildren == 0) {
                        $('#general_attributes').append(data.html_attributes_generale);
                    }else{
                        var numberOfChildrenOfChildren = $('#general_attributes > div > div').length;
                        if(numberOfChildrenOfChildren == 0){
                            $('#general_attributes').append(data.html_attributes_generale);
                        }else{
                            $('#general_attributes .attribute-variant-' + clicked[0]).remove();
                        }
                    }

                    if(attribute_variant_exist > 0){
                        $('#bloc_attributes .attribute-variant-' + clicked[0]).remove();
                        $('#general_attributes .attribute-variant-' + clicked[0]).remove();
                        $('#general_attributes').append(data.html);
                    }else{
                        $('body #bloc_attributes').append(data.html);
                    }

                    $("#bloc_variants_created div").each(function(index, element) {
                        id_variant = $(element).data('id');
                        $(element).find('.attributes').each(function(index, child_element) {
                            // Change the attribute name of the current input
                            if ($(child_element).attr("name") == undefined) {
                                var id_attribute = $(child_element).data('id_attributes');
                                var name = 'attributes-'+ id_attribute + '-' + id_variant
                                $(child_element).attr('name', name);
                            }

                        });

                        $(element).find('.attributes-units').each(function(index, child_element_units) {
                            if ($(child_element_units).attr("name") == undefined) {
                                var id_attribute = $(child_element_units).data('id_attributes');
                                var name = 'attributes_units-'+ id_attribute + '-' +id_variant
                                $(child_element_units).attr('name', name);
                            }
                        });

                    });

                    $("#general_attributes div").each(function(index, element) {
                        $(element).find('.attributes').each(function(index, child_element) {
                            // Change the attribute name of the current input
                            if ($(child_element).attr("name") == undefined) {
                                var id_attribute = $(child_element).data('id_attributes');
                                var name = 'attribute_generale-'+ id_attribute
                                $(child_element).attr('name', name);
                            }

                        });

                        $(element).find('.attributes-units').each(function(index, child_element_units) {
                            if ($(child_element_units).attr("name") == undefined) {
                                var id_attribute = $(child_element_units).data('id_attributes');
                                var name = 'unit_attribute_generale-'+ id_attribute
                                $(child_element_units).attr('name', name);
                            }
                        });

                    });

                    var count_boolean = 1;
                    $("body #bloc_attributes div").each(function(index, element) {
                        $(element).find('.attributes').each(function(index, child_element) {
                            // Change the attribute name of the current input
                            if ($(child_element).attr("type") == 'radio') {
                                $(child_element).parent().parent().find(':radio').each(function(index, radio_element) {
                                    $(radio_element).attr('name', 'boolean'+count_boolean);
                                });

                                count_boolean++;
                            }
                        });
                    });

                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
        })

        $('body').on('change', '.photos_variant', function() {
            // Get the number of selected files
            var numFiles = $(this)[0].files.length;

            // Update the label text accordingly
            var labelText = numFiles === 1 ? '1 file selected' : numFiles + ' files selected';
            $(this).next('.custom-file-label').html(labelText);
        });

        $('body').on('click', '#btn-create-variant', function() {
            // Clone the original div
            var clonedDiv = $('body #variant_informations').clone();

            // Add some unique identifier to the cloned div (optional)
            clonedDiv.attr('class', 'clonedDiv');
            clonedDiv.removeAttr('id');
            clonedDiv.attr('data-id', numbers_variant);
            // Disable all input elements in the cloned div
            clonedDiv.find('input').prop('readonly', true);

            // Append the cloned div to the container

            var count = numbers_variant + 1;
            //add attribute name for each input cloned
            var html_to_add = '<div style="float: right; margin-top: -35px"><i class="fa-regular fa-circle-check fa-xl square-variant" title="End edit"></i><i class="fa-regular fa-pen-to-square fa-xl square-variant" title="Edit variant"></i><i class="fa-regular fa-circle-xmark fa-lx delete-variant" style="font-size: 16px;" title="delete this variant"></i></div>'
            clonedDiv.find('h3').after(html_to_add);
            //clonedDiv.find('.fa-circle-xmark').hide();
            clonedDiv.find('.fa-circle-check').hide();
            clonedDiv.find('#btn-add-pricing-variant').hide();
            //clonedDiv.find('.sku').attr('name', 'sku-' + numbers_variant);
            clonedDiv.find('.vat_sample').attr('name', 'vat_sample-' + numbers_variant);
            clonedDiv.find('.sample_description').attr('name', 'sample_description-' + numbers_variant);
            clonedDiv.find('.sample_price').attr('name', 'sample_price-' + numbers_variant);
            clonedDiv.find('.photos_variant').attr('name', 'photos_variant-' + numbers_variant + '[]');
            clonedDiv.find('.photos_variant').attr('id', 'photos_variant-' + numbers_variant);
            clonedDiv.find('.custom-file-label').attr('for', 'photos_variant-' + numbers_variant);
            clonedDiv.find('.variant-pricing').attr('name', 'variant-pricing-' + numbers_variant);
            clonedDiv.find('.variant-pricing').attr('data-variant', numbers_variant);
            clonedDiv.find('.min-qty-variant').each(function(index, element) {
                $(element).attr('name', 'variant_pricing-from' + numbers_variant + '[from][]');
            });
            clonedDiv.find('.max-qty-variant').each(function(index, element) {
                $(element).attr('name', 'variant_pricing-from' + numbers_variant + '[to][]');
            });
            clonedDiv.find('.unit-price-variant').each(function(index, element) {
                $(element).attr('name', 'variant_pricing-from' + numbers_variant + '[unit_price][]');
            });
            clonedDiv.find('.discount_percentage-variant').each(function(index, element) {
                 $(element).attr('name', 'variant_pricing-from' + numbers_variant + '[discount_percentage][]');
            });
            clonedDiv.find('.discount_amount-variant').each(function(index, element) {
                 $(element).attr('name', 'variant_pricing-from' + numbers_variant + '[discount_amount][]');
            });
            clonedDiv.find('.discount-range-variant').each(function(index, element) {
                $(element).attr('name', 'variant_pricing-from' + numbers_variant + '[discount_range][]');
                $(element).daterangepicker({
                    timePicker: true,
                    autoUpdateInput: false,
                    minDate: today,
                    locale: {
                        format: 'DD-MM-Y HH:mm:ss',
                        separator : " to ",
                    },
                });

                var format = 'DD-MM-Y HH:mm:ss';
                var separator = " to ";
                $(element).on("apply.daterangepicker", function (ev, picker) {
                    $(this).val(
                        picker.startDate.format(format) +
                            separator +
                            picker.endDate.format(format)
                    );
                });
            });
            clonedDiv.find('.variant-shipping').attr('name', 'variant-shipping-' + numbers_variant);

            clonedDiv.find('.stock-warning').attr('name', 'stock-warning-' + numbers_variant);
            clonedDiv.find('.discount_type-variant').each(function(index, element) {
                $(element).attr('name', 'variant_pricing-from' + numbers_variant + '[discount_type][]');
                $('#variant_informations').find('.discount_type-variant').each(function(key, element_original) {
                    if(index == key){
                        $(element).find('option[value="' + $(element_original).val() + '"]').prop('selected', true);
                    }
                })
            });
            clonedDiv.find('.attributes').each(function(index, element) {
                // Retrieve the data-id_attributes value of the current input
                var dataIdValue = $(element).data('id_attributes');
                var value= 0;
                if($(element).attr('data-type')){
                    $('#variant_informations').find('.color').each(function(key, element_original) {
                        if($(element_original).data('id_attributes') == dataIdValue){
                            value = $(element_original).val();
                        }
                    })

                    $(element).val(value);
                }

                // Change the attribute name of the current input
                $(element).attr('name', 'attributes-' + dataIdValue + '-' + numbers_variant);
            });

            clonedDiv.find('.attributes-units').each(function(index, element) {
                // Retrieve the data-id_attributes value of the current input
                var dataIdValue = $(element).data('id_attributes');

                // Change the attribute name of the current input
                $(element).attr('name', 'attributes_units-' + dataIdValue + '-' + numbers_variant);
                $('#variant_informations').find('.attributes-units').each(function(key, element_original) {
                    if(index == key){
                        $(element).find('option[value="' + $(element_original).val() + '"]').prop('selected', true);
                    }
                })
            });

            clonedDiv.find('.variant-sample-available').attr('name', 'variant-sample-available' + numbers_variant);
            clonedDiv.find('.variant-sample-pricing').attr('name', 'variant-sample-pricing' + numbers_variant);
            clonedDiv.find('.variant-sample-shipping').attr('name', 'variant-sample-shipping' + numbers_variant);

            clonedDiv.find('.min-qty-shipping').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[from][]');
            });

            clonedDiv.find('.max-qty-shipping').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[to][]');
            });

            var id_shipper = 0;
            clonedDiv.find('.shipper').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[shipper]['+ id_shipper +'][]');
                $('#variant_informations #table_shipping_configuration').find('.shipper').each(function(key, element_original) {
                    if(index == key){
                        $(element_original).val().forEach(value => {
                            $(element).find('option[value="' + value + '"]').prop('selected', true);
                        });
                    }
                })

                id_shipper++;
            });

            clonedDiv.find('.estimated_order').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[estimated_order][]');
            });

            clonedDiv.find('.estimated_shipping').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[estimated_shipping][]');
            });

            clonedDiv.find('.paid').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[paid][]');
                $('#variant_informations #table_shipping_configuration').find('.paid').each(function(key, element_original) {
                    if(index == key){
                        $(element).find('option[value="' + $(element_original).val() + '"]').prop('selected', true);
                    }
                })
            });

            clonedDiv.find('.vat_shipping').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[vat_shipping][]');
            });

            clonedDiv.find('.shipping_charge').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[shipping_charge][]');
                $('#variant_informations #table_shipping_configuration').find('.shipping_charge').each(function(key, element_original) {
                    if(index == key){
                        $(element).find('option[value="' + $(element_original).val() + '"]').prop('selected', true);
                    }
                })
            });

            clonedDiv.find('.flat_rate_shipping').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[flat_rate_shipping][]');
            });

            clonedDiv.find('.charge_per_unit_shipping').each(function(index, element) {
                $(element).attr('name', 'variant_shipping-' + numbers_variant + '[charge_per_unit_shipping][]');
            });

            clonedDiv.find('.shipper_sample').each(function(index, element) {
                $(element).attr('name', 'variant_shipper_sample-' + numbers_variant);
                $('#variant_informations #table_sample_configuration').find('.shipper_sample').each(function(key, element_original) {
                    if(index == key){
                        $(element).find('option[value="' + $(element_original).val() + '"]').prop('selected', true);
                    }
                })
            });

            clonedDiv.find('.paid_sample').each(function(index, element) {
                $(element).attr('name', 'paid_sample-' + numbers_variant);
                $('#variant_informations #table_sample_configuration').find('.paid_sample').each(function(key, element_original) {
                    if(index == key){
                        $(element).find('option[value="' + $(element_original).val() + '"]').prop('selected', true);
                    }
                })
            });

            clonedDiv.find('.estimated_sample').attr('name', 'estimated_sample-' + numbers_variant);
            clonedDiv.find('.estimated_shipping_sample').attr('name', 'estimated_shipping_sample-' + numbers_variant);
            clonedDiv.find('.shipping_amount').attr('name', 'shipping_amount-' + numbers_variant);

            clonedDiv.find('.delete_shipping_canfiguration').attr('data-variant-id', numbers_variant);
            clonedDiv.find('.btn-add-shipping').attr('data-variant-id', numbers_variant);
            clonedDiv.find('.btn-add-pricing').attr('data-newvariant-id', numbers_variant);

            $('#bloc_variants_created').show();
            $('#bloc_variants_created').prepend(clonedDiv);
            var divId = "#bloc_variants_created";

            // Get the length of all h3 tags under the specific div
            var h3Count = $(divId + " h3").length;


            // Loop through each h3 tag and display its order
            $(divId + " h3").each(function(index) {
                var order = h3Count - index; // Number in descending order
                $(this).text("Variant informations  " + order);
            });
            numbers_variant++;
        });

        $('body').on('click', '.fa-pen-to-square', function(){
            $(this).parent().parent().find('input').prop('readonly', false);
            $(this).parent().parent().find('.fa-circle-xmark').show();
            $(this).parent().parent().find('#btn-add-pricing-variant').show();
            $(this).parent().parent().find('.fa-pen-to-square').hide();
            $(this).parent().parent().find('.fa-circle-check').show();
        })

        $('body').on('click', '.fa-circle-check', function(){
            $(this).parent().parent().find('input').prop('readonly', true);
            $(this).parent().parent().find('.fa-circle-xmark').hide();
            $(this).parent().parent().find('#btn-add-pricing-variant').hide();
            $(this).parent().parent().find('.fa-pen-to-square').show();
            $(this).parent().parent().find('.fa-circle-check').hide();
        })

        $('body').on('change', '.variant-sample-pricing', function(){
            if ($(this).is(':not(:checked)')) {
                $(this).parent().parent().parent().find('.bloc_sample_pricing_configuration_variant').show();
            }else{
                $(this).parent().parent().parent().find('.bloc_sample_pricing_configuration_variant').hide();
            }
        })

        $('body').on('change', '.variant-pricing', function(){

            if ($(this).is(':not(:checked)')) {
                var is_variant = $(this).data("variant");
                var clonedElement = $("#table_pricing_configuration").clone();

                clonedElement.find('.min-qty').each(function(index, element) {
                    $(element).removeClass("min-qty").addClass("min-qty-variant");
                    if(is_variant != undefined){
                        $(element).attr('name', 'variant_pricing-from' + is_variant + '[from][]');
                    }else{
                        $(element).removeAttr("name");
                    }
                });
                clonedElement.find('.max-qty').each(function(index, element) {
                    $(element).removeClass("max-qty").addClass("max-qty-variant");
                    if(is_variant != undefined){
                        $(element).attr('name', 'variant_pricing-from' + is_variant + '[to][]');
                    }else{
                        $(element).removeAttr("name");
                    }
                });
                clonedElement.find('.discount_percentage').each(function(index, element) {
                    $(element).removeClass("discount_percentage").addClass("discount_percentage-variant");
                    if(is_variant != undefined){
                        $(element).attr('name', 'variant_pricing-from' + is_variant + '[discount_percentage][]');
                    }else{
                        $(element).removeAttr("name");
                    }
                });
                clonedElement.find('.discount_amount').each(function(index, element) {
                    $(element).removeClass("discount_amount").addClass("discount_amount-variant");
                    if(is_variant != undefined){
                        $(element).attr('name', 'variant_pricing-from' + is_variant + '[discount_amount][]');
                    }else{
                        $(element).removeAttr("name");
                    }
                });
                clonedElement.find('.discount-range').each(function(index, element) {
                    $(element).daterangepicker({
                        timePicker: true,
                        autoUpdateInput: false,
                        minDate: today,
                        locale: {
                            format: 'DD-MM-Y HH:mm:ss',
                            separator : " to ",
                        },
                    });

                    var format = 'DD-MM-Y HH:mm:ss';
                    var separator = " to ";
                    $(element).on("apply.daterangepicker", function (ev, picker) {
                        $(this).val(
                            picker.startDate.format(format) +
                                separator +
                                picker.endDate.format(format)
                        );
                    });

                    $(element).removeClass("discount-range").addClass("discount-range-variant");
                    $(element).removeAttr("name");
                    if(is_variant != undefined){
                        $(element).attr('name', 'variant_pricing-from' + is_variant + '[discount_range][]');
                    }
                });
                clonedElement.find('.unit-price-variant').each(function(index, element) {
                    $(element).removeClass("unit-price").addClass("unit-price-variant");
                    if(is_variant != undefined){
                        $(element).attr('name', 'variant_pricing-from' + is_variant + '[unit_price][]');
                    }else{
                        $(element).removeAttr("name");
                    }
                });
                clonedElement.find('.discount_type').each(function(index, element) {
                    $(element).removeClass("discount_type").addClass("discount_type-variant");
                    $(element).removeClass("aiz-selectpicker")
                    if(is_variant != undefined){
                        $(element).attr('name', 'variant_pricing-from' + is_variant + '[discount_type][]');
                    }else{
                        $(element).removeAttr("name");
                    }
                    $('#bloc_pricing_configuration').find('.discount_type').each(function(key, element_original) {
                        if(index == key){
                            $(element).find('option[value="' + $(element_original).val() + '"]').prop('selected', true);
                        }
                    })
                });
                $(this).parent().parent().parent().find('#bloc_pricing_configuration_variant').show();
                $(this).parent().parent().parent().find('#bloc_pricing_configuration_variant').append(clonedElement);
            }else{
                $(this).parent().parent().parent().find('#bloc_pricing_configuration_variant').empty();
            }
        })

        $("#country_selector").countrySelect({
            responsiveDropdown: true,
            preferredCountries: ['ae']
        });

        //A text-field for “Product Short Description”. Maximum length is 512 characters
        $('#summernote').on("summernote.change", function (e) {
            //Get the text in textarea with tags
            let htmlContent = $('#summernote').summernote('code');
            //Extract the text from code summernote
            let textContent = $(htmlContent).text();
            let maxLength = 512;
            let charactersLeft = maxLength - textContent.length;

            //Check if difference between maxlength and text is greater than 0
            if (charactersLeft >= 0) {
                if($('#hidden_value').val() != ''){
                    $('#hidden_value').val('');
                }
                var message = "<p>Remaining characters: <span style='color: red'>" + charactersLeft + "</span></p>"
                $('#charCount').html(message);
            } else {
                let trimmedText = '<p>' + textContent.substr(0, maxLength) + '</p>';
                if($('#hidden_value').val() == ''){
                    $('#hidden_value').val(trimmedText);
                }
                $("#summernote").summernote("code", $('#hidden_value').val());
            }
        });

        $('body').on('focusout', '.max-qty', function() {
            let overlapFound = false; // Flag to track if any overlaps are found

            var valuesMinQtyArray = [];
            var valuesMaxQtyArray = [];
            $('body .min-qty').each(function() {
                // Get the value of each input field and push it to the array
                valuesMinQtyArray.push($(this).val());
                $(this).css('border-color', '#e2e5ec');
            });

            $('body .max-qty').each(function() {
                // Get the value of each input field and push it to the array
                valuesMaxQtyArray.push($(this).val());
                $(this).css('border-color', '#e2e5ec');
            });

            //check if there is any overlaps
            for (let i = 0; i < valuesMinQtyArray.length; i++) {
                var minVal = parseFloat(valuesMinQtyArray[i]); //get current min value to compare with other value
                var maxVal = parseFloat(valuesMaxQtyArray[i]); //get current max value to compare with other value
                for (let j = 0; j < valuesMinQtyArray.length; j++) {
                    if(i == j){
                        continue;
                    }else{
                        var otherMinVal = parseFloat(valuesMinQtyArray[j]);
                        var otherMaxVal = parseFloat(valuesMaxQtyArray[j]);
                        var difference = otherMinVal - parseFloat(valuesMaxQtyArray[j - 1]);

                        if(difference > 1){
                            $('body .min-qty').eq(j).css('border-color', 'red');
                            $('body .max-qty').eq(j - 1).css('border-color', 'red');
                            swal(
                                'Cancelled',
                                'Ensure that the difference between the minimum and maximum quantities of the preceding interval must be equal to one',
                                'error'
                            )
                            overlapFound = true;
                        }

                        if (minVal >= otherMinVal && minVal <= otherMaxVal) { //check if min value exist in another interval
                            $('body .min-qty').eq(i).css('border-color', 'red');
                            swal(
                                'Cancelled',
                                'Overlap found',
                                'error'
                            )
                            overlapFound = true;
                        }

                        if(maxVal >= otherMinVal && maxVal <= otherMaxVal){ //check if max value exist in another interval
                            $('body .max-qty').eq(i).css('border-color', 'red');
                            swal(
                                'Cancelled',
                                'Overlap found',
                                'error'
                            )
                            overlapFound = true;
                        }
                    }
                }
            }
            // add another ligne in pricing configuration when not any overlaps are found
        });

        $('#btn-add-pricing-variant').click(() => {
            var html_to_add = `
                            <div>
                                <hr>
                                <div class="icon-delete-pricing">
                                    <i class="fa-regular fa-circle-xmark fa-fw fa-2xl"></i>
                                </div>
                                <div class="row qty-stock">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('From Quantity') }}</label>
                                            <input type="number" class="form-control min-qty-variant">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('To Quantity') }}</label>
                                            <input type="number" class="form-control max-qty-variant">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Unit Price (VAT Exclusive)') }}</label>
                                            <input type="number" class="form-control unit-price-variant">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount(Start/End)') }}</label>
                                            <input type="text" class="form-control aiz-date-range-variant discount-range-variant" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Type') }}</label>
                                            <select class="form-control discount_type-variant">
                                                <option value="">{{translate('Choose type')}}</option>
                                                <option value="amount" @selected(old('discount_type') == 'amount')>{{translate('Flat')}}</option>
                                                <option value="percent" @selected(old('discount_type') == 'percent')>{{translate('Percent')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Amount') }}</label>
                                            <input type="number" class="form-control discount_amount-variant">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Percentage') }}</label>
                                            <input type="number" class="form-control discount_percentage-variant">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                // add another bloc in pricing configuration
                $('#bloc_pricing_configuration_variant').append(html_to_add);
                //Initialize last date range picker
                $('#bloc_pricing_configuration_variant .aiz-date-range-variant:last').daterangepicker({
                    timePicker: true,
                    autoUpdateInput: false,
                    minDate: today,
                    locale: {
                        format: 'DD-MM-Y HH:mm:ss',
                        separator : " to ",
                    },
                });

                var format = 'DD-MM-Y HH:mm:ss';
                var separator = " to ";
                $('#bloc_pricing_configuration_variant .aiz-date-range:last').on("apply.daterangepicker", function (ev, picker) {
                    $(this).val(
                        picker.startDate.format(format) +
                            separator +
                            picker.endDate.format(format)
                    );
                });

                //refresh select discount type
                AIZ.plugins.bootstrapSelect('refresh');

        });

        $('body').on('click', '.btn-add-pricing', function() {
            var newvariant = $(this).data('newvariant-id');
            
            if(newvariant != undefined){
                var html_to_add = `<tr>
                                    <td><input type="number" name="variant_pricing-from`+ newvariant +`[from][]" class="form-control min-qty" id=""></td>
                                    <td><input type="number" name="variant_pricing-from`+ newvariant +`[to][]" class="form-control max-qty" id=""></td>
                                    <td><input type="number" name="variant_pricing-from`+ newvariant +`[unit_price][]" class="form-control unit-price-variant" id=""></td>
                                    <td><input type="text" class="form-control aiz-date-range discount-range" name="variant_pricing-from'`+ newvariant +`[discount_range][]" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                    <td>
                                        <select class="form-control discount_type" name="variant_pricing-from`+ newvariant +`[discount_type][]">
                                            <option value="" selected>{{translate('Choose type')}}</option>
                                            <option value="amount" @selected(old('discount_type') == 'amount')>{{translate('Flat')}}</option>
                                            <option value="percent" @selected(old('discount_type') == 'percent')>{{translate('Percent')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control discount_amount" name="variant_pricing-from`+ newvariant +`[discount_amount][]"></td>
                                    <td><input type="number" class="form-control discount_percentage" name="variant_pricing-from`+ newvariant +`[discount_percentage][]"></td>
                                    <td>
                                        <i class="las la-plus btn-add-pricing" data-id_variant="` + newvariant + `" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                        <i class="las la-trash delete_pricing_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="Delete this ligne"></i>
                                    </td>
                                </tr>`;
            }else{
                // Check if the element or any of its parents has the specific Id
                if ($(this).closest('#variant_informations').length) {
                    var html_to_add = `
                                <tr>
                                    <td><input type="number" class="form-control min-qty-variant" id=""></td>
                                    <td><input type="number" class="form-control max-qty-variant" id=""></td>
                                    <td><input type="number" class="form-control unit-price-variant" id=""></td>
                                    <td><input type="text" class="form-control aiz-date-range discount-range-variant" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                    <td>
                                        <select class="form-control discount_type-variant">
                                            <option value="" selected>{{translate('Choose type')}}</option>
                                            <option value="amount" @selected(old('discount_type') == 'amount')>{{translate('Flat')}}</option>
                                            <option value="percent" @selected(old('discount_type') == 'percent')>{{translate('Percent')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control discount_amount-variant"></td>
                                    <td><input type="number" class="form-control discount_percentage-variant"></td>
                                    <td>
                                        <i class="las la-plus btn-add-pricing" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                        <i class="las la-trash delete_pricing_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="Delete this ligne"></i>
                                    </td>
                                </tr>
                            `;
                }else{
                    var html_to_add = `
                                <tr>
                                    <td><input type="number" name="from[]" class="form-control min-qty" id=""></td>
                                    <td><input type="number" name="to[]" class="form-control max-qty" id=""></td>
                                    <td><input type="number" name="unit_price[]" class="form-control unit-price-variant" id=""></td>
                                    <td><input type="text" class="form-control aiz-date-range discount-range" name="date_range_pricing[]" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                    <td>
                                        <select class="form-control discount_type" name="discount_type[]">
                                            <option value="" selected>{{translate('Choose type')}}</option>
                                            <option value="amount" @selected(old('discount_type') == 'amount')>{{translate('Flat')}}</option>
                                            <option value="percent" @selected(old('discount_type') == 'percent')>{{translate('Percent')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control discount_amount" name="discount_amount[]"></td>
                                    <td><input type="number" class="form-control discount_percentage" name="discount_percentage[]"></td>
                                    <td>
                                        <i class="las la-plus btn-add-pricing" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                        <i class="las la-trash delete_pricing_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="Delete this ligne"></i>
                                    </td>
                                </tr>
                            `;
                } 
            }
            // add another bloc in pricing configuration
            $(this).parent().parent().parent().append(html_to_add);
            

            //Initialize last date range picker
            $(this).parent().parent().parent().find('.aiz-date-range:last').daterangepicker({
                timePicker: true,
                autoUpdateInput: false,
                minDate: today,
                locale: {
                    format: 'DD-MM-Y HH:mm:ss',
                    separator : " to ",
                },
            });

            var format = 'DD-MM-Y HH:mm:ss';
            var separator = " to ";
            $(this).parent().parent().parent().find('.aiz-date-range:last').on("apply.daterangepicker", function (ev, picker) {
                $(this).val(
                    picker.startDate.format(format) +
                        separator +
                        picker.endDate.format(format)
                );
            });

            //refresh select discount type
            AIZ.plugins.bootstrapSelect('refresh');
        });

        $('body').on('click', '.delete_pricing_canfiguration', function(){
            //remove bloc pricing configuration
            $(this).parent().parent().remove();
        })

        $('body').on('change', '.discount_type', function(){
            //enablig or disabling input discount amout or discount percentage
            if($(this).val() == "amount"){
                $(this).parent().parent().find('.discount_amount').prop('readonly', false);
                $(this).parent().parent().find('.discount_percentage').prop('readonly', true);
                $(this).parent().parent().find('.discount_percentage').val('');
            }
            if($(this).val() == "percent"){
                $(this).parent().parent().find('.discount_amount').prop('readonly', true);
                $(this).parent().parent().find('.discount_percentage').prop('readonly', false);
                $(this).parent().parent().find('.discount_amount').val('');
            }
        })

        $('body').on('change', '.discount_type-variant', function(){
            //enablig or disabling input discount amout or discount percentage
            if($(this).val() == "amount"){
                $(this).parent().parent().find('.discount_amount-variant').prop('readonly', false);
                $(this).parent().parent().find('.discount_percentage-variant').prop('readonly', true);
                $(this).parent().parent().find('.discount_percentage-variant').val('');
            }
            if($(this).val() == "percent"){
                $(this).parent().parent().find('.discount_amount-variant').prop('readonly', true);
                $(this).parent().parent().find('.discount_percentage-variant').prop('readonly', false);
                $(this).parent().parent().find('.discount_amount-variant').val('');
            }
        })

        let dropifyInput = $('#photoUpload');
        let originalInput = dropifyInput.clone(true);

        let dropifyInputThumbnail = $('#photoUploadThumbnail');
        let originalInputThumbnail = dropifyInputThumbnail.clone(true);

        function removeDropify() {
            dropifyInput.closest('.dropify-wrapper').find('.dropify-clear').remove();
            dropifyInput.unwrap().siblings().remove();
            dropifyInput.removeClass('dropify').removeAttr('data-plugin-init');
            dropifyInput.val('');
        }
        function removeDropifyThumbnail() {
            dropifyInputThumbnail.closest('.dropify-wrapper').find('.dropify-clear').remove();
            dropifyInputThumbnail.unwrap().siblings().remove();
            dropifyInputThumbnail.removeClass('dropify').removeAttr('data-plugin-init');
            dropifyInputThumbnail.val('');
        }

        function initializeDropify() {
            dropifyInput.dropify({
                messages: {
                    'default': 'Drag and drop a file here or click',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong happened.'
                }
            });
        }
        function initializeDropifyThumbnail() {
            dropifyInputThumbnail.dropify({
                messages: {
                    'default': 'Drag and drop a file here or click',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong happened.'
                }
            });
        }

        initializeDropify();
        initializeDropifyThumbnail();

        $('body').on('change', '#photoUpload', function() {
            let files = $(this)[0].files;
            $('#dropifyUploadedFiles').empty();
            if (files.length > 10) {
                swal(
                            'Cancelled',
                            'Maximum 10 photos allowed.',
                            'error'
                        )
                $(this).val('');
                removeDropify();
                dropifyInput.replaceWith(originalInput.clone(true));
                dropifyInput = $('#photoUpload');
                initializeDropify();
            } else {
                let exceedingFiles = [];

                for (let i = 0; i < files.length; i++) {
                    const fileSizeInMB = files[i].size / (1024 * 1024);
                    if (fileSizeInMB > 2) {
                        exceedingFiles.push(files[i].name);
                    }
                }

                if (exceedingFiles.length > 0) {
                    swal(
                            'Cancelled',
                            'Following files exceed 2MB limit: ' + exceedingFiles.join(', '),
                            'error'
                        )
                    $(this).val('');
                    removeDropify();
                    dropifyInput.replaceWith(originalInput.clone(true));
                    dropifyInput = $('#photoUpload');
                    initializeDropify();
                } else {
                    let exceedingFilesDimension = [];

                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];
                        if (file.type.startsWith('image/')) {
                            // Create a FileReader object to read the uploaded file
                            let reader = new FileReader();

                            // Closure to capture the file information
                            reader.onload = function(event) {
                                let img = new Image();
                                img.src = event.target.result;

                                // Check image dimensions after it's loaded
                                img.onload = function() {
                                    if (img.width > 1200 || img.height > 1200) {
                                        exceedingFilesDimension.push(files[i].name);
                                    }
                                };
                            };

                            // Read the file as a data URL
                            reader.readAsDataURL(file);
                        }
                    }
                    setTimeout(function() {
                        if (exceedingFilesDimension.length > 0) {
                            swal(
                                'Cancelled',
                                'Following files exceeded 1200px width or height limit: ' + exceedingFilesDimension.join(', '),
                                'error'
                            )
                            $(this).val('');
                            removeDropify();
                            dropifyInput.replaceWith(originalInput.clone(true));
                            dropifyInput = $('#photoUpload');
                            initializeDropify();
                        }else{
                            let uploadedFilesHTML = '<div class="row">';
                            for (let i = 0; i < files.length; i++) {
                                let file = files[i];
                                if (file.type.startsWith('image/')) {
                                    uploadedFilesHTML += `<div class="col-2"><img src="${URL.createObjectURL(file)}" alt="${file.name}" height="80" width="80" /></div>`; // Display image preview
                                } else {
                                    // Display icon for document type
                                    uploadedFilesHTML += `<li><i class="fa fa-file-text-o"></i> ${file.name}</li>`;
                                }
                            }
                            uploadedFilesHTML += '</div>';
                            console.log('name :', $(this).prop('name'));

                            if ($('body #dropifyUploadedFiles').length === 0) {
                                $("#bloc_photos").append('<div id="dropifyUploadedFiles"></div>');
                                $('body #dropifyUploadedFiles').html(uploadedFilesHTML);
                            }else{
                                $('body #dropifyUploadedFiles').html(uploadedFilesHTML);
                            }
                        }
                    }, 500);
                }
            }
        });

        $('body').on('change', '#photoUploadThumbnail', function() {
            let files = $(this)[0].files;
            $('#dropifyUploadedFilesThumbnail').empty();
            if (files.length > 10) {
                swal(
                            'Cancelled',
                            'Maximum 10 photos allowed.',
                            'error'
                        )
                $(this).val('');
                removeDropifyThumbnail();
                dropifyInputThumbnail.replaceWith(dropifyInputThumbnail.clone(true));
                dropifyInputThumbnail = $('#photoUploadThumbnail');
                initializeDropifyThumbnail();
            } else {
                let exceedingFiles = [];

                for (let i = 0; i < files.length; i++) {
                    if (files[i].size > 512 * 1024) {
                        exceedingFiles.push(files[i].name);
                    }
                }

                if (exceedingFiles.length > 0) {
                    swal(
                            'Cancelled',
                            'Following files exceed 512Ko limit: ' + exceedingFiles.join(', '),
                            'error'
                        )
                    $(this).val('');
                    removeDropifyThumbnail();
                    dropifyInputThumbnail.replaceWith(originalInputThumbnail.clone(true));
                    dropifyInputThumbnail = $('#photoUploadThumbnail');
                    initializeDropifyThumbnail();
                } else {
                    let exceedingFilesDimension = [];

                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];
                        if (file.type.startsWith('image/')) {
                            // Create a FileReader object to read the uploaded file
                            let reader = new FileReader();

                            // Closure to capture the file information
                            reader.onload = function(event) {
                                let img = new Image();
                                img.src = event.target.result;

                                // Check image dimensions after it's loaded
                                img.onload = function() {
                                    if (img.width > 400 || img.width < 300 || img.height > 400 || img.height < 300) {
                                        exceedingFilesDimension.push(files[i].name);
                                    }
                                };
                            };

                            // Read the file as a data URL
                            reader.readAsDataURL(file);
                        }
                    }

                    setTimeout(function() {
                        if (exceedingFilesDimension.length > 0) {
                            swal(
                                'Cancelled',
                                'Please upload images with dimensions between 300px and 400px for both width and height: ' + exceedingFilesDimension.join(', '),
                                'error'
                            )
                            $(this).val('');
                            removeDropifyThumbnail();
                            dropifyInputThumbnail.replaceWith(originalInputThumbnail.clone(true));
                            dropifyInputThumbnail = $('#photoUploadThumbnail');
                            initializeDropifyThumbnail();
                        }else{

                            let uploadedFilesHTML = '<div class="row">';
                            for (let i = 0; i < files.length; i++) {
                                let file = files[i];
                                if (file.type.startsWith('image/')) {
                                    uploadedFilesHTML += `<div class="col-2"><img src="${URL.createObjectURL(file)}" alt="${file.name}" height="80" width="80" /></div>`; // Display image preview
                                } else {
                                    // Display icon for document type
                                    uploadedFilesHTML += `<li><i class="fa fa-file-text-o"></i> ${file.name}</li>`;
                                }
                            }
                            uploadedFilesHTML += '</div>';

                            if ($('body #dropifyUploadedFilesThumbnail').length == 0) {
                                $("#bloc_thumbnails").append('<div id="dropifyUploadedFilesThumbnail"></div>');
                                $('body #dropifyUploadedFilesThumbnail').html(uploadedFilesHTML);
                            }else{
                                console.log('existe')
                                $('body #dropifyUploadedFilesThumbnail').html(uploadedFilesHTML);
                            }
                        }
                    }, 500);
                }
            }
        });

        let fileInputCounter = 1;

        $('body').on('click', '.dropify-clear', function(){
            if($(this).parent().parent().parent().find('#dropifyUploadedFilesThumbnail').length) {
                $('body #dropifyUploadedFilesThumbnail').empty();
            }else{
                $('body #dropifyUploadedFiles').empty();
            }
        });

        $('body').on('change', '.file_input', function() {
            var file = $(this)[0].files[0]; // Get the file object
            if (file) {
                var maxSize = 15 * 1024 * 1024; // 15MB in bytes
                var maxAllUploadedSize = 25 * 1024 * 1024; // 25MB in bytes
                var fileSize = file.size; // Get the file size in bytes
                var totalSize = 0;
                var fileSizeMb = fileSize / (1024 * 1024);

                if (fileSize > maxSize) {
                    swal(
                        'Cancelled',
                        'File size exceeds 15MB.',
                        'error'
                        )
                } else {
                    $('.file_input').each(function() {
                        var files = $(this)[0].files;

                        for (var i = 0; i < files.length; i++) {
                            totalSize += files[i].size;
                        }
                    });

                    if (totalSize > maxAllUploadedSize) {
                        // If combined file size exceeds the limit, show an error message or take necessary action
                        swal(
                            'Cancelled',
                            'Total file size exceeds 25MB. Please select smaller files.',
                            'error'
                            )
                        // Reset the file inputs to prevent exceeding the limit
                        $(this).val('');
                    } else {
                        let fileName = $(this).val().split('\\').pop();
                        $(this).next('.custom-file-label').addClass('selected').html(fileName);
                        $(this).parent().parent().parent().find('.size-file-uploaded').remove();
                        $(this).parent().parent().parent().append("<p class='size-file-uploaded'>{{ translate('the size of the downloaded document: ') }}<span style='color: red'>"+ fileSizeMb.toFixed(2) +"MB<span></p>");
                    }
                }
            } else {
                console.log('No file selected.');
            }
        });

        $('body').on('click', '.add_document', function(){
            var html_document = `<div class="row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="exampleInputEmail">Document name</label>
                                        <input type="text" class="form-control" name="document_names[]">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="exampleInputEmail${fileInputCounter}">Document</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                            <input type="file" name="documents[]" class="custom-file-input file_input" id="exampleInputEmail${fileInputCounter}" aria-describedby="inputGroupFileAddon04">
                                            <label class="custom-file-label" for="exampleInputEmail${fileInputCounter}">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <i class="las la-plus add_document" style="margin-left: 5px; margin-top: 40px;" title="Add another document"></i>
                                    <i class="las la-trash trash_document" style="margin-left: 5px; margin-top: 40px;" title="Delete this document"></i>
                                </div>
                            </div>`;
            $('#documents_bloc').append(html_document);
            fileInputCounter++;
        })

        $('body').on('click', '.trash_document', function(){
            $(this).parent().parent().remove();
        })

        $('body').on('click', '.delete-variant', function(){
            $(this).parent().parent().remove();

            var divId = "#bloc_variants_created";

            // Get the length of all h3 tags under the specific div
            var h3Count = $(divId + " h3").length;


            // Loop through each h3 tag and display its order
            $(divId + " h3").each(function(index) {
                var order = h3Count - index; // Number in descending order
                $(this).text("Variant informations  " + order);
            });
        })

        //Shipping script
        $('body').on('click', '#third_party_activate', function() {
            if ($(this).is(':checked')) {
                var count_shippers = "{{ count($supported_shippers) }}";
                count_shippers = parseInt(count_shippers);
                if(count_shippers == 0){
                    $('body input[name="activate_third_party"]').prop('checked', false);
                    swal(
                            'Cancelled',
                            "You don't have any warehouse supported by our shippers",
                            'error'
                        )
                }else{
                    $('#bloc_third_party input[type="number"]').each(function() {
                        // Change readonly attribute from true to false
                        $(this).prop('readonly', false);
                    });

                    $('#bloc_third_party select').each(function() {
                        // Change readonly attribute from true to false
                        $(this).prop('disabled', false);
                    });
                }
            }else{
                $('#bloc_third_party input[type="number"]').each(function() {
                    // Change readonly attribute from true to false
                    $(this).prop('readonly', true);
                });

                $('#bloc_third_party select').each(function() {
                    // Change readonly attribute from true to false
                    $(this).prop('disabled', true);
                });

                $('#bloc_third_party input[type="number"]').val('').prop('readonly', true);
            }
        });

        $('body').on('click', '.btn-add-shipping', function() {
            var row = $(this).parent().parent().parent().find('tr').length;
            var id_variant = $(this).data('variant-id');

            if(id_variant != undefined){
                var html_to_add = `
                                <tr>
                                    <td><input type="number" name="variant_shipping-${id_variant}[from][]" class="form-control min-qty-shipping" id=""></td>
                                    <td><input type="number" name="variant_shipping-${id_variant}[to][]" class="form-control max-qty-shipping" id=""></td>
                                    <td>
                                        <select multiple class="form-control shipper" name="variant_shipping-${id_variant}[shipper][${row}][]">
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{translate('MawadOnline 3rd Party Shippers')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control estimated_order" name="variant_shipping-${id_variant}[estimated_order][]"></td>
                                    <td><input type="number" class="form-control estimated_shipping" name="variant_shipping-${id_variant}[estimated_shipping][]"></td>
                                    <td>
                                        <select class="form-control paid" name="variant_shipping-${id_variant}[paid][]">
                                            <option value="" selected>{{translate('Choose shipper')}}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{translate('Buyer')}}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control shipping_charge" name="variant_shipping-${id_variant}[shipping_charge][]">
                                            <option value="" selected>{{translate('Choose shipping charge')}}</option>
                                            <option value="flat" @selected(old('shipping_charge') == 'flat')>{{translate('Flat-rate regardless of quantity')}}</option>
                                            <option value="charging" @selected(old('shipping_charge') == 'charging')>{{translate('Charging per Unit of Sale')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control flat_rate_shipping" name="variant_shipping-${id_variant}[flat_rate_shipping][]" readonly></td>
                                    <td><input type="number" class="form-control charge_per_unit_shipping" name="variant_shipping-${id_variant}[charge_per_unit_shipping][]" readonly></td>
                                    <td>
                                        <i class="las la-plus btn-add-shipping" data-variant-id="${id_variant}" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                        <i class="las la-trash delete_shipping_canfiguration" data-variant-id="${id_variant}" style="margin-left: 5px; margin-top: 17px;" title="Delete this ligne"></i>
                                    </td>
                                </tr>
                            `;
            }else{
                if ($(this).closest('#variant_informations').length) {
                    var html_to_add = `
                                <tr>
                                    <td><input type="number"  class="form-control min-qty-shipping" id=""></td>
                                    <td><input type="number"  class="form-control max-qty-shipping" id=""></td>
                                    <td>
                                        <select multiple class="form-control shipper" >
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{translate('MawadOnline 3rd Party Shippers')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control estimated_order" ></td>
                                    <td><input type="number" class="form-control estimated_shipping" ></td>
                                    <td>
                                        <select class="form-control paid" >
                                            <option value="" selected>{{translate('Choose shipper')}}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{translate('Buyer')}}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control shipping_charge" >
                                            <option value="" selected>{{translate('Choose shipping charge')}}</option>
                                            <option value="flat" @selected(old('shipping_charge') == 'flat')>{{translate('Flat-rate regardless of quantity')}}</option>
                                            <option value="charging" @selected(old('shipping_charge') == 'charging')>{{translate('Charging per Unit of Sale')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control flat_rate_shipping" readonly></td>
                                    <td><input type="number" class="form-control charge_per_unit_shipping" readonly></td>
                                    <td>
                                        <i class="las la-plus btn-add-shipping" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                        <i class="las la-trash delete_shipping_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="Delete this ligne"></i>
                                    </td>
                                </tr>
                            `;
                }else{
                    var html_to_add = `
                                <tr>
                                    <td><input type="number" name="from_shipping[]" class="form-control min-qty-shipping" id=""></td>
                                    <td><input type="number" name="to_shipping[]" class="form-control max-qty-shipping" id=""></td>
                                    <td>
                                        <select multiple class="form-control shipper" name="shipper[${row}][]">
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{translate('MawadOnline 3rd Party Shippers')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control estimated_order" name="estimated_order[]"></td>
                                    <td><input type="number" class="form-control estimated_shipping" name="estimated_shipping[]"></td>
                                    <td>
                                        <select class="form-control paid" name="paid[]">
                                            <option value="" selected>{{translate('Choose shipper')}}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{translate('Buyer')}}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control shipping_charge" name="shipping_charge[]">
                                            <option value="" selected>{{translate('Choose shipping charge')}}</option>
                                            <option value="flat" @selected(old('shipping_charge') == 'flat')>{{translate('Flat-rate regardless of quantity')}}</option>
                                            <option value="charging" @selected(old('shipping_charge') == 'charging')>{{translate('Charging per Unit of Sale')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control flat_rate_shipping" name="flat_rate_shipping[]" readonly></td>
                                    <td><input type="number" class="form-control charge_per_unit_shipping" name="charge_per_unit_shipping[]" readonly></td>
                                    <td>
                                        <i class="las la-plus btn-add-shipping" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                        <i class="las la-trash delete_shipping_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="Delete this ligne"></i>
                                    </td>
                                </tr>
                            `;
                }
            }

            // add another row in shipping configuration
            $(this).parent().parent().parent().append(html_to_add);
        });

        $('body').on('click', '.delete_shipping_canfiguration', function(){
            //remove row in shipping configuration
            var current = $(this).parent().parent().parent();
            var variant_id = $(this).data('variant-id');
            $(this).parent().parent().remove();

            var count = 0;
            current.find('.shipper').each(function(index) {
                if(variant_id ==undefined){
                    $(this).attr('name', 'shipper[' + count + '][]')
                }else{
                    $(this).attr('name', 'variant_shipping-' + variant_id + '[shipper]['+ count +'][]')

                }

                count++
            });
        })

        $('body').on('change', '.shipper', function(){
            var count_shippers = "{{ count($supported_shippers) }}";
                count_shippers = parseInt(count_shippers);
            var selected = $(this).val();



            if(selected.indexOf('third_party') !== -1){
                if(count_shippers == 0){
                    swal(
                        'Cancelled',
                        "You cannot choose a third-party option because our shippers are unable to reach the warehouse.",
                        'error'
                    )

                    $(this).find("option[value='third_party']").prop('disabled', false);
                }else{
                    var weight = $('#weight').val();
                    var length = $('#length').val();
                    var width = $('#width').val();
                    var height = $('#height').val();
                    var breakable = $('#breakable').val();
                    var min_third_party = $('#min_third_party').val();
                    var max_third_party = $('#max_third_party').val();
                    var unit_third_party = $('#unit_third_party').val();
                    if((weight == '') || (length == '') ||(width == '') ||(height == '') ||(min_third_party == '') ||(max_third_party == '')){
                        swal(
                                'Cancelled',
                                "Please ensure that all required fields are filled to know all information about your package.",
                                'error'
                            )
                            $(this).find("option[value='third_party']").prop('disabled', false);
                            $(this).find("option[value='third_party']").prop('selected', false);
                    }else{
                        length = parseInt(length);
                        height = parseInt(height);
                        width = parseInt(width);
                        weight = parseInt(weight);
                        var volumetric_weight = (length * height * width) / 5000;
                        var chargeable_weight = 0;
                        var html = '';
                        if(volumetric_weight > weight){
                            chargeable_weight = volumetric_weight;
                        }else{
                            chargeable_weight = weight;
                        }

                        if(chargeable_weight > 30){
                            swal(
                                'Cancelled',
                                "Chargeable Weight = " + Number(chargeable_weight.toFixed(2)) + ", then not accepted by our shipper",
                                'error'
                            )

                            $(this).find("option[value='third_party']").prop("disabled", true);
                            $(this).find("option[value='third_party']").prop('selected', false);
                        }else{
                            $(this).parent().parent().find('.estimated_shipping').prop('readonly', true);
                            $(this).parent().parent().find('.shipping_charge').find("option:first").prop("selected", true);
                            $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', true);
                            $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                            $(this).parent().parent().find('.estimated_shipping').val(null);
                            $(this).parent().parent().find('.paid').val(null);
                            $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', true);
                            $(this).parent().parent().find('.flat_rate_shipping').val(null);
                        }
                    }
                }

            }

            if(selected.indexOf('vendor') !== -1){
                $(this).parent().parent().find('.estimated_shipping').prop('readonly', false);
                $(this).parent().parent().find('.shipping_charge').find("option:first").prop("selected", true);
                $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', true);
                $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                $(this).parent().parent().find('.paid').val(null);
                $(this).parent().parent().find('.estimated_shipping').val(null);
                $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', true);
                $(this).parent().parent().find('.flat_rate_shipping').val(null);
            }

        })


        $('body').on('change', '.paid', function(){
            var shippers = $(this).parent().parent().find('.shipper').val();
            if(shippers.indexOf('vendor') !== -1){
                if($(this).val() != "buyer"){
                    $(this).parent().parent().find('.shipping_charge').find("option:first").prop("selected", true);
                    $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', true);
                    $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                    $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', true);
                    $(this).parent().parent().find('.flat_rate_shipping').val(null);
                }
            }else{
                swal(
                        'Cancelled',
                        "You cannot selected, if you don't selected vendor in shippers",
                        'error'
                    )
            }
        })

        $('body').on('change', '.shipping_charge', function(){
            if($(this).parent().parent().find('.paid').val() == 'vendor'){
                swal(
                        'Cancelled',
                        "You cannot choose shipping charge when it is paid by vendor.",
                        'error'
                    )

                    $(this).find('option').eq(0).prop('selected', true);
            }else{
                if($(this).val() == "flat"){
                    $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', false);
                    $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', true);
                    $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                }else{
                    $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', false);
                    $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', true);
                    $(this).parent().parent().find('.flat_rate_shipping').val(null);
                }
            }
        })

        $('body').on('change', '.variant-shipping', function(){
            if ($(this).is(':not(:checked)')){
                var clonedDiv = $('#table_shipping_configuration').clone();
                var type_switch = $(this).data('type');

                clonedDiv.find('.shipper').each(function(index, element) {
                    $('#shipping_configuration_box #table_shipping_configuration').find('.shipper').each(function(key, element_original) {
                        console.log('$(element_original).val(): ', $(element_original).val())
                        if(index == key){
                            $(element_original).val().forEach(value => {
                                $(element).find('option[value="' + value + '"]').prop('selected', true);
                            });
                        }
                    })
                });

                clonedDiv.find('.paid').each(function(index, element) {
                    $('#shipping_configuration_box #table_shipping_configuration').find('.paid').each(function(key, element_original) {
                        if(index == key){
                            $(element).find('option[value="' + $(element_original).val() + '"]').prop('selected', true);
                        }
                    })
                });

                clonedDiv.find('.shipping_charge').each(function(index, element) {
                    $('#shipping_configuration_box #table_shipping_configuration').find('.shipping_charge').each(function(key, element_original) {
                        if(index == key){
                            $(element).find('option[value="' + $(element_original).val() + '"]').prop('selected', true);
                        }
                    })
                });

                if(type_switch != undefined){
                    clonedDiv.find('.min-qty-shipping').removeAttr('name');
                }

                if(type_switch != undefined){
                    clonedDiv.find('.max-qty-shipping').removeAttr('name');
                }

                if(type_switch != undefined){
                    clonedDiv.find('.estimated_order').removeAttr('name');
                }

                if(type_switch != undefined){
                    clonedDiv.find('.estimated_shipping').removeAttr('name');
                }

                if(type_switch != undefined){
                    clonedDiv.find('.shipping_charge').removeAttr('name');
                }

                if(type_switch != undefined){
                    clonedDiv.find('.flat_rate_shipping').removeAttr('name');
                }

                if(type_switch != undefined){
                    clonedDiv.find('.charge_per_unit_shipping').removeAttr('name');
                }

                $(this).parent().parent().parent().find('#bloc_default_shipping').append(clonedDiv);
            }else{
                $(this).parent().parent().parent().find('#bloc_default_shipping').empty();
            }
        })

        $('#bloc_third_party input[type="number"], select.calculate').on('input change', function() {
            var weight = $(this).parent().parent().find('#weight').val();
            var length = $(this).parent().parent().find('#length').val();
            var width = $(this).parent().parent().find('#width').val();
            var height = $(this).parent().parent().find('#height').val();
            var breakable = $(this).parent().parent().find('#breakable').val();
            var min_third_party = $(this).parent().parent().find('#min_third_party').val();
            var max_third_party = $(this).parent().parent().find('#max_third_party').val();
            var unit_third_party = $(this).parent().parent().find('#unit_third_party').val();

            if((weight == '') || (length == '') ||(width == '') ||(height == '') ||(min_third_party == '') ||(max_third_party == '')){
                html = '<span style="color: green"> Chargeable Weight = 0, then accepted by our shipper </span>';
                $('#result_calculate_third_party').html(html);
            }else{
                length = parseInt(length);
                height = parseInt(height);
                width = parseInt(width);
                weight = parseInt(weight);
                var volumetric_weight = (length * height * width) / 5000;
                var chargeable_weight = 0;
                var unit = $(this).parent().parent().find('#weight_unit').val();
                var max = 30;
                if(unit == "pounds"){
                    max *= 2.2;
                }
                var html = '';
                if(volumetric_weight > weight){
                    chargeable_weight = volumetric_weight;
                }else{
                    chargeable_weight = weight;
                }

                if(unit == "pounds"){
                    chargeable_weight *= 2.2;
                }

                if(chargeable_weight > max ){
                    html = '<span style="color: red">Chargeable Weight = ' + Number(chargeable_weight.toFixed(2)) + ", then not accepted by Aramex </span>"
                }else{
                    html = '<span style="color: green">Chargeable Weight = ' + Number(chargeable_weight.toFixed(2)) + ", then accepted by Aramex </span>"
                }



                $('#result_calculate_third_party').html(html);
            }
        });

        //sample script
        $('body').on('click', '.btn-add-sample', function() {
            var html_to_add = `
                                <tr>
                                    <td>
                                        <select class="form-control shipper_sample" name="shipper_sample[]">
                                            <option value="" disabled selected>{{translate('Choose shipper')}}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{translate('MawadOnline 3rd Party Shippers')}}</option>
                                        </select>
                                    </td>shipping_
                                    <td><input type="number" class="form-control estimated_sample" name="estimated_sample[]"></td>
                                    <td><input type="number" class="form-control estimated_shipping_sample" name="estimated_shipping_sample[]"></td>
                                    <td>
                                        <select class="form-control paid_sample" name="paid_sample[]">
                                            <option value="" disabled selected>{{translate('Choose shipper')}}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{translate('vendor')}}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{translate('Buyer')}}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control shipping_amount" name="shipping_amount[]"></td>
                                    <td>
                                        <i class="las la-plus btn-add-sample" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                        <i class="las la-trash delete_sample_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="Delete this ligne"></i>
                                    </td>
                                </tr>
                            `;
                // add another row in shipping configuration
                $(this).parent().parent().parent().append(html_to_add);
        });

        $('body').on('click', '.delete_sample_canfiguration', function(){
            //remove row in shipping configuration
            $(this).parent().parent().remove();
        })

        $('body').on('change', '.paid_sample', function(){
            if($(this).val() == "buyer"){
                $(this).parent().parent().find('.shipping_amount').prop('readonly', false);
            }else{
                $(this).parent().parent().find('.shipping_amount').prop('readonly', true);
                $(this).parent().parent().find('.shipping_amount').val(null);
            }
        });

        $('body').on('change', '.variant-sample-shipping', function(){
            if ($(this).is(':not(:checked)')) {
                var clonedDiv = $('#table_sample_configuration').clone();
                var paid_sample = $('#table_sample_configuration').find('.paid_sample').val();
                var shipper_sample = $('#table_sample_configuration').find('.shipper_sample').val();
                clonedDiv.find('.paid_sample').find('option[value="' + paid_sample + '"]').prop('selected', true);
                clonedDiv.find('.shipper_sample').find('option[value="' + shipper_sample + '"]').prop('selected', true);
                $(this).parent().parent().parent().find('#bloc-sample-shipping').append(clonedDiv);
            }else{
                $(this).parent().parent().parent().find('#bloc-sample-shipping').empty();
            }
        })

        $('body').on('change', '.variant-sample-available', function(){
            if ($(this).is(':checked')) {
                $(this).parent().parent().parent().parent().find('.variant-sample-pricing').prop('disabled', false);
                $(this).parent().parent().parent().parent().find('.variant-sample-shipping').prop('disabled', false);
            }else{
                $(this).parent().parent().parent().parent().find('.variant-sample-pricing').prop('disabled', true);
                $(this).parent().parent().parent().parent().find('.variant-sample-shipping').prop('disabled', true);
            }
        })
    });

</script>
<script>
    $(function() {
        $('#jstree').jstree({
            'core': {
                'data': {
                    "url": "{{ route('seller.categories.jstree') }}",
                    "data": function(node) {
                        return {
                            "id": node.id
                        };
                    },
                    "dataType": "json"
                },
                'check_callback': true,
                'themes': {
                    'responsive': false
                }
            },
            "plugins": ["wholerow", "search"] // Include the search plugin here
        }).on("changed.jstree", function(e, data) {
            if (data && data.selected && data.selected.length) {
                    var selectedId = data.selected[0]; // Get the ID of the first selected node

                    // Check if the selected node has children
                    var node = $('#jstree').jstree(true).get_node(selectedId);

                    if ((!node.children || node.children.length === 0) && node.state.loaded ==  true) {
                        $('#message-category').text("Correct choice.");
                        $('#message-category').css({'color': 'green', 'margin-right': '7px'});
                        // The node does not have children, proceed with your logic
                        $('#selected_parent_id').val(selectedId); // Update hidden input with selected ID
                        $('#attributes_bloc').html('<select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple disabled data-placeholder="{{ translate("No attributes found") }}"></select>');
                        $('body input[name="activate_attributes"]').prop("checked", false);
                        $('#variant_informations').hide();
                        $('body .div-btn').hide();
                        $('body #bloc_variants_created').hide();
                        $('#general_attributes').empty();
                        AIZ.plugins.bootstrapSelect('refresh');
                        // Call your functions to load attributes
                        load_attributes(selectedId);
                    } else {
                        // The node has children, maybe clear selection or handle differently
                        $('#message-category').text("Please select a category without subcategories.");
                        $('#message-category').css({'color': 'red', 'margin-right': '7px'});
                        $('#attributes_bloc').html('<select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple disabled data-placeholder="{{ translate("No attributes found") }}"></select>');
                        $('body input[name="activate_attributes"]').prop("checked", false);
                        $('#variant_informations').hide();
                        $('body .div-btn').hide();
                        $('body #bloc_variants_created').hide();
                        $('#general_attributes').empty();
                        AIZ.plugins.bootstrapSelect('refresh');
                        // Optionally, clear selection here if needed
                        // $('#jstree').jstree(true).deselect_node(selectedId);
                    }
                }
        });
    });

    // Search configuration
    var to = false;
    $('#search_input').keyup(function() {
        if (to) {
            clearTimeout(to);
        }
        to = setTimeout(function() {
            var v = $('#search_input').val();
            if (v === "") {
                lastSearchTerm = null;
                    // Explicitly reset the URL for the initial data load
                $('#jstree').jstree(true).settings.core.data.url = "{{ route('seller.categories.jstree') }}";

                $('#jstree').jstree(true).settings.core.data.data = function(node) {
                    return {
                        "id": node.id
                    };
                },
                $('#jstree').jstree(false, true).refresh(); // Refresh the tree to load initial data
            } else {
                lastSearchTerm = v;
                $.ajax({
                    url: "{{ route('seller.categories.jstreeSearch') }}", // Your actual API endpoint
                    type: 'GET', // Or 'POST', depending on your API
                    dataType: 'json', // Expected data format from API
                    data: {
                        searchTerm: v // Send the search term to your API
                    },
                    success: function(response) {
                        console.log(response);
                        // Assuming 'response' contains the data to update the jstree
                        // You will need to process 'response' to fit your jstree's data format

                        // Example: clear the existing jstree and populate with new data
                        $('#jstree').jstree(true).settings.core.data = response;
                        $('#jstree').jstree(true).refresh();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error during search API call:", status, error);
                    }
                });
            }
        }, 250);
    });

    function load_attributes(categorie_id) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"GET",
            url:'{{ route('seller.products.getAttributeCategorie') }}',
            data:{
                id: categorie_id
            },
            success: function(data) {
                if(data.html != ""){
                    $('#attributes_bloc').html(data.html);
                }else{
                    $('#attributes_bloc').html('<select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple disabled data-placeholder="{{ translate("No attributes found") }}"></select>');
                    $('body input[name="activate_attributes"]').prop("checked", false);
                    $('#variant_informations').hide();
                    $('body .div-btn').hide();
                    $('body #bloc_variants_created').hide();
                }

                $('#general_attributes').html(data.html_attributes_generale);

                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert2 JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('choice_form').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            Swal.fire({
                title: "Product Publication",
                text: "Do you want to publish or unpublish this product?",
                icon: "info",
                showCancelButton: false,
                confirmButtonText: "Next",
                html: '<input type="checkbox" id="publicationToggle" value="published" checked> Publish upon approval',
                focusConfirm: false,
                preConfirm: () => {
                    const publicationStatus = document.getElementById('publicationToggle').checked ? 'published' : 'unpublished';
                    if(publicationStatus == 'published'){
                        $('#published_after_approve').val(1)
                    }
                    Swal.fire({
                        title: "Create Stock Items",
                        text: "Do you want to create stock items for this product?",
                        icon: "info",
                        showCancelButton: false,
                        confirmButtonText: "Create",
                        html: '<input type="checkbox" id="stockToggle" value="published" checked> Create stock',
                        focusConfirm: false,
                    }).then((result) => {
                        var stockToggleStatus = document.getElementById('stockToggle').checked ? 'create' : 'not create';
                        if(stockToggleStatus == 'create'){
                            $('#create_stock').val(1)
                        }

                        // Update the hidden input field with the value of the clicked button
                        var clickedButtonValue = event.submitter.value;
                        document.getElementById('submit_button').value = clickedButtonValue;
                        
                        document.getElementById('choice_form').submit();
                    });
                }
            });
        });
    });
</script>

@endsection
