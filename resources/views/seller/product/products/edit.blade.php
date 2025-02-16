@extends('seller.layouts.app')

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

    .table th {
        font-size: 12px !important;
    }

    .button-container {
        position: fixed;
        top: 1%;
        right: 25%;
        z-index: 97;
    }

    .swal2-icon .swal2-icon-content {
        display: flex;
        align-items: center;
        font-size: 0.75em !important;
    }

    .swal2-confirm {
        margin-left: 88px;
    }

    .preview-button {
        background-color: #cb774b !important;
        /* Green background */
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

    @media screen and (min-width: 1280px) {
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
@if (app()->getLocale() == 'ae')
    <style>
        .multi-select-menuitem input {
            position: relative !important;
            margin-left: 0 !important;
        }
    </style>
@endif
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Edit Product') }}</h1>
            </div>
        </div>
    </div>

    <!-- Error Meassages -->
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

    <form action="{{ route('seller.products.update', $product->id) }}" method="POST"
        enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-12">
                @if ($product->rejection_reason != null)
                    <div class="alert alert-danger" role="alert">
                        {{ $product->rejection_reason }}
                    </div>
                @endif
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="last_version" id="last_version" value="0">
                {{-- Bloc Product Information --}}
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
                                        <input id="nameProduct" type="text" required class="form-control" name="name"
                                            value="{{ $product->name }}" placeholder="{{ translate('Product Name') }}">
                                    </div>
                                </div>
                                <div class="form-group row" id="brand">
                                    <label class="col-md-3 col-from-label">{{ translate('Brand') }}</label>
                                    <div class="col-md-8">
                                        <select required class="form-control aiz-selectpicker" name="brand_id"
                                            id="brand_id" data-live-search="true">
                                            <option value="">{{ translate('Select Brand') }}</option>
                                            @foreach (\App\Models\Brand::all() as $brand)
                                                <option value="{{ $brand->id }}" @selected($product->brand_id == $brand->id)>
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
                                            value="{{ $product->unit }}"
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
                                            value="{{ $product->unit_price }}"
                                            placeholder="{{ __('Unit of Sale Price') }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Country of origin') }}</label>
                                    <div class="col-md-8">
                                        <div class="form-item">
                                            <input id="country_selector" type="text">
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
                                            value="{{ $product->manufacturer }}"
                                            placeholder="{{ translate('Manufacturer') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Tags') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control aiz-tag-input"
                                            value="{{ $product->tags }}" id="tags" name="tags[]"
                                            placeholder="{{ translate('Type and hit enter to add a tag') }}">
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
                                        <textarea class="form-control" name="short_description" id="short_description">{{ $product->short_description }}</textarea>
                                        <div id="charCountShortDescription">{{ translate('Remaining characters: 512') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Show Stock Quantity') }}</label>
                                    <div class="col-md-8">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="stock_visibility_state" value="1"
                                                @if ($product->stock_visibility_state == 'quantity') checked="checked" @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ translate('Published') }}</label>
                                    <div class="col-md-8">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="published" value="1"
                                                @if ($product->published == 1) checked="checked" @endif>
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
                                                <input type="checkbox" name="refundable"
                                                    @if ($product->refundable == 1) checked @endif>
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
                                <input type="file" name="main_photos[]" class="form-control" id="photoUploadcustom"
                                    onchange="previewImages(event)" accept=".jpeg, .jpg, .png" multiple />
                                <div class="row mt-3" id="image-preview">
                                    @if (count($product->getImagesProduct()) > 0)
                                        @foreach ($product->getImagesProduct() as $image)
                                            <div class="col-2 container-img">
                                                <img src="{{ asset('/public/' . $image->path) }}" height="120"
                                                    width="120" />
                                                <i class="fa-regular fa-circle-xmark fa-fw fa-lg icon-delete-image"
                                                    title="delete this image" data-image_id="{{ $image->id }}"></i>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="signinSrEmail">{{ translate('Thumbnail Image') }}
                                <small>(400x400)</small></label>
                            <div class="col-md-8" id="bloc_thumbnails">
                                <input type="file" name="photosThumbnail[]" class="form-control"
                                    id="photoUploadThumbnailSeconde" onchange="previewImagesThumbnail(event)"
                                    accept=".jpeg, .jpg, .png" multiple />
                                <small
                                    style="display: block; margin-top: 12px;">{{ translate('Thumbnail images will be generated automatically from gallery images if not specified') }}</small>
                                <div class="row mt-3" id="image-preview-Thumbnail">
                                    @if (count($product->getThumbnailsProduct()) > 0)
                                        @foreach ($product->getThumbnailsProduct() as $image)
                                            <div class="col-2 container-img">
                                                <img src="{{ asset('/public/' . $image->path) }}" height="120"
                                                    width="120" />
                                                <i class="fa-regular fa-circle-xmark fa-fw fa-lg icon-delete-image"
                                                    title="delete this image" data-image_id="{{ $image->id }}"></i>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
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
                                            <option value="youtube" @selected($product->video_provider == 'youtube')>
                                                {{ translate('Youtube') }}</option>
                                            <option value="vimeo" @selected($product->video_provider == 'vimeo')>{{ translate('Vimeo') }}
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
                                            value="{{ $product->video_link }}"
                                            placeholder="{{ translate('Video Link') }}">
                                        <small
                                            class="text-muted">{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Pricing configuration --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Default Pricing Configuration') }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="vat" @if ($vat_user->vat_registered == 1) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <hr> --}}
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
                                        @if (count($product->getPricingConfiguration()) > 0)
                                            @foreach ($product->getPricingConfiguration() as $key => $pricing)
                                                <tr>
                                                    <td><input type="number" min="1" name="from[]"
                                                            class="form-control min-qty"
                                                            @if ($key == 0) id="min-qty-parent" @endif
                                                            value="{{ $pricing->from }}"></td>
                                                    <td><input type="number" min="1" name="to[]"
                                                            class="form-control max-qty"
                                                            @if ($key == 0) id="max-qty-parent" @endif
                                                            value="{{ $pricing->to }}"></td>
                                                    <td><input type="number" step="0.01" min="1"
                                                            name="unit_price[]" class="form-control unit-price-variant"
                                                            @if ($key == 0) id="unit-price-parent" @endif
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
                                                    <td style="width: 22% !important;">
                                                        <div class="col-md-9 input-group">
                                                            <input type="number" min="0" step="0.01"
                                                                class="form-control discount_percentage"
                                                                value="{{ $pricing->discount_percentage }}"
                                                                @if ($pricing->discount_type != 'percent') readonly @endif
                                                                name="discount_percentage[]">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <i class="las la-plus btn-add-pricing"
                                                            style="margin-left: 5px; margin-top: 17px;"
                                                            title="Add another ligne"></i>
                                                        @if ($key != 0)
                                                            <i class="las la-trash delete_pricing_canfiguration"
                                                                data-pricing_id="{{ $pricing->id }}"
                                                                style="margin-left: 5px; margin-top: 17px;"
                                                                title="Delete this ligne"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="number" name="from[]" class="form-control min-qty"
                                                        id="min-qty-parent" placeholder="{{ translate('From QTY') }}">
                                                </td>
                                                <td><input type="number" name="to[]" class="form-control max-qty"
                                                        id="max-qty-parent" placeholder="{{ translate('To QTY') }}"></td>
                                                <td><input type="number" name="unit_price[]" step="0.01"
                                                        min="1" placeholder="{{ translate('Unit Price') }}"
                                                        class="form-control unit-price-variant" id="unit-price-parent">
                                                </td>
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
                                                        name="discount_amount[]" placeholder="{{ translate('Amount') }}">
                                                </td>
                                                <td style="width: 22% !important;">
                                                    <div class="col-md-9 input-group">
                                                        <input type="number" class="form-control discount_percentage"
                                                            name="discount_percentage[]"
                                                            placeholder="{{ translate('Percentage') }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <i class="las la-plus btn-add-pricing"
                                                        style="margin-left: 5px; margin-top: 17px;"
                                                        title="{{ translate('Add another ligne') }}"></i>
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                            <div class="bloc-default-shipping-style" style="margin-top: 22px;">
                                <h6>{{ translate('Default Sample Pricing Configuration') }}</h6>
                                <hr>
                                <div id="sample_parent">
                                    <div class="row mb-3">
                                        <label class="col-md-2 col-from-label">{{ translate('Sample Available?') }}</label>
                                        <div class="col-md-10">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input value="{{ $product->sample_available }}" @if($product->sample_available == 1) checked @endif name="sample_available" type="checkbox" class="sample-available" />
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mb-3" id="sample-description-wrapper" @if($product->sample_available == 0) style="display: none;" @endif>
                                        <label
                                            class="col-md-2 col-from-label">{{ translate('Sample description') }}</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control sample_description_parent" id="sample_description_parent" name="sample_description">{{ $product->sample_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3" id="sample-price-wrapper" @if($product->sample_available == 0) style="display: none;" @endif>
                                        <label class="col-md-2 col-from-label">{{ translate('Sample price') }}</label>
                                        <div class="col-md-10">
                                            <input type="numbre" min="1" step="0.01"
                                                class="form-control sample_price_parent" id="sample_price_parent"
                                                name="sample_price" value="{{ $product->sample_price }}">
                                        </div>
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
                                <h6>{{ translate('MawadOnline 3rd Party Shipping') }}</h6>
                                <hr>
                                <div class="row mb-3">
                                    <label
                                        class="col-md-4 col-from-label">{{ translate('Activate MawadOnline 3rd Party Shipping') }}</label>
                                    <div class="col-md-8">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="1" type="checkbox" id="third_party_activate"
                                                name="activate_third_party"
                                                @if ($product->activate_third_party == 1) checked @endif>
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
                                                        id="length" step="0.1"
                                                        @if ($product->activate_third_party == 1) value="{{ $product->length }}" @else readonly @endif>
                                                </td>
                                                <td><input type="number" name="width" class="form-control"
                                                        id="width" step="0.1"
                                                        @if ($product->activate_third_party == 1) value="{{ $product->width }}" @else readonly @endif>
                                                </td>
                                                <td><input type="number" name="height" class="form-control"
                                                        id="height" step="0.1"
                                                        @if ($product->activate_third_party == 1) value="{{ $product->height }}" @else readonly @endif>
                                                </td>
                                                <td><input type="number" name="weight" class="form-control"
                                                        id="weight" step="0.1"
                                                        @if ($product->activate_third_party == 1) value="{{ $product->weight }}" @else readonly @endif>
                                                </td>
                                                <td>
                                                    <select class="form-control calculate" id="weight_unit"
                                                        name="unit_weight"
                                                        @if ($product->activate_third_party != 1) disabled @endif>
                                                        <option value="kilograms"
                                                            @if ($product->unit_weight == 'kilograms') {{ 'selected' }} @endif>
                                                            {{ translate('Kilograms') }}</option>
                                                        <option value="pounds"
                                                            @if ($product->unit_weight == 'pounds') {{ 'selected' }} @endif>
                                                            {{ translate('Pounds') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control calculate" id="breakable"
                                                        name="breakable"
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
                                    <div class="col-12" style="padding: 0">
                                        <small
                                            style="display: block !important">{{ translate('Fill all required fields for shippers to confirm delivery ability.') }}</small>
                                    </div>
                                    <div id="result_calculate_third_party">
                                        @if ($product->activate_third_party == 1)
                                            @if ($chargeable_weight > 30)
                                                <span style="color: red"> {{ translate('Chargeable Weight = ') }}
                                                    {{ number_format($chargeable_weight, 2) }},
                                                    {{ translate('then not accepted by our shipper') }} </span>
                                            @else
                                                <span style="color: green"> {{ translate('Chargeable Weight = ') }}
                                                    {{ number_format($chargeable_weight, 2) }},
                                                    {{ translate('then accepted by our shipper') }} </span>
                                            @endif
                                        @endif
                                    </div>
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
                                                <th>{{ translate('Est. Order Prep. Days') }}</th>
                                                <th>{{ translate('Est. Shipping Days') }}</th>
                                                <th style="width: 164px;">{{ translate('Paid by') }}</th>
                                                <th>{{ translate('Shipping Charge Type') }}</th>
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
                                                            @php $shippers = explode(",", $shipping->shipper); @endphp
                                                            <select class="form-control shipper"
                                                                name="shipper[]">
                                                                <option>{{ __("Select Shipper") }}</option>
                                                                <option value="vendor"
                                                                    @if ($shipping->shipper == 'vendor') selected @endif>
                                                                    {{ translate('vendor') }}</option>
                                                                <option value="third_party"
                                                                    @if ($shipping->shipper == 'third_party') selected @endif>
                                                                    {{ translate('MawadOnline 3rd Party Shippers') }}
                                                                </option>
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
                                                        <td><input @if($shipping->shipper === "third_party") readonly @endif type="number" class="form-control estimated_shipping"
                                                                value="{{ $shipping->estimated_shipping }}"
                                                                name="estimated_shipping[]"></td>
                                                        <td>
                                                            <select class="@if($shipping->shipper === "third_party") disabled-look__clz @endif form-control paid" name="paid[]">
                                                                <option>
                                                                    {{ translate('Choose paid by') }}</option>
                                                                <option value="vendor"
                                                                    @if ($shipping->paid == 'vendor') selected @endif>
                                                                    {{ translate('vendor') }}</option>
                                                                <option value="buyer"
                                                                    @if ($shipping->paid == 'buyer') selected @endif>
                                                                    {{ translate('Buyer') }}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="@if($shipping->shipper === "third_party") disabled-look__clz @endif form-control shipping_charge"
                                                                name="shipping_charge[]">
                                                                <option>
                                                                    {{ translate('Choose shipping charge') }}</option>
                                                                <option value="flat"
                                                                    @if ($shipping->shipping_charge == 'flat') selected @endif>
                                                                    {{ translate('Flat-rate regardless of quantity') }}
                                                                </option>
                                                                <option value="charging"
                                                                    @if ($shipping->shipping_charge == 'charging') selected @endif>
                                                                    {{ translate('Charging per Unit of Sale') }}</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control flat_rate_shipping"
                                                                value="{{ $shipping->flat_rate_shipping }}"
                                                                name="flat_rate_shipping[]" @if($shipping->shipper === "third_party") readonly @endif /></td>
                                                        <td><input type="number"
                                                                class="form-control charge_per_unit_shipping"
                                                                value="{{ $shipping->charge_per_unit_shipping }}"
                                                                name="charge_per_unit_shipping[]" @if($shipping->shipper === "third_party") readonly @endif /></td>
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
                                                            <option>{{ __("Select shipper") }}</option>
                                                            <option value="vendor" @selected(old('shipper') == 'vendor')>
                                                                {{ translate('vendor') }}</option>
                                                            <option value="third_party" @selected(old('shipper') == 'third_party')>
                                                                {{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                                        </select>
                                                    </td>
                                                    <td><input readonly type="number" name="from_shipping[]"
                                                            class="form-control min-qty-shipping" id=""
                                                            placeholder="{{ translate('From QTY') }}"></td>
                                                    <td><input readonly type="number" name="to_shipping[]"
                                                            class="form-control max-qty-shipping" id=""
                                                            placeholder="{{ translate('To QTY') }}"></td>
                                                    <td><input readonly type="number" class="form-control estimated_order"
                                                            name="estimated_order[]"
                                                            placeholder="{{ translate('Days') }}"></td>
                                                    <td><input readonly type="number" class="form-control estimated_shipping"
                                                            name="estimated_shipping[]"
                                                            placeholder="{{ translate('Days') }}"></td>
                                                    <td>
                                                        <select class="disabled-look__clz form-control paid" name="paid[]">
                                                            <option>{{ translate('Choose option') }}</option>
                                                            <option value="vendor" @selected(old('shipper') == 'vendor')>
                                                                {{ translate('vendor') }}</option>
                                                            <option value="buyer" @selected(old('shipper') == 'buyer')>
                                                                {{ translate('Buyer') }}</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="disabled-look__clz form-control shipping_charge"
                                                            name="shipping_charge[]">
                                                            <option value="" selected>
                                                                {{ translate('Choose shipping charge') }}</option>
                                                            <option value="flat" @selected(old('shipping_charge') == 'flat')>
                                                                {{ translate('Flat-rate regardless of quantity') }}
                                                            </option>
                                                            <option value="charging" @selected(old('shipping_charge') == 'charging')>
                                                                {{ translate('Charging per Unit of Sale') }}</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="form-control flat_rate_shipping"
                                                            name="flat_rate_shipping[]"
                                                            placeholder="{{ translate('Flat rate amount') }}" readonly />
                                                    </td>
                                                    <td><input type="number"
                                                            class="form-control charge_per_unit_shipping"
                                                            name="charge_per_unit_shipping[]"
                                                            placeholder="{{ translate('Charge unit') }}" readonly /></td>
                                                    <td>
                                                        <i class="las la-plus btn-add-shipping"
                                                            style="margin-left: 5px; margin-top: 17px;"
                                                            title="{{ __("Add another ligne") }}"></i>
                                                    </td>
                                                </tr>
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
                                                name="activate_third_party_sample"
                                                @if ($product->activate_third_party_sample == 1) checked @endif>
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
                                                        id="length_sample" step="0.1"
                                                        @if ($product->activate_third_party_sample == 1) value="{{ $product->length_sample }}" @else readonly @endif>
                                                </td>
                                                <td><input type="number" name="width_sample" class="form-control"
                                                        id="width_sample" step="0.1"
                                                        @if ($product->activate_third_party_sample == 1) value="{{ $product->width_sample }}" @else readonly @endif>
                                                </td>
                                                <td><input type="number" name="height_sample" class="form-control"
                                                        id="height_sample" step="0.1"
                                                        @if ($product->activate_third_party_sample == 1) value="{{ $product->height_sample }}" @else readonly @endif>
                                                </td>
                                                <td><input type="number" name="package_weight_sample"
                                                        class="form-control" id="package_weight_sample" step="0.1"
                                                        @if ($product->activate_third_party_sample == 1) value="{{ $product->package_weight_sample }}" @else readonly @endif>
                                                </td>
                                                <td>
                                                    <select class="form-control calculate" id="weight_unit_sample"
                                                        name="weight_unit_sample"
                                                        @if ($product->activate_third_party_sample != 1) disabled @endif>
                                                        <option value="kilograms"
                                                            @if ($product->weight_unit_sample == 'kilograms') {{ 'selected' }} @endif>
                                                            {{ translate('Kilograms') }}</option>
                                                        <option value="pounds"
                                                            @if ($product->weight_unit_sample == 'pounds') {{ 'selected' }} @endif>
                                                            {{ translate('Pounds') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control calculate" id="breakable_sample"
                                                        name="breakable_sample"
                                                        @if ($product->activate_third_party_sample != 1) disabled @endif>
                                                        <option value=""
                                                            @if ($product->breakable_sample == null) {{ 'selected' }} @endif>
                                                        </option>
                                                        <option value="yes"
                                                            @if ($product->breakable_sample == 'yes') {{ 'selected' }} @endif>
                                                            {{ translate('Yes') }}</option>
                                                        <option value="no"
                                                            @if ($product->breakable_sample == 'no') {{ 'selected' }} @endif>
                                                            {{ translate('No') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control calculate" id="unit_third_party_sample"
                                                        name="unit_third_party_sample"
                                                        @if ($product->activate_third_party_sample != 1) disabled @endif>
                                                        <option value="celsius"
                                                            @if ($product->unit_third_party_sample == 'celsius') {{ 'selected' }} @endif>
                                                            {{ translate('Celsius') }}</option>
                                                        <option value="kelvin"
                                                            @if ($product->unit_third_party_sample == 'kelvin') {{ 'selected' }} @endif>
                                                            {{ translate('Kelvin') }}</option>
                                                        <option value="fahrenheit"
                                                            @if ($product->unit_third_party_sample == 'fahrenheit') {{ 'selected' }} @endif>
                                                            {{ translate('Fahrenheit') }}</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control"
                                                        name="min_third_party_sample" id="min_third_party_sample"
                                                        step="0.1"
                                                        @if ($product->activate_third_party_sample == 1) value="{{ $product->min_third_party_sample }}" @else readonly @endif>
                                                </td>
                                                <td><input type="number" class="form-control"
                                                        name="max_third_party_sample" id="max_third_party_sample"
                                                        step="0.1"
                                                        @if ($product->activate_third_party_sample == 1) value="{{ $product->max_third_party_sample }}" @else readonly @endif>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small>{{ __("Fill all required fields for shippers to confirm delivery ability") }}.</small>
                                <div id="result_calculate_third_party_sample">
                                    @if ($product->activate_third_party_sample == 1)
                                        @if ($chargeable_weight_sample > 30)
                                            <span style="color: red"> {{ translate('Chargeable Weight = ') }}
                                                {{ number_format($chargeable_weight_sample, 2) }},
                                                {{ translate('then not accepted by our shipper') }} </span>
                                        @else
                                            <span style="color: green"> {{ translate('Chargeable Weight = ') }}
                                                {{ number_format($chargeable_weight_sample, 2) }},
                                                {{ translate('then accepted by our shipper') }} </span>
                                        @endif
                                    @endif
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
                                                <th>{{ translate('Shipping amount') }}</th>
                                                <th>{{ translate('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bloc_sample_configuration">
                                            @php $shippers_sample = $product->getSampleShipping(); @endphp
                                            @if($shippers_sample->count() > 0)
                                                @foreach($shippers_sample as $key => $shipper)
                                                    <tr>
                                                        <td>
                                                            <select class="form-control shipper_sample" name="shipper_sample[]"
                                                                id="shipper_sample_parent">
                                                                <option>{{ __("Select Shipper") }}</option>
                                                                <option value="vendor"
                                                                    @if ($shipper->shipper === "vendor") selected @endif>
                                                                    {{ translate('vendor') }}</option>
                                                                <option value="third_party"
                                                                    @if ($shipper->shipper === "third_party") selected @endif>
                                                                    {{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control estimated_sample"
                                                                id="estimated_sample_parent" name="estimated_sample[]"
                                                                @if ($shipper->estimated_order != null) value="{{ $shipper->estimated_order }}" @endif>
                                                        </td>
                                                        <td><input type="number"
                                                                @if ($shipper->shipper !== "vendor") readonly @endif
                                                                id="estimated_shipping_sample_parent"
                                                                class="form-control estimated_shipping_sample"
                                                                name="estimated_shipping_sample[]"
                                                                @if ($shipper->estimated_shipping != null) value="{{ $shipper->estimated_shipping }}" @endif>
                                                        </td>
                                                        <td>
                                                            <select style="width: max-content;" class="form-control paid_sample" name="paid_sample[]"
                                                                id="paid_sample_parent"
                                                                @if ($shipper->shipper !== "vendor") readonly @endif>
                                                                <option selected>{{ translate('Choose paid by') }}
                                                                </option>
                                                                <option
                                                                    value="vendor" @if ($shipper->paid == 'vendor') selected @endif>
                                                                    {{ translate('vendor') }}</option>
                                                                <option value="buyer"
                                                                    @if ($shipper->paid == 'buyer') selected @endif>
                                                                    {{ translate('Buyer') }}</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="number"
                                                                @if ($shipper->shipper !== "vendor") readonly @endif
                                                                class="form-control shipping_amount" name="shipping_amount[]"
                                                                @if ($shipper->flat_rate_shipping != null) value="{{ $shipper->flat_rate_shipping }}" @endif>
                                                        </td>
                                                        <td>
                                                            <i class="las la-plus btn-add-sample-shipping"
                                                                style="margin-left: 5px; margin-top: 17px;"
                                                                title="{{ __('Add another ligne') }}"></i>
                                                            @if ($key != 0)
                                                                <i class="las la-trash delete_shipping_canfiguration"
                                                                    data-id="{{ $shipper->id }}"
                                                                    style="margin-left: 5px; margin-top: 17px;"
                                                                    title="{{ __("Delete this ligne") }}"></i>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
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
                                                    <td><input type="number" class="form-control estimated_sample"
                                                            id="estimated_sample_parent" name="estimated_sample[]"
                                                            value="{{ old('estimated_sample') }}" /></td>
                                                    <td><input type="number"
                                                            class="form-control estimated_shipping_sample"
                                                            id="estimated_shipping_sample_parent"
                                                            name="estimated_shipping_sample[]"
                                                            value="{{ old('estimated_shipping_sample') }}" /></td>
                                                    <td>
                                                        <select class="form-control paid_sample" name="paid_sample[]"
                                                            id="paid_sample_parent" style="width: max-content!important;">
                                                            <option value="" selected>{{ translate('Choose paid by') }}
                                                            </option>
                                                            <option value="vendor" @selected(old('paid_sample') == 'vendor')>
                                                                {{ translate('vendor') }}</option>
                                                            <option value="buyer" @selected(old('paid_sample') == 'buyer')>
                                                                {{ translate('Buyer') }}</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="form-control shipping_amount"
                                                            name="shipping_amount[]" value="{{ old('shipping_amount') }}"
                                                            step="0.1" /></td>
                                                    <td>
                                                        <i class="las la-plus btn-add-sample-shipping"
                                                            style="margin-left: 5px; margin-top: 17px;"
                                                            title="{{ translate('Add another ligne') }}"></i>
                                                    </td>
                                                </tr>
                                            @endif
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
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Category') }}</h5>
                        <h6 class="float-right fs-13 mb-0">
                            {{-- <span id="message-category"><span>
                            {{ translate('Select Main') }}
                            <span class="position-relative main-category-info-icon">
                                <i class="las la-question-circle fs-18 text-info"></i>
                                <span class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate('This will be used for commission based calculations and homepage category wise product Show.') }}</span>
                            </span> --}}

                        </h6>
                    </div>
                    <input type="hidden" id="selected_parent_id" name="parent_id"
                        @if ($categorie != null) value="{{ $categorie->id }}" @else value="" @endif>
                    <input type="hidden" id="check_selected_parent_id"
                        @if ($categorie != null) value="1" @else value="-1" @endif>

                    <div class="card-body">

                        <div class="tree_main">

                            <input type="text"
                                @if ($categorie != null) value="{{ $categorie->name }}" @else value="" @endif
                                id="search_input" class="form-control" placeholder="Search">
                            <small
                                style="color: red">{{ translate('To select a different category, please clear the search field, However, you must choose other attributes to modify your variants') }}</small>
                            <div class="h-300px overflow-auto c-scrollbar-light">

                                <div id="jstree"></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc variant & attributes --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Create variants') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row gutters-5">
                            <label class="col-md-2 col-from-label">{{ translate('Activate variant option') }}</label>
                            <div class="col-md-10">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="activate_attributes"
                                        @if (count($product->getChildrenProducts()) > 0 && count($attributes) > 0) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row gutters-5">
                            <label class="col-md-2 col-from-label">{{ translate('Attributes') }}</label>
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
                                        <label class="custom-file-label" for="photos_variant">{{ __("Choose files") }}</label>
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
                                <div id="bloc_pricing_configuration_variant" class="row bloc_pricing_configuration_variant">
                                </div>
                            </div>
                            <div class="row mb-3" style="display: none">
                                <label class="col-md-2 col-from-label">{{ translate('Use default Shipping') }}</label>
                                <div class="col-md-10">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" class="variant-shipping" checked>
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
                                            disabled>
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
                                            <input type="text" class="form-control sample_price">
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
                                            disabled>
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
                                @if (count($variants_attributes_ids_attributes) > 0)
                                    @include('seller.product.products.attributes', [
                                        'attributes' => $attributes,
                                        'variants_attributes_ids_attributes' => $variants_attributes_ids_attributes,
                                    ])
                                @endif
                            </div>
                        </div>
                        <div class="row div-btn">
                            <button type="button" class="btn btn-primary"
                                id="btn-create-variant">{{ translate('Create variant') }}</button>
                        </div>
                        <hr>
                        <div id="bloc_variants_created">
                            @if (count($product->getChildrenProductsDesc()) > 0)
                                @foreach ($product->getChildrenProductsDesc() as $key => $children)
                                    <div data-id="{{ $children->id }}">
                                        <h3 class="mb-3">{{ translate('Variant Information') }} {{ $key + 1 }}
                                        </h3>
                                        <i class="fa-regular fa-circle-xmark fa-lx delete-variant"
                                            data-id={{ $children->id }}
                                            @if (app()->getLocale() == 'ae') style="font-size: 16px; float: left; margin-top: -35px;" @else style="font-size: 16px; float: right; margin-top: -35px;" @endif
                                            title="delete this variant"></i>
                                        <hr>
                                        <div class="row mb-3">
                                            <label class="col-md-2 col-from-label">{{ translate('Variant SKU') }}</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control sku" id="sku"
                                                    name="variant[sku][{{ $children->id }}]"
                                                    value="{{ $children->sku }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label
                                                class="col-md-2 col-from-label">{{ translate('Variant Photos') }}</label>
                                            <div class="col-md-10">
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input photos_variant"
                                                        data-count = "{{ count($children->getImagesProduct()) }}"
                                                        name="variant[photo][{{ $children->id }}][]"
                                                        id="photos_variant{{ $key }}"
                                                        accept=".jpeg, .jpg, .png" multiple>
                                                    <label class="custom-file-label"
                                                        for="photos_variant{{ $key }}">{{ translate('Choose files') }}</label>
                                                </div>
                                                @if (count($children->getImagesProduct()) > 0)
                                                    <div class="row mt-3 uploaded_images">
                                                        @foreach ($children->getImagesProduct() as $image)
                                                            <div class="col-2 container-img old_image">
                                                                <img src="{{ asset('/public/' . $image->path) }}"
                                                                    height="120" width="120" />
                                                                <i class="fa-regular fa-circle-xmark fa-fw fa-lg icon-delete-image"
                                                                    title="delete this image"
                                                                    data-image_id="{{ $image->id }}"></i>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label
                                                class="col-md-2 col-from-label">{{ translate('Use parent unit of sale price') }}</label>
                                            <div class="col-md-10">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input value="1" type="checkbox"
                                                        name="variant-pricing-{{ $children->id }}"
                                                        data-old_variant="{{ $children->id }}"
                                                        data-unit_price="{{ $children->unit_price }}"
                                                        class="variant-pricing"
                                                        @if ($children->unit_price === 0) checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                            @if ($children->unit_price !== 0)
                                                <div class="mx-0 row bloc_pricing_configuration_variant">
                                                    <label class="col-md-2 col-from-label">
                                                        {{ __('Unit of Sale Price') }}
                                                        <small>({{ __('VAT Exclusive') }})</small>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input type="number" class="form-control" name="variant[unit_sale_price][{{ $children->id }}]"
                                                            value="{{ $children->unit_price }}"
                                                            placeholder="{{ __('Unit of Sale Price') }}" />
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row mb-3">
                                            <label
                                                class="col-md-2 col-from-label">{{ translate('Use default Shipping') }}</label>
                                            <div class="col-md-10">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input value="1" type="checkbox" class="variant-shipping"
                                                        data-id_variant="{{ $children->id }}"
                                                        @if (count($children->getShipping()) == 0) checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>

                                            <div class="col-12 mt-3" id="bloc_default_shipping">
                                                <table class="table" id="table_shipping_configuration"
                                                    class="bloc_shipping_configuration_variant">
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
                                                        @if($children->getShipping()->count() > 0)
                                                            @foreach ($children->getShipping() as $key => $shipping)
                                                                <tr>
                                                                    <td>
                                                                        <select class="form-control shipper"
                                                                            name="variant[shipper][{{ $children->id }}][]">

                                                                            <option>{{ __("Select Shipper") }}</option>
                                                                            <option value="vendor"
                                                                                @if ($shipping->shipper === 'vendor') selected @endif>
                                                                                {{ translate('vendor') }}</option>
                                                                            <option value="third_party"
                                                                                @if ($shipping->shipper === 'third_party') selected @endif>
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
                                                                            @if ($shipping->shipper === "third_party") readonly @endif
                                                                            name="variant[estimated_shipping][{{ $children->id }}][]">
                                                                    </td>
                                                                    <td>
                                                                        <select class="@if($shipping->shipper === "third_party") disabled-look__clz @endif form-control paid"
                                                                            name="variant[paid][{{ $children->id }}][]">
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
                                                                        <select class="@if($shipping->shipper === "third_party") disabled-look__clz @endif form-control shipping_charge"
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
                                                                            @if($shipping->shipper === "third_party" || $shipping->charge_per_unit_shipping !== null) readonly @endif></td>
                                                                    <td><input type="number"
                                                                            class="form-control charge_per_unit_shipping"
                                                                            value="{{ $shipping->charge_per_unit_shipping }}"
                                                                            name="variant[charge_per_unit_shipping][{{ $children->id }}][]"
                                                                            @if($shipping->shipper === "third_party" || $shipping->flat_rate_shipping !== null) readonly @endif></td>
                                                                    <td>
                                                                        <i class="las la-plus btn-add-shipping"
                                                                            data-id_variant="{{ $children->id }}"
                                                                            style="margin-left: 5px; margin-top: 17px;"
                                                                            title="{{ __("Add another ligne") }}"></i>
                                                                        @if ($key != 0)
                                                                            <i class="las la-trash delete_shipping_canfiguration"
                                                                                data-id="{{ $shipping->id }}"
                                                                                data-id_variant="{{ $children->id }}"
                                                                                style="margin-left: 5px; margin-top: 17px;"
                                                                                title="{{ __("Delete this ligne") }}"></i>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <select class="form-control shipper"
                                                                        name="variant[shipper][{{ $children->id }}][{{ $key }}]">

                                                                        <option>{{ __("Select Shipper") }}</option>
                                                                        <option value="vendor">
                                                                            {{ translate('vendor') }}</option>
                                                                        <option value="third_party">
                                                                            {{ translate('MawadOnline 3rd Party Shippers') }}
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td><input readonly type="number"
                                                                        name="variant[from_shipping][{{ $children->id }}][]"
                                                                        class="form-control min-qty-shipping"
                                                                        id=""></td>
                                                                <td><input readonly type="number"
                                                                        name="variant[to_shipping][{{ $children->id }}][]"
                                                                        class="form-control max-qty-shipping"
                                                                        id=""></td>
                                                                <td><input readonly type="number"
                                                                        class="form-control estimated_order"
                                                                        name="variant[estimated_order][{{ $children->id }}][]">
                                                                </td>
                                                                <td><input readonly type="number"
                                                                        class="form-control estimated_shipping"
                                                                        name="variant[estimated_shipping][{{ $children->id }}][]">
                                                                </td>
                                                                <td>
                                                                    <select class="disabled-look__clz form-control paid"
                                                                        name="variant[paid][{{ $children->id }}][]">
                                                                        <option value="" selected>
                                                                            {{ translate('Choose option') }}</option>
                                                                        <option value="vendor">
                                                                            {{ translate('vendor') }}</option>
                                                                        <option value="buyer">
                                                                            {{ translate('Buyer') }}</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="disabled-look__clz form-control shipping_charge"
                                                                        name="variant[shipping_charge][{{ $children->id }}][]">
                                                                        <option value="" selected>
                                                                            {{ translate('Choose shipping charge') }}
                                                                        </option>
                                                                        <option value="flat">
                                                                            {{ translate('Flat-rate regardless of quantity') }}
                                                                        </option>
                                                                        <option value="charging">
                                                                            {{ translate('Charging per Unit of Sale') }}
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td><input readonly type="number"
                                                                        class="form-control flat_rate_shipping"
                                                                        name="variant[flat_rate_shipping][{{ $children->id }}][]" /></td>
                                                                <td><input readonly type="number"
                                                                        class="form-control charge_per_unit_shipping"
                                                                        name="variant[charge_per_unit_shipping][{{ $children->id }}][]" /></td>
                                                                <td>
                                                                    <i class="las la-plus btn-add-shipping"
                                                                        data-id_variant="{{ $children->id }}"
                                                                        style="margin-left: 5px; margin-top: 17px;"
                                                                        title="{{ __("Add another ligne") }}"></i>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label
                                                class="col-md-2 col-from-label">{{ translate('Sample Available?') }}</label>
                                            <div class="col-md-10">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input value="1" type="checkbox"
                                                        class="variant-sample-available"
                                                        name="variant[sample_available][{{ $children->id }}]"
                                                        @if ($children->sample_price != null) checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label
                                                class="col-md-2 col-from-label">{{ translate('Use default sample pricing configuration') }}</label>
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
                                                    {{-- <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                                <input value="1" class="vat_sample" type="checkbox" name="variant[vat_sample][{{ $children->id }}]" @if ($vat_user->vat_registered == 1) checked @endif>
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div> --}}
                                                    <div class="row mb-3">
                                                        <label
                                                            class="col-md-2 col-from-label">{{ translate('Sample description') }}</label>
                                                        <div class="col-md-10">
                                                            <textarea class="form-control sample_description" name="variant[sample_description][{{ $children->id }}]">{{ $children->sample_description }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label
                                                            class="col-md-2 col-from-label">{{ translate('Sample price') }}</label>
                                                        <div class="col-md-10">
                                                            <input type="number" min="1" step="0.01"
                                                                class="form-control sample_price"
                                                                name="variant[sample_price][{{ $children->id }}]"
                                                                value="{{ $children->sample_price }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label
                                                class="col-md-2 col-from-label">{{ translate('Use default sample shipping') }}</label>
                                            <div class="col-md-10">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input value="1" type="checkbox"
                                                        data-id_old_variant= "{{ $children->id }}"
                                                        class="variant-sample-shipping"
                                                        name="variant[sample_shipping][{{ $children->id }}]"
                                                        @if ($children->shipper_sample == null) checked @endif
                                                        @if ($children->sample_available != 1) disabled @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div class="col-12 mt-3" id="bloc-sample-shipping">
                                                <table class="table bloc_sample_configuration_variant" id="table_sample_configuration">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ translate('Shipping-by') }}</th>
                                                            <th>{{ translate('Estimated Sample Preparation Days') }}
                                                            </th>
                                                            <th>{{ translate('Estimated Shipping Days') }}</th>
                                                            <th>{{ translate('Paid by') }}</th>
                                                            {{-- <th>{{translate('VAT')}}</th> --}}
                                                            <th>{{ translate('Shipping amount') }}</th>
                                                            <th>{{ translate('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="bloc_sample_configuration">
                                                        @foreach($children->getSampleShipping() as $key => $shipping)
                                                            <tr>
                                                                <td>
                                                                    <select class="form-control shipper_sample"
                                                                        name="variant[shipper_sample][{{ $children->id }}][]">
                                                                        <option>{{ __("Select shipper") }}</option>
                                                                        <option value="vendor"
                                                                            @if ($shipping->shipper === 'vendor') selected @endif>
                                                                            {{ translate('vendor') }}</option>
                                                                        <option value="third_party"
                                                                            @if ($shipping->shipper === 'third_party') selected @endif>
                                                                            {{ translate('MawadOnline 3rd Party Shippers') }}
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="number"
                                                                        class="form-control estimated_sample"
                                                                        name="variant[estimated_sample][{{ $children->id }}][]"
                                                                        @if ($shipping->estimated_order != null) value="{{ $shipping->estimated_order }}" @endif>
                                                                </td>
                                                                <td><input type="number"
                                                                        class="form-control estimated_shipping_sample"
                                                                        name="variant[estimated_shipping_sample][{{ $children->id }}][]"
                                                                        @if ($shipping->shipper === "third_party") readonly @endif
                                                                        @if ($shipping->estimated_shipping != null && $shipping->shipper !== "third_party") value="{{ $shipping->estimated_shipping }}" @endif>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control paid_sample"
                                                                        name="variant[paid_sample][{{ $children->id }}][]">
                                                                        <option value="" selected>
                                                                            {{ translate('Choose paid by') }}</option>
                                                                        <option
                                                                            value="vendor" @if ($shipping->paid == 'vendor') selected @endif>
                                                                            {{ translate('vendor') }}</option>
                                                                        <option value="buyer"
                                                                            @if ($shipping->paid == 'buyer' || $shipping->shipper === "third_party") selected @endif>
                                                                            {{ translate('Buyer') }}</option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="number"
                                                                        class="form-control shipping_amount"
                                                                        name="variant[shipping_amount][{{ $children->id }}][]"
                                                                        @if ($shipping->flat_rate_shipping != null && $shipping->shipper !== "third_party") value="{{ $shipping->flat_rate_shipping }}" @endif>
                                                                </td>
                                                                <td>
                                                                    <i class="las la-plus btn-add-sample-shipping"
                                                                        style="margin-left: 5px; margin-top: 17px;"
                                                                        title="{{ __('Add another ligne') }}"></i>
                                                                    @if ($key != 0)
                                                                        <i class="las la-trash delete_shipping_canfiguration"
                                                                            data-id="{{ $shipping->id }}"
                                                                            style="margin-left: 5px; margin-top: 17px;"
                                                                            title="{{ __("Delete this ligne") }}"></i>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                       @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-2 col-from-label">{{ translate('Published') }}</label>
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
                                            <label
                                                class="col-md-2 col-from-label">{{ translate('Low-Stock Warning') }}</label>
                                            <div class="col-md-10">
                                                <input type="number" min="1" class="form-control stock-warning"
                                                    id="low_stock_warning"
                                                    name="variant[low_stock_quantity][{{ $children->id }}]"
                                                    value="{{ $children->low_stock_quantity }}">
                                            </div>
                                        </div>
                                        <div id="bloc_attributes">
                                            @if (count($children->getIdsAttributesVariant()) > 0)
                                                @include('seller.product.products.variant_attributes', [
                                                    'attributes' => $attributes,
                                                    'variants_attributes_ids_attributes' => $children->getIdsAttributesVariant(),
                                                    'variants_attributes' => $children->getAttributesVariant(),
                                                    'children' => $children,
                                                ])
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('General Attributes') }}</h5>
                    </div>
                    <div class="card-body">
                        @if (count($product->getChildrenProductsDesc()) == 0)
                            <div id="sku_product_product" style="margin-left: 4px;">
                                <div class="row">
                                    <label class="col-md-2 col-from-label">{{ translate('SKU') }}</label>
                                    <div class="col-md-10 mb-3">
                                        <input type="text" name="product_sk" class="form-control"
                                            id="sku_product_parent" value="{{ $product->sku }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-2 col-from-label">{{ translate('Low-Stock Warning') }}</label>
                                    <div class="col-md-10 mb-3">
                                        <input type="number" min="0" name="quantite_stock_warning"
                                            class="form-control" value="{{ $product->low_stock_quantity }}">
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div id="general_attributes" style="margin-left: 11px;">
                                @if (count($general_attributes_ids_attributes) > 0)
                                    <div class="row">
                                        @include('seller.product.products.general_attributes', [
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Description and Specifications') }}</h5>
                    </div>
                    <div class="card-body" id="documents_bloc">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                            <div class="col-md-8">
                                <textarea class="aiz-text-editor" name="description" id="long_description">{{ $product->description }}</textarea>
                                <input type="hidden" id="hidden_value" value="">
                            </div>
                        </div>
                        <hr>
                        @if (count($product->getDocumentsProduct()) > 0)
                            @foreach ($product->getDocumentsProduct() as $key => $document)
                                <div class="row">
                                    <div class="col-5">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Document name') }}</label>
                                            <input type="text" class="form-control"
                                                name="old_document_names[{{ $document->id }}]"
                                                value="{{ $document->document_name }}">
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputEmail{{ $key }}">{{ translate('Document') }}</label>
                                            <div class="input-group padding-name-document">
                                                <div class="custom-file">
                                                    <input type="file" name="old_documents[{{ $document->id }}]"
                                                        accept=".pdf,.png,.jpg,.pln,.dwg,.dxf,.gsm,.stl,.rfa,.rvt,.ifc,.3ds,.max,.obj,.fbx,.skp,.rar,.zip"
                                                        class="custom-file-input file_input"
                                                        id="inputGroupFile{{ $key }}"
                                                        aria-describedby="inputGroupFileAddon04">
                                                    <label class="custom-file-label"
                                                        for="inputGroupFile{{ $key }}">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <i class="las la-plus add_document font-size-icon"
                                            style="margin-left: 5px; margin-top: 34px;"></i>
                                        @if ($key != 0)
                                            <i class="las la-trash trash_document font-size-icon"
                                                data-id_document="{{ $document->id }}"
                                                style="margin-left: 5px; margin-top: 34px;"></i>
                                        @endif
                                        <a href="{{ asset('/public/' . $document->path) }}" download
                                            title="{{ translate('Click to download') }}">
                                            <i class="las la-download font-size-icon"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
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
                                        <div class="input-group padding-name-document">
                                            <div class="custom-file">
                                                <input type="file" name="documents[]"
                                                    accept=".pdf,.png,.jpg,.pln,.dwg,.dxf,.gsm,.stl,.rfa,.rvt,.ifc,.3ds,.max,.obj,.fbx,.skp,.rar,.zip"
                                                    class="custom-file-input file_input" id="inputGroupFile04"
                                                    aria-describedby="inputGroupFileAddon04">
                                                <label class="custom-file-label" for="inputGroupFile04">Choose
                                                    file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <i class="las la-plus add_document font-size-icon"
                                        style="margin-left: 5px; margin-top: 34px;"></i>
                                    {{-- <i class="las la-trash trash_document font-size-icon" style="margin-left: 5px; margin-top: 34px;" ></i> --}}
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
                            <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="meta_title"
                                    value="{{ $product->meta_title }}" placeholder="{{ translate('Meta Title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                            <div class="col-md-8">
                                <textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
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
                    <button type="submit" class="btn btn-primary">{{ translate('Edit Product') }}</button>
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
    <script src="{{ static_asset('assets/js/countrySelect.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert2 JS -->
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
                //$('body .preview-container').empty();
            } else {
                let exceedingFiles = [];

                for (let i = 0; i < files.length; i++) {
                    const fileSizeInMB = files[i].size / (1024 * 1024);
                    if (fileSizeInMB > 2) {
                        exceedingFiles.push(files[i].name);
                    }
                }

                if (exceedingFiles.length > 0) {
                    // Swal.fire({
                    //     title: 'Cancelled',
                    //     text: 'Following files exceed 2MB limit: ' + exceedingFiles.join(', '),
                    //     icon: 'error',
                    //     scrollbarPadding: false,
                    //     backdrop:false,
                    // });

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
                    //$('body .preview-container').empty();
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

                            // Swal.fire({
                            //     title: 'Cancelled',
                            //     text: 'The dimensions of the images have exceeded both a width and height of 1280 pixels: ' + exceedingFiles.join(', '),
                            //     icon: 'error',
                            //     scrollbarPadding: false,
                            //     backdrop:false,
                            // });

                            var title = "{{ translate('Product Media') }}";
                            var message =
                                '<b> {{ translate('The dimensions of the images have exceeded both a width and height of 1280 pixels: ') }} </b> ' +
                                exceedingFilesDimension.join(', ');

                            $('#title-modal').text(title);
                            $('#text-modal').html(message);

                            $('#modal-info').modal('show');

                            $('#photoUploadcustom').val('');
                            $('body .preview-container').empty();
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
                        }
                    }, 500);
                }
            }
        }

        function previewImagesThumbnail(event) {
            var preview = document.getElementById('image-preview-Thumbnail');
            //preview.innerHTML = '';

            var old_files = $('#image-preview-thumbnail').find('.preview-container-thumbnail').length;

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
                var message = '{{ translate('Maximum 10 photos allowed.') }}';

                $('#title-modal').text(title);
                $('#text-modal').text(message);

                $('#modal-info').modal('show');

                $('#photoUploadThumbnailSeconde').val('');
                $('#image-preview-thumbnail').empty();

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
                //$('body .preview-container-thumbnail').empty();
            } else {
                let exceedingFiles = [];

                for (let i = 0; i < files.length; i++) {
                    const fileSizeInMB = files[i].size / (512 * 1024);
                    if (fileSizeInMB > 2) {
                        exceedingFiles.push(files[i].name);
                    }
                }

                if (exceedingFiles.length > 0) {
                    // Swal.fire({
                    //     title: 'Cancelled',
                    //     text: 'Following files exceed 512Ko limit: ' + exceedingFiles.join(', '),
                    //     icon: 'error',
                    //     scrollbarPadding: false,
                    //     backdrop:false,
                    // });

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
                    }, 500);
                    //$('body .preview-container-thumbnail').empty();
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
                            // Swal.fire({
                            //     title: 'Cancelled',
                            //     text: 'Please upload images with dimensions between 300px and 400px for both width and height: ' + exceedingFilesDimension.join(', '),
                            //     icon: 'error',
                            //     scrollbarPadding: false,
                            //     backdrop:false,
                            // });

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
                            //$('body .preview-container-thumbnail').empty();
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
    <script>
        var previewUrlBase = "{{ route('seller.product.preview', ['slug' => 'PLACEHOLDER']) }}";
    </script>
    <script type="text/javascript">
        function submitForm() {
            var input = $('#nameProduct');
            input.removeClass('error'); // Add error class
            if (!input.val().trim()) {
                input.addClass('error'); // Add error class
                // Show SweetAlert2 message
                // Swal.fire({
                //     icon: 'error',
                //     title: 'Oops...',
                //     text: 'Please enter a product name!'
                // });

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
            $('body').on('click', '.swal2-cancel', function() {
                $('body .swal2-container').hide();
                $("body").css({
                    "overflow-y": "scroll",
                    "z-index": "1" // Set a higher z-index for the body element
                });
                $(".aiz-topbar").css({
                    "width": "calc(100% - 266px)",
                    "left": "265px"
                });

                $("html, body").animate({
                    scrollTop: $(document).height()
                }, 300);
            })

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

            $('body #bloc_pricing_configuration_variant').hide();
            $('body #bloc_sample_pricing_configuration_variant').hide();
            $('body .btn-variant-pricing').hide();
            var numbers_variant = "{{ count($product->getChildrenProducts()) }}";
            numbers_variant = parseInt(numbers_variant);
            var today = moment().startOf("day");
            var initial_attributes = $('#attributes').val();
            Array.prototype.diff = function(a) {
                return this.filter(function(i) {
                    return a.indexOf(i) < 0;
                });
            };

            function getCategorySelected() {
                if (to) {
                    clearTimeout(to);
                }
                to = setTimeout(function() {
                    @if ($categorie != null)
                        var v = "{{ $categorie->name }}";
                    @else
                        var v = ""
                    @endif
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
                            $('#jstree').jstree(false, true)
                        .refresh(); // Refresh the tree to load initial data
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
            }

            getCategorySelected();

            //activate variant option
            $('body input[name="activate_attributes"]').on('change', function() {
                if (!$('body input[name="activate_attributes"]').is(':checked')) {
                    $('body #attributes').val('');
                    $('body #attributes').prop('disabled', true);
                    $('#variant_informations').hide();
                    $('#btn-create-variant').hide();
                    $('body #sku_product_product').show();
                    $('body #bloc_variants_created').empty();
                    AIZ.plugins.bootstrapSelect('refresh');
                } else {
                    var category_choosen = $("#selected_parent_id").val();
                    if (category_choosen != "1") {
                        if ($('#attributes option').length > 0) {
                            $('body #attributes').prop('disabled', false);
                            $('#variant_informations').show();
                            $('body #sku_product_product').hide();
                            $('body #product_sk').val(null);
                            $('body #stock_qty_warning').val(null);
                            $('body #bloc_variants_created').show();
                            $('#btn-create-variant').show();
                            $('.div-btn').show();
                            AIZ.plugins.bootstrapSelect('refresh');
                        } else {
                            $('body input[name="activate_attributes"]').prop('checked', false);
                            // Swal.fire({
                            //     title: 'Cancelled',
                            //     text: 'You are unable to enable the variant option because the selected category lacks any attributes.',
                            //     icon: 'error',
                            //     scrollbarPadding: false,
                            //     backdrop:false,
                            // })

                            var title = "{{ translate('Product Category') }}";
                            var message =
                                '{{ translate('You are unable to enable the variant option because the selected category lacks any attributes.') }}';

                            $('#title-modal').text(title);
                            $('#text-modal').text(message);

                            $('#modal-info').modal('show');
                        }
                    } else {
                        $('body input[name="activate_attributes"]').prop('checked', false);
                        // Swal.fire({
                        //     title: 'Cancelled',
                        //     text: 'Select a category before activating the variant option.',
                        //     icon: 'error',
                        //     scrollbarPadding: false,
                        //     backdrop:false,
                        // });
                        var title = "{{ translate('Product Category') }}";
                        var message =
                            '{{ translate('Select a category before activating the variant option.') }}';

                        $('#title-modal').text(title);
                        $('#text-modal').text(message);

                        $('#modal-info').modal('show');
                    }
                }
            });

            //Check length short description
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

            //Get attribute of category checked
            $("body").on("click", '.radio-category', function() {
                var categorie_id = $(this).val();

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
                            $('#variant_informations').hide();
                            $('body .div-btn').hide();
                            $('body #bloc_variants_created').hide();
                        }

                        $('#general_attributes').html(data.html_attributes_generale);

                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                });
            });

            //Get value of attribute checked
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

                        AIZ.plugins.bootstrapSelect('refresh');

                        var count_boolean = 1;
                        $("#variant_informations > #bloc_attributes:first > div").each(function(
                            index, element) {
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

                        $("#bloc_variants_created div").each(function(index, element) {
                            if ($(element).hasClass('clonedDiv')) {
                                if ($(element).data('id') != undefined) {
                                    id_variant = $(element).data('id');
                                    $(element).find('.attributes').each(function(index,
                                        element) {
                                        // Change the attribute name of the current input
                                        if ($(element).attr("name") ==
                                            undefined) {
                                            var id_attribute = $(element).data(
                                                'id_attributes');
                                            var name = 'attributes-' +
                                                id_attribute + '-' + id_variant
                                            if ($(element).data('type') ==
                                                'color') {
                                                var name = 'attributes-' +
                                                    id_attribute + '-' +
                                                    id_variant + '[]'
                                                $(element).attr('name', name);
                                            } else {
                                                var name = 'attributes-' +
                                                    id_attribute + '-' +
                                                    id_variant
                                                $(element).attr('name', name);
                                            }
                                        }

                                    });

                                    $(element).find('.attributes-units').each(function(
                                        index, element) {
                                        if ($(element).attr("name") ==
                                            undefined) {
                                            console.log('done done')
                                            var id_attribute = $(element).data(
                                                'id_attributes');
                                            var name = 'unit_variant[' +
                                                id_variant + '][' +
                                                id_attribute + ']'
                                            $(element).attr('name', name);
                                        }
                                    });
                                }
                            } else {
                                if ($(element).data('id') != undefined) {
                                    id_variant = $(element).data('id');
                                    $(element).find('.attributes').each(function(index,
                                        element) {
                                        // Change the attribute name of the current input
                                        if ($(element).attr("name") ==
                                            undefined) {
                                            var id_attribute = $(element).data(
                                                'id_attributes');

                                            if ($(element).data('type') ==
                                                'color') {
                                                var name =
                                                    'variant[attributes][' +
                                                    id_variant + '][' +
                                                    id_attribute + '][]'
                                                $(element).attr('name', name);
                                            } else {
                                                var name =
                                                    'variant[attributes][' +
                                                    id_variant + '][' +
                                                    id_attribute + ']'
                                                $(element).attr('name', name);
                                            }
                                        }

                                    });

                                    $(element).find('.attributes-units').each(function(
                                        index, element) {
                                        if ($(element).attr("name") ==
                                            undefined) {
                                            console.log('done done')
                                            var id_attribute = $(element).data(
                                                'id_attributes');
                                            var name = 'unit_variant[' +
                                                id_variant + '][' +
                                                id_attribute + ']'
                                            $(element).attr('name', name);
                                        }
                                    });
                                }
                            }

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

                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                });


            })

            //Change label of input value by name of file selected
            $('body').on('change', '.photos_variant', function() {
                // Get the number of selected files
                var numFiles = $(this)[0].files.length;
                var files = this.files;
                var uploaded_files = $(this).data('count');

                if (uploaded_files != undefined) {
                    var all_files_length = files.length + uploaded_files
                } else {
                    var all_files_length = files.length
                }

                // Maximum number of allowed files
                var maxFiles = 10;
                if (all_files_length > maxFiles) {
                    // Swal.fire({
                    //     title: 'Cancelled',
                    //     text: '{{ translate('You can only upload a maximum of 10 files.') }}',
                    //     icon: 'error',
                    //     scrollbarPadding: false,
                    //     backdrop:false,
                    // });
                    var title = "{{ translate('Variant Media') }}";
                    var message = '{{ translate('You can only upload a maximum of 10 files.') }}';

                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show');
                    this.value = ''; // Clear the file input
                } else if (all_files_length == 0) {
                    // Swal.fire({
                    //     title: 'Cancelled',
                    //     text: '{{ translate('You need to select at least one picture.') }}',
                    //     icon: 'error',
                    //     scrollbarPadding: false,
                    //     backdrop:false,
                    // });
                    var title = "{{ translate('Variant Media') }}";
                    var message = '{{ translate('You need to select at least one picture.') }}';

                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show');

                    var labelText = '0 file selected';
                    $(this).next('.custom-file-label').html(labelText);
                } else if ((all_files_length <= maxFiles) && (all_files_length > 0)) {
                    // Update the label text accordingly
                    var labelText = numFiles === 1 ? '1 file selected' : numFiles + ' files selected';
                    $(this).next('.custom-file-label').html(labelText);

                    let uploadedFilesHTML = '';
                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];
                        if (file.type.startsWith('image/')) {
                            uploadedFilesHTML +=
                                `<div class="col-2"><img src="${URL.createObjectURL(file)}" alt="${file.name}" height="120" width="120" /></div>`; // Display image preview
                        } else {
                            // Display icon for document type
                            uploadedFilesHTML += `<li><i class="fa fa-file-text-o"></i> ${file.name}</li>`;
                        }
                    }

                    var parent_container = $(this).parent().parent().find('.uploaded_images');
                    parent_container.find('div:not(.old_image)').remove();
                    parent_container.append(uploadedFilesHTML);
                }
            });

            //Create variant when click on button create variant
            $('body').on('click', '#btn-create-variant', function() {
                // Clone the original div
                if ($('#attributes option:selected').length > 0) {
                    var clonedDiv = $('#variant_informations').clone();

                    // Add some unique identifier to the cloned div (optional)
                    clonedDiv.attr('class', 'clonedDiv');
                    clonedDiv.attr('data-id', numbers_variant);

                    // Append the cloned div to the container
                    var count = numbers_variant + 1;
                    //add attribute name for each input cloned
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
                    clonedDiv.find('div.row').each(function() {
                        // Check if the div has display:none set
                        if ($(this).css('display') === 'none') {
                            // If it's set to display:none, change it to its default value
                            $(this).css('display', '');
                        }
                    });
                    clonedDiv.find('.sku').attr('name', 'sku-' + numbers_variant);
                    clonedDiv.find('.sku').prop('readonly', true);
                    clonedDiv.find('.vat_sample').attr('name', 'vat_sample-' + numbers_variant);
                    clonedDiv.find('.sample_description').attr('name', 'sample_description-' +
                        numbers_variant);
                    clonedDiv.find('.sample_price').attr('name', 'sample_price-' + numbers_variant);
                    clonedDiv.find('.sample_description_parent').attr('name', 'sample_description-' +
                        numbers_variant);
                    clonedDiv.find('.sample_price_parent').attr('name', 'sample_price-' + numbers_variant);
                    clonedDiv.find('.photos_variant').attr('name', 'photos_variant-' + numbers_variant +
                        '[]');
                    clonedDiv.find('.photos_variant').attr('id', 'photos_variant-' + numbers_variant);
                    clonedDiv.find('.custom-file-label').attr('for', 'photos_variant-' + numbers_variant);
                    clonedDiv.find('.variant-pricing').attr('name', 'variant-pricing-' + numbers_variant);
                    clonedDiv.find('.variant-pricing').attr('data-variant', numbers_variant);
                    clonedDiv.find('.variant-sample-pricing').attr('name', 'variant-sample-pricing-' +
                        numbers_variant);
                    clonedDiv.find('.variant-published').attr('name', 'variant-published-' +
                        numbers_variant);
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
                    clonedDiv.find('.variant-shipping').attr('data-id', numbers_variant);
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
                        // Retrieve the data-id_attributes value of the current input
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

                        // Change the attribute name of the current input
                        if (check == false) {
                            $(element).attr('name', 'attributes-' + dataIdValue + '-' +
                                numbers_variant);
                        }

                    });

                    clonedDiv.find('.attributes-units').each(function(index, element) {
                        // Retrieve the data-id_attributes value of the current input
                        var dataIdValue = $(element).data('id_attributes');

                        // Change the attribute name of the current input
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

                    clonedDiv.find("#bloc_pricing_configuration_variant input[type=number]")
                        .attr("name", `variant-unit-price${numbers_variant}`);
                    clonedDiv.find('#bloc_pricing_configuration_variant input[type=number]')
                        .attr('data-id_newvariant', numbers_variant);

                    clonedDiv.find('.variant-sample-available').attr('name', 'variant-sample-available' +
                        numbers_variant);
                    clonedDiv.find('.variant-sample-pricing').attr('name', 'variant-sample-pricing' +
                        numbers_variant);
                    clonedDiv.find('.variant-sample-pricing').attr('data-id_newvariant', numbers_variant);
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
                        $(element).attr('name', 'variant_shippers_sample-' + numbers_variant);
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
                    clonedDiv.find('.delete_pricing_canfiguration').attr('data-newvariant-id',
                        numbers_variant);

                    // $('#bloc_variants_created').prepend(clonedDiv);
                    // var divId = "#bloc_variants_created";

                    // // Get the length of all h3 tags under the specific div
                    // var h3Count = $(divId + " h3").length;

                    // // Loop through each h3 tag and display its order
                    // $(divId + " h3").each(function(index) {
                    //     var order = h3Count - index; // Number in descending order
                    //     $(this).text("{{ translate('Variant Information') }}" + ' ' + order);
                    // });
                    // numbers_variant++;

                    if (clonedDiv.find('.sku').val() == '') {
                        var title =
                            "{{ translate('Please fill the missing fields and/or correct the listed entries in order to submit your product for approval') }}";
                        var message =
                            '{{ translate('The SKU field must be filled before creating the variant.') }}';
                        $('#title-modal').text(title);
                        $('#text-modal').text(message);

                        $('#modal-info').modal('show')
                    } else {
                        $('#bloc_variants_created').show();
                        $('#bloc_variants_created').prepend(clonedDiv);
                        var divId = "#bloc_variants_created";

                        // Get the length of all h3 tags under the specific div
                        var h3Count = $(divId + " h3").length;


                        // Loop through each h3 tag and display its order
                        $(divId + " h3").each(function(index) {
                            var order = h3Count - index; // Number in descending order
                            $(this).text("{{ translate('Variant Information') }}" + ' ' + order);
                        });
                        numbers_variant++;
                        //AIZ.plugins.bootstrapSelect('refresh');

                        $('#variant_informations').find(
                            'input[type="text"], input[type="number"], input[type="checkbox"], input[type="radio"], select'
                            ).each(function() {
                            // Reset text and number inputs
                            if ($(this).is('input[type="text"]') || $(this).is(
                                    'input[type="number"]')) {
                                $(this).val(''); // Clear the value for text and number inputs
                            }
                            // Reset checkboxes and radio buttons
                            else if ($(this).is('input[type="radio"]')) {
                                $(this).prop('checked',
                                false); // Uncheck checkboxes and radio buttons
                            }
                            // Reset select options
                            else if ($(this).is('select')) {
                                $(this).val(''); // Reset to the first option (index 0)
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

            //enabled all input under specific variant to edit
            $('body').on('click', '.fa-pen-to-square', function() {
                $(this).parent().find('input').prop('readonly', false);
                $(this).parent().find('.fa-circle-xmark').show();
                $(this).parent().find('#btn-add-pricing-variant').show();
                $(this).parent().find('.fa-pen-to-square').hide();
                $(this).parent().find('.fa-circle-check').show();
            })

            //disabled all input under specific variant to edit
            $('body').on('click', '.fa-circle-check', function() {
                $(this).parent().find('input').prop('readonly', true);
                $(this).parent().find('.fa-circle-xmark').hide();
                $(this).parent().find('#btn-add-pricing-variant').hide();
                $(this).parent().find('.fa-pen-to-square').show();
                $(this).parent().find('.fa-circle-check').hide();
            })

            //show or hide bloc sample variant under specific variant
            $('body').on('change', '.variant-sample-pricing', function() {
                if ($(this).is(':not(:checked)')) {
                    var clonedDiv = $('#sample_parent').clone();
                    id_variant = $(this).data('variant');
                    id_new_variant = $(this).data('id_newvariant');
                    if (id_variant != undefined) {
                        clonedDiv.find('.sample_description_parent').attr('name',
                            'variant[sample_description][' + id_variant + "]");
                        clonedDiv.find('.sample_price_parent').attr('name', 'variant[sample_price][' +
                            id_variant + "]");
                    } else if (id_new_variant != undefined) {
                        clonedDiv.find('.sample_description_parent').attr('name', 'sample_description-' +
                            id_new_variant);
                        clonedDiv.find('.sample_price_parent').attr('name', 'sample_price-' +
                            id_new_variant);
                        clonedDiv.find('.sample_price_parent').attr('readonly', false);
                    }
                    $(this).parent().parent().parent().find('.bloc_sample_pricing_configuration_variant')
                        .show();
                    $(this).parent().parent().parent().find('.bloc_sample_pricing_configuration_variant')
                        .html(clonedDiv);
                } else {
                    $(this).parent().parent().parent().find('.bloc_sample_pricing_configuration_variant')
                        .empty();
                }
            })

            $('body').on('change', '.variant-pricing', function() {
                if ($(this).is(':not(:checked)')) {
                    var is_variant = $(this).data("variant");
                    var old_variant = $(this).data("old_variant");
                    var clonedElement = $("#table_pricing_configuration").clone();

                    let oldVariantUnitPrice = $(this).data("unit_price") ?? 0;
                    let numbers_variant = parseInt("{{ count($product->getChildrenProducts()) }}");

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
                               name="${numbers_variant > 0 ? `variant_unit_price-${numbers_variant}` : `variant[unit_sale_price][${old_variant}]`}"
                               value="${oldVariantUnitPrice}"
                               placeholder="{{ __("Unit of Sale Price") }}"
                            />
                        </div>
                    `);

                    clonedElement.find('.min-qty').each(function(index, element) {
                        $(element).removeClass("min-qty").addClass("min-qty-variant");
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[from][]');
                        } else if (old_variant != undefined) {
                            $(element).attr('name', 'variant[from][' + old_variant + '][]');
                        } else {
                            $(element).removeAttr("name");
                        }
                    });
                    clonedElement.find('.max-qty').each(function(index, element) {
                        $(element).removeClass("max-qty").addClass("max-qty-variant");
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant + '[to][]');
                        } else if (old_variant != undefined) {
                            $(element).attr('name', 'variant[to][' + old_variant + '][]');
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
                        } else if (old_variant != undefined) {
                            $(element).attr('name', 'variant[discount_percentage][' + old_variant +
                                '][]');
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
                        } else if (old_variant != undefined) {
                            $(element).attr('name', 'variant[discount_amount][' + old_variant +
                                '][]');
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

                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[discount_range][]');
                        } else if (old_variant != undefined) {
                            $(element).attr('name', 'variant[date_range_pricing][' + old_variant +
                                '][]');
                        } else {
                            $(element).removeAttr("name");
                        }
                    });
                    clonedElement.find('.unit-price-variant').each(function(index, element) {
                        $(element).removeClass("unit-price").addClass("unit-price-variant");
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[unit_price][]');
                        } else if (old_variant != undefined) {
                            $(element).attr('name', 'variant[unit_price][' + old_variant + '][]');
                        } else {
                            $(element).removeAttr("name");
                        }
                    });
                    clonedElement.find('.discount_type').each(function(index, element) {
                        $(element).removeClass("discount_type").addClass("discount_type-variant");
                        $(element).removeClass("aiz-selectpicker")
                        if (is_variant != undefined) {
                            $(element).attr('name', 'variant_pricing-from' + is_variant +
                                '[discount_type][]');
                        } else if (old_variant != undefined) {
                            $(element).attr('name', 'variant[discount_type][' + old_variant +
                            '][]');
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

                    if (is_variant != undefined) {
                        clonedElement.find('.btn-add-pricing').attr('data-newvariant-id', is_variant);
                    } else if (old_variant != undefined) {
                        clonedElement.find('.btn-add-pricing').attr('data-id_variant', old_variant);
                        clonedElement.find('.delete_pricing_canfiguration').attr('data-pricing_id',
                            old_variant);
                    }

                    $(this).parent().parent().parent().find('.bloc_pricing_configuration_variant').show();
                    $(this).parent().parent().parent().find('.bloc_pricing_configuration_variant').append(
                        unitPriceElement);
                } else {
                    $(this).parent().parent().parent().find('.bloc_pricing_configuration_variant').empty();
                }
            })

            $("#country_selector").countrySelect({
                responsiveDropdown: true,
                defaultCountry: "{{ $product->country_code }}"
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
                var parent_selector = $(this).parent().parent().parent();

                $(parent_selector).find('.min-qty').each(function() {
                    // Get the value of each input field and push it to the array
                    valuesMinQtyArray.push($(this).val());
                    $(this).css('border-color', '#e2e5ec');
                });

                $(parent_selector).find('.max-qty').each(function() {
                    // Get the value of each input field and push it to the array
                    valuesMaxQtyArray.push($(this).val());
                    $(this).css('border-color', '#e2e5ec');
                });

                console.log('valuesMinQtyArray: ', valuesMinQtyArray)
                console.log('valuesMaxQtyArray: ', valuesMaxQtyArray)

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

            $('body').on('focusout', '.unit-price-variant', function() {
                var value = parseFloat(this.value);
                if (isNaN(value)) {
                    // Reset to 0.00 if the input is not a valid number
                    this.value = '0.00';
                } else {
                    // Round the value to two decimal places
                    this.value = value.toFixed(2);
                }
            })

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


            $('body').on('click', '.btn-add-pricing', function() {
                var id_variant = $(this).data('id_variant');
                var newvariant = $(this).data('newvariant-id');

                if (id_variant != undefined) {
                    var html_to_add = `
                                <tr>
                                    <td><input type="number" min="1" name="variant[from][` + id_variant + `][]" class="form-control min-qty" id="" placeholder="{{ translate('From QTY') }}"></td>
                                    <td><input type="number" min="1" name="variant[to][` + id_variant + `][]" class="form-control max-qty" id="" placeholder="{{ translate('To QTY') }}"></td>
                                    <td><input type="number" step="0.01" min="1" name="variant[unit_price][` +
                        id_variant +
                        `][]" class="form-control unit-price-variant" id="" placeholder="{{ translate('Unit Price') }}"></td>
                                    <td><input type="text" class="form-control aiz-date-range discount-range" name="variant[date_range_pricing][` +
                        id_variant + `][]" placeholder="{{ translate('Select Date') }}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                    <td>
                                        <select class="form-control discount_type" name="variant[discount_type][` +
                        id_variant +
                        `][]">
                                            <option value="" selected>{{ translate('Choose type') }}</option>
                                            <option value="amount" @selected(old('discount_type') == 'amount')>{{ translate('Flat') }}</option>
                                            <option value="percent" @selected(old('discount_type') == 'percent')>{{ translate('Percent') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control discount_amount" name="variant[discount_amount][` +
                        id_variant +
                        `][]" placeholder="{{ translate('Amount') }}" readonly></td>
                                    <td style="width: 19% !important;">
                                        <div class="col-md-9 input-group">
                                            <input type="number" class="form-control discount_percentage" name="variant[discount_percentage][` +
                        id_variant + `][]" placeholder="{{ translate('Percentage') }}" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="las la-plus btn-add-pricing" data-id_variant="` + id_variant + `" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                        <i class="las la-trash delete_pricing_canfiguration" data-pricing_id="` +
                        id_variant + `" style="margin-left: 5px; margin-top: 17px;" title="Delete this ligne')}}"></i>
                                    </td>
                                </tr>
                            `;
                } else if (newvariant != undefined) {
                    var html_to_add = `<tr>
                                    <td><input type="number" min="1" name="variant_pricing-from` + newvariant + `[from][]" class="form-control min-qty" id="" placeholder="{{ translate('From QTY') }}"></td>
                                    <td><input type="number" min="1" name="variant_pricing-from` + newvariant + `[to][]" class="form-control max-qty" id="" placeholder="{{ translate('To QTY') }}"></td>
                                    <td><input type="number" step="0.01" min="1" name="variant_pricing-from` +
                        newvariant +
                        `[unit_price][]" class="form-control unit-price-variant" id="" placeholder="{{ translate('Unit Price') }}"></td>
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
                                    <td><input type="number" class="form-control discount_amount" name="variant_pricing-from` +
                        newvariant +
                        `[discount_amount][]" placeholder="{{ translate('Amount') }}" readonly></td>
                                    <td style="width: 19% !important;">
                                        <div class="col-md-9 input-group">
                                            <input type="number" class="form-control discount_percentage" name="variant_pricing-from` +
                        newvariant + `[discount_percentage][]" placeholder="{{ translate('Percentage') }}" readonly>
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
                                    <td><input type="number" min="1" class="form-control min-qty-variant" id="" placeholder="{{ translate('From QTY') }}"></td>
                                    <td><input type="number" min="1" class="form-control max-qty-variant" id="" placeholder="{{ translate('To QTY') }}"></td>
                                    <td><input type="number" step="0.01" min="1" class="form-control unit-price-variant" id="" placeholder="{{ translate('Unit Price') }}"></td>
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
                                    <td><input type="number" min="1" name="from[]" class="form-control min-qty" id="" placeholder="{{ translate('From QTY') }}"></td>
                                    <td><input type="number" min="1" name="to[]" class="form-control max-qty" id="" placeholder="{{ translate('To QTY') }}"></td>
                                    <td><input type="number" step="0.01" min="1" name="unit_price[]" class="form-control unit-price-variant" id="" placeholder="{{ translate('Unit Price') }}"></td>
                                    <td><input type="text" class="form-control aiz-date-range discount-range" name="date_range_pricing[]" placeholder="{{ translate('Select Date') }}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                    <td>
                                        <select class="form-control discount_type" name="discount_type[]">
                                            <option value="" selected>{{ translate('Choose type') }}</option>
                                            <option value="amount" @selected(old('discount_type') == 'amount')>{{ translate('Flat') }}</option>
                                            <option value="percent" @selected(old('discount_type') == 'percent')>{{ translate('Percent') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control discount_amount" name="discount_amount[]" placeholder="{{ translate('Amount') }}" readonly></td>
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
                var current = $(this);
                var parent = $(this).parent().parent().parent();

                var id_pricing = $(this).data('pricing_id')

                var html_added = `<tr>
                                    <td><input type="number" name="from[]" class="form-control min-qty" id=""></td>
                                    <td><input type="number" name="to[]" class="form-control max-qty" id=""></td>
                                    <td><input type="number" name="unit_price[]" class="form-control unit-price-variant" id=""></td>
                                    <td><input type="text" class="form-control aiz-date-range discount-range" name="date_range_pricing[]" placeholder="{{ translate('Select Date') }}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                    <td>
                                        <select class="form-control discount_type" name="discount_type[]">
                                            <option value="" selected>{{ translate('Choose type') }}</option>
                                            <option value="amount" @selected(old('discount_type') == 'amount')>{{ translate('Flat') }}</option>
                                            <option value="percent" @selected(old('discount_type') == 'percent')>{{ translate('Percent') }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control discount_amount" name="discount_amount[]"></td>
                                    <td style="width: 19% !important;">
                                        <div class="col-md-9 input-group">
                                            <input type="number" class="form-control discount_percentage" name="discount_percentage[]">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="las la-plus btn-add-pricing" style="margin-left: 5px; margin-top: 17px;" title="Add another ligne"></i>
                                        <i class="las la-trash delete_pricing_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="Delete this ligne"></i>
                                    </td>
                                </tr>`
                if (id_pricing == undefined) {
                    $(this).parent().parent().remove();
                    if (parent.find('tr').length == 0) {
                        parent.append(html_added);
                    }
                } else {
                    swal({
                            title: '{{ translate('Are you sure you want to delete this pricing ?') }}',
                            type: "warning",
                            confirmButtonText: 'Delete',
                            showCancelButton: true
                        })
                        .then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: "{{ route('seller.products.delete_pricing') }}",
                                    type: "GET",
                                    data: {
                                        id: id_pricing
                                    },
                                    cache: false,
                                    dataType: 'JSON',
                                    success: function(dataResult) {
                                        if (dataResult.status != 'failed') {

                                            current.parent().parent().remove();
                                            if (parent.find('tr').length == 0) {
                                                parent.append(html_added);
                                            }

                                        } else {
                                            // Swal.fire({
                                            //     title: 'Cancelled',
                                            //     text: '{{ translate('Something went wrong.') }}',
                                            //     icon: 'warning',
                                            //     scrollbarPadding: false,
                                            //     backdrop:false,
                                            // });

                                            var title =
                                                "{{ translate('Pricing Configuration') }}";
                                            var message =
                                                '{{ translate('Something went wrong') }}';

                                            $('#title-modal').text(title);
                                            $('#text-modal').text(message);

                                            $('#modal-info').modal('show');
                                        }
                                    }
                                })
                            }
                        })
                }


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

            initializeDropify();
            initializeDropifyThumbnail();

            $('body').on('change', '#photoUpload', function() {
                let files = $(this)[0].files;
                var old_files = "{{ count($product->getImagesProduct()) }}";
                old_files = parseInt(old_files);
                var new_size = old_files + files.length;

                //$('#dropifyUploadedFiles').empty();
                if (new_size > 10) {
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
                            if (exceedingFilesDimension.length > 0) {
                                Swal.fire({
                                    title: 'Cancelled',
                                    text: 'Following files exceeded 1280px width or height limit: ' +
                                        exceedingFilesDimension.join(', '),
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
                                let uploadedFilesHTML = '';
                                for (let i = 0; i < files.length; i++) {
                                    let file = files[i];
                                    if (file.type.startsWith('image/')) {
                                        uploadedFilesHTML +=
                                            `<div class="col-2"><img src="${URL.createObjectURL(file)}" alt="${file.name}" height="120" width="120" /></div>`; // Display image preview
                                    } else {
                                        // Display icon for document type
                                        uploadedFilesHTML +=
                                            `<li><i class="fa fa-file-text-o"></i> ${file.name}</li>`;
                                    }
                                }

                                if ($('body #dropifyUploadedFiles').length === 0) {
                                    $("#bloc_photos").append(
                                        '<div class="row mt-3" id="dropifyUploadedFiles"></div>'
                                        );
                                    $('body #dropifyUploadedFiles').append(uploadedFilesHTML);
                                } else {
                                    $('body #dropifyUploadedFiles').append(uploadedFilesHTML);
                                }
                            }
                        }, 500);
                    }
                }
            });

            $('body').on('change', '#photoUploadThumbnail', function() {
                let files = $(this)[0].files;
                var old_files = "{{ count($product->getThumbnailsProduct()) }}";
                old_files = parseInt(old_files);
                var new_size = old_files + files.length;

                //$('#dropifyUploadedFilesThumbnail').empty();
                if (new_size > 10) {
                    Swal.fire({
                        title: 'Cancelled',
                        text: 'Maximum 10 photos allowed.',
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

                                let uploadedFilesHTML = '';
                                for (let i = 0; i < files.length; i++) {
                                    let file = files[i];
                                    if (file.type.startsWith('image/')) {
                                        uploadedFilesHTML +=
                                            `<div class="col-2"><img src="${URL.createObjectURL(file)}" alt="${file.name}" height="120" width="120" /></div>`; // Display image preview
                                    } else {
                                        // Display icon for document type
                                        uploadedFilesHTML +=
                                            `<li><i class="fa fa-file-text-o"></i> ${file.name}</li>`;
                                    }
                                }

                                if ($('body #dropifyUploadedFilesThumbnail').length == 0) {
                                    $("#bloc_thumbnails").append(
                                        '<div class="row mt-3" id="dropifyUploadedFilesThumbnail"></div>'
                                        );
                                    $('body #dropifyUploadedFilesThumbnail').append(
                                        uploadedFilesHTML);
                                } else {
                                    console.log('existe')
                                    $('body #dropifyUploadedFilesThumbnail').append(
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

                    if (fileSize > maxSize) {
                        // Swal.fire({
                        //     title: 'Cancelled',
                        //     text: 'File size exceeds 15MB.',
                        //     icon: 'error',
                        //     scrollbarPadding: false,
                        //     backdrop:false,
                        // });
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
                                            <input type="file" name="documents[]" class="custom-file-input file_input" id="exampleInputEmail${fileInputCounter}" accept=".pdf,.png,.jpg,.pln,.dwg,.dxf,.gsm,.stl,.rfa,.rvt,.ifc,.3ds,.max,.obj,.fbx,.skp,.rar,.zip" aria-describedby="inputGroupFileAddon04">
                                            <label class="custom-file-label" for="exampleInputEmail${fileInputCounter}">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <i class="las la-plus add_document font-size-icon" style="margin-left: 5px; margin-top: 34px;" ></i>
                                    <i class="las la-trash trash_document font-size-icon" style="margin-left: 5px; margin-top: 34px;" ></i>
                                </div>
                            </div>`;
                $('#documents_bloc').append(html_document);
                fileInputCounter++;
            })

            $('body').on('click', '.trash_document', function() {
                var id_document = $(this).data('id_document');

                if (id_document != undefined) {
                    var current = $(this);
                    Swal.fire({
                            title: '{{ translate('Are you sure you want to delete this documet ?') }}',
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: '{{ translate('Delete') }}',
                            denyButtonText: "{{ translate('No') }}",
                            scrollbarPadding: false,
                            backdrop: false,
                        })
                        .then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: "{{ route('seller.products.delete_image') }}",
                                    type: "GET",
                                    data: {
                                        id: id_document
                                    },
                                    cache: false,
                                    dataType: 'JSON',
                                    success: function(dataResult) {
                                        if (dataResult.status != 'failed') {
                                            current.parent().parent().remove();

                                            var numberOfChildren = $(
                                                '#documents_bloc > div').length;

                                            if (numberOfChildren == 0) {
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
                                                                        <input type="file" name="documents[]" class="custom-file-input file_input" id="exampleInputEmail${fileInputCounter}" accept=".jpeg, .jpg, .png" aria-describedby="inputGroupFileAddon04">
                                                                        <label class="custom-file-label" for="exampleInputEmail${fileInputCounter}">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-2">
                                                                <i class="las la-plus add_document font-size-icon" style="margin-left: 5px; margin-top: 34px;" ></i>
                                                                <i class="las la-trash trash_document font-size-icon" style="margin-left: 5px; margin-top: 34px;" ></i>
                                                            </div>
                                                        </div>`;
                                                $('#documents_bloc').append(html_document);
                                                fileInputCounter++;
                                            }
                                        }
                                    }
                                })
                            }
                        })
                } else {
                    $(this).parent().parent().remove();
                }
            })

            $('body').on('click', '.icon-delete-image', function() {
                var id_image = $(this).data('image_id');

                if (id_image != undefined) {
                    var current = $(this);
                    Swal.fire({
                        title: '{{ translate('Are you sure you want to delete this picture ?') }}',
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: '{{ translate('Delete') }}',
                        denyButtonText: "{{ translate('No') }}",
                        scrollbarPadding: false,
                        backdrop: false,
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: "{{ route('seller.products.delete_image') }}",
                                type: "GET",
                                data: {
                                    id: id_image
                                },
                                cache: false,
                                dataType: 'JSON',
                                success: function(dataResult) {
                                    if (dataResult.status != 'failed') {
                                        current.parent().remove();
                                    }
                                }
                            })
                        }
                    })
                }
            })

            $('body').on('click', '.delete-variant', function() {
                if ($(this).data('id')) {
                    var id_variant = $(this).data('id');
                    var current = $(this);
                    Swal.fire({
                            title: '{{ translate('Are you sure you want to delete this variant ?') }}',
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: '{{ translate('Delete') }}',
                            denyButtonText: "{{ translate('No') }}",
                            backdrop: false,
                        })
                        .then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: "{{ route('seller.products.delete_variant') }}",
                                    type: "GET",
                                    data: {
                                        id_variant: id_variant
                                    },
                                    cache: false,
                                    dataType: 'JSON',
                                    success: function(dataResult) {
                                        current.parent().parent().remove();

                                        var divId = "#bloc_variants_created";

                                        // Get the length of all h3 tags under the specific div
                                        var h3Count = $(divId + " h3").length;


                                        // Loop through each h3 tag and display its order
                                        $(divId + " h3").each(function(index) {
                                            var order = h3Count -
                                            index; // Number in descending order
                                            $(this).text("Variant Information  " +
                                                order);
                                        });
                                    }
                                })
                            }
                        })
                } else {
                    $(this).parent().parent().remove();
                }
            })

            //Shipping script
            $('body').on('click', '#third_party_activate', function() {
                if ($(this).is(':checked')) {
                    var count_shippers = "{{ count($supported_shippers) }}";
                    count_shippers = parseInt(count_shippers);

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

            $('#btn-calculate-formules').on('click', function() {
                var weight = $('#weight').val();
                var length = $('#length').val();
                var width = $('#width').val();
                var height = $('#height').val();
                var breakable = $('#breakable').val();
                var min_third_party = $('#min_third_party').val();
                var max_third_party = $('#max_third_party').val();
                var unit_third_party = $('#unit_third_party').val();

                if ((weight == '') || (length == '') || (width == '') || (height == '') || (
                        min_third_party == '') || (max_third_party == '')) {
                    Swal.fire({
                        title: 'Cancelled',
                        text: "Please ensure that all required fields are filled in.",
                        icon: 'error',
                        scrollbarPadding: false,
                        backdrop: false,
                    });
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
                        html = '<span style="color: red"> Chargeable Weight = ' + Number(chargeable_weight
                            .toFixed(2)) + ", then not accepted by our shipper </span>"
                    } else {
                        html = '<span style="color: green"> Chargeable Weight = ' + Number(chargeable_weight
                            .toFixed(2)) + ", then accepted by our shipper </span>"
                    }



                    $('#result_calculate_third_party').html(html);
                }
            });

            $('body').on('click', '.btn-add-shipping', function() {
                let row = $(this).parent().parent().parent().find('tr').length;
                let id_new_variant = $(this).data('id');
                let id_variant = $(this).data('id_variant');
                let html_to_add = "";

                if ((id_variant == undefined) && (id_new_variant == undefined)) {
                    if ($(this).closest('#variant_informations').length) {
                        html_to_add = `
                            <tr>
                                <td>
                                    <select class="form-control shipper">
                                        <option>{{ __("Select shipper") }}</option>
                                        <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                        <option value="third_party" @selected(old('shipper') == 'third_party')>{{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                    </select>
                                </td>
                                <td><input readonly type="number" class="form-control min-qty-shipping" id="" placeholder="{{ translate('From QTY') }}"></td>
                                <td><input readonly type="number" class="form-control max-qty-shipping" id="" placeholder="{{ translate('To QTY') }}"></td>

                                <td><input readonly type="number" class="form-control estimated_order" placeholder="{{ translate('Days') }}"></td>
                                <td><input readonly type="number" class="form-control estimated_shipping" placeholder="{{ translate('Days') }}"></td>
                                <td>
                                    <select class="form-control paid">
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
                                <td><input readonly type="number" class="form-control flat_rate_shipping" placeholder="{{ translate('Flat rate amount') }}" readonly></td>
                                <td><input readonly type="number" class="form-control charge_per_unit_shipping" placeholder="{{ translate('Charge unit') }}" readonly></td>
                                <td>
                                    <i class="las la-plus btn-add-shipping" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                    <i class="las la-trash delete_shipping_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                                </td>
                            </tr>
                        `;
                    } else {
                        html_to_add = `
                            <tr>
                                <td>
                                    <select class="form-control shipper" name="shipper[]">
                                        <option>{{ __("Select shipper") }}</option>
                                        <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                        <option value="third_party" @selected(old('shipper') == 'third_party')>{{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                    </select>
                                </td>
                                <td><input readonly type="number" name="from_shipping[]" class="form-control min-qty-shipping" id="" placeholder="{{ translate('From QTY') }}"></td>
                                <td><input readonly type="number" name="to_shipping[]" class="form-control max-qty-shipping" id="" placeholder="{{ translate('To QTY') }}"></td>
                                <td><input readonly type="number" class="form-control estimated_order" name="estimated_order[]" placeholder="{{ translate('Days') }}"></td>
                                <td><input readonly type="number" class="form-control estimated_shipping" name="estimated_shipping[]" placeholder="{{ translate('Days') }}"></td>
                                <td>
                                    <select class="disabled-look__clz form-control paid" name="paid[]">
                                        <option value="" selected>{{ translate('Choose option') }}</option>
                                        <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                        <option value="buyer" @selected(old('shipper') == 'buyer')>{{ translate('Buyer') }}</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="disabled-look__clz form-control shipping_charge" name="shipping_charge[]">
                                        <option value="" selected>{{ translate('Choose shipping charge') }}</option>
                                        <option value="flat" @selected(old('shipping_charge') == 'flat')>{{ translate('Flat-rate regardless of quantity') }}</option>
                                        <option value="charging" @selected(old('shipping_charge') == 'charging')>{{ translate('Charging per Unit of Sale') }}</option>
                                    </select>
                                </td>
                                <td><input readonly type="number" class="form-control flat_rate_shipping" name="flat_rate_shipping[]" placeholder="{{ translate('Flat rate amount') }}" /></td>
                                <td><input readonly type="number" class="form-control charge_per_unit_shipping" name="charge_per_unit_shipping[]" placeholder="{{ translate('Charge unit') }}" /></td>
                                <td>
                                    <i class="las la-plus btn-add-shipping" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                    <i class="las la-trash delete_shipping_canfiguration" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                                </td>
                            </tr>
                        `;
                    }
                } else if (id_variant != undefined) {
                    html_to_add = `
                        <tr>
                            <td>
                                <select class="form-control shipper" name="variant[shipper][${id_variant}][]">
                                    <option>{{ __("Select shipper") }}</option>
                                    <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                    <option value="third_party" @selected(old('shipper') == 'third_party')>{{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                </select>
                            </td>
                            <td><input readonly type="number" name="variant[from_shipping][${id_variant}][]" class="form-control min-qty-shipping" id="" placeholder="{{ translate('From QTY') }}"></td>
                            <td><input readonly type="number" name="variant[to_shipping][${id_variant}][]" class="form-control max-qty-shipping" id="" placeholder="{{ translate('To QTY') }}"></td>
                            <td><input readonly type="number" class="form-control estimated_order" name="variant[estimated_order][${id_variant}][]" placeholder="{{ translate('Days') }}"></td>
                            <td><input readonly type="number" class="form-control estimated_shipping" name="variant[estimated_shipping][${id_variant}][]" placeholder="{{ translate('Days') }}"></td>
                            <td>
                                <select class="disabled-look__clz form-control paid" name="variant[paid][${id_variant}][]">
                                    <option value="" selected>{{ translate('Choose option') }}</option>
                                    <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                    <option value="buyer" @selected(old('shipper') == 'buyer')>{{ translate('Buyer') }}</option>
                                </select>
                            </td>
                            <td>
                                <select class="disabled-look__clz form-control shipping_charge" name="variant[shipping_charge][${id_variant}][]">
                                    <option value="" selected>{{ translate('Choose shipping charge') }}</option>
                                    <option value="flat" @selected(old('shipping_charge') == 'flat')>{{ translate('Flat-rate regardless of quantity') }}</option>
                                    <option value="charging" @selected(old('shipping_charge') == 'charging')>{{ translate('Charging per Unit of Sale') }}</option>
                                </select>
                            </td>
                            <td><input readonly type="number" class="form-control flat_rate_shipping" name="variant[flat_rate_shipping][${id_variant}][]" placeholder="{{ translate('Flat rate amount') }}"></td>
                            <td><input readonly type="number" class="form-control charge_per_unit_shipping" name="variant[charge_per_unit_shipping][${id_variant}][]" placeholder="{{ translate('Charge unit') }}"></td>
                            <td>
                                <i class="las la-plus btn-add-shipping" data-id_variant="${id_variant}" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                <i class="las la-trash delete_shipping_canfiguration" data-id_variant="${id_variant}" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                            </td>
                        </tr>
                    `;
                } else if (id_new_variant != undefined) {
                    html_to_add = `
                        <tr>
                            <td>
                                <select class="form-control shipper" name="variant_shipping-${id_new_variant}[shipper][${row}][]">
                                    <option>{{ __("Select shipper") }}</option>
                                    <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                    <option value="third_party" @selected(old('shipper') == 'third_party')>{{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                </select>
                            </td>
                            <td><input readonly type="number" name="variant_shipping-${id_new_variant}[from][]" class="form-control min-qty-shipping" id="" placeholder="{{ translate('From QTY') }}"></td>
                            <td><input readonly type="number" name="variant_shipping-${id_new_variant}[to][]" class="form-control max-qty-shipping" id="" placeholder="{{ translate('To QTY') }}"></td>
                            <td><input readonly type="number" class="form-control estimated_order" name="variant_shipping-${id_new_variant}[estimated_order][]" placeholder="{{ translate('Days') }}"></td>
                            <td><input readonly type="number" class="form-control estimated_shipping" name="variant_shipping-${id_new_variant}[estimated_shipping][]" placeholder="{{ translate('Days') }}"></td>
                            <td>
                                <select class="form-control paid" name="variant_shipping-${id_new_variant}[paid][]">
                                    <option value="" selected>{{ translate('Choose option') }}</option>
                                    <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                    <option value="buyer" @selected(old('shipper') == 'buyer')>{{ translate('Buyer') }}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control shipping_charge" name="variant_shipping-${id_new_variant}[shipping_charge][]">
                                    <option value="" selected>{{ translate('Choose shipping charge') }}</option>
                                    <option value="flat" @selected(old('shipping_charge') == 'flat')>{{ translate('Flat-rate regardless of quantity') }}</option>
                                    <option value="charging" @selected(old('shipping_charge') == 'charging')>{{ translate('Charging per Unit of Sale') }}</option>
                                </select>
                            </td>
                            <td><input readonly type="number" class="form-control flat_rate_shipping" name="variant_shipping-${id_new_variant}[flat_rate_shipping][]" placeholder="{{ translate('Flat rate amount') }}"></td>
                            <td><input readonly type="number" class="form-control charge_per_unit_shipping" name="variant_shipping-${id_new_variant}[charge_per_unit_shipping][]" placeholder="{{ translate('Charge unit') }}" /></td>
                            <td>
                                <i class="las la-plus btn-add-shipping" data-variant-id="${id_new_variant}" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Add another ligne') }}"></i>
                                <i class="las la-trash delete_shipping_canfiguration" data-variant-id="${id_new_variant}" style="margin-left: 5px; margin-top: 17px;" title="{{ translate('Delete this ligne') }}"></i>
                            </td>
                        </tr>
                    `;
                }

                $(this).parent().parent().parent().append(html_to_add);
            });

            $('body').on('click', '.delete_shipping_canfiguration', function() {
                //remove row in shipping configuration
                let id_shipping = $(this).data('id');
                let current_parent = $(this).parent().parent().parent();
                let current = $(this);
                let new_variant_id = $(this).data('variant-id');
                let old_variant_id = $(this).data('id_variant');

                if ((id_shipping == undefined) && (new_variant_id == undefined) && (old_variant_id ==
                        undefined)) {
                    $(this).parent().parent().remove();
                    let count = 0;
                    current.find('.shipper').each(function(index) {
                        $(this).attr('name', 'shipper[' + count + ']')
                        count++
                    });
                } else {
                    swal({
                            title: '{{ translate('Are you sure you want to delete this shipping ?') }}',
                            type: "warning",
                            confirmButtonText: '{{ translate('Delete') }}',
                            denyButtonText: "{{ translate('No') }}",
                            showCancelButton: true
                        })
                        .then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: "{{ route('seller.products.delete_shipping') }}",
                                    type: "GET",
                                    data: {
                                        id: id_shipping
                                    },
                                    cache: false,
                                    dataType: 'JSON',
                                    success: function(dataResult) {
                                        if (dataResult.status == 'success') {
                                            current.parent().parent().remove();
                                            var count = 0;
                                            current_parent.find('.shipper').each(function(
                                                index) {
                                                if ((new_variant_id == undefined) &&
                                                    (old_variant_id == undefined)) {
                                                    $(this).attr('name',
                                                        'shipper[' + count +
                                                        ']')
                                                } else if (new_variant_id !=
                                                    undefined) {
                                                    $(this).attr('name',
                                                        'variant_shipping-' +
                                                        new_variant_id +
                                                        '[shipper][' + count +
                                                        '][]')
                                                } else if (old_variant_id !=
                                                    undefined) {
                                                    $(this).attr('name',
                                                        'variant[shipper][' +
                                                        old_variant_id + '][' +
                                                        count + ']')
                                                }

                                                count++
                                            });
                                        } else {
                                            $('#title-modal').text("{{ translate('Default Shipping Configuration') }}");
                                            $('#text-modal').text("{{ translate('Something went wrong') }}");

                                            $('#modal-info').modal('show');
                                        }
                                    }
                                })
                            }
                        })
                }

            })

            $('body').on('change', '.shipper', function() {
                let count_shippers = "{{ count($supported_shippers) }}";
                count_shippers = parseInt(count_shippers);
                let selected = $(this).val();

                if (["vendor", "third_party"].includes(selected) === true) {
                    $(this).parent().parent().find('input,select').each(function(index, el) {
                        if(index !== 0) {
                            $(el).val(null);

                            if (selected === "third_party") {
                                if(
                                    [
                                        "paid[]",
                                        "shipping_charge[]"
                                    ].includes($(el).attr("name")) === true
                                ) {
                                    $(el).addClass("disabled-look__clz")
                                } else if([
                                    "from_shipping[]",
                                    "to_shipping[]",
                                    "estimated_order[]",
                                ].includes($(el).attr("name")) === true) {
                                    $(el).attr("readonly", false);
                                } else {
                                    $(el).attr("readonly", true);
                                }
                            } else {
                                if($(el).hasClass("disabled-look__clz")) {
                                    $(el).removeClass("disabled-look__clz");
                                } else {
                                    $(el).attr("readonly", false);
                                }
                            }
                        }
                    });
                } else {
                    $(this).parent().parent().find('input,select').each(function(index, el) {
                        if(index !== 0) {
                            $(el).val();

                            if (
                                [
                                    "paid[]",
                                    "shipping_charge[]"
                                ].includes($(el).attr("name")) === true
                            ) {
                                $(el).addClass("disabled-look__clz")
                            } else {
                                $(el).attr("readonly", true)
                            }
                        }
                    });
                }

                if (selected.indexOf('third_party') !== -1) {
                    $(this).parent().parent().find('.paid').find("option:last")
                            .prop("selected", true);
                    if (count_shippers == 0) {
                        $('#title-modal').text("{{ translate('Default Shipping Configuration') }}");
                        $('#text-modal').html(`{{ translate("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}`);
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
                            $('#text-modal').text(message);

                            $('#modal-info').modal('show');
                        } else {
                            length = parseInt(length);
                            height = parseInt(height);
                            width = parseInt(width);
                            weight = parseInt(weight);
                            let volumetric_weight = (length * height * width) / 5000;
                            let chargeable_weight = 0;
                            let html = '';

                            if (volumetric_weight > weight) {
                                chargeable_weight = volumetric_weight;
                            } else {
                                chargeable_weight = weight;
                            }

                            if (chargeable_weight > 30) {
                                var title = "{{ translate('Default Shipping Configuration') }}";
                                var message = "Chargeable Weight = " + Number(chargeable_weight.toFixed(
                                    2)) + ", then not accepted by our shipper";

                                $('#title-modal').text(title);
                                $('#text-modal').text(message);

                                $(this).find("option[value='third_party']").addClass("disabled-look__clz");
                                $(this).find("option[value='third_party']").prop('selected', false);
                            } else {
                                $(this).parent().parent().find('.estimated_shipping').prop('readonly',
                                true);
                                $(this).parent().parent().find('.shipping_charge').find("option:first")
                                    .prop("selected", true);
                                $(this).parent().parent().find('.shipping_charge').addClass(
                                    "disabled-select");
                                $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly',
                                    true);
                                $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                                $(this).parent().parent().find('.estimated_shipping').val(null);
                                $(this).parent().parent().find('.flat_rate_shipping').prop('readonly',
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
                    $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', false);
                    $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                    $(this).parent().parent().find('.paid').val(null);
                    $(this).parent().parent().find('.estimated_shipping').val(null);
                    $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', false);
                    $(this).parent().parent().find('.flat_rate_shipping').val(null);
                }
            })

            $('body').on('change', '.paid', function() {
                let shippers = $(this).parent().parent().find('.shipper').val();

                if (shippers.indexOf('vendor') !== -1) {
                    if ($(this).val() != "buyer") {
                        $(this).parent().parent().find('.shipping_charge').find("option:first").prop(
                            "selected", true);
                        $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', true);
                        $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                        $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', true);
                        $(this).parent().parent().find('.flat_rate_shipping').val(null);
                    } else {
                        $(this).parent().parent().find('.shipping_charge').removeClass("disabled-select");
                    }
                } else {
                    $('#title-modal').text("{{ translate('Default Shipping Configuration') }}");
                    $('#text-modal').html("{{ translate("You cannot select this if you don't selected vendor in shippers") }}");
                    $('#modal-info').modal('show');

                    $(this).prop('selectedIndex', 0);
                }
            })

            $('body').on('change', '.shipping_charge', function() {
                if ($(this).parent().parent().find('.paid').val() == 'vendor') {
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
                    var title = "{{ translate('Default Shipping Configuration') }}";
                    var message = "{{ translate('Wrong choice.') }}";

                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show');

                    $(this).find('option').eq(0).prop('selected', true);
                }
            })

            $('body').on('click', '.btn-add-sample-shipping', function() {
                let row = $(this).parent().parent().parent().find('tr').length;
                let clonedTr = $(this).parent().parent().clone();

                if(row === 1) {
                    clonedTr.find("td:last").append(`
                        <i
                          class="las la-trash delete_shipping_canfiguration"
                          style="margin-left: 5px; margin-top: 17px;"
                          title="{{ __("Delete this ligne") }}"
                        ></i>
                    `);
                }

                clonedTr.find("input,select").each((index,el) => {
                    if (index === 0) {
                        $(el).find("option:first").attr("selected", true);
                    } else {
                        $(el).val(null);
                        $(el).attr("readonly", true);
                    }
                });

                $(this).parent().parent().parent().append(clonedTr);
            });

            $('body').on('change', '.variant-shipping', function() {
                var id_variant = $(this).data('id_variant');
                var id = $(this).data('id');
                if ($(this).is(':not(:checked)')) {
                    var clonedDiv = $('#table_shipping_configuration').clone();

                    clonedDiv.find('.shipper').each(function(index, element) {
                        if (id_variant != null) {
                            $(element).attr('name', `variant[shipper][` + id_variant + `]`)
                        } else if (id != null) {
                            $(element).attr('name', `variant_shipping-` + id + `[shipper]`)
                        } else {
                            $(element).removeAttr('name');
                        }

                        $('#shipping_configuration_box #table_shipping_configuration').find(
                            '.shipper').each(function(key, element_original) {
                            if (index == key) {
                                var values = $(element_original)
                            .val(); // Array containing values to check

                                $(element).find('option').each(function() {
                                    var optionValue = $(this)
                                .val(); // Get value of the option

                                    if ($.inArray(optionValue, values) !== -1) {
                                        $(this).prop('selected',
                                        true); // Select the option if value exists in array
                                    }
                                });
                            }
                        })
                    });

                    clonedDiv.find('.multi-select-container').each(function(index, element) {
                        if (index % 2 != 0) {
                            $(element).remove();
                        }
                    })

                    clonedDiv.find('.paid').each(function(index, element) {
                        if (id_variant != null) {
                            $(element).attr('name', `variant[paid][` + id_variant + `][]`)
                        } else if (id != null) {

                            $(element).attr('name', `variant_shipping-` + id + `[paid][]`)
                        } else {
                            $(element).removeAttr('name');
                        }

                        $('#shipping_configuration_box #table_shipping_configuration').find('.paid')
                            .each(function(key, element_original) {
                                if (index == key) {
                                    $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                                }
                            })
                    });

                    clonedDiv.find('.shipping_charge').each(function(index, element) {
                        if (id_variant != null) {
                            $(element).attr('name', `variant[shipping_charge][` + id_variant +
                                `][]`)
                        } else if (id != null) {

                            $(element).attr('name', `variant_shipping-` + id +
                                `[shipping_charge][]`)
                        } else {
                            $(element).removeAttr('name');
                        }

                        $('#shipping_configuration_box #table_shipping_configuration').find(
                            '.shipping_charge').each(function(key, element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                .val() + '"]').prop('selected', true);
                            }
                        })
                    });

                    if (id_variant != null) {
                        clonedDiv.find('.min-qty-shipping').attr('name', `variant[from_shipping][` +
                            id_variant + `][]`)
                    } else if (id != null) {

                        clonedDiv.find('.min-qty-shipping').attr('name', `variant_shipping-` + id +
                            `[from][]`)
                    } else {
                        clonedDiv.find('.min-qty-shipping').removeAttr('name');
                    }

                    if (id_variant != null) {
                        clonedDiv.find('.max-qty-shipping').attr('name', `variant[to_shipping][` +
                            id_variant + `][]`)
                    } else if (id != null) {

                        clonedDiv.find('.max-qty-shipping').attr('name', `variant_shipping-` + id +
                            `[to][]`)
                    } else {
                        clonedDiv.find('.max-qty-shipping').removeAttr('name');
                    }

                    if (id_variant != null) {
                        clonedDiv.find('.estimated_order').attr('name', `variant[estimated_order][` +
                            id_variant + `][]`)
                    } else if (id != null) {

                        clonedDiv.find('.estimated_order').attr('name', `variant_shipping-` + id +
                            `[estimated_order][]`)
                    } else {
                        clonedDiv.find('.estimated_order').removeAttr('name');
                    }

                    if (id_variant != null) {
                        clonedDiv.find('.estimated_shipping').attr('name', `variant[estimated_shipping][` +
                            id_variant + `][]`)
                    } else if (id != null) {

                        clonedDiv.find('.estimated_shipping').attr('name', `variant_shipping-` + id +
                            `[estimated_shipping][]`)
                    } else {
                        clonedDiv.find('.estimated_shipping').removeAttr('name');
                    }

                    if (id_variant != null) {
                        clonedDiv.find('.shipping_charge').attr('name', `variant[shipping_charge][` +
                            id_variant + `][]`)
                    } else if (id != null) {

                        clonedDiv.find('.shipping_charge').attr('name', `variant_shipping-` + id +
                            `[shipping_charge][]`)
                    } else {
                        clonedDiv.find('.shipping_charge').removeAttr('name');
                    }

                    if (id_variant != null) {
                        clonedDiv.find('.flat_rate_shipping').attr('name', `variant[flat_rate_shipping][` +
                            id_variant + `][]`)
                    } else if (id != null) {

                        clonedDiv.find('.flat_rate_shipping').attr('name', `variant_shipping-` + id +
                            `[flat_rate_shipping][]`)
                    } else {
                        clonedDiv.find('.flat_rate_shipping').removeAttr('name');
                    }

                    if (id_variant != null) {
                        clonedDiv.find('.charge_per_unit_shipping').attr('name',
                            `variant[charge_per_unit_shipping][` + id_variant + `][]`)
                    } else if (id != null) {

                        clonedDiv.find('.charge_per_unit_shipping').attr('name', `variant_shipping-` + id +
                            `[charge_per_unit_shipping][]`)
                    } else {
                        clonedDiv.find('.charge_per_unit_shipping').removeAttr('name');
                    }

                    if (id_variant != undefined) {
                        clonedDiv.find('.btn-add-shipping').attr('data-id_variant', id_variant);
                        clonedDiv.find('.delete_shipping_canfiguration').attr('data-id_variant',
                        id_variant);
                    } else if (id != undefined) {
                        clonedDiv.find('.btn-add-shipping').attr('data-id', id);
                        clonedDiv.find('.delete_shipping_canfiguration').attr('data-variant-id', id);
                    }

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

            //sample script
            $('body').on('click', '.btn-add-sample', function() {
                var html_to_add = `
                                <tr>
                                    <td>
                                        <select class="form-control shipper_sample" name="shipper_sample[]">
                                            <option value="" readonly selected>{{ translate('Choose shipper') }}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="third_party" @selected(old('shipper') == 'third_party')>{{ translate('MawadOnline 3rd Party Shippers') }}</option>
                                        </select>
                                    </td>shipping_
                                    <td><input type="number" class="form-control estimated_sample" name="estimated_sample[]"></td>
                                    <td><input type="number" class="form-control estimated_shipping_sample" name="estimated_shipping_sample[]"></td>
                                    <td>
                                        <select class="form-control paid_sample" name="paid_sample[]">
                                            <option value="" readonly selected>{{ translate('Choose option') }}</option>
                                            <option value="vendor" @selected(old('shipper') == 'vendor')>{{ translate('vendor') }}</option>
                                            <option value="buyer" @selected(old('shipper') == 'buyer')>{{ translate('Buyer') }}</option>
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
                    var clonedDiv = $('#table_sample_configuration').clone();
                    var paid_sample = $('#table_sample_configuration').find('.paid_sample').val();
                    var shipper_sample = $('#table_sample_configuration').find('.shipper_sample').val();
                    clonedDiv.find('.paid_sample').find('option[value="' + paid_sample + '"]').prop(
                        'selected', true);
                    clonedDiv.find('.shipper_sample').each(function(index, element) {
                        $('#table_sample_configuration').find('.shipper_sample').each(function(key,
                            element_original) {
                            if (index == key) {
                                $(element_original).val().forEach(value => {
                                    $(element).find('option[value="' + value + '"]')
                                        .prop('selected', true);
                                });
                            }
                        })
                    });
                    clonedDiv.find('.shipper_sample').multiSelect();
                    clonedDiv.find('.multi-select-container').each(function(index, element) {
                        if (index % 2 != 0) {
                            $(element).remove();
                        }
                    })

                    if ($(this).data('id_old_variant') != undefined) {
                        var id_variant = $(this).data('id_old_variant');

                        clonedDiv.find('.shipper_sample').attr('name', 'variant[shipper_sample][' +
                            id_variant + ']');
                        clonedDiv.find('.estimated_sample').attr('name', 'variant[estimated_sample][' +
                            id_variant + ']');
                        clonedDiv.find('.estimated_shipping_sample').attr('name',
                            'variant[estimated_shipping_sample][' + id_variant + ']');
                        clonedDiv.find('.paid_sample').attr('name', 'variant[paid_sample][' + id_variant +
                            ']');
                        clonedDiv.find('.shipping_amount').attr('name', 'variant[shipping_amount][' +
                            id_variant + ']');
                    } else if ($(this).data('id_new_variant') != undefined) {
                        var id_variant = $(this).data('id_new_variant');

                        clonedDiv.find('.shipper_sample').attr('name', 'variant_shippers_sample-' +
                            numbers_variant + '[]');
                        clonedDiv.find('.paid_sample').attr('name', 'paid_sample-' + numbers_variant);
                        clonedDiv.find('.estimated_sample').attr('name', 'estimated_sample-' +
                            numbers_variant);
                        clonedDiv.find('.estimated_shipping_sample').attr('name',
                            'estimated_shipping_sample-' + numbers_variant);
                        clonedDiv.find('.shipping_amount').attr('name', 'shipping_amount-' +
                            numbers_variant);
                    }
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
                    $(this).parent().parent().parent().parent().find(
                        '.bloc_sample_pricing_configuration_variant').empty();
                    $(this).parent().parent().parent().parent().find('#bloc-sample-shipping').empty();
                }
            })

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

            $('body').on('change', '.shipper_sample', function() {
                let count_shippers = "{{ count($supported_shippers) }}";
                count_shippers = parseInt(count_shippers);
                let selected = $(this).val();

                if (["vendor", "third_party"].includes(selected) === true) {
                    $(this).parent().parent().find('input,select').each(function(index, el) {
                        if(index !== 0) {
                           if (
                                selected === "third_party" && [
                                    "paid_sample[]",
                                ].includes($(el).attr("name")) === true
                            ) {
                                $(el).addClass("disabled-look__clz")
                            } else {
                                $(el).removeClass("disabled-look__clz");
                                $(el).attr("readonly", false);
                            }
                        }
                    });
                } else {
                    $(this).parent().parent().find('input').each(function(_, el) {
                        if(index !== 0) {
                            if ($(el).hasClass("disabled-look__clz")) {
                                $(el).removeClass("disabled-look__clz")
                            } else {
                                $(el).attr("readonly", true);
                            }
                        }
                    });
                }

                if (selected.indexOf('third_party') !== -1) {
                    $(this).parent().parent().find('.shipping_amount').val('');
                    $(this).parent().parent().find('.shipping_amount').prop('readonly', true);
                    $(this).parent().parent().find('.estimated_shipping_sample').val(null);
                    $(this).parent().parent().find('.estimated_shipping_sample').prop('readonly', true);
                    $(this).parent().parent().find('.paid_sample').val('buyer');
                    $(this).parent().parent().find('.paid_sample').prop('readonly', true);

                    if (count_shippers == 0) {
                        let title = "{{ translate('Default Shipping Configuration') }}";
                        let message = "{{ __("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}";

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');
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
                            $('#text-modal').text(message);

                            $('#modal-info').modal('show');
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
                            }
                        }
                    }
                }

                if (selected.indexOf('vendor') !== -1) {
                    $(this).parent().parent().find('.shipping_amount').prop('readonly', false);
                    $(this).parent().parent().find('.estimated_shipping_sample').prop('readonly', false);
                    $(this).parent().parent().find('.paid_sample').prop('readonly', false);
                }
            });

            $('body').on('click', '#third_party_activate_sample', function() {
                if ($(this).is(':checked')) {
                    var count_shippers = "{{ count($supported_shippers) }}";
                    count_shippers = parseInt(count_shippers);

                    if (count_shippers == 0) {
                        $('body input[name="activate_third_party"]').prop('checked', false);

                        var title = "{{ translate('Default Shipping Configuration') }}";
                        var message = "{{ translate("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}";

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');

                        $(this).prop('checked', false)

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
        });

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
                        $('body .div-btn').hide();
                        $('#general_attributes').empty();
                        AIZ.plugins.bootstrapSelect('refresh');
                        // Call your functions to load attributes
                        load_attributes(selectedId);
                    } else {
                        // The node has children, maybe clear selection or handle differently
                        $('#message-category').text("Please select a category without subcategories.");
                        $('#check_selected_parent_id').val(-1);
                        $('#message-category').css({
                            'color': 'red',
                            'margin-right': '7px'
                        });
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
                    if ($('body #bloc_variants_created div').length == 0) {
                        $('body input[name="activate_attributes"]').prop("checked", false);
                    } else {
                        $('body input[name="activate_attributes"]').prop("checked", true);
                        $('body input[name="activate_attributes"]').prop("disabled", false);
                        $('body #attributes').prop("disabled", false);
                        $('body #bloc_attributes').empty();
                    }


                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('choice_form').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                var approved = "{{ $product->approved }}";
                var added_from_catalog = "{{ $product->product_added_from_catalog }}";

                var valid_category = $('#check_selected_parent_id').val();
                if (valid_category == 1) {
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
                                    if ((min_qty == "") || (max_qty == "") || (unit_price == "")) {
                                        check_price = false;
                                    }
                                } else {
                                    $(this).parent().parent().parent().find(
                                            '#bloc_pricing_configuration').find('.min-qty-variant')
                                        .each(function() {
                                            if ($(this).val() == '') {
                                                check_price = false;
                                            }
                                        });

                                    $(this).parent().parent().parent().find(
                                            '#bloc_pricing_configuration').find('.max-qty-variant')
                                        .each(function() {
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

                    //Validation of shipping
                    var check_shipping = true;
                    var min_qty_shipping = $('#min-qty-shipping').val();
                    var max_qty_shipping = $('#max-qty-shipping').val();
                    var shipper_shipping = $('shipper_shipping').val();

                    if ($('body input[name="activate_attributes"]').is(':checked')) {
                        var attributes_selected = $('body #attributes').val();
                        if (attributes_selected.length != 0) {
                            $('body #bloc_variants_created .variant-shipping').each(function() {
                                if ($(this).is(':checked')) {
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
                                if ($(this).val() == '' && $(this).parent().parent().find(".shipper_sample").val() !== "third_party") {
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
                        if (($('body #sku_product_parent').val() == '') || ($('body #sku_product_parent')
                                .val() == undefined)) {
                            check_sku = false
                        }
                    }

                    //Validation of images
                    var check_images = true;
                    if ($('body input[name="activate_attributes"]').is(':checked')) {
                        var attributes_selected = $('body #attributes').val();
                        if (attributes_selected.length != 0) {
                            $('body #bloc_variants_created .photos_variant').each(function() {
                                if ($(this).parent().parent().find('.uploaded_images').children(
                                        'div').length == 0) {
                                    check_images = false;
                                    console.log('false')
                                }

                                if ($(this)[0].files.length === 0) {
                                    if (check_images != true) {
                                        check_images = false;
                                    }
                                } else {
                                    check_images = true;
                                }
                            });
                        } else {
                            check_attributes = false;
                            check_images = false;
                        }
                    }

                    //Validation of attributes
                    var check_attributes_empty = true;
                    var check_units_empty = true;
                    if ($('body input[name="activate_attributes"]').is(':checked')) {
                        $("#bloc_variants_created div").each(function(index, element) {
                            $(element).find('.attributes').each(function(index, child_element) {
                                // Change the attribute name of the current input
                                if ($(child_element).attr('type') == 'radio') {
                                    var name = $(child_element).attr('name');
                                    if ($(`body input[name="${name}"]:checked`).length ===
                                        0) {
                                        check_attributes_empty = false;
                                    }
                                } else {
                                    var specificString = 'attributes-undefined-';
                                    if ($(child_element).attr('name') != undefined) {
                                        if (!$(child_element).attr('name').includes(
                                                specificString)) {
                                            if ($(child_element).val() == '') {
                                                check_attributes_empty = false;
                                            }
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

                    var check_attributes_generale_empty = true;
                    var check_units_generale_empty = true;
                    $("#general_attributes div").each(function(index, element) {
                        $(element).find('.attributes').each(function(index, child_element) {
                            // Change the attribute name of the current input
                            if ($(child_element).attr('type') == 'radio') {
                                var name = $(child_element).attr('name');
                                if ($(`body input[name="${name}"]:checked`).length === 0) {
                                    check_attributes_generale_empty = false;
                                }
                            } else {
                                if ($(child_element).attr('name') != undefined) {
                                    if (($(child_element).attr('name') !=
                                            "attribute_generale-undefined")) {
                                        if ($(child_element).val() == '') {
                                            check_attributes_generale_empty = false;
                                        }
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

                    var tagifyInputs = $(".aiz-tag-input").not(".tagify");
                    var isEmpty = false;

                    tagifyInputs.each(function() {
                        var tagify = $(this).data('tagify');

                        if (tagify.value.length === 0) {
                            isEmpty = true;
                            return false; // Exit the loop early if an empty Tagify input is found
                        }
                    });

                    var check_main_images = true;
                    if ($('#image-preview').children('div').length > 0) {
                        var check_main_images = true;
                    } else {
                        var check_main_images = false;
                    }

                    if ($('body #photoUploadcustom')[0].files.length == 0) {
                        if (check_main_images != true) {
                            check_main_images = false;
                        }
                    }

                    var check_thumbnail_images = true;
                    // if ($('#image-preview-Thumbnail').children('div').length > 0) {
                    //     var check_thumbnail_images = true;
                    // } else {
                    //     var check_thumbnail_images = false;
                    // }

                    if ($('body #photoUploadThumbnailSeconde')[0].files.length == 0) {
                        if (check_thumbnail_images != true) {
                            check_thumbnail_images = false;
                        }
                    }

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

                    if (check_sample_price_variant == false) {
                        var message =
                            "{{ translate('The sample price variant is required and must be greater than or equal to 0.1 AED.') }}";
                        remarks.push(message);
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
                            var message = "{{ translate('You need to choose at least one attribute.') }}";
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

                            if (
                                $('#shipper_sample_parent').val() == '' ||
                                $('#estimated_sample_parent').val() == '' ||
                                $('#estimated_shipping_sample_parent').val() == '' ||
                                $('#paid_sample_parent').val() == ''
                            ) {
                                var message =
                                    "{{ translate('There is a problem with the configuration of your shipping sample.') }}";
                                remarks.push(message);
                            }
                        }

                        if (check_sample_shipping_variant == false) {
                            var message =
                                "{{ translate('There is a problem with the configuration of your shipping variant sample.') }}";
                            remarks.push(message);
                        }
                        if (check_shipping == false) {
                            var message =
                                "{{ translate('There is an issue with your shipping configuration.') }}";
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

                        if (isEmpty) {
                            var message = "{{ translate('tags input cannot be empty.') }}";
                            remarks.push(message);
                        }

                        if (check_long_description == false) {
                            //console.log('ok10');
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
                        if (approved == 1) {
                            Swal.fire({
                                title: "{{ translate('Product Update?') }}",
                                text: "{{ translate('As this product update requires approval, you can keep the last approved product published in the marketplace until the update is approved. If you unpublish the last approved product, then you have to publish the updated product after approval, manually. Do you want to keep the last approved product published?') }}",
                                icon: "info",
                                showCancelButton: true,
                                cancelButtonText: "{{ translate('Cancel Update') }}",
                                confirmButtonText: "{{ translate('Yes') }}",
                                showDenyButton: true,
                                denyButtonText: "{{ translate('No') }}",
                                focusConfirm: false,
                                backdrop: false,
                                preConfirm: () => {
                                    return Swal.getConfirmButton().click();
                                },
                                onOpen: () => {
                                    var html =
                                        '<button type="button" class="swal2-cancel swal2-styled" aria-label="" style="display: inline-block; margin-right: 83px !important">Cancel Update</button>';
                                    $('body').find('.swal2-actions').find('.swal2-cancel')
                                        .remove();
                                    $('body').find('.swal2-actions').prepend(html);
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#last_version').val(1);
                                } else if (result.isDenied) {
                                    $('#last_version').val(0);
                                } else {
                                    return false; // Cancel action if "Cancel Update" is clicked
                                }

                                document.getElementById('choice_form').submit();

                            });
                        } else {
                            document.getElementById('choice_form').submit();
                        }
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
