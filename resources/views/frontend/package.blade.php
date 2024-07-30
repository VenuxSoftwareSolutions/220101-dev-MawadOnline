@extends('frontend.layouts.app')

@section('content')
    <section class="py-6 bg-soft-primary">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 mx-auto text-center">
                    <h1 class="mb-4 fw-700">{{ translate('MawadOnline') }}</h1>
                    <h6 class="mb-4 fw-400">
                        {{ __('package.MawadOnline offers the best marketplace for contruction materials in UAE...') }}</h6>
                    <h6 class="mb-0 fw-700">
                        {{ __('package.You will not pay now. Choose your package, registre your eShop, create your products and inventory.') }}
                        <br>
                        {{ __('package.Make your eShop ready for MawadOnline launch') }}
                    </h6>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row row-cols-xxl-3 row-cols-lg-3 row-cols-md-2 row-cols-1 gutters-10 justify-content-center">
                <div class="col">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="text-center mb-4 mt-3">
                                <h5 class="mb-1 h5 fw-600">{{ $seller_packages[0]->getTranslation('name') }}</h5>
                            </div>
                            <div class="mb-3 d-flex align-items-center justify-content-center">
                                {{-- @if ($seller_packages[0]->amount == 0)
                                    <span class="fs-30 fw-600 lh-1 mb-0">-</span>
                                @else
                                    <span class="fs-32 fw-700 lh-1 mb-0">{{ __('package.AED') }} {{ $seller_packages[0]->amount }} /
                                        {{ __('package.month') }}</span>
                                @endif --}}
                                {{-- <span
                                        class="text-secondary border-left ml-2 pl-2">{{ $seller_packages->duration }}<br>{{ translate('Days') }}</span> --}}
                            </div>

                            <ul class="list-group list-group-raw fs-13 mb-5">
                                <li class="list-group-item py-2 fw-700">
                                    <i class="las la-check text-success mr-2"></i>
                                    {{ __('package.Full access to all eShop essentials') }}
                                </li>
                                <li class="list-group-item py-2 fw-700">
                                    <i class="las la-check text-success mr-2"></i>
                                    {{ __('package.Unlimited products') }}
                                </li>
                                <li class="list-group-item py-2 fw-700">
                                    <i class="las la-check text-success mr-2"></i>
                                    {{ __('package.Free eShop administrator + 4 positions') }}
                                </li>
                                {{-- <li class="list-group-item py-2 fw-700">
                                    <i class="las la-check text-success mr-2"></i>
                                    {{ __('package.Additional staff position just for AED 10/month') }}
                                </li> --}}
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
                                    onclick="select_package({{ $seller_packages[0]->id }})">{{ __('package.Register your eShop') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="text-center mb-4 mt-3">
                                <h5 class="mb-1 h5 fw-600">{{ $seller_packages[1]->getTranslation('name') }}</h5>
                            </div>
                            <div class="mb-3 d-flex align-items-center justify-content-center">
                                @if ($seller_packages[1]->amount == 0)
                                    <span class="fs-30 fw-600 lh-1 mb-0">-</span>
                                @else
                                    <span class="fs-32 fw-700 lh-1 mb-0">{{ __('package.AED') }} {{ $seller_packages[1]->amount }} /
                                        {{ __('package.month') }}</span>
                                @endif
                                {{-- <span
                                        class="text-secondary border-left ml-2 pl-2">{{ $seller_packages->duration }}<br>{{ translate('Days') }}</span> --}}
                            {{-- </div>
                            <ul class="list-group list-group-raw fs-13 mb-5">
                                <li class="list-group-item py-2 fw-700">
                                    <i class="las la-check text-success mr-2"></i>
                                    {{ __('package.Everything in the Pro Plan') }}
                                </li>
                                <li class="list-group-item py-2 fw-700">
                                    <i class="las la-check text-success mr-2"></i>
                                    {{__('package.Customized eShop roles') }}
                                </li>
                                <li class="list-group-item py-2 fw-700">
                                    <i class="las la-check text-success mr-2"></i>
                                    {{ __('package.Marketing analytics') }}
                                </li>
                                <li class="list-group-item py-2 fw-700">
                                    <i class="las la-check text-success mr-2"></i>
                                    {{ __('package.And much more...') }}
                                </li>
                            </ul>

                            <div class=" text-center"> --}}
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
                                {{-- <button class="btn btn-primary fw-600 col-10"
                                    disabled>{{ __('package.Coming Soon') }}</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="row mt-2 justify-content-center">
                <div class="fs-15">
                    <a href="{{ route('terms-and-conditions') }}" target="_blank"><u>{{ __('package.Terms & Conditions') }}<i
                                class="las la-external-link-alt"></i></u></a>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Select Payment Type Modal -->
    <div class="modal fade" id="select_package_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form class="" id="package_payment_form" action="{{ route('shops.create') }}" method="get">
            @csrf
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <input type="hidden" id="package_id" name="package_id" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <label
                                    class="fs-15 fw-600">{{__('package.Welcome to MawadOnline and thank you for joining our family!') }}</label>
                            </div>
                            <div class="col-md-12">
                                <label
                                    class="fs-15 fw-400">{{__('package.You\'re on your way to setting up your own eShop—no payment needed just yet. Once we\'ve got your registration squared away, you\'ll be all set to showcase your products. Get ready to be part of an exciting chapter in construction retail! Keep an eye out, we\'re launching soon!') }}</label>
                            </div>

                        </div>
                        <div class="form-group text-center mt-4 ">
                            <button type="submit"
                                class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ __('package.OK') }}</button>
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
