@extends('backend.layouts.app')
<style>
    .div-btn {
        display: flex;
        justify-content: center;
    }

    .border {
        border-color: red !important;
    }

    .table th {
        font-size: 12px !important;
    }

    .multi-select-button {
        height: 44px;
        border: 1px solid #e2e5ec !important;
        box-shadow: none !important;
    }

    body {
        background-color: rgb(247, 248, 250) !important;
    }

    .aiz-content-wrapper {
        background-color: rgb(247, 248, 250) !important;
    }

    .aiz-main-content .pr-lg-25px {
        background-color: rgb(247, 248, 250) !important;
    }

    .col-md-2 input[type="text"]:disabled {
        /* Your CSS styles here */
        background-color: white !important;
        border: none !important;
        color: black;
        font-size: 12px;
    }
</style>
<link rel="stylesheet" href="{{ static_asset('assets/css/example-styles.css') }}">
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Approve Product') }}</h5>
    </div>
    <div class="">
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

        @if ($product->added_from_catalog == 1)
            <div class="alert alert-warning" role="alert">
                {{ translate('This product is added from Mawad catalog.') }}
            </div>
        @endif

        <form>
            <div class="row gutters-5">
                <div class="col-lg-12">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    {{-- Bloc Product Information --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="col-3">
                                <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                            </div>
                            <div class="col-7">
                                <span style="float: right;"><b>{{ translate('Product status') }}:
                                        <span class="position-relative main-category-info-icon">
                                            <i class="las la-question-circle fs-18 text-info"></i>
                                            <span
                                                class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate("Choosing different items will completely change the product's status.") }}</span>
                                        </span></b>
                                </span>
                            </div>
                            <div class="col-2">
                                <select class="form-control status" data-id="{{ $product->id }}">
                                    <option value="0" @if ($product->approved == 0) selected @endif disabled>
                                        Pending</option>
                                    <option value="4" @if ($product->approved == 4) selected @endif>Under Review
                                    </option>
                                    <option value="2" @if ($product->approved == 2) selected @endif>Revision
                                        Required</option>
                                    <option value="3" @if ($product->approved == 3) selected @endif>Rejected
                                    </option>
                                    <option value="1" @if ($product->approved == 1) selected @endif>Approved
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Product Name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="name" value="{{ $product->name }}"
                                        @if (array_key_exists('name', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_informations['name'] }}" @endif
                                        placeholder="{{ translate('Product Name') }}">
                                </div>
                            </div>
                            <div class="form-group row" id="brand">
                                <label class="col-md-2 col-from-label">{{ translate('Brand') }}</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="brand_id" id="brand_id"
                                        @if (array_key_exists('brand_id', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_informations['brand_id'] }}" @endif
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Brand') }}</option>
                                        @foreach (\App\Models\Brand::all() as $brand)
                                            <option value="{{ $brand->id }}" @selected($product->brand_id == $brand->id)>
                                                {{ $brand->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Unit of Sale') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="unit" value="{{ $product->unit }}"
                                        @if (array_key_exists('unit', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_informations['unit'] }}" @endif
                                        placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">
                                    {{ __('Unit of Sale Price') }}
                                    <small>({{ __('VAT Exclusive') }})</small>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control" name="unit_sale_price"
                                        value="{{ $product->unit_price }}" placeholder="{{ __('Unit of Sale Price') }}" @if (array_key_exists('unit_price', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_informations['unit_price'] }}" @endif />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Country of origin') }}</label>
                                <div class="col-md-10">
                                    <div class="form-item">
                                        <input id="country_selector" type="text"
                                            @if (array_key_exists('country_code', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_informations['country_code'] }}" @endif>
                                        <label for="country_selector" style="display:none;">Select a country here...</label>
                                    </div>
                                    <div class="form-item" style="display:none;">
                                        <input type="text"
                                            @if (array_key_exists('country_code', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_informations['country_code'] }}" @endif
                                            id="country_selector_code" name="country_code" data-countrycodeinput="1"
                                            readonly="readonly" placeholder="Selected country code will appear here" />
                                        <label for="country_selector_code">...and the selected country code will be updated
                                            here</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Manufacturer') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="manufacturer"
                                        @if (array_key_exists('manufacturer', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_informations['manufacturer'] }}" @endif
                                        value="{{ $product->manufacturer }}" placeholder="Manufacturer">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Tags') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control aiz-tag-input" value="{{ $product->tags }}"
                                        name="tags[]" placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                    <small
                                        class="text-muted">{{ translate('This is used for search. Input those words by which cutomer can find this product.') }}</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Short description') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="short_description" id="short_description"
                                        @if (array_key_exists('short_description', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_informations['short_description'] }}" @endif>{{ $product->short_description }}</textarea>
                                    <div id="charCountShortDescription">Remaining characters: 512</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Show Stock Quantity') }}</label>
                                <div class="col-md-10">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="stock_visibility_state" value="1"
                                            @if ($product->stock_visibility_state == 'quantity') checked="checked" @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Published') }}</label>
                                <div class="col-md-10">
                                    <label class="aiz-switch aiz-switch-success mb-0"
                                        @if (array_key_exists('published', $general_informations)) style="border: 1px solid red !important; border-radius: 12px;" data-toggle="tooltip" data-html="true" title="Modified and old value is: @if ($general_informations['published'] == 1) Enabled @else Disabled @endif"
                                        @endif>
                                        <input type="checkbox" name="published" value="1"
                                            @if ($product->published == 1) checked="checked" @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            @if (addon_is_activated('pos_system'))
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">{{ translate('Barcode') }}</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="barcode"
                                            value="{{ old('barcode') }}" placeholder="{{ translate('Barcode') }}">
                                    </div>
                                </div>
                            @endif

                            @if (addon_is_activated('refund_request'))
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">{{ translate('Refundable') }}</label>
                                    <div class="col-md-10">
                                        <label class="aiz-switch aiz-switch-success mb-0"
                                            @if (array_key_exists('refundable', $general_informations)) style="border: 1px solid red !important; border-radius: 12px;" data-toggle="tooltip" data-html="true" title="Modified and old value is: @if ($general_informations['refundable'] == 1) Enabled @else Disabled @endif"
                                            @endif>
                                            <input type="checkbox" name="refundable"
                                                @if ($product->refundable == 1) checked @endif>
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
                            <h5 class="mb-0 h6">{{ translate('Product Images') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label"
                                    for="signinSrEmail">{{ translate('Gallery Images') }}
                                    <small>(1280x1280)</small></label>
                                <div class="col-md-10" id="bloc_photos">
                                    <div class="row mt-3" id="dropifyUploadedFiles">
                                        @if (count($product->getImagesProduct()) > 0)
                                            @foreach ($product->getImagesProduct() as $image)
                                                <div class="col-2 container-img">
                                                    <img src="{{ asset('/public/' . $image->path) }}" height="120"
                                                        width="120" class="style-img"
                                                        @if (in_array($image->id, $historique_images)) data-toggle="tooltip" data-html="true" title="Image added" style="border: 4px solid green" @endif />
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label"
                                    for="signinSrEmail">{{ translate('Thumbnail Image') }}
                                    <small>(400x400)</small></label>
                                <div class="col-md-10" id="bloc_thumbnails">
                                    <div class="row mt-3" id="dropifyUploadedFilesThumbnail">
                                        @if (count($product->getThumbnailsProduct()) > 0)
                                            @foreach ($product->getThumbnailsProduct() as $image)
                                                <div class="col-2 container-img">
                                                    <img src="{{ asset('/public/' . $image->path) }}" height="120"
                                                        width="120" class="style-img"
                                                        @if (in_array($image->id, $historique_images)) data-toggle="tooltip" data-html="true" title="Image added" style="border: 4px solid green" @endif />
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Bloc Pricing configuration --}}
                    <div class="card d-none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Pricing Configuration') }}</h5>
                        </div>
                        <div class="card-body">
                            {{-- <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                            </div>
                            <div class="col-md-10">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="vat" @if ($vat_user->vat_registered == 1) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <hr> --}}
                            <div>
                                <table class="bloc_pricing_configuration_variant table" id="table_pricing_configuration">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('From Quantity') }}</th>
                                            <th>{{ translate('To Quantity') }}</th>
                                            <th>{{ translate('Unit Price (VAT Exclusive)') }}</th>
                                            <th>{{ translate('Discount(Start/End)') }}</th>
                                            <th>{{ translate('Discount Type') }}</th>
                                            <th>{{ translate('Discount Amount') }}</th>
                                            <th>{{ translate('Discount Percentage') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bloc_pricing_configuration">
                                        @if (count($product->getPricingConfiguration()) > 0)
                                            @foreach ($product->getPricingConfiguration() as $pricing)
                                                <tr>
                                                    <td><input type="number" name="from[]" class="form-control min-qty"
                                                            id="" value="{{ $pricing->from }}"></td>
                                                    <td><input type="number" name="to[]" class="form-control max-qty"
                                                            id="" value="{{ $pricing->to }}"></td>
                                                    <td><input type="number" name="unit_price[]"
                                                            class="form-control unit-price-variant" id=""
                                                            value="{{ $pricing->unit_price }}"></td>
                                                    @php
                                                        $date_range = '';
                                                        if ($pricing->discount_start_datetime) {
                                                            $start_date = new DateTime($pricing->discount_start_datetime);
                                                            $start_date_formatted = $start_date->format('d-m-Y H:i:s');

                                                            $end_date = new DateTime($pricing->discount_end_datetime);
                                                            $end_date_formatted = $end_date->format('d-m-Y H:i:s');

                                                            $date_range = $start_date_formatted . ' to ' . $end_date_formatted;
                                                        }
                                                    @endphp
                                                    <td><input type="text"
                                                            class="form-control aiz-date-range discount-range"
                                                            value="{{ $date_range }}" name="date_range_pricing[]"
                                                            placeholder="{{ translate('Select Date') }}"
                                                            data-time-picker="true" data-separator=" to "
                                                            data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                                    <td>
                                                        <select class="form-control discount_type" name="discount_type[]">
                                                            <option value="" selected>{{ translate('Choose type') }}
                                                            </option>
                                                            <option value="amount" @selected($pricing->discount_type == 'amount')>
                                                                {{ translate('Flat') }}</option>
                                                            <option value="percent" @selected($pricing->discount_type == 'percent')>
                                                                {{ translate('Percent') }}</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="form-control discount_amount"
                                                            value="{{ $pricing->discount_amount }}"
                                                            @if ($pricing->discount_type != 'amount') readonly @endif
                                                            name="discount_amount[]"></td>
                                                    <td><input type="number" class="form-control discount_percentage"
                                                            value="{{ $pricing->discount_percentage }}"
                                                            @if ($pricing->discount_type != 'percent') readonly @endif
                                                            name="discount_percentage[]"></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="number" name="from[]" class="form-control min-qty"
                                                        id=""></td>
                                                <td><input type="number" name="to[]" class="form-control max-qty"
                                                        id=""></td>
                                                <td><input type="number" name="unit_price[]"
                                                        class="form-control unit-price-variant" id=""></td>
                                                <td><input type="text"
                                                        class="form-control aiz-date-range discount-range"
                                                        name="date_range_pricing[]"
                                                        placeholder="{{ translate('Select Date') }}"
                                                        data-time-picker="true" data-separator=" to "
                                                        data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                                <td>
                                                    <select class="form-control discount_type" name="discount_type[]">
                                                        <option value="" selected>{{ translate('Choose type') }}
                                                        </option>
                                                        <option value="amount" @selected(old('discount_type') == 'amount')>
                                                            {{ translate('Flat') }}</option>
                                                        <option value="percent" @selected(old('discount_type') == 'percent')>
                                                            {{ translate('Percent') }}</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control discount_amount"
                                                        name="discount_amount[]"></td>
                                                <td><input type="number" class="form-control discount_percentage"
                                                        name="discount_percentage[]"></td>
                                                <td>
                                                    <i class="las la-plus btn-add-pricing"
                                                        style="margin-left: 5px; margin-top: 17px;"
                                                        title="Add another ligne"></i>
                                                    <i class="las la-trash delete_pricing_canfiguration"
                                                        style="margin-left: 5px; margin-top: 17px;"
                                                        title="Delete this ligne"></i>
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- Bloc Default shipping configuration --}}
                    <div class="card" id="shipping_configuration_box">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Default Shipping Configuration') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label
                                    class="col-md-4 col-from-label">{{ translate('Activate MawadOnline 3rd Party Shipping') }}</label>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" id="third_party_activate"
                                            name="activate_third_party" @if ($product->activate_third_party == 1) checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <table class="table" id="table_third_party_configuration"
                                    class="bloc_third_configuration_variant">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Length Package (Cm)') }}</th>
                                            <th>{{ translate('Width Package (Cm)') }}</th>
                                            <th>{{ translate('Height Package (Cm)') }}</th>
                                            <th>{{ translate('Weight Package') }}</th>
                                            <th>{{ translate('Weight unit') }}</th>
                                            <th>{{ translate('Breakable') }}</th>
                                            <th>{{ translate('Unit') }}</th>
                                            <th>{{ translate('Min') }}</th>
                                            <th>{{ translate('Max') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bloc_third_party">
                                        <tr>
                                            <td><input type="number" name="length" class="form-control" id="length"
                                                    step="0.1"
                                                    @if ($product->activate_third_party == 1) value="{{ $product->length }}" @else readonly @endif>
                                            </td>
                                            <td><input type="number" name="width" class="form-control" id="width"
                                                    step="0.1"
                                                    @if ($product->activate_third_party == 1) value="{{ $product->width }}" @else readonly @endif>
                                            </td>
                                            <td><input type="number" name="height" class="form-control" id="height"
                                                    step="0.1"
                                                    @if ($product->activate_third_party == 1) value="{{ $product->height }}" @else readonly @endif>
                                            </td>
                                            <td><input type="number" name="weight" class="form-control" id="weight"
                                                    step="0.1"
                                                    @if ($product->activate_third_party == 1) value="{{ $product->weight }}" @else readonly @endif>
                                            </td>
                                            <td>
                                                <select class="form-control calculate" id="weight_unit"
                                                    name="unit_weight" @if ($product->activate_third_party != 1) disabled @endif>
                                                    <option value="kilograms"
                                                        @if ($product->unit_weight == 'kilograms') {{ 'selected' }} @endif>
                                                        {{ translate('Kilograms') }}</option>
                                                    <option value="pounds"
                                                        @if ($product->unit_weight == 'pounds') {{ 'selected' }} @endif>
                                                        {{ translate('Pounds') }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control calculate" id="breakable" name="breakable"
                                                    @if ($product->activate_third_party != 1) disabled @endif>
                                                    <option value=""
                                                        @if ($product->breakable == null) {{ 'selected' }} @endif>
                                                        {{ translate('Choose option') }}</option>
                                                    <option value="yes"
                                                        @if ($product->breakable == 'yes') {{ 'selected' }} @endif>
                                                        {{ translate('Yes') }}</option>
                                                    <option value="no"
                                                        @if ($product->breakable == 'no') {{ 'selected' }} @endif>
                                                        {{ translate('No') }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control calculate" id="unit_third_party"
                                                    name="unit_third_party"
                                                    @if ($product->activate_third_party != 1) disabled @endif>
                                                    <option value="celsius"
                                                        @if ($product->unit_third_party == 'celsius') {{ 'selected' }} @endif>
                                                        {{ translate('Celsius') }}</option>
                                                    <option value="kelvin"
                                                        @if ($product->unit_third_party == 'kelvin') {{ 'selected' }} @endif>
                                                        {{ translate('Kelvin') }}</option>
                                                    <option value="fahrenheit"
                                                        @if ($product->unit_third_party == 'fahrenheit') {{ 'selected' }} @endif>
                                                        {{ translate('Fahrenheit') }}</option>
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control" name="min_third_party"
                                                    id="min_third_party" step="0.1"
                                                    @if ($product->activate_third_party == 1) value="{{ $product->min_third_party }}" @else readonly @endif>
                                            </td>
                                            <td><input type="number" class="form-control" name="max_third_party"
                                                    id="max_third_party" step="0.1"
                                                    @if ($product->activate_third_party == 1) value="{{ $product->max_third_party }}" @else readonly @endif>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="result_calculate_third_party">
                                    @if ($product->activate_third_party == 1)
                                        @if ($chargeable_weight > 30)
                                            <span style="color: red"> Chargeable Weight =
                                                {{ number_format($chargeable_weight, 2) }}, then not accepted by our
                                                shipper </span>
                                        @else
                                            <span style="color: green"> Chargeable Weight =
                                                {{ number_format($chargeable_weight, 2) }}, then accepted by our shipper
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div>
                                <table class="bloc_shipping_configuration_variant table" id="table_shipping_configuration">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Shipper') }}</th>
                                            <th>{{ translate('From Quantity') }}</th>
                                            <th>{{ translate('To Quantity') }}</th>
                                            <th>{{ translate('Estimated Order Preparation Days') }}</th>
                                            <th>{{ translate('Estimated Shipping Days') }}</th>
                                            <th>{{ translate('Paid by') }}</th>
                                            <th>{{ translate('Shipping Charge') }}</th>
                                            <th>{{ translate('Flat-rate Amount') }}</th>
                                            <th>{{ translate('Charge per Unit of Sale') }}</th>
                                            <th>{{ translate('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bloc_shipping_configuration">
                                        @if (count($product->getShipping()) > 0)
                                            @foreach ($product->getShipping() as $key => $shipping)
                                                <tr>
                                                    <td>
                                                        <select class="form-control shipper"
                                                            name="shipper[]">
                                                            <option value="vendor"
                                                                @if ($shipping->shipper === 'vendor') selected @endif>
                                                                {{ translate('vendor') }}</option>
                                                            <option value="third_party"
                                                                @if ($shipping->shipper === 'third_party') selected @endif>
                                                                {{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="from_shipping[]"
                                                            value="{{ $shipping->from_shipping }}"
                                                            class="form-control min-qty-shipping" id=""></td>
                                                    <td><input type="number" name="to_shipping[]"
                                                            value="{{ $shipping->to_shipping }}"
                                                            class="form-control max-qty-shipping" id=""></td>
                                                    <td><input type="number" class="form-control estimated_order"
                                                            value="{{ $shipping->estimated_order }}"
                                                            name="estimated_order[]"></td>
                                                    <td><input type="number" class="form-control estimated_shipping"
                                                            value="{{ $shipping->estimated_shipping }}"
                                                            name="estimated_shipping[]"></td>
                                                    <td>
                                                        <select class="form-control paid" name="paid[]">
                                                            <option value="" selected>
                                                                {{ translate('Choose option') }}</option>
                                                            <option value="vendor"
                                                                @if ($shipping->paid == 'vendor') {{ 'selected' }} @endif>
                                                                {{ translate('vendor') }}</option>
                                                            <option value="buyer"
                                                                @if ($shipping->paid == 'buyer') {{ 'selected' }} @endif>
                                                                {{ translate('Buyer') }}</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control shipping_charge"
                                                            name="shipping_charge[]">
                                                            <option value="" selected>
                                                                {{ translate('Choose shipping charge') }}</option>
                                                            <option value="flat"
                                                                @if ($shipping->shipping_charge == 'flat') {{ 'selected' }} @endif>
                                                                {{ translate('Flat-rate regardless of quantity') }}
                                                            </option>
                                                            <option value="charging"
                                                                @if ($shipping->shipping_charge == 'charging') {{ 'selected' }} @endif>
                                                                {{ translate('Charging per Unit of Sale') }}</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="form-control flat_rate_shipping"
                                                            value="{{ $shipping->flat_rate_shipping }}"
                                                            name="flat_rate_shipping[]" readonly></td>
                                                    <td><input type="number"
                                                            class="form-control charge_per_unit_shipping"
                                                            value="{{ $shipping->charge_per_unit_shipping }}"
                                                            name="charge_per_unit_shipping[]" readonly></td>
                                                    <td>
                                                        <i class="las la-plus btn-add-shipping"
                                                            style="margin-left: 5px; margin-top: 17px;"
                                                            title="{{ __("Add another ligne") }}"></i>
                                                        @if ($key != 0)
                                                            <i class="las la-trash delete_shipping_canfiguration"
                                                                data-id="{{ $shipping->id }}"
                                                                style="margin-left: 5px; margin-top: 17px;"
                                                                title="{{ __("Delete this ligne") }}"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <select class="form-control shipper" name="shipper[]">
                                                        <option value="" selected>{{ translate('Choose shipper') }}
                                                        </option>
                                                        <option value="vendor" @selected(old('shipper') == 'vendor')>
                                                            {{ translate('vendor') }}</option>
                                                        <option value="third_party" @selected(old('shipper') == 'third_party')>
                                                            {{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" name="from_shipping[]"
                                                        class="form-control min-qty-shipping" id=""></td>
                                                <td><input type="number" name="to_shipping[]"
                                                        class="form-control max-qty-shipping" id=""></td>
                                                <td><input type="number" class="form-control estimated_order"
                                                        name="estimated_order[]"></td>
                                                <td><input type="number" class="form-control estimated_shipping"
                                                        name="estimated_shipping[]"></td>
                                                <td>
                                                    <select class="form-control paid" name="paid[]">
                                                        <option value="" selected>{{ translate('Choose option') }}
                                                        </option>
                                                        <option value="vendor" @selected(old('shipper') == 'vendor')>
                                                            {{ translate('vendor') }}</option>
                                                        <option value="buyer" @selected(old('shipper') == 'buyer')>
                                                            {{ translate('Buyer') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control shipping_charge" name="shipping_charge[]">
                                                        <option value="" selected>
                                                            {{ translate('Choose shipping charge') }}</option>
                                                        <option value="flat" @selected(old('shipping_charge') == 'flat')>
                                                            {{ translate('Flat-rate regardless of quantity') }}</option>
                                                        <option value="charging" @selected(old('shipping_charge') == 'charging')>
                                                            {{ translate('Charging per Unit of Sale') }}</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control flat_rate_shipping"
                                                        name="flat_rate_shipping[]" readonly></td>
                                                <td><input type="number" class="form-control charge_per_unit_shipping"
                                                        name="charge_per_unit_shipping[]" readonly></td>
                                                <td>
                                                    <i class="las la-plus btn-add-shipping"
                                                        style="margin-left: 5px; margin-top: 17px;"
                                                        title="Add another ligne"></i>
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- Bloc Sample pricing configuration --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Default Sample Pricing Configuration') }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="sample_parent">
                                <div class="row mb-3">
                                    <label class="col-md-2 col-from-label">{{ translate('Sample Available?') }}</label>
                                    <div class="col-md-10">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="{{ $product->sample_available }}" @if($product->sample_available == 1) checked @endif name="sample_available" type="checkbox" class="sample-available" disabled />
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <input type="text" class="form-control"
                                            value="{{ translate('Sample description') }}" disabled>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control sample_description_parent" name="sample_description">{{ $product->sample_description }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <input type="text" class="form-control"
                                            value="{{ translate('Sample price') }}" disabled>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control sample_price_parent"
                                            name="sample_price" value="{{ $product->sample_price }}">
                                    </div>
                                </div>
                            </div>
                            <table class="table" id="table_sample_configuration"
                                class="bloc_sample_configuration_variant">
                                <thead>
                                    <tr>
                                        <th>{{ translate('Shipping-by') }}</th>
                                        <th>{{ translate('Estimated Sample Preparation Days') }}</th>
                                        <th>{{ translate('Estimated Shipping Days') }}</th>
                                        <th>{{ translate('Paid by') }}</th>
                                        <th>{{ translate('Shipping amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="bloc_sample_configuration">
                                    @foreach($product->getSampleShipping() as $key => $shipping)
                                        <tr>
                                            <td>
                                                <select class="form-control shipper_sample" name="shipper_sample[]">
                                                    <option value="vendor"
                                                        @selected($shipping->shipper === 'vendor')>
                                                        {{ translate('vendor') }}</option>
                                                    <option value="third_party"
                                                        @selected($shipping->shipper === 'third_party')>
                                                        {{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control estimated_sample"
                                                    name="estimated_sample"
                                                    @if ($shipping->estimated_order != null) value="{{ $shipping->estimated_order }}" @endif>
                                            </td>
                                            <td><input type="number" class="form-control estimated_shipping_sample"
                                                    name="estimated_shipping_sample"
                                                    @if ($shipping->estimated_shipping != null) value="{{ $shipping->estimated_shipping }}" @endif>
                                            </td>
                                            <td>
                                                <select class="form-control paid_sample" name="paid_sample">
                                                    <option value="" selected>{{ translate('Choose paid by') }}
                                                    </option>
                                                    <option
                                                        value="vendor" @selected($shipping->paid == 'vendor')>
                                                        {{ translate('vendor') }}</option>
                                                    <option value="buyer"
                                                        @selected($shipping->paid == 'buyer')>
                                                        {{ translate('Buyer') }}</option>
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control shipping_amount"
                                                    name="shipping_amount"
                                                    @if ($shipping->flat_rate_shipping != null) value="{{ $shipping->flat_rate_shipping }}" @else readonly @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Bloc Product videos --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Videos') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Video Provider') }}</label>
                                <div class="col-md-10">
                                    <select class="form-control"
                                        @if (array_key_exists('video_provider', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old video provider is: {{ $general_informations['video_provider'] }}" @endif
                                        name="video_provider" id="video_provider">
                                        <option value="youtube" @selected($product->video_provider == 'youtube')>{{ translate('Youtube') }}
                                        </option>
                                        <option value="vimeo" @selected($product->video_provider == 'vimeo')>{{ translate('Vimeo') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Video Link') }}</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control"
                                        @if (array_key_exists('video_link', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old link is: {{ $general_informations['video_link'] }}" @endif
                                        name="video_link" value="{{ $product->video_link }}"
                                        placeholder="{{ translate('Video Link') }}">
                                    <small
                                        class="text-muted">{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Bloc Product Category --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Category') }}</h5>
                            <h6 class="float-right fs-13 mb-0">
                                {{ translate('Select Main') }}
                                <span class="position-relative main-category-info-icon">
                                    <i class="las la-question-circle fs-18 text-info"></i>
                                    <span
                                        class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate('This will be used for commission based calculations and homepage category wise product Show.') }}</span>
                                </span>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Category Path') }} :</label>
                                <div class="col-md-10">
                                    <b
                                        @if (array_key_exists('category_id', $general_informations)) style="color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old path is: {{ $general_informations['category_id'] }}" @endif>{{ $product->pathCategory() }}</b>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- Bloc variant & attributes --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Variation') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row gutters-5">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" value="{{ translate('Attributes') }}"
                                        disabled>
                                </div>
                                <div class="col-md-10" id="attributes_bloc">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        data-selected-text-format="count" id="attributes" multiple
                                        @if (count($product->getChildrenProducts()) == 0 || count($attributes) == 0) disabled @endif
                                        data-placeholder="{{ translate('Choose Attributes') }}">
                                        @if (count($attributes) > 0)
                                            @foreach ($attributes as $attribute)
                                                <option value="{{ $attribute->id }}" @selected(in_array($attribute->id, $variants_attributes_ids_attributes))>
                                                    {{ $attribute->getTranslation('name') }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                {{-- <div class="col-md-1">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="activate_attributes" @if (count($product->getChildrenProducts()) > 0 && count($attributes) > 0) checked @endif>
                                    <span></span>
                                </label>
                            </div> --}}
                            </div>
                            <div id="bloc_variants_created" style="margin-top: 42px;">
                                @if (count($product->getChildrenProductsDesc()) > 0)
                                    @foreach ($product->getChildrenProductsDesc() as $key => $children)
                                        <div data-id="{{ $children->id }}">
                                            <div class="row">
                                                <div class="col-3">
                                                    <h3 class="mb-3">Variant information {{ $key + 1 }}</h3>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ translate('Variant SKU') }}" disabled>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control sku" id="sku"
                                                        name="variant[sku][{{ $children->id }}]"
                                                        value="{{ $children->sku }}">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ translate('Variant Photos') }}" disabled>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="custom-file mb-3">
                                                        <input type="file" class="custom-file-input photos_variant"
                                                            name="variant[photo][{{ $children->id }}][]"
                                                            id="photos_variant{{ $key }}"
                                                            accept=".jpeg, .jpg, .png" multiple>
                                                        <label class="custom-file-label"
                                                            for="photos_variant{{ $key }}">Choose files</label>
                                                    </div>
                                                    @if (count($children->getImagesProduct()) > 0)
                                                        <div class="row mt-3">
                                                            @foreach ($children->getImagesProduct() as $image)
                                                                <div class="col-2 container-img">
                                                                    <img src="{{ asset('/public/' . $image->path) }}"
                                                                        height="120" width="120"
                                                                        @if (in_array($image->id, $historique_images)) data-toggle="tooltip" data-html="true" title="Image added" style="border: 4px solid green" @endif />
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ translate('Use default pricing configuration') }}"
                                                        disabled>
                                                </div>
                                                <div class="col-md-10">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input value="1" type="checkbox"
                                                            name="variant-pricing-{{ $children->id }}"
                                                            class="variant-pricing"
                                                            @if (count($children->getPricingConfiguration()) == 0) checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                                <div class="bloc_pricing_configuration_variant">
                                                    @if (count($children->getPricingConfiguration()) > 0)
                                                        <table class="table" class="bloc_pricing_configuration_variant">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ translate('From Quantity') }}</th>
                                                                    <th>{{ translate('To Quantity') }}</th>
                                                                    <th>{{ translate('Unit Price (VAT Exclusive)') }}</th>
                                                                    <th>{{ translate('Discount(Start/End)') }}</th>
                                                                    <th>{{ translate('Discount Type') }}</th>
                                                                    <th>{{ translate('Discount Amount') }}</th>
                                                                    <th>{{ translate('Discount Percentage') }}</th>
                                                                    <th>{{ translate('Action') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="bloc_pricing_configuration">
                                                                @foreach ($children->getPricingConfiguration() as $pricing)
                                                                    <tr>
                                                                        <td><input type="number"
                                                                                name="variant[from][{{ $children->id }}][]"
                                                                                class="form-control min-qty"
                                                                                id=""
                                                                                value="{{ $pricing->from }}"></td>
                                                                        <td><input type="number"
                                                                                name="variant[to][{{ $children->id }}][]"
                                                                                class="form-control max-qty"
                                                                                id=""
                                                                                value="{{ $pricing->to }}"></td>
                                                                        <td><input type="number"
                                                                                name="variant[unit_price][{{ $children->id }}][]"
                                                                                class="form-control unit-price-variant"
                                                                                id=""
                                                                                value="{{ $pricing->unit_price }}"></td>
                                                                        @php
                                                                            $date_range = '';
                                                                            if ($pricing->discount_start_datetime) {
                                                                                $start_date = new DateTime($pricing->discount_start_datetime);
                                                                                $start_date_formatted = $start_date->format('d-m-Y H:i:s');

                                                                                $end_date = new DateTime($pricing->discount_end_datetime);
                                                                                $end_date_formatted = $end_date->format('d-m-Y H:i:s');

                                                                                $date_range = $start_date_formatted . ' to ' . $end_date_formatted;
                                                                            }
                                                                        @endphp
                                                                        <td><input type="text"
                                                                                class="form-control aiz-date-range discount-range"
                                                                                value="{{ $date_range }}"
                                                                                name="variant[date_range_pricing][{{ $children->id }}][]"
                                                                                placeholder="{{ translate('Select Date') }}"
                                                                                data-time-picker="true"
                                                                                data-separator=" to "
                                                                                data-format="DD-MM-Y HH:mm:ss"
                                                                                autocomplete="off"></td>
                                                                        <td>
                                                                            <select class="form-control discount_type"
                                                                                name="variant[discount_type][{{ $children->id }}][]">
                                                                                <option value="" selected>
                                                                                    {{ translate('Choose type') }}
                                                                                </option>
                                                                                <option value="amount"
                                                                                    @selected($pricing->discount_type == 'amount')>
                                                                                    {{ translate('Flat') }}</option>
                                                                                <option value="percent"
                                                                                    @selected($pricing->discount_type == 'percent')>
                                                                                    {{ translate('Percent') }}</option>
                                                                            </select>
                                                                        </td>
                                                                        <td><input type="number"
                                                                                class="form-control discount_amount"
                                                                                value="{{ $pricing->discount_amount }}"
                                                                                name="variant[discount_amount][{{ $children->id }}][]">
                                                                        </td>
                                                                        <td><input type="number"
                                                                                class="form-control discount_percentage"
                                                                                value="{{ $pricing->discount_percentage }}"
                                                                                name="variant[discount_percentage][{{ $children->id }}][]">
                                                                        </td>
                                                                        <td>
                                                                            <i class="las la-plus btn-add-pricing"
                                                                                data-id_variant="{{ $children->id }}"
                                                                                style="margin-left: 5px; margin-top: 17px;"
                                                                                title="Add another ligne"></i>
                                                                            <i class="las la-trash delete_pricing_canfiguration"
                                                                                data-pricing_id="{{ $pricing->id }}"
                                                                                style="margin-left: 5px; margin-top: 17px;"
                                                                                title="Delete this ligne"></i>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ translate('Use default Shipping') }}" disabled>
                                                </div>
                                                <div class="col-md-10">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input value="1" type="checkbox" class="variant-shipping"
                                                            data-id_variant="{{ $children->id }}"
                                                            @if (count($children->getShipping()) == 0) checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>

                                                <div class="col-12 mt-3" id="bloc_default_shipping">
                                                    @if (count($children->getShipping()) > 0)
                                                        <table class="bloc_shipping_configuration_variant table" id="table_shipping_configuration">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ translate('Shipper') }}</th>
                                                                    <th>{{ translate('From Quantity') }}</th>
                                                                    <th>{{ translate('To Quantity') }}</th>
                                                                    <th>{{ translate('Estimated Order Preparation Days') }}
                                                                    </th>
                                                                    <th>{{ translate('Estimated Shipping Days') }}</th>
                                                                    <th>{{ translate('Paid by') }}</th>
                                                                    <th>{{ translate('Shipping Charge') }}</th>
                                                                    <th>{{ translate('Flat-rate Amount') }}</th>
                                                                    <th>{{ translate('Charge per Unit of Sale') }}</th>
                                                                    <th>{{ translate('Action') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="bloc_shipping_configuration">
                                                                @foreach ($children->getShipping() as $key => $shipping)
                                                                    <tr>
                                                                        <td>
                                                                            <select class="form-control shipper"
                                                                                name="variant[shipper][{{ $children->id }}][{{ $key }}][]">
                                                                                <option value="vendor"
                                                                                    @selected($shipping->shipper === 'vendor')>
                                                                                    {{ translate('vendor') }}</option>
                                                                                <option value="third_party"
                                                                                    @selected($shipping->shipper === 'third_party')>
                                                                                    {{ translate('MawadOnline 3rd Party Shippers') }}
                                                                                </option>
                                                                            </select>
                                                                        </td>
                                                                        <td><input type="number"
                                                                                name="variant[from_shipping][{{ $children->id }}][]"
                                                                                value="{{ $shipping->from_shipping }}"
                                                                                class="form-control min-qty-shipping"
                                                                                id=""></td>
                                                                        <td><input type="number"
                                                                                name="variant[to_shipping][{{ $children->id }}][]"
                                                                                value="{{ $shipping->to_shipping }}"
                                                                                class="form-control max-qty-shipping"
                                                                                id=""></td>
                                                                        <td><input type="number"
                                                                                class="form-control estimated_order"
                                                                                value="{{ $shipping->estimated_order }}"
                                                                                name="variant[estimated_order][{{ $children->id }}][]">
                                                                        </td>
                                                                        <td><input type="number"
                                                                                class="form-control estimated_shipping"
                                                                                value="{{ $shipping->estimated_shipping }}"
                                                                                name="variant[estimated_shipping][{{ $children->id }}][]">
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control paid"
                                                                                name="variant[paid][{{ $children->id }}][]">
                                                                                <option value="" selected>
                                                                                    {{ translate('Choose shipper') }}
                                                                                </option>
                                                                                <option value="vendor"
                                                                                    @if ($shipping->paid == 'vendor') {{ 'selected' }} @endif>
                                                                                    {{ translate('vendor') }}</option>
                                                                                <option value="buyer"
                                                                                    @if ($shipping->paid == 'buyer') {{ 'selected' }} @endif>
                                                                                    {{ translate('Buyer') }}</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control shipping_charge"
                                                                                name="variant[shipping_charge][{{ $children->id }}][]">
                                                                                <option value="" selected>
                                                                                    {{ translate('Choose shipping charge') }}
                                                                                </option>
                                                                                <option value="flat"
                                                                                    @if ($shipping->shipping_charge == 'flat') {{ 'selected' }} @endif>
                                                                                    {{ translate('Flat-rate regardless of quantity') }}
                                                                                </option>
                                                                                <option value="charging"
                                                                                    @if ($shipping->shipping_charge == 'charging') {{ 'selected' }} @endif>
                                                                                    {{ translate('Charging per Unit of Sale') }}
                                                                                </option>
                                                                            </select>
                                                                        </td>
                                                                        <td><input type="number"
                                                                                class="form-control flat_rate_shipping"
                                                                                value="{{ $shipping->flat_rate_shipping }}"
                                                                                name="variant[flat_rate_shipping][{{ $children->id }}][]"
                                                                                readonly></td>
                                                                        <td><input type="number"
                                                                                class="form-control charge_per_unit_shipping"
                                                                                value="{{ $shipping->charge_per_unit_shipping }}"
                                                                                name="variant[charge_per_unit_shipping][{{ $children->id }}][]"
                                                                                readonly></td>
                                                                        <td>
                                                                            <i class="las la-plus btn-add-shipping"
                                                                                data-id_variant="{{ $children->id }}"
                                                                                style="margin-left: 5px; margin-top: 17px;"
                                                                                title="Add another ligne"></i>
                                                                            @if ($key != 0)
                                                                                <i class="las la-trash delete_shipping_canfiguration"
                                                                                    data-id="{{ $shipping->id }}"
                                                                                    data-id_variant="{{ $children->id }}"
                                                                                    style="margin-left: 5px; margin-top: 17px;"
                                                                                    title="Delete this ligne"></i>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ translate('Sample Available?') }}" disabled>
                                                </div>
                                                <div class="col-md-10">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input value="1" type="checkbox"
                                                            class="variant-sample-available"
                                                            name="variant[sample_available][{{ $children->id }}]"
                                                            @if ($children->sample_available == 1) checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ translate('Use default sample pricing configuration') }}"
                                                        disabled>
                                                </div>
                                                <div class="col-md-10">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input value="1" type="checkbox"
                                                            name="variant[sample_pricing][{{ $children->id }}]"
                                                            data-variant="{{ $children->id }}"
                                                            class="variant-sample-pricing"
                                                            @if ($children->sample_price == null) checked @endif
                                                            @if ($children->sample_available != 1) disabled @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                                <div class="bloc_sample_pricing_configuration_variant">
                                                    @if ($children->sample_price != null)
                                                        <div class="row mb-3">
                                                            <div class="col-md-2" style="padding-right: 0;">
                                                                <input type="text" class="form-control"
                                                                    value="{{ translate('Sample description') }}"
                                                                    disabled>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <textarea class="form-control sample_description" name="variant[sample_description][{{ $children->id }}]">{{ $children->sample_description }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-2" style="padding-right: 0;">
                                                                <input type="text" class="form-control"
                                                                    value="{{ translate('Sample price') }}" disabled>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control sample_price"
                                                                    name="variant[sample_price][{{ $children->id }}]"
                                                                    value="{{ $children->sample_price }}">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ translate('Use default sample shipping') }}" disabled>
                                                </div>
                                                <div class="col-md-10">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input value="1" type="checkbox"
                                                            class="variant-sample-shipping"
                                                            name="variant[sample_shipping][{{ $children->id }}]"
                                                            @if ($children->shipper_sample != null) checked @endif
                                                            @if ($children->sample_available != 1) disabled @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                                <div class="col-12 mt-3" id="bloc-sample-shipping">
                                                    <table class="table" id="table_sample_configuration"
                                                        class="bloc_sample_configuration_variant">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ translate('Shipping-by') }}</th>
                                                                <th>{{ translate('Estimated Sample Preparation Days') }}
                                                                </th>
                                                                <th>{{ translate('Estimated Shipping Days') }}</th>
                                                                <th>{{ translate('Paid by') }}</th>
                                                                <th>{{ translate('Shipping amount') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="bloc_sample_configuration">
                                                            @foreach($children->getSampleShipping() as $key => $shipping)
                                                                <tr>
                                                                    <td>
                                                                        <select class="form-control shipper_sample"
                                                                            name="shipper_sample[]">
                                                                            <option value="vendor"
                                                                                @selected($shipping->shipper === "vendor")>
                                                                                {{ translate('vendor') }}</option>
                                                                            <option value="third_party"
                                                                                @selected($shipping->shipper === 'third_party')>
                                                                                {{ translate('MawadOnline 3rd Party Shippers') }}
                                                                            </option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number"
                                                                            class="form-control estimated_sample"
                                                                            name="variant[estimated_sample][{{ $children->id }}]"
                                                                            @if ($shipping->estimated_order != null) value="{{ $shipping->estimated_order }}" @endif>
                                                                    </td>
                                                                    <td><input type="number"
                                                                            class="form-control estimated_shipping_sample"
                                                                            name="variant[estimated_shipping_sample][{{ $children->id }}]"
                                                                            @if ($shipping->estimated_shipping != null) value="{{ $shipping->estimated_shipping }}" @endif>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control paid_sample"
                                                                            name="variant[paid_sample][{{ $children->id }}]">
                                                                            <option value="" selected>
                                                                                {{ translate('Choose paid by') }}</option>
                                                                            <option
                                                                                value="vendor" @selected($shipping->paid == 'vendor')>
                                                                                {{ translate('vendor') }}</option>
                                                                            <option value="buyer"
                                                                                @selected($shipping->paid == 'buyer')>
                                                                                {{ translate('Buyer') }}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number"
                                                                            class="form-control shipping_amount"
                                                                            name="variant[shipping_amount][{{ $children->id }}]"
                                                                            @if ($shipping->flat_rate_shipping != null) value="{{ $shipping->flat_rate_shipping }}" @else readonly @endif>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ translate('Published') }}" disabled>
                                                </div>
                                                <div class="col-md-10">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input value="1" type="checkbox" class="variant-published"
                                                            name="variant[published][{{ $children->id }}]"
                                                            value="{{ $children->published }}"
                                                            @if ($children->published == 1) checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ translate('Low-Stock Warning') }}" disabled>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="number" class="form-control stock-warning"
                                                        id="low_stock_warning"
                                                        name="variant[low_stock_quantity][{{ $children->id }}]"
                                                        value="{{ $children->low_stock_quantity }}">
                                                </div>
                                            </div>
                                            <div id="bloc_attributes">
                                                @if (count($children->getIdsAttributesVariant()) > 0)
                                                    @include(
                                                        'seller.product.products.variant_attributes',
                                                        [
                                                            'attributes' => $attributes,
                                                            'variants_attributes_ids_attributes' => $children->getIdsAttributesVariant(),
                                                            'variants_attributes' => $children->getAttributesVariant(),
                                                            'children' => $children,
                                                        ]
                                                    )
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Bloc General Attributes --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('General Attributes') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div id="general_attributes">
                                    @if (count($general_attributes_ids_attributes) > 0)
                                        <div class="row" style="padding-left: 16px !important;">
                                            @include('seller.product.products.general_attributes', [
                                                'data_general_attributes_color_added' => $data_general_attributes_color_added,
                                                'attributes' => $attributes,
                                                'general_attributes_ids_attributes' => $general_attributes_ids_attributes,
                                                'general_attributes' => $general_attributes,
                                            ])
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Bloc Product Description --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Description') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-from-label">{{ translate('Description') }}</label>
                                <div class="col-md-10">
                                    <textarea class="aiz-text-editor" name="description"
                                        @if (array_key_exists('description', $general_informations)) data-value="{{ $general_informations['description'] }}" style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old link is: {{ $general_informations['description'] }}" @endif>{{ $product->description }}</textarea>
                                    <input type="hidden" id="hidden_value" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Bloc Product Documents --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('PDF Specification') }}</h5>
                        </div>
                        <div class="card-body" id="documents_bloc">
                            @if (count($product->getDocumentsProduct()) > 0)
                                @foreach ($product->getDocumentsProduct() as $key => $document)
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Document name</label>
                                                <input type="text" class="form-control"
                                                    @if (array_key_exists($document->id, $data_historique_documents)) @if ($data_historique_documents[$document->id]['action'] == 'update')
                                                                                            @if (array_key_exists('document_name', $data_historique_documents[$document->id]))
                                                                                                data-toggle="tooltip" data-html="true" title="Modified and old document name is: {{ $data_historique_documents[$document->id]['document_name'] }}" @endif
                                                @else title="New document" @endif
                                                style="border-color: {{ $data_historique_documents[$document->id]['border_color'] }} !important;"
                                @endif
                                name="old_document_names[{{ $document->id }}]" value="{{ $document->document_name }}">
                        </div>
                    </div>
                    <div class="col-2">
                        <a href="{{ asset('/public/' . $document->path) }}" download>
                            <i class="las la-download font-size-icon"
                                @if (array_key_exists($document->id, $data_historique_documents)) @if ($data_historique_documents[$document->id]['action'] == 'update')
                                                                                                @if (array_key_exists('path', $data_historique_documents[$document->id]))
                                                                                                    data-toggle="tooltip" data-html="true" title="The document is modified" @endif
                            @else title="New document" @endif
                                style="border-color: {{ $data_historique_documents[$document->id]['border_color'] }} !important;"
                                @endif
                                ></i>
                        </a>
                    </div>
                </div>
                @endforeach
            @else
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
                            <div class="input-group padding-name-document">
                                <div class="custom-file">
                                    <input type="file" name="documents[]"
                                        accept=".pdf,.png,.jpg,.pln,.dwg,.dxf,.gsm,.stl,.rfa,.rvt,.ifc,.3ds,.max,.obj,.fbx,.skp,.rar,.zip"
                                        class="custom-file-input file_input" id="inputGroupFile04"
                                        aria-describedby="inputGroupFileAddon04">
                                    <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <i class="las la-plus add_document font-size-icon" style="margin-left: 5px; margin-top: 34px;"
                            title="Add another document"></i>
                        <i class="las la-trash trash_document font-size-icon" style="margin-left: 5px; margin-top: 34px;"
                            title="Delete this document"></i>
                    </div>
                </div>
                @endif
            </div>
    </div>
    {{-- Bloc SEO Meta --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-2 col-from-label">{{ translate('Meta Title') }}</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="meta_title"
                        @if (array_key_exists('meta_title', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old link is: {{ $general_informations['meta_title'] }}" @endif
                        value="{{ $product->meta_title }}" placeholder="{{ translate('Meta Title') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-from-label">{{ translate('Description') }}</label>
                <div class="col-md-10">
                    <textarea name="meta_description" rows="8" class="form-control"
                        @if (array_key_exists('meta_description', $general_informations)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old link is: {{ $general_informations['meta_description'] }}" @endif>{{ $product->meta_description }}</textarea>
                </div>
            </div>
            {{-- <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Meta Image') }}</label>
                            <div class="col-md-10">
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
                        </div> --}}
        </div>
    </div>
    </div>
    <div class="col-12">

    </div>
    <!-- show modal -->
    <div class="modal" tabindex="-1" role="dialog" id="reject">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rejection reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Reason</label>
                        <textarea class="form-control" id="reason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="save_reason" data-id=""
                        data-status="">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </form>
    </div>

@endsection

@section('script')
    <!-- Treeview js -->
    <script src="{{ static_asset('assets/js/hummingbird-treeview.js') }}"></script>
    <script src="{{ static_asset('assets/js/countrySelect.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="{{ static_asset('assets/js/jquery.multi-select.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            $(".plus").click(function() {
                $(this).toggleClass("minus").siblings("ul").toggle();
            })
        })

        function check_fst_lvl(dd) {
            //var ss = $('#' + dd).parents("ul[id^=bs_l]").attr("id");
            var ss = $('#' + dd).parent().closest("ul").attr("id");
            if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]')
                .length) {
                //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', true);
                $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
            } else {
                //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', false);
                $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
            }

        }

        function pageLoad() {
            $(".plus").click(function() {
                $(this).toggleClass("minus").siblings("ul").toggle();
            })
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.shipper').multiSelect();
            $('.shipper_sample').multiSelect();

            @if (count($product->getChildrenProducts()) > 0)
                $('#variant_informations').show();
            @else
                $('#variant_informations').hide();
            @endif

            @if (count($product->getChildrenProducts()) > 0)
                $('#btn-create-variant').show();
            @else
                $('#btn-create-variant').hide();
            @endif

            @if (array_key_exists('tags', $general_informations))
                var tags = "{{ $general_informations['tags'] }}";
                var tagsWithSpace = tags.replace(/,/g, ', ');

                $('.aiz-tag-input').attr('style', 'border: 1px solid #FF3C50 !important');
                $('.aiz-tag-input').attr('data-toggle', 'tooltip');
                $('.aiz-tag-input').attr('data-html', 'true');
                $('.aiz-tag-input').attr('title', tagsWithSpace);
            @endif

            // $('body button.btn.dropdown-toggle.btn-light').attr('style', 'border: 2px solid red !important');

            $('body .aiz-selectpicker').each(function() {
                if ($(this).attr('style') !== undefined) {
                    $(this).parent().find('button.btn.dropdown-toggle.btn-light').attr(
                        'data-tooltip-toggle', 'tooltip');
                    $(this).parent().find('button.btn.dropdown-toggle.btn-light').tooltip();
                    var value = $(this).attr('data-value');
                    var added = $(this).attr('data-added');
                    var type = $(this).attr('data-type');

                    if (added != undefined) {
                        if (type === 'color') {
                            $(this).parent().find('button.btn.dropdown-toggle.btn-light').attr('style',
                                'border: 1px solid green !important');
                            $(this).parent().find('button.btn.dropdown-toggle.btn-light').attr(
                                'data-original-title', 'Color added');
                        } else {
                            $(this).parent().find('button.btn.dropdown-toggle.btn-light').attr('style',
                                'border: 1px solid green !important');
                            $(this).parent().find('button.btn.dropdown-toggle.btn-light').attr(
                                'data-original-title', 'Attribute added');
                        }

                    } else {
                        $(this).parent().find('button.btn.dropdown-toggle.btn-light').attr('style',
                            'border: 1px solid #FF3C50 !important');
                        $(this).parent().find('button.btn.dropdown-toggle.btn-light').attr(
                            'data-original-title', 'Modified and old value is: ' + value);
                    }

                }
            })

            $('body .aiz-text-editor').each(function() {
                if ($(this).data('value') !== undefined) {
                    $(this).parent().find('.note-editor.note-frame.card').attr('style',
                        'border: 1px solid #FF3C50 !important');
                    var value = $(this).attr('data-value');
                    $(this).parent().find('.note-editor.note-frame.card').attr('data-tooltip-toggle',
                        'tooltip');
                    $(this).parent().find('.note-editor.note-frame.card').tooltip({
                        html: true
                    });
                    $(this).parent().find('.note-editor.note-frame.card').attr('data-html', 'true');
                    $(this).parent().find('.note-editor.note-frame.card').attr('data-original-title',
                        'Modified and old value is: ' + value);
                }
            })



            $('body #bloc_pricing_configuration_variant').hide();
            $('body #bloc_sample_pricing_configuration_variant').hide();
            $('body .btn-variant-pricing').hide();
            var numbers_variant = "{{ count($product->getChildrenProducts()) }}";
            numbers_variant = parseInt(numbers_variant);

            var initial_attributes = $('#attributes').val();
            Array.prototype.diff = function(a) {
                return this.filter(function(i) {
                    return a.indexOf(i) < 0;
                });
            };
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#summernote').summernote();
            $("#country_selector").countrySelect({
                responsiveDropdown: true,
                preferredCountries: ['ae']
            });

            $('.status').on('change', function() {
                id_variant = $(this).data('id');
                var status = $(this).val();

                if (($(this).val() == 2) || ($(this).val() == 3)) {
                    $('#reject').modal('show');
                    $('body #save_reason').attr('data-id', id_variant);
                    $('body #save_reason').attr('data-status', $(this).val());
                } else {
                    $.ajax({
                        url: "{{ route('products.approve_action') }}",
                        type: "GET",
                        data: {
                            id_variant: id_variant,
                            status: status,
                            reason: '',
                        },
                        cache: false,
                        dataType: 'JSON',
                        success: function(dataResult) {
                            if (dataResult.status == 'success') {
                                AIZ.plugins.notify('success',
                                    '{{ translate('Status updated') }}');
                            } else {
                                AIZ.plugins.notify('danger',
                                    '{{ translate('Something went wrong') }}');
                            }

                            $('#reject').modal('hide');
                        }
                    })
                }
            });

            $('body').on('click', '#save_reason', function() {
                var id_variant = $(this).data('id');
                var status = $(this).data('status');
                var reason = $('body #reason').val();

                $.ajax({
                    url: "{{ route('products.approve_action') }}",
                    type: "GET",
                    data: {
                        id_variant: id_variant,
                        status: status,
                        reason: reason,
                    },
                    cache: false,
                    dataType: 'JSON',
                    success: function(dataResult) {
                        if (dataResult.status == 'success') {
                            AIZ.plugins.notify('success',
                                '{{ translate('Status updated') }}');
                        } else {
                            AIZ.plugins.notify('danger',
                                '{{ translate('Something went wrong') }}');
                        }

                        $('#reject').modal('hide');
                    }
                })
            })

        });
    </script>
@endsection
