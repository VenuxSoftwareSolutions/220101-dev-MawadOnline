@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Categories')}}</h1>
        </div>
        @can('add_product_category')
            <div class="col-md-6 text-md-right">
                <a href="{{ route('categories.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New category')}}</span>
                </a>
            </div>
        @endcan
    </div>
</div>
<div class="card">
    <div class="card-header d-block d-md-flex">
        <h5 class="mb-0 h6">{{ translate('Categories') }}</h5>
        <form class="" id="sort_categories" action="" method="GET">
            <div class="box-inline pad-rgt pull-left">
                <div class="" style="min-width: 200px;">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0" id="categories-table">
            <thead>
                <tr>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{ translate('Parent Category') }}</th>
                    <th data-breakpoints="lg">{{ translate('order_level') }}</th>
                    <th data-breakpoints="lg">{{ translate('Level') }}</th>
                    <!--<th data-breakpoints="lg">{{translate('Banner')}}</th>
                    <th data-breakpoints="lg">{{translate('Icon')}}</th>-->
                    <th data-breakpoints="lg">{{translate('cover_image')}}</th>
                    <th data-breakpoints="lg">{{translate('Featured')}}</th>
                    <th data-breakpoints="lg">{{translate('Commission')}}</th>
                    <th width="10%" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $key => $category)
                    <tr id="level-{{$category->id}}">


                        <td class="d-flex align-items-center">
                            @if(count($category->childrenCategories)>0)
                                <button id="mycatbutton-{{$category->id}}" style="border: 0px" data-expand="close" onclick="expandmysubcategories({{$category->id}})">></button>
                             @endif
                            {{ $category->getTranslation('name') }}
                            @if($category->digital == 1)
                                <img src="{{ static_asset('assets/img/digital_tag.png') }}" alt="{{translate('Digital')}}" class="ml-2 h-25px" style="cursor: pointer;" title="DIgital">
                            @endif
                         </td>
                        <td>
                            @php
                                $parent = \App\Models\Category::where('id', $category->parent_id)->first();
                            @endphp
                            @if ($parent != null)
                                {{ $parent->getTranslation('name') }}
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $category->order_level }}</td>
                        <td>{{ $category->level }}</td>

                        <td>
                            @if($category->icon != null)
                                <img src="{{ uploaded_asset($category->cover_image) }}" alt="{{translate('Cover Image')}}" class="h-50px">
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="update_featured(this)" value="{{ $category->id }}" <?php if($category->featured == 1) echo "checked";?>>
                                <span></span>
                            </label>
                        </td>
                        <td>{{ $category->commision_rate }} %</td>
                        <td class="text-right">
                            @can('edit_product_category')
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('categories.edit', ['id'=>$category->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            @endcan
                            @can('delete_product_category')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('categories.destroy', $category->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>


                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            <input type="hidden" value="{{$intermidiateparent_tostring}}" id="intermidiateparent" name="intermidiateparent">
            <input type="hidden" value="{{$mycategory_ids_tostring}}" id="mycategory_ids" name="mycategory_ids">
            {{ $categories->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@endsection


@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')

    <script type="text/javascript">
        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('categories.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured categories updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }



        function expandmysubcategories(id){

            let rightsvg = arrowdown();
            let downsvg = arrowright();
            let state = document.getElementById('mycatbutton-'+id);
            var target = state.closest("tr"); // Find the closest parent <tr> element

            if (target) {
            var currentClass = target.className;
            }
            if(state && state.getAttribute('data-expand')=="close"){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:"POST",
                    url:'{{ route('categories.getsubcategories') }}',
                    data:{
                    id: id,
                    classes : currentClass,
                    intermidiateparent : document.getElementById('intermidiateparent').value,
                    mycategory_ids : document.getElementById('mycategory_ids').value,
                    searchablestring : document.getElementById('search').value
                    },
                    success: function(response) {
                            $(response).insertAfter($('tr#level-'+id).closest('tr#level-'+id));
                            state.innerHTML  = downsvg;
                    }
                });
                state.setAttribute('data-expand','open');

            }else{
                if(state && state.getAttribute('data-expand')=="open"){
                    let subcategories = document.querySelectorAll('.subcategories-'+id);
                    if(subcategories.length>0 && subcategories[0].style !== undefined && subcategories[0].style.display == "none"){
                        subcategories.forEach(element => {
                            if(element.getAttribute('related-to')==id){
                                element.style.display ='';
                                state.innerHTML  = downsvg;
                            }

                        });
                    }else{
                        subcategories.forEach(element => {

                            if(element.getAttribute('related-to')==id)
                            {
                                element.style.display = "none";
                                state.innerHTML  = rightsvg;
                            }else{
                                element.style.display = "none";
                                let buttonsubcat= document.getElementById('mycatbutton-'+element.getAttribute('related-to'));
                                if(buttonsubcat){
                                    buttonsubcat.innerHTML = rightsvg;
                                }
                            }
                        });

                    }
                }
            }

        }


        function arrowdown(){
            return '<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><svg fill=\"#000000\" height=\"8px\" width=\"8px\" version=\"1.1\" id=\"Layer_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" 	 viewBox=\"0 0 330 330\" xml:space=\"preserve\"><path id=\"XMLID_222_\" d=\"M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001	c-5.857,5.858-5.857,15.355,0.001,21.213l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213	C82.322,328.536,86.161,330,90,330s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606	C255,161.018,253.42,157.202,250.606,154.389z\"/></svg>';
        }
        function arrowright(){
            return '<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><svg fill=\"#000000\" height=\"8px\" width=\"8px\" version=\"1.1\" id=\"Layer_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"  viewBox=\"0 0 330 330\" xml:space=\"preserve\"><path id=\"XMLID_225_\" d=\"M325.607,79.393c-5.857-5.857-15.355-5.858-21.213,0.001l-139.39,139.393L25.607,79.393	c-5.857-5.857-15.355-5.858-21.213,0.001c-5.858,5.858-5.858,15.355,0,21.213l150.004,150c2.813,2.813,6.628,4.393,10.606,4.393	s7.794-1.581,10.606-4.394l149.996-150C331.465,94.749,331.465,85.251,325.607,79.393z\"/></svg>';

        }


        document.addEventListener('DOMContentLoaded', function() {
            if(document.getElementById('search').value!=''){



                var checkCount = 0;

                function checkButtons() {
                    var buttonsWithAttribute = document.querySelectorAll('button[data-expand]');

                    buttonsWithAttribute.forEach(function(button) {
                        //if(button.getAttribute('data-expand')=='close')
                        //button.click();
                    });

                    // Increment the check count
                    checkCount++;

                    // Check if the maximum number of checks (7 times) has been reached
                    if (checkCount >= 7) {
                        clearInterval(intervalId); // Stop the interval
                        console.log('Maximum number of checks reached.');
                    }
                }

                // Set an interval to check the buttons every 5 seconds
                var intervalId = setInterval(checkButtons, 2000);

                // Run the initial check immediately
                checkButtons();
            }

        });

    </script>
@endsection
