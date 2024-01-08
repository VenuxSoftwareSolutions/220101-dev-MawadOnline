@php
    $value = null;
    for ($i=1; $i < $child_category->level; $i++){
        $value .= '--';
    }
@endphp
<option @if(old('parent_id') == $child_category->id) selected @endif  value="{{ $child_category->id }}">{{ $value." ".$child_category->getTranslation('name') }}</option>
@if ($child_category->categories)
    @foreach ($child_category->categories as $childCategory)
        @include('categories.child_category', ['child_category' => $childCategory])
    @endforeach
@endif
