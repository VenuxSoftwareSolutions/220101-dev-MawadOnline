@extends('seller.layouts.app')

@section('panel_content')

<style>
.pagination .active .page-link
{
    background-color: #8f97ab !important;
}

.pagination .page-link:hover{
    background-color: #8f97ab !important;
}

.pagination-showin{
    Weight:400;
    size: 16px;
    line-height: 24px;
    color: #808080;
}

thead tr{
    height: 53px !important;
    padding: 0 !important;
    margin: 0 !important;
}
/* thead th{
    padding: 0 !important;
    margin: 0 !important;
} */

.aiz-table th {
    padding: 0 !important;
    vertical-align: middle !important;
}

.remove-top-padding {
    padding-top: 0 !important;
}
</style>

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h2 class="h3">{{ translate('All Products') }}</h2>
            <div class="row">
                <div class="col-md-8">
                    <p style="font-size: 16px;">{{ translate('Quickly access and organize your inventory with intuitive tools and features. Simplify your
                        inventory management process and stay in control of your products effortlessly.') }}</p>
                </div>
                @can('seller_create_product')
                <div class="col-md-4">
                    <div class="text-md-right">
                        {{-- <a href="{{ route('seller.product_bulk_upload.index')}}" class="btn btn-secondary btn-lg">
                        <i class="las la-plus la-1x text-white"></i> {{ translate('Bulk upload') }}</a>  --}}

                        <a href="{{ route('seller.products.create')}}" class="btn btn-secondary btn-lg">
                        <i class="las la-plus la-1x text-white"></i> {{ translate('Add New Product') }}</a>
                    </div>

                </div>
                @endcan
            </div>
        </div>
    </div>

    <div class="row gutters-10 justify-content-center">
        @if (addon_is_activated('seller_subscription'))
            <!-- <div class="col-md-4 mx-auto mb-3" >
                <div class="bg-grad-1 text-white rounded-lg overflow-hidden">
                  <span class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3">
                      <i class="las la-upload la-2x text-white"></i>
                  </span>
                  <div class="px-3 pt-3 pb-3">
                      {{-- <div class="h4 fw-700 text-center">{{ max(0, auth()->user()->shop->product_upload_limit - auth()->user()->products()->count()) }}</div> --}}
                      <div class="opacity-50 text-center">{{  translate('Remaining Uploads') }}</div>
                  </div>
                </div>
            </div> -->
        @endif

        <!-- <div class="col-md-4 mx-auto mb-3" > -->
            @can('seller_create_product')
            <!-- <a id="step1" href="{{ route('seller.products.create')}}">
                <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                    <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                        <i class="las la-plus la-3x text-white"></i>
                    </span>
                    <div class="fs-18 text-primary">{{ translate('Add New Product') }}</div>
                </div>
              </a> -->
            @endcan
        <!-- </div> -->

        {{-- @if (addon_is_activated('seller_subscription'))
        @php
            // $seller_package = \App\Models\SellerPackage::find(Auth::user()->shop->seller_package_id);
            $seller_package = null;
        @endphp
        <div class="col-md-4">
            <a href="{{ route('seller.seller_packages_list') }}" class="text-center bg-white shadow-sm hov-shadow-lg text-center d-block p-3 rounded">
                @if($seller_package != null)
                    <img src="{{ uploaded_asset($seller_package->logo) }}" height="44" class="mw-100 mx-auto">
                    <span class="d-block sub-title mb-2">{{ translate('Current Package')}}: {{ $seller_package->getTranslation('name') }}</span>
                @else
                    <i class="la la-frown-o mb-2 la-3x"></i>
                    <div class="d-block sub-title mb-2">{{ translate('No Package Found')}}</div>
                @endif
                <div class="btn btn-outline-primary py-1">{{ translate('Upgrade Package')}}</div>
            </a>
        </div>
        @endif --}}

    </div>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col-md-4">
                <div id="step3" class="input-group input-group-sm">
                <input type="text" class="form-control" id="search" name="search" @isset($search) value="{{ $search }}" @endisset placeholder="{{ translate('Search product') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i> <!-- Assuming you're using Font Awesome for icons -->
                    </button>
                </div>
            </div>

                </div>

                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal"> {{translate('Delete selection')}}</a>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <table id="step2" class="table aiz-table mb-0">
                    <thead>
                        <tr style="background-color: #f8f8f8;">
                            <th style="padding-left: 12px !important;">
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th width="30%" style="padding-left: 12px !important;">{{ translate('Name')}}</th>
                            <th width="30%" style="padding-left: 12px !important;">{{ translate('SKU')}}</th>

                            {{-- <th data-breakpoints="md">{{ translate('Category')}}</th> --}}
                            <th data-breakpoints="md">{{ translate('QTY')}}</th>
                            <th>{{ translate('Base Price')}}</th>
                            <th data-breakpoints="md">{{ translate('Status')}}</th>
                            <th data-breakpoints="md">{{ translate('Draft')}}</th>
                            <th data-breakpoints="md">{{ translate('Published')}}</th>
                            <th data-breakpoints="md" style="text-align: center;">{{ translate('Actions')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>
                                    <div class="form-group d-inline-block">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]" value="{{$product->id}}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('product', $product->slug) }}" target="_blank" class="text-reset">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ $product->sku }}
                                </td>
                                {{-- <td>
                                    @if ($product->main_category != null)
                                        {{ $product->main_category->getTranslation('name') }}
                                    @endif
                                </td> --}}
                                <td>
                                    {{$product->getTotalQuantity()}}
                                </td>
                                <td>{{ count($product->getChildrenProducts()) > 0 ? '--' : $product->getPriceRange() }}</td>
                                <td>
                                    @if ($product->is_draft == 0)
                                        @if($product->is_parent == 0)
                                            @switch($product->approved)
                                                @case(0)
                                                    <span class="badge badge-primary width-badge width-badge">{{ translate('Pending')}}</span>
                                                @break

                                                @case(1)
                                                <span class="badge badge-success width-badge width-badge" style="background-color: #35C658 !important;">{{ translate('Approved')}}</span>
                                                    @break
                                                @case(4)
                                                    <span class="badge badge-info width-badge width-badge">{{ translate('Under Review')}}</span>
                                                    @break
                                                @case(2)
                                                <span class="badge badge-warning width-badge width-badge">{{ translate('Revision Required')}}</span>
                                                    @break
                                                @case(3)
                                                    <span class="badge badge-danger width-badge width-badge">{{ translate('Rejected')}}</span>
                                                        @break
                                            @endswitch
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($product->is_draft == 1)
                                        <span>{{ translate('Yes')}}</span>
                                    @else
                                        <span>{{ translate('No')}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(count($product->getChildrenProducts()) == 0)
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="{{ $product->id }}" class="publsihed_product" @if($product->approved != 1) disabled @endif type="checkbox" <?php if($product->published == 1 && $product->approved == 1) echo "checked";?> >
                                            <span class=""> </span>
                                        </label>
                                    @endif
                                </td>
                                <td class="text-right remove-top-padding">
                                        <a class="btn btn-sm" href="{{route('seller.products.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')])}}" title="{{ translate('Edit') }}">
                                            <img src="{{asset('public/Edit.svg')}}">
                                        </a>
                                        {{-- <a href="{{route('seller.products.duplicate', $product->id)}}" class="btn btn-soft-success btn-icon btn-circle btn-sm"  title="{{ translate('Duplicate') }}">
                                            <i class="las la-copy"></i>
                                        </a> --}}

                                        <a href="#" class="btn btn-sm confirm-delete" data-href="{{route('seller.products.destroy', $product->id)}}" title="{{ translate('Delete') }}">
                                            <img src="{{asset('public/trash.svg')}}">
                                        </a>
                                </td>
                            </tr>
                            @if(count($product->getChildrenProducts()) > 0)
                                @foreach ($product->getChildrenProducts() as $children)
                                    <tr>
                                        <td>
                                            <div class="form-group d-inline-block">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]" value="{{$children->id}}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td >
                                            <a href="{{ route('product', $children->slug) }}" @if(app()->getLocale() == "ae") style="margin-right: 34px !important" @else style="margin-left: 34px !important" @endif target="_blank" class="text-reset">
                                                {{ $children->name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $children->sku }}
                                        </td>
                                        <td>
                                        {{$children->getTotalQuantity()}}
                                        </td>
                                        <td>{{ $children->getPriceRange() }}</td>
                                        <td>
                                            @if ($children->is_draft == 0)
                                                @switch($children->approved)
                                                    @case(0)
                                                        <span class="badge badge-primary width-badge">{{ translate('Pending')}}</span>
                                                    @break

                                                    @case(1)
                                                    <span class="badge badge-success width-badge">{{ translate('Approved')}}</span>
                                                        @break
                                                    @case(4)
                                                        <span class="badge badge-info width-badge">{{ translate('Under Review')}}</span>
                                                        @break
                                                    @case(2)
                                                    <span class="badge badge-warning width-badge">{{ translate('Revision Required')}}</span>
                                                        @break
                                                    @case(3)
                                                        <span class="badge badge-danger width-badge">{{ translate('Rejected')}}</span>
                                                            @break
                                                @endswitch
                                            @endif
                                        </td>
                                        <td>
                                            @if ($children->is_draft == 1)
                                                <span>{{ translate('Yes')}}</span>
                                            @else
                                                <span>{{ translate('No')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input value="{{ $children->id }}" class="publsihed_product" @if($children->approved != 1) disabled @endif id="{{ $children->id }}" type="checkbox" <?php if($children->published == 1) echo "checked";?> >
                                                <span class=""></span>
                                            </label>
                                        </td>
                                        <td class="text-right remove-top-padding">
                                            {{-- <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{route('seller.products.edit', ['id'=>$children->id, 'lang'=>env('DEFAULT_LANGUAGE')])}}" title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                            <a href="{{route('seller.products.duplicate', $children->id)}}" class="btn btn-soft-success btn-icon btn-circle btn-sm"  title="{{ translate('Duplicate') }}">
                                                <i class="las la-copy"></i>
                                            </a> --}}
                                            @if($product->approved != 4)
                                                <a href="#" class="btn btn-sm confirm-delete" data-href="{{route('seller.products.destroy', $children->id)}}" title="{{ translate('Delete') }}">
                                                    <img src="{{asset('public/trash.svg')}}">
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-6" style="padding-top: 11px; !important">
                        <p class="pagination-showin">Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }}</p>
                    </div>
                    <div class="col-6">
                        <div class="pagination-container text-right"  style="float: right;">
                            {{ $products->links('custom-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="modal-info" class="modal fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6" id="title-modal">{{translate('Delete Confirmation')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <input type="hidden" id="status">
                <input type="hidden" id="product_id">
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14" id="text-modal">{{translate('Are you sure to delete this?')}}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal" id="cancel_published">{{translate('Cancel')}}</button>
                    <button type="button" id="publish-link" class="btn btn-primary rounded-0 mt-2"></button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-success" class="modal fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6" id="title-modal-success">{{translate('Delete Confirmation')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <input type="hidden" id="status">
                <input type="hidden" id="product_id">
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14" id="text-modal-success">{{translate('Are you sure to delete this?')}}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal">{{translate('OK')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')
@endsection

@section('script')
    <script type="text/javascript">

        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        $('body').on('change', '.publsihed_product', function(){
            var current = $(this);
            var id = $(this).val();
            if($(this).is(':not(:checked)')){
                var published = 0;
                var message = "Do you want to unpublish ?";
                var message_button = "Unpublish";
                var message_success = "The product unpublished successfully";
                var message_icon = "Unpublished"
            }else{
                var published = 1;
                var message = "Do you want to publish ?";
                var message_button = "Publish";
                var message_success = "The product published successfully";
                var message_icon = "Published"
            }

            if(id != undefined){
                $("#title-modal").text('{{ translate("Publish Product") }}');
                $("#text-modal").text(message);
                $("#publish-link").text(message_button);
                $("#status").val(published);
                $("#product_id").val(id);

                $("#modal-info").modal('show')
            }
        });

        $('body').on('click', '#cancel_published', function(){
            $('#modal-info').modal('hide');
            var id = $("#product_id").val();
            var published = $("#status").val();

            if((id != '') && (id != undefined)){
                if(published == 0){
                    $('#' + id).prop('checked', true)
                }else{
                    $('#' + id).prop('checked', false)
                }
            }

            $("#product_id").val('');
            $("#status").val('');
        })

        $('body').on('click', '#publish-link', function(){
            $("#modal-info").modal('hide');
            var id = $("#product_id").val();
            var published = $("#status").val();

            if(published == 0){
                var message_success = "The product unpublished successfully";
                var message_icon = "Unpublished"
            }else{
                var message_success = "The product published successfully";
                var message_icon = "Published"
            }

            if((id != '') && (id != undefined)){
                $.ajax({
                    url: "{{ route('seller.products.published') }}",
                    type: "GET",
                    data: {
                        status: published,
                        id: id
                    },
                    cache: false,
                    dataType: 'JSON',
                    success: function(dataResult) {
                        if(dataResult.status == 'success'){
                            $("#title-modal-success").text(message_icon);
                            $("#text-modal-success").text(message_success);

                            $("#modal-success").modal('show')
                        }
                    }
                })
            }
        });

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('seller.products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('seller.products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                }
                else if(data == 2){
                    AIZ.plugins.notify('danger', '{{ translate('Please upgrade your package.') }}');
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.products.bulk-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('startTourButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default anchor click behavior
        localStorage.setItem('guide_tour', '0'); // Set local storage as required
        window.location.href = '{{ route("seller.dashboard") }}'; // Redirect to the dashboard
    });
    if (localStorage.getItem('guide_tour') != '0') {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}} ) {
            return;
        }
    }
        var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->getTranslation('title')}}',
                intro: "{{$step->getTranslation('description')}}",
                position: '{{ $step->getTranslation('lang') === 'en' ? 'right' : 'left' }}'
            },
            @endforeach
        ];
        var lang = '{{$tour_steps[0]->getTranslation('lang')}}';
        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
            nextLabel: lang == 'en' ? 'Next' : 'التالي',
            prevLabel: lang == 'en' ? 'Back' : 'رجوع',
            exitOnEsc : false ,
            exitOnOverlayClick : false ,
            disableInteraction : true ,
            overlayOpacity : 0.4 ,
            showStepNumbers : true ,
            hidePrev : true ,
            showProgress :true ,
        });

            tour.onexit(function() {
                $.ajax({
                url: "{{ route('seller.tour') }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' }, // Include CSRF token for Laravel
                success: function(response) {
                    // Handle success
                    console.log('User tour status updated successfully');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating user tour status:', error);
                }
            });
            localStorage.setItem('guide_tour', '1'); // Set local storage as required
            setTimeout(function() {
                window.location.href = '{{ route("seller.dashboard") }}';
            }, 500);
            });

            tour.onbeforechange(function(targetElement) {

                if (this._direction === 'backward') {
                window.location.href = '{{ route("seller.dashboard") }}'; // Redirect to another page
                sleep(60000);
                }

                step_number += 1 ;
                if (step_number == 3) {

                window.location.href = '{{ route("catalog.search_page") }}';
                sleep(60000);
                }

                //tour.exit();
            });

        tour.start();
        tour.goToStepNumber(2);
        });
    </script>
@endsection
