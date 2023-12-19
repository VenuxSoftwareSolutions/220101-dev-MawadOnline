@extends('frontend.layouts.app')
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
    margin-bottom: 8px ;
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
                                            href="#personal-info">Personal Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="code-verification-tab" data-toggle="tab"
                                            href="#code-verification">Code Verification Email</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" id="business-info-tab" data-toggle="tab"
                                        href="#business-info">Business Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-person-tab" data-toggle="tab"
                                        href="#contact-person">Contact Person</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="warehouses-tab" data-toggle="tab"
                                        href="#warehouses">Warehouses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="payout-info-tab" data-toggle="tab" href="#payout-info">Payout
                                        Information</a>
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
                                                            value="{{ old('name') }}"
                                                          id="first_name"  placeholder="{{ translate('First Name') }}" name="first_name"
                                                            required>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ translate('Last name') }} <span
                                                                class="text-primary">*</span></label>
                                                        <input type="text" class="form-control rounded-0"
                                                          id="last_name"   placeholder="{{ translate('Last name') }}" name="last_name"
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
                                                    {{-- onclick="switchTab('code-verification')" --}}>Next</button>
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
                                                        <small class="text-muted">A 6-digit code has been sent to your
                                                            email.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button id="verifyCodeBtn" type="button"
                                                    class="btn btn-primary fw-600 rounded-0"
                                                    {{-- onclick="switchTab('business-info')" --}}>Next</button>
                                                <button id="resendCodeBtn" type="button"
                                                    class="btn btn-secondary fw-600 rounded-0">Resend Code</button>

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
                                                                    class="text-primary">*</span></label>
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
                                                                    class="text-primary">*</span></label>

                                                            <input required type="date" class="form-control rounded-0"
                                                                placeholder="{{ translate('License Issue Date') }}"
                                                                value="{{ $user->business_information->license_issue_date ?? '' }}"
                                                                name="license_issue_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('License Expiry Date') }} <span
                                                                    class="text-primary">*</span></label>

                                                            <input required type="date" class="form-control rounded-0"
                                                                value="{{ $user->business_information->license_expiry_date ?? '' }}"
                                                                placeholder="{{ translate('License Expiry Date') }}"
                                                                name="license_expiry_date">
                                                        </div>
                                                    </div>
                                                    @if(isset($user->business_information) && !empty($user->business_information->state))
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('State/Emirate') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <select required name="state" class="form-control rounded-0"
                                                                id="emirateempire">
                                                                <option value="" >Please Choose !!</option>
                                                                <option
                                                                @if ($user->business_information->state == 1) selected @endif
                                                                value="1">Abu dhabi</option>
                                                            <option
                                                                @if ($user->business_information->state == 2) selected @endif
                                                                value="2">Ajman</option>
                                                            <option
                                                                @if ($user->business_information->state == 3) selected @endif
                                                                value="3">Sharjah</option>
                                                            <option
                                                                @if ($user->business_information->state == 4) selected @endif
                                                                value="4">Dubai</option>
                                                            <option
                                                                @if ($user->business_information->state == 5) selected @endif
                                                                value="5">Fujairah</option>
                                                            <option
                                                                @if ($user->business_information->state == 6) selected @endif
                                                                value="6">ras al khaimah</option>
                                                            <option
                                                                @if ($user->business_information->state == 7) selected @endif
                                                                value="7">Umm Al-Quwain</option>

                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Area') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <select required name="area_id" class="form-control rounded-0"
                                                                id="areaempire">
                                                                @php
                                                                $areas=App\Models\Area::where('emirate_id',$user->business_information->state)->get() ;
                                                            @endphp
                                                            <option value="" selected>Please Choose
                                                                !!</option>
                                                                @foreach ( $areas as $area )
                                                                <option value="{{$area->id}}" @if ($area->id == $user->business_information->area_id )
                                                                    selected
                                                                @endif >{{$area->name}}</option>
                                                                @endforeach


                                                            </select>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('State/Emirate') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <select required name="state" class="form-control rounded-0"
                                                                id="emirateempire">
                                                                <option value="" selected>Please Choose !!</option>
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
                                                            <label>{{ translate('Area') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <select required name="area_id" class="form-control rounded-0"
                                                                id="areaempire">
                                                                <option value="" selected>Please Choose !!</option>


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
                                                                    class="text-primary">*</span></label>
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
                                                                    class="text-primary">*</span></label>
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
                                                            <label>{{ translate('Civil Denfense Approval') }} <span
                                                                    class="text-primary"></span></label>
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
                                            <button type="button" class="btn btn-secondary fw-600 rounded-0 save-as-draft" data-action="save-as-draft">Save as Draft</button>

                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                {{-- onclick="switchTab('contact-person')" --}}>Next</button>

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
                                                            <input type="text" class="form-control rounded-0"
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
                                                                if (isset($user->contact_people->last_name) && !empty($user->contact_people->first_name)) {
                                                                    $lastName = $user->contact_people->last_name;
                                                                } elseif (isset($user->last_name)) {
                                                                    $lastName = $user->last_name;
                                                                }

                                                            @endphp
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Last Name') }}"
                                                                value="{{ $lastName }}" name="last_name" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Email') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="email" class="form-control rounded-0"
                                                                placeholder="{{ translate('Email') }}"
                                                                value="{{ $user->contact_people->email ?? '' }}"
                                                                name="email" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Mobile Phone') }} <span
                                                                    class="text-primary">*</span></label>
                                                                    <small class="text-muted">Example: +971123456789</small>

                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Mobile Phone') }}"
                                                                value="{{ $user->contact_people->mobile_phone ?? '+971' }}"
                                                                name="mobile_phone" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Additional Mobile Phone') }} <span
                                                                    class="text-primary"></span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Additional Mobile Phone') }}"
                                                                value="{{ $user->contact_people->additional_mobile_phone ?? '' }}"
                                                                name="additional_mobile_phone">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Nationality') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <br>
                                                            <select name="nationality" class="selectpicker countrypicker"
                                                           @if(isset($user->contact_people) && !empty($user->contact_people->nationality)) data-default="{{$user->contact_people->nationality}}" @endif data-flag="true"></select>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Date Of Birth') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="date" class="form-control rounded-0"
                                                                placeholder="{{ translate('Date Of Birth') }}"
                                                                value="{{ $user->contact_people->date_of_birth ?? '' }}"
                                                                name="date_of_birth" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Emirates ID - Number') }} <span
                                                                    class="text-primary">*</span></label>
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
                                                            <input type="date" class="form-control rounded-0"
                                                                placeholder="{{ translate('Emirates ID - Expiry Date') }}"
                                                                value="{{ $user->contact_people->emirates_id_expiry_date ?? '' }}"
                                                                required name="emirates_id_expiry_date">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Emirates ID') }} <span
                                                                    class="text-primary">*</span></label>
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
                                            <button type="button" class="btn btn-secondary fw-600 rounded-0 save-as-draft" data-action="save-as-draft">Save as Draft</button>

                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                {{-- onclick="switchTab('warehouses')" --}}>Next</button>
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
                                                {{-- <div class="container mt-4">

                                                    <table class="table" id="warehouseTable">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Warehouse Name</th>
                                                                <th>State/Emirate</th>
                                                                <th>Area</th>
                                                                <th>Street</th>
                                                                <th>Building</th>
                                                                <th>Unit/Office No.</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (isset($user))
                                                            @foreach ($user->warehouses as $warehouse)
                                                                <tr>
                                                                    <td><input value="{{ $warehouse->warehouse_name }}"
                                                                            type="text" class="form-control"
                                                                            name="warehouse_name[]" required></td>
                                                                    <td>

                                                                        <select required name="state[]"
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
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control areaSelect"
                                                                            name="area[]" required>
                                                                            @php
                                                                                $areas=App\Models\Area::where('emirate_id',$warehouse->emirate_id)->get() ;
                                                                            @endphp
                                                                            <option value="" selected>Please Choose
                                                                                !!</option>
                                                                                @foreach ( $areas as $area )
                                                                                <option value="{{$area->id}}" @if ($area->id == $warehouse->area_id  )
                                                                                    selected
                                                                                @endif >{{$area->name}}</option>
                                                                                @endforeach

                                                                            <!-- Options for area -->
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" class="form-control"
                                                                        value="{{$warehouse->address_street}}" name="street[]" required></td>
                                                                    <td><input type="text" class="form-control"
                                                                        value="{{$warehouse->address_building}}"   name="building[]" required></td>
                                                                    <td><input type="text" class="form-control"
                                                                        value="{{$warehouse->address_unit}}"  name="unit[]" required></td>
                                                                    <td><button type="button"
                                                                            class="btn btn-danger removeRow">Remove</button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            @endif
                                                            @if(!isset($user->warehouses) || isset($user->warehouses) && count($user->warehouses)==0  )
                                                            <tr>
                                                                <td><input type="text" class="form-control"
                                                                        name="warehouse_name[]" required></td>
                                                                <td>

                                                                    <select required name="state[]"
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
                                                                </td>
                                                                <td>
                                                                    <select class="form-control areaSelect" name="area[]"
                                                                        required>
                                                                        <option value="" selected>Please Choose !!
                                                                        </option>
                                                                        <!-- Options for area -->
                                                                    </select>
                                                                </td>
                                                                <td><input type="text" class="form-control"
                                                                        name="street[]" required></td>
                                                                <td><input type="text" class="form-control"
                                                                        name="building[]" required></td>
                                                                <td><input type="text" class="form-control"
                                                                        name="unit[]" required></td>
                                                                <td><button type="button"
                                                                        class="btn btn-danger removeRow">Remove</button>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>

                                                    <button type="button" class="btn btn-primary" id="addRow">Add
                                                        Row</button>
                                                </div> --}}
                                                <div id="warehouseRowsContainer">
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
                                                                    <select required name="state[]"
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
                                                                    name="area[]" required>
                                                                    @php
                                                                        $areas=App\Models\Area::where('emirate_id',$warehouse->emirate_id)->get() ;
                                                                    @endphp
                                                                    <option value="" selected>Please Choose
                                                                        !!</option>
                                                                        @foreach ( $areas as $area )
                                                                        <option value="{{$area->id}}" @if ($area->id == $warehouse->area_id  )
                                                                            selected
                                                                        @endif >{{$area->name}}</option>
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
                                                                    value="{{$warehouse->address_street}}" name="street[]" required>                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="building">Building<span
                                                                    class="text-primary">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                    value="{{$warehouse->address_building}}"   name="building[]" required>                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="unit">Unit/Office No.<span
                                                                    class="text-primary">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                    value="{{$warehouse->address_unit}}"  name="unit[]" required>                                                            </div>
                                                        </div>


                                                        <div class="col-auto ml-auto">
                                                            <button type="button" class="btn btn-outline-danger removeRow">
                                                                Remove Warehouse <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                    @if(!isset($user->warehouses) || isset($user->warehouses) && count($user->warehouses)==0  )

                                                <div class="row warehouseRow" id="warehouseRows">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="warehouse_name">Warehouse Name<span
                                                                class="text-primary">*</span></label>
                                                            <input type="text" class="form-control" name="warehouse_name[]" required>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="state">State/Emirate<span
                                                                class="text-primary">*</span></label>
                                                                <select required name="state[]"
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
                                                              <select class="form-control areaSelect" name="area[]"
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
                                                            <input type="text" class="form-control" name="street[]" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="building">Building<span
                                                                class="text-primary">*</span></label>
                                                            <input type="text" class="form-control" name="building[]" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="unit">Unit/Office No.<span
                                                                class="text-primary">*</span></label>
                                                            <input type="text" class="form-control" name="unit[]" required>
                                                        </div>
                                                    </div>


                                                    <div class="col-auto ml-auto">
                                                        <button type="button" class="btn btn-outline-danger removeRow">
                                                            Remove Warehouse <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endif

                                              </div>

                                                <div class="row">
                                                    <div class="col-auto mx-auto text-center">
                                                        <button type="button" class="btn btn-primary addWarehouse" id="addRow">Add
                                                            Warehouse</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="text-right">
                                            <button type="button" class="btn btn-secondary fw-600 rounded-0 save-as-draft" data-action="save-as-draft">Save as Draft</button>

                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                               {{--  onclick="switchTab('payout-info')" --}}>Next</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="payout-info">
                                    <form id="payoutInfoForm" class="" action="{{ route('shops.payout_info') }}"
                                    data-next-tab="payout-info"  method="POST">
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
                                                                    class="text-primary">*</span></label>
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
                                            <button type="button" class="btn btn-secondary fw-600 rounded-0 save-as-draft" data-action="save-as-draft">Save as Draft</button>

                                            <button type="submit" class="btn btn-primary fw-600 rounded-0">Register Your
                                                Shop</button>
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
       $('#addRow').on('click', function () {
            // Clone the entire warehouseRows section
            var newWarehouseRows = $('#warehouseRows').clone();

            // Show the "Remove Warehouse" button in the new row
            newWarehouseRows.find('.removeRow').show();
            newWarehouseRows.find('input,select').val('');
            newWarehouseRows.find('input, select').removeClass('is-invalid is-valid') ;
            // Append the new row to the warehouseRowsContainer
            $('#warehouseRowsContainer').append(newWarehouseRows);
        });

        // Remove Warehouse button click event
        $('#warehouseRowsContainer').on('click', '.removeRow', function () {
            // Check if there is at least one warehouse row
            if ($('#warehouseRowsContainer .row').length > 1) {
                // Remove the closest warehouse row
                $(this).closest('.row').remove();
            } else {
                // Display a message or take appropriate action when there's only one row left
                toastr.error('Cannot remove the last warehouse.');
            }
        });


            $('#registerTabs a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            $('#registerTabsContent').find('.tab-pane button:not(#resendCodeBtn,#addRow,.removeRow)').on('click', function(e) {
            // // Iterate over each warehouse row




               var shouldContinue = true;  // Initialize the boolean variable
                var clickedButton = e.target;


                e.preventDefault();
                var form = $(this).closest('form');
                if (form.attr('id') == 'warehousesForm') {
                $('#warehouseRowsContainer .warehouseRow').each(function () {
                            var warehouseInputs = $(this).find('input, select');
                            var isEmpty = true;

                            // Check if all input fields are empty
                            warehouseInputs.each(function () {
                                if ($(this).val() !== '') {
                                    isEmpty = false;
                                    return false; // Exit the loop if a non-empty field is found
                                }
                            });

                            // If the warehouse is empty, remove the row
                            if (isEmpty && $('#warehouseRowsContainer .warehouseRow').length>1) {
                                $(this).remove();
                            }
                        });
                }

                var formData = new FormData(form[0]); // Create FormData object from the form
                if ($(clickedButton).hasClass('save-as-draft')) {
                    var action = $(clickedButton).data('action');
                    formData.append('action',action) ;
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
                        }
                        // Handle success, e.g., show a message
                        if (response.hasOwnProperty('finish') && response.finish === true) {
                            location.reload();

                        }
                        if (response.hasOwnProperty('verif_login') && response.verif_login === true) {

                            $('#personal-info-tab, #code-verification-tab').addClass('disabled');
                            $('#personal-info, #code-verification').addClass('disabled');

                            $('#registerTabs a[data-toggle="tab"]').on('click', function (e) {
                                e.preventDefault();
                            });
                        }

                        // Switch to the next tab if the save operation is successful
                        if (response.success) {
                            // switchTab('code-verification'); // Change the tab ID accordingly
                            var nextTabId = form.data(
                                'next-tab'
                            ); // Assuming you set a data attribute on the form with the next tab ID
                            // if (form.attr('id') != 'warehousesForm') {
                            displayValidation(form) ;
                            // }
                            switchTab(nextTabId);

                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON.hasOwnProperty('loginFailed')) {
                // Display login failure message using JavaScript
                             toastr.error(xhr.responseJSON.loginFailed);
                             shouldContinue = false;

                        }
                        // Handle errors, e.g., show validation errors
                        var errors = xhr.responseJSON.errors;

                        // Display validation errors in the form
                        if (form.attr('id') != 'warehousesForm' && shouldContinue != false) {
                        displayValidationErrors(errors, form);
                        }
                        if (form.attr('id') == 'warehousesForm') {
                            toastr.error('Fill up the rest of the table for warehousesForm');
                        }
                    }
                });
            });

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

                        if (xhr.responseJSON.hasOwnProperty('loginFailed')) {
                            // Display the error message using Toastr.js
                            toastr.error(xhr.responseJSON.loginFailed);
                        }
                        // // Handle errors, e.g., show validation errors
                        // var errors = xhr.responseJSON.errors;
                        // // Update this part based on your error handling needs
                        // alert('Error: ' + JSON.stringify(errors));
                    }
                });
            });



            function displayValidationErrors(errors, form) {
                // Clear existing error messages and success styles
                form.find('.invalid-feedback').remove();
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.is-valid').removeClass('is-valid');

                // Clear global validation error messages
                $('#validation-errors').empty().hide();

                // Display new error messages
                $.each(errors, function(field, messages) {
                    var inputField = form.find('[name="' + field + '"]');
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
                });

                // Highlight fields without errors
                form.find('.form-control').each(function() {
                    var inputField = $(this);

                    if (!inputField.hasClass('is-invalid') && inputField.val() !== '') {
                        inputField.addClass('is-valid');
                    }
                });

                $('#validation-errors').html('Validation errors occurred. Please check the form.').show();
            }

            function displayValidation( form) {

                form.find('.form-control').each(function() {
                    var inputField = $(this);

                    if (inputField.val() !== '' || inputField.parent().find('a').hasClass('old_file') ) {

                        inputField.removeClass('is-invalid');
                        inputField.addClass('is-valid');
                    }
                });
            }

            function switchTab(tabId) {

                $('#registerTabs').find('.nav-link').removeClass('active');
                $('#registerTabsContent').find('.tab-pane').removeClass('show active');

                $('#' + tabId + '-tab').addClass('active');
                $('#' + tabId).addClass('show active');
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
                                var name = response['data'][i].name;

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
                        '<option value="" selected>Please Choose !!</option>');

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
                                var name = response['data'][i].name;

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
        $('#password').on('input', function () {
            $('#password-strength').css('border', '1px solid #ddd');
            $('#password-strength').css('padding', '10px');

            var password = $(this).val();
            var strengthMeter = $('#password-strength');

            // Password strength rules
            var rules = {
                "Minimum length of 9 characters": password.length >= 9,
                "At least one uppercase letter": /[A-Z]/.test(password),
                "At least one lowercase letter": /[a-z]/.test(password),
                // "At least one number": /\d/.test(password),
                "At least one special character": /[@$!%*?&]/.test(password),
                "At least one number and Max Four Numbers": /^\D*(\d\D*){1,4}$/.test(password),
                // maxConsecutiveChars: !/(.)\1\1/.test(password),
                // maxPercentage: calculateMaxPercentage(password),
                "No spaces allowed": !/\s/.test(password),
                "No three consecutive numbers, Example 678,543,987": !/(012|123|234|345|456|567|678|789)/.test(password),
                "No three characters or more can be a substring of first name, last name, or email": !checkSubstring(password)
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
@endsection
