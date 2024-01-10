@extends('backend.layouts.app')
<style>
    .div-btn{
        display: flex;
        justify-content: center;
    }
</style>

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Product')}}</h5>
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
    <form class="form form-horizontal mar-top" action="{{route('products.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-8">
                @csrf
                <input type="hidden" name="added_by" value="admin">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Information')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Product Name')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ translate('Product Name') }}" onchange="update_sku()" required>
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
                                <input type="text" class="form-control" name="unit" value="{{ old('unit') }}" placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" required>
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
                                <input type="text" class="form-control" name="manufacturer" value="{{ old('manufacturer') }}" placeholder="Manufacturer" required>
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
                            <label class="col-md-3 col-from-label">{{translate('Show Stock Quantity')}}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="stock_visibility_state" value="1" checked="checked">
                                    <span></span>
                                </label>
                            </div>
                        </div>

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
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Pricing Configuration')}}</h5>
                    </div>
                    <div class="card-body">
                        <div id="bloc_pricing_configuration">
                            <div>
                                <div class="row qty-stock">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('From Quantity') }}</label>
                                            <input type="number" class="form-control min-qty" name="from[]">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('To Quantity') }}</label>
                                            <input type="number" class="form-control max-qty" name="to[]">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Unit Price (VAT Exclusive)') }}</label>
                                            <input type="number" class="form-control" name="unit_price[]">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount(Start/End)') }}</label>
                                            <input type="text" class="form-control aiz-date-range" name="date_range_pricing[]" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Type') }}</label>
                                            <select class="form-control aiz-selectpicker discount_type" name="discount_type[]">
                                                <option value="" disabled selected>{{translate('Choose type')}}</option>
                                                <option value="amount" @selected(old('discount_type') == 'amount')>{{translate('Flat')}}</option>
                                                <option value="percent" @selected(old('discount_type') == 'percent')>{{translate('Percent')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Amount') }}</label>
                                            <input type="number" class="form-control discount_amount" name="discount_amount[]">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Percentage') }}</label>
                                            <input type="number" class="form-control discount_percentage" name="discount_percentage[]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row div-btn">
                            <button type="button" name="button" class="btn btn-primary" id="btn-add-pricing">Add another pricing configuration</button>
                        </div>
                    </div>
                </div>
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Variation')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row gutters-5">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('Colors')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple disabled>
                                    @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                    <option  value="{{ $color->code }}" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"></option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="colors_active">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row gutters-5">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <select name="choice_attributes[]" id="choice_attributes" class="form-control aiz-selectpicker" data-selected-text-format="count" data-live-search="true" multiple data-placeholder="{{ translate('Choose Attributes') }}">
                                    @foreach (\App\Models\Attribute::all() as $key => $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>
                            <br>
                        </div>

                        <div class="customer_choice_options" id="customer_choice_options">

                        </div>
                    </div>
                </div>
                {{-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product price + stock')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Unit price')}} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Unit price') }}" name="unit_price" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
	                        <label class="col-sm-3 control-label" for="start_date">{{translate('Discount Date Range')}}</label>
	                        <div class="col-sm-9">
	                          <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
	                        </div>
	                    </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Discount')}} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Discount') }}" name="discount" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control aiz-selectpicker" name="discount_type">
                                    <option value="amount" @selected(old('discount_type') == 'amount')>{{translate('Flat')}}</option>
                                    <option value="percent" @selected(old('discount_type') == 'percent')>{{translate('Percent')}}</option>
                                </select>
                            </div>
                        </div>

                        @if(addon_is_activated('club_point'))
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{translate('Set Point')}}
                                </label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ translate('1') }}" name="earn_point" class="form-control">
                                </div>
                            </div>
                        @endif

                        <div id="show-hide-div">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Quantity')}} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ translate('Quantity') }}" name="current_stock" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{translate('SKU')}}
                                </label>
                                <div class="col-md-6">
                                    <input type="text" placeholder="{{ translate('SKU') }}" name="sku" value="{{ old('sku') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{translate('External link')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('External link') }}" value="{{ old('external_link') }}" name="external_link" class="form-control">
                                <small class="text-muted">{{translate('Leave it blank if you do not use external site link')}}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{translate('External link button text')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('External link button text') }}" name="external_link_btn" value="{{ old('external_link_btn') }}" class="form-control">
                                <small class="text-muted">{{translate('Leave it blank if you do not use external site link')}}</small>
                            </div>
                        </div>
                        <br>
                        <div class="sku_combination" id="sku_combination">

                        </div>
                    </div>
                </div> --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Description')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Description')}}</label>
                            <div class="col-md-8">
                                <textarea id="summernote" name="description"></textarea>
                                <div id="charCount">Remaining characters: 512</div>
                                <input type="hidden" id="hidden_value" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('PDF Specification')}}</h5>
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
                                    <label for="exampleInputEmail1">Documnt</label>
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

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Category') }}</h5>
                        <h6 class="float-right fs-13 mb-0">
                            {{ translate('Select Main') }}
                            <span class="position-relative main-category-info-icon">
                                <i class="las la-question-circle fs-18 text-info"></i>
                                <span class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate('This will be used for commission based calculations and homepage category wise product Show.') }}</span>
                            </span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="h-300px overflow-auto c-scrollbar-light">
                            <ul class="hummingbird-treeview-converter list-unstyled" data-checkbox-name="category_ids[]" data-radio-name="category_id">
                                @foreach ($categories as $category)
                                <li id="{{ $category->id }}">{{ $category->getTranslation('name') }}</li>
                                    @foreach ($category->childrenCategories as $childCategory)
                                        @include('backend.product.products.child_category', ['child_category' => $childCategory])
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">
                            {{translate('Shipping Configuration')}}
                        </h5>
                    </div>

                    <div class="card-body">
                        @if (get_setting('shipping_type') == 'product_wise_shipping')
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Free Shipping')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="shipping_type" value="free" checked>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Flat Rate')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="shipping_type" value="flat_rate">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="flat_rate_shipping_div" style="display: none">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{translate('Shipping cost')}}</label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Is Product Quantity Mulitiply')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="is_quantity_multiplied" value="1">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        @else
                        <p>
                            {{ translate('Product wise shipping cost is disable. Shipping cost is configured from here') }}
                            <a href="{{route('shipping_configuration.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                <span class="aiz-side-nav-text">{{translate('Shipping Configuration')}}</span>
                            </a>
                        </p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Low Stock Quantity Warning')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Quantity')}}
                            </label>
                            <input type="number" name="low_stock_quantity" value="1" min="0" step="1" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">
                            {{translate('Stock Visibility State')}}
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Show Stock Quantity')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="quantity" checked>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Show Stock With Text Only')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="text">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Hide Stock')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="hide">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div> --}}

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Cash On Delivery')}}</h5>
                    </div>
                    <div class="card-body">
                        @if (get_setting('cash_payment') == '1')
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{translate('Status')}}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="cash_on_delivery" value="1" checked="">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        @else
                            <p>
                                {{ translate('Cash On Delivery option is disabled. Activate this feature from here') }}
                                <a href="{{route('activation.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Cash Payment Activation')}}</span>
                                </a>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Featured')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Status')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="featured" value="1">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Todays Deal')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Status')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="todays_deal" value="1">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Flash Deal')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Add To Flash')}}
                            </label>
                            <select class="form-control aiz-selectpicker" name="flash_deal_id" id="flash_deal">
                                <option value="">{{ translate('Choose Flash Title') }}</option>
                                @foreach(\App\Models\FlashDeal::where("status", 1)->get() as $flash_deal)
                                    <option value="{{ $flash_deal->id}}">
                                        {{ $flash_deal->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Discount')}}
                            </label>
                            <input type="number" name="flash_discount" value="0" min="0" step="0.01" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Discount Type')}}
                            </label>
                            <select class="form-control aiz-selectpicker" name="flash_discount_type" id="flash_discount_type">
                                <option value="">{{ translate('Choose Discount Type') }}</option>
                                <option value="amount">{{translate('Flat')}}</option>
                                <option value="percent">{{translate('Percent')}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Estimate Shipping Time')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Shipping Days')}}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="est_shipping_days" min="1" step="1" placeholder="{{translate('Shipping Days')}}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">{{translate('Days')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('VAT & Tax')}}</h5>
                    </div>
                    <div class="card-body">
                        @foreach(\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                        <label for="name">
                            {{$tax->name}}
                            <input type="hidden" value="{{$tax->id}}" name="tax_id[]">
                        </label>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control aiz-selectpicker" name="tax_type[]">
                                    <option value="amount">{{translate('Flat')}}</option>
                                    <option value="percent">{{translate('Percent')}}</option>
                                </select>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <div class="col-12">
                <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="Third group">
                        <button type="submit" name="button" value="unpublish" class="btn btn-primary action-btn">{{ translate('Save & Unpublish') }}</button>
                    </div>
                    <div class="btn-group" role="group" aria-label="Second group">
                        <button type="button" name="button" value="publish" class="btn btn-success action-btn" id="save">{{ translate('Save & Publish') }}</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#summernote').summernote();
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

        $('#save').click(function() {
            let overlapFound = false; // Flag to track if any overlaps are found

            var valuesMinQtyArray = [];
            var valuesMaxQtyArray = [];
            $('body .min-qty').each(function() {
                // Get the value of each input field and push it to the array
                valuesMinQtyArray.push($(this).val());
            });

            $('body .max-qty').each(function() {
                // Get the value of each input field and push it to the array
                valuesMaxQtyArray.push($(this).val());
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
            if(overlapFound == false){
                $('#choice_form').submit();
            }
        });


        $('#btn-add-pricing').click(() => {
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
                                            <input type="number" class="form-control min-qty" name="from[]">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('To Quantity') }}</label>
                                            <input type="number" class="form-control max-qty" name="to[]">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Unit Price (VAT Exclusive)') }}</label>
                                            <input type="number" class="form-control" name="unit_price[]">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount(Start/End)') }}</label>
                                            <input type="text" class="form-control aiz-date-range" name="date_range_pricing[]" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Type') }}</label>
                                            <select class="form-control aiz-selectpicker discount_type" name="discount_type[]">
                                                <option value="" disabled selected>{{translate('Choose type')}}</option>
                                                <option value="amount" @selected(old('discount_type') == 'amount')>{{translate('Flat')}}</option>
                                                <option value="percent" @selected(old('discount_type') == 'percent')>{{translate('Percent')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Amount') }}</label>
                                            <input type="number" class="form-control discount_amount" name="discount_amount[]">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ translate('Discount Percentage') }}</label>
                                            <input type="number" class="form-control discount_percentage" name="discount_percentage[]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                $('#bloc_pricing_configuration').append(html_to_add);
                $('body .aiz-date-range:last').daterangepicker({
                    timePicker: true,
                    locale: {
                        format: 'YYYY-MM-DD HH:mm:ss',
                        separator : " to "
                    },
                });
                AIZ.plugins.bootstrapSelect('refresh');

        });

        $('body').on('click', '.fa-circle-xmark', function(){
            $(this).parent().parent().remove();
        })

        $('body').on('change', '.discount_type', function(){
            if($(this).val() == "amount"){
                $(this).parent().parent().parent().parent().find('.discount_amount').prop('readonly', false);
                $(this).parent().parent().parent().parent().find('.discount_percentage').prop('readonly', true);
                $(this).parent().parent().parent().parent().find('.discount_percentage').val('');
            }
            if($(this).val() == "percent"){
                $(this).parent().parent().parent().parent().find('.discount_amount').prop('readonly', true);
                $(this).parent().parent().parent().parent().find('.discount_percentage').prop('readonly', false);
                $(this).parent().parent().parent().parent().find('.discount_amount').val('');
            }
        })
    });
</script>
<script>
    $(document).ready(function() {

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
                var fileSize = file.size; // Get the file size in bytes

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

                    if (totalSize > maxSize) {
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
                                        <label for="exampleInputEmail${fileInputCounter}">Documnt</label>
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

        $("#treeview").hummingbird();

        var main_id = '{{ old("category_id") }}';
        var selected_ids = [];
        @if(old("category_ids"))
            selected_ids = @json(old("category_ids"));
        @endif
        for (let i = 0; i < selected_ids.length; i++) {
            const element = selected_ids[i];
            $('#treeview input:checkbox#'+element).prop('checked',true);
            $('#treeview input:checkbox#'+element).parents( "ul" ).css( "display", "block" );
            $('#treeview input:checkbox#'+element).parents( "li" ).children('.las').removeClass( "la-plus" ).addClass('la-minus');
        }

        $('#treeview input:radio[value='+main_id+']').prop('checked',true);

    });



    $('body').on('click', '.trash_document', function(){
        $(this).parent().parent().remove();
    })

    $('form').bind('submit', function (e) {
		if ( $(".action-btn").attr('attempted') == 'true' ) {
			//stop submitting the form because we have already clicked submit.
			e.preventDefault();
		}
		else {
			$(".action-btn").attr("attempted", 'true');
		}
        // Disable the submit button while evaluating if the form should be submitted
        // $("button[type='submit']").prop('disabled', true);

        // var valid = true;

        // if (!valid) {
            // e.preventDefault();

            ////Reactivate the button if the form was not submitted
            // $("button[type='submit']").button.prop('disabled', false);
        // }
    });

    $("[name=shipping_type]").on("change", function (){
        $(".flat_rate_shipping_div").hide();

        if($(this).val() == 'flat_rate'){
            $(".flat_rate_shipping_div").show();
        }

    });

    function add_more_customer_choice_option(i, name){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('products.add-more-choice-option') }}',
            data:{
               attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly>\
                    </div>\
                    <div class="col-md-8">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" multiple>\
                            '+obj+'\
                        </select>\
                    </div>\
                </div>');
                AIZ.plugins.bootstrapSelect('refresh');
           }
       });


    }

    $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')) {
            $('#colors').prop('disabled', true);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        else {
            $('#colors').prop('disabled', false);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });

    $(document).on("change", ".attribute_choice",function() {
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    $('input[name="unit_price"]').on('keyup', function() {
        update_sku();
    });

    $('input[name="name"]').on('keyup', function() {
        update_sku();
    });

    function delete_row(em){
        $(em).closest('.form-group row').remove();
        update_sku();
    }

    function delete_variant(em){
        $(em).closest('.variant').remove();
    }

    function update_sku(){
        $.ajax({
           type:"POST",
           url:'{{ route('products.sku_combination') }}',
           data:$('#choice_form').serialize(),
           success: function(data) {
                $('#sku_combination').html(data);
                AIZ.uploader.previewGenerate();
                AIZ.plugins.fooTable();
                if (data.trim().length > 1) {
                   $('#show-hide-div').hide();
                }
                else {
                    $('#show-hide-div').show();
                }
           }
       });
    }

    $('#choice_attributes').on('change', function() {
        $('#customer_choice_options').html(null);
        $.each($("#choice_attributes option:selected"), function(){
            add_more_customer_choice_option($(this).val(), $(this).text());
        });

        update_sku();
    });

</script>
@endsection
