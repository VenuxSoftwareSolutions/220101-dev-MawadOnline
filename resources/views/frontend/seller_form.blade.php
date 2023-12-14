@extends('frontend.layouts.app')

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
                                <li class="nav-item">
                                    <a class="nav-link active" id="personal-info-tab" data-toggle="tab"
                                        href="#personal-info">Personal Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="code-verification-tab" data-toggle="tab"
                                        href="#code-verification">Code Verification Email</a>
                                </li>
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
                                <div class="tab-pane fade show active" id="personal-info">

                                    <form id="shop" class="" action="{{ route('shops.store') }}" method="POST"
                                        enctype="multipart/form-data" data-next-tab="code-verification">
                                        @csrf
                                        <!-- ... Personal Info form fields ... -->
                                        <div class="bg-white border mb-4">
                                            <div class="fs-15 fw-600 p-3">
                                                {{ translate('Personal Info') }}
                                            </div>
                                            <div id="validation-errors" class="alert alert-danger" style="display: none;">
                                            </div>

                                            <div class="p-3">
                                                <div class="form-group">
                                                    <label>{{ translate('First Name') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control rounded-0"
                                                        value="{{ old('name') }}"
                                                        placeholder="{{ translate('First Name') }}" name="first_name"
                                                        required>

                                                </div>
                                                <div class="form-group">
                                                    <label>{{ translate('Last name') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="text" class="form-control rounded-0"
                                                        placeholder="{{ translate('Last name') }}" name="last_name"
                                                        required>

                                                </div>
                                                <div class="form-group">
                                                    <label>{{ translate('Your Email') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input id="email" type="email" class="form-control rounded-0"
                                                        value="{{ old('email') }}" placeholder="{{ translate('Email') }}"
                                                        name="email" required>

                                                </div>
                                                <div class="form-group">
                                                    <label>{{ translate('Your Password') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input type="password" class="form-control rounded-0"
                                                        value="{{ old('password') }}"
                                                        placeholder="{{ translate('Password') }}" name="password" required>

                                                </div>
                                                <div class="form-group">
                                                    <label>{{ translate('Repeat Password') }} <span
                                                            class="text-primary">*</span></label>
                                                    <input id="password_confirmation" type="password" class="form-control rounded-0"
                                                        placeholder="{{ translate('Confirm Password') }}"
                                                        name="password_confirmation" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                onclick="switchTab('code-verification')">Next</button>
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
                                                    <input type="hidden" name="email" id="emailAccount">
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
                                                onclick="switchTab('business-info')">Next</button>
                                            <button id="resendCodeBtn" type="button"
                                                class="btn btn-secondary fw-600 rounded-0">Resend Code</button>

                                        </div>
                                    </form>
                                </div>


                                <div class="tab-pane fade" id="business-info">
                                    <form   id="businessInfoForm" class="" action="{{ route('shops.business_info') }}" method="POST" enctype="multipart/form-data"
                                        data-next-tab="contactPersonForm">
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
                                                                name="trade_name_english" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Arabic Trade Name') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Arabic Trade Name') }}"
                                                                name="trade_name_arabic" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Trade License Doc') }} <span
                                                                    class="text-primary">*</span></label>
                                                            {{-- <input type="file" class="form-control rounded-0"
                                                                placeholder="{{ translate('Trade License Doc') }}"
                                                                name="trade_license_doc" required> --}}

                                                          <div class="custom-file">
                                                            <input name="trade_license_doc" type="file" class="custom-file-input" id="inputGroupFile01">
                                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                                          </div>

                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('English E-shop Name') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('English E-shop Name') }}"
                                                                name="eshop_name_english" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Arabic E-shop Name') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Arabic E-shop Name') }}"
                                                                name="eshop_name_arabic" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('English e-Shop description') }} <span
                                                                    class="text-primary"></span></label>

                                                            <textarea class="form-control rounded-0" placeholder="{{ translate('English e-Shop description') }}"
                                                                name="eshop_desc_en"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Arabic e-Shop description') }} <span
                                                                    class="text-primary"></span></label>

                                                            <textarea class="form-control rounded-0" placeholder="{{ translate('Arabic e-Shop description') }}"
                                                                name="eshop_desc_ar"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('License Issue Date') }} <span
                                                                    class="text-primary">*</span></label>

                                                            <input required type="date" class="form-control rounded-0"
                                                                placeholder="{{ translate('License Issue Date') }}"
                                                                name="license_issue_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('License Expiry Date') }} <span
                                                                    class="text-primary">*</span></label>

                                                            <input required type="date" class="form-control rounded-0"
                                                                placeholder="{{ translate('License Expiry Date') }}"
                                                                name="license_expiry_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('State/Emirate') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <select required name="state"
                                                                class="form-control rounded-0" id="emirateempire">
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
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Street') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Street') }}" name="street"
                                                                required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Building') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Building') }}" name="building"
                                                                required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Unit/Office No.') }} <span
                                                                    class="text-primary"></span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('Unit/Office No.') }}"
                                                                name="unit">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('PO Box') }} <span
                                                                    class="text-primary"></span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('PO Box') }}" name="po_box">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Landline Phone No.') }} <span
                                                                    class="text-primary"></span></label>
                                                            <input type="text" class="form-control rounded-0"
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
                                                                    name="vat_registered" checked>
                                                                <label class="form-check-label" for="vatRegisteredYes">
                                                                    {{ translate('Yes') }}
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    value="0" id="vatRegisteredNo"
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
                                                            <input type="file" class="form-control rounded-0"
                                                                placeholder="{{ translate('Vat Certificate') }}"
                                                                name="vat_certificate">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="trnGroup">
                                                        <div class="form-group">
                                                            <label>{{ translate('TRN') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="text" class="form-control rounded-0"
                                                                placeholder="{{ translate('TRN') }}" name="trn">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="taxWaiverGroup" style="display: none;">
                                                        <div class="form-group">
                                                            <label>{{ translate('Tax Waiver Certificate') }} <span
                                                                    class="text-primary">*</span></label>
                                                            <input type="file" class="form-control rounded-0"
                                                                name="tax_waiver">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ translate('Civil Denfense Approval') }} <span
                                                                    class="text-primary"></span></label>
                                                            <input type="file" class="form-control rounded-0"
                                                                name="civil_defense_approval">

                                                        </div>

                                                    </div>


                                                </div>
                                            </div>
                                        </div>


                                        <div class="text-right">
                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                onclick="switchTab('contact-person')">Next</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="contact-person">
                                    <form id="contactPersonForm" class="" {{-- action="{{ route('shops.contact_person') }}" --}} method="POST"
                                        data-next-tab="warehousesForm">
                                        @csrf
                                        <!-- ... Contact Person form fields ... -->
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
                                                                name="trade_name_english" required>

                                                        </div>
                                                    </div>







                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                onclick="switchTab('warehouses')">Next</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="warehouses">
                                    <form id="warehousesForm" class="" {{-- action="{{ route('shops.warehouses') }}" --}}
                                        data-next-tab="payoutInfoForm" method="POST">
                                        @csrf
                                        <!-- ... Warehouses form fields ... -->
                                        <input type="password"
                                            class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            value="{{ old('password') }}" placeholder="{{ translate('Password') }}"
                                            name="password" required>

                                        <div class="text-right">
                                            <button type="button" class="btn btn-primary fw-600 rounded-0"
                                                onclick="switchTab('payout-info')">Next</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="payout-info">
                                    <form id="payoutInfoForm" class="" {{-- action="{{ route('shops.payout_info') }}" --}} method="POST">
                                        @csrf
                                        <!-- ... Payout Info form fields ... -->
                                        <input type="password"
                                            class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            value="{{ old('password') }}" placeholder="{{ translate('Password') }}"
                                            name="password" required>

                                        <div class="text-right">
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
    <script type="text/javascript">
        $(document).ready(function() {
            // Your existing logic here

            $('#registerTabs a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            $('#registerTabsContent').find('.tab-pane button:not(#resendCodeBtn)').on('click', function(e) {

                var email = $('#email').val();
                $('#emailAccount').val(email);

                e.preventDefault();
                var form = $(this).closest('form');
                var formData = new FormData(form[0]); // Create FormData object from the form

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    // data: form.serialize(),
                    data: formData,
                    contentType: false, // Required for sending FormData
                    processData: false, // Required for sending FormData
                    success: function(response) {
                        // Handle success, e.g., show a message
                        alert(response.message);

                        // Switch to the next tab if the save operation is successful
                        if (response.success) {
                            // switchTab('code-verification'); // Change the tab ID accordingly
                            var nextTabId = form.data(
                                'next-tab'
                            ); // Assuming you set a data attribute on the form with the next tab ID
                            switchTab(nextTabId);
                        }
                    },
                    error: function(xhr) {

                        // Handle errors, e.g., show validation errors
                        var errors = xhr.responseJSON.errors;

                        // Display validation errors in the form
                        displayValidationErrors(errors, form);
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
                    value: $('#email').val()
                });

                $.ajax({
                    url: '{{ route('resend.code') }}', // Replace with your actual route
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle success, e.g., show a message
                        alert(response.message);

                        // You can add additional logic here if needed
                    },
                    error: function(xhr) {
                        // Handle errors, e.g., show validation errors
                        var errors = xhr.responseJSON.errors;
                        // Update this part based on your error handling needs
                        alert('Error: ' + JSON.stringify(errors));
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
    $.each(errors, function (field, messages) {
        var inputField = form.find('[name="' + field + '"]');
        var errorContainer = $('<div class="invalid-feedback"></div>');

        $.each(messages, function (key, message) {
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
    form.find('.form-control').each(function () {
        var inputField = $(this);

        if (!inputField.hasClass('is-invalid') && inputField.val() !== '') {
            inputField.addClass('is-valid');
        }
    });

    $('#validation-errors').html('Validation errors occurred. Please check the form.').show();
}



            function switchTab(tabId) {
                $('#registerTabsContent').find('.nav-link').removeClass('active');
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

        });
    </script>
@endsection
