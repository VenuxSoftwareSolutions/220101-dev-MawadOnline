<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>MawadOnline | Terms and Conditions</title>
    <!-- Fav Icon -->
    <link rel="icon" type="image/png" href="{{ uploaded_asset(get_setting('site_icon')) }}">
    <!-- Stylesheets -->

    <link href="{{ asset('public/home_page/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/home_page/css/style.css')}}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>


<body>
    <header>
        <nav class="navbar top-nav navbar-expand-lg">
            <div class="header-top">
                <div class="logo">
                    <a class="logo-brand" href="{{route('home')}}"><img src="{{ asset('public/home_page/images/MawadLogo.png')}}"></a>
                </div>
                <div class="nav-menu nav-mobile">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{route('business')}}#why-mawad">Why Mawad</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('business')}}#how-it-works">How it Works</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('business')}}#exclusive-offer">Exclusive Offer</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('business')}}#waitlist">Waitlist</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="why-MawadOnline">
            <h2 class="titre-section2">Terms and Conditions - MawadOnline Waitlist</h2>
            <div class="blocs-section-2">
                <div class="bloc1">
                    <div class="box-2">
                        <div class="content-box">
                            <div class="title-box-2">Welcome to MawadOnline's waitlist sign-up page.
                                 By submitting your information to join our waitlist, you agree to the following terms and conditions:</div>
                            <div class="p-box-2"><b>1.	Eligibility: </b> To join the MawadOnline waitlist, you must be at least 18 years of age or older. If you are under 18, you must have the consent of a parent or guardian to participate.</div>
                            <div class="p-box-2"><b>2.	Personal Information: </b> The information you provide during sign-up, including your full name, email address, phone number, company, role, and location, will be collected and stored securely in accordance with our privacy policy.</div>
                            <div class="p-box-2"><b>3.	Communication: </b> By signing up for the waitlist, you consent to receiving email notifications and updates from MawadOnline related to available slots, events, product launches, and other relevant information. You can unsubscribe from these communications at any time by following the provided instructions.</div>
                            <div class="p-box-2"><b>4.	Early Sign-Up Discount:  </b> In appreciation of your early sign-up with MawadOnline, we are pleased to offer a 5% discount on your first 5 orders. Please note that the total value of each order must not exceed 500 DHS (United Arab Emirates Dirhams) to qualify for this discount.</div>
                            <div class="p-box-2"><b>5.	Availability:  </b>Joining the waitlist does not guarantee access to any specific event, product launch, or opportunity. It simply indicates your interest in being notified when such opportunities become available.</div>
                            <div class="p-box-2"><b>6.	Notifications: </b> When slots or opportunities become available, MawadOnline will notify waitlist participants in the order in which they joined the list. Notifications may be sent via email or phone, depending on the information you provided.</div>
                            <div class="p-box-2"><b>7.	Claiming Slots: </b>Upon receiving a notification, it is your responsibility to promptly respond and claim any available slots or opportunities as instructed in the notification. Failure to respond within the specified timeframe may result in the opportunity being offered to the next person on the waitlist. </div>
                            <div class="p-box-2"><b>8.	Privacy: </b> We take your privacy seriously. Your personal information will be used solely for the purpose of managing the waitlist and notifying you about relevant opportunities. We will not share your information with third parties without your consent.</div>
                            <div class="p-box-2"><b>9.	Changes and Updates: </b> We take your privacy seriously. Your personal information will be used solely for the purpose of managing the waitlist and notifying you about relevant opportunities. We will not share your information with third parties without your consent.</div>
                            <div class="p-box-2"><b>10.	Termination: </b> MawadOnline may terminate the waitlist program or your participation in it at any time and for any reason.</div>
                            <div class="p-box-2"><b>11.	Governing Law: </b> These terms and conditions are governed by the laws of the United Arab Emirates (UAE), and any disputes arising from or related to these terms will be subject to the jurisdiction of the UAE courts.</div>
                            <div class="p-box-3">By joining the MawadOnline waitlist, you acknowledge that you have read, understood, and agreed to these terms and conditions. If you do not agree with any part of these terms, please do not sign up for the waitlist.
                                <br>For any questions or concerns regarding the waitlist or these terms and conditions, please contact our customer service team at [email address] or [phone number].
                                <br>Thank you for your interest in MawadOnline. Together, we'll continue building the future of construction.
                                </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

    </main>

    <footer>
    </footer>

    <script src="public/home_page/js/bootstrap.min.js"></script>

    <!--  Hotjar Tracking Code for  -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:3827924,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
</body>

</html>
