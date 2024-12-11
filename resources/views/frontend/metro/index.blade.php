@extends('frontend.layouts.app')

@section('content')
    <style>
        #section_featured .slick-slider .slick-list {
            background: #fff;
        }

        #flash_deal .slick-slider .slick-list .slick-slide,
        #section_featured .slick-slider .slick-list .slick-slide {
            margin-bottom: -5px;
        }

        @media (max-width: 991px) {
            #flash_deal .slick-slider .slick-list .slick-slide {
                margin-bottom: 0px;
            }
        }

        @media (max-width: 575px) {
            #section_featured .slick-slider .slick-list .slick-slide {
                margin-bottom: -4px;
            }
        }
    </style>

    @php $lang = get_system_language()->code;  @endphp

    <div class="col-12 p-0">
        <div class="home-banner-area">
            <div class="banner-inner">
                <!-- Sliders -->
                <div class="home-slider slider-full">
                    @if (get_setting('home_slider_images') != null)
                        <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height" data-autoplay="true"
                            data-dots="true" data-infinite="true">
                            @php
                                $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
                                $sliders = get_slider_images($decoded_slider_images);
                            @endphp
                            @foreach ($sliders as $key => $slider)
                                <div class="carousel-box">
                                    <!-- Image -->
                                    <div
                                        class="d-block mw-100 img-fit overflow-hidden h-424px h-md-424px h-lg-460px h-xl-504px overflow-hidden">
                                        <div class="slider-content-container col-12 d-flex justify-content-center">
                                            <div class="container">
                                                <div class="col-12 col-xl-6 col-lg-8 col-md-10 slider-content pt-md-5 pt-4">
                                                    <div
                                                        class="col-12 slider-content-title fs-banner-title font-prompt-exd p-0 pt-5 pb-4">
                                                        Welcome to the UAE’s Premier Marketplace for Construction Materials,
                                                        Equipment, and Services.
                                                    </div>
                                                    <div class="col-12 slider-content-desc fs-16 font-prompt p-0">
                                                        Effortlessly buy and sell on one convenient platform.
                                                    </div>
                                                    <div class="col-12 pt-5 p-0">
                                                        <a href="https://business.mawadonline.com/"><button type="button"
                                                                class="btn btn-secondary-base slider-register-vendor text-white border-radius-16 fs-16 font-prompt py-2">
                                                                {{ __('Register as Vendor') }}
                                                            </button></a>
                                                        <a href="https://about.mawadonline.com/"><button type="button"
                                                                class="btn bg-white slider-register-buyer margin-s-r-b text-secondary-base border-radius-16 fs-16 font-prompt py-2">
                                                                {{ __('Join Buyer Waitlist') }}
                                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M14.4299 5.92993L20.4999 11.9999L14.4299 18.0699"
                                                                        stroke="#CB774B" stroke-width="1.5"
                                                                        stroke-miterlimit="10" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                    <path d="M3.5 12H20.33" stroke="#CB774B"
                                                                        stroke-width="1.5" stroke-miterlimit="10"
                                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>
                                                            </button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <img class="img-fit-slider h-100 m-auto has-transition ls-is-cached lazyloaded"
                                            src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                            alt="{{ env('APP_NAME') }} promo"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Flash Deal -->
    @php
        $flash_deal = get_featured_flash_deal();
        $flash_deal_bg = get_setting('flash_deal_bg_color');
        $flash_deal_bg_full_width = get_setting('flash_deal_bg_full_width') == 1 ? true : false;
        $flash_deal_banner_menu_text = get_setting('flash_deal_banner_menu_text') == 'dark' || get_setting('flash_deal_banner_menu_text') == null ? 'text-dark' : 'text-white';
    @endphp

    @if ($flash_deal != null)
        <section class="mb-2 mb-md-3"
            style="background: {{ $flash_deal_bg_full_width && $flash_deal_bg != null ? $flash_deal_bg : '' }};"
            id="flash_deal">
            <div class="container">
                <!-- Top Section sm to lg -->
                <div
                    class="d-flex d-lg-none flex-wrap mb-2 mb-md-3 @if ($flash_deal_bg_full_width && $flash_deal_bg != null) pt-2 pt-md-3 @endif align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span
                            class="d-inline-block {{ $flash_deal_bg_full_width && $flash_deal_bg != null ? $flash_deal_banner_menu_text : 'text-dark' }}">{{ translate('Flash Sale') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 16 24"
                            class="ml-3">
                            <path id="Path_28795" data-name="Path 28795"
                                d="M30.953,13.695a.474.474,0,0,0-.424-.25h-4.9l3.917-7.81a.423.423,0,0,0-.028-.428.477.477,0,0,0-.4-.207H21.588a.473.473,0,0,0-.429.263L15.041,18.151a.423.423,0,0,0,.034.423.478.478,0,0,0,.4.2h4.593l-2.229,9.683a.438.438,0,0,0,.259.5.489.489,0,0,0,.571-.127L30.9,14.164a.425.425,0,0,0,.054-.469Z"
                                transform="translate(-15 -5)" fill="#fcc201" />
                        </svg>
                    </h3>
                    <!-- Links -->
                    <div>
                        <div class="text-dark d-flex align-items-center mb-0">
                            <a href="{{ route('flash-deals') }}"
                                class="fs-10 fs-md-12 fw-700 has-transition @if (get_setting('flash_deal_banner_menu_text') == 'light' && $flash_deal_bg_full_width && $flash_deal_bg != null) text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif mr-3">{{ translate('View All Flash Sale') }}</a>
                            <span class=" border-left border-soft-light border-width-2 pl-3">
                                <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                    class="fs-10 fs-md-12 fw-700 has-transition @if (get_setting('flash_deal_banner_menu_text') == 'light' && $flash_deal_bg_full_width && $flash_deal_bg != null) == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif">{{ translate('View All Products from This Flash Sale') }}</a>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Countdown for small device -->
                <div class="bg-white mb-3 d-md-none">
                    <div class="aiz-count-down-circle" end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                </div>

                <div class="row no-gutters align-items-center" style="background: {{ $flash_deal_bg }};">
                    <!-- Flash Deals Baner & Countdown -->
                    <div class="col-xxl-4 col-lg-5 col-6 h-200px h-md-400px h-lg-475px">
                        <div class="h-100 w-100 w-xl-auto"
                            style="background-image: url('{{ uploaded_asset($flash_deal->banner) }}'); background-size: cover; background-position: center center;">
                            <div class="py-5 px-md-3 px-xl-5 d-none d-md-block">
                                <div class="bg-white">
                                    <div class="aiz-count-down-circle"
                                        end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-8 col-lg-7 col-6">
                        <div class="pl-3 pr-lg-3 pl-xl-2rem pr-xl-2rem">
                            <!-- Top Section from lg device -->
                            <div
                                class="d-none d-lg-flex flex-wrap mb-2 mb-md-3 align-items-baseline justify-content-between">
                                <!-- Title -->
                                <h3 class="fs-16 fs-md-20 fw-700 mb-2">
                                    <span
                                        class="d-inline-block {{ $flash_deal_banner_menu_text }}">{{ translate('Flash Sale') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24"
                                        viewBox="0 0 16 24" class="ml-3">
                                        <path id="Path_28795" data-name="Path 28795"
                                            d="M30.953,13.695a.474.474,0,0,0-.424-.25h-4.9l3.917-7.81a.423.423,0,0,0-.028-.428.477.477,0,0,0-.4-.207H21.588a.473.473,0,0,0-.429.263L15.041,18.151a.423.423,0,0,0,.034.423.478.478,0,0,0,.4.2h4.593l-2.229,9.683a.438.438,0,0,0,.259.5.489.489,0,0,0,.571-.127L30.9,14.164a.425.425,0,0,0,.054-.469Z"
                                            transform="translate(-15 -5)" fill="#fcc201" />
                                    </svg>
                                </h3>
                                <!-- Links -->
                                <div>
                                    <div class="text-dark d-flex align-items-center mb-0">
                                        <a href="{{ route('flash-deals') }}"
                                            class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif mr-3">
                                            {{ translate('View All Flash Sale') }}
                                        </a>
                                        <span class=" border-left border-soft-light border-width-2 pl-3">
                                            <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                                class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif">{{ translate('View All Products from This Flash Sale') }}</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Flash Deals Products -->
                            @php
                                $flash_deal_products = get_flash_deal_products($flash_deal->id);
                            @endphp
                            <div class="aiz-carousel border-top @if (count($flash_deal_products) > 8) border-right @endif arrow-inactive-none arrow-x-0"
                                data-items="5" data-xxl-items="5" data-xl-items="3.5" data-lg-items="3" data-md-items="2"
                                data-sm-items="2.5" data-xs-items="2" data-arrows="true" data-dots="false">
                                @php
                                    $init = 0;
                                    $end = 1;
                                @endphp
                                @for ($i = 0; $i < 5; $i++)
                                    <div
                                        class="carousel-box bg-white @if ($i == 0) border-left @endif">
                                        @foreach ($flash_deal_products as $key => $flash_deal_product)
                                            @if ($key >= $init && $key <= $end)
                                                @if ($flash_deal_product->product != null && $flash_deal_product->product->published != 0)
                                                    @php
                                                        $product_url = route('product', $flash_deal_product->product->slug);
                                                        if ($flash_deal_product->product->auction_product == 1) {
                                                            $product_url = route('auction-product', $flash_deal_product->product->slug);
                                                        }
                                                    @endphp
                                                    <div
                                                        class="h-100px h-md-200px h-lg-auto flash-deal-item position-relative text-center border-bottom @if ($i != 4) border-right @endif has-transition hov-shadow-out z-1">
                                                        <a href="{{ $product_url }}"
                                                            class="d-block py-md-2 overflow-hidden hov-scale-img"
                                                            title="{{ $flash_deal_product->product->getTranslation('name') }}">
                                                            <!-- Image -->
                                                            <img src="{{ get_image($flash_deal_product->product->thumbnail) }}"
                                                                class="lazyload h-60px h-md-100px h-lg-120px mw-100 mx-auto has-transition"
                                                                alt="{{ $flash_deal_product->product->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                            <!-- Price -->
                                                            <div
                                                                class="fs-10 fs-md-14 mt-md-2 text-center h-md-48px has-transition overflow-hidden pt-md-4 flash-deal-price">
                                                                <span
                                                                    class="d-block text-primary fw-700">{{ home_discounted_base_price($flash_deal_product->product) }}</span>
                                                                @if (home_base_price($flash_deal_product->product) != home_discounted_base_price($flash_deal_product->product))
                                                                    <del
                                                                        class="d-block fw-400 text-secondary">{{ home_base_price($flash_deal_product->product) }}</del>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach

                                        @php
                                            $init += 2;
                                            $end += 2;
                                        @endphp
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Today's deal -->
    @php
        $todays_deal_section_bg = get_setting('todays_deal_section_bg_color');
    @endphp

    <!-- Featured Categories -->
    @if (count($featured_categories) > 0)
        <section class="mt-4">
            <div class="container mt-2">
                <div class="bg-white pb-2 px-1">
                    <!-- Top Section -->
                    <div class="d-flexZ align-items-baseline justify-content-between">
                        <!-- Title -->
                        <h3 class="fs-20 mb-2 mb-sm-0">
                            <span class="font-prompt-md">{{ translate('Popular Categories') }}</span>
                        </h3>
                    </div>
                </div>
                <!-- Categories -->
                <div class="bg-white px-sm-3">
                    <div class="aiz-carousel sm-gutters-17" id="product-carousel" data-items="7" data-xxl-items="7"
                        data-xl-items="6" data-lg-items="5" data-md-items="3" data-sm-items="3" data-xs-items="2"
                        data-arrows="true" data-dots="false" data-autoplay="false" data-infinite="true"
                        style="min-height:250px;">
                        @foreach ($featured_categories as $key => $category)
                            @php
                                $category_name = $category->getTranslation('name');
                            @endphp

                            <div class="carousel-box position-relative p-0 has-transition">
                                <div class="h-250px p-4">
                                    <div
                                        class="h-150px w-150px w-xl-auto position-relative overflow-hidden radius-category">
                                        <div class="position-absolute h-100 w-100 overflow-hidden categ-image-shape">
                                            <img src="{{ isset($category->cover_image) ? my_asset('public/' . $category->cover_image) : static_asset('assets/img/placeholder-shop.png') }}"
                                                alt="{{ $category_name }}"
                                                class="img-fit h-100 has-transition radius-category categ-img"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-shop.png') }}';">
                                        </div>

                                    </div>
                                    <div style="top:180px;"
                                        class="px-4 absolute-bottom-left has-transition h-50 w-100 d-flex flex-column align-items-center justify-content-start align-center">
                                        <a class="d-flex flex-wrap overflow-hidden fs-15 text-dark home-category-name align-items-center hov-column-gap-1"
                                            href="{{ route('products.category', $category->slug) }}">
                                            <center>{{ $category_name }}&nbsp;</center>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Banner Section 2 -->
    @if (get_setting('home_banner2_images') != null)
        <div class="mb-2 mb-md-3">
            <div class="container">
                @php
                    $banner_2_imags = json_decode(get_setting('home_banner2_images', null, $lang));
                    $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                    data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_2_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Best Selling  -->
    <div id="section_best_selling"></div>

    <!-- New Products -->
    <div id="section_newest"></div>

    <!-- Banner Section 3 -->
    @if (get_setting('home_banner3_images') != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                @php
                    $banner_3_imags = json_decode(get_setting('home_banner3_images', null, $lang));
                    $data_md = count($banner_3_imags) >= 2 ? 2 : 1;
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_3_imags) }}" data-xxl-items="{{ count($banner_3_imags) }}"
                    data-xl-items="{{ count($banner_3_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_3_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Auction Product -->
    @if (addon_is_activated('auction'))
        <div id="auction_products"></div>
    @endif

    <!-- Coupon -->
    @if (get_setting('coupon_system') == 1)
        <div class=" mt-2 mt-md-3" style="background-color: {{ get_setting('cupon_background_color', '#292933') }}">
            <div class="container">
                <div class="position-relative py-5">
                    <div class="text-center text-xl-left position-relative z-5">
                        <div class="d-lg-flex">
                            <div class="mb-3 mb-lg-0">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="109.602" height="93.34" viewBox="0 0 109.602 93.34">
                                    <defs>
                                        <clipPath id="clip-pathcup">
                                            <path id="Union_10" data-name="Union 10" d="M12263,13778v-15h64v-41h12v56Z"
                                                transform="translate(-11966 -8442.865)" fill="none" stroke="#fff"
                                                stroke-width="2" />
                                        </clipPath>
                                    </defs>
                                    <g id="Group_24326" data-name="Group 24326"
                                        transform="translate(-274.201 -5254.611)">
                                        <g id="Mask_Group_23" data-name="Mask Group 23"
                                            transform="translate(-3652.459 1785.452) rotate(-45)"
                                            clip-path="url(#clip-pathcup)">
                                            <g id="Group_24322" data-name="Group 24322"
                                                transform="translate(207 18.136)">
                                                <g id="Subtraction_167" data-name="Subtraction 167"
                                                    transform="translate(-12177 -8458)" fill="none">
                                                    <path
                                                        d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                        stroke="none" />
                                                    <path
                                                        d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                        stroke="none" fill="#fff" />
                                                </g>
                                            </g>
                                        </g>
                                        <g id="Group_24321" data-name="Group 24321"
                                            transform="translate(-3514.477 1653.317) rotate(-45)">
                                            <g id="Subtraction_167-2" data-name="Subtraction 167"
                                                transform="translate(-12177 -8458)" fill="none">
                                                <path
                                                    d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                    stroke="none" />
                                                <path
                                                    d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                    stroke="none" fill="#fff" />
                                            </g>
                                            <g id="Group_24325" data-name="Group 24325">
                                                <rect id="Rectangle_18578" data-name="Rectangle 18578" width="8"
                                                    height="2" transform="translate(120 5287)" fill="#fff" />
                                                <rect id="Rectangle_18579" data-name="Rectangle 18579" width="8"
                                                    height="2" transform="translate(132 5287)" fill="#fff" />
                                                <rect id="Rectangle_18581" data-name="Rectangle 18581" width="8"
                                                    height="2" transform="translate(144 5287)" fill="#fff" />
                                                <rect id="Rectangle_18580" data-name="Rectangle 18580" width="8"
                                                    height="2" transform="translate(108 5287)" fill="#fff" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="ml-lg-3">
                                <h5 class="fs-36 fw-400 text-white mb-3">{{ translate(get_setting('cupon_title')) }}</h5>
                                <h5 class="fs-20 fw-400 text-gray">{{ translate(get_setting('cupon_subtitle')) }}</h5>
                                <div class="mt-5 pt-5">
                                    <a href="{{ route('coupons.all') }}"
                                        class="btn text-white hov-bg-white hov-text-dark border border-width-2 fs-16 px-5"
                                        style="border-radius: 28px;background: rgba(255, 255, 255, 0.2);box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.16);">{{ translate('View All Coupons') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute right-0 bottom-0 h-100">
                        <img class="h-100"
                            src="{{ uploaded_asset(get_setting('coupon_background_image', null, $lang)) }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/coupon.svg') }}';"
                            alt="{{ env('APP_NAME') }} promo">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Category wise Products -->
    <div id="section_home_categories"></div>

    <!-- Classified Product -->
    @if (get_setting('classified_product') == 1)
        @php
            $classified_products = get_home_page_classified_products(6);
        @endphp
        @if (count($classified_products) > 0)
            <section class="mb-2 mb-md-3 mt-3 mt-md-5">
                <div class="container">
                    <!-- Top Section -->
                    <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                        <!-- Title -->
                        <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                            <span class="">{{ translate('Classified Ads') }}</span>
                        </h3>
                        <!-- Links -->
                        <div class="d-flex">
                            <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                href="{{ route('customer.products') }}">{{ translate('View All Products') }}</a>
                        </div>
                    </div>
                    <!-- Banner -->
                    @php
                        $classifiedBannerImage = get_setting('classified_banner_image', null, $lang);
                        $classifiedBannerImageSmall = get_setting('classified_banner_image_small', null, $lang);
                    @endphp
                    @if ($classifiedBannerImage != null || $classifiedBannerImageSmall != null)
                        <div class="mb-3 overflow-hidden hov-scale-img d-none d-md-block">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($classifiedBannerImage) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                        <div class="mb-3 overflow-hidden hov-scale-img d-md-none">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ $classifiedBannerImageSmall != null ? uploaded_asset($classifiedBannerImageSmall) : uploaded_asset($classifiedBannerImage) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                    @endif
                    <!-- Products Section -->
                    <div class="bg-white pt-3">
                        <div class="row no-gutters border-top border-left">
                            @foreach ($classified_products as $key => $classified_product)
                                <div
                                    class="col-xl-4 col-md-6 border-right border-bottom has-transition hov-shadow-out z-1">
                                    <div class="aiz-card-box p-2 has-transition bg-white">
                                        <div class="row hov-scale-img">
                                            <div class="col-4 col-md-5 mb-3 mb-md-0">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                    class="d-block overflow-hidden h-auto h-md-150px text-center">
                                                    <img class="img-fluid lazyload mx-auto has-transition"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ isset($classified_product->thumbnail->file_name) ? my_asset($classified_product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                        alt="{{ $classified_product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                            </div>
                                            <div class="col">
                                                <h3
                                                    class="fw-400 fs-14 text-dark text-truncate-2 lh-1-4 mb-3 h-35px d-none d-sm-block">
                                                    <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                        class="d-block text-reset hov-text-primary">{{ $classified_product->getTranslation('name') }}</a>
                                                </h3>
                                                <div class="fs-14 mb-3">
                                                    <span
                                                        class="text-secondary">{{ $classified_product->user ? $classified_product->user->name : '' }}</span><br>
                                                    <span
                                                        class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                                </div>
                                                @if ($classified_product->conditon == 'new')
                                                    <span
                                                        class="badge badge-inline badge-soft-info fs-13 fw-700 px-3 py-2 text-info"
                                                        style="border-radius: 20px;">{{ translate('New') }}</span>
                                                @elseif($classified_product->conditon == 'used')
                                                    <span
                                                        class="badge badge-inline badge-soft-secondary-base fs-13 fw-700 px-3 py-2 text-danger"
                                                        style="border-radius: 20px;">{{ translate('Used') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- Top Sellers -->
    @if (get_setting('vendor_system_activation') == 1)
        @php
            $best_sellers = get_best_sellers(5);
        @endphp
        @if (count($best_sellers) > 0)
            <section class="mb-2 mb-md-3 mt-2 mt-md-3">
                <div class="container">
                    <!-- Top Section -->
                    <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                        <!-- Title -->
                        <h3 class="fs-20 mb-md-1 mb-sm-0">
                            <span class="font-prompt-md">{{ translate('Top Sellers') }}</span>
                        </h3>
                        <!-- Links -->
                        <div class="d-flex">
                            <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                href="{{ route('sellers') }}">{{ translate('View All Sellers') }}</a>
                        </div>
                    </div>
                    <!-- Sellers Section -->
                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="5" data-xxl-items="5"
                        data-xl-items="4" data-lg-items="3.4" data-md-items="2.5" data-sm-items="2" data-xs-items="1.4"
                        data-arrows="true" data-dots="false">
                        @foreach ($best_sellers as $key => $seller)
                            @if ($seller->user != null)
                                <div
                                    class="carousel-box h-100 position-relative text-center border-right border-top border-bottom @if ($key == 0) border-left @endif has-transition hov-animate-outline">
                                    <div class="position-relative px-3" style="padding-top: 2rem; padding-bottom:2rem;">
                                        <!-- Shop logo & Verification Status -->
                                        <div class="mx-auto size-100px size-md-120px">
                                            <a href="{{ route('shop.visit', $seller->slug) }}"
                                                class="d-flex mx-auto justify-content-center align-item-center size-100px size-md-120px border overflow-hidden hov-scale-img"
                                                tabindex="0"
                                                style="border: 1px solid #e5e5e5; border-radius: 50%; box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                    data-src="{{ uploaded_asset($seller->logo) }}"
                                                    alt="{{ $seller->name }}" class="img-fit lazyload has-transition"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                            </a>
                                        </div>
                                        <!-- Shop name -->
                                        <h2
                                            class="fs-14 fw-700 text-dark text-truncate-2 h-40px mt-3 mt-md-4 mb-0 mb-md-3">
                                            <a href="{{ route('shop.visit', $seller->slug) }}"
                                                class="text-reset hov-text-primary"
                                                tabindex="0">{{ $seller->name }}</a>
                                        </h2>
                                        <!-- Shop Rating -->
                                        <div class="rating rating-mr-1 text-dark mb-3">
                                            {{ renderStarRating($seller->rating) }}
                                            <span class="opacity-60 fs-14">({{ $seller->num_of_reviews }}
                                                {{ translate('Reviews') }})</span>
                                        </div>
                                        <!-- Visit Button -->
                                        <a href="{{ route('shop.visit', $seller->slug) }}" class="btn-visit">
                                            <span class="circle" aria-hidden="true">
                                                <span class="icon arrow"></span>
                                            </span>
                                            <span class="button-text">{{ translate('visit eShop') }}</span>
                                        </a>
                                        @if ($seller->verification_status == 1)
                                            <!--<span class="absolute-top-right mr-05rem mt-05rem">
                                                <img class="International-eShops-img"
                                                    src="{{ static_asset('assets/img/International-eShops.png') }}">
                                            </span>-->
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- Top Brands -->
    @if (get_setting('top_brands') != null)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">{{ translate('Top Brands') }}</h3>
                    <!-- Links -->
                    <div class="d-flex">
                        <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                            href="{{ route('brands.all') }}">{{ translate('View All Brands') }}</a>
                    </div>
                </div>
                <!-- Brands Section -->
                <div class="bg-white px-3">
                    <div
                        class="row row-cols-xxl-6 row-cols-xl-6 row-cols-lg-4 row-cols-md-4 row-cols-3 gutters-16 border-top border-left">
                        @php
                            $top_brands = json_decode(get_setting('top_brands'));
                            $brands = get_brands($top_brands);
                        @endphp
                        @foreach ($brands as $brand)
                            <div
                                class="col text-center border-right border-bottom hov-scale-img has-transition hov-shadow-out z-1">
                                <a href="{{ route('products.brand', $brand->slug) }}" class="d-block p-sm-3">
                                    <img src="{{ isset($brand->brandLogo->file_name) ? my_asset($brand->brandLogo->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        class="lazyload h-md-100px mx-auto has-transition p-2 p-sm-4 mw-100"
                                        alt="{{ $brand->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <p class="text-center text-dark fs-12 fs-md-14 fw-700 mt-2">
                                        {{ $brand->getTranslation('name') }}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    <div class="container mb-5 mt-5">
        <div class="bg-white pb-3 px-1">
            <!-- Top Section -->
            <div class="d-flexZ align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-20 mb-md-1 mb-sm-0">
                    <span class="font-prompt-md">Why Choose MawadOnline</span>
                </h3>
            </div>
        </div>
        <!-- 24/7 Virtual Showroom -->
        <div class="col-xl-4 col-lg-6 col-12 pr-xl-3 pr-lg-3 pb-lg-3 pb-md-3 p-0 pb-3 float-left">
            <div class="sllng-point col-md-12 p-5">
                <div class="col-12 p-0 pb-3">
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z"
                            fill="#3D3D3B" />
                        <path
                            d="M29.2266 15.0134L37.9066 19.6934C38.9199 20.24 38.9199 21.8 37.9066 22.3467L29.2266 27.0267C28.4533 27.44 27.5466 27.44 26.7733 27.0267L18.0933 22.3467C17.0799 21.8 17.0799 20.24 18.0933 19.6934L26.7733 15.0134C27.5466 14.6 28.4533 14.6 29.2266 15.0134Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M16.8134 25.5067L24.8801 29.5467C25.8801 30.0534 26.5201 31.08 26.5201 32.2V39.8267C26.5201 40.9334 25.3601 41.64 24.3734 41.1467L16.3067 37.1067C15.3067 36.6 14.6667 35.5734 14.6667 34.4534V26.8267C14.6667 25.72 15.8267 25.0134 16.8134 25.5067Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M39.1866 25.5067L31.12 29.5467C30.12 30.0534 29.48 31.08 29.48 32.2V39.8267C29.48 40.9334 30.64 41.64 31.6266 41.1467L39.6933 37.1067C40.6933 36.6 41.3333 35.5734 41.3333 34.4534V26.8267C41.3333 25.72 40.1733 25.0134 39.1866 25.5067Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="col-12 p-0">
                    <span class="sllng-point-title fs-24 font-prompt-md">24/7 Virtual Showroom</span>
                </div>
                <div class="col-12 p-0 mt-2">
                    <span class="sllng-point-desc fs-18 font-prompt">Browse and showcase products anytime, anywhere, with
                        our always-open online showroom.</span>
                </div>
            </div>
        </div>
        <!-- Secure Payments -->
        <div class="col-xl-4 col-lg-6 col-12 pr-xl-3 pr-lg-0 pb-lg-3 pb-md-3 p-0 pb-3 float-left">
            <div class="sllng-point bg-support col-md-12 p-5">
                <div class="col-12 p-0 pb-3">
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z"
                            fill="#3D3D3B" />
                        <path
                            d="M27.6534 35.6666H35.5468C35.4268 35.7733 35.3067 35.8667 35.1867 35.9733L29.4934 40.24C27.6134 41.64 24.5468 41.64 22.6534 40.24L16.9468 35.9733C15.6934 35.04 14.6667 32.9733 14.6667 31.4133V21.5333C14.6667 19.9066 15.9068 18.1066 17.4268 17.5333L24.0668 15.04C25.1601 14.6266 26.9734 14.6266 28.0668 15.04L34.6934 17.5333C35.9601 18.0133 37.0401 19.3466 37.3734 20.7066H27.6401C27.3467 20.7066 27.0801 20.72 26.8267 20.72C24.3601 20.8667 23.7201 21.76 23.7201 24.5733V31.8133C23.7334 34.88 24.5201 35.6666 27.6534 35.6666Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M23.7334 26.96H41.3334" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M41.3334 24.5601V31.96C41.3067 34.92 40.4934 35.6534 37.4134 35.6534H27.6534C24.5201 35.6534 23.7334 34.8667 23.7334 31.7867V24.5467C23.7334 21.7467 24.3734 20.8534 26.8401 20.6934C27.0934 20.6934 27.3601 20.6801 27.6534 20.6801H37.4134C40.5467 20.6934 41.3334 21.4667 41.3334 24.5601Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M27.0935 32.3467H28.8668" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M31.6667 32.3467H36.0268" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="col-12 p-0">
                    <span class="sllng-point-title fs-24 font-prompt-md">Secure Payments</span>
                </div>
                <div class="col-12 p-0 mt-2">
                    <span class="sllng-point-desc fs-18 font-prompt">Transact with peace of mind using our secure payment
                        system, safeguarding both buyers and vendors.</span>
                </div>
            </div>
        </div>
        <!-- Fast and Reliable Deliveries -->
        <div class="col-xl-4 col-lg-6 col-12 pb-md-3 p-0 pb-3 float-left">
            <div class="sllng-point col-md-12 p-5">
                <div class="col-12 p-0 pb-3">
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z"
                            fill="#3D3D3B" />
                        <path
                            d="M28 30.6666H29.3333C30.8 30.6666 32 29.4666 32 28V14.6666H20C18 14.6666 16.2533 15.7733 15.3467 17.3999"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M14.6667 34.6666C14.6667 36.88 16.4534 38.6666 18.6667 38.6666H20.0001C20.0001 37.2 21.2001 36 22.6667 36C24.1334 36 25.3334 37.2 25.3334 38.6666H30.6667C30.6667 37.2 31.8667 36 33.3334 36C34.8001 36 36.0001 37.2 36.0001 38.6666H37.3334C39.5468 38.6666 41.3334 36.88 41.3334 34.6666V30.6666H37.3334C36.6001 30.6666 36.0001 30.0666 36.0001 29.3333V25.3333C36.0001 24.6 36.6001 24 37.3334 24H39.0534L36.7734 20.0133C36.2934 19.1866 35.4135 18.6666 34.4535 18.6666H32.0001V28C32.0001 29.4666 30.8001 30.6666 29.3334 30.6666H28.0001"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M22.6667 41.3333C24.1394 41.3333 25.3333 40.1394 25.3333 38.6667C25.3333 37.1939 24.1394 36 22.6667 36C21.1939 36 20 37.1939 20 38.6667C20 40.1394 21.1939 41.3333 22.6667 41.3333Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M33.3334 41.3333C34.8062 41.3333 36.0001 40.1394 36.0001 38.6667C36.0001 37.1939 34.8062 36 33.3334 36C31.8607 36 30.6667 37.1939 30.6667 38.6667C30.6667 40.1394 31.8607 41.3333 33.3334 41.3333Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M41.3333 28V30.6667H37.3333C36.6 30.6667 36 30.0667 36 29.3333V25.3333C36 24.6 36.6 24 37.3333 24H39.0533L41.3333 28Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M14.6667 22.6666H22.6667" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M14.6667 26.6666H20.0001" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M14.6667 30.6666H17.3334" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="col-12 p-0">
                    <span class="sllng-point-title fs-24 font-prompt-md">Fast and Reliable Deliveries</span>
                </div>
                <div class="col-12 p-0 mt-2">
                    <span class="sllng-point-desc fs-18 font-prompt">Keep your projects on schedule—or deliver your orders
                        on time—with our quick and dependable delivery services.</span>
                </div>
            </div>
        </div>
        <!-- Trusted Marketplace -->
        <div class="col-xl-4 col-lg-6 pr-xl-3 pr-lg-3 pb-md-3 col-12 p-0 pb-3 float-left">
            <div class="sllng-point bg-support col-md-12 p-5">
                <div class="col-12 p-0 pb-3">
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z"
                            fill="#3D3D3B" />
                        <path
                            d="M25.9866 14.9735L19.3333 17.4801C17.8 18.0535 16.5466 19.8668 16.5466 21.4935V31.4001C16.5466 32.9735 17.5866 35.0401 18.8533 35.9868L24.5866 40.2668C26.4666 41.6801 29.56 41.6801 31.44 40.2668L37.1733 35.9868C38.44 35.0401 39.48 32.9735 39.48 31.4001V21.4935C39.48 19.8535 38.2266 18.0401 36.6933 17.4668L30.04 14.9735C28.9066 14.5601 27.0933 14.5601 25.9866 14.9735Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M24.0667 27.8268L26.2133 29.9734L31.9466 24.2401" stroke="#F3F4F5" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="col-12 p-0">
                    <span class="sllng-point-title fs-24 font-prompt-md">Trusted Marketplace</span>
                </div>
                <div class="col-12 p-0 mt-2">
                    <span class="sllng-point-desc fs-18 font-prompt">Build strong business relationships by leveraging
                        reviews and verified statuses to ensure credibility.</span>
                </div>
            </div>
        </div>
        <!-- Effortless Transaction Tracking -->
        <div class="col-xl-4 col-lg-6 pr-xl-3 pr-lg-3 pb-md-3 col-12 p-0 pb-3 float-left">
            <div class="sllng-point col-md-12 p-5">
                <div class="col-12 p-0 pb-3">
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z"
                            fill="#3D3D3B" />
                        <path
                            d="M39.3334 27.0666V21.3866C39.3334 16.0133 38.0801 14.6666 33.0401 14.6666H22.9601C17.9201 14.6666 16.6667 16.0133 16.6667 21.3866V36.3999C16.6667 39.9466 18.6134 40.7866 20.9734 38.2533L20.9867 38.24C22.0801 37.08 23.7467 37.1733 24.6934 38.4399L26.0401 40.24"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M36.2667 40.5333C38.6231 40.5333 40.5333 38.6231 40.5333 36.2667C40.5333 33.9103 38.6231 32 36.2667 32C33.9103 32 32 33.9103 32 36.2667C32 38.6231 33.9103 40.5333 36.2667 40.5333Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M41.3333 41.3333L40 40" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M22.6667 21.3334H33.3334" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M24 26.6666H32" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="col-12 p-0">
                    <span class="sllng-point-title fs-24 font-prompt-md">Effortless Transaction Tracking</span>
                </div>
                <div class="col-12 p-0 mt-2">
                    <span class="sllng-point-desc fs-18 font-prompt">Easily monitor your orders and payments, ensuring
                        transparency at every step.</span>
                </div>
            </div>
        </div>
        <!-- Informed Decisions -->
        <div class="col-xl-4 col-lg-6 col-12 p-0 float-left">
            <div class="sllng-point bg-support col-md-12 p-5">
                <div class="col-12 p-0 pb-3">
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z"
                            fill="#3D3D3B" />
                        <path
                            d="M23.0666 36.0534V34.5067C20 32.6534 17.48 29.04 17.48 25.2C17.48 18.6 23.5466 13.4267 30.4 14.92C33.4133 15.5867 36.0533 17.5867 37.4266 20.3467C40.2133 25.9467 37.28 31.8934 32.9733 34.4934V36.04C32.9733 36.4267 33.12 37.32 31.6933 37.32H24.3466C22.88 37.3334 23.0666 36.76 23.0666 36.0534Z"
                            stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M23.3333 41.3333C26.3866 40.4667 29.6133 40.4667 32.6666 41.3333" stroke="#F3F4F5"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="col-12 p-0">
                    <span class="sllng-point-title fs-24 font-prompt-md">Informed Decisions</span>
                </div>
                <div class="col-12 p-0 mt-2">
                    <span class="sllng-point-desc fs-18 font-prompt">Access detailed product information and reviews to
                        choose the best options for your construction needs.</span>
                </div>
            </div>
        </div>
    </div>
@endsection
