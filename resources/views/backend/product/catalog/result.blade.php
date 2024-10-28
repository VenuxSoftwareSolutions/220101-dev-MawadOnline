@php 
    $sum = count($catalogs)
@endphp

@if(count($catalogs) > 0)
    @foreach($catalogs as $catalog)
        <li class="list-group-item"><a href="{{ route('catalog.preview_product', ['id' => $catalog->id, 'is_catalog' => 1]) }}">{{ $catalog->name }}</a></li>
    @endforeach
@endif

@if($sum == 0)
    <li class="list-group-item">No Results Found</li>
@else
    <li class="list-group-item"><a href="{{ route('catalog.search.see_all', $search) }}">{{ translate('See all') }}</a></li>
@endif