@extends('frontend.layouts.app')

@section('content')
    <style>
        #section_featured .slick-slider .slick-list{
            background: #fff;
        }
        #flash_deal .slick-slider .slick-list .slick-slide,
        #section_featured .slick-slider .slick-list .slick-slide {
            margin-bottom: -5px;
        }
        @media (max-width: 991px){
            #flash_deal .slick-slider .slick-list .slick-slide{
                margin-bottom: 0px;
            }
        }
        @media (max-width: 575px){
            #section_featured .slick-slider .slick-list .slick-slide {
                margin-bottom: -4px;
            }
        }
    </style>

    @php $lang = get_system_language()->code;  @endphp

    <!-- Sliders old
    <div class="container">
    <div class="home-banner-area mb-3">
        <div class="banner-inner">
            <!-- Sliders
            <div class="home-slider slider-full">
                @if (get_setting('home_slider_images') != null)
                    <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height" data-autoplay="true" data-dots="true" data-infinite="true">

                        @php
                            $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
                            $sliders = get_slider_images($decoded_slider_images);
                        @endphp
                        @foreach ($sliders as $key => $slider)
                            <div class="carousel-box">

                                <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                    <!-- Image
                                    <div class="d-block mw-100 img-fit overflow-hidden h-180px h-md-320px h-lg-460px h-xl-553px overflow-hidden radius-banner">
                                        <img class="img-fit h-100 m-auto has-transition ls-is-cached lazyloaded"
                                        src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ env('APP_NAME') }} promo"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>-->
<div class="container">
    <div class="home-banner-area">
        <div class="banner-inner">
            <!-- Sliders -->
            <div class="home-slider slider-full">
                @if (get_setting('home_slider_images') != null)
                    <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height" data-autoplay="true" data-dots="true" data-infinite="true">

                        @php
                            $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
                            $sliders = get_slider_images($decoded_slider_images);
                        @endphp
                        @foreach ($sliders as $key => $slider)
                            <div class="carousel-box">

                                <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                    <!-- Image -->
                                    <div class="d-block mw-100 img-fit overflow-hidden h-424px h-md-424px h-lg-460px h-xl-553px overflow-hidden radius-banner">
                                    <div class="col-12 col-xl-6 col-lg-8 col-md-10 slider-content">
                                        <div class="col-12 slider-content-title fs-32 font-prompt-exd px-5 pt-5 pb-2">
                                            UAE's Premier Construction Marketplace
                                        </div>
                                        <div class="col-12 slider-content-desc fs-16 font-prompt px-5">
                                            Effortlessly Buy and Sell Materials, Equipment, and Services—all on One Platform.Effortlessly Buy and Sell Materials, Equipment, and Services—all on One Easy-to-Use, Reliable, and Secure Platform that Streamlines Your Transactions.
                                        </div>
                                        <div class="col-12 px-5 pt-4">
                                            <button type="button" class="btn btn-secondary-base slider-register-vendor text-white border-radius-16 fs-16 font-prompt py-2">
                                                Register as Vendor
                                                <svg width="20" height="20" viewBox="0 0 24 24" class="ml-1" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.43 5.92993L20.5 11.9999L14.43 18.0699" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M3.5 12H20.33" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                            <button type="button" class="slider-buyder-btn fs-16 font-prompt" href="#">Join Buyer Waitlist</button>
                                        </div>
                                    </div>
                                        <img class="img-fit-slider h-100 m-auto has-transition ls-is-cached lazyloaded"
                                        src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ env('APP_NAME') }} promo"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </div>
                                </a>
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
        $flash_deal_bg_full_width = (get_setting('flash_deal_bg_full_width') == 1) ? true : false;
        $flash_deal_banner_menu_text = ((get_setting('flash_deal_banner_menu_text') == 'dark') ||  (get_setting('flash_deal_banner_menu_text') == null)) ? 'text-dark' : 'text-white';

    @endphp
    @if ($flash_deal != null)
        <section class="mb-2 mb-md-3" style="background: {{ ($flash_deal_bg_full_width && $flash_deal_bg != null) ? $flash_deal_bg : '' }};" id="flash_deal">
            <div class="container">
                <!-- Top Section sm to lg -->
                <div class="d-flex d-lg-none flex-wrap mb-2 mb-md-3 @if ($flash_deal_bg_full_width && $flash_deal_bg != null) pt-2 pt-md-3 @endif align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span class="d-inline-block {{ ($flash_deal_bg_full_width && $flash_deal_bg != null) ? $flash_deal_banner_menu_text : 'text-dark'}}">{{ translate('Flash Sale') }}</span>
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
                                class="fs-10 fs-md-12 fw-700 has-transition @if ((get_setting('flash_deal_banner_menu_text') == 'light') && $flash_deal_bg_full_width && $flash_deal_bg != null) text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif mr-3">{{ translate('View All Flash Sale') }}</a>
                            <span class=" border-left border-soft-light border-width-2 pl-3">
                                <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                    class="fs-10 fs-md-12 fw-700 has-transition @if ((get_setting('flash_deal_banner_menu_text') == 'light') && $flash_deal_bg_full_width && $flash_deal_bg != null) == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif">{{ translate('View All Products from This Flash Sale') }}</a>
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
                            <div class="d-none d-lg-flex flex-wrap mb-2 mb-md-3 align-items-baseline justify-content-between">
                                <!-- Title -->
                                <h3 class="fs-16 fs-md-20 fw-700 mb-2">
                                    <span class="d-inline-block {{ $flash_deal_banner_menu_text }}">{{ translate('Flash Sale') }}</span>
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
                                    <div class="carousel-box bg-white @if ($i == 0) border-left @endif">
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
    <!--
    <div id="todays_deal" class="mb-2rem mt-2 mt-md-3" @if(get_setting('todays_deal_section_bg') == 1) style="background: {{ $todays_deal_section_bg }};" @endif>

    </div>
-->
    <!-- Featured Categories -->
    @if (count($featured_categories) > 0)
        <section class="mb-2 mb-md-3">
            <div class="container">
                <div class="bg-white pb-2">
                    <!-- Top Section -->
                    <div class="d-flexZ align-items-baseline justify-content-between">
                        <!-- Title -->
                        <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                            <span class="">{{ translate('Featured Categories') }}</span>
                        </h3>
                    </div>
                </div>
                <!-- Categories -->
                <div class="bg-white px-sm-3">
                    <div class="aiz-carousel sm-gutters-17" id="product-carousel" data-items="7" data-xxl-items="7" data-xl-items="6"
                        data-lg-items="5" data-md-items="3" data-sm-items="3" data-xs-items="2" data-arrows="true"
                        data-dots="false" data-autoplay="false" data-infinite="true"  style="min-height:250px;">
                        @foreach ($featured_categories as $key => $category)
                            @php
                                $category_name = $category->getTranslation('name');
                            @endphp

                            <div class="carousel-box position-relative p-0 has-transition">
                                <div class="h-250px p-4">
                                    <div class="h-150px w-150px w-xl-auto position-relative overflow-hidden radius-category">
                                        <div class="position-absolute h-100 w-100 overflow-hidden categ-image-shape">
                                            <img src="{{ isset($category->cover_image) ? my_asset('public/'.$category->cover_image) : static_asset('assets/img/placeholder.jpg') }}"
                                                alt="{{ $category_name }}"
                                                class="img-fit h-100 has-transition radius-category categ-img" loading="lazy"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </div>

                                    </div>
                                    <div style="top:180px;" class="px-4 absolute-bottom-left has-transition h-50 w-100 d-flex flex-column align-items-center justify-content-start align-center">
                                                <a class="d-flex flex-wrap overflow-hidden fs-15 text-dark home-category-name align-items-center hov-column-gap-1"
                                                    href="{{ route('products.category', $category->slug) }}"
                                                    >
                                                   <center>{{ $category_name }}&nbsp;</center>
                                                </a>
                                                <!--
                                                <div class="d-flex flex-wrap h-50px overflow-hidden mt-2">
                                                    @foreach ($category->childrenCategories->take(6) as $key => $child_category)
                                                    <a href="{{ route('products.category', $child_category->slug) }}" class="fs-13 fw-300 text-soft-dark hov-text-dark text-dark pr-3 pt-1">
                                                        {{ $child_category->getTranslation('name') }}
                                                    </a>
                                                    @endforeach
                                                </div>
                                            -->
                                        </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Banner section 1 -->
    @if (get_setting('home_banner1_images') != null)
        <div class="pb-2 pb-md-3 pt-2 pt-md-3">
            <div class="container mb-2 mb-md-3">
                @php
                    $banner_1_imags = json_decode(get_setting('home_banner1_images', null, $lang));
                    $data_md = count($banner_1_imags) >= 2 ? 2 : 1;
                @endphp
                <div class="w-100">
                    <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                        data-items="{{ count($banner_1_imags) }}" data-xxl-items="{{ count($banner_1_imags) }}"
                        data-xl-items="{{ count($banner_1_imags) }}" data-lg-items="{{ $data_md }}"
                        data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                        data-dots="false">
                        @foreach ($banner_1_imags as $key => $value)
                            <div class="carousel-box overflow-hidden hov-scale-img">
                                <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}"
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
        </div>
    @endif

    <!-- Featured Products -->
    <div id="section_featured" class="pt-2 pt-md-3">

    </div>

    <!-- Banner Section 2 -->
    @if (get_setting('home_banner2_images') != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
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
    <div id="section_best_selling">

    </div>

    <!-- New Products -->
    <div id="section_newest">

    </div>

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
        <div id="auction_products">

        </div>
    @endif

    <!-- Cupon -->
    @if (get_setting('coupon_system') == 1)
        <div class=" mt-2 mt-md-3"
            style="background-color: {{ get_setting('cupon_background_color', '#292933') }}">
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
                        <img class="h-100" src="{{ uploaded_asset(get_setting('coupon_background_image', null, $lang)) }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/coupon.svg') }}';"
                            alt="{{ env('APP_NAME') }} promo">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Category wise Products -->
    <div id="section_home_categories">

    </div>

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
            $best_selers = get_best_sellers(5);
        @endphp
        @if (count($best_selers) > 0)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span class="pb-3">{{ translate('Top Sellers') }}</span>
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
                    @foreach ($best_selers as $key => $seller)
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
                                                data-src="{{ uploaded_asset($seller->logo) }}" alt="{{ $seller->name }}"
                                                class="img-fit lazyload has-transition"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </a>
                                    </div>
                                    <!-- Shop name -->
                                    <h2 class="fs-14 fw-700 text-dark text-truncate-2 h-40px mt-3 mt-md-4 mb-0 mb-md-3">
                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                            class="text-reset hov-text-primary" tabindex="0">{{ $seller->name }}</a>
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
                                        <span class="absolute-top-right mr-05rem mt-05rem">
                                            <img class="International-eShops-img" src="{{ static_asset('assets/img/International-eShops.png') }}">
                                        </span>
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
    <div class="container mb-5">
        <!-- Easy to shop -->
        <div class="col-xl-3 col-lg-6 col-12 pr-xl-3 pr-lg-3 pb-lg-3 pb-md-3 p-0 float-left">
            <div class="sllng-point col-md-12 p-5">
            <div class="col-12 p-0 pb-3">
                <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z" fill="#3D3D3B"/>
                    <path d="M14.6667 14.6666H16.9867C18.4267 14.6666 19.56 15.9066 19.44 17.3333L18.3334 30.6133C18.1467 32.7866 19.8667 34.6533 22.0533 34.6533H36.2534C38.1734 34.6533 39.8534 33.08 40 31.1733L40.72 21.1733C40.88 18.96 39.2 17.16 36.9733 17.16H19.76" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M33.6667 41.3333C34.5871 41.3333 35.3333 40.5871 35.3333 39.6667C35.3333 38.7462 34.5871 38 33.6667 38C32.7462 38 32 38.7462 32 39.6667C32 40.5871 32.7462 41.3333 33.6667 41.3333Z" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M23 41.3333C23.9205 41.3333 24.6666 40.5871 24.6666 39.6667C24.6666 38.7462 23.9205 38 23 38C22.0795 38 21.3333 38.7462 21.3333 39.6667C21.3333 40.5871 22.0795 41.3333 23 41.3333Z" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M24 22.6666H40" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="col-12 p-0">
                <span class="sllng-point-title fs-24 font-prompt-md">Easy to Shop</span>
            </div>
            <div class="col-12 p-0 mt-2">
                <span class="sllng-point-desc fs-18 font-prompt">Effortlessly find and order construction materials with our user-friendly platform.</span>
            </div>
        </div>
        </div>
        <!-- 24/7 Support -->
        <div class="col-xl-3 col-lg-6 col-12 pr-xl-3 pr-lg-0 pb-lg-3 pb-md-3 p-0 float-left">
            <div class="sllng-point bg-support col-md-12 p-5">
            <div class="col-12 p-0 pb-3">
                <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z" fill="#3D3D3B"/>
                    <path d="M36 37.1467H34.9867C33.92 37.1467 32.9067 37.56 32.16 38.3067L29.88 40.5601C28.84 41.5868 27.1467 41.5868 26.1067 40.5601L23.8267 38.3067C23.08 37.56 22.0533 37.1467 21 37.1467H20C17.7867 37.1467 16 35.3734 16 33.1867V18.64C16 16.4533 17.7867 14.6801 20 14.6801H36C38.2133 14.6801 40 16.4533 40 18.64V33.1867C40 35.3601 38.2133 37.1467 36 37.1467Z" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21.3333 24.2134C21.3333 22.9734 22.3467 21.96 23.5867 21.96C24.8267 21.96 25.84 22.9734 25.84 24.2134C25.84 26.72 22.28 26.9867 21.4933 29.3734C21.3333 29.8667 21.7467 30.36 22.2667 30.36H25.84" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M33.3867 30.3466V22.7333C33.3867 22.3867 33.16 22.0799 32.8267 21.9866C32.4933 21.8933 32.1333 22.0266 31.9467 22.3199C30.9867 23.8666 29.9467 25.6266 29.04 27.1733C28.8933 27.4266 28.8933 27.7599 29.04 28.0133C29.1867 28.2666 29.4667 28.4265 29.7733 28.4265H34.6667" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="col-12 p-0">
                <span class="sllng-point-title fs-24 font-prompt-md">24/7 Support</span>
            </div>
            <div class="col-12 p-0 mt-2">
                <span class="sllng-point-desc fs-18 font-prompt">Get assistance anytime, anywhere with our round-the-clock support team.</span>
            </div>
        </div>
        </div>
        <!-- Fast & Free Shipping -->
        <div class="col-xl-3 col-lg-6 col-12 pr-xl-3 pr-lg-3 pb-md-3 p-0 float-left">
            <div class="sllng-point col-md-12 p-5">
            <div class="col-12 p-0 pb-3">
                <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z" fill="#3D3D3B"/>
                    <path d="M16.2267 21.9199L28 28.7332L39.6933 21.9599" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M28 40.8132V28.7198" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.24 15.3066L18.12 19.2667C16.5067 20.16 15.1867 22.4 15.1867 24.24V31.7733C15.1867 33.6133 16.5067 35.8533 18.12 36.7466L25.24 40.7067C26.76 41.5467 29.2533 41.5467 30.7733 40.7067L37.8933 36.7466C39.5067 35.8533 40.8267 33.6133 40.8267 31.7733V24.24C40.8267 22.4 39.5067 20.16 37.8933 19.2667L30.7733 15.3066C29.24 14.4533 26.76 14.4533 25.24 15.3066Z" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M34.6667 29.6534V24.7734L22.0133 17.4667" stroke="#F3F4F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="col-12 p-0">
                <span class="sllng-point-title fs-24 font-prompt-md">Fast & Free Shipping</span>
            </div>
            <div class="col-12 p-0 mt-2">
                <span class="sllng-point-desc fs-18 font-prompt">Enjoy prompt delivery with no additional cost on all orders.</span>
            </div>
        </div>
        </div>
        <!-- Fast & Free Shipping -->
        <div class="col-xl-3 col-lg-6 col-12 p-0 float-left">
            <div class="sllng-point col-md-12 p-5">
            <div class="col-12 p-0 pb-3">
                <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 16C0 7.16344 7.16344 0 16 0H40C48.8366 0 56 7.16344 56 16V40C56 48.8366 48.8366 56 40 56H16C7.16344 56 0 48.8366 0 40V16Z" fill="#3D3D3B"/>
                    <path d="M39.3333 31.9867L32.6533 38.68" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16.6667 31.9867H39.3333" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16.6667 24.0133L23.3467 17.3199" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M39.3333 24.0133H16.6667" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="col-12 p-0">
                <span class="sllng-point-title fs-24 font-prompt-md">Hassle Free Returns</span>
            </div>
            <div class="col-12 p-0 mt-2">
                <span class="sllng-point-desc fs-18 font-prompt">Return or exchange items hassle-free within our specified timeframe.</span>
            </div>
        </div>
        </div>
    </div>
@endsection

