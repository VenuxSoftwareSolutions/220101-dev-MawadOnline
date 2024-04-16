@extends('seller.layouts.app')

@section('panel_content')
    <section class="py-8 bg-soft-primary">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto text-center">
                    <h1 class="mb-0 fw-700">{{ translate('Premium Packages for Sellers') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row row-cols-xxl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 gutters-10 justify-content-center">
                @foreach ($seller_packages as $key => $seller_package)
                    <div id="step1" class="col">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="text-center mb-4 mt-3">
                                    <img class="mw-100 mx-auto mb-4" src="{{ uploaded_asset($seller_package->logo) }}"
                                        height="100">
                                    <h5 class="mb-3 h5 fw-600">{{ $seller_package->getTranslation('name') }}</h5>
                                </div>
                                <ul class="list-group list-group-raw fs-15 mb-5">
                                    <li class="list-group-item py-2">
                                        <i class="las la-check text-success mr-2"></i>
                                        {{ $seller_package->product_upload_limit }} {{ translate('Product Upload Limit') }}
                                    </li>
                                </ul>
                                <div class="mb-5 d-flex align-items-center justify-content-center">
                                    @if ($seller_package->amount == 0)
                                        <span class="fs-30 fw-600 lh-1 mb-0">{{ translate('Free') }}</span>
                                    @else
                                        <span
                                            class="fs-30 fw-600 lh-1 mb-0">{{ single_price($seller_package->amount) }}</span>
                                    @endif
                                    <span
                                        class="text-secondary border-left ml-2 pl-2">{{ $seller_package->duration }}<br>{{ translate('Days') }}</span>
                                </div>

                                <div class="text-center">
                                    @if ($seller_package->amount == 0)
                                        <button class="btn btn-primary fw-600"
                                            onclick="get_free_package({{ $seller_package->id }})">{{ translate('Free Package') }}</button>
                                    @else
                                        @if (addon_is_activated('offline_payment'))
                                            <button id="step2" class="btn btn-primary fw-600"
                                                onclick="select_payment_type({{ $seller_package->id }})">{{ translate('Purchase Package') }}</button>
                                        @else
                                            <button id="step2" class="btn btn-primary fw-600"
                                                onclick="show_price_modal({{ $seller_package->id }})">{{ translate('Purchase Package') }}</button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Select Payment Type Modal -->
    <div class="modal fade" id="select_payment_type_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Select Payment Type') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="package_id" name="package_id" value="">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Payment Type') }}</label>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <select class="form-control aiz-selectpicker" onchange="payment_type(this.value)"
                                    data-minimum-results-for-search="Infinity">
                                    <option value="">{{ translate('Select One') }}</option>
                                    <option value="online">{{ translate('Online payment') }}</option>
                                    <option value="offline">{{ translate('Offline payment') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-sm btn-primary transition-3d-hover mr-1"
                            id="select_type_cancel" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Online payment Modal-->
    <div class="modal fade" id="price_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Purchase Your Package') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="" id="package_payment_form" action="{{ route('seller_packages.purchase') }}"
                    method="post">
                    @csrf
                    <input type="hidden" name="seller_package_id" value="">
                    <div class="modal-body" style="overflow-y: unset;">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Payment Method') }}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        name="payment_option">
                                        @if (get_setting('paypal_payment') == 1)
                                            <option value="paypal">{{ translate('Paypal') }}</option>
                                        @endif
                                        @if (get_setting('stripe_payment') == 1)
                                            <option value="stripe">{{ translate('Stripe') }}</option>
                                        @endif
                                        @if (get_setting('mercadopago_payment') == 1)
                                            <option value="mercadopago">{{ translate('Mercadopago') }}</option>
                                            <option value="paypal">{{ translate('Paypal') }}</option>
                                        @endif
                                        @if (get_setting('toyyibpay_payment') == 1)
                                            <option value="toyyibpay">{{ translate('ToyyibPay') }}</option>
                                        @endif
                                        @if (get_setting('sslcommerz_payment') == 1)
                                            <option value="sslcommerz">{{ translate('sslcommerz') }}</option>
                                        @endif
                                        @if (get_setting('instamojo_payment') == 1)
                                            <option value="instamojo">{{ translate('Instamojo') }}</option>
                                        @endif
                                        @if (get_setting('razorpay') == 1)
                                            <option value="razorpay">{{ translate('RazorPay') }}</option>
                                        @endif
                                        @if (get_setting('paystack') == 1)
                                            <option value="paystack">{{ translate('PayStack') }}</option>
                                        @endif
                                        @if (get_setting('payhere') == 1)
                                            <option value="payhere">{{ translate('Payhere') }}</option>
                                        @endif
                                        @if (get_setting('ngenius') == 1)
                                            <option value="ngenius">{{ translate('Ngenius') }}</option>
                                        @endif
                                        @if (get_setting('iyzico') == 1)
                                            <option value="iyzico">{{ translate('Iyzico') }}</option>
                                        @endif
                                        @if (get_setting('nagad') == 1)
                                            <option value="nagad">{{ translate('Nagad') }}</option>
                                        @endif
                                        @if (get_setting('bkash') == 1)
                                            <option value="bkash">{{ translate('Bkash') }}</option>
                                        @endif
                                        @if (get_setting('aamarpay') == 1)
                                            <option value="aamarpay">{{ translate('Amarpay') }}</option>
                                        @endif
                                        @if (addon_is_activated('african_pg'))
                                            @if (get_setting('mpesa') == 1)
                                                <option value="mpesa">{{ translate('Mpesa') }}</option>
                                            @endif
                                            @if (get_setting('flutterwave') == 1)
                                                <option value="flutterwave">{{ translate('Flutterwave') }}</option>
                                            @endif
                                            @if (get_setting('payfast') == 1)
                                                <option value="payfast">{{ translate('PayFast') }}</option>
                                            @endif
                                        @endif
                                        @if (addon_is_activated('paytm'))
                                            @if (get_setting('myfatoorah') == 1)
                                                <option value="myfatoorah">{{ translate('MyFatoorah') }}</option>
                                            @endif
                                            @if (get_setting('khalti_payment') == 1)
                                                <option value="khalti">{{ translate('Khalti') }}</option>
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-sm btn-secondary transition-3d-hover mr-1"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit"
                                class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ translate('Confirm') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- offline payment Modal -->
    <div class="modal fade" id="offline_seller_package_purchase_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{ translate('Offline Package Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="offline_seller_package_purchase_modal_body"></div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function select_payment_type(id) {
            $('input[name=package_id]').val(id);
            $('#select_payment_type_modal').modal('show');
        }

        function payment_type(type) {
            var package_id = $('#package_id').val();
            if (type == 'online') {
                $("#select_type_cancel").click();
                show_price_modal(package_id);
            } else if (type == 'offline') {
                $("#select_type_cancel").click();
                $.post('{{ route('seller.offline_seller_package_purchase_modal') }}', {
                    _token: '{{ csrf_token() }}',
                    package_id: package_id
                }, function(data) {
                    $('#offline_seller_package_purchase_modal_body').html(data);
                    $('#offline_seller_package_purchase_modal').modal('show');
                });
            }
        }

        function show_price_modal(id) {
            $('input[name=seller_package_id]').val(id);
            $('#price_modal').modal('show');
        }

        function get_free_package(id) {
            $('input[name=seller_package_id]').val(id);
            $('#package_payment_form').submit();
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
                window.location.href = '{{ route("seller.packages_payment_list") }}';
                sleep(60000);
                }

                //tour.exit();
            });

        tour.start();
        tour.goToStepNumber(8);
        });
    </script>
@endsection
