@extends('seller.layouts.app')
<style>
    .search_bloc {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .row_center {
        justify-content: center;
        align-items: center;
    }

    small{
        font-size: 110% !important;
    }

    .list-group-item a{
        margin-left: 50px;
    }

    .list-group-item:last-child{
        display: flex;
        justify-content: center;
    }

    .row_center small:last-child{
        float: right;
    }
</style>

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Check Payment Information') }}</h1>
            </div>
        </div>
    </div>
<div class="row gutters-5">
    <div class="col-lg-12">
        <div class="card">
                    <div class="card-header">
                        <span>{{__('lease.Charge for')}}</span><h6>{{$package->name}}</h6>
                        <span>{{__('lease.Amount (AED)')}}</span><h6>{{$package->amount}} {{__('lease.AED')}}</h6>
                        <span>{{__('lease.Onboarding Discount')}}</span><h6>{{(get_setting('onboarding_discount_activation') == 1 ? "100%" : "0")}}</h6>
                        <span>{{__('lease.Total Lease Due')}}</span><h6>{{(get_setting('onboarding_discount_activation') == 1 ? "0" : $package->amount)}} {{__('lease.AED')}}</h6><br>
                        <!-- <h5 class="mb-0 h6">{{ translate('Payment Information') }}</h5> -->
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" id="package_id" value="{{$package->id}}">

                                <!-- Stripe Elements Placeholder -->
                                <!-- <div id="card-element">

                                </div> -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label" for="cardholder-name">Cardholder name</label>
                                    <div class="col-md-8">
                                    <input id="cardholder-name" class="form-control" class="card_input" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label" for="card-number-element">Card number</label>
                                    <div class="col-md-8">
                                    <div id="card-number-element" class="form-control" ></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label" for="card-expiry-element">Expiry date</label>
                                    <div class="col-md-8">
                                    <div id="card-expiry-element" class="form-control"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label" for="card-cvc-element">CVC</label>
                                    <div class="col-md-8">
                                        <div id="card-cvc-element" class="form-control"></div>
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 col-from-label">Automatic Payment?</label>
                                    <div class="col-md-8">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="automatic_payment" value="1" checked="checked">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="Footer-PoweredBy">
                            <a class="Link Link--primary" target="_blank" rel="noopener"><div class="Footer-PoweredBy-Text Text Text-color--gray400 Text-fontSize--12 Text-fontWeight--400">Powered by <span><svg class="InlineSVG Icon Footer-PoweredBy-Icon Icon--md" focusable="false" width="33" height="15" role="img" aria-labelledby="stripe-title"><title id="stripe-title">Stripe</title><g fill-rule="evenodd"><path d="M32.956 7.925c0-2.313-1.12-4.138-3.261-4.138-2.15 0-3.451 1.825-3.451 4.12 0 2.719 1.535 4.092 3.74 4.092 1.075 0 1.888-.244 2.502-.587V9.605c-.614.307-1.319.497-2.213.497-.876 0-1.653-.307-1.753-1.373h4.418c0-.118.018-.588.018-.804zm-4.463-.859c0-1.02.624-1.445 1.193-1.445.55 0 1.138.424 1.138 1.445h-2.33zM22.756 3.787c-.885 0-1.454.415-1.77.704l-.118-.56H18.88v10.535l2.259-.48.009-2.556c.325.235.804.57 1.6.57 1.616 0 3.089-1.302 3.089-4.166-.01-2.62-1.5-4.047-3.08-4.047zm-.542 6.225c-.533 0-.85-.19-1.066-.425l-.009-3.352c.235-.262.56-.443 1.075-.443.822 0 1.391.922 1.391 2.105 0 1.211-.56 2.115-1.39 2.115zM18.04 2.766V.932l-2.268.479v1.843zM15.772 3.94h2.268v7.905h-2.268zM13.342 4.609l-.144-.669h-1.952v7.906h2.259V6.488c.533-.696 1.436-.57 1.716-.47V3.94c-.289-.108-1.346-.307-1.879.669zM8.825 1.98l-2.205.47-.009 7.236c0 1.337 1.003 2.322 2.34 2.322.741 0 1.283-.135 1.581-.298V9.876c-.289.117-1.716.533-1.716-.804V5.865h1.716V3.94H8.816l.009-1.96zM2.718 6.235c0-.352.289-.488.767-.488.687 0 1.554.208 2.241.578V4.202a5.958 5.958 0 0 0-2.24-.415c-1.835 0-3.054.957-3.054 2.557 0 2.493 3.433 2.096 3.433 3.17 0 .416-.361.552-.867.552-.75 0-1.708-.307-2.467-.723v2.15c.84.362 1.69.515 2.467.515 1.879 0 3.17-.93 3.17-2.548-.008-2.692-3.45-2.213-3.45-3.225z"></path></g></svg></span></div></a></div>
                        </div>
                        <h5 class="mb-0 h6"><div id="card-result"></div></h5>
                    </div>
                </div>
               </div>

<div class="col-12">
                <div class="mar-all text-right mb-2">
                    <button class="btn btn-success" id="card-button" data-secret="{{ $intent->client_secret }}">
    {{__('lease.Pay Now')}}
</button>

                </div>
            </div>

</div>
</div>
@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env('STRIPE_KEY') }}');
    const elements = stripe.elements({
    fonts: [
        {
            cssSrc: 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap'
        }
    ]
})
    // const elements = stripe.elements();

    const cardNumberElement = elements.create('cardNumber', {
    style: {
        base: {
            color: '#555',
            fontFamily: 'Montserrat, sans-serif'
        }
    }
})
    cardNumberElement.mount('#card-number-element');

    const cardExpiryElement = elements.create('cardExpiry');
    cardExpiryElement.mount('#card-expiry-element');

    const cardCVCElement = elements.create('cardCvc');
    cardCVCElement.mount('#card-cvc-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;
    const cardResult = document.getElementById('card-result');
cardButton.addEventListener('click', async (e) => {
    cardResult.textContent = 'Loading...';
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardNumberElement,
                }
            }
        );
    if (error) {
        cardResult.textContent = error.message
    } else {

        const response = await fetch("{{route('vendor.save.payment')}}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                package : document.getElementById('package_id').value,
                paymentMethodId: setupIntent.payment_method,
                "_token": "{{ csrf_token() }}",
            })
        });
        let data = await response.json();
        cardResult.textContent = data.message
        cardNumberElement.clear();
        cardExpiryElement.clear();
        cardCVCElement.clear();
        document.getElementById('cardholder-name').value='';
        window.location.href="{{route('seller.lease.index',['display_flash'=>'true'])}}";
    }
});
cardNumberElement.on('change', (event) => {
    document.getElementById('card-number-element').style.backgroundImage = `url(images/${event.brand}.png)`
});
</script>
@endsection
