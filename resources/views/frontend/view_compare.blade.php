@extends('frontend.layouts.app')

@section('content')
   
    <input type="hidden" id="compare-data" value="{{ json_encode($compareList) }}">
    <section class="mb-4 mt-3">
        <div class="container text-left">
            <div class="bg-white shadow-sm rounded py-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="fw-700 text-dark">{{ translate('Compare Products') }}</h2>
                    <a href="{{ route('compare.reset') }}" class="btn btn-soft-primary btn-sm fw-600"
                        onclick="clearLocalStorage()">
                        {{ translate('Reset Compare List') }}
                    </a>
                </div>
                @php
                    $compareList = is_string($compareList) ? json_decode($compareList, true) : $compareList;
                    $compareList = $compareList ?? [];
                @endphp

                @forelse($compareList as $compareItem)
                    @php
                        $attributes = get_category_attributes($compareItem['id']);
                        $isOddRow = count($attributes) % 2 == 0;

                    @endphp
                    <h4 class="fw-600 text-primary mb-3">
                        {{ translate('Compare of') }} {{ $compareItem['category_name'] }}
                    </h4>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr class="bg-light">
                                    <th class="fw-700">{{ translate('Product') }}</th>
                                    @foreach ($compareItem['variants'] as $product)
                                        @php
                                            $product_id = $product->id;
                                            $product = get_single_product($product_id);
                                        @endphp
                                        <th>
                                            <div class="position-relative">
                                                <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    class="img-fluid mb-2" alt="{{ $product->name }}">
                                                <div class="absolute-top-right">
                                                    <a href="#" class="btn btn-sm confirm-delete"
                                                        data-category-id="{{ get_leaf_category($product->id) }}"
                                                        data-variant-id="{{ $product->id }}"
                                                        title="{{ translate('Delete') }}">
                                                        <img src="{{ asset('public/trash.svg') }}">
                                                    </a>
                                                </div>
                                            </div>

                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="fw-700">{{ translate('Name') }}</th>
                                    @foreach ($compareItem['variants'] as $product)
                                        @php
                                            $product_id = $product->id;
                                            $product = get_single_product($product_id);
                                        @endphp
                                        <td>{{ $product->name }}</td>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                <tr class="bg-light">
                                    <td class="fw-700">{{ translate('Price') }}</td>
                                    @foreach ($compareItem['variants'] as $product)
                                        @php
                                            $product_id = $product->id;
                                            $product = get_single_product($product_id);
                                        @endphp
                                        <td>
                                            @if (home_base_price($product) != home_discounted_base_price($product))
                                                <del class="fw-400 opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                            @endif
                                            <span
                                                class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-700">{{ translate('Ratings') }}</td>
                                    @foreach ($compareItem['variants'] as $product)
                                        @php
                                            $product_id = $product->id;
                                            $product = get_single_product($product_id);
                                        @endphp
                                        <td>
                                            {!! renderStarRating($product->rating) !!}
                                        </td>
                                    @endforeach
                                </tr>
                                <tr class="bg-light">
                                    <td class="fw-700">{{ translate('Brand') }}</td>
                                    @foreach ($compareItem['variants'] as $product)
                                        @php
                                            $product_id = $product->id;
                                            $product = get_single_product($product_id);
                                        @endphp
                                        <td>
                                            @if ($product->brand != null)
                                                {{ $product->brand->getTranslation('name') }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                                <tr>
                                    <td class="fw-700">{{ translate('Stock') }}</td>
                                    @foreach ($compareItem['variants'] as $product)
                                        <td>{{ $product->current_stock }}</td>
                                    @endforeach
                                </tr>
                                <tr class="bg-light">
                                    <td class="fw-700">{{ translate('Weight') }}</td>
                                    @foreach ($compareItem['variants'] as $product)
                                        <td>{{ $product->weight }}</td>
                                    @endforeach
                                </tr>

                                <tr>
                                    <td class="fw-700">{{ translate('Dimensions (L x W x H)') }}</td>
                                    @foreach ($compareItem['variants'] as $product)
                                        <td>{{ $product->length ?? 'N/A' }} x {{ $product->width ?? 'N/A' }} x
                                            {{ $product->height ?? 'N/A' }}</td>
                                    @endforeach
                                </tr>
                                
                                @foreach ($attributes as $item_attribute)
                                    <tr @if($loop->iteration % 2 != 0) class="bg-light" @endif>
                                        <td class="fw-700">{{ translate($item_attribute->name) }}</td>
                                        @foreach ($compareItem['variants'] as $product)
                                            <td>{{ get_product_attribute_value($product->id, $item_attribute->id) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr @if($isOddRow) class="bg-light" @endif>
                                    <td class="fw-700">{{ translate('Actions') }}</td>
                                    @foreach ($compareItem['variants'] as $product)
                                        @php
                                            $product_id = $product->id;
                                            $product = get_single_product($product_id);
                                        @endphp
                                        <td>
                                            <button type="button"
                                                class="btn btn-block btn-dark rounded-0 fs-13 fw-700 has-transition opacity-80 hov-opacity-100"
                                                onclick="showAddToCartModal({{ $product }})">
                                                {{ translate('Add to cart') }}
                                            </button>
                                        </td>
                                    @endforeach
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                @empty
                    <h4 class="fw-600 text-primary mb-3 text-center">
                        {{ translate('No items in the compare list') }}
                    </h4>
                @endforelse
            </div>
        </div>
    </section>

    <div id="delete-confirmation-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14">{{ translate('Are you sure you want to delete this item?') }}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger rounded-0 mt-2" id="confirm-delete-btn">
                        {{ translate('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log(fetchCompareData(JSON.parse(localStorage.getItem('compare'))));

            @if (!auth()->check())
                let compare = JSON.parse(localStorage.getItem('compare')) || {};
                if (Object.keys(compare).length > 0) {
                    fetchCompareData(compare);
                }
            @endif
            function fetchCompareData(compare) {
                fetch("{{ route('compare.data') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            compare: compare
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            renderCompareTable(data.compareData);
                        }
                    })
                    .catch((error) => console.error("Error fetching compare data:", error));
            }

            function renderCompareTable(compareData) {
                console.log(compareData);
            }
        });
    </script>
@endsection
