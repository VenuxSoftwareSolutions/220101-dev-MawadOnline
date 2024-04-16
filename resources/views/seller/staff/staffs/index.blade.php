@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Staffs')}}</h1>
		</div>

            <div class="col-md-6 text-md-right">
                <a id="step2" href="{{ route('seller.staffs.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New Staffs')}}</span>
                </a>
            </div>

	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Staffs')}}</h5>
    </div>
    <div class="card-body">
        <table id="step1" class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg" width="10%">#</th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Email')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th data-breakpoints="lg">{{translate('Roles')}}</th>
                    <th data-breakpoints="lg">{{__('Enabled')}}</th>
                    <th width="10%" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffs as $key => $staff)
                    @if($staff->user != null)

                        <tr>
                            <td>{{ ($key+1) + ($staffs->currentPage() - 1)*$staffs->perPage() }}</td>
                            <td>{{$staff->user->name}}</td>
                            <td>{{$staff->user->email}}</td>
                            <td>{{$staff->user->phone}}</td>
                            <td>
                                @foreach ($staff->user->getRoleNames() as $name)
                                {{ $name }}
                                <br>
                                @endforeach

							</td>
                            @php
                               // dd($staff->user->getRoleNames());
                            @endphp
                            <td>
                                <!-- Approval status column with toggle switch -->
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input id="vendor-checkbox-{{ $staff->user->id }}" type="checkbox" class="approval-checkbox" data-vendor-id="{{ $staff->user->id }}" <?php if($staff->user->status == 'Enabled') echo "checked";?> onchange="updateSettings(this, 'vendor_approval', {{ $staff->user->id }})">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-right">

                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('seller.staffs.edit', encrypt($staff->id))}}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>

                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('seller.staffs.destroy', $staff->id)}}" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>

                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $staffs->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
 <script>

        // Handle click event for select all checkboxes
            function updateSettings(el, type, vendorId) {
            // Determine the value based on whether the checkbox is checked or not
            var value = $(el).is(':checked') ? 'Enabled' : 'Disabled';

            // Send a POST request to update the vendor's approval status

            $.post("{{ route('seller.staff.approve', ':id') }}".replace(':id', vendorId), {_token:'{{ csrf_token() }}', type:type, value:value}, function(data){
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
            window.location.href = '{{ route("seller.lease.index") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(10);
    });
</script>
 @endsection
