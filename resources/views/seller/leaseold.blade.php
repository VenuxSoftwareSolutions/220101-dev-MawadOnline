
@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{__('lease.Leases')}}</h1>
		</div>
	</div>
</div>


<button id="checkout-button" class="btn btn-primary">Subscribe Now</button>

<div id="checkout-form"></div>


@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('pk_test_51PWazORvNlBWfmPIt08ghBVBXxLhG9JVpqGwdMvbdcZ4iu5c0H7lr8l6zYYtzA7Iey4oWyFn9weGUh8Jusr03Ji90082JZx2bE');

    document.addEventListener('DOMContentLoaded', async () => {
    const checkoutButton = document.getElementById('checkout-button');

    checkoutButton.addEventListener('click', async () => {
        try {
            const response = await fetch('{{ route('checkout.session') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            const clientSecret = data.clientSecret;
            alert(clientSecret)
            if (!clientSecret) {
                throw new Error('Client secret not returned from server');
            }

            const elements = stripe.elements({
                // Add any custom element options you might need here
            });

            const checkout = await stripe.initEmbeddedCheckout({
                clientSecret: clientSecret,


            });

            // No need to mount the checkout element anymore, it's handled internally
        } catch (error) {
            console.error('Error initializing Stripe Checkout:', error);
            // Display a user-friendly error message here
        }
    });
});
    </script>


@endsection

