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
                <div class="row row_center">
                    <div class="col-12 search_bloc">
                        <input type="text" required class="form-control search" style="width: 50%" id="search" placeholder="{{ translate('Search by product name, model, brand …') }}">
                        
                    </div>
                    <div id="result" class="col-12 search_bloc panel panel-default" style="display:none">
                        <ul class="list-group" id="memList" style="width: 50%">
                         
                        </ul>
                    </div>
                    @if(Auth::user()->user_type == "seller")
                        <div class="col-12" style="width: 50%; padding: 0px 422px;">
                            <small> <a href="{{ route('seller.products.create') }}">{{ translate('Create product manually.') }}</small></a> 
                            <small>{{ translate('Bulk upload products') }}</small>
                        </div>
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
                console.log('done');
                $('#memList').empty().html(data);
                $('#result').show();
            })
        }
    });
</script>
   
@endsection
