@foreach ($attributes as $attribute)
    @if (in_array($attribute->id, $variants_attributes_ids_attributes))
        <div class="row attribute-variant-{{ $attribute->id }}">
            <div class="col-md-3 mb-3">
                <input type="text" class="form-control" value="{{ translate($attribute->getTranslation('name')) }}" disabled>
            </div>
            @switch ($attribute->type_value)
                @case('text')
                    <div class="col-md-8 mb-3">
                        <input type="text" data-id_attributes="{{ $attribute->id }}" class="form-control attributes">
                    </div>
                    @break;
                @case ('list')
                    @php
                        $values = $attribute->attribute_values_list(app()->getLocale());
                    @endphp
                    <div class="col-md-8 mb-3">
                        <select class="form-control aiz-selectpicker" data-id_attributes="{{ $attribute->id }}" data-live-search="true" data-selected-text-format="count">
                            @foreach ($values as $key => $value)
                                <option  value='{{ $value->id }}'>{{ $value->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @break;
                @case ('color')
                    <div class="col-md-8 mb-3">
                        <select class="form-control attributes aiz-selectpicker" data-id_attributes="{{ $attribute->id }}" data-type="color" data-live-search="true" data-selected-text-format="count">
                            @foreach ($colors as $key => $color)
                                <option value="{{ $color->code }}" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"></option>'
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
                                <input type="number" data-id_attributes="{{ $attribute->id }}" class="form-control attributes">
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
                @case ('boolean')
                    <div class="col-md-8 mb-3" style="padding-top: 10px">
                        <label style="margin-right: 15px">
                            <input type="radio" class="attributes" data-id_attributes="{{ $attribute->id }}" name="boolean" value="yes">Yes
                        </label>
                        <label>
                            <input type="radio" class="attributes" data-id_attributes="{{ $attribute->id }}" name="boolean" value="no"> No
                        </label>
                    </div>
                    @break;
            @endswitch
        </div>
    @endif
@endforeach