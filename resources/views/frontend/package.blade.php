@extends('frontend.layouts.app')

@section('content')
    <section class="py-6 bg-soft-primary">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 mx-auto text-center">
                    <h1 class="mb-4 fw-700">{{ translate('MawadOnline') }}</h1>
                    <h6 class="mb-4 fw-400">{{ translate('MawadOnline offers the best marketplace for contruction materials in UAE...') }}</h6>
                    <h6 class="mb-0 fw-700">{{ translate('You will not pay now. Choose your package, registre your e-Shop, create your products and inventory.') }} <br>
                        {{ translate('Make your e-Shop ready for MawadOnline launch') }}</h6>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row row-cols-xxl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 gutters-10 justify-content-center">
                    <div class="col">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="text-center mb-4 mt-3">
                                    <h5 class="mb-1 h5 fw-600">{{ $seller_packages[0]->getTranslation('name') }}</h5>
                                </div>
                                <div class="mb-3 d-flex align-items-center justify-content-center">
                                    @if ($seller_packages[0]->amount == 0)
                                        <span class="fs-30 fw-600 lh-1 mb-0">-</span>
                                    @else
                                        <span
                                            class="fs-32 fw-700 lh-1 mb-0">AED {{ $seller_packages[0]->amount }} / month</span>
                                    @endif
                                    {{-- <span
                                        class="text-secondary border-left ml-2 pl-2">{{ $seller_packages->duration }}<br>{{ translate('Days') }}</span> --}}
                                </div>

                                <ul class="list-group list-group-raw fs-13 mb-5">
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                        {{ translate('Full access to all e-Shop essentials') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Unlimited products') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Free e-Shop adminstrator + 4 positions') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Additional staff position just for AED 10/month') }}
                                    </li>
                                </ul>

                                <div class="text-center">
                                    {{-- @if ($seller_packages->amount == 0)
                                        <button class="btn btn-primary fw-600"
                                            onclick="get_free_package({{ $seller_packages->id }})">{{ translate('Free Package') }}</button>
                                    @else
                                        @if (addon_is_activated('offline_payment'))
                                            <button class="btn btn-primary fw-600"
                                                onclick="select_package({{ $seller_packages->id }})">{{ translate('Purchase Package') }}</button>
                                        @else
                                            <button class="btn btn-primary fw-600"
                                                onclick="show_price_modal({{ $seller_packages->id }})">{{ translate('Purchase Package') }}</button>
                                        @endif
                                    @endif --}}
                                    <button class="btn btn-primary fw-600 col-10"
                                                onclick="select_package({{ $seller_packages[0]->id }})">{{ translate('Register your e-Shop') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="text-center mb-4 mt-3">
                                    <h5 class="mb-1 h5 fw-600">{{ $seller_packages[1]->getTranslation('name') }}</h5>
                                </div>
                                <div class="mb-3 d-flex align-items-center justify-content-center">
                                    @if ($seller_packages[1]->amount == 0)
                                        <span class="fs-30 fw-600 lh-1 mb-0">-</span>
                                    @else
                                        <span
                                            class="fs-32 fw-700 lh-1 mb-0">AED {{ $seller_packages[1]->amount }} / month</span>
                                    @endif
                                    {{-- <span
                                        class="text-secondary border-left ml-2 pl-2">{{ $seller_packages->duration }}<br>{{ translate('Days') }}</span> --}}
                                </div>
                                <ul class="list-group list-group-raw fs-13 mb-5">
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                        {{ translate('Everything in the Pro Plan') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Customized e-Shop roles') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('Marketing analytics') }}
                                    </li>
                                    <li class="list-group-item py-2 fw-700">
                                        <i class="las la-check text-success mr-2"></i>
                                         {{ translate('And much more...') }}
                                    </li>
                                </ul>

                                <div class=" text-center">
                                    {{-- @if ($seller_packages->amount == 0)
                                        <button class="btn btn-primary fw-600"
                                            onclick="get_free_package({{ $seller_packages->id }})">{{ translate('Free Package') }}</button>
                                    @else
                                        @if (addon_is_activated('offline_payment'))
                                            <button class="btn btn-primary fw-600"
                                                onclick="select_package({{ $seller_packages->id }})">{{ translate('Purchase Package') }}</button>
                                        @else
                                            <button class="btn btn-primary fw-600"
                                                onclick="show_price_modal({{ $seller_packages->id }})">{{ translate('Purchase Package') }}</button>
                                        @endif
                                    @endif --}}
                                    <button class="btn btn-primary fw-600 col-10" disabled>{{ translate('Coming soon') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="row mt-2 justify-content-center">
                <div class="fs-15">
                    <a href="{{route('terms-and-conditions')}}"><u>Terms & Conditions</u></a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Select Payment Type Modal -->
    <div class="modal fade" id="select_package_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form class="" id="package_payment_form" action="{{ route('shops.create') }}"
                    method="get">
                    @csrf
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Select Payment Type') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div> --}}
                <div class="modal-body">
                    <input type="hidden" id="package_id" name="package_id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="fs-15 fw-400">{{ translate('Thanks for choosing MawadOnline Pro package. You will now register your e-shop and make
                                it ready for MawadOnline launch. You will not pay anything now. Once your registration is
                                approved, you will be able to create your products. Stay tuned for the biggest e-market in the
                                construction history') }}</label>
                        </div>

                    </div>
                    <div class="form-group text-center mt-4 ">
                        <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ translate('OK') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>





@endsection

@section('script')
    <script type="text/javascript">
        function select_package(id) {
            $('input[name=package_id]').val(id);
            $('#select_package_modal').modal('show');
        }


    </script>
@endsection

