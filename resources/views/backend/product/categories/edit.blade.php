@extends('backend.layouts.app')

@section('content')

@php
CoreComponentRepository::instantiateShopRepository();
CoreComponentRepository::initializeCache();
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Category Information')}}</h5>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill border-light">
                    @foreach (get_all_active_language() as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('categories.edit', ['id'=>$category->id, 'lang'=> $language->code] ) }}">
                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                            <span>{{$language->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <form class="p-4" action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                        <div class="col-md-9">
                            <input type="text" name="name" value="{{ $category->getTranslation('name', $lang) }}" class="form-control" id="name" placeholder="{{translate('Name')}}" required>
                            @if($errors->has('name'))
                            <span class="text-danger" role="alert">
                                {{ $errors->first('name') }}
                            </span>
                        @endif
                        </div>
                        
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('description')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                        <div class="col-md-9">
                            <input type="text" name="description" value="{{ $category->getTranslation('description', $lang) }}" class="form-control" id="name" placeholder="{{translate('description')}}" required>
                            @if($errors->has('description'))
                            <span class="text-danger" role="alert">
                                {{ $errors->first('description') }}
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group row  not-translatable" >
                        <label class="col-md-3 col-form-label">{{translate('Parent Category')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item">
                                    @if ($loop->last)
                                        <strong>{{ $breadcrumb->name }}</strong> {{-- Active item --}}
                                    @else
                                        <a href="#">{{ $breadcrumb->name }}</a>
                                    @endif
                                </li>
                            @endforeach
                            <li class="breadcrumb-item">
                                <a href="#" onclick="showCategoryParentTree()" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </li>
                        </ol>
                            <div id="category_parent_tree" style="display: none;">
                                <input type="text" id="search_input" class="form-control" placeholder="Search">
                                <div class="h-300px overflow-auto c-scrollbar-light">

                                    <div id="jstree"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="selected_parent_id" name="parent_id" value="{{$category->parent_id}}">

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Type')}}</label>
                        <div class="col-md-9">
                            <select name="digital" onchange="categoriesByType(this.value)" required class="form-control aiz-selectpicker mb-2 mb-md-0">
                                <option value="0" @if ($category->digital == '0') selected @endif>{{translate('Physical')}}</option>
                            </select>
                        </div>
                    </div>



                    <div class="form-group row not-translatable">
                        <label class="col-md-3 col-form-label" for="formFile">{{ translate('Thumbnail Image') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="file" name="thumbnail_image" id="formFile" accept="image/jpeg, image/png, image/gif" onchange="previewImage(event)">
                            <div style="margin-top: 15px;">
                                <!-- Existing Image or Placeholder for New Selection -->
                                <img id="thumbnailPreview" src="{{ isset($category->thumbnail_image) ? asset('public/'.$category->thumbnail_image) : asset('public/landscape-placeholder.svg') }}" alt="Thumbnail Image" style="width: 100px; height: auto;">
                            </div>
                            @if($errors->has('thumbnail_image'))
                                <span class="text-danger" role="alert">{{ translate('Thumbnail Image is required') }}</span>
                            @endif
                        </div>
                    </div>



                    <div class="form-group row  not-translatable">
                        <label class="col-md-3 col-form-label">{{translate('featured')}}</label>
                        <div class="col-md-3">
                            <input type="checkbox" class="form-control" @if($category->featured) checked @endif name="featured" style="width: 20px; height:20px">
                        </div>
                    </div>




                    <div class="form-group row not-translatable">
                        <label class="col-md-3 col-form-label" for="categoryImageInput">{{ translate('Category Image') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="file" name="cover_image" id="categoryImageInput" accept="image/jpeg, image/png, image/gif" onchange="previewCategoryImage(event)">
                            <div style="margin-top: 15px;">
                                <!-- Placeholder for New Selection or Existing Image -->
                                <img id="categoryImagePreview" src="{{ isset($category->cover_image) ? asset('public/'.$category->cover_image) : asset('public/landscape-placeholder.svg') }}" alt="Category Image" style="width: 100px; height: auto;">
                            </div>
                            @if($errors->has('cover_image'))
                                <span class="text-danger" role="alert">{{ translate('Category Image is required') }}</span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Meta Title')}}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title" value="{{ $category->getTranslation('meta_title', $lang) }}" placeholder="{{translate('Meta Title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Meta Description')}}</label>
                        <div class="col-md-9">
                            <textarea name="meta_description" rows="5" class="form-control">{{ $category->getTranslation('meta_description', $lang) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Slug')}}</label>
                        <div class="col-md-9">
                            <input type="text" disabled placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $category->slug }}" class="form-control">
                        </div>
                    </div>
                    @if (get_setting('category_wise_commission') == 1)
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Commission Rate')}}</label>
                        <div class="col-md-9 input-group">
                            <input type="number" lang="en" min="0" step="0.01" id="commision_rate" name="commision_rate" value="{{ $category->commision_rate }}" class="form-control">
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
                            <input type="hidden" value="{{ implode(',',$category_attributes->toArray())}}" id="category_has_attributes">
                            <input type="hidden" value="{{ implode(',',$category_filtring_attributes->toArray())}}" id="category_filtring_attributes">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Filtering Attributes')}}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" id="filtering_attributes" name="filtering_attributes[]" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true" data-selected="{{ $category->attributes->pluck('id') }}" multiple>
                                @foreach (\App\Models\Attribute::all() as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>
                                @endforeach
                            </select>
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
    function categoriesByType(val) {
        $('select[name="parent_id"]').html('');
        AIZ.plugins.bootstrapSelect('refresh');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '{{ route('categories.categories-by-type') }}',
            data: {
                digital: val
            },
            success: function(data) {
                $('select[name="parent_id"]').html(data);
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    }
    document.addEventListener("DOMContentLoaded", function() {
        // Your JavaScript code here
        load_categories_attributes();
        load_filtring_attributes();
    });

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
                    let myattributes = document.querySelector('#category_filtring_attributes').value;
                    let myattributesarray = myattributes.split(',');
                    let founded = false;
                    data.forEach(element => {
                        myattributesarray.forEach(el => {
                            if (el == element.id) {
                                founded = true;
                            }
                        })
                        if (founded == true) {

                            innerhtmlselect += '<option selected value="' + element.id + '">' + element.name + '</option>';
                            founded = false
                        } else {
                            innerhtmlselect += '<option  value="' + element.id + '">' + element.name + '</option>';

                        }
                    });
                    /////////////////////////////////////////
                    var selectElement = document.getElementById('category_attributes');
                    var selectedOptionIds = [];
                    let myfiltringattributes = document.querySelector('#category_filtring_attributes').value;
                    let myfiltringattributesarray = myfiltringattributes.split(',');
                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].selected) {

                            myfiltringattributesarray.forEach(el => {
                                if (el == selectElement.options[i].value) {
                                    founded = true;
                                }
                            })
                            if (founded == true) {
                                innerhtmlselect += '<option selected value="' + selectElement.options[i].value + '">' + selectElement.options[i].text + '</option>';
                                founded = false;
                            } else {
                                innerhtmlselect += '<option value="' + selectElement.options[i].value + '">' + selectElement.options[i].text + '</option>';

                            }
                        }
                    }
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
                    let innerhtmlselect = '';
                    let myattributes = document.querySelector('#category_has_attributes').value;
                    let myattributesarray = myattributes.split(',');
                    let founded = false;
                    data.forEach(element => {
                        myattributesarray.forEach(el => {
                            if (el == element.id) {
                                founded = true;
                            }
                        })
                        if (founded == true) {

                            innerhtmlselect += '<option selected value="' + element.id + '">' + element.name + '</option>';
                            founded = false;
                        } else {
                            innerhtmlselect += '<option value="' + element.id + '">' + element.name + '</option>';
                        }
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
                    let myattributes = document.querySelector('#category_filtring_attributes').value;
                    let myattributesarray = myattributes.split(',');
                    let founded = false;
                    data.forEach(element => {
                        myattributesarray.forEach(el => {
                            if (el == element.id) {
                                founded = true;
                            }
                        })
                        if (founded == true) {
                            innerhtmlselect += '<option selected value="' + element.id + '">' + element.name + '</option>';
                            founded = false
                        } else {
                            innerhtmlselect += '<option  value="' + element.id + '">' + element.name + '</option>';

                        }

                    });

                    console.log(innerhtmlselect)
                    /////////////////////////////////////////
                    var selectElement = document.getElementById('category_attributes');
                    var selectedOptionIds = [];
                    let myfiltringattributes = document.querySelector('#category_filtring_attributes').value;
                    let myfiltringattributesarray = myfiltringattributes.split(',');

                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].selected) {

                            myfiltringattributesarray.forEach(el => {
                                if (el == selectElement.options[i].value) {
                                    founded = true;
                                }
                            })
                            if (founded == true) {
                                innerhtmlselect += '<option selected value="' + selectElement.options[i].value + '">' + selectElement.options[i].text + '</option>';
                                founded = false;
                            } else {
                                innerhtmlselect += '<option  value="' + selectElement.options[i].value + '">' + selectElement.options[i].text + '</option>';
                            }
                        }
                    }
                    /////////////////////////////////////////
                    $('select[id="filtering_attributes"]').html(innerhtmlselect);
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
        }
    }
</script>
<script>
$(document).ready(function() {
        var parentCategoryId = "{{ $category->parent_id != 0 ? $category->parent_id : 1 }}"; 
       
        $('#jstree').jstree({
            'core': {
                'data': {
                    "url": "{{ route('categories.jstree') }}",
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
            "plugins": ["wholerow","search"] // Include the search plugin here
        }).on("changed.jstree", function(e, data) {
            if (data && data.selected && data.selected.length) {
                var selectedId = data.selected[0]; // Get the ID of the first selected node
                $('#selected_parent_id').val(selectedId); // Update hidden input with selected ID
                var categoryBreadcrumbsUrl = "{{ route('categories.breadcrumbs', ['id' => ':id']) }}";
                    categoryBreadcrumbsUrl = categoryBreadcrumbsUrl.replace(':id', selectedId); // Replace 'selectedId' with the actual ID using JavaScript

                 // Fetch the new breadcrumb path
                $.ajax({
                    url: categoryBreadcrumbsUrl,
                    type: 'GET',
                    success: function(response) {
                        var breadcrumbHtml = '';
                        response.forEach(function(breadcrumb) {
                            if (response.indexOf(breadcrumb) === response.length - 1) {
                                // Last item - active
                                breadcrumbHtml += '<li class="breadcrumb-item"><strong>' + breadcrumb.name + '</strong></li>';
                            } else {
                                // Other items - links
                                breadcrumbHtml += '<li class="breadcrumb-item"><a href="#">' + breadcrumb.name + '</a></li>';
                            }
                        });
                        // Add the edit icon at the end
                        breadcrumbHtml += '<li class="breadcrumb-item"><a href="#" onclick="showCategoryParentTree()" title="Edit"><i class="fas fa-edit"></i></a></li>';

                        // Update the breadcrumb container
                        $('.breadcrumb').html(breadcrumbHtml);
                    }
                });
                // Call your functions to load attributes
                load_categories_attributes();
                load_filtring_attributes();
            }
        }).on("ready.jstree", function(e, data) {
        // Pre-select the node once the tree is fully loaded
        $('#jstree').jstree("select_node", parentCategoryId);
    });



    });


    // Search configuration
    var to = false;
    $('#search_input').keyup(function() {
        if (to) {
            clearTimeout(to);
        }
        to = setTimeout(function() {
            var v = $('#search_input').val();
            if (v === "") {
                lastSearchTerm = null;
                // Explicitly reset the URL for the initial data load
                $('#jstree').jstree(true).settings.core.data.url = "{{ route('categories.jstree') }}";

                $('#jstree').jstree(true).settings.core.data.data = function(node) {
                        return {
                            "id": node.id
                        };
                    },
                    $('#jstree').jstree(false, true).refresh(); // Refresh the tree to load initial data
            } else {
                lastSearchTerm = v;
                $.ajax({
                    url: "{{ route('categories.jstreeSearch') }}", // Your actual API endpoint
                    type: 'GET', // Or 'POST', depending on your API
                    dataType: 'json', // Expected data format from API
                    data: {
                        searchTerm: v // Send the search term to your API
                    },
                    success: function(response) {
                        console.log(response);
                        // Assuming 'response' contains the data to update the jstree
                        // You will need to process 'response' to fit your jstree's data format

                        // Example: clear the existing jstree and populate with new data
                        $('#jstree').jstree(true).settings.core.data = response;
                        $('#jstree').jstree(true).refresh();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error during search API call:", status, error);
                    }
                });
            }
        }, 250);
    });
</script>


<script>
    function showCategoryParentTree() {
        $('#category_parent_tree').show();
    }
</script>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('thumbnailPreview');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<script>
function previewCategoryImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('categoryImagePreview');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection