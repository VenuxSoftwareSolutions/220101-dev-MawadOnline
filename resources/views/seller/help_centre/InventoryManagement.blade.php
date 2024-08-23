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
                        <li class="breadcrumb-item"><a href="/support-ticket">{{ __('help.inventory_management') }}</a></li>
                        <span class="arrow-icon">
                            <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon"
                                style="width: 17px; height: 17px;">
                        </span>
                        <li class="breadcrumb-item active" aria-current="page" id="current-page">
                            {{ __('help.Adding_and_removing') }}</li>
                    </ol>
                </nav>
                <h1 class="h1"><b>{{ __('help.inventory_management') }}</b></h1>
            </div>
            <div class="container-flex">
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs help-centre" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="adding-tab" data-toggle="tab" href="#adding" role="tab"
                                aria-controls="account-and-registration" aria-selected="true"
                                data-title="{{ __('help.Adding_and_removing') }}">
                                <b>{{ __('help.Adding_and_removing') }}</b>
                                <p>{{ __('help.Learn_how_to_efficiently') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="Managing_Vendor-tab" data-toggle="tab" href="#Managing_Vendor"
                                role="tab" aria-controls="Managing_Vendor" aria-selected="false"
                                data-title="{{ __('help.Using_the_Mawad_Catalogue') }}">
                                <b>{{ __('help.Managing_Vendor_Warehouses') }}</b>
                                <p>{{ __('help.Navigate_and_utilize') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="viewing-tab" data-toggle="tab" href="#viewing" role="tab"
                                aria-controls="viewing" aria-selected="false" data-title="{{ __('help.Viewing_Stock') }}">
                                <b>{{ __('help.Viewing_Stock') }}</b>
                                <p>{{ __('help.Stay_tuned_for') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="stock-initiale-tab" data-toggle="tab" href="#stock-initiale"
                                role="tab" aria-controls="stock-initiale" aria-selected="false"
                                data-title="{{ __('help.Stock') }}">
                                <b>{{ __('help.Stock') }}</b>
                                <p>{{ __('help.Understand_the_steps') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content-wrapper">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="adding" role="tabpanel" aria-labelledby="adding-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Adding_removing_stock') }}</b></h5>
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



                                            <span class="registration-title"><b>{{ __('help.Adding_stock') }}</b></span>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.select_product') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.product_content') }}</span>
                                                </p>
                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-title">{{ __('help.select_warhouse') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.warehouse_content') }}</span>
                                                </p>
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Enter_Quantity') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Specify_the_quantity') }}</span>
                                                </p>

                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Comments') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Add_any_relevant') }}</span>
                                                </p>

                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Save') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Click_Save') }}</span>
                                                </p>



                                                </p>

                                            </div>
                                            <span class="registration-title"><b>{{ __('help.removing_stock') }}</b></span>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-title">{{ __('help.select_product') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.product_content') }}</span>
                                                </p>


                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-title">{{ __('help.select_warhouse') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.warehouse_content_removed') }}</span>
                                                </p>


                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-title">{{ __('help.Enter_Quantity') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Specify_the_quantity') }}</span>
                                                </p>

                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Comments') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-form-title">{{ __('help.Add_Comments_removed') }}</span>
                                                </p>

                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Save') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Click_Save') }}</span>
                                                </p>
                                                </p>

                                            </div>

                                            <span class="registration-title"><b>{{ __('help.updating_stock') }}</b></span>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.current_stock') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.current_stock_contents') }}</span>
                                                </p>
                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Add_remove') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Add_remove_contents') }}</span>
                                                </p>


                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-title">{{ __('help.Enter_Quantity') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Specify_the_quantity') }}</span>
                                                </p>

                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Comments') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Add_Comments') }}</span>
                                                </p>

                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.New_stock') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.view_updating') }}</span>
                                                </p>

                                                <p class="registration-form-title">
                                                    <span class="registration-title">{{ __('help.Save') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Click_Save') }}</span>
                                                </p>
                                                </p>
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Managing_Vendor" role="tabpanel"
                            aria-labelledby="Managing_Vendor-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Using_the_Mawad_Catalogue') }}</b></h5>
                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Access_the_Warehouse') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Access_the_Warehouse_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Access_the_Warehouse_content2') }}</span>
                                            </p>
                                            <span
                                                class="registration-title"><b>{{ __('help.Adding_a New_Warehouse') }}</b></span>

                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Adding_a New_Warehouse_content1') }}</span>

                                            </p>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-title">{{ __('help.Warehouse_Information') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Warehouse_Name') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Warehouse_content1') }}</span>

                                                    <br>
                                                    <span class="registration-title">{{ __('help.State/Emirate') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.State/Emirate_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Area') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Area_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Street') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Street_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Building') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Building_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Unit/Office') }}</span>
                                                    <span
                                                        class="registration-content">{{ __('help.Unit/Office_content') }}</span>
                                                    <br> <br>

                                                    <span class="registration-title">{{ __('help.Save_werhouse') }}</span>
                                                    <br>

                                                    <span
                                                        class="registration-content">{{ __('help.Save_werhouse_content') }}</span>
                                                </p>
                                            </div>

                                            <span class="registration-title"><b>{{ __('help.Editing') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-title">{{ __('help.Editing_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Editing_content2') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Editing_content3') }}</span>
                                            </p>

                                            <span class="registration-title"><b>{{ __('help.Deleting') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-title">{{ __('help.Deleting_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Deleting_content2') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Deleting_content3') }}</span>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="viewing" role="tabpanel" aria-labelledby="viewing-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Viewing_Stock') }}</b></h5>
                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Access') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Access_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Access_content2') }}</span>
                                            </p>

                                            <span
                                                class="registration-title"><b>{{ __('help.Search') }}</b></span>


                                            <div class="content-personal-info">

                                                <p class="registration-form-title">

                                                    <span class="registration-title">{{ __('help.Date_Range') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Date_Range_content1') }}</span>

                                                    <br>
                                                    <span class="registration-title">{{ __('help.Product_Variants') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-content">{{ __('help.Product_Variants_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Warehouses') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Warehouses_content1') }}</span>
                                                    <br>
                                                    <span class="registration-content">{{ __('help.Warehouses_content2') }}</span>


                                                </p>
                                            </div>

                                            <span class="registration-title"><b>{{ __('help.Viewing_Transaction') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-title">{{ __('help.Viewing_Transaction_content1') }}</span>
                                                <br>
                                                <div class="content-personal-info">

                                                    <p class="registration-form-title">

                                                        <span class="registration-title">{{ __('help.Date/Time') }}</span>
                                                        <br>
                                                        <span
                                                            class="registration-content">{{ __('help.Date/Time_content1') }}</span>

                                                        <br>
                                                        <span class="registration-title">{{ __('help.Type_of_Operation') }}</span>
                                                        <br>
                                                        <span
                                                            class="registration-content">{{ __('help.Type_of_Operation_content') }}</span>
                                                        <br>
                                                        <span class="registration-title">{{ __('help.Product_Variant') }}</span>
                                                        <br>
                                                        <span class="registration-content">{{ __('help.Product_Variant_content') }}</span>
                                                        <br>
                                                        <span class="registration-title">{{ __('help.Warehouse') }}</span>
                                                        <br>
                                                        <span class="registration-content">{{ __('help.Warehouse_content') }}</span>
                                                        <br>
                                                        <span class="registration-title">{{ __('help.Quantity') }}</span>
                                                        <br>
                                                        <span class="registration-content">{{ __('help.Quantity_content') }}</span>
                                                        <br>
                                                        <span class="registration-title">{{ __('help.Transaction') }}</span>
                                                        <br>
                                                        <span class="registration-content">{{ __('help.Transaction_content') }}</span>
                                                        <br>
                                                        <span class="registration-title">{{ __('help.Quantity_After') }}</span>
                                                        <br>
                                                        <span class="registration-content">{{ __('help.Quantity_After_content') }}</span>
                                                        <br>
                                                        <span class="registration-title">{{ __('help.User') }}</span>
                                                        <br>
                                                        <span class="registration-content">{{ __('help.User_content') }}</span>
                                                        <br>
                                                        <span class="registration-title">{{ __('help.Comments_title') }}</span>
                                                        <br>
                                                        <span class="registration-content">{{ __('help.Comments_title_content') }}</span>
                                                        <br>
                                                        <span class="registration-title">{{ __('help.Sales_Order') }}</span>
                                                        <br>
                                                        <span class="registration-content">{{ __('help.Sales_Order_content') }}</span>
                                                    </p>

                                                </div>

                                                <span class="registration-title">{{ __('help.Viewing_Transaction_content2') }}</span>
                                            </p>

                                            <span class="registration-title"><b>{{ __('help.Exporting') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-title">{{ __('help.Exporting_content') }}</span>

                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="stock-initiale" role="tabpanel" aria-labelledby="stock-initiale-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">

                                            <h5 class="title-content"><b>{{ __('help.Configuring') }}</b></h5>
                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Set') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Set_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Set_content2') }}</span>7
                                                    <br>
                                                <span
                                                    class="registration-content">{{ __('help.Set_content3') }}</span>
                                            </p>

                                            <h5 class="title-content"><b>{{ __('help.Setting_Up') }}</b></h5>


                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Access_Product') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Access_Product_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Access_Product_content1') }}</span>7

                                            </p>

                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Configure_Up') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Configure_Up_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Configure_Up_content2') }}</span>
                                                    <br>
                                                <span
                                                    class="registration-content">{{ __('help.Configure_Up_content3') }}</span>

                                            </p>
                                            <p>
                                                <span
                                                    class="registration-title"><b>{{ __('help.Receive') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Receive_content1') }}</span>
                                                <br>
                                                <span
                                                    class="registration-content">{{ __('help.Receive_content2') }}</span>

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
