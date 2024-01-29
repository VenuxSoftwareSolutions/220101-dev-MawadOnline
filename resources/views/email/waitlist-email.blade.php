<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>MawadOnline | Online Constrcution Materials, Services & Equipement</title>
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
                    <a class="logo-brand" href="{{route('home')}}"><img class="logo-image" src="{{ asset('public/home_page/images/MawadLogo.png')}}"></a>
                </div>
                <div class="nav-menu nav-mobile">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#why-mawad">Why Mawad</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#how-it-works">How it Works</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#exclusive-offer">Exclusive Offer</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#waitlist">Waitlist</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="slider"
            style="background: url(public/home_page/images/img-slide.jpg); background-repeat:no-repeat; background-size: cover;">
            <div class="content-slider">
                <div class="descriptif-slider">
                    <h1>Shaping The <br />Future of Construction</h1>
                    <p>A digital marketplace for construction materials, equipment and professional services.</p>
                    <p>Buy and sell construction materials, equipment and professional services with ease. Save time,
                        make informed choices and transact with peace of mind.</p>
                </div>
                <div class="buttons-slider">
                    <a class="btn1-slider" href="#waitlist">Join our waitlist</a>
                    <a class="btn2-slider" href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ3j7k5dpUNzwV6JhvPr0EElvJcamwSmLmP7f26wHhAUcUz6OvvodIwqPtjij8Vx3uybYawSDA8Z " target="_blank">
                        Speak with our team</a>
                </div>
            </div>
        </section>
        <section id="why-mawad" class="why-MawadOnline">
            <h2 class="titre-section2">Why MawadOnline</h2>
            <div class="blocs-section2">
                <div class="bloc1">
                    <div class="box">
                        <div class="icon-box"><img src="{{ asset('public/home_page/images/shop 1.svg')}}"></div>
                        <div class="content-box">
                            <div class="title-box">Shop what you need, when you need it</div>
                            <div class="p-box">Our marketplace is available 24/7</div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="icon-box"><img src="{{ asset('public/home_page/images/time 1.svg')}}"></div>
                        <div class="content-box">
                            <div class="title-box">Save Time</div>
                            <div class="p-box">No more time wasting time navigating hundreds of websites</div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="icon-box"><img src="{{ asset('public/home_page/images/choice 2.svg')}}"></div>
                        <div class="content-box">
                            <div class="title-box">Make informed choices</div>
                            <div class="p-box">Compare prices and read reviews to make better business decisions.</div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="icon-box"><img src="{{ asset('public/home_page/images/need 1.svg')}}"></div>
                        <div class="content-box">
                            <div class="title-box">Order exactly what you need</div>
                            <div class="p-box">Order quantities that match your project needs</div>
                        </div>
                    </div>
                </div>
                <div class="bloc1">
                    <div class="box">
                        <div class="icon-box"><img src="{{ asset('public/home_page/images/certified 1.svg')}}"></div>
                        <div class="content-box">
                            <div class="title-box">Purchase with a peace of mind</div>
                            <div class="p-box">Only vetted sellers and certified products</div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="icon-box"><img src="{{ asset('public/home_page/images/delivery 1.svg')}}"></div>
                        <div class="content-box">
                            <div class="title-box">Enjoy swift deliveries</div>
                            <div class="p-box">Fast deliveries with real-time tracking & updates</div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="icon-box"><img src="{{ asset('public/home_page/images/chart 1.svg')}}"></div>
                        <div class="content-box">
                            <div class="title-box">Get clear insights</div>
                            <div class="p-box">Get quick insights into your orders.</div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="icon-box"><img src="{{ asset('public/home_page/images/transactions 1.svg')}}"></div>
                        <div class="content-box">
                            <div class="title-box">Track your transactions </div>
                            <div class="p-box">View purchases, receipts & invoices any time</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-section2">
                <a href="#waitlist">Join our waitlist</a>
            </div>
        </section>

        <section id="how-it-works" class="How-It-Works">
            <div class="container-fluid">
                <div class="title-section3">How It Works</div>
                <div class="blocs-section3">
                    <div class="bloc1-section3">
                        <div class="bloc1-left first-bloc1">
                            <div class="content-bloc1-left">
                                <div class="title-bloc1-left">Search</div>
                                <div class="p-bloc1-left">Explore our marketplace to find the materials and equipments
                                    you need.</div>
                            </div>
                            <div class="image-bloc1-left">
                                <img src="{{ asset('public/home_page/images/image-1.png')}}">
                            </div>
                        </div>
                        <div class="bloc1-right">
                            <div class="image-bloc1-right">
                                <img class="vector-noresp" src="{{ asset('public/home_page/images/Vector 2.svg')}}">
                                <img class="vector-resp" src="{{ asset('public/home_page/images/Vector 5-1.svg')}}">
                            </div>
                        </div>
                    </div>
                    <div class="bloc2-section3">
                        <div class="bloc2-left">
                            <div class="image-bloc2-left">
                                <img class="vector-noresp" src="{{ asset('public/home_page/images/Vector 3.svg')}}">
                                <img class="vector-resp" src="{{ asset('public/home_page/images/Vector 5-2.svg')}}">
                            </div>
                        </div>
                        <div class="bloc2-right">
                            <div class="content-bloc2-right">
                                <div class="title-bloc2-right">Compare</div>
                                <div class="p-bloc2-right">Evaluate options based on quality, price, certifications and
                                    reviews to ensure you make the best choice</div>
                            </div>
                            <div class="image-bloc2-right">
                                <img src="{{ asset('public/home_page/images/image-2.png')}}">
                            </div>
                        </div>
                    </div>
                    <div class="bloc1-section3">
                        <div class="bloc1-left">
                            <div class="content-bloc1-left">
                                <div class="title-bloc1-left">Select</div>
                                <div class="p-bloc1-left">Choose the products an quantities that align with your project
                                    requirements</div>
                            </div>
                            <div class="image-bloc1-left">
                                <img src="{{ asset('public/home_page/images/image-3.png')}}">
                            </div>
                        </div>
                        <div class="bloc1-right">
                            <div class="image-bloc1-right">
                                <img class="vector-noresp" src="{{ asset('public/home_page/images/Vector 2.svg')}}">
                                <img class="vector-resp" src="{{ asset('public/home_page/images/Vector 5-1.svg')}}">
                            </div>
                        </div>
                    </div>
                    <div class="bloc2-section3">
                        <div class="bloc2-left">
                            <div class="image-bloc2-left">
                                <img class="vector-noresp" src="{{ asset('public/home_page/images/Vector 3.svg')}}">
                                <img class="vector-resp" src="{{ asset('public/home_page/images/Vector 5-2.svg')}}">
                            </div>
                        </div>
                        <div class="bloc2-right">
                            <div class="content-bloc2-right">
                                <div class="title-bloc2-right">Purchase</div>
                                <div class="p-bloc2-right">Seamlessly complete transactions with our fast and secure
                                    checkout process.</div>
                            </div>
                            <div class="image-bloc2-right">
                                <img src="{{ asset('public/home_page/images/image-4.png')}}">
                            </div>
                        </div>
                    </div>
                    <div class="bloc1-section3">
                        <div class="bloc1-left">
                            <div class="content-bloc1-left">
                                <div class="title-bloc1-left">Receive</div>
                                <div class="p-bloc1-left">Get your order delivered on time, wherever you are, thanks to
                                    our swift delivery service.</div>
                            </div>
                            <div class="image-bloc1-left">
                                <img src="{{ asset('public/home_page/images/icon-mawad-camion.png')}}">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section id="exclusive-offer" class="Exclusive-Launch-Offer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="bloc-left-section4">
                            <div class="title-left-section4">Exclusive Launch Offer</div>
                            <div class="boxes-number-section4">
                                <div class="box-num-section4">
                                    <div class="p-num-section4">For first</div>
                                    <div class="num-section4">50</div>
                                    <div class="p-num-section4">members</div>
                                </div>
                                <div class="box-num-section4">
                                    <div class="p-num-section4">Enjoy</div>
                                    <div class="num-section4">5%</div>
                                    <div class="p-num-section4">discount on first 5 orders</div>
                                </div>
                            </div>
                            <div class="p-left-section4">Get early bird access to all of our upcoming features.</div>
                            <div class="p-left-section4">Secure your spot and enjoy these exclusive benefits.</div>
                            <div class="btn-left-section4"><a href="#waitlist">Join Now</a></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="image-right-section4">
                            <img src="{{ asset('public/home_page/images/laptop-1.png')}}">
                        </div>
                    </div>
                </div>
            </div>
        </section>

                <form action="{{ route('send-waitlist-email') }}" method="post">
                    @csrf
                    <section id="waitlist" class="Join-The-Waitlist">
                        <div class="container-fluid">
                            <div class="row flex-section5">
                                <div class="col-lg-6">
                                    <div class="bloc-left-section5">
                                        <div class="title-left-section5">Join MawadOnline Waitlist</div>
                                        <div class="form-left-section5">
                                            <div class="champ1">
                                                <label>Name<span class="required">*</span></label>
                                                <input type="text" id="name" name="name" placeholder="Enter name" required>
                                            </div>
                                            <div class="champ2">
                                                <label>Email<span class="required">*</span></label>
                                                <input type="email" id="email" name="email" placeholder="Enter email" required>
                                            </div>
                                            <div class="champ2">
                                                <label>Phone number<span class="required">*</span></label>
                                                <input type="text" id="phone" name="phone" placeholder="Enter phone number" required>
                                            </div>
                                            <div class="champ2">
                                                <label>Work base
                                                </label>
                                                <input type="text" id="work" name="work" placeholder="Enter work base " >
                                            </div>
                                            <div class="champ2">
                                                <label>Job title
                                                </label>
                                                <input type="text" id="job" name="job" placeholder="Enter job title" >
                                            </div>
                                            <div class="champ2">
                                                <label>Location
                                                </label>
                                                <input type="text" id="location" name="location" placeholder="Enter location" >
                                            </div>
                                            <div class="champ2">
                                                <label>Comments
                                                </label>
                                                <textarea id="info" placeholder="Comments" name="info"></textarea>
                                            </div>
                                            <div class="champ4">
                                                <input type="checkbox" id="check-regles" name="check-regles">
                                                <label for="check-regles"><a href="{{route('terms-and-conditions')}}" target="_blank"> UAE Data protection check if needed (to subscribe to
                                                    the newsletter and offers)</a></label>
                                            </div>
                                            <div class="btn-submit-section5">
                                                <input type="submit" value="Join Now" name="subscribeNewsletter" style="background:none;border:0;color:white;font-family: 'Prompt-medium';">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="image-right-section5">
                                        <img src="{{ asset('public/home_page/images/Yellow-black-excavator-1.jpg')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>


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
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DK3MV3V7W4"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-DK3MV3V7W4');
    </script>
</body>

</html>
