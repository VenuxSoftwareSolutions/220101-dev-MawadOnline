    <!-- Top Bar Banner -->
    @php
        $topbar_banner = get_setting('topbar_banner');
        $topbar_banner_medium = get_setting('topbar_banner_medium');
        $topbar_banner_small = get_setting('topbar_banner_small');
        $topbar_banner_asset = uploaded_asset($topbar_banner);
    @endphp
    @if ($topbar_banner != null)
        <div class="position-relative top-banner removable-session z-1035 d-none" data-key="top-banner"
            data-value="removed">
            <a href="{{ get_setting('topbar_banner_link') }}" class="d-block text-reset h-40px h-lg-60px">
                <!-- For Large device -->
                <img src="{{ $topbar_banner_asset }}" class="d-none d-xl-block img-fit h-100" alt="{{ translate('topbar_banner') }}">
                <!-- For Medium device -->
                <img src="{{ $topbar_banner_medium != null ? uploaded_asset($topbar_banner_medium) : $topbar_banner_asset }}"
                    class="d-none d-md-block d-xl-none img-fit h-100" alt="{{ translate('topbar_banner') }}">
                <!-- For Small device -->
                <img src="{{ $topbar_banner_small != null ? uploaded_asset($topbar_banner_small) : $topbar_banner_asset }}"
                    class="d-md-none img-fit h-100" alt="{{ translate('topbar_banner') }}">
            </a>
            <button class="btn text-white h-100 absolute-top-right set-session" data-key="top-banner"
                data-value="removed" data-toggle="remove-parent" data-parent=".top-banner">
                <i class="la la-close la-2x"></i>
            </button>
        </div>
    @endif

    <!-- Top Bar -->
    <div class="top-navbar bg-white z-1035 h-35px h-sm-auto">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col">
                    <ul class="list-inline d-flex justify-content-between justify-content-lg-start mb-0">
                        <!-- Welcome -->
                        <li class="list-inline-item mr-0 pl-0 py-2">
                            <div class="text-dark fs-12 pr-3 d-inline-block border-width-2">
                                Welcome To MawadOnline's Marketplace
                            </div>
                        </li>
                        <!-- Currency Switcher -->
                    </ul>
                </div>

                <div class="col-6 text-right d-none d-lg-block">
                    <ul class="list-inline mb-0 h-100 d-flex justify-content-end align-items-center">
                        <!-- Currency Switcher -->
                        {{--  @if (get_setting('show_currency_switcher') == 'on') --}}
                        <li class="list-inline-item dropdown ml-auto ml-lg-0 mr-4" id="currency-change">
                            @php
                                $system_currency = get_system_currency();
                            @endphp

                            <a href="javascript:void(0)" class="dropdown-toggle text-secondary fs-12 my-2 pr-3 border-right"
                                data-toggle="dropdown" data-display="static">
                                {{ $system_currency->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                                @foreach (get_all_active_currency() as $key => $currency)
                                    <li>
                                        <a class="dropdown-item @if ($system_currency->code == $currency->code) active @endif"
                                            href="javascript:void(0)"
                                            data-currency="{{ $currency->code }}">{{ $currency->name }}
                                            ({{ $currency->symbol }})</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        {{-- @endif --}}
                        <!-- Language switcher -->
                        @if (get_setting('show_language_switcher') == 'on')
                            <li class="list-inline-item dropdown mr-4" id="lang-change">

                                <a href="javascript:void(0)" class="dropdown-toggle text-secondary fs-12 my-2 pr-3 border-right"
                                    data-toggle="dropdown" data-display="static">
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ static_asset('assets/img/flags/' . $system_language->code . '.png') }}"
                                    class="mr-1 lazyload" alt="{{ $system_language->name }}" height="11">
                                    <span class="">{{ $system_language->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    @foreach (get_all_active_language() as $key => $language)
                                        <li>
                                            <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                                                class="dropdown-item @if ($system_language->code == $language->code) active @endif">
                                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                                    class="mr-1 lazyload" alt="{{ $language->name }}" height="11">
                                                <span class="language">{{ $language->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                        @if (get_setting('vendor_system_activation') == 1)
                        @unless(Auth::check() && Auth::user()->user_type == 'seller')

                            <!-- Become a Seller -->
                            <li class="list-inline-item mr-0 pl-0 py-2">
                                <a href="{{ route('shops.packages') }}"
                                    class="text-secondary fs-12 pr-3 d-inline-block border-width-2 border-right">{{ translate('Become a Seller !') }}</a>
                            </li>
                            <!-- Seller Login -->
                            <li class="list-inline-item mr-0 pl-0 py-2">
                                <a href="{{ route('seller.login') }}"
                                    class="text-secondary fs-12 pl-3 d-inline-block">{{ translate('Login to Seller') }}</a>
                            </li>
                            @endunless

                        @endif
                        @if (get_setting('helpline_number'))
                            <!-- Helpline -->
                            <li class="list-inline-item ml-3 pl-3 mr-0 pr-0">
                                <a href="tel:{{ get_setting('helpline_number') }}"
                                    class="text-secondary fs-12 d-inline-block py-2">
                                    <span>{{ translate('Helpline') }}</span>
                                    <span>{{ get_setting('helpline_number') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <header class="z-1020 bg-white">
        <!-- Search Bar -->
        <div class="position-relative logo-bar-area border-md-nonea z-1025 border-header background-header">
            <div class="container">
                <div class="d-flex align-items-center">
                    <!-- top menu sidebar button -->
                    <button type="button" class="btn d-lg-none mr-3 mr-sm-4 p-0 active" data-toggle="class-toggle"
                        data-target=".aiz-top-menu-sidebar">
                        <svg id="Component_43_1" data-name="Component 43 – 1" xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" viewBox="0 0 16 16">
                            <rect id="Rectangle_19062" data-name="Rectangle 19062" width="16" height="2"
                                transform="translate(0 7)" fill="#919199" />
                            <rect id="Rectangle_19063" data-name="Rectangle 19063" width="16" height="2"
                                fill="#919199" />
                            <rect id="Rectangle_19064" data-name="Rectangle 19064" width="16" height="2"
                                transform="translate(0 14)" fill="#919199" />
                        </svg>

                    </button>
                    <!-- Header Logo -->
                    <div class="col-auto pl-0 pr-3 d-flex align-items-center">
                        <a class="d-block py-20px mr-3 ml-0" href="{{ route('home') }}">
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
                        </a>
                    </div>
                    <!-- Search Icon for small device -->
                    <div class="d-lg-none ml-auto mr-0">
                        <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle"
                            data-target=".front-header-search">
                            <i class="las la-search la-flip-horizontal la-2x"></i>
                        </a>
                    </div>
                    <!-- Search field -->
                    <div class="flex-grow-1 front-header-search d-flex align-items-center bg-white mx-2 mx-xl-4">
                        <div class="position-relative flex-grow-1 px-3 px-lg-0">
                            <form action="{{ route('search') }}" method="GET" class="stop-propagation m-0">
                                <div class="d-flex position-relative align-items-center">
                                    <div class="d-lg-none" data-toggle="class-toggle"
                                        data-target=".front-header-search">
                                        <button class="btn px-2" type="button"><i
                                                class="la la-2x la-long-arrow-left"></i></button>
                                    </div>
                                    <div class="search-input-box">
                                        <input type="text"
                                            class="border border-soft-light form-control fs-14 hov-animate-outline radius-search"
                                            id="search" name="keyword"
                                            @isset($query)
                                            value="{{ $query }}"
                                        @endisset
                                            placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                        <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                            width="20.001" height="20" viewBox="0 0 20.001 20">
                                            <path id="Path_3090" data-name="Path 3090"
                                                d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                                transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                            <path id="Path_3091" data-name="Path 3091"
                                                d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                                transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                        </svg>
                                    </div>
                                </div>
                            </form>
                            <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                                style="min-height: 200px">
                                <div class="search-preloader absolute-top-center">
                                    <div class="dot-loader">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="search-nothing d-none p-3 text-center fs-16">

                                </div>
                                <div id="search-content" class="text-left">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Search box -->
                    <div class="d-none d-lg-none ml-3 mr-0">
                        <div class="nav-search-box">
                            <a href="#" class="nav-box-link">
                                <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Compare -->
                    {{-- <div class="d-none d-lg-block ml-3 mr-0">
                        <div class="" id="compare">
                            @include('frontend.'.get_setting('homepage_select').'.partials.compare')
                        </div>
                    </div> --}}
                    <!-- Wishlist -->
                    <div class="dd-none d-lg-block mr-3"
                        data-hover="dropdown">
                        <div class="nav-cart-box dropdown h-100" id="cart_items" style="width: max-content;">
                            <a href="{{ route('wishlists.index') }}" class="d-flex align-items-center text-dark" data-toggle="tooltip" data-title="{{ translate('Wishlist') }}" data-placement="top">
                                <span class="position-relative d-inline-block">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M27.3333 19.9866L20.6533 26.6799" stroke="#F3F4F5" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M4.66666 19.9866H27.3333" stroke="#F3F4F5" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M4.66666 12.0134L11.3467 5.32007" stroke="#F3F4F5" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M27.3333 12.0134H4.66666" stroke="#F3F4F5" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                        <span class="badge badge-counter-compare font-prompt">0</span>
                                </span>
                            </a>

                        </div>
                    </div>
                    <div class="d-none d-lg-block mr-3" style="margin-left: 20px;">
                        <div class="" id="wishlist">
                            @include('frontend.'.get_setting('homepage_select').'.partials.wishlist')
                        </div>
                    </div>
                    <div class="dd-none d-lg-block"
                        data-hover="dropdown">
                        <div class="nav-cart-box dropdown h-100" id="cart_items" style="width: max-content;">
                            @include('frontend.'.get_setting('homepage_select').'.partials.cart')
                        </div>
                    </div>
                    @if (!isAdmin())
                        <!-- Notifications
                        <ul class="list-inline mb-0 h-100 d-none d-xl-flex justify-content-end align-items-center">
                            <li class="list-inline-item ml-2 mr-3 pr-3 pl-0 dropdown">
                                <a class="dropdown-toggle no-arrow text-secondary fs-12" data-toggle="dropdown"
                                    href="javascript:void(0);" role="button" aria-haspopup="false"
                                    aria-expanded="false">
                                    <span class="">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12.0201 2.91003C8.71009 2.91003 6.02009 5.60003 6.02009 8.91003V11.8C6.02009 12.41 5.76009 13.34 5.45009 13.86L4.30009 15.77C3.59009 16.95 4.08009 18.26 5.38009 18.7C9.69009 20.14 14.3401 20.14 18.6501 18.7C19.8601 18.3 20.3901 16.87 19.7301 15.77L18.5801 13.86C18.2801 13.34 18.0201 12.41 18.0201 11.8V8.91003C18.0201 5.61003 15.3201 2.91003 12.0201 2.91003Z" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
                                                <path d="M13.8699 3.19994C13.5599 3.10994 13.2399 3.03994 12.9099 2.99994C11.9499 2.87994 11.0299 2.94994 10.1699 3.19994C10.4599 2.45994 11.1799 1.93994 12.0199 1.93994C12.8599 1.93994 13.5799 2.45994 13.8699 3.19994Z" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M15.02 19.0601C15.02 20.7101 13.67 22.0601 12.02 22.0601C11.2 22.0601 10.44 21.7201 9.90002 21.1801C9.36002 20.6401 9.02002 19.8801 9.02002 19.0601" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10"/>
                                                </svg>


                                           {{-- @if (Auth::check() && count($user->unreadNotifications) > 0) --}}
                                           @if (Auth::check())
                                                <span class="badge badge-counter-notification font-prompt">{{ count($user->unreadNotifications) }}</span>
                                            @endif
                                        </span>
                                </a>

                                @auth
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0">
                                        <div class="p-3 bg-light border-bottom">
                                            <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                        </div>
                                        <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">
                                            <ul class="list-group list-group-flush">
                                                @forelse($user->unreadNotifications as $notification)
                                                    <li class="list-group-item">
                                                        @if ($notification->type == 'App\Notifications\OrderNotification')
                                                            @if ($user->user_type == 'customer')
                                                                <a href="{{ route('purchase_history.details', encrypt($notification->data['order_id'])) }}"
                                                                    class="text-secondary fs-12">
                                                                    <span class="ml-2">
                                                                        {{ translate('Order code: ') }}
                                                                        {{ $notification->data['order_code'] }}
                                                                        {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                    </span>
                                                                </a>
                                                            @elseif ($user->user_type == 'seller')
                                                                <a href="{{ route('seller.orders.show', encrypt($notification->data['order_id'])) }}"
                                                                    class="text-secondary fs-12">
                                                                    <span class="ml-2">
                                                                        {{ translate('Order code: ') }}
                                                                        {{ $notification->data['order_code'] }}
                                                                        {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                    </span>
                                                                </a>
                                                            @endif
                                                        @elseif ($notification->type == 'App\Notifications\CustomStatusNotification')
                                                        <span class="ml-2">
                                                            @if ($notification->data['newStatus'] == 'Suspended')
                                                                {{ __('messages.suspended_notification', ['reason' =>$notification->data['suspendedTitle'] ?? "" ]) }}
                                                                @elseif ($notification->data['newStatus'] == 'Pending Approval')
                                                                {{ __('messages.registration_completed_notif') }}
                                                                @elseif ($notification->data['newStatus'] == 'Closed')
                                                                {{ __('messages.vendor_closed') }}
                                                                @elseif ($notification->data['newStatus'] == 'Pending Closure')
                                                                {{ __('messages.pending_closure') }}
                                                                @elseif ($notification->data['newStatus'] == 'Enabled')
                                                                {{ __('messages.approved') }}
                                                                @elseif ($notification->data['newStatus'] == 'Rejected')
                                                                {{ __('messages.registration_rejected') }}
                                                            @endif
                                                        </span>
                                                        @elseif ($notification->type == 'App\Notifications\ChangesApprovedNotification')

                                                        <p>{{ $notification->data['message'] }}</p>
                                                        @elseif ($notification->type == 'App\Notifications\ModificationRejectedNotification')


                                                        <p>{{ $notification->data['message'] }}</p>
                                                        @endif
                                                    </li>
                                                @empty
                                                    <li class="list-group-item">
                                                        <div class="py-4 text-center fs-16">
                                                            {{ translate('No notification found') }}
                                                        </div>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <div class="text-center border-top">
                                            <a href="{{ route('all-notifications') }}"
                                                class="text-secondary fs-12 d-block py-2">
                                                {{ translate('View All Notifications') }}
                                            </a>
                                        </div>
                                    </div>
                                @endauth
                            </li>
                        </ul>
                    @endif
                -->
                    <div class="d-none d-xl-block ml-auto mr-0">
                        @auth
                            <span
                                class="d-flex align-items-center nav-user-info py-20px @if (isAdmin()) ml-5 @endif"
                                id="nav-user-info">
                                <!-- Image -->
                                <span
                                    class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                                    @if ($user->avatar_original != null)
                                        <img src="{{ $user_avatar }}"
                                            class="img-fit h-100" alt="{{ translate('avatar') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    @else
                                        <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image" alt="{{ translate('avatar') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    @endif
                                </span>
                                <!-- Name -->
                                <div class="user-section-header">
                                    <span class="user-s-h-account-dd font-prompt fs-16">
                                        {{ translate('My Account') }}
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 9L13.4141 15.4304C12.6364 16.1899 11.3636 16.1899 10.5859 15.4304L4 9" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <span class="font-prompt fs-14 user-s-h-account-un">{{ $user->name }}</span>
                                </div>
                            </span>
                        @else
                            <!--Login & Registration -->
                            <a class="user-s-h-account-dd" href="{{ route('user.login') }}">
                            <span class="d-flex align-items-center nav-user-info ml-3">
                                <!-- Image -->
                                <span
                                    class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img bg-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012"
                                        viewBox="0 0 19.902 20.012">
                                        <path id="fe2df171891038b33e9624c27e96e367"
                                            d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                                            transform="translate(-2.064 -1.995)" fill="#91919b" />
                                    </svg>
                                </span>
                                <!--
                                <a href="{{ route('user.login') }}"
                                    class="opacity-60 hov-opacity-100 hov-text-white text-white fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                                <a href="{{ route('business') }}"
                                    class="opacity-60 hov-opacity-100 hov-text-white text-white fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>
                                -->
                                <div class="user-section-header">
                                    <span class="user-s-h-account-dd font-prompt fs-16">{{ translate('My Account') }}</span>
                                    <span class="user-s-h-account-dd font-prompt fs-14">Hello, {{ translate('Sign in') }}</span>
                                </div>
                                </span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Loged in user Menus -->
            <div class="hover-user-top-menu position-absolute top-100 left-0 right-0 z-3">
                <div class="container">
                    <div class="position-static float-right">
                        <div class="aiz-user-top-menu bg-white rounded-0 border-top shadow-sm" style="width:220px;">
                            <ul class="list-unstyled no-scrollbar mb-0 text-left">
                                @if (isAdmin())
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <path id="Path_2916" data-name="Path 2916"
                                                    d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                    fill="#b5b5c0" />
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('dashboard') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <path id="Path_2916" data-name="Path 2916"
                                                    d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                    fill="#b5b5c0" />
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (isCustomer())
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('purchase_history.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <g id="Group_25261" data-name="Group 25261"
                                                    transform="translate(-27.466 -542.963)">
                                                    <path id="Path_2953" data-name="Path 2953"
                                                        d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                        transform="translate(22.966 537)" fill="#b5b5bf" />
                                                    <path id="Path_2954" data-name="Path 2954"
                                                        d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                        transform="translate(27.466 536)" fill="#b5b5bf" />
                                                    <path id="Path_2955" data-name="Path 2955"
                                                        d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                        transform="translate(23.966 532)" fill="#b5b5bf" />
                                                    <path id="Path_2956" data-name="Path 2956"
                                                        d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                        transform="translate(23.966 529)" fill="#b5b5bf" />
                                                    <path id="Path_2957" data-name="Path 2957"
                                                        d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                        transform="translate(23.966 526)" fill="#b5b5bf" />
                                                    <path id="Path_2958" data-name="Path 2958"
                                                        d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                        transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                                    <path id="Path_2959" data-name="Path 2959"
                                                        d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                        transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                                    <path id="Path_2960" data-name="Path 2960"
                                                        d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                        transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                        </a>
                                    </li>
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('digital_purchase_history.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                                viewBox="0 0 16.001 16">
                                                <g id="Group_25262" data-name="Group 25262"
                                                    transform="translate(-1388.154 -562.604)">
                                                    <path id="Path_2963" data-name="Path 2963"
                                                        d="M77.864,98.69V92.1a.5.5,0,1,0-1,0V98.69l-1.437-1.437a.5.5,0,0,0-.707.707l1.851,1.852a1,1,0,0,0,.707.293h.172a1,1,0,0,0,.707-.293l1.851-1.852a.5.5,0,0,0-.7-.713Z"
                                                        transform="translate(1318.79 478.5)" fill="#b5b5bf" />
                                                    <path id="Path_2964" data-name="Path 2964"
                                                        d="M67.155,88.6a3,3,0,0,1-.474-5.963q-.009-.089-.015-.179a5.5,5.5,0,0,1,10.977-.718,3.5,3.5,0,0,1-.989,6.859h-1.5a.5.5,0,0,1,0-1l1.5,0a2.5,2.5,0,0,0,.417-4.967.5.5,0,0,1-.417-.5,4.5,4.5,0,1,0-8.908.866.512.512,0,0,1,.009.121.5.5,0,0,1-.52.479,2,2,0,1,0-.162,4l.081,0h2a.5.5,0,0,1,0,1Z"
                                                        transform="translate(1324 486)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Downloads') }}</span>
                                        </a>
                                    </li>
                                    @if (get_setting('conversation_system') == 1)
                                        <li class="user-top-nav-element border border-top-0" data-id="1">
                                            <a href="{{ route('conversations.index') }}"
                                                class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 16 16">
                                                    <g id="Group_25263" data-name="Group 25263"
                                                        transform="translate(1053.151 256.688)">
                                                        <path id="Path_3012" data-name="Path 3012"
                                                            d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                                            transform="translate(-1178 -341)" fill="#b5b5bf" />
                                                        <path id="Path_3013" data-name="Path 3013"
                                                            d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                                            transform="translate(-1182 -337)" fill="#b5b5bf" />
                                                        <path id="Path_3014" data-name="Path 3014"
                                                            d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                            transform="translate(-1181 -343.5)" fill="#b5b5bf" />
                                                        <path id="Path_3015" data-name="Path 3015"
                                                            d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                                            transform="translate(-1181 -346.5)" fill="#b5b5bf" />
                                                    </g>
                                                </svg>
                                                <span
                                                    class="user-top-menu-name has-transition ml-3">{{ translate('Conversations') }}</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (get_setting('wallet_system') == 1)
                                        <li class="user-top-nav-element border border-top-0" data-id="1">
                                            <a href="{{ route('wallet.index') }}"
                                                class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="16"
                                                    height="16" viewBox="0 0 16 16">
                                                    <defs>
                                                        <clipPath id="clip-path1">
                                                            <rect id="Rectangle_1386" data-name="Rectangle 1386"
                                                                width="16" height="16" fill="#b5b5bf" />
                                                        </clipPath>
                                                    </defs>
                                                    <g id="Group_8102" data-name="Group 8102"
                                                        clip-path="url(#clip-path1)">
                                                        <path id="Path_2936" data-name="Path 2936"
                                                            d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                            fill="#b5b5bf" />
                                                    </g>
                                                </svg>
                                                <span
                                                    class="user-top-menu-name has-transition ml-3">{{ translate('My Wallet') }}</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('support_ticket.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                                viewBox="0 0 16 16.001">
                                                <g id="Group_25259" data-name="Group 25259"
                                                    transform="translate(-316 -1066)">
                                                    <path id="Subtraction_184" data-name="Subtraction 184"
                                                        d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                        transform="translate(-16096 180)" fill="#b5b5bf" />
                                                    <path id="Union_12" data-name="Union 12"
                                                        d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                        transform="translate(-16090.998 183.001)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Support Ticket') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('logout') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                            viewBox="0 0 16 15.999">
                                            <g id="Group_25503" data-name="Group 25503"
                                                transform="translate(-24.002 -377)">
                                                <g id="Group_25265" data-name="Group 25265"
                                                    transform="translate(-216.534 -160)">
                                                    <path id="Subtraction_192" data-name="Subtraction 192"
                                                        d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                        transform="translate(-11803.999 -2367)" fill="#d43533" />
                                                </g>
                                                <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1"
                                                    height="8" rx="0.5" transform="translate(31.5 377)"
                                                    fill="#d43533" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name text-primary has-transition ml-3">{{ translate('Logout') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Bar -->
        <div class="d-none d-lg-block position-relative background-header h-50px">
            <div class="container h-100">
                <div class="d-flex h-100">
                    <!-- Categoty Menu Button -->
                    <div class="d-none d-xl-block all-category has-transition bg-black-10" id="category-menu-bar">
                        <div class="px-3 h-100"
                            style="padding-top: 12px;padding-bottom: 12px; width:270px; cursor: pointer;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 7H21" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M3 12H21" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M3 17H21" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    <span class="fw-700 fs-16 text-white ml-2 mr-3 category-menu-title">{{ translate('Categories') }}</span>
                                </div>
                                <i class="las la-angle-down text-white has-transition" id="category-menu-bar-icon"
                                    style="font-size: 1.2rem !important"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Header Menus -->
                    @php
                        $nav_txt_color = ((get_setting('header_nav_menu_text') == 'light') ||  (get_setting('header_nav_menu_text') == null)) ? 'text-white' : 'text-white';
                    @endphp
                    <div class="ml-xl-4 w-100 overflow-hidden">
                        <div class="d-flex align-items-center justify-content-center justify-content-xl-start h-100">
                            <ul class="list-inline mb-0 pl-0 hor-swipe c-scrollbar-light">
                                @if (get_setting('header_menu_labels') != null)
                                    @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                                        <li class="list-inline-item mr-0 animate-underline-white">
                                            <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                                                class="fs-16 px-3 py-3 d-inline-block fw-400 {{ $nav_txt_color }} header_menu_links hov-bg-black-10
                                            @if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) active @endif">
                                            @if ($value == "Brands")
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 15.29V5.71002C2 4.38002 2.77 4.06002 3.71 5.00002L6.3 7.59002C6.69 7.98002 7.33 7.98002 7.71 7.59002L11.29 4.00002C11.68 3.61002 12.32 3.61002 12.7 4.00002L16.29 7.59002C16.68 7.98002 17.32 7.98002 17.7 7.59002L20.29 5.00002C21.23 4.06002 22 4.38002 22 5.71002V15.3C22 18.3 20 20.3 17 20.3H7C4.24 20.29 2 18.05 2 15.29Z" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        @elseif ($value == "Vendors")
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3.01001 11.22V15.71C3.01001 20.2 4.81001 22 9.30001 22H14.69C19.18 22 20.98 20.2 20.98 15.71V11.22" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 12C13.83 12 15.18 10.51 15 8.68L14.34 2H9.66999L8.99999 8.68C8.81999 10.51 10.17 12 12 12Z" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18.31 12C20.33 12 21.81 10.36 21.61 8.35L21.33 5.6C20.97 3 19.97 2 17.35 2H14.3L15 9.01C15.17 10.66 16.66 12 18.31 12Z" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M5.64 12C7.29 12 8.78 10.66 8.94 9.01L9.16 6.8L9.64001 2H6.59C3.97001 2 2.97 3 2.61 5.6L2.34 8.35C2.14 10.36 3.62 12 5.64 12Z" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 17C10.33 17 9.5 17.83 9.5 19.5V22H14.5V19.5C14.5 17.83 13.67 17 12 17Z" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        @endif

                                            {{ translate($value) }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                                <li class="list-inline-item mr-0 animate-underline-white" style="cursor:pointer;">
                                    <a class="fs-16 px-3 py-3 d-inline-block fw-400 text-white header_menu_links hov-bg-black-10">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 22H22" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9.75 4V22H14.25V4C14.25 2.9 13.8 2 12.45 2H11.55C10.2 2 9.75 2.9 9.75 4Z" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M3 10V22H7V10C7 8.9 6.6 8 5.4 8H4.6C3.4 8 3 8.9 3 10Z" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M17 15V22H21V15C21 13.9 20.6 13 19.4 13H18.6C17.4 13 17 13.9 17 15Z" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="ml-1 fs-16">MawadOnline Index</span>
                                        <i class="las la-angle-down text-white has-transition ml-3" id="category-menu-bar-icon" style="font-size: 1.2rem !important; position:relative;top:3px;"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Categoty Menus -->
            <div class="hover-category-menu position-absolute w-100 top-100 left-0 right-0 z-3 d-none"
                id="click-category-menu">
                <div class="container">
                    <div class="d-flex position-relative">
                        <div class="position-static">
                            @include('frontend.'.get_setting("homepage_select").'.partials.category_menu')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Top Menu Sidebar -->
    <div class="aiz-top-menu-sidebar collapse-sidebar-wrap sidebar-xl sidebar-left d-lg-none z-1035">
        <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
            data-target=".aiz-top-menu-sidebar" data-same=".hide-top-menu-bar"></div>
        <div class="collapse-sidebar c-scrollbar-light text-left">
            <button type="button" class="btn btn-sm p-4 hide-top-menu-bar" data-toggle="class-toggle"
                data-target=".aiz-top-menu-sidebar">
                <i class="las la-times la-2x text-primary"></i>
            </button>
            @auth
                <span class="d-flex align-items-center nav-user-info pl-4">
                    <!-- Image -->
                    <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                        @if ($user->avatar_original != null)
                            <img src="{{ $user_avatar }}" class="img-fit h-100" alt="{{ translate('avatar') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image" alt="{{ translate('avatar') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                        @endif
                    </span>
                    <!-- Name -->
                    <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">{{ $user->name }}</h4>
                </span>
            @else
                <!--Login & Registration -->
                <span class="d-flex align-items-center nav-user-info pl-4">
                    <!-- Image -->
                    <span
                        class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012"
                            viewBox="0 0 19.902 20.012">
                            <path id="fe2df171891038b33e9624c27e96e367"
                                d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                                transform="translate(-2.064 -1.995)" fill="#91919b" />
                        </svg>
                    </span>
                    <a href="{{ route('user.login') }}"
                        class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                    <a href="{{ route('user.registration') }}"
                        class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>
                </span>
            @endauth
            <hr>
            <ul class="mb-0 pl-3 pb-3 h-100">
                @if (get_setting('header_menu_labels') != null)
                    @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                        <li class="mr-0">
                            <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                            @if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) active @endif">
                                {{ translate($value) }}
                            </a>
                        </li>
                    @endforeach
                @endif
                @auth
                    @if (isAdmin())
                        <hr>
                        <li class="mr-0">
                            <a href="{{ route('admin.dashboard') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links">
                                {{ translate('My Account') }}
                            </a>
                        </li>
                    @else
                        <hr>
                        <li class="mr-0">
                            <a href="{{ route('dashboard') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['dashboard'], ' active') }}">
                                {{ translate('My Account') }}
                            </a>
                        </li>
                    @endif
                    @if (isCustomer())
                        <li class="mr-0">
                            <a href="{{ route('all-notifications') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['all-notifications'], ' active') }}">
                                {{ translate('Notifications') }}
                            </a>
                        </li>
                        <li class="mr-0">
                            <a href="{{ route('wishlists.index') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                                {{ translate('Wishlist') }}
                            </a>
                        </li>
                        <li class="mr-0">
                            <a href="{{ route('compare') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['compare'], ' active') }}">
                                {{ translate('Compare') }}
                            </a>
                        </li>
                    @endif
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('logout') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-primary header_menu_links">
                            {{ translate('Logout') }}
                        </a>
                    </li>
                @endauth
            </ul>
            <br>
            <br>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script type="text/javascript">
            function show_order_details(order_id) {
                $('#order-details-modal-body').html(null);

                if (!$('#modal-size').hasClass('modal-lg')) {
                    $('#modal-size').addClass('modal-lg');
                }

                $.post('{{ route('orders.details') }}', {
                    _token: AIZ.data.csrf,
                    order_id: order_id
                }, function(data) {
                    $('#order-details-modal-body').html(data);
                    $('#order_details').modal();
                    $('.c-preloader').hide();
                    AIZ.plugins.bootstrapSelect('refresh');
                });
            }
        </script>
    @endsection
