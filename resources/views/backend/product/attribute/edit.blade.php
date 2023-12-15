@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Attribute Information') }}</h5>
    </div>

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <form class="p-4" action="{{ route('attributes.update', $attribute->id) }}" method="POST">
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="attribue_id" value="{{ $attribute->id }}">
                    @csrf
                    <div class="row">
                        <div class="form-group mb-3 col-6">
                            <label for="name">{{ translate('Name') }}</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $attribute->name }}" required>
                            <small style="color: red">The name should be unique. </small>
                        </div>
                        <div class="form-group mb-3 col-6">
                            <label for="name">{{ translate('Display name in english version') }}</label>
                            <input type="text"  id="name" name="display_name_english" class="form-control" value="{{ $attribute->name_display_english }}" required>
                        </div>
                        <div class="form-group mb-3 col-6">
                            <label for="name">{{ translate('Display name in arabic version') }}</label>
                            <input type="text" id="name" name="display_name_arabic" class="form-control" value="{{ $attribute->name_display_arabic }}" required>
                        </div>
                        <div class="form-group mb-3 col-6">
                            <label for="name">{{ translate('Value type') }}</label>
                            <select class="form-control" id="value_type" name="type_value">
                                <option value="list" @if($attribute->type_value == "list") {{ 'selected' }} @endif>List of values</option>
                                <option value="text" @if($attribute->type_value == "text") {{ 'selected' }} @endif>Text</option>
                                <option value="color" @if($attribute->type_value == "color") {{ 'selected' }} @endif>Color</option>
                                <option value="numeric" @if($attribute->type_value == "numeric") {{ 'selected' }} @endif>Numeric</option>
                                <option value="boolean" @if($attribute->type_value == "boolean") {{ 'selected' }} @endif>Boolean</option>
                            </select>
                        </div>
                        <div class="form-group mb-3 col-6">
                            <label for="name">{{ translate('English description') }}</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description_english">{{ $attribute->description_english }}</textarea>
                        </div>
                        <div class="form-group mb-3 col-6">
                            <label for="name">{{ translate('Arabic description') }}</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description_arabic">{{ $attribute->description_arabic }}</textarea>
                        </div>
                    </div>

                    <div class="row" id="color">
                        @if($attribute->type_value == "color")
                            @foreach ($attribute->attribute_values_color() as $color)
                                <div class="row" style="width: 100%;margin-left: 1px;">
                                    <div class="form-group mb-3 col-6">
                                        <label for="name">{{ translate('Color name') }}</label>
                                        <input type="text" id="name" name="colors_name-{{ $color->id }}" value="{{ $color->value }}" class="form-control">
                                    </div>
                                    <div class="form-group mb-3 col-5">
                                        <label for="name">{{ translate('Code color') }}</label>
                                        <input type="color" name="colors_code-{{ $color->id }}" value="{{ $color->color_code }}" class="form-control">
                                    </div>
                                    <div class="col-1">
                                        <i class="las la-plus add" style="margin-left: 5px; margin-top: 40px;" title="Add another color"></i>
                                        <i class="las la-trash trash" data-href="{{ route('attributes-delete-value', $color->id) }}" style="margin-left: 5px; margin-top: 40px;" title="Delete this color"></i>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row" style="width: 100%;margin-left: 1px;">
                                <div class="form-group mb-3 col-6">
                                    <label for="name">{{ translate('Color name') }}</label>
                                    <input type="text" id="name" name="color_name[]" class="form-control">
                                </div>
                                <div class="form-group mb-3 col-5">
                                    <label for="name">{{ translate('Code color') }}</label>
                                    <input type="color" name="color_code[]" class="form-control">
                                </div>
                                <div class="col-1">
                                    <i class="las la-plus add" style="margin-left: 5px; margin-top: 40px;" title="Add another color"></i>
                                    <i class="las la-trash trash" style="margin-left: 5px; margin-top: 40px;" title="Delete this color"></i>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row" id="values">
                        @if($attribute->type_value == "list")
                        @php
                            $values = $attribute->attribute_values_list();
                        @endphp
                            @foreach ($values as $key => $value)
                                @if($key % 2 == 0)
                                    <?php
                                        $id = $value->id.'-'.$values[$key + 1]->id;
                                    ?>
                                    <div class="row" style="width: 100%;margin-left: 1px;">
                                        <div class="form-group mb-3 col-5">
                                            <label for="name" class="tagify-label">{{ translate('Values in english') }}</label>
                                            <input name='value_english-{{ $value->id }}' class="form-control" value="{{ $value->value }}" autofocus>
                                        </div>
                                        <div class="form-group mb-3 col-5">
                                            <label for="name" class="tagify-label">{{ translate('Values in arabic') }}</label>
                                            <input name='value_arabic-{{ $values[$key + 1]->id }}' class="form-control" value="{{ $values[$key + 1]->value }}" autofocus>
                                        </div>
                                        <div class="col-1">
                                            <i class="las la-plus add_values" style="margin-left: 5px; margin-top: 40px;" title="Add another values"></i>
                                            <i class="las la-trash trash_values" data-href="{{ route('attributes-delete-value', $id) }}" style="margin-left: 5px; margin-top: 40px;" title="Delete this values"></i>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="row" style="width: 100%;margin-left: 1px;">
                                <div class="form-group mb-3 col-5">
                                    <label for="name" class="tagify-label">{{ translate('Values in english') }}</label>
                                    <input name='values_english[]' class="form-control" autofocus>
                                </div>
                                <div class="form-group mb-3 col-5">
                                    <label for="name" class="tagify-label">{{ translate('Values in arabic') }}</label>
                                    <input name='values_arabic[]' class="form-control" autofocus>
                                </div>
                                <div class="col-1">
                                    <i class="las la-plus add_values" style="margin-left: 5px; margin-top: 40px;" title="Add another values"></i>
                                    <i class="las la-trash trash_values" style="margin-left: 5px; margin-top: 40px;" title="Delete this values"></i>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="form-group mb-3 col-6" id="unit">
                            <label for="name">{{ translate('Units') }}</label>
                            <select multiple name="units[]" id="shapes">
                                @if(count($units) > 0)
                                    @foreach ($units as $key => $unit)
                                        <option value="{{ $unit->id }}" @if(($attribute->type_value == "numeric") && (count($attribute->get_attribute_units()) > 0)) @if (in_array($unit->id, $attribute->get_attribute_units())) {{ 'selected' }} @endif @endif>{{ $unit->name }}</option>
                                    @endforeach
                                @endif
                            </select>

                        </div>
                    </div>
                    <div class="form-group mb-3 text-center">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="{{ static_asset('assets/js/jQuery.tagify.min.js') }}"></script>
    <script src="{{ static_asset('assets/js/tagify.min.js') }}"></script>
    <script src="{{ static_asset('assets/js/filter-multi-select-bundle.js') }}"></script>
    <script>
        $( document ).ready(function() {
            var shapes = $('#shapes').filterMultiSelect({
                placeholderText: 'click to select a unit',
                filterText: 'search',
                labelText: 'Units',
                caseSensitive: true,
            });

            $('body .trash:first').hide();
            $('body .trash_values:first').hide();

            @if($attribute->type_value == "list")
                $('#values').show();
            @else
                $('#values').hide();
            @endif

            @if($attribute->type_value == "numeric")
                $('#unit').show();
            @else
                $('#unit').hide();
            @endif

            @if($attribute->type_value == "color")
                $('#color').show();
            @else
                $('#color').hide();
            @endif



            $('body').on('click', '.add', function(){
                var html = `<div class="row" style="width: 100%;margin-left: 1px;">
                                    <div class="form-group mb-3 col-6">
                                        <label for="name">{{ translate('Color name') }}</label>
                                        <input type="text" id="name" name="color_name[]" class="form-control">
                                    </div>
                                    <div class="form-group mb-3 col-5">
                                        <label for="name">{{ translate('Code color') }}</label>
                                        <input type="color" name="color_code[]" class="form-control">
                                    </div>
                                    <div class="col-1">
                                        <i class="las la-plus add" style="margin-left: 5px; margin-top: 40px;" title="Add another color"></i>
                                        <i class="las la-trash trash" style="margin-left: 5px; margin-top: 40px;" title="Delete this color"></i>
                                    </div>
                                </div>`;
                $('#color').append(html);
                AIZ.plugins.bootstrapSelect('refresh');
            })

            $('body').on('click', '.add_values', function(){
                var html = `<div class="row" style="width: 100%;margin-left: 1px;">
                                    <div class="form-group mb-3 col-5">
                                        <label for="name" class="tagify-label">{{ translate('Values in english') }}</label>
                                        <input name='values_english[]' class="form-control" autofocus>
                                    </div>
                                    <div class="form-group mb-3 col-5">
                                        <label for="name" class="tagify-label">{{ translate('Values in arabic') }}</label>
                                        <input name='values_arabic[]' class="form-control" autofocus>
                                    </div>
                                    <div class="col-1">
                                        <i class="las la-plus add_values" style="margin-left: 5px; margin-top: 40px;" title="Add another values"></i>
                                        <i class="las la-trash trash_values" style="margin-left: 5px; margin-top: 40px;" title="Delete this values"></i>
                                    </div>
                                </div>`;
                $('#values').append(html);
            })

            $('body').on('click', '.trash', function(){
                var link = $(this).data('href');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var current = $(this);

                swal({
                    title: "Are you sure you want to delete?",
                    type: "warning",
                    confirmButtonText: "Delete",
                    showCancelButton: true
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: link,
                            type: "POST",
                            data: {},
                            cache: false,
                            dataType: 'JSON',
                            success: function(dataResult) {
                                current.parent().parent().remove();
                                swal(
                                    'Delete',
                                    'Your deletion is done successfully',
                                    'success'
                                )
                            }
                        })
                    } else if (result.dismiss === 'cancel') {
                        swal(
                            'Cancelled',
                            'Your deletion is undone',
                            'warning'
                        )
                    } else{
                        swal(
                            'Error',
                            'Something went wrong!',
                            'error'
                        )
                    }
                })
            })

            $('body').on('click', '.trash_values', function(){
                var link = $(this).data('href');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var current = $(this);

                swal({
                    title: "Are you sure you want to delete?",
                    type: "warning",
                    confirmButtonText: "Delete",
                    showCancelButton: true
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: link,
                            type: "POST",
                            data: {},
                            cache: false,
                            dataType: 'JSON',
                            success: function(dataResult) {
                                current.parent().parent().remove();
                                swal(
                                    'Delete',
                                    'Your deletion is done successfully',
                                    'success'
                                )
                            }
                        })
                    } else if (result.dismiss === 'cancel') {
                        swal(
                            'Cancelled',
                            'Your deletion is undone',
                            'warning'
                        )
                    } else{
                        swal(
                            'Error',
                            'Something went wrong!',
                            'error'
                        )
                    }
                })
            })

            $('#value_type').on('change', function(){
                if($(this).val() == "list"){
                    $("#color").hide();
                    $("#unit").hide();
                    $("#values").show();
                }else if($(this).val() == "color"){
                    $("#values").hide();
                    $("#unit").hide();
                    $("#color").show();
                }else if($(this).val() == "numeric"){
                    $("#color").hide();
                    $("#values").hide();
                    $("#unit").show();
                }else{
                    $("#values").hide();
                    $("#color").hide();
                    $("#unit").hide();
                }
            });

        });
    </script>
@endsection
