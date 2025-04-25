<form id="businessInfoForm" class="" action="{{ route('shops.business_info') }}" method="POST" enctype="multipart/form-data" data-next-tab="contact-person">
    @csrf                               
    <div class="bg-white border mb-4">
    
        <div class="fs-20 fw-600 p-3 orange-text">
            {{ __('profile.Trade Information') }}
        </div>
        <div class="p-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>{{ translate('English Trade Name') }}</b> <span
                                class="text-primary">*</span></label>
                        <input type="text" class="form-control rounded-0"
                            placeholder="{{ translate('English Trade Name') }}"
                            value="{{ isset($user->business_information->trade_name) ? $user->business_information->getTranslation('trade_name', 'en', false) : '' }}"
                            name="trade_name_english" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>{{ translate('Arabic Trade Name') }}</b> <span
                                class="text-primary">*</span></label>
                        <input type="text" class="form-control rounded-0"
                            placeholder="{{ translate('Arabic Trade Name') }}"
                            value="{{ isset($user->business_information->trade_name) ? $user->business_information->getTranslation('trade_name', 'ar', false) : '' }}"
                            name="trade_name_arabic" required>

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label><b>{{ translate('Trade License Doc') }} </b> <span
                                class="text-primary">*</span></label>
                        @if (isset($user) && isset($user->business_information) && $user->business_information->trade_license_doc)
                            <a class="old_file"
                                href="{{ static_asset($user->business_information->trade_license_doc) }}"
                                target="_blank">{{ translate('View Trade License Doc') }}</a>
                            <input type="hidden" name="trade_license_doc_old"
                                value="{{ $user->business_information->trade_license_doc }}">
                        @endif
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file"
                                    class="form-control custom-file-input"
                                    id="trade_license_doc_input"
                                    name="trade_license_doc" required
                                    accept=".pdf,.jpeg,.jpg,.png,.webp,.gif,.avif,.bmp,.tiff,.heic">

                                <label class="custom-file-label"
                                    for="trade_license_doc_input">{{ translate('Choose a file') }}</label>
                            </div>
                        </div>
                        <small>{{ translate('Max file size is 5MB and accepted file types are PDF and image formats.') }}</small>
                    </div>
                </div>

                <div class="col-md-12" style="padding-left: 0px">
                    <div class="fs-20 fw-600 p-3 orange-text">

                        {{ __('profile.E-Shop Information') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>{{ translate('English E-shop Name') }}</b> <span
                                class="text-primary">*</span></label>
                        <input type="text" class="form-control rounded-0"
                            placeholder="{{ translate('English E-shop Name') }}"
                            value="{{ isset($user->business_information->eshop_name) ? $user->business_information->getTranslation('eshop_name', 'en', false) : '' }}"
                            name="eshop_name_english" required>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>{{ translate('Arabic E-shop Name') }} </b><span
                                class="text-primary">*</span></label>
                        <input type="text" class="form-control rounded-0"
                            placeholder="{{ translate('Arabic E-shop Name') }}"
                            value="{{ isset($user->business_information->eshop_name) ? $user->business_information->getTranslation('eshop_name', 'ar', false) : '' }}"
                            name="eshop_name_arabic" required>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>{{ translate('English e-Shop description') }}
                            </b><span
                                class="text-primary">{{ __('profile.Optional') }}</span></label>

                        <textarea class="form-control rounded-0"
                            placeholder="{{ translate('English e-Shop description') }}"
                            name="eshop_desc_en">{{ isset($user->business_information->eshop_desc) ? $user->business_information->getTranslation('eshop_desc', 'en', false) : '' }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>{{ translate('Arabic e-Shop description') }}</b> <span
                                class="text-primary">{{ __('profile.Optional') }}</span></label>

                        <textarea class="form-control rounded-0"
                            placeholder="{{ translate('Arabic e-Shop description') }}"
                            name="eshop_desc_ar">{{ isset($user->business_information->eshop_desc) ? $user->business_information->getTranslation('eshop_desc', 'ar', false) : '' }}</textarea>
                    </div>
                </div>

                <div class="col-md-12 Grand-title">
                    <div class="fs-20 fw-600 p-3 orange-text">


                        {{ __('profile.License Information') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>{{ translate('License Issue Date') }} </b><span
                                class="text-primary">*</span>
                        </label>

                        <input dir="auto" required type="{{-- date --}}text"
                            class="datepicker form-control rounded-0"
                            placeholder="{{ translate('License Issue Date') }}"
                            id="license_issue_date"
                            value="{{ isset($user->business_information->license_issue_date) ? Carbon::createFromFormat('Y-m-d', $user->business_information->license_issue_date)->format('d M Y') : '' }}"
                            name="license_issue_date">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>{{ translate('License Expiry Date') }} </b><span
                                class="text-primary">*</span></label>

                        <input dir="auto" required type="text"
                            class="datepicker form-control rounded-0" {{--
                            value="{{ $user->business_information->license_expiry_date ?? '' }}"
                            --}}
                            value="{{ isset($user->business_information->license_expiry_date) ? Carbon::createFromFormat('Y-m-d', $user->business_information->license_expiry_date)->format('d M Y') : '' }}"
                            placeholder="{{ translate('License Expiry Date') }}"
                            name="license_expiry_date">
                    </div>
                </div>
                <div class="fs-20 fw-600 p-3 orange-text">
                    {{ __('profile.location_information') }}
                </div>
                <div class="p-3">
                    <div class="row">
                        @if (isset($user->business_information) && !empty($user->business_information->state))
                                                                                <div class="col-md-6 location">
                                                                                    <div class="form-group">
                                                                                        <label><b>{{ translate('State/Emirate') }} </b><span
                                                                                                class="text-primary">*</span></label>
                                                                                        <select required name="state"
                                                                                            class="form-control rounded-0" id="emirateempire">
                                                                                            <option value="">{{ translate('please_choose') }}
                                                                                            </option>
                                                                                            @foreach ($emirates as $emirate)
                                                                                                <option value="{{ $emirate->id }}" @if (isset($user) && $user->business_information->state == $emirate->id)
                                                                                                selected @endif>
                                                                                                    {{ $emirate->name }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-1"></div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label><b>{{ translate('Area') }} </b><span
                                                                                                class="text-primary">*</span></label>
                                                                                        <select required name="area_id"
                                                                                            class="form-control rounded-0" id="areaempire">
                                                                                            @php
                                                                                                $areas = App\Models\Area::where('emirate_id', $user->business_information->state)->get();
                                                                                            @endphp
                                                                                            <option value="" selected>
                                                                                                {{ translate('please_choose') }}</option>
                                                                                            @foreach ($areas as $area)
                                                                                                <option value="{{ $area->id }}" @if ($area->id == $user->business_information->area_id)
                                                                                                selected @endif>
                                                                                                    {{ $area->name }}
                                                                                                </option>
                                                                                            @endforeach


                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                        @else
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>{{ translate('State/Emirate') }}</b> <span
                                            class="text-primary">*</span></label>
                                    <select required name="state"
                                        class="form-control rounded-0" id="emirateempire">
                                        <option value="" selected>
                                            {{ translate('please_choose') }}</option>
                                        @foreach ($emirates as $emirate)
                                            <option value="{{ $emirate->id }}">
                                                {{ $emirate->name }}</option>
                                        @endforeach
                                    </select>


                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>{{ translate('Area') }} </b><span
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
                                <label><b>{{ translate('Street') }} </b><span
                                        class="text-primary">*</span></label>
                                <input type="text" class="form-control rounded-0"
                                    value="{{ $user->business_information->street ?? '' }}"
                                    placeholder="{{ translate('Street') }}"
                                    name="street" required>
                                <small
                                    class="text-muted">{{ translate('Example: 123 Main Street') }}</small>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><b>{{ translate('Building') }}</b> <span
                                        class="text-primary">*</span></label>
                                <input type="text" class="form-control rounded-0"
                                    value="{{ $user->business_information->building ?? '' }}"
                                    placeholder="{{ translate('Building') }}"
                                    name="building" required>
                                <small
                                    class="text-muted">{{ translate('Example: Tower A or 15B') }}</small>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><b>{{ translate('Unit/Office No.') }}</b> <span
                                        class="text-primary">{{ __('profile.Optional') }}</span></label>
                                <input type="text" class="form-control rounded-0"
                                    value="{{ $user->business_information->unit ?? '' }}"
                                    placeholder="{{ translate('Unit/Office No.') }}"
                                    name="unit">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><b>{{ translate('PO Box') }} </b><span
                                        class="text-primary">{{ __('profile.Optional') }}</span>
                                </label>
                                <input type="text" class="form-control rounded-0"
                                    value="{{ $user->business_information->po_box ?? '' }}"
                                    placeholder="{{ translate('PO Box') }}"
                                    name="po_box">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="fs-20 fw-600 p-3 orange-text">
                    {{ __('profile.contact_information') }}
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><b>{{ translate('Landline Phone No.') }}</b><span
                                class="text-primary">{{ __('profile.Optional') }}</span></label>
                        <input value="{{ $user->business_information->landline ?? '' }}"
                            type="text" class="form-control rounded-0"
                            placeholder="{{ translate('Landline Phone No.') }}"
                            name="landline">

                    </div>
                </div>
                <div class="col-md-12" style="padding-left: 0px">
                    <div class="fs-20 fw-600 p-3 orange-text">

                        {{ __('profile.Tax Information') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>{{ translate('Vat Registered') }} </b><span
                                class="text-primary">*
                            </span></label> <br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="1"
                                id="vatRegisteredYes" name="vat_registered" @if (
                                    (isset($user) && isset($user->business_information) && $user->business_information->vat_registered == 1) ||
                                    !isset($user->business_information->vat_registered)
                                )
                                checked @endif>
                            <label class="form-check-label" for="vatRegisteredYes">
                                {{ translate('Yes') }}
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="0"
                                id="vatRegisteredNo" @if (isset($user) && isset($user->business_information) && $user->business_information->vat_registered == 0) checked
                                @endif name="vat_registered">
                            <label class="form-check-label" for="vatRegisteredNo">
                                {{ translate('No') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="vatCertificateGroup">
                    <div class="form-group">
                        <label><b>{{ translate('Vat Certificate') }}
                                {{ __('profile.File upload') }}</b> <span
                                class="text-primary">*</span></label>
                        @if (isset($user) && isset($user->business_information) && $user->business_information->vat_certificate)
                            <a class="old_file"
                                href="{{ static_asset($user->business_information->vat_certificate) }}"
                                target="_blank">{{ translate('View Vat Certificate') }}</a>
                            <input type="hidden" name="vat_certificate_old"
                                value="{{ $user->business_information->vat_certificate }}">
                        @endif
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file"
                                    class="form-control custom-file-input"
                                    id="vat_certificate_input" name="vat_certificate"
                                    accept=".pdf,.jpeg,.jpg,.png,.webp,.gif,.avif,.bmp,.tiff,.heic">

                                <label class="custom-file-label"
                                    for="vat_certificate_input">{{ translate('Choose a file') }}</label>
                            </div>
                        </div>
                        <small>{{ translate('Max file size is 5MB and accepted file types are PDF and image formats.') }}</small>
                    </div>
                </div>

                <div class="col-md-12" id="trnGroup">
                    <div class="form-group">
                        <label><b>{{ translate('') }}
                                {{ __('profile.TRN') }}
                                {{ __('profile.Tax Registration Number') }}
                            </b><span class="text-primary">*</span></label>
                        <input value="{{ $user->business_information->trn ?? '' }}"
                            type="text" class="form-control rounded-0"
                            placeholder="{{ __('profile.TRN') }} {{ __('profile.Tax Registration Number') }}"
                            name="trn">
                    </div>
                </div>
                <div class="col-md-6" id="taxWaiverGroup" >
                    
                </div>
                <div class="fs-20 fw-600 p-3 orange-text">

                    {{ __('profile.Regulatory Information') }}
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><b>{{ translate('Civil Defense Approval') }}</b> <span
                                class="text-primary"></span></label>
                        
                        @if (isset($user) && isset($user->business_information) && $user->business_information->civil_defense_approval)
                            <a class="old_file"
                                href="{{ static_asset($user->business_information->civil_defense_approval) }}"
                                target="_blank">{{ translate('View Civil Defense Approval') }}</a>
                            <input type="hidden" name="civil_defense_approval_old"
                                value="{{ $user->business_information->civil_defense_approval }}">
                        @endif
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file"
                                    class="form-control custom-file-input"
                                    id="civil_defense_approval_input"
                                    name="civil_defense_approval"
                                    accept=".pdf,.jpeg,.jpg,.png,.webp,.gif,.avif,.bmp,.tiff,.heic">

                                <label class="custom-file-label"
                                    for="civil_defense_approval_input">{{ translate('Choose a file') }}</label>
                            </div>
                        </div>
                        <small>{{ translate('Max file size is 5MB and accepted file types are PDF and image formats.') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right">
        <button type="button" class="btn btn-secondary fw-600 rounded-0 save-as-draft"
            data-action="save-as-draft">{{ translate('Save as Draft') }}</button>

        <button type="button" class="btn btn-primary fw-600 rounded-0" >{{ translate('Save and Continue') }}</button>
    </div>
</form>