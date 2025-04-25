@extends('frontend.layouts.app')


@section('style')
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

        @media (min-width:822px) and (max-width:1198px) {
            ul.nav.nav-tabs.shop {
                background: #f8f9fa;
                margin: 0;
                display: block !important;
                justify-content: space-between;
                align-items: center;
            }
        }

        @media (min-width:579px) and (max-width:805px) {
            ul.nav.nav-tabs.shop {
                background: #f8f9fa;
                margin: 0;
                display: block !important;
                justify-content: space-between;
                align-items: center;
            }
        }


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

        .orange-text {
            color: #CB774B;

        }

        .custom-file-input:lang(en)~.custom-file-label::after {
            content: "Browse";
            background-color: #CB774B;
            color: #fff
        }

        .nav-tabs .nav-item {
            border-radius: 0px;
        }

        .col-md-6 {
            padding-left: 18px;
            padding-right: 30px;
        }


        .nav-tabs .nav-item .nav-link.active {
            background-color: #CB774B;
            color: white;
            padding: 12px 20px;
        }


        .nav-tabs .nav-item .nav-link {
            background-color: #f8f9fa;
            color: gray;
            padding: 12px 20px;
        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: .0rem;
            border-top-right-radius: .0rem;
        }

        .smaller-gray-text {
            font-size: 0.9rem;
            color: rgb(160, 158, 158);
            margin-bottom: 30px;

        }

        .number-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            line-height: 20px;
            border-radius: 50%;
            text-align: center;
            color: white;
            background-color: rgb(160, 158, 158);
        }

        .nav-link.active .number-icon {
            background-color: white;
            color: #CB774B;
        }

        ul.nav.nav-tabs.shop {
            background: #f8f9fa;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .btn.btn-secondary.save-as-draft {
            background-color: rgb(218 218 218);
            color: rgb(0, 0, 0);
            font-weight: 700;
            border: none;
            border-radius: 0;
            font-size: 0.95rem;
        }

        .btn.btn-secondary.save-as-draft:hover {
            background-color: rgb(218 218 218);
            color: rgb(3, 3, 3);
        }

        .Grand-title {
            padding-left: 0px;
        }

        .location {
            padding-right: 80px;
        }

        button {
            font-size: 14px
        }

        .invalid-feedback {
            display: block
        }

        input.is-invalid {
            padding-right: 2.5rem;
        }

        input.is-invalid+.invalid-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #dc3545;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }

        .progress-container {
            height: 5px;
            background: #eee;
            margin-top: 5px;
            border-radius: 3px;
        }

        .progress-bar {
            height: 100%;
            width: 0;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .requirements-list {
            margin-top: 10px;
            padding: 0;
            list-style: none;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            margin: 5px 0;
            color: #666;
        }

        .requirement-item i {
            margin-right: 8px;
            font-size: 0.9em;
        }

        .valid-check {
            color: #28a745;
        }

        .invalid-x {
            color: #dc3545;
        }
    </style>
@endsection
@section('content')
    <section class="pt-4 mb-4">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-11">
                    <h1 class="fw-700 fs-20 fs-md-24 text-dark text-center mb-3">
                        @if (!Auth::user() || (Auth::user() && Auth::user()->owner_id == null))
                            {{ translate('Register Your Shop') }}
                            <h2 class="fs-16 text-muted text-center mt-2"> {{-- Changed to h2 for better hierarchy --}}
                                {{ __('profile.It is the first step to reaching customers globally and growing your brand.') }}<br>
                                {{ __('profile.Let get started') }}
                            </h2>
                        @else
                            {{ translate('Account verification') }}
                        @endif
                    </h1>

                    <div class="row">
                        <div class="col-12">
                            @php
                                $allTabs = [];
                                
                                // Authentication Tabs
                                if (!Auth::user() || (Auth::check() && !Auth::user()->email_verified_at)) {
                                    $allTabs = array_merge($allTabs, [
                                        [
                                            'number' => 1,
                                            'id' => 'personal_info',
                                            'label' => 'Personal Info',
                                            'active' => true
                                        ],
                                        [
                                            'number' => 2,
                                            'id' => 'code_verification',
                                            'label' => 'Code Verification Email'
                                        ]
                                    ]);
                                }

                                // Business Tabs
                                if (!Auth::user() || (Auth::user() && (Auth::user()->owner_id == null || Auth::user()->owner_id == Auth::user()->id))) {
                                    $allTabs = array_merge($allTabs, [
                                        [
                                            'number' => count($allTabs) + 1,
                                            'id' => 'business_info',
                                            'label' => 'Business Information'
                                        ],
                                        [
                                            'number' => count($allTabs) + 2,
                                            'id' => 'contact_person',
                                            'label' => 'Contact Person'
                                        ],
                                        [
                                            'number' => count($allTabs) + 3,
                                            'id' => 'warehouses',
                                            'label' => 'Warehouses'
                                        ],
                                        [
                                            'number' => count($allTabs) + 4,
                                            'id' => 'payout_info',
                                            'label' => 'Payout Information'
                                        ]
                                    ]);
                                }
                            @endphp

                            <ul class="nav nav-tabs shop" id="registerTabs">
                                @foreach ($allTabs as $index => $tab)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $tab['active'] ?? false ? 'active' : '' }}" 
                                        id="{{ $tab['id'] }}-tab" 
                                        data-toggle="tab" 
                                        href="#{{ $tab['id'] }}">
                                            <span class="number-icon">{{ $tab['number'] }}</span>
                                            {{ translate($tab['label']) }}
                                        </a>
                                    </li>
                                    @if(!$loop->last)
                                        <x-icons.arrow-divider />
                                    @endif
                                @endforeach
                            </ul>

                            <div class="tab-content" id="registerTabsContent">
                                @foreach ($allTabs as $tab)
                                    <div class="tab-pane fade {{ $tab['active'] ?? false ? 'show active' : '' }}" 
                                        id="{{ $tab['id'] }}">
                                        @includeWhen(view()->exists("frontend.shops.partials.{$tab['id']}_form"), 
                                            "frontend.shops.partials.{$tab['id']}_form")
                                    </div>
                                @endforeach
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha3/0.9.3/sha3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> 

    <script type="text/javascript">
        $(document).ready(function () {

            var stepNumber = {{ $step_number ?? 0 }}; // Get the step number from the server

            // Check the stepNumber variable and switch tabs accordingly
            switch (stepNumber) {
                case 0:
                    switchTab('personal_info');
                    break;
                case 1:
                    switchTab('code_verification');
                    break;
                case 2:
                    switchTab('business_info');
                    break;
                case 3:
                    switchTab('contact_person');
                    break;
                case 4:
                    switchTab('warehouses');
                    break;
                case 5:
                    switchTab('payout_info');
                    break;

                default:

                    switchTab('personal_info');
                    break;
            }
            

            $('.prv-tab').on('click', function () {
                switchTab($(this).data('prv'));
                var form = $(this).closest('form');
                $(form).find('.is-invalid').first().focus();

            });

            $('#addRow').on('click', function () {
                var warehouseName = $('input[name="warehouse_name_add"]').val();
                var state = $('select[name="state_warehouse_add"]').val();
                var stateText = $('select[name="state_warehouse_add"] option:selected').text();
                var area = $('select[name="area_warehouse_add"]').val();
                var areaText = $('select[name="area_warehouse_add"] option:selected').text();
                var street = $('input[name="street_warehouse_add"]').val();
                var building = $('input[name="building_warehouse_add"]').val();
                var unit = $('input[name="unit_add"]').val();
                const errors = [];

                // Check if any input is empty
                if (!warehouseName || !state || !area || !street || !building) {
                    if (!warehouseName) {
                            errors.push('- Warehouse Name is required');
                        } else {
                            if (warehouseName.length > 128) {
                                errors.push('- Warehouse Name must be less than 128 characters');
                            }
                            if (/^\d+$/.test(warehouseName)) {
                                errors.push('- Warehouse Name must contain letters');
                            }
                    }


                    if (!state) {
                        errorMsg += '\n- State';
                    }
                    if (!area) {
                        errorMsg += '\n- Area';
                    }
                    if (!street) {
                            errors.push('- Street is required');
                        } else {
                            if (street.length > 128) {
                                errors.push('- Street must be less than 128 characters');
                            }
                            if (/^\d+$/.test(street)) {
                                errors.push('- Street must contain letters');
                            }
                    }
                    if (building) {
                        if (building.length > 64) {
                            errors.push('- Building must be less than 64 characters');
                        }
                        if (/^\d+$/.test(building)) {
                            errors.push('- Building must contain letters');
                        }
                    }
                    if (unit && unit.length > 64) {
                        errors.push('- Unit must be less than 64 characters');
                    }

                    if (!state) errors.push('- State is required');
                    if (!area) errors.push('- Area is required');
                    if (!building) errors.push('- Building is required');

                    if (errors.length > 0) {
                        toastr.error('Please fix these errors:\n' + errors.join('\n'));
                        return;
                    }
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
                    unit + '" ></td>');
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
            $(document).on('click', '.removeRow', function () {
                $(this).closest('tr').remove();
            });

            $('#registerTabs a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

            $('#registerTabsContent').find(
                '.tab-pane button:not(#resendCodeBtn,#addRow,.removeRow,#registerShop,.prv-tab,.dropdown-toggle)')
                .on('click', function (e) {
                    // // Iterate over each warehouse row


                    var csrfToken = $('meta[name="csrf-token"]').attr('content');


                    var shouldContinue = true; // Initialize the boolean variable
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

                    var email = $('#email').val();

                    var salt_url = '{{ route("generateSalt", ["email" => ":email"]) }}';
                    salt_url = salt_url.replace(':email', email);

                    if ($(this).attr('data-action') == 'register') {
                        const form = $('#shop');
                        Swal.fire({
                            title: 'Hang tight. This’ll take just a sec.',
                            html: 'Now’s a good time to stretch.',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading() }
                        }); 


                        $.ajax({
                            url: salt_url, // Replace this with your Laravel backend route
                            method: 'GET', // or 'GET', depending on your backend setup
                            headers: {
                                'Platform-key': '{{ Config('app.system_key') }}',
                                'mobile-version': '{{ Config('api.mobile_version') }}',
                            },
                            success: function (response) {

                                var hashedPassword = hashPass(email, $('#password').val(), response.salt, response.num_hashing_rounds);
                                formData.append('password', hashedPassword);
                                formData.append('password_confirmation', hashedPassword);

                                $.ajax({
                                    url: form.attr('action'),
                                    type: 'POST',
                                    // data: form.serialize(),
                                    data: formData,
                                    contentType: false, // Required for sending FormData
                                    processData: false, // Required for sending FormData
                                    success: function (response) {
                                        Swal.close(); 
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
                                        if (response.hasOwnProperty('verif_staff_login') && response.verif_staff_login ===
                                            true && response.staff === true) {
                                            window.location.href = "{{ url('/seller/dashboard') }}";
                                            return;
                                        }
                                        if (response.hasOwnProperty('verif_login') && response.verif_login ===
                                            true) {
                                            $('#personal_info-tab, #code_verification-tab').addClass(
                                                'disabled');
                                            $('#personal_info, #code_verification').addClass('disabled');

                                            $('#registerTabs a[data-toggle="tab"]').on('click', function (e) {
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
                                    error: function (xhr) {
                                        Swal.close();
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
                                                shouldContinue = false;

                                            }
                                            else if (xhr.status === 422) {  // Add this block for validation errors
                                                var errors = xhr.responseJSON.errors;
                                                if (errors) {
                                                    displayValidationErrors(errors, form);
                                                    $(form).find('.is-invalid').first().focus();
                                                }
                                                shouldContinue = false;  // Prevent generic message
                                            }
                                            else {
                                                // Handle other errors
                                                toastr.error(xhr.responseJSON.message || "{{ translate('An unexpected error occurred.') }}");
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

                                        
                                        }

                                    }
                                });
                            },
                            error: function (xhr, status, error) {
                                Swal.close();

                                toastr.error(
                                    "{{ translate('Something Wrong.') }}"
                                );
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Hang tight. This’ll take just a sec.',
                            html: 'Now’s a good time to stretch.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false, 
                            success: function (response) {
                                Swal.close();

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
                                if (response.hasOwnProperty('verif_staff_login') && response.verif_staff_login ===
                                    true && response.staff === true) {
                                    window.location.href = "{{ url('/seller/dashboard') }}";
                                    return;
                                }
                                if (response.hasOwnProperty('verif_login') && response.verif_login ===
                                    true) {
                                    $('#personal_info-tab, #code_verification-tab').addClass(
                                        'disabled');
                                    $('#personal_info, #code_verification').addClass('disabled');

                                    $('#registerTabs a[data-toggle="tab"]').on('click', function (e) {
                                        e.preventDefault();
                                    });
                                }


                                // Switch to the next tab if the save operation is successful
                                if (response.success) {
                                    Swal.close();

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
                            error: function (xhr) {
                                Swal.close();

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
                                    else if (xhr.status === 422) { 
                                        var errors = xhr.responseJSON.errors;
                                        if (errors) {
                                            displayValidationErrors(errors, form);
                                            $(form).find('.is-invalid').first().focus();
                                        }
                                        shouldContinue = false;  
                                    }
                                    else {
                                        toastr.error(xhr.responseJSON.message || "{{ translate('An unexpected error occurred.') }}");
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
                    }



                });

            $('#registerTabsContent').find('.tab-pane button#registerShop').on('click', function (e) {


                // // Iterate over each warehouse row
                e.preventDefault();
                // Create a FormData object to store all form data
                var formData = new FormData();

                // Iterate over each form and append its data to the main FormData object
                $('#businessInfoForm, #contactPersonForm, #warehousesForm, #payoutInfoForm').each(
                    function () {
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
                    success: function (response) {

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
                    error: function (xhr) {

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
                                        function () {

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



            // Add an event listener to the resend button
            $('#resendCodeBtn').on('click', function () {
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
                    success: function (response) {
                        // Handle success, e.g., show a message
                        toastr.success(response.message);

                        // You can add additional logic here if needed
                    },
                    error: function (xhr) {
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
               

                // Display new error messages

                $.each(errors, function (field, messages) {


                    if (form.attr('id') == "warehousesForm") {

                        var correctedFieldName = field.split('.')[0].replace('[', '\\[').replace(']',
                            '\\]');
                        var index = parseInt(field.split('.')[1]);


                        var inputField = $('[name="' + correctedFieldName + '[]"]:eq(' + index + ')');
                    } else {
                        var inputField = form.find('[name="' + field + '"]');
                    }
                    var errorContainer = $('<div class="invalid-feedback"></div>');

                    $.each(messages, function (key, message) {
                        errorContainer.append('<strong>' + message.replace(/^(The )?.*?( of .*?)? format is/, 'The format is') + '</strong><br>');
                    });

                    inputField.addClass('is-invalid');

                    // Check if the current field is the password field and if it has the class "is-invalid"
                    if (field === 'password' && inputField.hasClass('is-invalid')) {
                        $("#password_confirmation").addClass('is-invalid');
                    }

                    inputField.after(errorContainer);
                    // Check if input has class 'custom-file-input'
                    if (inputField.hasClass('custom-file-input')) {
                        // Append error message after the <small> element
                        inputField.closest('.form-group').find('small').before(errorContainer);
                    }

                    form.find('.form-control').each(function () {
                        var inputField = $(this);

                        if (inputField.hasClass('is-invalid')) {
                            tabErreur = $(this).closest('.tab-pane').attr('id')
                            $('#' + tabErreur + "-tab").addClass('has-errors');

                        }
                    });
                });

                // Highlight fields without errors
                form.find('.form-control').each(function () {
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

                form.find('.form-control').each(function () {
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
                $('html, body').stop(true).animate({
                    scrollTop: $(window).height() * 0.3
                }, 0);

            }

            $('#emirateempire').change(function () {
                var getAreaUrl = "{{ route('get.area', ['id' => ':id']) }}";

                var id = $(this).val();
                $('#areaempire').find('option').not(':first').remove();

                // AJAX request
                $.ajax({
                    //  url: '/user/getArea/'+id,

                    url: getAreaUrl.replace(':id', id), // Use the route variable
                    type: 'get',
                    dataType: 'json',
                    success: function (response) {

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

            $('input[name="vat_registered"]').change(function () {
                var isVatRegistered = $(this).val() == 1;

                // Show/hide relevant form groups based on VAT registration status
                $('#vatCertificateGroup').toggle(isVatRegistered);
                $('#trnGroup').toggle(isVatRegistered);
                $('#taxWaiverGroup').toggle(!isVatRegistered);

                // Set or remove the "required" attribute based on VAT registration status
                $('#vatCertificate').prop('required', isVatRegistered);
                $('#trn').prop('required', isVatRegistered);
                $('#taxWaiver').prop('required', false);
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
            }



            $(document).on('change', '.emirateSelect', function () {

                // $('').on('change', function() {
                var emirateId = $(this).val();

                var areaSelect = $(this).closest('.warehouseRow').find('.areaSelect');

                // Make an AJAX call to get areas based on the selected emirate
                $.ajax({
                    url: '{{ route('get.area', ['id' => ':id']) }}'.replace(':id', emirateId),
                    method: 'GET',
                    success: function (response) {
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
                    error: function (error) {
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
        $(document).ready(function () {
            const $password = $('#password');
            const $bar = $('#strength-bar');
            const $criteria = $('#password-criteria li');
            let dictionary = [];
            $.getJSON("{{ route('get.words') }}")
                .done(words => {
                    dictionary = words.filter(w => w.length >= 3);
                })
                .always(() => {
                    $('#dict-loader').hide();
                });


            const rules = {
                length: (val) => val.length >= 8,
                /*                 uppercase: (val) => /[A-Z]/.test(val),
                                lowercase: (val) => /[a-z]/.test(val),
                                number: (val) => /\d/.test(val),
                                special: (val) => /[@#\-+/=$!%*?&]/.test(val),
                 */
                allowedChars: v => /^[A-Za-z0-9@#\-+\/=$!%*?&]+$/.test(v),
                maxNumbers: v => (v.match(/\d/g) || []).length <= 3,
                noSeqNum: val => {
                    for (let i = 0; i + 2 < val.length; i++) {
                        const a = +val[i], b = +val[i + 1], c = +val[i + 2];
                        if (!isNaN(a) && !isNaN(b) && !isNaN(c)) {
                            if ((b - a === 1 && c - b === 1) || (a - b === 1 && b - c === 1)) return false;
                        }
                    }
                    return true;
                },
                noSeqChar: val => {
                    for (let i = 0; i + 2 < val.length; i++) {
                        const a = val.charCodeAt(i), b = val.charCodeAt(i + 1), c = val.charCodeAt(i + 2);
                        const sa = val[i], sb = val[i + 1], sc = val[i + 2];
                        const sameCase = (sa === sa.toLowerCase() && sb === sb.toLowerCase() && sc === sc.toLowerCase())
                            || (sa === sa.toUpperCase() && sb === sb.toUpperCase() && sc === sc.toUpperCase());
                        if (sameCase && ((b - a === 1 && c - b === 1) || (a - b === 1 && b - c === 1))) {
                            return false;
                        }
                    }
                    return true;
                },
                maxCategory: v => {
                    const counts = {};
                    for (let ch of v) {
                        counts[ch] = (counts[ch] || 0) + 1;
                        if (counts[ch] > 3) return false;
                    }
                    return true;
                },

                noNameEmail: val => {
                    const target = val.toLowerCase();
                    const sources = [
                        $('#first_name').val().toLowerCase(),
                        $('#last_name').val().toLowerCase(),
                        $('#email').val().toLowerCase()
                    ];
                    return sources.every(str => {
                        for (let L = 3; L <= str.length; L++) {
                            for (let i = 0; i + L <= str.length; i++) {
                                if (target.includes(str.substr(i, L))) {
                                    return false;
                                }
                            }
                        }
                        return true;
                    });
                },

                noDict: v => {
                    const lowerV = v.toLowerCase();
                    return !dictionary.some(w => lowerV.includes(w));
                }


            };

            $password.on('input', function () {
                const val = $(this).val();
                let passed = 0;

                // Evaluate each rule
                $criteria.each(function () {
                    const rule = $(this).data('rule');
                    const valid = rules[rule](val);
                    $(this).find('span')
                        .text(valid ? '✔' : '✘')
                        .toggleClass('text-danger', !valid)
                        .toggleClass('text-success', valid);
                    if (valid) passed++;
                });

                // Update progress bar
                const strength = (passed / Object.keys(rules).length) * 100;
                $bar.css('width', strength + '%');

                if (strength <= 40) {
                    $bar.removeClass().addClass('progress-bar bg-danger');
                } else if (strength < 80) {
                    $bar.removeClass().addClass('progress-bar bg-warning');
                } else {
                    $bar.removeClass().addClass('progress-bar bg-success');
                }
            });

            $('.password-toggle').on('click', function () {
                const $icon = $(this);
                const targetSelector = $icon.data('target') || '#password';
                const $input = $(targetSelector);
                const isPassword = $input.attr('type') === 'password';

                $input.attr('type', isPassword ? 'text' : 'password');
                $icon.toggleClass('la-eye la-eye-slash');

            });

        });
        
        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            input.addEventListener('change', function (e) {
                var fileName = e.target.files[0].name; 
                var label = e.target.nextElementSibling; 
                label.innerText = fileName; 
            });
        });


        $(document).ready(function () {
            $('.datepicker').datepicker({
                dateFormat: 'dd M yy', 
                changeYear: true,      
                yearRange: "-100:+10"  
            });
        });
        function hashPass(username, password, salt, rounds) {
            let hash = username;
            for (var i = 0; i < rounds; i++) {
                hash = password + salt + hash;
                hash = sha3_512(hash);
            }
            return hash;
        }
    </script>
  
@endsection