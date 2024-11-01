@extends('seller.layouts.app')

@push('styles')
    <style>
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

        .tab-card.active,
        .tab-card:hover {
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

        .btn-dark-custom {
            background-color: #333;
            color: #fff;
            border-color: #333;
        }

        .btn-dark-custom:hover {
            background-color: #555;
            border-color: #555;
        }
    </style>
@endpush

@section('panel_content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="tab-card active" id="product-tab" data-bs-toggle="tab" data-bs-target="#product" role="tab">
                    <div class="tab-card-icon"><i class="bi bi-box"></i></div>
                    <div class="tab-card-title">Product</div>
                    <div class="tab-card-description">Click here to offer a discount on a certain product from your
                        inventory.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="category-tab" data-bs-toggle="tab" data-bs-target="#category" role="tab">
                    <div class="tab-card-icon"><i class="bi bi-tag"></i></div>
                    <div class="tab-card-title">Category</div>
                    <div class="tab-card-description">Click here to offer a discount on a certain category from your
                        inventory.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="ordersOverAmount-tab" data-bs-toggle="tab" data-bs-target="#ordersOverAmount"
                    role="tab">
                    <div class="tab-card-icon"><i class="bi bi-cart4"></i></div>
                    <div class="tab-card-title">Orders over an Amount</div>
                    <div class="tab-card-description">Click here to offer a discount on all orders above a certain amount.
                    </div>
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

        <div class="tab-content p-4 border border-top-0" id="discountTabsContent">
            <div class="tab-pane fade show active" id="product" role="tabpanel">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="attributes" type="radio" name="discountType" id="discount" checked>
                                <label class="form-check-label" for="discount">Discount</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="attributes" type="radio" name="discountType" id="coupons">
                                <label class="form-check-label" for="coupons">Coupons</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control aiz-selectpicker" id="category">
                                <option selected>Select category</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="product" class="form-label">Product</label>
                            <select class="form-control aiz-selectpicker" id="product">
                                <option selected>Select product</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="percent" class="form-label">Percent</label>
                            <input type="number" class="form-control" id="percent" placeholder="0%">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="maxDiscount" class="form-label">Max Discount</label>
                            <input type="number" class="form-control" id="maxDiscount" placeholder="0">
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-dark-custom">Add Discount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endpush
