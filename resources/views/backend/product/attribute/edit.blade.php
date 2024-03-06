@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Attribute Informations')}}</h5>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill border-light">
                    @foreach (get_all_active_language() as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link text-reset @if ($language->code == $lang) show active @else bg-soft-dark border-light border-left-0 @endif py-3" data-toggle="tab" href="#{{ $language->code }}">
                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                            <span>{{$language->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <form class="p-4" action="{{ route('attributes.update', $attribute->id) }}" method="POST">
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="attribue_id" value="{{ $attribute->id }}">
                    @csrf
                    <div class="tab-content p-4">
                        @foreach (get_all_active_language() as $key => $language)
                            @php
                                $name = strtolower($language->name);
                                $titre_display = 'Display name in '.$name.' version';
                                $titre_discription = ucfirst($name).' description';
                                $key_display = 'name_display_'.$name;
                                $key_description = 'description_'.$name;
                            @endphp
                            <div class="tab-pane @if ($language->code == $lang) fade in active show @endif" id="{{ $language->code }}">
                                <div class="row">
                                    <div class="col-12 ">
                                        <div class="row">
                                            @if ($language->code == $lang)
                                                <div class="form-group mb-3 col-12">
                                                    <label for="name">{{ translate('Name') }}</label>
                                                    <input type="text" id="name" name="name" class="form-control" value="{{ $attribute->name }}" required>
                                                    <small style="color: red">The name should be unique. </small>
                                                </div>
                                            @endif
                                            <div class="form-group mb-3 col-12">
                                                <label for="name">{{ translate($titre_display) }}</label>
                                                <input type="text"  id="name" name="display_name_{{ $name }}" class="form-control" value="{{ $attribute->$key_display }}" required>
                                            </div>
                                            @if ($language->code == $lang)
                                                <div class="form-group mb-3 col-12">
                                                    <label for="name">{{ translate('Value type') }}</label>
                                                    <select class="form-control" id="value_type" name="type_value">
                                                        <option value="list" @if($attribute->type_value == "list") {{ 'selected' }} @endif>{{ translate('List of values')}}</option>
                                                        <option value="text" @if($attribute->type_value == "text") {{ 'selected' }} @endif>{{ translate('Text')}}</option>
                                                        <option value="color" @if($attribute->type_value == "color") {{ 'selected' }} @endif>{{ translate('Color')}}</option>
                                                        <option value="numeric" @if($attribute->type_value == "numeric") {{ 'selected' }} @endif>{{ translate('Numeric')}}</option>
                                                        <option value="boolean" @if($attribute->type_value == "boolean") {{ 'selected' }} @endif>{{ translate('Boolean')}}</option>
                                                    </select>
                                                </div>
                                            @endif
                                            <div class="form-group mb-3 col-12">
                                                <label for="name">{{ translate($titre_discription) }}</label>
                                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description_{{ $name }}">{{ $attribute->$key_description }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row values_{{$name}}" id="">
                                            @php
                                                $titre = 'Value in '.$name;
                                            @endphp
                                            @if($attribute->type_value == "list")
                                                @php
                                                    $values = $attribute->attribute_values_list();
                                                    $id_bloc_with_name = "id_bloc_".$name."_";
                                                @endphp
                                                @foreach ($values as $key => $value)
                                                    <div class="row" style="width: 100%;margin-left: 1px;" id="{{'id_bloc_'.$value->id}}">
                                                        <div class="form-group mb-3 col-10">
                                                            <label for="name" class="tagify-label">{{ translate($titre) }}</label>
                                                            <input name='value_{{ $name }}-{{ $value->id }}' data-id={{ $value->id }} class="form-control values_attribute_list" value="{{ $value->getTranslation('value', $language->app_lang_code,false) }}" autofocus>
                                                        </div>
                                                        <div class="col-1">
                                                            <i class="las la-plus add_values" style="margin-left: 5px; margin-top: 40px;" title="Add another values"></i>
                                                            <i class="las la-trash trash_values" data-id={{ $value->id }} data-href="{{ route('get-id-to-delete-value', ["id" => $value->id, "language" => $name]) }}" style="margin-left: 5px; margin-top: 40px;" title="Delete this values"></i>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row" style="width: 100%;margin-left: 1px;">
                                                    <div class="form-group mb-3 col-10">
                                                        <label for="name" class="tagify-label">{{ translate($titre) }}</label>
                                                        <input name='values_{{ $name }}[]' class="form-control" autofocus>
                                                    </div>
                                                    <div class="col-1">
                                                        <i class="las la-plus add_values" style="margin-left: 5px; margin-top: 40px;" title="Add another values"></i>
                                                        <i class="las la-trash trash_values" data-id_bloc="1" style="margin-left: 5px; margin-top: 40px;" title="Delete this values"></i>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @if ($language->code == $lang)
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
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group mb-3 text-center">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script type="text/javascript">
    $(document).on('click', '.nav-link', function(){
        $('.nav-tabs').find('a').addClass('bg-soft-dark');
        $('.nav-tabs').find('a').addClass('border-light');
        $('.nav-tabs').find('a').addClass('border-left-0');
        $('.nav-tabs').find('.active').removeClass("active");
        $(this).addClass('active');
        $(this).removeClass('bg-soft-dark');
        $(this).removeClass('border-light');
        $(this).removeClass('border-left-0');
    })
</script>

<script src="{{ static_asset('assets/js/jQuery.tagify.min.js') }}"></script>
    <script src="{{ static_asset('assets/js/tagify.min.js') }}"></script>
    <script src="{{ static_asset('assets/js/filter-multi-select-bundle.js') }}"></script>
    <script>
        $( document ).ready(function() {
            var shapes = $('#shapes').filterMultiSelect({
                placeholderText: 'click to select a unit',
                filterText: 'search',
                labelText: 'Units',
                caseSensitive: false,
            });

            $("body div[class*='values_'] .trash_values:first").hide();
            $('body .trash_values:first').hide();

            @if($attribute->type_value == "list")
                $("div[class*='values_']").show();
            @else
                $("div[class*='values_']").hide();
            @endif

            @if($attribute->type_value == "numeric")
                $('#unit').show();
            @else
                $('#unit').hide();
            @endif

            var id_bloc = 2;

            $('body').on('click', '.add_values', function(){

                var html_english = `<div class="row" style="width: 100%;margin-left: 1px;" id="bloc_english_${id_bloc}">
                                    <div class="form-group mb-3 col-10">
                                        <label for="name" class="tagify-label">{{ translate('Value in english') }}</label>
                                        <input name='values_english[]' class="form-control" autofocus>
                                    </div>
                                    <div class="col-1">
                                        <i class="las la-plus add_values" style="margin-left: 5px; margin-top: 40px;" title="{{ translate('Add another values') }}"></i>
                                        <i class="las la-trash trash_values" data-language="english" data-id_bloc="${id_bloc}" style="margin-left: 5px; margin-top: 40px;" title="{{ translate('Delete this value') }}"></i>
                                    </div>
                                </div>`;

                var html_arabic = `<div class="row" style="width: 100%;margin-left: 1px;" id="bloc_arabic_${id_bloc}">
                                    <div class="form-group mb-3 col-10">
                                        <label for="name" class="tagify-label">{{ translate('Value in arabic') }}</label>
                                        <input name='values_arabic[]' class="form-control" autofocus>
                                    </div>
                                    <div class="col-1">
                                        <i class="las la-plus add_values" style="margin-left: 5px; margin-top: 40px;" title="{{ translate('Add another values') }}"></i>
                                        <i class="las la-trash trash_values" data-language="arabic" data-id_bloc="${id_bloc}" style="margin-left: 5px; margin-top: 40px;" title="{{ translate('Delete this value') }}"></i>
                                    </div>
                                </div>`;
                $('.values_english').append(html_english);
                $('.values_arabic').append(html_arabic);
                id_bloc++;
            })

            $('body').on('click', '.trash_values', function(){
                var link = $(this).data('href');
                var language = $(this).data('language');
                var current = $(this);

                if(link == undefined){
                   var id = $(this).data('id_bloc');
                    swal({
                        title: "{{ translate('This value will be deleted in both English and Arabic sections!')}}",
                        type: "warning",
                        confirmButtonText: "Delete",
                        showCancelButton: true
                    })
                    .then((result) => {
                        if (result.value) {
                            if(language == "arabic"){
                                current.parent().parent().remove();
                                $(`body #bloc_english_${id}`).remove();
                            }else{
                                current.parent().parent().remove();
                                $(`body #bloc_arabic_${id}`).remove();
                            }

                        } else if (result.dismiss === 'cancel') {
                            swal(
                                "{{ translate('Cancelled')}}",
                                "{{ translate('Your deletion is undone')}}",
                                'warning'
                            )
                        } else{
                            swal(
                                "{{ translate('Error')}}",
                                "{{ translate('Something went wrong!')}}",
                                'error'
                            )
                        }
                    })
                   $('.values_english div[data-id_bloc="'+id+'"]').remove();
                }else{
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var id_to_delete = 0;

                    $.ajax({
                        url: link,
                        type: "POST",
                        data: {},
                        cache: false,
                        dataType: 'JSON',
                        success: function(dataResult) {
                            if(dataResult.status == "done"){
                                var ids = current.data('id') + '-' + dataResult.id_to_delete;
                                doSomethingWithValue(ids, dataResult.id_to_delete, current.data('id'));
                            }

                            if(dataResult.status == "failed used"){
                                current.parent().parent().find('.form-control').attr('readonly', true);
                                current.parent().parent().find('small').remove();
                                current.parent().parent().find('.col-10').append("<small style='color:red'>{{ translate('Cannot delete this value because is used in product!')}}</small>");
                            }

                        }
                    })

                    function doSomethingWithValue(ids_to_delete, id_to_delete_from_another_language, current_id) {
                        swal({
                            title: "{{ translate('Are you sure you want to delete?')}}",
                            type: "warning",
                            confirmButtonText: "{{ translate('Delete') }}",
                            showCancelButton: true
                        })
                        .then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: "{{ route('attribute-delete-values') }}",
                                    type: "GET",
                                    data: {
                                        ids: ids_to_delete
                                    },
                                    cache: false,
                                    dataType: 'JSON',
                                    success: function(dataResult) {
                                        $('#id_bloc_' + current_id).remove();
                                        $('#id_bloc_' + id_to_delete_from_another_language).remove();
                                        swal(
                                            "{{ translate('Delete')}}",
                                            "{{ translate('Your deletion is done successfully') }}",
                                            'success'
                                        )
                                    }
                                })
                            } else if (result.dismiss === 'cancel') {
                                swal(
                                    "{{ translate('Cancelled')}}",
                                    "{{ translate('Your deletion is undone')}}",
                                    'warning'
                                )
                            } else{
                                swal(
                                    "{{ translate('Error')}}",
                                    "{{ translate('Something went wrong!')}}",
                                    'error'
                                )
                            }
                        })
                    }


                }
            })

            $('#value_type').on('change', function(){
                if($(this).val() == "list"){
                    $("#unit").hide();
                    $("div[class*='values_']").show();
                }else if($(this).val() == "color"){
                    $("div[class*='values_']").hide();
                    $("#unit").hide();
                }else if($(this).val() == "numeric"){
                    $("div[class*='values_']").hide();
                    $("#unit").show();
                }else{
                    $("div[class*='values_']").hide();
                    $("#unit").hide();
                }
            });

            $('#value_type').on('click', function(){
                var type_value = $(this).val();
                var current = $(this);
                var attribute_id = "{{ $attribute->id }}";

                if(type_value == "list"){
                    $.ajax({
                        url: "{{route('search-attribute-has-values-used-by-type')}}",
                        type: "GET",
                        data: {
                            attribute_id: attribute_id
                        },
                        cache: false,
                        dataType: 'JSON',
                        success: function(dataResult) {
                            if(dataResult.status == "Exist"){
                                current.attr('disabled', true);
                                current.attr('readonly', true);
                                current.attr('disabled', false);
                                current.addClass('nonClickableSelect');
                                current.parent().find('small').remove();
                                current.parent().append('<small style="color:red">Cannot delete this value because is used in create product!</small>');
                            }
                        }
                    })
                }
            });

            $('body').on('focusin', '.values_attribute_list', function(){
                var value_id = $(this).data('id');
                var attribute_id = "{{ $attribute->id }}"
                var current = $(this);
                $.ajax({
                        url: "{{route('search-value-is-used')}}",
                        type: "GET",
                        data: {
                            value_id: value_id,
                            attribute_id: attribute_id
                        },
                        cache: false,
                        dataType: 'JSON',
                        success: function(dataResult) {
                            if(dataResult.status == "Exist"){
                                current.attr('readonly', true);
                                current.parent().find('small').remove();
                                current.parent().append('<small style="color:red">Cannot delete this value because is used in create product!</small>');
                            }

                        }
                    })
            });

        });
    </script>

@endsection
