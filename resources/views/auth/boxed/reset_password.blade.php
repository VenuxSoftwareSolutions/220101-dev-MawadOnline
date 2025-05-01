@extends('auth.layouts.authentication')

<style>
    #password-strength {
     margin-top: 10px;
    /* padding: 10px; */
    /* border: 1px solid #ddd; */
     border-radius: 5px;
    }

#password-strength p {
    margin: 5px 0;
}

#password-strength.valid {
    border-color: #4caf50;
    background-color: #dff0d8;
}
</style>


@section('content')
<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha3/0.9.3/sha3.min.js"></script>

<script>
    var $passwordCheckedCondition=false ;
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
        } else if (password !== passwordConfirmation) {
            // If password and password confirmation do not match, display an error message
            $('#error_message').text('Password and password confirmation do not match.').show();
            $('.login_btn').show();
            $('.loading_btn').hide();
            return; // Stop further execution
        } else if (!passwordCheckedCondition) {
        // If passwordCheckedCondition is false, display an error message
        $('#error_message').text('Please check all conditions.').show();
        $('.login_btn').show();
        $('.loading_btn').hide();
        return; // Stop further execution
        }
    else {
            // If all fields are filled and passwords match, hide the error message
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
                                @if(session('success'))
                                <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('warning'))
                                    <div class="alert alert-warning">
                                        {{ session('warning') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if(session('errors'))
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach(session('errors') as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

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
                                            <div class="form-group ">
                                                <label for="password">
                                                    <b>{{ translate('Password') }} <span
                                                            class="text-primary">*</span></b>
                                                </label>
                                                <div class="position-relative">

                                                    <input type="password" id="password" name="password"
                                                        class="form-control rounded-0" autocomplete="off" required
                                                        placeholder="{{ translate('Password') }}">

                                                    <i class="password-toggle las la-2x la-eye"></i>
                                                </div>

                                                <div id="password-strength" class="mt-2">
                                                    <div class="progress" style="height: 8px;">
                                                        <div id="strength-bar" class="progress-bar" role="progressbar"
                                                            style="width: 0%;" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>

                                                    <div id="dict-loader" class="small text-muted mt-1">
                                                        <i class="las la-spinner la-pulse"></i> Loading dictionary…
                                                    </div>

                                                    <ul id="password-criteria" class="row list-unstyled mt-2"
                                                        style="padding-left: 0">
                                                        <li class="col-6 mb-1" data-rule="length">
                                                            <span class="text-danger">✘</span> Minimum 8 characters
                                                        </li>
                                                        {{-- <li class="col-6 mb-1" data-rule="uppercase">
                                                            <span class="text-danger">✘</span> At least one uppercase
                                                        </li>
                                                        <li class="col-6 mb-1" data-rule="lowercase">
                                                            <span class="text-danger">✘</span> At least one lowercase
                                                        </li>
                                                        <li class="col-6 mb-1" data-rule="number">
                                                            <span class="text-danger">✘</span> At least one number
                                                        </li>
                                                        <li class="col-6 mb-1" data-rule="special">
                                                            <span class="text-danger">✘</span> At least one special
                                                        </li> --}}
                                                        <li class="col-6 mb-1" data-rule="allowedChars">
                                                            <span class="text-danger">✘</span> Only letters, numbers,
                                                            signs
                                                        </li>
                                                        <li class="col-6 mb-1" data-rule="maxNumbers">
                                                            <span class="text-danger">✘</span> No more than 3 numbers
                                                        </li>

                                                        <li class="col-6 mb-1" data-rule="noSeqNum">
                                                            <span class="text-danger">✘</span> No 3 consecutive numbers
                                                        </li>
                                                        <li class="col-6 mb-1" data-rule="noSeqChar">
                                                            <span class="text-danger">✘</span> No 3 consecutive letters
                                                        </li>
                                                        <li class="col-6 mb-1" data-rule="maxCategory">
                                                            <span class="text-danger">✘</span> No letter, number, or
                                                            symbol may appear more than three times.
                                                        </li>
                                                        <li class="col-6 mb-1" data-rule="noNameEmail">
                                                            <span class="text-danger">✘</span> No part of name, email or
                                                            personal information.
                                                        </li>
                                                        <li class="col-6 mb-1" data-rule="noDict">
                                                            <span class="text-danger">✘</span> No vocabularies.
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            

                                            <!-- Password Confirmation-->
                                            <div class="form-group">
                                                <label
                                                    for="password_confirmation"><b>{{ translate('Repeat Password') }}</b>
                                                    <span class="text-primary">*</span></label>
                                                <div class="position-relative">
                                                    <input type="password" id="password-confirm" 
                                                        name="password_confirmation" class="form-control rounded-0"
                                                        required placeholder="{{ translate('Reset Password') }}" required>
                                                    <i class="password-toggle las la-2x la-eye"
                                                        data-target="#password_confirmation"></i>
                                                </div>

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
@section('script')
<script type="text/javascript">
        $(document).ready(function () {

         const $password = $('#password');
            const $bar = $('#strength-bar');
            const $criteria = $('#password-criteria li');
            let dictionary = [];
            $.getJSON("{{ route('get.words') }}")
                .done(words => {
                    dictionary = words.filter(w => w.length >= 3);
                })
                .always(() => {
                    $('#dict-loader').hide();
                });


            const rules = {
                length: (val) => val.length >= 8,
                /*                 uppercase: (val) => /[A-Z]/.test(val),
                                lowercase: (val) => /[a-z]/.test(val),
                                number: (val) => /\d/.test(val),
                                special: (val) => /[@#\-+/=$!%*?&]/.test(val),
                 */
                allowedChars: v => /^[A-Za-z0-9@#\-+\/=$!%*?&]+$/.test(v),
                maxNumbers: v => (v.match(/\d/g) || []).length <= 3,
                noSeqNum: val => {
                    for (let i = 0; i + 2 < val.length; i++) {
                        const a = +val[i], b = +val[i + 1], c = +val[i + 2];
                        if (!isNaN(a) && !isNaN(b) && !isNaN(c)) {
                            if ((b - a === 1 && c - b === 1) || (a - b === 1 && b - c === 1)) return false;
                        }
                    }
                    return true;
                },
                noSeqChar: val => {
                    for (let i = 0; i + 2 < val.length; i++) {
                        const a = val.charCodeAt(i), b = val.charCodeAt(i + 1), c = val.charCodeAt(i + 2);
                        const sa = val[i], sb = val[i + 1], sc = val[i + 2];
                        const sameCase = (sa === sa.toLowerCase() && sb === sb.toLowerCase() && sc === sc.toLowerCase())
                            || (sa === sa.toUpperCase() && sb === sb.toUpperCase() && sc === sc.toUpperCase());
                        if (sameCase && ((b - a === 1 && c - b === 1) || (a - b === 1 && b - c === 1))) {
                            return false;
                        }
                    }
                    return true;
                },
                maxCategory: v => {
                    const counts = {};
                    for (let ch of v) {
                        counts[ch] = (counts[ch] || 0) + 1;
                        if (counts[ch] > 3) return false;
                    }
                    return true;
                },

                noNameEmail: val => {
                    const target = val.toLowerCase();
                    const sources = [
                        $('#first_name').val().toLowerCase(),
                        $('#last_name').val().toLowerCase(),
                        $('#email').val().toLowerCase()
                    ];
                    return sources.every(str => {
                        for (let L = 3; L <= str.length; L++) {
                            for (let i = 0; i + L <= str.length; i++) {
                                if (target.includes(str.substr(i, L))) {
                                    return false;
                                }
                            }
                        }
                        return true;
                    });
                },

                noDict: v => {
                    const lowerV = v.toLowerCase();
                    return !dictionary.some(w => lowerV.includes(w));
                }


            };

            $password.on('input', function () {
                const val = $(this).val();
                let passed = 0;

                // Evaluate each rule
                $criteria.each(function () {
                    const rule = $(this).data('rule');
                    const valid = rules[rule](val);
                    $(this).find('span')
                        .text(valid ? '✔' : '✘')
                        .toggleClass('text-danger', !valid)
                        .toggleClass('text-success', valid);
                    if (valid) passed++;
                });

                // Update progress bar
                const strength = (passed / Object.keys(rules).length) * 100;
                $bar.css('width', strength + '%');

                if (strength <= 40) {
                    $bar.removeClass().addClass('progress-bar bg-danger');
                } else if (strength < 80) {
                    $bar.removeClass().addClass('progress-bar bg-warning');
                } else {
                    $bar.removeClass().addClass('progress-bar bg-success');
                }
            });

            $('.password-toggle').on('click', function () {
                const $icon = $(this);
                const targetSelector = $icon.data('target') || '#password';
                const $input = $(targetSelector);
                const isPassword = $input.attr('type') === 'password';

                $input.attr('type', isPassword ? 'text' : 'password');
                $icon.toggleClass('la-eye la-eye-slash');

            });

        });

</script>
@endsection
