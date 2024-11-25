<option value="{{ $category->id }}" data-section="{{ $category->path }}"
    @if ($category->isLeaf) data-leaf="true" @endif>
    {{ $category->name }}
</option>

@if (isset($category->children))
    @foreach ($category->children as $child)
        @include('seller.promotions.partials.category_option', ['category' => $child])
    @endforeach
@endif
