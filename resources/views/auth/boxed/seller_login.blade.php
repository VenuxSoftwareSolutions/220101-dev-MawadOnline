@extends('auth.layouts.authentication')

@section('content')
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        function onSubmit(token) {
            document.getElementById("login_form").submit();
        }
    </script>
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column justify-content-md-center bg-white">
        <section class="bg-white overflow-hidden">
            <div class="row">
                <div class="col-xxl-6 col-xl-9 col-lg-10 col-md-7 mx-auto py-lg-4">
                    <div class="card shadow-none rounded-0 border-0">
                        <div class="row no-gutters">
                            <!-- Left Side Image-->
                            <div class="col-lg-6">
                                <img src="{{ uploaded_asset(get_setting('seller_login_page_image')) }}"
                                     alt="{{ translate('Seller Login Page Image') }}" class="img-fit h-100">
                            </div>

                            <!-- Right Side -->
                            <div
                                class="col-lg-6 p-4 p-lg-5 d-flex flex-column justify-content-center border right-content"
                                style="height: auto;">
                                <!-- Site Icon -->
                                <div class="size-48px mb-3 mx-auto mx-lg-0">
                                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}"
                                         alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                                </div>

                                <!-- Titles -->
                                <div class="text-center text-lg-left">
                                    <h1 class="fs-20 fs-md-24 fw-700 text-primary"
                                        style="text-transform: uppercase;">{{ translate('Welcome Back !')}}</h1>
                                    <h5 class="fs-14 fw-400 text-dark">{{ translate('Login To Your Seller Account')}}</h5>
                                </div>
                                <!-- Login form -->
                                <div class="pt-3">
                                    <div class="">

                                        <form class="form-default" id="login_form" role="form"
                                              action="{{ route('seller.login_seller') }}" method="POST">
                                            @csrf

                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="text-danger" role="alert">
                                                        <strong>Please check your email for otp code request !</strong>
                                                    </span>
                                            @endif
                                            <div class="form-group">
                                                <label for="email"
                                                       class="fs-12 fw-700 text-soft-dark">{{  translate('Email') }}</label>
                                                <input type="email"
                                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} rounded-0"
                                                       value="{{ old('email') }}"
                                                       placeholder="{{  translate('johndoe@example.com') }}"
                                                       name="email" id="email" autocomplete="off">
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- password -->
                                            <div class="form-group">
                                                <label for="password"
                                                       class="fs-12 fw-700 text-soft-dark">{{  translate('Password') }}</label>
                                                <div class="position-relative">
                                                    <input type="password"
                                                           class="form-control rounded-0 {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                           placeholder="{{ translate('Password')}}" name="password"
                                                           id="password">
                                                    <i class="password-toggle las la-2x la-eye"></i>
                                                </div>
                                            </div>

                                            @if ($errors->has('g-recaptcha-response') || $errors->has('otp_code'))
                                                <div class="form-group">
                                                    <label for="password"
                                                           class="fs-12 fw-700 text-soft-dark">{{  translate('Otp code') }}</label>
                                                    <div class="position-relative">
                                                        <input type="text"
                                                               class="form-control rounded-0 {{ $errors->has('otp_code') ? ' is-invalid' : '' }}"
                                                               placeholder="{{ translate('OTP code')}}" name="otp_code"
                                                               id="otp_code">
                                                        @if ($errors->has('otp_code'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('otp_code') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row mb-2">
                                                <!-- Remember Me -->
                                                <div class="col-6">
                                                    <label class="aiz-checkbox">
                                                        <input type="checkbox"
                                                               name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                        <span
                                                            class="has-transition fs-12 fw-400 text-gray-dark hov-text-primary">{{  translate('Remember Me') }}</span>
                                                        <span class="aiz-square-check"></span>
                                                    </label>
                                                </div>
                                                <!-- Forgot password -->
                                                <div class="col-6 text-right">
                                                    <a href="{{ route('password.request') }}"
                                                       class="text-reset fs-12 fw-400 text-gray-dark hov-text-primary"><u>{{ translate('Forgot password?')}}</u></a>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="mb-4 mt-4">
                                                <button
                                                    class="g-recaptcha btn btn-primary btn-block fw-700 fs-14 rounded-0"
                                                    data-sitekey="{{ config('services.recaptcha_v3.siteKey') }}"
                                                    data-callback="onSubmit"
                                                    data-action="submitLoginForm">
                                                    {{  translate('Login') }}
                                                </button>
                                            </div>
                                        </form>

                                        <!-- DEMO MODE -->
                                        @if (env("DEMO_MODE") == "On")
                                            <div class="mb-4">
                                                <table class="table table-bordered mb-0">
                                                    <tbody>
                                                    <tr>
                                                        <td>{{ translate('Seller Account')}}</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-info btn-sm"
                                                                    onclick="autoFillSeller()">{{ translate('Copy credentials') }}</button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Register Now -->
                                    <p class="fs-12 text-gray mb-0">
                                        {{ translate('Dont have an account?')}}
                                        <a href="{{ route('shops.packages') }}"
                                           class="ml-2 fs-14 fw-700 animate-underline-primary">{{ translate('Register Now')}}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Go Back -->
                        <div class="mt-3 mr-4 mr-md-0">
                            <a href="{{ url()->previous() }}"
                               class="ml-auto fs-14 fw-700 d-flex align-items-center text-primary"
                               style="max-width: fit-content;">
                                <i class="las la-arrow-left fs-20 mr-1"></i>
                                {{ translate('Back to Previous Page')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')

    <script type="text/javascript">
        function autoFillSeller() {
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }
    </script>
@endsection
