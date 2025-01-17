@extends('frontend.layouts.app')

@section('content')
    <section class="steps-wrapper__clz pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done-mwd">
                            <div class="text-center border border-bottom-6px p-2 text-success-mwd">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 font-prompt-md d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                            </div>
                        </div>
                        <div class="col done-mwd">
                            <div class="text-center border border-bottom-6px p-2 text-success-mwd">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 font-prompt-md d-none d-lg-block">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-truck cart-animate"
                                    style="margin-left: -100px; transition: 2s;"></i>
                                <h3 class="fs-14 font-prompt-md d-none d-lg-block">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                                <h3 class="fs-14 font-prompt-md d-none d-lg-block opacity-50">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                                <h3 class="fs-14 font-prompt-md d-none d-lg-block opacity-50">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="delivery-info-wrapper__clz py-4 gry-bg">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-xl-10 mx-auto">
                    <div class="border bg-white p-4 mb-4 border-radius-16">
                        <form class="form-default" action="{{ route('checkout.store_delivery_info') }}" role="form"
                            method="POST">
                            @csrf

                            @if (!empty($admin_products))
                                <div class="inhouse-products-wrapper__clz card mb-3 border-0 rounded-0 shadow-none">
                                    <div class="card-body p-0">
                                        <!-- Product List -->
                                        <ul class="list-group list-group-flush border p-3 mb-3 border-radius-16">
                                            @php
                                                $physical = false;
                                            @endphp
                                            @foreach ($admin_products as $key => $cartItem)
                                                @php
                                                    $product = get_single_product($cartItem);
                                                    if ($product->digital == 0) {
                                                        $physical = true;
                                                    }
                                                @endphp
                                                <li class="list-group-item">
                                                    <div class="d-flex align-items-center">
                                                        <span class="mr-2 mr-md-3">
                                                            <img src="{{ get_image($product->thumbnail) }}"
                                                                class="img-fit size-60px border-radius-8px"
                                                                alt="{{ $product->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                        </span>
                                                        <span class="fs-15 dark-c3 font-prompt">
                                                            {{ $product->getTranslation('name') }}
                                                            <br>
                                                            @if ($admin_product_variation[$key] != '')
                                                                <span
                                                                    class="fs-13 text-secondary font-prompt">{{ translate('Variation') }}:
                                                                    {{ $admin_product_variation[$key] }}</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="mt-3">
                                                        <h6 class="fs-14 font-prompt-md">{{ translate('Shipping Options') }}</h6>
                                                        @php
                                                            $shippers = [];
                                                            $shippingOptions = $product->shippingOptions($productQtyPanier[$product->id]);
                                                            if ($shippingOptions) {
                                                                $shippers = explode(',', $shippingOptions->shipper);
                                                                $duration = $shippingOptions->estimated_order + $shippingOptions->estimated_shipping;
                                                            }

                                                            $productWeight = getProductWeightGeneralAttribute($product->id);
                                                        @endphp
                                                        <div
                                                            class="border border-radius-12 p-3 mb-2 @if (in_array("third_party", $shippers) && $productWeight === null) d-none @endif">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="shipping_method_{{ $product->id }}"
                                                                        class="fs-14 text-secondary font-prompt">{{ translate('Shipping Method') }} :</label>
                                                                    <script>
                                                                        window.shippingMethodSelectFirstChange_{{ $product->id }} = true;
                                                                    </script>
                                                                    <select data-prod="{{ $product->id }}"
                                                                        name="shipping_method_{{ $product->id }}"
                                                                        id="shipping_method_{{ $product->id }}"
                                                                        class="form-control fs-14 dark-c3 font-prompt border-radius-8px"
                                                                        @if ($shippers_areas->count() > 0) onchange="toggleShippersArea(this, '{{ $product->id }}')" @endif>
                                                                        <option value="">
                                                                            {{ translate('Please choose shipper') }}
                                                                        </option>
                                                                        @foreach ($shippers as $option)
                                                                            <option value="{{ $option }}">
                                                                                {{ ucfirst($option === 'third_party' ? __('MawadOnline 3rd Party Shipping') : $option) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <!-- Checkbox for Shippers Area -->
                                                                <div class="col-md-6"
                                                                    id="shippers_area_container_{{ $product->id }}"
                                                                    style="display: none;">
                                                                    <label
                                                                        class="fs-14 text-secondary font-prompt">{{ translate('Shippers') }}:</label>

                                                                    @foreach ($shippers_areas as $area)
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="shippers_area"
                                                                                id="shippers_area_{{ $product->id }}"
                                                                                value="{{ $area->id }}"
                                                                                @if ($shippers_areas->count() === 1) checked @endif>
                                                                            <label
                                                                                class="form-check-label fs-14 dark-c3 font-prompt"
                                                                                for="shippers_area_{{ $area->id }}">
                                                                                {{ $area->shipper->name }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-md-6">
                                                                    <span
                                                                        class="fs-14 text-secondary font-prompt">{{ translate('Duration') }} :</span>
                                                                    <span id="shipping_duration_{{ $product->id }}"
                                                                        class="fs-14 dark-c3 font-prompt-md">N/A</span>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-1 charge-wrapper-{{ $product->id }}__clz">
                                                                <div class="col-md-12">
                                                                    <span
                                                                        class="fs-14 text-secondary font-prompt">{{ translate('Charge') }} :</span>
                                                                    <span class="fs-14 dark-c3 font-prompt-md"
                                                                        id="charge-result_{{ $product->id }}">N/A</span>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                document.addEventListener("DOMContentLoaded", function() {
                                                                    $("#shipping_method_{{ $product->id }}").on("change", function() {
                                                                        if (["vendor", ""].includes($(this).val()) === false &&
                                                                            shippingMethodSelectFirstChange_{{ $product->id }} === true) {
                                                                            @if ($shippingOptions !== null)
                                                                                $("#charge-result_{{ $product->id }}").html(`
                                                                                    <span class="p-1 bg-black-20 rounded">
                                                                                        <span
                                                                                            class="spinner-border spinner-border-sm"
                                                                                            role="status"
                                                                                            aria-hidden="true"></span>
                                                                                        <span
                                                                                            class="visually-hidden">Loading...</span>
                                                                                    </span>
                                                                                `);

                                                                                $.post("{{ route('user.orders', ['user_id' => auth()->user()->id]) }}", {
                                                                                    product_id: {{ $product->id }},
                                                                                }).then(
                                                                                    function({
                                                                                        error,
                                                                                        data,
                                                                                        message
                                                                                    }) {
                                                                                        shippingMethodSelectFirstChange_{{ $product->id }} = false;
                                                                                        if (error === true) {
                                                                                            throw new Error(message);
                                                                                        } else if (data?.HasErrors === false) {
                                                                                            @php
                                                                                                $quantity = $carts->filter(fn($cart) => $cart->product_id === $product->id)->first()->quantity;
                                                                                                $aramexShippingDuration = getAramexShippingDuration($product, $quantity);
                                                                                            @endphp
                                                                                            $("#shipping_duration_{{ $product->id }}").html(
                                                                                                '{{ $aramexShippingDuration }}');

                                                                                            $("#charge-result_{{ $product->id }}").html(
                                                                                                `${data["TotalAmount"]["Value"]} ${data["TotalAmount"]["CurrencyCode"]}`
                                                                                            ).removeClass("text-dark").addClass("text-success").addClass(
                                                                                                "fw-700");
                                                                                        } else {
                                                                                            $("#charge-result_{{ $product->id }}").html("N/A");
                                                                                            AIZ.plugins.notify('danger', data["Notifications"][0]["Message"]
                                                                                                .split(" - ")[1]);
                                                                                        }
                                                                                    }).catch(() => {
                                                                                    $("#charge-result_{{ $product->id }}").html("N/A");
                                                                                    AIZ.plugins.notify('danger', '{{ __('Something went wrong!') }}')
                                                                                });
                                                                            @else
                                                                                $("#charge-result_{{ $product->id }}").html(
                                                                                    '{{ __('Free (handled by vendor)') }}');
                                                                            @endif
                                                                        } else if (["vendor"].includes($(this).val()) === true) {
                                                                            $("#shipping_duration_{{ $product->id }}").html(
                                                                                '{{ $duration . ' ' . __('days') }}');
                                                                            @if ($shippingOptions !== null && $shippingOptions->paid === 'vendor')
                                                                                $("#charge-result_{{ $product->id }}").html(
                                                                                    '{{ __('Free (handled by vendor)') }}');
                                                                            @elseif ($shippingOptions !== null && $shippingOptions->paid === 'buyer')
                                                                                $("#charge-result_{{ $product->id }}").html(
                                                                                    '{{ formatChargeBasedOnChargeType($shippingOptions, $carts) }}');
                                                                            @endif
                                                                        }
                                                                    });
                                                                });
                                                            </script>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <!-- Choose Delivery Type -->
                                        @if ($physical)
                                            <div class="row pt-3">
                                                <div class="d-none col-md-6">
                                                    <h6 class="fs-14 fw-700 mt-3">{{ translate('Choose Delivery Type') }}
                                                    </h6>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row gutters-5">
                                                        <!-- Home Delivery -->
                                                        @if (get_setting('shipping_type') != 'carrier_wise_shipping')
                                                            <div class="d-none col-6">
                                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                                    <input type="radio"
                                                                        name="shipping_type_{{ get_admin()->id }}"
                                                                        value="home_delivery"
                                                                        onchange="show_pickup_point(this, 'admin')"
                                                                        data-target=".pickup_point_id_admin" checked>
                                                                    <span class="d-flex aiz-megabox-elem rounded-0"
                                                                        style="padding: 0.75rem 1.2rem;">
                                                                        <span
                                                                            class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                        <span
                                                                            class="flex-grow-1 pl-3 fw-600">{{ translate('Home Delivery') }}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <!-- Carrier -->
                                                        @else
                                                            <div class="col-6">
                                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                                    <input type="radio"
                                                                        name="shipping_type_{{ get_admin()->id }}"
                                                                        value="carrier"
                                                                        onchange="show_pickup_point(this, 'admin')"
                                                                        data-target=".pickup_point_id_admin" checked>
                                                                    <span class="d-flex aiz-megabox-elem rounded-0"
                                                                        style="padding: 0.75rem 1.2rem;">
                                                                        <span
                                                                            class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                        <span
                                                                            class="flex-grow-1 pl-3 fw-600">{{ translate('Carrier') }}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endif
                                                        <!-- Local Pickup -->
                                                        @if ($pickup_point_list)
                                                            <div class="col-6">
                                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                                    <input type="radio"
                                                                        name="shipping_type_{{ get_admin()->id }}"
                                                                        value="pickup_point"
                                                                        onchange="show_pickup_point(this, 'admin')"
                                                                        data-target=".pickup_point_id_admin">
                                                                    <span class="d-flex aiz-megabox-elem rounded-0"
                                                                        style="padding: 0.75rem 1.2rem;">
                                                                        <span
                                                                            class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                        <span
                                                                            class="flex-grow-1 pl-3 fw-600">{{ translate('Local Pickup') }}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Pickup Point List -->
                                                    @if ($pickup_point_list)
                                                        <div class="mt-3 pickup_point_id_admin d-none">
                                                            <select class="form-control aiz-selectpicker rounded-0"
                                                                name="pickup_point_id_{{ get_admin()->id }}"
                                                                data-live-search="true">
                                                                <option>{{ translate('Select your nearest pickup point') }}
                                                                </option>
                                                                @foreach ($pickup_point_list as $pick_up_point)
                                                                    <option value="{{ $pick_up_point->id }}"
                                                                        data-content="<span class='d-block'>
                                                                                    <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                                                </span>">
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Carrier Wise Shipping -->
                                            @if (get_setting('shipping_type') == 'carrier_wise_shipping')
                                                <div class="row pt-3 carrier_id_admin">
                                                    @foreach ($carrier_list as $carrier_key => $carrier)
                                                        <div class="col-md-12 mb-2">
                                                            <label class="aiz-megabox d-block bg-white mb-0">
                                                                <input type="radio"
                                                                    name="carrier_id_{{ get_admin()->id }}"
                                                                    value="{{ $carrier->id }}"
                                                                    @if ($carrier_key == 0) checked @endif>
                                                                <span class="d-flex p-3 aiz-megabox-elem rounded-0">
                                                                    <span
                                                                        class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                    <span class="flex-grow-1 pl-3 fw-600">
                                                                        <img src="{{ uploaded_asset($carrier->logo) }}"
                                                                            alt="Image" class="w-50px img-fit">
                                                                    </span>
                                                                    <span
                                                                        class="flex-grow-1 pl-3 fw-700">{{ $carrier->name }}</span>
                                                                    <span
                                                                        class="flex-grow-1 pl-3 fw-600">{{ translate('Transit in') . ' ' . $carrier->transit_time }}</span>
                                                                    <span
                                                                        class="flex-grow-1 pl-3 fw-600">{{ single_price(carrier_base_price($carts, $carrier->id, get_admin()->id)) }}</span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Seller Products -->
                            @if (!empty($seller_products))
                                @foreach ($seller_products as $key => $seller_product)
                                    <div class="card mb-5 border-0 rounded-0 shadow-none">
                                        <div class="card-header py-3 px-0 border-bottom-0">
                                            <h5 class="fs-16 fw-700 text-dark mb-0">{{ get_shop_by_user_id($key)->name }}
                                                {{ translate('Products') }}</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <!-- Product List -->
                                            <ul class="list-group list-group-flush border p-3 mb-3">
                                                @php
                                                    $physical = false;
                                                @endphp
                                                @foreach ($seller_product as $key2 => $cartItem)
                                                    @php
                                                        $product = get_single_product($cartItem);
                                                        if ($product->digital == 0) {
                                                            $physical = true;
                                                        }
                                                    @endphp
                                                    <li class="list-group-item">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mr-2 mr-md-3">
                                                                <img src="{{ get_image($product->thumbnail) }}"
                                                                    class="img-fit size-60px"
                                                                    alt="{{ $product->getTranslation('name') }}"
                                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                            </span>
                                                            <span class="fs-14 fw-400 text-dark">
                                                                {{ $product->getTranslation('name') }}
                                                                <br>
                                                                @if ($seller_product_variation[$key2] != '')
                                                                    <span
                                                                        class="fs-12 text-secondary">{{ translate('Variation') }}:
                                                                        {{ $seller_product_variation[$key2] }}</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <!-- Choose Delivery Type -->
                                            @if ($physical)
                                                <div class="row pt-3">
                                                    <div class="col-md-6">
                                                        <h6 class="fs-14 fw-700 mt-3">
                                                            {{ translate('Choose Delivery Type') }}</h6>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row gutters-5">
                                                            <!-- Home Delivery -->
                                                            @if (get_setting('shipping_type') != 'carrier_wise_shipping')
                                                                <div class="col-6">
                                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                                        <input type="radio"
                                                                            name="shipping_type_{{ $key }}"
                                                                            value="home_delivery"
                                                                            onchange="show_pickup_point(this, {{ $key }})"
                                                                            data-target=".pickup_point_id_{{ $key }}"
                                                                            checked>
                                                                        <span class="d-flex p-3 aiz-megabox-elem rounded-0"
                                                                            style="padding: 0.75rem 1.2rem;">
                                                                            <span
                                                                                class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                            <span
                                                                                class="flex-grow-1 pl-3 fw-600">{{ translate('Home Delivery') }}</span>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                                <!-- Carrier -->
                                                            @else
                                                                <div class="col-6">
                                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                                        <input type="radio"
                                                                            name="shipping_type_{{ $key }}"
                                                                            value="carrier"
                                                                            onchange="show_pickup_point(this, {{ $key }})"
                                                                            data-target=".pickup_point_id_{{ $key }}"
                                                                            checked>
                                                                        <span class="d-flex p-3 aiz-megabox-elem rounded-0"
                                                                            style="padding: 0.75rem 1.2rem;">
                                                                            <span
                                                                                class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                            <span
                                                                                class="flex-grow-1 pl-3 fw-600">{{ translate('Carrier') }}</span>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @endif
                                                            <!-- Local Pickup -->
                                                            @if ($pickup_point_list)
                                                                <div class="col-6">
                                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                                        <input type="radio"
                                                                            name="shipping_type_{{ $key }}"
                                                                            value="pickup_point"
                                                                            onchange="show_pickup_point(this, {{ $key }})"
                                                                            data-target=".pickup_point_id_{{ $key }}">
                                                                        <span class="d-flex p-3 aiz-megabox-elem rounded-0"
                                                                            style="padding: 0.75rem 1.2rem;">
                                                                            <span
                                                                                class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                            <span
                                                                                class="flex-grow-1 pl-3 fw-600">{{ translate('Local Pickup') }}</span>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Pickup Point List -->
                                                        @if ($pickup_point_list)
                                                            <div class="mt-4 pickup_point_id_{{ $key }} d-none">
                                                                <select class="form-control aiz-selectpicker rounded-0"
                                                                    name="pickup_point_id_{{ $key }}"
                                                                    data-live-search="true">
                                                                    <option>
                                                                        {{ translate('Select your nearest pickup point') }}
                                                                    </option>
                                                                    @foreach ($pickup_point_list as $pick_up_point)
                                                                        <option value="{{ $pick_up_point->id }}"
                                                                            data-content="<span class='d-block'>
                                                                                            <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                                                            <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                                                            <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                                                        </span>">
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Carrier Wise Shipping -->
                                                @if (get_setting('shipping_type') == 'carrier_wise_shipping')
                                                    <div class="row pt-3 carrier_id_{{ $key }}">
                                                        @foreach ($carrier_list as $carrier_key => $carrier)
                                                            <div class="col-md-12 mb-2">
                                                                <label class="aiz-megabox d-block bg-white mb-0">
                                                                    <input type="radio"
                                                                        name="carrier_id_{{ $key }}"
                                                                        value="{{ $carrier->id }}"
                                                                        @if ($carrier_key == 0) checked @endif>
                                                                    <span class="d-flex p-3 aiz-megabox-elem rounded-0">
                                                                        <span
                                                                            class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                        <span class="flex-grow-1 pl-3 fw-600">
                                                                            <img src="{{ uploaded_asset($carrier->logo) }}"
                                                                                alt="Image" class="w-50px img-fit">
                                                                        </span>
                                                                        <span
                                                                            class="flex-grow-1 pl-3 fw-600">{{ $carrier->name }}</span>
                                                                        <span
                                                                            class="flex-grow-1 pl-3 fw-600">{{ translate('Transit in') . ' ' . $carrier->transit_time }}</span>
                                                                        <span
                                                                            class="flex-grow-1 pl-3 fw-600">{{ single_price(carrier_base_price($carts, $carrier->id, $key)) }}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <hr>
                            <div class="row align-items-center">
                                <!-- Return to shop -->
                                <div class="col-md-6 text-center text-md-left order-1 order-md-0 d-none d-md-block">
                                    <a href="{{ route('home') }}" class="btn btn-white cart-drop-btn-checkout text-secondary-base border-radius-12 fs-16 font-prompt py-2">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.9998 19.92L8.47984 13.4C7.70984 12.63 7.70984 11.37 8.47984 10.6L14.9998 4.08002" stroke="#cb774b" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        {{ translate('Return to shop') }}
                                    </a>
                                </div>
                                <div class="col-md-6 text-center text-md-left order-1 order-md-0 d-block d-md-none">
                                    <a href="{{ route('home') }}" class="btn btn-link fs-16 text-secondary-base font-prompt pt-3 px-0">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.9998 19.92L8.47984 13.4C7.70984 12.63 7.70984 11.37 8.47984 10.6L14.9998 4.08002" stroke="#cb774b" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        {{ translate('Return to shop') }}
                                    </a>
                                </div>
                                <!-- Continue to Delivery Info -->
                                <div class="col-md-6 text-center text-md-right">
                                    <button type="submit"
                                        class="btn btn-secondary-base cart-drop-btn-vcart text-white border-radius-12 fs-16 font-prompt py-2">{{ translate('Continue to Payment') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    @if ($isCheckoutSessionTimeoutExpires === true)
        @include('frontend.' . get_setting('homepage_select') . '.partials.checkout_timeout_modal')
    @endif
@endsection

@section('script')
    <script>
        function show_pickup_point(el, type) {
            var value = $(el).val();
            var target = $(el).data('target');

            if (value == 'home_delivery' || value == 'carrier') {
                if (!$(target).hasClass('d-none')) {
                    $(target).addClass('d-none');
                }
                $('.carrier_id_' + type).removeClass('d-none');
            } else {
                $(target).removeClass('d-none');
                $('.carrier_id_' + type).addClass('d-none');
            }
        }

        function toggleShippersArea(selectElement, productId) {
            let selectedValue = selectElement.value;

            let shippersAreaContainer = document.getElementById('shippers_area_container_' + productId);

            if (selectedValue === 'third_party') {
                shippersAreaContainer.style.display = 'block';
            } else {
                shippersAreaContainer.style.display = 'none';
            }
        }
    </script>
@endsection
