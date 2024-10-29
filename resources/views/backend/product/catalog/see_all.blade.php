@extends('backend.layouts.app')
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
@section('content')

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
                <div class="row row_center mb-3">
                    <div class="col-12 search_bloc">
                        <input type="text" required class="form-control search" style="width: 50%" id="search" placeholder="{{ translate('Search by product name, model, brand …') }}">
                        
                    </div>
                    <div id="result" class="col-12 search_bloc panel panel-default" style="display:none">
                        <ul class="list-group" id="memList" style="width: 50%">
                         
                        </ul>
                    </div>
                </div>
                <div class="row">
                    @if(count($products) > 0)
                        @foreach($products as $product)
                            <div class="col-3">
                                <div class="card" style="width: 18rem;">
                                    <img class="card-img-top" src="{{ asset('/public'.$product->getFirstImage()) }}">
                                    <div class="card-body">
                                    <h5 class="card-title">{{ $product->sku }}</h5>
                                    <p class="card-text">Number of variants: {{ $product->checkIfParentToGetNumVariants() }}.</p>
                                    <a href="{{ route('catalog.preview_product', ['id' => $product->id, 'is_catalog' => 2]) }}" class="btn btn-primary" style="background-color: #232734 !important; border-color: #232734 !important;">{{ translate('View product') }}</a>
                                    <button type="button" class="btn btn-danger" data-id="{{ $product->id }}">{{ translate('Delete from catalog') }}</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if(count($catalogs) > 0)
                        @foreach($catalogs as $catalog)
                            <div class="col-3">
                                <div class="card" style="width: 18rem;">
                                    <img class="card-img-top" src="{{ asset('/public'.$catalog->getFirstImage()) }}">
                                    <div class="card-body">
                                    <h5 class="card-title">{{ $catalog->sku }}</h5>
                                    <p class="card-text">Number of variants: {{ $catalog->checkIfParentToGetNumVariants() }}.</p>
                                    <a href="{{ route('catalog.preview_product', ['id' => $catalog->id, 'is_catalog' => 1]) }}" class="btn btn-primary" style="background-color: #232734 !important; border-color: #232734 !important;">{{ translate('View product') }}</a>
                                    <button type="button" class="btn btn-danger ml-3" data-id="{{ $catalog->id }}">{{ translate('Delete') }}</button>
                                </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
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
                $.get("{{ route('catalog.search.action') }}",{name:search}, function(data){
                    $('#memList').empty().html(data);
                    $('#result').show();
                })
            }
        });

        $('.btn-danger').on('click', function(){
            var id = $(this).data('id');
            var current = $(this);
            if(id != undefined){
                $.get("{{ route('catalog.delete') }}",{id:id}, function(data){
                    if(data.status == 'success'){
                        current.parent().parent().remove();
                    }else{
                        alert('failed');
                    }
                })
            }
        })
    </script>
@endsection
