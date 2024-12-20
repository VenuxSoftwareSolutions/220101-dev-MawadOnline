@extends('frontend.layouts.app')

@section('content')

    @php
        $compareList = Session::has('compareData') ? Session::get('compareData') : [];
    @endphp

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

                @forelse($compareList as $compareItem)
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
                                                <button class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                    onclick="removeCompareItem('{{ $product->id }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
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


@endsection
