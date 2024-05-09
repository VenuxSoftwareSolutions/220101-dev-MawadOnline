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

                                            <!-- Password -->
                                            <div class="form-group">
                                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ translate('New Password') }}" required>
                                                <div id="password-strength"></div>

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
@section('script')
<script type="text/javascript">
    $(document).ready(function() {

        $('#password').on('input', function() {

            $('#password-strength').css('border', '1px solid #ddd');
            $('#password-strength').css('padding', '10px');

            var password = $(this).val();
            var strengthMeter = $('#password-strength');

            var patterns = [
                'abc', 'bcd', 'cde', 'def', 'efg', 'fgh', 'ghi', 'hij', 'ijk', 'jkl',
                'klm', 'lmn', 'mno', 'nop', 'opq', 'pqr', 'qrs', 'rst', 'stu', 'tuv',
                'uvw', 'vwx', 'wxy', 'xyz'
            ];

            var patternRegex = new RegExp(patterns.join('|') + '|' + patterns.map(function(pattern) {
                return pattern.split('').reverse().join('');
            }).join('|'), 'i');

            // Function to calculate the percentage of repeated characters in the password
            function calculateRepeatedCharacterPercentage(password) {
                var characterCount = {};
                for (var i = 0; i < password.length; i++) {
                    var char = password[i];
                    characterCount[char] = (characterCount[char] || 0) + 1;
                }

                var repeatedCount = 0;
                for (var char in characterCount) {
                    if (characterCount[char] > 1) {
                        repeatedCount += characterCount[char];
                    }
                }

                return (repeatedCount / password.length) * 100;
            }

            var repeatedCharacterPercentage = calculateRepeatedCharacterPercentage(password);

            // Password strength rules
            var rules = {
                "{{ translate('Minimum length of 9 characters') }}": password.length >= 9,
                "{{ translate('At least one uppercase letter') }}": /[A-Z]/.test(password),
                "{{ translate('At least one lowercase letter') }}": /[a-z]/.test(password),
                // "At least one number": /\d/.test(password),
                "{{ translate('At least one special character') }}": /[@#-+/=$!%*?&]/.test(password),
                "{{ translate('At least one number and Max Four Numbers') }}": /^\D*(\d\D*){1,4}$/.test(
                    password),
                // maxConsecutiveChars: !/(.)\1\1/.test(password),
                // maxPercentage: calculateMaxPercentage(password),
                "{{ translate('No spaces allowed') }}": !/\s/.test(password),
                "{{ translate('No three consecutive numbers, Example 678,543,789,987') }}": !
                    /(012|123|234|345|456|567|678|789|987|876|765|654|543|432|321|210)/.test(password),
                // "{{ translate('No three characters or more can be a substring of first name, last name, or email') }}":
                //     !
                //     checkSubstring(password),
                "{{ translate('No three consecutive characters or their reverses in the same case are allowed, Example efg,ZYX,LMN,cba') }}":
                    !patternRegex.test(password),
                "{{ translate('No more than 40% of repeated characters') }}": repeatedCharacterPercentage <=
                    40,
                "{{ translate('No substring of the password can be a common English dictionary word') }}": !containsDictionaryWord(password, dictionaryWords),


            };

            // Display password strength rules
            var strengthText = '';
            for (var rule in rules) {
                if (rules.hasOwnProperty(rule)) {
                    strengthText += '<p>' + rule + ': ' + (rules[rule] ? '✔' : '✘') + '</p>';
                }
            }

            // Update UI
            strengthMeter.html(strengthText);

            // Check if all rules are satisfied
            var isPasswordValid = Object.values(rules).every(Boolean);

            // Apply visual feedback
            if (isPasswordValid) {
                strengthMeter.addClass('valid');
                passwordCheckedCondition = true ;
            } else {
                strengthMeter.removeClass('valid');
                passwordCheckedCondition = false ;
            }

        });

        // No substring of the password can be a common English dictionary word

        let dictionaryWords=[] ;

                $.ajax({
            url: '{{route("get.words")}}', // Make sure this URL matches your Laravel route
            method: 'GET',
            success: function(data) {
                dictionaryWords = data;
            },
            error: function(error) {
                console.error("Error fetching dictionary words", error);
            }
        });

        function containsDictionaryWord(password,dictionaryWords) {
            for(let word of dictionaryWords) {
                if(password.toLowerCase().includes(word.toLowerCase())){
                        return true ;
                }
            }
            return false ;
        }

        function calculateMaxPercentage(password) {
            // Calculate the percentage of lowercase, uppercase, numbers, and special characters
            var lowercasePercentage = (password.replace(/[^a-z]/g, '').length / password.length) * 100;
            var uppercasePercentage = (password.replace(/[^A-Z]/g, '').length / password.length) * 100;
            var numberPercentage = (password.replace(/[^0-9]/g, '').length / password.length) * 100;
            var specialCharPercentage = (password.replace(/[^@$!%*?&]/g, '').length / password.length) * 100;

            // Return the maximum percentage
            return Math.max(lowercasePercentage, uppercasePercentage, numberPercentage, specialCharPercentage);
        }

        // function checkSubstring(password) {
        //     // Check if the password contains a substring of the first name, last name, or email with a length of 3 or more characters
        //     var firstName = $('#first_name').val().toLowerCase();
        //     var lastName = $('#last_name').val().toLowerCase();
        //     var email = $('#email').val().toLowerCase();

        //     // Combine the first name, last name, and email into a single string
        //     var combinedStrings = firstName + lastName + email;

        //     // Check if any substring of length 3 or more exists in the password
        //     for (var i = 0; i < combinedStrings.length - 2; i++) {
        //         var substring = combinedStrings.substring(i, i + 3).toLowerCase();
        //         if (password.toLowerCase().includes(substring)) {
        //             return true;
        //         }
        //     }

        //     return false;
        // }

    })
</script>
@endsection
