

@foreach($categories as $category)
<tr  id="level-{{$category->id}}" class="subcategories-{{$category->parent_id}} {{$classes}}" style="" related-to="{{$category->parent_id}}" >



    <td class="d-flex  align-items-center">
        <div class="d-flex flex-container">
                @for($i=1;$i<$level;$i++)

                <div class="flex-item" style="color:white">--</div>
                @endfor
                <div class="flex-item">
                    @if(count($category->childrenCategories)>0)
                    <button id="mycatbutton-{{$category->id}}" data-expand="close" style="border: 0px" onclick="expandmysubcategories({{$category->id}})" >></button>
                    @else
                    <div class="flex-item" style="color:white">----</div>

                    @endif
                </div>
                <div class="flex-item"  style="color:white">--</div>
                <div class="flex-item">{!! $category->getTranslation('name') !!}</div>
            <!-- Add more divs as needed -->
          </div>

        @if($category->digital == 1)
            <img src="{{ static_asset('assets/img/digital_tag.png') }}" alt="{{translate('Digital')}}" class="ml-2 h-25px" style="cursor: pointer;" title="DIgital">
        @endif
    </td>
    <td style="display: table-cell;">
        @php
            $parent = \App\Models\Category::where('id', $category->parent_id)->first();
        @endphp
        @if ($parent != null)
            {{ $parent->getTranslation('name') }}
        @else
            —
        @endif
    </td>
    <td style="display: table-cell;">{{ $category->order_level }}</td>
    <td style="display: table-cell;">{{ $category->level }}</td>

    <td style="display: table-cell;">
        @if($category->icon != null)
            <img src="{{ uploaded_asset($category->cover_image) }}" alt="{{translate('Cover Image')}}" class="h-50px">
        @else
            —
        @endif
    </td>
    <td style="display: table-cell;">
        <label class="aiz-switch aiz-switch-success mb-0">
            <input type="checkbox" onchange="update_featured(this)" value="{{ $category->id }}" <?php if($category->featured == 1) echo "checked";?>>
            <span></span>
        </label>
    </td>
    <td style="display: table-cell;">{{ $category->commision_rate }} %</td>
    <td style="display: table-cell;" class="text-right footable-last-visible" >
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

@endforeach
