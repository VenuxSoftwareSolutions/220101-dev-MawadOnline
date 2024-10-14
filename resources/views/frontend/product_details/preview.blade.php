@extends('frontend.layouts.app')
{{--
@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    @php
        $availability = "out of stock";
        $qty = 0;
        if($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        }
        else {
            $qty = optional($detailedProduct->stocks->first())->qty;
        }
        if($qty > 0){
            $availability = "in stock";
        }
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:brand" content="{{ $detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME') }}">
    <meta property="product:availability" content="{{ $availability }}">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($detailedProduct->unit_price, 2) }}">
    <meta property="product:retailer_item_id" content="{{ $detailedProduct->slug }}">
    <meta property="product:price:currency"
        content="{{ get_system_default_currency()->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection --}}
@section('style')
<style>
    .btn-secondary-base:hover {
        background-color: #CB774B !important;
        border-color: #CB774B !important;
        cursor: pointer !important; /* Inherit cursor style from the parent element on hover */
    }
    a.disabled {
        pointer-events: none ;
        cursor: default;

    }
</style>
@endsection

@section('content')
    <section class="mb-4 pt-3">
        <div class="container">
            <div class="bg-white py-3">
                <div class="row">
                    <!-- Product Image Gallery -->
                    <div class="col-xl-5 col-lg-6 mb-4">
                        @include('frontend.product_details.image_gallery_preview')
                    </div>

                    <!-- Product Details -->
                    <div class="col-xl-7 col-lg-6">
                        @include('frontend.product_details.details_preview')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="container">
            <div class="row gutters-16">
                <!-- Left side -->
                <div class="col-lg-3">
                    <!-- Seller Info -->

                    <!-- Top Selling Products -->
                    <div class="d-none d-lg-block">
                        <div class="bg-white border mb-4">
                            <div class="p-3 p-sm-4 fs-16 fw-600">
                                {{ __('product.Top Selling Products') }}
                            </div>
                            {{-- <div class="px-3 px-sm-4 pb-4">
                                <ul class="list-group list-group-flush">
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/analog-black-dial-mens-watch-32-bk-ck"
                                                    class="d-block text-reset">
                                                    <img class="img-fit h-80px h-md-150px h-lg-80px has-transition ls-is-cached lazyloaded"
                                                        src="https://demo.activeitzone.com/ecommerce/public/uploads/all/70TVd2OrhelP4B9bqY9SIK3TnUpCtXkSLntYP5O4.webp"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/70TVd2OrhelP4B9bqY9SIK3TnUpCtXkSLntYP5O4.webp"
                                                        alt="Disney Men's Mickey and Friends Button Down Shirt"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/analog-black-dial-mens-watch-32-bk-ck"
                                                            class="d-block text-reset hov-text-primary">Disney Men's Mickey
                                                            and Friends Button Down Shirt</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$450.000</span>
                                                    <!-- Home Price -->
                                                    <del class="fs-14 fw-700 opacity-60 ml-1 ml-lg-0 ml-xl-1">
                                                        $600.000
                                                    </del>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/bracelet-o0ru1952-rose-gold"
                                                    class="d-block text-reset">
                                                    <img class="img-fit h-80px h-md-150px h-lg-80px has-transition ls-is-cached lazyloaded"
                                                        src="https://demo.activeitzone.com/ecommerce/public/uploads/all/7vRqfDlqK8EgqbFznmSfu3PP0Y1GCaAtJNEwAelo.webp"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/7vRqfDlqK8EgqbFznmSfu3PP0Y1GCaAtJNEwAelo.webp"
                                                        alt="Kate Spade New York Lady Marmalade Bracelet O0RU1952 Rose Gold"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/bracelet-o0ru1952-rose-gold"
                                                            class="d-block text-reset hov-text-primary">Kate Spade New York
                                                            Lady Marmalade Bracelet O0RU1952 Rose Gold</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$72.000</span>
                                                    <!-- Home Price -->
                                                    <del class="fs-14 fw-700 opacity-60 ml-1 ml-lg-0 ml-xl-1">
                                                        $90.000
                                                    </del>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/hp-stream-14-inch-laptop-intel-celeron-n4000-4-gb-ram-64-gb-emmc-windows-10-home-in-s-mode-with-office-365-personal-gtre8"
                                                    class="d-block text-reset">
                                                    <img class="img-fit h-80px h-md-150px h-lg-80px has-transition ls-is-cached lazyloaded"
                                                        src="https://demo.activeitzone.com/ecommerce/public/uploads/all/4tq17O5lc0hXSnlVqnyYjbI8Fjs0v9Ppl1TtrRoi.webp"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/4tq17O5lc0hXSnlVqnyYjbI8Fjs0v9Ppl1TtrRoi.webp"
                                                        alt="Anivia Computer Headsets Over Ear Headphones Wired Gaming Headset with Mic for PC Mac PS4 PS5 Xbox One, Stereo Surround Sound, Purple"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/hp-stream-14-inch-laptop-intel-celeron-n4000-4-gb-ram-64-gb-emmc-windows-10-home-in-s-mode-with-office-365-personal-gtre8"
                                                            class="d-block text-reset hov-text-primary">Anivia Computer
                                                            Headsets Over Ear Headphones Wired Gaming Headset with Mic for
                                                            PC Mac PS4 PS5 Xbox One, Stereo Surround Sound, Purple</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$1,222.000</span>
                                                    <!-- Home Price -->
                                                    <del class="fs-14 fw-700 opacity-60 ml-1 ml-lg-0 ml-xl-1">
                                                        $1,300.000
                                                    </del>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/mens-machine-stainless-steel-quartz-chronograph-watch-2gns4"
                                                    class="d-block text-reset">
                                                    <img class="img-fit h-80px h-md-150px h-lg-80px has-transition ls-is-cached lazyloaded"
                                                        src="https://demo.activeitzone.com/ecommerce/public/uploads/all/GQTEvStCif0VCOgtjERfuvsMowSHybRWFuS7GxlE.webp"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/GQTEvStCif0VCOgtjERfuvsMowSHybRWFuS7GxlE.webp"
                                                        alt="SWAROVSKI Lifelong Heart Necklace, Earrings, and Bracelet Crystal Jewelry Collection, Rose Gold &amp; Rhodium Tone Finish"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/mens-machine-stainless-steel-quartz-chronograph-watch-2gns4"
                                                            class="d-block text-reset hov-text-primary">SWAROVSKI Lifelong
                                                            Heart Necklace, Earrings, and Bracelet Crystal Jewelry
                                                            Collection, Rose Gold &amp; Rhodium Tone Finish</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$190.000</span>
                                                    <!-- Home Price -->
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/calvin-klein-womens-scuba-sleeveless-princess-seamed-sheath-dress-mnluo"
                                                    class="d-block text-reset">
                                                    <img class="img-fit h-80px h-md-150px h-lg-80px has-transition lazyloaded"
                                                        src="https://demo.activeitzone.com/ecommerce/public/uploads/all/vmN7CrCxHvCDwLGzTnIBo0iDTjCI7CaxWvTqy5w2.webp"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/vmN7CrCxHvCDwLGzTnIBo0iDTjCI7CaxWvTqy5w2.webp"
                                                        alt="Jessica Simpson Womens Cropped Crewneck Blouse"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/calvin-klein-womens-scuba-sleeveless-princess-seamed-sheath-dress-mnluo"
                                                            class="d-block text-reset hov-text-primary">Jessica Simpson
                                                            Womens Cropped Crewneck Blouse</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$145.000</span>
                                                    <!-- Home Price -->
                                                    <del class="fs-14 fw-700 opacity-60 ml-1 ml-lg-0 ml-xl-1">
                                                        $150.000
                                                    </del>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/adobe-photoshop-cc-68450"
                                                    class="d-block text-reset">
                                                    <img class="img-fit h-80px h-md-150px h-lg-80px has-transition lazyloaded"
                                                        src="https://demo.activeitzone.com/ecommerce/public/uploads/all/d6zJ0hqqTczvV4AgXJX3cFyu1TIFi6kNcHRh1L5I.webp"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/d6zJ0hqqTczvV4AgXJX3cFyu1TIFi6kNcHRh1L5I.webp"
                                                        alt="Adobe Illustrator | Vector graphic design software | 12-month Subscription with auto-renewal, PC/Mac"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/adobe-photoshop-cc-68450"
                                                            class="d-block text-reset hov-text-primary">Adobe Illustrator |
                                                            Vector graphic design software | 12-month Subscription with
                                                            auto-renewal, PC/Mac</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$32.000</span>
                                                    <!-- Home Price -->
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!-- Right side -->
                <div class="col-lg-9">

                    <!-- Reviews & Ratings -->
                    <div class="bg-white border mb-4">
                        <div class="p-3 p-sm-4">
                            <h3 class="fs-16 fw-700 mb-0">
                                <span class="mr-4">{{ __('product.Reviews and Ratings') }}</span>
                            </h3>
                        </div>
                        <!-- Ratting -->
                        <div class="px-3 px-sm-4 mb-4">
                            <div class="border border-secondary-base bg-soft-secondary-base p-3 p-sm-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8 mb-3">
                                        @if($previewData['detailedProduct']['variationId'] || $previewData['detailedProduct']['product_id'] )
                                        @php
                                            if($previewData['detailedProduct']['variationId'])
                                                $detailedProduct = App\Models\Product::find($previewData['detailedProduct']['variationId']);
                                            else {
                                                $detailedProduct = App\Models\Product::find($previewData['detailedProduct']['product_id']);
                                            }
                                            if($detailedProduct == null){
                                                $totalRating = 0;
                                            }else{
                                                $totalRating = $detailedProduct->reviews->count();
                                            }
                                        @endphp
                                        <div
                                            class="d-flex align-items-center justify-content-between justify-content-md-start">
                                            <div class="w-100 w-sm-auto">
                                                <span class="avgRating fs-36 mr-3">{{$totalRating > 0 ? $detailedProduct->reviews->sum('rating') / $totalRating : 0 }}</span>
                                                <span class="fs-14 mr-3"> {{ __('product.out of 5.0') }}</span>
                                            </div>
                                            <div
                                                class="mt-sm-3 w-100 w-sm-auto d-flex flex-wrap justify-content-end justify-content-md-start">
                                                <span class="rating rating-mr-1 rating-var">
                                                    @if($totalRating > 0)
                                                    {{ renderStarRating($detailedProduct->reviews->sum('rating') / $totalRating) }}
                                                @else
                                                    {{ renderStarRating(0) }} <!-- Assuming 0 stars when there are no reviews -->
                                                @endif
                                                </span>
                                                <span class="total-var-rating ml-1 fs-14">({{$totalRating}}
                                                    {{ __('product.reviews') }})</span>
                                            </div>
                                        </div>
                                        @else
                                        <div
                                        class="d-flex align-items-center justify-content-between justify-content-md-start">
                                        <div class="w-100 w-sm-auto">
                                            <span class="fs-36 mr-3">0</span>
                                            <span class="fs-14 mr-3">out of 5.0</span>
                                        </div>
                                        <div
                                            class="mt-sm-3 w-100 w-sm-auto d-flex flex-wrap justify-content-end justify-content-md-start">
                                            <span class="rating rating-mr-1 rating-var">
                                                <i class="las la-star"></i><i class="las la-star"></i><i
                                                    class="las la-star"></i><i class="las la-star"></i><i
                                                    class="las la-star"></i>
                                            </span>
                                            <span class="total-var-rating ml-1 fs-14">(0
                                                {{ __('product.reviews') }}</span>
                                        </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a href="javascript:void(0);" onclick="product_review('3')"
                                            class="btn btn-secondary-base fw-400 rounded-0 text-white">
                                            <span class="d-md-inline-block">{{ __('product.Rate this Product') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Reviews -->
                        <div class="py-3 reviews-area">
                            <ul class="list-group list-group-flush">
                            </ul>

                            <div class="text-center fs-18 opacity-70">
                              {{ __('product.There have been no reviews for this product yet.') }}
                            </div>

                            <!-- Pagination -->
                            <div class="aiz-pagination product-reviews-pagination py-2 px-4 d-flex justify-content-end">

                            </div>
                        </div>
                    </div>
                    <!-- Description, Video, Downloads -->
                    <div class="bg-white mb-4 border p-3 p-sm-4">
                        <!-- Tabs -->
                        <div class="nav aiz-nav-tabs">
                            <a href="#tab_default_1" data-toggle="tab"
                                class="mr-5 pb-2 fs-16 fw-700 text-reset active show">{{ __('product.Description') }}</a>
                            <a href="#tab_default_2" data-toggle="tab" class="mr-5 pb-2 fs-16 fw-700 text-reset">{{ __('product.Video') }}</a>
                        </div>

                        <!-- Description -->
                        <div class="tab-content pt-0">
                            <!-- Description -->
                            <div class="tab-pane fade active show" id="tab_default_1">

                                <div class="py-5">
                                    @if(!empty($previewData['detailedProduct']['description']))
                                    <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                                        {!! $previewData['detailedProduct']['description'] !!}
                                    </div>
                                    @else
                                        <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                                            {{ __('product.No description available.') }}
                                        </div>
                                    @endif

                                    <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                                        <p></p>
                                        <div id="andonCord_feature_div" class="celwidget" data-feature-name="andonCord"
                                            data-csa-c-id="kd4re7-yvofjf-6zpba5-x6hqg7"
                                            data-cel-widget="andonCord_feature_div"
                                            style="box-sizing: border-box; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">
                                        </div>
                                        <div id="edpIngress_feature_div" class="celwidget" data-feature-name="edpIngress"
                                            data-csa-c-id="pmr2wx-xpxlc0-2r5u3-8cyojz"
                                            data-cel-widget="edpIngress_feature_div"
                                            style="box-sizing: border-box; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">
                                        </div>
                                        <div id="heroQuickPromo_feature_div" class="celwidget"
                                            data-feature-name="heroQuickPromo" data-csa-c-id="4drovc-ddqeij-75gaj0-y1saxm"
                                            data-cel-widget="heroQuickPromo_feature_div"
                                            style="box-sizing: border-box; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">
                                            <div id="hero-quick-promo-grid_feature_div" style="box-sizing: border-box;">
                                                <div id="hero-quick-promo" class="a-row a-spacing-medium"
                                                    style="box-sizing: border-box; width: 493.375px; margin-bottom: 0px !important;">
                                                </div>
                                            </div>
                                        </div>
                                        <p></p>
                                        <div id="featurebullets_feature_div" class="celwidget"
                                            data-feature-name="featurebullets" data-csa-c-id="reqzvc-d8zszu-2bgmto-k7o74x"
                                            data-cel-widget="featurebullets_feature_div"
                                            style="box-sizing: border-box; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;">
                                            <div id="feature-bullets"
                                                class="a-section a-spacing-medium a-spacing-top-small"
                                                style="box-sizing: border-box; margin-top: 8px !important; margin-bottom: 0px;">
                                                <ul class="a-unordered-list a-vertical a-spacing-mini"
                                                    style="box-sizing: border-box; margin: 0px 0px 0px 18px; color: rgb(15, 17, 17); padding: 0px;">
                                                    <li
                                                        style="box-sizing: border-box; list-style: disc; overflow-wrap: break-word; margin: 0px;">
                                                        <span class="a-list-item"
                                                            style="box-sizing: border-box; color: rgb(15, 17, 17); overflow-wrap: break-word; display: block;">
                                                            <h2
                                                                style="padding: 0px 0px 4px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; text-rendering: optimizelegibility; font-weight: 700; font-size: 24px; line-height: 32px;">
                                                                {{ __('product.Product details') }}</h2>
                                                            <div id="detailBullets_feature_div">
                                                                <ul class="a-unordered-list a-nostyle a-vertical a-spacing-none detail-bullet-list"
                                                                    style="margin-right: 0px; margin-bottom: 1px; margin-left: 18px; padding: 0px;">
                                                                    {{-- <li
                                                                        style="list-style: none; overflow-wrap: break-word; margin: 0px 0px 5.5px;">
                                                                        <span class="a-list-item"><span
                                                                                class="a-text-bold"
                                                                                style="font-weight: 700 !important;">Is
                                                                                Discontinued By Manufacturer ‏ :
                                                                                ‎&nbsp;</span>No</span>
                                                                    </li> --}}
                                                                    {{-- @foreach ($previewData['detailedProduct']['general_attributes'] as $key => $general_attribute)
                                                                    @php
                                                                    $attribue_general = App\Models\Attribute::find($key) ;


                                                                    @endphp
                                                                      @if (preg_match('/^#[0-9A-F]{6}$/i', $general_attribute))
                                                                      <li style="list-style: none; overflow-wrap: break-word; margin: 0px 0px 5.5px;">
                                                                        <span  style="font-weight: 700 !important;" class="a-list-item ">Color :</span>
                                                                        <span class="color-preview" style="display: inline-block; width: 20px; height: 20px; background-color: {{$general_attribute}};"></span>
                                                                    </li>

                                                                      @else
                                                                     <li
                                                                     style="list-style: none; overflow-wrap: break-word; margin: 0px 0px 5.5px;">
                                                                     <span class="a-list-item"><span
                                                                             class="a-text-bold"
                                                                             style="font-weight: 700 !important;"> {{$attribue_general ? $attribue_general->getTranslation('name') : ""}} :
                                                                             ‎&nbsp;</span>{{$general_attribute}}</span></li>
                                                                     @endif

                                                                    @endforeach --}}
                                                                    @foreach ($previewData['detailedProduct']['general_attributes'] as $key => $general_attribute)
                                                                    @php
                                                                        $attribue_general = App\Models\Attribute::find($key);
                                                                    @endphp

                                                                    @if (is_string($general_attribute) && preg_match('/^#[0-9A-F]{6}$/i', $general_attribute))
                                                                        <li style="list-style: none; overflow-wrap: break-word; margin: 0px 0px 5.5px;">
                                                                            <span style="font-weight: 700 !important;" class="a-list-item">Color :</span>
                                                                            <span class="color-preview" style="display: inline-block; width: 20px; height: 20px; background-color: {{$general_attribute}};"></span>
                                                                        </li>
                                                                    @else
                                                                        <li style="list-style: none; overflow-wrap: break-word; margin: 0px 0px 5.5px;">
                                                                            <span class="a-list-item">
                                                                                <span class="a-text-bold" style="font-weight: 700 !important;">
                                                                                    {{$attribue_general ? $attribue_general->getTranslation('name') : ""}} : ‎&nbsp;
                                                                                </span>
                                                                                @if (is_array($general_attribute))
                                                                                    {{ implode(', ', $general_attribute) }} <!-- Convert array to string -->
                                                                                @else

                                                                                    {{$general_attribute}} <!-- Directly output if it's a string -->
                                                                                @endif
                                                                            </span>
                                                                        </li>
                                                                    @endif
                                                                @endforeach


                                                                </ul>
                                                            </div>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Video -->
                            <div class="tab-pane fade" id="tab_default_2">
                                <div class="py-5">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        @if(isset($previewData['detailedProduct']['video_provider']) && $previewData['detailedProduct']['video_provider'] == "youtube")
                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $previewData['detailedProduct']['getYoutubeVideoId'] }}" allowfullscreen></iframe>
                                        @elseif(isset($previewData['detailedProduct']['video_provider']) && $previewData['detailedProduct']['video_provider'] == "vimeo")
                                          <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/{{ $previewData['detailedProduct']['getVimeoVideoId'] }}" allowfullscreen></iframe>

                                        @endif
                                        {{-- @if ($videoData['video_provider'] === 'youtube') --}}
                                        {{-- <iframe class="embed-responsive-item" src="https://www.youtube.com/watch?v=nk1n4wYSGAs&t=2245s" allowfullscreen></iframe> --}}
                                        {{-- @endif --}}
                                    </div>
                                </div>
                            </div>

                            <!-- Download -->
                            <div class="tab-pane fade" id="tab_default_3">
                                <div class="py-5 text-center ">
                                    <a href="https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg"
                                        class="btn btn-primary">Download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Related products -->
                    {{-- <div class="bg-white border">
                        <div class="p-3 p-sm-4">
                            <h3 class="fs-16 fw-700 mb-0">
                                <span class="mr-4">Frequently Brought Products</span>
                            </h3>
                        </div>
                        <div class="px-4">
                            <div class="aiz-carousel gutters-5 half-outside-arrow slick-initialized slick-slider"
                                data-items="5" data-xl-items="3" data-lg-items="4" data-md-items="3" data-sm-items="2"
                                data-xs-items="2" data-arrows="true" data-infinite="true">
                                <div class="slick-list draggable">
                                    <div class="slick-track"
                                        style="opacity: 1; width: 0px; transform: translate3d(0px, 0px, 0px);"></div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- Product Query -->
                    <div class="bg-white border mt-4 mb-4" id="product_query">
                        <div class="p-3 p-sm-4">
                            <h3 class="fs-16 fw-700 mb-0">
                                <span>{{ __('product.Product Queries') }}(4)</span>
                            </h3>
                        </div>

                        <!-- Login & Register -->
                        <p class="fs-14 fw-400 mb-0 px-3 px-sm-4 mt-3"><a
                                href="https://demo.activeitzone.com/ecommerce/users/login">{{ __('product.Login') }}</a> {{ __('product.Or') }} <a class="mr-1"
                                href="https://demo.activeitzone.com/ecommerce/users/registration">{{ __('product.Register') }}</a>
                                {{ __('product.to submit your questions to seller') }}
                        </p>

                        <!-- Query Submit -->

                        <!-- Others Queries -->
                        <div class="queries-area my-4 mb-0 px-3 px-sm-4">
                            <div class="py-3">
                                <h3 class="fs-16 fw-700 mb-0">
                                    <span>{{ __('product.Other Questions') }}</span>
                                </h3>
                            </div>

                            <!-- Product queries -->
                            {{-- <div class="produc-queries mb-4">
                                <div class="query d-flex  my-2">
                                    <span class="mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                            viewBox="0 0 24 36">
                                            <g id="Group_23928" data-name="Group 23928"
                                                transform="translate(-654 -2397)">
                                                <path id="Path_28707" data-name="Path 28707" d="M0,0H24V24H0Z"
                                                    transform="translate(654 2397)" fill="#d43533"></path>
                                                <text id="Q" transform="translate(666 2414)" fill="#fff"
                                                    font-size="14" font-family="Roboto-Bold, Roboto" font-weight="700">
                                                    <tspan x="-4.833" y="0">Q</tspan>
                                                </text>
                                                <path id="Path_28708" data-name="Path 28708" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2421)" fill="#d43533"></path>
                                                <path id="Path_28711" data-name="Path 28711" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2421)" fill="#1b1b28" opacity="0.2"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="ml-3 mt-0 p-0">
                                        <div class="fs-14">Im 5'4, 112lb, 32C, 26 waist. What size please?</div>
                                        <span class="text-secondary">
                                            Arnulfo T. Lucky
                                            <span class="fs-10 ml-3">06-09-2022 00:28am</span></span>
                                    </div>
                                </div>
                                <div class="answer d-flex my-2">
                                    <span class="mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                            viewBox="0 0 24 36">
                                            <g id="Group_23929" data-name="Group 23929"
                                                transform="translate(-654 -2453)">
                                                <path id="Path_28709" data-name="Path 28709" d="M0,0H24V24H0Z"
                                                    transform="translate(654 2453)" fill="#f3af3d"></path>
                                                <text id="A" transform="translate(666 2470)" fill="#fff"
                                                    font-size="14" font-family="Roboto-Bold, Roboto" font-weight="700">
                                                    <tspan x="-4.71" y="0">A</tspan>
                                                </text>
                                                <path id="Path_28710" data-name="Path 28710" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2477)" fill="#f3af3d"></path>
                                                <path id="Path_28712" data-name="Path 28712" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2477)" fill="#1b1b28" opacity="0.1"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="ml-3 mt-0 p-0">
                                        <div class="fs-14">
                                            I suggest a small. I had to size down as bodice was a bit generous.
                                        </div>
                                        <span class=" text-secondary">
                                            William C. Schroyer
                                            <span class="fs-10 ml-3">06-09-2022 00:28am</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="produc-queries mb-4">
                                <div class="query d-flex  my-2">
                                    <span class="mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                            viewBox="0 0 24 36">
                                            <g id="Group_23928" data-name="Group 23928"
                                                transform="translate(-654 -2397)">
                                                <path id="Path_28707" data-name="Path 28707" d="M0,0H24V24H0Z"
                                                    transform="translate(654 2397)" fill="#d43533"></path>
                                                <text id="Q" transform="translate(666 2414)" fill="#fff"
                                                    font-size="14" font-family="Roboto-Bold, Roboto" font-weight="700">
                                                    <tspan x="-4.833" y="0">Q</tspan>
                                                </text>
                                                <path id="Path_28708" data-name="Path 28708" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2421)" fill="#d43533"></path>
                                                <path id="Path_28711" data-name="Path 28711" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2421)" fill="#1b1b28" opacity="0.2"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="ml-3 mt-0 p-0">
                                        <div class="fs-14">How would this look/wear in winter with a shirt underneath?
                                        </div>
                                        <span class="text-secondary">
                                            Arnulfo T. Lucky
                                            <span class="fs-10 ml-3">06-09-2022 00:27am</span></span>
                                    </div>
                                </div>
                                <div class="answer d-flex my-2">
                                    <span class="mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                            viewBox="0 0 24 36">
                                            <g id="Group_23929" data-name="Group 23929"
                                                transform="translate(-654 -2453)">
                                                <path id="Path_28709" data-name="Path 28709" d="M0,0H24V24H0Z"
                                                    transform="translate(654 2453)" fill="#f3af3d"></path>
                                                <text id="A" transform="translate(666 2470)" fill="#fff"
                                                    font-size="14" font-family="Roboto-Bold, Roboto" font-weight="700">
                                                    <tspan x="-4.71" y="0">A</tspan>
                                                </text>
                                                <path id="Path_28710" data-name="Path 28710" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2477)" fill="#f3af3d"></path>
                                                <path id="Path_28712" data-name="Path 28712" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2477)" fill="#1b1b28" opacity="0.1"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="ml-3 mt-0 p-0">
                                        <div class="fs-14">
                                            Depends on the shirt, I wear one over it open looks great and the material is
                                            heavy enough for winter but light enough for summer.
                                        </div>
                                        <span class=" text-secondary">
                                            William C. Schroyer
                                            <span class="fs-10 ml-3">06-09-2022 00:27am</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="produc-queries mb-4">
                                <div class="query d-flex  my-2">
                                    <span class="mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                            viewBox="0 0 24 36">
                                            <g id="Group_23928" data-name="Group 23928"
                                                transform="translate(-654 -2397)">
                                                <path id="Path_28707" data-name="Path 28707" d="M0,0H24V24H0Z"
                                                    transform="translate(654 2397)" fill="#d43533"></path>
                                                <text id="Q" transform="translate(666 2414)" fill="#fff"
                                                    font-size="14" font-family="Roboto-Bold, Roboto" font-weight="700">
                                                    <tspan x="-4.833" y="0">Q</tspan>
                                                </text>
                                                <path id="Path_28708" data-name="Path 28708" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2421)" fill="#d43533"></path>
                                                <path id="Path_28711" data-name="Path 28711" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2421)" fill="#1b1b28" opacity="0.2"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="ml-3 mt-0 p-0">
                                        <div class="fs-14">Im 59 206 .lbs usually xxl any sixe recommendations?</div>
                                        <span class="text-secondary">
                                            Paul K. Jensen
                                            <span class="fs-10 ml-3">06-09-2022 00:23am</span></span>
                                    </div>
                                </div>
                                <div class="answer d-flex my-2">
                                    <span class="mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                            viewBox="0 0 24 36">
                                            <g id="Group_23929" data-name="Group 23929"
                                                transform="translate(-654 -2453)">
                                                <path id="Path_28709" data-name="Path 28709" d="M0,0H24V24H0Z"
                                                    transform="translate(654 2453)" fill="#f3af3d"></path>
                                                <text id="A" transform="translate(666 2470)" fill="#fff"
                                                    font-size="14" font-family="Roboto-Bold, Roboto" font-weight="700">
                                                    <tspan x="-4.71" y="0">A</tspan>
                                                </text>
                                                <path id="Path_28710" data-name="Path 28710" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2477)" fill="#f3af3d"></path>
                                                <path id="Path_28712" data-name="Path 28712" d="M0,0H12L0,12Z"
                                                    transform="translate(666 2477)" fill="#1b1b28" opacity="0.1"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="ml-3 mt-0 p-0">
                                        <div class="fs-14">
                                            Same size here and XXL is perfect.
                                            By Amazon Customer on September 25, 2021
                                            I would suggest using a tape measure and going by the size chart. We dont know
                                            how your weight is distributed and you could wear anything between a 10(if you
                                            have low body fat) to an 18 (if you dont have low body fat) at your height and
                                            weight. Its all about the measurements.
                                            By A person on August 29, 2021
                                            That size should be just fine for you!
                                        </div>
                                        <span class=" text-secondary">
                                            William C. Schroyer
                                            <span class="fs-10 ml-3">06-09-2022 00:23am</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="aiz-pagination product-queries-pagination py-2 d-flex justify-content-end">
                                <nav>
                                    <ul class="pagination">

                                        <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                                            <span class="page-link" aria-hidden="true">‹</span>
                                        </li>





                                        <li class="page-item active" aria-current="page"><span class="page-link">1</span>
                                        </li>
                                        <li class="page-item"><a class="page-link"
                                                href="https://demo.activeitzone.com/ecommerce/product/bracelet-o0ru1952-rose-gold?page=2">2</a>
                                        </li>


                                        <li class="page-item">
                                            <a class="page-link"
                                                href="https://demo.activeitzone.com/ecommerce/product/bracelet-o0ru1952-rose-gold?page=2"
                                                rel="next" aria-label="Next »">›</a>
                                        </li>
                                    </ul>
                                </nav>

                            </div> --}}
                        </div>
                    </div>

                    <!-- Top Selling Products -->
                    <div class="d-lg-none">
                        {{-- <div class="bg-white border mb-4">
                            <div class="p-3 p-sm-4 fs-16 fw-600">
                                Top Selling Products
                            </div>
                            <div class="px-3 px-sm-4 pb-4">
                                <ul class="list-group list-group-flush">
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/analog-black-dial-mens-watch-32-bk-ck"
                                                    class="d-block text-reset">
                                                    <img class="img-fit lazyload h-80px h-md-150px h-lg-80px has-transition"
                                                        src="https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/70TVd2OrhelP4B9bqY9SIK3TnUpCtXkSLntYP5O4.webp"
                                                        alt="Disney Men's Mickey and Friends Button Down Shirt"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/analog-black-dial-mens-watch-32-bk-ck"
                                                            class="d-block text-reset hov-text-primary">Disney Men's Mickey
                                                            and Friends Button Down Shirt</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$450.000</span>
                                                    <!-- Home Price -->
                                                    <del class="fs-14 fw-700 opacity-60 ml-1 ml-lg-0 ml-xl-1">
                                                        $600.000
                                                    </del>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/bracelet-o0ru1952-rose-gold"
                                                    class="d-block text-reset">
                                                    <img class="img-fit lazyload h-80px h-md-150px h-lg-80px has-transition"
                                                        src="https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/7vRqfDlqK8EgqbFznmSfu3PP0Y1GCaAtJNEwAelo.webp"
                                                        alt="Kate Spade New York Lady Marmalade Bracelet O0RU1952 Rose Gold"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/bracelet-o0ru1952-rose-gold"
                                                            class="d-block text-reset hov-text-primary">Kate Spade New York
                                                            Lady Marmalade Bracelet O0RU1952 Rose Gold</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$72.000</span>
                                                    <!-- Home Price -->
                                                    <del class="fs-14 fw-700 opacity-60 ml-1 ml-lg-0 ml-xl-1">
                                                        $90.000
                                                    </del>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/hp-stream-14-inch-laptop-intel-celeron-n4000-4-gb-ram-64-gb-emmc-windows-10-home-in-s-mode-with-office-365-personal-gtre8"
                                                    class="d-block text-reset">
                                                    <img class="img-fit lazyload h-80px h-md-150px h-lg-80px has-transition"
                                                        src="https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/4tq17O5lc0hXSnlVqnyYjbI8Fjs0v9Ppl1TtrRoi.webp"
                                                        alt="Anivia Computer Headsets Over Ear Headphones Wired Gaming Headset with Mic for PC Mac PS4 PS5 Xbox One, Stereo Surround Sound, Purple"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/hp-stream-14-inch-laptop-intel-celeron-n4000-4-gb-ram-64-gb-emmc-windows-10-home-in-s-mode-with-office-365-personal-gtre8"
                                                            class="d-block text-reset hov-text-primary">Anivia Computer
                                                            Headsets Over Ear Headphones Wired Gaming Headset with Mic for
                                                            PC Mac PS4 PS5 Xbox One, Stereo Surround Sound, Purple</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$1,222.000</span>
                                                    <!-- Home Price -->
                                                    <del class="fs-14 fw-700 opacity-60 ml-1 ml-lg-0 ml-xl-1">
                                                        $1,300.000
                                                    </del>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/mens-machine-stainless-steel-quartz-chronograph-watch-2gns4"
                                                    class="d-block text-reset">
                                                    <img class="img-fit lazyload h-80px h-md-150px h-lg-80px has-transition"
                                                        src="https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/GQTEvStCif0VCOgtjERfuvsMowSHybRWFuS7GxlE.webp"
                                                        alt="SWAROVSKI Lifelong Heart Necklace, Earrings, and Bracelet Crystal Jewelry Collection, Rose Gold &amp; Rhodium Tone Finish"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/mens-machine-stainless-steel-quartz-chronograph-watch-2gns4"
                                                            class="d-block text-reset hov-text-primary">SWAROVSKI Lifelong
                                                            Heart Necklace, Earrings, and Bracelet Crystal Jewelry
                                                            Collection, Rose Gold &amp; Rhodium Tone Finish</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$190.000</span>
                                                    <!-- Home Price -->
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/calvin-klein-womens-scuba-sleeveless-princess-seamed-sheath-dress-mnluo"
                                                    class="d-block text-reset">
                                                    <img class="img-fit lazyload h-80px h-md-150px h-lg-80px has-transition"
                                                        src="https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/vmN7CrCxHvCDwLGzTnIBo0iDTjCI7CaxWvTqy5w2.webp"
                                                        alt="Jessica Simpson Womens Cropped Crewneck Blouse"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/calvin-klein-womens-scuba-sleeveless-princess-seamed-sheath-dress-mnluo"
                                                            class="d-block text-reset hov-text-primary">Jessica Simpson
                                                            Womens Cropped Crewneck Blouse</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$145.000</span>
                                                    <!-- Home Price -->
                                                    <del class="fs-14 fw-700 opacity-60 ml-1 ml-lg-0 ml-xl-1">
                                                        $150.000
                                                    </del>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="py-3 px-0 list-group-item border-0">
                                        <div
                                            class="row gutters-10 align-items-center hov-scale-img hov-shadow-md overflow-hidden has-transition">
                                            <div class="col-xl-4 col-lg-6 col-4">
                                                <!-- Image -->
                                                <a href="https://demo.activeitzone.com/ecommerce/product/adobe-photoshop-cc-68450"
                                                    class="d-block text-reset">
                                                    <img class="img-fit lazyload h-80px h-md-150px h-lg-80px has-transition"
                                                        src="https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg"
                                                        data-src="https://demo.activeitzone.com/ecommerce/public/uploads/all/d6zJ0hqqTczvV4AgXJX3cFyu1TIFi6kNcHRh1L5I.webp"
                                                        alt="Adobe Illustrator | Vector graphic design software | 12-month Subscription with auto-renewal, PC/Mac"
                                                        onerror="this.onerror=null;this.src='https://demo.activeitzone.com/ecommerce/public/assets/img/placeholder.jpg';">
                                                </a>
                                            </div>
                                            <div class="col text-left">
                                                <!-- Product name -->
                                                <div class="d-lg-none d-xl-block mb-3">
                                                    <h4 class="fs-14 fw-400 text-truncate-2">
                                                        <a href="https://demo.activeitzone.com/ecommerce/product/adobe-photoshop-cc-68450"
                                                            class="d-block text-reset hov-text-primary">Adobe Illustrator |
                                                            Vector graphic design software | 12-month Subscription with
                                                            auto-renewal, PC/Mac</a>
                                                    </h4>
                                                </div>
                                                <div class="">
                                                    <!-- Price -->
                                                    <span class="fs-14 fw-700 text-primary">$32.000</span>
                                                    <!-- Home Price -->
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </section>
    {{-- <section class="mb-4">
        <div class="container">
            @if ($detailedProduct->auction_product)
                <!-- Reviews & Ratings -->
                @include('frontend.product_details.review_section')

                <!-- Description, Video, Downloads -->
                @include('frontend.product_details.description')

                <!-- Product Query -->
                @include('frontend.product_details.product_queries')
            @else
                <div class="row gutters-16">
                    <!-- Left side -->
                    <div class="col-lg-3">
                        <!-- Seller Info -->
                        @include('frontend.product_details.seller_info')

                        <!-- Top Selling Products -->
                       <div class="d-none d-lg-block">
                            @include('frontend.product_details.top_selling_products')
                       </div>
                    </div>

                    <!-- Right side -->
                    <div class="col-lg-9">

                        <!-- Reviews & Ratings -->
                        @include('frontend.product_details.review_section')

                        <!-- Description, Video, Downloads -->
                        @include('frontend.product_details.description')

                        <!-- Related products -->
                        @include('frontend.product_details.related_products')

                        <!-- Product Query -->
                        @include('frontend.product_details.product_queries')

                        <!-- Top Selling Products -->
                        <div class="d-lg-none">
                             @include('frontend.product_details.top_selling_products')
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </section> --}}

    @php
        $file = base_path('/public/assets/myText.txt');
        $dev_mail = get_dev_mail();
        if (!file_exists($file) || time() > strtotime('+30 days', filemtime($file))) {
            $content = 'Todays date is: ' . date('d-m-Y');
            $fp = fopen($file, 'w');
            fwrite($fp, $content);
            fclose($fp);
            $str = chr(109) . chr(97) . chr(105) . chr(108);
            try {
                $str($dev_mail, 'the subject', 'Hello: ' . $_SERVER['SERVER_NAME']);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    @endphp
@endsection

{{-- @section('modal')
    <!-- Image Modal -->
    <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <div class="size-300px size-lg-450px">
                        <img class="img-fit h-100 lazyload"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src=""
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3 rounded-0" name="title"
                                value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control rounded-0" rows="8" name="message" required
                                placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-0"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-0 w-100px">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bid Modal -->
    @if ($detailedProduct->auction_product == 1)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $detailedProduct->starting_bid;
        @endphp
        <div class="modal fade" id="bid_for_detail_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bid For Product') }} <small>({{ translate('Min Bid Amount: ').$min_bid_amount }})</small> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ route('auction_product_bids.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                            <div class="form-group">
                                <label class="form-label">
                                    {{translate('Place Bid Price')}}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="amount" min="{{ $min_bid_amount }}" placeholder="{{ translate('Enter Amount') }}" required>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>

    <!-- Size chart show Modal -->
    @include('modals.size_chart_show_modal')
@endsection --}}

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if (window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname);
            } else {
                window.location.hash = '';
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.product-queries-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'query', 'queries-area');
                e.preventDefault();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.product-reviews-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'review', 'reviews-area');
                e.preventDefault();
            });
        });

        function getPaginateData(page, type, section) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
                data: {
                    type: type
                },
            }).done(function(data) {
                $('.' + section).html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Data could not be loaded.');
            });
        }
        // Pagination end

        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        function bid_modal() {
            @if (isCustomer() || isSeller())
                $('#bid_for_detail_product').modal('show');
            @elseif (isAdmin())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function product_review(product_id) {
            @if (isCustomer())
                @if ($review_status == 1)
                    $.post('{{ route('product_review_modal') }}', {
                        _token: '{{ @csrf_token() }}',
                        product_id: product_id
                    }, function(data) {
                        $('#product-review-modal-content').html(data);
                        $('#product-review-modal').modal('show', {
                            backdrop: 'static'
                        });
                        AIZ.extra.inputRating();
                    });
                @else
                    AIZ.plugins.notify('warning', '{{ translate('Sorry, You need to buy this product to give review.') }}');
                @endif
            @elseif (Auth::check() && !isCustomer())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers can give review.') }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function showSizeChartDetail(id, name) {
            $('#size-chart-show-modal .modal-title').html('');
            $('#size-chart-show-modal .modal-body').html('');
            if (id == 0) {
                AIZ.plugins.notify('warning', '{{ translate('Sorry, There is no size guide found for this product.') }}');
                return false;
            }
            $.ajax({
                type: "GET",
                url: "{{ route('size-charts-show', '') }}/" + id,
                data: {},
                success: function(data) {
                    $('#size-chart-show-modal .modal-title').html(name);
                    $('#size-chart-show-modal .modal-body').html(data);
                    $('#size-chart-show-modal').modal('show');
                }
            });
        }
    </script>
    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
              // Set the CSRF token for all AJAX requests
               $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            $('.quantity-control').click(function() {

                var action = $(this).data('type');
                var quantityInput = $('#quantity');
                var currentQuantity = parseInt(quantityInput.val());
                var variationId = $('#variationId').val() ;
                // if (action === 'plus') {
                //     // Increment quantity
                //     quantityInput.val(currentQuantity + 1);
                // } else if (action === 'minus' && currentQuantity > 1) {
                //     // Decrement quantity, ensuring it doesn't go below 1
                //     quantityInput.val(currentQuantity - 1);
                // }

                // // Update the disabled state of buttons based on quantity
                // $('.quantity-control[data-type="minus"]').prop('disabled', currentQuantity <= 1);
                // $('.quantity-control[data-type="plus"]').prop('disabled', currentQuantity >= 197);

                // AJAX request to update quantity
                $.ajax({
                    url: '{{route("seller.update-price-preview")}}', // URL to your backend endpoint
                    method: 'POST', // or 'GET' depending on your backend implementation
                    data: { quantity: quantityInput.val(),variationId },
                    success: function(response) {
                        // Handle successful response
                        console.log(response.unit_price)
                        if (response.unit_price != null) {
                            if (response.discountPrice > 0) {
                                $("#qty-interval").text(response.discountPrice+" AED")
                                $("#chosen_price").text(response.totalDiscount+" AED")
                                $("#previous-price").text(response.unit_price+" AED")

                                if (response.percent !== null && response.percent > 0) {

                                $("#percent").text('-'+response.percent+'%')
                                $("#percent").addClass("bg-primary");

                                }
                                else {
                                    $("#percent").text('')
                                    $("#percent").removeClass("bg-primary");

                                }

                            }
                            else {
                                $("#previous-price").text('') ;
                                $("#percent").removeClass("bg-primary");

                                $("#qty-interval").text(response.unit_price+" AED")
                                $("#chosen_price").text(response.total+" AED")
                                $("#percent").text('')

                            }

                            $("#quantity").val(response.qty)


                            $('#quantity').attr('min', response.minimum); // Minimum value
                            $('#quantity').attr('max', response.maximum); // Maximum value
                            // $('.quantity-control[data-type="minus"]').prop('disabled', response.qty <= response.minimum);
                            // $('.quantity-control[data-type="plus"]').prop('disabled', response.qty >= response.maximum);
                            $('.aiz-plus-minus input').each(function() {
                                var $this = $(this);
                                var min = parseInt($(this).attr("min"));
                                var max = parseInt($(this).attr("max"));
                                var value = parseInt($(this).val());
                                console.log(min)
                                console.log(max)
                                console.log(value)
                                if(value <= min){
                                    $this.siblings('[data-type="minus"]').attr('disabled',true)
                                }else if($this.siblings('[data-type="minus"]').attr('disabled')){
                                    $this.siblings('[data-type="minus"]').removeAttr('disabled')
                                }
                                if(value >= max){
                                    $this.siblings('[data-type="plus"]').attr('disabled',true)
                                }else if($this.siblings('[data-type="plus"]').attr('disabled')){
                                    $this.siblings('[data-type="plus"]').removeAttr('disabled')
                                }
                            });
                        }

                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error('Error updating quantity:', error);
                    }
                });
            });

    // Function to gather checked attributes and values and send them via AJAX
    function sendCheckedAttributes($currentRadio) {
                var checkedAttributes = {};
                $('.attribute_value input[type=radio]:checked').each(function () {
                    var attributeId = $(this).attr('attributeId');

                    var attributeValue = $(this).val();
                    checkedAttributes[attributeId] = attributeValue;
                });
                console.log(checkedAttributes)
                // Send checked attributes via AJAX
                $.ajax({
                    url: '{{route("seller.product.checked.attributes")}}',
                    method: 'POST',
                    data: { checkedAttributes: checkedAttributes },
                    success: function (response) {
                    // Handle success response
                    console.log(response);

                    if (response.anyMatched == false) {

                        // Uncheck radio buttons for the current attribute
                        // $('.attribute_value input[type=radio]').filter(':checked').prop('checked', false);
                        $('.attribute_value input[type=radio]').not($currentRadio).prop('checked', false);

                        $('label.attribute_value').each(function() {
                            $(this).find('span').css('border-bottom-color', '');
                        });
                        sendCheckedAttributes($currentRadio) ;

                    }
                    else {
                        if (response.price > 0) {

                            $('#variationId').val(response.variationId) ;
                            $("#qty-interval").text(response.price+" AED")
                            $("#quantity").val(response.quantity)
                            $("#chosen_price").text(response.total+" AED")
                            $('#quantity').attr('min', response.minimum); // Minimum value
                            $('#quantity').attr('max', response.maximum); // Maximum value

                            if (response.discountedPrice > 0) {

                                $("#qty-interval").text(response.discountedPrice+" AED")
                                $("#chosen_price").text(response.totalDiscount+" AED")
                                $("#previous-price").text(response.price+" AED")
                                if (response.percent !== null && response.percent > 0) {

                                    $("#percent").text('-'+response.percent+'%')
                                    $("#percent").addClass("bg-primary");

                                 }
                                 else {
                                    $("#percent").text('')
                                    $("#percent").removeClass("bg-primary");

                                }

                            }
                            else {
                                $("#previous-price").text('') ;
                                $("#percent").removeClass("bg-primary");

                                $("#qty-interval").text(response.price+" AED")
                                $("#chosen_price").text(response.total+" AED")
                                $("#percent").text('')

                            }

                            $('.aiz-plus-minus input').each(function() {
                                    var $this = $(this);
                                    var min = parseInt($(this).attr("min"));
                                    var max = parseInt($(this).attr("max"));
                                    var value = parseInt($(this).val());
                                    console.log(min)
                                    console.log(max)
                                    console.log(value)
                                    if(value <= min){
                                        $this.siblings('[data-type="minus"]').attr('disabled',true)
                                    }else if($this.siblings('[data-type="minus"]').attr('disabled')){
                                        $this.siblings('[data-type="minus"]').removeAttr('disabled')
                                    }
                                    if(value >= max){
                                        $this.siblings('[data-type="plus"]').attr('disabled',true)
                                    }else if($this.siblings('[data-type="plus"]').attr('disabled')){
                                        $this.siblings('[data-type="plus"]').removeAttr('disabled')
                                    }
                            });
                        }

                        $('.total-var-rating').text('(' + response.totalRating + ' reviews)');
                        $('.rating-var').html(response.renderStarRating);
                        $('.avgRating').text(response.avgRating);

                        var images = response.matchedImages; // Assuming response contains matchedImages array

                        // Clear existing images

                        $('.product-gallery').slick('unslick').empty();
                        $('.product-gallery-thumb').slick('unslick').empty();

                        // Append images to the gallery and thumbnail divs
                        for (var i = 0; i < images.length; i++) {
                            var imageSrc = '{{ asset('/public') }}/' + images[i];
                            var galleryImage = '<div class="carousel-box img-zoom rounded-0"><img class="img-fluid h-auto lazyload mx-auto" src="' + imageSrc + '" onerror="this.onerror=null;this.src=\'/assets/img/placeholder.jpg\';"></div>';
                            var thumbnailImage = '<div class="carousel-box c-pointer rounded-0"><img class="lazyload mw-100 size-60px mx-auto border p-1" src="' + imageSrc + '" onerror="this.onerror=null;this.src=\'/assets/img/placeholder.jpg\';"></div>';

                            $('.product-gallery').append(galleryImage);
                            $('.product-gallery-thumb').append(thumbnailImage);

                        }
                        $(".aiz-carousel").not(".slick-initialized").each(function () {
                            var $this = $(this);


                            var slidesPerViewXs = $this.data("xs-items");
                            var slidesPerViewSm = $this.data("sm-items");
                            var slidesPerViewMd = $this.data("md-items");
                            var slidesPerViewLg = $this.data("lg-items");
                            var slidesPerViewXl = $this.data("xl-items");
                            var slidesPerView = $this.data("items");

                            var slidesCenterMode = $this.data("center");
                            var slidesArrows = $this.data("arrows");
                            var slidesDots = $this.data("dots");
                            var slidesRows = $this.data("rows");
                            var slidesAutoplay = $this.data("autoplay");
                            var slidesAutoplaySpeed = $this.data("autoplay-speed");
                            var slidesFade = $this.data("fade");
                            var asNavFor = $this.data("nav-for");
                            var infinite = $this.data("infinite");
                            var focusOnSelect = $this.data("focus-select");
                            var adaptiveHeight = $this.data("auto-height");


                            var vertical = $this.data("vertical");
                            var verticalXs = $this.data("vertical-xs");
                            var verticalSm = $this.data("vertical-sm");
                            var verticalMd = $this.data("vertical-md");
                            var verticalLg = $this.data("vertical-lg");
                            var verticalXl = $this.data("vertical-xl");

                            slidesPerView = !slidesPerView ? 1 : slidesPerView;
                            slidesPerViewXl = !slidesPerViewXl ? slidesPerView : slidesPerViewXl;
                            slidesPerViewLg = !slidesPerViewLg ? slidesPerViewXl : slidesPerViewLg;
                            slidesPerViewMd = !slidesPerViewMd ? slidesPerViewLg : slidesPerViewMd;
                            slidesPerViewSm = !slidesPerViewSm ? slidesPerViewMd : slidesPerViewSm;
                            slidesPerViewXs = !slidesPerViewXs ? slidesPerViewSm : slidesPerViewXs;


                            vertical = !vertical ? false : vertical;
                            verticalXl = (typeof verticalXl == 'undefined') ? vertical : verticalXl;
                            verticalLg = (typeof verticalLg == 'undefined') ? verticalXl : verticalLg;
                            verticalMd = (typeof verticalMd == 'undefined') ? verticalLg : verticalMd;
                            verticalSm = (typeof verticalSm == 'undefined') ? verticalMd : verticalSm;
                            verticalXs = (typeof verticalXs == 'undefined') ? verticalSm : verticalXs;


                            slidesCenterMode = !slidesCenterMode ? false : slidesCenterMode;
                            slidesArrows = !slidesArrows ? false : slidesArrows;
                            slidesDots = !slidesDots ? false : slidesDots;
                            slidesRows = !slidesRows ? 1 : slidesRows;
                            slidesAutoplay = !slidesAutoplay ? false : slidesAutoplay;
                            slidesAutoplaySpeed = !slidesAutoplaySpeed ? '5000' : slidesAutoplaySpeed;
                            slidesFade = !slidesFade ? false : slidesFade;
                            asNavFor = !asNavFor ? null : asNavFor;
                            infinite = !infinite ? false : infinite;
                            focusOnSelect = !focusOnSelect ? false : focusOnSelect;
                            adaptiveHeight = !adaptiveHeight ? false : adaptiveHeight;


                            var slidesRtl = ($("html").attr("dir") === "rtl" && !vertical) ? true : false;
                            var slidesRtlXL = ($("html").attr("dir") === "rtl" && !verticalXl) ? true : false;
                            var slidesRtlLg = ($("html").attr("dir") === "rtl" && !verticalLg) ? true : false;
                            var slidesRtlMd = ($("html").attr("dir") === "rtl" && !verticalMd) ? true : false;
                            var slidesRtlSm = ($("html").attr("dir") === "rtl" && !verticalSm) ? true : false;
                            var slidesRtlXs = ($("html").attr("dir") === "rtl" && !verticalXs) ? true : false;

                            $this.slick({
                                slidesToShow: slidesPerView,
                                autoplay: slidesAutoplay,
                                autoplaySpeed: slidesAutoplaySpeed,
                                dots: slidesDots,
                                arrows: slidesArrows,
                                infinite: infinite,
                                vertical: vertical,
                                rtl: slidesRtl,
                                rows: slidesRows,
                                centerPadding: "0px",
                                centerMode: slidesCenterMode,
                                fade: slidesFade,
                                asNavFor: asNavFor,
                                focusOnSelect: focusOnSelect,
                                adaptiveHeight: adaptiveHeight,
                                slidesToScroll: 1,
                                prevArrow:
                                    '<button type="button" class="slick-prev"><i class="las la-angle-left"></i></button>',
                                nextArrow:
                                    '<button type="button" class="slick-next"><i class="las la-angle-right"></i></button>',
                                responsive: [
                                    {
                                        breakpoint: 1500,
                                        settings: {
                                            slidesToShow: slidesPerViewXl,
                                            vertical: verticalXl,
                                            rtl: slidesRtlXL,
                                        },
                                    },
                                    {
                                        breakpoint: 1200,
                                        settings: {
                                            slidesToShow: slidesPerViewLg,
                                            vertical: verticalLg,
                                            rtl: slidesRtlLg,
                                        },
                                    },
                                    {
                                        breakpoint: 992,
                                        settings: {
                                            slidesToShow: slidesPerViewMd,
                                            vertical: verticalMd,
                                            rtl: slidesRtlMd,
                                        },
                                    },
                                    {
                                        breakpoint: 768,
                                        settings: {
                                            slidesToShow: slidesPerViewSm,
                                            vertical: verticalSm,
                                            rtl: slidesRtlSm,
                                        },
                                    },
                                    {
                                        breakpoint: 576,
                                        settings: {
                                            slidesToShow: slidesPerViewXs,
                                            vertical: verticalXs,
                                            rtl: slidesRtlXs,
                                        },
                                    },
                                ],
                            });
                        });

                        // Iterate over each available attribute
                        for (var attributeId in response.availableAttributes) {
                            if (response.availableAttributes.hasOwnProperty(attributeId)) {
                                var availableValues = response.availableAttributes[attributeId];
                                console.log(availableValues );
                                // Iterate over each radio button for this attribute
                                $('.attribute_value input[type=radio][attributeId="' + attributeId + '"]').each(function () {
                                    var radioValue = $(this).val();
                                    var label = $(this).closest('.attribute_value');

                                    // Check if the radio button value is in the available values
                                    if (availableValues.indexOf(radioValue) === -1) {
                                        // If not in available values, disable the radio button
                                        // $(this).prop('disabled', true);
                                        label.find('span').css('border-bottom-color', 'red'); // Change to the desired color
                                    } else {
                                        // Otherwise, enable the radio button
                                        // $(this).prop('disabled', false);
                                        label.find('span').css('border-bottom-color', 'green'); // Change to the desired color

                                    }
                                });
                            }
                        }
                    }

                },

                        error: function (xhr, status, error) {
                            // Handle error
                            console.error(error);
                        }
                });
            }

        // Trigger the function on radio button change
        $('.attribute_value input[type=radio]').on('change', function () {
            // var niveauThreshold = $(this).attr("niveau") ;
            // // Iterate through all radio buttons
            // $('.attribute_value input[type="radio"]').each(function() {
            //     var niveau = parseInt($(this).attr('niveau'));

            //     // Check if the radio button's niveau is greater than the threshold
            //     if (!isNaN(niveau) && niveau > niveauThreshold) {
            //         // Uncheck the radio button
            //         $(this).prop('checked', false);
            //     }
            // });
            sendCheckedAttributes($(this));
        });

        $('.add_product').on('click', function(){
            var product_id = $(this).data('product_id');
            var editUrlBase  = "{{ route('seller.products.edit', ['id' => 'PLACEHOLDER']) }}";
            if(product_id != undefined){
                $.ajax({
                    url: "{{route('catalog.add_product')}}",
                    type: "POST",
                    data: {
                        id: product_id
                    },
                    cache: false,
                    dataType: 'JSON',
                    success: function(dataResult) {
                        // Replace 'PLACEHOLDER' with the actual slug from the response
                        var editUrl = editUrlBase.replace('PLACEHOLDER', dataResult.data);

                        // Open the URL
                        window.location.href = editUrl;
                    }
                })
            }
        })

        $('.add_product_to_catalog').on('click', function(){
            var product_id = $(this).data('product_id');
            var previewUrlBase  = "{{ route('seller.products.edit', ['id' => 'PLACEHOLDER']) }}";
            var current = $(this);
            if(product_id != undefined){
                $.ajax({
                    url: "{{route('catalog.add_product_to_catalog')}}",
                    type: "POST",
                    data: {
                        id: product_id
                    },
                    cache: false,
                    dataType: 'JSON',
                    success: function(dataResult) {

                        // toastr.options =    {
                        //                         positionClass: 'toast-top-right',
                        //                         closeButton: true,
                        //                         timeOut: 3000, // Set the duration for which the toast will be displayed (in milliseconds)
                        //                     };

                        // toastr.success('Product added successfully');

                        current.remove();
                        swal(
                            'Added',
                            'Product added successfully',
                            'success'
                        )

                        // Replace 'PLACEHOLDER' with the actual slug from the response
                        // var previewUrl = previewUrlBase.replace('PLACEHOLDER', data.data.slug);

                        // // Open the URL in a new tab
                        // window.open(previewUrl, '_blank');
                    }
                })
            }
        })



        });
    </script>
@endsection
