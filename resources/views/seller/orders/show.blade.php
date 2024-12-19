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
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->orderDetails->where('seller_id', Auth::user()->owner_id)->first()->payment_status;
                @endphp
                @if (get_setting('product_manage_by_admin') == 0)
                    <div class="col-md-3 ml-auto">
                        <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                        @if (($order->payment_type == 'cash_on_delivery' || (addon_is_activated('offline_payment') == 1 && $order->manual_payment == 1)) && $payment_status == 'unpaid')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_payment_status">
                                <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                    {{ translate('Unpaid') }}</option>
                                <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                    {{ translate('Paid') }}</option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ translate($payment_status) }}" disabled>
                        @endif
                    </div>
                @endif
            </div>
            <div class="row gutters-5 mt-2">
                <div class="col text-md-left text-center">
                    @if(json_decode($order->shipping_address))
                        <address>
                            <strong class="text-main">
                                {{ json_decode($order->shipping_address)->name }}
                            </strong><br>
                            {{ json_decode($order->shipping_address)->email }}<br>
                            {{ json_decode($order->shipping_address)->phone }}<br>
                            {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ json_decode($order->shipping_address)->postal_code }}<br>
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
                                <th data-breakpoints="lg" class="text-uppercase">{{ translate('Delivery Type') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Qty') }}
                                </th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Price') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    {{ translate('Total') }}</th>
                                    <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    {{ translate('Order Status') }}</th>
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
                                                {{ translate('Transit Time').' - '.$order->carrier->transit_time }}
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

                                        @if ($orderDetail->delivery_status != 'delivered' && $orderDetail->delivery_status != 'cancelled')
                                                <select onchange="handleDeliveryStatusChanged(this)" class="form-control" data-user_id="{{$orderDetail->seller->id}}" data-product_id="{{$orderDetail->product->id}}" data-orderdetail_id="{{$orderDetail->id}}" data-minimum-results-for-search="Infinity"
                                                    id="update_delivery_status" style="width:200px;">
                                                    <option value="pending" @if ($orderDetail->delivery_status == 'pending') selected @endif @if ($orderDetail->delivery_status == 'in_preparation' || $orderDetail->delivery_status == 'ready_for_chipment' || $orderDetail->delivery_status == 'on_the_way') disabled @endif>
                                                        {{ translate('Pending') }}</option>
                                                    <option value="in_preparation" @if ($orderDetail->delivery_status == 'in_preparation') selected @endif @if ($orderDetail->delivery_status == 'in_preparation' || $orderDetail->delivery_status == 'ready_for_chipment' || $orderDetail->delivery_status == 'on_the_way') disabled @endif>
                                                        {{ __('order.in_preparation') }}</option>
                                                    <option value="ready_for_chipment" @if ($orderDetail->delivery_status == 'ready_for_chipment') selected @endif >
                                                        {{ __('order.ready_for_shipment') }}</option>
                                                    <option value="on_the_way" @if ($orderDetail->delivery_status == 'on_the_way') selected @endif>
                                                        {{ __('order.on_the_way') }}</option>
                                                    <option value="delivered" @if ($orderDetail->delivery_status == 'delivered') selected @endif>
                                                        {{ translate('Delivered') }}</option>
                                                    <option value="cancelled" @if ($orderDetail->delivery_status == 'cancelled') selected @endif>
                                                        {{ translate('Cancel') }}</option>
                                                </select>
                                            @else
                                                <input type="text" class="form-control" value="{{ translate(ucfirst(str_replace('_', ' ', $orderDetail->delivery_status))) }}" disabled>
                                            @endif
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


    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="warehouse-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Warehouses') }}</h5>
                </div>
                    <div class="modal-body c-scrollbar-light">
                        <div class="p-3">
                            <div class="col-lg-12 table-responsive">
                                <div class="alert alert-warning"id="alert-quantity" style="display:none;" role="alert">
                                    {{__('order.quantity_entered_must_be_equal_to_the_quantity_requested') }}
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
                            <div class=" text-left col-8">
                                <div class="form-group row">
                                    <label class="col-md-3">{{__('order.total_quantity')}}</label>
                                    <input class="form-control col-md-6" type="number" id="quantity_requested" />
                                </div>
                            </div>
                            <div class="form-group text-right col-4">
                                <button id="save-stock-movment" data-quantity_requested onclick="handleSaveStockMovement(this)"  class="btn btn-primary rounded-0 w-150px">{{translate('Save')}}</button>
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
        const handleDeliveryStatusChanged = (event) =>{
            (event.value == "in_preparation") ?  handleUpdateWarehouse(event) :  updateDeliveryStatus(event);
        };

        const updateDeliveryStatus = (event) => {
            console.log("change delivery status");
            let order_id = event.dataset.orderdetail_id;
            let status = event.value;
            $.post('{{ route('seller.orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
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
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                seller: seller,
                product: product,
            }, function(data) {
                let stock = data.data;
                let tr ='';
                stock.forEach((element,key) => {
                    tr+=`<tr>
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
                AIZ.plugins.notify('success', '{{ translate('Order status has been updated') }}');
            });
        }

        const handleSaveStockMovement = (event) => {
            let order = event.dataset.order;
            let product = event.dataset.product;
            let quantity = event.dataset.quantity_requested;
            inputs = document.getElementsByName('quantity');
            let warehouses = [];
            let totalQuantity = 0
            inputs.forEach(element => {
                warehouses.push({warehouse_id:element.id,quantity:parseInt(element.value)});
                totalQuantity+=parseInt(element.value);
            });
            if(quantity!=totalQuantity){
                document.getElementById('alert-quantity').style.display ='';
                return;
            }else{
                document.getElementById('alert-quantity').style.display ='none';
                $.post('{{ route('seller.orders.stock_movement') }}', {
                    _token: '{{ @csrf_token() }}',
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
    </script>
@endsection
