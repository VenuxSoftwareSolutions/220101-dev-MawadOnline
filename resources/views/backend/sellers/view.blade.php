@extends('backend.layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{static_asset('assets/css/summernote.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

@endsection
@php
use Carbon\Carbon;
@endphp
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">Vendor Registration View</h1>

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

                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('English Trade Name') }}</label>
                                    <input type="text" class="form-control rounded-0"

                                        value="{{ isset($user->business_information->trade_name) ? $user->business_information->getTranslation('trade_name', 'en', false) : '' }}"
                                        name="trade_name_english" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Arabic Trade Name') }}</label>
                                    <input type="text" class="form-control rounded-0"

                                        value="{{ isset($user->business_information->trade_name) ? $user->business_information->getTranslation('trade_name', 'ar', false) : '' }}"
                                        name="trade_name_arabic" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Trade License Doc') }}</label>

                                            <div>
                                                @if (isset($user) && isset($user->business_information) && $user->business_information->trade_license_doc)
                                                <a class="old_file"
                                                    href="{{ static_asset($user->business_information->trade_license_doc) }}"
                                                    target="_blank">{{ translate('View Trade License Doc') }}</a>
                                                <input type="hidden" name="trade_license_doc_old"
                                                    value="{{ $user->business_information->trade_license_doc }}">
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
                                    <label>{{ translate('English E-shop Name') }}</label>
                                    <input type="text" class="form-control rounded-0"

                                        value="{{ isset($user->business_information->eshop_name) ? $user->business_information->getTranslation('eshop_name', 'en', false) : '' }}"
                                        name="eshop_name_english" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Arabic E-shop Name') }}</label>
                                    <input type="text" class="form-control rounded-0"

                                        value="{{ isset($user->business_information->eshop_name) ? $user->business_information->getTranslation('eshop_name', 'ar', false) : '' }}"
                                        name="eshop_name_arabic" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('English e-Shop description') }} <span
                                            class="text-primary"></span></label>

                                    <textarea class="form-control rounded-0"
                                        name="eshop_desc_en">{{ isset($user->business_information->eshop_desc) ? $user->business_information->getTranslation('eshop_desc', 'en', false) : '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Arabic e-Shop description') }} <span
                                            class="text-primary"></span></label>

                                    <textarea class="form-control rounded-0"
                                        name="eshop_desc_ar">{{ isset($user->business_information->eshop_desc) ? $user->business_information->getTranslation('eshop_desc', 'ar', false) : '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('License Issue Date') }}
                                    </label>

                                    <input dir="auto" required type="{{-- date --}}text" class="datepicker form-control rounded-0"

                                        id="license_issue_date"
                                        value="{{ isset($user->business_information->license_issue_date) ? Carbon::createFromFormat('Y-m-d', $user->business_information->license_issue_date)->format('d M Y') : '' }}"
                                        name="license_issue_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('License Expiry Date') }}</label>

                                    <input dir="auto" required type="text" class="datepicker form-control rounded-0"
                                        {{-- value="{{ $user->business_information->license_expiry_date ?? '' }}" --}}
                                        value="{{ isset($user->business_information->license_expiry_date) ? Carbon::createFromFormat('Y-m-d', $user->business_information->license_expiry_date)->format('d M Y') : '' }}"

                                        name="license_expiry_date">
                                </div>
                            </div>
                            @if (isset($user->business_information) && !empty($user->business_information->state))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('State/Emirate') }}</label>
                                            <select required name="state" class="form-control rounded-0" id="emirateempire">
                                                <option value="">{{ translate('please_choose') }}</option>
                                                @foreach ($emirates as $emirate)
                                                    <option value="{{ $emirate->id }}" @if (isset($user) && $user->business_information->state == $emirate->id) selected @endif>
                                                        {{ $emirate->name }}
                                                    </option>
                                                @endforeach
                                            </select>


                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Area') }}</label>
                                    <select required name="area_id"
                                        class="form-control rounded-0" id="areaempire">
                                        @php
                                            $areas = App\Models\Area::where('emirate_id', $user->business_information->state)->get();
                                        @endphp
                                        <option value="" selected>{{ translate('please_choose') }}</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}"
                                                @if ($area->id == $user->business_information->area_id) selected @endif>
                                                {{ $area->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('State/Emirate') }}</label>
                                            <select required name="state" class="form-control rounded-0" id="emirateempire">
                                                <option value="" selected>{{ translate('please_choose') }}</option>
                                                @foreach ($emirates as $emirate)
                                                    <option value="{{ $emirate->id }}">{{ $emirate->name }}</option>
                                                @endforeach
                                            </select>


                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Area') }}</label>
                                    <select required name="area_id"
                                        class="form-control rounded-0" id="areaempire">
                                        <option value="" selected>
                                            {{ translate('please_choose') }}
                                        </option>


                                    </select>
                                </div>
                            </div>
                        @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Street') }}</label>
                                    <input type="text" class="form-control rounded-0"
                                        value="{{ $user->business_information->street ?? '' }}"
                                        name="street"
                                        required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Building') }}</label>
                                    <input type="text" class="form-control rounded-0"
                                        value="{{ $user->business_information->building ?? '' }}"
                                         name="building"
                                        required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Unit/Office No.') }} <span
                                            class="text-primary"></span></label>
                                    <input type="text" class="form-control rounded-0"
                                        value="{{ $user->business_information->unit ?? '' }}"

                                        name="unit">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('PO Box') }} <span
                                            class="text-primary"></span></label>
                                    <input type="text" class="form-control rounded-0"
                                        value="{{ $user->business_information->po_box ?? '' }}"
                                         name="po_box">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Landline Phone No.') }} <span
                                            class="text-primary"></span></label>
                                    <input
                                        value="{{ $user->business_information->landline ?? '' }}"
                                        type="text" class="form-control rounded-0"

                                        name="landline">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ translate('Vat Registered') }} <span
                                            class="text-primary">*
                                        </span></label> <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            value="1" id="vatRegisteredYes"
                                            name="vat_registered"
                                            @if (
                                                (isset($user) && isset($user->business_information) && $user->business_information->vat_registered == 1) ||
                                                    !isset($user->business_information->vat_registered)) checked @endif>
                                        <label class="form-check-label" for="vatRegisteredYes">
                                            {{ translate('Yes') }}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            value="0" id="vatRegisteredNo"
                                            @if (isset($user) && isset($user->business_information) && $user->business_information->vat_registered == 0) checked @endif
                                            name="vat_registered">
                                        <label class="form-check-label" for="vatRegisteredNo">
                                            {{ translate('No') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="vatCertificateGroup">
                                <div class="form-group">
                                    <label>{{ translate('Vat Certificate') }}</label>
                                    <div>
                                        @if (isset($user) && isset($user->business_information) && $user->business_information->vat_certificate)
                                        <a class="old_file"
                                            href="{{ static_asset($user->business_information->vat_certificate) }}"
                                            target="_blank">{{ translate('View Vat Certificate') }}</a>
                                        <input type="hidden" name="vat_certificate_old"
                                            value="{{ $user->business_information->vat_certificate }}">
                                        @endif

                                    </div>


                                </div>
                            </div>
                            <div class="col-md-6" id="trnGroup">
                                <div class="form-group">
                                    <label>{{ translate('TRN') }}</label>
                                    <input value="{{ $user->business_information->trn ?? '' }}"
                                        type="text" class="form-control rounded-0"
                                         name="trn">
                                </div>
                            </div>
                            <div class="col-md-6" id="taxWaiverGroup" {{-- style="display: none;" --}}>
                                <div class="form-group">
                                    <label>{{ translate('Tax Waiver Certificate') }}</label>
                                    <div>
                                        @if (isset($user) && isset($user->business_information) && $user->business_information->tax_waiver)
                                        <a class="old_file"
                                            href="{{ static_asset($user->business_information->tax_waiver) }}"
                                            target="_blank">{{ translate('View Tax Waiver Certificate') }}</a>
                                        <input type="hidden" name="tax_waiver_old"
                                            value="{{ $user->business_information->tax_waiver }}">
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
                                    <div>
                                        @if (isset($user) && isset($user->business_information) && $user->business_information->civil_defense_approval)
                                        <a class="old_file"
                                            href="{{ static_asset($user->business_information->civil_defense_approval) }}"
                                            target="_blank">{{ translate('View Civil Defense Approval') }}</a>
                                        <input type="hidden" name="civil_defense_approval_old"
                                            value="{{ $user->business_information->civil_defense_approval }}">
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

                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('First Name') }} </label>
                                        @php
                                            $fistName = null;
                                            if (isset($user->contact_people->first_name) && !empty($user->contact_people->first_name)) {
                                                $fistName = $user->contact_people->first_name;
                                            } elseif (isset($user->first_name)) {
                                                $fistName = $user->first_name;
                                                // dd($user->first_name) ;
                                            }

                                        @endphp
                                        <input id="first_name_bi" type="text"
                                            class="form-control rounded-0"

                                            value="{{ $fistName }}" name="first_name" required>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Last Name') }} </label>
                                        @php
                                            $lastName = null;
                                            if (isset($user->contact_people->last_name) && !empty($user->contact_people->last_name)) {
                                                $lastName = $user->contact_people->last_name;
                                            } elseif (isset($user->last_name)) {
                                                $lastName = $user->last_name;
                                            }

                                        @endphp
                                        <input type="text" class="form-control rounded-0"

                                            id="last_name_bi" value="{{ $lastName }}"
                                            name="last_name" required>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Email') }} </label>
                                        @php
                                            $emailUser = null;
                                            if (isset($user->contact_people->email) && !empty($user->contact_people->email)) {
                                                $emailUser = $user->contact_people->email;
                                            } elseif (isset($user->email)) {
                                                $emailUser = $user->email;
                                            }

                                        @endphp
                                        <input type="email" class="form-control rounded-0"
                                            id="email_bi"
                                            value="{{ $emailUser }}" name="email" required>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Mobile Phone') }} </label>

                                        <input type="text" dir="auto" class="form-control rounded-0"

                                            value="{{ $user->contact_people->mobile_phone ?? '+971' }}"
                                            name="mobile_phone" required>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Additional Mobile Phone') }} <span
                                                class="text-primary"></span></label>
                                        <input type="text" dir="auto" class="form-control rounded-0"

                                            value="{{ $user->contact_people->additional_mobile_phone ?? '+971' }}"
                                            name="additional_mobile_phone">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Nationality') }} </label>
                                        <br>
                                        <select  title="{{ translate('Select Nationality') }}"
                                            name="nationality" class="form-control selectpicker countrypicker"
                                            @if (isset($user->contact_people) && !empty($user->contact_people->nationality)) data-default="{{ $user->contact_people->nationality }}" @else data-default="" @endif
                                            data-flag="true"></select>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Date Of Birth') }} </label>
                                        <input  dir="auto" type="text" class="datepicker form-control rounded-0"

                                            {{-- value="{{ $user->contact_people->date_of_birth ?? '' }}" --}}
                                            value="{{ isset($user->contact_people->date_of_birth) ? Carbon::createFromFormat('Y-m-d', $user->contact_people->date_of_birth)->format('d M Y') : '' }}"

                                            name="date_of_birth" required>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Emirates ID - Number') }} </label>
                                        <input type="text" class="form-control rounded-0"

                                            value="{{ $user->contact_people->emirates_id_number ?? '' }}"
                                            required name="emirates_id_number">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Emirates ID - Expiry Date') }} </label>
                                        <input dir="auto" type="text" class="datepicker form-control rounded-0"

                                            {{-- value="{{ $user->contact_people->emirates_id_expiry_date ?? '' }}" --}}
                                            value="{{ isset($user->contact_people->emirates_id_expiry_date) ? Carbon::createFromFormat('Y-m-d', $user->contact_people->emirates_id_expiry_date)->format('d M Y') : '' }}"

                                            required name="emirates_id_expiry_date">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Emirates ID') }}</label>
                                        <div>
                                            @if (isset($user) && isset($user->contact_people) && $user->contact_people->emirates_id_file_path)
                                            <a class="old_file"
                                                href="{{ static_asset($user->contact_people->emirates_id_file_path) }}"
                                                target="_blank">{{ translate('View Emirates ID') }}</a>
                                            <input type="hidden" name="emirates_id_file_old"
                                                value="{{ $user->contact_people->emirates_id_file_path }}">
                                            @endif
                                        </div>



                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Business Owner') }} <span
                                                class="text-primary">*
                                            </span></label> <br>
                                        <div class="form-check form-check-inline">
                                            <input @if (
                                                (isset($user->contact_people->business_owner) && $user->contact_people->business_owner == 1) ||
                                                    !isset($user->contact_people)) checked @endif
                                                class="form-check-input" type="radio"
                                                value="1" id="vatRegisteredYes"
                                                name="business_owner">
                                            <label class="form-check-label" for="vatRegisteredYes">
                                                {{ translate('Yes') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                @if (isset($user->contact_people->business_owner) && $user->contact_people->business_owner == 0) checked @endif
                                                value="0" id="vatRegisteredNo"
                                                name="business_owner">
                                            <label class="form-check-label" for="vatRegisteredNo">
                                                {{ translate('No') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Designation') }} </label>
                                        <input type="text" class="form-control rounded-0" required

                                            value="{{ $user->contact_people->designation ?? '' }}"
                                            name="designation">

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

                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Bank Name') }} </label>
                                        <input
                                            value="{{ $user->payout_information->bank_name ?? '' }}"
                                            type="text" class="form-control rounded-0"

                                            name="bank_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Account Name') }} </label>
                                        <input
                                            value="{{ $user->payout_information->account_name ?? '' }}"
                                            type="text" class="form-control rounded-0"

                                            name="account_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Account Number') }} </label>
                                        <input
                                            value="{{ $user->payout_information->account_number ?? '' }}"
                                            type="text" class="form-control rounded-0"

                                            name="account_number" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('IBAN') }} </label>
                                        <input value="{{ $user->payout_information->iban ?? '' }}"
                                            type="text" class="form-control rounded-0"
                                            name="iban"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('Swift Code') }} </label>
                                        <input
                                            value="{{ $user->payout_information->swift_code ?? '' }}"
                                            type="text" class="form-control rounded-0"

                                            name="swift_code" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ translate('IBAN Certificate') }}</label>
                                        <div>
                                            @if (isset($user) && isset($user->payout_information) && $user->payout_information->iban_certificate)
                                            <a class="old_file"
                                                href="{{ static_asset($user->payout_information->iban_certificate) }}"
                                                target="_blank">{{ translate('View IBAN Certificate') }}</a>
                                            <input type="hidden" name="iban_certificate_old"
                                                value="{{ $user->payout_information->iban_certificate }}">
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
                        @if ($user->status != "Draft" && $user->status != "Enabled" )
                        <!-- Approve Button -->
                        <a href="{{route('vendors.approve.registration', $user->id)}}" name="action" value="approve" class="btn btn-success fw-600 rounded-0">
                            {{ translate('Approve') }}
                        </a>
                        @endif
                        <!-- Reject Button -->
                        {{-- <button id="rejectButton" type="button" name="action" value="reject" class="btn btn-danger fw-600 rounded-0">
                            {{ translate('Reject') }}
                        </button> --}}
                        @if ($user->status != "Draft" && $user->status != "Rejected" )
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
