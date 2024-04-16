@extends('seller.layouts.app')

@section('panel_content')

    <div class="card">
        <form id="sort_orders" action="" method="GET">
            <div id="step1" class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Orders') }}</h5>
                </div>
                <div class="col-md-3 ml-auto">
                    <select class="form-control aiz-selectpicker"
                        data-placeholder="{{ translate('Filter by Payment Status') }}" name="payment_status"
                        onchange="sort_orders()">
                        <option value="">{{ translate('Filter by Payment Status') }}</option>
                        <option value="paid"
                            @isset($payment_status) @if ($payment_status == 'paid') selected @endif @endisset>
                            {{ translate('Paid') }}</option>
                        <option value="unpaid"
                            @isset($payment_status) @if ($payment_status == 'unpaid') selected @endif @endisset>
                            {{ translate('Unpaid') }}</option>
                    </select>
                </div>

                <div class="col-md-3 ml-auto">
                    <select class="form-control aiz-selectpicker"
                        data-placeholder="{{ translate('Filter by Payment Status') }}" name="delivery_status"
                        onchange="sort_orders()">
                        <option value="">{{ translate('Filter by Deliver Status') }}</option>
                        <option value="pending"
                            @isset($delivery_status) @if ($delivery_status == 'pending') selected @endif @endisset>
                            {{ translate('Pending') }}</option>
                        <option value="confirmed"
                            @isset($delivery_status) @if ($delivery_status == 'confirmed') selected @endif @endisset>
                            {{ translate('Confirmed') }}</option>
                        <option value="on_the_way"
                            @isset($delivery_status) @if ($delivery_status == 'on_the_way') selected @endif @endisset>
                            {{ translate('On The Way') }}</option>
                        <option value="delivered"
                            @isset($delivery_status) @if ($delivery_status == 'delivered') selected @endif @endisset>
                            {{ translate('Delivered') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="from-group mb-0">
                        <input type="text" class="form-control" id="search" name="search"
                            @isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
            </div>
        </form>

        @if (count($orders) > 0)
            <div class="card-body p-3">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>
                            <th data-breakpoints="lg">{{ translate('Customer') }}</th>
                            <th data-breakpoints="md">{{ translate('Amount') }}</th>
                            <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>
                            <th>{{ translate('Payment Status') }}</th>
                            <th class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order_id)
                            @php
                                $order = \App\Models\Order::find($order_id->id);
                            @endphp
                            @if ($order != null)
                                <tr>
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        <a href="#{{ $order->code }}"
                                            onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                                        @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                            <span class="badge badge-inline badge-danger">{{ translate('POS') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ count($order->orderDetails->where('seller_id', Auth::user()->owner_id)) }}
                                    </td>
                                    <td>
                                        @if ($order->user_id != null)
                                            {{ optional($order->user)->name }}
                                        @else
                                            {{ translate('Guest') }} ({{ $order->guest_id }})
                                        @endif
                                    </td>
                                    <td>
                                        {{ single_price($order->grand_total) }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $order->delivery_status;
                                        @endphp
                                        {{ translate(ucfirst(str_replace('_', ' ', $status))) }}
                                    </td>
                                    <td>
                                        @if ($order->payment_status == 'paid')
                                            <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                                href="{{ route('seller.invoice.thermal_printer', $order->id) }}"
                                                target="_blank" title="{{ translate('Thermal Printer') }}">
                                                <i class="las la-print"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('seller.orders.show', encrypt($order->id)) }}"
                                            class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                            title="{{ translate('Order Details') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                        <a href="{{ route('seller.invoice.download', $order->id) }}"
                                            class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                            title="{{ translate('Download Invoice') }}">
                                            <i class="las la-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function sort_orders(el) {
            $('#sort_orders').submit();
        }
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}}) {
            return;
        }
    let tour = introJs();
    let step_number = 0 ;
    tour.setOptions({
        steps: [
            {
                element: document.querySelector('#dashboard'),
                title: 'Dashboard',
                intro: "Welcome to your e-Shop dashboard! This is your central hub for managing your shop's performance, sales, and settings.",
                position: 'right'
            },
            {
                element: document.querySelector('#products'),
                title: 'Step 1: Manage Products',
                intro: "Here, you can add, edit, and manage your products. Showcase your offerings to attract buyers and keep your inventory up to date.",
                position: 'right'
            },
            {
                element: document.querySelector('#reviews'),
                title: 'Step 2: Monitor Reviews',
                intro: "Stay informed about what customers are saying. Manage and respond to reviews to maintain a positive reputation and improve your products.",
                position: 'right'
            },
            {
                element: document.querySelector('#catalog'),
                title: 'Step 3: Catalog Management',
                intro: "Organize your products into categories and collections. Enhance discoverability and make it easier for customers to find what they're looking for.",
                position: 'right'
            },
            {
                element: document.querySelector('#stock'),
                title: 'Step 4: Stock Management',
                intro: "Track your inventory levels and manage stock efficiently. Avoid overselling and keep your customers satisfied with accurate stock updates.",
                position: 'right'
            },
            {
                element: document.querySelector('#stock_details'),
                title: 'Step 5: Stock Details',
                intro: "View detailed information about your stock, including quantities, variations, and restocking options. Keep your inventory organized and up to date.",
                position: 'right'
            },
            {
                element: document.querySelector('#order'),
                title: 'Step 6: Order Management',
                intro: "Keep track of incoming orders, process payments, and manage order fulfillment. Ensure smooth transactions and timely delivery to your customers.",
                position: 'right'
            },
            {
                element: document.querySelector('#packages'),
                title: 'Step 7: Package Management',
                intro: "Manage packaging options and shipping details for your products. Choose the best packaging solutions to protect your items during transit.",
                position: 'right'
            },
            {
                element: document.querySelector('#package_list'),
                title: 'Step 8: Package List',
                intro: "View a list of all packages associated with your orders. Keep track of shipments and delivery status to provide accurate updates to customers.",
                position: 'right'
            },
            {
                element: document.querySelector('#staff'),
                title: 'Step 9: Staff Management',
                intro: "Add, remove, or manage staff members who assist with running your shop. Delegate tasks and collaborate effectively to streamline operations.",
                position: 'right'
            },
            {
                element: document.querySelector('#lease'),
                title: 'Step 10: Lease Management',
                intro: "Manage lease agreements for your shop premises or equipment. Stay organized and ensure compliance with lease terms and conditions.",
                position: 'right'
            },
            {
                element: document.querySelector('#lease_details'),
                title: 'Step 11: Lease Details',
                intro: "View detailed information about your lease agreements, including terms, renewal dates, and rental payments. Stay on top of lease obligations.",
                position: 'right'
            },
            {
                element: document.querySelector('#support_tickets'),
                title: 'Step 12: Support Tickets',
                intro: "Handle customer inquiries, feedback, and support requests. Provide timely assistance and resolve issues to maintain customer satisfaction.",
                position: 'right'
            },
            {
                element: document.querySelector('#setting'),
                title: 'Step 13: Account Settings',
                intro: "Adjust your account settings and preferences. Customize your dashboard experience to suit your needs and optimize your workflow.",
                position: 'right'
            }
        ],

        doneLabel: 'Next', // Replace the "Done" button with "Next"
        exitOnEsc : false ,
        exitOnOverlayClick : false ,
        disableInteraction : true ,
        overlayOpacity : 0.4 ,
        showStepNumbers : true ,
        hidePrev : true ,
        showProgress :true ,
    });

        tour.onexit(function() {
            $.ajax({
                url: "{{ route('seller.tour') }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' }, // Include CSRF token for Laravel
                success: function(response) {
                    // Handle success
                    console.log('User tour status updated successfully');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating user tour status:', error);
                }
            });
            setTimeout(function() {
                window.location.href = '{{ route("seller.dashboard") }}';
            }, 500);
        });

        tour.onbeforechange(function(targetElement) {
            step_number += 1 ;
            if (step_number == 3) {
            window.location.href = '{{ route("seller.seller_packages_list") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(7);
    });
</script>
@endsection
