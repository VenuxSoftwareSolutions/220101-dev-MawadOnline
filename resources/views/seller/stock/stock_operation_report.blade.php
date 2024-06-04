@extends('seller.layouts.app')

@section('panel_content')
<style>
     .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #a3b8c7 !important;
            color: #fff !important;
            border-radius: 5px !important; /* Rounded corners */
            border: #a3b8c7 !important;
        }
        .ms-options-wrap.ms-has-selections > button {
            border-radius: 5px;
        }
</style>
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
                            @if ($productVariant)
                                <option @if (is_array(request('product_variants')) && (in_array($productVariant->id,request('product_variants')))|| !(request('product_variants')))
                                    selected
                                @endif value="{{$productVariant->id}}">{{$productVariant->name.' '.$productVariant->sku /* .$productVariant->productVariantDetails() */}}</option>
                                @endif
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

                        <button id="step2" type="submit" class="btn btn-primary customer-btn-color">{{ __('stock.Search') }}</button>
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
                        <th class="custom-th">{{__('stock.Date/Time')}}</th>
                        <th class="custom-th">{{__('stock.Type of Operation')}}</th>
                        <th class="custom-th">{{__('stock.Product/Variant + SKU')}}</th>
                        <th class="custom-th">{{__('stock.Warehouse Name')}}</th>
                        <th class="custom-th">{{__('stock.Quantity Before Operation')}}</th>
                        <th class="custom-th">{{__('stock.Operation Quantity')}}</th>
                        <th class="custom-th">{{__('stock.Quantity After Operation')}}</th>
                        <th class="custom-th">{{__('stock.User')}}</th>
                        <th class="custom-th">{{__('stock.User Comments')}}</th>
                        <th class="custom-th">{{__('stock.Sales Order')}}</th>
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
            "language": {
                "search": "", // Remove the label text for search input
                "searchPlaceholder": "Search Records" // Custom search placeholder text
            },
            "buttons":   [{
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> {{__("stock.Export to Excel")}}', // Adding Font Awesome icon
                className: 'btn-excel', // Custom class for styling

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
        var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->title}}',
                intro: "{{$step->description}}",
                position: 'right'
            },
            @endforeach
        ];

        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
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
            if (this._direction === 'backward') {
            window.location.href = '{{ route("seller.warehouses.index") }}'; // Redirect to another page
            sleep(60000);
            }
            step_number += 1 ;
            if (step_number == 3) {
            window.location.href = '{{ route("seller.orders.index") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(7);
    });
</script>

@endsection
