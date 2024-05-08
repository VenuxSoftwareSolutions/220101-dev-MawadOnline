@extends('auth.layouts.authentication')

@section('content')
<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha3/0.9.3/sha3.min.js"></script>

<script>
    function onSubmit(token) {

        $('.login_btn').hide();
        $('.loading_btn').show();

        // Get email and password values
        var email = $('#login_form input[name="email"]').val();
        var password = $('#login_form input[name="password"]').val();
        var passwordConfirmation = $('#login_form input[name="password_confirmation"]').val();
        var code = $('#login_form input[name="code"]').val();

        // Validate email and password
         // Validate email and password
         if (!email || !code || !password || !passwordConfirmation) {
            // If email or password is empty, display an error message
            $('#error_message').text('Email, code, password, and password confirmation are required.').show();
            $('.login_btn').show();
            $('.loading_btn').hide();
            return; // Stop further execution
        } else {
            // If both fields are filled, hide the error message
            $('#error_message').hide();
        }
        // Construct the URL dynamically
        var url = '{{ route("generateSalt", ["email" => ":email"]) }}';
        url = url.replace(':email', email);
        // Perform AJAX request
        $.ajax({
            type: 'GET',
            url: url,
            headers: {
                'Platform-key': '{{ Config('app.system_key') }}',
                'mobile-version': '{{ Config('api.mobile_version') }}',
            },
            success: function(response) {
                var hashedPassword = hashPass(email, $('#password').val(), response.salt, response.num_hashing_rounds);
                $('#password').val(hashedPassword);
                $('#password-confirm').val(hashedPassword);

                document.getElementById("login_form").submit();

            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error('Error submitting form:', error);
                // Optionally, you can show an error message to the user
            }
        });
    }

    function hashPass(username, password, salt, rounds) {
        let hash = username;
        for (var i = 0; i < rounds; i++) {
            hash = password + salt + hash;
            hash = sha3_512(hash);
        }
        return hash;
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
                                <img src="{{ uploaded_asset(get_setting('password_reset_page_image')) }}" alt="{{ translate('Password Reset Page Image') }}" class="img-fit h-100">
                            </div>

                            <div class="col-lg-6 p-4 p-lg-5 d-flex flex-column justify-content-center border right-content" style="height: auto;">
                                <!-- Site Icon -->
                                <div class="size-48px mb-3 mx-auto mx-lg-0">
                                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                                </div>

                                <!-- Titles -->
                                <div class="text-center text-lg-left">
                                    <h1 class="fs-20 fs-md-20 fw-700 text-primary" style="text-transform: uppercase;">{{ translate('Reset Password') }}</h1>
                                    <h5 class="fs-14 fw-400 text-dark">
                                        {{ translate('Enter your email address and new password and confirm password.') }}
                                    </h5>
                                </div>
                                <div id="error_message" class="text-danger mb-3" style="display: none;"></div>

                                <!-- Reset password form -->
                                <div class="pt-3">
                                    <div class="">
                                        <form class="form-default" id="login_form" role="form" action="{{ route('password.update') }}" method="POST">
                                            @csrf

                                            <!-- Email -->
                                            <div class="form-group">
                                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" placeholder="{{ translate('Email') }}" required autofocus>

                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Code -->
                                            <div class="form-group">
                                                <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ $email ?? old('code') }}" placeholder="{{translate('Code')}}" required autofocus>

                                                @if ($errors->has('code'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Password -->
                                            <div class="form-group">
                                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ translate('New Password') }}" required>

                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Password Confirmation-->

                                            <div class="form-group">
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ translate('Reset Password') }}" required>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="mb-4 mt-4">

                                                <button data-sitekey="{{ config('services.recaptcha_v3.siteKey') }}" data-callback="onSubmit" data-action="submitLoginForm" class="g-recaptcha btn btn-primary btn-block fw-700 fs-14 rounded-0">
                                                    <span class="login_btn">{{ translate('Reset Password') }}</span>
                                                    <div class="loading_btn" style="display: none;">
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Go Back -->
                        <div class="mt-3 mr-4 mr-md-0">
                            <a href="{{ url()->previous() }}" class="ml-auto fs-14 fw-700 d-flex align-items-center text-primary" style="max-width: fit-content;">
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
