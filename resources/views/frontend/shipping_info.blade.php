@extends('frontend.layouts.app')

@section('content')
    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done-mwd">
                            <div class="text-center border border-bottom-6px p-2 text-success-mwd">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 font-prompt-md d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-map cart-animate"
                                    style="margin-right: -100px; transition: 2s;"></i>
                                <h3 class="fs-14 font-prompt-md600 d-none d-lg-block">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-truck"></i>
                                <h3 class="fs-14 font-prompt-md d-none d-lg-block opacity-50">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                                <h3 class="fs-14 font-prompt-md d-none d-lg-block opacity-50">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                                <h3 class="fs-14 font-prompt-md d-none d-lg-block opacity-50">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shipping Info -->
    <section class="mb-4 gry-bg">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-xxl-8 col-xl-10 mx-auto">
                    <form class="form-default shipping-info-form__clz" data-toggle="validator"
                        action="{{ route('checkout.store_shipping_infostore') }}" role="form" method="POST">
                        @csrf
                        @if (Auth::check())
                            <div class="border bg-white p-4 mb-4 border-radius-16">
                                @if (Auth::user()->addresses->isEmpty())
                                <div class="row">
                                    <div class="col-xl-8 mx-auto">
                                        <div class="px-4 py-0">
                                            <!-- Empty cart -->
                                            <div class="text-center p-3">
                                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.4209 12.9999C9.50086 12.4599 8.88086 11.4599 8.88086 10.3099C8.88086 8.58994 10.2709 7.18994 12.0009 7.18994C13.1509 7.18994 14.1509 7.80994 14.6909 8.73994" stroke="#DBDCDF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M5.9891 17.8101C4.1491 15.3001 2.8091 12.0901 3.6291 8.49011C5.2791 1.23011 14.5691 0.0601072 18.6791 4.98011" stroke="#DBDCDF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M20.3806 8.5C21.5306 13.58 18.3706 17.88 15.6006 20.54C13.5906 22.48 10.4106 22.48 8.39062 20.54" stroke="#DBDCDF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M22 2L2 22" stroke="#DBDCDF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                <h3 class="h4 font-prompt-md dark-c3 mt-3">No addresses found.</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                @foreach (Auth::user()->addresses as $key => $address)
                                    <div class="border mb-4  border-radius-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="aiz-megabox d-block mb-0">
                                                    <input type="radio" name="address_id" value="{{ $address->id }}"
                                                        @if ($address->set_default) checked @endif required>
                                                    <span class="d-flex p-3 aiz-megabox-elem border-0">
                                                        <!-- Checkbox -->
                                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                        <!-- Address -->
                                                        <span class="flex-grow-1 pl-3 text-left">
                                                            <div class="row">
                                                                <span
                                                                    class="fs-14 font-prompt text-secondary col-3">{{ translate('Address') }}</span>
                                                                <span
                                                                    class="fs-14 font-prompt text-dark fw-500 ml-2 col">{{ $address->address }}</span>
                                                            </div>
                                                            {{-- <div class="row">
                                                            <span class="fs-14 text-secondary col-3">{{ translate('Postal Code') }}</span>
                                                            <span class="fs-14 text-dark fw-500 ml-2 col">{{ $address->postal_code }}</span>
                                                        </div> --}}
                                                        <div class="row">
                                                            <span class="fs-14 font-prompt text-secondary col-3">{{ translate('City') }}</span>
                                                            <span class="fs-14 font-prompt text-dark fw-500 ml-2 col">{{ optional($address->emirate)->name }}</span>
                                                        </div>
                                                        <div class="row">
                                                            <span class="fs-14 font-prompt text-secondary col-3">{{ translate('State') }}</span>
                                                            <span class="fs-14 font-prompt text-dark fw-500 ml-2 col">{{ optional($address->state)->name }}</span>
                                                        </div>
                                                        <div class="row">
                                                            <span class="fs-14 font-prompt text-secondary col-3">{{ translate('Country') }}</span>
                                                            <span class="fs-14 font-prompt text-dark fw-500 ml-2 col">{{ optional($address->country)->name }}</span>
                                                        </div>
                                                        <div class="row">
                                                            <span class="fs-14 font-prompt text-secondary col-3">{{ translate('Phone') }}</span>
                                                            <span class="fs-14 font-prompt text-dark fw-500 ml-2 col">{{ $address->phone }}</span>
                                                        </div>
                                                    </span>
                                                </label>
                                            </div>
                                            <!-- Edit Address Button -->
                                            <div class="col-md-4 p-3 text-right">
                                                <a class="btn btn-secondary-base btn-ori-40 text-white border-radius-12 fs-15 font-prompt py-2 mr-4 px-4"
                                                    onclick="edit_address('{{ $address->id }}')">{{ translate('Change') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @endif

                                <input type="hidden" name="checkout_type" value="logged">
                                <!-- Add New Address -->
                                <div class="mb-4 d-flex justify-content-center">
                                    <div class="col-6 col-md-4 btn c-pointer bg-light has-transition hov-bg-soft-light dark-c3 border-radius-12 py-2 d-flex justify-content-center h-45px"
                                        onclick="add_new_address()">
                                        <i class="las la-plus add-loc-icon"></i>
                                        <div class="fs-16 font-prompt ml-2">{{ translate('Add New Address') }}</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <!-- Return to shop -->
                                    <div class="col-md-6 text-center text-md-left order-1 order-md-0 d-none d-md-block">
                                        <a href="{{ route('home') }}" class="btn btn-white cart-drop-btn-checkout text-secondary-base border-radius-12 fs-16 font-prompt py-2">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.9998 19.92L8.47984 13.4C7.70984 12.63 7.70984 11.37 8.47984 10.6L14.9998 4.08002" stroke="#cb774b" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            {{ translate('Return to shop') }}
                                        </a>
                                    </div>
                                    <div class="col-md-6 text-center text-md-left order-1 order-md-0 d-block d-md-none">
                                        <a href="{{ route('home') }}" class="btn btn-link fs-16 text-secondary-base font-prompt pt-3 px-0">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.9998 19.92L8.47984 13.4C7.70984 12.63 7.70984 11.37 8.47984 10.6L14.9998 4.08002" stroke="#cb774b" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            {{ translate('Return to shop') }}
                                        </a>
                                    </div>
                                    <!-- Continue to Delivery Info -->
                                    <div class="col-md-6 text-center text-md-right">
                                        <button type="submit"
                                            class="btn btn-secondary-base cart-drop-btn-vcart text-white border-radius-12 fs-16 font-prompt py-2">{{ translate('Continue to Delivery Info') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Address Modal -->
    @include('frontend.' . get_setting('homepage_select') . '.partials.address_modal')
@endsection
