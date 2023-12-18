@php
    $value = null;
    isset($classes) ? $classes: $classes = null;
    $pc = \App\Models\Category::where('id', $category->parent_id)->first();
    for ($i=0; $i < $category->level; $i++){
        $value .= '---/';
        $classes .=  ' mycat-'.$pc->id;
    }
@endphp
<tr class="{{$classes}}" style="display: none">
    <td></td>
    <td>
        @if(count($category->childrenCategories)>0)
            <button id="mycatbutton-{{$category->id}}" style="border: 0px" onclick="expandmysubcategories({{$category->id}})">></button>
        @endif
        <input type="hidden" value="" id="hide-{{$pc->id}}">
    </td>

    <td class="d-flex align-items-center">
        <span style="clear: both;
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;">

            {{ $value." " }} {{ $category->getTranslation('name') }}
        </span>
        @if($category->digital == 1)
            <img src="{{ static_asset('assets/img/digital_tag.png') }}" alt="{{translate('Digital')}}" class="ml-2 h-25px" style="cursor: pointer;" title="DIgital">
        @endif
    </td>
    <td>
        @php
            $parent = \App\Models\Category::where('id', $category->parent_id)->first();
        @endphp
        @if ($parent != null)
            {{ $parent->getTranslation('name') }}
        @else
            —
        @endif
    </td>
    <td>{{ $category->order_level }}</td>
    <td>{{ $category->level }}</td>
    <td>
        @if($category->banner != null)
            <img src="{{ uploaded_asset($category->banner) }}" alt="{{translate('Banner')}}" class="h-50px">
        @else
            —
        @endif
    </td>
    <td>
        @if($category->icon != null)
            <span class="avatar avatar-square avatar-xs">
                <img src="{{ uploaded_asset($category->icon) }}" alt="{{translate('icon')}}">
            </span>
        @else
            —
        @endif
    </td>
    <td>
        @if($category->icon != null)
            <img src="{{ uploaded_asset($category->cover_image) }}" alt="{{translate('Cover Image')}}" class="h-50px">
        @else
            —
        @endif
    </td>
    <td>
        <label class="aiz-switch aiz-switch-success mb-0">
            <input type="checkbox" onchange="update_featured(this)" value="{{ $category->id }}" <?php if($category->featured == 1) echo "checked";?>>
            <span></span>
        </label>
    </td>
    <td>{{ $category->commision_rate }} %</td>
    <td class="text-right">
        @can('edit_product_category')
            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('categories.edit', ['id'=>$category->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                <i class="las la-edit"></i>
            </a>
        @endcan
        @can('delete_product_category')
            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('categories.destroy', $category->id)}}" title="{{ translate('Delete') }}">
                <i class="las la-trash"></i>
            </a>
        @endcan
    </td>
</tr>
@foreach($category->childrenCategories as $childCategory)
@include('backend.product.categories.list-subcategories', ['category' => $childCategory,'parent'=>$category,'classes'=>$classes])
@endforeach
