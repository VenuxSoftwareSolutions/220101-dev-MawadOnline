@forelse($compareData as $compareItem)
    @php
        $attributes = get_category_attributes($compareItem['id']);
        $isOddRow = count($attributes) % 2 == 0;
    @endphp

    <h4 class="fw-600 text-primary mb-3">
        {{ translate('Compare of') }} {{ $compareItem['category_name'] }}
    </h4>

    <div class="table-responsive">
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
                                        <div class="product-card">
                                            <img src="{{ get_uploaded_product($product_id) }}"
                                                 class="product-image" alt="{{ $product->name }}">
                                            <div class="absolute-top-right">
                                                <a href="#" class="btn btn-sm confirm-delete"
                                                   data-category-id="{{ get_leaf_category($product->id) }}"
                                                   data-variant-id="{{ $product->id }}"
                                                   title="{{ translate('Delete') }}">
                                                    <img src="{{ asset('public/trash.svg') }}">
                                                </a>
                                            </div>
                                        </div>
                                        <p class="product-name">{{ $product->name }}</p>
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
                                @if (get_product_price($product_id) != home_discounted_base_price($product))
                                    <del class="fw-400 opacity-50 mr-1">{{ get_product_price($product_id) }}</del>
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

                  {{--   <tr>
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
                    </tr> --}}
                    
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
    <h4 class="fw-600 text-primary mb-3 text-center">{{ translate('No items in the compare list') }}</h4>
@endforelse
<style>
.product-card {
    position: relative;
    width: 300px;
    height: 300px;
    overflow: hidden;
    margin: 10px auto;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.absolute-top-right {
    position: absolute;
    top: 10px;
    right: 10px;
}

/* Styling for the product name */
.product-name {
    text-align: center;
    font-weight: bold;
    margin-top: 10px;
    font-size: 16px;
    color: #007185; 
}

</style>