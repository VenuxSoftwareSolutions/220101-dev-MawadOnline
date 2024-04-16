@extends('seller.layouts.app')
@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Purchase Package List') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Purchase Package') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <table id="step1" class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th width="30%">{{ translate('Package')}}</th>
                        <th data-breakpoints="md">{{ translate('Package Price')}}</th>
                        <th data-breakpoints="md">{{ translate('Payment Type')}}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($seller_packages_payment as $key => $payment)
                        <tr>
                            <td>{{ ($key+1) + ($seller_packages_payment->currentPage() - 1) * $seller_packages_payment->perPage() }}</td>
                            <td>{{ $payment->seller_package->name ?? translate('Package Unavailable') }}</td>
                            <td>{{ $payment->seller_package->amount ?? translate('Package Unavailable') }}</td>
                            <td>
                                @if($payment->offline_payment == 1)
                                    {{ translate('Offline Payment') }}
                                @else
                                    {{ translate('Online Payment') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $seller_packages_payment->links() }}
          	</div>
        </div>
    </div>

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
                window.location.href = '{{ route("seller.staffs.index") }}';
                sleep(60000);
                }

                //tour.exit();
            });

        tour.start();
        tour.goToStepNumber(9);
        });
    </script>
@endsection

