@extends('seller.layouts.app')

@section('panel_content')
<style>
     .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #a3b8c7 !important;
            color: #fff !important;
            border-radius: 5px !important; /* Rounded corners */
            border: #a3b8c7 !important;
        }
        .customer-color {
            background-color: #f77b0b !important;
            border: #f77b0b !important;
        }
</style>
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{__('stock.Inventory Management')}}</h1>
        </div>
    </div>
</div>
<div class="card">
    <form class="form-horizontal" id="add_inventory_record" action="{{ route('seller.save.inventory.record') }}" method="POST">
        @csrf
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ __('stock.Add Inventory Record') }}</h5>
            </div>
        </div>
        <div id="step1" class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product_variant">{{ __('stock.Product Variant') }} <span class="text-primary">*</span> </label>
                        <!-- Add your searchable dropdown for choosing a product’s variant here -->
                        <select required class="form-control select2" id="product_variant" name="product_variant">
                            <option value="">{{__('stock.Please Choose !!')}}</option>
                            <!-- Populate options dynamically based on your data -->
                            @foreach ($products as $product  )

                            <option @if (request('productVariant') == $product->id )
                                selected
                            @endif value="{{$product->id}}">{{$product->name.' '.$product->sku .$product->productVariantDetails()}}</option>
                             @endforeach

                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="warehouse">{{ __('stock.Warehouse') }}<span class="text-primary">*</span></label>
                        <!-- Add your dropdown for choosing the warehouse by name here -->
                        <select required class="form-control select2" id="warehouse" name="warehouse">
                            <option value="">{{__('stock.Please Choose !!')}}</option>
                            @foreach ($warehouses as $warehouse  )
                                <option @if (request('warehouse') == $warehouse->id )
                                    selected
                                @endif value="{{$warehouse->id}}">{{$warehouse->warehouse_name}}</option>
                            @endforeach

                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="quantity">{{ __('stock.Quantity') }}<span class="text-primary">*</span></label>
                        <input min="1" required type="number" class="form-control" id="quantity" name="quantity" placeholder="{{__('stock.Enter quantity')}}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="comment">{{ __('stock.Comment') }}</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="{{__('stock.Enter comment')}}"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary customer-btn-color" data-toggle="confirmation" id="save_record_btn">
                + {{ __('stock.Add Inventory Record') }}
            </button>
        </div>
    </form>
</div>

<div class="card">

    <form class="" id="sort_sellers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ __('stock.Summarized Listing') }}</h5>
            </div>

            {{-- @can('delete_seller')
                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal">{{translate('Delete selection')}}</a>
                    </div>
                </div>
            @endcan

            <div class="col-md-3 ml-auto">
                <select class="form-control aiz-selectpicker" name="approved_status" id="approved_status" onchange="sort_sellers()">
                    <option value="">{{translate('Filter by Approval')}}</option>
                    <option value="1"  @isset($approved) @if($approved == '1') selected @endif @endisset>{{translate('Approved')}}</option>
                    <option value="0"  @isset($approved) @if($approved == '0') selected @endif @endisset>{{translate('Non-Approved')}}</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                  <input type="text" class="form-control" id="search" name="search" value="{{ request()->search }}" placeholder="{{translate('Search')}}...">

                </div>
            </div> --}}

              {{-- Export to Excel Button --}}
            {{-- <div class="col-auto">
                <a href="{{ route('stocks.export', ['search' => request('search'), 'sort_field' => $sortField, 'sort_direction' => $sortDirection]) }}" class="btn btn-success">
                    {{ translate('Export to Excel') }}
                </a>
            </div> --}}
        </div>

        <div class="card-body">
            {{-- <table class="table aiz-table mb-0">
                <thead>
                <tr>

                    <th>
                        @if(auth()->user()->can('delete_seller'))
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-all">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        @else
                            #
                        @endif
                    </th>

                    <th>{{ translate('Product/Variant') }}</th>
                    <th>{{ translate('SKU') }}</th>
                    <th><a href="{{ route('stocks.index', ['search' => request('search'),'sort_field' => 'warehouse', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">{{ translate('Warehouse') }}</a>
                    @if($sortField == 'warehouse')
                    <i class="fa {{ $sortDirection == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    @endif
                     </th>
                    <th> <a href="{{route('stocks.index',['search' => request('search'),'sort_field' => 'quantity', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc' ])}}">{{ translate('Quantity') }}</a>
                    @if($sortField == 'quantity')
                    <i class="fa {{ $sortDirection == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    @endif
                    </th>
                    <th> <a href="{{route('stocks.index',['search' => request('search'),'sort_field' => 'updated_at', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc' ])}}">{{ translate('Last Update Date/Time') }}</a>
                    @if($sortField == 'updated_at')
                    <i class="fa {{ $sortDirection == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    </th>
                    @endif
                    <th>{{ translate('Action') }}</th>

                </tr>
                </thead>
                <tbody>
                @foreach($inventoryData as $key => $item)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $item->warehouse->warehouse_name }}</td>
                        <td>{{ $item->current_total_quantity }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <button type="button" onclick="openUpdateDialog('{{ $item->variant_id  }}', '{{ $item->current_total_quantity }}',{{$item->warehouse->id}})" class="btn btn-primary">{{ __('Add/Remove Stock') }}</button>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table> --}}
            {{-- <div class="aiz-pagination">
                {{ $inventoryData->appends(['sort_field' => $sortField, 'sort_direction' => $sortDirection])->links() }}
            </div> --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error )
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <table id="step3" class="table {{-- aiz-table --}} mb-0">
                <thead>
                    <tr>
                        <th class="custom-th">{{ __('stock.Product/Variant') }}</th>
                        <th class="custom-th">{{ __('stock.SKU') }}</th>
                        <th class="custom-th">{{ __('stock.Warehouse') }}</th>
                        <th class="custom-th">{{ __('stock.Quantity') }}</th>
                        <th class="custom-th">{{ __('stock.Last Update Date/Time') }}</th>
                        <th class="custom-th">{{ __('stock.Action') }}</th>

                </thead>
                <tbody>
                    @foreach ($inventoryData as $item)
                        @if ($item->productVariant)
                            <tr>



                                <td>{{$item->productVariant ? $item->productVariant->name :""}}</td>
                                <td>{{$item->productVariant ? $item->productVariant->sku : ""}}</td>
                                <td>{{ $item->warehouse->warehouse_name ?? "" }}</td>
                                <td>{{ $item->current_total_quantity }}</td>
                                <td>{{ $item->updated_at }}</td>
                                <td>
                                    <button type="button" onclick="openUpdateDialog('{{ $item->variant_id  }}', '{{ $item->current_total_quantity }}',{{$item->warehouse->id ?? ''}})" class="btn btn-primary customer-btn-color">{{ __('stock.Add/Remove Stock') }}</button>
                                </td>

                                <!-- Add other fields accordingly -->
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>
<!-- Update Quantity Modal -->
<div class="modal fade" id="updateQuantityModal" tabindex="-1" role="dialog" aria-labelledby="updateQuantityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateQuantityModalLabel">{{ __('stock.Update Quantity') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('seller.stock.add_remove')}}" id="updateQuantityForm">
                    @csrf
                    <div class="form-group">
                        <input id="currentProduct_variant" type="hidden" class="form-control" name="product_variant">
                        <input id="currentWarehouse" type="hidden" class="form-control" name="warehouse" >

                        <label for="currentStock">{{ __('stock.Current Stock') }}</label>
                        <input type="text" class="form-control" id="currentStock" readonly>
                    </div>
                    <div class="form-group">
                        <label>{{ __('stock.Add/Remove') }}</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="addRemove" id="addRadio" value="add" checked>
                            <label class="form-check-label" for="addRadio">
                                {{ __('stock.Add') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="addRemove" id="removeRadio" value="remove">
                            <label class="form-check-label" for="removeRadio">
                                {{ __('stock.Remove') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity">{{ __('stock.Quantity') }}</label>
                        <input required min="1" name="quantity" type="number" class="form-control" id="quantityAddRemove" >
                    </div>
                    <div class="form-group">
                        <label for="comments">{{ __('stock.Comments') }}</label>
                        <textarea class="form-control" id="comments" name="comment" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="newStock">{{ __('stock.New Stock') }}</label>
                        <input type="text"  class="form-control" id="newStock" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="/* updateNewStock(); */ updateQuantity();">{{ __('stock.Save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

 <!-- Bootstrap Modal for Confirmation -->
 <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel">{{ __('stock.Product Exists') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{ __('stock.The product in this warehouse has been added to the table before. Do you want to search for it?') }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('stock.No') }}</button>
          <button type="button" class="btn btn-primary customer-color" id="confirmSearchBtn">{{ __('stock.Yes, search for it!') }}</button>
        </div>
      </div>
    </div>
  </div>

@endsection

{{-- @section('modal')
	<!-- Delete Modal -->
	@include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')

	<!-- Seller Profile Modal -->
	<div class="modal fade" id="profile_modal">
		<div class="modal-dialog">
			<div class="modal-content" id="profile-modal-content">

			</div>
		</div>
	</div>

	<!-- Seller Payment Modal -->
	<div class="modal fade" id="payment_modal">
	    <div class="modal-dialog">
	        <div class="modal-content" id="payment-modal-content">

	        </div>
	    </div>
	</div>

	 <!-- Ban Seller Modal -->
	<div class="modal fade" id="confirm-ban">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
					<button type="button" class="close" data-dismiss="modal">
					</button>
				</div>
				<div class="modal-body">
                    <p>{{translate('Do you really want to ban this seller?')}}</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
					<a class="btn btn-primary" id="confirmation">{{translate('Proceed!')}}</a>
				</div>
			</div>
		</div>
	</div>

	 <!-- Unban Seller Modal -->
	<div class="modal fade" id="confirm-unban">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
						<button type="button" class="close" data-dismiss="modal">
						</button>
					</div>
					<div class="modal-body">
							<p>{{translate('Do you really want to unban this seller?')}}</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
						<a class="btn btn-primary" id="confirmationunban">{{translate('Proceed!')}}</a>
					</div>
				</div>
			</div>
		</div>
@endsection --}}

@section('script')
    <!-- DataTables Buttons extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <!-- Include SweetAlert from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    {{-- <script type="text/javascript">
        // $(document).on("change", ".check-all", function() {
        //     if(this.checked) {
        //         // Iterate each checkbox
        //         $('.check-one:checkbox').each(function() {
        //             this.checked = true;
        //         });
        //     } else {
        //         $('.check-one:checkbox').each(function() {
        //             this.checked = false;
        //         });
        //     }

        // });

        // function show_seller_payment_modal(id){
        //     $.post('{{ route('sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
        //         $('#payment_modal #payment-modal-content').html(data);
        //         $('#payment_modal').modal('show', {backdrop: 'static'});
        //         $('.demo-select2-placeholder').select2();
        //     });
        // }

        // function show_seller_profile(id){
        //     $.post('{{ route('sellers.profile_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
        //         $('#profile_modal #profile-modal-content').html(data);
        //         $('#profile_modal').modal('show', {backdrop: 'static'});
        //     });
        // }

        // function update_approved(el){
        //     if(el.checked){
        //         var status = 1;
        //     }
        //     else{
        //         var status = 0;
        //     }
        //     $.post('{{ route('sellers.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
        //         if(data == 1){
        //             AIZ.plugins.notify('success', '{{ translate('Approved sellers updated successfully') }}');
        //         }
        //         else{
        //             AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
        //         }
        //     });
        // }

        function sort_sellers(el){
            $('#sort_sellers').submit();
        }

        function confirm_ban(url)
        {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function confirm_unban(url)
        {
            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href' , url);
        }

        function bulk_delete() {
            var data = new FormData($('#sort_sellers')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-seller-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

    </script> --}}



<script>
    function openUpdateDialog(itemId, currentQuantity,warehouseId) {
        // Populate the modal with the current quantity
        $('#currentStock').val(currentQuantity);
        $('#currentProduct_variant').val(itemId);
        $('#currentWarehouse').val(warehouseId);

        // Set the new stock field based on the operation (add/remove)
        $('input[name="addRemove"]').change(function () {
            var quantity = parseFloat($('#quantityAddRemove').val());
            // Check if quantity is a valid number
            if (!isNaN(quantity) && quantity > 0) {
                updateNewStock();
            }
            else {
            $("#newStock").val('') ;
             }
        });

        // Show the modal
        $('#updateQuantityModal').modal('show');
    }

    function updateNewStock() {
        // Implement logic to update the new stock based on add/remove operation
        var currentStock = parseFloat($('#currentStock').val());
        var quantity = parseFloat($('#quantityAddRemove').val());
        var addRemove = $('input[name="addRemove"]:checked').val();
        if (addRemove === 'remove' && quantity > currentStock) {
        // User is trying to remove more than the current stock
        // alert("{{__('stock.Error: Cannot remove more than the current stock.')}}");
        // Swal.fire({
        //     icon: 'error',
        //     title: "{{__('stock.Error: Cannot remove more than the current stock.')}}",
        //     confirmButtonText: 'OK'
        // });
        toastr.error("{{__('stock.Error: Cannot remove more than the current stock.')}}", 'Error');

        // Reset the quantity field
        $('#quantityAddRemove').val("") ;
        $("#newStock").val('') ;

        return;
    }
        var newStock = addRemove === 'add' ? currentStock + quantity : currentStock - quantity;
        $('#newStock').val(newStock);
    }

    function updateQuantity() {
        // Perform AJAX request to update quantity in the database
        // ...

        // Close the modal
        // $('#updateQuantityModal').modal('hide');
    }

     // Add this function to handle quantity changes
     $('#quantityAddRemove').on('input', function () {
        var quantity = parseFloat($('#quantityAddRemove').val());
         // Check if quantity is a valid number
        if (!isNaN(quantity) && quantity > 0) {
            updateNewStock();
        }
        else {
            $("#newStock").val('') ;
        }
    });

//     $('#add_inventory_record').on('submit', function(e) {

//     e.preventDefault();

//     var productVariant = $('#product_variant').val();
//     var warehouse = $('#warehouse').val();


//     $.ajax({
//         url: '{{ route("seller.inventory.check") }}',
//         type: 'POST',
//         data: {
//             product_variant: productVariant,
//             warehouse: warehouse,

//             _token: '{{ csrf_token() }}'
//         },
//         success: function(response) {
//             // if(response.exists) {
//             //     // Prompt user and handle the 'Yes' response
//             //     if(confirm("The product in this warehouse has been added to the table before. Do you want to search for it?")) {
//             //         // Implement search functionality here
//             //         window.location.href = '{{ route("seller.stocks.index") }}?productVariant=' + productVariant + '&warehouse=' + warehouse;
//             //     }
//             // } else {
//             //     // Submit the form if the combination does not exist
//             //     $('#add_inventory_record').unbind('submit').submit();
//             // }
//             if (response.exists) {
//                 // Using SweetAlert for confirmation
//                 Swal.fire({
//                     title: '{{__("stock.Product Exists")}}',
//                     text: '{{__("stock.The product in this warehouse has been added to the table before. Do you want to search for it?")}}',
//                     icon: 'warning',
//                     showCancelButton: true,
//                     confirmButtonColor: '#3085d6',
//                     cancelButtonColor: '#d33',
//                     confirmButtonText: '{{__("stock.Yes, search for it!")}}',
//                     cancelButtonText: '{{__("stock.No")}}'
//                 }).then((result) => {
//                     if (result.isConfirmed) {
//                         // User clicked 'Yes', implement search functionality here
//                         window.location.href = '{{ route("seller.stocks.index") }}?productVariant=' + productVariant + '&warehouse=' + warehouse;
//                     }
//                 });
//             } else {
//                 // Submit the form if the combination does not exist
//                 $('#add_inventory_record').unbind('submit').submit();
//             }

//         }
//     });
// });
        $('#add_inventory_record').on('submit', function(e) {
                    e.preventDefault();

                    var productVariant = $('#product_variant').val();
                    var warehouse = $('#warehouse').val();

                    $.ajax({
                        url: '{{ route("seller.inventory.check") }}',
                        type: 'POST',
                        data: {
                            product_variant: productVariant,
                            warehouse: warehouse,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.exists) {
                                // Show the Bootstrap modal
                                $('#confirmationModal').modal('show');
                            } else {
                                // Submit the form if the combination does not exist
                                $('#add_inventory_record').unbind('submit').submit();
                            }
                        }
                    });
                });

                // Handle the confirmation button click event
                $('#confirmSearchBtn').on('click', function() {
                    var productVariant = $('#product_variant').val();
                    var warehouse = $('#warehouse').val();
                    // User clicked 'Yes', implement search functionality here
                    window.location.href = '{{ route("seller.stocks.index") }}?productVariant=' + productVariant + '&warehouse=' + warehouse;
                });

</script>

<script>
    $(document).ready( function () {
        $('.table').DataTable({
            "order": [[5, "desc"]], // Sort by first column in descending order
            "dom": 'Bfrtip', // Add buttons to the layout
            "language": {
                "search": "", // Remove the label text for search input
                "searchPlaceholder": "{{ __('stock.search_records') }}" // Custom search placeholder text
            },
            "buttons":   [{
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> {{__("stock.Export to Excel")}}', // Adding Font Awesome icon
                className: 'btn-excel', // Custom class for styling
                exportOptions: {
                    columns: ':not(:last-child)' // Export all columns except the last one
                }
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
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById('startTourButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default anchor click behavior
        localStorage.setItem('guide_tour', '0'); // Set local storage as required
        window.location.href = '{{ route("seller.dashboard") }}'; // Redirect to the dashboard
    });
    if (localStorage.getItem('guide_tour') != '0') {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}} ) {
            return;
        }
    }
                var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->getTranslation('title')}}',
                intro: "{{$step->getTranslation('description')}}",
                position: '{{ $step->getTranslation('lang') === 'en' ? 'right' : 'left' }}'
            },
            @endforeach
        ];
        var lang = '{{$tour_steps[0]->getTranslation('lang')}}';
        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
            nextLabel: lang == 'en' ? 'Next' : 'التالي',
            prevLabel: lang == 'en' ? 'Back' : 'رجوع',
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
                    localStorage.setItem('guide_tour', '1'); // Set local storage as required
                    setTimeout(function() {
                        window.location.href = '{{ route("seller.dashboard") }}';
                    }, 500);
                });

                tour.onbeforechange(function(targetElement) {
                    if (this._direction === 'backward') {
                    window.location.href = '{{ route("seller.reviews") }}'; // Redirect to another page
                    sleep(60000);
                    }

                    step_number += 1 ;
                    if (step_number == 3) {
                    window.location.href = '{{ route("seller.warehouses.index") }}';
                    sleep(60000);
                    }

                    //tour.exit();
                });

            tour.start();
            tour.goToStepNumber(5);
            });
        </script>
@endsection
