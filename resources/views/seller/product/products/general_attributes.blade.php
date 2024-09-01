@foreach ($attributes as $attribute)
    @if (in_array($attribute->id, $general_attributes_ids_attributes))
        {{-- <div class="col-md-4 mb-3 ">
            <input type="text" class="form-control" value="{{ translate($attribute->getTranslation('name')) }}" disabled>
        </div> --}}
        <label class="col-md-2 col-from-label attribute-variant-{{ $attribute->id }}">{{ translate($attribute->getTranslation('name')) }}</label>
        @switch ($attribute->type_value)
            @case('text')
                <div class="col-md-10 mb-3 attribute-variant-{{ $attribute->id }}">
                    <input type="text" class="form-control attributes" @if(request()->route()->getName() === 'products.approve') @if(isset($general_attributes[$attribute->id]->added)) data-toggle="tooltip" data-html="true" title="Attribute added" style="border-color: green !important;" @endif @if(isset($general_attributes[$attribute->id]->old_value)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old video provider is: {{ isset($general_attributes[$attribute->id]->old_value) }}" @endif @endif name="attribute_generale-{{ $attribute->id }}" value="{{ $general_attributes[$attribute->id]->value }}">
                </div>
                @break;
            @case ('list')
                @php
                    $values = $attribute->attribute_values_list(app()->getLocale());
                @endphp
                <div class="col-md-10 mb-3 attribute-variant-{{ $attribute->id }}">
                    <select class="form-control" @if(request()->route()->getName() === 'products.approve') @if(isset($general_attributes[$attribute->id]->added)) data-toggle="tooltip" data-html="true" title="Attribute added" style="border-color: green !important;" @endif @if(isset($general_attributes[$attribute->id]->old_value)) style="border-color: #FF3C50 !important;" data-toggle="tooltip" data-html="true" title="Modified and old video provider is: {{ isset($general_attributes[$attribute->id]->old_value) }}" @endif @endif name="attribute_generale-{{ $attribute->id }}">
                        @foreach ($values as $key => $value)
                            <option  value='{{ $value->id }}' @if($general_attributes[$attribute->id]->id_values == $value->id) selected @endif>{{ $value->value }}</option>
                        @endforeach
                    </select>
                </div>
                @break;
            @case ('color')
                <div class="col-md-10 mb-3 attribute-variant-{{ $attribute->id }}">
                    <select class="form-control attributes aiz-selectpicker" @if(request()->route()->getName() === 'products.approve') @if(count(@$data_general_attributes_color_added) > 0) data-added="true" style="border-color: green !important;" @endif @if(isset($general_attributes[$attribute->id]->old_value)) data-value="{{ $general_attributes[$attribute->id]->old_value }}" style="border-color: #FF3C50 !important;" @endif @endif name="attribute_generale-{{ $attribute->id }}[]" data-type="color" data-live-search="true" data-selected-text-format="count" multiple>
                        @foreach ($colors as $key => $color)
                            <option value="{{ $color->code }}" @if(in_array($color->id, $general_attributes[$attribute->id])) selected @endif data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"></option>'
                        @endforeach
                    </select>
                </div>
                @break;
            @case ('numeric')
                @php
                    $units_id = $attribute->get_attribute_units();
                    $units = \App\Models\Unity::whereIn('id', $units_id)->get();
                @endphp

                <div class="col-md-10 mb-3 attribute-variant-{{ $attribute->id }}">
                    <div class="row">
                        <div class="col-6"> 
                            <input type="number" step="0.1" class="form-control attributes" @if(request()->route()->getName() === 'products.approve') @if(isset($general_attributes[$attribute->id]->added)) data-toggle="tooltip" data-html="true" title="Attribute added" style="border-color: green !important;" @endif @if(isset($general_attributes[$attribute->id]->old_value) && ($general_attributes[$attribute->id]->key == "value"))  data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_attributes[$attribute->id]->old_value }}" style="border-color: #FF3C50 !important;" @endif @endif name="attribute_generale-{{ $attribute->id }}" value="{{ $general_attributes[$attribute->id]->value }}">
                        </div>
                        <div class="col-6">
                            <select class="form-control attributes-units" @if(request()->route()->getName() === 'products.approve') @if(isset($general_attributes[$attribute->id]->added)) data-toggle="tooltip" data-html="true" title="Attribute added" style="border-color: green !important;" @endif @if(isset($general_attributes[$attribute->id]->old_value) && ($general_attributes[$attribute->id]->key == "id_units"))  data-toggle="tooltip" data-html="true" title="Modified and old unit is: {{ $general_attributes[$attribute->id]->old_value }}" style="border-color: #FF3C50 !important;" @endif @endif name="unit_attribute_generale-{{ $attribute->id }}" data-live-search="true" data-selected-text-format="count">
                                @foreach ($units as $key=>$unit)
                                    <option  value='{{ $unit->id }}' @if($general_attributes[$attribute->id]->id_units == $unit->id) selected @endif >{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @break;
            @case ('boolean')
                <div class="col-md-10 mb-3 attribute-variant-{{ $attribute->id }}" style="padding-top: 10px">
                    <label style="margin-right: 15px">
                        <input type="radio" class="attributes" name="attribute_generale-{{ $attribute->id }}"   @if(request()->route()->getName() === 'products.approve') 
                                                                                                                    @if(isset($general_attributes[$attribute->id]->old_value))  
                                                                                                                        data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_attributes[$attribute->id]->old_value }}" style=" accent-color:red !important;" 
                                                                                                                    @endif 
                                                                                                                    
                                                                                                                    @if(isset($general_attributes[$attribute->id]->added)) 
                                                                                                                        data-toggle="tooltip" data-html="true" title="Attribute added" style="accent-color:green !important;" 
                                                                                                                    @endif 
                                                                                                                @endif 
                                                                                                                @if($general_attributes[$attribute->id]->value == "yes") 
                                                                                                                    checked 
                                                                                                                @endif  
                                                                                                                name="boolean" value="yes">Yes
                    </label>
                    <label>
                        <input type="radio" class="attributes" name="attribute_generale-{{ $attribute->id }}" name="boolean" @if(request()->route()->getName() === 'products.approve') @if(isset($general_attributes[$attribute->id]->old_value))  data-toggle="tooltip" data-html="true" title="Modified and old value is: {{ $general_attributes[$attribute->id]->old_value }}" style=" accent-color:red !important;" @endif  @if(isset($general_attributes[$attribute->id]->added)) data-toggle="tooltip" data-html="true" title="Attribute added" style="accent-color:green !important;" @endif @endif @if($general_attributes[$attribute->id]->value == "no") checked @endif value="no"> No
                    </label>
                </div>
                @break;
        @endswitch
    @endif
@endforeach
