@extends('seller.layouts.app')
@php
    use Carbon\Carbon;
@endphp

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
        /* Yellow shadow */
        transition: border-color 0.3s ease, box-shadow 0.3s ease !important;
        /* Smooth transition */
    }

    .color-modified-file {
        color: #e8c068e8
    }

    .highlighted-tab {
        border-color: red !important;
    }

    .btn-primary {
        background-color: #a2b8c6 !important;
        /* Use the global variable */
        border-color: #a2b8c6 !important;
    }

    .swal2-confirm {
        background-color: var(--success) !important;
        /* Use the global variable */
        border-color: none !important;
    }

    #social-icon-two {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        /* Add any other styles you need */
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
        text-align: center !important;
        border-top-left-radius: 1rem !important;
        border-top-right-radius: 1rem !important;
        border-bottom-left-radius: 1rem !important;
        border-bottom-right-radius: 1rem !important;
        width: 200px;
    }

    .file-condition {
        font-size: 12px;
        font-weight: 450;
        margin-top: 3px;
        display: block;
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
</style>

@section('panel_content')
    <div class="aiz-titlebar mt-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6 adjust-card">
                <h1 class="h1"><b>{{ __('profile.mawed_vendor_help_center') }}</b></h1>
            </div>
            <div class="col-md-12 mt-2">
                <h1 class="h1"><b>{{ __('profile.All_help_topics') }}</b></h1>
            </div>
            <div class="container-flex">
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs help-centre" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="account-and-registration-tab" data-toggle="tab"
                                href="#account-and-registration" role="tab" aria-controls="account-and-registration"
                                aria-selected="true">{{ __('sidenav.Account_and_Registration') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="product-management-tab" data-toggle="tab"
                                href="#product-management" role="tab" aria-controls="product-management"
                                aria-selected="false">{{ __('sidenav.product-management') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="inventory-management-tab" data-toggle="tab" href="#inventory-management"
                                role="tab" aria-controls="inventory-management"
                                aria-selected="false">{{ __('sidenav.Inventory Management') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="orders-management-tab" data-toggle="tab" href="#orders-management"
                                role="tab" aria-controls="orders-management"
                                aria-selected="false">{{ __('sidenav.Orders Management') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="eShop-lease-tab" data-toggle="tab" href="#eShop-lease"
                                role="tab" aria-controls="eShop-lease"
                                aria-selected="false">{{ __('sidenav.eShop_lease') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="staff-management-tab" data-toggle="tab" href="#staff-management"
                                role="tab" aria-controls="staff-management"
                                aria-selected="false">{{ __('sidenav.staff-management') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" role="tab"
                                aria-controls="billing" aria-selected="false">{{ __('sidenav.Billing') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="support-ticket-tab" data-toggle="tab" href="#support-ticket"
                                role="tab" aria-controls="support-ticket"
                                aria-selected="false">{{ __('sidenav.Support_ticket') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">{{ __('sidenav.e_shop_profile') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="faqs-tab" data-toggle="tab" href="#faqs" role="tab"
                                aria-controls="faqs" aria-selected="false">{{ __('sidenav.FAQs') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content-wrapper">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="account-and-registration" role="tabpanel"
                            aria-labelledby="account-and-registration-tab">
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-6 adjust-card">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    {{ __('help.Account_creating') }}</h5>
                                                <a
                                                    href="{{ route('seller.support_ticket.index') }}">{{ __('help.start_setup_up') }}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 adjust-card">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ __('help.initiale-setup') }}</h5>
                                                <a
                                                    href="{{ route('seller.support_ticket.index') }}">{{ __('help.Set_up_your_profile') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="product-management" role="tabpanel" aria-labelledby="product-management-tab">
                            <div class="row">
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                {{ __('help.Adding_updating') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Learn_how_to_efficiently') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Using_the_Mawad_Catalogue') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Navigate_and_utilize') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Product_Review') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Stay_tuned_for') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Product_approval') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Understand_the_steps') }}</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="inventory-management" role="tabpanel"
                            aria-labelledby="inventory-management-tab">
                                <div class="row">
                                        <div class="col-md-6 adjust-card">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ __('help.Adding_and_removing') }}</h5>
                                                    <a href="{{ route('seller.stocks.index') }}"
                                                        class="card-link">{{ __('help.Manage_your_inventory') }}</a>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="col-md-6 adjust-card">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ __('help.Managing_Vendor_Warehouses') }}</h5>
                                                <a href="{{ route('seller.warehouses.index') }}"
                                                    class="card-link">{{ __('help.Organize__and_control') }}</a>
                                            </div>
                                        </div>
                                    </div>

                                        <div class="col-md-6 adjust-card">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ __('help.Viewing_Stock_Operation') }}</h5>
                                                    <a href="{{ route('seller.stock.operation.report') }}"
                                                        class="card-link">{{ __('help.Access_detailed') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 adjust-card">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ __('help.Stock_visibility_and_notifications') }}</h5>
                                                    <a href="{{ route('seller.stock.operation.report') }}"
                                                        class="card-link">{{ __('help.Ensure_you_are_aware') }}</a>
                                                </div>
                                            </div>
                                        </div>

                                </div>

                        </div>
                        <div class="tab-pane fade" id="orders-management" role="tabpanel"
                            aria-labelledby="orders-management-tab">
                            @can('seller_view_all_orders')
                                <div class="card-deck">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('sidenav.orders_overview_management') }}</h5>
                                            <a href="{{ route('seller.orders.index') }}"
                                                class="card-link">{{ __('sidenav.Orders Management') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                        <div class="tab-pane fade" id="eShop-lease" role="tabpanel"
                            aria-labelledby="eShop-lease-tab">
                            <div class="row">
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                {{ __('help.Understanding-eshop') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Explore') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.lease_options') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Review_different_leasing') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.select_and_managing') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Select_and_Managing_Lease_Packages') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.lease_Billing') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Understand_the_billing') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Refunds') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Learn_about_the_refund') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.staff_Rolles') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Manage_your') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="staff-management" role="tabpanel"
                            aria-labelledby="staff-management-tab">
                            <div class="row">
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                {{ __('help.Adding-editing') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Assigning-roles') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.roles-assignment') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.understand-the-limits') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.select_and_managing') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Select_and_Managing_Lease_Packages') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.notifications_and_approvals') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Understand_the_billing') }}</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
                            <div class="row">
                                    <div class="col-md-6 adjust-card">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ __('sidenav.eShop-lease') }}</h5>
                                                <a
                                                    href="{{ route('seller.lease.index') }}">{{ __('help.Manage-e-Shop-lease') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 adjust-card">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ __('help.Sales-transictions') }}</h5>
                                                <a
                                                    href="{{ route('seller.sales.index') }}">{{ __('help.track') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 adjust-card">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ __('help.understang') }}</h5>
                                                <a
                                                    href="{{ route('seller.sales.index') }}">{{ __('help.Get-detailled') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 adjust-card">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ __('help.managing-additional') }}</h5>
                                                <a
                                                    href="{{ route('seller.sales.index') }}">{{ __('help.Add-and-manage') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 adjust-card">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ __('help.reviewing') }}</h5>
                                                <a
                                                    href="{{ route('seller.sales.index') }}">{{ __('help.lean-how-to-review') }}</a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="support-ticket" role="tabpanel"
                            aria-labelledby="support-ticket-tab">
                            <div class="row">
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Creating_and_managing_support_tickets') }}
                                            </h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Submit_managing_support_tickets_to_get_help') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Example_of_support_ticket_workflow') }}
                                            </h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Example_of_support_ticket_workflow') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Notification_and_communication') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Set_up_Notifications_and_communication_to_stay') }}</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                {{ __('help.Managing_Personal_and_Business_Information') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Upadate_and_maintain') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Approval_Process') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Understand_the_approval') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="faqs" role="tabpanel" aria-labelledby="faqs-tab">
                            <div class="row">
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                {{ __('help.General_Questions') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Find_ansewrs_to_general') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Getting_Started') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Find_ansewrs_to_guide') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.product_management') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.lean_how_to_manage') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.inventory_management') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.explore_best_practises') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.order_management') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.understand-how-to-handle') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.eShop_lease_management') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.understand-how-to-handle') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.staff_management') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.organize-and-manage') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Billing') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Get-information') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.Support_ticket') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.manage-your-eshop') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 adjust-card">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('help.eShop-profile') }}</h5>
                                            <a
                                                href="{{ route('seller.support_ticket.index') }}">{{ __('help.Manage-eshop') }}</a>
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
@endsection
