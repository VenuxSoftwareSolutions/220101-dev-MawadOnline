@foreach ($categories as $category)
    <option value="{{ $category['id'] }}">{{ $category['text'] }}</option>

    @if(!empty($category['children']))
        @include('backend.product.categories.categories_option', ['categories' => $category['children']])
    @endif
@endforeach
