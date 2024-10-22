@extends('frontend.layouts.app')

@section('meta_title'){{ $shop->meta_title }}@stop

@section('meta_description'){{ $shop->meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $shop->meta_title }}">
    <meta itemprop="description" content="{{ $shop->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($shop->logo) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $shop->meta_title }}">
    <meta name="twitter:description" content="{{ $shop->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($shop->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $shop->meta_title }}" />
    <meta property="og:type" content="Shop" />
    <meta property="og:url" content="{{ route('shop.visit', $shop->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($shop->logo) }}" />
    <meta property="og:description" content="{{ $shop->meta_description }}" />
    <meta property="og:site_name" content="{{ $shop->name }}" />
@endsection

@section('content')

    @php
        $total = 0;
        $rating = 0;
        foreach ($shop->user->products as $key => $seller_product) {
            $total += $seller_product->reviews->count();
            $rating += $seller_product->reviews->sum('rating');
        }
    @endphp

    <section class="py-5 mb-4 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="d-flex justify-content-center">
                        <!-- Shop Logo -->
                        <a href="{{ route('shop.visit', $shop->slug) }}" class="overflow-hidden size-64px rounded-content" style="border: 1px solid #e5e5e5;
                            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                            <img class="lazyload h-64px  mx-auto"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($shop->logo) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </a>
                        <div class="ml-3">
                            <!-- Shop Name & Verification Status -->
                            <a href="{{ route('shop.visit', $shop->slug) }}"
                                class="text-dark d-block fs-16 fw-700">
                                {{ $shop->name }}
                            </a>
                            <!-- Ratting -->
                            <div class="rating rating-mr-1 text-dark">
                                {{ renderStarRating($shop->rating) }}
                                <span class="opacity-60 fs-12">({{ $shop->num_of_reviews }}
                                    {{ translate('Reviews') }})</span>
                            </div>
                            <!-- Address -->
                            <div class="location fs-12 opacity-70 text-dark mt-1">{{ $shop->address }}</div>
                            <!-- Member Since -->
                            <div class="mt-3">
                                <div class="fs-10 fw-400 text-secondary">{{ translate('Member Since') }}</div>
                                <div class="fs-16 fw-700 text-secondary">{{ date('d M Y',strtotime($shop->created_at)) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xxl-5 col-xl-6 col-md-8 mx-auto">
                    <div class="bg-white border p-4 text-center">
                        <h3 class="fw-600 fs-20">
                            {{$shop->user->name}} {{ translate('has not been verified yet.')}}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
