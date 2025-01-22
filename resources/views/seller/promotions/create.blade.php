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
        #copyButton {
            position: relative;
        }   

        .tooltip-text {
            visibility: hidden;
            width: 100px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            bottom: 125%; 
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 1;
        }

        #copyButton:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        /* jQuery Tree Multiselect v2.6.3 | (c) Patrick Tsai | MIT Licensed */
        div.tree-multiselect {
            border: 2px solid #D8D8D8;
            border-radius: 5px;
            display: table;
            height: inherit;
            width: 100%;
        }

        div.tree-multiselect>div.selected,
        div.tree-multiselect>div.selections {
            display: inline-block;
            box-sizing: border-box;
            overflow: auto;
            padding: 1%;
            vertical-align: top;
            width: 50%;
        }

        div.tree-multiselect>div.selections {
            border-right: solid 2px #D8D8D8;
        }

        div.tree-multiselect>div.selections div.item {
            margin-left: 16px;
        }

        div.tree-multiselect>div.selections div.item label {
            cursor: pointer;
            display: inline;
        }

        div.tree-multiselect>div.selections div.item label.disabled {
            color: #D8D8D8;
        }

        div.tree-multiselect>div.selections *[searchhit=false] {
            display: none;
        }

        div.tree-multiselect>div.selections.no-border {
            border-right: none;
        }

        div.tree-multiselect>div.selected>div.item {
            background: #EAEAEA;
            border-radius: 2px;
            padding: 2px 5px;
            overflow: auto;
        }

        div.tree-multiselect>div.selected.ui-sortable>div.item:hover {
            cursor: move;
        }

        div.tree-multiselect div.section>div.section,
        div.tree-multiselect div.section>div.item {
            padding-left: 20px;
        }

        div.tree-multiselect div.section.collapsed>div.title span.collapse-section:after {
            content: "+";
        }

        div.tree-multiselect div.section.collapsed:not([searchhit])>.item,
        div.tree-multiselect div.section.collapsed:not([searchhit])>.section {
            display: none;
        }

        div.tree-multiselect div.title,
        div.tree-multiselect div.item {
            margin-bottom: 2px;
        }

        div.tree-multiselect div.title {
            background: #777;
            color: white;
            padding: 2px;
        }

        div.tree-multiselect div.title>* {
            display: inline-block;
        }

        div.tree-multiselect div.title>span.collapse-section {
            margin: 0 3px;
            width: 8px;
        }

        div.tree-multiselect div.title>span.collapse-section:after {
            content: "-";
        }

        div.tree-multiselect div.title:hover {
            cursor: pointer;
        }

        div.tree-multiselect input[type=checkbox] {
            display: inline;
            margin-right: 5px;
        }

        div.tree-multiselect input[type=checkbox]:not([disabled]):hover {
            cursor: pointer;
        }

        div.tree-multiselect span.remove-selected,
        div.tree-multiselect span.description {
            background: #777;
            border-radius: 2px;
            color: white;
            margin-right: 5px;
            padding: 0 3px;
        }

        div.tree-multiselect span.remove-selected:hover {
            cursor: pointer;
        }

        div.tree-multiselect span.description:hover {
            cursor: help;
        }

        div.tree-multiselect div.temp-description-popup {
            background: #EAEAEA;
            border: 2px solid #676767;
            border-radius: 3px;
            padding: 5px;
        }

        div.tree-multiselect span.section-name {
            float: right;
            font-style: italic;
        }

        div.tree-multiselect .auxiliary {
            display: table;
            width: 100%;
        }

        div.tree-multiselect .auxiliary input.search {
            border: 2px solid #D8D8D8;
            display: table-cell;
            margin: 0;
            padding: 5px;
            width: 100%;
        }

        div.tree-multiselect .auxiliary .select-all-container {
            display: table-cell;
            text-align: right;
        }

        div.tree-multiselect .auxiliary .select-all-container span.select-all,
        div.tree-multiselect .auxiliary .select-all-container span.unselect-all {
            margin-right: 5px;
            padding-right: 5px;
        }

        div.tree-multiselect .auxiliary .select-all-container span.select-all:hover,
        div.tree-multiselect .auxiliary .select-all-container span.unselect-all:hover {
            cursor: pointer;
        }

        div.tree-multiselect .auxiliary .select-all-container span.select-all {
            border-right: 2px solid #D8D8D8;
        }
    </style>
@endpush

@section('panel_content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="tab-card" id="product-tab" data-scope="product">
                    <div class="tab-card-icon"><i class="fas fa-box"></i></div>
                    <div class="tab-card-title">Product</div>
                    <div class="tab-card-description">Click here to offer a discount on a certain product from your
                        inventory.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="category-tab" data-scope="category">
                    <div class="tab-card-icon"><i class="fas fa-tag"></i></div>
                    <div class="tab-card-title">Category</div>
                    <div class="tab-card-description">Click here to offer a discount on a certain category from your
                        inventory.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="ordersOverAmount-tab" data-scope="ordersOverAmount">
                    <div class="tab-card-icon"><i class="fas fa-image"></i></div>
                    <div class="tab-card-title">Orders over an Amount</div>
                    <div class="tab-card-description">Click here to offer a discount on all orders above a certain amount.
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="tab-card" id="allOrders-tab" data-scope="allOrders">
                    <div class="tab-card-icon"><i class="fas fa-home"></i></div>
                    <div class="tab-card-title">All Orders</div>
                    <div class="tab-card-description">Click here to offer a discount on all the orders.</div>
                </div>
            </div>
        </div>

        <div class="tab-content p-4 border border-top-0" id="discountTabsContent">
            <div class="tab-pane fade show active" id="product" role="tabpanel">
                <form id="discountForm" action="{{ route('seller.discounts.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="attributes" type="radio" name="offerType" value="discount" onclick="updateFormAndUrl()"  id="discount" checked>
                                <label class="form-check-label" for="discount">Discount</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="attributes" type="radio" name="offerType" value="coupon" onclick="updateFormAndUrl()" id="coupons">
                                <label class="form-check-label" for="coupons">Coupons</label>
                            </div>
                        </div>
                    </div>
    

                    <div class="row">
                        <div class="col-md-6">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-md-6 mb-3 tree-multiselect" id="multiTreeContainer" style="display:none;">
                            <label for="multiTreeCategory" class="form-label">Category</label>
                            <select id="multiTreeCategory" name="category_id" multiple="multiple">
                                @foreach ($nestedCategories as $category)
                                    @include('seller.promotions.partials.category_option', [
                                        'category' => $category,
                                    ])
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3" id="productCategoryContainer" >
                            <label for="productCategory" class="form-label">Category</label>
                            <select class="form-control aiz-selectpicker" id="productCategory" name="category_id">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        </div>



                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-control aiz-selectpicker" id="product_id" name="product_id">
                                <option value="" selected>Select product</option>
                              
                            </select>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order_amount" class="form-label">Order Amount</label>
                            <input type="number" class="form-control" id="order_amount" name="min_order_amount"
                                placeholder="Minimum order amount">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="percent" class="form-label">Percent</label>
                            <input type="number" class="form-control" id="percent" name="discount_percentage"
                                min="0" max="100" placeholder="0%" required>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="min_qty" class="form-label">Minimum Quantity</label>
                            <input type="number" class="form-control" id="min_qty" name="min_qty" placeholder="Minimum quantity" min="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="max_qty" class="form-label">Maximum Quantity</label>
                            <input type="number" class="form-control" id="max_qty" name="max_qty" placeholder="Maximum quantity" min="1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="maxDiscount" class="form-label">Max Use	Discount</label>
                            <input type="number" class="form-control" name="max_discount"
                                placeholder="Maximum discount amount">
                        </div>
                    </div>
                    <input type="hidden" name="scope" id="scope" value="">

                    <div class="row" id="couponCodeContainer" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <div class="form-label"></div>
                            <div style="display: flex; align-items: center; justify-content: center; width: 250px; margin: 0 auto; border: 1px dashed #ccc; padding: 10px; text-align: center;">
                                <span id="generatedCode" style="  text-align: center;"></span>
                                
                                <button id="copyButton" type="button"  onclick="copyToClipboard()" style="background: none; border: none; cursor: pointer; display: none; margin-left: 10px; position: relative;">
                                    <i class="fas fa-copy" aria-hidden="true"></i>
                                    <span class="tooltip-text" id="tooltipText">Copy coupon code</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="code" id="code">
                    
                        <div class="col-md-12 text-center">
                            <button type="button" id="generateButton" class="btn btn-dark-custom" onclick="generateCouponCode()">Generate Coupon</button>
                            <button type="button" id="activateButton" class="btn btn-dark-custom" style="display: none;">Activate Coupon</button>
                        </div>
                    </div>
                    
    
                    <div id="DiscountContainer" class="text-center">
                        <button type="submit" class="btn btn-dark-custom">Add Discount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

<div id="modal-discount-overlap" class="modal fade">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">{{ translate('Offers Overlap Confirmation') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1 fs-14">{{ translate('Please note that this range overlaps with the existing offers listed below.') }}</p>
                <p class="fs-14">{{ translate('If you proceed, the greater offer will be applied.') }}</p>
                
                <ul  id="overlappingDiscountList" class="list-group mb-3" >
                    <li class="list-group-item">
                    </li>
                </ul>

                <button type="button" class="btn btn-secondary rounded-0 mt-2"
                    data-dismiss="modal">{{ translate('Cancel') }}</button>
                <button type="button" class="btn btn-primary rounded-0 mt-2"
                    id="confirmProceedBtn">{{ translate('Proceed') }}</button>
            </div>
        </div>
    </div>
</div>

             
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tree-multiselect@2.6.3/dist/jquery.tree-multiselect.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        function generateCouponCode() {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = '';
            for (let i = 0; i < 10; i++) {
                code += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            document.getElementById('generatedCode').textContent = code;
            document.getElementById('code').value = code; 
            document.getElementById('copyButton').style.display = 'inline-block';
            document.getElementById('activateButton').style.display = 'inline-block';
            document.getElementById('generateButton').style.display = 'none';
        }

        function copyToClipboard() {
            const code = document.getElementById("generatedCode").textContent;
            const textarea = document.createElement('textarea');
            textarea.value = code;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');   
            document.body.removeChild(textarea);

            const copyButtonIcon = document.querySelector("#copyButton i");
            copyButtonIcon.classList.remove('fa-copy');
            copyButtonIcon.classList.add('fa-check');

            const tooltipText = document.getElementById("tooltipText");
            tooltipText.textContent = "copied!";

            setTimeout(() => {
                copyButtonIcon.classList.remove('fa-check');
                copyButtonIcon.classList.add('fa-copy');
                tooltipText.textContent = "Copy coupon code";
            }, 3000);
        }
        function updateFormAndUrl(selectedScope = null) {
                const offerType = document.querySelector('input[name="offerType"]:checked').value;
                const baseUrl = offerType === 'coupon' ? '/vendor/coupons/create' : '/vendor/discounts/create';
                const scope = selectedScope || new URLSearchParams(window.location.search).get('scope');
                
                const url = new URL(window.location.origin + baseUrl);
                if (scope) {
                    url.searchParams.set('scope', scope);
                }
                window.history.replaceState(null, '', url);

                const formAction = offerType === 'coupon' ? "{{ route('seller.coupons.store') }}" : "{{ route('seller.discounts.store') }}";
                document.getElementById('discountForm').setAttribute('action', formAction);
        }
        function submitForm(ignoreOverlap, type) {
            const formData = new FormData($('#discountForm')[0]);
            
            if (ignoreOverlap) {
                formData.append('ignore_overlap', true);
            }

            const url = type === 'coupon' 
                ? "{{ route('seller.coupons.store') }}" 
                : "{{ route('seller.discounts.store') }}";

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'overlap') {
                        const overlappingItems = type === 'coupon' ? response.overlappingCoupons : response.overlappingDiscounts;
                        showOverlapModal(overlappingItems, type);
                    } else if (response.status === 'success') {
                        window.location.href = response.redirectUrl;
                    }
                },
                error: function() {
                    alert('An error occurred.');
                }
            });
        }
        function submitDiscountForm(ignoreOverlap) {
                submitForm(ignoreOverlap, 'discount');

        }
        function submitCouponForm(ignoreOverlap) {
            submitForm(ignoreOverlap, 'coupon');
        }
        function showOverlapModal(overlappingItems, type) {
            const ul = $('#overlappingDiscountList');
            ul.empty();

            overlappingItems.forEach(function(item) {
                const startDate = new Date(item.start_date).toISOString().slice(0, 10);
                const endDate = new Date(item.end_date).toISOString().slice(0, 10);
                ul.append(`
                    <li class="list-group-item">
                        <strong>Scope:</strong> ${item.scope} 
                        <br>
                        <strong>From:</strong> ${startDate} <strong>to</strong> ${endDate}
                    </li>
                `);
            });

            $('#modal-discount-overlap').modal('show');

            $('#confirmProceedBtn').off('click').on('click', function() {
                $('#modal-discount-overlap').modal('hide');
                if (type === 'coupon') {
                    submitCouponForm(true);
                } else {
                    submitDiscountForm(true);
                }
            });
        }
        $(document).ready(function() {
            const form = document.getElementById("discountForm");
            const scopeInput = document.getElementById("scope");
            const productSelect = document.getElementById("product_id");
            const categorySelect = document.getElementById("productCategory");
            const orderAmountInput = document.getElementById('order_amount');
            const multiTreeContainer = document.getElementById("multiTreeContainer");
            const productCategoryContainer = document.getElementById("productCategoryContainer");
            const getProductByCategoryUrl = @json(route('seller.discounts.getproductbycategory'));
            const discountRadio = document.getElementById('discount');
            const couponRadio = document.getElementById('coupons');
            const couponCodeContainer = document.getElementById('couponCodeContainer');
            const DiscountContainer = document.getElementById('DiscountContainer');
            const maxQuantityInput = document.getElementById('max_qty');
            const minQuantityInput = document.getElementById('min_qty');

           


            function updateScopeView(scope) {
                scopeInput.value = scope;
                productSelect.disabled = true;
                productSelect.value = "";
                categorySelect.disabled = true;
                categorySelect.value = "";
                orderAmountInput.disabled = true;
                orderAmountInput.value = "";
                productCategoryContainer.style.display = "none";
                multiTreeContainer.style.display = "none";
                maxQuantityInput.disabled = true ;
                minQuantityInput.disabled = true ; 

                const multiTreeParams = {
                    sortable: true,
                    searchable: true,
                    searchParams: ['section', 'text'],
                    onChange: function (allSelectedItems, addedItems, removedItems) {
                        addedItems.forEach(item => {
                            if (!$(item).data('leaf')) {
                                $(item).prop('selected', false);
                            }
                        });
                    },
                    startCollapsed: true,
                    maxSelections: 1,
                    freeze: false
                };

                if (scope === "product") {
                    productCategoryContainer.style.display = "block";
                    maxQuantityInput.style.display = "block";

                    productSelect.disabled = false;
                    categorySelect.disabled = false;
                    maxQuantityInput.disabled = false ;
                    minQuantityInput.disabled = false ; 



                } else if (scope === "category") {
                    multiTreeContainer.style.display = "block";
                    if ($("select#multiTreeCategory").next(".tree-multiselect").length) {
                        $("select#multiTreeCategory").next(".tree-multiselect").remove();
                     }

                    $("select#multiTreeCategory").treeMultiselect(multiTreeParams);
                    productSelect.value = null;
                    productSelect.disabled = true;



                } else if (scope === "ordersOverAmount") {
                    // Enable order amount field only
                    orderAmountInput.disabled = false;
                    productCategoryContainer.style.display = "block";

                } else if (scope === "allOrders") {
                    // All fields remain disabled except the discount fields
                    productCategoryContainer.style.display = "block";
                }

                $('.aiz-selectpicker').selectpicker('refresh');
            }

            categorySelect.addEventListener('change', function () {
                const selectedCategoryId = this.value;
                if (selectedCategoryId) {
                    $.ajax({
                        url: getProductByCategoryUrl,
                        type: 'GET',
                        data: { category_id: selectedCategoryId },
                        success: function (response) {
                            productSelect.innerHTML = '<option value="" selected>Select product</option>';
                            response.products.forEach(function (product) {
                                const option = new Option(product.name, product.id);
                                productSelect.add(option);
                            });
                            $('.aiz-selectpicker').selectpicker('refresh');
                        }
                    });
                } else {
                    productSelect.innerHTML = '<option value="" selected>Select product</option>';
                    $('.aiz-selectpicker').selectpicker('refresh');
                }
            });

            document.querySelectorAll('.tab-card').forEach(card => {
                card.addEventListener('click', function() {
                    const scope = this.getAttribute('data-scope');
                    updateScopeView(scope);

                    document.querySelectorAll('.tab-card').forEach(card => card.classList.remove(
                        'active'));
                    this.classList.add('active');

                    updateFormAndUrl(scope);

                });
            });
            const urlParams = new URLSearchParams(window.location.search);
            const selectedScope = urlParams.get('scope') || 'product';
            if (selectedScope) {
                updateScopeView(selectedScope);

                document.querySelectorAll('.tab-card').forEach(card => {
                    card.classList.remove('active');
                    if (card.getAttribute('data-scope') === selectedScope) {
                        card.classList.add('active');
                    }
                });
            }
            updateFormAndUrl(selectedScope);

            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: message,
                    confirmButtonText: 'Okay'
                });
            }

            function validateForm() {
                const startDate = form.querySelector("input[name='start_date']").value;
                const endDate = form.querySelector("input[name='end_date']").value;
                const scope = document.getElementById("scope").value;
                const percent = form.querySelector("input[name='discount_percentage']").value;
                const maxDiscount = form.querySelector("input[name='max_discount']").value;
                const productId = form.querySelector("select[name='product_id']").value;
                const categoryId = form.querySelector("select[name='category_id']").value;
                const orderAmount = form.querySelector("#order_amount").value;
                const generatedCode = document.getElementById("code").value;
                const offerType = document.querySelector('input[name="offerType"]:checked').value;
                const maxQty = form.querySelector("input[name='max_qty']").value;
                const minQty = form.querySelector("input[name='min_qty']").value;

                if (!startDate) return showError("Start date is required.");
                if (!endDate) return showError("End date is required.");
                if (new Date(endDate) < new Date(startDate)) return showError(
                    "End date must be after or equal to the start date.");
                if (!scope) return showError("Please select a discount scope.");
                if (percent === "" || isNaN(percent) || percent < 0 || percent > 100) return showError(
                    "Percent must be a number between 0 and 100.");
                if (maxDiscount && (isNaN(maxDiscount) || maxDiscount < 0)) return showError(
                    "Max discount must be a positive number.");
                if (scope === "product" && (!productId || isNaN(productId))) return showError(
                    "Please select a valid product.");
                if (scope === "category" && (!categoryId || isNaN(categoryId))) return showError(
                    "Please select a valid category.");
                if (scope === "ordersOverAmount" && (!orderAmount || isNaN(orderAmount) || orderAmount <= 0)) {
                    return showError("Minimum Order amount must be a positive number.");
                }
                if (offerType === "coupon" && (!generatedCode)) {
                    return showError ("Please generate a coupon code before submitting.");
                }
                if (minQty === "" || isNaN(minQty) || minQty <= 0)
                    return showError("Minimum quantity must be a positive number.");
                if (maxQty === "" || isNaN(maxQty) || maxQty <= 0)
                    return showError("Maximum quantity must be a positive number.");
                if (Number(maxQty) < Number(minQty))
                    return showError("Maximum quantity must be greater than or equal to the minimum quantity.");

                return true;
            }

            $('#discountForm').on('submit', function(e) {
                e.preventDefault();
                if (validateForm()) {
                    submitDiscountForm(false); 
                }
            });
            $('#activateButton').on('click', function(e) {
                e.preventDefault();
                if (validateForm()) {
                    submitCouponForm(false);
                }
            });
            $('#confirmProceedBtn').on('click', function() {
                $('#modal-discount-overlap').modal('hide'); 
                submitDiscountForm(true); 
            });
           
            
            discountRadio.addEventListener('change', toggleCouponFields);
            couponRadio.addEventListener('change', toggleCouponFields);
            function toggleCouponFields() {

                if (couponRadio.checked) {
                    couponCodeContainer.style.display = 'block';
                    DiscountContainer.style.display = 'none';
                } else {
                    couponCodeContainer.style.display = 'none';
                    DiscountContainer.style.display = 'block';
                }
            }
            toggleCouponFields();
           
        });
    </script>
@endsection
