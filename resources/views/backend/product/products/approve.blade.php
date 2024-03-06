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
    <h5 class="mb-0 h6">{{translate('Approve Product')}}</h5>
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
    <form>
        <div class="row gutters-5">
            <div class="col-lg-12">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                {{-- Bloc Product Information --}}
                <div class="card">
                    <div class="card-header">
                        <div class="col-3">
                            <h5 class="mb-0 h6">{{translate('Product Information')}}</h5>
                        </div>
                        @if($product->is_parent == 0)
                            <div class="col-7">
                                <span style="float: right;"><b>{{translate('Product status')}}: 
                                    <span class="position-relative main-category-info-icon">
                                    <i class="las la-question-circle fs-18 text-info"></i>
                                    <span class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate("Choosing different items will completely change the product's status.") }}</span>
                                    </span></b>
                                </span> 
                            </div>
                            <div class="col-2">
                                <select class="form-control status" data-id="{{ $product->id }}">
                                    <option value="0" @if($product->approved == 0) selected @endif>Pending</option>
                                    <option value="4" @if($product->approved == 4) selected @endif>Under Review</option>
                                    <option value="2" @if($product->approved == 2) selected @endif>Revision Required</option>
                                    <option value="3" @if($product->approved == 3) selected @endif>Rejected</option>
                                    <option value="1" @if($product->approved == 1) selected @endif>Approved</option>
                                </select>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Product Name')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" value="{{ $product->name }}" placeholder="{{ translate('Product Name') }}" >
                            </div>
                        </div>
                        <div class="form-group row" id="brand">
                            <label class="col-md-3 col-from-label">{{translate('Brand')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">
                                    <option value="">{{ translate('Select Brand') }}</option>
                                    @foreach (\App\Models\Brand::all() as $brand)
                                    <option value="{{ $brand->id }}" @selected($product->brand_id == $brand->id)>{{ $brand->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Unit of Sale')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="unit" value="{{ $product->unit }}" placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" >
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
                                <input type="text" class="form-control" name="manufacturer" value="{{ $product->manufacturer }}" placeholder="Manufacturer" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Tags')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control aiz-tag-input" value="{{ $product->tags }}" name="tags[]" placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                <small class="text-muted">{{translate('This is used for search. Input those words by which cutomer can find this product.')}}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Short description')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="short_description" id="short_description">{{ $product->short_description }}</textarea>
                                <div id="charCountShortDescription">Remaining characters: 512</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Show Stock Quantity')}}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="stock_visibility_state" value="1" @if( $product->stock_visibility_state == 1) checked="checked" @endif>
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
                                    <input type="checkbox" name="refundable" @if($product->refundable == 1) checked @endif>
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
                                <div class="row mt-3" id="dropifyUploadedFiles">
                                    @if(count($product->getImagesProduct()) > 0)
                                        @foreach ($product->getImagesProduct() as $image)
                                            <div class="col-2 container-img">
                                                <img src="{{ asset('/public/'.$image->path) }}" height="120" width="120" class="style-img" />
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(400x400)</small></label>
                            <div class="col-md-8" id="bloc_thumbnails">
                                <div class="row mt-3" id="dropifyUploadedFilesThumbnail">
                                    @if(count($product->getThumbnailsProduct()) > 0)
                                        @foreach ($product->getThumbnailsProduct() as $image)
                                            <div class="col-2 container-img">
                                                <img src="{{ asset('/public/'.$image->path) }}" height="120" width="120" class="style-img" />
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Pricing configuration --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Pricing Configuration')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
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
                        <hr>
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
                                    </tr>
                                </thead>
                                <tbody id="bloc_pricing_configuration">
                                    @if(count($product->getPricingConfiguration()) > 0)
                                        @foreach ($product->getPricingConfiguration() as $pricing)
                                            <tr>
                                                <td><input type="number" name="from[]" class="form-control min-qty" id="" value="{{ $pricing->from }}"></td>
                                                <td><input type="number" name="to[]" class="form-control max-qty" id="" value="{{ $pricing->to }}"></td>
                                                <td><input type="number" name="unit_price[]" class="form-control unit-price-variant" id="" value="{{ $pricing->unit_price }}"></td>
                                                @php
                                                    $date_range = '';
                                                    if($pricing->discount_start_datetime){
                                                        $start_date = new DateTime($pricing->discount_start_datetime);
                                                        $start_date_formatted = $start_date->format('d-m-Y H:i:s');

                                                        $end_date = new DateTime($pricing->discount_end_datetime);
                                                        $end_date_formatted = $end_date->format('d-m-Y H:i:s');

                                                        $date_range = $start_date_formatted.' to '.$end_date_formatted;
                                                    }
                                                @endphp
                                                <td><input type="text" class="form-control aiz-date-range discount-range" value="{{ $date_range }}" name="date_range_pricing[]" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                                <td>
                                                    <select class="form-control discount_type" name="discount_type[]">
                                                        <option value="" selected>{{translate('Choose type')}}</option>
                                                        <option value="amount" @selected($pricing->discount_type == 'amount')>{{translate('Flat')}}</option>
                                                        <option value="percent" @selected($pricing->discount_type == 'percent')>{{translate('Percent')}}</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control discount_amount" value="{{ $pricing->discount_amount }}" @if($pricing->discount_type != 'amount') readonly @endif name="discount_amount[]"></td>
                                                <td><input type="number" class="form-control discount_percentage" value="{{ $pricing->discount_percentage }}" @if($pricing->discount_type != 'percent') readonly @endif name="discount_percentage[]"></td>
                                            </tr>
                                        @endforeach
                                    @else
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
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Bloc Sample pricing configuration --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Sample Pricing Configuration')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="vat_sample" @if($vat_user->vat_registered == 1) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('Sample description')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" name="sample_description">{{ $product->sample_description }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('Sample price')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="sample_price" value="{{ $product->sample_price }}">
                            </div>
                        </div>
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
                                    <option value="youtube" @selected($product->video_provider == 'youtube')>{{translate('Youtube')}}</option>
                                    <option value="vimeo" @selected($product->video_provider == 'vimeo')>{{translate('Vimeo')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Video Link')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}" placeholder="{{ translate('Video Link') }}">
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
                            {{ translate('Select Main') }}
                            <span class="position-relative main-category-info-icon">
                                <i class="las la-question-circle fs-18 text-info"></i>
                                <span class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate('This will be used for commission based calculations and homepage category wise product Show.') }}</span>
                            </span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Category Path')}} :</label>
                            <div class="col-md-8">
                                <b>{{ $product->pathCategory() }}</b>
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
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>
                            </div>
                            <div class="col-md-8" id="attributes_bloc">
                                <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" id="attributes" multiple @if((count($product->getChildrenProducts()) == 0) || (count($attributes) == 0)) disabled @endif data-placeholder="{{ translate('Choose Attributes') }}">
                                    @if(count($attributes) > 0)
                                        @foreach ($attributes as $attribute)
                                            <option  value="{{ $attribute->id }}" @selected(in_array($attribute->id, $variants_attributes_ids_attributes))>{{ $attribute->getTranslation('name') }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="activate_attributes" @if((count($product->getChildrenProducts()) > 0) && (count($attributes) > 0)) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>
                            <br>
                        </div>
                        <div id="bloc_variants_created" style="margin-top: 42px;">
                            @if(count($product->getChildrenProductsDesc()) > 0)
                                @php $key = count($product->getChildrenProductsDesc()) @endphp
                                @foreach ($product->getChildrenProductsDesc() as $children)
                                    <div data-id="{{ $children->id }}">
                                        <div class="row">
                                            <div class="col-3">
                                                <h3 class="mb-3">Variant informations {{ $key }}</h3>
                                            </div>
                                            <div class="col-7">
                                                <span style="float: right; margin-top: 11px;"><b>{{translate('Variant status')}}: <span class="position-relative main-category-info-icon">
                                                    <i class="las la-question-circle fs-18 text-info"></i>
                                                    <span class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate("Choosing different items will completely change the product's status.") }}</span>
                                                    </span></b>
                                                </span> 
                                            </div>
                                            <div class="col-2">
                                                <select class="form-control status" data-id="{{ $children->id }}">
                                                    <option value="0" @if($product->approved == 0) selected @endif>Pending</option>
                                                    <option value="4" @if($product->approved == 4) selected @endif>Under Review</option>
                                                    <option value="2" @if($product->approved == 2) selected @endif>Revision Required</option>
                                                    <option value="3" @if($product->approved == 3) selected @endif>Rejected</option>
                                                    <option value="1" @if($product->approved == 1) selected @endif>Approved</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        <div class="row mb-3" style="margin-top: 30px">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" value="{{translate('Variant SKU')}}" disabled>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control sku" id="sku" name="variant[sku][{{ $children->id }}]" value="{{ $children->sku }}" >
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" value="{{translate('Variant Photos')}}" disabled>
                                            </div>
                                            <div class="col-md-8">
                                                @if(count($children->getImagesProduct()) > 0)
                                                    <div class="row mt-3">
                                                        @foreach ($children->getImagesProduct() as $image)
                                                            <div class="col-2 container-img">
                                                                <img src="{{ asset('/public/'.$image->path) }}" height="120" width="120" class="style-img" />
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" value="{{translate('Variant Pricing')}}" disabled>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input value="1" type="checkbox" name="variant-pricing-{{ $children->id }}" class="variant-pricing" @if(count($children->getPricingConfiguration()) == 0) checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div class="bloc_pricing_configuration_variant">
                                                @if(count($children->getPricingConfiguration()) > 0)
                                                    <table class="table" class="bloc_pricing_configuration_variant">
                                                        <thead>
                                                            <tr>
                                                                <th>{{translate('From Quantity')}}</th>
                                                                <th>{{translate('To Quantity')}}</th>
                                                                <th>{{translate('Unit Price (VAT Exclusive)')}}</th>
                                                                <th>{{translate('Discount(Start/End)')}}</th>
                                                                <th>{{translate('Discount Type')}}</th>
                                                                <th>{{translate('Discount Amount')}}</th>
                                                                <th>{{translate('Discount Percentage')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="bloc_pricing_configuration">
                                                            @foreach ($children->getPricingConfiguration() as $pricing)
                                                                <tr>
                                                                    <td><input type="number" name="variant[from][{{ $children->id }}][]" class="form-control min-qty" id="" value="{{ $pricing->from }}"></td>
                                                                    <td><input type="number" name="variant[to][{{ $children->id }}][]" class="form-control max-qty" id="" value="{{ $pricing->to }}"></td>
                                                                    <td><input type="number" name="variant[unit_price][{{ $children->id }}][]" class="form-control unit-price-variant" id="" value="{{ $pricing->unit_price }}"></td>
                                                                    @php
                                                                        $date_range = '';
                                                                        if($pricing->discount_start_datetime){
                                                                            $start_date = new DateTime($pricing->discount_start_datetime);
                                                                            $start_date_formatted = $start_date->format('d-m-Y H:i:s');

                                                                            $end_date = new DateTime($pricing->discount_end_datetime);
                                                                            $end_date_formatted = $end_date->format('d-m-Y H:i:s');

                                                                            $date_range = $start_date_formatted.' to '.$end_date_formatted;
                                                                        }
                                                                    @endphp
                                                                    <td><input type="text" class="form-control aiz-date-range discount-range" value="{{ $date_range }}" name="variant[date_range_pricing][{{ $children->id }}][]" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-separator=" to " data-format="DD-MM-Y HH:mm:ss" autocomplete="off"></td>
                                                                    <td>
                                                                        <select class="form-control discount_type" name="variant[discount_type][{{ $children->id }}][]">
                                                                            <option value="" selected>{{translate('Choose type')}}</option>
                                                                            <option value="amount" @selected($pricing->discount_type == 'amount')>{{translate('Flat')}}</option>
                                                                            <option value="percent" @selected($pricing->discount_type == 'percent')>{{translate('Percent')}}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" class="form-control discount_amount" value="{{ $pricing->discount_amount }}" name="variant[discount_amount][{{ $children->id }}][]"></td>
                                                                    <td><input type="number" class="form-control discount_percentage" value="{{ $pricing->discount_percentage }}" name="variant[discount_percentage][{{ $children->id }}][]"></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" value="{{translate('Variant Sample Pricing')}}" disabled>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input value="1" type="checkbox" name="variant[sample_pricing][{{ $children->id }}]" class="variant-sample-pricing" checked>
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div class="bloc_sample_pricing_configuration_variant">
                                                @if($children->sample_price != null)
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control" value="{{translate('VAT')}}" disabled>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                                <input value="1" class="vat_sample" type="checkbox" name="variant[vat_sample][{{ $children->id }}]" @if($vat_user->vat_registered == 1) checked @endif>
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control" value="{{translate('Sample description')}}" disabled>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <textarea class="form-control sample_description" name="variant[sample_description][{{ $children->id }}]">{{ $children->sample_description }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control" value="{{translate('Sample price')}}" disabled>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control sample_price" name="variant[sample_price][{{ $children->id }}]" value="{{ $children->sample_price }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" value="{{translate('Variant Shipping')}}" disabled>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input value="1" type="checkbox" class="variant-shipping" name="variant[shipping][{{ $children->id }}]" value="{{ $children->shipping }}" @if($children->shipping == 1) checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" value="{{translate('Variant Sample Shipping')}}" disabled>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input value="1" type="checkbox" class="variant-sample-shipping" name="variant[sample_shipping][{{ $children->id }}]" value="{{ $children->sample_shipping }}" @if($children->sample_shipping == 1) checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" value="{{translate('Low-Stock Warning')}}" disabled>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control stock-warning" id="low_stock_warning" name="variant[low_stock_quantity][{{ $children->low_stock_quantity }}]" >
                                            </div>
                                        </div>
                                        <div id="bloc_attributes">
                                            @if (count($children->getIdsAttributesVariant()) > 0)
                                                @include('seller.product.products.variant_attributes', ['attributes' => $attributes, 'variants_attributes_ids_attributes' => $children->getIdsAttributesVariant(), 'variants_attributes' => $children->getAttributesVariant(), 'children' => $children])
                                            @endif
                                        </div>
                                    </div>
                                    @php $key-- @endphp
                                @endforeach
                            @endif
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
                            <div id="general_attributes">
                                @if (count($general_attributes_ids_attributes) > 0)
                                    <div class="row">
                                        @include('seller.product.products.general_attributes', ['attributes' => $attributes, 'general_attributes_ids_attributes' => $general_attributes_ids_attributes, 'general_attributes' => $general_attributes])
                                    </div>
                                @endif
                            </div>
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
                                <textarea class="aiz-text-editor" name="description">{{ $product->description }}</textarea>
                                <div id="charCount">Remaining characters: 512</div>
                                <input type="hidden" id="hidden_value" value="">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bloc Product Documents --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('PDF Specification')}}</h5>
                    </div>
                    <div class="card-body" id="documents_bloc">
                        @if(count($product->getDocumentsProduct()) > 0)
                            @foreach ($product->getDocumentsProduct() as $key => $document)
                                <div class="row">
                                    <div class="col-5">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Document name</label>
                                            <input type="text" class="form-control" name="old_document_names[{{ $document->id }}]" value="{{ $document->document_name }}">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <a href="{{ asset('/public/'.$document->path) }}" download title="{{ translate('Click to download') }}">
                                            <i class="las la-download font-size-icon"></i>
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
                                            <input type="file" name="documents[]" accept=".pdf,.png,.jpg,.pln,.dwg,.dxf,.gsm,.stl,.rfa,.rvt,.ifc,.3ds,.max,.obj,.fbx,.skp,.rar,.zip" class="custom-file-input file_input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
                                            <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <i class="las la-plus add_document font-size-icon" style="margin-left: 5px; margin-top: 34px;" title="Add another document"></i>
                                    <i class="las la-trash trash_document font-size-icon" style="margin-left: 5px; margin-top: 34px;" title="Delete this document"></i>
                                </div>
                            </div>
                        @endif
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
                                <input type="text" class="form-control" name="meta_title" value="{{ $product->meta_title }}" placeholder="{{ translate('Meta Title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Description')}}</label>
                            <div class="col-md-8">
                                <textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
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
                        <button type="button" class="btn btn-primary" id="save_reason" data-id="" data-status="">Save</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function () {
        $(".plus").click(function () {
            $(this).toggleClass("minus").siblings("ul").toggle();
        })
    })

    function check_fst_lvl(dd) {
        //var ss = $('#' + dd).parents("ul[id^=bs_l]").attr("id");
        var ss = $('#' + dd).parent().closest("ul").attr("id");
        if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
            //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', true);
            $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
        }
        else {
            //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', false);
            $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
        }

    }

    function pageLoad() {
        $(".plus").click(function () {
            $(this).toggleClass("minus").siblings("ul").toggle();
        })
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        @if(count($product->getChildrenProducts()) > 0)
            $('#variant_informations').show();
        @else
            $('#variant_informations').hide();
        @endif

        @if(count($product->getChildrenProducts()) > 0)
            $('#btn-create-variant').show();
        @else
            $('#btn-create-variant').hide();
        @endif
        
        $('body #bloc_pricing_configuration_variant').hide();
        $('body #bloc_sample_pricing_configuration_variant').hide();
        $('body .btn-variant-pricing').hide();
        var numbers_variant = "{{ count($product->getChildrenProducts()) }}";
        numbers_variant = parseInt(numbers_variant);

        var initial_attributes = $('#attributes').val();
        Array.prototype.diff = function(a) {
            return this.filter(function(i) {return a.indexOf(i) < 0;});
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

        $('.status').on('change', function(){
            id_variant = $(this).data('id');
            var status = $(this).val();

            if(($(this).val() == 2) || ($(this).val() == 3)){
                $('#reject').modal('show');
                $('body #save_reason').attr('data-id', id_variant);
                $('body #save_reason').attr('data-status', $(this).val());
            }else{
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
                    if(dataResult.status == 'success'){
                        AIZ.plugins.notify('success', '{{ translate("Status updated") }}');
                    }else{
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }

                    $('#reject').modal('hide');
                }
            })
            }
        });

        $('body').on('click', '#save_reason', function(){
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
                    if(dataResult.status == 'success'){
                        AIZ.plugins.notify('success', '{{ translate("Status updated") }}');
                    }else{
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }

                    $('#reject').modal('hide');
                }
            })
        })
            
    });
</script>

@endsection
