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
                <button class="nav-link " id="discounts-tab" data-bs-toggle="tab" data-bs-target="#discounts"
                    type="button" role="tab">Discounts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="coupons-tab" data-bs-toggle="tab" data-bs-target="#coupons" type="button"
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
            @if(!$isCoupon)
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
                                            <input 
                                                class="published_product toggle-offer-switch" 
                                                type="checkbox" 
                                                data-id="{{ $discount->id }}" 
                                                data-type="discount"
                                                {{ $discount->status ? 'checked' : '' }}
                                            >
                                            <span></span>
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
            @else
                <div class="tab-pane fade show active" id="coupons" role="tabpanel">
                    <h5>Coupons - {{ ucfirst($scope) }}</h5>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Coupon Code</th>
                                <th>{{ $columnHeader }}</th>
                                <th>Percentage</th>
                                <th>Start Date</th>
                                <th>Expires on</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($coupons as $coupon)
                                <tr>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input 
                                                class="published_product toggle-offer-switch" 
                                                type="checkbox" 
                                                data-id="{{ $coupon->id }}" 
                                                data-type="coupon"
                                                {{ $coupon->status ? 'checked' : '' }}
                                            >
                                            <span></span>
                                    </td>
                                    </label>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{!! $columnValue($coupon) !!}</td>
                                    <td>{{ $coupon->discount_percentage }}%</td>
                                    <td>{{ $coupon->start_date->format('m/d/Y') }}</td>
                                    <td>{{ $coupon->end_date->format('m/d/Y') }}</td>
                                    <td>
                                        <a class="btn btn-sm edit-coupon-btn" href="#" title="Edit" data-id="{{ $coupon->id }}">
                                            <img src="{{ asset('public/Edit.svg') }}">
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No coupons available for this scope.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
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

    <div id="editCouponModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Coupon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="editCouponForm">
                        <div class="mb-3">
                            <label for="couponCode" class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" id="couponCode" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="startDateCoupon" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="startDateCoupon">
                        </div>
                        <div class="mb-3">
                            <label for="endDateCoupon" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" id="endDateCoupon">
                        </div>
                        <div class="mb-3">
                            <label for="amountCoupon" class="form-label">Amount</label>
                            <input type="text" class="form-control" id="amountCoupon" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="percentageCoupon" class="form-label">Percentage</label>
                            <input type="text" class="form-control" id="percentageCoupon" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="maxDiscountCoupon" class="form-label">Max Discount</label>
                            <input type="text" class="form-control" id="maxDiscountCoupon" disabled>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" id="saveCouponChangesBtn">Save Changes</button>
                    <button type="button" class="btn btn-danger" id="deleteCouponBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-info" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6" id="title-modal">{{ translate('Delete Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
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
    <div id="toggle-confirmation-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <p id="confirmation-message"></p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-0 mt-2" id="confirm-toggle-btn">Proceed</button>
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

    function fetchCouponData(couponId) {
        return fetch(`/vendor/coupons/${couponId}/edit`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                document.getElementById('startDateCoupon').value = formatDate(data.start_date);
                document.getElementById('endDateCoupon').value = formatDate(data.end_date);
                document.getElementById('couponCode').value = data.code;
                document.getElementById('amountCoupon').value = data.min_order_amount;
                document.getElementById('percentageCoupon').value = data.discount_percentage;
                document.getElementById('maxDiscountCoupon').value = data.max_discount;

                document.getElementById('couponCode').disabled = true;
                document.getElementById('amountCoupon').disabled = true;
                document.getElementById('percentageCoupon').disabled = true;
                document.getElementById('maxDiscountCoupon').disabled = true;

                const modal = new bootstrap.Modal(document.getElementById('editCouponModal'));
                modal.show();
            })
            .catch(error => console.error('Error fetching coupon data:', error));
    }   
    function updateCoupon(couponId, start_date, end_date) {
        fetch(`/vendor/coupons/${couponId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ start_date, end_date })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const bootstrapModal = new bootstrap.Modal(document.getElementById('editCouponModal'));
                    bootstrapModal.hide();
                    displayToast(data.message);
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error updating coupon:', error));
    }
    function deleteCoupon(couponId) {
        fetch(`/vendor/coupons/${couponId}`, {
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
            .catch(error => console.error('Error deleting coupon:', error));
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
        const path = window.location.pathname;
        const discountTab = document.getElementById('discounts-tab');
        const couponTab = document.getElementById('coupons-tab');
        const toggleModal = document.getElementById('toggle-confirmation-modal');
        const confirmToggleBtn = document.getElementById('confirm-toggle-btn');
        const confirmationMessage = document.getElementById('confirmation-message');
        let itemId, isCoupon, isEnabled, toggle, originalState;

        if (path.includes('/vendor/coupons')) {
            couponTab.classList.add('active');
            discountTab.classList.remove('active');
        } else if (path.includes('/vendor/discounts')) {
            discountTab.classList.add('active');
            couponTab.classList.remove('active');
        }

        function updateURL(tab, scope) {
            const baseUrl = (tab === 'discounts') ? '/vendor/discounts' : '/vendor/coupons';
            window.location.href = `${baseUrl}?scope=${scope}`;
        }
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

        document.querySelectorAll('.edit-coupon-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const couponId = button.getAttribute('data-id');
                fetchCouponData(couponId);
            });
        });
        document.getElementById('saveCouponChangesBtn').addEventListener('click', function() {
            const couponId = document.querySelector('.edit-coupon-btn[data-id]').getAttribute('data-id');
            const start_date = document.getElementById('startDateCoupon').value;
            const end_date = document.getElementById('endDateCoupon').value;
            updateCoupon(couponId, start_date, end_date);
        });
        document.getElementById("deleteCouponBtn").addEventListener("click", function() {
            const couponId = document.querySelector('.edit-coupon-btn[data-id]').getAttribute('data-id');
            $('#modal-info').modal('show');
        });
        document.getElementById("confirmDeleteBtn").addEventListener("click", function() {
            const couponId = document.querySelector('.edit-coupon-btn[data-id]').getAttribute('data-id');
            if (couponId) {
                deleteCoupon(couponId);
            }
        });
        
        document.querySelectorAll('.aiz-switch input[type="checkbox"]').forEach(function (element) {
            element.addEventListener('change', function () {
                toggle = element;
                itemId = toggle.getAttribute('data-id');
                isCoupon = toggle.getAttribute('data-type') === 'coupon';
                isEnabled = toggle.checked;
                originalState = !isEnabled;

                confirmationMessage.textContent = `Are you sure you want to ${isEnabled ? 'enable' : 'disable'} this ${isCoupon ? 'coupon' : 'discount'}?`;

                $(toggleModal).modal('show');
            });
        });
        $(toggleModal).on('hidden.bs.modal', function () {
                if (toggle) {
                    toggle.checked = originalState; 
                }
        });
        confirmToggleBtn.addEventListener('click', function () {
            const url = isCoupon ? '/vendor/coupons/toggle-status' : '/vendor/discounts/toggle-status';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ id: itemId, status: isEnabled })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayToast(`${isCoupon ? 'Coupon' : 'Discount'} status updated successfully.`);
                } else {
                    displayToast('Error updating status.');
                    toggle.checked = !isEnabled; 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toggle.checked = !isEnabled; 
            })
            .finally(() => {
                $(toggleModal).modal('hide');
            });
           


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
