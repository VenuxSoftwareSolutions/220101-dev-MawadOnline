@extends('seller.layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <style>
        #image-preview {
            display: flex;
            flex-wrap: wrap;
        }

        #image-preview img {
            margin: 5px;
        }

        #image-preview-thumbnail {
            display: flex;
            flex-wrap: wrap;
        }

        #image-preview-thumbnail img {
            margin: 5px;
        }

        .preview-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 5px;
        }

        .preview-container img {
            max-width: 100px;
            max-height: 100px;
        }

        .preview-container button {
            margin-top: 5px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .preview-container-thumbnail {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 5px;
        }

        .preview-container-thumbnail img {
            max-width: 100px;
            max-height: 100px;
        }

        .preview-container-thumbnail button {
            margin-top: 5px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>

    @if (app()->getLocale() == 'ae')
        <style>
            .multi-select-menuitem input {
                position: relative !important;
                margin-left: 0 !important;
            }
        </style>
    @endif

    <style>
        .table th {
            font-size: 12px !important;
        }

        .swal2-icon .swal2-icon-content {
            display: flex;
            align-items: center;
            font-size: 0.75em !important;
        }

        .button-container {
            position: fixed;
            top: 1%;
            right: 25%;
            z-index: 97;
        }

        .preview-button {
            background-color: #cb774b !important;
            border: none;
            color: white;
            padding: 15px 25px;
            text-align: center;
            text-decoration: none;
            display: inline-block;

            cursor: pointer;
            border-radius: 8px;
        }

        #short_description {
            height: 83px;
        }

        th {
            border: none !important;
        }

        .table td,
        .table th {
            padding: 7px !important;
        }

        .table input[type="number"] {
            width: 96px !important;
        }

        .error {
            border-color: red !important;
        }

        .multi-select-button {
            height: 44px;
            border: 1px solid #e2e5ec !important;
            box-shadow: none !important;
        }

        h6 {
            font-size: 0.9rem !important;
        }

        .form-control {
            color: #222224 !important;
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

        .disabled-select {
            background-color: #f7f8fa !important;
        }

        .clonedDiv {
            margin-top: 60px;
        }

        .col-from-label {
            weight: 400 !important;
            font-size: 14px !important;
            line-height: 24px !important;
        }

        .form-group.row {
            display: flex;
            align-items: center;
        }

        .form-group.row .col-md-3 {
            text-align: left;
        }

        input.form-control {
            border-radius: 0;
        }

        @media screen and (min-width: 1800px) {
            .icon-delete-image {
                position: absolute;
                color: red;
                top: 0;
                right: 31px !important;
            }
        }

        @media screen and (max-width: 1799px) {
            .icon-delete-image {
                position: absolute;
                color: red;
                top: 0;
                right: -11px !important;
            }
        }
    </style>
@endpush

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Add Your Product') }}</h1>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="button-container">
        <button type="button" class="preview-button" onclick="submitForm()">{{ __('product.PreviewProduct') }}</button>
    </div>

    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" id="choice_form">
        @csrf
        <div class="row gutters-5">
            <div class="col-lg-12">
                {{-- Bloc Product Information --}}
                <input type="hidden" id="published_after_approve" value="0" name="published_modal">
                <input type="hidden" id="create_stock" value="0" name="create_stock">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Product Name') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input id="nameProduct" type="text" class="form-control" required name="name"
                                            value="{{ old('name') }}" placeholder="{{ translate('Product Name') }}">
                                    </div>
                                </div>
                                <div class="form-group row" id="brand">
                                    <label class="col-md-3 col-from-label">{{ translate('Brand') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                            data-live-search="true">
                                            <option value="">{{ translate('Select Brand') }}</option>
                                            @foreach (\App\Models\Brand::all() as $brand)
                                                <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>
                                                    {{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Unit of Sale') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="unit"
                                            value="{{ old('unit') }}"
                                            placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">
                                        {{ __('Unit of Sale Price') }}
                                        <small>({{ __('VAT Exclusive') }})</small>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" name="unit_sale_price"
                                            value="{{ old('unit_sale_price') }}"
                                            placeholder="{{ __('Unit of Sale Price') }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Country of origin') }}</label>
                                    <div class="col-md-8">
                                        <div class="form-item">
                                            <input id="country_selector" name="country_selector" type="text"
                                                value="{{ old('country_selector') }}">
                                            <label for="country_selector" style="display:none;">Select a country
                                                here...</label>
                                        </div>
                                        <div class="form-item" style="display:none;">
                                            <input type="text" id="country_selector_code" name="country_code"
                                                data-countrycodeinput="1" readonly="readonly"
                                                placeholder="Selected country code will appear here" />
                                            <label for="country_selector_code">...and the selected country code will be
                                                updated here</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Manufacturer') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="manufacturer"
                                            value="{{ old('manufacturer') }}"
                                            placeholder="{{ translate('Manufacturer') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Tags') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control aiz-tag-input" id="tags"
                                            name="tags[]"
                                            placeholder="{{ translate('Type and hit enter to add a tag') }}"
                                            value="{{ old('tags.0') }}">
                                        <small
                                            class="text-muted">{{ translate('This is used for search. Input those words by which cutomer can find this product.') }}</small>
                                        <div id="error-message" style="display:none; color: red">
                                            {{ translate('tags input cannot be empty') }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Short description') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" name="short_description" id="short_description">{{ old('short_description') }}</textarea>
                                        <div id="charCountShortDescription">{{ translate('Remaining characters:') }} 512
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Show Stock Quantity') }}</label>
                                    <div class="col-md-8">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="stock_visibility_state" value="1"
                                                checked="checked">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                @if (addon_is_activated('pos_system'))
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ translate('Barcode') }}</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="barcode"
                                                value="{{ old('barcode') }}" placeholder="{{ translate('Barcode') }}">
                                        </div>
                                    </div>
                                @endif

                                @if (addon_is_activated('refund_request'))
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ translate('Refundable') }}</label>
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
                    </div>
                </div>
                {{-- Bloc Product images --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Media') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="signinSrEmail">{{ translate('Gallery Images') }}
                                <small>(1280x1280)</small></label>
                            <div class="col-md-8" id="bloc_photos">
                                <input type="file" class="form-control" name="main_photos[]" id="photoUploadcustom"
                                    onchange="previewImages(event)" value="{{ old('main_photos.0') }}"
                                    accept=".jpeg, .jpg, .png" multiple />
                                <div id="image-preview"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="signinSrEmail">{{ translate('Thumbnail Image') }}
                                <small>(400x400)</small></label>
                            <div class="col-md-8" id="bloc_thumbnails">
                                <input type="file" class="form-control" name="photosThumbnail[]"
                                    id="photoUploadThumbnailSeconde" onchange="previewImagesThumbnail(event)"
                                    accept=".jpeg, .jpg, .png" multiple value="{{ old('photosThumbnail.0') }}" />
                                <small
                                    style="display: block; margin-top: 12px;">{{ translate('Thumbnail images will be generated automatically from gallery images if not specified') }}</small>
                                <div id="image-preview-thumbnail"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="row" style="margin-left: -3px;">
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label"
                                        style="padding-top: 12px; padding-right: 0px;padding-left: 0px;">{{ translate('Video Provider') }}</label>
                                    <div class="col-md-7">
                                        <select class="form-control aiz-selectpicker" name="video_provider"
                                            id="video_provider">
                                            <option value="youtube" @selected(old('video_provider') == 'youtube')>
                                                {{ translate('Youtube') }}</option>
                                            <option value="vimeo" @selected(old('video_provider') == 'vimeo')>{{ translate('Vimeo') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group row">
                                    <label class="col-md-1 col-from-label"
                                        style="padding-top: 12px; padding-right: 0px;">{{ translate('Video Link') }}</label>
                                    <div class="col-md-11">
                                        <input type="text" class="form-control" name="video_link"
                                            value="{{ old('video_link') }}" placeholder="{{ translate('Video Link') }}">
                                        <small
                                            class="text-muted">{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card default-pricing-wrapper__clz">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Default Pricing Configuration') }}</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <div id="bloc-pricing-parent" class="bloc-default-shipping-style d-none">
                                <h6>{{ translate('Default Product Pricing Configuration') }}</h6>
                                <hr>
                                <table class="table" id="table_pricing_configuration"
                                    class="bloc_pricing_configuration_variant">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('From QTY') }}</th>
                                            <th>{{ translate('To QTY') }}</th>
                                            <th>{{ translate('Unit Price (VAT Exclusive)') }}</th>
                                            <th>{{ translate('Discount(Start/End)') }}</th>
                                            <th>{{ translate('Discount Type') }}</th>
                                            <th>{{ translate('Discount Amount') }}</th>
                                            <th>{{ translate('Discount Percentage') }}</th>
                                            <th>{{ translate('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bloc_pricing_configuration">
                                        <tr>
                                            <td><input type="number" name="from[]" min="1"
                                                    class="form-control min-qty" id="min-qty-parent"
                                                    placeholder="{{ translate('From QTY') }}"
                                                    value="{{ old('from.0') }}"></td>
                                            <td><input type="number" name="to[]" min="1"
                                                    class="form-control max-qty" id="max-qty-parent"
                                                    placeholder="{{ translate('To QTY') }}" value="{{ old('to.0') }}">
                                            </td>
                                            <td><input type="number" name="unit_price[]" step="0.01" min="1"
                                                    placeholder="{{ translate('Unit Price') }}"
                                                    class="form-control unit-price-variant" id="unit-price-parent"
                                                    value="{{ old('unit_price.0') }}"></td>
                                            <td><input type="text" class="form-control aiz-date-range discount-range"
                                                    name="date_range_pricing[]"
                                                    placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                                    data-separator=" to " data-format="DD-MM-Y HH:mm:ss"
                                                    autocomplete="off" value="{{ old('date_range_pricing.0') }}"></td>
                                            <td>
                                                <select class="form-control discount_type" name="discount_type[]">
                                                    <option value="" @selected(old('discount_type.0') == null)>
                                                        {{ translate('Choose type') }}
                                                    </option>
                                                    <option value="amount" @selected(old('discount_type.0') == 'amount')>
                                                        {{ translate('Flat') }}</option>
                                                    <option value="percent" @selected(old('discount_type.0') == 'percent')>
                                                        {{ translate('Percent') }}</option>
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control discount_amount"
                                                    name="discount_amount[]" placeholder="{{ translate('Amount') }}"
                                                    value="{{ old('discount_amount.0') }}" readonly></td>
                                            <td style="width: 22% !important;">
                                                <div class="col-md-9 input-group">
                                                    <input type="number" class="form-control discount_percentage"
                                                        name="discount_percentage[]"
                                                        placeholder="{{ translate('Percentage') }}"
                                                        value="{{ old('discount_percentage.0') }}" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="las la-plus btn-add-pricing"
                                                    style="margin-left: 5px; margin-top: 17px;"
                                                    title="Add another ligne"></i>
                                            </td>
                                        </tr>
                                        @if (count(old('from', [])) > 1)
                                            @foreach (old('from', []) as $index => $value)
                                                @if ($index > 0)
                                                    <tr>
                                                        <td><input type="number" name="from[]" min="1"
                                                                class="form-control min-qty" id="min-qty-parent"
                                                                placeholder="{{ translate('From QTY') }}"
                                                                value="{{ old('from', [])[$index] }}"></td>
                                                        <td><input type="number" name="to[]" min="1"
                                                                class="form-control max-qty" id="max-qty-parent"
                                                                placeholder="{{ translate('To QTY') }}"
                                                                value="{{ old('to', [])[$index] }}">
                                                        </td>
                                                        <td><input type="number" name="unit_price[]" step="0.01"
                                                                min="1"
                                                                placeholder="{{ translate('Unit Price') }}"
                                                                class="form-control unit-price-variant"
                                                                id="unit-price-parent"
                                                                value="{{ old('unit_price', [])[$index] }}">
                                                        </td>
                                                        <td><input type="text"
                                                                class="form-control aiz-date-range discount-range"
                                                                name="date_range_pricing[]"
                                                                placeholder="{{ translate('Select Date') }}"
                                                                data-time-picker="true" data-separator=" to "
                                                                data-format="DD-MM-Y HH:mm:ss" autocomplete="off"
                                                                value="{{ old('date_range_pricing', [])[$index] }}"></td>
                                                        <td>
                                                            <select class="form-control discount_type"
                                                                name="discount_type[]">
                                                                <option value="" selected>
                                                                    {{ translate('Choose type') }}
                                                                </option>
                                                                <option value="amount" @selected(old('discount_type', [])[$index] == 'amount')>
                                                                    {{ translate('Flat') }}</option>
                                                                <option value="percent" @selected(old('discount_type', [])[$index] == 'percent')>
                                                                    {{ translate('Percent') }}</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control discount_amount"
                                                                name="discount_amount[]"
                                                                placeholder="{{ translate('Amount') }}"
                                                                value="{{ old('discount_amount', [])[$index] }}" readonly>
                                                        </td>
                                                        <td style="width: 22% !important;">
                                                            <div class="col-md-9 input-group">
                                                                <input type="number"
                                                                    class="form-control discount_percentage"
                                                                    name="discount_percentage[]"
                                                                    placeholder="{{ translate('Percentage') }}"
                                                                    value="{{ old('discount_percentage', [])[$index] }}"
                                                                    readonly>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <i class="las la-plus btn-add-pricing"
                                                                style="margin-left: 5px; margin-top: 17px;"
                                                                title="Add another ligne"></i>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="bloc-default-shipping-style" style="margin-top: 22px;">
                                <h6>{{ translate('Default Sample Pricing Configuration') }}</h6>
                                <hr>
                                <div class="row mb-3">
                                    <label class="col-md-2 col-from-label">{{ translate('Sample Available?') }}</label>
                                    <div class="col-md-10">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="0" name="sample_available" type="checkbox" class="sample-available" />
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mb-3" style="display: none;" id="sample-description-wrapper">
                                    <label class="col-md-2 col-from-label">{{ translate('Sample description') }}</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="sample_description" id="sample_description_parent">{{ old('sample_description') }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3" id="sample-price-wrapper" style="display: none;">
                                    <label class="col-md-2 col-from-label">{{ translate('Sample price') }}</label>
                                    <div class="col-md-10">
                                        <input type="number" step="0.01" class="form-control" name="sample_price"
                                            id="sample_price_parent" value="{{ old('sample_price') }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Default shipping configuration --}}
                <div class="card" id="shipping_configuration_box">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Default Shipping Configuration') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="bloc-default-shipping-style">
                            <h6>{{ ucfirst(translate('Default Product Shipping')) }}</h6>
                            <hr>
                            <div class="bloc-default-shipping-style">
                                <h6>{{ ucfirst(translate('MawadOnline 3rd Party Shipping')) }}</h6>
                                <hr>
                                <div class="row mb-3">
                                    <label
                                        class="col-md-4 col-from-label">{{ translate('Activate MawadOnline 3rd Party Shipping') }}</label>
                                    <div class="col-md-8">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="1" type="checkbox" id="third_party_activate"
                                                name="activate_third_party">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <table class="table" id="table_third_party_configuration"
                                        class="bloc_third_configuration_variant">
                                        <thead>
                                            <tr>
                                                <th>{{ translate('Package Length (Cm)') }}</th>
                                                <th>{{ translate('Package Width (Cm)') }}</th>
                                                <th>{{ translate('Package Height (Cm)') }}</th>
                                                <th>{{ translate('Package Weight') }}</th>
                                                <th>{{ translate('Weight Unit') }}</th>
                                                <th>{{ translate('Breakable') }}</th>
                                                <th>{{ translate('Temperature Unit') }}</th>
                                                <th>{{ translate('Temperature Min') }}</th>
                                                <th>{{ translate('Temperature Max') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bloc_third_party">
                                            <tr>
                                                <td><input type="number" name="length" class="form-control"
                                                        id="length" step="0.1" readonly></td>
                                                <td><input type="number" name="width" class="form-control"
                                                        id="width" step="0.1" readonly></td>
                                                <td><input type="number" name="height" class="form-control"
                                                        id="height" step="0.1" readonly></td>
                                                <td><input type="number" name="weight" class="form-control"
                                                        id="weight" step="0.1" readonly></td>
                                                <td>
                                                    <select class="form-control calculate" id="weight_unit"
                                                        name="unit_weight" disabled>
                                                        <option value="kilograms">{{ translate('Kilograms') }}</option>
                                                        <option value="pounds">{{ translate('Pounds') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control calculate" id="breakable"
                                                        name="breakable" disabled>
                                                        <option value=""></option>
                                                        <option value="yes">{{ translate('Yes') }}</option>
                                                        <option value="no">{{ translate('No') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control calculate" id="unit_third_party"
                                                        name="unit_third_party" disabled>
                                                        <option value="celsius">{{ translate('Celsius') }}</option>
                                                        <option value="kelvin">{{ translate('Kelvin') }}</option>
                                                        <option value="fahrenheit">{{ translate('Fahrenheit') }}</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control" name="min_third_party"
                                                        id="min_third_party" step="0.1" readonly></td>
                                                <td><input type="number" class="form-control" name="max_third_party"
                                                        id="max_third_party" step="0.1" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small>{{ translate('Fill all required fields for shippers to confirm delivery ability') }}.</small>
                                <div id="result_calculate_third_party">

                                </div>
                            </div>
                            <div class="bloc-default-shipping-style" style="margin-top: 22px;">
                                <h6>{{ translate('Shipping Duration & Charge') }}</h6>
                                <hr>
                                <div>
                                    <table class="table" id="table_shipping_configuration"
                                        class="bloc_shipping_configuration_variant">
                                        <thead>
                                            <tr>
                                                <th>{{ translate('Shipper') }}</th>
                                                <th>{{ translate('From QTY') }}</th>
                                                <th>{{ translate('To QTY') }}</th>
                                                <th>{{ translate('Est. Order Pre. Days') }}</th>
                                                <th>{{ translate('Est. Shipping Days') }}</th>
                                                <th style="width: 164px;">{{ translate('Paid by') }}</th>
                                                <th>{{ translate('Shipping Charge Type') }}</th>
                                                <th>{{ translate('Flat-rate Amount') }}</th>
                                                <th>{{ translate('Charge per Unit of Sale') }}</th>
                                                <th>{{ translate('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bloc_shipping_configuration">
                                            <tr>
                                                <td>
                                                    <select class="form-control shipper"
                                                        name="shipper[]"
                                                        id="shipper_shipping">
                                                        <option>{{ __("Select Shipper") }}</option>
                                                        <option value="vendor" @selected(old('shipper.0') == 'vendor')>
                                                            {{ translate('vendor') }}</option>
                                                        <option value="third_party" @selected(old('shipper.0') == 'third_party')>
                                                            {{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                                    </select>
                                                </td>
                                                <td><input disabled type="number" name="from_shipping[]"
                                                        class="form-control min-qty-shipping" id="min-qty-shipping"
                                                        placeholder="{{ translate('From QTY') }}"
                                                        value="{{ old('from_shipping.0') }}"></td>
                                                <td><input disabled type="number" name="to_shipping[]"
                                                        class="form-control max-qty-shipping" id="max-qty-shipping"
                                                        placeholder="{{ translate('To QTY') }}"
                                                        value="{{ old('to_shipping.0') }}"></td>

                                                <td><input disabled type="number" class="form-control estimated_order"
                                                        name="estimated_order[]" value="{{ old('estimated_order.0') }}"
                                                        placeholder="{{ translate('Days') }}">
                                                </td>
                                                <td><input disabled type="number" class="form-control estimated_shipping"
                                                        name="estimated_shipping[]"
                                                        placeholder="{{ translate('Days') }}"
                                                        value="{{ old('estimated_shipping.0') }}"></td>
                                                <td>
                                                    <select class="form-control paid" name="paid[]">
                                                        <option value="" selected>{{ translate('Choose option') }}
                                                        </option>
                                                        <option value="vendor" @selected(old('paid.0') == 'vendor')>
                                                            {{ translate('vendor') }}</option>
                                                        <option value="buyer" @selected(old('paid.0') == 'buyer')>
                                                            {{ translate('Buyer') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control shipping_charge" name="shipping_charge[]">
                                                        <option selected>
                                                            {{ translate('Choose shipping charge') }}</option>
                                                        <option value="flat" @selected(old('shipping_charge.0') == 'flat')>
                                                            {{ translate('Flat-rate regardless of quantity') }}</option>
                                                        <option value="charging" @selected(old('shipping_charge.0') == 'charging')>
                                                            {{ translate('Charging per Unit of Sale') }}</option>
                                                    </select>
                                                </td>
                                                <td><input disabled type="number" class="form-control flat_rate_shipping"
                                                        name="flat_rate_shipping[]"
                                                        value="{{ old('flat_rate_shipping.0') }}"
                                                        placeholder="{{ translate('Flat rate amount') }}" readonly></td>
                                                <td><input disabled type="number" class="form-control charge_per_unit_shipping"
                                                        name="charge_per_unit_shipping[]"
                                                        placeholder="{{ translate('Charge unit') }}"
                                                        value="{{ old('charge_per_unit_shipping.0') }}" readonly></td>
                                                <td>
                                                    <i class="las la-plus btn-add-shipping"
                                                        style="margin-left: 5px; margin-top: 17px;"
                                                        title="{{ translate('Add another ligne') }}"></i>
                                                </td>
                                            </tr>
                                            @if (count(old('from_shipping', [])) > 1)
                                                @foreach (old('from_shipping', []) as $index => $value)
                                                    @if ($index > 0)
                                                        <tr>
                                                            <td>
                                                                <select class="form-control shipper" name="shipper[]"
                                                                    id="shipper_shipping">
                                                                    <option value="vendor" @selected(count(old('shipper', [])) > 1 && old('shipper', [])[$index] == 'vendor')>
                                                                        {{ translate('vendor') }}</option>
                                                                    <option value="third_party"
                                                                        @selected(count(old('shipper', [])) > 1 && old('shipper', [])[$index] == 'third_party')>
                                                                        {{ translate('MawadOnline 3rd Party Shippers') }}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td><input type="number" name="from_shipping[]"
                                                                    class="form-control min-qty-shipping"
                                                                    id="min-qty-shipping"
                                                                    placeholder="{{ translate('From QTY') }}"
                                                                    value="{{ old('from_shipping', [])[$index] }}"></td>
                                                            <td><input type="number" name="to_shipping[]"
                                                                    class="form-control max-qty-shipping"
                                                                    id="max-qty-shipping"
                                                                    placeholder="{{ translate('To QTY') }}"
                                                                    value="{{ old('to_shipping', [])[$index] }}"></td>
                                                            <td><input type="number" class="form-control estimated_order"
                                                                    name="estimated_order[]"
                                                                    value="{{ old('estimated_order', [])[$index] }}"
                                                                    placeholder="{{ translate('Days') }}">
                                                            </td>
                                                            <td><input type="number"
                                                                    class="form-control estimated_shipping"
                                                                    name="estimated_shipping[]"
                                                                    placeholder="{{ translate('Days') }}"
                                                                    value="{{ old('estimated_shipping', [])[$index] }}">
                                                            </td>
                                                            <td>
                                                                <select class="form-control paid" name="paid[]">
                                                                    <option value="" selected>
                                                                        {{ translate('Choose option') }}
                                                                    </option>
                                                                    <option value="vendor" @selected(old('paid', [])[$index] == 'vendor')>
                                                                        {{ translate('vendor') }}</option>
                                                                    <option value="buyer" @selected(old('paid', [])[$index] == 'buyer')>
                                                                        {{ translate('Buyer') }}</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control shipping_charge"
                                                                    name="shipping_charge[]">
                                                                    <option selected>
                                                                        {{ translate('Choose shipping charge') }}</option>
                                                                    <option value="flat" @selected(old('shipping_charge', [])[$index] == 'flat')>
                                                                        {{ translate('Flat-rate regardless of quantity') }}
                                                                    </option>
                                                                    <option value="charging" @selected(old('shipping_charge', [])[$index] == 'charging')>
                                                                        {{ translate('Charging per Unit of Sale') }}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td><input type="number"
                                                                    class="form-control flat_rate_shipping"
                                                                    name="flat_rate_shipping[]"
                                                                    placeholder="{{ translate('Flat rate amount') }}"
                                                                    value="{{ old('flat_rate_shipping', [])[$index] }}"
                                                                    readonly></td>
                                                            <td><input type="number"
                                                                    class="form-control charge_per_unit_shipping"
                                                                    name="charge_per_unit_shipping[]"
                                                                    placeholder="{{ translate('Charge unit') }}"
                                                                    value="{{ old('charge_per_unit_shipping', [])[$index] }}"
                                                                    readonly></td>
                                                            <td>
                                                                <i class="las la-plus btn-add-shipping"
                                                                    style="margin-left: 5px; margin-top: 17px;"
                                                                    title="{{ translate('Add another ligne') }}"></i>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="bloc-default-shipping-style" style="margin-top: 22px;">
                            <h6>{{ ucfirst(translate('Default Sample Shipping')) }}</h6>
                            <hr>
                            <div class="bloc-default-shipping-style">
                                <h6>{{ ucfirst(translate('MawadOnline 3rd Party Shipping')) }}</h6>
                                <hr>
                                <div class="row mb-3">
                                    <label
                                        class="col-md-4 col-from-label">{{ translate('Activate MawadOnline 3rd Party Shipping') }}</label>
                                    <div class="col-md-8">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="1" type="checkbox" id="third_party_activate_sample"
                                                name="activate_third_party_sample">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <table class="table" id="table_third_party_configuration_sample"
                                        class="bloc_third_configuration_variant_sample">
                                        <thead>
                                            <tr>
                                                <th>{{ translate('Package Length (Cm)') }}</th>
                                                <th>{{ translate('Package Width (Cm)') }}</th>
                                                <th>{{ translate('Package Height (Cm)') }}</th>
                                                <th>{{ translate('Package Weight') }}</th>
                                                <th>{{ translate('Weight Unit') }}</th>
                                                <th>{{ translate('Breakable') }}</th>
                                                <th>{{ translate('Temperature Unit') }}</th>
                                                <th>{{ translate('Temperature Min') }}</th>
                                                <th>{{ translate('Temperature Max') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bloc_third_party_sample">
                                            <tr>
                                                <td><input type="number" name="length_sample" class="form-control"
                                                        id="length_sample" step="0.1" readonly></td>
                                                <td><input type="number" name="width_sample" class="form-control"
                                                        id="width_sample" step="0.1" readonly></td>
                                                <td><input type="number" name="height_sample" class="form-control"
                                                        id="height_sample" step="0.1" readonly></td>
                                                <td><input type="number" name="package_weight_sample"
                                                        class="form-control" id="package_weight_sample" step="0.1"
                                                        readonly></td>
                                                <td>
                                                    <select class="form-control calculate" id="weight_unit_sample"
                                                        name="weight_unit_sample" disabled>
                                                        <option value="kilograms">{{ translate('Kilograms') }}</option>
                                                        <option value="pounds">{{ translate('Pounds') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control calculate" id="breakable_sample"
                                                        name="breakable_sample" disabled>
                                                        <option value=""></option>
                                                        <option value="yes">{{ translate('Yes') }}</option>
                                                        <option value="no">{{ translate('No') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control calculate" id="unit_third_party_sample"
                                                        name="unit_third_party_sample" disabled>
                                                        <option value="celsius">{{ translate('Celsius') }}</option>
                                                        <option value="kelvin">{{ translate('Kelvin') }}</option>
                                                        <option value="fahrenheit">{{ translate('Fahrenheit') }}</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control"
                                                        name="min_third_party_sample" id="min_third_party_sample"
                                                        step="0.1" readonly></td>
                                                <td><input type="number" class="form-control"
                                                        name="max_third_party_sample" id="max_third_party_sample"
                                                        step="0.1" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small>{{ translate('Fill all required fields for shippers to confirm delivery ability') }}.</small>
                                <div id="result_calculate_third_party_sample">
                                </div>
                            </div>
                            <div class="bloc-default-shipping-style" style="margin-top: 22px;">
                                <h6>{{ translate('Shipping Duration & Charge') }}</h6>
                                <hr>
                                <div>
                                    <table class="table" id="table_sample_configuration"
                                        class="bloc_sample_configuration_variant">
                                        <thead>
                                            <tr>
                                                <th>{{ translate('Shipping-by') }}</th>
                                                <th>{{ translate('Estimated Sample Preparation Days') }}</th>
                                                <th>{{ translate('Estimated Shipping Days') }}</th>
                                                <th>{{ translate('Paid by') }}</th>
                                                {{-- <th>{{translate('VAT')}}</th> --}}
                                                <th>{{ translate('Shipping amount') }}</th>
                                                <th>{{ translate('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bloc_sample_configuration">
                                            <tr>
                                                <td>
                                                    <select class="form-control shipper_sample" name="shipper_sample[]"
                                                        id="shipper_sample_parent">
                                                        <option>{{ __("Select Shipper") }}</option>
                                                        <option value="vendor" @selected(old('shipper_sample.0') == 'vendor')>
                                                            {{ translate('vendor') }}</option>
                                                        <option value="third_party" @selected(old('shipper_sample.0') == 'third_party')>
                                                            {{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                                    </select>
                                                </td>
                                                <td><input disabled type="number" class="form-control estimated_sample"
                                                        id="estimated_sample_parent" name="estimated_sample[]"
                                                        value="{{ old('estimated_sample.0') }}" /></td>
                                                <td><input disabled type="number"
                                                        class="form-control estimated_shipping_sample"
                                                        id="estimated_shipping_sample_parent"
                                                        name="estimated_shipping_sample[]"
                                                        value="{{ old('estimated_shipping_sample.0') }}" /></td>
                                                <td>
                                                    <select class="form-control paid_sample" name="paid_sample[]"
                                                        id="paid_sample_parent" style="width: max-content!important;">
                                                        <option value="" selected>{{ translate('Choose paid by') }}
                                                        </option>
                                                        <option value="vendor" @selected(old('paid_sample.0') == 'vendor')>
                                                            {{ translate('vendor') }}</option>
                                                        <option value="buyer" @selected(old('paid_sample.0') == 'buyer')>
                                                            {{ translate('Buyer') }}</option>
                                                    </select>
                                                </td>
                                                <td><input disabled type="number" class="form-control shipping_amount"
                                                        name="shipping_amount[]" value="{{ old('shipping_amount.0') }}"
                                                        step="0.1" /></td>
                                                <td>
                                                    <i class="las la-plus btn-add-sample-shipping"
                                                        style="margin-left: 5px; margin-top: 17px;"
                                                        title="{{ translate('Add another ligne') }}"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Sample pricing configuration --}}
                {{-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Default Sample Pricing Configuration')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="vat_sample" @if ($vat_user->vat_registered == 1) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- Bloc Product videos --}}
                {{-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Videos')}}</h5>
                    </div>
                    <div class="card-body">

                    </div>
                </div> --}}
                {{-- Bloc Product Category --}}
                <div class="card">
                    <input type="hidden" id="selected_parent_id" name="parent_id" value="{{ old('parent_id') }}">
                    <input type="hidden" id="check_selected_parent_id" value="-1">

                    <div class="card-body">
                        <div class="tree_main">
                            <input type="text" id="search_input" class="form-control"
                                placeholder="{{ translate('Search') }}"
                                value="{{ old('parent_id') ? get_single_category(old('parent_id'))->name : '' }}">
                            <div class="h-300px overflow-auto c-scrollbar-light">
                                <div id="jstree"></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc variant & attributes --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Create Variants') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row gutters-5">
                            <label class="col-md-2 col-from-label">{{ translate('Activate variant option') }}</label>
                            <div class="col-md-10">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="activate_attributes">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row gutters-5">
                            <label class="col-md-2 col-from-label">{{ translate('Attributes') }}</label>
                            <div class="col-md-10" id="attributes_bloc">
                                <select class="form-control aiz-selectpicker" data-live-search="true"
                                    data-selected-text-format="count" id="attributes" multiple disabled
                                    data-placeholder="{{ translate('Choose Attributes') }}">

                                </select>
                            </div>
                        </div>
                        <div>
                            <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}
                            </p>
                            <br>
                        </div>
                        <div id="variant_informations">
                            <h3 class="mb-3">{{ translate('Variant Information') }}</h3>
                            <hr>
                            <div class="row mb-3">
                                <label class="col-md-2 col-from-label">{{ translate('Variant SKU') }}</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control sku" id="sku">
                                </div>
                            </div>
                            <div class="row mb-3" style="display: none">
                                <label class="col-md-2 col-from-label">{{ translate('Variant Photos') }}</label>
                                <div class="col-md-10">
                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input photos_variant"
                                            id="photos_variant" accept=".jpeg, .jpg, .png" multiple>
                                        <label class="custom-file-label"
                                            for="photos_variant">{{ translate('Choose files') }}</label>
                                    </div>

                                    <div class="row uploaded_images">

                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3" style="display: none">
                                <label
                                    class="col-md-2 col-from-label">{{ translate('Use parent unit of sale price') }}</label>
                                <div class="col-md-10">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-pricing" checked>
                                        <span></span>
                                    </label>
                                </div>
                                <div class="row mx-0" id="bloc_pricing_configuration_variant">
                                </div>
                            </div>
                            <div class="row mb-3" style="display: none">
                                <label class="col-md-2 col-from-label">{{ translate('Use default Shipping') }}</label>
                                <div class="col-md-10">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-shipping"
                                            data-type="variant-information" checked>
                                        <span></span>
                                    </label>
                                </div>

                                <div class="col-12 mt-3" id="bloc_default_shipping">

                                </div>
                            </div>
                            <div class="row mb-3" style="display: none">
                                <label class="col-md-2 col-from-label">{{ translate('Sample Available?') }}</label>
                                <div class="col-md-10">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-sample-available">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row mb-3" style="display: none">
                                <label
                                    class="col-md-2 col-from-label">{{ translate('Use default sample pricing configuration') }}</label>
                                <div class="col-md-10">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-sample-pricing" checked
                                            />
                                        <span></span>
                                    </label>
                                </div>
                                <div id="bloc_sample_pricing_configuration_variant"
                                    class="bloc_sample_pricing_configuration_variant">
                                    {{-- <div class="row mb-3">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input value="1" class="vat_sample" type="checkbox" @if ($vat_user->vat_registered == 1) checked @endif>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div> --}}
                                    <div class="row mb-3">
                                        <label
                                            class="col-md-2 col-from-label">{{ translate('Sample description') }}</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control sample_description"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-2 col-from-label">{{ translate('Sample price') }}</label>
                                        <div class="col-md-10">
                                            <input type="number" step="0.01" class="form-control sample_price">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3" style="display: none">
                                <label
                                    class="col-md-2 col-from-label">{{ translate('Use default sample Shipping') }}</label>
                                <div class="col-md-10">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-sample-shipping" checked
                                            />
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-12 mt-3" id="bloc-sample-shipping">

                                </div>
                            </div>
                            <div class="row mb-3" style="display: none">
                                <label class="col-md-2 col-from-label">{{ translate('Low-Stock Warning') }}</label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control stock-warning" id="low_stock_warning">
                                </div>
                            </div>
                            <div id="bloc_attributes">

                            </div>
                        </div>
                        <div class="row div-btn">
                            <button type="button" class="btn btn-primary"
                                id="btn-create-variant">{{ translate('Create variant') }}</button>
                        </div>
                        <hr>
                        <div id="bloc_variants_created">

                        </div>
                    </div>
                </div>
                {{-- Bloc General Attributes --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('General Attributes') }}</h5>
                    </div>
                    <div class="card-body">
                        <div id="sku_product_product" style="margin-left: 4px;">
                            <div class="row">
                                <label class="col-md-2 col-from-label">{{ translate('SKU') }}</label>
                                <div class="col-md-10 mb-3">
                                    <input type="text" name="product_sk" class="form-control" id="sku_product_parent"
                                        value="{{ old('product_sk') }}">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-from-label">{{ translate('Low-Stock Warning') }}</label>
                                <div class="col-md-10 mb-3">
                                    <input type="number" min="0" name="quantite_stock_warning"
                                        class="form-control" value="{{ old('quantite_stock_warning') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="general_attributes"></div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Product Description --}}
                {{-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Description')}}</h5>
                    </div>
                    <div class="card-body">

                    </div>
                </div> --}}
                {{-- Bloc Product Documents --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Description and Specifications') }}<i
                                class="las la-info-circle ml-2" data-toggle="tooltip" data-html="true"
                                title="The maximum size permitted in a single document: <span style='red'>15 MB</span><br>Max size allowed for all documents: <span style='red'>25 MB</span>"></i>
                    </div>
                    <div class="card-body" id="documents_bloc">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                            <div class="col-md-8">
                                <textarea class="aiz-text-editor" name="description" id="long_description">{{ old('description') }}</textarea>
                                <input type="hidden" id="hidden_value" value="">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{ translate('Document name') }}</label>
                                    <input type="text" class="form-control" name="document_names[]">
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{ translate('Document') }}</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="documents[]"
                                                accept=".pdf,.png,.jpg,.pln,.dwg,.dxf,.gsm,.stl,.rfa,.rvt,.ifc,.3ds,.max,.obj,.fbx,.skp,.rar,.zip"
                                                class="custom-file-input file_input" id="inputGroupFile04"
                                                aria-describedby="inputGroupFileAddon04">
                                            <label class="custom-file-label"
                                                for="inputGroupFile04">{{ translate('Choose file') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <i class="las la-plus add_document" style="margin-left: 5px; margin-top: 40px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc SEO Meta --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="meta_title"
                                    value="{{ old('meta_title') }}" placeholder="{{ translate('Meta Title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                            <div class="col-md-8">
                                <textarea name="meta_description" rows="8" class="form-control">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
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
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="mar-all text-right mb-2">
                    <button type="submit" name="button" value="draft"
                        class="btn btn-success">{{ translate('Save as draft') }}</button>
                    <button type="submit" name="button" value="publish"
                        class="btn btn-primary">{{ translate('Create Product') }}</button>
                    <input type="hidden" name="submit_button" id="submit_button">
                </div>
            </div>
        </div>
    </form>
    <div id="modal-info" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6" id="title-modal">{{ translate('Delete Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14" id="text-modal">{{ translate('Are you sure to delete this?') }}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2"
                        data-dismiss="modal">{{ translate('OK') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Treeview js -->
    <script src="{{ static_asset('assets/js/countrySelect.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script src="{{ static_asset('assets/js/filter-multi-select-bundle.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert2 JS -->

    <script>
        var previewUrlBase = "{{ route('seller.product.preview', ['slug' => 'PLACEHOLDER']) }}";
    </script>

    <script>
        function previewImages(event) {
            var preview = document.getElementById('image-preview');
            //preview.innerHTML = '';
            var old_files = $('#image-preview').find('.preview-container').length;

            var files = event.target.files;
            var all_files = old_files + files.length;

            if (all_files > 10) {
                // Swal.fire({
                //     title: 'Cancelled',
                //     text: '{{ translate('Maximum 10 photos allowed.') }}',
                //     icon: 'error',
                //     scrollbarPadding: false,
                //     backdrop:false,
                // });

                var title = "{{ translate('Product Media') }}";
                var message = "{{ translate('Maximum 10 photos allowed.') }}";

                $('#title-modal').text(title);
                $('#text-modal').text(message);

                $('#modal-info').modal('show');

                $('#photoUploadcustom').val('');
                setTimeout(function() {
                    var previewContainers = document.querySelectorAll('.preview-container');
                    var files_update = [];

                    previewContainers.forEach(function(container) {
                        var img = container.querySelector('img');
                        var file = dataURLtoFile(img.src, 'image_' + Date.now() + '.png');
                        files_update.push(file);
                    });

                    var newInput = document.createElement('input');
                    newInput.type = 'file';
                    newInput.id = 'photoUploadcustom';
                    newInput.name = 'main_photos[]';
                    newInput.multiple = true;
                    newInput.classList.add('form-control'); // Add the 'form-control' class
                    newInput.accept = 'image/*'; // Accept only image files

                    newInput.addEventListener('change', previewImages);

                    // Replace the old input with the new one
                    var oldInput = document.getElementById('photoUploadcustom');
                    oldInput.parentNode.replaceChild(newInput, oldInput);

                    // Set files to the new input
                    var dataTransfer = new DataTransfer();
                    files_update.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });
                    newInput.files = dataTransfer.files;
                }, 500);
            } else {
                let exceedingFiles = [];

                for (let i = 0; i < files.length; i++) {
                    const fileSizeInMB = files[i].size / (1024 * 1024);
                    if (fileSizeInMB > 2) {
                        exceedingFiles.push(files[i].name);
                    }
                }

                if (exceedingFiles.length > 0) {
                    alert();
                    var title = "{{ translate('Product Media') }}";
                    var message = '<b> {{ translate('Following files exceed 2MB limit: ') }} </b> ' + exceedingFiles.join(
                        ', ');

                    $('#title-modal').text(title);
                    $('#text-modal').html(message);

                    $('#modal-info').modal('show');

                    $('#photoUploadcustom').val('');

                    setTimeout(function() {
                        var previewContainers = document.querySelectorAll('.preview-container');
                        var files_update = [];

                        previewContainers.forEach(function(container) {
                            var img = container.querySelector('img');
                            var file = dataURLtoFile(img.src, 'image_' + Date.now() + '.png');
                            files_update.push(file);
                        });

                        var newInput = document.createElement('input');
                        newInput.type = 'file';
                        newInput.id = 'photoUploadcustom';
                        newInput.name = 'main_photos[]';
                        newInput.multiple = true;
                        newInput.classList.add('form-control'); // Add the 'form-control' class
                        newInput.accept = 'image/*'; // Accept only image files

                        newInput.addEventListener('change', previewImages);

                        // Replace the old input with the new one
                        var oldInput = document.getElementById('photoUploadcustom');
                        oldInput.parentNode.replaceChild(newInput, oldInput);

                        // Set files to the new input
                        var dataTransfer = new DataTransfer();
                        files_update.forEach(function(file) {
                            dataTransfer.items.add(file);
                        });
                        newInput.files = dataTransfer.files;
                    }, 500);
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
                                    if (img.width > 1280 || img.height > 1280) {
                                        exceedingFilesDimension.push(files[i].name);
                                    }
                                };
                            };

                            // Read the file as a data URL
                            reader.readAsDataURL(file);
                        }
                    }

                    setTimeout(function() {
                        if (exceedingFilesDimension.length) {
                            var title = "{{ translate('Product Media') }}";
                            var message =
                                '<b> {{ translate('The dimensions of the images have exceeded both a width and height of 1280 pixels: ') }} </b> ' +
                                exceedingFilesDimension.join(', ');

                            $('#title-modal').text(title);
                            $('#text-modal').html(message);

                            $('#modal-info').modal('show');

                            $('#photoUploadcustom').val('');

                            setTimeout(function() {
                                var previewContainers = document.querySelectorAll('.preview-container');
                                var files_update = [];

                                previewContainers.forEach(function(container) {
                                    var img = container.querySelector('img');
                                    var file = dataURLtoFile(img.src, 'image_' + Date.now() +
                                        '.png');
                                    files_update.push(file);
                                });

                                var newInput = document.createElement('input');
                                newInput.type = 'file';
                                newInput.id = 'photoUploadcustom';
                                newInput.name = 'main_photos[]';
                                newInput.multiple = true;
                                newInput.classList.add('form-control'); // Add the 'form-control' class
                                newInput.accept = 'image/*'; // Accept only image files

                                newInput.addEventListener('change', previewImages);

                                // Replace the old input with the new one
                                var oldInput = document.getElementById('photoUploadcustom');
                                oldInput.parentNode.replaceChild(newInput, oldInput);

                                // Set files to the new input
                                var dataTransfer = new DataTransfer();
                                files_update.forEach(function(file) {
                                    dataTransfer.items.add(file);
                                });
                                newInput.files = dataTransfer.files;
                            }, 500);
                        } else {
                            for (var i = 0; i < files.length; i++) {
                                var file = files[i];
                                var reader = new FileReader();

                                reader.onload = function(e) {
                                    var imgContainer = document.createElement('div');
                                    imgContainer.classList.add('preview-container');

                                    var img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.style.maxWidth =
                                        '100px'; // Adjust the size of the preview image as needed
                                    img.style.maxHeight = '100px';
                                    imgContainer.appendChild(img);

                                    var deleteBtn = document.createElement('button');
                                    deleteBtn.innerText = 'Delete';
                                    deleteBtn.onclick = function() {
                                        imgContainer
                                            .remove(); // Remove the preview container when delete button is clicked
                                        updateFileInput(); // Update the file input after deleting
                                    };
                                    imgContainer.appendChild(deleteBtn);

                                    preview.appendChild(imgContainer);
                                }

                                reader.readAsDataURL(file);
                            }

                            setTimeout(function() {
                                var previewContainers = document.querySelectorAll('.preview-container');
                                var files_update = [];

                                previewContainers.forEach(function(container) {
                                    var img = container.querySelector('img');
                                    var file = dataURLtoFile(img.src, 'image_' + Date.now() +
                                        '.png');
                                    files_update.push(file);
                                });

                                var newInput = document.createElement('input');
                                newInput.type = 'file';
                                newInput.id = 'photoUploadcustom';
                                newInput.name = 'main_photos[]';
                                newInput.multiple = true;
                                newInput.classList.add('form-control'); // Add the 'form-control' class
                                newInput.accept = 'image/*'; // Accept only image files

                                newInput.addEventListener('change', previewImages);

                                // Replace the old input with the new one
                                var oldInput = document.getElementById('photoUploadcustom');
                                oldInput.parentNode.replaceChild(newInput, oldInput);

                                // Set files to the new input
                                var dataTransfer = new DataTransfer();
                                files_update.forEach(function(file) {
                                    dataTransfer.items.add(file);
                                });
                                newInput.files = dataTransfer.files;
                            }, 500);
                        }
                    }, 500);
                }
            }
        }

        function previewImagesThumbnail(event) {
            var preview = document.getElementById('image-preview-thumbnail');
            var old_files = $('#image-preview-thumbnail').find('.preview-container-thumbnail').length;

            var files = event.target.files;
            var all_files = old_files + files.length;
            if (all_files > 10) {
                var title = "{{ translate('Product Media') }}";
                var message = '{{ translate('Maximum 10 photos allowed.') }}';

                $('#title-modal').text(title);
                $('#text-modal').text(message);

                $('#modal-info').modal('show');

                $('#photoUploadThumbnailSeconde').val('');

                setTimeout(function() {
                    var previewContainers = document.querySelectorAll('.preview-container-thumbnail');
                    var files_update = [];

                    previewContainers.forEach(function(container) {
                        var img = container.querySelector('img');
                        var file = dataURLtoFile(img.src, 'image_' + Date.now() + '.png');
                        files_update.push(file);
                    });

                    var newInput = document.createElement('input');
                    newInput.type = 'file';
                    newInput.id = 'photoUploadThumbnailSeconde';
                    newInput.name = 'photosThumbnail[]';
                    newInput.multiple = true;
                    newInput.classList.add('form-control'); // Add the 'form-control' class
                    newInput.accept = 'image/*'; // Accept only image files

                    newInput.addEventListener('change', previewImagesThumbnail);

                    // Replace the old input with the new one
                    var oldInput = document.getElementById('photoUploadThumbnailSeconde');
                    oldInput.parentNode.replaceChild(newInput, oldInput);

                    // Set files to the new input
                    var dataTransfer = new DataTransfer();
                    files_update.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });
                    newInput.files = dataTransfer.files;
                }, 500);
            } else {
                let exceedingFiles = [];

                for (let i = 0; i < files.length; i++) {
                    const fileSizeInMB = files[i].size / (512 * 1024);
                    if (fileSizeInMB > 2) {
                        exceedingFiles.push(files[i].name);
                    }
                }

                if (exceedingFiles.length > 0) {
                    var title = "{{ translate('Product Media') }}";
                    var message = '<b>{{ translate('Following files exceed 512Ko limit: ') }}</b> ' + exceedingFiles.join(
                        ', ');

                    $('#title-modal').text(title);
                    $('#text-modal').html(message);

                    $('#modal-info').modal('show');

                    $('#photoUploadThumbnailSeconde').val('');

                    setTimeout(function() {
                        var previewContainers = document.querySelectorAll('.preview-container-thumbnail');
                        var files_update = [];

                        previewContainers.forEach(function(container) {
                            var img = container.querySelector('img');
                            var file = dataURLtoFile(img.src, 'image_' + Date.now() + '.png');
                            files_update.push(file);
                        });

                        var newInput = document.createElement('input');
                        newInput.type = 'file';
                        newInput.id = 'photoUploadThumbnailSeconde';
                        newInput.name = 'photosThumbnail[]';
                        newInput.multiple = true;
                        newInput.classList.add('form-control'); // Add the 'form-control' class
                        newInput.accept = 'image/*'; // Accept only image files

                        newInput.addEventListener('change', previewImagesThumbnail);

                        // Replace the old input with the new one
                        var oldInput = document.getElementById('photoUploadThumbnailSeconde');
                        oldInput.parentNode.replaceChild(newInput, oldInput);

                        // Set files to the new input
                        var dataTransfer = new DataTransfer();
                        files_update.forEach(function(file) {
                            dataTransfer.items.add(file);
                        });
                        newInput.files = dataTransfer.files;
                    });
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
                                    if (img.width > 400 || img.width < 300 || img.height > 400 || img.height <
                                        300) {
                                        exceedingFilesDimension.push(files[i].name);
                                    }
                                };
                            };

                            // Read the file as a data URL
                            reader.readAsDataURL(file);
                        }
                    }

                    setTimeout(function() {
                        if (exceedingFilesDimension.length) {
                            var title = "{{ translate('Product Media') }}";
                            var message =
                                '<b>{{ translate('Please upload images with dimensions between 300px and 400px for both width and height: ') }}</b> ' +
                                exceedingFilesDimension.join(', ');

                            $('#title-modal').text(title);
                            $('#text-modal').html(message);

                            $('#modal-info').modal('show');

                            $('#photoUploadThumbnailSeconde').val('');

                            setTimeout(function() {
                                var previewContainers = document.querySelectorAll(
                                    '.preview-container-thumbnail');
                                var files_update = [];

                                previewContainers.forEach(function(container) {
                                    var img = container.querySelector('img');
                                    var file = dataURLtoFile(img.src, 'image_' + Date.now() +
                                        '.png');
                                    files_update.push(file);
                                });

                                var newInput = document.createElement('input');
                                newInput.type = 'file';
                                newInput.id = 'photoUploadThumbnailSeconde';
                                newInput.name = 'photosThumbnail[]';
                                newInput.multiple = true;
                                newInput.classList.add('form-control'); // Add the 'form-control' class
                                newInput.accept = 'image/*'; // Accept only image files

                                newInput.addEventListener('change', previewImagesThumbnail);

                                // Replace the old input with the new one
                                var oldInput = document.getElementById('photoUploadThumbnailSeconde');
                                oldInput.parentNode.replaceChild(newInput, oldInput);

                                // Set files to the new input
                                var dataTransfer = new DataTransfer();
                                files_update.forEach(function(file) {
                                    dataTransfer.items.add(file);
                                });
                                newInput.files = dataTransfer.files;
                            }, 500);
                        } else {
                            for (var i = 0; i < files.length; i++) {
                                var file = files[i];
                                var reader = new FileReader();

                                reader.onload = function(e) {
                                    var imgContainer = document.createElement('div');
                                    imgContainer.classList.add('preview-container-thumbnail');

                                    var img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.style.maxWidth =
                                        '100px'; // Adjust the size of the preview image as needed
                                    img.style.maxHeight = '100px';
                                    imgContainer.appendChild(img);

                                    var deleteBtn = document.createElement('button');
                                    deleteBtn.innerText = 'Delete';
                                    deleteBtn.onclick = function() {
                                        imgContainer
                                            .remove(); // Remove the preview container when delete button is clicked
                                        updateFileInputThumbnail(); // Update the file input after deleting
                                    };
                                    imgContainer.appendChild(deleteBtn);

                                    preview.appendChild(imgContainer);
                                }

                                reader.readAsDataURL(file);
                            }

                            setTimeout(function() {
                                var previewContainers = document.querySelectorAll(
                                    '.preview-container-thumbnail');
                                var files_update = [];

                                previewContainers.forEach(function(container) {
                                    var img = container.querySelector('img');
                                    var file = dataURLtoFile(img.src, 'image_' + Date.now() +
                                        '.png');
                                    files_update.push(file);
                                });

                                var newInput = document.createElement('input');
                                newInput.type = 'file';
                                newInput.id = 'photoUploadThumbnailSeconde';
                                newInput.name = 'photosThumbnail[]';
                                newInput.multiple = true;
                                newInput.classList.add('form-control'); // Add the 'form-control' class
                                newInput.accept = 'image/*'; // Accept only image files

                                newInput.addEventListener('change', previewImagesThumbnail);

                                // Replace the old input with the new one
                                var oldInput = document.getElementById('photoUploadThumbnailSeconde');
                                oldInput.parentNode.replaceChild(newInput, oldInput);

                                // Set files to the new input
                                var dataTransfer = new DataTransfer();
                                files_update.forEach(function(file) {
                                    dataTransfer.items.add(file);
                                });
                                newInput.files = dataTransfer.files;
                            }, 500);
                        }
                    }, 500);
                }
            }
        }

        function updateFileInput() {
            var previewContainers = document.querySelectorAll('.preview-container');
            var files = [];

            previewContainers.forEach(function(container) {
                var img = container.querySelector('img');
                var file = dataURLtoFile(img.src, 'image_' + Date.now() + '.png');
                files.push(file);
            });

            var newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.id = 'photoUploadcustom';
            newInput.name = 'main_photos[]';
            newInput.multiple = true;
            newInput.classList.add('form-control'); // Add the 'form-control' class
            newInput.accept = 'image/*'; // Accept only image files

            newInput.addEventListener('change', previewImages);

            // Replace the old input with the new one
            var oldInput = document.getElementById('photoUploadcustom');
            oldInput.parentNode.replaceChild(newInput, oldInput);

            // Set files to the new input
            var dataTransfer = new DataTransfer();
            files.forEach(function(file) {
                dataTransfer.items.add(file);
            });
            newInput.files = dataTransfer.files;
        }

        function updateFileInputThumbnail() {
            var previewContainers = document.querySelectorAll('.preview-container-thumbnail');
            var files = [];

            previewContainers.forEach(function(container) {
                var img = container.querySelector('img');
                var file = dataURLtoFile(img.src, 'image_' + Date.now() + '.png');
                files.push(file);
            });

            var newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.id = 'photoUploadThumbnailSeconde';
            newInput.name = 'photosThumbnail[]';
            newInput.multiple = true;
            newInput.classList.add('form-control'); // Add the 'form-control' class
            newInput.accept = 'image/*'; // Accept only image files

            newInput.addEventListener('change', previewImagesThumbnail);

            // Replace the old input with the new one
            var oldInput = document.getElementById('photoUploadThumbnailSeconde');
            oldInput.parentNode.replaceChild(newInput, oldInput);

            // Set files to the new input
            var dataTransfer = new DataTransfer();
            files.forEach(function(file) {
                dataTransfer.items.add(file);
            });
            newInput.files = dataTransfer.files;
        }

        // Convert data URL to File object
        function dataURLtoFile(dataUrl, filename) {
            var arr = dataUrl.split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);

            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }

            return new File([u8arr], filename, {
                type: mime
            });
        }
    </script>
    <!--- category parent tree -->
    <script type="text/javascript">
        function submitForm() {
            var input = $('#nameProduct');
            input.removeClass('error'); // Add error class
            if (!input.val().trim()) {
                input.addClass('error'); // Add error class

                var title = "{{ translate('Product Preview') }}";
                var message = '{{ translate('Please enter a product name!') }}';

                $('#title-modal').text(title);
                $('#text-modal').text(message);

                $('#modal-info').modal('show');

                return;
            }
            const formData = new FormData(document.getElementById('choice_form'));

            fetch('{{ route('seller.product.tempStore') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var slug = generateSlug(data.data.slug);
                        // Replace 'PLACEHOLDER' with the actual slug from the response
                        var previewUrl = previewUrlBase.replace('PLACEHOLDER', slug);

                        // Open the URL in a new tab
                        window.open(previewUrl, '_blank');
                    }
                });
        }

        function generateSlug(text) {
            return text
                .toString() // Ensure it's a string
                .trim() // Remove extra spaces
                .toLowerCase() // Convert to lowercase
                .replace(/[\s_]+/g, '-') // Replace spaces and underscores with hyphens
                .replace(/[^\w\-]+/g, '') // Remove non-word characters (except hyphens)
                .replace(/\-\-+/g, '-'); // Replace multiple hyphens with a single one
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

            Array.prototype.diff = function(a) {
                return this.filter(function(i) {
                    return a.indexOf(i) < 0;
                });
            };

            $('body input[name="activate_attributes"]').on('change', function() {
                if (!$('body input[name="activate_attributes"]').is(':checked')) {
                    $('body #attributes').val('');
                    $('body #attributes').prop('disabled', true);
                    $('#variant_informations').hide();
                    $('#btn-create-variant').hide();
                    $('body #bloc_variants_created').empty();
                    $('body #sku_product_product').show();
                    AIZ.plugins.bootstrapSelect('refresh');
                } else {
                    var category_choosen = $("#selected_parent_id").val();
                    if (category_choosen != "1") {
                        if ($('#attributes option').length > 0) {
                            $('body #attributes').prop('disabled', false);
                            $('#variant_informations').show();
                            $('#btn-create-variant').show();
                            $('body #sku_product_product').hide();
                            $('body #bloc_variants_created').show();
                            $('.div-btn').show();
                            AIZ.plugins.bootstrapSelect('refresh');
                        } else {
                            $('body input[name="activate_attributes"]').prop('checked', false);

                            var title = "{{ translate('Product Category') }}";
                            var message =
                                '{{ translate('You are unable to enable the variant option because the selected category lacks any attributes.') }}';

                            $('#title-modal').text(title);
                            $('#text-modal').text(message);

                            $('#modal-info').modal('show');

                        }
                    } else {
                        $('body input[name="activate_attributes"]').prop('checked', false);

                        var title = "{{ translate('Product Category') }}";
                        var message =
                            '{{ translate('Select a category before activating the variant option.') }}';

                        $('#title-modal').text(title);
                        $('#text-modal').text(message);

                        $('#modal-info').modal('show');
                    }
                }
            });

            $('body').on('focusout',
                '.unit-price-variant, .sample_price_parent, .sample_price, input[name="sample_price"]',
                function() {
                    var value = parseFloat(this.value);
                    if (isNaN(value)) {
                        // Reset to 0.00 if the input is not a valid number
                        this.value = '0.00';
                    } else {
                        // Round the value to two decimal places
                        this.value = value.toFixed(2);
                    }
                })


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
                } else {
                    var message =
                        "<p>{{ translate('Remaining characters:') }} <span style='color: red'>" +
                        charactersLeft + "</span></p>"
                    $('#charCountShortDescription').html(message);
                }
            });

            $('body').on('change', '#attributes', function() {
                var ids_attributes = $(this).val();

                var clicked = ids_attributes.diff(initial_attributes);
                if (clicked.length == 0) {
                    clicked = initial_attributes.diff(ids_attributes);
                }

                if (initial_attributes.includes(clicked[0])) {
                    initial_attributes.splice(initial_attributes.indexOf(clicked[0]), 1);
                } else {
                    initial_attributes.push(clicked[0]);
                }

                var allValues = $('#attributes option').map(function() {
                    return $(this).val();
                }).get();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: '{{ route('seller.products.getAttributes') }}',
                    data: {
                        ids: clicked,
                        id_variant: numbers_variant,
                        selected: ids_attributes,
                        allValues: allValues
                    },
                    success: function(data) {
                        var attribute_variant_exist = $(
                            '#bloc_attributes > .attribute-variant-' + clicked[0]).length
                        var numberOfChildren = $('#general_attributes > div').length;

                        if (numberOfChildren == 0) {
                            $('#general_attributes').append(data.html_attributes_generale);
                        } else {
                            var numberOfChildrenOfChildren = $(
                                '#general_attributes > div > div').length;
                            if (numberOfChildrenOfChildren == 0) {
                                $('#general_attributes').append(data.html_attributes_generale);
                            } else {
                                $('#general_attributes .attribute-variant-' + clicked[0])
                                    .remove();
                            }
                        }

                        if (attribute_variant_exist > 0) {
                            $('#bloc_attributes .attribute-variant-' + clicked[0]).remove();
                            $('#general_attributes .attribute-variant-' + clicked[0]).remove();
                            $('#general_attributes').append(data.html);
                        } else {
                            $('body #bloc_attributes').append(data.html);
                        }

                        $("#bloc_variants_created div").each(function(index, element) {
                            id_variant = $(element).data('id');
                            $(element).find('.attributes').each(function(index,
                                child_element) {
                                // Change the attribute name of the current input
                                if ($(child_element).attr("name") ==
                                    undefined) {
                                    var id_attribute = $(child_element).data(
                                        'id_attributes');
                                    var name = 'attributes-' + id_attribute +
                                        '-' + id_variant
                                    if ($(child_element).data('type') ==
                                        'color') {
                                        var name = 'attributes-' +
                                            id_attribute + '-' + id_variant +
                                            '[]'
                                        $(child_element).attr('name', name);
                                    } else {
                                        var name = 'attributes-' +
                                            id_attribute + '-' + id_variant
                                        $(child_element).attr('name', name);
                                    }
                                }

                            });

                            $(element).find('.attributes-units').each(function(index,
                                child_element_units) {
                                if ($(child_element_units).attr("name") ==
                                    undefined) {
                                    var id_attribute = $(child_element_units)
                                        .data('id_attributes');
                                    var name = 'attributes_units-' +
                                        id_attribute + '-' + id_variant
                                    $(child_element_units).attr('name', name);
                                }
                            });

                        });

                        $("#general_attributes div").each(function(index, element) {
                            $(element).find('.attributes').each(function(index,
                                child_element) {
                                // Change the attribute name of the current input
                                if ($(child_element).attr("name") ==
                                    undefined) {
                                    var id_attribute = $(child_element).data(
                                        'id_attributes');

                                    if ($(child_element).data('type') ==
                                        'color') {
                                        var name = 'attribute_generale-' +
                                            id_attribute + '[]'
                                        $(child_element).attr('name', name);
                                    } else {
                                        var name = 'attribute_generale-' +
                                            id_attribute
                                        $(child_element).attr('name', name);
                                    }

                                }

                            });

                            $(element).find('.attributes-units').each(function(index,
                                child_element_units) {
                                if ($(child_element_units).attr("name") ==
                                    undefined) {
                                    var id_attribute = $(child_element_units)
                                        .data('id_attributes');
                                    var name = 'unit_attribute_generale-' +
                                        id_attribute
                                    $(child_element_units).attr('name', name);
                                }
                            });

                        });

                        var count_boolean = 1;
                        $("body #bloc_attributes div").each(function(index, element) {
                            $(element).find('.attributes').each(function(index,
                                child_element) {
                                // Change the attribute name of the current input
                                if ($(child_element).attr("type") == 'radio') {
                                    $(child_element).parent().parent().find(
                                        ':radio').each(function(index,
                                        radio_element) {
                                        $(radio_element).attr('name',
                                            'boolean' +
                                            count_boolean);
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
                var files = this.files;

                // Maximum number of allowed files
                var maxFiles = 10;
                if (files.length > maxFiles) {
                    // Swal.fire({
                    //         title: 'Cancelled',
                    //         text: '{{ translate('You can only upload a maximum of 10 files.') }}',
                    //         icon: 'error',
                    //         scrollbarPadding: false,
                    //         backdrop:false,
                    //     });
                    var title = "{{ translate('Variant Media') }}";
                    var message = '{{ translate('You can only upload a maximum of 10 files.') }}';

                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show');
                    this.value = ''; // Clear the file input
                } else if (files.length == 0) {
                    // Swal.fire({
                    //         title: 'Cancelled',
                    //         text: '{{ translate('You need to select at least one picture.') }}',
                    //         icon: 'error',
                    //         scrollbarPadding: false,
                    //         backdrop:false,
                    //     });

                    var title = "{{ translate('Variant Media') }}";
                    var message = '{{ translate('You need to select at least one picture.') }}';

                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show');
                    var labelText = '0 file selected';
                    $(this).next('.custom-file-label').html(labelText);
                } else if ((files.length <= maxFiles) && (files.length > 0)) {
                    var labelText = numFiles === 1 ? '1 file selected' : numFiles + ' files selected';
                    $(this).next('.custom-file-label').html(labelText);

                    let uploadedFilesHTML = '';
                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];
                        if (file.type.startsWith('image/')) {
                            uploadedFilesHTML +=
                                `<div class="col-2"><img src="${URL.createObjectURL(file)}" alt="${file.name}" height="80" width="80" /></div>`; // Display image preview
                        } else {
                            // Display icon for document type
                            uploadedFilesHTML += `<li><i class="fa fa-file-text-o"></i> ${file.name}</li>`;
                        }
                    }

                    $(this).parent().parent().find('.uploaded_images').html(uploadedFilesHTML);
                }
            });

            $('body').on('click', '#btn-create-variant', function() {
                if ($('#attributes option:selected').length > 0) {
                    var clonedDiv = $('body #variant_informations').clone();

                    clonedDiv.attr('class', 'clonedDiv');
                    clonedDiv.removeAttr('id');
                    clonedDiv.attr('data-id', numbers_variant);

                    var count = numbers_variant + 1;

                    @if (app()->getLocale() == 'ae')
                        var html_to_add =
                            '<div style="float: left; margin-top: -35px"><i class="fa-regular fa-circle-xmark fa-lx delete-variant" style="font-size: 16px;" title="delete this variant"></i></div>'
                    @else
                        var html_to_add =
                            '<div style="float: right; margin-top: -35px"><i class="fa-regular fa-circle-xmark fa-lx delete-variant" style="font-size: 16px;" title="delete this variant"></i></div>'
                    @endif

                    clonedDiv.find('h3').after(html_to_add);
                    clonedDiv.find('.fa-circle-check').hide();
                    clonedDiv.find('#btn-add-pricing-variant').hide();
                    clonedDiv.find('.sku').attr('name', 'sku-' + numbers_variant);
                    clonedDiv.find('.sku').prop('readonly', true);
                    clonedDiv.find('div.row').each(function() {
                        if ($(this).css('display') === 'none') {
                            $(this).css('display', '');
                        }
                    });
                    clonedDiv.find('.vat_sample').attr('name', 'vat_sample-' + numbers_variant);
                    clonedDiv.find('.sample_description').attr('name', 'sample_description-' +
                        numbers_variant);
                    clonedDiv.find('.sample_price').attr('name', 'sample_price-' + numbers_variant);
                    clonedDiv.find('.photos_variant').attr('name', 'photos_variant-' + numbers_variant +
                        '[]');
                    clonedDiv.find('.photos_variant').attr('id', 'photos_variant-' + numbers_variant);
                    clonedDiv.find('.custom-file-label').attr('for', 'photos_variant-' + numbers_variant);
                    clonedDiv.find('.variant-pricing').attr('name', 'variant-pricing-' + numbers_variant);
                    clonedDiv.find('.variant-pricing').attr('data-variant', numbers_variant);
                    clonedDiv.find('.min-qty-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[from][]');
                    });
                    clonedDiv.find('.max-qty-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[to][]');
                    });
                    clonedDiv.find('.unit-price-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[unit_price][]');
                    });
                    clonedDiv.find('.discount_percentage-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[discount_percentage][]');
                    });
                    clonedDiv.find('.discount_amount-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[discount_amount][]');
                    });
                    clonedDiv.find('.discount-range-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[discount_range][]');
                        $(element).daterangepicker({
                            timePicker: true,
                            autoUpdateInput: false,
                            minDate: today,
                            locale: {
                                format: 'DD-MM-Y HH:mm:ss',
                                separator: " to ",
                            },
                        });

                        var format = 'DD-MM-Y HH:mm:ss';
                        var separator = " to ";
                        $(element).on("apply.daterangepicker", function(ev, picker) {
                            $(this).val(
                                picker.startDate.format(format) +
                                separator +
                                picker.endDate.format(format)
                            );
                        });
                    });
                    clonedDiv.find('.variant-shipping').attr('name', 'variant-shipping-' + numbers_variant);
                    clonedDiv.find('.variant-shipping').attr('data-id_variant', numbers_variant);

                    clonedDiv.find('.stock-warning').attr('name', 'stock-warning-' + numbers_variant);
                    clonedDiv.find('.discount_type-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[discount_type][]');
                        $('#variant_informations').find('.discount_type-variant').each(function(key,
                            element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });
                    clonedDiv.find('.attributes').each(function(index, element) {
                        var dataIdValue = $(element).data('id_attributes');
                        var value = 0;
                        var check = false;
                        if ($(element).attr('data-type')) {
                            $('#variant_informations').find('.color').each(function(key,
                                element_original) {
                                if ($(element_original).data('id_attributes') ==
                                    dataIdValue) {
                                    value = $(element_original).val();
                                    $(element).attr('name', 'attributes-' + dataIdValue +
                                        '-' + numbers_variant + '[]');
                                    check = true;
                                }
                            })

                            $(element).val(value);
                        }

                        if (check == false) {
                            $(element).attr('name', 'attributes-' + dataIdValue + '-' +
                                numbers_variant);
                        }

                    });

                    clonedDiv.find('.attributes-units').each(function(index, element) {
                        var dataIdValue = $(element).data('id_attributes');

                        $(element).attr('name', 'attributes_units-' + dataIdValue + '-' +
                            numbers_variant);
                        $('#variant_informations').find('.attributes-units').each(function(key,
                            element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });

                    clonedDiv.find('.variant-sample-available').attr('name', 'variant-sample-available' +
                        numbers_variant);
                    clonedDiv.find('.variant-sample-pricing').attr('name', 'variant-sample-pricing' +
                        numbers_variant);
                    clonedDiv.find('.variant-sample-shipping').attr('name', 'variant-sample-shipping' +
                        numbers_variant);
                    clonedDiv.find('.variant-sample-shipping').attr('data-id_new_variant', numbers_variant);

                    clonedDiv.find('.min-qty-shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant + '[from][]');
                    });

                    clonedDiv.find('.max-qty-shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant + '[to][]');
                    });

                    var id_shipper = 0;
                    clonedDiv.find('.shipper').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[shipper][' + id_shipper + '][]');
                        $('#variant_informations #table_shipping_configuration').find('.shipper')
                            .each(function(key, element_original) {
                                if (index == key) {
                                    $(element_original).val().forEach(value => {
                                        $(element).find('option[value="' + value + '"]')
                                            .prop('selected', true);
                                    });
                                }
                            })

                        id_shipper++;
                    });

                    clonedDiv.find('.estimated_order').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[estimated_order][]');
                    });

                    clonedDiv.find('.estimated_shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[estimated_shipping][]');
                    });

                    clonedDiv.find('.paid').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant + '[paid][]');
                        $('#variant_informations #table_shipping_configuration').find('.paid').each(
                            function(key, element_original) {
                                if (index == key) {
                                    $(element).find('option[value="' + $(element_original)
                                        .val() + '"]').prop('selected', true);
                                }
                            })
                    });

                    clonedDiv.find('.vat_shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[vat_shipping][]');
                    });

                    clonedDiv.find('.shipping_charge').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[shipping_charge][]');
                        $('#variant_informations #table_shipping_configuration').find(
                            '.shipping_charge').each(function(key, element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });

                    clonedDiv.find('.flat_rate_shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[flat_rate_shipping][]');
                    });

                    clonedDiv.find('.charge_per_unit_shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[charge_per_unit_shipping][]');
                    });

                    clonedDiv.find('.shipper_sample').each(function(index, element) {
                        $(element).attr('name', 'variant_shipper_sample-' + numbers_variant + '[]');
                        $('#variant_informations #table_sample_configuration').find(
                            '.shipper_sample').each(function(key, element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });

                    clonedDiv.find('.paid_sample').each(function(index, element) {
                        $(element).attr('name', 'paid_sample-' + numbers_variant);
                        $('#variant_informations #table_sample_configuration').find('.paid_sample')
                            .each(function(key, element_original) {
                                if (index == key) {
                                    $(element).find('option[value="' + $(element_original)
                                        .val() + '"]').prop('selected', true);
                                }
                            })
                    });

                    clonedDiv.find('.estimated_sample').attr('name', 'estimated_sample-' + numbers_variant);
                    clonedDiv.find('.estimated_shipping_sample').attr('name', 'estimated_shipping_sample-' +
                        numbers_variant);
                    clonedDiv.find('.shipping_amount').attr('name', 'shipping_amount-' + numbers_variant);

                    clonedDiv.find('.delete_shipping_canfiguration').attr('data-variant-id',
                        numbers_variant);
                    clonedDiv.find('.btn-add-shipping').attr('data-variant-id', numbers_variant);
                    clonedDiv.find('.btn-add-pricing').attr('data-newvariant-id', numbers_variant);

                    if (clonedDiv.find('.sku').val() == '') {
                        var title = "{{ translate('Form validation') }}";
                        var message =
                            '{{ translate('The SKU field must be filled before creating the variant.') }}';
                        $('#title-modal').text(title);
                        $('#text-modal').text(message);

                        $('#modal-info').modal('show')
                    } else {
                        $('#bloc_variants_created').show();
                        $('#bloc_variants_created').prepend(clonedDiv);
                        var divId = "#bloc_variants_created";

                        var h3Count = $(divId + " h3").length;

                        $(divId + " h3").each(function(index) {
                            var order = h3Count - index;
                            $(this).text("{{ translate('Variant Information') }}" + ' ' + order);
                        });
                        numbers_variant++;

                        $('#variant_informations').find(
                            'input[type="text"], input[type="number"], input[type="checkbox"], input[type="radio"], select'
                        ).each(function() {
                            if ($(this).is('input[type="text"]') || $(this).is(
                                    'input[type="number"]')) {
                                $(this).val('');
                            }
                            else if ($(this).is('input[type="radio"]')) {
                                $(this).prop('checked',
                                    false);
                            } else if ($(this).is('select')) {
                                $(this).val('');
                            }
                        });

                        $('#variant_informations').find('.filter-option-inner-inner').each(function() {
                            $(this).text('Nothing selected')
                        });
                    }
                } else {
                    var title = "{{ translate('Create variant') }}";
                    var message =
                        '{{ translate('A minimum of one attribute must be selected in order to create a variant.') }}';
                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show')
                }
            });

            $('body').on('click', '.fa-pen-to-square', function() {
                $(this).parent().parent().find('input').prop('readonly', false);
                $(this).parent().parent().find('.fa-circle-xmark').show();
                $(this).parent().parent().find('#btn-add-pricing-variant').show();
                $(this).parent().parent().find('.fa-pen-to-square').hide();
                $(this).parent().parent().find('.fa-circle-check').show();
            })

            $('body').on('click', '.fa-circle-check', function() {
                $(this).parent().parent().find('input').prop('readonly', true);
                $(this).parent().parent().find('.fa-circle-xmark').hide();
                $(this).parent().parent().find('#btn-add-pricing-variant').hide();
                $(this).parent().parent().find('.fa-pen-to-square').show();
                $(this).parent().parent().find('.fa-circle-check').hide();
            })

            $('body').on('change', '.variant-sample-pricing', function() {
                if ($(this).is(':not(:checked)')) {
                    $(this).parent().parent().parent().find('.bloc_sample_pricing_configuration_variant')
                        .show();
                } else {
                    $(this).parent().parent().parent().find('.bloc_sample_pricing_configuration_variant')
                        .hide();
                }
            })

            $('body').on('change', '.variant-pricing', function() {
                if ($(this).is(':not(:checked)')) {
                    var is_variant = $(this).data("variant");
                    var clonedElement = $("#table_pricing_configuration").clone();

                    let unitPriceElement = $(`
                        <label class="col-md-2 col-from-label">
                            {{ __("Unit of Sale Price") }}
                            <small>({{ __("VAT Exclusive") }})</small>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-10">
                            <input
                               type="number"
                               class="form-control"
                               name="variant-unit_sale_price-${is_variant}"
                               value="0"
                               placeholder="{{ __("Unit of Sale Price") }}"
                            />
                        </div>
                    `);

                    clonedElement.find('.min-qty').each(function(index, element) {
                        $(element).removeClass("min-qty").addClass("min-qty-variant");
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[from][]');
                        } else {
                            $(element).removeAttr("name");
                        }
                    });
                    clonedElement.find('.max-qty').each(function(index, element) {
                        $(element).removeClass("max-qty").addClass("max-qty-variant");
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant + '[to][]');
                        } else {
                            $(element).removeAttr("name");
                        }
                    });
                    clonedElement.find('.discount_percentage').each(function(index, element) {
                        $(element).removeClass("discount_percentage").addClass(
                            "discount_percentage-variant");
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[discount_percentage][]');
                        } else {
                            $(element).removeAttr("name");
                        }
                    });
                    clonedElement.find('.discount_amount').each(function(index, element) {
                        $(element).removeClass("discount_amount").addClass(
                            "discount_amount-variant");
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[discount_amount][]');
                        } else {
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
                                separator: " to ",
                            },
                        });

                        var format = 'DD-MM-Y HH:mm:ss';
                        var separator = " to ";
                        $(element).on("apply.daterangepicker", function(ev, picker) {
                            $(this).val(
                                picker.startDate.format(format) +
                                separator +
                                picker.endDate.format(format)
                            );
                        });

                        $(element).removeClass("discount-range").addClass("discount-range-variant");
                        $(element).removeAttr("name");
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[discount_range][]');
                        }
                    });
                    clonedElement.find('.unit-price-variant').each(function(index, element) {
                        $(element).removeClass("unit-price").addClass("unit-price-variant");
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[unit_price][]');
                        } else {
                            $(element).removeAttr("name");
                        }
                    });
                    clonedElement.find('.btn-add-pricing').each(function(index, element) {
                        if (is_variant != undefined) {
                            $(element).attr('data-id_variant', is_variant);
                        }
                    });
                    clonedElement.find('.discount_type').each(function(index, element) {
                        $(element).removeClass("discount_type").addClass("discount_type-variant");
                        $(element).removeClass("aiz-selectpicker")
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[discount_type][]');
                        } else {
                            $(element).removeAttr("name");
                        }
                        $('#bloc_pricing_configuration').find('.discount_type').each(function(key,
                            element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });

                    $(this).parent().parent().parent()
                        .find('#bloc_pricing_configuration_variant').show();
                    $(this).parent().parent().parent()
                        .find('#bloc_pricing_configuration_variant').append(
                        unitPriceElement);
                } else {
                    $(this).parent().parent().parent().find('#bloc_pricing_configuration_variant').empty();
                }
            })

            $("#country_selector").countrySelect({
                responsiveDropdown: true,
                preferredCountries: ['ae']
            });

            //A text-field for “Product Short Description”. Maximum length is 512 characters
            $('#summernote').on("summernote.change", function(e) {
                //Get the text in textarea with tags
                let htmlContent = $('#summernote').summernote('code');
                //Extract the text from code summernote
                let textContent = $(htmlContent).text();
                let maxLength = 512;
                let charactersLeft = maxLength - textContent.length;

                //Check if difference between maxlength and text is greater than 0
                if (charactersLeft >= 0) {
                    if ($('#hidden_value').val() != '') {
                        $('#hidden_value').val('');
                    }
                    var message = "<p>Remaining characters: <span style='color: red'>" + charactersLeft +
                        "</span></p>"
                    $('#charCount').html(message);
                } else {
                    let trimmedText = '<p>' + textContent.substr(0, maxLength) + '</p>';
                    if ($('#hidden_value').val() == '') {
                        $('#hidden_value').val(trimmedText);
                    }
                    $("#summernote").summernote("code", $('#hidden_value').val());
                }
            });

            $('body').on('focusout', '.max-qty', function() {
                let overlapFound = false; // Flag to track if any overlaps are found

                var valuesMinQtyArray = [];
                var valuesMaxQtyArray = [];
                $(this).parent().parent().parent().find('.min-qty').each(function() {
                    // Get the value of each input field and push it to the array
                    valuesMinQtyArray.push($(this).val());
                    $(this).css('border-color', '#e2e5ec');
                });

                $(this).parent().parent().parent().find('.max-qty').each(function() {
                    // Get the value of each input field and push it to the array
                    valuesMaxQtyArray.push($(this).val());
                    $(this).css('border-color', '#e2e5ec');
                });

                //check if there is any overlaps
                for (let i = 0; i < valuesMinQtyArray.length; i++) {
                    var minVal = parseFloat(valuesMinQtyArray[
                        i]); //get current min value to compare with other value
                    var maxVal = parseFloat(valuesMaxQtyArray[
                        i]); //get current max value to compare with other value
                    for (let j = 0; j < valuesMinQtyArray.length; j++) {
                        if (i == j) {
                            continue;
                        } else {
                            var otherMinVal = parseFloat(valuesMinQtyArray[j]);
                            var otherMaxVal = parseFloat(valuesMaxQtyArray[j]);
                            var difference = otherMinVal - parseFloat(valuesMaxQtyArray[j - 1]);

                            if (difference > 1) {
                                $(this).parent().parent().parent().find('.min-qty').eq(j).css(
                                    'border-color', 'red');
                                $(this).parent().parent().parent().find('.max-qty').eq(j - 1).css(
                                    'border-color', 'red');
                                // Swal.fire({
                                //     title: 'Cancelled',
                                //     text: '{{ translate('Ensure that the difference between the minimum and maximum quantities of the preceding interval must be equal to one.') }}',
                                //     icon: 'error',
                                //     scrollbarPadding: false,
                                //     backdrop:false,
                                // });
                                var title = "{{ translate('Pricing Configuration') }}";
                                var message =
                                    '{{ translate('Ensure that the difference between the minimum and maximum quantities of the preceding interval must be equal to one.') }}';

                                $('#title-modal').text(title);
                                $('#text-modal').text(message);

                                $('#modal-info').modal('show');
                                overlapFound = true;
                            }

                            if (minVal >= otherMinVal && minVal <=
                                otherMaxVal) { //check if min value exist in another interval
                                $(this).parent().parent().parent().find('.min-qty').eq(i).css(
                                    'border-color', 'red');
                                // Swal.fire({
                                //     title: 'Cancelled',
                                //     text: '{{ translate('Overlap found.') }}',
                                //     icon: 'error',
                                //     scrollbarPadding: false,
                                //     backdrop:false,
                                // });
                                var title = "{{ translate('Pricing Configuration') }}";
                                var message = '{{ translate('Overlap found.') }}';

                                $('#title-modal').text(title);
                                $('#text-modal').text(message);

                                $('#modal-info').modal('show');
                                overlapFound = true;
                            }

                            if (maxVal >= otherMinVal && maxVal <=
                                otherMaxVal) { //check if max value exist in another interval
                                $(this).parent().parent().parent().find('.max-qty').eq(i).css(
                                    'border-color', 'red');
                                // Swal.fire({
                                //     title: 'Cancelled',
                                //     text: '{{ translate('Overlap found.') }}',
                                //     icon: 'error',
                                //     scrollbarPadding: false,
                                //     backdrop:false,
                                // });

                                var title = "{{ translate('Pricing Configuration') }}";
                                var message = '{{ translate('Overlap found.') }}';

                                $('#title-modal').text(title);
                                $('#text-modal').text(message);

                                $('#modal-info').modal('show');
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
                                            <input type="text" class="form-control aiz-date-range-variant discount-range-variant" placeholder="{{ translate('Select Date') }}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Type') }}</label>
                                            <select class="form-control discount_type-variant">
                                                <option value="">{{ translate('Choose type') }}</option>
                                                <option value="amount" @selected(old('discount_type') == 'amount')>{{ translate('Flat') }}</option>
                                                <option value="percent" @selected(old('discount_type') == 'percent')>{{ translate('Percent') }}</option>
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
                        separator: " to ",
                    },
                });

                var format = 'DD-MM-Y HH:mm:ss';
                var separator = " to ";
                $('#bloc_pricing_configuration_variant .aiz-date-range:last').on("apply.daterangepicker",
                    function(ev, picker) {
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
                var newvariant = $(this).data('id_variant');

                if (newvariant != undefined) {
                    var html_to_add =
                        `<tr>
                                    <td><input type="number" min="1" placeholder="{{ translate('From QTY') }}" required name="variant_pricing-from` +
                        newvariant +
                        `[from][]" class="form-control min-qty" id=""></td>
                                    <td><input type="number" min="1" placeholder="{{ translate('To QTY') }}" required name="variant_pricing-from` +
                        newvariant +
                        `[to][]" class="form-control max-qty" id=""></td>
                                    <td><input type="number" step="0.01" placeholder="{{ translate('Unit Price') }}" min="1" required name="variant_pricing-from` +
                        newvariant +
                        `[unit_price][]" class="form-control unit-price-variant" id=""></td>
                                    <td><input type="text" class="form-control aiz-date-range discount-range" name="variant_pricing-from` +
                        newvariant + `[discount_range][]" placeholder="{{ translate('Select Date') }}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                    <td>
                                        <select class="form-control discount_type" name="variant_pricing-from` +
                        newvariant +
                        `[discount_type][]">
                                            <option value="" selected>{{ translate('Choose type') }}</option>
                                            <option value="amount" @selected(old('discount_type') == 'amount')>{{ translate('Flat') }}</option>
                                            <option value="percent" @selected(old('discount_type') == 'percent')>{{ translate('Percent') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control discount_amount" placeholder="{{ translate('Amount') }}" name="variant_pricing-from` +
                        newvariant +
                        `[discount_amount][]" readonly></td>
                                    <td style="width: 19% !important;">
                                        <div class="col-md-9 input-group">
                                            <input type="number" class="form-control discount_percentage" placeholder="{{ translate('Percentage') }}" name="variant_pricing-from` +
                        newvariant + `[discount_percentage][]" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="las la-plus btn-add-pricing" data-id_variant="` + newvariant + `" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                        <i class="las la-trash delete_pricing_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                                    </td>
                                </tr>`;
                } else {
                    // Check if the element or any of its parents has the specific Id
                    if ($(this).closest('#variant_informations').length) {
                        var html_to_add = `
                                <tr>
                                    <td><input type="number" min="1" placeholder="{{ translate('From QTY') }}" class="form-control min-qty-variant" id=""></td>
                                    <td><input type="number" min="1" placeholder="{{ translate('To QTY') }}" class="form-control max-qty-variant" id=""></td>
                                    <td><input type="number" placeholder="{{ translate('Unit Price') }}" step="0.01" min="1" class="form-control unit-price-variant" id=""></td>
                                    <td><input type="text" class="form-control aiz-date-range discount-range-variant" placeholder="{{ translate('Select Date') }}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                    <td>
                                        <select class="form-control discount_type-variant">
                                            <option value="" selected>{{ translate('Choose type') }}</option>
                                            <option value="amount" @selected(old('discount_type') == 'amount')>{{ translate('Flat') }}</option>
                                            <option value="percent" @selected(old('discount_type') == 'percent')>{{ translate('Percent') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control discount_amount-variant" placeholder="{{ translate('Amount') }}" readonly></td>
                                    <td style="width: 19% !important;">
                                        <div class="col-md-9 input-group">
                                            <input type="number" class="form-control discount_percentage-variant" placeholder="{{ translate('Percentage') }}" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="las la-plus btn-add-pricing" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                        <i class="las la-trash delete_pricing_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                                    </td>
                                </tr>
                            `;
                    } else {
                        var html_to_add = `
                                <tr>
                                    <td><input type="number" min="1" placeholder="{{ translate('From QTY') }}" name="from[]" class="form-control min-qty" id=""></td>
                                    <td><input type="number" min="1" placeholder="{{ translate('To QTY') }}" name="to[]" class="form-control max-qty" id=""></td>
                                    <td><input type="number" placeholder="{{ translate('Unit Price') }}" step="0.01" min="1" name="unit_price[]" class="form-control unit-price-variant" id=""></td>
                                    <td><input type="text" class="form-control aiz-date-range discount-range" name="date_range_pricing[]" placeholder="{{ translate('Select Date') }}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                    <td>
                                        <select class="form-control discount_type" name="discount_type[]">
                                            <option value="" selected>{{ translate('Choose type') }}</option>
                                            <option value="amount" @selected(old('discount_type') == 'amount')>{{ translate('Flat') }}</option>
                                            <option value="percent" @selected(old('discount_type') == 'percent')>{{ translate('Percent') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control discount_amount" placeholder="{{ translate('Amount') }}" name="discount_amount[]" readonly></td>
                                    <td style="width: 19% !important;">
                                        <div class="col-md-9 input-group">
                                            <input type="number" class="form-control discount_percentage" name="discount_percentage[]" placeholder="{{ translate('Percentage') }}" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="las la-plus btn-add-pricing" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                        <i class="las la-trash delete_pricing_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
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
                        separator: " to ",
                    },
                });

                var format = 'DD-MM-Y HH:mm:ss';
                var separator = " to ";
                $(this).parent().parent().parent().find('.aiz-date-range:last').on("apply.daterangepicker",
                    function(ev, picker) {
                        $(this).val(
                            picker.startDate.format(format) +
                            separator +
                            picker.endDate.format(format)
                        );
                    });

                //refresh select discount type
                AIZ.plugins.bootstrapSelect('refresh');
            });

            $('body').on('click', '.delete_pricing_canfiguration', function() {
                //remove bloc pricing configuration
                $(this).parent().parent().remove();
            })

            $('body').on('change', '.discount_type', function() {
                //enablig or disabling input discount amout or discount percentage
                if ($(this).val() == "amount") {
                    $(this).parent().parent().find('.discount_amount').prop('readonly', false);
                    $(this).parent().parent().find('.discount_percentage').prop('readonly', true);
                    $(this).parent().parent().find('.discount_percentage').val('');
                }

                if ($(this).val() == "percent") {
                    $(this).parent().parent().find('.discount_amount').prop('readonly', true);
                    $(this).parent().parent().find('.discount_percentage').prop('readonly', false);
                    $(this).parent().parent().find('.discount_amount').val('');
                }

                if ($(this).val() == '') {
                    $(this).parent().parent().find('.discount_amount').prop('readonly', true);
                    $(this).parent().parent().find('.discount_amount').val('');
                    $(this).parent().parent().find('.discount_percentage').prop('readonly', true);
                    $(this).parent().parent().find('.discount_percentage').val('');
                }
            })

            $('body').on('change', '.discount_type-variant', function() {
                //enablig or disabling input discount amout or discount percentage
                if ($(this).val() == "amount") {
                    $(this).parent().parent().find('.discount_amount-variant').prop('readonly', false);
                    $(this).parent().parent().find('.discount_percentage-variant').prop('readonly', true);
                    $(this).parent().parent().find('.discount_percentage-variant').val('');
                }
                if ($(this).val() == "percent") {
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

            //    initializeDropify();
            initializeDropifyThumbnail();

            $('body').on('change', '#photoUpload', function() {
                let files = $(this)[0].files;
                $('#dropifyUploadedFiles').empty();
                if (files.length > 10) {
                    Swal.fire({
                        title: 'Cancelled',
                        text: '{{ translate('Maximum 10 photos allowed.') }}',
                        icon: 'error',
                        scrollbarPadding: false,
                        backdrop: false,
                    });
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
                        Swal.fire({
                            title: 'Cancelled',
                            text: 'Following files exceed 2MB limit: ' + exceedingFiles.join(', '),
                            icon: 'error',
                            scrollbarPadding: false,
                            backdrop: false,
                        });
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
                                        if (img.width > 1280 || img.height > 1280) {
                                            exceedingFilesDimension.push(files[i].name);
                                        }
                                    };
                                };

                                // Read the file as a data URL
                                reader.readAsDataURL(file);
                            }
                        }
                        setTimeout(function() {
                            if (exceedingFilesDimension.length) {

                            } else {
                                let uploadedFilesHTML = '<div class="row">';
                                for (let i = 0; i < files.length; i++) {
                                    let file = files[i];
                                    if (file.type.startsWith('image/')) {
                                        uploadedFilesHTML +=
                                            `<div class="col-2"><img src="${URL.createObjectURL(file)}" alt="${file.name}" height="80" width="80" /></div>`; // Display image preview
                                    } else {
                                        // Display icon for document type
                                        uploadedFilesHTML +=
                                            `<li><i class="fa fa-file-text-o"></i> ${file.name}</li>`;
                                    }
                                }
                                uploadedFilesHTML += '</div>';

                                if ($('body #dropifyUploadedFiles').length === 0) {
                                    $("#bloc_photos").append(
                                        '<div id="dropifyUploadedFiles"></div>');
                                    $('body #dropifyUploadedFiles').html(uploadedFilesHTML);
                                } else {
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
                    Swal.fire({
                        title: 'Cancelled',
                        text: '{{ translate('Maximum 10 photos allowed.') }}',
                        icon: 'error',
                        scrollbarPadding: false,
                        backdrop: false,
                    });
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
                        Swal.fire({
                            title: 'Cancelled',
                            text: 'Following files exceed 512Ko limit: ' + exceedingFiles.join(
                                ', '),
                            icon: 'error',
                            scrollbarPadding: false,
                            backdrop: false,
                        });
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
                                        if (img.width > 400 || img.width < 300 || img.height >
                                            400 || img.height < 300) {
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
                                Swal.fire({
                                    title: 'Cancelled',
                                    text: 'Please upload images with dimensions between 300px and 400px for both width and height: ' +
                                        exceedingFilesDimension.join(', '),
                                    icon: 'error',
                                    scrollbarPadding: false,
                                    backdrop: false,
                                });
                                $(this).val('');
                                removeDropifyThumbnail();
                                dropifyInputThumbnail.replaceWith(originalInputThumbnail.clone(
                                    true));
                                dropifyInputThumbnail = $('#photoUploadThumbnail');
                                initializeDropifyThumbnail();
                            } else {

                                let uploadedFilesHTML = '<div class="row">';
                                for (let i = 0; i < files.length; i++) {
                                    let file = files[i];
                                    if (file.type.startsWith('image/')) {
                                        uploadedFilesHTML +=
                                            `<div class="col-2"><img src="${URL.createObjectURL(file)}" alt="${file.name}" height="80" width="80" /></div>`; // Display image preview
                                    } else {
                                        // Display icon for document type
                                        uploadedFilesHTML +=
                                            `<li><i class="fa fa-file-text-o"></i> ${file.name}</li>`;
                                    }
                                }
                                uploadedFilesHTML += '</div>';

                                if ($('body #dropifyUploadedFilesThumbnail').length == 0) {
                                    $("#bloc_thumbnails").append(
                                        '<div id="dropifyUploadedFilesThumbnail"></div>');
                                    $('body #dropifyUploadedFilesThumbnail').html(
                                        uploadedFilesHTML);
                                } else {
                                    console.log('existe')
                                    $('body #dropifyUploadedFilesThumbnail').html(
                                        uploadedFilesHTML);
                                }
                            }
                        }, 500);
                    }
                }
            });

            let fileInputCounter = 1;

            $('body').on('click', '.dropify-clear', function() {
                if ($(this).parent().parent().parent().find('#dropifyUploadedFilesThumbnail').length) {
                    $('body #dropifyUploadedFilesThumbnail').empty();
                } else {
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
                        // Swal.fire({
                        //         title: 'Cancelled',
                        //         text: 'File size exceeds 15MB.',
                        //         icon: 'error',
                        //         scrollbarPadding: false,
                        //         backdrop:false,
                        //     });

                        var title = "{{ translate('Product Description and Specifications') }}";
                        var message = '{{ translate('File size exceeds 15MB.') }}';

                        $('#title-modal').text(title);
                        $('#text-modal').text(message);

                        $('#modal-info').modal('show');
                    } else {
                        $('.file_input').each(function() {
                            var files = $(this)[0].files;

                            for (var i = 0; i < files.length; i++) {
                                totalSize += files[i].size;
                            }
                        });

                        if (totalSize > maxAllUploadedSize) {
                            // If combined file size exceeds the limit, show an error message or take necessary action
                            // Swal.fire({
                            //     title: 'Cancelled',
                            //     text: 'Total file size exceeds 25MB. Please select smaller files.',
                            //     icon: 'error',
                            //     scrollbarPadding: false,
                            //     backdrop:false,
                            // });

                            var title = "{{ translate('Product Description and Specifications') }}";
                            var message =
                                '{{ translate('Total file size exceeds 25MB. Please select smaller files.') }}';

                            $('#title-modal').text(title);
                            $('#text-modal').text(message);

                            $('#modal-info').modal('show');
                            // Reset the file inputs to prevent exceeding the limit
                            $(this).val('');
                        } else {
                            let fileName = $(this).val().split('\\').pop();
                            $(this).next('.custom-file-label').addClass('selected').html(fileName);
                            $(this).parent().parent().parent().find('.size-file-uploaded').remove();
                            $(this).parent().parent().parent().append(
                                "<p class='size-file-uploaded'>{{ translate('the size of the downloaded document: ') }}<span style='color: red'>" +
                                fileSizeMb.toFixed(2) + "MB<span></p>");
                        }
                    }
                } else {
                    console.log('No file selected.');
                }
            });

            $('body').on('click', '.add_document', function() {
                var html_document = `<div class="row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="exampleInputEmail">{{ translate('Document name') }}</label>
                                        <input type="text" class="form-control" name="document_names[]">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="exampleInputEmail${fileInputCounter}">{{ translate('Document') }}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                            <input type="file" name="documents[]" class="custom-file-input file_input" id="exampleInputEmail${fileInputCounter}" aria-describedby="inputGroupFileAddon04">
                                            <label class="custom-file-label" for="exampleInputEmail${fileInputCounter}">{{ translate('Choose file') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <i class="las la-plus add_document" style="margin-left: 5px; margin-top: 40px;"></i>
                                    <i class="las la-trash trash_document" style="margin-left: 5px; margin-top: 40px;" ></i>
                                </div>
                            </div>`;
                $('#documents_bloc').append(html_document);
                fileInputCounter++;
            })

            $('body').on('click', '.trash_document', function() {
                $(this).parent().parent().remove();
            })

            $('body').on('click', '.delete-variant', function() {
                $(this).parent().parent().remove();

                var divId = "#bloc_variants_created";

                // Get the length of all h3 tags under the specific div
                var h3Count = $(divId + " h3").length;


                // Loop through each h3 tag and display its order
                $(divId + " h3").each(function(index) {
                    var order = h3Count - index; // Number in descending order
                    $(this).text("Variant Information  " + order);
                });
            })

            //Shipping script
            $('body').on('click', '#third_party_activate', function() {
                if ($(this).is(':checked')) {
                    var count_shippers = {{ count($supported_shippers) }};

                    if (count_shippers == 0) {
                        $('body input[name="activate_third_party"]').prop('checked', false);

                        var title = "{{ translate('Default Shipping Configuration') }}";
                        var message = '{{ __("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}';

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');

                        $(this).prop('checked', false)
                    } else {
                        $('#bloc_third_party input[type="number"]').each(function() {
                            // Change readonly attribute from true to false
                            $(this).prop('readonly', false);
                        });

                        $('#bloc_third_party select').each(function() {
                            // Change readonly attribute from true to false
                            $(this).prop('disabled', false);
                        });
                    }
                } else {
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

            $('body').on('click', '#third_party_activate_sample', function() {
                if ($(this).is(':checked')) {
                    var count_shippers = {{ count($supported_shippers) }};

                    if (count_shippers == 0) {
                        $('body input[name="activate_third_party"]').prop('checked', false);
                        var title = "{{ translate('Default Shipping Configuration') }}";
                        var message = '{{ __("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}';

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');

                        $(this).prop('checked', false)
                    } else {
                        $('#bloc_third_party_sample input[type="number"]').each(function() {
                            // Change readonly attribute from true to false
                            $(this).prop('readonly', false);
                        });

                        $('#bloc_third_party_sample select').each(function() {
                            // Change readonly attribute from true to false
                            $(this).prop('disabled', false);
                        });
                    }
                } else {
                    $('#bloc_third_party_sample input[type="number"]').each(function() {
                        // Change readonly attribute from true to false
                        $(this).prop('readonly', true);
                    });

                    $('#bloc_third_party_sample select').each(function() {
                        // Change readonly attribute from true to false
                        $(this).prop('disabled', true);
                    });

                    $('#bloc_third_party_sample input[type="number"]').val('').prop('readonly', true);
                }
            });

            $('body').on('click', '.btn-add-shipping', function() {
                var row = $(this).parent().parent().parent().find('tr').length;
                var id_variant = $(this).data('variant-id');

                if (id_variant != undefined) {
                    var html_to_add = `
                                <tr>
                                    <td>
                                        <select class="form-control shipper" name="variant_shipping-${id_variant}[shipper][${row}][]">
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="variant_shipping-${id_variant}[from][]" class="form-control min-qty-shipping" id="" placeholder="{{ translate('From QTY') }}"></td>
                                    <td><input type="number" name="variant_shipping-${id_variant}[to][]" class="form-control max-qty-shipping" id="" placeholder="{{ translate('To QTY') }}"></td>
                                    <td><input type="number" class="form-control estimated_order" name="variant_shipping-${id_variant}[estimated_order][]" placeholder="{{ translate('Days') }}"></td>
                                    <td><input type="number" class="form-control estimated_shipping" name="variant_shipping-${id_variant}[estimated_shipping][]" placeholder="{{ translate('Days') }}"></td>
                                    <td>
                                        <select class="form-control paid" name="variant_shipping-${id_variant}[paid][]">
                                            <option value="" selected>{{ translate('Choose option') }}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{ translate('Buyer') }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control shipping_charge" name="variant_shipping-${id_variant}[shipping_charge][]">
                                            <option value="" selected>{{ translate('Choose shipping charge') }}</option>
                                            <option value="flat" @selected(old('shipping_charge') == 'flat')>{{ translate('Flat-rate regardless of quantity') }}</option>
                                            <option value="charging" @selected(old('shipping_charge') == 'charging')>{{ translate('Charging per Unit of Sale') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control flat_rate_shipping" placeholder="{{ translate('Flat rate amount') }}" name="variant_shipping-${id_variant}[flat_rate_shipping][]" readonly></td>
                                    <td><input type="number" class="form-control charge_per_unit_shipping" placeholder="{{ translate('Charge unit') }}" name="variant_shipping-${id_variant}[charge_per_unit_shipping][]" readonly></td>
                                    <td>
                                        <i class="las la-plus btn-add-shipping" data-variant-id="${id_variant}" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                        <i class="las la-trash delete_shipping_canfiguration" data-variant-id="${id_variant}" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                                    </td>
                                </tr>
                            `;
                } else {
                    if ($(this).closest('#variant_informations').length) {
                        var html_to_add = `
                                <tr>
                                   <td>
                                        <select class="form-control shipper">
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number"  class="form-control min-qty-shipping" id="" placeholder="{{ translate('From QTY') }}"></td>
                                    <td><input type="number"  class="form-control max-qty-shipping" id="" placeholder="{{ translate('To QTY') }}"></td>
                                    <td><input type="number" class="form-control estimated_order"  placeholder="{{ translate('Days') }}"></td>
                                    <td><input type="number" class="form-control estimated_shipping"  placeholder="{{ translate('Days') }}"></td>
                                    <td>
                                        <select class="form-control paid" >
                                            <option value="" selected>{{ translate('Choose option') }}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{ translate('Buyer') }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control shipping_charge">
                                            <option value="" selected>{{ translate('Choose shipping charge') }}</option>
                                            <option value="flat" @selected(old('shipping_charge') == 'flat')>{{ translate('Flat-rate regardless of quantity') }}</option>
                                            <option value="charging" @selected(old('shipping_charge') == 'charging')>{{ translate('Charging per Unit of Sale') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control flat_rate_shipping" placeholder="{{ translate('Flat rate amount') }}" readonly></td>
                                    <td><input type="number" class="form-control charge_per_unit_shipping" placeholder="{{ translate('Charge unit') }}" readonly></td>
                                    <td>
                                        <i class="las la-plus btn-add-shipping" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                        <i class="las la-trash delete_shipping_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                                    </td>
                                </tr>
                            `;
                    } else {
                        var html_to_add = `
                                <tr>
                                   <td>
                                        <select class="form-control shipper" name="shipper[]">
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="from_shipping[]" class="form-control min-qty-shipping" id="" placeholder="{{ translate('From QTY') }}"></td>
                                    <td><input type="number" name="to_shipping[]" class="form-control max-qty-shipping" id="" placeholder="{{ translate('To QTY') }}"></td>
                                    <td><input type="number" class="form-control estimated_order" name="estimated_order[]" placeholder="{{ translate('Days') }}"></td>
                                    <td><input type="number" class="form-control estimated_shipping" name="estimated_shipping[]" placeholder="{{ translate('Days') }}"></td>
                                    <td>
                                        <select class="form-control paid" name="paid[]">
                                            <option value="" selected>{{ translate('Choose option') }}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{ translate('Buyer') }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control shipping_charge" name="shipping_charge[]">
                                            <option value="" selected>{{ translate('Choose shipping charge') }}</option>
                                            <option value="flat" @selected(old('shipping_charge') == 'flat')>{{ translate('Flat-rate regardless of quantity') }}</option>
                                            <option value="charging" @selected(old('shipping_charge') == 'charging')>{{ translate('Charging per Unit of Sale') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control flat_rate_shipping" placeholder="{{ translate('Flat rate amount') }}" name="flat_rate_shipping[]" readonly></td>
                                    <td><input type="number" class="form-control charge_per_unit_shipping" placeholder="{{ translate('Charge unit') }}" name="charge_per_unit_shipping[]" readonly></td>
                                    <td>
                                        <i class="las la-plus btn-add-shipping" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                        <i class="las la-trash delete_shipping_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                                    </td>
                                </tr>
                            `;
                    }
                }

                // add another row in shipping configuration
                $(this).parent().parent().parent().append(html_to_add);
            });

            $('body').on('click', '.btn-add-sample-shipping', function() {
                let row = $(this).parent().parent().parent().find('tr').length;
                let id_variant = $(this).data('variant-id');
                let clonedTr = $(this).parent().parent().clone();
                let removeIcon = `
                    <i
                      class="las la-trash delete_sample_shipping_canfiguration"
                      style="margin-left: 5px; margin-top: 17px;"
                      title="{{ __("Delete this ligne") }}"
                    ></i>
                `;
                clonedTr.find("td:last").append(removeIcon);
                $(this).parent().parent().parent().append(clonedTr);
            });

            $('body').on('click', '.delete_shipping_canfiguration', function() {
                //remove row in shipping configuration
                var current = $(this).parent().parent().parent();
                var variant_id = $(this).data('variant-id');
                $(this).parent().parent().remove();

                var count = 0;
                current.find('.shipper').each(function(index) {
                    if (variant_id == undefined) {
                        $(this).attr('name', 'shipper[' + count + '][]')
                    } else {
                        $(this).attr('name', 'variant_shipping-' + variant_id + '[shipper][' +
                            count + '][]')
                    }

                    count++
                });
            })

            $('body').on('change', '.shipper', function() {
                let count_shippers = {{ count($supported_shippers) }};
                let selected = $(this).val();

                if(["vendor", "third_party"].includes(selected) === true) {
                    $(this).parent().parent().find("input").each(function(_, el) {
                        $(el).attr("disabled", false)
                    });
                } else {
                    $(this).parent().parent().find("input").each(function(_, el) {
                        $(el).attr("disabled", true)
                    });
                }

                if (selected.indexOf('third_party') !== -1) {
                    if (count_shippers == 0) {
                        let title = "{{ translate('Default Shipping Configuration') }}";
                        let message = '{{ __("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}';

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');
                    } else {
                        let weight = $('#weight').val();
                        let length = $('#length').val();
                        let width = $('#width').val();
                        let height = $('#height').val();
                        let breakable = $('#breakable').val();
                        let min_third_party = $('#min_third_party').val();
                        let max_third_party = $('#max_third_party').val();
                        let unit_third_party = $('#unit_third_party').val();

                        if ((weight == '') || (length == '') || (width == '') || (height == '') || (
                                min_third_party == '') || (max_third_party == '')) {
                            let title = "{{ translate('Default Shipping Configuration') }}";
                            let message =
                                "{{ translate('Please ensure that all required fields are filled to know all information about your package.') }}";

                            $('#title-modal').text(title);
                            $('#text-modal').html(message);

                            $('#modal-info').modal('show');
                        } else {
                            length = parseInt(length);
                            height = parseInt(height);
                            width = parseInt(width);
                            weight = parseInt(weight);
                            var volumetric_weight = (length * height * width) / 5000;
                            var chargeable_weight = 0;
                            var html = '';
                            if (volumetric_weight > weight) {
                                chargeable_weight = volumetric_weight;
                            } else {
                                chargeable_weight = weight;
                            }

                            if (chargeable_weight > 30) {
                                var title = "{{ translate('Default Shipping Configuration') }}";
                                var message = "{{ translate('Chargeable Weight = ') }}" + Number(
                                        chargeable_weight.toFixed(2)) +
                                    ", {{ translate('then not accepted by our shipper') }}";

                                $('#title-modal').text(title);
                                $('#text-modal').text(message);

                                $('#modal-info').modal('show');

                                $(this).find("option[value='third_party']").prop("disabled", true);
                                $(this).find("option[value='third_party']").prop('selected', false);
                            } else {
                                $(this).parent().parent().find('.estimated_shipping').prop('disabled',
                                    true);
                                $(this).parent().parent().find('.shipping_charge').find("option:first")
                                    .prop("selected", true);
                                $(this).parent().parent().find('.shipping_charge').addClass(
                                    "disabled-select");
                                $(this).parent().parent().find('.shipping_charge').prop("disabled", true);

                                $(this).parent().parent().find('.paid').find("option:last")
                                    .prop("selected", true);
                                $(this).parent().parent().find('.paid').prop("disabled", true);

                                $(this).parent().parent().find('.charge_per_unit_shipping').prop('disabled',
                                    true);
                                $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                                $(this).parent().parent().find('.estimated_shipping').val(null);

                                $(this).parent().parent().find('.flat_rate_shipping').prop('disabled',
                                    true);
                                $(this).parent().parent().find('.flat_rate_shipping').val(null);
                            }
                        }
                    }
                }

                if (selected.indexOf('vendor') !== -1) {
                    $(this).parent().parent().find('.estimated_shipping').prop('readonly', false);
                    $(this).parent().parent().find('.shipping_charge').find("option:first").prop("selected",
                        true);
                    $(this).parent().parent().find('.shipping_charge').addClass("disabled-select");
                    $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', true);
                    $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                    $(this).parent().parent().find('.paid').val(null);
                    $(this).parent().parent().find('.estimated_shipping').val(null);
                    $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', true);
                    $(this).parent().parent().find('.flat_rate_shipping').val(null);
                }
            });

            $('body').on('change', '.paid', function() {
                var shippers = $(this).parent().parent().find('.shipper').val();
                if (shippers.indexOf('vendor') !== -1) {
                    if ($(this).val() != "buyer") {
                        $(this).parent().parent().find('.shipping_charge').find("option:first").prop(
                            "selected", true);
                        $(this).parent().parent().find('.shipping_charge').addClass("disabled-select");
                        $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', true);
                        $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                        $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', true);
                        $(this).parent().parent().find('.flat_rate_shipping').val(null);
                    } else {
                        $(this).parent().parent().find('.shipping_charge').removeClass("disabled-select");
                    }
                } else {
                    // Swal.fire({
                    //     title: 'Cancelled',
                    //     text: "You cannot selected, if you don't selected vendor in shippers",
                    //     icon: 'error',
                    //     scrollbarPadding: false,
                    //     backdrop:false,
                    // });

                    var title = "{{ translate('Default Shipping Configuration') }}";
                    var messagePart1 = "{{ translate('You cannot selected, if you don') }}";
                    var messagePart2 = "{{ translate('t selected vendor in shippers') }}";

                    // Combine the parts with the single quote correctly placed
                    var message = messagePart1 + "'" + messagePart2;

                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show');
                }
            })

            $('body').on('change', '.shipping_charge', function() {
                if ($(this).parent().parent().find('.paid').val() == 'vendor') {
                    // Swal.fire({
                    //     title: 'Cancelled',
                    //     text: "You cannot choose shipping charge when it is paid by vendor.",
                    //     icon: 'error',
                    //     scrollbarPadding: false,
                    //     backdrop:false,
                    // });

                    var title = "{{ translate('Default Shipping Configuration') }}";
                    var message =
                        "{{ translate('You cannot choose shipping charge when it is paid by vendor.') }}";

                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show');

                    $(this).find('option').eq(0).prop('selected', true);
                } else if ($(this).parent().parent().find('.paid').val() == 'buyer') {
                    if ($(this).val() == "flat") {
                        $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', false);
                        $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', true);
                        $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                    } else {
                        $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', false);
                        $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', true);
                        $(this).parent().parent().find('.flat_rate_shipping').val(null);
                    }
                } else {
                    // Swal.fire({
                    //     title: 'Cancelled',
                    //     text: "Wrong choice.",
                    //     icon: 'error',
                    //     scrollbarPadding: false,
                    //     backdrop:false,
                    // });

                    var title = "{{ translate('Default Shipping Configuration') }}";
                    var message = "{{ translate('Wrong choice.') }}";

                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show');

                    $(this).find('option').eq(0).prop('selected', true);
                }
            })

            $('body').on('change', '.variant-shipping', function() {
                if ($(this).is(':not(:checked)')) {
                    var clonedDiv = $('#table_shipping_configuration').clone();
                    var id = $(this).data('id_variant');

                    clonedDiv.find('.shipper').each(function(index, element) {
                        $(element).attr('name', `variant_shipping-${id}[shipper][${index}][]`)
                        $('#shipping_configuration_box #table_shipping_configuration').find(
                            '.shipper')?.each(function(key, element_original) {
                            if (index == key) {
                                let value = $(element_original).val();
                                $(element).find(`option[value="${value}"]`)
                                        .prop('selected', true);
                            }
                        })
                    });

                    clonedDiv.find('.multi-select-container').each(function(index, element) {
                        if (index % 2 != 0) {
                            $(element).remove();
                        }
                    })

                    clonedDiv.find('.paid').each(function(index, element) {
                        $(element).attr('name', `variant_shipping-` + id + `[paid][]`)

                        $('#shipping_configuration_box #table_shipping_configuration').find('.paid')
                            .each(function(key, element_original) {
                                if (index == key) {
                                    $(element).find('option[value="' + $(element_original)
                                        .val() + '"]').prop('selected', true);
                                }
                            })
                    });

                    clonedDiv.find('.shipping_charge').each(function(index, element) {
                        $(element).attr('name', `variant_shipping-` + id + `[shipping_charge][]`)
                        $('#shipping_configuration_box #table_shipping_configuration').find(
                            '.shipping_charge').each(function(key, element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });

                    clonedDiv.find('.min-qty-shipping').attr('name', `variant_shipping-` + id + `[from][]`)
                    clonedDiv.find('.max-qty-shipping').attr('name', `variant_shipping-` + id + `[to][]`)
                    clonedDiv.find('.estimated_order').attr('name', `variant_shipping-` + id +
                        `[estimated_order][]`)
                    clonedDiv.find('.estimated_shipping').attr('name', `variant_shipping-` + id +
                        `[estimated_shipping][]`)
                    clonedDiv.find('.shipping_charge').attr('name', `variant_shipping-` + id +
                        `[shipping_charge][]`)
                    clonedDiv.find('.flat_rate_shipping').attr('name', `variant_shipping-` + id +
                        `[flat_rate_shipping][]`)
                    clonedDiv.find('.charge_per_unit_shipping').attr('name', `variant_shipping-` + id +
                        `[charge_per_unit_shipping][]`)
                    clonedDiv.find('.btn-add-shipping').attr('data-id', id);
                    clonedDiv.find('.delete_shipping_canfiguration').attr('data-variant-id', id);
                    clonedDiv.find("input").each((_, el) => {
                        $(el).attr("disabled", true);
                    });
                    $(this).parent().parent().parent().find('#bloc_default_shipping').append(clonedDiv);
                } else {
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

                if ((weight == '') || (length == '') || (width == '') || (height == '') || (
                        min_third_party == '') || (max_third_party == '')) {
                    $('#result_calculate_third_party').empty();
                } else {
                    length = parseInt(length);
                    height = parseInt(height);
                    width = parseInt(width);
                    weight = parseInt(weight);
                    var volumetric_weight = (length * height * width) / 5000;
                    var chargeable_weight = 0;
                    var unit = $(this).parent().parent().find('#weight_unit').val();
                    var max = 30;
                    if (unit == "pounds") {
                        max *= 2.2;
                    }
                    var html = '';
                    if (volumetric_weight > weight) {
                        chargeable_weight = volumetric_weight;
                    } else {
                        chargeable_weight = weight;
                    }

                    if (unit == "pounds") {
                        chargeable_weight *= 2.2;
                    }

                    if (chargeable_weight > max) {
                        html = '<span style="color: red">{{ translate('Chargeable Weight = ') }}' +
                            Number(chargeable_weight.toFixed(2)) +
                            ", {{ translate('then not accepted by Aramex') }} </span>"
                    } else {
                        html = '<span style="color: green">{{ translate('Chargeable Weight = ') }}' +
                            Number(chargeable_weight.toFixed(2)) +
                            ", {{ translate('then accepted by Aramex') }} </span>"
                    }

                    $('#result_calculate_third_party').html(html);
                }
            });

            $('#bloc_third_party_sample input[type="number"], select.calculate').on('input change', function() {
                var weight = $(this).parent().parent().find('#package_weight_sample').val();
                var length = $(this).parent().parent().find('#length_sample').val();
                var width = $(this).parent().parent().find('#width_sample').val();
                var height = $(this).parent().parent().find('#height_sample').val();
                var breakable = $(this).parent().parent().find('#breakable_sample').val();
                var min_third_party = $(this).parent().parent().find('#min_third_party_sample').val();
                var max_third_party = $(this).parent().parent().find('#max_third_party_sample').val();
                var unit_third_party = $(this).parent().parent().find('#unit_third_party_sample').val();

                if ((weight == '') || (length == '') || (width == '') || (height == '') || (
                        min_third_party == '') || (max_third_party == '')) {
                    $('#result_calculate_third_party_sample').empty();
                } else {
                    length = parseInt(length);
                    height = parseInt(height);
                    width = parseInt(width);
                    weight = parseInt(weight);
                    var volumetric_weight = (length * height * width) / 5000;
                    var chargeable_weight = 0;
                    var unit = $(this).parent().parent().find('#weight_unit_sample').val();
                    var max = 30;
                    if (unit == "pounds") {
                        max *= 2.2;
                    }
                    var html = '';
                    if (volumetric_weight > weight) {
                        chargeable_weight = volumetric_weight;
                    } else {
                        chargeable_weight = weight;
                    }

                    if (unit == "pounds") {
                        chargeable_weight *= 2.2;
                    }

                    if (chargeable_weight > max) {
                        html = '<span style="color: red">{{ translate('Chargeable Weight = ') }}' +
                            Number(chargeable_weight.toFixed(2)) +
                            ", {{ translate('then not accepted by Aramex') }} </span>"
                    } else {
                        html = '<span style="color: green">{{ translate('Chargeable Weight = ') }}' +
                            Number(chargeable_weight.toFixed(2)) +
                            ", {{ translate('then accepted by Aramex') }} </span>"
                    }

                    $('#result_calculate_third_party_sample').html(html);
                }
            });

            //sample script
            $('body').on('click', '.btn-add-sample', function() {
                var html_to_add = `
                                <tr>
                                    <td>
                                        <select class="form-control shipper_sample" name="shipper_sample[]">
                                            <option value="" disabled selected>{{ translate('Choose shipper') }}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                        </select>
                                    </td>shipping_
                                    <td><input type="number" class="form-control estimated_sample" name="estimated_sample[]"></td>
                                    <td><input type="number" class="form-control estimated_shipping_sample" name="estimated_shipping_sample[]"></td>
                                    <td>
                                        <select class="form-control paid_sample" name="paid_sample[]">
                                            <option value="" disabled selected>{{ translate('Choose shipper') }}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{ translate('Buyer') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control shipping_amount" name="shipping_amount[]"></td>
                                    <td>
                                        <i class="las la-plus btn-add-sample" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                        <i class="las la-trash delete_sample_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                                    </td>
                                </tr>
                            `;
                // add another row in shipping configuration
                $(this).parent().parent().parent().append(html_to_add);
            });

            $('body').on('click', '.delete_sample_canfiguration', function() {
                //remove row in shipping configuration
                $(this).parent().parent().remove();
            })

            $('body').on('change', '.paid_sample', function() {
                if ($(this).val() == "buyer") {
                    $(this).parent().parent().find('.shipping_amount').prop('readonly', false);
                } else {
                    $(this).parent().parent().find('.shipping_amount').prop('readonly', true);
                    $(this).parent().parent().find('.shipping_amount').val(null);
                }
            });

            $('body').on('change', '.variant-sample-shipping', function() {
                if ($(this).is(':not(:checked)')) {
                    let clonedDiv = $('#table_sample_configuration').clone();
                    let paid_sample = $('#table_sample_configuration').find('.paid_sample').val();
                    let shipper_sample = $('#table_sample_configuration').find('.shipper_sample').val();
                    let id_variant = $(this).data('id_new_variant');

                    clonedDiv.find('.paid_sample').find(`option[value="${paid_sample}"]`).prop(
                        'selected', true);

                    clonedDiv.find('.shipper_sample').each(function(index, element) {
                        $('#table_sample_configuration').find('.shipper_sample').each(function(key,
                            element_original) {
                            if (index == key) {
                                let value = $(element_original).val();
                                $(element).find(`option[value="${value}"]`)
                                    .prop('selected', true);
                            }
                        })
                    });

                    clonedDiv.find('.shipper_sample').attr('name', 'variant_shipper_sample-' + id_variant +
                        '[]');

                    clonedDiv.find('.multi-select-container').each(function(index, element) {
                        if (index % 2 != 0) {
                            $(element).remove();
                        }
                    })
                    clonedDiv.find('.paid_sample').attr('name', 'paid_sample-' + id_variant);
                    clonedDiv.find('.estimated_sample').attr('name', 'estimated_sample-' + id_variant);
                    clonedDiv.find('.estimated_shipping_sample').attr('name', 'estimated_shipping_sample-' +
                        id_variant);
                    clonedDiv.find('.shipping_amount').attr('name', 'shipping_amount-' + id_variant);
                    clonedDiv.find("input").each((_, el) => {
                        $(el).attr("disabled", true);
                    });
                    $(this).parent().parent().parent().find('#bloc-sample-shipping').append(clonedDiv);
                } else {
                    $(this).parent().parent().parent().find('#bloc-sample-shipping').empty();
                }
            })

            $('body').on('change', '.sample-available', function() {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                    $("#sample-description-wrapper").show();
                    $("#sample-price-wrapper").show();
                } else {
                    $(this).val(0);
                    $("#sample-description-wrapper").hide();
                    $("#sample-price-wrapper").hide();
                }
            });

            $('body').on('change', '.variant-sample-available', function() {
                if ($(this).is(':checked')) {
                    $(this).parent().parent().parent().parent().find('.variant-sample-pricing').prop(
                        'disabled', false);
                    $(this).parent().parent().parent().parent().find('.variant-sample-shipping').prop(
                        'disabled', false);
                } else {
                    $(this).parent().parent().parent().parent().find('.variant-sample-pricing').prop(
                        'checked', true);
                    $(this).parent().parent().parent().parent().find('.variant-sample-shipping').prop(
                        'checked', true);
                    $(this).parent().parent().parent().parent().find('.variant-sample-pricing').prop(
                        'disabled', true);
                    $(this).parent().parent().parent().parent().find('.variant-sample-shipping').prop(
                        'disabled', true);
                    $(this).parent().parent().parent().parent().find('#bloc-sample-shipping').empty();
                    $(this).parent().parent().parent().parent().find(
                        '.bloc_sample_pricing_configuration_variant').hide();
                }
            });

            $('body').on('change', '.shipper_sample', function() {
                let count_shippers = {{ count($supported_shippers) }};
                let selected = $(this).val();

                if (["vendor", "third_party"].includes(selected) === true) {
                    $(this).parent().parent().find('input').each(function(_, el) {
                        $(el).attr("disabled", false);
                    });
                } else {
                    $(this).parent().parent().find('input').each(function(_, el) {
                        $(el).attr("disabled", true);
                    });
                }

                if (selected.indexOf('third_party') !== -1) {
                    $(this).parent().parent().find('.shipping_amount').val('');
                    $(this).parent().parent().find('.shipping_amount').prop('disabled', true);
                    $(this).parent().parent().find('.estimated_shipping_sample').val('');
                    $(this).parent().parent().find('.estimated_shipping_sample').prop('disabled', true);
                    $(this).parent().parent().find('.paid_sample').find("option:last").prop("selected", true);
                    $(this).parent().parent().find('.paid_sample').prop('disabled', true);

                    if (count_shippers == 0) {
                        let title = "{{ translate('Default Shipping Configuration') }}";
                        let message = '{{ __("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}';

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');

                        var checkbox = $(this).parent().find('input[type="checkbox"][value="third_party"]');
                        if (selected.length == 1) {
                            $(this).parent().find('.multi-select-button').text('-- Select --');
                        } else {
                            $(this).parent().find('.multi-select-button').text('vendor');
                        }
                        // Uncheck the checkbox
                        checkbox.prop('checked', false);
                        $(this).find("option[value='third_party']").prop('disabled', false);
                        $(this).find("option[value='third_party']").prop('selected', false);

                        $(this).find("option:first").prop("selected", true)
                        $(this).parent().parent().find('.shipping_amount').val('');
                        $(this).parent().parent().find('.shipping_amount').prop('disabled', false);
                        $(this).parent().parent().find('.estimated_shipping_sample').val('');
                        $(this).parent().parent().find('.estimated_shipping_sample').prop('disabled', false);
                        $(this).parent().parent().find('.paid_sample').find("option:first").prop("selected", true);
                        $(this).parent().parent().find('.paid_sample').prop('disabled', false);
                    } else {
                        let weight = $('#package_weight_sample').val();
                        let length = $('#length_sample').val();
                        let width = $('#width_sample').val();
                        let height = $('#height_sample').val();
                        let breakable = $('#breakable_sample').val();
                        let min_third_party = $('#min_third_party_sample').val();
                        let max_third_party = $('#max_third_party_sample').val();
                        let unit_third_party = $('#unit_third_party_sample').val();

                        if ((weight == '') || (length == '') || (width == '') || (height == '') || (
                                min_third_party == '') || (max_third_party == '')) {
                            let title = "{{ translate('Default Shipping Configuration') }}";
                            let message =
                                "{{ translate('Please ensure that all required fields are filled to know all information about your package.') }}";

                            $('#title-modal').text(title);
                            $('#text-modal').html(message);

                            $('#modal-info').modal('show');
                            let checkbox = $(this).parent().find(
                                'input[type="checkbox"][value="third_party"]');
                            if (selected.length == 1) {
                                $(this).parent().find('.multi-select-button').text('-- Select --');
                            } else {
                                $(this).parent().find('.multi-select-button').text('vendor');
                            }

                            // Uncheck the checkbox
                            checkbox.prop('checked', false);
                            $(this).find("option[value='third_party']").prop('disabled', false);
                            $(this).find("option[value='third_party']").prop('selected', false);
                        } else {
                            length = parseInt(length);
                            height = parseInt(height);
                            width = parseInt(width);
                            weight = parseInt(weight);
                            let volumetric_weight = (length * height * width) / 5000;
                            let chargeable_weight = 0;
                            let unit = $('#weight_unit_sample').val();
                            let max = 30;
                            if (unit == "pounds") {
                                max *= 2.2;
                            }
                            let html = '';
                            if (volumetric_weight > weight) {
                                chargeable_weight = volumetric_weight;
                            } else {
                                chargeable_weight = weight;
                            }

                            if (unit == "pounds") {
                                chargeable_weight *= 2.2;
                            }

                            if (chargeable_weight > max) {
                                var title = "{{ translate('Default Shipping Configuration') }}";
                                var message = "{{ translate('Chargeable Weight = ') }}" + Number(
                                        chargeable_weight.toFixed(2)) +
                                    ", {{ translate('then not accepted by our shipper') }}";

                                $('#title-modal').text(title);
                                $('#text-modal').text(message);

                                $('#modal-info').modal('show');

                                var checkbox = $(this).parent().find(
                                    'input[type="checkbox"][value="third_party"]');
                                if (selected.length == 1) {
                                    $(this).parent().find('.multi-select-button').text('-- Select --');
                                } else {
                                    $(this).parent().find('.multi-select-button').text('vendor');
                                }

                                // Uncheck the checkbox
                                checkbox.prop('checked', false);
                                $(this).find("option[value='third_party']").prop('disabled', false);
                                $(this).find("option[value='third_party']").prop('selected', false);
                            }
                        }
                    }
                }

                if (selected.indexOf('vendor') !== -1) {
                    $(this).parent().parent().find('.shipping_amount').prop('disabled', false);
                    $(this).parent().parent().find('.estimated_shipping_sample').prop('disabled', false);
                    $(this).parent().parent().find('.paid_sample').prop('disabled', false);
                    $(this).parent().parent().find('.paid_sample').val('');
                }
            });
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

                    if ((!node.children || node.children.length === 0) && node.state.loaded == true) {
                        $('#message-category').text("");
                        $('#check_selected_parent_id').val(1);
                        $('#message-category').css({
                            'color': 'green',
                            'margin-right': '7px'
                        });
                        // The node does not have children, proceed with your logic
                        $('#selected_parent_id').val(selectedId); // Update hidden input with selected ID
                        $('#attributes_bloc').html(
                            '<select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple disabled data-placeholder="{{ translate('No attributes found') }}"></select>'
                        );
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
                        $('#message-category').css({
                            'color': 'red',
                            'margin-right': '7px'
                        });
                        $('#check_selected_parent_id').val(-1);
                        $('#attributes_bloc').html(
                            '<select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple disabled data-placeholder="{{ translate('No attributes found') }}"></select>'
                        );
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
                    $('#jstree').jstree(true).settings.core.data.url =
                        "{{ route('seller.categories.jstree') }}";

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
                            //console.log(response);
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
                type: "GET",
                url: '{{ route('seller.products.getAttributeCategorie') }}',
                data: {
                    id: categorie_id
                },
                success: function(data) {
                    if (data.html != "") {
                        $('#attributes_bloc').html(data.html);
                    } else {
                        $('#attributes_bloc').html(
                            '<select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple disabled data-placeholder="{{ translate('No attributes found') }}"></select>'
                        );
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('choice_form').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                var clickedButtonValue = event.submitter.value;
                document.getElementById('submit_button').value = clickedButtonValue;

                var valid_category = $('#check_selected_parent_id').val();
                if (valid_category == 1) {
                    if (clickedButtonValue === "publish") {
                        //Validation of price
                        var check_price = true;
                        var check_attributes = true;
                        var min_qty = $('#min-qty-parent').val();
                        var max_qty = $('#max-qty-parent').val();
                        var unit_price = $('#unit-price-parent').val();

                        if ($('body input[name="activate_attributes"]').is(':checked')) {
                            var attributes_selected = $('body #attributes').val();
                            if (attributes_selected.length != 0) {
                                $('body #bloc_variants_created .variant-pricing').each(function() {
                                    if ($(this).is(':checked')) {
                                        if ((min_qty == "") || (max_qty == "") || (unit_price ==
                                                "")) {
                                            check_price = false;
                                        }
                                    } else {
                                        $(this).parent().parent().parent().find(
                                            '#bloc_pricing_configuration').find(
                                            '.min-qty-variant').each(function() {
                                            if ($(this).val() == '') {
                                                check_price = false;
                                            }
                                        });

                                        $(this).parent().parent().parent().find(
                                            '#bloc_pricing_configuration').find(
                                            '.max-qty-variant').each(function() {
                                            if ($(this).val() == '') {
                                                check_price = false;
                                            }
                                        });

                                        $(this).parent().parent().parent().find(
                                            '#bloc_pricing_configuration').find(
                                            '.unit-price-variant').each(function() {
                                            if ($(this).val() == '') {
                                                check_price = false;
                                            }
                                        });
                                    }
                                });

                                $('body #bloc-pricing-parent tr').each(function() {
                                    $(this).find('.min-qty').each(function() {
                                        if ($(this).val() == '') {
                                            check_price = false;
                                        }
                                    });

                                    $(this).find('.max-qty').each(function() {
                                        if ($(this).val() == '') {
                                            check_price = false;
                                        }
                                    });

                                    $(this).find('.unit-price-variant').each(function() {
                                        if ($(this).val() == '') {
                                            check_price = false;
                                        }
                                    });
                                });
                            } else {
                                check_attributes = false;
                                check_price = false;
                            }

                        } else {
                            $('#bloc-pricing-parent tr').each(function() {
                                $(this).find('.min-qty').each(function() {
                                    if ($(this).val() == '') {
                                        check_price = false;
                                    }
                                });

                                $(this).find('.max-qty').each(function() {
                                    if ($(this).val() == '') {
                                        check_price = false;
                                    }
                                });

                                $(this).find('.unit-price-variant').each(function() {
                                    if ($(this).val() == '') {
                                        check_price = false;
                                    }
                                });
                            });
                        }

                        //Validation of sample description
                        var check_sample_description = true;
                        if ($('#sample_description_parent').val().trim() === '') {
                            check_sample_description = false
                        }

                        //Validation of short description
                        var check_short_description = true;
                        if ($('#short_description').val().trim() === '') {
                            check_short_description = false
                        }

                        //Validation of long description
                        var check_long_description = true;
                        if ($('#long_description').val().trim() === '') {
                            check_long_description = false
                        }

                        //Validation of sample price
                        var sample_price_parent = parseFloat($('#sample_price_parent').val());
                        var check_sample_price = true;
                        var check_sample_price_undefined = true;
                        if (!isNaN(sample_price_parent)) {
                            if (sample_price_parent <= 0) {
                                check_sample_price = false;
                            }
                        } else {
                            check_sample_price_undefined = false;
                        }

                        var tagifyInputs = $(".aiz-tag-input").not(".tagify");
                        var isEmpty = false;

                        tagifyInputs.each(function() {
                            var tagify = $(this).data('tagify');

                            if (tagify.value.length === 0) {
                                isEmpty = true;
                                return false; // Exit the loop early if an empty Tagify input is found
                            }
                        });

                        //Validation of shipping
                        var check_shipping = true;
                        var min_qty_shipping = $('#min-qty-shipping').val();
                        var max_qty_shipping = $('#max-qty-shipping').val();
                        var shipper_shipping = $('#shipper_shipping').val();

                        if ($('body input[name="activate_attributes"]').is(':checked')) {
                            var attributes_selected = $('body #attributes').val();
                            if (attributes_selected.length != 0) {
                                $('body #bloc_variants_created .variant-shipping').each(function() {
                                    if ($(this).is(':checked')) {
                                        $('#shipping_configuration_box tr').each(function() {
                                            $(this).find('.min-qty-shipping').each(
                                                function() {
                                                    if ($(this).val() == '') {
                                                        check_shipping = false;
                                                    }
                                                });

                                            $(this).find('.max-qty-shipping').each(
                                                function() {
                                                    if ($(this).val() == '') {
                                                        check_shipping = false;
                                                    }
                                                });

                                            $(this).find('.shipper').each(function() {
                                                if ($(this).val() == '') {
                                                    check_shipping = false;
                                                }
                                            });
                                        });
                                    } else {
                                        $(this).parent().parent().parent().find(
                                                '#bloc_default_shipping').find('.min-qty-shipping')
                                            .each(function() {
                                                if ($(this).val() == '') {
                                                    check_shipping = false;
                                                }
                                            });

                                        $(this).parent().parent().parent().find(
                                                '#bloc_default_shipping').find('.max-qty-shipping')
                                            .each(function() {
                                                if ($(this).val() == '') {
                                                    check_shipping = false;
                                                }
                                            });

                                        $(this).parent().parent().parent().find(
                                            '#bloc_default_shipping').find('.shipper').each(
                                            function() {
                                                if ($(this).val() == '') {
                                                    check_shipping = false;
                                                }
                                            });

                                        $('#shipping_configuration_box tr').each(function() {
                                            $(this).find('.min-qty-shipping').each(
                                                function() {
                                                    if ($(this).val() == '') {
                                                        check_shipping = false;
                                                    }
                                                });

                                            $(this).find('.max-qty-shipping').each(
                                                function() {
                                                    if ($(this).val() == '') {
                                                        check_shipping = false;
                                                    }
                                                });

                                            $(this).find('.shipper').each(function() {
                                                if ($(this).val() == '') {
                                                    check_shipping = false;
                                                }
                                            });
                                        });
                                    }
                                });
                            } else {
                                check_attributes = false;
                                check_shipping = false;
                            }

                        } else {
                            $('#shipping_configuration_box tr').each(function() {
                                $(this).find('.min-qty-shipping').each(function() {
                                    if ($(this).val() == '') {
                                        check_shipping = false;
                                    }
                                });

                                $(this).find('.max-qty-shipping').each(function() {
                                    if ($(this).val() == '') {
                                        check_shipping = false;
                                    }
                                });

                                $(this).find('.shipper').each(function() {
                                    if ($(this).val() == '') {
                                        check_shipping = false;
                                    }
                                });
                            });
                        }

                        //Validation of sku
                        var check_sku = true;
                        if ($('body input[name="activate_attributes"]').is(':checked')) {
                            var attributes_selected = $('body #attributes').val();
                            if (attributes_selected.length != 0) {
                                $('body #bloc_variants_created .sku').each(function() {
                                    if ($(this).val() == '') {
                                        check_sku = false;
                                    }
                                });
                            } else {
                                check_attributes = false;
                                check_sku = false;
                            }

                        } else {
                            if (($('body #sku_product_parent').val() == '') || ($(
                                    'body #sku_product_parent').val() == undefined)) {
                                check_sku = false
                            }
                        }

                        //Validation of images
                        var check_images = true;
                        if ($('body input[name="activate_attributes"]').is(':checked')) {
                            var attributes_selected = $('body #attributes').val();
                            if (attributes_selected.length != 0) {
                                $('body #bloc_variants_created .photos_variant').each(function() {
                                    if ($(this)[0].files.length === 0) {
                                        check_images = false;
                                    }
                                });
                            } else {
                                check_attributes = false;
                                check_images = false;
                            }
                        }

                        //Validation of attributes
                        let check_attributes_empty = true;
                        let check_units_empty = true;

                        if ($('body input[name="activate_attributes"]').is(':checked')) {
                            $("#bloc_variants_created div").each(function(index, element) {
                                $(element).find('.attributes').each(function(index, child_element) {
                                    // Change the attribute name of the current input
                                    if ($(child_element).attr('type') == 'radio') {
                                        let name = $(child_element).attr('name');
                                        if ($(`body input[name="${name}"]:checked`)
                                            .length === 0) {
                                            check_attributes_empty = false;
                                        }
                                    } else {
                                        let specificString = 'attributes-undefined-';

                                        if ((!$(child_element).attr('name')?.includes(
                                                specificString)) && ($(child_element).attr(
                                                'name') != undefined)) {
                                            if ($(child_element).val() == '') {
                                                check_attributes_empty = false;
                                            }
                                        }
                                    }
                                });

                                $(element).find('.attributes-units').each(function(index,
                                    child_element_units) {
                                    if ($(child_element_units).val() == '') {
                                        check_units_empty = false;
                                    }
                                });
                            });
                        }

                        let check_attributes_generale_empty = true;
                        let check_units_generale_empty = true;

                        $("#general_attributes div").each(function(index, element) {
                            $(element).find('.attributes').each(function(index, child_element) {
                                // Change the attribute name of the current input
                                if ($(child_element).attr('type') == 'radio') {
                                    let name = $(child_element).attr('name');
                                    if ($(`body input[name="${name}"]:checked`).length ===
                                        0) {
                                        check_attributes_generale_empty = false;
                                    }
                                } else {
                                    if (($(child_element).attr('name') !=
                                            "attribute_generale-undefined") && ($(
                                            child_element).attr('name') != undefined)) {
                                        if ($(child_element).val() == '') {
                                            check_attributes_generale_empty = false;
                                        }
                                    }
                                }
                            });

                            $(element).find('.attributes-units').each(function(index,
                                child_element_units) {
                                if ($(child_element_units).val() == '') {
                                    check_units_generale_empty = false;
                                }
                            });
                        });

                        var check_main_images = true;
                        if ($('body #photoUploadcustom')[0].files.length == 0) {
                            check_main_images = false;
                        }

                        var check_thumbnail_images = true;
                        // if($('body #photoUploadThumbnailSeconde')[0].files.length == 0){
                        //     check_thumbnail_images = false;
                        // }

                        var check_sample_price_variant = true;
                        if ($('body input[name="activate_attributes"]').is(':checked')) {
                            $("#bloc_variants_created div").each(function(index, element) {
                                $(element).find('.sample_description').each(function(index,
                                    child_element) {
                                    if ($(this).val() != '') {
                                        var sample_price_variant = $(this).parent().parent()
                                            .parent().find('.sample_price').val();
                                        if (!isNaN(sample_price_variant)) {
                                            if (sample_price_variant <= 0) {
                                                check_sample_price_variant = false;
                                            }
                                        } else {
                                            check_sample_price_variant = false;
                                        }
                                    }

                                });
                            });
                        }

                        var check_sample_shipping_variant = true;
                        if ($('body input[name="activate_attributes"]').is(':checked')) {
                            var attributes_selected = $('body #attributes').val();
                            if (attributes_selected.length != 0) {
                                $('#bloc_variants_created .shipper_sample').each(function() {
                                    if ($(this).val() == '') {
                                        check_sample_shipping_variant = false;
                                    }
                                });

                                $('#bloc_variants_created .estimated_sample').each(function() {
                                    if ($(this).val() == '') {
                                        check_sample_shipping_variant = false;
                                    }
                                });

                                $('#bloc_variants_created .estimated_shipping_sample').each(function() {
                                    if ($(this).val() == '') {
                                        check_sample_shipping_variant = false;
                                    }
                                });

                                $('#bloc_variants_created .paid_sample').each(function() {
                                    if ($(this).val() == '') {
                                        check_sample_shipping_variant = false;
                                    }
                                });

                            }
                        }

                        var check_attributes_selected = true;
                        if ($('body input[name="activate_attributes"]').is(':checked')) {
                            if ($('#attributes option:selected').length == 0) {
                                check_attributes_selected = false;
                            }
                        }

                        var remarks = [];
                        if (check_attributes_selected != false) {
                            if (check_attributes == false) {
                                var message =
                                    "{{ translate('You need to choose at least one attribute.') }}";
                                remarks.push(message);
                            }

                            if ($('#sample_description_parent').val() != '') {
                                if (check_sample_price == false) {
                                    var message =
                                        "{{ translate('The sample price must be greater than or equal to 0.1 AED.') }}";
                                    remarks.push(message);
                                }

                                if (check_sample_price_undefined == false) {
                                    var message =
                                        "{{ translate('The sample price is required and must be greater than or equal to 0.1 AED.') }}";
                                    remarks.push(message);
                                }

                                if ($('#shipper_sample_parent').val() == '' || $('#estimated_sample_parent')
                                    .val() == '' || $('#estimated_shipping_sample_parent').val() == '' || $(
                                        '#paid_sample_parent').val() == '') {
                                    var message =
                                        "{{ translate('There is a problem with the configuration of your shipping sample.') }}";
                                    remarks.push(message);
                                }
                            }

                            if (check_sample_price_variant == false) {
                                var message =
                                    "{{ translate('The sample price variant is required and must be greater than or equal to 0.1 AED.') }}";
                                remarks.push(message);
                            }
                            if (check_shipping == false) {
                                var message =
                                    "{{ translate('There is an issue with your shipping configuration.') }}";
                                remarks.push(message);
                            }
                            if (check_sample_shipping_variant == false) {
                                var message =
                                    "{{ translate('There is a problem with the configuration of your shipping variant sample.') }}";
                                remarks.push(message);
                            }
                            if (check_sku == false) {
                                var message = "{{ translate('There is an empty SKU.') }}";
                                remarks.push(message);
                            }
                            if (check_images == false) {
                                var message =
                                    "{{ translate('All file inputs in the variant section must contain at least one photo.') }}";
                                remarks.push(message);
                            }
                            if (check_main_images == false) {
                                var message =
                                    "{{ translate('The main image must include at least one picture.') }}";
                                remarks.push(message);
                            }
                            if (check_thumbnail_images == false) {
                                var message =
                                    "{{ translate('The thumbnail image must include at least one picture.') }}";
                                remarks.push(message);
                            }

                            if (check_short_description == false) {
                                var message = "{{ translate('The short description is required.') }}";
                                remarks.push(message);
                            }

                            if (check_long_description == false) {
                                var message = "{{ translate('The description is required.') }}";
                                remarks.push(message);
                            }

                            if (check_attributes_empty == false) {
                                var message = "{{ translate('All attributes must have values.') }}";
                                remarks.push(message);
                            }

                            if (check_units_empty == false) {
                                var message = "{{ translate('All units must have values.') }}";
                                remarks.push(message);
                            }

                            if (isEmpty) {
                                var message = "{{ translate('tags input cannot be empty.') }}";
                                remarks.push(message);
                            }

                            if (check_attributes_generale_empty == false) {
                                var message =
                                    "{{ translate('All attributes in section General Attributes must have values.') }}";
                                remarks.push(message);
                            }

                            if (check_units_generale_empty == false) {
                                var message =
                                    "{{ translate('All units in section General Attributes must have values.') }}";
                                remarks.push(message);
                            }
                        } else {
                            var message =
                                '{{ translate('A minimum of one attribute must be selected in order to create your product.') }}';
                            remarks.push(message);
                        }
                        if (clickedButtonValue === "publish") {
                            if (remarks.length != 0) {
                                var html = '<ol>';
                                remarks.forEach(function(item) {
                                    html = html + '<li>' + item + '</li>'
                                });

                                html = html + '</ol>';

                                $('#title-modal').text(
                                    'Please fill the missing fields and/or correct the listed entries in order to submit your product for approval'
                                );
                                $('#text-modal').html(html);

                                $('#modal-info').modal('show');
                                $('#modal-info').find('.modal-body').removeClass('text-center');
                            } else {
                                Swal.fire({
                                    title: "Product Publishing",
                                    text: " {{ translate('Your product has been created successfully, but it will be pending for admin approval. You can set the product published to appear in the marketplace once approved. Do you want to make it published?') }}",
                                    icon: "info",
                                    showCancelButton: true,
                                    confirmButtonText: "Yes",
                                    cancelButtonText: "No",
                                    allowOutsideClick: false,
                                    focusConfirm: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $('#published_after_approve').val(1)
                                    }

                                    Swal.fire({
                                        title: "Product Inventory",
                                        text: "{{ translate('You can create the inventory of the products and make it ready before admin approval. This is recommended if your product will be immediately published upon approval. Do you want to continue?') }}",
                                        icon: "info",
                                        showCancelButton: true,
                                        confirmButtonText: "Yes",
                                        cancelButtonText: "Cancel",
                                        allowOutsideClick: false,
                                        focusConfirm: false
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $('#create_stock').val(1)
                                        }

                                        document.getElementById('choice_form').submit();
                                    });
                                });
                            }
                        } else {
                            document.getElementById('choice_form').submit();
                        }
                    } else {
                        document.getElementById('choice_form').submit();
                    }
                } else {
                    Swal.fire({
                        title: '{{ translate('Cancelled') }}',
                        text: '{{ translate('Please select a category without subcategories.') }}',
                        icon: 'error',
                        scrollbarPadding: false,
                        backdrop: false,
                    })
                }
            });
        });
    </script>
@endsection
