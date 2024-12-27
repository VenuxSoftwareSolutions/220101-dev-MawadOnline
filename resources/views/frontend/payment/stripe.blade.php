@extends('frontend.layouts.app')

@section('meta_title', 'Stripe Payment')

@section('style')
    <style>
         .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            margin: auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        #payment-form {
            max-width: 400px;
            margin: 50px auto;
        }
        #card-element {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .error {
            color: red;
            margin-top: 5px;
        }
    </style>
@endsection

@section('content')
<section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-credit-card cart-animate"
                                    style="margin-right: -100px; transition: 2s;"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-lg-12">
                    <div id="payment-form">
                        <div id="spinner-wrapper" class="d-none c-preloader text-center p-3">
                            <i class="las la-spinner la-spin la-3x"></i>
                        </div>
                        <input type="email" id="email" placeholder="{{ __('Email Address') }}" class="form-control mb-2" />
                        <div id="card-element">
                            <div class="c-preloader text-center p-3">
                                <i class="las la-spinner la-spin la-3x"></i>
                            </div>
                        </div>
                        <input type="text" id="name" placeholder="{{ __('Full Name') }}" class="form-control my-2" />
                        <button id="submit-button" class="form-control btn btn-primary">{{ __("Pay") }} {{ single_price($amount / 100) }}</button>
                        <div id="error-message" class="error"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const stripe = Stripe('{{ env('STRIPE_KEY') }}');
            const elements = stripe.elements();

            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                    },
                },
                hidePostalCode: true,
            });

            cardElement.mount('#card-element');

            const submitButton = document.getElementById('submit-button');
            const errorMessage = document.getElementById('error-message');
            const spinnerWrapper = document.getElementById("spinner-wrapper");

            submitButton.addEventListener('click', async function () {
                spinnerWrapper.classList.remove("d-none");
                errorMessage.textContent = '';
                submitButton.disabled = true;

                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;

                if (!name || !email) {
                    AIZ.plugins.notify('danger', '{{ __("Please provide your name and email.") }}');
                    submitButton.disabled = false;
                    spinnerWrapper.classList.add("d-none");
                    return;
                }

                try {
                    spinnerWrapper.classList.remove("d-none");

                    const response = await fetch('{{ route("stripe.create_payment_intent") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ name, email, amount: {{ $amount }} }),
                    });

                    const { client_secret } = await response.json();

                    if (client_secret === undefined) {
                        spinnerWrapper.classList.add("d-none");
                        throw new Error("No client secret returned from server.");
                    }

                    let return_url = '{{ route("stripe.success") }}';

                    const { paymentIntent, error } = await stripe.confirmCardPayment(client_secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: name,
                                email: email,
                            },
                        },
                        return_url
                    });

                    if (error !== undefined) {
                        AIZ.plugins.notify('danger', error.message);
                        submitButton.disabled = false;
                        spinnerWrapper.classList.add("d-none");

                        fetch("{{ route('cancel_checkout', ["combined_order_id" => $client_reference_id]) }}", {
                            method: "DELETE",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                        }).then(response => response.json())
                        .then((data) => {
                            if (data.error === false) {
                                location.href = "/cart";
                            }
                        }).catch((error) => {
                            AIZ.plugins.notify('danger', '{{ __("Something went wrong!") }}')
                        });
                    } else {
                        spinnerWrapper.classList.add("d-none");
                        location.href = `${return_url}?payment_intent=${paymentIntent.id}`;
                    }
                } catch (error) {
                    AIZ.plugins.notify('danger', '{{ __("An error occurred. Please try again.") }}')

                    spinnerWrapper.classList.add("d-none");
                    submitButton.disabled = false;
                }
            });
        });
    </script>
@endsection
