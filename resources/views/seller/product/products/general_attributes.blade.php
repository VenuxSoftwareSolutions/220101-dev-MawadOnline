@foreach ($attributes as $attribute)
    @if (in_array($attribute->id, $general_attributes_ids_attributes))
        <div class="col-md-3 mb-3">
            <input type="text" class="form-control" value="{{ translate($attribute->getTranslation('name')) }}" disabled>
        </div>
        @switch ($attribute->type_value)
            @case('text')
                <div class="col-md-8 mb-3">
                    <input type="text" class="form-control attributes" name="attribute_generale-{{ $attribute->id }}" value="{{ $general_attributes[$attribute->id]->value }}">
                </div>
                @break;
            @case ('list')
                @php
                    $values = $attribute->attribute_values_list(app()->getLocale());
                @endphp
                <div class="col-md-8 mb-3">
                    <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="attribute_generale-{{ $attribute->id }}">
                        @foreach ($values as $key => $value)
                            <option  value='{{ $value->id }}' @if($general_attributes[$attribute->id]->id_values == $value->id) selected @endif>{{ $value->value }}</option>
                        @endforeach
                    </select>
                </div>
                @break;
            @case ('color')
                <div class="col-md-8 mb-3">
                    <select class="form-control attributes aiz-selectpicker" name="attribute_generale-{{ $attribute->id }}" data-type="color" data-live-search="true" data-selected-text-format="count">
                        @foreach ($colors as $key => $color)
                            <option value="{{ $color->code }}" @if($general_attributes[$attribute->id]->id_colors == $color->id) selected @endif data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"></option>'
                        @endforeach
                    </select>
                </div>
                @break;
            @case ('numeric')
                @php
                    $units_id = $attribute->get_attribute_units();
                    $units = \App\Models\Unity::whereIn('id', $units_id)->get();
                @endphp

                <div class="col-md-8 mb-3">
                    <div class="row">
                        <div class="col-6">
                            <input type="number" class="form-control attributes" name="attribute_generale-{{ $attribute->id }}" value="{{ $general_attributes[$attribute->id]->value }}">
                        </div>
                        <div class="col-6">
                            <select class="form-control attributes-units aiz-selectpicker" name="unit_attribute_generale-{{ $attribute->id }}" data-live-search="true" data-selected-text-format="count">
                                @foreach ($units as $key=>$unit)
                                    <option  value='{{ $unit->id }}' @if($general_attributes[$attribute->id]->id_units == $unit->id) selected @endif >{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @break;
            @case ('boolean')
                <div class="col-md-8 mb-3" style="padding-top: 10px">
                    <label style="margin-right: 15px">
                        <input type="radio" class="attributes" name="attribute_generale-{{ $attribute->id }}" @if($general_attributes[$attribute->id]->value == "yes") checked @endif name="boolean" value="yes">Yes
                    </label>
                    <label>
                        <input type="radio" class="attributes" name="attribute_generale-{{ $attribute->id }}" name="boolean" @if($general_attributes[$attribute->id]->value == "no") checked @endif value="no"> No
                    </label>
                </div>
                @break;
        @endswitch
    @endif
@endforeach
