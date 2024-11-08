<option value="{{ $category->id }}" {{ isset($category->children) ? 'disabled' : '' }}>
    {{ str_repeat('— ', $level ?? 0) }}{{ $category->name }}
</option>

@if(isset($category->children))
    @foreach($category->children as $child)
        @include('seller.promotions.partials.category_option', ['category' => $child, 'level' => ($level ?? 0) + 1])
    @endforeach
@endif
