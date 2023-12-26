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
                        <a class="nav-link text-reset @if ($language->code == app()->getLocale()) show active @else bg-soft-dark border-light border-left-0 @endif py-3" data-toggle="tab" href="#{{ $language->code }}">
                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                            <span>{{$language->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <form action="{{ route('attributes.store') }}" method="post">
                    @csrf
                    <div class="tab-content p-4">
                        @foreach (get_all_active_language() as $key => $language)
                        @php
                            $name = strtolower($language->name);
                            $titre_display = 'Display name in '.$name.' version';
                            $titre_discription = ucfirst($name).' description';
                        @endphp
                            <div class="tab-pane @if ($language->code == app()->getLocale()) fade in active show @endif" id="{{ $language->code }}">
                                <div class="row">
                                    <div class="col-12 ">
                                        <div class="row">
                                            @if ($language->code == "en")
                                                <div class="form-group mb-3 col-12">
                                                    <label for="name">{{ translate('Name') }}</label>
                                                    <input type="text" id="name" name="name" class="form-control" required>
                                                    <small style="color: red">{{ translate('The name should be unique.')}} </small>
                                                </div>
                                            @endif
                                            <div class="form-group mb-3 col-12">
                                                <label for="name">{{ translate($titre_display) }}</label>
                                                <input type="text"  id="name" name="display_name_{{ $name }}" class="form-control" required>
                                            </div>
                                            @if ($language->code == "en")
                                                <div class="form-group mb-3 col-12">
                                                    <label for="name">{{ translate('Value type') }}</label>
                                                    <select class="form-control" id="value_type" name="type_value">
                                                        <option value="" selected="true" disabled="disabled">{{ translate('Please choose type') }}</option>
                                                        <option value="list">{{translate('List of values')}}</option>
                                                        <option value="text">{{translate('Text')}}</option>
                                                        <option value="color">{{translate('Color')}}</option>
                                                        <option value="numeric">{{translate('Numeric')}}</option>
                                                        <option value="boolean">{{translate('Boolean')}}</option>
                                                    </select>
                                                </div>
                                            @endif
                                            <div class="form-group mb-3 col-12">
                                                <label for="name">{{ translate($titre_discription) }}</label>
                                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description_{{ $name }}"></textarea>
                                            </div>
                                        </div>
                                        @php
                                            $titre = 'Value in '.$name;
                                        @endphp
                                        <div class="row values_{{$name}}" id="">
                                            <div class="row" style="width: 100%;margin-left: 1px;" id="bloc_{{ $name }}_1">
                                                <div class="form-group mb-3 col-10">
                                                    <label for="name" class="tagify-label">{{ translate($titre) }}</label>
                                                    <input name='values_{{ $name }}[]' class="form-control" autofocus>
                                                </div>
                                                <div class="col-1">
                                                    <i class="las la-plus add_values" style="margin-left: 5px; margin-top: 40px;" title="Add another values"></i>
                                                    <i class="las la-trash trash_values" data-id_bloc="1" style="margin-left: 5px; margin-top: 40px;" title="Delete this values"></i>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($language->code == "en")
                                            <div class="row">
                                                <div class="form-group mb-3 col-6" id="unit">
                                                    <label for="name">{{ translate('Units') }}</label>
                                                    <select multiple name="units[]" id="shapes">
                                                        @if(count($units) > 0)
                                                            @foreach ($units as $key => $unit)
                                                                <option value="{{ $unit->id }}">
                                                                    @if (app()->getLocale() == "sa")
                                                                        {{ $unit->getTranslation('name','ar',false) }}
                                                                    @else
                                                                        {{ $unit->name }}
                                                                    @endif
                                                                </option>
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
                placeholderText: "{{ translate('click to select a unit') }}",
                filterText: "{{ translate('Search') }}",
                labelText: "{{ translate('Units') }}",
                caseSensitive: false,
            });

            $('body div[class="items dropdown-item"]').attr('dir', 'auto');

            $('body .trash_values:first').hide();

            $("div[class*='values_']").hide();
            $('#unit').hide();

            var id_bloc = 2;

            $('body').on('click', '.add_values', function(){

                var html_english = `<div class="row" style="width: 100%;margin-left: 1px;" id="bloc_english_${id_bloc}">
                                    <div class="form-group mb-3 col-10">
                                        <label for="name" class="tagify-label">{{ translate('Values in english') }}</label>
                                        <input name='values_english[]' class="form-control" autofocus>
                                    </div>
                                    <div class="col-1">
                                        <i class="las la-plus add_values" style="margin-left: 5px; margin-top: 40px;" title="{{ translate('Add another values') }}"></i>
                                        <i class="las la-trash trash_values" data-language="english" data-id_bloc="${id_bloc}" style="margin-left: 5px; margin-top: 40px;" title="{{ translate('Delete this value') }}"></i>
                                    </div>
                                </div>`;

                var html_arabic = `<div class="row" style="width: 100%;margin-left: 1px;" id="bloc_arabic_${id_bloc}">
                                    <div class="form-group mb-3 col-10">
                                        <label for="name" class="tagify-label">{{ translate('Values in arabic') }}</label>
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
                var language = $(this).data('language');
                var current = $(this);

                var id = $(this).data('id_bloc');
                swal({
                    title: "{{ translate('This value will be deleted in both English and Arabic sections!')}}",
                    type: "warning",
                    confirmButtonText: "{{ translate('Delete')}}",
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

        });
    </script>

@endsection
