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
                        <li class="breadcrumb-item"><a href="/eshopProfile">{{ __('help.eShop_Profile') }}</a></li>
                        <span class="arrow-icon">
                            <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon" style="width: 17px; height: 17px;">
                        </span>
                        <li class="breadcrumb-item active" aria-current="page" id="current-page">{{ __('help.Manage_eShop') }}</li>
                    </ol>

                </nav>
                <h1 class="h1"><b>{{ __('help.eShop_Profile') }}</b></h1>
            </div>
            <div class="container-flex">

                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs help-centre" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="eshop-profile-tab" data-toggle="tab" href="#eshop-profile"
                                role="tab" aria-controls="eshop-profile" aria-selected="true" data-title="{{ __('help.Manage_eShop') }}">
                                <b>{{ __('help.Manage_eShop') }}</b>
                                <p>{{ __('help.maintein-content') }}</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" id="personal-information-tab" data-toggle="tab" href="#personal-information"
                                role="tab" aria-controls="personal-information" aria-selected="false" data-title="{{ __('help.Approval_Process_title') }}">
                                <b>{{ __('help.Approval_Process_title') }}</b>
                                <p>{{ __('help.Lease_card_content') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content-wrapper">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="eshop-profile" role="tabpanel"
                            aria-labelledby="eshop-profile-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Managing_Personal') }}</b></h5>
                                            <span
                                                class="registration-title"><b>{{ __('help.Access_the_eShop') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Access_the_eShop_content') }}</span>
                                            </p>
                                            {{-- <br>
                                            <span class="registration-title"><b>{{ __('help.Navigating_the_eShop') }}</b></h5>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Navigating_the_eShop_content') }}</span>
                                            </p> --}}

                                            {{-- start personal info section --}}
                                            <span
                                                class="registration-title"><b>{{ __('help.Personal_Info_title') }}</b></span>

                                            <br>
                                            <span class="registration-title">{{ __('help.Updating_Personal') }}</span>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Fields') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.First_Name_title') }}</span><span
                                                        class="registration-content">{{ __('help.First_Name_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Last_Name_title') }}</span><span
                                                        class="registration-content">{{ __('help.Last_Name_content') }}</span>

                                                </p>
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.save') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.save_content') }}</span>


                                                </p>
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Note') }}</span><span
                                                        class="registration-content">{{ __('help.Note_content') }}</span>
                                                </p>
                                            </div>
                                            {{-- start business section --}}
                                            <span
                                                class="registration-title"><b>{{ __('help.business_Info_title') }}</b></span>
                                            <br>
                                            <span class="registration-title">{{ __('help.Updating_Business') }}</span>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Fields') }}</span>
                                                    <br>
                                                    {{-- <span class="registration-content">{{ __('help.Access_the_eShop_content') }}</span> --}}

                                                    <span
                                                        class="registration-title">{{ __('help.English_Trade') }}</span><span
                                                        class="registration-content">{{ __('help.English_Trade_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Arabic_Trade') }}</span><span
                                                        class="registration-content">{{ __('help.Arabic_Trade_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Trade_License') }}</span><span
                                                        class="registration-content">{{ __('help.Trade_License_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Address_Details') }}</span><span
                                                        class="registration-content">{{ __('help.Address_Details_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.VAT_Registration') }}</span><span
                                                        class="registration-content">{{ __('help.VAT_Registration_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Civil_Defence') }}</span><span
                                                        class="registration-content">{{ __('help.Civil_Defence_content') }}</span>



                                                </p>
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Save_as_Draft') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Save_as_Draft_content1') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Save_as_Draft_content2') }}</span>

                                                </p>
                                            </div>

                                            <span
                                                class="registration-title"><b>{{ __('help.Contact_Person_title') }}</b></span>
                                            <br>
                                            <span class="registration-title">{{ __('help.Updating_Contact') }}</span>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Fields') }}</span>
                                                    <br>
                                                    {{-- <span class="registration-content">{{ __('help.Access_the_eShop_content') }}</span> --}}

                                                    <span
                                                        class="registration-title">{{ __('help.First_Name_title') }}</span><span
                                                        class="registration-content">{{ __('help.First_Name_content1') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Last_Name_title') }}</span><span
                                                        class="registration-content">{{ __('help.Last_Name_content1') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Email_title') }}</span><span
                                                        class="registration-content">{{ __('help.Trade_License_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Address_Details') }}</span><span
                                                        class="registration-content">{{ __('help.Address_Details_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Mobile_title') }}</span><span
                                                        class="registration-content">{{ __('help.Mobile_content1') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Nationality_title') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Nationality_content1') }}
                                                    </span>
                                                    <span
                                                        class="registration-title">{{ __('help.Date_title') }}</span><span
                                                        class="registration-content">{{ __('help.Date_content1') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Emirate_title') }}</span><span
                                                        class="registration-content">{{ __('help.Emirate_content1') }}</span>

                                                </p>
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Save_as_Draft') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Save_as_Draft_content1') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Save_as_Draft_content2') }}</span>

                                                </p>
                                            </div>

                                            {{-- start payout section --}}
                                            <span
                                                class="registration-title"><b>{{ __('help.payout_info_title') }}</b></span>
                                            <br>
                                            <span class="registration-title">{{ __('help.Updating_Payout') }}</span>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Fields') }}</span>
                                                    <br>
                                                    {{-- <span class="registration-content">{{ __('help.Access_the_eShop_content') }}</span> --}}

                                                    <span
                                                        class="registration-title">{{ __('help.Bank_Name') }}</span><span
                                                        class="registration-content">{{ __('help.Bank_Name_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Account_Name') }}</span><span
                                                        class="registration-content">{{ __('help.Account_Name_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Account_Number') }}</span><span
                                                        class="registration-content">{{ __('help.Account_Number_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Address_Details') }}</span><span
                                                        class="registration-content">{{ __('help.Address_Details_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.IBAN') }}</span><span
                                                        class="registration-content">{{ __('help.IBAN_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Swift_Code') }}</span>
                                                    <span class="registration-content">{{ __('help.Swift_Code_content') }}
                                                    </span>
                                                    <span
                                                        class="registration-title">{{ __('help.IBAN_Certificate') }}</span><span
                                                        class="registration-content">{{ __('help.IBAN_Certificate_content') }}</span>
                                                    <br>


                                                </p>
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Save_as_Draft') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Save_as_Draft_content1') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Save_as_Draft_content2') }}</span>

                                                </p>
                                            </div>

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
                                            <h5 class="title-content"><b>{{ __('help.Account_creating') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Admin_Review ') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Admin_Review_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Admin_Review_content2') }}</span>
                                            </p>

                                            {{-- Status Notifications section --}}
                                            <span
                                                class="registration-title"><b>{{ __('help.Status_Notifications') }}</b></span>

                                            <br>
                                            {{-- <span class="registration-title">{{ __('help.Updating_Personal') }}</span> --}}
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">

                                                    <span class="registration-title">{{ __('help.Pending') }}</span><span
                                                        class="registration-content">{{ __('help.Pending_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Pending_app') }}</span><span
                                                        class="registration-content">{{ __('help.Pending_app_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.under') }}</span><span
                                                        class="registration-content">{{ __('help.under_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Revision') }}</span><span
                                                        class="registration-content">{{ __('help.Revision') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Rejected') }}</span><span
                                                        class="registration-content">{{ __('help.rejected_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Approved') }}</span><span
                                                        class="registration-content">{{ __('help.Approved_content') }}</span>
                                                    <br>

                                                </p>
                                            </div>
                                            <span class="registration-title"><b>{{ __('help.Distinctive') }}</b></span>
                                            <br>

                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Distinctive_content') }}</span>

                                            </p>

                                            <span
                                                class="registration-title"><b>{{ __('help.Admin_Approval') }}</b></span>
                                            <br>
                                            <span class="registration-content">{{ __('help.approve_content') }}</span>

                                            <div class="content-personal-info">
                                                <p class="registration-form-title">

                                                    <span
                                                        class="registration-title">{{ __('help.Approve_Changes') }}</span><span
                                                        class="registration-content">{{ __('help.Approve_Changes_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Reject_Changes') }}</span><span
                                                        class="registration-content">{{ __('help.Reject_Changes_content') }}</span>

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
