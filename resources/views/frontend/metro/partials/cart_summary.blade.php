<div class="card border shadow-none border-radius-16">
    <div class="card-header pt-4 pb-1 border-bottom-0">
        <h3 class="fs-16 font-prompt-md dark-c3 mb-0">{{ translate('Summary') }}</h3>
        <div class="text-right">
            <!-- Items Count -->
            <span class="badge badge-inline badge-primary fs-13 border-radius-6 px-2 font-prompt">
                {{ count($carts) . " " . translate('Items') }}
            </span>

            <!-- Minimum Order Amount -->
            @php
                $coupon_discount = 0;
            @endphp
            @if (Auth::check() && get_setting('coupon_system') == 1)
                @php
                    $coupon_code = null;
                @endphp

                @foreach ($carts as $key => $cartItem)
                    @php
                        $product = get_single_product($cartItem['product_id']);
                    @endphp
                    @if ($cartItem->coupon_applied == 1)
                        @php
                            $coupon_code = $cartItem->coupon_code;
                            break;
                        @endphp
                    @endif
                @endforeach

                @php
                    $coupon_discount = carts_coupon_discount($coupon_code);
                @endphp
            @endif

            @php $subtotal_for_min_order_amount = 0; @endphp

            @foreach ($carts as $key => $cartItem)
                @php
                    $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity'];
                @endphp
            @endforeach

            @if (get_setting('minimum_order_amount_check') == 1 &&
                    $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                <span class="badge badge-inline badge-primary fs-12 rounded-0 px-2">
                    {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
                </span>
            @endif
        </div>
    </div>

    <!-- Club point -->
    @if (addon_is_activated('club_point'))
        <div class="px-4 pt-1 w-100 d-flex align-items-center justify-content-between">
            <h3 class="fs-14 fw-700 mb-0">{{ translate('Total Clubpoint') }}</h3>
            <div class="text-right">
                <span class="badge badge-inline badge-secondary-base fs-12 rounded-0 px-2 text-white">
                    @php
                        $total_point = 0;
                    @endphp
                    @foreach ($carts as $key => $cartItem)
                        @php
                            $product = get_single_product($cartItem['product_id']);
                            $total_point += $product->earn_point * $cartItem['quantity'];
                        @endphp
                    @endforeach

                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"
                        class="mr-2">
                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6" r="6"
                                transform="translate(973 633)" fill="#fff" />
                            <g id="Group_23920" data-name="Group 23920" transform="translate(973 633)">
                                <path id="Path_28698" data-name="Path 28698" d="M7.667,3H4.333L3,5,6,9,9,5Z"
                                    transform="translate(0 0)" fill="#f3af3d" />
                                <path id="Path_28699" data-name="Path 28699" d="M5.33,3h-1L3,5,6,9,4.331,5Z"
                                    transform="translate(0 0)" fill="#f3af3d" opacity="0.5" />
                                <path id="Path_28700" data-name="Path 28700" d="M12.666,3h1L15,5,12,9l1.664-4Z"
                                    transform="translate(-5.995 0)" fill="#f3af3d" />
                            </g>
                        </g>
                    </svg>
                    {{ $total_point }}
                </span>
            </div>
        </div>
    @endif

    <div class="card-body">
        <!-- Products Info -->
        <table class="table">
            <thead>
                <tr>
                    <th class="product-name border-top-0 border-bottom-1 pl-0 fs-13 opacity-60 font-prompt dark-c3">
                        {{ translate('Product') }}</th>
                    <th class="product-total text-right border-top-0 border-bottom-1 pr-0 fs-13 opacity-60 font-prompt dark-c3">
                        {{ translate('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                    $tax = 0;
                    $shipping = 0;
                    $product_shipping_cost = 0;
                    $shipping_region = $shipping_info['city'];
                @endphp
                @foreach ($carts as $key => $cartItem)
                    @php
                        $product = get_single_product($cartItem['product_id']);
                        $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                        $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                        $product_shipping_cost = $cartItem['shipping_cost'];

                        $shipping += $product_shipping_cost;

                        $product_name_with_choice = $cartItem->is_sample === 1 ? __("Sample of") . " " . $product->getTranslation('name') : $product->getTranslation('name');

                        if ($cartItem['variant'] != null) {
                            $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variant'];
                        }
                    @endphp
                    <tr class="cart_item">
                        <td class="product-name pl-0 fs-14 font-prompt dark-c3 border-top-0 border-bottom">
                            {{ $product_name_with_choice }}
                            <strong class="product-quantity">
                                × {{ $cartItem['quantity'] }}
                            </strong>
                        </td>
                        <td class="product-total text-right pr-0 fs-14 text-primary font-prompt dark-c3 border-top-0 border-bottom">
                            <span
                                class="pl-4 pr-0 font-prompt-md" id="product_{{ $cartItem->product->id }}">{{ single_price(cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']) }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <input type="hidden" id="sub_total" value="{{ $subtotal }}">

        <table class="table" style="margin-top: 2rem!important;">
            <tfoot>
                @if(count($ordersDiscounts) > 0)
                    <tr class="orders-discounts__clz">
                        <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">
                            <a class="toggle-discounts mr-1" data-target=".ordersDiscountsDetails" href="#" data-toggle="collapse" aria-expanded="false" aria-controls="ordersDiscountsDetails">+</a>
                            <span>{{ translate('Order discount') }}</span>
                        </th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                            <span class="fw-600">-{{ single_price(array_sum($ordersDiscounts)) }}</span>
                        </td>
                    </tr>
                    @foreach($ordersDiscounts as $vendor_id => $discount)
                        <tr class="collapse ordersDiscountsDetails">
                            <th class="pl-3 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">
                                <span>{{ get_shop_by_user_id($vendor_id)->name }}</span>
                            </th>
                            <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                              <div class="p-1">
                                  -{{ single_price($discount) }}
                              </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                <!-- Subtotal -->
                <tr class="cart-subtotal">
                    <th class="pl-0 fs-14 pt-0 pb-2 dark-c3 font-prompt border-top-0">{{ translate('Subtotal') }}</th>
                    <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                        <span class="font-prompt">{{ single_price($subtotal) }}</span>
                    </td>
                </tr>
                <!-- Tax -->
<<<<<<< HEAD
                <tr class="cart-tax">
                    <th class="pl-0 fs-14 pt-0 pb-2 dark-c3 font-prompt border-top-0">{{ translate('Tax') }}</th>
=======
                <tr class="d-none cart-tax">
                    <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Tax') }}</th>
>>>>>>> 006ddc7f8f49066beb87496942a2d5f5b59eac47
                    <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                        <span class="font-prompt">{{ single_price($tax) }}</span>
                    </td>
                </tr>
                <!-- Total Shipping -->
                <tr class="cart-shipping">
                    <th class="pl-0 fs-14 pt-0 pb-2 dark-c3 font-prompt border-top-0">{{ translate('Total Shipping') }}
                    </th>
                    <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                        <span class="font-prompt">{{ single_price($shipping) }}</span>
                    </td>
                </tr>
                <!-- Redeem point -->
                @if (Session::has('club_point'))
                    <tr class="cart-shipping">
                        <th class="pl-0 fs-14 pt-0 pb-2 dark-c3 font-prompt border-top-0">{{ translate('Redeem point') }}
                        </th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 font-prompt text-primary border-top-0">
                            <span class="font-prompt">{{ single_price(Session::get('club_point')) }}</span>
                        </td>
                    </tr>
                @endif
                <!-- Coupon Discount -->
                @if ($coupon_discount > 0)
                    <tr class="cart-shipping">
                        <th class="pl-0 fs-14 pt-0 pb-2 dark-c3 font-prompt border-top-0">
                            {{ translate('Coupon Discount') }}</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 font-prompt text-primary border-top-0">
                            <span class="font-prompt">{{ single_price($coupon_discount) }}</span>
                        </td>
                    </tr>
                @endif

                @php
                    if (Session::has('club_point')) {
                        $total -= Session::get('club_point');
                    }
                    if ($coupon_discount > 0) {
                        $total -= $coupon_discount;
                    }
                @endphp
                <!-- Total -->
                <tr class="cart-total">
                    <th class="pl-0 fs-16 dark-c3 font-prompt"><span class="strong-600">{{ translate('Total') }}</span>
                    </th>
                    <td class="text-right pr-0 fs-16 font-prompt-md text-primary">
                        <strong><span>{{ single_price($total) }}</span></strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Remove Redeem Point -->
        @if (addon_is_activated('club_point'))
            @if (Session::has('club_point'))
                <div class="mt-3">
                    <form class="" action="{{ route('checkout.remove_club_point') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <div class="form-control">{{ Session::get('club_point') }}</div>
                            <div class="input-group-append">
                                <button type="submit"
                                    class="btn btn-primary">{{ translate('Remove Redeem Point') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        @endif

        <!-- Coupon System -->
        @if (Auth::check() && get_setting('coupon_system') == 1)
            @if ($coupon_discount > 0 && $coupon_code)
                <div class="mt-3">
                    <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <div class="form-control">{{ $coupon_code }}</div>
                            <div class="input-group-append">
                                <button type="button" id="coupon-remove"
                                    class="btn btn-primary">{{ translate('Change Coupon') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-3">
                    <form id="apply-coupon-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                        <div class="input-group">
                            <input type="text" class="form-control rounded-0" name="code"
                                onkeydown="return event.key != 'Enter';"
                                placeholder="{{ translate('Put coupon code here') }}" required>
                            <div class="input-group-append">
                                <button type="button" id="coupon-apply"
                                    class="btn btn-primary rounded-0">{{ translate('Apply') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        @endif
    </div>
</div>
