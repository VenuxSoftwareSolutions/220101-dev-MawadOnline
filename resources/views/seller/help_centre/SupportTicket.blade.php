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
                        <li class="breadcrumb-item"><a href="/support-ticket">{{ __('help.support-ticket') }}</a></li>
                        <span class="arrow-icon">
                            <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon" style="width: 17px; height: 17px;">
                        </span>
                        <li class="breadcrumb-item active" aria-current="page" id="current-page">{{ __('help.Creating_and_managing') }}</li>
                    </ol>
                </nav>
                {{-- <div class="breadcrumb-nav">
                    <a href="{{ route('seller.help-center.index') }}" class="text-nav">Vendor Help Center</a>
                    <span class="arrow-icon">
                        <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon" style="width: 25px; height: 25px;">
                    </span>
                    <a href="{{ route('seller.support_ticket.index') }}" class="text-nav">Support Tickets</a>
                    <span class="arrow-icon">
                        <img src="{{ asset('public/arrowIcon.png') }}" alt="arrow icon" style="width: 25px; height: 25px;">
                    </span>
                    <a href="#" class="text-nav" id="currentPageBreadcrumb">Current Page</a>
                </div> --}}
                <h1 class="h1"><b>{{ __('help.support-ticket') }}</b></h1>
            </div>
            <div class="container-flex">
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs help-centre" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="Creating_and_managing-tab" data-toggle="tab"
                                href="#Creating_and_managing" role="tab" aria-controls="Creating_and_managing"
                                aria-selected="true" data-title="{{ __('help.Creating_and_managing') }}">
                                <b>{{ __('help.Creating_and_managing') }}</b>
                                <p>{{ __('help.Creating-content') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="getting-tab" data-toggle="tab" href="#getting" role="tab"
                                aria-controls="getting" aria-selected="false" data-title="{{ __('help.Example_of_a_Support') }}">
                                <b>{{ __('help.Example_of_a_Support') }}</b>
                                <p>{{ __('help.Find_ansewrs_to_guide') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="notification-tab" data-toggle="tab" href="#notification"
                                role="tab" aria-controls="notification" aria-selected="false" data-title="{{ __('help.Notifications') }}">
                                <b>{{ __('help.Notifications') }}</b>
                                <p>{{ __('help.lean_how_to_manage') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content-wrapper">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Creating_and_managing" role="tabpanel"
                            aria-labelledby="Creating_and_managing-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Creating_and_managing') }}</b></h5>
                                            <span
                                                class="registration-title"><b>{{ __('help.Access_the_Support') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Access_the_Support_content') }}</span>
                                            </p>


                                            <span
                                                class="registration-title"><b>{{ __('help.Creating_a_New_Support') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Creating_a_New_Support_content') }}</span>
                                            </p>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-title">{{ __('help.Ticket_Information') }}</span>
                                                    <br>
                                                    {{-- <span class="registration-content">{{ __('help.Access_the_eShop_content') }}</span> --}}

                                                    <span class="registration-title">{{ __('help.Subject') }}</span><span
                                                        class="registration-content">{{ __('help.Subject_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Category') }}</span><span
                                                        class="registration-content">{{ __('help.Category_content') }}</span>
                                                    <br>
                                                    <span
                                                        class="registration-title">{{ __('help.Description') }}</span><span
                                                        class="registration-content">{{ __('help.Description_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Attachments') }}
                                                    </span>
                                                    <span class="registration-content">{{ __('help.Attachments_content') }}
                                                    </span>
                                                    <br><br>
                                                    <span class="registration-title">{{ __('help.Submit') }}</span>
                                                    <br>

                                                    <span
                                                        class="registration-content">{{ __('help.submit_content') }}</span>

                                                    <br>


                                                    {{-- <span
                                                        class="registration-content">{{ __('help.Viewing_content1') }}</span><span
                                                        class="registration-content">{{ __('help.Viewing_content2') }}</span>
                                                    <br> --}}
                                                </p>

                                            </div>
                                            <span class="registration-title"><b>{{ __('help.Viewing') }}</b></span>
                                            <p class="registration-form-title">
                                                <span class="registration-content">{{ __('help.Viewing_content1') }}</span>
                                                <br>
                                                <span class="registration-content">{{ __('help.Viewing_content2') }}</span>
                                                {{-- change't with the table --}}
                                                <span class="registration-content">{{ __('help.Viewing_content2') }}</span>
                                            </p>

                                            <span class="registration-title"><b>{{ __('help.Checking_Ticket') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-title">{{ __('help.Checking_Ticket_title') }}</span><span
                                                    class="registration-content">{{ __('help.Checking_Ticket_content1') }}</span>
                                                <br>

                                            </p>
                                            <div class="content-personal-info">
                                                <p class="registration-form-title">
                                                    <span
                                                        class="registration-title">{{ __('help.Status_Indicators') }}</span>
                                                    <br>
                                                    {{-- <span class="registration-content">{{ __('help.Access_the_eShop_content') }}</span> --}}
                                                    <span class="registration-title">{{ __('help.Open') }}</span><span
                                                        class="registration-content">{{ __('help.The_ticket') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Progress') }}</span><span
                                                        class="registration-content">{{ __('help.Progress_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Resolved') }}</span><span
                                                        class="registration-content">{{ __('help.Resolved_content') }}</span>
                                                    <br>
                                                    <span class="registration-title">{{ __('help.Closed') }}
                                                    </span>
                                                    <span class="registration-content">{{ __('help.Closed_content') }}
                                                    </span>
                                                </p>
                                            </div>
                                            <p class="registration-form-title">
                                                <span class="registration-title">{{ __('help.Responding') }}</span><span
                                                    class="registration-content">{{ __('help.Responding_content') }}</span>
                                                <br>
                                            </p>
                                            <span class="registration-title"><b>{{ __('help.Updating') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-title">{{ __('help.Add_Comments_title') }}</span><span
                                                    class="registration-content">{{ __('help.Add_Comments_content') }}</span>
                                                <br>
                                                <span class="registration-title">{{ __('help.upload_title') }}</span><span
                                                    class="registration-content">{{ __('help.upload_content') }}</span>
                                                <br>
                                                <span
                                                    class="registration-title">{{ __('help.Close_Ticket_title') }}</span><span
                                                    class="registration-content">{{ __('help.Close_Ticket_content') }}</span>

                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="tab-pane fade" id="getting" role="tabpanel"
                            aria-labelledby="getting-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <div class="content">
                                            <h5 class="title-content"><b>{{ __('help.Example_of_a_Support') }}</b></h5>
                                            <span class="registration-title"><b>{{ __('help.Issue_Identification') }}</b></span>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Issue_Identification_content') }}</span>

                                            </p>
                                            <span
                                                class="registration-title"><b>{{ __('help.Creating') }}</b></span>


                                            <br>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.creating_content') }}</span>

                                            </p>

                                            <span
                                                class="registration-title"><b>{{ __('help.Ticket') }}</b></span>


                                            <br>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Ticket_content') }}</span>

                                            </p>
                                            <span
                                                class="registration-title"><b>{{ __('help.vendor') }}</b></span>


                                            <br>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.vendor_content') }}</span>

                                            </p>

                                            <span
                                                class="registration-title"><b>{{ __('help.Ticket_Closure') }}</b></span>


                                            <br>
                                            <p class="registration-form-title">
                                                <span
                                                    class="registration-content">{{ __('help.Ticket_Closure_content') }}</span>

                                            </p>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="notification" role="tabpanel"
                        aria-labelledby="notification-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <div class="content">
                                        <h5 class="title-content"><b>{{ __('help.Notifications_section') }}</b></h5>
                                        <span class="registration-title"><b>{{ __('help.Ticket_Creation') }}</b></span>
                                        <p class="registration-form-title">
                                            <span
                                                class="registration-content">{{ __('help.Ticket_content1') }}</span>

                                        </p>
                                        <span
                                            class="registration-title"><b>{{ __('help.Ticket_content2') }}</b></span>
                                        <br>
                                        <p class="registration-form-title">
                                            <span
                                                class="registration-content">{{ __('help.status_updates') }}</span>

                                        </p>

                                        <span
                                            class="registration-title"><b>{{ __('help.status_updates_content') }}</b></span>


                                        <br>


                                        <p class="registration-form-title">
                                            <span
                                                class="registration-content">{{ __('help.Ticket_content') }}</span>

                                        </p>
                                        <span
                                            class="registration-title"><b>{{ __('help.Support_Team') }}</b></span>


                                        <br>
                                        <p class="registration-form-title">
                                            <span
                                                class="registration-content">{{ __('help.Support_Team_content') }}</span>

                                        </p>

                                        <span
                                            class="registration-title"><b>{{ __('help.Ticket_Closure') }}</b></span>


                                        <br>
                                        <p class="registration-form-title">
                                            <span
                                                class="registration-content">{{ __('help.Ticket_Closure_content') }}</span>

                                        </p>

                                        <h5 class="title-content"><b>{{ __('help.Communication') }}</b></h5>
                                        <span class="registration-title"><b>{{ __('help.In-Ticket') }}</b></span>
                                        <p class="registration-form-title">
                                            <span
                                                class="registration-content">{{ __('help.Support_Team_content1') }}</span>
                                                <br>
                                                <span
                                                class="registration-content">{{ __('help.Support_Team_content2') }}</span>

                                        </p>

                                        <span class="registration-title"><b>{{ __('help.Attachments_se') }}</b></span>
                                        <p class="registration-form-title">
                                            <span
                                                class="registration-content">{{ __('help.Attachments_se_content') }}</span>


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
