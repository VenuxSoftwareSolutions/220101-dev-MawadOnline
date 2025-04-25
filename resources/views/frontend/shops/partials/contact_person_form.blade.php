<form id="contactPersonForm" class="" action="{{ route('shops.contact_person') }}"
method="POST" data-next-tab="warehouses">
@csrf
<div class="bg-white border mb-4">
    <div class="fs-20 fw-600 p-3 orange-text">
        {{ __('profile.personal_information') }}
    </div>

    

    <div class="p-3">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('First Name') }} </b><span
                            class="text-primary">*</span></label>
                    @php
                        $firstName = null;
                        if (isset($user->contact_people->first_name) && !empty($user->contact_people->first_name)) {
                            $firstName = $user->contact_people->first_name;
                        } elseif (isset($user->first_name)) {
                            $firstName = $user->first_name;
                        }

                    @endphp
                    <input id="first_name_bi" type="text" class="form-control rounded-0"placeholder="{{ translate('First Name') }}" value="{{ $firstName }}" name="first_name" required>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Last Name') }}</b> <span
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
                        placeholder="{{ translate('Last Name') }}" id="last_name_bi"
                        value="{{ $lastName }}" name="last_name" required>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Date Of Birth') }} </b><span
                            class="text-primary">*</span></label>
                    <input dir="auto" type="text"
                        class="datepicker form-control rounded-0"
                        placeholder="{{ translate('Date Of Birth') }}"
                        value="{{ isset($user->contact_people->date_of_birth) ? Carbon::createFromFormat('Y-m-d', $user->contact_people->date_of_birth)->format('d M Y') : '' }}"
                        name="date_of_birth" required>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Nationality') }} </b><span
                            class="text-primary">*</span></label>
                    <br>
                    <select title="{{ translate('Select Nationality') }}"
                        name="nationality"
                        class="form-control selectpicker countrypicker" @if (isset($user->contact_people) && !empty($user->contact_people->nationality))
                        data-default="{{ $user->contact_people->nationality }}"
                        @else data-default="" @endif data-flag="true"></select>

                </div>
            </div>
            <div class="fs-20 fw-600 p-3 orange-text">
                {{ __('profile.contact_information') }}
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label><b>{{ translate('Email') }}</b> <span
                            class="text-primary">*</span></label>
                    @php
                        $emailUser = null;
                        if (isset($user->contact_people->email) && !empty($user->contact_people->email)) {
                            $emailUser = $user->contact_people->email;
                        } elseif (isset($user->email)) {
                            $emailUser = $user->email;
                        }

                    @endphp
                    <input type="email" class="form-control rounded-0" id="email_bi"
                        placeholder="{{ translate('Email') }}"
                        value="{{ $emailUser }}" name="email" required>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Mobile Phone') }} </b><span
                            class="text-primary">*</span></label>


                    <input type="text" dir="auto" class="form-control rounded-0"
                        placeholder="{{ translate('Mobile Phone') }}"
                        value="{{ $user->contact_people->mobile_phone ?? '+971' }}"
                        name="mobile_phone" required>
                    <small class="text-muted">{{ translate('Example') }}:
                        +971123456789 {{ translate('or') }}
                        00971123456789</small>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Additional Mobile Phone') }} </b><span
                            class="text-primary"></span></label>
                    <input type="text" dir="auto" class="form-control rounded-0"
                        placeholder="{{ translate('Additional Mobile Phone') }}"
                        value="{{ $user->contact_people->additional_mobile_phone ?? '+971' }}"
                        name="additional_mobile_phone">
                    <small class="text-muted">{{ translate('Example') }}:
                        +971123456789 {{ translate('or') }}
                        00971123456789</small>

                </div>
            </div>


            <div class="fs-20 fw-600 p-3 orange-text">
                {{ __('profile.Emirates ID') }}
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label><b>{{ translate('Emirates ID - Number') }} </b><span
                            class="text-primary">*</span></label>
                    <input type="text" class="form-control rounded-0"
                        placeholder="{{ translate('Emirates ID - Number') }}"
                        value="{{ $user->contact_people->emirates_id_number ?? '' }}"
                        required name="emirates_id_number">
                    <small
                        class="text-muted">{{ translate('Example') }}:123456789012345
                    </small>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Emirates ID - Expiry Date') }} </b><span
                            class="text-primary">*</span></label>
                    <input dir="auto" type="text"
                        class="datepicker form-control rounded-0"
                        placeholder="{{ translate('Emirates ID - Expiry Date') }}"
                        {{--
                        value="{{ $user->contact_people->emirates_id_expiry_date ?? '' }}"
                        --}}
                        value="{{ isset($user->contact_people->emirates_id_expiry_date) ? Carbon::createFromFormat('Y-m-d', $user->contact_people->emirates_id_expiry_date)->format('d M Y') : '' }}"
                        required name="emirates_id_expiry_date">

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Emirates ID') }} </b><span
                            class="text-primary">*</span></label>
                    @if (isset($user) && isset($user->contact_people) && $user->contact_people->emirates_id_file_path)
                        <a class="old_file"
                            href="{{ static_asset($user->contact_people->emirates_id_file_path) }}"
                            target="_blank">{{ translate('View Emirates ID') }}</a>
                        <input type="hidden" name="emirates_id_file_old"
                            value="{{ $user->contact_people->emirates_id_file_path }}">

                    @endif
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file"
                                class="form-control custom-file-input"
                                placeholder="{{ translate('Emirates ID') }}"
                                required name="emirates_id_file"
                                id="emirates_id_file_input"
                                accept=".pdf,.jpeg,.jpg,.png,.webp">
                            <label class="custom-file-label"
                                for="emirates_id_file_input">{{ translate('Choose a file') }}</label>

                        </div>
                    </div>

                    <small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}
                    </small>

                </div>
            </div>

            <div class="col-md-12" style="padding-left: 0px">
                <div class="fs-20 fw-600 p-3 orange-text">

                    {{ __('profile.Employment Information') }}
                </div>
            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label><b>{{ translate('Business Owner') }} </b><span
                            class="text-primary">*
                        </span></label> <br>
                    <div class="form-check form-check-inline">
                        <input @if (
                            (isset($user->contact_people->business_owner) && $user->contact_people->business_owner == 1) ||
                            !isset($user->contact_people)
                        ) checked @endif
                            class="form-check-input" type="radio" value="1"
                            id="vatRegisteredYes" name="business_owner">
                        <label class="form-check-label" for="vatRegisteredYes">
                            {{ translate('Yes') }}
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" @if (isset($user->contact_people->business_owner) && $user->contact_people->business_owner == 0) checked @endif
                            value="0" id="vatRegisteredNo" name="business_owner">
                        <label class="form-check-label" for="vatRegisteredNo">
                            {{ translate('No') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Designation') }} </b><span
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
    <button type="button" data-prv='business_info'class="btn btn-info fw-600 rounded-0 prv-tab"> {{ translate('Previous') }}</button>
    <button type="button" class="btn btn-secondary fw-600 rounded-0 save-as-draft" data-action="save-as-draft">{{ translate('Save as Draft') }}</button>
    <button type="button" class="btn btn-primary fw-600 rounded-0">{{ translate('Save and Continue') }}</button>
</div>
</form>