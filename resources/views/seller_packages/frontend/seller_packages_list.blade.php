@extends('seller.layouts.app')
@push('styles')
<style>
.card {
    border: 2px solid #ccc;
    height: 100%; /* Ensures the card body takes up full card height */
}
.card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* This will push your button to the bottom */
}
.card .card-body {
    padding: 20px 2px;
}
@media (max-width: 768px) {
    .col {
        margin-bottom: 3rem !important ;
    }
}


</style>

@endpush
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
            <div class="row row-cols-xxl-3 row-cols-lg-3 row-cols-md-2 row-cols-1 gutters-10 justify-content-center">
                    <div class="col">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="text-center mb-4 mt-3">
                                    <h5 class="mb-1 h5 fw-600">{{ $seller_packages[0]->getTranslation('name') }}</h5>
                                </div>
                                <div class="mb-3 d-flex align-items-center justify-content-center">
                                    @if ($seller_packages[0]->amount == 0)
                                        <span class="fs-30 fw-600 lh-1 mb-0">-</span>
                                    @else
                                        <span
                                            class="fs-30 fw-700 lh-1 mb-0">AED {{ $seller_packages[0]->amount }} / month</span>
                                    @endif
                                    {{-- <span
                                        class="text-secondary border-left ml-2 pl-2">{{ $seller_packages->duration }}<br>{{ translate('Days') }}</span> --}}
                                </div>

                                <ul class="list-group list-group-raw fs-13 mb-5">
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                        {{ translate('Full access to all e-Shop essentials') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Unlimited products') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Free e-Shop adminstrator + 4 positions') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Additional staff position just for AED 10/month') }}
                                    </li>
                                </ul>

                                <div class="text-center">
                                    {{-- @if ($seller_packages->amount == 0)
                                        <button class="btn btn-primary fw-600"
                                            onclick="get_free_package({{ $seller_packages->id }})">{{ translate('Free Package') }}</button>
                                    @else
                                        @if (addon_is_activated('offline_payment'))
                                            <button class="btn btn-primary fw-600"
                                                onclick="select_package({{ $seller_packages->id }})">{{ translate('Purchase Package') }}</button>
                                        @else
                                            <button class="btn btn-primary fw-600"
                                                onclick="show_price_modal({{ $seller_packages->id }})">{{ translate('Purchase Package') }}</button>
                                        @endif
                                    @endif --}}
                                    <button class="btn btn-primary fw-600 col-10"
                                                onclick="select_package({{ $seller_packages[0]->id }})">{{ translate('Register your e-Shop') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="text-center mb-4 mt-3">
                                    <h5 class="mb-1 h5 fw-600">{{ $seller_packages[1]->getTranslation('name') }}</h5>
                                </div>
                                <div class="mb-3 d-flex align-items-center justify-content-center">
                                    @if ($seller_packages[1]->amount == 0)
                                        <span class="fs-30 fw-600 lh-1 mb-0">-</span>
                                    @else
                                        <span
                                            class="fs-32 fw-700 lh-1 mb-0">AED {{ $seller_packages[1]->amount }} / month</span>
                                    @endif
                                    {{-- <span
                                        class="text-secondary border-left ml-2 pl-2">{{ $seller_packages->duration }}<br>{{ translate('Days') }}</span> --}}
                                </div>
                                <ul class="list-group list-group-raw fs-13 mb-5">
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                        {{ translate('Everything in the Pro Plan') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Customized e-Shop roles') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Marketing analytics') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('And much more...') }}
                                    </li>
                                </ul>

                                <div class=" text-center">
                                    {{-- @if ($seller_packages->amount == 0)
                                        <button class="btn btn-primary fw-600"
                                            onclick="get_free_package({{ $seller_packages->id }})">{{ translate('Free Package') }}</button>
                                    @else
                                        @if (addon_is_activated('offline_payment'))
                                            <button class="btn btn-primary fw-600"
                                                onclick="select_package({{ $seller_packages->id }})">{{ translate('Purchase Package') }}</button>
                                        @else
                                            <button class="btn btn-primary fw-600"
                                                onclick="show_price_modal({{ $seller_packages->id }})">{{ translate('Purchase Package') }}</button>
                                        @endif
                                    @endif --}}
                                    <button class="btn btn-primary fw-600 col-10" disabled>{{ translate('Coming soon') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </section>
    {{-- <section class="py-4 py-lg-5">
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
    </section> --}}
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
                window.location.href = '{{ route("seller.orders.index") }}'; // Redirect to another page
                sleep(60000);
                }
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
