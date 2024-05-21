@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Vendors')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    {{-- <form class="" id="sort_sellers" action="" method="GET"> --}}
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Vendors') }}</h5>
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
                  <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name or email & Enter') }}">
                </div>
            </div> --}}
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success')}}
                </div>
            @endif
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
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th data-breakpoints="lg">{{translate('Email Address')}}</th>
                    <th data-breakpoints="lg">{{translate('Verification Info')}}</th>
                    <th data-breakpoints="lg">{{translate('Approval')}}</th>
                    <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>
                    <th data-breakpoints="lg">{{ translate('Due to seller') }}</th>
                    <th data-breakpoints="lg">{{ translate('Status') }}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($shops as $key => $shop)
                    <tr>
                        <td>
                            @if(auth()->user()->can('delete_seller'))
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]" value="{{$shop->id}}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            @else
                                {{ ($key+1) + ($shops->currentPage() - 1)*$shops->perPage() }}
                            @endif
                        </td>
                        <td>@if($shop->user->banned == 1) <i class="fa fa-ban text-danger" aria-hidden="true"></i> @endif {{$shop->name}}</td>
                        <td>{{$shop->user->phone}}</td>
                        <td>{{$shop->user->email}}</td>
                        <td>
                            @if ($shop->verification_status != 1 && $shop->verification_info != null)
                                <a href="{{ route('sellers.show_verification_request', $shop->id) }}">
                                    <span class="badge badge-inline badge-info">{{translate('Show')}}</span>
                                </a>
                            @endif
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input
                                    @can('approve_seller') onchange="update_approved(this)" @endcan
                                    value="{{ $shop->id }}" type="checkbox"
                                    <?php if($shop->verification_status == 1) echo "checked";?>
                                    @cannot('approve_seller') disabled @endcan
                                >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>{{ $shop->user->products->count() }}</td>
                        <td>
                            @if ($shop->admin_to_pay >= 0)
                                {{ single_price($shop->admin_to_pay) }}
                            @else
                                {{ single_price(abs($shop->admin_to_pay)) }} ({{ translate('Due to Admin') }})
                            @endif
                        </td>
                        <td>
                            @if($shop->user->banned)
                                <span class="badge badge-inline badge-danger">{{ translate('Ban') }}</span>
                            @else
                                <span class="badge badge-inline badge-success">{{ translate('Regular') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="las la-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                    @can('view_seller_profile')
                                        <a href="#" onclick="show_seller_profile('{{$shop->id}}');"  class="dropdown-item">
                                            {{translate('Profile')}}
                                        </a>
                                    @endcan
                                    @can('login_as_seller')
                                        <a href="{{route('sellers.login', encrypt($shop->id))}}" class="dropdown-item">
                                            {{translate('Log in as this Seller')}}
                                        </a>
                                    @endcan
                                    @can('pay_to_seller')
                                        <a href="#" onclick="show_seller_payment_modal('{{$shop->id}}');" class="dropdown-item">
                                            {{translate('Go to Payment')}}
                                        </a>
                                    @endcan
                                    @can('seller_payment_history')
                                        <a href="{{route('sellers.payment_history', encrypt($shop->user_id))}}" class="dropdown-item">
                                            {{translate('Payment History')}}
                                        </a>
                                    @endcan
                                    @can('edit_seller')
                                        <a href="{{route('sellers.edit', encrypt($shop->id))}}" class="dropdown-item">
                                            {{translate('Edit')}}
                                        </a>
                                    @endcan
                                    @can('ban_seller')
                                        @if($shop->user->banned != 1)
                                            <a href="#" onclick="confirm_ban('{{route('sellers.ban', $shop->id)}}');" class="dropdown-item">
                                                {{translate('Ban this seller')}}
                                                <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                            </a>
                                        @else
                                            <a href="#" onclick="confirm_unban('{{route('sellers.ban', $shop->id)}}');" class="dropdown-item">
                                                {{translate('Unban this seller')}}
                                                <i class="fa fa-check text-success" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('delete_seller')
                                        <a href="#" class="dropdown-item confirm-delete" data-href="{{route('sellers.destroy', $shop->id)}}" class="">
                                            {{translate('Delete')}}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
              {{ $shops->appends(request()->input())->links() }}
            </div> --}}
            <table id="myTable" {{-- class="table aiz-table mb-0" --}}>
                <thead>
                    <tr>
                        <th>{{__('messages.email_address')}}</th>
                        {{-- <th>{{__('messages.approval')}}</th> --}}
                        <th>Business name</th>
                        <th>Vendor name</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>Joining Date/Time</th>
                        <th>Last Status Update</th>
                        <th width="10%">{{__('messages.options')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ( $sellers as $seller )
                        <tr>
                            <td>{{ $seller->email }}</td>
                            <td>{{ $seller->name }}</td>
                            <td>{{ $seller->business_information ?  $seller->business_information->trade_name :"" }}</td>


                            {{-- <td>
                                @if ($seller->status != "Draft")
                                <!-- Approval status column with toggle switch -->
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input id="vendor-checkbox-{{ $seller->id }}" type="checkbox" class="approval-checkbox" data-vendor-id="{{ $seller->id }}" <?php if($seller->status == 'Enabled') echo "checked";?> onchange="updateSettings(this, 'vendor_approval', {{ $seller->id }})">
                                    <span class="slider round"></span>
                                </label>
                                @endif
                            </td> --}}



                            <td id="status-{{ $seller->id}}"> {{$seller->status}}
                               {{-- Check if there are pending profile changes --}}
                                @if ($seller->checkProposedChanges())
                                {{-- Display a message indicating pending changes --}}
                                <span class="text-danger"> - Changes Pending Approval</span>
                                @endif
                                {{-- @if($seller->status == 'Rejected')
                                    <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                @elseif($seller->steps == 0)
                                    <span class="badge bg-info">{{ __('Draft') }}</span>
                                @else
                                    <span class="badge bg-warning">{{ __('Pending Approval') }}</span>
                                @endif --}}
                            </td>
                            <td>{{ $seller->approved_at ? $seller->approved_at->format('jS F Y, H:i') : '' }}</td>
                            <td id="last-status-update-{{ $seller->id}}"> {{$seller->last_status_update ? $seller->last_status_update->format('jS F Y, H:i') : ''}}
                                {{-- @if($seller->vendor_status_history->isNotEmpty())
                                    {{ $seller->vendor_status_history->sortByDesc('created_at')->first()->created_at->format('jS F Y, H:i') }}
                                @endif --}}
                            </td>
                            <td>

                                <!-- Options column -->
                                <div class="dropdown">
                                    {{-- <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ __('messages.actions') }}
                                    </button> --}}
                                    <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                        <i class="las la-ellipsis-v"></i>
                                    </button>
                                    {{-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"> --}}
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs vendor-action-{{$seller->id}}">
                                        <a href="{{route('vendor.registration.view',$seller->id)}}" class="dropdown-item" >
                                            {{ __('messages.View') }}
                                        </a>
                                        @if ($seller->status != "Draft" && $seller->status !="Closed" && $seller->status !="Pending Approval" && $seller->status !="Rejected" )
                                        {{-- Resubmit Registration --}}
                                        {{-- <button type="button" class="dropdown-item resubmit-registration" data-vendor-id="{{ $seller->id }}">
                                            {{ __('messages.resubmit_registration') }}
                                        </button> --}}

                                        {{-- Suspend Vendor --}}
                                        {{-- <button type="button" class="dropdown-item suspend-vendor-btn" data-vendor-id="{{ $seller->id }}">   {{ __('messages.suspend_vendor') }}</button> --}}
                                        @if ( $seller->status =="Enabled"  )
                                        <a href="{{route('vendors.suspend.view',$seller->id)}}" class="dropdown-item" >
                                            {{ __('messages.suspend_vendor') }}
                                        </a>
                                        @endif
                                        {{-- Pending Closure --}}
                                        @if ($seller->status !="Pending Closure" )
                                           <button type="button" class="dropdown-item {{-- btn btn-warning --}} pending-closure-btn" data-vendor-id="{{ $seller->id }}">{{ __('messages.pending_closure_op') }}</button>
                                        @endif
                                        @if ($seller->status != "Enabled" && $seller->status !="Suspended"  )
                                            {{-- Close Vendor --}}
                                            <button type="button" class="dropdown-item {{-- btn btn-danger --}} close-vendor-btn" data-vendor-id="{{ $seller->id }}">{{ __('messages.close_vendor') }}</button>
                                        @endif
                                        {{-- View Status History --}}
                                        {{-- <button type="button" class="dropdown-item btn btn-info view-status-history-btn" data-vendor-id="{{ $seller->id }}">
                                            View Status History
                                        </button> --}}
                                        @endif
                                        @if ($seller->status != "Draft" && $seller->status !='Pending Approval')
                                        <a href="{{route('vendors.status-history',$seller->id)}}" class="dropdown-item" >
                                            {{ __('messages.View Status History') }}
                                        </a>
                                        <a href="{{route('sellers.staff',$seller->id)}}" class="dropdown-item" >
                                            {{ __('messages.View Staff') }}
                                        </a>
                                        @endif
                                    </div>
                                </div>


                            </td>
                        </tr>

                        @endforeach


                    </tbody>
            </table>
        </div>
    {{-- </form> --}}
</div>

@endsection

@section('modal')
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

    <!-- Bootstrap Modal for Confirmation -->
    <div class="modal fade" id="pendingClosureModal" tabindex="-1" role="dialog" aria-labelledby="pendingClosureModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="pendingClosureModalLabel">Are you sure?</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              This will mark the vendor for pending closure. Are you sure you want to proceed?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" id="confirmPendingClosureBtn">Yes, pending closure!</button>
            </div>
          </div>
        </div>
      </div>
      <!-- Bootstrap Modal for Confirmation -->
    <div class="modal fade" id="closeVendorModal" tabindex="-1" role="dialog" aria-labelledby="closeVendorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="closeVendorModalLabel">Are you sure?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            This will close the vendor's e-shop. Are you sure you want to proceed?
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmCloseVendorBtn">Yes, close it!</button>
            </div>
        </div>
        </div>
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
<!-- Include SweetAlert library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function show_seller_payment_modal(id){
            $.post('{{ route('sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment_modal #payment-modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        function show_seller_profile(id){
            $.post('{{ route('sellers.profile_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#profile_modal #profile-modal-content').html(data);
                $('#profile_modal').modal('show', {backdrop: 'static'});
            });
        }

        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('sellers.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Approved sellers updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

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
        function updateDropDownMenu(vendorId) {
        // Make AJAX call to update the seller's status and refresh the dropdown menu
        $.ajax({
            url: '{{route("update.seller.dropdown")}}', // Replace with your URL
            type: 'POST',
            data: {
                vendor_id: vendorId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Assuming response contains the updated HTML for the dropdown menu
                var updatedHtml = response.html;

                // Update the dropdown menu content with the updated HTML
                $('.dropdown-menu.vendor-action-'+vendorId).html(updatedHtml);
            },
            error: function(xhr, status, error) {
                // Handle error
            }
        });
    }

    </script>

<script type="text/javascript">
    // JavaScript function
    function updateSettings(el, type, vendorId) {
        // Determine the value based on whether the checkbox is checked or not
        var value = $(el).is(':checked') ? 'Enabled' : 'Disabled';

        // Send a POST request to update the vendor's approval status

        $.post("{{ route('vendors.approve', ':id') }}".replace(':id', vendorId), {_token:'{{ csrf_token() }}', type:type, value:value}, function(data){
            // Handle the response from the server
            if(data.success){
                AIZ.plugins.notify('success', '{{ translate('Vendor approval status updated successfully') }}');
                $('#status-' + vendorId).text(data.status); // Update the status cell with the new status

            }
            else{
                AIZ.plugins.notify('danger', 'Something went wrong');
            }
        });
    }
        // Function to uncheck checkbox by vendorId
        function uncheckCheckboxByVendorId(vendorId) {

        // Construct the ID of the checkbox using the vendorId variable
        var checkboxId = "vendor-checkbox-" + vendorId;

        // Uncheck the checkbox with the constructed ID
        $("#" + checkboxId).prop("checked", false);
    }
</script>
<!-- JavaScript code to handle suspension -->
<script>
    $(document).on('click', '.suspend-vendor-btn', function () {
        var vendorId = $(this).data('vendor-id');

        // Display SweetAlert modal
        Swal.fire({
            title: 'Select Suspension Reason',
            input: 'select',
            inputOptions: {
                'Fraud': 'Fraud',
                'Violation of Policies': 'Violation of Policies',
                'Non-compliance': 'Non-compliance',
                'Legal Issues': 'Legal Issues',
                'Non-payment': 'Non-payment',
                'IT Security Concerns': 'IT Security Concerns'
            },
            inputPlaceholder: 'Select a reason',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to select a reason';
                }
            },
            html: '<input id="reason-title" class="swal2-input" placeholder="Reason Title">' +
                  '<textarea id="reason-details" class="swal2-textarea" placeholder="Reason Details"></textarea>',
            showCancelButton: true,
            confirmButtonText: 'Suspend',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // AJAX request to suspend the vendor
                return $.ajax({
                    url: "{{ route('vendors.suspend', ':id') }}".replace(':id', vendorId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reason: $('#swal2-select').val(),
                        reason_title: $('#reason-title').val(),
                        reason_details: $('#reason-details').val()
                    }
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                // Show success message
                Swal.fire('Vendor Suspended', 'The vendor has been suspended successfully', 'success');
                $('#status-' + vendorId).text('Suspended');
                uncheckCheckboxByVendorId(vendorId);


            }
        });
    });
</script>
<!-- JavaScript code to handle status change -->
<script>
    // $(document).on('click', '.pending-closure-btn', function () {
    //     var vendorId = $(this).data('vendor-id');

    //     // Display SweetAlert confirmation dialog
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: 'This will mark the vendor for pending closure. Are you sure you want to proceed?',
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, pending closure!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // Trigger AJAX request to change status to "Pending Closure"
    //             $.ajax({
    //                 url: "{{ route('vendors.pending-closure', ':id') }}".replace(':id', vendorId),
    //                 method: 'POST',
    //                 data: {
    //                     _token: '{{ csrf_token() }}'
    //                 },
    //                 success: function(data) {
    //                     // Show success message
    //                     Swal.fire('Vendor Suspended with Pending Closure', 'The vendor has been successfully suspended with pending closure.', 'success');
    //                     $('#status-' + vendorId).text('Pending Closure');
    //                     $('#last-status-update-' + vendorId).text(data.last_status_update);
    //                     uncheckCheckboxByVendorId(vendorId);
    //                     updateDropDownMenu(vendorId) ;

    //                 },
    //                 error: function(xhr, status, error) {
    //                     // Show error message
    //                     Swal.fire('Error', 'Failed to pending closure the vendor. Please try again later.', 'error');
    //                 }
    //             });
    //         }
    //     });
    // });
    let vendorIdToClose;

$(document).on('click', '.pending-closure-btn', function () {
    vendorIdToClose = $(this).data('vendor-id');
    // Show the Bootstrap modal
    $('#pendingClosureModal').modal('show');
});

// Handle the confirmation button click event
$('#confirmPendingClosureBtn').on('click', function() {
    // Trigger AJAX request to change status to "Pending Closure"
    $.ajax({
        url: "{{ route('vendors.pending-closure', ':id') }}".replace(':id', vendorIdToClose),
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(data) {
            // Show success message
            // Swal.fire('Vendor Suspended with Pending Closure', 'The vendor has been successfully suspended with pending closure.', 'success');
            toastr.success('Vendor Suspended with Pending Closure', 'The vendor has been successfully suspended with pending closure.');
            $('#status-' + vendorIdToClose).text('Pending Closure');
            $('#last-status-update-' + vendorIdToClose).text(data.last_status_update);
            uncheckCheckboxByVendorId(vendorIdToClose);
            updateDropDownMenu(vendorIdToClose);
        },
        error: function(xhr, status, error) {
            // Show error message
            toastr.error('Failed to pending closure the vendor. Please try again later.', 'Error');
            // Swal.fire('Error', 'Failed to pending closure the vendor. Please try again later.', 'error');
        }
    });

    // Hide the confirmation modal after sending the request
    $('#pendingClosureModal').modal('hide');
});
</script>
<script>
    // $(document).on('click', '.close-vendor-btn', function () {
    //     var vendorId = $(this).data('vendor-id');

    //     // Display SweetAlert confirmation dialog
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: 'This will close the vendor\'s e-shop. Are you sure you want to proceed?',
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#d33',
    //         cancelButtonColor: '#3085d6',
    //         confirmButtonText: 'Yes, close it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // Trigger AJAX request to change status to "Closed"
    //             $.ajax({
    //                 url: "{{ route('vendors.close', ':id') }}".replace(':id', vendorId),
    //                 method: 'POST',
    //                 data: {
    //                     _token: '{{ csrf_token() }}'
    //                 },
    //                 success: function(data) {
    //                     // Show success message
    //                     Swal.fire('Vendor Closed', 'The vendor\'s e-shop has been closed successfully', 'success');
    //                     $('#status-' + vendorId).text('Closed');
    //                     $('#last-status-update-' + vendorId).text(data.last_status_update);
    //                     uncheckCheckboxByVendorId(vendorId);
    //                     updateDropDownMenu(vendorId) ;
    //                 },
    //                 error: function(xhr, status, error) {
    //                     // Show error message
    //                     Swal.fire('Error', 'Failed to close the vendor\'s e-shop. Please try again later.', 'error');
    //                 }
    //             });
    //         }
    //     });
    // });
    $(document).on('click', '.close-vendor-btn', function () {
    var vendorId = $(this).data('vendor-id');
    // Show the Bootstrap modal
    $('#closeVendorModal').modal('show');
    });
    $('#confirmCloseVendorBtn').on('click', function() {
    var vendorId = $('.close-vendor-btn').data('vendor-id');
    // Trigger AJAX request to change status to "Closed"
    $.ajax({
        url: "{{ route('vendors.close', ':id') }}".replace(':id', vendorId),
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(data) {
            // Show success message
            // $('#successModal .modal-body').text('The vendor\'s e-shop has been closed successfully');
            // $('#successModal').modal('show');
            // Swal.fire('Vendor Closed', 'The vendor\'s e-shop has been closed successfully', 'success');
            toastr.success('The vendor\'s e-shop has been closed successfully', 'Vendor Closed');

            $('#status-' + vendorId).text('Closed');
            $('#last-status-update-' + vendorId).text(data.last_status_update);
            uncheckCheckboxByVendorId(vendorId);
            updateDropDownMenu(vendorId);
        },
        error: function(xhr, status, error) {
            // Show error message
            // $('#errorModal .modal-body').text('Failed to close the vendor\'s e-shop. Please try again later.');
            // $('#errorModal').modal('show');
            // Swal.fire('Error', 'Failed to close the vendor\'s e-shop. Please try again later.', 'error');
            toastr.error('Failed to close the vendor\'s e-shop. Please try again later.', 'Error');

        }
    });

    // Hide the confirmation modal after sending the request
    $('#closeVendorModal').modal('hide');
});


</script>
<script>
    $(document).on('click', '.resubmit-registration', function() {
        var vendorId = $(this).data('vendor-id');

        // Display SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will resubmit the vendor\'s registration. Are you sure you want to proceed?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, resubmit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to resubmit registration
                $.ajax({
                    url: "{{ route('resubmit.registration', ':id') }}".replace(':id', vendorId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success');
                            $('#status-' + vendorId).text('Rejected');
                            uncheckCheckboxByVendorId(vendorId);

                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'Failed to resubmit registration. Please try again later.', 'error');
                    }
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Fetch and display vendor status history when a button is clicked
            $(document).on('click', '.view-status-history-btn', function() {

            var vendorId = $(this).data('vendor-id');

            // AJAX request to fetch vendor status history
            $.ajax({
                url: "{{ route('vendors.status-history', ':id') }}".replace(':id', vendorId),
                method: 'GET',
                success: function(response) {
               // Format the history data for display in a table
                var historyHtml = '<table class="table"><thead><tr><th>Status</th><th>Reason</th><th>Suspension Reason</th><th>Details</th><th>Date</th></tr></thead><tbody>';
                $.each(response.history, function(index, item) {
                    // Format the date using JavaScript
                    var formattedDate = new Date(item.created_at).toLocaleString();

                    // Check if the details are null and display an empty string if true
                    var details = item.details !== null ? item.details : "";
                    var reason = item.reason !== null ? item.reason : "";
                    var suspension_reason = item.suspension_reason !== null ? item.suspension_reason : "";

                    historyHtml += '<tr><td>' + item.status + '</td><td>' + reason + '</td><td>' + suspension_reason + '</td><td>' + details + '</td><td>' + formattedDate + '</td></tr>';
                });
                historyHtml += '</tbody></table>';

                // Display the history using SweetAlert with customized width
                Swal.fire({
                    title: 'Vendor Status History',
                    html: historyHtml,
                    icon: 'info',
                    customClass: {
                        container: 'swal2-container-custom', // Custom class to apply custom styling
                    },
                    width: '70%', // Customize the width of the SweetAlert dialog box
                    confirmButtonText: 'Close' // Optionally, customize the confirm button text
                });

                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Failed to fetch vendor status history. Please try again later.', 'error');
                }
            });
        });
        $('#myTable').DataTable({
                "order": false
        });



    });
</script>
@endsection
