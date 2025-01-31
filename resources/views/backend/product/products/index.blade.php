@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3">{{ translate('All products') }}</h1>
            </div>
        </div>
    </div>
    <br>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('All Product') }}</h5>
                </div>

                @can('product_delete')
                    <div class="dropdown mb-2 mb-md-0">
                        <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item confirm-alert" href="javascript:void(0)" data-target="#bulk-delete-modal">
                                {{ translate('Delete selection') }}</a>
                        </div>
                    </div>
                @endcan

                {{-- @if ($type == 'Seller')
            <div class="col-md-2 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="user_id" onchange="sort_products()">
                    <option value="">{{ translate('All Sellers') }}</option>
                    @foreach (App\Models\User::where('user_type', '=', 'seller')->get() as $key => $seller)
                        <option value="{{ $seller->id }}" @if ($seller->id == $seller_id) selected @endif>
                            {{ $seller->shop->name }} ({{ $seller->name }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            @if ($type == 'All' && get_setting('vendor_system_activation') == 1)
            <div class="col-md-2 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="user_id" onchange="sort_products()">
                    <option value="">{{ translate('All Sellers') }}</option>
                        @foreach (App\Models\User::where('user_type', '=', 'admin')->orWhere('user_type', '=', 'seller')->get() as $key => $seller)
                            <option value="{{ $seller->id }}" @if ($seller->id == $seller_id) selected @endif>{{ $seller->name }}</option>
                        @endforeach
                </select>
            </div>
            @endif --}}
                <div class="col-md-2 ml-auto">
                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="type" id="type"
                        onchange="sort_products()">
                        <option value="">{{ translate('Sort By') }}</option>
                        <option value="rating,desc"
                            @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'desc') selected @endif @endisset>
                            {{ translate('Rating (High > Low)') }}</option>
                        <option value="rating,asc"
                            @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'asc') selected @endif @endisset>
                            {{ translate('Rating (Low > High)') }}</option>
                        <option
                            value="num_of_sale,desc"@isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'desc') selected @endif @endisset>
                            {{ translate('Num of Sale (High > Low)') }}</option>
                        <option
                            value="num_of_sale,asc"@isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'asc') selected @endif @endisset>
                            {{ translate('Num of Sale (Low > High)') }}</option>
                        <option
                            value="unit_price,desc"@isset($col_name, $query) @if ($col_name == 'unit_price' && $query == 'desc') selected @endif @endisset>
                            {{ translate('Base Price (High > Low)') }}</option>
                        <option
                            value="unit_price,asc"@isset($col_name, $query) @if ($col_name == 'unit_price' && $query == 'asc') selected @endif @endisset>
                            {{ translate('Base Price (Low > High)') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control form-control-sm" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type & Enter') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th width="30%">{{ translate('Name') }}</th>
                            {{-- <th data-breakpoints="md">{{ translate('Category')}}</th> --}}
                            <th data-breakpoints="md">{{ translate('Current Qty') }}</th>
                            <th>{{ translate('Base Price') }}</th>
                            <th data-breakpoints="md">{{ translate('Status') }}</th>
                            <th data-breakpoints="md">{{ translate('Draft') }}</th>
                            <th data-breakpoints="md">{{ translate('Featured') }}</th>
                            <th data-breakpoints="md" class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>
                                    <div class="form-group d-inline-block">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]"
                                                value="{{ $product->id }}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('product', $product->slug) }}" target="_blank" class="text-reset">
                                        {{ $product->sku }}
                                    </a>
                                    <br>
                                    <small>{{ $product->getShopName() }}</small>
                                </td>
                                {{-- <td>
                                @if ($product->main_category != null)
                                    {{ $product->main_category->getTranslation('name') }}
                                @endif
                            </td> --}}
                                <td>
                                    @php
                                        $qty = 0;
                                        foreach ($product->stocks as $key => $stock) {
                                            $qty += $stock->qty;
                                        }
                                        echo $qty;
                                    @endphp
                                </td>
                                <td>{{ count($product->getChildrenProducts()) > 0 ? '--' : $product->getPriceRange() }}
                                </td>
                                <td>
                                    @if ($product->is_parent == 0)
                                        @switch($product->approved)
                                            @case(0)
                                                <span class="badge badge-primary width-badge"
                                                    style="background-color: #2E294E;">{{ translate('Pending') }}</span>
                                            @break

                                            @case(1)
                                                <span class="badge badge-success width-badge">{{ translate('Approved') }}</span>
                                            @break

                                            @case(4)
                                                <span class="badge badge-info width-badge">{{ translate('Under Review') }}</span>
                                            @break

                                            @case(2)
                                                <span
                                                    class="badge badge-warning width-badge">{{ translate('Revision Required') }}</span>
                                            @break

                                            @case(3)
                                                <span class="badge badge-danger width-badge">{{ translate('Rejected') }}</span>
                                            @break
                                        @endswitch
                                    @endif
                                </td>
                                <td>
                                    @if ($product->is_draft == 1)
                                        <span class="badge badge-inline badge-info">{{ translate('Yes') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-success">{{ translate('No') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_featured(this)" value="{{ $product->id }}" type="checkbox"
                                            <?php if ($product->seller_featured == 1) {
                                                echo 'checked';
                                            } ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                        href="{{ route('products.approve', $product->id) }}"
                                        title="{{ translate('Approve product') }}">
                                        <i class="las la-eye"></i>
                                    </a>
                                    @if ($product->CheckIfAddedToCatalog() == false)
                                        <button type="button" class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                            data-id="{{ $product->id }}" title="{{ translate('Add to catalog') }}">
                                            <i class="las la-plus" data-id="{{ $product->id }}"></i>
                                        </button>
                                    @endif
                                    {{-- <a href="{{route('seller.products.duplicate', $product->id)}}" class="btn btn-soft-success btn-icon btn-circle btn-sm"  title="{{ translate('Duplicate') }}">
                                    <i class="las la-copy"></i>
                                </a> --}}
                                </td>
                            </tr>
                            @if (count($product->getChildrenProducts()) > 0 && $product->is_draft == 0)
                                @foreach ($product->getChildrenProducts() as $children)
                                    <tr>
                                        <td>
                                            <div class="form-group d-inline-block">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]"
                                                        value="{{ $children->id }}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('product', $children->slug) }}"
                                                style="margin-left: 34px !important" target="_blank" class="text-reset">
                                                {{ $children->sku }}
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $qty = 0;
                                                foreach ($children->stocks as $key => $stock) {
                                                    $qty += $stock->qty;
                                                }
                                                echo $qty;
                                            @endphp
                                        </td>
                                        <td>{{ $children->getPriceRange() }}</td>
                                        <td>
                                            @switch($children->approved)
                                                @case(0)
                                                    <span class="badge badge-primary width-badge"
                                                        style="background-color: #2E294E;">{{ translate('Pending') }}</span>
                                                @break

                                                @case(1)
                                                    <span
                                                        class="badge badge-success width-badge">{{ translate('Approved') }}</span>
                                                @break

                                                @case(4)
                                                    <span
                                                        class="badge badge-info width-badge">{{ translate('Under Review') }}</span>
                                                @break

                                                @case(2)
                                                    <span
                                                        class="badge badge-warning width-badge">{{ translate('Revision Required') }}</span>
                                                @break

                                                @case(3)
                                                    <span
                                                        class="badge badge-danger width-badge">{{ translate('Rejected') }}</span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @if ($product->is_draft == 1)
                                                <span class="badge badge-inline badge-info">{{ translate('Yes') }}</span>
                                            @else
                                                <span
                                                    class="badge badge-inline badge-success">{{ translate('No') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input onchange="update_featured(this)" value="{{ $children->id }}"
                                                    type="checkbox" <?php if ($children->seller_featured == 1) {
                                                        echo 'checked';
                                                    } ?>>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="text-right">
                                            {{-- <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{route('seller.products.edit', ['id'=>$children->id, 'lang'=>env('DEFAULT_LANGUAGE')])}}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="{{route('seller.products.duplicate', $children->id)}}" class="btn btn-soft-success btn-icon btn-circle btn-sm"  title="{{ translate('Duplicate') }}">
                                            <i class="las la-copy"></i>
                                        </a> --}}
                                            {{-- <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('seller.products.destroy', $children->id)}}" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>

    <div id="modal-info-catalog" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6" id="title-modal-catalog">{{ translate('Delete Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body-catalog text-center">
                    <p class="mt-1 fs-14" id="text-modal-catalog">{{ translate('Are you sure to delete this?') }}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2"
                        data-dismiss="modal">{{ translate('OK') }}</button>
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
            if (this.checked) {
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

        $(document).on('click', '.la-plus', function() {
            var product_id = $(this).data('id');
            var current = $(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('catalog.add_product_to_catalog') }}",
                type: "POST",
                data: {
                    id: product_id
                },
                cache: false,
                dataType: 'JSON',
                success: function(dataResult) {
                    current.parent().remove();
                    var html = '<p>{{ translate('Product added to catalog successfully') }}</p>';

                    $('#title-modal-catalog').text('MawadCatalog');
                    $('#text-modal-catalog').html(html);

                    $('#modal-info-catalog').modal('show');
                }
            })
        })

        $(document).ready(function() {
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_todays_deal(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_approved(el) {
            if (el.checked) {
                var approved = 1;
            } else {
                var approved = 0;
            }
            $.post('{{ route('products.approved') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                approved: approved
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Product approval update successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function sort_products(el) {
            $('#sort_products').submit();
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-product-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
