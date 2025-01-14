@php
    $total = 0;
    $carts = get_user_cart();
    if (count($carts) > 0) {
        foreach ($carts as $key => $cartItem) {
            $product = get_single_product($cartItem['product_id']);
            $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
        }
    }
@endphp
<a href="javascript:void(0)" class="d-flex align-items-center text-dark px-3 h-100" data-toggle="dropdown">
    <span class="mr-2">
        <span class="badge badge-counter font-prompt">{{ count($carts) }}</span>
        <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M7.048 19.728C5.63032 19.9956 4.35043 20.7495 3.42893 21.8596C2.50744 22.9696 2.00209 24.3664 2 25.8091C2.00181 27.4505 2.65465 29.0241 3.81526 30.1847C4.97588 31.3454 6.5495 31.9982 8.19086 32C9.63166 31.9976 11.0266 31.4934 12.136 30.574C13.2454 29.6547 13.9998 28.3776 14.2697 26.9623L28.9554 26.952C29.2585 26.952 29.5492 26.8316 29.7635 26.6173C29.9779 26.4029 30.0983 26.1122 30.0983 25.8091C30.0983 25.506 29.9779 25.2153 29.7635 25.001C29.5492 24.7867 29.2585 24.6663 28.9554 24.6663H25.9509V11.4869C25.9509 9.59657 24.4126 8.05829 22.5223 8.05829H9.33371V5.61714C9.3322 4.12765 8.73975 2.69961 7.68642 1.64649C6.63308 0.593367 5.20492 0.0012102 3.71543 0C3.41232 0 3.12163 0.120408 2.90731 0.334735C2.69298 0.549062 2.57257 0.839753 2.57257 1.14286C2.57257 1.44596 2.69298 1.73665 2.90731 1.95098C3.12163 2.16531 3.41232 2.28571 3.71543 2.28571C4.5989 2.28632 5.44603 2.63747 6.07084 3.26207C6.69565 3.88667 7.04709 4.73367 7.048 5.61714V19.728ZM8.19086 29.7143C6.03657 29.7143 4.28571 27.9623 4.28571 25.8091C4.28571 23.656 6.03657 21.904 8.19086 21.904C10.3451 21.904 12.096 23.656 12.096 25.8091C12.096 27.9623 10.344 29.7143 8.19086 29.7143ZM15.1931 10.344V13.4754C15.1931 13.7785 15.3135 14.0692 15.5279 14.2836C15.7422 14.4979 16.0329 14.6183 16.336 14.6183C16.6391 14.6183 16.9298 14.4979 17.1441 14.2836C17.3584 14.0692 17.4789 13.7785 17.4789 13.4754V10.344H22.5223C23.152 10.344 23.6651 10.8571 23.6651 11.4869V24.6663H14.2709C14.0387 23.4428 13.4436 22.3176 12.563 21.437C11.6824 20.5564 10.5572 19.9613 9.33371 19.7291V10.3429L15.1931 10.344Z"
                fill="#F3F4F5" />
            <path
                d="M9.35661 25.8194C9.35661 25.5163 9.23621 25.2256 9.02188 25.0113C8.80755 24.7969 8.51686 24.6765 8.21376 24.6765H8.20233C7.57033 24.6765 7.06519 25.1885 7.06519 25.8194C7.06519 26.4502 7.58176 26.9622 8.21376 26.9622C8.84576 26.9622 9.35661 26.4502 9.35661 25.8194Z"
                fill="#F3F4F5" />
        </svg>
</a>
<!-- Cart Items -->
<!-- Cart Items -->
<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg dropdown-menu-all p-0 border-radius-16">
<div class="dropdown-menu-cart-rel w-100">
    <svg width="38" height="23" class="dropdown-menu-rel-icon" viewBox="0 0 38 23" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M16.3542 2.81881C17.0675 1.99522 17.5685 1.41855 18.0015 1.04108C18.424 0.672893 18.7148 0.555051 19 0.555051C19.2852 0.555051 19.576 0.672893 19.9985 1.04108C20.4315 1.41855 20.9325 1.99522 21.6458 2.81881L33.6748 16.7087C34.9216 18.1485 35.8186 19.1862 36.3455 20.008C36.8781 20.8386 36.9254 21.2858 36.7712 21.6233C36.617 21.9608 36.248 22.2178 35.2714 22.359C34.3053 22.4987 32.9336 22.5 31.029 22.5H6.971C5.0664 22.5 3.69471 22.4987 2.72859 22.359C1.75202 22.2178 1.38303 21.9608 1.22883 21.6233C1.07463 21.2858 1.12192 20.8386 1.6545 20.008C2.18138 19.1862 3.07839 18.1485 4.32524 16.7087L16.3542 2.81881Z" fill="white" stroke="#DBDCDF"/>
    </svg>
</div>
<div class="h-100 w-100 stop-propagation border-radius-12 dropdown-menu-in">

    @if (isset($carts) && count($carts) > 0)
        <div class="fs-16 fw-700 font-prompt text-soft-dark pt-4 pb-2 mx-4 border-bottom" style="border-color: #e5e5e5 !important;">
            {{ translate('Cart Items') }}
        </div>
        <!-- Cart Products -->
        <ul class="overflow-auto c-scrollbar-light list-group list-group-flush mx-1 cart-drop-inside">
            @foreach ($carts as $key => $cartItem)
                @php
                    $product = get_single_product($cartItem['product_id']);
                @endphp
                @if ($product != null)
                    <li class="list-group-item border-0 hov-scale-img">
                        <span class="d-flex align-items-center">
                            <a href="{{ route('product', $product->slug) }}"
                                class="text-reset d-flex align-items-center flex-grow-1">
                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ asset('/public' . $product->getFirstImage()) }}"
                                    class="img-fit
                                    lazyload size-60px has-transition border-radius-8px" alt="{{ $product->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                <span class="minw-0 pl-2 flex-grow-1">
                                    <span class="fw-700 fs-13 text-dark text-truncate-2 font-prompt pt-1"
                                        title="{{ $product->getTranslation('name') }}">
                                        {{ $cartItem->is_sample === 1 ? __("Sample of") . " " . $product->getTranslation('name') : $product->getTranslation("name") }}
                                    </span>
                                    <span class="fs-14 fw-400 text-secondary font-prompt">{{ $cartItem['quantity'] }}x</span>
                                    <span
                                        class="fs-14 fw-400 text-secondary font-prompt">ADE {{ cart_product_price($cartItem, $product) }}</span>
                                </span>
                            </a>
                            <span class="">
                                <button onclick="removeFromCart({{ $cartItem['id'] }})"
                                    class="btn btn-sm btn-icon stop-propagation cart-drop-rem">
                                    <!--<i class="la la-close fs-18 fw-600 text-secondary"></i>-->
                                    <svg width="22" height="22" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M26.25 7.4751C22.0875 7.0626 17.9 6.8501 13.725 6.8501C11.25 6.8501 8.775 6.9751 6.3 7.2251L3.75 7.4751" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.625 6.2125L10.9 4.575C11.1 3.3875 11.25 2.5 13.3625 2.5H16.6375C18.75 2.5 18.9125 3.4375 19.1 4.5875L19.375 6.2125" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M23.563 11.4248L22.7505 24.0123C22.613 25.9748 22.5005 27.4998 19.013 27.4998H10.988C7.50049 27.4998 7.38799 25.9748 7.25049 24.0123L6.43799 11.4248" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12.9126 20.625H17.0751" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M11.875 15.625H18.125" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                </button>
                            </span>
                        </span>
                    </li>
                @endif
            @endforeach
        </ul>
        <!-- Subtotal -->
        <div class="px-3 py-2 fs-15 border-top d-flex justify-content-between mx-4"
            style="border-color: #e5e5e5 !important;">
            <span class="fs-16 fw-400 text-secondary font-prompt">{{ translate('Subtotal') }}</span>
            <span class="fs-16 fw-700 text-dark font-prompt">{{ single_price($total) }}</span>
        </div>
        <!-- View cart & Checkout Buttons -->
        <div class="py-3 text-center border-top mx-4" style="border-color: #e5e5e5 !important;">
            <div class="row gutters-10 justify-content-center">
                @if (Auth::check())
                <div class="col-sm-6">
                    <a href="{{ route('checkout.shipping_info') }}"
                        class="btn btn-secondary-base cart-drop-btn-vcart text-white border-radius-12 fs-18 font-prompt py-2">
                        {{ translate('Checkout') }}
                    </a>
                </div>
                <div class="col-sm-6 mb-2">
                    <a href="{{ route('cart') }}" class="btn btn-white cart-drop-btn-checkout text-secondary-base border-radius-12 fs-18 font-prompt py-2">
                        {{ translate('View cart') }}
                    </a>
                </div>
                @else
                <div class="col-sm-6 mb-2">
                    <a href="{{ route('cart') }}" class="btn btn-secondary-base cart-drop-btn-vcart text-white border-radius-12 fs-18 font-prompt py-2">
                        {{ translate('View cart') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center p-3">
            <!--<i class="las la-frown la-3x opacity-60 mb-3"></i>-->
            <svg width="64" height="64" class="mb-3" viewBox="0 0 64 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.8569 44.388C8.66712 44.9902 5.78736 46.6863 3.714 49.184C1.64065 51.6817 0.503615 54.8245 0.498901 58.0706C0.502985 61.7636 1.97186 65.3043 4.58325 67.9157C7.19464 70.527 10.7353 71.9959 14.4283 72C17.6701 71.9946 20.8088 70.8602 23.3049 68.7916C25.801 66.723 27.4985 63.8496 28.1058 60.6651L61.1486 60.642C61.8306 60.642 62.4846 60.3711 62.9669 59.8888C63.4491 59.4066 63.72 58.7526 63.72 58.0706C63.72 57.3886 63.4491 56.7345 62.9669 56.2523C62.4846 55.7701 61.8306 55.4991 61.1486 55.4991H54.3883V25.8454C54.3883 21.5923 50.9272 18.1311 46.674 18.1311H16.9998V12.6386C16.9964 9.28721 15.6633 6.07413 13.2933 3.7046C10.9233 1.33508 7.70997 0.00272295 4.35862 0C3.67663 0 3.02258 0.270917 2.54034 0.753154C2.0581 1.23539 1.78719 1.88944 1.78719 2.57143C1.78719 3.25341 2.0581 3.90747 2.54034 4.3897C3.02258 4.87194 3.67663 5.14286 4.35862 5.14286C6.34642 5.14422 8.25246 5.9343 9.65829 7.33965C11.0641 8.745 11.8549 10.6508 11.8569 12.6386V44.388ZM14.4283 66.8571C9.58119 66.8571 5.64176 62.9151 5.64176 58.0706C5.64176 53.226 9.58119 49.284 14.4283 49.284C19.2755 49.284 23.2149 53.226 23.2149 58.0706C23.2149 62.9151 19.2729 66.8571 14.4283 66.8571ZM30.1835 23.274V30.3197C30.1835 31.0017 30.4544 31.6558 30.9366 32.138C31.4189 32.6202 32.0729 32.8911 32.7549 32.8911C33.4369 32.8911 34.0909 32.6202 34.5732 32.138C35.0554 31.6558 35.3263 31.0017 35.3263 30.3197V23.274H46.674C48.0909 23.274 49.2455 24.4286 49.2455 25.8454V55.4991H28.1083C27.5859 52.7463 26.2469 50.2145 24.2657 48.2332C22.2844 46.252 19.7526 44.913 16.9998 44.3906V23.2714L30.1835 23.274Z" fill="#DBDCDF"/>
                <path d="M17.0531 58.0939C17.0531 57.4119 16.7821 56.7579 16.2999 56.2756C15.8177 55.7934 15.1636 55.5225 14.4816 55.5225H14.4559C13.0339 55.5225 11.8973 56.6745 11.8973 58.0939C11.8973 59.5133 13.0596 60.6653 14.4816 60.6653C15.9036 60.6653 17.0531 59.5133 17.0531 58.0939Z" fill="#DBDCDF"/>
                </svg>
            <br/>
            <span class="cart-drop-empty fs-18 font-prompt-md">{{ translate('Your Cart is empty') }}</span>
        </div>
    @endif
</div>
</div>
