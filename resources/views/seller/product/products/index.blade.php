@extends('seller.layouts.app')

@section('panel_content')

@push('styles')
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

        .aiz-table th {
            padding: 0 !important;
            vertical-align: middle !important;
        }

        .remove-top-padding {
            padding-top: 0 !important;
        }

        .product-name__clz {
            display: inline-block;
            max-width: 30ch;
            word-wrap: break-word;
        }

        .product-sku__clz {
            display: inline-block;
            max-width: 20ch;
            word-wrap: break-word;
        }

        #block-ui-overlay__id {
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .block-ui-spinner__clz {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
        }

        .scrollable-error-container__clz {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 0.5rem;
        }

        .scrollable-error-container__clz::-webkit-scrollbar {
            width: 8px;
        }
        .scrollable-error-container__clz::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .scrollable-error-container__clz::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .scrollable-error-container__clz::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
@endpush

<div id="block-ui-overlay__id" style="display:none;">
    <div class="block-ui-spinner__clz">
        <div class="spinner-border text-light" role="status"></div>
        <span class="ms-2 text-light">{{ __("Processing") }}...</span>
    </div>
</div>

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

                        <a href="{{ route('seller.products.create')}}" class="btn btn-primary btn-lg">
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
                <div class="col-3">
                    <div id="step3" class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search" name="search" @isset($search) value="{{ $search }}" @endisset placeholder="{{ translate('Search product') }}" />
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <select class="form-control aiz-selectpicker" id="productCategory" name="category_id" data-live-search="true">
                        <option value="">{{ __("All categories") }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select class="form-control aiz-selectpicker" id="productBuJob" name="bu_job_id" data-live-search="true">
                        <option value="">{{ __("All BU jobs") }}</option>
                        @foreach($bu_jobs as $bu_job)
                            <option value="{{ $bu_job->id }}" {{ request('bu_job_id') == $bu_job->id ? 'selected' : '' }}>{{ formatBuJob($bu_job) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-2 dropdown mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Actions')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="d-none dropdown-item confirm-alert" href="javascript:void(0)" data-target="#bulk-delete-modal"> {{translate('Delete selection')}}</a>
                        <a class="bulk-publish__clz dropdown-item confirm-alert" data-title="{{ __("Publish selection") }}" data-message="{{ translate('Are you sure to publish this selection?') }}" href="javascript:void(0)"> {{translate('Publish selection')}}</a>
                        <a class="bulk-unpublish__clz dropdown-item confirm-alert" data-title="{{ __("Unpublish selection") }}" href="javascript:void(0)" data-message="{{ translate('Are you sure to unpublish this selection?') }}"> {{translate('Unpulish selection')}}</a>
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
                            <th data-breakpoints="md">{{ translate('Category')}}</th>
                            <th data-breakpoints="md">{{ translate('Bu job ID')}}</th>
                            <th data-breakpoints="md">{{ translate('QTY')}}</th>
                            <th class="text-nowrap">{{ translate('Unit of Sale Price') }}</th>
                            <th class="text-center" data-breakpoints="md">{{ translate('Status')}}</th>
                            <th data-breakpoints="md">{{ translate('Draft')}}</th>
                            <th data-breakpoints="md">{{ translate('Published')}}</th>
                            <th class="text-center" data-breakpoints="md">{{ translate('Actions')}}</th>
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
                                        <span class="product-name__clz">
                                            {{ $product->name }}
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <span class="product-sku__clz">
                                        {{ $product->sku }}
                                    </span>
                                </td>
                                <td>
                                    @if ($product->main_category != null)
                                        {{ $product->main_category->getTranslation('name') }}
                                    @endif
                                </td>
                                <td>
                                    @if($product->bu_job !== null)
                                        {{ formatBuJob($product->bu_job, false) }}
                                    @endif
                                </td>
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
                                                <span class="product-name__clz">
                                                    {{ $children->name }}
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="product-sku__clz">
                                                {{ $children->sku }}
                                            </span>
                                        </td>
                                        <td></td>
                                        <td></td>
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
                        <p class="pagination-showin">
                            {{ __("product.showing_items_pagination", [
                                    "first" =>$products->firstItem(),
                                    "last" => $products->lastItem(),
                                    "total" => $products->total()
                                ]) }}
                        </p>
                    </div>
                    <div class="col-6">
                        <div class="pagination-container float-right">
                            {{ $products->links('custom-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')
    @include('modals.bulk_publish_modal')

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
                    <button type="button" class="btn btn-secondary rounded-0 mt-2 btn-ok" data-dismiss="modal">{{translate('OK')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("#modal-success").on("click", ".btn-ok", function () {
                location.reload();
            });

            $("#productCategory").on("change", function() {
                const categoryId = $(this).val();
                const url = new URL(window.location.href);

                if (categoryId) {
                    url.searchParams.set('category_id', categoryId);

                    url.searchParams.delete('page');
                } else {
                    url.searchParams.delete('category_id');
                }

                window.location.href = url.toString();
            });

            $("#productBuJob").on("change", function() {
                const buJobId = $(this).val();
                console.log({buJobId})
                const url = new URL(window.location.href);

                if (buJobId) {
                    url.searchParams.set('bu_job_id', buJobId);

                    url.searchParams.delete('page');
                } else {
                    url.searchParams.delete('bu_job_id');
                }

                window.location.href = url.toString();
            });

            $(".bulk-publish__clz,.bulk-unpublish__clz").click(function(event) {
                let title = $(event.target).data("title");
                let isProductsSelected = $('.check-one:checkbox:checked').length > 0;
                let message = isProductsSelected ?
                    $(event.target).data("message")
                    : "{{ __('Please select at least one product.') }}";

                let modal = $("#bulk-publish-modal");

                modal.find(".modal-title").text(title);
                modal.find(".modal-message").text(message);

                if(isProductsSelected === true) {
                    modal.find(".action-btn").each(function() {
                        if(title.includes("{{ __("Publish ") }}")) {
                            $(modal.find(".action-btn")[0]).removeClass("d-none");
                            $(modal.find(".action-btn")[1]).addClass("d-none");
                        } else {
                            $(modal.find(".action-btn")[1]).removeClass("d-none");
                            $(modal.find(".action-btn")[0]).addClass("d-none");
                        }
                    });
                }

                modal.modal("show");
            });
        });

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
            let current = $(this);
            let id = $(this).val();

            let published = 1;
            let message = "Do you want to publish ?";
            let message_button = "Publish";
            let message_success = "The product published successfully";
            let message_icon = "Published";

            if($(this).is(':not(:checked)')) {
                published = 0;
                message = "Do you want to unpublish ?";
                message_button = "Unpublish";
                message_success = "The product unpublished successfully";
                message_icon = "Unpublished";
            }

            if(id != undefined) {
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
            let id = $("#product_id").val();
            let published = $("#status").val();
            let message_success = "The product published successfully";
            let message_icon = "Published"

            if(published == 0) {
                let message_success = "The product unpublished successfully";
                let  message_icon = "Unpublished"
            }

            if((id != '') && (id != undefined)) {
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
            let data = new FormData($('#sort_products')[0]);
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
                    $("#bulk-delete-modal").modal("hide")

                    if(response.error == false) {
                        $("#title-modal-success").text("Bulk Delete");
                        $("#text-modal-success").text(response.message);

                        $("#modal-success").modal('show')
                    } else {
                        // @todo
                    }
                }
            });
        }

        function bulk_publish(publish = true) {
            let data = new FormData();

            let productsIds = [];

            $('.check-one:checkbox:checked').each(function() {
                productsIds.push($(this).val());
            });

            if (productsIds.length === 0) {
                alert('Please select at least one product.');
                return;
            }

            data.append('products_ids', JSON.stringify(productsIds));
            data.append('publish', publish ? 1 : 0);

            $("#block-ui-overlay__id").show();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.products.bulk-publish')}}",
                type: 'POST',
                data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#bulk-publish-modal").modal("hide");
                    $("#block-ui-overlay__id").hide();

                    if(response.error === true) {
                        let errorCount = Object.keys(response.error_details || {}).length;
                        let needsScroll = errorCount > 10;

                        let errorsText = `
                            <p>${response.message}:</p>
                            <div class="${needsScroll ? 'scrollable-error-container__clz' : ''}">
                                <ul class="list-unstyled ${needsScroll ? 'mb-0' : ''}">
                        `;

                        Object.entries(response.error_details || {}).forEach(([_, message]) => {
                            errorsText += `<li class="py-1">{{ __("Product sku") }}: ${message}</li>`;
                        });

                        errorsText += `
                                </ul>
                            </div>
                            ${needsScroll ? `<small class="text-muted">Scroll to see all ${errorCount} errors</small>` : ''}
                        `;

                        $("#title-modal-success").text(`Bulk ${response.action}`);
                        $("#text-modal-success").html(errorsText);

                        $("#text-modal-success").addClass("text-left")
                        $("#modal-success").modal('show');
                    } else {
                        setTimeout(() => {
                            location.reload();
                        }, 500)
                    }
                },
                error: function(xhr) {
                    $("#block-ui-overlay__id").hide();
                    let response = xhr.responseJSON;
                    let errorMessage = response?.message || 'An error occurred.';

                    $("#bulk-publish-modal").find(".modal-message").html(
                        `<span class="d-flex alert alert-danger">${errorMessage}</span>`
                    );
                    $("#bulk-publish-modal").find(".action-btn").addClass("d-none");
                }
            });
        }
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
