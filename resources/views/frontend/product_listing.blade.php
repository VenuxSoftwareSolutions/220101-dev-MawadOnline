@extends('frontend.layouts.app')

@if (isset($category_id))
    @php
        $meta_title = $category->meta_title;
        $meta_description = $category->meta_description;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = get_single_brand($brand_id)->meta_title;
        $meta_description = get_single_brand($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title = get_setting('meta_title');
        $meta_description = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/preloader.css') }}">
    <style>
        .min-max {
            display: flex;
            justify-content: center;
        }

        .min-max input {
            width: 120px;
            margin: 5px;
        }

        .fs-16.fw-700.p-3.width {
            display: flex;
            justify-content: space-between;
        }

        .display_none {
            display: none !important;
        }

        #spinner-div {
            position: fixed;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 2;
        }

        .spinner-border-search {
            left: 50% !important;
            top: 50% !important;
            position: absolute !important;
            border-right-color: var(--primary) !important;
        }

        /* SVG spinner icon animation */
        .spinner {
            -webkit-animation: rotate 2s linear infinite;
            animation: rotate 2s linear infinite;
            z-index: 2;
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -25px 0 0 -25px;
            width: 50px;
            height: 50px;
        }

        .spinner .path {
            stroke: #cccccc;
            stroke-linecap: round;
            -webkit-animation: dash 1.5s ease-in-out infinite;
            animation: dash 1.5s ease-in-out infinite;
        }

        @-webkit-keyframes rotate {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes rotate {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-webkit-keyframes dash {
            0% {
                stroke-dasharray: 1, 150;
                stroke-dashoffset: 0;
            }

            50% {
                stroke-dasharray: 90, 150;
                stroke-dashoffset: -35;
            }

            100% {
                stroke-dasharray: 90, 150;
                stroke-dashoffset: -124;
            }
        }

        @keyframes dash {
            0% {
                stroke-dasharray: 1, 150;
                stroke-dashoffset: 0;
            }

            50% {
                stroke-dasharray: 90, 150;
                stroke-dashoffset: -35;
            }

            100% {
                stroke-dasharray: 90, 150;
                stroke-dashoffset: -124;
            }
        }

        .aiz-checkbox-list {
            scrollbar-width: thin;
            scrollbar-color: #ccc transparent;
        }

        .aiz-checkbox-list::-webkit-scrollbar {
            width: 8px;
        }

        .aiz-checkbox-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .aiz-checkbox-list::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        .color-label {
            position: relative;
            cursor: pointer;
        }

        .color-name {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease;
            z-index: 1;
            pointer-events: none;
        }

        .color-label:hover .color-name {
            opacity: 1;
            visibility: visible;
        }

        .color-name::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -4px;
            border-width: 4px;
            border-style: solid;
            border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
        }

        .color-label input[type="checkbox"] {
            display: none;
        }

        .color-label .aiz-megabox-elem {
            border: 2px solid transparent;
            transition: border 0.2s ease;
        }

        .color-label input[type="checkbox"]:checked+.aiz-megabox-elem {
            border-color: #000;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }
    </style>
@endsection

@section('content')
    <div id="spinner-div" class="pt-5">
        <div class="spinner-border-search text-primary" role="status">
            <svg class="spinner" viewBox="0 0 50 50">
                <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
            </svg>
        </div>
    </div>
    <section class="mb-4 pt-4">
        <div class="container sm-px-0 pt-2">
            <form class="" id="search-form" action="" method="GET">
                <div class="row">

                    <!-- Sidebar Filters -->
                    <div class="col-xl-3">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
                                data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar c-scrollbar-light text-left">
                                <div class="d-flex d-xl-none justify-content-between align-items-center pl-3 border-bottom">
                                    <h3 class="h6 mb-0 fw-600">{{ translate('Filters') }}</h3>
                                    <button type="button" class="btn btn-sm p-2 filter-sidebar-thumb"
                                        data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                        <i class="las la-times la-2x"></i>
                                    </button>
                                </div>
                                @include('frontend.product_listing_filter')
                            </div>
                        </div>
                    </div>

                    <!-- Contents -->
                    <div class="col-xl-9">

                        <!-- Breadcrumb -->
                        <ul class="breadcrumb bg-transparent py-0 px-1" id="list_categories">
                            <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                                <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                            </li>
                            @if (!isset($category_id))
                                <li class="breadcrumb-item fw-700  text-dark">
                                    "{{ translate('All Categories') }}"
                                </li>
                            @else
                                <li class="breadcrumb-item opacity-50 hov-opacity-100">
                                    <a class="text-reset"
                                        href="{{ route('search') }}">{{ translate('All Categories') }}</a>
                                </li>
                            @endif
                            @if (isset($category_parent_parent))
                                @if ($category_parent_parent->level != 0)
                                    <li class="text-dark fw-600 breadcrumb-item">
                                        "{{ $category_parent_parent->getTranslation('name') }}"
                                    </li>
                                @endif
                            @endif
                            @if (isset($category_parent))
                                @if ($category_parent->level != 0)
                                    <li class="text-dark fw-600 breadcrumb-item">
                                        "{{ $category_parent->getTranslation('name') }}"
                                    </li>
                                @endif
                            @endif
                            @if (isset($category_id))
                                @if ($category->level != 0)
                                    <li class="text-dark fw-600 breadcrumb-item">
                                        "{{ $category->getTranslation('name') }}"
                                    </li>
                                @endif
                            @endif


                        </ul>

                        <!-- Top Filters -->
                        <div class="text-left">
                            <div class="row gutters-5 flex-wrap align-items-center">
                                <div class="col-lg col-10">
                                    <h1 class="fs-20 fs-md-24 fw-700 text-dark title_category">
                                        @if (isset($category_id))
                                            {{ $category->getTranslation('name') }}
                                        @elseif(isset($query))
                                            {{ translate('Search result for ') }}"{{ $query }}"
                                        @else
                                            {{ translate('All Products') }}
                                        @endif
                                    </h1>
                                    <input type="hidden" name="keyword" value="{{ $query }}">
                                </div>
                                <div class="col-2 col-lg-auto d-xl-none mb-lg-3 text-right">
                                    <button type="button" class="btn btn-icon p-0" data-toggle="class-toggle"
                                        data-target=".aiz-filter-sidebar">
                                        <i class="la la-filter la-2x"></i>
                                    </button>
                                </div>
                                {{-- <div class="col-6 col-lg-auto mb-3 w-lg-200px mr-xl-4 mr-lg-3">
                                    @if (Route::currentRouteName() != 'products.brand')
                                        <select class="form-control form-control-sm aiz-selectpicker rounded-0" data-live-search="true" name="brand" onchange="filter()">
                                            <option value="">{{ translate('Brands')}}</option>
                                            @foreach (get_all_brands() as $brand)
                                                <option value="{{ $brand->slug }}" @isset($brand_id) @if ($brand_id == $brand->id) selected @endif @endisset>{{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div> --}}
                                <div class="col-6 col-lg-auto mb-3 w-lg-200px">
                                    <select class="form-control form-control-sm aiz-selectpicker rounded-0" name="sort_by"
                                        onchange="filter()">
                                        <option value="">{{ translate('Sort by') }}</option>
                                        <option value="newest"
                                            @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>
                                            {{ translate('Newest') }}</option>
                                        <option value="oldest"
                                            @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>
                                            {{ translate('Oldest') }}</option>
                                        <option value="price-asc"
                                            @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>
                                            {{ translate('Price low to high') }}</option>
                                        <option value="price-desc"
                                            @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>
                                            {{ translate('Price high to low') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="px-3">
                            <div
                                class="all_product row gutters-16 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2 border-top border-left">
                                @foreach ($products as $key => $product)
                                    <div class="col border-right border-bottom has-transition hov-shadow-out z-1">
                                        @include(
                                            'frontend.' .
                                                get_setting('homepage_select') .
                                                '.partials.product_box_1',
                                            ['product' => $product]
                                        )
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="aiz-pagination mt-4">
                            {!! str_replace('href', 'data-href', $products->appends(request()->input())->links()) !!}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('script')

    <script src="{{ asset('public/assets/js/jquery.preloader.min.js') }}"></script>
    <script type="text/javascript">
        function filter_category(category_id) {
            $("#spinner-div").show();
            $('#category_id').val(category_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            console.log($('#search-form').attr('action'));
            var data = {
                category_id: category_id,
            };
            $.ajax({
                url: $('#search-form').attr('action'),
                method: $('#search-form').attr('method'),
                data: data,

                success: function(response) {
                    $('.all_product').html(response.html);
                    $('.aiz-pagination').html(response.pagination);
                    $('#filter').html(response.filter);
                    $('#list_categories').html(response.list_categories);
                    $('.title_category').html(response.title_category);
                    updateSelectedValues(response.selected_values);

                    slide_refresh();
                    rating_refresh();
                    $("#spinner-div").hide();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });
                    alert(errorMessage);
                }
            });
        }

        function filter_attribute() {
            filter();
        }

        function filter() {
            $("#spinner-div").show();
            // $('#search-form').submit();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: $('#search-form').attr('action'),
                method: $('#search-form').attr('method'),
                data: $('#search-form').serialize(),
                success: function(response) {
                    $('.all_product').html(response.html);
                    $('.aiz-pagination').html(response.pagination);
                    $('#filter').html(response.filter);
                    slide_refresh();
                    rating_refresh();
                    $("#spinner-div").hide();
                    console.log(response.selected_values);
                    updateSelectedValues(response.selected_values);

                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });
                    alert(errorMessage);
                }
            });
        }

        function rangefilter(arg) {
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }

        function rangefilter_attribute(arg, id) {
            $('#min_attribute_numeric_' + id).val(arg[0]);
            $('#max_attribute_numeric_' + id).val(arg[1]);
            filter();
        }

        function updateSelectedValues(selected_values) {
            if (selected_values) {
                if (selected_values.numeric_attributes) {
                    Object.keys(selected_values.numeric_attributes).forEach(function(attribute_id) {
                        let numericAttr = selected_values.numeric_attributes[attribute_id];
                        if (numericAttr) {
                            $('#min_attribute_numeric_' + attribute_id).val(numericAttr.min);
                            $('#max_attribute_numeric_' + attribute_id).val(numericAttr.max);
                            let slider = $('.aiz-range-slider-attribute-' + attribute_id +
                                ' .attribute-input-slider-range')[0];
                            if (slider && slider.noUiSlider) {
                                slider.noUiSlider.set([numericAttr.min, numericAttr.max]);
                            }
                        }
                    });
                }

                if (selected_values.boolean_attributes) {
                    Object.keys(selected_values.boolean_attributes).forEach(function(attribute_id) {
                        let isChecked = selected_values.boolean_attributes[attribute_id] === true;
                        $('input[name="attributes[' + attribute_id + '][]"]').prop('checked', isChecked);
                    });
                }

                if (selected_values.list_attributes) {
                    Object.keys(selected_values.list_attributes).forEach(function(attribute_id) {
                        let values = selected_values.list_attributes[attribute_id];
                        if (Array.isArray(values)) {
                            values.forEach(function(value) {
                                $('input[name="attributes[' + attribute_id + '][]"][value="' + value + '"]')
                                    .prop('checked', true);
                            });
                        }
                    });
                }

                if (selected_values.color_attributes) {
                    Object.keys(selected_values.color_attributes).forEach(function(attribute_id) {
                        let colors = selected_values.color_attributes[attribute_id];
                        if (Array.isArray(colors)) {
                            colors.forEach(function(colorId) {
                                $('input[name="attributes[' + attribute_id + '][]"][value="' + colorId +
                                    '"]').prop('checked', true);
                            });
                        }
                    });
                }
            }
        }

        function updateColorSelection(checkbox) {
            let anySelected = document.querySelectorAll('.color-label input[type="checkbox"]:checked').length > 0;
            if (!anySelected) {
                resetFilters();
            } else {
                filter();
            }
        }

        function resetFilters() {
            let checkboxes = document.querySelectorAll('.color-label input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
            filter();
        }
    </script>

    <script>
        $('body').on('click', '.pagination li a', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#spinner-div").show();
            $.ajax({
                url: $(this).data('href'),
                method: $('#search-form').attr('method'),
                success: function(response) {
                    $('.all_product').html(response.html);
                    $('.aiz-pagination').html(response.pagination);
                    $('.title_category').html(response.title_category);

                    $("#spinner-div").hide();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });
                    alert(errorMessage);
                }
            });
        });
    </script>

    <script>
        $('body').on('click', '.show-hide-attribute').on('click', function() {

            if ($(this).find('i').hasClass('la-angle-down')) {
                $(this).parent().find('.hide_attribute').removeClass('display_none');
                $(this).html('{{ translate('Less') }} <i class="las la-angle-up"></i>');
            } else {

                $(this).parent().find('.hide_attribute').addClass('display_none');
                $(this).html('{{ translate('More') }} <i class="las la-angle-down"></i>');
            }
        });
    </script>

    <script>
        function slide_refresh() {
            if ($(".aiz-range-slider")[0]) {
                $(".aiz-range-slider").each(function() {
                    var c = document.getElementById("input-slider-range"),
                        d = document.getElementById("input-slider-range-value-low"),
                        e = document.getElementById("input-slider-range-value-high"),
                        f = [d, e];

                    noUiSlider.create(c, {
                            start: [
                                parseFloat(d.getAttribute("data-range-value-low")),
                                parseFloat(e.getAttribute("data-range-value-high")),
                            ],
                            connect: !0,
                            range: {
                                min: parseFloat(c.getAttribute("data-range-value-min")),
                                max: parseFloat(c.getAttribute("data-range-value-max")),
                            },
                        }),

                        c.noUiSlider.on("update", function(a, b) {
                            f[b].textContent = a[b];
                        }),
                        c.noUiSlider.on("change", function(a, b) {
                            rangefilter(a);
                        });
                });
            }
            if ($(".aiz-range-slider-attribute")[0]) {
                $(".aiz-range-slider-attribute").each(function() {
                    var c = $(this).find(".attribute-input-slider-range")[0],
                        d = $(this).find(".attribute-input-slider-range-value-low")[0],
                        e = $(this).find(".attribute-input-slider-range-value-high")[0],
                        id_attribute = $(this).data("id"),
                        f = [d, e];

                    noUiSlider.create(c, {
                        start: [
                            parseFloat(d.getAttribute("data-range-value-low")),
                            parseFloat(e.getAttribute("data-range-value-high")),
                        ],
                        connect: true,
                        range: {
                            min: parseFloat(c.getAttribute("data-range-value-min")),
                            max: parseFloat(c.getAttribute("data-range-value-max")),
                        },
                    });

                    c.noUiSlider.on("update", function(a, b) {
                        f[b].textContent = a[b];
                    });

                    c.noUiSlider.on("change", function(a, b) {
                        rangefilter_attribute(a, id_attribute);
                    });
                });
            }
        }

        function rating_refresh() {
            $(".rating-input").each(function() {
                $(this)
                    .find("label")
                    .on({
                        mouseover: function(event) {
                            $(this).find("i").addClass("hover");
                            $(this).prevAll().find("i").addClass("hover");
                        },
                        mouseleave: function(event) {
                            $(this).find("i").removeClass("hover");
                            $(this).prevAll().find("i").removeClass("hover");
                        },
                        click: function(event) {
                            $(this).siblings().find("i").removeClass("active");
                            $(this).find("i").addClass("active");
                            $(this).prevAll().find("i").addClass("active");
                        },
                    });
                if ($(this).find("input").is(":checked")) {
                    $(this)
                        .find("label")
                        .siblings()
                        .find("i")
                        .removeClass("active");
                    $(this)
                        .find("input:checked")
                        .closest("label")
                        .find("i")
                        .addClass("active");
                    $(this)
                        .find("input:checked")
                        .closest("label")
                        .prevAll()
                        .find("i")
                        .addClass("active");
                }
            });
        }
    </script>
@endsection
