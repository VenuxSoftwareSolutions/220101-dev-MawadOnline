@extends('frontend.layouts.app')
@php
use Carbon\Carbon;
@endphp

@section('style')
    <style>
        #password-strength {
            margin-top: 10px;
            /* padding: 10px; */
            /* border: 1px solid #ddd; */
            border-radius: 5px;
        }

        #password-strength p {
            margin: 5px 0;
        }

        #password-strength.valid {
            border-color: #4caf50;
            background-color: #dff0d8;
        }

        .removeRow {
            margin-bottom: 8px;
        }

        .has-errors {
            background-color: #f8d7da;
            /* Highlight color for errors */
            color: #721c24;
            /* Text color for errors */
        }
        .countrypicker {
            width: 100% !important
        }
    </style>
@endsection
@section('content')
    <section class="pt-4 mb-4">
        <!-- ... Existing HTML code ... -->
    </section>

    <section class="pt-4 mb-4">
        <div class="container">
            <div class="row">
                <div class="mx-auto">
                    <h1 class="fw-700 fs-20 fs-md-24 text-dark text-center mb-3">{{ translate('Register Your Shop') }}</h1>

                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="registerTabs">
                                @if (!Auth::check() || (Auth::check() && !Auth::user()->email_verified_at))
                                    <li class="nav-item">
                                        <a class="nav-link active" id="personal-info-tab" data-toggle="tab"
                                            href="#personal-info">{{ translate('Personal Info') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="code-verification-tab" data-toggle="tab"
                                            href="#code-verification">{{ translate('Code Verification Email') }}</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" id="business-info-tab" data-toggle="tab"
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
                                @if (!Auth::check() || (Auth::check() && !Auth::user()->email_verified_at))
                                    <div class="tab-pane fade show active" id="personal-info">

                                        <form id="shop" class="" action="{{ route('shops.store') }}"
                                            method="POST" enctype="multipart/form-data" data-next-tab="code-verification">
                                            @csrf
                                            <!-- ... Personal Info form fields ... -->
                                            <div class="bg-white border mb-4">
                                                <div class="fs-15 fw-600 p-3">
                                                    {{ translate('Personal Info') }}
                                                </div>
                                                {{-- <div id="validation-errors" class="alert alert-danger" style="display: none;">
                                            </div> --}}

                                                <div class="p-3">
                                                    <div class="form-group">
                                                        <label>{{ translate('First Name') }} <span
                                                                class="text-primary">*</span></label>
                                                        <input type="text" class="form-control rounded-0"
                                                            value="{{ old('name') }}" id="first_name"
                                                            placeholder="{{ translate('First Name') }}" name="first_name"
                                                            required>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ translate('Last name') }} <span
                                                                class="text-primary">*</span></label>
                                                        <input type="text" class="form-control rounded-0" id="last_name"
                                                            placeholder="{{ translate('Last name') }}" name="last_name"
                                                            required>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ translate('Your Email') }} <span
                                                                class="text-primary">*</span></label>
                                                        <input id="email" type="email" class="form-control rounded-0"
                                                            value="{{ $user->email ?? '' }}"
                                                            placeholder="{{ translate('Email') }}" name="email" required>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ translate('Your Password') }} <span
                                                                class="text-primary">*</span></label>
                                                        <input id="password" type="password" class="form-control rounded-0"
                                                            value="{{ old('password') }}"
                                                            placeholder="{{ translate('Password') }}" name="password"
                                                            required>
                                                        <div id="password-strength"></div>


                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ translate('Repeat Password') }} <span
                                                                class="text-primary">*</span></label>
                                                        <input id="password_confirmation" type="password"
                                                            class="form-control rounded-0"
                                                            placeholder="{{ translate('Confirm Password') }}"
                                                            name="password_confirmation" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                    {{-- onclick="switchTab('code-verification')" --}}>{{ translate('Next') }}</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="code-verification">
                                        <form id="codeVerificationForm" class="" action="{{ route('verify.code') }}"
                                            method="POST" data-next-tab="business-info">
                                            @csrf
                                            <div class="bg-white border mb-4">
                                                <div class="fs-15 fw-600 p-3">
                                                    {{-- {{ translate('Personal Info')}} --}}
                                                </div>
                                                <div class="p-3">

                                                    <!-- ... Code Verification form fields ... -->
                                                    <div class="form-group">
                                                        <label>{{ translate('Verification Code') }} <span
                                                                class="text-primary">*</span></label>
                                                        <input type="hidden" value="{{ $user->email ?? '' }}"
                                                            name="email" id="emailAccount">
                                                        <input type="text" class="form-control rounded-0"
                                                            placeholder="{{ translate('Enter Code') }}"
                                                            name="verification_code" required maxlength="6"
                                                            pattern="[0-9]{6}">
                                                        <small
                                                            class="text-muted">{{ translate('a_6digit_code') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <!-- Previous Button -->
                                                <button type="button" data-prv='personal-info'
                                                    class="btn btn-info fw-600 rounded-0 prv-tab">
                                                    {{ translate('previous') }}
                                                </button>

                                                <button id="verifyCodeBtn" type="button"
                                                    class="btn btn-primary fw-600 rounded-0"
                                                    {{-- onclick="switchTab('business-info')" --}}>{{ translate('Next') }}</button>
                                                <button id="resendCodeBtn" type="button"
                                                    class="btn btn-secondary fw-600 rounded-0">{{ translate('Resend Code') }}</button>

                                            </div>
                                        </form>
                                    </div>
                                @endif
                                <div class="tab-pane fade" id="business-info">
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
                                                            <label>{{ translate('English Trade Name') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('English Trade Name') }}"
                                                                value="{{ isset($user->business_information->trade_name) ? $user->business_information->getTranslation('trade_name', 'en', false) : '' }}"
                                                                name="trade_name_english" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Arabic Trade Name') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Arabic Trade Name') }}"
                                                                value="{{ isset($user->business_information->trade_name) ? $user->business_information->getTranslation('trade_name', 'ar', false) : '' }}"
                                                                name="trade_name_arabic" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Trade License Doc') }} <span
                                                                    class="text-primary">*</span><small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}</small></label>


                                                            @if (isset($user) && isset($user->business_information) && $user->business_information->trade_license_doc)
                                                                <a class="old_file"
                                                                    href="{{ static_asset($user->business_information->trade_license_doc) }}"
                                                                    target="_blank">{{ translate('View Trade License Doc') }}</a>
                                                                <input type="hidden" name="trade_license_doc_old"
                                                                    value="{{ $user->business_information->trade_license_doc }}">
                                                            @endif
                                                            <input type="file" class="form-control rounded-0"
                                                                placeholder="{{ translate('Trade License Doc') }}"
                                                                name="trade_license_doc" required>


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
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('English E-shop Name') }}"
                                                                value="{{ isset($user->business_information->eshop_name) ? $user->business_information->getTranslation('eshop_name', 'en', false) : '' }}"
                                                                name="eshop_name_english" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Arabic E-shop Name') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Arabic E-shop Name') }}"
                                                                value="{{ isset($user->business_information->eshop_name) ? $user->business_information->getTranslation('eshop_name', 'ar', false) : '' }}"
                                                                name="eshop_name_arabic" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('English e-Shop description') }} <span
                                                                    class="text-primary"></span></label>

                                                            <textarea class="form-control rounded-0" placeholder="{{ translate('English e-Shop description') }}"
                                                                name="eshop_desc_en">{{ isset($user->business_information->eshop_desc) ? $user->business_information->getTranslation('eshop_desc', 'en', false) : '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Arabic e-Shop description') }} <span
                                                                    class="text-primary"></span></label>

                                                            <textarea class="form-control rounded-0" placeholder="{{ translate('Arabic e-Shop description') }}"
                                                                name="eshop_desc_ar">{{ isset($user->business_information->eshop_desc) ? $user->business_information->getTranslation('eshop_desc', 'ar', false) : '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('License Issue Date') }} <span
                                                                    class="text-primary">*</span>
                                                            </label>

                                                            <input dir="auto" required type="{{-- date --}}text" class="datepicker form-control rounded-0"
                                                                placeholder="{{ translate('License Issue Date') }}"
                                                                id="license_issue_date"
                                                                value="{{ isset($user->business_information->license_issue_date) ? Carbon::createFromFormat('Y-m-d', $user->business_information->license_issue_date)->format('d M Y') : '' }}"
                                                                name="license_issue_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('License Expiry Date') }} <span
                                                                    class="text-primary">*</span></label>

                                                            <input dir="auto" required type="text" class="datepicker form-control rounded-0"
                                                                {{-- value="{{ $user->business_information->license_expiry_date ?? '' }}" --}}
                                                                value="{{ isset($user->business_information->license_expiry_date) ? Carbon::createFromFormat('Y-m-d', $user->business_information->license_expiry_date)->format('d M Y') : '' }}"
                                                                placeholder="{{ translate('License Expiry Date') }}"
                                                                name="license_expiry_date">
                                                        </div>
                                                    </div>
                                                    @if (isset($user->business_information) && !empty($user->business_information->state))
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('State/Emirate') }} <span
                                                                        class="text-primary">*</span></label>
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
                                                                <label>{{ translate('Area') }} <span
                                                                        class="text-primary">*</span></label>
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
                                                                <label>{{ translate('State/Emirate') }} <span
                                                                        class="text-primary">*</span></label>
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
                                                                <label>{{ translate('Area') }} <span
                                                                        class="text-primary">*</span></label>
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
                                                            <label>{{ translate('Street') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                value="{{ $user->business_information->street ?? '' }}"
                                                                placeholder="{{ translate('Street') }}" name="street"
                                                                required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Building') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                value="{{ $user->business_information->building ?? '' }}"
                                                                placeholder="{{ translate('Building') }}" name="building"
                                                                required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Unit/Office No.') }} <span
                                                                    class="text-primary"></span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                value="{{ $user->business_information->unit ?? '' }}"
                                                                placeholder="{{ translate('Unit/Office No.') }}"
                                                                name="unit">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('PO Box') }} <span
                                                                    class="text-primary"></span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                value="{{ $user->business_information->po_box ?? '' }}"
                                                                placeholder="{{ translate('PO Box') }}" name="po_box">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Landline Phone No.') }} <span
                                                                    class="text-primary"></span></label>
                                                            <input
                                                                value="{{ $user->business_information->landline ?? '' }}"
                                                                type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Landline Phone No.') }}"
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
                                                            <label>{{ translate('Vat Certificate') }} <span
                                                                    class="text-primary">*</span><small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}</small></label>
                                                            @if (isset($user) && isset($user->business_information) && $user->business_information->vat_certificate)
                                                                <a class="old_file"
                                                                    href="{{ static_asset($user->business_information->vat_certificate) }}"
                                                                    target="_blank">{{ translate('View Vat Certificate') }}</a>
                                                                <input type="hidden" name="vat_certificate_old"
                                                                    value="{{ $user->business_information->vat_certificate }}">
                                                            @endif
                                                            <input type="file" class="form-control rounded-0"
                                                                placeholder="{{ translate('Vat Certificate') }}"
                                                                name="vat_certificate">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="trnGroup">
                                                        <div class="form-group">
                                                            <label>{{ translate('TRN') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input value="{{ $user->business_information->trn ?? '' }}"
                                                                type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('TRN') }}" name="trn">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="taxWaiverGroup" {{-- style="display: none;" --}}>
                                                        <div class="form-group">
                                                            <label>{{ translate('Tax Waiver Certificate') }} <span
                                                                    class="text-primary">*</span><small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}</small></label>
                                                            @if (isset($user) && isset($user->business_information) && $user->business_information->tax_waiver)
                                                                <a class="old_file"
                                                                    href="{{ static_asset($user->business_information->tax_waiver) }}"
                                                                    target="_blank">{{ translate('View Tax Waiver Certificate') }}</a>
                                                                <input type="hidden" name="tax_waiver_old"
                                                                    value="{{ $user->business_information->tax_waiver }}">
                                                            @endif
                                                            <input type="file" class="form-control rounded-0"
                                                                name="tax_waiver">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Civil Defense Approval') }} <span
                                                                    class="text-primary"></span><small>{{ translate('Max file size is 5MB and accepted file types are PDF and image formats.') }}</small></label>
                                                            {{-- <input  type="file" class="form-control rounded-0"
                                                                name="civil_defense_approval"> --}}
                                                            @if (isset($user) && isset($user->business_information) && $user->business_information->civil_defense_approval)
                                                                <a class="old_file"
                                                                    href="{{ static_asset($user->business_information->civil_defense_approval) }}"
                                                                    target="_blank">{{ translate('View Civil Defense Approval') }}</a>
                                                                <input type="hidden" name="civil_defense_approval_old"
                                                                    value="{{ $user->business_information->civil_defense_approval }}">
                                                            @endif
                                                            <input type="file" class="form-control rounded-0"
                                                                name="civil_defense_approval">

                                                        </div>

                                                    </div>


                                                </div>
                                            </div>
                                        </div>


                                        <div class="text-right">
                                            <button type="button"
                                                class="btn btn-secondary fw-600 rounded-0 save-as-draft"
                                                data-action="save-as-draft">{{ translate('Save as Draft') }}</button>

                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                {{-- onclick="switchTab('contact-person')" --}}>{{ translate('Save and Continue') }}</button>

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
                                                            <label>{{ translate('First Name') }} <span
                                                                    class="text-primary">*</span></label>
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
                                                                placeholder="{{ translate('First Name') }}"
                                                                value="{{ $fistName }}" name="first_name" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Last Name') }} <span
                                                                    class="text-primary">*</span></label>
                                                            @php
                                                                $lastName = null;
                                                                if (isset($user->contact_people->last_name) && !empty($user->contact_people->last_name)) {
                                                                    $lastName = $user->contact_people->last_name;
                                                                } elseif (isset($user->last_name)) {
                                                                    $lastName = $user->last_name;
                                                                }

                                                            @endphp
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Last Name') }}"
                                                                id="last_name_bi" value="{{ $lastName }}"
                                                                name="last_name" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Email') }} <span
                                                                    class="text-primary">*</span></label>
                                                            @php
                                                                $emailUser = null;
                                                                if (isset($user->contact_people->email) && !empty($user->contact_people->email)) {
                                                                    $emailUser = $user->contact_people->email;
                                                                } elseif (isset($user->email)) {
                                                                    $emailUser = $user->email;
                                                                }

                                                            @endphp
                                                            <input type="email" class="form-control rounded-0"
                                                                id="email_bi" placeholder="{{ translate('Email') }}"
                                                                value="{{ $emailUser }}" name="email" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Mobile Phone') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <small class="text-muted">{{ translate('Example') }}:
                                                                +971123456789 {{ translate('or') }}
                                                                00971123456789</small>

                                                            <input type="text" dir="auto" class="form-control rounded-0"
                                                                placeholder="{{ translate('Mobile Phone') }}"
                                                                value="{{ $user->contact_people->mobile_phone ?? '+971' }}"
                                                                name="mobile_phone" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Additional Mobile Phone') }} <span
                                                                    class="text-primary"></span><small
                                                                    class="text-muted">{{ translate('Example') }}:
                                                                    +971123456789 {{ translate('or') }}
                                                                    00971123456789</small></label>
                                                            <input type="text" dir="auto" class="form-control rounded-0"
                                                                placeholder="{{ translate('Additional Mobile Phone') }}"
                                                                value="{{ $user->contact_people->additional_mobile_phone ?? '+971' }}"
                                                                name="additional_mobile_phone">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Nationality') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <br>
                                                            <select  title="{{ translate('Select Nationality') }}"
                                                                name="nationality" class="form-control selectpicker countrypicker"
                                                                @if (isset($user->contact_people) && !empty($user->contact_people->nationality)) data-default="{{ $user->contact_people->nationality }}" @else data-default="" @endif
                                                                data-flag="true"></select>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Date Of Birth') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input  dir="auto" type="text" class="datepicker form-control rounded-0"
                                                                placeholder="{{ translate('Date Of Birth') }}"
                                                                {{-- value="{{ $user->contact_people->date_of_birth ?? '' }}" --}}
                                                                value="{{ isset($user->contact_people->date_of_birth) ? Carbon::createFromFormat('Y-m-d', $user->contact_people->date_of_birth)->format('d M Y') : '' }}"

                                                                name="date_of_birth" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Emirates ID - Number') }} <span
                                                                    class="text-primary">*</span></label> <small
                                                                class="text-muted">{{ translate('Example') }}:123456789012345
                                                            </small>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Emirates ID - Number') }}"
                                                                value="{{ $user->contact_people->emirates_id_number ?? '' }}"
                                                                required name="emirates_id_number">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Emirates ID - Expiry Date') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input dir="auto" type="text" class="datepicker form-control rounded-0"
                                                                placeholder="{{ translate('Emirates ID - Expiry Date') }}"
                                                                {{-- value="{{ $user->contact_people->emirates_id_expiry_date ?? '' }}" --}}
                                                                value="{{ isset($user->contact_people->emirates_id_expiry_date) ? Carbon::createFromFormat('Y-m-d', $user->contact_people->emirates_id_expiry_date)->format('d M Y') : '' }}"

                                                                required name="emirates_id_expiry_date">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Emirates ID') }} <span
                                                                    class="text-primary">*</span><small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}
                                                                                 </small></label>
                                                            @if (isset($user) && isset($user->contact_people) && $user->contact_people->emirates_id_file_path)
                                                                <a class="old_file"
                                                                    href="{{ static_asset($user->contact_people->emirates_id_file_path) }}"
                                                                    target="_blank">{{ translate('View Emirates ID') }}</a>
                                                                <input type="hidden" name="emirates_id_file_old"
                                                                    value="{{ $user->contact_people->emirates_id_file_path }}">
                                                            @endif
                                                            <input type="file" class="form-control rounded-0"
                                                                placeholder="{{ translate('Emirates ID') }}" required
                                                                name="emirates_id_file">

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
                                                            <label>{{ translate('Designation') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0" required
                                                                placeholder="{{ translate('Designation') }}"
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
                                            <button type="button"
                                                class="btn btn-secondary fw-600 rounded-0 save-as-draft"
                                                data-action="save-as-draft">{{ translate('Save as Draft') }}</button>

                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                {{-- onclick="switchTab('warehouses')" --}}>{{ translate('Save and Continue') }}</button>
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

                                                <div class="row warehouseRow" id="warehouseRows">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="warehouse_name">{{ translate('Warehouse Name') }}<span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="warehouse_name_add">

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="state">{{ translate('State/Emirate') }}<span
                                                                    class="text-primary">*</span></label>
                                                                    <select name="state_warehouse_add" class="form-control rounded-0 emirateSelect" id="emirateempire">
                                                                        <option value="" selected>{{ translate('please_choose') }}</option>
                                                                        @foreach ($emirates as $emirate)
                                                                            <option value="{{ $emirate->id }}">{{ $emirate->name }}</option>
                                                                        @endforeach
                                                                    </select>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="area">{{ translate('Area') }}<span
                                                                    class="text-primary">*</span></label>
                                                            <select name="area_warehouse_add"
                                                                class="form-control areaSelect">
                                                                <option value="" selected>
                                                                    {{ translate('please_choose') }}
                                                                </option>
                                                                <!-- Options for area -->
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="street">{{ translate('Street') }}<span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="street_warehouse_add">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="building">{{ translate('Building') }}<span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="building_warehouse_add">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="unit">{{ translate('Unit/Office No.') }}<span
                                                                    class="text-primary"></span></label>
                                                            <input type="text" class="form-control" name="unit_add">
                                                        </div>
                                                    </div>


                                                    <div class="col-auto ml-auto">
                                                        <button type="button" class="btn btn-primary"
                                                            id="addRow">{{ translate('Add Warehouse') }}</button>

                                                    </div>
                                                </div>
                                                <table class="table mt-3" id="warehouseTable">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>{{ translate('Warehouse Name') }}</th>
                                                            <th>{{ translate('State/Emirate') }}</th>
                                                            <th>{{ translate('Area') }}</th>
                                                            <th>{{ translate('Street') }}</th>
                                                            <th>{{ translate('Building') }}</th>
                                                            <th>{{ translate('Unit/Office No.') }}</th>
                                                            <th>{{ translate('Action') }}</th>
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
                                                                    <td><button type="button"
                                                                            class="btn btn-danger removeRow">{{ translate('Remove') }}</button>
                                                                    </td>
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

                                            <button type="button"
                                                class="btn btn-secondary fw-600 rounded-0 save-as-draft"
                                                data-action="save-as-draft">{{ translate('Save as Draft') }}</button>

                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                {{--  onclick="switchTab('payout-info')" --}}>{{ translate('Save and Continue') }}</button>
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
                                                            <label>{{ translate('Bank Name') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input
                                                                value="{{ $user->payout_information->bank_name ?? '' }}"
                                                                type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Bank Name') }}"
                                                                name="bank_name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Account Name') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input
                                                                value="{{ $user->payout_information->account_name ?? '' }}"
                                                                type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Account Name') }}"
                                                                name="account_name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Account Number') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input
                                                                value="{{ $user->payout_information->account_number ?? '' }}"
                                                                type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Account Number') }}"
                                                                name="account_number" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('IBAN') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input value="{{ $user->payout_information->iban ?? '' }}"
                                                                type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('IBAN') }}" name="iban"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Swift Code') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input
                                                                value="{{ $user->payout_information->swift_code ?? '' }}"
                                                                type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Swift Code') }}"
                                                                name="swift_code" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('IBAN Certificate') }}<span
                                                                    class="text-primary">*</span><small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}</small></label>
                                                            @if (isset($user) && isset($user->payout_information) && $user->payout_information->iban_certificate)
                                                                <a class="old_file"
                                                                    href="{{ static_asset($user->payout_information->iban_certificate) }}"
                                                                    target="_blank">{{ translate('View IBAN Certificate') }}</a>
                                                                <input type="hidden" name="iban_certificate_old"
                                                                    value="{{ $user->payout_information->iban_certificate }}">
                                                            @endif
                                                            <input required type="file" class="form-control rounded-0"
                                                                name="iban_certificate">
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

                                            <button type="button"
                                                class="btn btn-secondary fw-600 rounded-0 save-as-draft"
                                                data-action="save-as-draft">{{ translate('Save as Draft') }}</button>

                                            <button id="registerShop" type="submit"
                                                class="btn btn-primary fw-600 rounded-0">{{ translate('Register Your Shop') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Your existing logic here

            var stepNumber = {{ $step_number ?? 0 }}; // Get the step number from the server

            // Check the stepNumber variable and switch tabs accordingly
            switch (stepNumber) {
                case 0:
                    switchTab('personal-info');
                    break;
                case 1:
                    switchTab('code-verification');
                    break;
                case 2:
                    switchTab('business-info');
                    break;
                case 3:
                    switchTab('contact-person');
                    break;
                case 4:
                    switchTab('warehouses');
                    break;
                case 5:
                    switchTab('payout-info');
                    break;

                default:

                    switchTab('business-info'); // Default to the first tab if stepNumber is not recognized
                    break;
            }
            //             var errors = {
            //     "area_warehouse.0": ["The area_warehouse.0 field is required."],
            //     // ... other errors
            // };

            // // Extract the field name with the first error
            // var firstErrorField = Object.keys(errors)[0];

            // // Highlight the first input field in red
            // $('[name="warehouse_name[]"]:nb(3)').css('border-color', 'red');

            // // Add Row
            // $('#addRow').on('click', function() {
            //     var newRow = $('#warehouseTable tbody tr:first').clone();
            //     newRow.find('input, select').val('');
            //     newRow.find('.removeRow').show();
            //     $('#warehouseTable tbody').append(newRow);
            // });

            // // Remove Row
            // $('#warehouseTable').on('click', '.removeRow', function() {
            //     // Check if there is at least one <tr> element
            //     if ($('#warehouseTable tbody tr').length > 1) {
            //         // Remove the closest <tr> element
            //         $(this).closest('tr').remove();
            //     } else {
            //         // Display a message or take appropriate action when there's only one row left
            //         alert('Cannot remove the last row.');
            //     }
            // });
            // Add Warehouse Row
            // Add Warehouse button click event
            // $('#addRow').on('click', function() {
            //     // Clone the entire warehouseRows section
            //     var newWarehouseRows = $('#warehouseRows').clone();

            //     // Show the "Remove Warehouse" button in the new row
            //     newWarehouseRows.find('.removeRow').show();
            //     newWarehouseRows.find('input,select').val('');
            //     newWarehouseRows.find('input, select').removeClass('is-invalid is-valid');
            //     // Append the new row to the warehouseRowsContainer
            //     $('#warehouseRowsContainer').append(newWarehouseRows);
            // });

            // // Remove Warehouse button click event
            // $('#warehouseRowsContainer').on('click', '.removeRow', function() {
            //     // Check if there is at least one warehouse row
            //     if ($('#warehouseRowsContainer .row').length > 1) {
            //         // Remove the closest warehouse row
            //         $(this).closest('.row').remove();
            //     } else {
            //         // Display a message or take appropriate action when there's only one row left
            //         toastr.error('Cannot remove the last warehouse.');
            //     }
            // });

            $('.prv-tab').on('click', function() {
                switchTab($(this).data('prv'));
                var form = $(this).closest('form');
                $(form).find('.is-invalid').first().focus();

            });

            $('#addRow').on('click', function() {
                var warehouseName = $('input[name="warehouse_name_add"]').val();
                var state = $('select[name="state_warehouse_add"]').val();
                var stateText = $('select[name="state_warehouse_add"] option:selected').text();
                var area = $('select[name="area_warehouse_add"]').val();
                var areaText = $('select[name="area_warehouse_add"] option:selected').text();
                var street = $('input[name="street_warehouse_add"]').val();
                var building = $('input[name="building_warehouse_add"]').val();
                var unit = $('input[name="unit_add"]').val();
                // Check if any input is empty
                if (!warehouseName || !state || !area || !street || !building || !unit) {
                    // Show toast with translated message
                    toastr.error('{{ translate('Please fill in all fields.') }}');
                    return; // Stop execution if any input is empty
                }
                const newRow = $('<tr>');

                // Create cells
                newRow.append(
                    '<td><input type="text" class="form-control" name="warehouse_name[]" value="' +
                    warehouseName + '" required></td>');
                newRow.append(
                    '<td><select required name="state_warehouse[]" class="form-control rounded-0 emirateSelect"><option value="' +
                    state + '" selected>' + stateText + '</option></select></td>');
                newRow.append(
                    '<td><select class="form-control areaSelect" name="area_warehouse[]" required><option value="' +
                    area + '" selected>' + areaText + '</option></select></td>');
                newRow.append(
                    '<td><input type="text" class="form-control" name="street_warehouse[]" value="' +
                    street + '" required></td>');
                newRow.append(
                    '<td><input type="text" class="form-control" name="building_warehouse[]" value="' +
                    building + '" required></td>');
                newRow.append(
                    '<td><input type="text" class="form-control" name="unit_warehouse[]" value="' +
                    unit + '" required></td>');
                newRow.append(
                    '<td><button type="button" class="btn btn-danger removeRow">Remove</button></td>');

                $('#warehouseTable tbody').append(newRow);

                // Clear input fields
                $('input[name="warehouse_name_add"]').val('');
                $('select[name="state_warehouse_add"]').val('');
                $('select[name="area_warehouse_add"]').val('');
                $('input[name="street_warehouse_add"]').val('');
                $('input[name="building_warehouse_add"]').val('');
                $('input[name="unit_add"]').val('');
            });

            // Add event listener for the "Remove" button
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            $('#registerTabs a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            $('#registerTabsContent').find(
                    '.tab-pane button:not(#resendCodeBtn,#addRow,.removeRow,#registerShop,.prv-tab)')
                .on('click', function(e) {
                    // // Iterate over each warehouse row




                    var shouldContinue = true; // Initialize the boolean variable
                    var clickedButton = e.target;


                    e.preventDefault();
                    var form = $(this).closest('form');
                    if (form.attr('id') == 'warehousesForm') {
                        $('#warehouseRowsContainer .warehouseRow').each(function() {
                            var warehouseInputs = $(this).find('input, select');
                            var isEmpty = true;

                            // Check if all input fields are empty
                            warehouseInputs.each(function() {
                                if ($(this).val() !== '') {
                                    isEmpty = false;
                                    return false; // Exit the loop if a non-empty field is found
                                }
                            });

                            // If the warehouse is empty, remove the row
                            if (isEmpty && $('#warehouseRowsContainer .warehouseRow').length > 1) {
                                $(this).remove();
                            }
                        });
                    }

                    var formData = new FormData(form[0]); // Create FormData object from the form
                    if ($(clickedButton).hasClass('save-as-draft')) {
                        var action = $(clickedButton).data('action');
                        formData.append('action', action);
                    }
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        // data: form.serialize(),
                        data: formData,
                        contentType: false, // Required for sending FormData
                        processData: false, // Required for sending FormData
                        success: function(response) {
                            if (form.attr('id') == 'shop') {
                                var email = $('#email').val();
                                $('#emailAccount').val(email);
                                firstName = $('#first_name').val();
                                lastName = $('#last_name').val();
                                $('#first_name_bi').val(firstName);
                                $('#last_name_bi').val(lastName);
                                $('#email_bi').val(email);

                            }
                            // Handle success, e.g., show a message
                            if (response.hasOwnProperty('finish') && response.finish === true) {
                                location.reload();

                            }
                            if (response.hasOwnProperty('verif_login') && response.verif_login ===
                                true) {

                                $('#personal-info-tab, #code-verification-tab').addClass(
                                    'disabled');
                                $('#personal-info, #code-verification').addClass('disabled');

                                $('#registerTabs a[data-toggle="tab"]').on('click', function(e) {
                                    e.preventDefault();
                                });
                            }


                            // Switch to the next tab if the save operation is successful
                            if (response.success) {

                                if (response.infoMsg) {
                                    toastr.info(response
                                        .message); // Display success message using Toastr

                                } else {
                                    toastr.success(response
                                        .message); // Display success message using Toastr
                                }


                                // switchTab('code-verification'); // Change the tab ID accordingly
                                var nextTabId = form.data(
                                    'next-tab'
                                ); // Assuming you set a data attribute on the form with the next tab ID
                                // if (form.attr('id') != 'warehousesForm') {
                                displayValidation(form);
                                // }
                                if (!response.save_as_draft) {
                                    switchTab(nextTabId);
                                }

                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 429) {
                                // Too Many Attempts
                                toastr.error(
                                    "{{ translate('Too many attempts. Please try again later.') }}"
                                );
                            } else {
                                // if (xhr.responseJSON.hasOwnProperty('loginFailed')) {
                                //     // Display login failure message using JavaScript
                                //     toastr.error(xhr.responseJSON.loginFailed);
                                //     shouldContinue = false;

                                // }
                                if (xhr.status === 403) {
                                    // Authorization failed
                                    toastr.error(xhr.responseJSON
                                        .message); // Display the error message
                                    shouldContinue = false;

                                }
                                // Handle errors, e.g., show validation errors
                                var errors = xhr.responseJSON.errors;

                                // Display validation errors in the form
                                if ( /* form.attr('id') != 'warehousesForm' && */ shouldContinue !=
                                    false) {
                                    displayValidationErrors(errors, form);
                                    $(form).find('.is-invalid').first().focus();
                                    // Display a general toast message
                                    toastr.error(
                                        "{{ translate('Please review the form for errors.') }}"
                                    );
                                }

                                // if ((form).attr('id') == 'warehousesForm' && shouldContinue !=
                                //     false) {
                                //             // toastr.error('Please fill up the rest of the table for warehousesForm. Ensure that no field exceeds 128 characters.');

                                //             displayValidationWhErrors(errors);
                                //         }

                                // if (form.attr('id') == 'warehousesForm') {
                                //     toastr.error('Please fill up the rest of the table for warehousesForm. Ensure that no field exceeds 128 characters.');

                                // }
                            }

                        }
                    });
                });

            $('#registerTabsContent').find('.tab-pane button#registerShop').on('click', function(e) {
                // // Iterate over each warehouse row
                e.preventDefault();
                // Create a FormData object to store all form data
                var formData = new FormData();

                // Iterate over each form and append its data to the main FormData object
                $('#businessInfoForm, #contactPersonForm, #warehousesForm, #payoutInfoForm').each(
                    function() {
                        var currentFormData = new FormData($(this)[0]);

                        // Append each key-value pair from the current form data to the main form data
                        for (var pair of currentFormData.entries()) {
                            formData.append(pair[0], pair[1]);
                        }
                    });

                // Include the CSRF token in the headers
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ route('shops.register') }}",
                    type: 'POST',
                    // data: form.serialize(),
                    data: formData,

                    headers: {
                        'X-CSRF-TOKEN': csrfToken, // Include CSRF token in headers
                    },
                    contentType: false, // Required for sending FormData
                    processData: false, // Required for sending FormData
                    success: function(response) {

                        // Handle success, e.g., show a message
                        if (response.hasOwnProperty('finish') && response.finish === true) {
                            location.reload();

                        }

                        if (response.status === 'error') {
                            // Display the error message
                            toastr.error(response.message);
                            // Check if redirectWh exists and is true
                            // if (response.redirectWh) {
                            //     switchTab('warehouses');
                            // }
                        }
                        // Switch to the next tab if the save operation is successful
                        if (response.success) {
                            // switchTab('code-verification'); // Change the tab ID accordingly
                            var nextTabId = form.data(
                                'next-tab'
                            ); // Assuming you set a data attribute on the form with the next tab ID
                            // if (form.attr('id') != 'warehousesForm') {
                            displayValidation(form);
                            // }
                            switchTab(nextTabId);

                        }
                    },
                    error: function(xhr) {

                        if (xhr.status === 429) {
                            // Too Many Attempts
                            toastr.error(
                                "{{ translate('Too many attempts. Please try again later.') }}"
                            );
                        } else {
                            if (xhr.status === 403) {
                                // Authorization failed
                                toastr.error(xhr.responseJSON
                                    .message); // Display the error message
                                if (xhr.responseJSON
                                    .message == "Please add at least one warehouse." || xhr
                                    .responseJSON
                                    .message == "الرجاء إضافة مستودع واحد على الأقل.") {
                                    switchTab('warehouses');
                                }
                            } else {
                                // Handle errors, e.g., show validation errors
                                var errors = xhr.responseJSON.errors;
                                var tabErrorFirst = false;
                                // Display validation errors in the form
                                $('#businessInfoForm, #contactPersonForm, #payoutInfoForm, #warehousesForm')
                                    .each(
                                        function() {

                                            tabError = displayValidationErrors(errors, $(this));
                                            if (tabError == false && tabErrorFirst == false) {
                                                tabErrorFirst = true;
                                                switchTab($(this).parent().attr('id'));
                                                // Display a general toast message
                                                toastr.error(
                                                    "{{ translate('Please review the form for errors.') }}"
                                                );

                                            }

                                            // if ($(this).attr('id') == 'warehousesForm') {
                                            //     // toastr.error('Please fill up the rest of the table for warehousesForm. Ensure that no field exceeds 128 characters.');

                                            //     displayValidationWhErrors(errors);
                                            // }
                                        });

                            }
                        }

                    }
                });
            });



            // function displayValidationWhErrors(errors) {
            //     // Clear existing error messages
            //     $('.error-message').remove();
            //     var formTab = $('#warehouses-tab');

            //     if (formTab.hasClass('has-errors')) {
            //         formTab.removeClass('has-errors');
            //     }
            //     // Display new error messages
            //     $.each(errors, function(fieldName, messages) {
            //         // Correct the field name without escaping the dot
            //         var correctedFieldName = fieldName.split('.')[0].replace('[', '\\[').replace(']',
            //         '\\]');

            //         // Extract the index from the field name, e.g., "area_warehouse.0" => 0
            //         var index = parseInt(fieldName.split('.')[1]);


            //         var inputField = $('[name="' + correctedFieldName + '[]"]:eq(' + index + ')');

            //         inputField.addClass('is-invalid');

            //         var errorContainer = $('<div class="invalid-feedback"></div>');

            //         $.each(messages, function(key, message) {
            //             errorContainer.append('<strong>' + message + '</strong><br>');
            //         });

            //         inputField.closest('td').append(errorContainer);
            //         $('#warehouses-tab').addClass('has-errors');

            //     });
            // }



            // $('#verifyCodeBtn').on('click', function () {

            //     var form = $('#codeVerificationForm');
            //     // Adding the email to the data object
            //     var formData = form.serializeArray();
            //         formData.push({ name: 'email', value: $('#email').val() });
            //     $.ajax({
            //         url: '{{ route('verify.code') }}', // Replace with your actual route
            //         type: 'POST',
            //         data: formData,

            //         success: function (response) {
            //             // Handle success, e.g., show a message
            //             alert(response.message);

            //             // Switch to the next tab if the verification is successful
            //             if (response.success) {
            //                 switchTab('business-info'); // Change the tab ID accordingly
            //             }
            //         },
            //         error: function (xhr) {
            //             // Handle errors, e.g., show validation errors
            //             var errors = xhr.responseJSON.errors;
            //             // Update this part based on your error handling needs
            //             alert('Error: ' + JSON.stringify(errors));
            //         }
            //     });
            // });

            // Add an event listener to the resend button
            $('#resendCodeBtn').on('click', function() {
                var form = $('#codeVerificationForm');

                // Adding the email to the data object
                var formData = form.serializeArray();
                formData.push({
                    name: 'email',
                    value: $('#emailAccount').val()
                });

                $.ajax({
                    url: '{{ route('resend.code') }}', // Replace with your actual route
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle success, e.g., show a message
                        toastr.success(response.message);

                        // You can add additional logic here if needed
                    },
                    error: function(xhr) {
                        if (xhr.status === 429) {
                            // Too Many Attempts
                            toastr.error('Too many attempts. Please try again later.');
                        } else {
                            if (xhr.status === 403) {
                                // Authorization failed
                                toastr.error(xhr.responseJSON
                                    .message); // Display the error message

                            }
                        }

                        // // Handle errors, e.g., show validation errors
                        // var errors = xhr.responseJSON.errors;
                        // // Update this part based on your error handling needs
                        // alert('Error: ' + JSON.stringify(errors));
                    }
                });
            });



            function displayValidationErrors(errors, form) {
                var testValid = true;
                // Clear existing error messages and success styles
                form.find('.invalid-feedback').remove();
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.is-valid').removeClass('is-valid');

                var formTab = $('#' + form.parent().attr('id') + "-tab");

                if (formTab.hasClass('has-errors')) {

                    formTab.removeClass('has-errors');
                }
                // Clear global validation error messages
                $('#validation-errors').empty().hide();
                // $("#registerTabs a").removeClass('has-errors');
                //  $("#business-info-tab").removeClass('has-errors');

                // Display new error messages

                $.each(errors, function(field, messages) {


                    if (form.attr('id') == "warehousesForm") {

                        var correctedFieldName = field.split('.')[0].replace('[', '\\[').replace(']',
                            '\\]');
                        var index = parseInt(field.split('.')[1]);


                        var inputField = $('[name="' + correctedFieldName + '[]"]:eq(' + index + ')');
                    } else {
                        var inputField = form.find('[name="' + field + '"]');
                    }
                    var errorContainer = $('<div class="invalid-feedback"></div>');

                    $.each(messages, function(key, message) {
                        errorContainer.append('<strong>' + message + '</strong><br>');
                    });

                    inputField.addClass('is-invalid');

                    // Check if the current field is the password field and if it has the class "is-invalid"
                    if (field === 'password' && inputField.hasClass('is-invalid')) {
                        $("#password_confirmation").addClass('is-invalid');
                    }

                    inputField.after(errorContainer);

                    form.find('.form-control').each(function() {
                        var inputField = $(this);

                        if (inputField.hasClass('is-invalid')) {
                            tabErreur = $(this).closest('.tab-pane').attr('id')
                            $('#' + tabErreur + "-tab").addClass('has-errors');

                        }
                    });
                });

                // Highlight fields without errors
                form.find('.form-control').each(function() {
                    var inputField = $(this);

                    if (!inputField.hasClass('is-invalid') && inputField.val() !== '' || inputField.parent()
                        .find('a').hasClass('old_file')) {
                        inputField.addClass('is-valid');
                    } else if (inputField.hasClass('is-invalid')) {
                        testValid = false;
                    }

                });
                return testValid;
                $('#validation-errors').html('Validation errors occurred. Please check the form.').show();
            }

            function displayValidation(form) {

                form.find('.form-control').each(function() {
                    var inputField = $(this);
                    inputField.removeClass('is-valid');
                    if (inputField.val() !== '' || inputField.parent().find('a').hasClass('old_file')) {

                        inputField.removeClass('is-invalid');
                        inputField.addClass('is-valid');
                    }
                });
                var formTab = $('#' + form.parent().attr('id') + "-tab");

                if (formTab.hasClass('has-errors')) {

                    formTab.removeClass('has-errors');
                }
            }

            function switchTab(tabId) {

                $('#registerTabs').find('.nav-link').removeClass('active');
                $('#registerTabsContent').find('.tab-pane').removeClass('show active');

                $('#' + tabId + '-tab').addClass('active');
                $('#' + tabId).addClass('show active');

                // Focus on the first input with 'is-invalid' class within the active tab
                $('#' + tabId).find('.is-invalid').first().focus();
            }

            $('#emirateempire').change(function() {
                var getAreaUrl = "{{ route('get.area', ['id' => ':id']) }}";

                var id = $(this).val();
                $('#areaempire').find('option').not(':first').remove();

                // AJAX request
                $.ajax({
                    //  url: '/user/getArea/'+id,

                    url: getAreaUrl.replace(':id', id), // Use the route variable
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {

                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }

                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {

                                var id = response['data'][i].id;
                                var name = response['data'][i].name_translated;

                                var option = "<option value='" + id + "'>" + name + "</option>";

                                $("#areaempire").append(option);
                            }
                        }

                    }
                });
            });

            $('input[name="vat_registered"]').change(function() {
                var isVatRegistered = $(this).val() == 1;

                // Show/hide relevant form groups based on VAT registration status
                $('#vatCertificateGroup').toggle(isVatRegistered);
                $('#trnGroup').toggle(isVatRegistered);
                $('#taxWaiverGroup').toggle(!isVatRegistered);

                // Set or remove the "required" attribute based on VAT registration status
                $('#vatCertificate').prop('required', isVatRegistered);
                $('#trn').prop('required', isVatRegistered);
                $('#taxWaiver').prop('required', !isVatRegistered);
            });

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



            $(document).on('change', '.emirateSelect', function() {

                // $('').on('change', function() {
                var emirateId = $(this).val();

                var areaSelect = $(this).closest('.warehouseRow').find('.areaSelect');

                // Make an AJAX call to get areas based on the selected emirate
                $.ajax({
                    url: '{{ route('get.area', ['id' => ':id']) }}'.replace(':id', emirateId),
                    method: 'GET',
                    success: function(response) {
                        // Update the options in the area select
                        areaSelect.empty();
                        areaSelect.append(
                            '<option value="" selected>{{ translate('please_choose') }}</option>');

                        // Add options based on the response
                        // $.each(response, function(index, area) {
                        //     console.log(area)
                        //     areaSelect.append('<option value="' + area[0].id + '">' + area[0].name + '</option>');
                        // });
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }

                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {

                                var id = response['data'][i].id;
                                var name = response['data'][i].name_translated;

                                var option = "<option value='" + id + "'>" + name + "</option>";

                                areaSelect.append(option);
                            }
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching areas:', error);
                    }
                });
            });

        });

        toastr.options = {
            positionClass: 'toast-top-right',
            closeButton: true,
            timeOut: 3000, // Set the duration for which the toast will be displayed (in milliseconds)
        };
        $('#password').on('input', function() {
            $('#password-strength').css('border', '1px solid #ddd');
            $('#password-strength').css('padding', '10px');

            var password = $(this).val();
            var strengthMeter = $('#password-strength');

            var patterns = [
                'abc', 'bcd', 'cde', 'def', 'efg', 'fgh', 'ghi', 'hij', 'ijk', 'jkl',
                'klm', 'lmn', 'mno', 'nop', 'opq', 'pqr', 'qrs', 'rst', 'stu', 'tuv',
                'uvw', 'vwx', 'wxy', 'xyz'
            ];

            var patternRegex = new RegExp(patterns.join('|') + '|' + patterns.map(function(pattern) {
                return pattern.split('').reverse().join('');
            }).join('|'), 'i');

            // Function to calculate the percentage of repeated characters in the password
            function calculateRepeatedCharacterPercentage(password) {
                var characterCount = {};
                for (var i = 0; i < password.length; i++) {
                    var char = password[i];
                    characterCount[char] = (characterCount[char] || 0) + 1;
                }

                var repeatedCount = 0;
                for (var char in characterCount) {
                    if (characterCount[char] > 1) {
                        repeatedCount += characterCount[char];
                    }
                }

                return (repeatedCount / password.length) * 100;
            }

            var repeatedCharacterPercentage = calculateRepeatedCharacterPercentage(password);

            // Password strength rules
            var rules = {
                "{{ translate('Minimum length of 9 characters') }}": password.length >= 9,
                "{{ translate('At least one uppercase letter') }}": /[A-Z]/.test(password),
                "{{ translate('At least one lowercase letter') }}": /[a-z]/.test(password),
                // "At least one number": /\d/.test(password),
                "{{ translate('At least one special character') }}": /[@#-+/=$!%*?&]/.test(password),
                "{{ translate('At least one number and Max Four Numbers') }}": /^\D*(\d\D*){1,4}$/.test(
                    password),
                // maxConsecutiveChars: !/(.)\1\1/.test(password),
                // maxPercentage: calculateMaxPercentage(password),
                "{{ translate('No spaces allowed') }}": !/\s/.test(password),
                "{{ translate('No three consecutive numbers, Example 678,543,789,987') }}": !
                    /(012|123|234|345|456|567|678|789|987|876|765|654|543|432|321|210)/.test(password),
                "{{ translate('No three characters or more can be a substring of first name, last name, or email') }}":
                    !
                    checkSubstring(password),
                "{{ translate('No three consecutive characters or their reverses in the same case are allowed, Example efg,ZYX,LMN,cba') }}":
                    !patternRegex.test(password),
                "{{ translate('No more than 40% of repeated characters') }}": repeatedCharacterPercentage <=
                    40,
                "{{ translate('No substring of the password can be a common English dictionary word') }}": !containsDictionaryWord(password, dictionaryWords),


            };

            // Display password strength rules
            var strengthText = '';
            for (var rule in rules) {
                if (rules.hasOwnProperty(rule)) {
                    strengthText += '<p>' + rule + ': ' + (rules[rule] ? '✔' : '✘') + '</p>';
                }
            }

            // Update UI
            strengthMeter.html(strengthText);

            // Check if all rules are satisfied
            var isPasswordValid = Object.values(rules).every(Boolean);

            // Apply visual feedback
            if (isPasswordValid) {
                strengthMeter.addClass('valid');
            } else {
                strengthMeter.removeClass('valid');
            }
        });

        // No substring of the password can be a common English dictionary word

        let dictionaryWords=[] ;

                $.ajax({
            url: '{{route("get.words")}}', // Make sure this URL matches your Laravel route
            method: 'GET',
            success: function(data) {
                dictionaryWords = data;
            },
            error: function(error) {
                console.error("Error fetching dictionary words", error);
            }
        });

        function containsDictionaryWord(password,dictionaryWords) {
            for(let word of dictionaryWords) {
                if(password.toLowerCase().includes(word.toLowerCase())){
                        return true ;
                }
            }
            return false ;
        }

        function calculateMaxPercentage(password) {
            // Calculate the percentage of lowercase, uppercase, numbers, and special characters
            var lowercasePercentage = (password.replace(/[^a-z]/g, '').length / password.length) * 100;
            var uppercasePercentage = (password.replace(/[^A-Z]/g, '').length / password.length) * 100;
            var numberPercentage = (password.replace(/[^0-9]/g, '').length / password.length) * 100;
            var specialCharPercentage = (password.replace(/[^@$!%*?&]/g, '').length / password.length) * 100;

            // Return the maximum percentage
            return Math.max(lowercasePercentage, uppercasePercentage, numberPercentage, specialCharPercentage);
        }

        function checkSubstring(password) {
            // Check if the password contains a substring of the first name, last name, or email with a length of 3 or more characters
            var firstName = $('#first_name').val().toLowerCase();
            var lastName = $('#last_name').val().toLowerCase();
            var email = $('#email').val().toLowerCase();

            // Combine the first name, last name, and email into a single string
            var combinedStrings = firstName + lastName + email;

            // Check if any substring of length 3 or more exists in the password
            for (var i = 0; i < combinedStrings.length - 2; i++) {
                var substring = combinedStrings.substring(i, i + 3).toLowerCase();
                if (password.toLowerCase().includes(substring)) {
                    return true;
                }
            }

            return false;
        }
    </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the input element
            var inputElement = document.getElementById('license_issue_date');

            // Get the span element for displaying the formatted date
            var formattedDateElement = document.getElementById('formattedDate');
            // Add an event listener to handle the change in the input value
            inputElement.addEventListener('change', function() {
                // Get the selected date from the input
                var selectedDate = inputElement.value;

                // Convert the selected date to a JavaScript Date object
                var dateObject = new Date(selectedDate);

                // Format the date as "dd mmm yyyy"
                var formattedDate = dateObject.getDate() + ' ' + getMonthAbbreviation(dateObject
                    .getMonth()) + ' ' + dateObject.getFullYear();

                // Display the formatted date
                formattedDateElement.textContent = formattedDate;
            });

            // Function to get the abbreviated month name
            function getMonthAbbreviation(monthIndex) {
                var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return months[monthIndex];
            }
        });
    </script> --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all date input elements
            var dateInputs = document.querySelectorAll('input[type="date"]');

            // Iterate through each date input
            dateInputs.forEach(function(inputElement) {
                // Create a new span element for each input
                var newSpan = document.createElement('span');

                // Set the content of the new span
                newSpan.textContent = ''; // Initially empty, will be populated on input change

                // Add a class or style to the new span as needed
                newSpan.classList.add('text-muted'); // Add your custom class here

                // Set the dir attribute to auto for automatic text direction
                newSpan.setAttribute('dir', 'auto');

                // Append the new span after the label
                inputElement.parentNode.appendChild(newSpan);

                // Add an event listener to handle the change in the input value
                inputElement.addEventListener('change', function() {
                    // Get the selected date from the input
                    var selectedDate = inputElement.value;

                    // Convert the selected date to a JavaScript Date object
                    var dateObject = new Date(selectedDate);

                    // Format the date as "dd mmm yyyy"
                    var formattedDate = dateObject.getDate() + ' ' + getMonthAbbreviation(dateObject
                        .getMonth()) + ' ' + dateObject.getFullYear();

                    // Set the content of the span with the formatted date
                    newSpan.textContent = formattedDate;
                });
            });

            // Function to get the abbreviated month name
            function getMonthAbbreviation(monthIndex) {
                var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return months[monthIndex];
            }
        });


    </script> --}}
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                dateFormat: 'dd M yy', // Setting the display format
                changeYear: true,      // Enable year dropdown
                 yearRange: "-100:+10"  // Optional: specify the range of years available
            });

        });
    </script>

@endsection
