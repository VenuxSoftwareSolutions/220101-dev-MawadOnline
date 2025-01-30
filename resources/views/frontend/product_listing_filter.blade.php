<div id="filter">
    <!-- Categories -->
    <div class="bg-white border mb-3">
        <div class="fs-16 fw-700 p-3">
            <a href="#collapse_1"
                class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between"
                data-toggle="collapse">
                {{ translate('Categories') }}
            </a>
        </div>
        <div class="collapse show" id="collapse_1">
            <ul class="p-3 mb-0 list-unstyled">
                {{-- @if (!isset($category_id))
                    @foreach ($categories as $category)
                        <li class="mb-3 text-dark">
                            <a class="text-reset fs-14 hov-text-primary" href="{{ route('products.category', $category->slug) }}">
                                {{ $category->getTranslation('name') }}
                            </a>
                        </li>
                    @endforeach
                @else
                    
                    @if ($category_parent && $category_parent->parent_id != 0)
                        <li class="mb-3">
                            <a class="text-reset fs-14 fw-600 hov-text-primary" 
                                @if ($category_parent->level == 0)
                                href="{{ route('products.category', get_single_category($category_parent->parent_id)->slug) }}"
                                @else
                                href="{{ route('search') }}"
                                @endif
                            >
                                <i class="las la-angle-left"></i>
                                {{ get_single_category($category_parent->parent_id)->getTranslation('name') }}
                            </a>
                        </li>
                    @endif
                    
                    @if ($category->parent_id != 0)
                        <li class="mb-3">
                            <a class="text-reset fs-14 fw-600 hov-text-primary" href="{{ route('products.category', get_single_category($category->parent_id)->slug) }}">
                                <i class="las la-angle-left"></i>
                                {{ get_single_category($category->parent_id)->getTranslation('name') }}
                            </a>
                        </li>
                    @endif
                    <li class="mb-3">
                        <a class="text-reset fs-14 fw-600 hov-text-primary" href="{{ route('products.category', $category->slug) }}">
                            <i class="las la-angle-left"></i>
                            {{ $category->getTranslation('name') }}
                        </a>
                    </li>
                    @foreach ($category->childrenCategories as $key => $immediate_children_category)
                        <li class="ml-4 mb-3">
                            <a class="text-reset fs-14 hov-text-primary" href="{{ route('products.category', $immediate_children_category->slug) }}">
                                {{ $immediate_children_category->getTranslation('name') }}
                            </a>
                        </li>
                    @endforeach
                @endif --}}
                <li class="mb-3">
                    <a class="text-reset fs-14 fw-600 hov-text-primary" href="javascript:filter_category(0);">
                        {{ translate('All Categories') }}
                    </a>
                </li>
                <input type="hidden" name="category_id" id="category_id"
                    @if (isset($request_all['category_id'])) value="{{ $request_all['category_id'] }}" @endif>
                @foreach (get_level_zero_categories() as $category_level_zero)
                    <li class="mb-3 ml-1 @if (in_array($category_level_zero->id, $category_parents_ids)) fw-600 @endif">
                        <a class="text-reset fs-14 hov-text-primary"
                            href="javascript:filter_category({{ $category_level_zero->id }});">
                            @if (in_array($category_level_zero->id, $category_parents_ids))
                                -
                            @else
                                +
                            @endif{{ $category_level_zero->getTranslation('name') }}
                        </a>
                    </li>

                    @foreach ($category_level_zero->childrenCategories as $key => $sub_category)
                        @if (in_array($category_level_zero->id, $category_parents_ids))
                            <li
                                class="ml-4 mb-3 @if (in_array($sub_category->id, $category_parents_ids)) fw-600 @endif @if (!in_array($category_level_zero->id, $category_parents_ids)) display_none @endif">
                                <a class="text-reset fs-14 hov-text-primary"
                                    href="javascript:filter_category({{ $sub_category->id }});">
                                    @if (in_array($sub_category->id, $category_parents_ids))
                                        -
                                    @else
                                        +
                                    @endif{{ $sub_category->getTranslation('name') }}
                                </a>
                            </li>
                            @foreach ($sub_category->childrenCategories as $key => $immediate_children_category)
                                @if (in_array($sub_category->id, $category_parents_ids))
                                    <li
                                        class="ml-5 mb-3 @if (in_array($immediate_children_category->id, $category_parents_ids)) fw-600 @endif @if (!in_array($sub_category->id, $category_parents_ids)) display_none @endif">
                                        <a class="text-reset fs-14 hov-text-primary"
                                            href="javascript:filter_category({{ $immediate_children_category->id }});">
                                            {{ $immediate_children_category->getTranslation('name') }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>

    <div class="bg-white border mb-3">
        <div class="fs-16 fw-700 p-3">
            {{ translate('Price range') }}
        </div>
        <div class="p-3 mr-3">
            @php
                $product_count = get_products_count();
                $min_max_price['min'] = $min_all_price;
                $min_max_price['max'] = $max_all_price;
            @endphp
            <div class="aiz-range-slider">
                <div id="input-slider-range" data-range-value-min="{{ $min_max_price['min'] }}"
                    data-range-value-max="{{ $min_max_price['max'] }}"></div>

                <div class="row mt-2">
                    <div class="col-6">
                        <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                            @if (isset($min_price) && $min_price && $min_price > $min_max_price['min'] && $min_price < $min_max_price['max']) data-range-value-low="{{ $min_price }}"
                            @else
                                data-range-value-low="{{ $min_max_price['min'] }}" @endif
                            id="input-slider-range-value-low"></span>
                    </div>
                    <div class="col-6 text-right">
                        <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                            @if (isset($max_price) && $max_price && $min_price < $min_max_price['max']) data-range-value-high="{{ $max_price }}"
                            @else
                                data-range-value-high="{{ $min_max_price['max'] }}" @endif
                            id="input-slider-range-value-high"></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hidden Items -->
        <div class="min-max ">
            <input class="form-control" onchange="filter()" min="{{ $min_max_price['min'] }}" type="number"
                class="form-control" placeholder='{{ translate('min price') }}' name="min_price"
                value="@if (isset($min_price) && $min_price && $min_price > $min_max_price['min'] && $min_price < $min_max_price['max']) {{ $min_price }}@else{{ $min_max_price['min'] }} @endif">
            <input class="form-control" onchange="filter()" max="{{ $min_max_price['max'] }}" type="number"
                class="form-control" placeholder='{{ translate('max price') }}' name="max_price"
                value="@if (isset($max_price) && $max_price && $min_price < $min_max_price['max']) {{ $max_price }}@else{{ $min_max_price['max'] }} @endif">
        </div>
    </div>

    <!-- Brand -->
    @if (count($brands) > 0)
        @php
            $show = '';
            if (count($brand_ids) > 0) {
                $show = 'show';
            }
        @endphp
        <div class="bg-white border mb-3">
            <div class="fs-16 fw-700 p-3">
                <a href="#"
                    class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                    data-toggle="collapse" data-target="#collapse_brand" style="white-space: normal;">
                    {{ translate('Brand') }}
                </a>
            </div>

            <div class="collapse {{ $show }}" id="collapse_brand">
                <div class="p-3 aiz-checkbox-list">
                    @foreach ($brands as $key => $brand)
                        <label
                            class="aiz-checkbox mb-3 @if ($key > 4 && count($brands) > 7) hide_attribute display_none @endif">
                            <input type="checkbox" name="brand[]" value="{{ $brand->slug }}"
                                @if (in_array($brand->id, $brand_ids)) checked @endif onchange="filter()">
                            <span class="aiz-square-check"></span>
                            <span class="fs-14 fw-400 text-dark">{{ $brand->name }}</span>
                        </label>
                    @endforeach
                    @if (count($brands) > 7)
                        <a href="javascript:void(1)"
                            class="show-hide-attribute text-primary hov-text-primary fs-12 fw-700">{{ translate('More') }}
                            <i class="las la-angle-down"></i></a>
                    @endif
                </div>
            </div>
        </div>
    @endif
    <!-- Rating -->
    @php
        $show = '';
        if (isset($rating) && $rating > 0) {
            $show = 'show';
        }
    @endphp
    <div class="bg-white border mb-3">
        <div class="fs-16 fw-700 p-3">
            <a href="#"
                class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                data-toggle="collapse" data-target="#collapse_ratting" style="white-space: normal;">
                {{ translate('Rating') }}
            </a>
        </div>

        <div class="collapse {{ $show }}" id="collapse_ratting">
            <div class="p-3 aiz-checkbox-list">
                <div class="form-group">
                    <div class="rating rating-input">
                        <label>
                            <input type="radio" name="rating" value="1"
                                @if (isset($rating) && $rating > 0) checked @endif onchange="filter()" required>
                            <i class="las la-star @if (isset($rating) && $rating > 0) active @endif"></i>
                        </label>
                        <label>
                            <input type="radio" name="rating" value="2"
                                @if (isset($rating) && $rating > 1) checked @endif onchange="filter()">
                            <i class="las la-star @if (isset($rating) && $rating > 1) active @endif"></i>
                        </label>
                        <label>
                            <input type="radio" name="rating" value="3"
                                @if (isset($rating) && $rating > 2) checked @endif onchange="filter()">
                            <i class="las la-star @if (isset($rating) && $rating > 2) active @endif"></i>
                        </label>
                        <label>
                            <input type="radio" name="rating" value="4"
                                @if (isset($rating) && $rating > 3) checked @endif onchange="filter()">
                            <i class="las la-star @if (isset($rating) && $rating > 3) active @endif"></i>
                        </label>
                        <label>
                            <input type="radio" name="rating" value="5"
                                @if (isset($rating) && $rating > 4) checked @endif onchange="filter()">
                            <i class="las la-star @if (isset($rating) && $rating > 4) active @endif"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor -->
    @if (count($shops) > 0)
        @php
            $show = '';
            if (count($vender_user_ids) > 0) {
                $show = 'show';
            }
        @endphp
    @endif

    <div class="bg-white border mb-3">
        <div class="fs-16 fw-700 p-3">
            <a href="#"
                class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                data-toggle="collapse" data-target="#collapse_vendor" style="white-space: normal;">
                {{ translate('Vendor') }}
            </a>
        </div>

        <div class="collapse {{ $show }}" id="collapse_vendor">
            <div class="p-3 aiz-checkbox-list">
                @foreach ($shops as $key => $shop)
                    <label
                        class="aiz-checkbox mb-3 @if ($key > 4 && count($shops) > 7) hide_attribute display_none @endif">
                        <input type="checkbox" name="shops[]" value="{{ $shop->slug }}"
                            @if ($shop->user && in_array($shop->user->id, $vender_user_ids)) checked @endif onchange="filter()">
                        <span class="aiz-square-check"></span>
                        <span class="fs-14 fw-400 text-dark">{{ $shop->name }}</span>
                    </label>
                @endforeach
                @if (count($shops) > 7)
                    <a href="javascript:void(1)"
                        class="show-hide-attribute text-primary hov-text-primary fs-12 fw-700">{{ translate('More') }}
                        <i class="las la-angle-down"></i></a>
                @endif
            </div>
        </div>
    </div>
    <!-- Attributes -->
    @foreach ($attributes as $attribute)
        {{-- @if (!empty($selected_attribute_values[$attribute->id])) --}}
            @if ($attribute->type_value == 'numeric')
                <div class="bg-white border mb-3">
                    <div class="fs-16 fw-700 p-3 width">
                        <a href="#"
                            class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                            data-toggle="collapse"
                            data-target="#collapse_{{ str_replace(' ', '_', $attribute->name) }}"
                            style="white-space: normal;">
                            {{ $attribute->getTranslation('name') }}
                        </a>
                        @php
                            if (isset($request_all['units_' . $attribute->id])) {
                                $unit_active = $request_all['units_' . $attribute->id];
                                $unit_active_model = \App\Models\Unity::find($unit_active);
                            } else {
                                $default_unit = $attribute->get_units()->where('default_unit', 1)->first();
                                if (!$default_unit) {
                                    $default_unit = $attribute->get_units()->first(); 
                                }
                                $unit_active_model = $default_unit;
                                $unit_active = $unit_active_model ? $unit_active_model->id : null;
                            }
                        $rate = $unit_active_model ? $unit_active_model->rate : null;

                        @endphp
                        <input type="hidden" name="units_old_{{ $attribute->id }}" value="{{ $rate }}">
                        <select class="form-control units_fil" name="units_{{ $attribute->id }}" id=""
                            onchange="filter_attribute()" disabled>
                            @foreach ($attribute->get_units() as $unit)
                                <option @if ($unit_active == $unit->id) selected @endif
                                    data-rate="{{ $unit->rate }}" value="{{ $unit->id }}">{{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        // frequest_all[$attribute->id]);
                        $show = '';
                        if (
                            isset($selected_attribute_values[$attribute->id]) &&
                            !empty($selected_attribute_values[$attribute->id])
                        ) {
                            $show = 'show';
                        }
                        $min_attribute_value = $attribute->max_min_value($conditions, $unit_active)['min'];
                        $max_attribute_value = $attribute->max_min_value($conditions, $unit_active)['max'];

                        if (isset($request_all['new_min_value_' . $attribute->id])) {
                            $min_value = $request_all['new_min_value_' . $attribute->id];
                        } else {
                            $min_value = $min_attribute_value;
                        }

                        if (isset($request_all['new_max_value_' . $attribute->id])) {
                            $max_value = $request_all['new_max_value_' . $attribute->id];
                        } else {
                            $max_value = $max_attribute_value;
                        }

                    @endphp
                    <div class="collapse {{ $show }}"
                        id="collapse_{{ str_replace(' ', '_', $attribute->name) }}">
                        <div class="p-3 mr-3">
                            <div class="aiz-range-slider-attribute aiz-range-slider-attribute-{{ $attribute->id }}"
                                data-id="{{ $attribute->id }}">
                                <div class="attribute-input-slider-range" id="attribute-input-slider-range"
                                    data-range-value-min="{{ $min_attribute_value }}"
                                    data-range-value-max="{{ $max_attribute_value }}"></div>

                                <div class="row mt-2">
                                    <div class="col-6">
                                        <span
                                            class="attribute-input-slider-range-value-low range-slider-value value-low fs-14 fw-600 opacity-70"
                                            @if ($min_value < $min_attribute_value || $min_value > $max_attribute_value) data-range-value-low="{{ $min_attribute_value }}"
                                        @else
                                            data-range-value-low="{{ $min_value }}" @endif
                                            id="attribute-input-slider-range-value-low"></span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span
                                            class="attribute-input-slider-range-value-high nb range-slider-value value-high fs-14 fw-600 opacity-70"
                                            @if ($max_value > $max_attribute_value) data-range-value-high="{{ $max_attribute_value }}"
                                        @else
                                            data-range-value-high="{{ $max_value }}" @endif
                                            id="attribute-input-slider-range-value-high"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="min-max ">
                            <input class="form-control" onchange="filter()"
                                placeholder='{{ translate('min') }} {{ $attribute->name }}' type="number"
                                id="min_attribute_numeric_{{ $attribute->id }}" name="{{ $attribute->id }}[]"
                                value="@if ($min_value < $min_attribute_value || $min_value > $max_attribute_value) {{ $min_attribute_value }}@else{{ $min_value }} @endif">
                            <input class="form-control" onchange="filter()"
                                placeholder='{{ translate('max') }} {{ $attribute->name }}' type="number"
                                id="max_attribute_numeric_{{ $attribute->id }}" name="{{ $attribute->id }}[]"
                                value="@if ($max_value > $max_attribute_value) {{ $max_attribute_value }}@else{{ $max_value }} @endif">
                        </div>
                    </div>
                </div>
            @elseif($attribute->type_value == 'color')
                <div class="bg-white border mb-3">
                    <div class="fs-16 fw-700 p-3">
                        <a href="#"
                            class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                            data-toggle="collapse" data-target="#collapse_color">
                            {{ translate('Color') }}
                        </a>
                    </div>
                    @php
                        $show = '';
                        if (
                            isset($selected_attribute_values[$attribute->id]) &&
                            !empty($selected_attribute_values[$attribute->id])
                        ) {
                            $show = 'show';
                        }
                    @endphp
                    <div class="collapse {{ $show }}" id="collapse_color">
                        <div class="p-3 aiz-radio-inline">
                            @foreach ($colors as $key => $color)
                                <label class="aiz-megabox " data-toggle="tooltip" data-title="{{ $color->name }}">
                                    <input type="checkbox" name="{{ $attribute->id }}[]"
                                        value="{{ $color->id }}" onchange="filter()"
                                        @if (isset($request_all[$attribute->id]) && in_array($color->id, $request_all[$attribute->id])) checked @endif>
                                    <span
                                        class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-2">
                                        <span class="size-30px d-inline-block rounded"
                                            style="background: {{ $color->code }};"></span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white border mb-3">
                    <div class="fs-16 fw-700 p-3">
                        <a href="#"
                            class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                            data-toggle="collapse"
                            data-target="#collapse_{{ str_replace(' ', '_', $attribute->name) }}"
                            style="white-space: normal;">
                            {{ $attribute->getTranslation('name') }}
                        </a>
                    </div>
                    @php
                        $show = '';
                        if (
                            isset($selected_attribute_values[$attribute->id]) &&
                            !empty($selected_attribute_values[$attribute->id])
                        ) {
                            $show = 'show';
                        }
                        $attribute_values = $selected_attribute_values[$attribute->id] ?? [];
                        $attribute_id = $attribute->id;
                        // dd($attribute->id,$attribute->attribute_values_filter());
                    @endphp
                    <div class="collapse {{ $show }}"
                        id="collapse_{{ str_replace(' ', '_', $attribute->name) }}">
                        <div class="p-3 aiz-checkbox-list">
                            @foreach ($attribute_values as $attribute_value)
                                @php
                                    // Decode JSON values if needed
                                    if (is_string($attribute_value) && is_array(json_decode($attribute_value, true))) {
                                        $decoded_value = json_decode($attribute_value, true);
                                    } else {
                                        $decoded_value = $attribute_value;
                                    }
                                @endphp

                                <label class="aiz-checkbox mb-3">
                                    <input type="checkbox" name="attributes[{{ $attribute_id }}][]"
                                        value="{{ is_array($decoded_value) ? $decoded_value['en'] : $decoded_value }}"
                                        onchange="filter()">
                                    <span class="aiz-square-check"></span>
                                    <span class="fs-14 fw-400 text-dark">
                                        {{ is_array($decoded_value) ? $decoded_value['en'] : $decoded_value }}
                                    </span>
                                </label>
                            @endforeach


                            @if (count($attribute->attribute_values_filter($conditions)) > 7)
                                <a href="javascript:void(1)"
                                    class="show-hide-attribute text-primary hov-text-primary fs-12 fw-700">{{ translate('More') }}
                                    <i class="las la-angle-down"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        {{-- @endif --}}
    @endforeach

</div>
