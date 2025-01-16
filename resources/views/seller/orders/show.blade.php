@extends('seller.layouts.app')

@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>

        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                </div>
            </div>
            <div class="row gutters-5 mt-2">
                <div class="col text-md-left text-center">
                    @if (json_decode($order->shipping_address))
                        <address>
                            {{ json_decode($order->shipping_address)->phone }}<br>
                            {{ json_decode($order->shipping_address)->address }},
                            {{ json_decode($order->shipping_address)->city }}, @if (isset(json_decode($order->shipping_address)->state))
                                {{ json_decode($order->shipping_address)->state }} -
                            @endif {{ json_decode($order->shipping_address)->postal_code }}<br>
                            {{ json_decode($order->shipping_address)->country }}
                        </address>
                    @else
                        <address>
                            <strong class="text-main">
                                {{ $order->user->name }}
                            </strong><br>
                            {{ $order->user->email }}<br>
                            {{ $order->user->phone }}<br>
                        </address>
                    @endif
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}:
                        {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}"
                            target="_blank"><img
                                src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100"></a>
                    @endif
                </div>
                <div class="col-md-4 ml-auto">
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-info text-bold text-right">{{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }}</td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Total amount') }}</td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>

                            <tr>
                                <td class="text-main text-bold">{{ translate('Additional Info') }}</td>
                                <td class="text-right">{{ $order->additional_info }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table-bordered aiz-table invoice-summary table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th class="text-uppercase">{{ translate('Description') }}</th>
                                <th data-breakpoints="lg" class="text-uppercase">{{ translate('Shipping Type') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Qty') }}
                                </th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Price') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    {{ translate('Total') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    {{ translate('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                target="_blank"><img height="50"
                                                    src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                target="_blank"><img height="50"
                                                    src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                        @else
                                            <strong>{{ translate('N/A') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <strong><a href="{{ route('product', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                            <small>{{ $orderDetail->variation }}</small>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <strong><a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->getTranslation('name') }}
                                                ({{ translate('Pickup Point') }})
                                            @else
                                                {{ translate('Pickup Point') }}
                                            @endif
                                        @elseif($order->shipping_type == 'carrier')
                                            @if ($order->carrier != null)
                                                {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                                <br>
                                                {{ translate('Transit Time') . ' - ' . $order->carrier->transit_time }}
                                            @else
                                                {{ translate('Carrier') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                                    <td>
                                        <div class="row align-items-center justify-content-center">
                                            @if ($orderDetail->delivery_status != 'delivered' && $orderDetail->delivery_status != 'cancelled')
                                                <select onchange="handleDeliveryStatusChanged(this)" class="form-control"
                                                    data-user_id="{{ $orderDetail->seller->id }}"
                                                    data-product_id="{{ $orderDetail->product->id }}"
                                                    data-orderdetail_id="{{ $orderDetail->id }}"
                                                    data-minimum-results-for-search="Infinity" id="update_delivery_status"
                                                    style="width:200px;">
                                                    <option value="pending"
                                                        @if ($orderDetail->delivery_status == 'pending') selected @endif
                                                        @if (
                                                            $orderDetail->delivery_status == 'in_preparation' ||
                                                                $orderDetail->delivery_status == 'ready_for_shipment' ||
                                                                $orderDetail->delivery_status == 'on_the_way') disabled @endif>
                                                        {{ translate('Pending') }}</option>
                                                    <option value="in_preparation"
                                                        @if ($orderDetail->delivery_status == 'in_preparation') selected @endif
                                                        @if (
                                                            $orderDetail->delivery_status == 'in_preparation' ||
                                                                $orderDetail->delivery_status == 'ready_for_shipment' ||
                                                                $orderDetail->delivery_status == 'on_the_way') disabled @endif>
                                                        {{ __('order.in_preparation') }}</option>
                                                    <option value="ready_for_shipment"
                                                        @if ($orderDetail->delivery_status == 'ready_for_shipment') selected @endif>
                                                        {{ __('order.ready_for_shipment') }}</option>
                                                    <option value="on_the_way"
                                                        @if ($orderDetail->delivery_status == 'on_the_way') selected @endif>
                                                        {{ __('order.on_the_way') }}</option>
                                                    <option value="delivered"
                                                        @if ($orderDetail->delivery_status == 'delivered') selected @endif>
                                                        {{ translate('Delivered') }}</option>
                                                    <option value="cancelled"
                                                        @if ($orderDetail->delivery_status == 'cancelled') selected @endif>
                                                        {{ translate('Canceled') }}</option>
                                                </select>
                                            @else
                                                <input type="text" class="form-control"
                                                    value="{{ translate(ucfirst(str_replace('_', ' ', $orderDetail->delivery_status))) }}"
                                                    disabled>
                                            @endif

                                            @if ($orderDetail->trackingShipment !== null)
                                                <a class="mx-3" href="{{ $orderDetail->trackingShipment->label_url }}"
                                                    target="_blank" data-toggle="tooltip"
                                                    title="{{ translate('Printable Label') }}"><i
                                                        class="las la-print"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Tax') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Shipping') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="no-print text-right">
                    <a href="{{ route('seller.invoice.download', $order->id) }}" type="button"
                        class="btn btn-icon btn-light"><i class="las la-print"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="shipment-modal" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Shipment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    <div class="p-3">
                        <form id="shipment_form">
                            <div class="row">
                                <div class="col-md-4">{{ __('Shipping date') }} *</div>
                                <div class="col-md-8"><input type="datetime-local" class="form-control my-2"
                                        id="shipping_datetime" name="shipping_datetime"
                                        value="{{ old('shipping_datetime', now()->format('Y-m-d\TH:i')) }}"></div>
                            </div>
                            @if (json_decode($order->shipping_address))
                                <input type="hidden" name="consignee_email"
                                    value="{{ json_decode($order->shipping_address)->email }}">
                                <input type="hidden" name="consignee_name"
                                    value="{{ json_decode($order->shipping_address)->name }}"> <input type="hidden"
                                    name="consignee_phone" value="{{ json_decode($order->shipping_address)->phone }}">
                                <input type="hidden" name="consignee_city"
                                    value="{{ json_decode($order->shipping_address)->city }}">
                                <input type="hidden" name="consignee_state"
                                    value="{{ json_decode($order->shipping_address)->state }}">
                                <input type="hidden" name="consignee_post_code"
                                    value="{{ json_decode($order->shipping_address)->postal_code }}">
                                <input type="hidden" name="consignee_address"
                                    value="{{ json_decode($order->shipping_address)->address }}"> <input type="hidden"
                                    name="consignee_country_code" value="AE">
                                <input type="hidden" id="order_detail_id" name="order_id" value="">
                                <input type="hidden" name="status" value="ready_for_shipment">
                            @endif
                            <input type="hidden" name="product_id" />
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">{{ __('Pickup Address') }}
                                        *</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control my-2" name="pickup_post_code"
                                        placeholder="{{ __('Post code') }}">
                                    <input type="text" class="form-control my-2" name="pickup_building_name"
                                        placeholder="{{ __('Building name') }}">
                                    <input type="text" class="form-control my-2" name="pickup_building_number"
                                        placeholder="{{ __('Building number') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">{{ __('Pickup date') }} *</div>
                                <div class="col-md-8"><input type="datetime-local" class="form-control my-2"
                                        id="pickup_datetime" name="pickup_datetime"
                                        value="{{ old('pickup_datetime', now()->format('Y-m-d\TH:i')) }}"></div>
                            </div>
                            <div class="row" style="display: none;">
                                <div class="col-md-4">{{ __('Generated printable label') }}</div>
                                <div class="col-md-8" id="printable-label-wrapper"></div>
                            </div>

                            <div class="modal-footer form-group text-right">
                                <button type="button" class="btn btn-secondary rounded-0 w-150px"
                                    data-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" id="save_shippment_btn" class="btn btn-primary rounded-0 w-150px">
                                    {{ __('Save') }}
                                    <div style="display: none;" class="spinner-border spinner-border-sm float-right"
                                        role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="warehouse-modal" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Warehouses') }}</h5>
                </div>
                <div class="modal-body c-scrollbar-light">
                    <div class="p-3">
                        <div class="col-lg-12 table-responsive">
                            <div class="alert alert-warning"id="alert-quantity" style="display:none;" role="alert">
                                {{ __('order.quantity_entered_must_be_equal_to_the_quantity_requested') }}
                            </div>
                            <table class="table-bordered   table">
                                <thead>
                                    <tr class="bg-trans-dark">
                                        <th data-breakpoints="lg" class="min-col">#</th>
                                        <th>{{ translate('warehouses') }}</th>
                                        <th>{{ translate('product') }}</th>
                                        <th>{{ translate('current quantity') }}</th>
                                        <th>{{ translate('quantity') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="warehouses_table"></tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class=" text-left col-6">
                                <div class="form-group row">
                                    <label class="col-md-4">{{ __('order.total_quantity') }}</label>
                                    <input class="form-control col-md-8" type="number" id="quantity_requested" />
                                </div>
                            </div>
                            <!-- Save button -->
                            <div class="form-group text-right col-6">
                                <button onClick="window.location.reload();"
                                    class="btn btn-danger rounded-0 w-150px">{{ translate('Cancel') }}</button>
                                <button id="save-stock-movment" data-quantity_requested
                                    onclick="handleSaveStockMovement(this)"
                                    class="btn btn-primary rounded-0 w-150px">{{ __('order.confirm_order') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const handleDeliveryStatusChanged = (event) => {
            if (event.value == "in_preparation") {
                handleUpdateWarehouse(event)
            } else if (event.value === "ready_for_shipment") {
                $("#shipment-modal").modal("show");
                $("#shipment-modal").on('shown.bs.modal', function(e) {
                    let orderDetailId = $(event).data("orderdetail_id");
                    let productId = $(event).data("product_id");
                    let modal = $(this);
                    modal.find('.modal-body #order_detail_id').val(orderDetailId);
                    modal.find('.modal-body [name=product_id]').val(productId);
                });
            } else {
                updateDeliveryStatus(event);
            }
        };

        const updateDeliveryStatus = (event) => {
            let order_id = event.dataset.orderdetail_id;
            let status = event.value;

            $.post('{{ route('seller.orders.update_delivery_status') }}', {
                _token: '{{ csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                $('#order_details').modal('hide');
                AIZ.plugins.notify('success', '{{ translate('Order status has been updated') }}');
                location.reload().setTimeOut(1000);
            });
        }

        const handleUpdateWarehouse = (event) => {
            let order_id = event.dataset.orderdetail_id;
            let seller = event.dataset.user_id;
            let product = event.dataset.product_id;
            $.post('{{ route('seller.orders.get_warehouses') }}', {
                _token: '{{ csrf_token() }}',
                order_id: order_id,
                seller: seller,
                product: product,
            }, function(data) {
                let stock = data.data;
                let tr = '';
                stock.forEach((element, key) => {
                    tr += `<tr>
                            <td>${key}</td>
                            <td>${element.warehouse.warehouse_name}</td>
                            <td>${element.product_variant.name}</td>
                            <td>${element.current_total_quantity}</td>
                            <td><input class="form-control" type="number" name="quantity" id="${element.warehouse.id}" value="0"/></td>
                        </tr>`;
                });
                document.getElementById('save-stock-movment').dataset.order = order_id;
                document.getElementById('save-stock-movment').dataset.product = product;
                document.getElementById('save-stock-movment').dataset.quantity_requested = data.quantity;
                document.getElementById('quantity_requested').value = data.quantity;
                $('#warehouses_table').html('');
                $('#warehouses_table').append(tr);
                $('#warehouse-modal').modal("show");
            });
        }

        const handleSaveStockMovement = (event) => {
            let order = event.dataset.order;
            let product = event.dataset.product;
            let quantity = event.dataset.quantity_requested;
            let inputs = document.getElementsByName('quantity');
            let warehouses = [];
            let totalQuantity = 0
            inputs.forEach(element => {
                warehouses.push({
                    warehouse_id: element.id,
                    quantity: parseInt(element.value)
                });
                totalQuantity += parseInt(element.value);
            });
            if (quantity != totalQuantity) {
                document.getElementById('alert-quantity').style.display = '';
                return;
            } else {
                document.getElementById('alert-quantity').style.display = 'none';
                $.post('{{ route('seller.orders.stock_movement') }}', {
                    _token: '{{ csrf_token() }}',
                    order: order,
                    warehouses: warehouses,
                    product: product,
                }, function(data) {
                    AIZ.plugins.notify('success', '{{ translate('Order status has been updated') }}');
                    location.reload().setTimeOut(1000);
                });
            }
        }

        $('#update_payment_status').on('change', function() {
            let order_id = {{ $order->id }};
            let status = $('#update_payment_status').val();
            $.post('{{ route('seller.orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                $('#order_details').modal('hide');
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
                location.reload().setTimeOut(500);
            });
        });

        $(document).ready(function() {
            document.getElementById("shipment_form").addEventListener('submit', function(event) {
                event.preventDefault();
                $("#save_shippment_btn .spinner-border").show();
                const formData = new FormData(document.getElementById('shipment_form'));

                fetch('{{ route('seller.orders.update_delivery_status') }}', {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    }).then(response => response.json())
                    .then(function({
                        data
                    }) {
                        $("#save_shippment_btn .spinner-border").hide();
                        $('#shipment_modal').modal('hide');

                        if (data.link !== undefined) {
                            $("#printable-label-wrapper").parent().show();
                            $("#printable-label-wrapper").html(
                                `<a href="${data.link}" target="_blank">{{ __('Printable label') }}</a>`
                            );
                        }

                        AIZ.plugins.notify('success',
                            '{{ translate('Order status has been updated') }}');
                        location.reload().setTimeOut(1000);
                    }).catch((e) => {
                        $("#save_shippment_btn .spinner-border").hide();
                        AIZ.plugins.notify(
                            'danger',
                            e.message
                        );
                    });
            });

            $(document).on('change', '[name=state],[name=pickup_state]', function() {
                let url = "{{ route('emirate.states', ['emirate_id' => ':id']) }}";
                let id = $(this).val();
                let parent = $(this).attr('name');

                $(`${parent === "state" ? "[name=city]" : "[name=pickup_city]"}`).find('option').remove();

                $.ajax({
                    url: url.replace(':id', id),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.data !== null && response.data?.length > 0) {
                            for (let i = 0; i < response.data.length; i++) {
                                let id = response['data'][i].id;
                                let name = response['data'][i].name;

                                $(`${parent === "state" ? "[name=city]" : "[name=pickup_city]"}`)
                                    .append(`
                                    <option value='${id}'>
                                        ${name}
                                    </option>
                                `);

                                AIZ.plugins.bootstrapSelect('refresh');
                            }
                        }
                    },
                    error: function() {
                        AIZ.plugins.notify('{{ __('Something went wrong!') }}');
                    }
                });
            });
        });
    </script>
@endsection
