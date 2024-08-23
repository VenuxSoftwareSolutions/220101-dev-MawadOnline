@extends('seller.layouts.app')
<style>
    .search_bloc {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .row_center {
        justify-content: center;
        align-items: center;
    }

    small{
        font-size: 110% !important;
    }

    .list-group-item a{
        margin-left: 50px;
    }

    .list-group-item:last-child{
        display: flex;
        justify-content: center;
    }

    .row_center small:last-child{
        float: right;
    }
    .row_center small:first-child{
        float: left;
    }
</style>

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('MawadCatalog search page') }}</h1>
        </div>
      </div>
    </div>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('MawadCatalog search page') }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row row_center">
                    <div  class="col-12 search_bloc">
                        <input  type="text" required class="form-control search" style="width: 50%" placeholder="{{ translate('Search by product name, model, brand …') }}">

                    </div>
                    <div id="result" class="col-12 search_bloc panel panel-default" style="display:none">
                        <ul class="list-group" id="memList" style="width: 50%">

                        </ul>
                    </div>
                    @if(Auth::user()->user_type == "seller")
                        <div class="col-12" style="width: 50%; padding: 0px 422px;">
                            <small> <a href="{{ route('seller.products.create') }}">{{ translate('Create product manually.') }}</small></a>
                            {{-- <small>{{ translate('Bulk upload products') }}</small> --}}
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')

<script>
    $('.search').keyup(function(){
        var search = $(this).val();
        if(search==""){
            $("#memList").html("");
            $('#result').hide();
        }else{
            $.get("{{ route('catalog.search.action') }}",{name:search}, function(data){
                console.log('done');
                $('#memList').empty().html(data);
                $('#result').show();
            })
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('startTourButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default anchor click behavior
        localStorage.setItem('guide_tour', '0'); // Set local storage as required
        window.location.href = '{{ route("seller.dashboard") }}'; // Redirect to the dashboard
    });
    if (localStorage.getItem('guide_tour') != '0') {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}} ) {
            return;
        }
    }
        var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->getTranslation('title')}}',
                intro: "{{$step->getTranslation('description')}}",
                position: '{{ $step->getTranslation('lang') === 'en' ? 'right' : 'left' }}'
            },
            @endforeach
        ];
        var lang = '{{$tour_steps[0]->getTranslation('lang')}}';
        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
            nextLabel: lang == 'en' ? 'Next' : 'التالي',
            prevLabel: lang == 'en' ? 'Back' : 'رجوع',
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
            localStorage.setItem('guide_tour', '1'); // Set local storage as required
            setTimeout(function() {
                window.location.href = '{{ route("seller.dashboard") }}';
            }, 500);
        });

        tour.onbeforechange(function(targetElement) {
            step_number += 1 ;
            if (step_number == 3) {
            window.location.href = '{{ route("seller.stocks.index") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(4);
    });
</script>
@endsection
