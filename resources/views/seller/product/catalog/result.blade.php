@if(count($products) > 0)
    @foreach($products as $product)
        <li class="list-group-item"><a href="">{{ $product->name }}</a></li>
    @endforeach
        <li class="list-group-item"><a href="{{ route('seller.catalog.search.see_all', $search) }}">{{ translate('See all') }}</a></li>
@else
    <li class="list-group-item">No Results Found</li>
@endif