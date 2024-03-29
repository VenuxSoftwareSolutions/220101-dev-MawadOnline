@php 
    $sum = count($products) + count($catalogs)
@endphp
@if(count($products) > 0)
    @foreach($products as $product)
        <li class="list-group-item"><a href="{{ route('catalog.preview_product', ['id' => $product->id, 'is_catalog' => 2]) }}">{{ $product->name }}</a></li>
    @endforeach
@endif

@if(count($catalogs) > 0)
    @foreach($catalogs as $catalog)
        <li class="list-group-item"><a href="{{ route('catalog.preview_product', ['id' => $catalog->id, 'is_catalog' => 1]) }}">{{ $catalog->name }}</a></li>
    @endforeach
@endif

@if($sum == 0)
    <li class="list-group-item">No Results Found</li>
@else
    <li class="list-group-item"><a href="{{ route('catalog.search.see_all', $search) }}">{{ translate('See all') }} {{ $sum }}</a></li>
@endif