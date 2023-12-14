@extends('backend.layouts.app')
<style>
    .dropdown-item.active, .dropdown-item:hover, .dropdown-item:active {
        color: black !important;
    }
</style>
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Add Attributes') }}</h1>
        </div>
    </div>

    <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Add New Attribute') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('attributes.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group mb-3 col-6">
                                    <label for="name">{{ translate('Name') }}</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                    <small style="color: red">The name should be unique. </small>
                                </div>
                                <div class="form-group mb-3 col-6">
                                    <label for="name">{{ translate('Display name in english version') }}</label>
                                    <input type="text"  id="name" name="display_name_english" class="form-control" required>
                                </div>
                                <div class="form-group mb-3 col-6">
                                    <label for="name">{{ translate('Display name in arabic version') }}</label>
                                    <input type="text" id="name" name="display_name_arabic" class="form-control" required>
                                </div>
                                <div class="form-group mb-3 col-6">
                                    <label for="name">{{ translate('Value type') }}</label>
                                    <select class="form-control" id="value_type" name="type_value">
                                        <option value="" selected="true" disabled="disabled">Please choose type</option>
                                        <option value="list">List of values</option>
                                        <option value="text">Text</option>
                                        <option value="color">Color</option>
                                        <option value="numeric">Numeric</option>
                                        <option value="boolean">Boolean</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3 col-6">
                                    <label for="name">{{ translate('English description') }}</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description_english"></textarea>
                                </div>
                                <div class="form-group mb-3 col-6">
                                    <label for="name">{{ translate('Arabic description') }}</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description_arabic"></textarea>
                                </div>
                            </div>

                            <div class="row" id="color">
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
                            </div>

                            <div class="row" id="values">
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
                            </div>

                            <div class="row">
                                <div class="form-group mb-3 col-6" id="unit">
                                    <label for="name">{{ translate('Units') }}</label>
                                    <select multiple name="units[]" id="shapes">
                                        @if(count($units) > 0)
                                            @foreach ($units as $key => $unit)
                                                <option value="{{ $unit->id }}" @if($key == 0) {{ 'selected' }} @endif>{{ $unit->name }}</option>
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

            $('#color').hide();
            $('#values').hide();
            $('#unit').hide();

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
                $(this).parent().parent().remove();
            })

            $('body').on('click', '.trash_values', function(){
                $(this).parent().parent().remove();
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
