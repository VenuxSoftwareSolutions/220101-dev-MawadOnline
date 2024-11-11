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

        .nav-item {
            width: 50%;
            text-align: center;
        }

        .table th,
        .table td {
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
        .toast-success {
            background-color: green !important;
            color: white !important;
        }

    </style>
@endpush

@section('panel_content')
    <div class="container mt-5">
        <!-- Tabs for Discount Types with Card Styles -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="tab-card active" id="product-tab" data-bs-toggle="tab" data-bs-target="#product" role="tab" data-scope="product">
                    <div class="tab-card-icon"><i class="fas fa-box"></i></div>
                    <div class="tab-card-title">Product</div>
                    <div class="tab-card-description">Click here to offer a discount on a certain product from your
                        inventory.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="category-tab" data-bs-toggle="tab" data-bs-target="#category" role="tab" data-scope="category">
                    <div class="tab-card-icon"><i class="fas fa-tag"></i></div>
                    <div class="tab-card-title">Category</div>
                    <div class="tab-card-description">Click here to offer a discount on a certain category from your
                        inventory.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="ordersOverAmount-tab" data-bs-toggle="tab" data-bs-target="#ordersOverAmount" data-scope="ordersOverAmount"
                    role="tab">
                    <div class="tab-card-icon"><i class="fas fa-card"></i></div>
                    <div class="tab-card-title">Orders over an Amount</div>
                    <div class="tab-card-description">Click here to offer a discount on all orders above a certain amount.
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="allOrders-tab" data-bs-toggle="tab" data-bs-target="#allOrders" role="tab" data-scope="allOrders">
                    <div class="tab-card-icon"><i class="fas fa-basket"></i></div>
                    <div class="tab-card-title">All Orders</div>
                    <div class="tab-card-description">Click here to offer a discount on all the orders.</div>
                </div>
            </div>
        </div>

        <!-- Tabs for Discounts and Coupons -->
        <ul class="nav nav-tabs mb-3" id="discountCouponTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="discounts-tab" data-bs-toggle="tab" data-bs-target="#discounts"
                    type="button" role="tab">Discounts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="coupons-tab" data-bs-toggle="tab" data-bs-target="#coupons" type="button"
                    role="tab">Coupons</button>
            </li>
        </ul>

        <!-- Content for Discounts and Coupons -->
        <div class="tab-content" id="discountCouponContent">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Discounts Table -->
            <div class="tab-pane fade show active" id="discounts" role="tabpanel">
                <h5>Discounts - {{ ucfirst($scope) }}</h5>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>{{ $columnHeader }}</th>
                            <th>Percent</th>
                            <th>Max Discount</th>
                            <th>Start Date</th>
                            <th>Expires on</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($discounts as $discount)
                            <tr>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input class="publsihed_product" type="checkbox" checked>
                                        <span class=""> </span>
                                    </label>
                                </td>
                                <td>{!! $columnValue($discount) !!}</td>
                                <td>{{ $discount->discount_percentage }}%</td>
                                <td>{{ $discount->max_discount }}</td>
                                <td>{{ $discount->start_date->format('m/d/Y') }}</td>
                                <td>{{ $discount->end_date->format('m/d/Y') }}</td>
                                <td>
                                    <a class="btn btn-sm edit-discount-btn" href="#" title="Edit" data-id="{{ $discount->id }}">
                                        <img src="{{ asset('public/Edit.svg') }}">
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No discounts available for this scope.</td>
                            </tr>
                        @endforelse
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
@section('modal')
    <div id="editDiscountModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="editDiscountForm">
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="startDate">
                        </div>

                        <div class="mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control"  name="end_date" id="endDate">
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="text" class="form-control" id="amount" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="percentage" class="form-label">Percentage</label>
                            <input type="text" class="form-control" id="percentage" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="maxDiscount" class="form-label">Max Discount</label>
                            <input type="text" class="form-control" id="maxDiscount" disabled>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" id="saveChangesBtn" data-id="">Save Changes</button>
                    <button type="button" class="btn btn-danger" id="deleteDiscountBtn"
                        data-id="discountId">Delete</button>

                </div>
            </div>
        </div>
    </div>
    <div id="modal-info" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6" id="title-modal">{{ translate('Delete Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14" id="text-modal">{{ translate('Are you sure to delete this?') }}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2"
                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" class="btn btn-danger rounded-0 mt-2"
                        id="confirmDeleteBtn">{{ translate('OK') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    function formatDate(dateString) {
        return dateString.split('T')[0];
    }

    function fetchDiscountData(discountId) {
        return fetch(`/vendor/discounts/${discountId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('startDate').value = formatDate(data.start_date);
                document.getElementById('endDate').value = formatDate(data.end_date);
                document.getElementById('amount').value = data.min_order_amount;
                document.getElementById('percentage').value = data.discount_percentage;
                document.getElementById('maxDiscount').value = data.max_discount;

                document.getElementById('amount').disabled = true;
                document.getElementById('percentage').disabled = true;
                document.getElementById('maxDiscount').disabled = true;

                const modal = new bootstrap.Modal(document.getElementById('editDiscountModal'));
                modal.show();
            })
            .catch(error => console.error('Error fetching discount data:', error));
    }

    function updateDiscount(discountId, start_date, end_date) {
        fetch(`/vendor/discounts/${discountId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ start_date, end_date})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const bootstrapModal = new bootstrap.Modal(document.getElementById('editDiscountModal'));
                    bootstrapModal.hide();
                    displayToast(data.message);
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error updating discount:', error));
    }

    function deleteDiscount(discountId) {
        fetch(`/vendor/discounts/${discountId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                $('#modal-info').modal('hide');
                displayToast(data.message);
                window.location.reload();
            })
            .catch(error => console.error('Error deleting discount:', error));
    }

    function displayToast(message) {
        if (typeof toastr !== "undefined") {
            toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: true,
                progressBar: true,
                positionClass: "toast-top-right",
                preventDuplicates: true,
                showDuration: "500",
                hideDuration: "2000",
                timeOut: "10000",
                extendedTimeOut: "2000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };
            toastr.success(message);
        } else {
            console.error("Toastr is not loaded");
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('#discountCouponTabs .nav-link');
        const scopeCards = document.querySelectorAll('.tab-card');

        // Function to update URL based on selected tab and scope
        function updateURL(tab, scope) {
            const baseUrl = (tab === 'discounts') ? '/vendor/discounts' : '/vendor/coupons';
            window.location.href = `${baseUrl}?scope=${scope}`;
        }
        // Set up tab listeners for Discounts and Coupons
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const selectedTab = this.id === 'discounts-tab' ? 'discounts' : 'coupons';
                const activeScope = document.querySelector('.tab-card.active').getAttribute('data-scope');
                updateURL(selectedTab, activeScope);
            });
        });
        scopeCards.forEach(card => {
            card.addEventListener('click', () => {
                const activeTab = document.querySelector('#discountCouponTabs .nav-link.active').id.includes('discounts') ? 'discounts' : 'coupons';
                const scope = card.getAttribute('data-scope');
                updateURL(activeTab, scope);
            });
        });
        document.querySelectorAll('.edit-discount-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const discountId = button.getAttribute('data-id');
                fetchDiscountData(discountId);
            });
        });

        document.getElementById('saveChangesBtn').addEventListener('click', function() {
            const discountId = document.querySelector('.edit-discount-btn[data-id]').getAttribute('data-id');
            const start_date = document.getElementById('startDate').value;
            const end_date = document.getElementById('endDate').value;
            updateDiscount(discountId, start_date, end_date);
        });

        document.getElementById("deleteDiscountBtn").addEventListener("click", function() {
            const discountId = document.querySelector('.edit-discount-btn[data-id]').getAttribute('data-id');
            $('#modal-info').modal('show');
        });

        document.getElementById("confirmDeleteBtn").addEventListener("click", function() {
            const discountId = document.querySelector('.edit-discount-btn[data-id]').getAttribute('data-id');
            if (discountId) {
                deleteDiscount(discountId);
            }
        });
    });
</script>


{{-- @push('scripts')
    <script type="text/javascript">
       $(document).ready(function() {
            $('body').on('click', '.edit-discount-btn', function(e) {
                console.log("clicked");
                e.preventDefault();
                $('#editDiscountModal').modal('show');
            });
        });

    </script> 
@endpush
--}}
