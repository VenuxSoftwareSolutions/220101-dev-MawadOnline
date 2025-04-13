@php
    $cart_added = [];
@endphp
<div class="aiz-card-box h-auto bg-white" style="margin:6px 0px;">
    <div class="position-relative h-264px img-fit overflow-hidden">
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

                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.5167 17.8417C10.2333 17.9417 9.76666 17.9417 9.48332 17.8417C7.06666 17.0167 1.66666 13.5751 1.66666 7.74174C1.66666 5.16674 3.74166 3.0834 6.29999 3.0834C7.81666 3.0834 9.15832 3.81674 9.99999 4.95007C10.8417 3.81674 12.1917 3.0834 13.7 3.0834C16.2583 3.0834 18.3333 5.16674 18.3333 7.74174C18.3333 13.5751 12.9333 17.0167 10.5167 17.8417Z" stroke="#3A3B40" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                </a>
                <a href="javascript:void(0)" class="hov-svg-white border-radius-8px" onclick="addToCompare({{ $product->id }})"
                    data-toggle="tooltip" data-title="{{ translate('Add to compare') }}" data-placement="left">
                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.4 9.2083L17.5 6.10828L14.4 3.0083" stroke="#3A3B40" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.5 6.10828H17.5" stroke="#3A3B40" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5.59998 11.7916L2.5 14.8917L5.59998 17.9916" stroke="#292D32" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.5 14.8917H2.5" stroke="#292D32" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                </a>
            </div>
            {{-- <div class="absolute-top-left aiz-p-hov-span font-prompt @if($product->discount !== Null) aiz-p-hov-span-bg-orange @else aiz-p-hov-span-bg-dark @endif">
                <a href="javascript:void(0)" class="hov-svg-white border-radius-8px" data-toggle="tooltip" data-placement="left">
                    <span>
                        @if($product->discount !== Null)
                            -{{ $product->discount }}%
                        @else
                            Featured
                        @endif
                    </span>
                </a>
            </div> --}}

            @if($product->featured)
            <div class="absolute-top-left aiz-p-hov-span font-prompt @if($product->discount !== Null) aiz-p-hov-span-bg-orange @else aiz-p-hov-span-bg-dark @endif">
                <a href="javascript:void(0)" class="hov-svg-white border-radius-8px" data-toggle="tooltip" data-placement="left">
                    <span>
                            {{ __("Featured") }}
                    </span>
                </a>
            </div>
            @endif

            @php
                $totalRating = $product->reviews->count();
            @endphp
            <div class="absolute-bottom-left-rating aiz-p-hov-span-rating font-prompt">
                <a href="javascript:void(0)" class="hov-svg-white border-radius-8px" "
                    data-toggle="tooltip" data-placement="left">
                    <span>
                        {{ $totalRating > 0 ? number_format($product->reviews->sum('rating') / $totalRating, 1) : number_format(0, 1) }}
                        <svg width="17" height="17" class="rating-star" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.3334 8.41623C18.4167 7.99956 18.0834 7.49956 17.6667 7.49956L12.9167 6.8329L10.7501 2.49956C10.6667 2.3329 10.5834 2.24956 10.4167 2.16623C10.0001 1.91623 9.50008 2.0829 9.25008 2.49956L7.16675 6.8329L2.41675 7.49956C2.16675 7.49956 2.00008 7.5829 1.91675 7.74956C1.58341 8.0829 1.58341 8.58289 1.91675 8.91623L5.33341 12.2496L4.50008 16.9996C4.50008 17.1662 4.50008 17.3329 4.58341 17.4996C4.83341 17.9162 5.33341 18.0829 5.75008 17.8329L10.0001 15.5829L14.2501 17.8329C14.3334 17.9162 14.5001 17.9162 14.6667 17.9162C14.7501 17.9162 14.7501 17.9162 14.8334 17.9162C15.2501 17.8329 15.5834 17.4162 15.5001 16.9162L14.6667 12.1662L18.0834 8.8329C18.2501 8.74956 18.3334 8.5829 18.3334 8.41623Z" fill="#FFB800"/>
                            </svg>

                        <span class="rating-content">({{$totalRating}})</span>
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
        <h3 class="fw-600 fs-14 text-truncate-2 lh-1-4 mb-0 h-25px text-left">
            <a href="{{ $product_url }}" class="d-block text-reset product-title-card"
                title="{{ $product->getTranslation('name') }}">{{ $product->getTranslation('name') }}</a>
        </h3>
        <div class="fs-14 d-flex justify-content-start">
            {{-- @if ($product->auction_product == 0)
                @if(count($product->getPricingConfiguration()) > 0)
                    @foreach ($product->getPricingConfiguration() as $pricing)
                        @php
                            $date_range = '';
                            if($pricing->discount_start_datetime){
                                $start_date = new DateTime($pricing->discount_start_datetime);
                                $start_date_formatted = $start_date->format('d-m-Y H:i:s');

                                $end_date = new DateTime($pricing->discount_end_datetime);
                                $end_date_formatted = $end_date->format('d-m-Y H:i:s');

                                $date_range = $start_date_formatted.' to '.$end_date_formatted;
                            }
                        @endphp
                        @if ($pricing->discount_amount)

                            @php
                                $disc = (home_discounted_base_price($product,false) - $pricing->discount_amount) ;
                                $formattedDisc = "AED" . number_format($disc, 2, '.', ',');
                            @endphp
                            <!-- price -->
                            <div class="">
                                <span class="fw-700 text-dark mr-1">{{ $formattedDisc }}</span>
                            </div>
                            <!-- Previous price -->
                            <div class="">
                                <del class="fw-400 text-secondary">{{ home_discounted_base_price($product) }}</del>
                            </div>
                        @else
                            <div class="">
                                <span class="fw-700 text-dark mr-1">{{ home_discounted_base_price($product) }}</span>
                            </div>
                        @endif
                    @endforeach
                @endif
            @endif
            @if ($product->auction_product == 1)
                <!-- Bid Amount -->
                <div class="">
                    <span class="fw-700 text-primary">{{ single_price($product->starting_bid) }}</span>
                </div>
            @endif --}}

            @if ($product->auction_product == 0)
                @if($product->getFirstPricingConfiguration() != null)
                    @php
                        // Retrieve dates from the database
                        $discountStart = \Carbon\Carbon::parse($product->getFirstPricingConfiguration()->discount_start_datetime);
                        $discountEnd = \Carbon\Carbon::parse($product->getFirstPricingConfiguration()->discount_end_datetime);

                        // Get today's date
                        $today = \Carbon\Carbon::today();
                    @endphp
                    @if($today->between($discountStart, $discountEnd))
                        @php
                            if($product->getFirstPricingConfiguration()->discount_amount != null){
                                $disc = (home_discounted_base_price($product,false) - $product->getFirstPricingConfiguration()->discount_amount);
                                $formattedDisc = "AED" . number_format($disc, 2, '.', ',');
                            }else{
                                $disc = (home_discounted_base_price($product,false) - $product->getFirstPricingConfiguration()->discount_percentage);
                                $formattedDisc = "AED" . number_format($disc, 2, '.', ',');
                            }

                        @endphp
                        <!-- price -->
                        <div class="">
                            <span class="fw-700 text-dark mr-1">{{ home_discounted_base_price($product) }}</span>
                        </div>
                        <!-- Previous price -->
                        <div class="">
                            <del class="fw-400 text-secondary">{{ "AED" . number_format($product->getFirstPricingConfiguration()->unit_price, 2, '.', ',') }}</del>
                        </div>
                    @else
                        <div class="">
                            <span class="fw-700 text-dark mr-1">{{ "AED" . number_format($product->getFirstPricingConfiguration()->unit_price, 2, '.', ',') }}</span>
                        </div>
                    @endif
                @else
                    @php
                        $priceAfterDiscountVatIncl = \App\Utility\CartUtility::priceProduct($product->id, 1);
                        $priceAfterMwdCommission = calculatePriceWithDiscountAndMwdCommission($product, 1, false);

                        if(isset($mwd_price) === false)
                            $mwd_price = calculatePriceWithDiscountAndMwdCommission($product);
                    @endphp
                    <div class="discounted-price__clz" data-unit_price="{{ $product->unit_price }}" data-product_id="{{ $product->id }}" data-price_after_mwd_commission="{{ $priceAfterMwdCommission }}">
                        <span class="fw-700 text-dark mr-1">{{ single_price($mwd_price) }}</span>
                    </div>

                    @if($priceAfterDiscountVatIncl !== $product->unit_price)
                        <div class="main-price__clz">
                            <del class="fw-400 text-secondary">{{ single_price($priceAfterMwdCommission) }}</del>
                        </div>
                    @endif
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
    <div class="stock-quantity">
            <div class="stock-quantity-content">
                <svg width="20" height="20" class="stock-quantity-icon mr-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.62 16L11.12 17.5L14.37 14.5" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.81 2L5.19 5.63" stroke="#3A3B40" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.19 2L18.81 5.63" stroke="#3A3B40" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 7.84998C2 5.99998 2.99 5.84998 4.22 5.84998H19.78C21.01 5.84998 22 5.99998 22 7.84998C22 9.99998 21.01 9.84998 19.78 9.84998H4.22C2.99 9.84998 2 9.99998 2 7.84998Z" stroke="#3A3B40" stroke-width="1.5"/>
                    <path d="M3.5 10L4.91 18.64C5.23 20.58 6 22 8.86 22H14.89C18 22 18.46 20.64 18.82 18.76L20.5 10" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    @php
                    $totalQuantity = $product->getTotalQuantity();
                @endphp

                <span class="fs-14 stock-quantity-text" style="font-size: 6px">
                    {{ $totalQuantity > 0 ? "Only $totalQuantity left in stock" : 'Out of stock' }}
                </span>
            </div>
    </div>
</div>
