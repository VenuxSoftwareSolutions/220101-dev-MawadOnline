@foreach ($attributes as $key => $attribute)
    @if (in_array($attribute->id, $variants_attributes_ids_attributes))
        <div class="row mb-3 attribute-variant-{{ $attribute->id }}">
            <label class="col-md-2 col-from-label">{{ translate($attribute->getTranslation('name')) }}</label>

    @switch ($attribute->type_value) 
        @case ("text")
            <div class="col-md-10">
                <input type="text" class="form-control attributes" data-id_attributes="{{ $attribute->id }}">
            </div>
            @break;
        @case ("list")
            @php
                $values = $attribute->attribute_values_list(app()->getLocale());
                $options = '<div class="col-md-10"><select class="form-control attributes aiz-selectpicker" data-id_attributes="'.$attribute->id.'" data-live-search="true" data-selected-text-format="count" >';
                foreach ($values as $key=>$value){
                    $options .= "<option  value='".$value->id."'>". $value->value ."</option>";
                }
                $options .= "</select></div>";
                $html .= $options;
            @endphp
            @break;
        @case ("color")
            @php
                $colors = \App\Models\Color::orderBy('name', 'asc')->get();
            @endphp
            <div class="col-md-10">
                <select class="form-control attributes color aiz-selectpicker" data-id_attributes="{{ $attribute->id }}" data-type="color" data-live-search="true" data-selected-text-format="count" multiple>
                    @foreach ($colors as $key => $color)
                        <option value="{{ $color->code }}" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"></option>
                    @endforeach
                </select>
            </div>
            @break;
        @case ("numeric")
            @php
                $units_id = $attribute->get_attribute_units();
                $units = \App\Models\Unity::whereIn('id', $units_id)->get();
            @endphp
            <div class="col-md-10">
                <div class="row">
                    <div class="col-6">
                        <input type="number" step="0.1" class="form-control attributes" data-id_attributes="{{ $attribute->id }}">
                    </div>
                    <div class="col-6">
                        <select class="form-control attributes-units aiz-selectpicker" data-id_attributes="{{ $attribute->id }}" data-live-search="true" data-selected-text-format="count">
                            @foreach ($units as $key=>$unit)
                                <option  value='{{ $unit->id }}'>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @break;
        @case ("boolean")
            <div class="col-md-10" style="padding-top: 10px">
                <label style="margin-right: 15px">
                    <input type="radio" class="attributes" data-id_attributes="{{ $attribute->id }}" name="boolean{{ $key }}" value="yes">Yes
                </label>
                <label>
                    <input type="radio" class="attributes" data-id_attributes="{{ $attribute->id }}" name="boolean{{ $key }}" value="no"> No
                </label>
            </div>
            @break;
        @endswitch

    </div>
    @endif
@endforeach