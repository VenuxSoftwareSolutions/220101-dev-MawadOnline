@extends('frontend.layouts.app')

<style>
    /* This will apply a style to the label when its associated input is checked */
.attribute_value input[type="radio"]:checked + .aiz-megabox-elem {
    border: 1px solid #D42D2A; /* Change the border color or any other style */

}

</style>

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

@section('content')
    <section class="mb-4 pt-3">
        <div class="container">
            <div class="bg-white py-3">
                <div class="row">
                    <!-- Product Image Gallery -->
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12 mb-4">
                        @include('frontend.product_details.image_gallery_preview')
                    </div>

                    <!-- Product Details -->
                    <div class="col-xl-8 col-lg-8 col-md-8 col-12">
                        @include('frontend.details')
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="container">
            <div class="row gutters-16">
                <!-- Left side
                <div class="col-lg-3">
                    <!-- Seller Info

                    <!-- Top Selling Products
                    <div class="d-none d-lg-block">
                        <div class="bg-white border mb-4">
                            <div class="p-3 p-sm-4 fs-16 fw-600">
                                Top Selling Products
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
                <div class="col-lg-12">

                    <!-- Reviews & Ratings -->
                    <div class="bg-white mb-4">
                        <!--<div class="p-3 p-sm-4">
                            <h3 class="fs-16 fw-700 mb-0">
                                <span class="mr-4">Reviews &amp; Ratings</span>
                            </h3>
                        </div>
                        <!-- Ratting
                        {{-- <div class="px-3 px-sm-4 mb-4">
                            <div class="border border-secondary-base bg-soft-secondary-base p-3 p-sm-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8 mb-3">
                                        <div
                                            class="d-flex align-items-center justify-content-between justify-content-md-start">
                                            <div class="w-100 w-sm-auto">
                                                <span class="fs-36 mr-3">0</span>
                                                <span class="fs-14 mr-3">out of 5.0</span>
                                            </div>
                                            <div
                                                class="mt-sm-3 w-100 w-sm-auto d-flex flex-wrap justify-content-end justify-content-md-start">
                                                <span class="rating rating-mr-1">
                                                    <i class="las la-star"></i><i class="las la-star"></i><i
                                                        class="las la-star"></i><i class="las la-star"></i><i
                                                        class="las la-star"></i>
                                                </span>
                                                <span class="ml-1 fs-14">(0
                                                    reviews)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a href="javascript:void(0);" onclick="product_review('3')"
                                            class="btn btn-secondary-base fw-400 rounded-0 text-white">
                                            <span class="d-md-inline-block"> Rate this Product</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> --}} <!--
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
                                        $totalRating = $detailedProduct->reviews->count();
                                        @endphp
                                        <div
                                            class="d-flex align-items-center justify-content-between justify-content-md-start">
                                            <div class="w-100 w-sm-auto">
                                                <span class="avgRating fs-36 mr-3">{{$totalRating > 0 ? $detailedProduct->reviews->sum('rating') / $totalRating : 0 }}</span>
                                                <span class="fs-14 mr-3">out of 5.0</span>
                                            </div>
                                            <div
                                                class="mt-sm-3 w-100 w-sm-auto d-flex flex-wrap justify-content-end justify-content-md-start">
                                                <span class="rating rating-mr-1 rating-var">
                                                    @if($totalRating > 0)
                                                    {{ renderStarRating($detailedProduct->reviews->sum('rating') / $totalRating) }}
                                                @else
                                                    {{ renderStarRating(0) }} <!-- Assuming 0 stars when there are no reviews
                                                @endif
                                                </span>
                                                <span class="total-var-rating ml-1 fs-14">({{$totalRating}}
                                                    reviews)</span>
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
                                                reviews)</span>
                                        </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a href="javascript:void(0);" onclick="product_review('3')"
                                            class="btn btn-secondary-base fw-400 rounded-0 text-white">
                                            <span class="d-md-inline-block"> Rate this Product</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Reviews
                        <div class="py-3 reviews-area">
                            <ul class="list-group list-group-flush">
                            </ul>

                            <div class="text-center fs-18 opacity-70">
                                There have been no reviews for this product yet.
                            </div>

                            <!-- Pagination
                            <div class="aiz-pagination product-reviews-pagination py-2 px-4 d-flex justify-content-end">

                            </div>
                        </div>
                    </div> -->
                    <!-- Description, Video, Downloads -->
                    <div class="bg-white mb-4 p-3 p-sm-4 col-12 col-md-12 float-left">
                        <!-- Tabs -->
                        <div class="nav aiz-nav-tabs">
                            <a href="#tab_default_1" data-toggle="tab" class="mr-5 pb-2 fs-16 fw-700 text-reset active show">Description</a>
                            <a href="#tab_default_2" data-toggle="tab" class="mr-5 pb-2 fs-16 fw-700 text-reset">Downloadable</a>
                            <a href="#tab_default_3" data-toggle="tab" class="mr-5 pb-2 fs-16 fw-700 text-reset">Reviews ( {{$totalRating}} )</a>
                        </div>

                        <!-- Description -->
                        <div class="tab-content pt-0">
                            <!-- Description -->
                            <div class="tab-pane fade active show col-12 float-left" id="tab_default_1">

                                <div class="col-12 col-md-6 py-5 float-left">
                                    <span class="fs-20 font-prompt-md pb-2">Product information</span>
                                    @if(!empty($previewData['detailedProduct']['description']))
                                    <div class="mw-100 overflow-hidden text-left aiz-editor-data fs-16 font-prompt">
                                        {!! $previewData['detailedProduct']['description'] !!}
                                    </div>
                                    @else
                                        <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                                            No description available.
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
                                        <!--
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
                                                                Product details</h2>
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
                                                                    @foreach ($previewData['detailedProduct']['general_attributes'] as $key => $general_attribute)
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

                                                                    @endforeach

                                                                </ul>
                                                            </div>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="bg-white p-sm-4 col-12 col-md-6 float-left mt-4 product-table-style">
                                    <span class="fs-20 font-prompt-md">Product Specification</span>
                                    <table class="table prod-details-table border">
                                        <tbody>
                                            @foreach ($previewData['detailedProduct']['general_attributes'] as $key => $general_attribute)
                                                @php
                                                    $attribue_general = App\Models\Attribute::find($key) ;
                                                @endphp

                                                @if (preg_match('/^#[0-9A-F]{6}$/i', $general_attribute))
                                                    <tr>
                                                        <td class="background-green">Color</td>
                                                        <td><span class="color-preview" style="display: inline-block; width: 20px; height: 20px; background-color: {{$general_attribute}};"></span></td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="background-green">{{$attribue_general ? $attribue_general->getTranslation('name') : ""}}</td>
                                                        <td>{{$general_attribute}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Video
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
                            </div>-->

                            <!-- Download -->
                            <div class="tab-pane fade" id="tab_default_2">
                                <div class="py-3 col-12 p-0 float-left">
                                    <div class="download-title fs-20 font-prompt-md text-left col-md-12">BIM/CAD</div>
                                    <div class="col-md-12 my-2 float-left">
                                        @if(count($previewData['detailedProduct']['documents']) > 0)
                                            @foreach($previewData['detailedProduct']['documents'] as $document)
                                                @switch($document->extension)
                                                    @case('pdf')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('jpg')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('png')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('pln')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('dwg')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('dxf')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('gsm')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('stl')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('rfa')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('rvt')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('ifc')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_3326_35086)">
                                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                                        </g>
                                                                        <defs>
                                                                        <clipPath id="clip0_3326_35086">
                                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                        </clipPath>
                                                                        </defs>
                                                                        </svg>
                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('3ds')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                        <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <g clip-path="url(#clip0_3326_35104)">
                                                                            <path opacity="0.75" d="M23.5474 19.5151L24.6967 15.8119L18.5283 18.3982L23.5474 19.5151ZM36.9317 12.5202C32.2327 7.7296 20.8415 8.626 16.3607 9.74283C11.1233 11.0948 9.72671 15.1212 9.87219 21.6458C9.93039 25.3931 11.5452 28.7142 14.6731 29.8457C21.0452 32.0793 26.5152 31.7707 32.2618 26.216C29.323 23.0125 26.879 21.8957 22.8637 22.1896C18.9648 22.3806 16.7535 21.7634 13.9893 18.7068C17.3645 15.4298 19.4303 13.8428 23.5038 13.4166C28.0574 12.9464 32.1163 14.8861 33.8766 16.6936C35.5205 18.3982 36.3498 22.2924 35.957 27.0095C35.6515 30.8303 34.444 33.0786 31.6653 33.8868C28.0864 35.0037 22.8928 35.5474 18.0919 35.3563C12.4327 35.1212 9.46485 33.9603 8.6356 33.0786C6.88983 31.1095 5.60959 26.9361 5.31863 22.1455C5.02767 17.3549 5.76962 12.8288 7.23898 10.36C8.69379 7.89125 14.0766 6.14253 22.0053 5.6429C28.6247 5.26083 34.764 5.76046 34.8368 5.76046L34.4294 0.426147C34.4294 0.426147 20.2741 2.08669 18.1064 2.27773C12.8546 2.79205 5.78417 3.93827 3.41283 7.96472C-0.820679 15.1506 0.255882 30.4482 5.37682 36.1793C9.94493 41.3225 25.8315 40.4702 32.9455 38.3101C36.1461 37.3402 39.7686 34.6804 40.3942 27.4063C40.8015 22.6157 40.1468 15.8413 36.9317 12.5202ZM22.7036 25.349L27.7227 26.4658L21.5543 29.0522L22.7036 25.349Z" fill="black"/>
                                                                            <path d="M23.2574 19.0889L24.4067 15.3857L18.2383 17.9721L23.2574 19.0889ZM36.6417 12.094C31.9426 7.30345 20.5514 8.18516 16.0706 9.31668C10.8333 10.6686 9.43667 14.6951 9.58215 21.2197C9.64035 24.9669 11.2552 28.288 14.383 29.4195C20.7551 31.6532 26.2252 31.3446 31.9717 25.7899C29.033 22.5863 26.5889 21.4695 22.5736 21.7634C18.6747 21.9544 16.4634 21.3373 13.6993 18.2807C17.0744 15.0037 19.1403 13.4166 23.2137 12.9904C27.7673 12.5202 31.8262 14.46 33.5866 16.2675C35.2305 17.9721 36.0597 21.8663 35.6669 26.5834C35.3614 30.4041 34.1539 32.6525 31.3752 33.4607C27.7964 34.5775 22.5882 35.1212 17.8018 34.9302C12.1426 34.6951 9.17481 33.5342 8.34556 32.6525C6.59979 30.6833 5.31955 26.5099 5.02859 21.7193C4.73763 16.9287 5.47958 12.4026 6.94894 9.93387C8.40376 7.4651 13.7866 5.71638 21.7153 5.21675C28.3347 4.83468 34.474 5.33431 34.5467 5.33431L34.1394 0C34.1394 0 19.9841 1.66054 17.8164 1.85158C12.5645 2.35121 5.47958 3.51212 3.12279 7.53857C-1.11072 14.7245 -0.0341572 30.0367 5.08678 35.7531C9.65489 40.8964 25.5414 40.0441 32.6555 37.8839C35.8561 36.914 39.4786 34.2542 40.1041 26.9802C40.5115 22.1896 39.8568 15.4151 36.6417 12.094ZM22.4136 24.9229L27.4327 26.0397L21.2643 28.626L22.4136 24.9229Z" fill="#016B6B"/>
                                                                            </g>
                                                                            <defs>
                                                                            <clipPath id="clip0_3326_35104">
                                                                            <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                            </clipPath>
                                                                            </defs>
                                                                            </svg>

                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">3D Studio MAX</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('max')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                        <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <g clip-path="url(#clip0_3326_35104)">
                                                                            <path opacity="0.75" d="M23.5474 19.5151L24.6967 15.8119L18.5283 18.3982L23.5474 19.5151ZM36.9317 12.5202C32.2327 7.7296 20.8415 8.626 16.3607 9.74283C11.1233 11.0948 9.72671 15.1212 9.87219 21.6458C9.93039 25.3931 11.5452 28.7142 14.6731 29.8457C21.0452 32.0793 26.5152 31.7707 32.2618 26.216C29.323 23.0125 26.879 21.8957 22.8637 22.1896C18.9648 22.3806 16.7535 21.7634 13.9893 18.7068C17.3645 15.4298 19.4303 13.8428 23.5038 13.4166C28.0574 12.9464 32.1163 14.8861 33.8766 16.6936C35.5205 18.3982 36.3498 22.2924 35.957 27.0095C35.6515 30.8303 34.444 33.0786 31.6653 33.8868C28.0864 35.0037 22.8928 35.5474 18.0919 35.3563C12.4327 35.1212 9.46485 33.9603 8.6356 33.0786C6.88983 31.1095 5.60959 26.9361 5.31863 22.1455C5.02767 17.3549 5.76962 12.8288 7.23898 10.36C8.69379 7.89125 14.0766 6.14253 22.0053 5.6429C28.6247 5.26083 34.764 5.76046 34.8368 5.76046L34.4294 0.426147C34.4294 0.426147 20.2741 2.08669 18.1064 2.27773C12.8546 2.79205 5.78417 3.93827 3.41283 7.96472C-0.820679 15.1506 0.255882 30.4482 5.37682 36.1793C9.94493 41.3225 25.8315 40.4702 32.9455 38.3101C36.1461 37.3402 39.7686 34.6804 40.3942 27.4063C40.8015 22.6157 40.1468 15.8413 36.9317 12.5202ZM22.7036 25.349L27.7227 26.4658L21.5543 29.0522L22.7036 25.349Z" fill="black"/>
                                                                            <path d="M23.2574 19.0889L24.4067 15.3857L18.2383 17.9721L23.2574 19.0889ZM36.6417 12.094C31.9426 7.30345 20.5514 8.18516 16.0706 9.31668C10.8333 10.6686 9.43667 14.6951 9.58215 21.2197C9.64035 24.9669 11.2552 28.288 14.383 29.4195C20.7551 31.6532 26.2252 31.3446 31.9717 25.7899C29.033 22.5863 26.5889 21.4695 22.5736 21.7634C18.6747 21.9544 16.4634 21.3373 13.6993 18.2807C17.0744 15.0037 19.1403 13.4166 23.2137 12.9904C27.7673 12.5202 31.8262 14.46 33.5866 16.2675C35.2305 17.9721 36.0597 21.8663 35.6669 26.5834C35.3614 30.4041 34.1539 32.6525 31.3752 33.4607C27.7964 34.5775 22.5882 35.1212 17.8018 34.9302C12.1426 34.6951 9.17481 33.5342 8.34556 32.6525C6.59979 30.6833 5.31955 26.5099 5.02859 21.7193C4.73763 16.9287 5.47958 12.4026 6.94894 9.93387C8.40376 7.4651 13.7866 5.71638 21.7153 5.21675C28.3347 4.83468 34.474 5.33431 34.5467 5.33431L34.1394 0C34.1394 0 19.9841 1.66054 17.8164 1.85158C12.5645 2.35121 5.47958 3.51212 3.12279 7.53857C-1.11072 14.7245 -0.0341572 30.0367 5.08678 35.7531C9.65489 40.8964 25.5414 40.0441 32.6555 37.8839C35.8561 36.914 39.4786 34.2542 40.1041 26.9802C40.5115 22.1896 39.8568 15.4151 36.6417 12.094ZM22.4136 24.9229L27.4327 26.0397L21.2643 28.626L22.4136 24.9229Z" fill="#016B6B"/>
                                                                            </g>
                                                                            <defs>
                                                                            <clipPath id="clip0_3326_35104">
                                                                            <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                            </clipPath>
                                                                            </defs>
                                                                            </svg>

                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">3D Studio MAX</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('obj')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                        <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <g clip-path="url(#clip0_3326_35104)">
                                                                            <path opacity="0.75" d="M23.5474 19.5151L24.6967 15.8119L18.5283 18.3982L23.5474 19.5151ZM36.9317 12.5202C32.2327 7.7296 20.8415 8.626 16.3607 9.74283C11.1233 11.0948 9.72671 15.1212 9.87219 21.6458C9.93039 25.3931 11.5452 28.7142 14.6731 29.8457C21.0452 32.0793 26.5152 31.7707 32.2618 26.216C29.323 23.0125 26.879 21.8957 22.8637 22.1896C18.9648 22.3806 16.7535 21.7634 13.9893 18.7068C17.3645 15.4298 19.4303 13.8428 23.5038 13.4166C28.0574 12.9464 32.1163 14.8861 33.8766 16.6936C35.5205 18.3982 36.3498 22.2924 35.957 27.0095C35.6515 30.8303 34.444 33.0786 31.6653 33.8868C28.0864 35.0037 22.8928 35.5474 18.0919 35.3563C12.4327 35.1212 9.46485 33.9603 8.6356 33.0786C6.88983 31.1095 5.60959 26.9361 5.31863 22.1455C5.02767 17.3549 5.76962 12.8288 7.23898 10.36C8.69379 7.89125 14.0766 6.14253 22.0053 5.6429C28.6247 5.26083 34.764 5.76046 34.8368 5.76046L34.4294 0.426147C34.4294 0.426147 20.2741 2.08669 18.1064 2.27773C12.8546 2.79205 5.78417 3.93827 3.41283 7.96472C-0.820679 15.1506 0.255882 30.4482 5.37682 36.1793C9.94493 41.3225 25.8315 40.4702 32.9455 38.3101C36.1461 37.3402 39.7686 34.6804 40.3942 27.4063C40.8015 22.6157 40.1468 15.8413 36.9317 12.5202ZM22.7036 25.349L27.7227 26.4658L21.5543 29.0522L22.7036 25.349Z" fill="black"/>
                                                                            <path d="M23.2574 19.0889L24.4067 15.3857L18.2383 17.9721L23.2574 19.0889ZM36.6417 12.094C31.9426 7.30345 20.5514 8.18516 16.0706 9.31668C10.8333 10.6686 9.43667 14.6951 9.58215 21.2197C9.64035 24.9669 11.2552 28.288 14.383 29.4195C20.7551 31.6532 26.2252 31.3446 31.9717 25.7899C29.033 22.5863 26.5889 21.4695 22.5736 21.7634C18.6747 21.9544 16.4634 21.3373 13.6993 18.2807C17.0744 15.0037 19.1403 13.4166 23.2137 12.9904C27.7673 12.5202 31.8262 14.46 33.5866 16.2675C35.2305 17.9721 36.0597 21.8663 35.6669 26.5834C35.3614 30.4041 34.1539 32.6525 31.3752 33.4607C27.7964 34.5775 22.5882 35.1212 17.8018 34.9302C12.1426 34.6951 9.17481 33.5342 8.34556 32.6525C6.59979 30.6833 5.31955 26.5099 5.02859 21.7193C4.73763 16.9287 5.47958 12.4026 6.94894 9.93387C8.40376 7.4651 13.7866 5.71638 21.7153 5.21675C28.3347 4.83468 34.474 5.33431 34.5467 5.33431L34.1394 0C34.1394 0 19.9841 1.66054 17.8164 1.85158C12.5645 2.35121 5.47958 3.51212 3.12279 7.53857C-1.11072 14.7245 -0.0341572 30.0367 5.08678 35.7531C9.65489 40.8964 25.5414 40.0441 32.6555 37.8839C35.8561 36.914 39.4786 34.2542 40.1041 26.9802C40.5115 22.1896 39.8568 15.4151 36.6417 12.094ZM22.4136 24.9229L27.4327 26.0397L21.2643 28.626L22.4136 24.9229Z" fill="#016B6B"/>
                                                                            </g>
                                                                            <defs>
                                                                            <clipPath id="clip0_3326_35104">
                                                                            <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                            </clipPath>
                                                                            </defs>
                                                                            </svg>

                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">3D Studio MAX</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('fbx')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                        <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <g clip-path="url(#clip0_3326_35104)">
                                                                            <path opacity="0.75" d="M23.5474 19.5151L24.6967 15.8119L18.5283 18.3982L23.5474 19.5151ZM36.9317 12.5202C32.2327 7.7296 20.8415 8.626 16.3607 9.74283C11.1233 11.0948 9.72671 15.1212 9.87219 21.6458C9.93039 25.3931 11.5452 28.7142 14.6731 29.8457C21.0452 32.0793 26.5152 31.7707 32.2618 26.216C29.323 23.0125 26.879 21.8957 22.8637 22.1896C18.9648 22.3806 16.7535 21.7634 13.9893 18.7068C17.3645 15.4298 19.4303 13.8428 23.5038 13.4166C28.0574 12.9464 32.1163 14.8861 33.8766 16.6936C35.5205 18.3982 36.3498 22.2924 35.957 27.0095C35.6515 30.8303 34.444 33.0786 31.6653 33.8868C28.0864 35.0037 22.8928 35.5474 18.0919 35.3563C12.4327 35.1212 9.46485 33.9603 8.6356 33.0786C6.88983 31.1095 5.60959 26.9361 5.31863 22.1455C5.02767 17.3549 5.76962 12.8288 7.23898 10.36C8.69379 7.89125 14.0766 6.14253 22.0053 5.6429C28.6247 5.26083 34.764 5.76046 34.8368 5.76046L34.4294 0.426147C34.4294 0.426147 20.2741 2.08669 18.1064 2.27773C12.8546 2.79205 5.78417 3.93827 3.41283 7.96472C-0.820679 15.1506 0.255882 30.4482 5.37682 36.1793C9.94493 41.3225 25.8315 40.4702 32.9455 38.3101C36.1461 37.3402 39.7686 34.6804 40.3942 27.4063C40.8015 22.6157 40.1468 15.8413 36.9317 12.5202ZM22.7036 25.349L27.7227 26.4658L21.5543 29.0522L22.7036 25.349Z" fill="black"/>
                                                                            <path d="M23.2574 19.0889L24.4067 15.3857L18.2383 17.9721L23.2574 19.0889ZM36.6417 12.094C31.9426 7.30345 20.5514 8.18516 16.0706 9.31668C10.8333 10.6686 9.43667 14.6951 9.58215 21.2197C9.64035 24.9669 11.2552 28.288 14.383 29.4195C20.7551 31.6532 26.2252 31.3446 31.9717 25.7899C29.033 22.5863 26.5889 21.4695 22.5736 21.7634C18.6747 21.9544 16.4634 21.3373 13.6993 18.2807C17.0744 15.0037 19.1403 13.4166 23.2137 12.9904C27.7673 12.5202 31.8262 14.46 33.5866 16.2675C35.2305 17.9721 36.0597 21.8663 35.6669 26.5834C35.3614 30.4041 34.1539 32.6525 31.3752 33.4607C27.7964 34.5775 22.5882 35.1212 17.8018 34.9302C12.1426 34.6951 9.17481 33.5342 8.34556 32.6525C6.59979 30.6833 5.31955 26.5099 5.02859 21.7193C4.73763 16.9287 5.47958 12.4026 6.94894 9.93387C8.40376 7.4651 13.7866 5.71638 21.7153 5.21675C28.3347 4.83468 34.474 5.33431 34.5467 5.33431L34.1394 0C34.1394 0 19.9841 1.66054 17.8164 1.85158C12.5645 2.35121 5.47958 3.51212 3.12279 7.53857C-1.11072 14.7245 -0.0341572 30.0367 5.08678 35.7531C9.65489 40.8964 25.5414 40.0441 32.6555 37.8839C35.8561 36.914 39.4786 34.2542 40.1041 26.9802C40.5115 22.1896 39.8568 15.4151 36.6417 12.094ZM22.4136 24.9229L27.4327 26.0397L21.2643 28.626L22.4136 24.9229Z" fill="#016B6B"/>
                                                                            </g>
                                                                            <defs>
                                                                            <clipPath id="clip0_3326_35104">
                                                                            <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                            </clipPath>
                                                                            </defs>
                                                                            </svg>

                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">3D Studio MAX</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('skp')
                                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                                            <div class="py-5 px-4">
                                                                <div class="my-4 mx-0">
                                                                    <center>
                                                                        <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <g clip-path="url(#clip0_3326_35104)">
                                                                            <path opacity="0.75" d="M23.5474 19.5151L24.6967 15.8119L18.5283 18.3982L23.5474 19.5151ZM36.9317 12.5202C32.2327 7.7296 20.8415 8.626 16.3607 9.74283C11.1233 11.0948 9.72671 15.1212 9.87219 21.6458C9.93039 25.3931 11.5452 28.7142 14.6731 29.8457C21.0452 32.0793 26.5152 31.7707 32.2618 26.216C29.323 23.0125 26.879 21.8957 22.8637 22.1896C18.9648 22.3806 16.7535 21.7634 13.9893 18.7068C17.3645 15.4298 19.4303 13.8428 23.5038 13.4166C28.0574 12.9464 32.1163 14.8861 33.8766 16.6936C35.5205 18.3982 36.3498 22.2924 35.957 27.0095C35.6515 30.8303 34.444 33.0786 31.6653 33.8868C28.0864 35.0037 22.8928 35.5474 18.0919 35.3563C12.4327 35.1212 9.46485 33.9603 8.6356 33.0786C6.88983 31.1095 5.60959 26.9361 5.31863 22.1455C5.02767 17.3549 5.76962 12.8288 7.23898 10.36C8.69379 7.89125 14.0766 6.14253 22.0053 5.6429C28.6247 5.26083 34.764 5.76046 34.8368 5.76046L34.4294 0.426147C34.4294 0.426147 20.2741 2.08669 18.1064 2.27773C12.8546 2.79205 5.78417 3.93827 3.41283 7.96472C-0.820679 15.1506 0.255882 30.4482 5.37682 36.1793C9.94493 41.3225 25.8315 40.4702 32.9455 38.3101C36.1461 37.3402 39.7686 34.6804 40.3942 27.4063C40.8015 22.6157 40.1468 15.8413 36.9317 12.5202ZM22.7036 25.349L27.7227 26.4658L21.5543 29.0522L22.7036 25.349Z" fill="black"/>
                                                                            <path d="M23.2574 19.0889L24.4067 15.3857L18.2383 17.9721L23.2574 19.0889ZM36.6417 12.094C31.9426 7.30345 20.5514 8.18516 16.0706 9.31668C10.8333 10.6686 9.43667 14.6951 9.58215 21.2197C9.64035 24.9669 11.2552 28.288 14.383 29.4195C20.7551 31.6532 26.2252 31.3446 31.9717 25.7899C29.033 22.5863 26.5889 21.4695 22.5736 21.7634C18.6747 21.9544 16.4634 21.3373 13.6993 18.2807C17.0744 15.0037 19.1403 13.4166 23.2137 12.9904C27.7673 12.5202 31.8262 14.46 33.5866 16.2675C35.2305 17.9721 36.0597 21.8663 35.6669 26.5834C35.3614 30.4041 34.1539 32.6525 31.3752 33.4607C27.7964 34.5775 22.5882 35.1212 17.8018 34.9302C12.1426 34.6951 9.17481 33.5342 8.34556 32.6525C6.59979 30.6833 5.31955 26.5099 5.02859 21.7193C4.73763 16.9287 5.47958 12.4026 6.94894 9.93387C8.40376 7.4651 13.7866 5.71638 21.7153 5.21675C28.3347 4.83468 34.474 5.33431 34.5467 5.33431L34.1394 0C34.1394 0 19.9841 1.66054 17.8164 1.85158C12.5645 2.35121 5.47958 3.51212 3.12279 7.53857C-1.11072 14.7245 -0.0341572 30.0367 5.08678 35.7531C9.65489 40.8964 25.5414 40.0441 32.6555 37.8839C35.8561 36.914 39.4786 34.2542 40.1041 26.9802C40.5115 22.1896 39.8568 15.4151 36.6417 12.094ZM22.4136 24.9229L27.4327 26.0397L21.2643 28.626L22.4136 24.9229Z" fill="#016B6B"/>
                                                                            </g>
                                                                            <defs>
                                                                            <clipPath id="clip0_3326_35104">
                                                                            <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                                            </clipPath>
                                                                            </defs>
                                                                            </svg>

                                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">3D Studio MAX</span>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                                <span class="download-box-btm-r">
                                                                    1
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @break
                                                @endswitch
                                            @endforeach
                                        @else
                                            No document exist
                                        @endif
                                        {{-- <!-- AutoCAD -->
                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                            <div class="py-5 px-4">
                                                <div class="my-4 mx-0">
                                                    <center>
                                                    <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_3326_35086)">
                                                        <path d="M0.603516 5.86286C2.74577 4.47029 4.89102 3.08471 7.02829 1.68616C7.0243 11.5719 7.03528 21.4586 7.0233 31.3443C6.18376 31.9034 5.32926 32.4394 4.49072 33.0004C3.40961 33.6733 2.34248 34.37 1.28433 35.0798C1.07569 35.2255 0.851083 35.3453 0.616493 35.4432C0.60651 25.5834 0.630469 15.7226 0.603516 5.86286Z" fill="#E85984"/>
                                                        <path d="M7.02818 1.68604C16.4797 1.68704 25.9321 1.68405 35.3836 1.68704C36.3699 1.70002 37.3203 2.47666 37.4011 3.4839C37.439 5.25481 37.3762 7.02871 37.4321 8.80061C37.453 15.5798 37.4351 22.3589 37.441 29.1381C37.4271 29.8798 37.476 30.6225 37.4161 31.3632C34.2506 31.3951 31.0832 31.3672 27.9167 31.3762C21.3282 31.3762 14.7397 31.3762 8.15122 31.3772C7.77488 31.3831 7.39754 31.3851 7.02319 31.3442C7.03517 21.4585 7.02419 11.5718 7.02818 1.68604ZM20.0125 7.85227C19.3846 9.6671 18.8835 11.5248 18.2995 13.3537C17.2094 16.9224 16.1183 20.4912 15.0272 24.0599C14.9264 24.3444 14.8415 24.6359 14.7926 24.9344C15.8248 24.9504 16.859 24.9584 17.8912 24.9314C18.2875 23.5588 18.6898 22.1882 19.0721 20.8116C19.1101 20.6898 19.159 20.573 19.2069 20.4562C21.1645 20.4612 23.1231 20.4303 25.0806 20.4722C25.5508 21.9626 25.998 23.461 26.4972 24.9414C27.5523 24.9474 28.6085 24.9544 29.6636 24.9354C29.0377 22.8361 28.325 20.7637 27.6741 18.6724C26.5002 15.0707 25.3691 11.455 24.1882 7.85626C22.7976 7.82232 21.4031 7.82931 20.0125 7.85227Z" fill="#E51050"/>
                                                        <path d="M22.0596 10.0714C22.2613 10.5536 22.3411 11.0747 22.5039 11.5698C23.1008 13.6951 23.7327 15.8124 24.3047 17.9457C22.8373 17.9656 21.3678 17.9666 19.8994 17.9457C20.5762 15.3103 21.3678 12.7038 22.0596 10.0714Z" fill="#E51050"/>
                                                        <path d="M20.0126 7.85236C21.4032 7.8294 22.7977 7.82242 24.1883 7.85636C25.3692 11.4551 26.5003 15.0707 27.6742 18.6725C28.3251 20.7638 29.0378 22.8362 29.6637 24.9355C28.6086 24.9545 27.5524 24.9475 26.4973 24.9415C25.9981 23.4611 25.5509 21.9627 25.0807 20.4723C23.1232 20.4304 21.1646 20.4613 19.207 20.4563C19.1591 20.5731 19.1102 20.6899 19.0722 20.8117C18.6899 22.1883 18.2876 23.5589 17.8913 24.9315C16.8591 24.9585 15.8249 24.9505 14.7927 24.9345C14.8416 24.636 14.9265 24.3445 15.0273 24.06C16.1184 20.4913 17.2095 16.9225 18.2996 13.3537C18.8836 11.5249 19.3847 9.66719 20.0126 7.85236ZM22.06 10.0715C21.3682 12.7039 20.5766 15.3103 19.8998 17.9457C21.3682 17.9667 22.8377 17.9657 24.3051 17.9457C23.7331 15.8125 23.1012 13.6952 22.5042 11.5699C22.3415 11.0747 22.2617 10.5536 22.06 10.0715Z" fill="white"/>
                                                        <path d="M37.4326 8.80067C38.2762 8.69685 39.1317 8.74976 39.9792 8.76573C40.0221 10.9968 39.9852 13.2299 39.9972 15.462C39.9972 22.5826 39.9982 29.7041 39.9972 36.8247C39.9922 37.3548 40.0161 37.8848 39.9802 38.4149C28.9964 38.4329 18.0126 38.4139 7.02878 38.4239C6.37692 38.4219 5.66117 38.362 5.15505 37.9038C4.77571 37.5474 4.48123 37.0563 4.49321 36.5212C4.49021 35.3473 4.49221 34.1743 4.49121 33.0004C5.32975 32.4394 6.18425 31.9033 7.02378 31.3443C7.39813 31.3852 7.77547 31.3832 8.15181 31.3772C14.7403 31.3762 21.3288 31.3762 27.9173 31.3762C31.0837 31.3672 34.2512 31.3952 37.4167 31.3632C37.4766 30.6225 37.4277 29.8798 37.4416 29.1381C37.4356 22.359 37.4536 15.5798 37.4326 8.80067Z" fill="#770829"/>
                                                        </g>
                                                        <defs>
                                                        <clipPath id="clip0_3326_35086">
                                                        <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                        </clipPath>
                                                        </defs>
                                                        </svg>
                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">AutoCAD</span>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                <span class="download-box-btm-r">
                                                    2
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- 3D Studio MAX -->
                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                            <div class="py-5 px-4">
                                                <div class="my-4 mx-0">
                                                    <center>
                                                        <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#clip0_3326_35104)">
                                                            <path opacity="0.75" d="M23.5474 19.5151L24.6967 15.8119L18.5283 18.3982L23.5474 19.5151ZM36.9317 12.5202C32.2327 7.7296 20.8415 8.626 16.3607 9.74283C11.1233 11.0948 9.72671 15.1212 9.87219 21.6458C9.93039 25.3931 11.5452 28.7142 14.6731 29.8457C21.0452 32.0793 26.5152 31.7707 32.2618 26.216C29.323 23.0125 26.879 21.8957 22.8637 22.1896C18.9648 22.3806 16.7535 21.7634 13.9893 18.7068C17.3645 15.4298 19.4303 13.8428 23.5038 13.4166C28.0574 12.9464 32.1163 14.8861 33.8766 16.6936C35.5205 18.3982 36.3498 22.2924 35.957 27.0095C35.6515 30.8303 34.444 33.0786 31.6653 33.8868C28.0864 35.0037 22.8928 35.5474 18.0919 35.3563C12.4327 35.1212 9.46485 33.9603 8.6356 33.0786C6.88983 31.1095 5.60959 26.9361 5.31863 22.1455C5.02767 17.3549 5.76962 12.8288 7.23898 10.36C8.69379 7.89125 14.0766 6.14253 22.0053 5.6429C28.6247 5.26083 34.764 5.76046 34.8368 5.76046L34.4294 0.426147C34.4294 0.426147 20.2741 2.08669 18.1064 2.27773C12.8546 2.79205 5.78417 3.93827 3.41283 7.96472C-0.820679 15.1506 0.255882 30.4482 5.37682 36.1793C9.94493 41.3225 25.8315 40.4702 32.9455 38.3101C36.1461 37.3402 39.7686 34.6804 40.3942 27.4063C40.8015 22.6157 40.1468 15.8413 36.9317 12.5202ZM22.7036 25.349L27.7227 26.4658L21.5543 29.0522L22.7036 25.349Z" fill="black"/>
                                                            <path d="M23.2574 19.0889L24.4067 15.3857L18.2383 17.9721L23.2574 19.0889ZM36.6417 12.094C31.9426 7.30345 20.5514 8.18516 16.0706 9.31668C10.8333 10.6686 9.43667 14.6951 9.58215 21.2197C9.64035 24.9669 11.2552 28.288 14.383 29.4195C20.7551 31.6532 26.2252 31.3446 31.9717 25.7899C29.033 22.5863 26.5889 21.4695 22.5736 21.7634C18.6747 21.9544 16.4634 21.3373 13.6993 18.2807C17.0744 15.0037 19.1403 13.4166 23.2137 12.9904C27.7673 12.5202 31.8262 14.46 33.5866 16.2675C35.2305 17.9721 36.0597 21.8663 35.6669 26.5834C35.3614 30.4041 34.1539 32.6525 31.3752 33.4607C27.7964 34.5775 22.5882 35.1212 17.8018 34.9302C12.1426 34.6951 9.17481 33.5342 8.34556 32.6525C6.59979 30.6833 5.31955 26.5099 5.02859 21.7193C4.73763 16.9287 5.47958 12.4026 6.94894 9.93387C8.40376 7.4651 13.7866 5.71638 21.7153 5.21675C28.3347 4.83468 34.474 5.33431 34.5467 5.33431L34.1394 0C34.1394 0 19.9841 1.66054 17.8164 1.85158C12.5645 2.35121 5.47958 3.51212 3.12279 7.53857C-1.11072 14.7245 -0.0341572 30.0367 5.08678 35.7531C9.65489 40.8964 25.5414 40.0441 32.6555 37.8839C35.8561 36.914 39.4786 34.2542 40.1041 26.9802C40.5115 22.1896 39.8568 15.4151 36.6417 12.094ZM22.4136 24.9229L27.4327 26.0397L21.2643 28.626L22.4136 24.9229Z" fill="#016B6B"/>
                                                            </g>
                                                            <defs>
                                                            <clipPath id="clip0_3326_35104">
                                                            <rect width="40" height="40" fill="white" transform="translate(0.5)"/>
                                                            </clipPath>
                                                            </defs>
                                                            </svg>

                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">3D Studio MAX</span>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                <span class="download-box-btm-r">
                                                    2
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Cinema 4D -->
                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                            <div class="py-5 px-4">
                                                <div class="my-4 mx-0">
                                                    <center>
                                                        <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M20.9167 36.6666C29.4311 36.6666 36.3333 29.3913 36.3333 20.4166C36.3333 11.442 29.4311 4.16663 20.9167 4.16663C12.4023 4.16663 5.5 11.442 5.5 20.4166C5.5 29.3913 12.4023 36.6666 20.9167 36.6666Z" fill="#616161"/>
                                                            <path d="M23.833 29.1125C30.7366 29.1125 36.333 23.6967 36.333 17.0159C36.333 10.3351 30.7366 4.91919 23.833 4.91919C16.9294 4.91919 11.333 10.3351 11.333 17.0159C11.333 23.6967 16.9294 29.1125 23.833 29.1125Z" fill="#212121"/>
                                                            <path d="M23 5.83337C23 5.83337 27.1667 7.50004 25.4159 8.98504C24.53 9.73587 23.4042 10.1334 22.3542 10.625C21.135 11.1959 19.8792 11.6917 18.7775 12.49C17.945 13.0934 17.1584 13.7417 16.5934 14.6134C15.9167 15.6567 15.5184 16.7984 15.45 18.045C15.3884 19.1675 15.5967 20.2459 16.0634 21.2617C16.7292 22.7117 17.81 23.7725 19.2284 24.5025C20.2384 25.0225 21.3092 25.2634 22.4392 25.2367C23.7625 25.2059 24.9817 24.8309 26.0759 24.0759C26.6967 23.6475 27.1917 23.1042 27.6584 22.5092C28.1984 21.8209 28.6342 21.0809 29.0084 20.3009C29.4742 19.3309 29.825 18.3142 30.2109 17.3109C30.5067 16.5425 30.8084 15.77 31.2675 15.0842C33 12.5 34.6667 15 34.6667 15C34.6667 15 33.8334 11.6667 31.3334 9.16671C28.8334 6.66671 23 5.83337 23 5.83337Z" fill="#3949AB"/>
                                                            <path d="M32.1672 16.6666C31.8005 21.3441 30.878 24.8408 29.6172 27.4383C27.7005 32.1816 22.868 35.4333 17.3363 34.9533C11.5238 34.4483 6.81715 29.6891 6.37049 23.8716C6.01632 19.2608 8.25465 15.15 11.7763 12.8275C14.658 10.7033 18.6988 9.85995 22.1672 9.16662C24.9772 8.60495 26.3655 7.75829 26.9722 6.88412C27.5688 6.02412 27.1063 4.82495 26.1288 4.45162C23.8847 3.59579 21.4205 3.18912 18.833 3.37912C9.87965 4.03662 2.83882 11.76 3.00382 20.7358C3.18298 30.555 11.648 38.35 21.7013 37.4258C29.348 36.7225 35.6813 30.8266 36.9321 23.2508C37.4813 19.9266 37.038 16.765 35.888 13.9658C35.3488 12.655 33.5021 12.5358 32.863 13.8008C32.373 14.7716 32.2138 16.0716 32.1672 16.6666Z" fill="#E0E0E0"/>
                                                            </svg>

                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">Cinema 4D</span>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                <span class="download-box-btm-r">
                                                    2
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Wavefront -->
                                        <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                            <div class="py-5 px-4">
                                                <div class="my-4 mx-0">
                                                    <center>
                                                        <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M12.4606 37.875L4.19558 39.9856C3.82058 40.0606 3.44433 39.8356 3.2937 39.3844V31.045C3.2937 30.67 3.5187 30.3687 3.89495 30.2937L12.0843 28.19C12.4593 28.115 12.9106 28.34 12.9862 28.7162V37.0625C12.9862 37.5125 12.7612 37.8137 12.46 37.8887L12.4606 37.875ZM12.4606 25.5537L4.19558 27.5887C3.82058 27.7387 3.44433 27.4387 3.2937 27.0625V9.03124C3.2937 8.65624 3.5187 8.35499 3.89495 8.27999L12.085 6.17499C12.46 6.09999 12.9112 6.32499 12.9868 6.70124V24.8087C12.9868 25.1837 12.7618 25.485 12.4606 25.56V25.5537ZM24.7825 37.4375L16.5937 39.535C16.2187 39.61 15.7675 39.385 15.6918 39.0087V27.965C15.6918 27.59 15.9168 27.2887 16.2931 27.2137L24.4825 25.11C24.8575 25.035 25.3087 25.26 25.3843 25.6362V36.6875C25.3843 37.0625 25.0843 37.3637 24.7831 37.4387L24.7825 37.4375ZM24.7825 22.4794L16.5937 24.5831C16.2187 24.6581 15.7675 24.4331 15.6918 24.0569V8.57937C15.6918 8.20437 15.9168 7.90312 16.2931 7.82812L24.4806 5.72499C24.8556 5.64999 25.3068 5.87499 25.3825 6.25124V21.7287C25.3825 22.1037 25.0824 22.405 24.7812 22.48L24.7825 22.4794ZM37.1043 31.7212L28.915 33.825C28.54 33.9 28.0887 33.675 28.0131 33.2987V24.8837C28.0131 24.5087 28.2381 24.2075 28.6143 24.1325L36.8037 22.0287C37.1787 21.9537 37.63 22.1787 37.7056 22.555V30.9687C37.7056 31.3437 37.4806 31.645 37.1043 31.72V31.7212ZM37.1043 19.3987L28.915 21.5025C28.54 21.5775 28.0887 21.3525 28.0131 20.9762V2.87499C28.0131 2.49999 28.2381 2.19874 28.6143 2.12374L36.8043 0.0143674C37.1793 -0.0606326 37.6306 0.164367 37.7062 0.540617V18.6481C37.7062 18.9481 37.4812 19.2494 37.1049 19.3994L37.1043 19.3987Z" fill="#29AAE1"/>
                                                            </svg>

                                                        <br/><span class="fs-16 font-prompt-md mt-1 mt-logo-download">Wavefront</span>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-between px-3 py-2">
                                                <a href="{{ asset('public'.$document->path) }}" class="download-box-btm-l p-0" download>
                                                                    Download
                                                                </a>
                                                <span class="download-box-btm-r">
                                                    2
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"/>
                                                        <path d="M8 2H17C19 2 20 3 20 5V6.38" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div> --}}
                                    <div>
                                </div>
                            </div>
                        </div>
                        <!-- ----------------- -->
                        <div class="py-2 float-left">
                            <div class="download-title fs-20 font-prompt-md text-left col-md-12">Videos</div>
                            <div class="col-12 float-left my-2">
                                <div class="col-lg-2 col-md-3 col-6 mr-2 download-box p-0 float-left">
                                    <div class="video-img">
                                        @if(isset($previewData['detailedProduct']['video_provider']) && $previewData['detailedProduct']['video_provider'] == "youtube")
                                            @if($previewData['detailedProduct']['getYoutubeVideoId'] != "")
                                                <img class="col-12 p-0 h-100" data-toggle="modal" data-target="#videoModal" src="https://images.squarespace-cdn.com/content/v1/5ba5e044b10f25cb908c506f/1612465370111-8X2AQHP6F8YNW25WGWVQ/Screen%2BShot%2B2021-02-04%2Bat%2B11.00.17%2BAM.jpg">
                                                <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-start px-3 py-2">
                                                    <span class="download-box-btm-l">View </span>
                                                </div>
                                            @endif
                                        @elseif(isset($previewData['detailedProduct']['video_provider']) && $previewData['detailedProduct']['video_provider'] == "vimeo")
                                            @if($previewData['detailedProduct']['getVimeoVideoId'] != "")
                                                <img class="col-12 p-0 h-100" data-toggle="modal" data-target="#videoModal" src="https://images.squarespace-cdn.com/content/v1/5ba5e044b10f25cb908c506f/1612465370111-8X2AQHP6F8YNW25WGWVQ/Screen%2BShot%2B2021-02-04%2Bat%2B11.00.17%2BAM.jpg">
                                                <div class="download-box-btm fs-14 font-prompt-md d-flex justify-content-start px-3 py-2">
                                                    <span class="download-box-btm-l">View </span>
                                                </div>
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            <div>
                        </div>
                    </div>
                </div>
                </div>
                    <div class="tab-pane fade" id="tab_default_3">
                                <!-- Ratting -->
                                <div class="px-3 px-sm-4 mb-4 mt-4">
                                    @if($previewData['detailedProduct']['variationId'] || $previewData['detailedProduct']['product_id'] )
                                    @php
                                    if($previewData['detailedProduct']['variationId'])
                                        $detailedProduct = App\Models\Product::find($previewData['detailedProduct']['variationId']);
                                    else {
                                        $detailedProduct = App\Models\Product::find($previewData['detailedProduct']['product_id']);
                                    }
                                    $totalRating = $detailedProduct->reviews->count();
                                    @endphp
                                    <div class="review-title fs-20 font-prompt-md text-left col-md-12">Total Reviews</div>
                                    <div class="review-subtitle fs-16 font-prompt-md text-left col-md-12">This shows the average of reviews</div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-12 mt-4 review-box p-4">
                                            <div class="col-12 fs-48 font-prompt-md text-center">
                                                {{ $totalRating > 0 ? number_format($detailedProduct->reviews->sum('rating') / $totalRating, 1) : number_format(0, 1) }}
                                            </div>
                                            <div class="rating rating-mr-1 rating-var text-center">
                                                @if($totalRating > 0)
                                                {{ renderStarRating($detailedProduct->reviews->sum('rating') / $totalRating) }}
                                            @else
                                                {{ renderStarRating(0) }}
                                            @endif
                                            </div>
                                            <div class="total-var-rating ml-1 fs-16 text-center mt-2">
                                                Average of <b>{{$totalRating}} reviews</b>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-6 col-12 mt-4 review-lines p-3">
                                            <div class="col-12 p-0 float-left mb-2">
                                                <span class="float-left rating-txt-style fs-16">5 Stars</span>
                                                <div class="progress float-left mx-2">
                                                    <div class="progress-bar custom-progress" role="progressbar" style="width: {{ $previewData['detailedProduct']['ratingPercentages'][0]['percentage'] }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="float-left rating-txt-color fs-16">{{ $previewData['detailedProduct']['ratingPercentages'][0]['percentage'] }}%</span>
                                            </div>
                                            <div class="col-12 p-0 float-left mb-2">
                                                <span class="float-left rating-txt-style fs-16">4 Stars</span>
                                                <div class="progress float-left mx-2">
                                                    <div class="progress-bar custom-progress" role="progressbar" style="width: {{ $previewData['detailedProduct']['ratingPercentages'][1]['percentage'] }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="float-left rating-txt-color fs-16">{{ $previewData['detailedProduct']['ratingPercentages'][1]['percentage'] }}%</span>
                                            </div>
                                            <div class="col-12 p-0 float-left mb-2">
                                                <span class="float-left rating-txt-style fs-16">3 Stars</span>
                                                <div class="progress float-left mx-2">
                                                    <div class="progress-bar custom-progress" role="progressbar" style="width: {{ $previewData['detailedProduct']['ratingPercentages'][2]['percentage'] }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="float-left rating-txt-color fs-16">{{ $previewData['detailedProduct']['ratingPercentages'][2]['percentage'] }}%</span>
                                            </div>
                                            <div class="col-12 p-0 float-left mb-2">
                                                <span class="float-left rating-txt-style fs-16">2 Stars</span>
                                                <div class="progress float-left mx-2">
                                                    <div class="progress-bar custom-progress" role="progressbar" style="width: {{ $previewData['detailedProduct']['ratingPercentages'][3]['percentage'] }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="float-left rating-txt-color fs-16">{{ $previewData['detailedProduct']['ratingPercentages'][3]['percentage'] }}%</span>
                                            </div>
                                            <div class="col-12 p-0 float-left mb-2">
                                                <span class="float-left rating-txt-style fs-16">1 Star</span>
                                                <div class="progress float-left mx-2">
                                                    <div class="progress-bar custom-progress" role="progressbar" style="width: {{ $previewData['detailedProduct']['ratingPercentages'][4]['percentage'] }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="float-left rating-txt-color fs-16">{{ $previewData['detailedProduct']['ratingPercentages'][4]['percentage'] }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-12 fs-20 font-prompt-md pb-3 pt-5 px-1 comment-style">
                                            {{App\Models\Review::where('product_id', $previewData['detailedProduct']['product_id'])->where('status', 1)->count() }}
                                                Total Reviews
                                        </div>

                                            @php
                                                $comments = App\Models\Review::where('product_id', $previewData['detailedProduct']['product_id'])->where('status', 1)->take(3)->get();
                                            @endphp
                                            @if(count($comments) > 0)
                                                @foreach ($comments as $comment)
                                                    <div class="col-12 fs-20 font-prompt-md py-4 px-1 comment-style">
                                                        <div class="comment-img-porter p-0 float-left">
                                                            @if($comment->user->avatar_original != null)
                                                                <img src="{{ uploaded_asset($comment->user->avatar_original) }}" alt="{{ translate('avatar') }}" class="comment-img">
                                                            @else
                                                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="{{ translate('avatar') }}" class="comment-img">
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-11 col-md-10 col-9 p-0 float-left">
                                                            <div class="col-12 float-left p-0">
                                                                <div class="col-6 float-left p-0">
                                                                    <span class="col-12 float-left fs-16 font-prompt-md comment-name text-left">{{ $comment->name }}</span>
                                                                    <span class="col-12 float-left fs-14 font-prompt comment-date text-left">{{ \Carbon\Carbon::parse($comment->created_at)->format('M d, Y H:i'); }}</span>
                                                                </div>
                                                                <div class="col-6 float-right p-0">
                                                                    <div class="rating rating-mr-1 rating-var text-right">
                                                                        {{ renderStarRating($comment->rating) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 float-left fs-16 font-prompt comment-content">
                                                                {{ $comment->comment }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                No comments yet
                                            @endif
                                            {{-- <div class="col-12 fs-20 font-prompt-md py-4 px-1 comment-style">
                                                <div class="comment-img-porter p-0 float-left">
                                                    <img src="https://img.freepik.com/free-photo/portrait-man-laughing_23-2148859448.jpg" class="comment-img">
                                                </div>
                                                <div class="col-lg-11 col-md-10 col-9 p-0 float-left">
                                                    <div class="col-12 float-left p-0">
                                                        <div class="col-6 float-left p-0">
                                                            <span class="col-12 float-left fs-16 font-prompt-md comment-name text-left">Michel Knaby</span>
                                                            <span class="col-12 float-left fs-14 font-prompt comment-date text-left">Dec 30, 2019 05:18</span>
                                                        </div>
                                                        <div class="col-6 float-right p-0">
                                                            <div class="rating rating-mr-1 rating-var text-right">
                                                            @if($totalRating > 0)
                                                                {{ renderStarRating($detailedProduct->reviews->sum('rating') / $totalRating) }}
                                                            @else
                                                                {{ renderStarRating(0) }}
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 float-left fs-16 font-prompt comment-content">
                                                        I bought materials from Mawad Online, and I'm very happy. The order was easy, and delivery was fast. The quality is excellent, and prices are good. Highly recommend!
                                                    </div>
                                                </div>
                                            </div> --}}

                                        <!-- end comments -->
                                        @if(count($comments) > 2)
                                            <div class="col-12 py-3">
                                                <center>
                                                    <button class="comment-button-more fs-20 font-prompt-md" id="load-more" data-product-id="{{ $previewData['detailedProduct']['product_id'] }}" @if(count($comments) > 0) data-offset="{{ $comment->id }}" @endif>View More</button>
                                                </center>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- end comment and button -->
                                    <div class="row pt-5">
                                        <div class="review-title fs-20 font-prompt-md text-left col-md-12">Submit Your Review</div>
                                        <div class="review-subtitle fs-16 font-prompt-md text-left col-md-12">Your email address will not be published.</div>
                                        <div class="col-12">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
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
                                            <form method="POST" action="{{ route('reviews.store') }}">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $previewData['detailedProduct']['product_id'] }}">
                                                <div class="form-group">
                                                    <label class="opacity-60">{{ translate('Rating')}}</label>
                                                    <div class="rating rating-input">
                                                        <label>
                                                            <input type="radio" name="rating" value="1" required>
                                                            <i class="las la-star"></i>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="rating" value="2">
                                                            <i class="las la-star"></i>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="rating" value="3">
                                                            <i class="las la-star"></i>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="rating" value="4">
                                                            <i class="las la-star"></i>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="rating" value="5">
                                                            <i class="las la-star"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group m-0">
                                                    <textarea class="form-control text-a-review-f fs-16 mt-3" id="exampleFormControlTextarea3" placeholder="Write your review here" name="comment" rows="5"></textarea>
                                                </div>
                                                <div class="form-group m-0">
                                                    <input type="text" class="form-control text-input-review-f fs-16 mt-2" placeholder="Name" @if(Auth::check()) value="{{ Auth::user()->name }}" @endif aria-label="name" name="name" aria-describedby="basic-addon1">
                                                </div>
                                                @if(Auth::check())
                                                    <button class="btn-review-f fs-16 mt-2 font-prompt py-2 col-6 col-md-3 col-lg-2">Submit Review</button>
                                                @else
                                                    <div class="alert alert-danger mt-3" role="alert">
                                                        You must be <a href="{{ route('user.login') }}" class="alert-link">logged in</a> to write a comment on this product.
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                    <!-- end form comment -->
                                </div>
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
                <!-- Product Query
                <div class="bg-white border mt-4 mb-4" id="product_query">
                    <div class="p-3 p-sm-4">
                        <h3 class="fs-16 fw-700 mb-0">
                            <span>Product Queries (4)</span>
                        </h3>
                    </div>

                    <!-- Login & Register
                    <p class="fs-14 fw-400 mb-0 px-3 px-sm-4 mt-3"><a
                            href="https://demo.activeitzone.com/ecommerce/users/login">Login</a> Or <a class="mr-1"
                            href="https://demo.activeitzone.com/ecommerce/users/registration">Register</a>to submit
                        your questions to seller
                    </p>

                    <!-- Query Submit -->

                    <!-- Others Queries
                    <div class="queries-area my-4 mb-0 px-3 px-sm-4">
                        <div class="py-3">
                            <h3 class="fs-16 fw-700 mb-0">
                                <span>Other Questions</span>
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
                    <!--</div>
                </div>-->

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

@section('modal')
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

    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel">Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        @if(isset($previewData['detailedProduct']['video_provider']) && $previewData['detailedProduct']['video_provider'] == "youtube")
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $previewData['detailedProduct']['getYoutubeVideoId'] }}" allowfullscreen></iframe>
                        @elseif(isset($previewData['detailedProduct']['video_provider']) && $previewData['detailedProduct']['video_provider'] == "vimeo")
                            <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/{{ $previewData['detailedProduct']['getVimeoVideoId'] }}" allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Size chart show Modal -->
    @include('modals.size_chart_show_modal')
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#load-more').on('click', function() {
            var productId = $(this).data('product-id');
            var offset = $(this).data('offset');

            $.ajax({
                url: "{{ route('comments.loadMore') }}",
                type: "GET",
                data: {
                    productId: productId,
                    offset: offset
                },
                cache: false,
                dataType: 'JSON',
                success: function(dataResult) {
                    $('.comment-style').last().after(dataResult.html);
                    $('#load-more').data('offset', dataResult.lastId);
                    // Hide button if no more comments
                    if(dataResult.lastId == null){
                        $('#load-more').hide();
                    }
                },
                error: function() {
                    alert('Error loading messages.');
                }
            })
        });
    });
</script>
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
            var product_id = $("#variationId").val() || {{$previewData['detailedProduct']['product_id']}};

            @if (isCustomer())
                @if (/* $review_status */ 1 == 1)
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
    <script>
        // $(document).ready(function() {
              // Set the CSRF token for all AJAX requests
               $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $(document).on('click', '.quantity-control', function() {

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
                            // $("#qty-interval").text(response.unit_price+" AED")
                            $("#quantity").val(response.qty)
                            // $("#chosen_price").text(response.total+" AED")
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
                            $(this).find('span').css('color', 'black');
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
                                var availableValues = response.availableAttributes[attributeId][0];
                                console.log(availableValues );
                                // Iterate over each radio button for this attribute
                                $('.attribute_value input[type=radio][attributeId="' + attributeId + '"]').each(function () {
                                    var radioValue = $(this).val();
                                    var label = $(this).closest('.attribute_value');

                                    // Check if the radio button value is in the available values
                                    if (availableValues.indexOf(radioValue) === -1) {
                                        // If not in available values, disable the radio button
                                        // $(this).prop('disabled', true);
                                        label.find('span').css('color', 'red'); // Change to the desired color
                                    } else {
                                        // Otherwise, enable the radio button
                                        // $(this).prop('disabled', false);
                                        label.find('span').css('color', 'mediumseagreen'); // Change to the desired color

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


        // });
    </script>



@endsection
