@extends('backend.layouts.app')

@section('content')

@php
CoreComponentRepository::instantiateShopRepository();
CoreComponentRepository::initializeCache();
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <ul class="nav nav-tabs nav-fill border-light">
                @foreach (get_all_active_language() as $key => $language)
                <li class="nav-item fa fa-hand-pointer-o language-switcher" id="my-active-lang-{{$language->code}}" data-lang-switcher="{{$language->code}}" style="
                    @if($errors->has($language->code=='en' ? 'name'. '':'name'.'_'.$language->code) ||
                    $errors->has($language->code=='en' ? 'description'. '':'description'.'_'.$language->code)
                    )
                    border:1px solid red
                    @endif
                    ">
                    <div class="nav-link text-reset @if ($language->code == $lang) active @else @if($language->code !=env('DEFAULT_LANGUAGE'))bg-soft-dark @endif border-light border-left-0 @endif py-3" href="">
                        <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                        <span>{{$language->name}}</span>
                    </div>
                </li>
                @endforeach
            </ul>
            <script>
                let switchers = document.querySelectorAll('.language-switcher');
                switchers.forEach(element => {

                    element.addEventListener('click', function() {
                        switchers.forEach(elm => {
                            elm.querySelector("div").classList.add('bg-soft-dark');
                        });
                        element.querySelector("div").classList.remove('bg-soft-dark');
                        let lang = element.getAttribute('data-lang-switcher');
                        let allswitchers = document.querySelectorAll('.language-switcher-tabs');
                        allswitchers.forEach(myelement => {

                            let currentlang = myelement.getAttribute('data-lang-switcher');
                            if (currentlang == lang) {
                                myelement.style.display = '';
                            } else {
                                myelement.style.display = 'none';

                            }
                        })
                        let nottranslatabletabs = document.querySelectorAll('.not-translatable');
                        if (nottranslatabletabs && lang != 'en') {
                            nottranslatabletabs.forEach(nottranselement => {
                                nottranselement.style.display = 'none';
                            })
                        }
                        if (nottranslatabletabs && lang == 'en') {
                            nottranslatabletabs.forEach(nottranselement => {
                                nottranselement.style.display = '';
                            })
                        }

                    })
                });
            </script>
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Category Information')}}</h5>
            </div>
            <div class="card-body">

                <form class="form-horizontal" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @foreach (get_all_active_language() as $key => $language)

                    <div class="form-group row language-switcher-tabs " data-lang-switcher="{{$language->code}}" style="@if($language->code!='en')display:none @endif
                            ">
                        <div class="col-md-12">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{translate('Name')}}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                <div class="col-md-9">
                                    <input type="text" placeholder="{{translate('name_'.$language->code)}}" id="name{{$language->code=='en'?'':'_'.$language->code}}" name="name{{$language->code=='en'?'':'_'.$language->code}}" class="form-control" value="{{old('name'. ($language->code=='en'?'':'_'.$language->code))}}">
                                    <div class="mt-3">
                                        @if($errors->has($language->code=='en' ? 'name'. '':'name'.'_'.$language->code))
                                        <span class="text-danger" role="alert">
                                            {{ translate($errors->get($language->code=='en' ? 'name'. '':'name'.'_'.$language->code)[0]) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row language-switcher-tabs" data-lang-switcher="{{$language->code}}" style="@if($language->code!='en')display:none @endif">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{translate('Description')}}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                <div class="col-md-9">
                                    <textarea rows="10" cols="30" type="text" placeholder="{{translate('Description '. ($language->code=='en'?'':$language->code))}}" id="description{{$language->code=='en'?'':'_'.$language->code}}" name="description{{$language->code=='en'?'':'_'.$language->code}}" class="form-control">{{old('description'. ($language->code=='en'?'':'_'.$language->code))}}</textarea>

                                    <div class="mt-3">
                                        @if($errors->has($language->code=='en' ? 'description'. '':'description'.'_'.$language->code))
                                        <span class="text-danger" role="alert">
                                            {{ translate($errors->get($language->code=='en' ? 'description'. '':'description'.'_'.$language->code)[0]) }}
                                        </span>

                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach


                    <div class="form-group row not-translatable">
                        <label class="col-md-3 col-form-label">{{translate('Type')}}</label>
                        <div class="col-md-9">
                            <select name="digital" onchange="categoriesByType(this.value)" required class="form-control aiz-selectpicker mb-2 mb-md-0">
                                <option value="0">{{translate('Physical')}}</option>
                                <!--<option value="1">{{translate('Digital')}}</option>-->
                            </select>
                        </div>
                    </div>



                    <div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label">{{translate('Parent Category')}}</label>
                        <div class="col-md-9">
                                <input type="text" id="search_input" class="form-control" placeholder="Search">
                                <div class="h-300px overflow-auto c-scrollbar-light">

                                <div id="jstree"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="selected_parent_id" name="parent_id" value="">


                    <div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label">
                            {{translate('Ordering Number')}}
                        </label>
                        <div class="col-md-9">
                            <input type="number" name="order_level" class="form-control" id="order_level" value="{{old('order_level')}}" placeholder="{{translate('Order Level')}}">
                            <small>{{translate('Higher number has high priority')}}</small>
                        </div>
                    </div>
                    <!--<div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Banner')}} <small>({{ translate('200x200') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="banner" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Icon')}} <small>({{ translate('32x32') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="icon" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>-->
                    <div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Cover Image')}} <small>({{ translate('360x360') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="cover_image" class="selected-files">
                            </div>
                            <br>
                            <div>
                                @if($errors->has('cover_image'))
                                <span class="text-danger" role="alert">{{ translate('Cover Image is required')}}</span>
                                @endif
                            </div>

                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>

                    @foreach (get_all_active_language() as $key => $language)
                    <div class="form-group row  language-switcher-tabs" data-lang-switcher="{{$language->code}}" style="@if($language->code!='en')display:none @endif">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{translate('Meta Title')}}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="meta_title{{$language->code=='en'?'':'_'.$language->code}}" placeholder="{{translate('Meta Title '. ($language->code=='en'?'':$language->code))}}" value="{{old('meta_title'. ($language->code=='en'?'':'_'.$language->code))}}">
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="form-group row  language-switcher-tabs" data-lang-switcher="{{$language->code}}" style="@if($language->code!='en')display:none @endif">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{translate('Meta Description')}}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                <div class="col-md-9">
                                    <textarea name="meta_description{{$language->code=='en'?'':'_'.$language->code}}" rows="5" placeholder="{{translate('Meta Description '. ($language->code=='en'?'':$language->code))}}" class="form-control"> {{old('meta_description'. ($language->code=='en'?'':'_'.$language->code))}}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    @endforeach

                    @if (get_setting('category_wise_commission') == 1)
                    <div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label">{{translate('Commission Rate')}}</label>
                        <div class="col-md-9 input-group">
                            <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Commission Rate')}}" value="{{old('commision_rate')}}" id="commision_rate" name="commision_rate" class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label">{{translate('Category Attributes')}}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" onchange="load_filtring_attributes()" id="category_attributes" name="category_attributes[]" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true" multiple>

                            </select>
                        </div>
                    </div>

                    <div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label">{{translate('Filtering Attributes')}}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" id="filtering_attributes" name="filtering_attributes[]" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true" multiple>
                                @foreach (\App\Models\Attribute::all() as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label">{{translate('featured')}}</label>
                        <div class="col-md-3">
                            <input type="checkbox" class="form-control" checked name="featured" style="width: 20px; height:20px">
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<script type="text/javascript">
    function load_filtring_attributes() {
        let parent_category = document.getElementById('selected_parent_id');
        if (parent_category) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('categories.parent-attributes') }}',

                data: {
                    category_id: parent_category.value
                },
                success: function(data) {
                    let innerhtmlselect = '';
                    data.forEach(element => {
                        innerhtmlselect += '<option value="' + element.id + '">' + element.name + '</option>';
                    });
                    /////////////////////////////////////////
                    var selectElement = document.getElementById('category_attributes');
                    var selectedOptionIds = [];

                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].selected) {
                            var optionObject = {
                                key: selectElement.options[i].value,
                                value: selectElement.options[i].text
                            };
                            selectedOptionIds.push(optionObject);
                            innerhtmlselect += '<option value="' + selectElement.options[i].value + '">' + selectElement.options[i].text + '</option>';
                        }
                    }
                    console.log(selectedOptionIds);
                    /////////////////////////////////////////
                    $('select[id="filtering_attributes"]').html(innerhtmlselect);
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
        }


    }

    function load_categories_attributes() {
        let parent_category = document.getElementById('selected_parent_id');
        if (parent_category) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('categories.categories-attributes') }}',


                data: {
                    category_id: parent_category.value
                },
                success: function(data) {
                    console.log(data);
                    let innerhtmlselect = '';
                    data.forEach(element => {
                        innerhtmlselect += '<option value="' + element.id + '">' + element.name + '</option>';
                    })
                    $('select[id="category_attributes"]').html(innerhtmlselect);
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('categories.parent-attributes') }}',

                data: {
                    category_id: parent_category.value
                },
                success: function(data) {
                    let innerhtmlselect = '';
                    data.forEach(element => {
                        innerhtmlselect += '<option value="' + element.id + '">' + element.name + '</option>';
                    });
                    /////////////////////////////////////////
                    var selectElement = document.getElementById('category_attributes');
                    var selectedOptionIds = [];

                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].selected) {
                            innerhtmlselect += '<option value="' + selectElement.options[i].value + '">' + selectElement.options[i].text + '</option>';
                        }
                    }
                    console.log(selectedOptionIds);
                    /////////////////////////////////////////
                    $('select[id="filtering_attributes"]').html(innerhtmlselect);
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });

        }
    }
</script>



<script>
    $(function() {
        var lastSearchTerm = null; // Keep track of the last search term

        $('#jstree').jstree({
            'core': {
                'data': {
                    "url": function(node) {
                        return "{{ route('categories.jstree') }}";
                        if (lastSearchTerm) {
                            url += "?search=" + lastSearchTerm;
                        }
                        return url;
                    },
                    "data": function(node) {
                        return {
                            "id": node.id
                        };
                    },
                    "dataType": "json"
                },
                'check_callback': true,
                'themes': {
                    'responsive': false
                }
            },
            "plugins": ["wholerow", "state", "search"] // Include the search plugin here
        }).on("changed.jstree", function(e, data) {
            if (data && data.selected && data.selected.length) {
                var selectedId = data.selected[0]; // Get the ID of the first selected node
                $('#selected_parent_id').val(selectedId); // Update hidden input with selected ID

                // Call your functions to load attributes
                load_categories_attributes();
                load_filtring_attributes();
            }
        });

        // Search configuration
        var to = false;
        $('#search_input').keyup(function() {
            if (to) {
                clearTimeout(to);
            }
            to = setTimeout(function() {
                var v = $('#search_input').val();
                lastSearchTerm = v;
                $('#jstree').jstree(true).search(v);
            }, 250);
        });
    });
</script>


@endsection