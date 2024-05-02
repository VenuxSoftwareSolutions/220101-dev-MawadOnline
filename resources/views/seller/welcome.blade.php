@extends('seller.layouts.app')

@section('panel_content')
    <section class="py-8 bg-soft-primary">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto text-center">
                    <h1 class="mb-0 fw-700">{{ translate('Welcome aboard!') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row  gutters-10 justify-content-center">
                <div class="col">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="text-center mb-4 mt-3">
                                <h5 class="mb-3 h5 fw-600"></h5>
                            </div>
                            <div class="mb-5 d-flex align-items-center justify-content-center">
                                <span class="fs-30 fw-600 lh-1 mb-0">It's great to have you with us at MawadOnline.</span>
                            </div>
                            <div class="mb-5 d-flex align-items-center text-center">
                                <span class="fs-22 fw-600 lh-1 mb-0"> You're now in the onboarding phase—this is your
                                    opportunity to line your e-shop's shelves and get your inventory in tip-top shape. We're
                                    gearing up for an exciting launch. When you're set to begin, click on the “Start Tour”
                                    button to explore the Vendor Portal. We're here with you at every step!</span>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-primary fw-600"
                                    onclick="start_tour()">{{ translate('Start Tour') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        function start_tour() {
            // Reload the page
            location.reload();
        }
    </script>
@endsection
