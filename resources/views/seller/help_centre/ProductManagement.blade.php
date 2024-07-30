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
                            <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon"
                                style="width: 17px; height: 17px;">
                        </span>
                        <li class="breadcrumb-item"><a href="/product-management">{{ __('help.product_management') }}</a>
                        </li>
                        <span class="arrow-icon">
                            <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon"
                                style="width: 17px; height: 17px;">
                        </span>
                        <li class="breadcrumb-item active" aria-current="page" id="current-page">
                            {{ __('help.Adding_updating') }}</li>
                    </ol>
                </nav>
                <h1 class="h1"><b>{{ __('help.product_management') }}</b></h1>
            </div>
            <div class="container-flex">
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs help-centre" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="account-and-registration-tab" data-toggle="tab"
                                href="#account-and-registration" role="tab" aria-controls="account-and-registration"
                                aria-selected="true" data-title="{{ __('help.Adding_updating') }}">
                                <b>{{ __('help.Adding_updating') }}</b>
                                <p>{{ __('help.Learn_how_to_efficiently') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="initial-setup-tab" data-toggle="tab" href="#initial-setup"
                                role="tab" aria-controls="initial-setup" aria-selected="false"
                                data-title="{{ __('help.Using_the_Mawad_Catalogue') }}">
                                <b>{{ __('help.Using_the_Mawad_Catalogue') }}</b>
                                <p>{{ __('help.Navigate_and_utilize') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab"
                                aria-controls="review" aria-selected="false" data-title="{{ __('help.Product_Review') }}">
                                <b>{{ __('help.Product_Review') }}</b>
                                <p>{{ __('help.Stay_tuned_for') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval"
                                role="tab" aria-controls="approval" aria-selected="false"
                                data-title="{{ __('help.Product_approval') }}">
                                <b>{{ __('help.Product_approval') }}</b>
                                <p>{{ __('help.Understand_the_steps') }}</p>
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
                                            <h5 class="title-content"><b>{{ __('help.Adding_updating') }}</b></h5>
                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Access_the_Product_Management') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.From_the_Vendor_Dashboard') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Select_Products_to view') }}</span>

                                            </p>

                                            <span
                                                class="registration-title"><b>{{ __('help.Adding_a_New_Product') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Add_new_product') }}</span>
                                            </p>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-title">{{ __('help.Product_information') }}</span>
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Product_Name') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Product_Name_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Unit_of_Sale') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Unit_of_Sale_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Product_Short_Description') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Product_Short_Description_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Country_of_Origin') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Country_of_Origin_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Manufacturer') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Manufacturer_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Stock_Visibility') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Stock_Visibility_content') }}</span>
                                                </p>



                                                <span class="registration-title">{{ __('help.Product_images') }}</span>
                                                <p class="registration-form-title">
                                                    <span class="registration-content">{{ __('help.Upload_up') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Images_must_be') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Optionally') }}</span>

                                                </p>
                                                <span class="registration-title">{{ __('help.Product_Videos') }}</span>
                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-content">{{ __('help.Add_videos_content') }}</span>
                                                </p>
                                                <span
                                                    class="registration-title">{{ __('help.Product_Documentation') }}</span>
                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-content">{{ __('help.Documentation_content') }}</span>
                                                </p>
                                                <span
                                                    class="registration-title">{{ __('help.Pricing_Configuration') }}</span>
                                                <p class="registration-form-title">
                                                    <span class="registration-content">
                                                        {{ __('help.Pricing_Configuration_content') }}
                                                        <br>
                                                        {{ __('help.Pricing_Configuration_content_simple') }}


                                                    </span>
                                                </p>

                                                <span
                                                    class="registration-title">{{ __('help.shipping_Configuration') }}</span>
                                                <p class="registration-form-title">
                                                    <span class="registration-content">
                                                        {{ __('help.shipping_Configuration_content') }}
                                                    </span>
                                                </p>



                                                <span class="registration-title">{{ __('help.product_category') }}</span>
                                                <p class="registration-form-title">
                                                    <span class="registration-content">
                                                        {{ __('help.product_category_content') }}
                                                    </span>
                                                </p>
                                                <span
                                                    class="registration-title">{{ __('help.General_Attributes') }}</span>
                                                <p class="registration-form-title">
                                                    <span class="registration-content">
                                                        {{ __('help.Define_additional') }}
                                                    </span>
                                                </p>

                                                <span
                                                    class="registration-title">{{ __('help.Product_Description') }}</span>
                                                <p class="registration-form-title">
                                                    <span class="registration-content">
                                                        {{ __('help.detailed_description') }}
                                                    </span>
                                                </p>

                                                <span class="registration-title">{{ __('help.SEO_Meta') }}</span>
                                                <p class="registration-form-title">
                                                    <span class="registration-content">
                                                        {{ __('help.Optimize_the_product') }}
                                                    </span>
                                                </p>
                                                </p>
                                            </div>
                                            </p>
                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Updating_an_Existing') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.product_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.product_content2') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.product_content3') }}</span>
                                            </p>

                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Deleting_a_Product') }}</b></span>
                                            <p class="registration-form-title">

                                                <span class="registration-content">{{ __('help.Product1') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.Product2') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.Product3') }}</span>
                                            </p>

                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Viewing_a_Product') }}</b></span>
                                            <p class="registration-form-title">

                                                <span class="registration-content">{{ __('help.Product4') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.Product5') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.Product6') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="initial-setup" role="tabpanel"
                            aria-labelledby="initial-setup">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Using_the_Mawad_Catalogue') }}</b></h5>
                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Initiate') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.Initiate_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Initiate_content2') }}</span>

                                            </p>
                                            <br>
                                            <span
                                                class="registration-title"><b>{{ __('help.Choose') }}</b></span>
                                                        <br>
                                            <div class="content-personal-info">


                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Option1') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Option1_content') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Option1_content1') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Option2') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Option2_content') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Option2_content1') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Option3') }}</span>

                                                    <span
                                                        class="registration-content">{{ __('help.Option3_content') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Option3_content1') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Option3_content2') }}</span>
                                                </p>

                                            </div>

                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Manual') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.Manual_content1') }}</span>


                                            </p>
                                            <br>

                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Bulk') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.Bulk_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Bulk_content2') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Bulk_content3') }}</span>
                                            </p>
                                            <br>

                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.using') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.using_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.using_content2') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.using_content3') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show " id="review" role="tabpanel" aria-labelledby="review-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Product_Review') }}</b></h5>
                                            <p>
                                                <span class="registration-title"><b>{{ __('help.Guidelines') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.Guidelines_content') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show " id="approval" role="tabpanel" aria-labelledby="approval-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.workflow') }}</b></h5>
                                            <p>
                                                <span class="registration-title"><b>{{ __('help.Pending_app') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.pending1') }}</span>
                                                    <br>
                                                    <span
                                                    class="registration-content">{{ __('help.pending2') }}</span>

                                            </p>
                                            <br>
                                            <p>
                                                <span class="registration-title"><b>{{ __('help.Under') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.Under_content1') }}</span>
                                                    <br>
                                                    <span
                                                    class="registration-content">{{ __('help.Under_content2') }}</span>
                                                    <br>
                                                    <span
                                                    class="registration-content">{{ __('help.Under_content3') }}</span>


                                            </p>
                                            <br>
                                            <p>
                                                <span class="registration-title"><b>{{ __('help.Revision_title') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.Revision_content1') }}</span>
                                                    <br>
                                                    <span
                                                    class="registration-content">{{ __('help.Revision_content2') }}</span>



                                            </p>
                                            <br>
                                            <p>
                                                <span class="registration-title"><b>{{ __('help.Rejected_title') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.Rejected_content1') }}</span>
                                                    <br>
                                                    <span
                                                    class="registration-content">{{ __('help.Rejected_content2') }}</span>



                                            </p>
                                            <br>
                                            <p>
                                                <span class="registration-title"><b>{{ __('help.Approved_title') }}</b></span>
                                            <p class="registration-form-title">

                                                <span
                                                    class="registration-content">{{ __('help.Approved_content1') }}</span>
                                                    <br>
                                                    <span
                                                    class="registration-content">{{ __('help.Approved_content2') }}</span>



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
        document.addEventListener('DOMContentLoaded', function() {
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
            document.querySelectorAll('.nav-link').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    // Update the breadcrumb with the clicked tab's title
                    updateBreadcrumb(tab.getAttribute('data-title'));
                });
            });
        });
    </script>
@endsection
