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
                    <a class="logo-brand" href="{{ route('home') }}"><img class="logo-image"
                            src="{{ asset('public/home_page/images/MawadLogo1.png') }}"></a>
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
            <h2 class="titre-section2">Terms and Conditions - MawadOnline</h2>
            <div class="blocs-section-2">
                <div class="bloc1">
                    <div class="box-2">

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
