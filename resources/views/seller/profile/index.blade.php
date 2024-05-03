@extends('seller.layouts.app')
@php
    use Carbon\Carbon;
@endphp

<style>
    @media (max-width: 540px) {
 ul.nav.nav-tabs.shop {
        background: #f8f9fa;
        margin: 0;
        display: block !important;
        justify-content: space-between;
        align-items: center;
}
}

@media (min-width:822px) and (max-width:1198px ) {
    ul.nav.nav-tabs.shop {
        background: #f8f9fa;
        margin: 0;
        display: block !important;
        justify-content: space-between;
        align-items: center;
}
}

@media (min-width:579px) and (max-width:805px ) {
    ul.nav.nav-tabs.shop {
        background: #f8f9fa;
        margin: 0;
        display: block !important;
        justify-content: space-between;
        align-items: center;
}
}

    .color-modified {
        border: 2px dashed #e8c068e8 !important; /* Yellow dashed border */
        box-shadow: 0 0 5px rgba(255, 204, 0, 0.5) !important; /* Yellow shadow */
        transition: border-color 0.3s ease, box-shadow 0.3s ease !important; /* Smooth transition */
        /* Your desired border color */
    }

    .color-modified-file {

        color: #e8c068e8
        /* Your desired border color */
    }
       /* Custom CSS for highlighted tabs */
       .highlighted-tab {
        border-color: red !important;
    }
    .btn-info {
        background-color: var(--primary) !important; /* Use the global variable */
        border-color: var(--primary) !important;

    }
    .btn-primary {
        background-color: #CB774B !important; /* Use the global variable */
        border-color: #CB774B !important;

    }
    .swal2-confirm {
        background-color: var(--success) !important; /* Use the global variable */
        border-color: none !important;

    }
    .swal2-confirm:hover {
        border-color: none !important;

    }

    .orange-text{
    color: #CB774B;

    }

    .Grand-title{
    padding-left: 0px !important;
    }

    .custom-file-input:lang(en)~.custom-file-label::after {
    content: "Browse";
    background-color: #CB774B;
    color:#fff
}
</style>
@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Manage Profile') }}</h1>
            </div>
        </div>
    </div>
    {{-- <form action="{{ route('seller.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        <input name="_method" type="hidden" value="POST">
        @csrf
        <!-- Basic Info-->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Basic Info')}}</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="name">{{ translate('Your Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" name="name" value="{{ $user->name }}" id="name" class="form-control" placeholder="{{ translate('Your Name') }}" required>
                        @error('name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="phone">{{ translate('Your Phone') }}</label>
                    <div class="col-md-10">
                        <input type="text" name="phone" value="{{ $user->phone }}" id="phone" class="form-control" placeholder="{{ translate('Your Phone')}}">
                        @error('phone')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('Photo') }}</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="photo" value="{{ $user->avatar_original }}" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="password">{{ translate('Your Password') }}</label>
                    <div class="col-md-10">
                        <input type="password" name="new_password" id="password" class="form-control" placeholder="{{ translate('New Password') }}">
                        @error('new_password')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="confirm_password">{{ translate('Confirm Password') }}</label>
                    <div class="col-md-10">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="{{ translate('Confirm Password') }}" >
                        @error('confirm_password')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <!-- Payment System -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Payment Setting')}}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <label class="col-md-3 col-form-label">{{ translate('Cash Payment') }}</label>
                    <div class="col-md-9">
                        <label class="aiz-switch aiz-switch-success mb-3">
                            <input value="1" name="cash_on_delivery_status" type="checkbox" @if ($user->shop->cash_on_delivery_status == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3 col-form-label">{{ translate('Bank Payment') }}</label>
                    <div class="col-md-9">
                        <label class="aiz-switch aiz-switch-success mb-3">
                            <input value="1" name="bank_payment_status" type="checkbox" @if ($user->shop->bank_payment_status == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3 col-form-label" for="bank_name">{{ translate('Bank Name') }}</label>
                    <div class="col-md-9">
                        <input type="text" name="bank_name" value="{{ $user->shop->bank_name }}" id="bank_name" class="form-control mb-3" placeholder="{{ translate('Bank Name')}}">
                        @error('phone')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3 col-form-label" for="bank_acc_name">{{ translate('Bank Account Name') }}</label>
                    <div class="col-md-9">
                        <input type="text" name="bank_acc_name" value="{{ $user->shop->bank_acc_name }}" id="bank_acc_name" class="form-control mb-3" placeholder="{{ translate('Bank Account Name')}}">
                        @error('bank_acc_name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3 col-form-label" for="bank_acc_no">{{ translate('Bank Account Number') }}</label>
                    <div class="col-md-9">
                        <input type="text" name="bank_acc_no" value="{{ $user->shop->bank_acc_no }}" id="bank_acc_no" class="form-control mb-3" placeholder="{{ translate('Bank Account Number')}}">
                        @error('bank_acc_no')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3 col-form-label" for="bank_routing_no">{{ translate('Bank Routing Number') }}</label>
                    <div class="col-md-9">
                        <input type="number" name="bank_routing_no" value="{{ $user->shop->bank_routing_no }}" id="bank_routing_no" lang="en" class="form-control mb-3" placeholder="{{ translate('Bank Routing Number')}}">
                        @error('bank_routing_no')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-0 text-right">
            <button type="submit" class="btn btn-primary">{{translate('Update Profile')}}</button>
        </div>
    </form>

    <br>

    <!-- Address -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Address')}}</h5>
        </div>
        <div class="card-body">
            <div class="row gutters-10">
                @foreach ($addresses as $key => $address)
                    <div class="col-lg-4">
                        <div class="border p-3 pr-5 rounded mb-3 position-relative">
                            <div>
                                <span class="w-50 fw-600">{{ translate('Address') }}:</span>
                                <span class="ml-2">{{ $address->address }}</span>
                            </div>
                            <div>
                                <span class="w-50 fw-600">{{ translate('Postal Code') }}:</span>
                                <span class="ml-2">{{ $address->postal_code }}</span>
                            </div>
                            <div>
                                <span class="w-50 fw-600">{{ translate('City') }}:</span>
                                <span class="ml-2">{{ optional($address->city)->name }}</span>
                            </div>
                            <div>
                                <span class="w-50 fw-600">{{ translate('State') }}:</span>
                                <span class="ml-2">{{ optional($address->state)->name }}</span>
                            </div>
                            <div>
                                <span class="w-50 fw-600">{{ translate('Country') }}:</span>
                                <span class="ml-2">{{ optional($address->country)->name }}</span>
                            </div>
                            <div>
                                <span class="w-50 fw-600">{{ translate('Phone') }}:</span>
                                <span class="ml-2">{{ $address->phone }}</span>
                            </div>
                            @if ($address->set_default)
                                <div class="position-absolute right-0 bottom-0 pr-2 pb-3">
                                    <span class="badge badge-inline badge-primary">{{ translate('Default') }}</span>
                                </div>
                            @endif
                            <div class="dropdown position-absolute right-0 top-0">
                                <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">
                                    <i class="la la-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" onclick="edit_address('{{$address->id}}')">
                                        {{ translate('Edit') }}
                                    </a>
                                    @if (!$address->set_default)
                                        <a class="dropdown-item" href="{{ route('seller.addresses.set_default', $address->id) }}">{{ translate('Make This Default') }}</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('seller.addresses.destroy', $address->id) }}">{{ translate('Delete') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-4 mx-auto" onclick="add_new_address()">
                    <div class="border p-3 rounded mb-3 c-pointer text-center bg-light">
                        <i class="la la-plus la-2x"></i>
                        <div class="alpha-7">{{ translate('Add New Address') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Email -->
    <form action="{{ route('user.change.email') }}" method="POST">
        @csrf
        <div class="card">
          <div class="card-header">
              <h5 class="mb-0 h6">{{ translate('Change your email')}}</h5>
          </div>
          <div class="card-body">
              <div class="row">
                  <div class="col-md-2">
                      <label>{{ translate('Your Email') }}</label>
                  </div>
                  <div class="col-md-10">
                      <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="{{ translate('Your Email')}}" name="email" value="{{ $user->email }}" />
                        <div class="input-group-append">
                           <button type="button" class="btn btn-outline-secondary new-email-verification">
                               <span class="d-none loading">
                                   <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ translate('Sending Email...') }}
                               </span>
                               <span class="default">{{ translate('Verify') }}</span>
                           </button>
                        </div>
                      </div>
                      <div class="form-group mb-0 text-right">
                          <button type="submit" class="btn btn-primary">{{translate('Update Email')}}</button>
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </form> --}}

    <div class="card">
        <div class="card-body">


            <ul class="nav nav-tabs shop" id="registerTabs">
                <li class="nav-item">
                    <a class="nav-link active" id="personal-info-tab" data-toggle="tab"
                        href="#personal-info">{{ translate('Personal Info') }}</a>
                </li>
                @if ($user->id == $user->owner_id)
                <li class="nav-item">
                    <a class="nav-link" id="business-info-tab" data-toggle="tab"
                        href="#business-info">{{ translate('Business Information') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-person-tab" data-toggle="tab"
                        href="#contact-person">{{ translate('Contact Person') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="payout-info-tab" data-toggle="tab"
                        href="#payout-info">{{ translate('Payout Information') }}</a>
                </li>
                @endif

            </ul>
            <div class="tab-content" id="registerTabsContent">

                <div class="tab-pane fade show active" id="personal-info">

                    <form method="POST" action="{{route('seller.personal-info.update')}}">
                    @csrf
                    <!-- ... Personal Info form fields ... -->
                    <div class="bg-white border mb-4">
                        <div class="fs-20 fw-600 p-3 orange-text">
                            {{ translate('Personal Info') }}
                        </div>

                        {{-- <div id="validation-errors" class="alert alert-danger" style="display: none;">
                        </div> --}}
                        @if ($proposedPayoutChange)
                        <!-- Display message indicating pending review -->
                        <div class="alert alert-info">
                            {{ __('messages.admin_review_message') }}
                        </div>
                        @endif
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                         @endif

                        <div class="p-3">
                            <div class="form-group">
                                <label><b>{{ translate('First Name') }} </b><span class="text-primary">*</span></label>
                                <input type="text" class="form-control rounded-0"
                                    value="{{ auth()->check() ? auth()->user()->first_name : old('first_name_personal') }}"
                                    id="first_name" placeholder="{{ translate('First Name') }}" name="first_name_personal" required>
                                    @error('first_name_personal')
                                    <div class="text-danger">{{ $message }}</div>
                                     @enderror
                            </div>
                            <div class="form-group">
                                <label><b>{{ translate('Last name') }} </b><span class="text-primary">*</span></label>
                                <input type="text" class="form-control rounded-0" id="last_name"
                                    value="{{ auth()->check() ? auth()->user()->last_name : old('last_name_personal') }}"
                                    placeholder="{{ translate('Last name') }}" name="last_name_personal" required>
                                    @error('last_name_personal')
                                    <div class="text-danger">{{ $message }}</div>
                                     @enderror
                            </div>
                            <div class="form-group">
                                <label><b>{{ translate('Email') }}</b> <span class="text-primary">*</span></label>
                                <input disabled  type="text" class="form-control rounded-0"
                                    value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                    placeholder="{{ translate('Email') }}" name="email" >
                                    <div style="color: #CB774B;">
                                        Email cannot be changed
                                    </div>
                            </div>

                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary fw-600 rounded-0"
                            {{-- onclick="switchTab('business-info')" --}}>{{ translate('Save') }}</button>
                    </div>
                    </form>
                </div>
                @if ($user->id == $user->owner_id)
                <div class="tab-pane fade" id="business-info">
                    <form id="profileForm" class="" action="{{ route('seller.profile.seller.update', $user->id) }}"
                        method="POST" enctype="multipart/form-data" data-next-tab="contact-person">
                        @csrf
                        <!-- ... Business Info form fields ... -->

                        <div class="bg-white border mb-4">
                            <div class="fs-20 fw-600 p-3 orange-text">
                                {{ translate('Business Information') }}
                            </div>

                            {{-- <div id="validation-errors" class="alert alert-danger"
                            style="display: none;"></div> --}}
                            <?php
                            // $proposedPayoutChange = App\Models\ProposedPayoutChange::where('user_id', $user->id)
                            //     ->latest()
                            //     ->first();
                                $trade_name_english = $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_name_english')
                                ? $proposedPayoutChange->getNewValue('trade_name_english')
                                : $user->business_information->getTranslation('trade_name', 'en', false) ?? '';
                                $trade_name_arabic = $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_name_arabic')
                                ? $proposedPayoutChange->getNewValue('trade_name_arabic')
                                : $user->business_information->getTranslation('trade_name', 'ar', false) ?? '';

                                 $trade_license_doc = $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_license_doc') ? $proposedPayoutChange->getNewValue('trade_license_doc') : $user->business_information->trade_license_doc ?? '';
                                 $eshop_name_english = $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_english')
                                ? $proposedPayoutChange->getNewValue('eshop_name_english')
                                : $user->business_information->getTranslation('eshop_name', 'en', false) ?? '';
                                $eshop_name_arabic = $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_arabic')
                                ? $proposedPayoutChange->getNewValue('eshop_name_arabic')
                                : $user->business_information->getTranslation('eshop_name', 'ar', false) ?? '';
                                $eshop_desc_english = $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_english')
                                ? $proposedPayoutChange->getNewValue('eshop_desc_english')
                                : $user->business_information->getTranslation('eshop_desc', 'en', false) ?? '';
                                $eshop_desc_arabic = $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_arabic')
                                ? $proposedPayoutChange->getNewValue('eshop_desc_arabic')
                                : $user->business_information->getTranslation('eshop_desc', 'ar', false) ?? '';
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
                                            <label><b>{{ translate('English Trade Name') }} </b><span
                                                    class="text-primary">*</span></label>
                                            <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_name_english') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('English Trade Name') }}"
                                                value="{{$trade_name_english }}"
                                                name="trade_name_english" required>
                                                @error('trade_name_english')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('Arabic Trade Name') }} </b><span
                                                    class="text-primary">*</span></label>
                                            <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_name_arabic') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('Arabic Trade Name') }}"
                                                value="{{$trade_name_arabic }}"
                                                name="trade_name_arabic" required>
                                                @error('trade_name_arabic')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><b>{{ translate('Trade License Doc') }} </b><span
                                                    class="text-primary">*</span></label>


                                            @if ($trade_license_doc)
                                                <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trade_license_doc') ? 'color-modified-file' : '' }}"
                                                    href="{{ static_asset($trade_license_doc) }}"
                                                    target="_blank">{{ translate('View Trade License Doc') }}</a>
                                                <input type="hidden" name="trade_license_doc"
                                                    value="{{ $trade_license_doc }}">
                                            @endif

                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control custom-file-input" id="trade_license_doc_input"
                                                        name="trade_license_doc" placeholder="{{ translate('Trade License Doc') }}" required>

                                                        <label class="custom-file-label" for="trade_license_doc_input">{{ translate('Choose a file') }}</label>
                                                </div>
                                            </div>
                                            @error('trade_license_doc')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                            <small>{{ translate('Max file size is 5MB and accepted file types are PDF and image formats.') }}</small>





                                            {{-- <div class="custom-file">
                                        <input name="trade_license_doc" type="file" class="custom-file-input" id="inputGroupFile01">
                                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                      </div> --}}

                                        </div>

                                    </div>
                                    <div class="col-md-12" style="padding-left: 0px">
                                        <div class="fs-20 fw-600 p-3 orange-text">
                                            E-Shop Information
                                        </div>   </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('English E-shop Name') }}</b> <span
                                                    class="text-primary">*</span></label>
                                            <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_english') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('English E-shop Name') }}"
                                                value="{{ $eshop_name_english }}"
                                                name="eshop_name_english" required>
                                                @error('eshop_name_english')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('Arabic E-shop Name') }} </b><span
                                                    class="text-primary">*</span></label>
                                            <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_name_arabic') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('Arabic E-shop Name') }}"
                                                value="{{$eshop_name_arabic }}"
                                                name="eshop_name_arabic" required>
                                                @error('eshop_name_arabic')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('English e-Shop description') }} </b><span
                                                    class="text-primary"></span></label>

                                            <textarea class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_english') ? 'color-modified' : '' }}" placeholder="{{ translate('English e-Shop description') }}"
                                                name="eshop_desc_english">{{ $eshop_desc_english }}</textarea>
                                                @error('eshop_desc_english')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('Arabic e-Shop description') }}</b> <span
                                                    class="text-primary"></span></label>

                                            <textarea class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('eshop_desc_arabic') ? 'color-modified' : '' }}" placeholder="{{ translate('Arabic e-Shop description') }}"
                                                name="eshop_desc_arabic">{{ $eshop_desc_arabic  }}</textarea>
                                                @error('eshop_desc_arabic')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 Grand-title">
                                        <div class="fs-20 fw-600 p-3 orange-text">
                                               License Information
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('License Issue Date') }} </b><span
                                                    class="text-primary">*</span>
                                            </label>

                                            <input dir="auto" required type="{{-- date --}}text"
                                                class="datepicker form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('license_issue_date') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('License Issue Date') }}"
                                                id="license_issue_date"
                                                value="{{ $license_issue_date }}"
                                                name="license_issue_date">
                                                @error('license_issue_date')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('License Expiry Date') }} </b><span
                                                    class="text-primary">*</span></label>

                                            <input dir="auto" required type="text"
                                                class="datepicker form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('license_expiry_date') ? 'color-modified' : '' }}" {{-- value="{{ $user->business_information->license_expiry_date ?? '' }}" --}}
                                                value="{{ $license_expiry_date }}"
                                                placeholder="{{ translate('License Expiry Date') }}"
                                                name="license_expiry_date">
                                                @error('license_expiry_date')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 Grand-title">
                                        <div class="fs-20 fw-600 p-3 orange-text">
                                            Location Information
                                        </div>
                                    </div>

                                    {{-- @if (isset($user->business_information) && !empty($user->business_information->state)) --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><b>{{ translate('State/Emirate') }}</b> <span
                                                        class="text-primary">*</span></label>
                                                <select required name="state" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('state') ? 'color-modified' : '' }}"
                                                    id="emirateempire">
                                                    <option value="">{{ translate('please_choose') }}</option>
                                                    @foreach ($emirates as $emirate)
                                                        <option value="{{ $emirate->id }}"
                                                            @if ( $state == $emirate->id) selected @endif>
                                                            {{ $emirate->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('state')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><b>{{ translate('Area') }} </b><span class="text-primary">*</span></label>
                                                <select required name="area_id" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('area_id') ? 'color-modified' : '' }}"
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
                                                @error('area_id')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
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
                                            <label><b>{{ translate('Street') }}</b> <span class="text-primary">*</span></label>
                                            <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('street') ? 'color-modified' : '' }}"
                                                value="{{ $street }}"
                                                placeholder="{{ translate('Street') }}" name="street" required>
                                                @error('street')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('Building') }} </b><span class="text-primary">*</span></label>
                                            <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('building') ? 'color-modified' : '' }}"
                                                value="{{$building }}"
                                                placeholder="{{ translate('Building') }}" name="building" required>
                                                @error('building')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('Unit/Office No.') }} </b><span
                                                    class="text-primary"></span></label>
                                            <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('unit') ? 'color-modified' : '' }}"
                                                value="{{$unit }}"
                                                placeholder="{{ translate('Unit/Office No.') }}" name="unit">
                                                @error('unit')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('PO Box') }} </b><span class="text-primary "></span></label>
                                            <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('po_box') ? 'color-modified' : '' }}"
                                                value="{{ $po_box }}"
                                                placeholder="{{ translate('PO Box') }}" name="po_box">
                                                @error('po_box')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="fs-20 fw-600 p-3 orange-text">
                                        Contact Information
                                     </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><b>{{ translate('Landline Phone No.') }} </b><span
                                                    class="text-primary"></span></label>
                                            <input value="{{$landline }}"
                                                type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('landline') ? 'color-modified' : '' }}"
                                                placeholder="{{ translate('Landline Phone No.') }}" name="landline">
                                                @error('landline')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding-left: 0px">
                                        <div class="fs-20 fw-600 p-3 orange-text">
                                            Tax Information
                                        </div>   </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="{{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('vat_registered') != null ? 'color-modified-file' : '' }}"><b>{{ translate('Vat Registered') }} </b><span class="text-primary">*
                                                </span></label> <br>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="1"
                                                    id="vatRegisteredYes" name="vat_registered"
                                                    @if ( (old("vat_registered") == 1) ||
                                                        ($vat_registered == 1) ||
                                                        empty($vat_registered)) checked @endif>
                                                <label class="form-check-label" for="vatRegisteredYes">
                                                    {{ translate('Yes') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="0"
                                                    id="vatRegisteredNo" @if (  $vat_registered == 0  ||  (old("vat_registered") != null && old("vat_registered") == 0) ) checked @endif
                                                    name="vat_registered">
                                                <label class="form-check-label" for="vatRegisteredNo">
                                                    {{ translate('No') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="vatCertificateGroup">
                                        <div class="form-group">
                                            <label><b>{{ translate('Vat Certificate') }} </b><span
                                                    class="text-primary">*</span><small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}</small></label>
                                            @if ($vat_certificate)
                                                <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('vat_certificate') ? 'color-modified-file' : '' }}"
                                                    href="{{ static_asset($vat_certificate) }}"
                                                    target="_blank">{{ translate('View Vat Certificate') }}</a>
                                                <input type="hidden" name="vat_certificate"
                                                    value="{{ $vat_certificate}}">
                                            @endif
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control custom-file-input" id="vat_certificate_input"
                                                        name="vat_certificate"  placeholder="{{ translate('Vat Certificate') }}">

                                                    <label class="custom-file-label" for="vat_certificate_input">{{ translate('Choose a file') }}</label>
                                                </div>

                                            </div>
                                            @error('vat_certificate')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                            <small>{{ translate('Max file size is 5MB and accepted file types are PDF and image formats.') }}</small>


                                        </div>
                                    </div>
                                    <div class="col-md-12" id="trnGroup">
                                        <div class="form-group">
                                            <label><b>{{ translate('TRN') }} </b><span class="text-primary">*</span></label>
                                            <input value="{{ $trn }}" type="text"
                                                class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('trn') ? 'color-modified' : '' }}" placeholder="{{ translate('TRN') }}"
                                                name="trn">
                                                @error('trn')
                                                <div class="text-danger">{{ $message }}</div>
                                                 @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 Grand-title">
                                        <div class="fs-20 fw-600 p-3 orange-text">
                                            Regulatory Information
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="taxWaiverGroup" {{-- style="display: none;" --}}>
                                        <div class="form-group">
                                            <label><b>{{ translate('Tax Waiver Certificate') }}</b> <span
                                                    class="text-primary">*</span><small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}</small></label>
                                            @if ($tax_waiver)
                                                <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('tax_waiver') ? 'color-modified-file' : '' }}"
                                                    href="{{ static_asset($tax_waiver) }}"
                                                    target="_blank">{{ translate('View Tax Waiver Certificate') }}</a>
                                                <input type="hidden" name="tax_waiver"
                                                    value="{{ $tax_waiver }}">
                                            @endif
                                            <input type="file" class="form-control rounded-0" name="tax_waiver">
                                            @error('tax_waiver')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><b>{{ translate('Civil Defense Approval') }}</b> <span
                                                    class="text-primary"></span></label>
                                            {{-- <input  type="file" class="form-control rounded-0"
                                            name="civil_defense_approval"> --}}
                                            @if ($civil_defense_approval )
                                                <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('civil_defense_approval') ? 'color-modified-file' : '' }}"
                                                    href="{{ static_asset($civil_defense_approval ) }}"
                                                    target="_blank">{{ translate('View Civil Defense Approval') }}</a>
                                                <input type="hidden" name="civil_defense_approval"
                                                    value="{{ $civil_defense_approval  }}">
                                            @endif

                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control custom-file-input" id="civil_defense_approval_input"
                                                        name="civil_defense_approval">
                                                        @error('civil_defense_approval')
                                                        <div class="text-danger">{{ $message }}</div>
                                                         @enderror
                                                    <label class="custom-file-label" for="civil_defense_approval_input">{{ translate('Choose a file') }}</label>
                                                </div>
                                            </div>
                                            @error('civil_defense_approval') <div class="text-danger">{{ $message }}</div> @enderror
                                            <small>{{ translate('Max file size is 5MB and accepted file types are PDF and image formats.') }}</small>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>


                        <div class="text-right">
                            <!-- Previous Button -->
                            <button type="button" data-prv='personal-info'
                                class="btn btn-info fw-600 rounded-0 prv-tab">
                                {{ translate('Previous') }}
                            </button>

                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                onclick="switchTab('contact-person')">{{ translate('Next') }}</button>

                        </div>

                </div>
                <div class="tab-pane fade" id="contact-person">

                    <!-- ... Contact Person form fields ... -->
                    <div class="bg-white border mb-4">
                        <div class="fs-20 fw-600 p-3 orange-text">
                            {{ translate('Personal Information') }}
                        </div>


                        {{-- <div id="validation-errors" class="alert alert-danger"
                                style="display: none;"></div> --}}
                        <?php

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
                        $business_owner = $proposedPayoutChange && $proposedPayoutChange->getNewValue('business_owner') !=null ? $proposedPayoutChange->getNewValue('business_owner') : $user->contact_people->business_owner ?? '';
                        $designation = $proposedPayoutChange && $proposedPayoutChange->getNewValue('designation') ? $proposedPayoutChange->getNewValue('designation') : $user->contact_people->designation ?? '';

                        ?>
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('First Name') }}</b><span class="text-primary">*</span></label>
                                        {{-- @php
                                                $fistName = null;
                                                if (isset($user->contact_people->first_name) && !empty($user->contact_people->first_name)) {
                                                    $fistName = $user->contact_people->first_name;
                                                }

                                            @endphp --}}
                                        <input id="first_name_bi" type="text"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('first_name') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('First Name') }}" value="{{ $first_name }}"
                                            name="first_name" required>
                                            @error('first_name')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Last Name') }} </b><span class="text-primary">*</span></label>
                                        {{-- @php
                                                $lastName = null;
                                                if (isset($user->contact_people->last_name) && !empty($user->contact_people->last_name)) {
                                                    $lastName = $user->contact_people->last_name;
                                                }

                                            @endphp --}}
                                        <input type="text"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('last_name') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Last Name') }}" id="last_name_bi"
                                            value="{{ $last_name }}" name="last_name" required>
                                            @error('last_name')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Date Of Birth') }} </b><span
                                                class="text-primary">*</span></label>
                                        <input dir="auto" type="text" class="datepicker form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('date_of_birth') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Date Of Birth') }}" {{-- value="{{ $user->contact_people->date_of_birth ?? '' }}" --}}
                                            value="{{ $date_of_birth }}"
                                            name="date_of_birth" required>
                                            @error('date_of_birth')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Nationality') }} </b><span class="text-primary">*</span></label>
                                        <br>
                                        <select id="nationality" title="{{ translate('Select Nationality') }}" name="nationality"
                                            class="form-control selectpicker countrypicker {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('nationality') ? 'color-modified' : '' }}"
                                            @if ($nationality) data-default="{{ $nationality }}" @else data-default="" @endif
                                            data-flag="true"></select>
                                            <input type="hidden" value="{{$nationality }}" id="nationalityHidden" name="nationality"> <!-- Hidden input for nationality -->
                                            @error('nationality')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="fs-20 fw-600 p-3 orange-text">
                                    Contact Information
                                 </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><b>{{ translate('Email') }}</b> <span class="text-primary">*</span></label>
                                        {{-- @php
                                                $emailUser = null;
                                                if (isset($user->contact_people->email) && !empty($user->contact_people->email)) {
                                                    $emailUser = $user->contact_people->email;
                                                }

                                            @endphp --}}
                                        <input type="email"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('email') ? 'color-modified' : '' }}"
                                            id="email_bi" placeholder="{{ translate('Email') }}"
                                            value="{{ $email }}" name="email" required>
                                            @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Mobile Phone') }}</b> <span
                                                class="text-primary">*</span></label>
                                        <small class="text-muted">{{ translate('Example') }}:
                                            +971123456789 {{ translate('or') }}
                                            00971123456789</small>

                                        <input type="text" dir="auto"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('mobile_phone') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Mobile Phone') }}" value="{{ $mobile_phone }}"
                                            name="mobile_phone" required>
                                            @error('mobile_phone')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Additional Mobile Phone') }} </b><span
                                                class="text-primary"></span></label>
                                        <input type="text" dir="auto"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('additional_mobile_phone') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Additional Mobile Phone') }}"
                                            value="{{ $additional_mobile_phone }}" name="additional_mobile_phone">
                                            <small
                                                class="text-muted">{{ translate('Example') }}:
                                                +971123456789 {{ translate('or') }}
                                                00971123456789</small>
                                            @error('additional_mobile_phone')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>

                                <div class="fs-20 fw-600 p-3 orange-text">
                                    Emirates ID
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><b>{{ translate('Emirates ID - Number') }}</b><span
                                                class="text-primary">*</span></label>
                                        <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_number') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Emirates ID - Number') }}"
                                            value="{{  $emirates_id_number }}" required
                                            name="emirates_id_number">
                                            <small
                                            class="text-muted">{{ translate('Example') }}:123456789012345
                                            </small>
                                            @error('emirates_id_number')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>


                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Emirates ID - Expiry Date') }}</b> <span
                                                class="text-primary">*</span></label>
                                        <input dir="auto" type="text" class="datepicker form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_expiry_date') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Emirates ID - Expiry Date') }}"
                                            {{-- value="{{ $user->contact_people->emirates_id_expiry_date ?? '' }}" --}}
                                            value="{{ $emirates_id_expiry_date }}"
                                            required name="emirates_id_expiry_date">
                                            @error('emirates_id_expiry_date')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Emirates ID') }}</b><span
                                                class="text-primary">*</span></label>
                                        @if ($emirates_id_file_path)
                                            <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('emirates_id_file_path') ? 'color-modified-file' : '' }}"
                                                href="{{ static_asset($emirates_id_file_path) }}"
                                                target="_blank">{{ translate('View Emirates ID') }}</a>
                                            <input type="hidden" name="emirates_id_file_path"
                                                value="{{ $emirates_id_file_path }}">
                                        @endif
                                        <div class="input-group">
                                            <div class="custom-file">
                                        <input id="emirates_id_file_input" type="file" class="form-control custom-file-input"
                                            placeholder="{{ translate('Emirates ID') }}" required
                                            name="emirates_id_file_path">
                                            <label class="custom-file-label" for="emirates_id_file_input">{{ translate('Choose a file') }}</label>

                                            </div>
                                        </div>

                                            @error('emirates_id_file_path')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                             <small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}
                                            </small>
                                    </div>

                                    {{-- <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="form-control custom-file-input"
                                        placeholder="{{ translate('Emirates ID') }}" required
                                        name="emirates_id_file" id="emirates_id_file_input">
                                        <label class="custom-file-label" for="emirates_id_file_input">{{ translate('Choose a file') }}</label> --}}
                                        {{-- </div>
                                    </div>

                                    <small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}
                                        </small> --}}
                                </div>
                                <div class="col-md-12" style="padding-left: 0px">
                                    <div class="fs-20 fw-600 p-3 orange-text">
                                        Employment Information
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="{{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('business_owner') != null ? 'color-modified-file' : '' }}"><b>{{ translate('Business Owner') }}</b><span class="text-primary">*
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
                                        <label><b>{{ translate('Designation') }}</b> <span class="text-primary">*</span></label>
                                        <input type="text" class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('designation') ? 'color-modified' : '' }}" required
                                            placeholder="{{ translate('Designation') }}"
                                            value="{{ $designation }}" name="designation">
                                            @error('designation')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <!-- Previous Button -->
                        <button type="button" data-prv='business-info' class="btn btn-info fw-600 rounded-0 prv-tab">
                            {{ translate('Previous') }}
                        </button>

                        <button type="button" class="btn btn-primary fw-600 rounded-0"
                            onclick="switchTab('payout-info')">{{ translate('Next') }}</button>
                    </div>

                </div>

                <div class="tab-pane fade" id="payout-info">

                    <!-- ... Payout Info form fields ... -->
                    <div class="bg-white border mb-4">

                       <div class="fs-20 fw-600 p-3 orange-text">
                                                {{ translate('Bank Information') }}
                        </div>
                        {{-- <div id="validation-errors" class="alert alert-danger"
                                style="display: none;"></div> --}}
                        <?php

                        $bankName = $proposedPayoutChange && $proposedPayoutChange->getNewValue('bank_name') ? $proposedPayoutChange->getNewValue('bank_name') : $user->payout_information->bank_name ?? '';
                        $accountName = $proposedPayoutChange && $proposedPayoutChange->getNewValue('account_name') ? $proposedPayoutChange->getNewValue('account_name') : $user->payout_information->account_name ?? '';
                        $accountNumber = $proposedPayoutChange && $proposedPayoutChange->getNewValue('account_number') ? $proposedPayoutChange->getNewValue('account_number') : $user->payout_information->account_number ?? '';
                        $iban = $proposedPayoutChange && $proposedPayoutChange->getNewValue('iban') ? $proposedPayoutChange->getNewValue('iban') : $user->payout_information->iban ?? '';
                        $swiftCode = $proposedPayoutChange && $proposedPayoutChange->getNewValue('swift_code') ? $proposedPayoutChange->getNewValue('swift_code') : $user->payout_information->swift_code ?? '';
                        $ibanCertificate = $proposedPayoutChange && $proposedPayoutChange->getNewValue('iban_certificate') ? $proposedPayoutChange->getNewValue('iban_certificate') : $user->payout_information->iban_certificate ?? '';

                        ?>

                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Bank Name') }}</b> <span class="text-primary">*</span></label>
                                        <input value="{{ $bankName }}" type="text"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('bank_name') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Bank Name') }}" name="bank_name" required>
                                            @error('bank_name')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Account Name') }}</b> <span
                                                class="text-primary">*</span></label>
                                        <input value="{{ $accountName }}" type="text"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('account_name') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Account Name') }}" name="account_name" required>
                                            @error('account_name')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Account Number') }}</b> <span
                                                class="text-primary">*</span></label>
                                        <input value="{{ $accountNumber }}" type="text"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('account_number') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Account Number') }}" name="account_number"
                                            required>
                                            @error('account_number')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('IBAN') }}</b> <span class="text-primary">*</span></label>
                                        <input value="{{ $iban }}" type="text"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('iban') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('IBAN') }}" name="iban" required>
                                            @error('iban')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('Swift Code') }} </b><span class="text-primary">*</span></label>
                                        <input value="{{ $swiftCode }}" type="text"
                                            class="form-control rounded-0 {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('swift_code') ? 'color-modified' : '' }}"
                                            placeholder="{{ translate('Swift Code') }}" name="swift_code" required>
                                            @error('swift_code')
                                            <div class="text-danger">{{ $message }}</div>
                                             @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b>{{ translate('IBAN Certificate') }}</b><span
                                                class="text-primary">*</span></label>
                                        @if ($ibanCertificate)
                                            <a class="old_file {{ $proposedPayoutChange && $proposedPayoutChange->getNewValue('iban_certificate') ? 'color-modified-file' : '' }}"
                                                href="{{ static_asset($ibanCertificate) }}"
                                                target="_blank">{{ translate('View IBAN Certificate') }}</a>
                                            <input type="hidden" name="iban_certificate"
                                                value="{{ $ibanCertificate }}">
                                        @endif
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="form-control custom-file-input" id="iban_certificate_input"
                                                name="iban_certificate">

                                                <label class="custom-file-label" for="iban_certificate_input">{{ translate('Choose a file') }}</label>

                                            </div>
                                        </div>
                                        @error('iban_certificate')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}</small>


                                    </div>




                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-right">
                        <!-- Previous Button -->
                        <button type="button" data-prv='contact-person' class="btn btn-info fw-600 rounded-0 prv-tab">
                            {{ translate('Previous') }}
                        </button>
                        @if ($proposedPayoutChange && $proposedPayoutChange->admin_viewed == 0 || !$proposedPayoutChange)
                        <!-- Submit for Review Button -->
                        <button type="button" id="submitForReviewBtn" class="btn btn-primary fw-600 rounded-0">
                            {{ translate('Submit for Review') }}
                        </button>
                        @endif


                    </div>

                </div>
                @endif
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

{{-- @section('modal')

    <div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-default" role="form" action="{{ route('seller.addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Address')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control mb-3" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Country')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="mb-3">
                                        <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                            <option value="">{{ translate('Select your country') }}</option>
                                            @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('State')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>

                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('City')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city_id" required>

                                    </select>
                                </div>
                            </div>

                            @if (get_setting('google_map') == 1)
                                <div class="row">
                                    <input id="searchInput" class="controls" type="text" placeholder="{{translate('Enter a location')}}">
                                    <div id="map"></div>
                                    <ul id="geoData">
                                        <li style="display: none;">{{ translate('Full Address') }}: <span id="location"></span></li>
                                        <li style="display: none;">{{ translate('Postal Code') }}: <span id="postal_code"></span></li>
                                        <li style="display: none;">{{ translate('Country') }}: <span id="country"></span></li>
                                        <li style="display: none;">{{ translate('Latitude') }}: <span id="lat"></span></li>
                                        <li style="display: none;">{{ translate('Longitude') }}: <span id="lon"></span></li>
                                    </ul>
                                </div>

                                <div class="row">
                                    <div class="col-md-2" id="">
                                        <label for="exampleInputuname">{{ translate('Longitude') }}</label>
                                    </div>
                                    <div class="col-md-10" id="">
                                        <input type="text" class="form-control mb-3" id="longitude" name="longitude" readonly="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2" id="">
                                        <label for="exampleInputuname">{{ translate('Latitude') }}</label>
                                    </div>
                                    <div class="col-md-10" id="">
                                        <input type="text" class="form-control mb-3" id="latitude" name="latitude" readonly="">
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Postal code')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>{{ translate('Phone')}}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('+880')}}" name="phone" value="" required>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="edit_modal_body">

                </div>
            </div>
        </div>
    </div>

@endsection --}}

@section('script')
    {{-- <script type="text/javascript">

        $('.new-email-verification').on('click', function() {
            $(this).find('.loading').removeClass('d-none');
            $(this).find('.default').addClass('d-none');
            var email = $("input[name=email]").val();

            $.post('{{ route('user.new.verify') }}', {_token:'{{ csrf_token() }}', email: email}, function(data){
                data = JSON.parse(data);
                $('.default').removeClass('d-none');
                $('.loading').addClass('d-none');
                if(data.status == 2)
                    AIZ.plugins.notify('warning', data.message);
                else if(data.status == 1)
                    AIZ.plugins.notify('success', data.message);
                else
                    AIZ.plugins.notify('danger', data.message);
            });
        });

        function add_new_address(){
            $('#new-address-modal').modal('show');
        }

        function edit_address(address) {
            var url = '{{ route("seller.addresses.edit", ":id") }}';
            url = url.replace(':id', address);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        var lat     = -33.8688;
                        var long    = 151.2195;

                        if(response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat     = parseFloat(response.data.address_data.latitude);
                            long    = parseFloat(response.data.address_data.longitude);
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }

        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

    </script>

    @if (get_setting('google_map') == 1)

        @include('frontend.'.get_setting('homepage_select').'.partials.google_map')

    @endif --}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-select-country.min.css') }}">
    <script src="{{ static_asset('assets/js/bootstrap-select-country.min.js') }}"></script>
    <script src="//code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
           // Function to highlight tabs with text-danger content
           function highlightDangerousTabs() {
                    $('.tab-pane').each(function() {
                        if ($(this).find('.text-danger').length > 0) {
                            var tabPaneId = $(this).attr('id');

                            $('.nav-tabs a[href="#' + tabPaneId + '"]').addClass('highlighted-tab');
                        }
                    });
                }
        function switchTab(tabId) {

            $('#registerTabs').find('.nav-link').removeClass('active');
            $('#registerTabsContent').find('.tab-pane').removeClass('show active');

            $('#' + tabId + '-tab').addClass('active');
            $('#' + tabId).addClass('show active');

            // Focus on the first input with 'is-invalid' class within the active tab
            $('#' + tabId).find('.is-invalid').first().focus();
            // Remove all <div> elements with class "text-danger"
            $(this).parent().parent().parent().find('div.text-danger').remove();

        }
        $(document).ready(function() {
            $('.datepicker').datepicker({
                dateFormat: 'dd M yy', // Setting the display format
                changeYear: true, // Enable year dropdown
                yearRange: "-100:+10" // Optional: specify the range of years available
            });
            $('#nationality').change(function() {
                $('#nationalityHidden').val($(this).val());
            });

        // Call the function initially when document is ready
        highlightDangerousTabs();

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
            $('#emirateempire').change(function() {
                var getAreaUrl = "{{ route('get.area', ['id' => ':id']) }}";

                var id = $(this).val();
                // $('#areaempire').find('option').not(':first').remove();
                $('#areaempire').find('option').remove();

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
            // Submit for Review Button Click Event
            $('#submitForReviewBtn').on('click', function() {
                // Show confirmation dialog
                Swal.fire({
                    title: 'Confirm',
                    text: 'Changes in all tabs will be submitted for admin’s review. Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user clicks "Yes", submit the form
                        submitChangesForReview();
                    }
                });
            });

            // Function to submit the form for review
            function submitChangesForReview() {
                // Submit the form
                document.getElementById('profileForm').submit();
            }
            // Check if select element has class 'color-modified' on page load
            if ($('#nationality').hasClass('color-modified')) {
                    // If select element has class 'color-modified', add class to the button
                    $('.btn[data-id="nationality"]').addClass('color-modified');
                }
        })
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}}) {
            return;
        }
        var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->title}}',
                intro: "{{$step->description}}",
                position: 'right'
            },
            @endforeach
        ];

        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
            doneLabel: 'Finish', // Replace the "Done" button with "Next"
            exitOnEsc : false ,
            exitOnOverlayClick : false ,
            disableInteraction : true ,
            overlayOpacity : 0.4 ,
            showStepNumbers : true ,
            hidePrev : true ,
            showProgress :true ,
        });
        tour.onexit(function() {
            $.ajax({
                url: "{{ route('seller.tour') }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' }, // Include CSRF token for Laravel
                success: function(response) {
                    // Handle success
                    console.log('User tour status updated successfully');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating user tour status:', error);
                }
            });
            setTimeout(function() {
                window.location.href = '{{ route("seller.dashboard") }}';
            }, 500);
        });
        tour.onbeforechange(function(targetElement) {
                    if (this._direction === 'backward') {
                    window.location.href = '{{ route("seller.support_ticket.index") }}'; // Redirect to another page
                    sleep(60000);
                    }
                    //tour.exit();
                });

    tour.start();
    tour.goToStepNumber(14);
    });
</script>
@endsection
