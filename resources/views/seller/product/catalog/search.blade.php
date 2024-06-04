@extends('seller.layouts.app')
<style>
    .btn-filter{
        background-color: #a2b8c6 !important;
        border: none !important;
        margin-right: 10px;
    }

    .btn-outline-primary{
        border-color: #a2b8c6 !important;
        color: #a2b8c6 !important;
    }

    .aiz-titlebar h1 {
        font-size: 1.75rem !important;
    }

    .align-items-center p{
        font-size: 15px;
    }

    .search-container {
        position: relative;
        display: inline-block;
    }

    .search-input {
        padding-right: 30px; /* Adjust the padding to fit the icon */
    }

    .pagination .active .page-link {
        /* background-color: var(--primary); */
        background-color: #a2b8c6 !important;
        border-radius: 5px !important;
    }

    .pagination .page-link:hover {
        background-color: #ffffff !important;
        color: black !important;
        border: 1px solid #a2b8c6 !important;
        border-radius: 5px !important;
    }

</style>

@if(app()->getLocale() == "ae")
    <style>
        .search-icon {
            position: absolute !important;
            top: 50%;
            left: 10px; /* Adjust the position to fit the input field */
            transform: translateY(-50%);
            width: 20px; /* Adjust the size of the icon */
            height: 20px;
            background-image: url('search-icon.png'); /* Replace 'search-icon.png' with your image path */
            background-size: cover;
            background-repeat: no-repeat;
            cursor: pointer;
        }

        .btn-create{
            float: left;
            background-color: #a2b8c6 !important;
            border: none !important;
        }

    </style>
@else
    <style>
        .search-icon {
            position: absolute !important;
            top: 50%;
            right: 10px; /* Adjust the position to fit the input field */
            transform: translateY(-50%);
            width: 20px; /* Adjust the size of the icon */
            height: 20px;
            background-image: url('search-icon.png'); /* Replace 'search-icon.png' with your image path */
            background-size: cover;
            background-repeat: no-repeat;
            cursor: pointer;
        }

        .btn-create{
            float: right;
            background-color: #a2b8c6 !important;
            border: none !important;
        }
    </style>
@endif

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-12">
            <h1 class="h3">{{ translate('Mawad Catalog Search Page') }}</h1>
        </div>
        <div class="col-6">
            <p>
                {{ translate("Easily find products from other vendors here! If you can't find yours, no worries you can always add them manually. Save time and keep your listings consistent!") }}
            </p>
        </div>
        <div class="col-6">
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-create"><i class="las la-plus text-white"></i> {{translate('Create Product Manually')}} </a>
        </div>
      </div>
    </div>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col-6 search-container">
                    <input  type="text" required class="form-control search search-input" placeholder="{{ translate('Search by product name, model, brand …') }}">
                    <div class="search-icon">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div id="result" class="col-12 search_bloc panel panel-default" style="display:none">
                    <ul class="list-group" id="memList" style="width: 50%">

                    </ul>
                </div>
                <!-- <div class="col-6" style="display: flex; justify-content: flex-end;">
                    <button class="btn btn-primary btn-filter"><i class="fa-solid fa-filter"></i> Filter</button>
                    <button class="btn btn-outline-primary"><i class="fa-solid fa-file-excel"></i> Export to excel</button>
                </div> -->
            </div>
            <div class="card-body">
                <div id="search-result">
                    <div class="text-center">
                        {{translate('Please search to get the list of product catalogue !') }}
                    </div>
{{--                    @if(count($catalogs) < 0)--}}
{{--                        <div class="row">--}}
{{--                            @foreach($catalogs as $catalog)--}}
{{--                                <div class="col-3">--}}
{{--                                    <div class="card" style="width: 18rem; height: 400px">--}}
{{--                                        <div class="card-header py-2">--}}
{{--                                            <div class="d-flex justify-content-center">--}}
{{--                                                <div class="p-2" style="background: #dddddd;">--}}
{{--                                            <img class="card-img-top p-2" src="{{ asset('/public'.$catalog->getFirstImage()) }}">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="card-body">--}}
{{--                                            <h5 class="card-title">{{ $catalog->sku }}</h5>--}}
{{--                                            <p class="card-text">Number of variants: {{ $catalog->checkIfParentToGetNumVariants() }}.</p>--}}

{{--                                            <a href="{{ route('catalog.preview_product', ['id' => $catalog->id, 'is_catalog' => 1]) }}" class="btn btn-secondary" style="position: absolute; bottom: 20px !important; left: 50%; transform: translateX(-50%); width: 90%">{{ translate('View product') }}</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}

{{--                            --}}{{-- {{ $catalogs->onEachSide(1)->links('seller.product.catalog.pagination') }} --}}
{{--                        </div>--}}
{{--                        <hr>--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-6" style="padding-top: 11px; !important">--}}
{{--                                <p style="color: #bababa">Showing {{ $catalogs->firstItem() }} - {{ $catalogs->lastItem() }} of {{ $catalogs->total() }}</p>--}}
{{--                            </div>--}}
{{--                            <div class="col-6" style="display: flex; justify-content: flex-end;">--}}
{{--                                {{ $catalogs->links() }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @else--}}
{{--                        <div class="text-center">--}}
{{--                            {{translate('No catalog found') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
<script>
    $('body').on('click', '.search-icon', function(){
        var search = $('.search').val();
        $('#memList').empty();
        if(search == ""){
            alert('{{ translate("Please fill in the search input before browsing the catalog.") }}')
        }else{
            $.get("{{ route('catalog.search.new_action') }}",{name:search}, function(data){
                $('#search-result').empty().html(data);
                handleImageErrors();
            })
        }
    });

    $('body').on('keyup', '.search', function(){
        var search = $('body .search').val();

        $.get("{{ route('catalog.search.action') }}",{name:search}, function(data){
            $('#memList').empty().html(data);
            $('#result').show();
        })
    });

    // Function to handle pagination links
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href'); // Get the URL from the pagination link
        var search = $('.search').val(); // Get the current search query
        // Append the current search query to the URL
        url += (url.includes('?') ? '&' : '?') + 'name=' + search;
        // Fetch the paginated data using Ajax
        $.get(url, function(data){
            if(search == ""){
                $('#search-result').empty().html(data);
            }else{
                $('#search-result').empty().html(data);
                handleImageErrors();

            }

        });
    });

    function handleImageErrors() {
        jQuery("img").one('error', function () {
            jQuery(this).attr("src", "{{asset('public/images/placeholder.png')}}");
        }).each(function () {
            if (this.complete && !this.naturalHeight && !this.naturalWidth) {
                $(this).triggerHandler('error');
            }
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}}) {
            return;
        }
        var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->title}}',
                intro: "{{$step->description}}",
                position: 'right'
            },
            @endforeach
        ];

        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
            doneLabel: 'Next', // Replace the "Done" button with "Next"
            exitOnEsc : false ,
            exitOnOverlayClick : false ,
            disableInteraction : true ,
            overlayOpacity : 0.4 ,
            showStepNumbers : true ,
            hidePrev : true ,
            showProgress :true ,
        });

        tour.onexit(function() {
            $.ajax({
                url: "{{ route('seller.tour') }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' }, // Include CSRF token for Laravel
                success: function(response) {
                    // Handle success
                    console.log('User tour status updated successfully');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating user tour status:', error);
                }
            });
            setTimeout(function() {
                window.location.href = '{{ route("seller.dashboard") }}';
            }, 500);
        });

        tour.onbeforechange(function(targetElement) {
            if (this._direction === 'backward') {
                window.location.href = '{{ route("seller.products") }}'; // Redirect to another page
                sleep(60000);
                }

            step_number += 1 ;
            if (step_number == 3) {
            window.location.href = '{{ route("seller.reviews") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(3);
    });
</script>
@endsection
