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
                <a href="{{ route('compare.reset') }}" 
                   class="btn btn-soft-primary btn-sm fw-600">
                   {{ translate('Reset Compare List') }}
                </a>
            </div>
            
                @foreach($compareList as $compareItem )
                  

                    <h4 class="fw-600 text-primary mb-3">
                        {{ translate('Compare of') }} {{ $compareItem['category_name'] }}
                    </h4>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <!-- Alternating Background Rows -->
                                <tr class="bg-light">
                                    <th class="fw-700">{{ translate('Product') }}</th>
                                    @foreach($compareItem['variants'] as $product)
                                    <th>
                                            <div class="position-relative">
                                                <img src="{{ uploaded_asset($product->thumbnail_img) }}" 
                                                     class="img-fluid" 
                                                     alt="{{ $product->name }}">
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="fw-700">{{ translate('Name') }}</th>
                                    @foreach($compareItem['variants'] as $product)
                                        <td>{{ $product->name }}</td>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                <tr class="bg-light">
                                    <td class="fw-700">{{ translate('Price') }}</td>
                                    @foreach($compareItem['variants'] as $product)
                                        <td>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-700">{{ translate('Ratings') }}</td>
                                    @foreach($compareItem['variants'] as $product)
                                        <td>
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($product->rating >= $i)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach
        </div>
    </div>
</section>

@endsection
