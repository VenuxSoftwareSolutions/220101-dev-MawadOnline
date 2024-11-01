@extends('seller.layouts.app')

@push('styles')
    <!-- Include any additional styles if needed -->
    <style>
        /* Card Styles for Tabs */
        .tab-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
            transition: background-color 0.2s ease;
            height: 100%;
        }
        .tab-card.active, .tab-card:hover {
            background-color: #e9ecef;
            border-color: #ced4da;
        }
        .tab-card-icon {
            font-size: 24px;
            color: #495057;
        }
        .tab-card-title {
            font-size: 18px;
            font-weight: 500;
            color: #333;
            margin-top: 10px;
        }
        .tab-card-description {
            font-size: 14px;
            color: #666;
        }
        .nav-tabs {
            border-bottom: none;
        }
        .nav-item {
            width: 50%;
            text-align: center;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table .toggle-switch {
            width: 50px;
        }
        .action-icon {
            color: #495057;
            cursor: pointer;
        }
        .action-icon:hover {
            color: #333;
        }
    </style>
@endpush

@section('panel_content')
<div class="container mt-5">
    <!-- Tabs for Discount Types with Card Styles -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="tab-card active" id="product-tab" data-bs-toggle="tab" data-bs-target="#product" role="tab">
                <div class="tab-card-icon"><i class="bi bi-box"></i></div>
                <div class="tab-card-title">Product</div>
                <div class="tab-card-description">Click here to offer a discount on a certain product from your inventory.</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="tab-card" id="category-tab" data-bs-toggle="tab" data-bs-target="#category" role="tab">
                <div class="tab-card-icon"><i class="bi bi-tag"></i></div>
                <div class="tab-card-title">Category</div>
                <div class="tab-card-description">Click here to offer a discount on a certain category from your inventory.</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="tab-card" id="ordersOverAmount-tab" data-bs-toggle="tab" data-bs-target="#ordersOverAmount" role="tab">
                <div class="tab-card-icon"><i class="bi bi-cart4"></i></div>
                <div class="tab-card-title">Orders over an Amount</div>
                <div class="tab-card-description">Click here to offer a discount on all orders above a certain amount.</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="tab-card" id="allOrders-tab" data-bs-toggle="tab" data-bs-target="#allOrders" role="tab">
                <div class="tab-card-icon"><i class="bi bi-basket"></i></div>
                <div class="tab-card-title">All Orders</div>
                <div class="tab-card-description">Click here to offer a discount on all the orders.</div>
            </div>
        </div>
    </div>

    <!-- Tabs for Discounts and Coupons -->
    <ul class="nav nav-tabs mb-3" id="discountCouponTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="discounts-tab" data-bs-toggle="tab" data-bs-target="#discounts" type="button" role="tab">Discounts</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="coupons-tab" data-bs-toggle="tab" data-bs-target="#coupons" type="button" role="tab">Coupons</button>
        </li>
    </ul>

    <!-- Content for Discounts and Coupons -->
    <div class="tab-content" id="discountCouponContent">
        <!-- Discounts Table -->
        <div class="tab-pane fade show active" id="discounts" role="tabpanel">
            <h5>Discounts</h5>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Category Name</th>
                        <th>Percent</th>
                        <th>Max Discount</th>
                        <th>Start Date</th>
                        <th>Expires on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="toggle-switch text-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="statusToggle1" checked>
                            </div>
                        </td>
                        <td>Ceiling Panels</td>
                        <td>20%</td>
                        <td>100</td>
                        <td>03/10/2022</td>
                        <td>03/31/2022</td>
                        <td class="text-center">
                            <i class="bi bi-pencil action-icon"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Coupons Table -->
        <div class="tab-pane fade" id="coupons" role="tabpanel">
            <h5>Coupons</h5>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Coupon Code</th>
                        <th>Discount</th>
                        <th>Max Use</th>
                        <th>Start Date</th>
                        <th>Expires on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="toggle-switch text-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="statusToggle2">
                            </div>
                        </td>
                        <td>COUPON2022</td>
                        <td>15%</td>
                        <td>50</td>
                        <td>04/01/2022</td>
                        <td>04/30/2022</td>
                        <td class="text-center">
                            <i class="bi bi-pencil action-icon"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
@endpush
