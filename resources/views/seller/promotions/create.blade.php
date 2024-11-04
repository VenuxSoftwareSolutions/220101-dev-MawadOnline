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
                <div class="tab-card" id="product-tab" data-scope="product">
                    <div class="tab-card-icon"><i class="bi bi-box"></i></div>
                    <div class="tab-card-title">Product</div>
                    <div class="tab-card-description">Click here to offer a discount on a certain product from your inventory.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="category-tab" data-scope="category">
                    <div class="tab-card-icon"><i class="bi bi-tag"></i></div>
                    <div class="tab-card-title">Category</div>
                    <div class="tab-card-description">Click here to offer a discount on a certain category from your inventory.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="ordersOverAmount-tab" data-scope="ordersOverAmount">
                    <div class="tab-card-icon"><i class="bi bi-cart4"></i></div>
                    <div class="tab-card-title">Orders over an Amount</div>
                    <div class="tab-card-description">Click here to offer a discount on all orders above a certain amount.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="allOrders-tab" data-scope="allOrders">
                    <div class="tab-card-icon"><i class="bi bi-basket"></i></div>
                    <div class="tab-card-title">All Orders</div>
                    <div class="tab-card-description">Click here to offer a discount on all the orders.</div>
                </div>
            </div>
        </div>
    
    

        <div class="tab-content p-4 border border-top-0" id="discountTabsContent">
            <div class="tab-pane fade show active" id="product" role="tabpanel">
                <form action="{{ route('seller.discounts.store') }}" method="POST">
                    @csrf

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
                        <div class="col-md-6">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="startDate" required>
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="endDate" required>
                        </div>               
                    </div>

                    <div class="row" >
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control aiz-selectpicker" id="category" name="category_id" > >
                                <option selected>Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-control aiz-selectpicker"  id="product_id" name="product_id">
                                <option selected>Select product</option>
                                @foreach($products as $product)
                                     <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="percent" class="form-label">Percent</label>
                            <input type="number" class="form-control" id="percent" name="percent" min="0" max="100" placeholder="0%" required >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="maxDiscount" class="form-label">Max Discount</label>
                            <input type="number" class="form-control" name="maxDiscount" placeholder="Maximum discount amount">
                        </div>
                    </div>
                    <input type="hidden" name="scope" id="scope" value="">

                    <div class="text-center">
                        <button type="submit" class="btn btn-dark-custom">Add Discount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<script>
   document.addEventListener("DOMContentLoaded", function () {
    const scopeInput = document.getElementById("scope");
    const categorySelect = document.getElementById("category");
    const productSelect = document.getElementById("product_id");

    function updateFieldState(scope) {
        scopeInput.value = scope;

        categorySelect.disabled = true;
        productSelect.disabled = true;

        if (scope === "category") {
            categorySelect.disabled = false;
        } else if (scope === "product") {
            productSelect.disabled = false;
        }

        $('.aiz-selectpicker').selectpicker('refresh');
    }

    document.querySelectorAll('.tab-card').forEach(card => {
        card.addEventListener('click', function () {
            const scope = this.getAttribute('data-scope');
            updateFieldState(scope);

            // Set active class for visual indication
            document.querySelectorAll('.tab-card').forEach(card => card.classList.remove('active'));
            this.classList.add('active');

            // Update the URL without refreshing
            const url = new URL(window.location);
            url.searchParams.set('scope', scope);
            window.history.replaceState(null, '', url);
        });
    });

    // Check if there's a scope in the URL when the page loads
    const urlParams = new URLSearchParams(window.location.search);
    const selectedScope = urlParams.get('scope');

    if (selectedScope) {
        updateFieldState(selectedScope);

        // Set the correct tab as active based on the scope
        document.querySelectorAll('.tab-card').forEach(card => {
            card.classList.remove('active');
            if (card.getAttribute('data-scope') === selectedScope) {
                card.classList.add('active');
            }
        });
    }
});

</script>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endpush
