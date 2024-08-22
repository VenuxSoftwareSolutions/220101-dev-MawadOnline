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
        background-color: #2e294e !important;
        border-color: ##2e294e !important;
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
        padding-top: 10px;
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

    .text-nav {
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
                        <li class="breadcrumb-item"><a href="/support-ticket">{{ __('Support Ticket') }}</a></li>
                        <span class="arrow-icon">
                            <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon" style="width: 17px; height: 17px;">
                        </span>
                        <li class="breadcrumb-item active" aria-current="page" id="current-page">{{ __('help.Creating_and_managing') }}</li>
                    </ol>
                </nav>
                <h1 class="h1"><b>{{ __('help.Overview_shop') }}</b></h1>
            </div>
            <div class="container-flex">
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs help-centre" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="account-and-registration-tab" data-toggle="tab"
                                href="#account-and-registration" role="tab" aria-controls="account-and-registration"
                                aria-selected="true" data-title="{{ __('help.Understanding_eShop') }}">
                                <b>{{ __('help.Understanding_eShop') }}</b>
                                <p>{{ __('help.eShop-content') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="initial-lease-tab" data-toggle="tab" href="#initial-lease"
                                role="tab" aria-controls="initial-lease" aria-selected="false" data-title="{{ __('help.Lease_option_title') }}">
                                <b>{{ __('help.Lease_option_title') }}</b>
                                <p>{{ __('help.Lease_card_content') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="initial-manage-tab" data-toggle="tab" href="#initial-manage"
                                role="tab" aria-controls="initial-manage" aria-selected="false" data-title="{{ __('help.Understanding_eShop_title') }}">
                                <b>{{ __('help.Understanding_eShop_title') }}</b>
                                <p>{{ __('help.overview_content') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="lease-billing-tab" data-toggle="tab" href="#lease-billing"
                                role="tab" aria-controls="lease-billing" aria-selected="true" data-title="{{ __('help.lease_billing_title') }}">
                                <b>{{ __('help.lease_billing_title') }}</b>
                                <p>{{ __('help.select_and_manage_lease_content') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="Refunds-tab" data-toggle="tab" href="#Refunds" role="tab"
                                aria-controls="Refunds" aria-selected="true" data-title="{{ __('help.refunds_title') }}">
                                <b>{{ __('help.refunds_title') }}</b>
                                <p>{{ __('help.refunds_content') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="staff-roles-tab" data-toggle="tab" href="#staff-roles" role="tab"
                                aria-controls="staff-roles" aria-selected="true" data-title="{{ __('help.staff_roles') }}">
                                <b>{{ __('help.staff_roles') }}</b>
                                <p>{{ __('help.lease_billing_content') }}</p>
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="tab-content-wrapper">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="account-and-registration" role="tabpanel"
                            aria-labelledby="account-and-registration-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Understanding_eShop') }}</b></h5>
                                            <p>
                                                {{-- <span class="registration-title-big"><b>{{ __('help.Overview_of_Order_management') }}</b></span> --}}
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.eShop-content') }}</span>
                                            </p>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="initial-lease" role="tabpanel" aria-labelledby="initial-lease-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Lease_option_title') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Standard_eShop_title') }}</b></h5>
                                            <p class="registration-form-title">
                                                {{-- <span class="registration-title">{{ __('help.Standard_eShop_title') }}</span> --}}
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Standard_eShop_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Standard_eShop_content2') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Standard_eShop_content3') }}</span>
                                            </p>

                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="initial-manage" role="tabpanel" aria-labelledby="initial-manage-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Understanding_eShop_title') }}</b></h5>


                                            <span class="registration-title"><b>{{ __('help.Lease_Management_title1') }}</b></h5>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.lease_content1') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Lease_Management_title2') }}</b></h5>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.lease_content2') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Lease_Management_title3') }}</b></h5>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Package_title1') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.Package_title2') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.Package_title3') }}</span>

                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.review_title') }}</b></h5>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.review_title1') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.review_title2') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.review_title3') }}</span>

                                            </p>

                                            <br>
                                            <span class="registration-title"><b>{{ __('help.payment_title') }}</b></h5>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.payment_title1') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.payment_title2') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.payment_title3') }}</span>

                                            </p>

                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Managing_Your_eShop') }}</b></h5>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.manage_shop_title1') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.manage_shop_title2') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.manage_shop_title3') }}</span>

                                            </p>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="lease-billing" role="tabpanel"
                            aria-labelledby="lease-billing-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.lease_billing_title') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.billing_title') }}</b></h5>
                                            <p class="registration-form-title">
                                                {{-- <span class="registration-title">{{ __('help.Standard_eShop_title') }}</span> --}}
                                                <span
                                                    class="registration-content">{{ __('help.billing_content1') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.billing_title_details') }}</b></h5>
                                            <p class="registration-form-title">
                                                {{-- <span class="registration-title">{{ __('help.Standard_eShop_title') }}</span> --}}
                                                <span
                                                    class="registration-content">{{ __('help.lease_billing_content1') }}</span>
                                                 <br>
                                                <span
                                                    class="registration-content">{{ __('help.lease_billing_content2') }}</span>
                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.make_payment_title') }}</b></h5>
                                            <p class="registration-form-title">
                                                {{-- <span class="registration-title">{{ __('help.Standard_eShop_title') }}</span> --}}
                                                <span
                                                    class="registration-content">{{ __('help.make_payment_content1') }}</span>
                                                 <br>
                                                <span
                                                    class="registration-content">{{ __('help.make_payment_content2') }}</span>
                                                    <br>
                                                <span
                                                    class="registration-content">{{ __('help.make_payment_content3') }}</span>
                                            </p>
                                            <br>

                                            <span class="registration-title"><b>{{ __('help.Lease_History') }}</b></h5>
                                            <p class="registration-form-title">
                                                {{-- <span class="registration-title">{{ __('help.Standard_eShop_title') }}</span> --}}
                                                <span
                                                    class="registration-content">{{ __('help.Lease_History_content1') }}</span>
                                                 <br>
                                                <span
                                                    class="registration-content">{{ __('help.Lease_History_content2') }}</span>

                                            </p>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="Refunds" role="tabpanel"
                            aria-labelledby="Refunds-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.refunds_title') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Refunds_title1') }}</b></h5>
                                            <p class="registration-form-title">
                                                {{-- <span class="registration-title">{{ __('help.Standard_eShop_title') }}</span> --}}
                                                <span
                                                    class="registration-content">{{ __('help.Refunds_content1') }}</span>
                                                    <br>
                                                    <span
                                                    class="registration-content">{{ __('help.Refunds_content2') }}</span>

                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.Refunds_title2') }}</b></h5>
                                            <p class="registration-form-title">
                                                {{-- <span class="registration-title">{{ __('help.Standard_eShop_title') }}</span> --}}
                                                <span
                                                    class="registration-content">{{ __('help.Refunds_content3') }}</span>
                                                 <br>
                                                <span
                                                    class="registration-content">{{ __('help.Refunds_content4') }}</span>
                                            </p>

                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="staff-roles" role="tabpanel"
                            aria-labelledby="staff-roles-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.staff_roles') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Free_Role_title') }}</b></h5>
                                            <p class="registration-form-title">
                                                {{-- <span class="registration-title">{{ __('help.Standard_eShop_title') }}</span> --}}
                                                <span
                                                    class="registration-content">{{ __('help.eshop_content1') }}</span>
                                                    <br>
                                                    <span
                                                    class="registration-content">{{ __('help.eshop_content2') }}</span>

                                            </p>
                                            <br>
                                            <span class="registration-title"><b>{{ __('help.The_standard_eShop') }}</b></h5>
                                            <p class="registration-form-title">
                                                {{-- <span class="registration-title">{{ __('help.Standard_eShop_title') }}</span> --}}
                                                <span
                                                    class="registration-content">{{ __('help.eshop_content3') }}</span>
                                                 <br>
                                                <span
                                                    class="registration-content">{{ __('help.eshop_content4') }}</span>
                                            </p>

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
