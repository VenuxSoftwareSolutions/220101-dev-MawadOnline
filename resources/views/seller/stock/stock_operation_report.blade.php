@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{__('stock.Stock Operation Details')}}</h1>
        </div>
    </div>
</div>


<div class="card">

    <form class="" id="sort_sellers" action="{{route('seller.stock.search')}}" method="GET">
        <div class="card-header gutters-5">
            {{-- <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Summarized Listing') }}</h5>
            </div> --}}
            <div class="row">
                <div id="step1"  class="col-md-12 row" >
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_date">{{ __('stock.From Date') }}</label>
                            @php
                            // Calculate the date 3 months ago
                            $threeMonthsAgo = (new DateTime())->sub(new DateInterval('P3M'))->format('Y-m-d');
                            @endphp
                            <input min="{{ $threeMonthsAgo }}" value="{{request('from_date')}}" type="date" id="from_date" name="from_date" class="form-control" required>
                            <small class="form-text text-muted">{{ __('stock.info_message_from_date') }}</small>
                        </div>

                        @if ($errors->has('from_date'))
                        <span class="text-danger">{{ $errors->first('from_date') }}</span>
                    @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_date">{{ __('stock.To Date') }}</label>
                            <input value="{{request('to_date')}}" type="date" id="to_date" name="to_date" class="form-control" required>
                            @if ($errors->has('to_date'))
                            <span class="text-danger">{{ $errors->first('to_date') }}</span>
                            @endif

                        </div>

                    </div>
                    @if (request('from_date') || request('to_date') )
                    <div class="col-md-6">
                        {{-- <div class="form-group">
                            <label for="product_variants">{{ __('stock.Product Variants') }}</label>
                            <select id="product_variants" name="product_variants[]" class="form-control select2" multiple="multiple">

                                <option @if (is_array(request('product_variants')) && (in_array(2,request('product_variants'))))
                                    selected
                                @endif value="2">Zephania Mann</option>
                                <option @if (is_array(request('product_variants')) && (in_array(1,request('product_variants'))))
                                selected
                            @endif value="1">Lucian Burke</option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <button id="select-all" type="button" class="btn btn-sm btn-secondary">{{__('stock.Select All')}}</button>
                            <button id="deselect-all" type="button" class="btn btn-sm btn-secondary">{{__('stock.Deselect All')}}</button>
                        </div> --}}
                        <div class="form-group">
                            <label for="product_variants">{{ __('stock.Product Variants') }}</label>
                            <select id="product_variants" name="product_variants[]" class="form-control myMultiselect" multiple="multiple">

                                {{-- <option @if (is_array(request('product_variants')) && (in_array(2,request('product_variants')))|| !(request('product_variants')))
                                    selected
                                @endif value="2">Zephania Mann</option>
                                <option @if (is_array(request('product_variants')) && (in_array(1,request('product_variants')))|| !(request('product_variants')))
                                selected
                            @endif value="1">Lucian Burke</option> --}}
                            @foreach ($productVariants as $productVariant)
                                <option @if (is_array(request('product_variants')) && (in_array($productVariant->id,request('product_variants')))|| !(request('product_variants')))
                                    selected
                                @endif value="{{$productVariant->id}}">{{$productVariant->name.' '.$productVariant->sku .$productVariant->productVariantDetails()}}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">{{ trans('stock.products_on_selected_date') }}</small>

                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- <div class="form-group">
                            <label for="warehouses">{{ __('stock.Warehouses') }}</label>

                            <select id="warehouses" name="warehouses[]" class="form-control select2" multiple="multiple">

                                @foreach ($warehouses as $warehouse)
                                    <option @if (is_array(request('warehouses')) && (in_array($warehouse->id,request('warehouses'))))
                                        selected
                                    @endif value="{{$warehouse->id}}">{{$warehouse->warehouse_name}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="warehouses">{{ __('stock.Warehouses') }}</label>
                            <select id="warehouses" name="warehouses[]" class="form-control myMultiselect" multiple="multiple">

                                @foreach ($warehouses as $warehouse)
                                    <option @if (is_array(request('warehouses')) && (in_array($warehouse->id,request('warehouses')))|| !(request('warehouses')))
                                        selected
                                    @endif value="{{$warehouse->id}}">{{$warehouse->warehouse_name}}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">{{ trans('stock.warehouses_on_selected_date') }}</small>

                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-md-auto">
                    <div class="form-group mt-4">

                        <button id="step2" type="submit" class="btn btn-primary">{{ __('stock.Search') }}</button>
                    </div>

            </div>

            </div>
              {{-- Export to Excel Button --}}
            {{-- <div class="col-auto">
                <a href="{{ route('stocks.export', ['search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}" class="btn btn-success">
                    {{ translate('Export to Excel') }}
                </a>
            </div> --}}
        </div>
        @if (isset($records))


        <div class="card-body">
            <table class="table {{-- aiz-table --}} mb-0">
                <thead>
                    <tr>
                        <th>{{__('stock.Date/Time')}}</th>
                        <th>{{__('stock.Type of Operation')}}</th>
                        <th>{{__('stock.Product/Variant + SKU')}}</th>
                        <th>{{__('stock.Warehouse Name')}}</th>
                        <th>{{__('stock.Quantity Before Operation')}}</th>
                        <th>{{__('stock.Operation Quantity')}}</th>
                        <th>{{__('stock.Quantity After Operation')}}</th>
                        <th>{{__('stock.User')}}</th>
                        <th>{{__('stock.User Comments')}}</th>
                        <th>{{__('stock.Sales Order')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $record)
                        <tr>
                            <td>{{ $record->created_at }}</td>
                            <td>{{ $record->operation_type }}</td>
                            <td>{{ $record->productVariant->name.' '.$record->productVariant->sku }}</td>
                            <td>{{ $record->warehouse->warehouse_name }}</td>
                            <td>{{ $record->before_quantity }}</td>
                            <td>{{ $record->transaction_quantity	 }}</td>
                            <td>{{ $record->after_quantity	 }}</td>
                            <td>{{ $record->seller->name	 }}</td>
                            <td>{{ $record->user_comment	 }}</td>
                            <td></td>

                            <!-- Add other fields accordingly -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- <div class="aiz-pagination">
                {{ $records->links() }} <!-- Pagination links -->
            </div> --}}
        </div>
        @endif
    </form>
</div>

@endsection



@section('script')
    <!-- DataTables Buttons extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>



<script>
    $(document).ready( function () {
        $('.table').DataTable({
            "order": [[0, "desc"]], // Sort by first column in descending order
            "dom": 'Bfrtip', // Add buttons to the layout

            "buttons":   [{
                extend: 'excelHtml5',
                text: '{{__("stock.Export to Excel")}}',

            }]
        });
        $('.select2').select2(); // Initialize Select2 elements

        // $('.aiz-table').DataTable( {
        //     dom: 'Bfrtip',
        // buttons: [
        //     'copy', 'csv', 'excel', 'pdf', 'print'
        // ]
        // } );
    });
    </script>

<script>
    $(document).ready(function() {
        $('#select-all').click(function(e) {
            e.preventDefault();
            $('#product_variants > option').prop('selected',true);
            $('#product_variants').trigger('change'); // Notify select2 about the change
            });
        $('#deselect-all').click(function(e) {
                e.preventDefault();
                $('#product_variants > option').prop('selected', false);
                $('#product_variants').trigger('change'); // Notify select2 about the change
            });
            $('.myMultiselect').multiselect({
            columns: 1,
            placeholder: 'Select Options',
            selectAll: true,
            search: true
        });
    });
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
            window.location.href = '{{ route("seller.orders.index") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(6);
    });
</script>

@endsection
