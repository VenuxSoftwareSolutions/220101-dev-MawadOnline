<div class="text-left col-md-6 float-left">
    <!-- Product Name -->
    <div class="row">
        <div class="col-6">
            <h2 class="mt-1 fs-24 fw-700 text-dark font-prompt-sb">
                {{ $previewData['detailedProduct']['name'] }}
            </h2>
        </div>
        {{-- @php
        $detailedProduct = App\Models\Product::find(5) ;
        @endphp --}}

        @if($previewData['detailedProduct']['variationId'] || $previewData['detailedProduct']['product_id'] || isset($previewData['detailedProduct']['previewCreate']))
            @php
                if(!isset($previewData['detailedProduct']['previewCreate'])) {
                    if($previewData['detailedProduct']['variationId'])
                        $detailedProduct = App\Models\Product::find($previewData['detailedProduct']['variationId']);
                    else {
                        $detailedProduct = App\Models\Product::find($previewData['detailedProduct']['product_id']);
                    }
                    $totalRating = $detailedProduct->reviews->count();
                }

            @endphp
            @if (isset($detailedProduct) && $detailedProduct->digital == 1)
            <div class="col-6 d-flex justify-content-end align-items-center">
                <span class="badge badge-md badge-inline badge-pill badge-success-light fs-14 font-prompt-md border-radius-8px in-stock-style">{{ translate('In stock') }}</span>
            </div>
            @endif
    </div>
    <!-- Short Description -->
    <div class="row col-md-12 fs-16 font-prompt">
        <!--{!! $previewData['detailedProduct']['short_description'] !!}-->

        <div>
            @php
                // Get the first 140 characters of the description
                $shortDescription = Str::limit($previewData['detailedProduct']['short_description'], 85);
            @endphp

            <!-- Short description -->
            <p id="shortDescription">
                {!! $shortDescription !!}<span class="seemorebtn" onclick="toggleDescription()">View more</span>
            </p>

            <!-- Full description (hidden initially) -->
            <p id="fullDescription" style="display: none;">
                {!! $previewData['detailedProduct']['short_description'] !!} <span class="seemorebtn" onclick="toggleDescription()">View less</span>
            </p>

            <!-- Toggle button
            <div style="margin-top: 10px;">
                <button id="seeMoreBtn" onclick="toggleDescription()">See More</button>
            </div>-->
        </div>

        <script>
            function toggleDescription() {
                var shortDesc = document.getElementById("shortDescription");
                var fullDesc = document.getElementById("fullDescription");
                if (shortDesc.style.display === "none") {
                    shortDesc.style.display = "block";
                    fullDesc.style.display = "none";
                } else {
                    shortDesc.style.display = "none";
                    fullDesc.style.display = "block";
                }
            }
        </script>


    </div>
    <!-- Price -->
    <div class="row no-gutters mb-2">
        <div class="col-sm-10">
            <div class="d-flex align-items-center">

                <!-- Discount Price -->
                <strong id="qty-interval" class="fs-24 fw-700 text-dark font-prompt-sb">
                    @if (isset($previewData['detailedProduct']['discountedPrice']))
                     {{ $previewData['detailedProduct']['discountedPrice'] }} AED
                    @else
                     {{ $previewData['detailedProduct']['price'] }} AED / {{ @$previewData['detailedProduct']['unit_of_sale'] }}
                    @endif

                </strong>

                  <!-- Home Price -->
                  <del id="previous-price" class="fs-24 opacity-60 ml-2 text-secondary">
                    @if (isset($previewData['detailedProduct']['discountedPrice']))
                  {{$previewData['detailedProduct']['price']}} AED
                  @endif
                </del>
                <!-- Unit -->
                 <!-- Discount percentage
                <span id="percent" class="@if ($previewData['detailedProduct']['percent']> 0) bg-primary @endif ml-2 fs-11 fw-700 text-white w-35px text-center p-1"
                    style="padding-top:2px;padding-bottom:2px;">
                    @if ($previewData['detailedProduct']['percent']> 0)
                        -{{$previewData['detailedProduct']['percent']}}%
                    @endif

                </span>
                -->
                <!-- Club Point -->
                </div>
                <!-- Home Price -->
                {{-- <del class="fs-14 opacity-60 ml-2">
                    $90.000
                </del>
                <!-- Unit -->
                <span class="opacity-70 ml-1">/pc</span>
                <!-- Discount percentage -->
                <span class="bg-primary ml-2 fs-11 fw-700 text-white w-35px text-center p-1"
                    style="padding-top:2px;padding-bottom:2px;">-20%</span>
                <!-- Club Point -->
                <div class="ml-2 bg-secondary-base d-flex justify-content-center align-items-center px-3 py-1"
                    style="width: fit-content;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6" r="6"
                                transform="translate(973 633)" fill="#fff"></circle>
                            <g id="Group_23920" data-name="Group 23920" transform="translate(973 633)">
                                <path id="Path_28698" data-name="Path 28698" d="M7.667,3H4.333L3,5,6,9,9,5Z"
                                    transform="translate(0 0)" fill="#f3af3d"></path>
                                <path id="Path_28699" data-name="Path 28699" d="M5.33,3h-1L3,5,6,9,4.331,5Z"
                                    transform="translate(0 0)" fill="#f3af3d" opacity="0.5"></path>
                                <path id="Path_28700" data-name="Path 28700" d="M12.666,3h1L15,5,12,9l1.664-4Z"
                                    transform="translate(-5.995 0)" fill="#f3af3d"></path>
                            </g>
                        </g>
                    </svg>
                    <small class="fs-11 fw-500 text-white ml-2">Club Point:
                        450</small>
                </div> --}}
            </div>
        </div>
    <div class="row align-items-center mb-3">
        <!-- Review -->
        {{-- @if ($detailedProduct->auction_product != 1) --}}
        <div class="col-12">



            <span class="rating rating-mr-1 rating-var">
                @if(isset($totalRating) && $totalRating > 0)
                    {{ renderStarRating($detailedProduct->reviews->sum('rating') / $totalRating) }}
                @else
                    {{ renderStarRating(0) }} <!-- Assuming 0 stars when there are no reviews -->
                @endif
            </span>
            <span class="total-var-rating ml-1 fs-16 font-prompt-md rating-style">({{ $totalRating ?? "0" }} {{ translate('reviews') }})</span>
         @else
            <span class="rating rating-mr-1 rating-var fs-16 font-prompt-md rating-style">
                {{ renderStarRating(0) }}


            </span>
            <span class="total-var-rating ml-1 fs-16 font-prompt-md rating-style">(0
                {{ translate('reviews') }})</span>

            @endif

        </div>
        <hr>
        {{-- @endif --}}
        <!-- Estimate Shipping Time -->
        {{-- @if ($detailedProduct->est_shipping_days)
            <div class="col-auto fs-14 mt-1">
                <small class="mr-1 opacity-50 fs-14">{{ translate('Estimate Shipping Time') }}:</small>
                <span class="fw-500">{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }}</span>
            </div>
        @endif
        <!-- In stock -->
        @if ($detailedProduct->digital == 1)
            <div class="col-12 mt-1">
                <span class="badge badge-md badge-inline badge-pill badge-success">{{ translate('In stock') }}</span>
            </div>
        @endif --}}
    </div>
<!-- new commented
    <div class="row mb-3">
        <div class="col-6">
            <span class="text-secondary fs-14 fw-400 mr-4 w-50px">Sold by: </span> <a href="#" class="text-reset hov-text-primary fs-14 fw-700">{{ $previewData['detailedProduct']['shop_name'] }}</a>
        </div>
    </div> -->


    {{-- <div class="row align-items-center mb-3">
        <!-- Review -->
       @if ($detailedProduct->auction_product != 1)
        <div class="col-12">
             @php
                    $total = 0;
                    $total += $detailedProduct->reviews->count();
                @endphp
            <span class="rating rating-mr-1">
                0
            </span>
            <span class="ml-1 opacity-50 fs-14">(0
                {{ translate('reviews') }})</span>
        </div>
         @endif
        <!-- Estimate Shipping Time -->
        @if ($detailedProduct->est_shipping_days)
            <div class="col-auto fs-14 mt-1">
                <small class="mr-1 opacity-50 fs-14">{{ translate('Estimate Shipping Time') }}:</small>
                <span class="fw-500">{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }}</span>
            </div>
        @endif
        <!-- In stock -->
        @if ($detailedProduct->digital == 1)
            <div class="col-12 mt-1">
                <span class="badge badge-md badge-inline badge-pill badge-success">{{ translate('In stock') }}</span>
            </div>
        @endif
    </div> --}}

    <hr class="hr-style"/>
    <!-- Category -->
    <div class="col-md-12 p-0 pb-2">
        <div class="product-desc-each">
            <span class="fs-16 font-prompt-md">Category:</span>
            <span class="fs-16 font-prompt">{{ $previewData['detailedProduct']['category'] }}</span>
        </div>
    </div>
    <!-- Category -->
    <div class="col-md-12 p-0 pb-2">
        <div class="product-desc-each">
            <span class="fs-16 font-prompt-md">SKU:</span>
            <span class="fs-16 font-prompt">{{ $previewData['detailedProduct']['sku'] }}</span>
        </div>
    </div>
    <!-- Unit of Sale -->
    <div class="col-md-12 p-0 pb-2">
        <div class="product-desc-each">
            <span class="fs-16 font-prompt-md">Unit of Sale:</span>
            <span class="fs-16 font-prompt">{{ $previewData['detailedProduct']['unit_of_sale'] }}</span>
        </div>
    </div>

    <!-- Category -->
    <div class="col-md-12 p-0 pb-2">
        <div class="product-desc-each">
            <span class="fs-16 font-prompt-md">Tags:</span>
            <span class="fs-16 font-prompt">
                @if(is_array($previewData['detailedProduct']['tags']))
                @foreach($previewData['detailedProduct']['tags'] as $tag)
                @php
                    $decodedTag = json_decode($tag, true);
                @endphp
                @if(is_array($decodedTag))
                    @foreach($decodedTag as $item)
                        @if(isset($item['value']))
                            {{ $item['value'] }}
                        @endif
                    @endforeach
                @else
                    --
                @endif
            @endforeach
            @elseif(!empty($previewData['detailedProduct']['tags']))
                {{ $previewData['detailedProduct']['tags'] }}
            @else
                {{ __('No tags available') }}
            @endif
            </span>
        </div>
        <hr class="hr-style"/>
    </div>
    @if (isset($detailedProduct) &&  $detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
    <div class="row no-gutters mb-3">
        <div class="col-2">
            <div class="fs-16 font-prompt-md mt-2">{{ translate('Color') }}:</div>
        </div>
        <div class="col-10 pl-2">
            <div class="aiz-radio-inline"><!--
                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                    <label class="aiz-megabox pl-0 mr-1 mb-0" data-toggle="tooltip"
                        data-title="{{ get_single_color_name($color) }}">
                        <input type="radio" name="color"
                            value="{{ get_single_color_name($color) }}"
                            @if ($key == 0) checked @endif>
                        <span
                            class="aiz-megabox-elem d-flex align-items-center justify-content-center p-1">
                            <span class="d-inline-block product-color-style"
                                style="background: {{ $color }};">
                                <svg width="24" height="24" class="m-1 checked-color" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.75 11.9999L10.58 14.8299L16.25 9.16992" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                            </span>
                        </span>
                    </label>
                @endforeach-->
                @foreach ($previewData['detailedProduct']['general_attributes'] as $key => $general_attribute)
                @php
                    $attribue_general = App\Models\Attribute::find($key) ;
                @endphp
                 @if (is_array($general_attribute))
                 @foreach ( $general_attribute as $color )
                   @if ((preg_match('/^#[0-9A-F]{6}$/i', $color)))
                   <li style="list-style: none; overflow-wrap: break-word; margin: 0px 0px 5.5px;">
                       <span  style="font-weight: 700 !important;" class="a-list-item ">Color :</span>
                       <span class="color-preview" style="display: inline-block; width: 20px; height: 20px; background-color: {{$color}};"></span>
                   </li>
                   @endif
                 @endforeach

                 @elseif (preg_match('/^#[0-9A-F]{6}$/i', $general_attribute))
                    <label class="aiz-megabox pl-0 mr-1 mb-0" data-toggle="tooltip"
                        data-title="{{ get_single_color_name($color) }}">
                        <input type="radio" name="color"
                            value="{{ get_single_color_name($color) }}"
                            @if ($key == 0) checked @endif>
                        <span
                            class="aiz-megabox-elem d-flex align-items-center justify-content-center p-1">
                            <span class="d-inline-block product-color-style"
                                style="background: {{$general_attribute}};">
                                <svg width="24" height="24" class="m-1 checked-color" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.75 11.9999L10.58 14.8299L16.25 9.16992" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                            </span>
                        </span>
                    </label>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif

    <!--
    <div class="col-md-12 p-0 pb-2 pt-1" style="height: 45px;">
        <span class="fs-16 font-prompt-md float-left mr-5" style="height: 45px;line-height:38px;">Size:</span>
        <div class="border border-radius-8px font-prompt-md fs-14 float-left size-style mr-2">Small</div>
        <div class="border border-radius-8px font-prompt-md fs-14 float-left size-style mr-2">Medium</div>
        <div class="border border-radius-8px font-prompt-md fs-14 float-left size-style mr-2">Large</div>
    </div>
    -->
    <hr class="hr-style"/>
    <!-- new commented
    <div class="row align-items-center">
        {{-- @if (get_setting('product_query_activation') == 1) --}}
        <!-- Ask about this product
        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 mb-3">
            <a href="javascript:void();" onclick="goToView('product_query')" class="text-primary fs-14 fw-600 d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
                    <g id="Group_25571" data-name="Group 25571" transform="translate(-975 -411)">
                        <g id="Path_32843" data-name="Path 32843" transform="translate(975 411)" fill="#fff">
                            <path
                                d="M 16 31 C 11.9933500289917 31 8.226519584655762 29.43972969055176 5.393400192260742 26.60659980773926 C 2.560270071029663 23.77347946166992 1 20.00665092468262 1 16 C 1 11.9933500289917 2.560270071029663 8.226519584655762 5.393400192260742 5.393400192260742 C 8.226519584655762 2.560270071029663 11.9933500289917 1 16 1 C 20.00665092468262 1 23.77347946166992 2.560270071029663 26.60659980773926 5.393400192260742 C 29.43972969055176 8.226519584655762 31 11.9933500289917 31 16 C 31 20.00665092468262 29.43972969055176 23.77347946166992 26.60659980773926 26.60659980773926 C 23.77347946166992 29.43972969055176 20.00665092468262 31 16 31 Z"
                                stroke="none" />
                            <path
                                d="M 16 2 C 12.26045989990234 2 8.744749069213867 3.456249237060547 6.100500106811523 6.100500106811523 C 3.456249237060547 8.744749069213867 2 12.26045989990234 2 16 C 2 19.73954010009766 3.456249237060547 23.2552490234375 6.100500106811523 25.89949989318848 C 8.744749069213867 28.54375076293945 12.26045989990234 30 16 30 C 19.73954010009766 30 23.2552490234375 28.54375076293945 25.89949989318848 25.89949989318848 C 28.54375076293945 23.2552490234375 30 19.73954010009766 30 16 C 30 12.26045989990234 28.54375076293945 8.744749069213867 25.89949989318848 6.100500106811523 C 23.2552490234375 3.456249237060547 19.73954010009766 2 16 2 M 16 0 C 24.8365592956543 0 32 7.163440704345703 32 16 C 32 24.8365592956543 24.8365592956543 32 16 32 C 7.163440704345703 32 0 24.8365592956543 0 16 C 0 7.163440704345703 7.163440704345703 0 16 0 Z"
                                stroke="none" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                        </g>
                        <path id="Path_32842" data-name="Path 32842"
                            d="M28.738,30.935a1.185,1.185,0,0,1-1.185-1.185,3.964,3.964,0,0,1,.942-2.613c.089-.095.213-.207.361-.344.735-.658,2.252-2.032,2.252-3.555a2.228,2.228,0,0,0-2.37-2.37,2.228,2.228,0,0,0-2.37,2.37,1.185,1.185,0,1,1-2.37,0,4.592,4.592,0,0,1,4.74-4.74,4.592,4.592,0,0,1,4.74,4.74c0,2.577-2.044,4.432-3.028,5.333l-.284.255a1.89,1.89,0,0,0-.243.948A1.185,1.185,0,0,1,28.738,30.935Zm0,3.561a1.185,1.185,0,0,1-.835-2.026,1.226,1.226,0,0,1,1.671,0,1.061,1.061,0,0,1,.148.184,1.345,1.345,0,0,1,.113.2,1.41,1.41,0,0,1,.065.225,1.138,1.138,0,0,1,0,.462,1.338,1.338,0,0,1-.065.219,1.185,1.185,0,0,1-.113.207,1.06,1.06,0,0,1-.148.184A1.185,1.185,0,0,1,28.738,34.5Z"
                            transform="translate(962.004 400.504)"
                            fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                    </g>
                </svg>
                <span class="ml-2 text-primary animate-underline-blue">{{ translate('Product Inquiry') }}</span>
            </a>
        </div>
        {{-- @endif --}}
        <div class="col mb-3">
            {{-- @if ($detailedProduct->auction_product != 1) --}}
            <div class="d-flex">
                <!-- Add to wishlist button
                <a href="javascript:void(0)"
                    class="mr-3 fs-14 text-dark opacity-60 has-transitiuon hov-opacity-100">
                    <i class="la la-heart-o mr-1"></i>
                    {{ translate('Add to Wishlist') }}
                </a>
                <!-- Add to compare button
                <a href="javascript:void(0)"
                    class="fs-14 text-dark opacity-60 has-transitiuon hov-opacity-100">
                    <i class="las la-sync mr-1"></i>
                    {{ translate('Add to Compare') }}
                </a>
            </div>
            {{-- @endif --}}
        </div>
    </div>
    <!--
    <!-- new commented
    <!-- Brand Logo & Name
    @if ($previewData['detailedProduct']['brand'])
        <div class="d-flex flex-wrap align-items-center mb-3">
            <span class="text-secondary fs-14 fw-400 mr-4 w-50px">{{ translate('Brand') }}</span><br>
            <a href="{{-- {{ route('products.brand', $detailedProduct->brand->slug) }} --}}"
                class="text-reset hov-text-primary fs-14 fw-700">{{ $previewData['detailedProduct']['brand'] }}</a>
        </div>
    @endif
        -->
    <!-- Seller Info -->
    {{-- <div class="d-flex flex-wrap align-items-center">
        <div class="d-flex align-items-center mr-4">
            <!-- Shop Name -->
            @if ($detailedProduct->added_by == 'seller' && get_setting('vendor_system_activation') == 1)
                <span class="text-secondary fs-14 fw-400 mr-4 w-50px">{{ translate('Sold by') }}</span>
                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                    class="text-reset hov-text-primary fs-14 fw-700">{{ $detailedProduct->user->shop->name }}</a>
            @else
                <p class="mb-0 fs-14 fw-700">{{ translate('Inhouse product') }}</p>
            @endif
        </div>
        <!-- Messase to seller -->
        @if (get_setting('conversation_system') == 1)
            <div class="">
                <button class="btn btn-sm btn-soft-secondary-base btn-outline-secondary-base hov-svg-white hov-text-white rounded-4"
                    onclick="show_chat_modal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                        class="mr-2 has-transition">
                        <g id="Group_23918" data-name="Group 23918" transform="translate(1053.151 256.688)">
                            <path id="Path_3012" data-name="Path 3012"
                                d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                transform="translate(-1178 -341)" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            <path id="Path_3013" data-name="Path 3013"
                                d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                transform="translate(-1182 -337)" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            <path id="Path_3014" data-name="Path 3014"
                                d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                transform="translate(-1181 -343.5)" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            <path id="Path_3015" data-name="Path 3015"
                                d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                transform="translate(-1181 -346.5)" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                        </g>
                    </svg>

                    {{ translate('Message Seller') }}
                </button>
            </div>
        @endif
        <!-- Size guide -->
        @php
            $sizeChartId = ($detailedProduct->main_category && $detailedProduct->main_category->sizeChart) ? $detailedProduct->main_category->sizeChart->id : 0;
            $sizeChartName = ($detailedProduct->main_category && $detailedProduct->main_category->sizeChart) ? $detailedProduct->main_category->sizeChart->name : null;
        @endphp
        <div class=" ml-4">
            <a href="javascript:void(1);" onclick="showSizeChartDetail({{ $sizeChartId }}, '{{ $sizeChartName }}')" class="animate-underline-primary">{{ translate('Show size guide') }}</a>
        </div>
    </div> --}}
    <!-- new commented
    <div class="row no-gutters mb-3">
        <div class="col-sm-2">
            <div class="text-secondary fs-14 fw-400">Price</div>
        </div>
        <div class="col-sm-10">
            <div class="d-flex align-items-center">

                <!-- Discount Price
                <strong id="qty-interval" class="fs-16 fw-700 text-primary">
                    @if (isset($previewData['detailedProduct']['discountedPrice']))
                    {{ $previewData['detailedProduct']['discountedPrice'] }} AED
                    @else
                    {{ $previewData['detailedProduct']['price'] }} AED
                    @endif

                </strong>

                  <!-- Home Price
                  <del id="previous-price" class="fs-14 opacity-60 ml-2">
                    @if (isset($previewData['detailedProduct']['discountedPrice']))
                  {{$previewData['detailedProduct']['price']}} AED
                  @endif
                </del>
                <!-- Unit
               <span class="opacity-70 ml-1">/pc</span>
                 <!-- Discount percentage
                <span id="percent" class="@if ($previewData['detailedProduct']['percent']> 0) bg-primary @endif ml-2 fs-11 fw-700 text-white w-35px text-center p-1"
                    style="padding-top:2px;padding-bottom:2px;">
                    @if ($previewData['detailedProduct']['percent']> 0)
                        -{{$previewData['detailedProduct']['percent']}}%
                    @endif

                </span>

                <!-- Club Point
                </div>
                <!-- Home Price
                {{-- <del class="fs-14 opacity-60 ml-2">
                    $90.000
                </del>
                <!-- Unit
                <span class="opacity-70 ml-1">/pc</span>
                <!-- Discount percentage -->
                <span class="bg-primary ml-2 fs-11 fw-700 text-white w-35px text-center p-1"
                    style="padding-top:2px;padding-bottom:2px;">-20%</span>
                <!-- Club Point -->
                <div class="ml-2 bg-secondary-base d-flex justify-content-center align-items-center px-3 py-1"
                    style="width: fit-content;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6" r="6"
                                transform="translate(973 633)" fill="#fff"></circle>
                            <g id="Group_23920" data-name="Group 23920" transform="translate(973 633)">
                                <path id="Path_28698" data-name="Path 28698" d="M7.667,3H4.333L3,5,6,9,9,5Z"
                                    transform="translate(0 0)" fill="#f3af3d"></path>
                                <path id="Path_28699" data-name="Path 28699" d="M5.33,3h-1L3,5,6,9,4.331,5Z"
                                    transform="translate(0 0)" fill="#f3af3d" opacity="0.5"></path>
                                <path id="Path_28700" data-name="Path 28700" d="M12.666,3h1L15,5,12,9l1.664-4Z"
                                    transform="translate(-5.995 0)" fill="#f3af3d"></path>
                            </g>
                        </g>
                    </svg>
                    <small class="fs-11 fw-500 text-white ml-2">Club Point:
                        450</small>
                </div> --}}
            </div>
        </div>
    -->
    <form id="option-choice-form-preview">
        {{-- <input type="hidden" name="_token" value="4q2wUsXR8Psahk2hhNrRCBY6rAnlDDtK17T5izTc"> <input type="hidden"
            name="id" value="3"> --}}
        @csrf
        <!-- Choice Options -->

        <!-- Color Options -->

        <!-- Quantity + Add to cart -->
        <div class="row no-gutters mb-3 col-md-12 p-0">
            <div class="col-md-4">
                <div class="product-quantity d-flex align-items-center pr-0 pr-md-3">
                    <div class="row no-gutters align-items-center aiz-plus-minus mr-0 mr-md-2 product-quantity-counter col-md-12 p-0 ">
                        <button class="btn col-auto btn-icon btn-sm btn-light rounded-0 quantity-control fs-16 font-prompt-md product-quantity-btn" type="button"
                            data-type="minus" data-field="quantity" disabled="disabled">
                            <i class="las la-minus"></i>
                        </button>
                        <input readonly type="number" id="quantity" name="quantity"
                            class="col border-0 text-center flex-grow-1 fs-16 input-number fs-16 font-prompt-md" placeholder="1"
                            value="{{ $previewData['detailedProduct']['quantity'] }}" min="{{ $previewData['detailedProduct']['min'] }}" max="{{ $previewData['detailedProduct']['max'] }}"
                            lang="en">
                        <button class="btn col-auto btn-icon btn-sm btn-light rounded-0 quantity-control fs-16 font-prompt-md product-quantity-btn" type="button"
                            data-type="plus" data-field="quantity">
                            <i class="las la-plus"></i>
                        </button>
                        <input type="hidden" value="{{$previewData['detailedProduct']['variationId'] ?? $previewData['detailedProduct']['product_id']}}" name="variationId" id="variationId">

                    </div>
                    <div class="avialable-amount opacity-60">
                        {{-- (<span id="available-quantity">197</span>
                        available) --}}

                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary-base add-to-cart col-10 col-md-6 text-white border-radius-16 fs-20 font-prompt py-2 mt-3 mt-md-0"
          {{--   @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif --}} @if (isset($isPreview) && $isPreview) onclick="addToCart({{ json_encode($isPreview) }})" @else onclick="addToCart()" @endif>
                <svg width="29" height="32" viewBox="0 0 29 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="path-1-inside-1_5065_4531" fill="white">
                <path d="M5.84805 19.728C4.43037 19.9956 3.15047 20.7495 2.22898 21.8596C1.30749 22.9696 0.802144 24.3664 0.800049 25.8091C0.801864 27.4505 1.4547 29.0241 2.61531 30.1847C3.77593 31.3454 5.34955 31.9982 6.99091 32C8.43171 31.9976 9.82668 31.4934 10.936 30.574C12.0454 29.6547 12.7999 28.3776 13.0698 26.9623L27.7555 26.952C28.0586 26.952 28.3493 26.8316 28.5636 26.6173C28.7779 26.4029 28.8983 26.1122 28.8983 25.8091C28.8983 25.506 28.7779 25.2153 28.5636 25.001C28.3493 24.7867 28.0586 24.6663 27.7555 24.6663H24.7509V11.4869C24.7509 9.59657 23.2126 8.05829 21.3223 8.05829H8.13376V5.61714C8.13225 4.12765 7.5398 2.69961 6.48646 1.64649C5.43313 0.593367 4.00497 0.0012102 2.51548 0C2.21237 0 1.92168 0.120408 1.70736 0.334735C1.49303 0.549062 1.37262 0.839753 1.37262 1.14286C1.37262 1.44596 1.49303 1.73665 1.70736 1.95098C1.92168 2.16531 2.21237 2.28571 2.51548 2.28571C3.39895 2.28632 4.24607 2.63747 4.87089 3.26207C5.4957 3.88667 5.84714 4.73367 5.84805 5.61714V19.728ZM6.99091 29.7143C4.83662 29.7143 3.08576 27.9623 3.08576 25.8091C3.08576 23.656 4.83662 21.904 6.99091 21.904C9.14519 21.904 10.896 23.656 10.896 25.8091C10.896 27.9623 9.14405 29.7143 6.99091 29.7143ZM13.9932 10.344V13.4754C13.9932 13.7785 14.1136 14.0692 14.3279 14.2836C14.5423 14.4979 14.8329 14.6183 15.136 14.6183C15.4392 14.6183 15.7298 14.4979 15.9442 14.2836C16.1585 14.0692 16.2789 13.7785 16.2789 13.4754V10.344H21.3223C21.952 10.344 22.4652 10.8571 22.4652 11.4869V24.6663H13.0709C12.8387 23.4428 12.2436 22.3176 11.3631 21.437C10.4825 20.5564 9.35724 19.9613 8.13376 19.7291V10.3429L13.9932 10.344Z"/>
                </mask>
                <path d="M5.84805 19.728C4.43037 19.9956 3.15047 20.7495 2.22898 21.8596C1.30749 22.9696 0.802144 24.3664 0.800049 25.8091C0.801864 27.4505 1.4547 29.0241 2.61531 30.1847C3.77593 31.3454 5.34955 31.9982 6.99091 32C8.43171 31.9976 9.82668 31.4934 10.936 30.574C12.0454 29.6547 12.7999 28.3776 13.0698 26.9623L27.7555 26.952C28.0586 26.952 28.3493 26.8316 28.5636 26.6173C28.7779 26.4029 28.8983 26.1122 28.8983 25.8091C28.8983 25.506 28.7779 25.2153 28.5636 25.001C28.3493 24.7867 28.0586 24.6663 27.7555 24.6663H24.7509V11.4869C24.7509 9.59657 23.2126 8.05829 21.3223 8.05829H8.13376V5.61714C8.13225 4.12765 7.5398 2.69961 6.48646 1.64649C5.43313 0.593367 4.00497 0.0012102 2.51548 0C2.21237 0 1.92168 0.120408 1.70736 0.334735C1.49303 0.549062 1.37262 0.839753 1.37262 1.14286C1.37262 1.44596 1.49303 1.73665 1.70736 1.95098C1.92168 2.16531 2.21237 2.28571 2.51548 2.28571C3.39895 2.28632 4.24607 2.63747 4.87089 3.26207C5.4957 3.88667 5.84714 4.73367 5.84805 5.61714V19.728ZM6.99091 29.7143C4.83662 29.7143 3.08576 27.9623 3.08576 25.8091C3.08576 23.656 4.83662 21.904 6.99091 21.904C9.14519 21.904 10.896 23.656 10.896 25.8091C10.896 27.9623 9.14405 29.7143 6.99091 29.7143ZM13.9932 10.344V13.4754C13.9932 13.7785 14.1136 14.0692 14.3279 14.2836C14.5423 14.4979 14.8329 14.6183 15.136 14.6183C15.4392 14.6183 15.7298 14.4979 15.9442 14.2836C16.1585 14.0692 16.2789 13.7785 16.2789 13.4754V10.344H21.3223C21.952 10.344 22.4652 10.8571 22.4652 11.4869V24.6663H13.0709C12.8387 23.4428 12.2436 22.3176 11.3631 21.437C10.4825 20.5564 9.35724 19.9613 8.13376 19.7291V10.3429L13.9932 10.344Z" fill="#F3F4F5"/>
                <path d="M5.84805 19.728L8.39212 33.2043L19.5623 31.0955V19.728H5.84805ZM0.800049 25.8091L-12.9142 25.7892L-12.9142 25.8068L-12.9142 25.8243L0.800049 25.8091ZM6.99091 32L6.97574 45.7143L6.9947 45.7143L7.01365 45.7143L6.99091 32ZM13.0698 26.9623L13.0602 13.248L1.72202 13.2559L-0.40178 24.3934L13.0698 26.9623ZM27.7555 26.952V13.2377L27.7459 13.2377L27.7555 26.952ZM24.7509 24.6663H11.0366V38.3806H24.7509V24.6663ZM8.13376 8.05829H-5.58052V21.7726H8.13376V8.05829ZM8.13376 5.61714H21.8481L21.848 5.60321L8.13376 5.61714ZM2.51548 0L2.52662 -13.7143H2.51548V0ZM2.51548 2.28571L2.52487 -11.4286H2.51548V2.28571ZM5.84805 5.61714H19.5623L19.5623 5.60305L5.84805 5.61714ZM13.9932 10.344H27.7075V-3.36761L13.9959 -3.37028L13.9932 10.344ZM16.2789 10.344V-3.37028H2.56462V10.344H16.2789ZM22.4652 24.6663V38.3806H36.1795V24.6663H22.4652ZM13.0709 24.6663L-0.402904 27.2233L1.71445 38.3806H13.0709V24.6663ZM8.13376 19.7291H-5.58052V31.0856L5.5768 33.203L8.13376 19.7291ZM8.13376 10.3429L8.13644 -3.37143L-5.58052 -3.3741V10.3429H8.13376ZM3.30398 6.25175C-1.25074 7.1116 -5.36279 9.53351 -8.32335 13.1L12.7813 30.6191C11.6637 31.9654 10.1115 32.8797 8.39212 33.2043L3.30398 6.25175ZM-8.32335 13.1C-11.2839 16.6665 -12.9075 21.1541 -12.9142 25.7892L14.5143 25.8291C14.5118 27.5788 13.8989 29.2728 12.7813 30.6191L-8.32335 13.1ZM-12.9142 25.8243C-12.9084 31.0977 -10.811 36.1534 -7.08215 39.8822L12.3128 20.4873C13.7204 21.8949 14.5121 23.8033 14.5143 25.794L-12.9142 25.8243ZM-7.08215 39.8822C-3.35332 43.611 1.70239 45.7084 6.97574 45.7143L7.00607 18.2857C8.99671 18.2879 10.9052 19.0797 12.3128 20.4873L-7.08215 39.8822ZM7.01365 45.7143C11.6421 45.7066 16.1233 44.0869 19.687 41.1335L2.18511 20.0146C3.5301 18.8999 5.22135 18.2886 6.96816 18.2857L7.01365 45.7143ZM19.687 41.1335C23.2507 38.1802 25.6743 34.0777 26.5413 29.5312L-0.40178 24.3934C-0.0745777 22.6775 0.840124 21.1292 2.18511 20.0146L19.687 41.1335ZM13.0794 40.6766L27.7651 40.6663L27.7459 13.2377L13.0602 13.248L13.0794 40.6766ZM27.7555 40.6663C31.6958 40.6663 35.4748 39.101 38.2611 36.3147L18.8661 16.9198C21.2238 14.5622 24.4214 13.2377 27.7555 13.2377V40.6663ZM38.2611 36.3147C41.0473 33.5285 42.6126 29.7495 42.6126 25.8091H15.184C15.184 22.475 16.5085 19.2774 18.8661 16.9198L38.2611 36.3147ZM42.6126 25.8091C42.6126 21.8688 41.0473 18.0898 38.2611 15.3036L18.8661 34.6985C16.5085 32.3409 15.184 29.1433 15.184 25.8091H42.6126ZM38.2611 15.3036C35.4748 12.5173 31.6958 10.952 27.7555 10.952V38.3806C24.4214 38.3806 21.2238 37.0561 18.8661 34.6985L38.2611 15.3036ZM27.7555 10.952H24.7509V38.3806H27.7555V10.952ZM38.4652 24.6663V11.4869H11.0366V24.6663H38.4652ZM38.4652 11.4869C38.4652 2.02238 30.7868 -5.656 21.3223 -5.656V21.7726C15.6384 21.7726 11.0366 17.1708 11.0366 11.4869H38.4652ZM21.3223 -5.656H8.13376V21.7726H21.3223V-5.656ZM21.848 8.05829V5.61714H-5.58052V8.05829H21.848ZM21.848 5.60321C21.8428 0.480816 19.8054 -4.43024 16.1829 -8.05196L-3.21001 11.3449C-4.7258 9.82947 -5.57834 7.77449 -5.58052 5.63108L21.848 5.60321ZM16.1829 -8.05196C12.5605 -11.6737 7.64902 -13.7101 2.52662 -13.7143L2.50433 13.7143C0.36092 13.7125 -1.69423 12.8604 -3.21001 11.3449L16.1829 -8.05196ZM2.51548 -13.7143C-1.42488 -13.7143 -5.20385 -12.149 -7.99011 -9.36273L11.4048 10.0322C9.04722 12.3898 5.84962 13.7143 2.51548 13.7143V-13.7143ZM-7.99011 -9.36273C-10.7764 -6.57647 -12.3417 -2.79749 -12.3417 1.14286H15.0869C15.0869 4.477 13.7624 7.67459 11.4048 10.0322L-7.99011 -9.36273ZM-12.3417 1.14286C-12.3417 5.08321 -10.7764 8.86218 -7.99011 11.6484L11.4048 -7.74648C13.7624 -5.38888 15.0869 -2.19128 15.0869 1.14286H-12.3417ZM-7.99011 11.6484C-5.20385 14.4347 -1.42488 16 2.51548 16V-11.4286C5.84962 -11.4286 9.04722 -10.1041 11.4048 -7.74648L-7.99011 11.6484ZM2.50608 16C-0.243634 15.9981 -2.88024 14.9052 -4.82491 12.9612L14.5667 -6.43706C11.3724 -9.63027 7.04153 -11.4255 2.52487 -11.4286L2.50608 16ZM-4.82491 12.9612C-6.76959 11.0172 -7.8634 8.38095 -7.86623 5.63124L19.5623 5.60305C19.5577 1.0864 17.761 -3.24385 14.5667 -6.43706L-4.82491 12.9612ZM-7.86624 5.61714V19.728H19.5623V5.61714H-7.86624ZM6.99091 16C12.4134 16 16.8 20.3907 16.8 25.8091H-10.6285C-10.6285 35.5339 -2.74018 43.4286 6.99091 43.4286V16ZM16.8 25.8091C16.8 31.2276 12.4134 35.6183 6.99091 35.6183V8.18972C-2.74018 8.18972 -10.6285 16.0844 -10.6285 25.8091H16.8ZM6.99091 35.6183C1.56839 35.6183 -2.81824 31.2276 -2.81824 25.8091H24.6103C24.6103 16.0844 16.722 8.18972 6.99091 8.18972V35.6183ZM-2.81824 25.8091C-2.81824 20.3881 1.56986 16 6.99091 16V43.4286C16.7182 43.4286 24.6103 35.5365 24.6103 25.8091H-2.81824ZM0.278907 10.344V13.4754H27.7075V10.344H0.278907ZM0.278907 13.4754C0.278907 17.4158 1.84419 21.1947 4.63046 23.981L24.0254 4.58609C26.383 6.94371 27.7075 10.1413 27.7075 13.4754H0.278907ZM4.63046 23.981C7.41671 26.7673 11.1957 28.3326 15.136 28.3326V0.904002C18.4702 0.904002 21.6678 2.22849 24.0254 4.58609L4.63046 23.981ZM15.136 28.3326C19.0764 28.3326 22.8554 26.7673 25.6416 23.981L6.24671 4.58609C8.60431 2.22848 11.8019 0.904002 15.136 0.904002V28.3326ZM25.6416 23.981C28.4279 21.1947 29.9932 17.4158 29.9932 13.4754H2.56462C2.56462 10.1413 3.88909 6.94371 6.24671 4.58609L25.6416 23.981ZM29.9932 13.4754V10.344H2.56462V13.4754H29.9932ZM16.2789 24.0583H21.3223V-3.37028H16.2789V24.0583ZM21.3223 24.0583C14.3779 24.0583 8.7509 18.4313 8.7509 11.4869H36.1795C36.1795 3.28295 29.5262 -3.37028 21.3223 -3.37028V24.0583ZM8.7509 11.4869V24.6663H36.1795V11.4869H8.7509ZM22.4652 10.952H13.0709V38.3806H22.4652V10.952ZM26.5447 22.1093C25.7991 18.1805 23.8882 14.5672 21.0605 11.7395L1.66559 31.1345C0.599076 30.0679 -0.121692 28.7051 -0.402904 27.2233L26.5447 22.1093ZM21.0605 11.7395C18.2329 8.91188 14.6195 7.00091 10.6907 6.25533L5.5768 33.203C4.09498 32.9217 2.73211 32.201 1.66559 31.1345L21.0605 11.7395ZM21.848 19.7291V10.3429H-5.58052V19.7291H21.848ZM8.13109 24.0571L13.9905 24.0583L13.9959 -3.37028L8.13644 -3.37143L8.13109 24.0571Z" fill="#F3F4F5" mask="url(#path-1-inside-1_5065_4531)"/>
                </svg>
                <span class="add-to-cart-style-txt">Add to cart</span>
            </button>
            <div class="col-md-2 col-2 mt-3 mt-md-0">
                {{-- @if ($detailedProduct->auction_product != 1) --}}
                <div class="d-flex justify-content-end">
                    <!-- Add to wishlist button -->
                    <a href="javascript:void(0)"
                        class="opacity-60 has-transitiuon hov-opacity-100 border-radius-16 wishlist-btn-style">
                        <svg width="33" height="32" class="wishlist-btn-style-icon" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.6267 27.7469C17.1733 27.9069 16.4267 27.9069 15.9733 27.7469C12.1067 26.4269 3.46667 20.9202 3.46667 11.5869C3.46667 7.46688 6.78667 4.13354 10.88 4.13354C13.3067 4.13354 15.4533 5.30688 16.8 7.12021C18.1467 5.30688 20.3067 4.13354 22.72 4.13354C26.8133 4.13354 30.1333 7.46688 30.1333 11.5869C30.1333 20.9202 21.4933 26.4269 17.6267 27.7469Z" stroke="#777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                    </a>
                </div>
                {{-- @endif --}}
            </div>
        </div>
        <!--
        <div class="row no-gutters mb-3 col-md-12 p-0">
        <a href="javascript:void(0)"
            class="col-md-12 opacity-60 has-transitiuon hov-opacity-100 border-radius-16 Compare-btn-style">
            <center><svg width="32" height="32" viewBox="0 0 32 32" class="wishlist-btn-style-icon" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M27.3333 19.9866L20.6533 26.6799" stroke="#CB774B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4.66663 19.9866H27.3333" stroke="#CB774B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4.66663 12.0134L11.3466 5.32007" stroke="#CB774B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M27.3333 12.0134H4.66663" stroke="#CB774B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

            <span class="fs-20 font-prompt-md compare-btn-txt">{{ translate('Compare') }}</span></center>
        </a>
        </div>
        -->
        <!-- Total Price
        <div class="row no-gutters pb-3" id="chosen_price_div">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">Total Price</div>
            </div>
            <div class="col-sm-10">
                <div class="product-price">
                    <strong id="chosen_price"
                        class="fs-20 fw-700 text-primary">{{ $previewData['detailedProduct']['totalDiscount'] ??  $previewData['detailedProduct']['total'] }} AED</strong>
                </div>
            </div>
        </div>-->
        <div class="row no-gutters mb-3">
            @php
                $niveau = 0 ;
            @endphp
            @foreach ($previewData['detailedProduct']['attributes'] as  $attributeId=>$attributeValues)
            @php
                $niveau++ ;
            @endphp
            <div class="col-sm-2 mb-2">
                <div class="text-secondary fs-14 fw-400 mt-2 ">
                    @php
                        $attribue = App\Models\Attribute::find($attributeId) ;

                    @endphp
                    {{$attribue ? $attribue->getTranslation('name') : ""}}
                </div>
            </div>
            <div class="col-sm-10">

                <div class="aiz-radio-inline">
                    {{-- @foreach ( $attributeValues as $key=>$value)
                    <label class="aiz-megabox pl-0 mr-2 mb-0">
                        <input  type="radio" data-attributeId="{{$attributeId}}" name="attribute_id_{{$attributeId}}"   checked >
                        @if (preg_match('/^#[0-9A-F]{6}$/i', $value))
                        <span
                        class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center p-1">
                        <span class="size-25px d-inline-block rounded"
                            style="background: {{ $value }};"></span>
                         </span>
                        @else
                        <span
                            class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center py-1 px-3">
                             {{$value}}
                        </span>
                        @endif
                    </label>
                    @endforeach --}}
                    @foreach ($attributeValues as $key => $value)
                    @php
                        $lastItem = $previewData['detailedProduct']['lastItem'] ?? null ;
                    @endphp
                    <label class="attribute_value aiz-megabox pl-0 mr-2 mb-0">
                        <input @if (($lastItem) && isset($lastItem[$attributeId]) && $lastItem[$attributeId] == $value  )
                            checked
                        @endif niveau={{$niveau}} id="attribute_id_{{$attributeId}}_{{$value}}" type="radio" attributeId="{{$attributeId}}"  name="attribute_id_{{$attributeId}}" value="{{$value}}" >
                        @if (preg_match('/^#[0-9A-F]{6}$/i', $value))
                            <span class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center p-1">
                                <span class="size-25px d-inline-block rounded" style="background: {{ $value }};"></span>
                            </span>
                        @else
                            <span class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center py-1 px-3">
                                {{$value}}
                            </span>
                        @endif
                    </label>
                @endforeach
                </div>


            </div>
            @endforeach

        </div>
    </form>
    <!--
    <div class="mt-3">

        <button type="button" class="btn btn-primary buy-now fw-600 add-to-cart min-w-150px rounded-0"
        @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
            <i class="la la-shopping-cart"></i> Buy Now
        </button>
        <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled="">
            <i class="la la-cart-arrow-down"></i> Out of Stock
        </button>
    </div>
    <div class="row no-gutters mt-3">
        <div class="col-sm-2">
            <div class="text-secondary fs-14 fw-400 mt-2">Refund</div>
        </div>
        <div class="col-sm-10">
            <a href="{{route('terms-and-conditions')}}" target="_blank">
                <img src="https://demo.activeitzone.com/ecommerce/public/assets/img/refund-sticker.jpg"
                    height="36">
            </a>
            <a href="{{route('terms-and-conditions')}}"
                class="text-blue hov-text-primary fs-14 ml-3" target="_blank">View Policy</a>
        </div>
    </div>
    <div class="row no-gutters mt-4">
        <div class="col-sm-2">
            <div class="text-secondary fs-14 fw-400 mt-2">Share</div>
        </div>
        <div class="col-sm-10">
            <div class="aiz-share jssocials">
                <div class="jssocials-shares">
                    <div class="jssocials-share jssocials-share-email"><a target="_self"
                            href="mailto:?subject=Product%20details%0AIs%20Discontinued%20By%20Manufacturer%20%E2%80%8F%20%3A%20%E2%80%8E%20No%0APackage%20Dimensions%20%E2%80%8F%20%3A%20%E2%80%8E%205.9%20x%204.2%20x%201.3%20inches%3B%201.59%20Ounces%0ADepartment%20%E2%80%8F%20%3A%20%E2%80%8E%20womens%0ADate%20First%20Available%20%E2%80%8F%20%3A%20%E2%80%8E%20October%203%2C%202017%0AManufacturer%20%E2%80%8F%20%3A%20%E2%80%8E%20Kate%20Spade%20New%20York%0AASIN%20%E2%80%8F%20%3A%20%20B077MMVB1B&amp;body=https%3A%2F%2Fdemo.activeitzone.com%2Fecommerce%2Fproduct%2Fbracelet-o0ru1952-rose-gold"
                            class="jssocials-share-link"><i class="lar la-envelope jssocials-share-logo"></i></a>
                    </div>
                    <div class="jssocials-share jssocials-share-twitter"><a target="_blank"
                            href="https://twitter.com/share?url=https%3A%2F%2Fdemo.activeitzone.com%2Fecommerce%2Fproduct%2Fbracelet-o0ru1952-rose-gold&amp;text=Product%20details%0AIs%20Discontinued%20By%20Manufacturer%20%E2%80%8F%20%3A%20%E2%80%8E%20No%0APackage%20Dimensions%20%E2%80%8F%20%3A%20%E2%80%8E%205.9%20x%204.2%20x%201.3%20inches%3B%201.59%20Ounces%0ADepartment%20%E2%80%8F%20%3A%20%E2%80%8E%20womens%0ADate%20First%20Available%20%E2%80%8F%20%3A%20%E2%80%8E%20October%203%2C%202017%0AManufacturer%20%E2%80%8F%20%3A%20%E2%80%8E%20Kate%20Spade%20New%20York%0AASIN%20%E2%80%8F%20%3A%20%20B077MMVB1B"
                            class="jssocials-share-link"><i class="lab la-twitter jssocials-share-logo"></i></a></div>
                    <div class="jssocials-share jssocials-share-facebook"><a target="_blank"
                            href="https://facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdemo.activeitzone.com%2Fecommerce%2Fproduct%2Fbracelet-o0ru1952-rose-gold"
                            class="jssocials-share-link"><i class="lab la-facebook-f jssocials-share-logo"></i></a>
                    </div>
                    <div class="jssocials-share jssocials-share-linkedin"><a target="_blank"
                            href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fdemo.activeitzone.com%2Fecommerce%2Fproduct%2Fbracelet-o0ru1952-rose-gold"
                            class="jssocials-share-link"><i class="lab la-linkedin-in jssocials-share-logo"></i></a>
                    </div>
                    <div class="jssocials-share jssocials-share-whatsapp"><a target="_self"
                            href="whatsapp://send?text=https%3A%2F%2Fdemo.activeitzone.com%2Fecommerce%2Fproduct%2Fbracelet-o0ru1952-rose-gold Product%20details%0AIs%20Discontinued%20By%20Manufacturer%20%E2%80%8F%20%3A%20%E2%80%8E%20No%0APackage%20Dimensions%20%E2%80%8F%20%3A%20%E2%80%8E%205.9%20x%204.2%20x%201.3%20inches%3B%201.59%20Ounces%0ADepartment%20%E2%80%8F%20%3A%20%E2%80%8E%20womens%0ADate%20First%20Available%20%E2%80%8F%20%3A%20%E2%80%8E%20October%203%2C%202017%0AManufacturer%20%E2%80%8F%20%3A%20%E2%80%8E%20Kate%20Spade%20New%20York%0AASIN%20%E2%80%8F%20%3A%20%20B077MMVB1B"
                            class="jssocials-share-link"><i class="lab la-whatsapp jssocials-share-logo"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
    <div class="col-md-6 float-left">
        <div class="col-md-12 product-rightbox-seller border-radius-8px float-left">
            <div class="col-md-12 product-rightbox-seller-info float-left">

                {{-- <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="avatar-seller mr-2 overflow-hidden border float-left">
                    <img class="lazyload"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($detailedProduct->user->shop->logo) }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </a> --}}
                @if(isset($detailedProduct))
                    <div class="product-rightbox-seller-details float-left">
                        <div class="float-left col-md-12 p-0">
                        <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="link-style-none">
                        <span class="fs-16 font-prompt-md float-left product-rightbox-seller-name">
                            {{ $detailedProduct->user->shop->name }}
                        </span>
                        </a>
                            @if ($detailedProduct->user->shop->verification_status == 1)
                                <span class="ml-2 float-left">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_3402_10941)">
                                        <path d="M23.2181 13.1502L13.1237 23.2446C12.4949 23.8734 11.4725 23.8734 10.8389 23.2446L0.754071 13.1502C0.125271 12.5214 0.125271 11.499 0.754071 10.8702L10.8437 0.775799C11.4725 0.146999 12.4949 0.146999 13.1285 0.775799L17.0165 4.6638L11.6885 10.5102L9.10607 7.9518L5.54447 11.5518L11.8805 17.8206L20.6021 8.2494L22.1189 9.7662L23.2229 10.8702C23.8469 11.499 23.8469 12.5214 23.2181 13.1502Z" fill="#1E78C1"/>
                                        <path d="M11.8373 16.4476L6.88367 11.542L9.10607 9.29563L11.7221 11.8828L21.3509 1.31323L23.6885 3.44443L11.8373 16.4476Z" fill="#1E78C1"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_3402_10941">
                                        <rect width="24" height="24" fill="white"/>
                                        </clipPath>
                                        </defs>
                                        </svg>
                                </span>
                            @endif
                        </div>
                        <div class="float-left col-md-12 p-0">
                            <div class="float-left">
                                <div class="rating rating-mr-1">
                                    {{ renderStarRatingSmall($detailedProduct->user->shop->rating) }}
                                </div>
                                <div class="opacity-60 fs-16">
                                    ({{ $detailedProduct->user->shop->num_of_reviews }}
                                    {{ translate('reviews') }})
                                </div>

                            </div>
                            <div class="float-right">
                                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="link-style-none">
                                    <button class="fs-16 font-prompt border-radius-8px view-store-btn">View Store</button>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $user = Auth::user() ;
                    @endphp
                       <div class="product-rightbox-seller-details float-left">
                        <div class="float-left col-md-12 p-0">
                        <a href="{{ route('shop.visit', $user->shop->slug) }}" class="link-style-none">
                        <span class="fs-16 font-prompt-md float-left product-rightbox-seller-name">
                            {{ $user->shop->name }}
                        </span>
                        </a>
                            @if ($user->shop->verification_status == 1)
                                <span class="ml-2 float-left">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_3402_10941)">
                                        <path d="M23.2181 13.1502L13.1237 23.2446C12.4949 23.8734 11.4725 23.8734 10.8389 23.2446L0.754071 13.1502C0.125271 12.5214 0.125271 11.499 0.754071 10.8702L10.8437 0.775799C11.4725 0.146999 12.4949 0.146999 13.1285 0.775799L17.0165 4.6638L11.6885 10.5102L9.10607 7.9518L5.54447 11.5518L11.8805 17.8206L20.6021 8.2494L22.1189 9.7662L23.2229 10.8702C23.8469 11.499 23.8469 12.5214 23.2181 13.1502Z" fill="#1E78C1"/>
                                        <path d="M11.8373 16.4476L6.88367 11.542L9.10607 9.29563L11.7221 11.8828L21.3509 1.31323L23.6885 3.44443L11.8373 16.4476Z" fill="#1E78C1"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_3402_10941">
                                        <rect width="24" height="24" fill="white"/>
                                        </clipPath>
                                        </defs>
                                        </svg>
                                </span>
                            @endif
                        </div>
                        <div class="float-left col-md-12 p-0">
                            <div class="float-left">
                                <div class="rating rating-mr-1">
                                    {{ renderStarRatingSmall($user->shop->rating) }}
                                </div>
                                <div class="opacity-60 fs-16">
                                    ({{ $user->shop->num_of_reviews }}
                                    {{ translate('reviews') }})
                                </div>

                            </div>
                            <div class="float-right">
                                <a href="{{ route('shop.visit', $user->shop->slug) }}" class="link-style-none">
                                    <button class="fs-16 font-prompt border-radius-8px view-store-btn">View Store</button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- <div class="col-md-12 p-0 pt-2 float-left">
                <span class="col-md-12 fs-16 font-prompt float-left p-0 pt-2">
                    <font color="#4C4E54"> Address : <span class="opacity-70">{{ $detailedProduct->user->shop->address }}</span></font>
                </span>
                <span class="col-md-12 fs-16 font-prompt float-left p-0 pt-2">
                    <font color="#4C4E54"> Phone : <span class="opacity-70">{{ $detailedProduct->user->shop->phone }}</span></font>
                </span>
            </div> --}}

        </div>
    </div>
    <!--
    <div class="col-md-6 float-left mt-3">
        <div class="col-md-12 product-rightbox-seller border-radius-8px float-left">
            <span class="fs-16 font-prompt">
                Experience an exceptional shopping experience with our product, designed to offer you the best in quality and convenience. Here are some key features and benefits:
            </span>
            <button class="col-12 fs-16 font-prompt border-radius-8px product-rightbox-btn-dwn mt-3">Offer/Coupon</button>
            <button class="col-12 fs-16 font-prompt border-radius-8px product-rightbox-btn-dwn mt-2">Credit/Debit card Offers</button>
            <div class="col-12 float-left p-0 pt-3">
                <div class="col-12 col-md-7 float-left p-0 pt-2">
                    <svg class="float-left mr-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.17004 7.43994L12 12.5499L20.77 7.46991" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 21.61V12.54" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.92999 2.48L4.59 5.45003C3.38 6.12003 2.39001 7.80001 2.39001 9.18001V14.83C2.39001 16.21 3.38 17.89 4.59 18.56L9.92999 21.53C11.07 22.16 12.94 22.16 14.08 21.53L19.42 18.56C20.63 17.89 21.62 16.21 21.62 14.83V9.18001C21.62 7.80001 20.63 6.12003 19.42 5.45003L14.08 2.48C12.93 1.84 11.07 1.84 9.92999 2.48Z" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 13.2401V9.58014L7.51001 4.1001" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="float-left fs-16 font-prompt">Ships to your location</span>
                </div>
                <div class="col-12 col-md-5 float-left p-0 pt-2">
                    <svg class="float-left mr-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.93 12.86C21.91 13.05 21.88 13.23 21.83 13.41C20.79 12.53 19.44 12 17.97 12C14.66 12 11.97 14.69 11.97 18C11.97 19.47 12.5 20.82 13.38 21.86C13.2 21.91 13.02 21.94 12.83 21.96C11.98 22.04 11.11 22 10.21 21.85C6.09999 21.15 2.78999 17.82 2.10999 13.7C0.97999 6.85002 6.81999 1.01002 13.67 2.14002C17.79 2.82002 21.12 6.13002 21.82 10.24C21.97 11.14 22.01 12.01 21.93 12.86Z" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21.83 13.41C21.69 13.9 21.43 14.34 21.06 14.71L14.68 21.09C14.31 21.46 13.87 21.72 13.38 21.86C12.5 20.82 11.97 19.47 11.97 18C11.97 14.69 14.66 12 17.97 12C19.44 12 20.79 12.53 21.83 13.41Z" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                    <span class="float-left fs-16 font-prompt">1 Year Warranty</span>
                </div>

                <div class="col-12 col-md-7 float-left p-0 pt-2">
                    <svg class="float-left mr-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.55 21.67C18.84 20.54 22 16.64 22 12C22 6.48 17.56 2 12 2C5.33 2 2 7.56 2 7.56M2 7.56V3M2 7.56H4.01H6.44" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12C2 17.52 6.48 22 12 22" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="3 3"/>
                        </svg>

                    <span class="float-left fs-16 font-prompt">Return&Refund</span>
                </div>
                <div class="col-12 col-md-5 float-left p-0 pt-2">
                    <svg class="float-left mr-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.51 2.83008H8.49C6 2.83008 5.45 4.07008 5.13 5.59008L4 11.0001H20L18.87 5.59008C18.55 4.07008 18 2.83008 15.51 2.83008Z" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21.99 19.82C22.1 20.99 21.16 22 19.96 22H18.08C17 22 16.85 21.54 16.66 20.97L16.46 20.37C16.18 19.55 16 19 14.56 19H9.43998C7.99998 19 7.78998 19.62 7.53998 20.37L7.33998 20.97C7.14998 21.54 6.99998 22 5.91998 22H4.03998C2.83998 22 1.89998 20.99 2.00998 19.82L2.56998 13.73C2.70998 12.23 2.99998 11 5.61998 11H18.38C21 11 21.29 12.23 21.43 13.73L21.99 19.82Z" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 8H3" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 8H20" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 3V5" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.5 5H13.5" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 15H9" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15 15H18" stroke="#3A3B40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>


                    <span class="float-left fs-16 font-prompt">1 Day Delivery</span>
                </div>

            </div>
        </div>
    </div>-->
    {{-- <!-- For auction product -->
    @if ($detailedProduct->auction_product)
        <div class="row no-gutters mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Auction Will End') }}</div>
            </div>
            <div class="col-sm-10">
                @if ($detailedProduct->auction_end_date > strtotime('now'))
                    <div class="aiz-count-down align-items-center"
                        data-date="{{ date('Y/m/d H:i:s', $detailedProduct->auction_end_date) }}"></div>
                @else
                    <p>{{ translate('Ended') }}</p>
                @endif

            </div>
        </div>

        <div class="row no-gutters mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Starting Bid') }}</div>
            </div>
            <div class="col-sm-10">
                <span class="opacity-50 fs-20">
                    {{ single_price($detailedProduct->starting_bid) }}
                </span>
                @if ($detailedProduct->unit != null)
                    <span class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                @endif
            </div>
        </div>

        @if (Auth::check() &&
    Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
            <div class="row no-gutters mb-3">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('My Bidded Amount') }}</div>
                </div>
                <div class="col-sm-10">
                    <span class="opacity-50 fs-20">
                        {{ single_price(Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first()->amount) }}
                    </span>
                </div>
            </div>
            <hr>
        @endif

        @php $highest_bid = $detailedProduct->bids->max('amount'); @endphp
        <div class="row no-gutters my-2 mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Highest Bid') }}</div>
            </div>
            <div class="col-sm-10">
                <strong class="h3 fw-600 text-primary">
                    @if ($highest_bid != null)
                        {{ single_price($highest_bid) }}
                    @endif
                </strong>
            </div>
        </div>
    @else
        <!-- Without auction product -->
        @if ($detailedProduct->wholesale_product == 1)
            <!-- Wholesale -->
            <table class="table mb-3">
                <thead>
                    <tr>
                        <th class="border-top-0">{{ translate('Min Qty') }}</th>
                        <th class="border-top-0">{{ translate('Max Qty') }}</th>
                        <th class="border-top-0">{{ translate('Unit Price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailedProduct->stocks->first()->wholesalePrices as $wholesalePrice)
                        <tr>
                            <td>{{ $wholesalePrice->min_qty }}</td>
                            <td>{{ $wholesalePrice->max_qty }}</td>
                            <td>{{ single_price($wholesalePrice->price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <!-- Without Wholesale -->
            @if (home_price($detailedProduct) != home_discounted_price($detailedProduct))
                <div class="row no-gutters mb-3">
                    <div class="col-sm-2">
                        <div class="text-secondary fs-14 fw-400">{{ translate('Price') }}</div>
                    </div>
                    <div class="col-sm-10">
                        <div class="d-flex align-items-center">
                            <!-- Discount Price -->
                            <strong class="fs-16 fw-700 text-primary">
                                {{ home_discounted_price($detailedProduct) }}
                            </strong>
                            <!-- Home Price -->
                            <del class="fs-14 opacity-60 ml-2">
                                {{ home_price($detailedProduct) }}
                            </del>
                            <!-- Unit -->
                            @if ($detailedProduct->unit != null)
                                <span class="opacity-70 ml-1">/{{ $detailedProduct->getTranslation('unit') }}</span>
                            @endif
                            <!-- Discount percentage -->
                            @if (discount_in_percentage($detailedProduct) > 0)
                                <span class="bg-primary ml-2 fs-11 fw-700 text-white w-35px text-center p-1"
                                    style="padding-top:2px;padding-bottom:2px;">-{{ discount_in_percentage($detailedProduct) }}%</span>
                            @endif
                            <!-- Club Point -->
                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <div class="ml-2 bg-secondary-base d-flex justify-content-center align-items-center px-3 py-1"
                                    style="width: fit-content;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 12 12">
                                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6"
                                                cy="6" r="6" transform="translate(973 633)"
                                                fill="#fff" />
                                            <g id="Group_23920" data-name="Group 23920"
                                                transform="translate(973 633)">
                                                <path id="Path_28698" data-name="Path 28698"
                                                    d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" />
                                                <path id="Path_28699" data-name="Path 28699"
                                                    d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" opacity="0.5" />
                                                <path id="Path_28700" data-name="Path 28700"
                                                    d="M12.666,3h1L15,5,12,9l1.664-4Z" transform="translate(-5.995 0)"
                                                    fill="#f3af3d" />
                                            </g>
                                        </g>
                                    </svg>
                                    <small class="fs-11 fw-500 text-white ml-2">{{ translate('Club Point') }}:
                                        {{ $detailedProduct->earn_point }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="row no-gutters mb-3">
                    <div class="col-sm-2">
                        <div class="text-secondary fs-14 fw-400">{{ translate('Price') }}</div>
                    </div>
                    <div class="col-sm-10">
                        <div class="d-flex align-items-center">
                            <!-- Discount Price -->
                            <strong class="fs-16 fw-700 text-primary">
                                {{ home_discounted_price($detailedProduct) }}
                            </strong>
                            <!-- Unit -->
                            @if ($detailedProduct->unit != null)
                                <span class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                            @endif
                            <!-- Club Point -->
                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <div class="ml-2 bg-secondary-base d-flex justify-content-center align-items-center px-3 py-1"
                                    style="width: fit-content;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 12 12">
                                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6"
                                                cy="6" r="6" transform="translate(973 633)"
                                                fill="#fff" />
                                            <g id="Group_23920" data-name="Group 23920"
                                                transform="translate(973 633)">
                                                <path id="Path_28698" data-name="Path 28698"
                                                    d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" />
                                                <path id="Path_28699" data-name="Path 28699"
                                                    d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" opacity="0.5" />
                                                <path id="Path_28700" data-name="Path 28700"
                                                    d="M12.666,3h1L15,5,12,9l1.664-4Z" transform="translate(-5.995 0)"
                                                    fill="#f3af3d" />
                                            </g>
                                        </g>
                                    </svg>
                                    <small class="fs-11 fw-500 text-white ml-2">{{ translate('Club Point') }}:
                                        {{ $detailedProduct->earn_point }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif

    @if ($detailedProduct->auction_product != 1)
        <form id="option-choice-form">
            @csrf
            <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

            @if ($detailedProduct->digital == 0)
                <!-- Choice Options -->
                @if ($detailedProduct->choice_options != null)
                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                        <div class="row no-gutters mb-3">
                            <div class="col-sm-2">
                                <div class="text-secondary fs-14 fw-400 mt-2 ">
                                    {{ get_single_attribute_name($choice->attribute_id) }}
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <div class="aiz-radio-inline">
                                    @foreach ($choice->values as $key => $value)
                                        <label class="aiz-megabox pl-0 mr-2 mb-0">
                                            <input type="radio" name="attribute_id_{{ $choice->attribute_id }}"
                                                value="{{ $value }}"
                                                @if ($key == 0) checked @endif>
                                            <span
                                                class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center py-1 px-3">
                                                {{ $value }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Color Options -->
                @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
                    <div class="row no-gutters mb-3">
                        <div class="col-sm-2">
                            <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Color') }}</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="aiz-radio-inline">
                                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                    <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip"
                                        data-title="{{ get_single_color_name($color) }}">
                                        <input type="radio" name="color"
                                            value="{{ get_single_color_name($color) }}"
                                            @if ($key == 0) checked @endif>
                                        <span
                                            class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center p-1">
                                            <span class="size-25px d-inline-block rounded"
                                                style="background: {{ $color }};"></span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quantity + Add to cart -->
                <div class="row no-gutters mb-3">
                    <div class="col-sm-2">
                        <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Quantity') }}</div>
                    </div>
                    <div class="col-sm-10">
                        <div class="product-quantity d-flex align-items-center">
                            <div class="row no-gutters align-items-center aiz-plus-minus mr-3" style="width: 130px;">
                                <button class="btn col-auto btn-icon btn-sm btn-light rounded-0" type="button"
                                    data-type="minus" data-field="quantity" disabled="">
                                    <i class="las la-minus"></i>
                                </button>
                                <input type="number" name="quantity"
                                    class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1"
                                    value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}"
                                    max="10" lang="en">
                                <button class="btn col-auto btn-icon btn-sm btn-light rounded-0" type="button"
                                    data-type="plus" data-field="quantity">
                                    <i class="las la-plus"></i>
                                </button>
                            </div>
                            @php
                                $qty = 0;
                                foreach ($detailedProduct->stocks as $key => $stock) {
                                    $qty += $stock->qty;
                                }
                            @endphp
                            <div class="avialable-amount opacity-60">
                                @if ($detailedProduct->stock_visibility_state == 'quantity')
                                    (<span id="available-quantity">{{ $qty }}</span>
                                    {{ translate('available') }})
                                @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1)
                                    (<span id="available-quantity">{{ translate('In Stock') }}</span>)
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Quantity -->
                <input type="hidden" name="quantity" value="1">
            @endif

            <!-- Total Price -->
            <div class="row no-gutters pb-3 d-none" id="chosen_price_div">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Total Price') }}</div>
                </div>
                <div class="col-sm-10">
                    <div class="product-price">
                        <strong id="chosen_price" class="fs-20 fw-700 text-primary">

                        </strong>
                    </div>
                </div>
            </div>

        </form>
    @endif

    @if ($detailedProduct->auction_product)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $detailedProduct->starting_bid;
        @endphp
        @if ($detailedProduct->auction_end_date >= strtotime('now'))
            <div class="mt-4">
                @if (Auth::check() && $detailedProduct->user_id == Auth::user()->id)
                    <span
                        class="badge badge-inline badge-danger">{{ translate('Seller cannot Place Bid to His Own Product') }}</span>
                @else
                    <button type="button" class="btn btn-primary buy-now  fw-600 min-w-150px rounded-0"
                        onclick="bid_modal()">
                        <i class="las la-gavel"></i>
                        @if (Auth::check() &&
    Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
                            {{ translate('Change Bid') }}
                        @else
                            {{ translate('Place Bid') }}
                        @endif
                    </button>
                @endif
            </div>
        @endif
    @else
        <!-- Add to cart & Buy now Buttons -->
        <div class="mt-3">
            @if ($detailedProduct->digital == 0)
                @if ($detailedProduct->external_link != null)
                    <a type="button" class="btn btn-primary buy-now fw-600 add-to-cart px-4 rounded-0"
                        href="{{ $detailedProduct->external_link }}">
                        <i class="la la-share"></i> {{ translate($detailedProduct->external_link_btn) }}
                    </a>
                @else
                    <button type="button"
                        class="btn btn-secondary-base mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                        @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                        <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
                    </button>
                    <button type="button" class="btn btn-primary buy-now fw-600 add-to-cart min-w-150px rounded-0"
                        @if (Auth::check()) onclick="buyNow()" @else onclick="showLoginModal()" @endif>
                        <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                    </button>
                @endif
                <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                    <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                </button>
            @elseif ($detailedProduct->digital == 1)
                <button type="button"
                    class="btn btn-secondary-base mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                    @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                    <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
                </button>
                <button type="button" class="btn btn-primary buy-now fw-600 add-to-cart min-w-150px rounded-0"
                    @if (Auth::check()) onclick="buyNow()" @else onclick="showLoginModal()" @endif>
                    <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                </button>
            @endif
        </div>

        <!-- Promote Link -->
        <div class="d-table width-100 mt-3">
            <div class="d-table-cell">
                @if (Auth::check() && addon_is_activated('affiliate_system') && get_affliate_option_status() && Auth::user()->affiliate_user != null && Auth::user()->affiliate_user->status)
                    @php
                        if (Auth::check()) {
                            if (Auth::user()->referral_code == null) {
                                Auth::user()->referral_code = substr(Auth::user()->id . Str::random(10), 0, 10);
                                Auth::user()->save();
                            }
                            $referral_code = Auth::user()->referral_code;
                            $referral_code_url = URL::to('/product') . '/' . $detailedProduct->slug . "?product_referral_code=$referral_code";
                        }
                    @endphp
                    <div>
                        <button type="button" id="ref-cpurl-btn" class="btn btn-secondary w-200px rounded-0"
                            data-attrcpy="{{ translate('Copied') }}" onclick="CopyToClipboard(this)"
                            data-url="{{ $referral_code_url }}">{{ translate('Copy the Promote Link') }}</button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Refund -->
        @php
            $refund_sticker = get_setting('refund_sticker');
        @endphp
        @if (addon_is_activated('refund_request'))
            <div class="row no-gutters mt-3">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Refund') }}</div>
                </div>
                <div class="col-sm-10">
                    @if ($detailedProduct->refundable == 1)
                        <a href="{{ route('returnpolicy') }}" target="_blank">
                            @if ($refund_sticker != null)
                                <img src="{{ uploaded_asset($refund_sticker) }}" height="36">
                            @else
                                <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}" height="36">
                            @endif
                        </a>
                        <a href="{{ route('returnpolicy') }}" class="text-blue hov-text-primary fs-14 ml-3"
                            target="_blank">{{ translate('View Policy') }}</a>
                    @else
                        <div class="text-dark fs-14 fw-400 mt-2">{{ translate('Not Applicable') }}</div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Seller Guarantees -->
        @if ($detailedProduct->digital == 1)
            @if ($detailedProduct->added_by == 'seller')
                <div class="row no-gutters mt-3">
                    <div class="col-2">
                        <div class="text-secondary fs-14 fw-400">{{ translate('Seller Guarantees') }}</div>
                    </div>
                    <div class="col-10">
                        @if ($detailedProduct->user->shop->verification_status == 1)
                            <span class="text-success fs-14 fw-700">{{ translate('Verified seller') }}</span>
                        @else
                            <span class="text-danger fs-14 fw-700">{{ translate('Non verified seller') }}</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    @endif

    <!-- Share -->
    <div class="row no-gutters mt-4">
        <div class="col-sm-2">
            <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Share') }}</div>
        </div>
        <div class="col-sm-10">
            <div class="aiz-share"></div>
        </div>
    </div>
</div> --}}
