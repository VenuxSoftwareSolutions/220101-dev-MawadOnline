<div class="modal-body px-4 py-4 c-scrollbar-light">
    <!-- Item added to your cart -->
    <div class="text-success mb-1 d-flex justify-content-start">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9.61914 16L11.1191 17.5L14.3691 14.5" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8.80945 2L5.18945 5.63" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M15.1895 2L18.8095 5.63" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 7.8501C2 6.0001 2.99 5.8501 4.22 5.8501H19.78C21.01 5.8501 22 6.0001 22 7.8501C22 10.0001 21.01 9.8501 19.78 9.8501H4.22C2.99 9.8501 2 10.0001 2 7.8501Z" stroke="#292D32" stroke-width="1.5"/>
            <path d="M3.5 10L4.91 18.64C5.23 20.58 6 22 8.86 22H14.89C18 22 18.46 20.64 18.82 18.76L20.5 10" stroke="#292D32" stroke-width="1.5" stroke-linecap="round"/>
            </svg>

        <span class="fs-20 font-prompt-md ml-2 dark-c3">{{ translate('Item added to your cart!') }}</span>
    </div>
    <hr>

    <!-- Product Info -->
    <div class="media mb-1">
        <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
            data-src="{{ asset('/public/' . $product->getFirstImage()) }}"
            class="mr-3 lazyload size-90px img-fit border-radius-8px" alt="Product Image"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        <div class="media-body mt-2 text-left d-flex flex-column justify-content-between">
            <h6 class="fs-16 font-prompt-md text-truncate-2">
                {{ $product->getTranslation('name') }}
            </h6>
            <div class="row mt-2">
                <div class="col-2 pr-0 fs-16 font-prompt text-secondary">
                    <div>{{ translate('Price') }}</div>
                </div>
                <div class="col-9">
                    <div class="fs-16 font-prompt-sb text-primary">
                        <strong>
                            {{ single_price((isset($samplePrice) ? $samplePrice : cart_product_price($cart, $product, false)) * $cart->quantity) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (get_related_products($product)->count() > 0)
        <!-- Related product -->
        <div class="bg-white shadow-sm">
            <div class="py-3">
                <h3 class="fs-16 fw-700 mb-0 text-dark">
                    <span class="mr-4">{{ translate('Frequently Bought Together') }}</span>
                </h3>
            </div>
            <div class="p-3">
                <div class="aiz-carousel gutters-5 half-outside-arrow" data-items="2" data-xl-items="3"
                    data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'
                    data-infinite='true'>
                    @foreach (get_related_products($product) as $key => $related_product)
                        <div class="carousel-box hov-scale-img hov-shadow-sm">
                            <div class="aiz-card-box my-2 has-transition">
                                <div class="">
                                    <a href="{{ route('product', $related_product->slug) }}" class="d-block">
                                        <img class="img-fit lazyload mx-auto h-140px h-md-200px has-transition"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($related_product->thumbnail_img) }}"
                                            alt="{{ $related_product->getTranslation('name') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                </div>
                                <div class="p-md-3 p-2 text-center">
                                    <h3 class="fw-400 fs-14 text-dark text-truncate-2 lh-1-4 mb-0 h-35px">
                                        <a href="{{ route('product', $related_product->slug) }}"
                                            class="d-block text-reset hov-text-primary">{{ $related_product->getTranslation('name') }}</a>
                                    </h3>
                                    <div class="fs-14 mt-3">
                                        <span
                                            class="fw-700 text-primary">{{ home_discounted_base_price($related_product) }}</span>
                                        @if (home_base_price($related_product) != home_discounted_base_price($related_product))
                                            <del
                                                class="fw-600 opacity-50 ml-1">{{ home_base_price($related_product) }}</del>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Back to shopping & Checkout buttons -->
    <div class="row gutters-5">
        <div class="col-sm-6">
            <button class="btn btn-secondary-base mb-3 mb-sm-0 btn-block rounded-0 text-white"
                data-dismiss="modal">{{ translate('Back to shopping') }}</button>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('cart') }}"
                class="btn btn-primary mb-3 mb-sm-0 btn-block rounded-0">{{ translate('Proceed to Checkout') }}</a>
        </div>

    </div>
</div>
