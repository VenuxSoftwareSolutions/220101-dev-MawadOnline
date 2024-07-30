@php
    $cart_added = [];
@endphp
<div class="aiz-card-box h-auto bg-white py-2 hov-scale-img">
    <div class="position-relative h-140px h-md-200px img-fit overflow-hidden">
        @php
            $product_url = route('product', $product->slug);
            if ($product->auction_product == 1) {
                $product_url = route('auction-product', $product->slug);
            }
        @endphp
        <!-- Image -->
        <a href="{{ $product_url }}" class="d-block h-100">
            <img class="lazyload mx-auto img-fit has-transition border-radius-8px"
                src="{{ asset('/public/'.$product->getFirstImage()) }}"
                alt="{{ $product->getTranslation('name') }}" title="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        <!-- Discount percentage tag
        @if (discount_in_percentage($product) > 0)
            <span class="absolute-top-left bg-primary ml-1 mt-1 fs-11 fw-700 text-white w-35px text-center"
                style="padding-top:2px;padding-bottom:2px;">-{{ discount_in_percentage($product) }}%</span>
        @endif
        <!-- Wholesale tag
        @if ($product->wholesale_product)
            <span class="absolute-top-left fs-11 text-white fw-700 px-2 lh-1-8 ml-1 mt-1"
                style="background-color: #455a64; @if (discount_in_percentage($product) > 0) top:25px; @endif">
                {{ translate('Wholesale') }}
            </span>
        @endif-->
        @if ($product->auction_product == 0)
            <!-- wishlisht & compare icons -->
            <div class="absolute-top-right aiz-p-hov-icon">
                <a href="javascript:void(0)" class="hov-svg-white border-radius-8px" onclick="addToWishList({{ $product->id }})"
                    data-toggle="tooltip" data-title="{{ translate('Add to wishlist') }}" data-placement="left">

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                        <path d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8l0-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5l0 3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20-.1-.1s0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5l0 3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2l0-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"
                        fill="#333"/>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="hov-svg-white border-radius-8px" onclick="addToCompare({{ $product->id }})"
                    data-toggle="tooltip" data-title="{{ translate('Add to compare') }}" data-placement="left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512">
                    <path d="M438.6 150.6c12.5-12.5 12.5-32.8 0-45.3l-96-96c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.7 96 32 96C14.3 96 0 110.3 0 128s14.3 32 32 32l306.7 0-41.4 41.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l96-96zm-333.3 352c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 416 416 416c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0 41.4-41.4c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-96 96c-12.5 12.5-12.5 32.8 0 45.3l96 96z"
                    fill="#555"/>
                    </svg>
                </a>
            </div>
            <div class="absolute-top-left aiz-p-hov-span @if($product->discount !== Null) aiz-p-hov-span-bg-orange @else aiz-p-hov-span-bg-dark @endif">
                <a href="javascript:void(0)" class="hov-svg-white border-radius-8px" "
                    data-toggle="tooltip" data-placement="left">
                    <span>
                        @if($product->discount !== Null)
                            -{{ $product->discount }}%
                        @else
                            Featured
                        @endif
                    </span>
                </a>
            </div>
            <div class="absolute-bottom-left-rating aiz-p-hov-span-rating @if($product->discount !== Null) aiz-p-hov-span-bg-white @else aiz-p-hov-span-bg-dark @endif">
                <a href="javascript:void(0)" class="hov-svg-white border-radius-8px" "
                    data-toggle="tooltip" data-placement="left">
                    <span>
                        4.0
                        <svg xmlns="http://www.w3.org/2000/svg" class="rating-star" width="16" height="16" viewBox="0 0 620 620">
                             <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"
                             transform="translate(-2.037 -2.038)" fill="#FFB800"/>
                        </svg>
                        <span class="rating-content">(1.5k)</span>
                    </span>
                </a>
            </div>
            <!-- add to cart
            <a class="cart-btn absolute-bottom-left w-100 h-35px aiz-p-hov-icon text-white fs-13 fw-700 d-flex flex-column justify-content-center align-items-center @if (in_array($product->id, $cart_added)) active @endif"
                href="javascript:void(0)"
                @if (Auth::check()) onclick="showAddToCartModal({{ $product->id }})" @else onclick="showLoginModal()" @endif>
                <span class="cart-btn-text">
                    {{ translate('Add to Cart') }}
                </span>
                <span><i class="las la-2x la-shopping-cart"></i></span>
            </a>
             -->
        @endif
        @if (
            $product->auction_product == 1 &&
                $product->auction_start_date <= strtotime('now') &&
                $product->auction_end_date >= strtotime('now'))
            <!-- Place Bid -->
            @php
                $carts = get_user_cart();
                if (count($carts) > 0) {
                    $cart_added = $carts->pluck('product_id')->toArray();
                }
                $highest_bid = $product->bids->max('amount');
                $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
            @endphp
            <a class="cart-btn absolute-bottom-left w-100 h-35px aiz-p-hov-icon text-white fs-13 fw-700 d-flex flex-column justify-content-center align-items-center @if (in_array($product->id, $cart_added)) active @endif"
                href="javascript:void(0)" onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }})">
                <span class="cart-btn-text">{{ translate('Place Bid') }}</span>
                <br>
                <span><i class="las la-2x la-gavel"></i></span>
            </a>
        @endif
    </div>

    <div class="p-1 pt-2 text-left">
        <!-- Product name -->
        <h3 class="fw-600 fs-14 text-truncate-2 lh-1-4 mb-0 h-25px text-center">
            <a href="{{ $product_url }}" class="d-block text-reset"
                title="{{ $product->getTranslation('name') }}">{{ $product->getTranslation('name') }}</a>
        </h3>
        <div class="fs-14 d-flex justify-content-center">
            @if ($product->auction_product == 0)
                <!-- price -->
                <div class="">
                    <span class="fw-700 text-primary mr-1">{{ home_discounted_base_price($product) }}</span>
                </div>
                <!-- Previous price -->
                @if (home_base_price($product) != home_discounted_base_price($product))
                    <div class="">
                        <del class="fw-400 text-secondary">{{ home_discounted_base_price($product) }}</del>
                    </div>
                @endif
            @endif
            @if ($product->auction_product == 1)
                <!-- Bid Amount -->
                <div class="">
                    <span class="fw-700 text-primary">{{ single_price($product->starting_bid) }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
