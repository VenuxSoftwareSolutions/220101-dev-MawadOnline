<!DOCTYPE html>
<html>

<head>
    <title>MawadOnline Vendor Verification</title>
    <style>
        :root {
            --primary-blue: #3D3D3B;
            /* Trustworthy navy blue */
            --action-orange: #FF6B35;
            /* Vibrant safety orange */
            --success-green: #2A9D8F;
            /* Construction teal */
            --background-gray: #F8F9FA;
            /* Light background */
            --text-dark: #2D3748;
            /* Primary text color */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: var(--background-gray);
        }

        .header-bg {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #0F2347 100%);
            padding: 30px 0;
        }

        .verification-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin: 20px;
            padding: 40px;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin: 30px 0;
        }

        .step {
            width: 100px;
            height: 4px;
            background: #E2E8F0;
            margin: 0 10px;
            border-radius: 2px;
        }

        .step.active {
            background: var(--action-orange);
        }

        a:hover svg path {
            fill: var(--action-orange) !important;
        }
    </style>
</head>

<body>
    <div style="max-width: 600px; margin: 0 auto;">
        <!-- Header with Branding -->
        <div class="header-bg">
            <center>
                @php
                    $header_logo = get_setting('header_logo');
                @endphp
                @if ($header_logo != null)
                    <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                        class="mw-100 h-30px h-md-40px" height="40">
                @else
                    <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                        class="mw-100 h-30px h-md-40px" height="40">
                @endif

                <h1 style="color: white; margin: 0; font-size: 24px;">Complete Your Vendor Registration</h1>
            </center>
        </div>

        <!-- Registration Steps Visualization -->
        <div class="step-indicator">
            <div class="step active"></div>
            <div class="step active"></div>
            <div class="step"></div>
        </div>

        <!-- Verification Card -->
        <div class="verification-card">
            <h2 style="color: var(--primary-blue); margin-top: 0; font-size: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" width="24" height="24" style="vertical-align: middle; margin-right: 5px;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3l7.5 4.5v5.25a8.25 8.25 0 01-5.63 7.79l-.87.28a.75.75 0 01-.5 0l-.87-.28A8.25 8.25 0 014.5 12.75V7.5L12 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                </svg>
                Step 2: Email Verification
            </h2>

            <p style="color: var(--text-dark); line-height: 1.6;">
                Welcome to MawadOnline's global construction marketplace!<br>
                To activate your vendor account and start reaching customers worldwide, please enter this verification
                code:
            </p>

            <div style="text-align: center; margin: 40px 0;">
                <div
                    style="display: inline-block; padding: 20px 40px; background: var(--primary-blue); border-radius: 8px;">
                    <span style="font-size: 36px; letter-spacing: 4px; color: white; font-weight: bold;">
                        {{ $verificationCode }}
                    </span>
                </div>
            </div>

            <div
                style="background: #FFF9F7; padding: 20px; border-radius: 6px; border-left: 4px solid var(--action-orange); display: flex; align-items: flex-start;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" width="24" height="24"
                    style="margin-right: 15px; flex-shrink: 0; color: var(--action-orange);">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3m0 4h.01M10.29 3.86l-7.38 12.78A1.5 1.5 0 004.12 18h15.76a1.5 1.5 0 001.21-2.36L13.71 3.86a1.5 1.5 0 00-2.42 0z" />
                </svg>
                <p style="color: var(--text-dark); margin: 0; font-size: 14px;">
                    <strong>Next Step:</strong> After verification, you'll complete your business profile to start
                    showcasing your construction materials/services.
                </p>
            </div>

        </div>



        <!-- Footer -->
        <!-- Footer -->
        <div style="background: var(--primary-blue); padding: 25px; text-align: center; color: white; font-size: 12px;">
            <p style="margin: 5px 0; line-height: 1.5;">
                © {{ date('Y') }} MawadOnline - Building the Future of Construction Materials<br>
                Need help? <a href="mailto:vendor-support@mawadonline.com"
                    style="color: var(--action-orange); text-decoration: none; font-weight: 500;">
                    Contact Vendor Support Team
                </a>
            </p>

            <div style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                <table align="center" style="border-collapse: collapse; margin: 0 auto;">
                    <tr>
                        @if (!empty(get_setting('linkedin_link')))
                            <td style="padding: 0 10px;">
                                <a href="{{ get_setting('linkedin_link') }}" target="_blank" style="display: block;">
                                    <svg width="24" height="24" viewBox="0 0 32 33" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#FFFFFF"
                                            d="M27.2655 27.5328H22.5241V20.1073C22.5241 18.3366 22.4925 16.0572 20.0581 16.0572C17.5886 16.0572 17.2108 17.9864 17.2108 19.9784V27.5323H12.4694V12.2625H17.0211V14.3492H17.0848C17.5404 13.5704 18.1986 12.9296 18.9895 12.4953C19.7803 12.0609 20.6742 11.8492 21.5758 11.8826C26.3815 11.8826 27.2675 15.0437 27.2675 19.156L27.2655 27.5328ZM7.1195 10.1752C6.57531 10.1753 6.04329 10.014 5.59076 9.71174C5.13822 9.40947 4.7855 8.97979 4.57716 8.47704C4.36882 7.97429 4.31421 7.42105 4.42029 6.88728C4.52636 6.3535 4.78833 5.86317 5.17307 5.47828C5.5578 5.0934 6.04803 4.83125 6.58174 4.72498C7.11546 4.61871 7.66869 4.6731 8.1715 4.88128C8.67431 5.08945 9.10409 5.44205 9.40651 5.89449C9.70893 6.34693 9.87041 6.87889 9.87051 7.42311C9.87057 7.78445 9.79945 8.14227 9.66124 8.47613C9.52302 8.80999 9.32043 9.11336 9.06497 9.36892C8.80952 9.62447 8.50621 9.8272 8.17241 9.96554C7.8386 10.1039 7.48083 10.1751 7.1195 10.1752ZM9.4902 27.5328H4.74386V12.2625H9.4902V27.5328ZM29.6293 0.268782H2.36132C1.74241 0.261797 1.14602 0.50082 0.703253 0.933325C0.260483 1.36583 0.00755341 1.95643 0 2.57535V29.9574C0.00729489 30.5766 0.260076 31.1676 0.702831 31.6006C1.14559 32.0335 1.74211 32.273 2.36132 32.2664H29.6293C30.2497 32.2742 30.8479 32.0354 31.2924 31.6024C31.7369 31.1695 31.9914 30.5778 32 29.9574V2.57338C31.9912 1.95323 31.7365 1.36196 31.292 0.92946C30.8475 0.496966 30.2494 0.258626 29.6293 0.266805" />
                                    </svg>
                                </a>
                            </td>
                        @endif

                        @if (!empty(get_setting('twitter_link')))
                            <td style="padding: 0 10px;">
                                <a href="{{ get_setting('twitter_link') }}" target="_blank" style="display: block;">
                                    <svg width="24" height="24" viewBox="0 0 27 25" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#FFFFFF"
                                            d="M21.253 0.266602H25.3734L16.3734 10.4533L26.9999 24.2666H18.6505L12.1445 15.8933L4.66259 24.2666H0.542108L10.1927 13.3866L-6.10352e-05 0.266602H8.5662L14.4758 7.9466L21.253 0.266602ZM19.7891 21.8133H22.0662L7.31922 2.55994H4.82524L19.7891 21.8133Z" />
                                    </svg>
                                </a>
                            </td>
                        @endif

                        @if (!empty(get_setting('facebook_link')))
                            <td style="padding: 0 10px;">
                                <a href="{{ get_setting('facebook_link') }}" target="_blank" style="display: block;">
                                    <svg width="24" height="24" viewBox="0 0 32 33" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#FFFFFF" fill-rule="evenodd" clip-rule="evenodd"
                                            d="M15.9999 0.266602C24.8364 0.266602 31.9999 7.47382 31.9999 16.3644C31.9999 24.3992 26.1489 31.0589 18.4999 32.2666V21.0176H22.228L22.9374 16.3644H22.7688L22.8081 16.1113H18.4999V13.3447C18.4999 12.0716 19.1197 10.8308 21.1073 10.8308H23.1249V6.86921C23.1249 6.86921 23.0808 6.86164 22.9999 6.84886V6.58214C22.9999 6.58214 21.127 6.2666 19.3365 6.2666C15.5984 6.2666 13.1551 8.50311 13.1551 12.552V16.1113H8.99988V20.7812H9.43738V21.0176H13.1551V32.0703C13.2697 32.0881 13.3846 32.1047 13.4999 32.12V32.2666C5.85085 31.0589 -0.00012207 24.3992 -0.00012207 16.3644C-0.00012207 7.47382 7.16332 0.266602 15.9999 0.266602Z" />
                                    </svg>
                                </a>
                            </td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>