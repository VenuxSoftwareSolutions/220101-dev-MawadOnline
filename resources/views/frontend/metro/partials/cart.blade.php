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
<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg p-0 stop-propagation rounded-0">

    @if (isset($carts) && count($carts) > 0)
        <div class="fs-16 fw-700 text-soft-dark pt-4 pb-2 mx-4 border-bottom" style="border-color: #e5e5e5 !important;">
            {{ translate('Cart Items') }}
        </div>
        <!-- Cart Products -->
        <ul class="h-360px overflow-auto c-scrollbar-light list-group list-group-flush mx-1">
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
                                    lazyload size-60px has-transition" alt="{{ $product->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                <span class="minw-0 pl-2 flex-grow-1">
                                    <span class="fw-700 fs-13 text-dark mb-2 text-truncate-2"
                                        title="{{ $product->getTranslation('name') }}">
                                        {{ $cartItem->is_sample === 1 ? __("Sample of") . " " . $product->getTranslation('name') : $product->getTranslation("name") }}
                                    </span>
                                    <span class="fs-14 fw-400 text-secondary">{{ $cartItem['quantity'] }}x</span>
                                    <span
                                        class="fs-14 fw-400 text-secondary">{{ cart_product_price($cartItem, $product) }}</span>
                                </span>
                            </a>
                            <span class="">
                                <button onclick="removeFromCart({{ $cartItem['id'] }})"
                                    class="btn btn-sm btn-icon stop-propagation">
                                    <i class="la la-close fs-18 fw-600 text-secondary"></i>
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
            <span class="fs-14 fw-400 text-secondary">{{ translate('Subtotal') }}</span>
            <span class="fs-16 fw-700 text-dark">{{ single_price($total) }}</span>
        </div>
        <!-- View cart & Checkout Buttons -->
        <div class="py-3 text-center border-top mx-4" style="border-color: #e5e5e5 !important;">
            <div class="row gutters-10 justify-content-center">
                <div class="col-sm-6 mb-2">
                    <a href="{{ route('cart') }}" class="btn btn-secondary-base btn-sm btn-block rounded-4 text-white">
                        {{ translate('View cart') }}
                    </a>
                </div>
                @if (Auth::check())
                    <div class="col-sm-6">
                        <a href="{{ route('checkout.shipping_info') }}"
                            class="btn btn-primary btn-sm btn-block rounded-4">
                            {{ translate('Checkout') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center p-3">
            <i class="las la-frown la-3x opacity-60 mb-3"></i>
            <h3 class="h6 fw-700">{{ translate('Your Cart is empty') }}</h3>
        </div>
    @endif
</div>
