@extends('backend.layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{static_asset('assets/css/summernote.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .color-modified {
        border-color: #FF0017 !important;
        /* Your desired border color */
    }

    .color-modified-file {
        color: #FF0017 !important;
        /* Your desired border color */
    }
</style>
@endsection
@php
use Carbon\Carbon;
@endphp
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ __('messages.vendor_registration_view') }}</h1>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">


        <ul class="nav nav-tabs" id="registerTabs">

            <li class="nav-item">
                <a class="nav-link active" id="business-info-tab" data-toggle="tab"
                    href="#business-info">{{ translate('Business Information') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-person-tab" data-toggle="tab"
                    href="#contact-person">{{ translate('Contact Person') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="warehouses-tab" data-toggle="tab"
                    href="#warehouses">{{ translate('Warehouses') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="payout-info-tab" data-toggle="tab"
                    href="#payout-info">{{ translate('Payout Information') }}</a>
            </li>
        </ul>
        <div class="tab-content" id="registerTabsContent">
            <div class="tab-pane fade show active" id="business-info">
                <form id="businessInfoForm" class=""
                action="{{ route('shops.business_info') }}" method="POST"
                enctype="multipart/form-data" data-next-tab="contact-person">
                @csrf
                <!-- ... Business Info form fields ... -->

                <div class="bg-white border mb-4">
                    <div class="fs-15 fw-600 p-3">
                        {{ translate('Business Information') }}
                    </div>
                    {{-- <div id="validation-errors" class="alert alert-danger"
                        style="display: none;"></div> --}}
                        <?php
                        // $proposedPayoutChange = App\Models\ProposedPayoutChange::where('user_id', $user->id)
                        //     ->latest()
                        //     ->first();
                        $trade_name_english = ($proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_name_english'))
                            ? $proposedPayoutChange->getNewValue('trade_name_english')
                            : (
                                ($user->business_information)
                                    ? $user->business_information->getTranslation('trade_name', 'en', false) ?? ''
                                    : ''
                            );

                            $trade_name_arabic = ($proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_name_arabic'))
                            ? $proposedPayoutChange->getNewValue('trade_name_arabic')
                            : (
                                ($user->business_information)
                                    ? $user->business_information->getTranslation('trade_name', 'ar', false) ?? ''
                                    : ''
                            );
                            // $trade_name_arabic = $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_name_arabic')
                            // ? $proposedPayoutChange->getNewValue('trade_name_arabic')
                            // : $user->business_information->getTranslation('trade_name', 'ar', false) ?? '';

                             $trade_license_doc = $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_license_doc') ? $proposedPayoutChange->getNewValue('trade_license_doc') : $user->business_information->trade_license_doc ?? '';

                             $eshop_name_english = ($proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_english'))
                            ? $proposedPayoutChange->getNewValue('eshop_name_english')
                            : (
                                ($user->business_information)
                                    ? $user->business_information->getTranslation('eshop_name', 'en', false) ?? ''
                                    : ''
                            );
                            $eshop_name_arabic = ($proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_arabic'))
                            ? $proposedPayoutChange->getNewValue('eshop_name_arabic')
                            : (
                                ($user->business_information)
                                    ? $user->business_information->getTranslation('eshop_name', 'ar', false) ?? ''
                                    : ''
                            );
                            //  $eshop_name_english = $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_english')
                            // ? $proposedPayoutChange->getNewValue('eshop_name_english')
                            // : $user->business_information->getTranslation('eshop_name', 'en', false) ?? '';

                            // $eshop_name_arabic = $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_arabic')
                            // ? $proposedPayoutChange->getNewValue('eshop_name_arabic')
                            // : $user->business_information->getTranslation('eshop_name', 'ar', false) ?? '';

                            // $eshop_desc_english = $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_english')
                            // ? $proposedPayoutChange->getNewValue('eshop_desc_english')
                            // : $user->business_information->getTranslation('eshop_desc', 'en', false) ?? '';

                            // $eshop_desc_arabic = $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_arabic')
                            // ? $proposedPayoutChange->getNewValue('eshop_desc_arabic')
                            // : $user->business_information->getTranslation('eshop_desc', 'ar', false) ?? '';
                            $eshop_desc_english = ($proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_english'))
                            ? $proposedPayoutChange->getNewValue('eshop_desc_english')
                            : (
                                ($user->business_information)
                                    ? $user->business_information->getTranslation('eshop_desc', 'en', false) ?? ''
                                    : ''
                            );
                            $eshop_desc_arabic = ($proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_arabic'))
                            ? $proposedPayoutChange->getNewValue('eshop_desc_arabic')
                            : (
                                ($user->business_information)
                                    ? $user->business_information->getTranslation('eshop_desc', 'ar', false) ?? ''
                                    : ''
                            );
                            $license_issue_date = $proposedPayoutChange && $proposedPayoutChange->getNewValue('license_issue_date') ? $proposedPayoutChange->getNewValue('license_issue_date') : $user->business_information->license_issue_date ?? '';
                            if (!empty($license_issue_date))
                                $license_issue_date = Carbon::createFromFormat('Y-m-d', $license_issue_date)->format('d M Y');

                            $license_expiry_date = $proposedPayoutChange && $proposedPayoutChange->getNewValue('license_expiry_date') ? $proposedPayoutChange->getNewValue('license_expiry_date') : $user->business_information->license_expiry_date ?? '';
                            if (!empty($license_expiry_date))
                                $license_expiry_date = Carbon::createFromFormat('Y-m-d', $license_expiry_date)->format('d M Y');

                            $state = $proposedPayoutChange && $proposedPayoutChange->getNewValue('state') ? $proposedPayoutChange->getNewValue('state') : $user->business_information->state ?? '';
                            $area_id = $proposedPayoutChange && $proposedPayoutChange->getNewValue('area_id') ? $proposedPayoutChange->getNewValue('area_id') : $user->business_information->area_id ?? '';

                            $street = $proposedPayoutChange && $proposedPayoutChange->getNewValue('street') ? $proposedPayoutChange->getNewValue('street') : $user->business_information->street ?? '';
                            $building = $proposedPayoutChange && $proposedPayoutChange->getNewValue('building') ? $proposedPayoutChange->getNewValue('building') : $user->business_information->building ?? '';
                            $unit = $proposedPayoutChange && $proposedPayoutChange->getNewValue('unit') ? $proposedPayoutChange->getNewValue('unit') : $user->business_information->unit ?? '';
                            $po_box = $proposedPayoutChange && $proposedPayoutChange->getNewValue('po_box') ? $proposedPayoutChange->getNewValue('po_box') : $user->business_information->po_box ?? '';
                            $landline = $proposedPayoutChange && $proposedPayoutChange->getNewValue('landline') ? $proposedPayoutChange->getNewValue('landline') : $user->business_information->landline ?? '';
                            $vat_registered = $proposedPayoutChange && $proposedPayoutChange->getNewValue('vat_registered') != null ? $proposedPayoutChange->getNewValue('vat_registered') : $user->business_information->vat_registered ?? '';

                            $vat_certificate = $proposedPayoutChange && $proposedPayoutChange->getNewValue('vat_certificate') ? $proposedPayoutChange->getNewValue('vat_certificate') : $user->business_information->vat_certificate ?? '';
                            $trn = $proposedPayoutChange && $proposedPayoutChange->getNewValue('trn') ? $proposedPayoutChange->getNewValue('trn') : $user->business_information->trn ?? '';
                            $tax_waiver = $proposedPayoutChange && $proposedPayoutChange->getNewValue('tax_waiver') ? $proposedPayoutChange->getNewValue('tax_waiver') : $user->business_information->tax_waiver ?? '';
                            $civil_defense_approval = $proposedPayoutChange && $proposedPayoutChange->getNewValue('civil_defense_approval') ? $proposedPayoutChange->getNewValue('civil_defense_approval') : $user->business_information->civil_defense_approval ?? '';

                       ?>
                     <div class="p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('English Trade Name') }} <span
                                            class="text-primary">*</span></label>
                                    @if (isset($user->business_information))
                                    <input title="{{$user->business_information->getTranslation('trade_name', 'en', false) ?? ''}}"  type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_name_english') ? 'color-modified' : '' }}"
                                    placeholder="{{ translate('English Trade Name') }}"
                                    value="{{$trade_name_english }}"
                                    name="trade_name_english" required>
                                    @else
                                    <input  type="text" class="form-control rounded-0 "
                                         name="trade_name_english" required>
                                    @endif


                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Arabic Trade Name') }} <span
                                            class="text-primary">*</span></label>
                                            @if (isset($user->business_information))
                                    <input title="{{$user->business_information->getTranslation('trade_name', 'ar', false) ?? ''}}" type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_name_arabic') ? 'color-modified' : '' }}"
                                        placeholder="{{ translate('Arabic Trade Name') }}"
                                        value="{{$trade_name_arabic }}"
                                        name="trade_name_arabic" required>
                                        @else
                                        <input  type="text" class="form-control rounded-0 "
                                             name="trade_name_arabic" required>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Trade License Doc') }} <span
                                            class="text-primary">*</span></label>


                                    {{-- @if ($trade_license_doc)
                                        <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_license_doc') ? 'color-modified-file' : '' }}"
                                            href="{{ static_asset($trade_license_doc) }}"
                                            target="_blank">{{ translate('View Trade License Doc') }}</a>
                                        <input type="hidden" name="trade_license_doc"
                                            value="{{ $trade_license_doc }}">
                                    @endif --}}

                                    <div class="row">
                                        @if ($proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_license_doc'))
                                        <div class="col-6">
                                            <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_license_doc') ? 'color-modified-file' : '' }}"
                                                href="{{ static_asset($proposedPayoutChange->getNewValue('trade_license_doc')) }}"
                                                target="_blank">{{ translate('View Trade License Doc') }}
                                            </a>
                                        </div>
                                        @endif

                                        @if (isset($user->business_information->trade_license_doc) && !empty($user->business_information->trade_license_doc))
                                        <div class="col-6">
                                            <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_license_doc') ? 'color-modified-file' : '' }}"
                                                href="{{ static_asset($user->business_information->trade_license_doc) }}"
                                                target="_blank">@if ($user->status== "Enabled")
                                                    {{ translate('View Approved Trade License Doc') }}
                                                @else
                                                    {{ translate('View Trade License Doc') }}
                                                @endif
                                            </a>
                                        </div>
                                        @endif

                                    </div>

                                    {{-- <div class="custom-file">
                                <input name="trade_license_doc" type="file" class="custom-file-input" id="inputGroupFile01">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                              </div> --}}

                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('English E-shop Name') }} <span
                                            class="text-primary">*</span></label>
                                            @if (isset($user->business_information))
                                            <input title="{{$user->business_information->eshop_name ? $user->business_information->getTranslation('eshop_name', 'en', false) : ''}}" type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_english') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('English E-shop Name') }}"
                                                value="{{ $eshop_name_english }}"
                                                name="eshop_name_english" required>
                                            @else
                                            <input  type="text" class="form-control rounded-0 "
                                            name="eshop_name_english" required>
                                            @endif

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Arabic E-shop Name') }} <span
                                            class="text-primary">*</span></label>
                                            @if (isset($user->business_information))

                                    <input title="{{$user->business_information->eshop_name ? $user->business_information->getTranslation('eshop_name', 'ar', false) : ''}}" type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_arabic') ? 'color-modified' : '' }}"
                                        placeholder="{{ translate('Arabic E-shop Name') }}"
                                        value="{{$eshop_name_arabic }}"
                                        name="eshop_name_arabic" required>
                                        @else
                                        <input  type="text" class="form-control rounded-0 "
                                        name="eshop_name_arabic" required>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('English e-Shop description') }} <span
                                            class="text-primary"></span></label>
                                            @if (isset($user->business_information))

                                    <textarea title="{{$user->business_information->eshop_desc ? $user->business_information->getTranslation('eshop_desc', 'en', false) : ''}}" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_english') ? 'color-modified' : '' }}" placeholder="{{ translate('English e-Shop description') }}"
                                        name="eshop_desc_english">{{ $eshop_desc_english }}</textarea>
                                        @else
                                        <textarea class="form-control" >
                                           </textarea>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Arabic e-Shop description') }} <span
                                            class="text-primary"></span></label>
                                            @if (isset($user->business_information))

                                    <textarea title="{{$user->business_information->eshop_desc ? $user->business_information->getTranslation('eshop_desc', 'ar', false) : ''}}" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_arabic') ? 'color-modified' : '' }}" placeholder="{{ translate('Arabic e-Shop description') }}"
                                        name="eshop_desc_arabic">{{ $eshop_desc_arabic  }}</textarea>
                                        @else
                                        <textarea  class="form-control">
                                           </textarea>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('License Issue Date') }} <span
                                            class="text-primary">*</span>
                                    </label>

                                    <input title="{{$user->business_information ? $user->business_information->license_issue_date :""}}" dir="auto" required type="{{-- date --}}text"
                                        class="datepicker form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('license_issue_date') ? 'color-modified' : '' }}"
                                        placeholder="{{ translate('License Issue Date') }}"
                                        id="license_issue_date"
                                        value="{{ $license_issue_date }}"
                                        name="license_issue_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('License Expiry Date') }} <span
                                            class="text-primary">*</span></label>

                                    <input title="{{$user->business_information ? $user->business_information->license_expiry_date :""}}" dir="auto" required type="text"
                                        class="datepicker form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('license_expiry_date') ? 'color-modified' : '' }}" {{-- value="{{ $user->business_information->license_expiry_date ?? '' }}" --}}
                                        value="{{ $license_expiry_date }}"
                                        placeholder="{{ translate('License Expiry Date') }}"
                                        name="license_expiry_date">
                                </div>
                            </div>
                            {{-- @if (isset($user->business_information) && !empty($user->business_information->state)) --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('State/Emirate') }} <span
                                                class="text-primary">*</span></label>
                                        <select title="{{$user->business_information && App\Models\Emirate::find($user->business_information->state)  ? App\Models\Emirate::find($user->business_information->state)->name :""}}" required name="state" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('state') ? 'color-modified' : '' }}"
                                            id="emirateempire">
                                            <option value="">{{ translate('please_choose') }}</option>
                                            @foreach ($emirates as $emirate)
                                                <option value="{{ $emirate->id }}"
                                                    @if ( $state == $emirate->id) selected @endif>
                                                    {{ $emirate->name }}
                                                </option>
                                            @endforeach
                                        </select>


                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Area') }} <span class="text-primary">*</span></label>
                                        <select title="{{$user->business_information && App\Models\Area::find($user->business_information->area_id) ? App\Models\Area::find($user->business_information->area_id)->name :""}}" required name="area_id" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('area_id') ? 'color-modified' : '' }}"
                                            id="areaempire">
                                            @php
                                                $areas = App\Models\Area::where(
                                                    'emirate_id',
                                                    $state,
                                                )->get();
                                            @endphp
                                            {{-- <option value="" selected>{{ translate('please_choose') }}
                                            </option> --}}
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    @if ($area->id == $area_id) selected @endif>
                                                    {{ $area->name }}</option>
                                            @endforeach


                                        </select>
                                    </div>
                                </div>
                            {{-- @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('State/Emirate') }} <span
                                                class="text-primary">*</span></label>
                                        <select required name="state" class="form-control rounded-0"
                                            id="emirateempire">
                                            <option value="" selected>{{ translate('please_choose') }}
                                            </option>
                                            @foreach ($emirates as $emirate)
                                                <option value="{{ $emirate->id }}">{{ $emirate->name }}</option>
                                            @endforeach
                                        </select>


                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Area') }} <span class="text-primary">*</span></label>
                                        <select required name="area_id" class="form-control rounded-0"
                                            id="areaempire">
                                            <option value="" selected>
                                                {{ translate('please_choose') }}
                                            </option>


                                        </select>
                                    </div>
                                </div>
                            @endif --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Street') }} <span class="text-primary">*</span></label>
                                    <input title="{{$user->business_information ? $user->business_information->street : '' }}" type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('street') ? 'color-modified' : '' }}"
                                        value="{{ $street }}"
                                        placeholder="{{ translate('Street') }}" name="street" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Building') }} <span class="text-primary">*</span></label>
                                    <input title="{{$user->business_information ? $user->business_information->building : '' }}" type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('building') ? 'color-modified' : '' }}"
                                        value="{{$building }}"
                                        placeholder="{{ translate('Building') }}" name="building" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Unit/Office No.') }} <span
                                            class="text-primary"></span></label>
                                    <input title="{{$user->business_information ? $user->business_information->unit : '' }}" type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('unit') ? 'color-modified' : '' }}"
                                        value="{{$unit }}"
                                        placeholder="{{ translate('Unit/Office No.') }}" name="unit">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('PO Box') }} <span class="text-primary "></span></label>
                                    <input title="{{$user->business_information ? $user->business_information->po_box : '' }}" type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('po_box') ? 'color-modified' : '' }}"
                                        value="{{ $po_box }}"
                                        placeholder="{{ translate('PO Box') }}" name="po_box">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Landline Phone No.') }} <span
                                            class="text-primary"></span></label>
                                    <input title="{{$user->business_information ? $user->business_information->landline : '' }}" value="{{$landline }}"
                                        type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('landline') ? 'color-modified' : '' }}"
                                        placeholder="{{ translate('Landline Phone No.') }}" name="landline">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="{{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('vat_registered') != null ? 'color-modified-file' : '' }}">{{ translate('Vat Registered') }} <span class="text-primary">*
                                        </span></label> <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="1"
                                            id="vatRegisteredYes" name="vat_registered"
                                            @if (
                                                ($vat_registered == 1) ||
                                                empty($vat_registered)) checked @endif>
                                        <label class="form-check-label" for="vatRegisteredYes">
                                            {{ translate('Yes') }}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="0"
                                            id="vatRegisteredNo" @if ( $vat_registered == 0) checked @endif
                                            name="vat_registered">
                                        <label class="form-check-label" for="vatRegisteredNo">
                                            {{ translate('No') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="vatCertificateGroup">
                                <div class="form-group">
                                    <label>{{ translate('Vat Certificate') }} <span
                                            class="text-primary">*</span></label>
                                    {{-- @if ($vat_certificate)
                                        <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('vat_certificate') ? 'color-modified-file' : '' }}"
                                            href="{{ static_asset($vat_certificate) }}"
                                            target="_blank">{{ translate('View Vat Certificate') }}</a>
                                        <input type="hidden" name="vat_certificate"
                                            value="{{ $vat_certificate}}">
                                    @endif --}}
                                    <div class="row">
                                        @if ($proposedPayoutChange && $proposedPayoutChange->getNewValue('vat_certificate'))
                                        <div class="col-6">
                                            <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('vat_certificate') ? 'color-modified-file' : '' }}"
                                                href="{{ static_asset($proposedPayoutChange->getNewValue('vat_certificate')) }}"
                                                target="_blank">{{ translate('View Vat Certificate') }}
                                            </a>
                                        </div>
                                        @endif

                                        @if (isset($user->business_information->vat_certificate) && !empty($user->business_information->vat_certificate))
                                        <div class="col-6">
                                            <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('vat_certificate') ? 'color-modified-file' : '' }}"
                                                href="{{ static_asset($user->business_information->vat_certificate) }}"
                                                target="_blank">@if ($user->status== "Enabled")
                                                     {{ translate('View Approved Vat Certificate') }}
                                                @else
                                                    {{ translate('View Vat Certificate') }}
                                                @endif
                                            </a>
                                        </div>
                                        @endif

                                    </div>
                                    {{-- <input type="file" class="form-control rounded-0"
                                        placeholder="{{ translate('Vat Certificate') }}" name="vat_certificate"> --}}

                                </div>
                            </div>
                            <div class="col-md-6" id="trnGroup">
                                <div class="form-group">
                                    <label>{{ translate('TRN') }} <span class="text-primary">*</span></label>
                                    <input title="{{$user->business_information}}" value="{{ $trn }}" type="text"
                                        class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trn') ? 'color-modified' : '' }}" placeholder="{{ translate('TRN') }}"
                                        name="trn">
                                </div>
                            </div>
                            <div class="col-md-6" id="taxWaiverGroup" {{-- style="display: none;" --}}>
                                <div class="form-group">
                                    <label>{{ translate('Tax Waiver Certificate') }} <span
                                            class="text-primary">*</span></label>
                                    {{-- @if ($tax_waiver)
                                        <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('tax_waiver') ? 'color-modified-file' : '' }}"
                                            href="{{ static_asset($tax_waiver) }}"
                                            target="_blank">{{ translate('View Tax Waiver Certificate') }}</a>
                                        <input type="hidden" name="tax_waiver"
                                            value="{{ $tax_waiver }}">
                                    @endif --}}
                                    <div class="row">
                                        @if ($proposedPayoutChange && $proposedPayoutChange->getNewValue('tax_waiver'))
                                        <div class="col-6">
                                            <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('tax_waiver') ? 'color-modified-file' : '' }}"
                                                href="{{ static_asset($proposedPayoutChange->getNewValue('tax_waiver')) }}"
                                                target="_blank">{{ translate('View Tax Waiver Certificate') }}
                                            </a>
                                        </div>
                                        @endif

                                        @if (isset($user->business_information->tax_waiver) && !empty($user->business_information->tax_waiver))
                                        <div class="col-6">
                                            <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('tax_waiver') ? 'color-modified-file' : '' }}"
                                                href="{{ static_asset($user->business_information->tax_waiver) }}"
                                                target="_blank">@if ($user->status =="Enabled")
                                                     {{ translate('View Approved Tax Waiver Certificate') }}
                                                @else
                                                     {{ translate('View Tax Waiver Certificate') }}
                                                @endif
                                            </a>
                                        </div>
                                        @endif

                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Civil Defense Approval') }} <span
                                            class="text-primary"></span></label>
                                    {{-- <input  type="file" class="form-control rounded-0"
                                    name="civil_defense_approval"> --}}
                                    {{-- @if ($civil_defense_approval )
                                        <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('civil_defense_approval') ? 'color-modified-file' : '' }}"
                                            href="{{ static_asset($civil_defense_approval ) }}"
                                            target="_blank">{{ translate('View Civil Defense Approval') }}</a>
                                        <input type="hidden" name="civil_defense_approval"
                                            value="{{ $civil_defense_approval  }}">
                                    @endif
                                    <input type="file" class="form-control rounded-0"
                                        name="civil_defense_approval"> --}}
                                        <div class="row">
                                            @if ($proposedPayoutChange && $proposedPayoutChange->getNewValue('civil_defense_approval'))
                                            <div class="col-6">
                                                <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('civil_defense_approval') ? 'color-modified-file' : '' }}"
                                                    href="{{ static_asset($proposedPayoutChange->getNewValue('civil_defense_approval')) }}"
                                                    target="_blank">{{ translate('View Civil Defense Approval') }}
                                                </a>
                                            </div>
                                            @endif

                                            @if (isset($user->business_information->civil_defense_approval) && !empty($user->business_information->civil_defense_approval))
                                            <div class="col-6">
                                                <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('civil_defense_approval') ? 'color-modified-file' : '' }}"
                                                    href="{{ static_asset($user->business_information->civil_defense_approval) }}"
                                                    target="_blank">@if ($user->status =="Enabled")
                                                        {{ translate('View Approved Civil Defense Approval') }}
                                                    @else
                                                        {{ translate('View Civil Defense Approval') }}
                                                    @endif
                                                </a>
                                            </div>
                                            @endif

                                        </div>

                                </div>

                            </div>


                        </div>
                    </div>
                </div>


                <div class="text-right">


                    <button type="button" class="btn btn-primary fw-600 rounded-0"
                         onclick="switchTab('contact-person')">{{ translate('Next') }}</button>

                </div>
            </form>
            </div>
            <div class="tab-pane fade" id="contact-person">
                <form id="contactPersonForm" class=""
                    action="{{ route('shops.contact_person') }}" method="POST"
                    data-next-tab="warehouses">
                    @csrf
                    <!-- ... Contact Person form fields ... -->
                    <div class="bg-white border mb-4">
                        <div class="fs-15 fw-600 p-3">
                            {{ translate('Contact Person') }}
                        </div>
                        {{-- <div id="validation-errors" class="alert alert-danger"
                            style="display: none;"></div> --}}
                            <?php
                            // $proposedPayoutChange = App\Models\ProposedPayoutChange::where('user_id', $user->id)
                            //     ->latest()
                            //     ->first();
                            $first_name = $proposedPayoutChange && $proposedPayoutChange->getNewValue('first_name') ? $proposedPayoutChange->getNewValue('first_name') : $user->contact_people->first_name ?? '';
                            $last_name = $proposedPayoutChange && $proposedPayoutChange->getNewValue('last_name') ? $proposedPayoutChange->getNewValue('last_name') : $user->contact_people->last_name ?? '';
                            $email = $proposedPayoutChange && $proposedPayoutChange->getNewValue('email') ? $proposedPayoutChange->getNewValue('email') : $user->contact_people->email ?? '';
                            $mobile_phone = $proposedPayoutChange && $proposedPayoutChange->getNewValue('mobile_phone') ? $proposedPayoutChange->getNewValue('mobile_phone') : $user->contact_people->mobile_phone ?? '';
                            $additional_mobile_phone = $proposedPayoutChange && $proposedPayoutChange->getNewValue('additional_mobile_phone') ? $proposedPayoutChange->getNewValue('additional_mobile_phone') : $user->contact_people->additional_mobile_phone ?? '';
                            $nationality = $proposedPayoutChange && $proposedPayoutChange->getNewValue('nationality') ? $proposedPayoutChange->getNewValue('nationality') : $user->contact_people->nationality ?? '';
                            $date_of_birth = $proposedPayoutChange && $proposedPayoutChange->getNewValue('date_of_birth') ? $proposedPayoutChange->getNewValue('date_of_birth') : $user->contact_people->date_of_birth ?? '';
                            if (!empty($date_of_birth))
                                $date_of_birth = Carbon::createFromFormat('Y-m-d', $date_of_birth)->format('d M Y');

                            $emirates_id_number = $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_number') ? $proposedPayoutChange->getNewValue('emirates_id_number') : $user->contact_people->emirates_id_number ?? '';
                            $emirates_id_expiry_date = $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_expiry_date') ? $proposedPayoutChange->getNewValue('emirates_id_expiry_date') : $user->contact_people->emirates_id_expiry_date ?? '';
                            if (!empty($emirates_id_expiry_date))
                                $emirates_id_expiry_date = Carbon::createFromFormat('Y-m-d', $emirates_id_expiry_date)->format('d M Y')  ;
                            $emirates_id_file_path = $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_file_path') ? $proposedPayoutChange->getNewValue('emirates_id_file_path') : $user->contact_people->emirates_id_file_path ?? '';
                            $business_owner = $proposedPayoutChange && $proposedPayoutChange->getNewValue('business_owner') !=null ? $proposedPayoutChange->getNewValue('business_owner') : $user->contact_people->business_owner ?? 1;
                            $designation = $proposedPayoutChange && $proposedPayoutChange->getNewValue('designation') ? $proposedPayoutChange->getNewValue('designation') : $user->contact_people->designation ?? '';

                            ?>
                            <div class="p-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('First Name') }} <span class="text-primary">*</span></label>
                                            {{-- @php
                                                    $fistName = null;
                                                    if (isset($user->contact_people->first_name) && !empty($user->contact_people->first_name)) {
                                                        $fistName = $user->contact_people->first_name;
                                                    }

                                                @endphp --}}
                                            <input id="first_name_bi" type="text" title="{{$user->contact_people->first_name ?? ''}}"
                                                class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('first_name') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('First Name') }}" value="{{ $first_name }}"
                                                name="first_name" required>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Last Name') }} <span class="text-primary">*</span></label>
                                            {{-- @php
                                                    $lastName = null;
                                                    if (isset($user->contact_people->last_name) && !empty($user->contact_people->last_name)) {
                                                        $lastName = $user->contact_people->last_name;
                                                    }

                                                @endphp --}}
                                            <input type="text" title="{{$user->contact_people->last_name ?? ''}}"
                                                class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('last_name') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('Last Name') }}" id="last_name_bi"
                                                value="{{ $last_name }}" name="last_name" required>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Email') }} <span class="text-primary">*</span></label>
                                            {{-- @php
                                                    $emailUser = null;
                                                    if (isset($user->contact_people->email) && !empty($user->contact_people->email)) {
                                                        $emailUser = $user->contact_people->email;
                                                    }

                                                @endphp --}}
                                            <input type="email" title="{{$user->contact_people->email ?? ''}}"
                                                class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('email') ? 'color-modified' : '' }}"
                                                id="email_bi" placeholder="{{ translate('Email') }}"
                                                value="{{ $email }}" name="email" required>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Mobile Phone') }} <span
                                                    class="text-primary">*</span></label>


                                            <input type="text" dir="auto" title="{{$user->contact_people->mobile_phone ?? ''}}"
                                                class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('mobile_phone') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('Mobile Phone') }}" value="{{ $mobile_phone }}"
                                                name="mobile_phone" required>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Additional Mobile Phone') }} <span
                                                    class="text-primary"></span></label>
                                            <input type="text" dir="auto" title="{{$user->contact_people->additional_mobile_phone ?? ''}}"
                                                class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('additional_mobile_phone') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('Additional Mobile Phone') }}"
                                                value="{{ $additional_mobile_phone }}" name="additional_mobile_phone">

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Nationality') }} <span class="text-primary">*</span></label>
                                            <br>
                                            <select id="nationality" data-prevCountry='{{$user->contact_people->nationality ?? ''}}' title="{{ translate('Select Nationality') }}" name="nationality"
                                                class="form-control selectpicker countrypicker {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('nationality') ? 'color-modified' : '' }}"
                                                @if ($nationality) data-default="{{ $nationality }}" @else data-default="" @endif
                                                data-flag="true"></select>
                                                <input type="hidden" value="{{$nationality }}" id="nationalityHidden" name="nationality"> <!-- Hidden input for nationality -->

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Date Of Birth') }} <span
                                                    class="text-primary">*</span></label>
                                            <input title="{{$user->contact_people->date_of_birth ?? ''}}"  dir="auto" type="text" class="datepicker form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('date_of_birth') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('Date Of Birth') }}" {{-- value="{{ $user->contact_people->date_of_birth ?? '' }}" --}}
                                                value="{{ $date_of_birth }}"
                                                name="date_of_birth" required>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Emirates ID - Number') }} <span
                                                    class="text-primary">*</span></label> <small
                                                class="text-muted">{{ translate('Example') }}:123456789012345
                                            </small>
                                            <input  title="{{$user->contact_people->emirates_id_number ?? ''}}" type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_number') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('Emirates ID - Number') }}"
                                                value="{{  $emirates_id_number }}" required
                                                name="emirates_id_number">

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Emirates ID - Expiry Date') }} <span
                                                    class="text-primary">*</span></label>
                                            <input title="{{$user->contact_people->emirates_id_expiry_date ?? ''}}" dir="auto" type="text" class="datepicker form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_expiry_date') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('Emirates ID - Expiry Date') }}"
                                                {{-- value="{{ $user->contact_people->emirates_id_expiry_date ?? '' }}" --}}
                                                value="{{ $emirates_id_expiry_date }}"
                                                required name="emirates_id_expiry_date">

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Emirates ID') }} <span
                                                    class="text-primary">*</span><small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}
                                                </small></label>
                                            {{-- @if ($emirates_id_file_path)
                                                <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_file_path') ? 'color-modified-file' : '' }}"
                                                    href="{{ static_asset($emirates_id_file_path) }}"
                                                    target="_blank">{{ translate('View Emirates ID') }}</a>
                                                <input type="hidden" name="emirates_id_file_path"
                                                    value="{{ $emirates_id_file_path }}">
                                            @endif --}}
                                            <div class="row">
                                                @if ($proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_file_path'))
                                                <div class="col-6">
                                                    <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_file_path') ? 'color-modified-file' : '' }}"
                                                        href="{{ static_asset($proposedPayoutChange->getNewValue('emirates_id_file_path')) }}"
                                                        target="_blank">{{ translate('View Emirates ID') }}
                                                    </a>
                                                </div>
                                                @endif

                                                @if (isset($user->contact_people->emirates_id_file_path) && !empty($user->contact_people->emirates_id_file_path))
                                                <div class="col-6">
                                                    <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_file_path') ? 'color-modified-file' : '' }}"
                                                        href="{{ static_asset($user->contact_people->emirates_id_file_path) }}"
                                                        target="_blank">@if ($user->status =="Enabled")
                                                          {{ translate('View Approved Emirates ID') }}
                                                        @else
                                                          {{ translate('View Emirates ID') }}
                                                        @endif
                                                    </a>
                                                </div>
                                                @endif

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="{{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('business_owner') != null ? 'color-modified-file' : '' }}">{{ translate('Business Owner') }} <span class="text-primary">*
                                                </span></label> <br>
                                            <div class="form-check form-check-inline">

                                                <input @if (
                                                    ($business_owner == 1) || empty($business_owner)
                                                      ) checked @endif
                                                    class="form-check-input" type="radio" value="1"
                                                    id="" name="business_owner">
                                                <label class="form-check-label" for="">
                                                    {{ translate('Yes') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                    @if ($business_owner == 0) checked @endif value="0"
                                                    id="" name="business_owner">
                                                <label class="form-check-label" for="">
                                                    {{ translate('No') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ translate('Designation') }} <span class="text-primary">*</span></label>
                                            <input title="{{$user->contact_people->designation ?? ''}}" type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('designation') ? 'color-modified' : '' }}" required
                                                placeholder="{{ translate('Designation') }}"
                                                value="{{ $designation }}" name="designation">

                                        </div>
                                    </div>

                                </div>
                            </div>
                    </div>

                    <div class="text-right">
                        <!-- Previous Button -->
                        <button type="button" data-prv='business-info'
                            class="btn btn-info fw-600 rounded-0 prv-tab">
                            {{ translate('Previous') }}
                        </button>

                        <button type="button" class="btn btn-primary fw-600 rounded-0"
                            onclick="switchTab('warehouses')">{{ translate('Next') }}</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="warehouses">
                <form id="warehousesForm" class="" action="{{ route('shops.warehouses') }}"
                    data-next-tab="payout-info" method="POST">
                    @csrf
                    <!-- ... Warehouses form fields ... -->
                    <div class="bg-white border mb-4">
                        <div class="fs-15 fw-600 p-3">
                            {{ translate('Warehouses') }}
                        </div>
                        {{-- <div id="validation-errors" class="alert alert-danger"
                            style="display: none;"></div> --}}

                        <div class="p-3">


                            <table class="table mt-3" id="warehouseTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>{{ translate('Warehouse Name') }}</th>
                                        <th>{{ translate('State/Emirate') }}</th>
                                        <th>{{ translate('Area') }}</th>
                                        <th>{{ translate('Street') }}</th>
                                        <th>{{ translate('Building') }}</th>
                                        <th>{{ translate('Unit/Office No.') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($user))
                                        @foreach ($user->warehouses as $warehouse)
                                            <tr class="warehouseRow">
                                                <td><input value="{{ $warehouse->warehouse_name }}"
                                                        type="text" class="form-control"
                                                        name="warehouse_name[]" required></td>
                                                <td>

                                                    <select required name="state_warehouse[]" class="form-control rounded-0 emirateSelect" id="emirateempire">
                                                        <option value="" selected>{{ translate('please_choose') }}</option>
                                                        @foreach ($emirates as $emirate)
                                                            <option value="{{ $emirate->id }}" @if ($warehouse->emirate_id == $emirate->id) selected @endif>
                                                                {{ $emirate->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </td>
                                                <td>
                                                    <select class="form-control areaSelect"
                                                        name="area_warehouse[]" required>
                                                        @php
                                                            $areas = App\Models\Area::where('emirate_id', $warehouse->emirate_id)->get();
                                                        @endphp
                                                        <option value="" selected>
                                                            {{ translate('please_choose') }}</option>
                                                        @foreach ($areas as $area)
                                                            <option value="{{ $area->id }}"
                                                                @if ($area->id == $warehouse->area_id) selected @endif>
                                                                {{ $area->name }}</option>
                                                        @endforeach

                                                        <!-- Options for area -->
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $warehouse->address_street }}"
                                                        name="street_warehouse[]" required></td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $warehouse->address_building }}"
                                                        name="building_warehouse[]" required></td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $warehouse->address_unit }}"
                                                        name="unit_warehouse[]" required></td>

                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>


                            {{-- <div id="warehouseRowsContainer">
                                @if (isset($user))
                                    @foreach ($user->warehouses as $warehouse)
                                        <div class="row warehouseRow" id="warehouseRows">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="warehouse_name">Warehouse Name<span
                                                            class="text-primary">*</span></label>
                                                    <input value="{{ $warehouse->warehouse_name }}"
                                                        type="text" class="form-control"
                                                        name="warehouse_name[]" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="state">State/Emirate<span
                                                            class="text-primary">*</span></label>
                                                    <select required name="state_warehouse[]"
                                                        class="form-control rounded-0 emirateSelect"
                                                        id="emirateempire">
                                                        <option value="" selected>Please Choose
                                                            !!</option>
                                                        <option
                                                            @if ($warehouse->emirate_id == 1) selected @endif
                                                            value="1">Abu dhabi</option>
                                                        <option
                                                            @if ($warehouse->emirate_id == 2) selected @endif
                                                            value="2">Ajman</option>
                                                        <option
                                                            @if ($warehouse->emirate_id == 3) selected @endif
                                                            value="3">Sharjah</option>
                                                        <option
                                                            @if ($warehouse->emirate_id == 4) selected @endif
                                                            value="4">Dubai</option>
                                                        <option
                                                            @if ($warehouse->emirate_id == 5) selected @endif
                                                            value="5">Fujairah</option>
                                                        <option
                                                            @if ($warehouse->emirate_id == 6) selected @endif
                                                            value="6">ras al khaimah</option>
                                                        <option
                                                            @if ($warehouse->emirate_id == 7) selected @endif
                                                            value="7">Umm Al-Quwain</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="area">Area<span
                                                            class="text-primary">*</span></label>
                                                    <select class="form-control areaSelect"
                                                        name="area_warehouse[]" required>
                                                        @php
                                                            $areas = App\Models\Area::where('emirate_id', $warehouse->emirate_id)->get();
                                                        @endphp
                                                        <option value="" selected>Please Choose
                                                            !!</option>
                                                        @foreach ($areas as $area)
                                                            <option value="{{ $area->id }}"
                                                                @if ($area->id == $warehouse->area_id) selected @endif>
                                                                {{ $area->name }}</option>
                                                        @endforeach

                                                        <!-- Options for area -->
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="street">Street<span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $warehouse->address_street }}"
                                                        name="street_warehouse[]" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="building">Building<span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $warehouse->address_building }}"
                                                        name="building_warehouse[]" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="unit">Unit/Office No.<span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $warehouse->address_unit }}"
                                                        name="unit_warehouse[]" required>
                                                </div>
                                            </div>


                                            <div class="col-auto ml-auto">
                                                <button type="button"
                                                    class="btn btn-outline-danger removeRow">
                                                    Remove Warehouse <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                @if (!isset($user->warehouses) || (isset($user->warehouses) && count($user->warehouses) == 0))
                                    <div class="row warehouseRow" id="warehouseRows">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="warehouse_name">Warehouse Name<span
                                                        class="text-primary">*</span></label>
                                                <input type="text" class="form-control"
                                                    name="warehouse_name[]" required>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="state">State/Emirate<span
                                                        class="text-primary">*</span></label>
                                                <select required name="state_warehouse[]"
                                                    class="form-control rounded-0 emirateSelect"
                                                    id="emirateempire">
                                                    <option value="" selected>Please Choose !!
                                                    </option>
                                                    <option value="1">Abu dhabi</option>
                                                    <option value="2">Ajman</option>
                                                    <option value="3">Sharjah</option>
                                                    <option value="4">Dubai</option>
                                                    <option value="5">Fujairah</option>
                                                    <option value="6">ras al khaimah</option>
                                                    <option value="7">Umm Al-Quwain</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="area">Area<span
                                                        class="text-primary">*</span></label>
                                                <select class="form-control areaSelect" name="area_warehouse[]"
                                                    required>
                                                    <option value="" selected>Please Choose !!
                                                    </option>
                                                    <!-- Options for area -->
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="street">Street<span
                                                        class="text-primary">*</span></label>
                                                <input type="text" class="form-control"
                                                    name="street_warehouse[]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="building">Building<span
                                                        class="text-primary">*</span></label>
                                                <input type="text" class="form-control"
                                                    name="building_warehouse[]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="unit">Unit/Office No.<span
                                                        class="text-primary">*</span></label>
                                                <input type="text" class="form-control"
                                                    name="unit_warehouse[]" required>
                                            </div>
                                        </div>


                                        <div class="col-auto ml-auto">
                                            <button type="button"
                                                class="btn btn-outline-danger removeRow">
                                                Remove Warehouse <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="row">
                                <div class="col-auto mx-auto text-center">
                                    <button type="button" class="btn btn-primary addWarehouse"
                                        id="addRow">Add
                                        Warehouse</button>
                                </div>
                            </div> --}}
                        </div>
                    </div>


                    <div class="text-right">
                        <!-- Previous Button -->
                        <button type="button" data-prv='contact-person'
                            class="btn btn-info fw-600 rounded-0 prv-tab">
                            {{ translate('Previous') }}
                        </button>



                        <button type="button" class="btn btn-primary fw-600 rounded-0"
                             onclick="switchTab('payout-info')">{{ translate('Next') }}</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="payout-info">
                <form id="payoutInfoForm" class="" action="{{ route('shops.payout_info') }}"
                    data-next-tab="payout-info" method="POST">
                    @csrf
                    <!-- ... Payout Info form fields ... -->
                    <div class="bg-white border mb-4">
                        <div class="fs-15 fw-600 p-3">
                            {{ translate('Payout Information') }}
                        </div>
                        {{-- <div id="validation-errors" class="alert alert-danger"
                            style="display: none;"></div> --}}
                            <?php
                            // $proposedPayoutChange = App\Models\ProposedPayoutChange::where('user_id', $user->id)
                            // ->latest()
                            // ->first();

                            $bankName = $proposedPayoutChange && $proposedPayoutChange->getNewValue('bank_name') ? $proposedPayoutChange->getNewValue('bank_name') : $user->payout_information->bank_name ?? '';
                            $accountName = $proposedPayoutChange && $proposedPayoutChange->getNewValue('account_name') ? $proposedPayoutChange->getNewValue('account_name') : $user->payout_information->account_name ?? '';
                            $accountNumber = $proposedPayoutChange && $proposedPayoutChange->getNewValue('account_number') ? $proposedPayoutChange->getNewValue('account_number') : $user->payout_information->account_number ?? '';
                            $iban = $proposedPayoutChange && $proposedPayoutChange->getNewValue('iban') ? $proposedPayoutChange->getNewValue('iban') : $user->payout_information->iban ?? '';
                            $swiftCode = $proposedPayoutChange && $proposedPayoutChange->getNewValue('swift_code') ? $proposedPayoutChange->getNewValue('swift_code') : $user->payout_information->swift_code ?? '';

                            ?>
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Bank Name') }} <span class="text-primary">*</span></label>
                                        <input value="{{ $bankName }}" type="text"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('bank_name') ? 'color-modified' : '' }}"
                                            title="{{ $user->payout_information->bank_name ?? ''}}" placeholder="{{ translate('Bank Name') }}" name="bank_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Account Name') }} <span
                                                class="text-primary">*</span></label>
                                        <input value="{{ $accountName }}" type="text" title="{{ $user->payout_information->account_name ?? ''}}"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('account_name') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Account Name') }}" name="account_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Account Number') }} <span
                                                class="text-primary">*</span></label>
                                        <input value="{{ $accountNumber }}" type="text" title="{{ $user->payout_information->account_number ?? ''}}"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('account_number') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Account Number') }}" name="account_number"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('IBAN') }} <span class="text-primary">*</span></label>
                                        <input value="{{ $iban }}" type="text" title="{{ $user->payout_information->iban ?? ''}}"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('iban') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('IBAN') }}" name="iban" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Swift Code') }} <span class="text-primary">*</span></label>
                                        <input value="{{ $swiftCode }}" type="text" title="{{ $user->payout_information->swift_code ?? ''}}"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('swift_code') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Swift Code') }}" name="swift_code" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('IBAN Certificate') }}<span
                                                class="text-primary">*</span></label>

                                        <div class="row">
                                            @if ($proposedPayoutChange && $proposedPayoutChange->getNewValue('iban_certificate'))
                                            <div class="col-6">
                                                <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('iban_certificate') ? 'color-modified-file' : '' }}"
                                                    href="{{ static_asset($proposedPayoutChange->getNewValue('iban_certificate')) }}"
                                                    target="_blank">   {{ __('messages.view_iban_certificate') }}
                                                </a>
                                            </div>
                                            @endif

                                            @if (isset($user->payout_information->iban_certificate) && !empty($user->payout_information->iban_certificate))
                                            <div class="col-6">
                                                <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('iban_certificate') ? 'color-modified-file' : '' }}"
                                                    href="{{ static_asset($user->payout_information->iban_certificate) }}"
                                                    target="_blank">@if ($user->status =="Enabled")
                                                        {{ __('messages.view_approved_iban_certificate') }}
                                                    @else
                                                    {{ __('messages.view_iban_certificate') }}
                                                    @endif
                                                </a>
                                            </div>
                                            @endif

                                        </div>




                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-right">
                        <!-- Previous Button -->
                        <button type="button" data-prv='warehouses'
                            class="btn btn-info fw-600 rounded-0 prv-tab">
                            {{ translate('Previous') }}
                        </button>

                        @if ($proposedPayoutChange)
                        <!-- Approve Changes Button -->
                        <a  href="{{route('approve-changes', $proposedPayoutChange->id)}}" type="button" class="btn btn-success" id="approveChangesBtn">Approve Changes</a>
                        <!-- Reject Changes Button -->
                        <a  href="{{route('reject.seller.registration', [$proposedPayoutChange->user_id,$proposedPayoutChange->id] )}}" type="button" class="btn btn-danger" id="rejectChangesBtn">Reject Changes</a>

                        @endif

                        @if ($user->status == "Pending Approval" || $user->status == "Suspended" || $user->status =="Pending Closure")
                        <!-- Approve Button -->
                        <a href="{{route('vendors.approve.registration', $user->id)}}" name="action" value="approve" class="btn btn-success fw-600 rounded-0">
                           @if($user->status == "Suspended") {{ __('messages.Unsuspended') }} @else {{ __('messages.approve') }} @endif

                        </a>
                        @endif
                        <!-- Reject Button -->
                        {{-- <button id="rejectButton" type="button" name="action" value="reject" class="btn btn-danger fw-600 rounded-0">
                            {{ translate('Reject') }}
                        </button> --}}
                        @if ($user->status == "Pending Approval" )
                        <a href="{{ route('reject.seller.registration', $user->id) }}" class="btn btn-danger fw-600 rounded-0">
                            {{ translate('Reject') }}
                        </a>

                        @endif

                    </div>
                </form>
            </div>
        </div>
        <!-- Bootstrap Modal Dialog for Reject -->
        {{-- <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form enctype="multipart/form-data"  method="POST" action="{{ route('resubmit.registration', $user->id) }}">
                    @csrf
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <textarea name="reject_reason" id="editor"></textarea>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitRejection" class="btn btn-danger">Submit</button>
                    </div>
                </div>
                </form>
            </div>
        </div> --}}

    </div>
</div>
@endsection
@section('script')
<link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-select-country.min.css') }}">
<script src="{{ static_asset('assets/js/bootstrap-select-country.min.js') }}"></script>
<script src={{static_asset('assets/js/editor/summernote/summernote.js')}}></script>
<script src={{static_asset('assets/js/editor/summernote/summernote.custom.js')}}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {

       isVatRegistered = $('input[name="vat_registered"]:checked').val();

        if (isVatRegistered == 1) {
            // If VAT is registered
            $('#vatCertificateGroup').show();
            $('#trnGroup').show();
            $('#taxWaiverGroup').hide();
            $('#vatCertificate').prop('required', true);
            $('#trn').prop('required', true);
            $('#taxWaiver').prop('required', false);
        } else {

            // If VAT is not registered
            $('#vatCertificateGroup').hide();
            $('#trnGroup').hide();
            $('#taxWaiverGroup').show();
            $('#vatCertificate').prop('required', false);
            $('#trn').prop('required', false);
            $('#taxWaiver').prop('required', true);
        }
        $('.prv-tab').on('click', function() {
                switchTab($(this).data('prv'));
                var form = $(this).closest('form');
                $(form).find('.is-invalid').first().focus();

            });
        // When the admin clicks the "Reject" button, show the modal
$('#rejectButton').click(function() {
    $('#rejectModal').modal('show');
});



// When the admin clicks the "Submit" button in the modal
// $('#submitRejection').click(function() {
//     var rejectionReasons = CKEditor.instances.rejectionReasonsEditor.getData();
//     submitRejection(rejectionReasons);
//     $('#rejectModal').modal('hide'); // Close the modal after submission
// });

// Function to handle submission actions
function submitRejection(rejectionReasons) {
    // Perform submission actions, e.g., send email notification with rejection reasons
}
   // Check if select element has class 'color-modified' on page load
   // Check if the '#nationality' element has the class 'color-modified'
if ($('#nationality').hasClass('color-modified')) {
    // Add the 'color-modified' class to the button with data-id="nationality"
    $('.btn[data-id="nationality"]').addClass('color-modified');
}
// $('.btn[data-id="nationality').on('change', function() {

//         var selectedCountry = $(this).val();
//         $(this).attr('title', selectedCountry);
//     });

    // Update the title attribute of #nationality on hover
    $('.btn[data-id="nationality').on('mouseenter', function() {

        var selectedCountry = $('#nationality').data('prevcountry');
        $(this).attr('title', selectedCountry);
    });


    })
</script>
<script>
       function switchTab(tabId) {

            $('#registerTabs').find('.nav-link').removeClass('active');
            $('#registerTabsContent').find('.tab-pane').removeClass('show active');

            $('#' + tabId + '-tab').addClass('active');
            $('#' + tabId).addClass('show active');

            // Focus on the first input with 'is-invalid' class within the active tab
            $('#' + tabId).find('.is-invalid').first().focus();
        }
        $('input, textarea').prop('readonly', true);
        // Disable select elements
        $('select').prop('disabled', true);
        // Disable radio buttons
        $('input[type="radio"]').prop('disabled', true);
        $('#editor').summernote({
            placeholder: 'Type your text here...',
                        height: 300,
                        callbacks: {
                onImageUpload: function(files) {
                    uploadImage(files[0]);
                },
                onMediaDelete : function(image) {
                    deleteImage(image[0].src);
                }
            }

        })
        function uploadImage(file) {
            var formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{route("upload.image")}}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#editor').summernote('editor.insertImage', response.imageUrl);
                },
                error: function(xhr, status, error) {
                    // Display validation errors using Toastr
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error("An error occurred while uploading the image.");
                    }
                }
            });
        }
        function deleteImage(imageSrc) {

            $.ajax({
                url: '{{ route("delete.image") }}',
                method: 'DELETE',
                data: { src: imageSrc },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Image deleted successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to delete image:', error);
                }
            });
        }

</script>



@endsection
