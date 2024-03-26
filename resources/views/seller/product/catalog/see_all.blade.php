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
                @if($products->count() > 0)
                    @foreach($products as $product)
                        <div class="card" style="width: 18rem;">
                            <img class="card-img-top" src="{{ asset('/public'.$product->getFirstImage()) }}">
                            <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Number of variants: {{ $product->checkIfParentToGetNumVariants() }}.</p>
                            <a href="#" class="btn btn-primary" style="width: 100%;">{{ translate('View product') }}</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </form>
    </div>

@endsection

@section('script')

<script>
    $('#search').keyup(function(){
        var search = $('#search').val();
        if(search==""){
            $("#memList").html("");
            $('#result').hide();
        }else{
            $.get("{{ route('seller.catalog.search.action') }}",{name:search}, function(data){
                console.log('done');
                $('#memList').empty().html(data);
                $('#result').show();
            })
        }
    });
</script>
   
@endsection
