
@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('Leases')}}</h1>
		</div>
	</div>
</div>

<div id="step1" class="card">
    <div class="card-header">
        <h5 class="mb-0 h6 fw-700 fs-22"><div>{{translate('Current Lease Due')}}</div> <br>
            {{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->start_date)->isoFormat('DD-MMMM-YYYY')}} to
            {{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->end_date)->isoFormat('DD-MMMM-YYYY')}}
        </h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Charge for</th>
                    <th>Amount (AED)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>e-Shop lease</td>
                    <td>{{$current_lease->package->amount}}</td>
                </tr>
                @foreach($current_details as $key => $detail)
                    <tr>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>1 {{$detail->role->name}} Role</td>
                        <td>{{$detail->amount}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-right"><div>Onboarding Discount:</div> <br>
                        <div>Sub Total:</div> <br>
                        <div>VAT:</div> <br>
                        <div>Paid Amount:</div> <br>
                        <div class="fw-700">Total Lease Due:</div>
                    </td>
                    <td class="text-right"><div>-{{$current_lease->discount}} AED</div> <br>
                        <div>{{number_format($current_lease->total-$current_lease->discount,2)}} AED</div> <br>
                        <div>{{ number_format(($current_lease->total - $current_lease->discount) * 0.05, 2) }} AED</div> <br>
                        <div>0.00 AED</div> <br>
                        <div class="fw-700">{{number_format(($current_lease->total-$current_lease->discount)*0.05+($current_lease->total-$current_lease->discount),2)}} AED</div> <br>
                        <div>
                            <button class="btn btn-primary fw-600" disabled>Pay Now</button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@foreach ($leases as $lease)
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6 fw-700 fs-22"><div>{{translate('Lease History')}}</div> <br>
            {{Carbon\Carbon::createFromFormat('Y-m-d', $lease->start_date)->isoFormat('DD-MMMM-YYYY')}} to
            {{Carbon\Carbon::createFromFormat('Y-m-d', $lease->end_date)->isoFormat('DD-MMMM-YYYY')}}
        </h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Charge for</th>
                    <th>Amount (AED)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $lease->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $lease->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>e-Shop lease</td>
                    <td>{{$lease->package->amount}}</td>
                </tr>
                @php
                    $details=App\Models\SellerLeaseDetail::where('lease_id',$lease->id)->get();
                @endphp
                @foreach($details as $key => $detail)
                    <tr>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>1 {{$detail->role->name}} Role</td>
                        <td>{{$detail->amount}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-right"><div>Onboarding Discount:</div> <br>
                        <div>Sub Total:</div> <br>
                        <div>VAT:</div> <br>
                        <div>Paid Amount:</div> <br>
                        <div class="fw-700">Total Lease Due:</div>
                    </td>
                    <td class="text-right"><div>-{{$lease->discount}} AED</div> <br>
                        <div>{{number_format($lease->total-$lease->discount,2)}} AED</div> <br>
                        <div>{{ number_format(($lease->total - $lease->discount) * 0.05, 2) }} AED</div> <br>
                        <div>0.00 AED</div> <br>
                        <div class="fw-700">{{number_format(($lease->total-$lease->discount)*0.05+($lease->total-$lease->discount),2)}} AED</div> <br>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@endforeach
@endsection

@section('script')

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
            window.location.href = '{{ route("seller.sales.index") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(11);
    });
</script>
@endsection

