@extends('seller.layouts.app')
@php
    use Carbon\Carbon;
@endphp

<style>
    /* Your existing styles */
    @media (max-width: 540px) {
        ul.nav.nav-tabs.shop {
            background: #f8f9fa;
            margin: 0;
            display: block !important;
            justify-content: space-between;
            align-items: center;
        }
    }

    @media (min-width: 822px) and (max-width: 1198px) {
        ul.nav.nav-tabs.shop {
            background: #f8f9fa;
            margin: 0;
            display: block !important;
            justify-content: space-between;
            align-items: center;
        }
    }

    @media (min-width: 579px) and (max-width: 805px) {
        ul.nav.nav-tabs.shop {
            background: #f8f9fa;
            margin: 0;
            display: block !important;
            justify-content: space-between;
            align-items: center;
        }
    }

    .color-modified {
        border: 2px dashed #e8c068e8 !important;
        box-shadow: 0 0 5px rgba(255, 204, 0, 0.5) !important;
        transition: border-color 0.3s ease, box-shadow 0.3s ease !important;
    }

    .align-items-center {
        -ms-flex-align: center !important;
    }

    .row.card-adjust {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
        flex-direction: column;
        align-content: stretch;
        justify-content: space-evenly;
        align-items: flex-start;
    }

    .color-modified-file {
        color: #e8c068e8
    }

    .highlighted-tab {
        border-color: red !important;
    }

    .btn-primary {
        background-color: #a2b8c6 !important;
        border-color: #a2b8c6 !important;
    }

    .swal2-confirm {
        background-color: var(--success) !important;
        border-color: none !important;
    }

    #social-icon-two {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }

    .swal2-confirm:hover {
        border-color: none !important;
    }

    .orange-text {
        color: #b9c9d4;
    }

    .Grand-title {
        padding-left: 0px !important;
    }

    .custom-file-input:lang(en)~.custom-file-label::after {
        content: "Browse";
        background-color: #a2b8c6;
        color: #fff;
    }

    .nav-link.active {
        background-color: #a2b8c6 !important;
        width: 200px;
    }

    .nav-tabs .nav-link {
        padding: 18px !important;
        text-align: start;
        border-top-left-radius: 1rem !important;
        border-top-right-radius: 1rem !important;
        border-bottom-left-radius: 1rem !important;
        border-bottom-right-radius: 1rem !important;
        width: 200px;
    }

    /* .nav-tabs .nav-link {
    background-color: white !important;
    } */

    .file-condition {
        font-size: 12px;
        font-weight: 450;
        margin-top: 3px;
        display: block;
    }

    li.nav-item {
        padding-bottom: 20px !important;
    }

    .optional {
        color: #b9c9d4;
        font-size: 14px;
    }

    span.aiz-side-nav-text:active {
        background-color: rgb(196, 196, 196);
        border-radius: 4% !important;
        width: auto;
        height: 50px;
    }

    .customer-color {
        background-color: #f77b0b !important;
        border: #f77b0b !important;
    }

    .card {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        margin-bottom: 10px;
        background-color: #f8f9fa;
    }

    .card-body {
        padding: 1rem;
    }

    .card-title {
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .card-deck {
        margin-right: 34px !important;
    }

    .card-link {
        color: #007bff;
        text-decoration: none;
    }

    .card-link:hover {
        text-decoration: underline;
    }

    .aiz-side-nav-item {
        display: flex;
    }

    .card {
        height: 94% !important;
    }

    .aiz-side-nav-list-content .aiz-side-nav-link {
        display: flex;
        flex-grow: 1;
        align-items: flex-start;
        padding: 10px 25px;
        font-size: 0.875rem;
        font-weight: 400;
        color: #252E4D;
    }

    .menu {
        margin-top: 42px;
    }

    ul.nav.nav-tabs.help-centre {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: nowrap;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
        flex-direction: column;
        justify-content: flex-start;
        align-content: flex-start;
        align-items: baseline;
    }

    .container-flex {
        display: flex;
        margin-top: 24px;
        margin-left: 33px;
    }

    .nav-tabs-wrapper {
        flex: 0 0 250px;
        max-width: 250px;
    }

    .tab-content-wrapper {
        flex: 1;
        padding-left: 20px;
        margin-right: 37px;
    }

    .col-md-6.adjust-card {
        padding-right: 5px !important;
        padding-left: 5px !important;
    }

    .nav-tabs {
        border-bottom: none !important;
    }

    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nav-link.active {
        color: #000 !important;
    }

    .content {
        padding-top: 20px;
    }

    .title-content {
        padding-bottom: 10px;
    }

    .title-business {
        display: inline-block;
        font-size: 1em;
        /* font-weight: bold; */
        font-size: 15px;


    }

    p.email-form-title1 {
        padding-bottom: 10px;
    }

    p.account-form-title1 {
        padding-bottom: 10px;
    }

    p.business-form-title1 {
        padding-bottom: 10px;
    }

    p.contact-form-title1 {
        padding-bottom: 10px;
    }

    p.Warehouse-form-title1 {
        padding-bottom: 10px;
    }

    p.Payout-form-title1 {
        padding-bottom: 10px;
        /* /font-weight: 500; */
    }

    span.package-title {
        font-size: 16px;
        font-weight: 500;
    }

    span.title-business {
        font-weight: 500;
    }

    span.registration-title {
        font-size: 16px !important;
        font-weight: 500 !important;
    }

    span.registration-content {
        font-size: 15px;

    }



    .tab-content>.active {
        display: block;
        text-align: justify;
    }

    .content-personal-info {
        padding-left: 20px;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        padding-bottom: 25px;
    }

    .breadcrumb-nav a {
        text-decoration: none;
        color: #000;
    }

    .breadcrumb-nav .arrow-icon {
        margin: 0 10px;
    }

    .nav-link {
        background-color: #d3d3d3;
        width: 200px;
    }

    .text-nav{
        font-size: 14px;
    }



</style>




@section('panel_content')
    <div class="aiz-titlebar mt-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-12 mt-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.help-center.index') }}">{{ __('help.Home') }}</a></li>
                        <span class="arrow-icon">
                            <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon" style="width: 17px; height: 17px;">
                        </span>
                        <li class="breadcrumb-item"><a href="/FAQS">{{ __('help.FAQS') }}</a></li>
                        <span class="arrow-icon">
                            <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon" style="width: 17px; height: 17px;">
                        </span>
                        <li class="breadcrumb-item active" aria-current="page" id="current-page">{{ __('help.Getting_Started') }}</li>
                    </ol>
                </nav>
                <h1 class="h1"><b>{{ __('help.FAQS') }}</b></h1>
            </div>
            <div class="container-flex">
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs help-centre" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="General-Q-tab" data-toggle="tab" href="#General-Q" role="tab"
                                aria-controls="General-Q" aria-selected="true"  data-title="{{ __('help.General_Q') }}">
                                <b>{{ __('help.General_Q') }}</b>
                                <p>{{ __('help.Find_ansewrs_to_general') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="getting-tab" data-toggle="tab" href="#getting" role="tab"
                                aria-controls="getting" aria-selected="false" data-title="{{ __('help.Getting_Started') }}">
                                <b>{{ __('help.Getting_Started') }}</b>
                                <p>{{ __('help.Find_ansewrs_to_guide') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="personal-information-tab" data-toggle="tab" href="#personal-information"
                                role="tab" aria-controls="personal-information" aria-selected="false" data-title="{{ __('help.product_management') }}">
                                <b>{{ __('help.product_management') }}</b>
                                <p>{{ __('help.lean_how_to_manage') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="inventory-management-tab" data-toggle="tab" href="#inventory-management"
                                role="tab" aria-controls="inventory-management" aria-selected="false" data-title="{{ __('help.Inventory_Management') }}">
                                <b>{{ __('help.Inventory_Management') }}</b>
                                <p>{{ __('help.lean_how_to_manage') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="orders-management-tab" data-toggle="tab" href="#orders-management"
                                role="tab" aria-controls="orders-management" aria-selected="false" data-title="{{ __('help.Orders_Management') }}">
                                <b>{{ __('help.Orders_Management') }}</b>
                                <p>{{ __('help.lean_how_to_manage') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="eShop-lease-tab" data-toggle="tab" href="#eShop-lease" role="tab"
                                aria-controls="eShop-lease" aria-selected="false" data-title="{{ __('help.eShop_lease') }}">
                                <b>{{ __('help.eShop_lease') }}</b>
                                <p>{{ __('help.lean_how_to_manage') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="staff-management-tab" data-toggle="tab" href="#staff-management"
                                role="tab" aria-controls="staff-management" aria-selected="false" data-title="{{ __('help.staff_management') }}">
                                <b>{{ __('help.staff_management') }}</b>
                                <p>{{ __('help.lean_how_to_manage') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" role="tab"
                                aria-controls="billing" aria-selected="false" data-title="{{ __('help.Billing') }}">
                                <b>{{ __('help.Billing') }}</b>
                                <p>{{ __('help.lean_how_to_manage') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="support-ticket-tab" data-toggle="tab" href="#support-ticket"
                                role="tab" aria-controls="support-ticket" aria-selected="false" data-title="{{ __('help.Support_ticket') }}">
                                <b>{{ __('help.Support_ticket') }}</b>
                                <p>{{ __('help.lean_how_to_manage') }}</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false" data-title="{{ __('help.e_shop_profile') }}">
                                <b>{{ __('help.e_shop_profile') }}</b>
                                <p>{{ __('help.lean_how_to_manage') }}</p>
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="tab-content-wrapper">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="General-Q" role="tabpanel" aria-labelledby="General-Q-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.General_question') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question23') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer23') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question24') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer24') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="getting" role="tabpanel" aria-labelledby="getting-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Getting_Started') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question1') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer1') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question2') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer2') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="personal-information" role="tabpanel"
                            aria-labelledby="personal-information-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.product-management') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question3') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer3') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question4') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer4') }}</span>
                                            </p>
                                            <span class="registration-title"><b>{{ __('help.Question5') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer5') }}</span>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="inventory-management" role="tabpanel"
                            aria-labelledby="inventory-management-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.inventory_management') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question6') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer6') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question7') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer7') }}</span>
                                            </p>
                                            <span class="registration-title"><b>{{ __('help.Question8') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer8') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="orders-management" role="tabpanel"
                            aria-labelledby="orders-management-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.order_management') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question9') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer9') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="eShop-lease" role="tabpanel"
                            aria-labelledby="eShop-lease-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.eShop_lease_management') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question10') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer10') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question11') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer11') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question12') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer12') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question13') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer13') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question14') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer14') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="staff-management" role="tabpanel"
                            aria-labelledby="staff-management-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.staff_management') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question15') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer15') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question16') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer16') }}</span>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="billing" role="tabpanel"
                        aria-labelledby="billing-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Billing') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question17') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer17') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question18') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer18') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="staff-management" role="tabpanel"
                            aria-labelledby="staff-management-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.staff_management') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question15') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer15') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question16') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer16') }}</span>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="support-ticket" role="tabpanel"
                        aria-labelledby="support-ticket-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <div class="content">
                                        <h5 class="title-content"><b>{{ __('help.support-ticket') }}</b></h5>
                                        <span class="registration-title"><b>{{ __('help.Question17') }}</b></span>
                                        <p class="registration-form-title">
                                            <span class="registration-content">{{ __('help.Answer17') }}</span>
                                        </p>
                                        <br>
                                        <span class="registration-title"><b>{{ __('help.Question18') }}</b></span>
                                        <p class="registration-form-title">
                                            <span class="registration-content">{{ __('help.Answer18') }}</span>
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="staff-management" role="tabpanel"
                            aria-labelledby="staff-management-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.staff_management') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Question21') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer21') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Question22') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Answer22') }}</span>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to update the breadcrumb
            function updateBreadcrumb(title) {
                document.getElementById('current-page').textContent = title;
            }

            // Initial breadcrumb update with the active tab's title
            const activeTab = document.querySelector('.nav-link.active');
            if (activeTab) {
                updateBreadcrumb(activeTab.getAttribute('data-title'));
            }

            // Add event listeners to all nav-links
            document.querySelectorAll('.nav-link').forEach(function (tab) {
                tab.addEventListener('click', function () {
                    // Update the breadcrumb with the clicked tab's title
                    updateBreadcrumb(tab.getAttribute('data-title'));
                });
            });
        });
    </script>
@endsection
