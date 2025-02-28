<!doctype html>
@if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
    <title>{{ get_setting('website_name') . ' | ' . get_setting('site_motto') }}</title>

    <!-- google font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

    <!-- aiz core css -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
        <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-seller.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
        integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ static_asset('assets/css/countrySelect.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css" rel="stylesheet">

    @stack('styles')
    <style>
        /* Override Dropify's default message font size */
        .dropify-wrapper .dropify-message p {
            font-size: 13px !important;
            /* Adjust the font size as needed */
        }

        .country-select {
            width: 100%;
        }

        .country-select.inside input,
        .country-select.inside input[type=text] {
            width: 100%;
            height: calc(1.3125rem + 1.2rem + 2px);
            border: 1px solid #e4e5eb;
            color: #898b92;

        }

        .icon-delete-pricing {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 14px;
        }

        .div-btn {
            display: flex;
            width: 100%;
            justify-content: center;
        }

        #bloc_pricing_configuration_variant {
            width: 94%;
            margin-left: 19px;
            margin-top: 21px;
        }

        .bloc_pricing_configuration_variant {
            width: 94%;
            margin-left: 19px;
            margin-top: 21px;
        }

        #bloc_sample_pricing_configuration_variant {
            width: 98%;
            margin-left: 14px;
            margin-top: 21px;
        }

        .bloc_sample_pricing_configuration_variant {
            width: 98%;
            margin-left: 14px;
            margin-top: 21px;
        }

        #general_attributes {
            width: 100%;
            margin-left: 19px !important;
        }

        .font-size-icon {
            font-size: 23px;
        }

        .container-img {
            position: relative;
        }

        .icon-delete-image {
            position: absolute;
            color: red;
            top: 0;
            right: -11px;
        }

        .icon-delete-image:hover {
            cursor: pointer;
        }

        .custom-th {
            background-color: rgb(242 242 242);
        }

        .dataTables_wrapper .dataTables_filter {
            float: left !important;

        }

        .dataTables_filter input[type="search"] {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"%3E%3Cpath fill="%23757575" d="M505.1 442.7L392.4 330c-7.4-7.5-19.4-7.5-26.9 0s-7.4 19.7 0 27.3l112.7 112.7c7.5 7.5 19.7 7.5 27.2 0l27.2-27.2c7.5-7.5 7.5-19.7 0-27.3zM184 0C82.6 0 0 82.6 0 184c0 101.3 82.6 184 184 184 101.4 0 184-82.7 184-184C368 82.6 285.3 0 184 0zm0 328c-72.1 0-136-64.9-136-136 0-72.1 63.9-136 136-136s136 63.9 136 136c0 71.1-63.9 136-136 136z"%3E%3C/path%3E%3C/svg%3E');
            background-position: right 10px center;
            /* Position icon to the left */
            background-repeat: no-repeat;
            border: 1px solid rgb(217, 216, 216) !important;
            /* Blue border */
            border-radius: 5px !important;
            /* Rounded corners */
            padding: 5px 10px !important;
            /* Padding */
            background-size: 16px;
            /* Adjust the size of the background image */

        }

        .btn-excel {
            background-color: white !important;
            /* White background */
            color: #a2b8c6 !important;
            /* Blue text color */
            border: 1px solid #a2b8c6 !important;
            /* Blue border */
            border-radius: 5px !important;
            /* Rounded corners */
            padding: 5px 10px !important;
            /* Padding */
            margin-left: 10px !important;
            /* Adjust as needed */
        }

        div.dt-buttons {
            float: right !important;
        }

        div.dt-buttons>.dt-button,
        div.dt-buttons>div.dt-button-split .dt-button {
            background: none !important;
            /* White background */

        }

        .customer-btn-color {
            border-radius: 5px !important;
            /* Rounded corners */
            background-color: #a2b8c6 !important;
            /* Initial background color */
            border: 1px solid #a2b8c6 !important;
            /* Border color */
        }

        .customer-btn-color:hover {
            background-color: #1b3a57 !important;
            /* Navy blue background on hover */
            border: 1px solid #1b3a57 !important;
            /* Navy blue border on hover */
        }
        .disabled-look__clz {
            opacity: 1;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Include MultiSelect CSS -->
    <link rel="stylesheet" href="https://cdn.rawgit.com/nobleclem/jQuery-MultiSelect/master/jquery.multiselect.css">
    <link rel="stylesheet" href="{{ static_asset('assets/css/filter_multi_select.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/example-styles.css') }}">
    <style>
        body {
            font-size: 12px;
        }

        #map {
            width: 100%;
            height: 250px;
        }

        #edit_map {
            width: 100%;
            height: 250px;
        }

        .pac-container {
            z-index: 100000;
        }

        .plus,
        .minus {
            display: inline-block;
            background-repeat: no-repeat;
            background-size: 16px 16px !important;
            width: 16px;
            height: 16px;
        }

        .plus {
            background-image: url(https://img.icons8.com/android/24/plus.png);
        }

        .minus {
            background-image: url(https://img.icons8.com/material-rounded/24/minus.png);
        }

        .square-variant {
            margin-right: 10px;
            color: black;
        }

        ul {
            list-style: none;
            padding: 0px 0px 0px 20px;
        }

        ul.inner_ul li:before {
            content: "├";
            font-size: 18px;
            margin-left: -11px;
            margin-top: -5px;
            vertical-align: middle;
            float: left;
            width: 8px;
            color: #41424e;
        }

        ul.inner_ul li:last-child:before {
            content: "└";
        }

        .inner_ul {
            padding: 0px 0px 0px 35px;
        }

        .width-badge {
            width: 100%;
        }

        .ms-options-wrap>.ms-options {
            position: absolute;
            left: 0;
            width: 100%;
            margin-top: 1px;
            margin-bottom: 20px;
            background: white;
            z-index: 2000;
            border: 1px solid #aaa;
            overflow: auto;
            visibility: hidden;
        }

        .bloc-default-shipping-style {
            border: 1px solid gainsboro;
            border-radius: 5px;
            padding: 15px 26px;
        }

        .coming-soon-container {
            text-align: center;
            padding: 50px;
            background-color: #f7f8fa;
        }

        .coming-soon-container img {
            max-width: 100%;
            height: auto;
        }

        .coming-soon-container h1 {
            font-weight: 700;
            font-size: 2.5em;
            /* Adjusted size for visibility */
            color: #333;
            /* Adjusted color for contrast */
            margin-bottom: 0.5em;
            /* Spacing adjusted */
        }

        .coming-soon-container p {
            color: #666;
            /* Adjusted color for contrast */
            font-size: 1em;
            /* Adjusted size for readability */
            margin-bottom: 2em;
            /* Spacing adjusted */
        }

        .email-input {
            padding: 15px;
            margin-right: 10px;
            /* Space between input and button */
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
            /* Fixed width for the input */
        }

        .notify-btn {
            padding: 15px 25px;
            background-color: #A2B8C6;
            /* Button color reference */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-size: 1em;
            /* Adding hover effect for the button */
            transition: background-color 0.3s ease;
        }

        .notify-btn:hover {
            background-color: #8a9ba8;
            /* Slightly darker shade on hover */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .coming-soon-container img {
                max-width: 70%;
                /* Larger image on smaller screens */
            }

            .email-input {
                width: auto;
                /* Full width on small screens */
                margin: 0 0 1em 0;
                /* Stack input above button */
            }

            .notify-btn {
                width: auto;
                /* Full width on small screens */
            }
        }
    </style>
    <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
</head>
<body class="">
    <div class="aiz-main-wrapper">
        @include('seller.inc.seller_sidenav')
        <div class="aiz-content-wrapper">
            @include('seller.inc.seller_nav')
            <div class="aiz-main-content">
                <div class="px-15px px-lg-25px">
                    @yield('panel_content')
                </div>
                <div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto border-sm-top">
                    <p class="mb-0">&copy; {{ get_setting('site_name') }} v{{ get_setting('current_version') }}</p>
                </div>
            </div><!-- .aiz-main-content -->
        </div><!-- .aiz-content-wrapper -->
    </div><!-- .aiz-main-wrapper -->

    @yield('modal')

    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <!-- Select extension -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var numbers_variant = 0;

        $(document).ready(function() {
            $('body').on('change', '.shipper', function() {
                let count_shippers = parseInt($(this).data("count_shippers"));
                let selected = $(this).val();

                if(["vendor", "third_party"].includes(selected) === true) {
                    $(this).parent().parent().find("input,select").each(function(index, el) {
                        if(index !== 0) {
                            $(el).val(null);

                            if (selected === "third_party") {
                                if(
                                    [
                                        "paid[]",
                                        "shipping_charge[]"
                                    ].includes($(el).attr("name")) === true ||
                                    $(el).attr("class").includes("paid") === true ||
                                    $(el).attr("class").includes("shipping_charge") === true
                                ) {
                                    $(el).addClass("disabled-look__clz")
                                } else if(
                                    [
                                        "from_shipping[]",
                                        "to_shipping[]",
                                        "estimated_order[]",
                                    ].includes($(el).attr("name")) === true ||
                                    [
                                       "min-qty-shipping", "max-qty-shipping"
                                    ].includes($(el).attr("id")) === true ||
                                    $(el).attr("class").includes("estimated_order") === true ||
                                    $(el).attr("class").includes("min-qty-shipping") ||
                                    $(el).attr("class").includes("max-qty-shipping")
                                ) {
                                    $(el).attr("readonly", false);
                                } else {
                                    $(el).attr("readonly", true);
                                }
                            } else {
                                if($(el).hasClass("disabled-look__clz")) {
                                    $(el).removeClass("disabled-look__clz");
                                } else {
                                    $(el).attr("readonly", false);
                                }
                            }
                        }
                    });
                } else {
                    $(this).parent().parent().find("input,select").each(function(index, el) {
                        if(index !== 0) {
                            $(el).val(null)

                            if (
                                [
                                    "paid[]",
                                    "shipping_charge[]"
                                ].includes($(el).attr("name")) === true ||
                                $(el).attr("class").includes("paid") === true ||
                                $(el).attr("class").includes("shipping_charge") === true
                            ) {
                                $(el).addClass("disabled-look__clz")
                            } else {
                                $(el).attr("readonly", true)
                            }
                        }
                    });
                }

                if (selected.indexOf('third_party') !== -1) {
                     $(this).parent().parent().find('.paid').find("option:last")
                            .prop("selected", true);

                    if (count_shippers == 0) {
                        let title = "{{ translate('Default Shipping Configuration') }}";
                        let message = '{{ __("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}';

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');
                    } else {
                        let weight = $('#weight').val();
                        let length = $('#length').val();
                        let width = $('#width').val();
                        let height = $('#height').val();
                        let breakable = $('#breakable').val();
                        let min_third_party = $('#min_third_party').val();
                        let max_third_party = $('#max_third_party').val();
                        let unit_third_party = $('#unit_third_party').val();

                        if ((weight == '') || (length == '') || (width == '') || (height == '') || (
                                min_third_party == '') || (max_third_party == '')) {
                            let title = "{{ translate('Default Shipping Configuration') }}";
                            let message =
                                "{{ translate('Please ensure that all required fields are filled to know all information about your package.') }}";

                            $('#title-modal').text(title);
                            $('#text-modal').html(message);

                            $('#modal-info').modal('show');
                        } else {
                            length = parseInt(length);
                            height = parseInt(height);
                            width = parseInt(width);
                            weight = parseInt(weight);
                            let volumetric_weight = (length * height * width) / 5000;
                            let chargeable_weight = 0;
                            let html = '';

                            if (volumetric_weight > weight) {
                                chargeable_weight = volumetric_weight;
                            } else {
                                chargeable_weight = weight;
                            }

                            if (chargeable_weight > 30) {
                                let title = "{{ translate('Default Shipping Configuration') }}";
                                let message = "{{ translate('Chargeable Weight = ') }}" + Number(
                                        chargeable_weight.toFixed(2)) +
                                    ", {{ translate('then not accepted by our shipper') }}";

                                $('#title-modal').text(title);
                                $('#text-modal').text(message);

                                $('#modal-info').modal('show');
                            } else {
                                $(this).parent().parent().find('.estimated_shipping').prop('readonly',
                                    true);
                                $(this).parent().parent().find('.shipping_charge').find("option:first")
                                    .prop("selected", true);
                                $(this).parent().parent().find('.shipping_charge').addClass('disabled-look__clz');

                                $(this).parent().parent().find('.paid').find("option:last")
                                    .prop("selected", true);
                                $(this).parent().parent().find('.paid').addClass("disabled-look__clz");

                                $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly',
                                    true);
                                $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                                $(this).parent().parent().find('.estimated_shipping').val(null);

                                $(this).parent().parent().find('.flat_rate_shipping').prop('readonly',
                                    true);
                                $(this).parent().parent().find('.flat_rate_shipping').val(null);
                            }
                        }
                    }
                }

                if (selected.indexOf('vendor') !== -1) {
                    $(this).parent().parent().find('.estimated_shipping').prop('readonly', false);
                    $(this).parent().parent().find('.shipping_charge').find("option:first").prop("selected",
                        true);
                    $(this).parent().parent().find('.charge_per_unit_shipping').prop('readonly', false);
                    $(this).parent().parent().find('.charge_per_unit_shipping').val(null);
                    $(this).parent().parent().find('.paid').val(null);
                    $(this).parent().parent().find('.estimated_shipping').val(null);
                    $(this).parent().parent().find('.flat_rate_shipping').prop('readonly', false);
                    $(this).parent().parent().find('.flat_rate_shipping').val(null);
                }
            });

            $('body').on('change', '.shipper_sample', function() {
                let count_shippers = parseInt($(this).data("count_shippers"));
                let selected = $(this).val();

                if (["vendor", "third_party"].includes(selected) === true) {
                    $(this).parent().parent().find('input,select').each(function(index, el) {
                        if(index !== 0) {
                            $(el).val(null);

                            if (selected === "third_party") {
                                if(
                                    [
                                        "paid_sample[]",
                                    ].includes($(el).attr("name")) === true ||
                                    $(el).attr("class").includes("paid_sample") === true
                                ) {
                                    $(el).addClass("disabled-look__clz")
                                } else if(
                                    [
                                        "estimated_sample[]",
                                    ].includes($(el).attr("name")) === true ||
                                    $(el).attr("class").includes("estimated_sample") === true
                                ) {
                                    $(el).attr("readonly", false);
                                } else {
                                    $(el).attr("readonly", true);
                                }
                            } else {
                                if($(el).hasClass("disabled-look__clz")) {
                                    $(el).removeClass("disabled-look__clz");
                                } else {
                                    $(el).attr("readonly", false);
                                }
                            }
                        }
                    });
                } else {
                    $(this).parent().parent().find('input,select').each(function(index, el) {
                        if(index !== 0) {
                            $(el).val(null)

                            if (
                                [
                                    "paid_sample[]",
                                ].includes($(el).attr("name")) === true
                            ) {
                                $(el).addClass("disabled-look__clz")
                            } else {
                                $(el).attr("readonly", true)
                            }
                        }
                    });
                }

                if (selected.indexOf('third_party') !== -1) {
                    $(this).parent().parent().find('.shipping_amount').val('');
                    $(this).parent().parent().find('.shipping_amount').prop('readonly', true);
                    $(this).parent().parent().find('.estimated_shipping_sample').val('');
                    $(this).parent().parent().find('.estimated_shipping_sample').prop('readonly', true);
                    $(this).parent().parent().find('.paid_sample').find("option:last").prop("selected", true);

                    if (count_shippers == 0) {
                        let title = "{{ translate('Default Shipping Configuration') }}";
                        let message = '{{ __("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}';

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');

                        $(this).find("option:first").prop("selected", true)
                        $(this).parent().parent().find('.shipping_amount').val('');
                        $(this).parent().parent().find('.shipping_amount').prop('readonly', false);
                        $(this).parent().parent().find('.estimated_shipping_sample').val('');
                        $(this).parent().parent().find('.estimated_shipping_sample').prop('readonly', false);
                        $(this).parent().parent().find('.paid_sample').find("option:first").prop("selected", true);
                    } else {
                        let weight = $('#package_weight_sample').val();
                        let length = $('#length_sample').val();
                        let width = $('#width_sample').val();
                        let height = $('#height_sample').val();
                        let breakable = $('#breakable_sample').val();
                        let min_third_party = $('#min_third_party_sample').val();
                        let max_third_party = $('#max_third_party_sample').val();
                        let unit_third_party = $('#unit_third_party_sample').val();

                        if ((weight == '') || (length == '') || (width == '') || (height == '') || (
                                min_third_party == '') || (max_third_party == '')) {
                            let title = "{{ translate('Default Shipping Configuration') }}";
                            let message =
                                "{{ translate('Please ensure that all required fields are filled to know all information about your package.') }}";

                            $('#title-modal').text(title);
                            $('#text-modal').html(message);

                            $('#modal-info').modal('show');
                        } else {
                            length = parseInt(length);
                            height = parseInt(height);
                            width = parseInt(width);
                            weight = parseInt(weight);
                            let volumetric_weight = (length * height * width) / 5000;
                            let chargeable_weight = 0;
                            let unit = $('#weight_unit_sample').val();
                            let max = 30;
                            if (unit == "pounds") {
                                max *= 2.2;
                            }
                            let html = '';
                            if (volumetric_weight > weight) {
                                chargeable_weight = volumetric_weight;
                            } else {
                                chargeable_weight = weight;
                            }

                            if (unit == "pounds") {
                                chargeable_weight *= 2.2;
                            }

                            if (chargeable_weight > max) {
                                var title = "{{ translate('Default Shipping Configuration') }}";
                                var message = "{{ translate('Chargeable Weight = ') }}" + Number(
                                        chargeable_weight.toFixed(2)) +
                                    ", {{ translate('then not accepted by our shipper') }}";

                                $('#title-modal').text(title);
                                $('#text-modal').text(message);

                                $('#modal-info').modal('show');
                            }
                        }
                    }
                }

                if (selected.indexOf('vendor') !== -1) {
                    $(this).parent().parent().find('.shipping_amount').prop('readonly', false);
                    $(this).parent().parent().find('.estimated_shipping_sample').prop('readonly', false);
                    $(this).parent().parent().find('.paid_sample').val('');
                }
            });

            $('body').on('click', '#third_party_activate', function() {
                if ($(this).is(':checked')) {
                    let count_shippers = parseInt($(this).data("count_shippers"));

                    if (count_shippers == 0) {
                        $('body input[name="activate_third_party"]').prop('checked', false);

                        var title = "{{ translate('Default Shipping Configuration') }}";
                        var message = '{{ __("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}';

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');

                        $(this).prop('checked', false)
                    } else {
                        $('#bloc_third_party input[type="number"]').each(function() {
                            $(this).prop('readonly', false);
                        });

                        $('#bloc_third_party select').each(function() {
                            $(this).prop('disabled', false);
                        });
                    }
                } else {
                    $('#bloc_third_party input[type="number"]').each(function() {
                        $(this).prop('readonly', true);
                    });

                    $('#bloc_third_party select').each(function() {
                        $(this).prop('disabled', true);
                    });

                    $('#bloc_third_party input[type="number"]').val('').prop('readonly', true);
                }
            });

            $('body').on('click', '#third_party_activate_sample', function() {
                if ($(this).is(':checked')) {
                    let count_shippers = parseInt($(this).data("count_shippers"));

                    if (count_shippers == 0) {
                        $('body input[name="activate_third_party"]').prop('checked', false);
                        var title = "{{ translate('Default Shipping Configuration') }}";
                        var message = '{{ __("You don't have any warehouse supported by MawadOnline 3rd party shippers. If you haven't created your warehouses, you can save the product as draft, create your warehouses by going to the Warehouses page under Inventory Management, and then you may continue editing your product.") }}';

                        $('#title-modal').text(title);
                        $('#text-modal').html(message);

                        $('#modal-info').modal('show');

                        $(this).prop('checked', false)
                    } else {
                        $('#bloc_third_party_sample input[type="number"]').each(function() {
                            $(this).prop('readonly', false);
                        });

                        $('#bloc_third_party_sample select').each(function() {
                            $(this).prop('disabled', false);
                        });
                    }
                } else {
                    $('#bloc_third_party_sample input[type="number"]').each(function() {
                        $(this).prop('readonly', true);
                    });

                    $('#bloc_third_party_sample select').each(function() {
                        $(this).prop('disabled', true);
                    });

                    $('#bloc_third_party_sample input[type="number"]').val('').prop('readonly', true);
                }
            });

            $('body').on('click', '#btn-create-variant', function() {
                if ($('#attributes option:selected').length > 0) {
                    let clonedDiv = $('body #variant_informations').clone();

                    clonedDiv.attr('class', 'clonedDiv');
                    clonedDiv.removeAttr('id');
                    clonedDiv.attr('data-id', numbers_variant);

                    let count = numbers_variant + 1;

                    @if (app()->getLocale() == 'ae')
                        let html_to_add =
                            '<div style="float: left; margin-top: -35px"><i class="fa-regular fa-circle-xmark fa-lx delete-variant" style="font-size: 16px;" title="delete this variant"></i></div>'
                    @else
                        let html_to_add =
                            '<div style="float: right; margin-top: -35px"><i class="fa-regular fa-circle-xmark fa-lx delete-variant" style="font-size: 16px;" title="delete this variant"></i></div>'
                    @endif

                    clonedDiv.find('h3').after(html_to_add);
                    clonedDiv.find('.fa-circle-check').hide();
                    clonedDiv.find('#btn-add-pricing-variant').hide();

                    clonedDiv.find('.sku').attr('name', 'sku-' + numbers_variant);
                    clonedDiv.find('.sku').prop('readonly', true);

                    clonedDiv.find('div.row').each(function() {
                        if ($(this).css('display') === 'none') {
                            $(this).css('display', '');
                        }
                    });

                    clonedDiv.find('.vat_sample').attr('name', 'vat_sample-' + numbers_variant);
                    clonedDiv.find('.sample_description').attr('name', 'sample_description-' +
                        numbers_variant);
                    clonedDiv.find('.sample_price').attr('name', 'sample_price-' + numbers_variant);
                    clonedDiv.find('.photos_variant').attr('name', 'photos_variant-' + numbers_variant +
                        '[]');
                    clonedDiv.find('.photos_variant').attr('id', 'photos_variant-' + numbers_variant);
                    clonedDiv.find('.custom-file-label').attr('for', 'photos_variant-' + numbers_variant);
                    clonedDiv.find('.variant-pricing').attr('name', 'variant-pricing-' + numbers_variant);
                    clonedDiv.find('.variant-pricing').attr('data-variant', numbers_variant);

                    clonedDiv.find('.min-qty-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[from][]');
                    });
                    clonedDiv.find('.max-qty-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[to][]');
                    });
                    clonedDiv.find('.unit-price-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[unit_price][]');
                    });
                    clonedDiv.find('.discount_percentage-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[discount_percentage][]');
                    });
                    clonedDiv.find('.discount_amount-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[discount_amount][]');
                    });
                    clonedDiv.find('.discount-range-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[discount_range][]');
                        $(element).daterangepicker({
                            timePicker: true,
                            autoUpdateInput: false,
                            minDate: today,
                            locale: {
                                format: 'DD-MM-Y HH:mm:ss',
                                separator: " to ",
                            },
                        });

                        let format = 'DD-MM-Y HH:mm:ss';
                        let separator = " to ";

                        $(element).on("apply.daterangepicker", function(ev, picker) {
                            $(this).val(
                                picker.startDate.format(format) +
                                separator +
                                picker.endDate.format(format)
                            );
                        });
                    });
                    clonedDiv.find('.variant-shipping').attr('name', 'variant-shipping-' + numbers_variant);
                    clonedDiv.find('.variant-shipping').attr('data-id_variant', numbers_variant);

                    clonedDiv.find('.stock-warning').attr('name', 'stock-warning-' + numbers_variant);
                    clonedDiv.find('.discount_type-variant').each(function(index, element) {
                        $(element).attr('name', 'variant_pricing-from' + numbers_variant +
                            '[discount_type][]');
                        $('#variant_informations').find('.discount_type-variant').each(function(key,
                            element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });
                    clonedDiv.find('.attributes').each(function(index, element) {
                        let dataIdValue = $(element).data('id_attributes');
                        let value = 0;
                        let check = false;
                        if ($(element).attr('data-type')) {
                            $('#variant_informations').find('.color').each(function(key,
                                element_original) {
                                if ($(element_original).data('id_attributes') ==
                                    dataIdValue) {
                                    value = $(element_original).val();
                                    $(element).attr('name', 'attributes-' + dataIdValue +
                                        '-' + numbers_variant + '[]');
                                    check = true;
                                }
                            })

                            $(element).val(value);
                        }

                        if (check == false) {
                            $(element).attr('name', 'attributes-' + dataIdValue + '-' +
                                numbers_variant);
                        }
                    });

                    clonedDiv.find('.attributes-units').each(function(index, element) {
                        let dataIdValue = $(element).data('id_attributes');

                        $(element).attr('name', 'attributes_units-' + dataIdValue + '-' +
                            numbers_variant);
                        $('#variant_informations').find('.attributes-units').each(function(key,
                            element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });

                    clonedDiv.find('.variant-sample-available').attr('name', 'variant-sample-available' +
                        numbers_variant);
                    clonedDiv.find('.variant-sample-pricing').attr('name', 'variant-sample-pricing' +
                        numbers_variant);
                    clonedDiv.find('.variant-sample-shipping').attr('name', 'variant-sample-shipping' +
                        numbers_variant);
                    clonedDiv.find('.variant-sample-shipping').attr('data-id_new_variant', numbers_variant);

                    clonedDiv.find('.min-qty-shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant + '[from][]');
                    });

                    clonedDiv.find('.max-qty-shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant + '[to][]');
                    });

                    let id_shipper = 0;

                    clonedDiv.find('.shipper').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[shipper][' + id_shipper + '][]');
                        $('#variant_informations #table_shipping_configuration').find('.shipper')
                            .each(function(key, element_original) {
                                if (index == key) {
                                    $(element_original).val().forEach(value => {
                                        $(element).find('option[value="' + value + '"]')
                                            .prop('selected', true);
                                    });
                                }
                            })

                        id_shipper++;
                    });

                    clonedDiv.find('.estimated_order').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[estimated_order][]');
                    });

                    clonedDiv.find('.estimated_shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[estimated_shipping][]');
                    });

                    clonedDiv.find('.paid').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant + '[paid][]');
                        $('#variant_informations #table_shipping_configuration').find('.paid').each(
                            function(key, element_original) {
                                if (index == key) {
                                    $(element).find('option[value="' + $(element_original)
                                        .val() + '"]').prop('selected', true);
                                }
                            })
                    });

                    clonedDiv.find('.vat_shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[vat_shipping][]');
                    });

                    clonedDiv.find('.shipping_charge').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[shipping_charge][]');
                        $('#variant_informations #table_shipping_configuration').find(
                            '.shipping_charge').each(function(key, element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });

                    clonedDiv.find('.flat_rate_shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[flat_rate_shipping][]');
                    });

                    clonedDiv.find('.charge_per_unit_shipping').each(function(index, element) {
                        $(element).attr('name', 'variant_shipping-' + numbers_variant +
                            '[charge_per_unit_shipping][]');
                    });

                    clonedDiv.find('.shipper_sample').each(function(index, element) {
                        $(element).attr('name', 'variant_shipper_sample-' + numbers_variant + '[]');
                        $('#variant_informations #table_sample_configuration').find(
                            '.shipper_sample').each(function(key, element_original) {
                            if (index == key) {
                                $(element).find('option[value="' + $(element_original)
                                    .val() + '"]').prop('selected', true);
                            }
                        })
                    });

                    clonedDiv.find('.paid_sample').each(function(index, element) {
                        $(element).attr('name', 'paid_sample-' + numbers_variant);
                        $('#variant_informations #table_sample_configuration').find('.paid_sample')
                            .each(function(key, element_original) {
                                if (index == key) {
                                    $(element).find('option[value="' + $(element_original)
                                        .val() + '"]').prop('selected', true);
                                }
                            })
                    });

                    clonedDiv.find('.estimated_sample').attr('name', 'estimated_sample-' + numbers_variant);
                    clonedDiv.find('.estimated_shipping_sample').attr('name', 'estimated_shipping_sample-' +
                        numbers_variant);
                    clonedDiv.find('.shipping_amount').attr('name', 'shipping_amount-' + numbers_variant);

                    clonedDiv.find('.delete_shipping_canfiguration').attr('data-variant-id',
                        numbers_variant);
                    clonedDiv.find('.btn-add-shipping').attr('data-variant-id', numbers_variant);
                    clonedDiv.find('.btn-add-pricing').attr('data-newvariant-id', numbers_variant);

                    if (clonedDiv.find('.sku').val() == '') {
                        var title = "{{ translate('Form validation') }}";
                        var message =
                            '{{ translate('The SKU field must be filled before creating the variant.') }}';
                        $('#title-modal').text(title);
                        $('#text-modal').text(message);

                        $('#modal-info').modal('show')
                    } else {
                        $('#bloc_variants_created').show();
                        $('#bloc_variants_created').prepend(clonedDiv);
                        var divId = "#bloc_variants_created";

                        var h3Count = $(divId + " h3").length;

                        $(divId + " h3").each(function(index) {
                            var order = h3Count - index;
                            $(this).text("{{ translate('Variant Information') }}" + ' ' + order);
                        });
                        numbers_variant++;

                        $('#variant_informations').find(
                            'input[type="text"], input[type="number"], input[type="checkbox"], input[type="radio"], select'
                        ).each(function() {
                            if ($(this).is('input[type="text"]') || $(this).is(
                                    'input[type="number"]')) {
                                $(this).val('');
                            } else if ($(this).is('input[type="radio"]')) {
                                $(this).prop('checked',
                                    false);
                            } else if ($(this).is('select')) {
                                $(this).val('');
                            }
                        });

                        $('#variant_informations').find('.filter-option-inner-inner').each(function() {
                            $(this).text('Nothing selected')
                        });
                    }
                } else {
                    var title = "{{ translate('Create variant') }}";
                    var message =
                        '{{ translate('A minimum of one attribute must be selected in order to create a variant.') }}';
                    $('#title-modal').text(title);
                    $('#text-modal').text(message);

                    $('#modal-info').modal('show')
                }
            });
        });
    </script>

    @yield('script')

    <!-- Include MultiSelect JS -->
    <script src="https://cdn.rawgit.com/nobleclem/jQuery-MultiSelect/master/jquery.multiselect.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

    <script type="text/javascript">
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach

        $('.dropdown-menu a[data-toggle="tab"]').click(function(e) {
            e.stopPropagation()
            $(this).tab('show')
        })

        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-menu a').each(function() {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}', {
                        _token: '{{ csrf_token() }}',
                        locale: locale
                    }, function(data) {
                        location.reload();
                    });

                });
            });
        }

        function menuSearch() {
            var filter, item;
            filter = $("#menu-search").val().toUpperCase();
            items = $("#main-menu").find("a");
            items = items.filter(function(i, item) {
                if ($(item).find(".aiz-side-nav-text")[0].innerText.toUpperCase().indexOf(filter) > -1 && $(item)
                    .attr('href') !== '#') {
                    return item;
                }
            });

            if (filter !== '') {
                $("#main-menu").addClass('d-none');
                $("#search-menu").html('')
                if (items.length > 0) {
                    for (i = 0; i < items.length; i++) {
                        const text = $(items[i]).find(".aiz-side-nav-text")[0].innerText;
                        const link = $(items[i]).attr('href');
                        $("#search-menu").append(
                            `<li class="aiz-side-nav-item"><a href="${link}" class="aiz-side-nav-link"><i class="las la-ellipsis-h aiz-side-nav-icon"></i><span>${text}</span></a></li`
                            );
                    }
                } else {
                    $("#search-menu").html(
                        `<li class="aiz-side-nav-item"><span class="text-center text-muted d-block">{{ translate('Nothing Found') }}</span></li>`
                        );
                }
            } else {
                $("#main-menu").removeClass('d-none');
                $("#search-menu").html('')
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <script src="{{ static_asset('assets/js/jquery.multi-select.js') }}"></script>
    <script>
        jQuery("img").one('error', function() {
            jQuery(this).attr("src",
            "{{ asset('public/images/placeholder.png') }}"); //.unbind("error") is useless here
        }).each(function() {
            if (this.complete && !this.naturalHeight && !this.naturalWidth) {
                $(this).triggerHandler('error');
            }
        });
    </script>
</body>
</html>
