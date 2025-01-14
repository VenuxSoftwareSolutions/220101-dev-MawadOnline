<div class="container">
    @if ($carts && count($carts) > 0)
        <div class="row">
            <div class="col-xxl-8 col-xl-10 mx-auto">
                <div class="border bg-white p-3 p-lg-4 text-left border-radius-16">
                    <div class="mb-0 mb-md-4">
                        <!-- Headers -->
                        <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3 dark-c3 fs-12">
                            <div class="col-md-3  fs-15 font-prompt p-0">{{ translate('Product') }}</div>
                            <div class="col-md-2 fs-15 col-md-1 font-prompt p-0">{{ translate('Qty') }}</div>
                            <div class="col-md-2 fs-15 font-prompt p-0">{{ translate('Price') }}</div>
                            <div class="col-md-1 fs-15 font-prompt p-0">{{ translate('Status') }}</div>
                            <div class="col-md-1 fs-15 font-prompt p-0">{{ translate('Tax') }}</div>
                            <div class="col-md-2 fs-15 font-prompt p-0">{{ translate('Subtotal') }}</div>
                            <div class="col-md-1 fs-15 font-prompt p-0"><!--{{ translate('Remove') }}--></div>
                        </div>
                        <!-- Cart Items -->
                        <ul class="list-group list-group-flush">
                            @foreach ($carts as $key => $cartItem)
                                <li class="list-group-item px-0 pt-0">
                                    <div class="row gutters-5 align-items-center">
                                        <!-- Product Image & name -->
                                        <div class="col-6 col-md-3 mb-2 mb-md-0 d-flex justify-content-start p-0">
                                            <span class="mr-2 ml-0 float-left">
                                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ asset('/public' . $data[$key]["product"]->getFirstImage()) }}"
                                                    class="img-fit lazyload size-60px has-transition border-radius-8px"
                                                    alt="{{ $data[$key]["product"]->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </span>
                                            <!--<span class="fs-14 float-left">{{ $data[$key]["is_sample"] === true ? __("Sample of") . " " . $data[$key]["product_name_with_choice"] : $data[$key]["product_name_with_choice"] }}</span>-->
                                            <span class="fs-14 font-prompt-md float-left text-truncate-2">{{ $data[$key]["product"]->getTranslation('name') }}</span>
                                        </div>
                                        <!-- Quantity
                                        <div class="col-md-1 col order-1 order-md-0">
                                            @if ($cartItem['digital'] != 1 && $data[$key]["product"]->auction_product == 0)
                                                <div
                                                    class="d-flex flex-column align-items-start aiz-plus-minus mr-2 ml-0">
                                                    <button class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="plus"
                                                        data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-plus"></i>
                                                    </button>
                                                    <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                                        class="col border-0 text-left px-0 flex-grow-1 fs-14 input-number"
                                                        placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                        min="{{ $data[$key]["product"]->minMaxQuantity()['minFrom'] }}"
                                                        max="{{ $data[$key]["product"]->minMaxQuantity()['maxTo'] }}"
                                                        onchange="updateQuantity({{ $cartItem['id'] }}, this)"
                                                        style="padding-left:0.75rem !important;">
                                                    <button class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="minus"
                                                        data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-minus"></i>
                                                    </button>
                                                </div>
                                            @elseif($data[$key]["product"]->auction_product == 1)
                                                <span class="fw-700 fs-14">1</span>
                                            @endif
                                        </div>
                                         -->


                                        <div class="col-6 product-quantity quantity-pos d-flex align-items-center col-md-2 pr-3 pl-0 order-1 order-md-0">
                                            @if ($cartItem['digital'] != 1 && $data[$key]["product"]->auction_product == 0)
                                                <div class="row no-gutters align-items-center aiz-plus-minus mr-0 mr-md-2 product-quantity-counter col-md-12 p-0">
                                                    <button class="btn col-auto btn-icon btn-sm btn-light rounded-0 quantity-control fs-16 font-prompt-md product-quantity-btn"
                                                            type="button" data-type="minus"
                                                            data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-minus"></i>
                                                    </button>
                                                    <input type="number"
                                                           name="quantity[{{ $cartItem['id'] }}]"
                                                           class="col border-0 text-center flex-grow-1 fs-16 input-number fs-16 font-prompt-md"
                                                           placeholder="1"
                                                           value="{{ $cartItem['quantity'] }}"
                                                           min="{{ $data[$key]["product"]->minMaxQuantity()['minFrom'] }}"
                                                           max="{{ $data[$key]["product"]->minMaxQuantity()['maxTo'] }}"
                                                           lang="en"
                                                           onchange="updateQuantity({{ $cartItem['id'] }}, this)">
                                                    <button class="btn col-auto btn-icon btn-sm btn-light rounded-0 quantity-control fs-16 font-prompt-md product-quantity-btn"
                                                            type="button" data-type="plus"
                                                            data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-plus"></i>
                                                    </button>
                                                    <input type="hidden" value="13" name="variationId" id="variationId">
                                                </div>
                                            @elseif($data[$key]["product"]->auction_product == 1)
                                                <span class="fw-700 fs-14">1</span>
                                            @endif
                                            <div class="available-amount opacity-60"></div>
                                        </div>

                                        <!-- Price -->
                                        <div class="col-6 col-md-2 order-2 order-md-0 my-3 my-md-0 p-0">
                                            <span
                                                class="opacity-60 fs-12 d-block d-md-none font-prompt">{{ translate('Price') }}</span>
                                            <span
                                                class="fw-700 fs-16 dark-c3 font-prompt">AED{{ cart_product_price($cartItem, $data[$key]["product"], true, false) }}</span>
                                        </div>
                                         <!-- Stock Status -->
                                         <div class="col-6 col-md-1 order-2 order-md-0 my-3 my-md-0 p-0">
                                            <span
                                                class="opacity-60 fs-12 d-block d-md-none font-prompt">{{ translate('Status') }}</span>
                                            <span
                                                class="fs-16 font-prompt-md {{ $data[$key]["is_sample"] === false && $data[$key]["stockStatus"] == 'Out of Stock' ? 'text-danger' : 'text-success' }}">{{ $data[$key]["is_sample"] === true ? __("Sample available") : $data[$key]["stockStatus"] }}</span>
                                            @if ($data[$key]["is_sample"] === false && $data[$key]["stockAlert"])
                                                <span class="badge badge-warning font-prompt">{{ $data[$key]["stockAlert"] }}</span>
                                            @endif
                                        </div>
                                        <!-- Tax -->
                                        <div class="col-6 col-md-1 order-3 order-md-0 my-3 my-md-0 p-0">
                                            <span
                                                class="opacity-60 fs-12 d-block d-md-none font-prompt">{{ translate('Tax') }}</span>
                                            <span class="fw-700 fs-14 font-prompt">0</span>
                                        </div>
                                        <!-- Total -->
                                        <div class="col-6 col-md-2 order-4 order-md-0 my-3 my-md-0 p-0">
                                            <span
                                                class="opacity-60 fs-12 d-block d-md-none font-prompt">{{ translate('Total') }}</span>
                                            <span
                                                class="fw-700 fs-16 text-primary font-prompt">{{ single_price(cart_product_price($cartItem, $data[$key]["product"], false) * $cartItem['quantity']) }}</span>
                                        </div>
                                        <!-- Remove From Cart big screen -->
                                        <div class="d-none d-md-block col-md-1 order-5 order-md-0 text-right p-0">
                                            <a href="javascript:void(0)"
                                                onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                                class="cart-trash-btn float-left">
                                                <svg width="22" height="22" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M26.25 7.4751C22.0875 7.0626 17.9 6.8501 13.725 6.8501C11.25 6.8501 8.775 6.9751 6.3 7.2251L3.75 7.4751" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M10.625 6.2125L10.9 4.575C11.1 3.3875 11.25 2.5 13.3625 2.5H16.6375C18.75 2.5 18.9125 3.4375 19.1 4.5875L19.375 6.2125" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M23.563 11.4248L22.7505 24.0123C22.613 25.9748 22.5005 27.4998 19.013 27.4998H10.988C7.50049 27.4998 7.38799 25.9748 7.25049 24.0123L6.43799 11.4248" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M12.9126 20.625H17.0751" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M11.875 15.625H18.125" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                            </a>
                                        </div>
                                        <!-- Remove From Cart small screen -->
                                        <div class="col-md-12 order-5 order-md-0 text-right p-0 d-block d-md-none">
                                            <a href="javascript:void(0)"
                                                onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                                class="btn btn-white cart-trash-btn border-radius-12 float-right">
                                                <svg width="22" height="22" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M26.25 7.4751C22.0875 7.0626 17.9 6.8501 13.725 6.8501C11.25 6.8501 8.775 6.9751 6.3 7.2251L3.75 7.4751" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M10.625 6.2125L10.9 4.575C11.1 3.3875 11.25 2.5 13.3625 2.5H16.6375C18.75 2.5 18.9125 3.4375 19.1 4.5875L19.375 6.2125" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M23.563 11.4248L22.7505 24.0123C22.613 25.9748 22.5005 27.4998 19.013 27.4998H10.988C7.50049 27.4998 7.38799 25.9748 7.25049 24.0123L6.43799 11.4248" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M12.9126 20.625H17.0751" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M11.875 15.625H18.125" stroke="#3A3B40" stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                <span class="font-prompt fs-14 dark-c3 chechout-btn-rem-txt">Remove</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if ($data[$key]["is_sample"] === false && count($data[$key]["outOfStockItems"]) > 0)
                            <div class="alert alert-warning mt-3">
                                {{ translate('Some products were out of stock and have been moved to your Wishlist.') }}
                            </div>
                        @endif
                    </div>

                    <div class="px-0 py-2 mb-4 border-top d-flex justify-content-between">
                        <span class="opacity-80 dark-c3 fs-16 font-prompt">{{ translate('Total') }}</span>
                        <span class="fs-16 dark-c3 font-prompt-md">{{ single_price($data[$key]["total"]) }}</span>
                    </div>
                    <!--<div class="row align-items-center">-->
                        <div class="col-12 h-50px p-0">
                        <!-- Return to shop -->
                        <div class="col-6 col-md-6 text-center text-md-left order-1 order-md-0 float-left p-0">
                            <a href="{{ route('home') }}" class="btn btn-white cart-drop-btn-checkout text-secondary-base border-radius-12 fs-16 font-prompt py-2 float-left">

                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.9998 19.92L8.47984 13.4C7.70984 12.63 7.70984 11.37 8.47984 10.6L14.9998 4.08002" stroke="#cb774b" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>


                                {{ translate('Return to shop') }}
                            </a>
                        </div>
                        <!-- Continue to Shipping -->
                        <div class="col-6 col-md-6 text-center text-md-right float-right p-0">
                            @if (Auth::check())
                                <a href="{{ route('checkout.shipping_info') }}"
                                    class="btn btn-secondary-base cart-drop-btn-vcart text-white border-radius-12 fs-16 font-prompt py-2 float-right">
                                    {{ translate('Continue to Shipping') }}
                                </a>
                            @else
                                <button class="btn btn-secondary-base cart-drop-btn-vcart text-white border-radius-12 fs-16 font-prompt py-2 float-right"
                                    onclick="showLoginModal()">{{ translate('Continue to Shipping') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="border bg-white p-4">
                    <!-- Empty cart -->
                    <div class="text-center p-3">
                        <svg width="64" height="64" class="mb-3" viewBox="0 0 64 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.8569 44.388C8.66712 44.9902 5.78736 46.6863 3.714 49.184C1.64065 51.6817 0.503615 54.8245 0.498901 58.0706C0.502985 61.7636 1.97186 65.3043 4.58325 67.9157C7.19464 70.527 10.7353 71.9959 14.4283 72C17.6701 71.9946 20.8088 70.8602 23.3049 68.7916C25.801 66.723 27.4985 63.8496 28.1058 60.6651L61.1486 60.642C61.8306 60.642 62.4846 60.3711 62.9669 59.8888C63.4491 59.4066 63.72 58.7526 63.72 58.0706C63.72 57.3886 63.4491 56.7345 62.9669 56.2523C62.4846 55.7701 61.8306 55.4991 61.1486 55.4991H54.3883V25.8454C54.3883 21.5923 50.9272 18.1311 46.674 18.1311H16.9998V12.6386C16.9964 9.28721 15.6633 6.07413 13.2933 3.7046C10.9233 1.33508 7.70997 0.00272295 4.35862 0C3.67663 0 3.02258 0.270917 2.54034 0.753154C2.0581 1.23539 1.78719 1.88944 1.78719 2.57143C1.78719 3.25341 2.0581 3.90747 2.54034 4.3897C3.02258 4.87194 3.67663 5.14286 4.35862 5.14286C6.34642 5.14422 8.25246 5.9343 9.65829 7.33965C11.0641 8.745 11.8549 10.6508 11.8569 12.6386V44.388ZM14.4283 66.8571C9.58119 66.8571 5.64176 62.9151 5.64176 58.0706C5.64176 53.226 9.58119 49.284 14.4283 49.284C19.2755 49.284 23.2149 53.226 23.2149 58.0706C23.2149 62.9151 19.2729 66.8571 14.4283 66.8571ZM30.1835 23.274V30.3197C30.1835 31.0017 30.4544 31.6558 30.9366 32.138C31.4189 32.6202 32.0729 32.8911 32.7549 32.8911C33.4369 32.8911 34.0909 32.6202 34.5732 32.138C35.0554 31.6558 35.3263 31.0017 35.3263 30.3197V23.274H46.674C48.0909 23.274 49.2455 24.4286 49.2455 25.8454V55.4991H28.1083C27.5859 52.7463 26.2469 50.2145 24.2657 48.2332C22.2844 46.252 19.7526 44.913 16.9998 44.3906V23.2714L30.1835 23.274Z" fill="#DBDCDF"></path>
                            <path d="M17.0531 58.0939C17.0531 57.4119 16.7821 56.7579 16.2999 56.2756C15.8177 55.7934 15.1636 55.5225 14.4816 55.5225H14.4559C13.0339 55.5225 11.8973 56.6745 11.8973 58.0939C11.8973 59.5133 13.0596 60.6653 14.4816 60.6653C15.9036 60.6653 17.0531 59.5133 17.0531 58.0939Z" fill="#DBDCDF"></path>
                            </svg>
                        <h3 class="h4 font-prompt-md dark-c3">{{ translate('Your Cart is empty') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    AIZ.extra.plusMinus();
</script>
