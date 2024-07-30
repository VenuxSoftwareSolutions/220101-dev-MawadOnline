@extends('seller.layouts.app')

@section('panel_content')

<style>
.pagination .active .page-link {
    background-color: #8f97ab !important;
}

.pagination .page-link:hover {
    background-color: #8f97ab !important;
}

.pagination-showin {
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: #808080;
}

thead tr {
    height: 53px !important;
}

.aiz-table th {
    padding: 0 !important;
    vertical-align: middle !important;
}

.remove-top-padding {
    padding-top: 0 !important;
}

.modal-header {
    border-bottom: none;
}

.modal-footer {
    border-top: none;
}
</style>

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h2 class="h3">Subscription Details</h2>
            <div class="row">
                <div class="col-md-8">
                    <p style="font-size: 16px;"></p>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($isSubscribedToDailyPlan)
                @if ($subscription)
                    @if ($subscription->stripe_status === 'active')
                        @php
                            $pauseCollection = json_decode($subscription->pause_collection, true);
                            $isPaused = is_array($pauseCollection) && isset($pauseCollection['behavior']) && $pauseCollection['behavior'] === 'keep_as_draft';
                        @endphp
                        <div class="subscription-status">
                            @if ($subscription->ends_at && $subscription->ends_at > now())
                                <p class="text-gray-500">Subscription will be cancelled on {{ $subscription->ends_at->format('Y-m-d H:i:s') }}</p>
                                <a href="{{ route('subscription.resume') }}" class="btn btn-success mt-3">Cancel Scheduled Cancellation</a>
                            @else
                                @if ($isPaused)
                                    <p class="text-gray-500">Your subscription is currently paused.</p>
                                    <a href="{{ route('subscription.unpause') }}" class="btn btn-success mt-3">Unpause Subscription</a>
                                @else
                                    <p class="text-gray-500">Active Subscription</p>
                                    <a href="{{ route('subscription.pause') }}" class="btn btn-warning mt-3">Pause Subscription</a>
                                @endif
                                <a data-toggle="modal" data-target="#updatePaymentModal" class="btn btn-warning mt-3">Update Payment Information</a>
                                <a href="{{ route('subscription.cancel') }}" class="btn btn-danger mt-3">Cancel Subscription</a>
                            @endif
                        </div>
                    @elseif ($subscription->stripe_status === 'past_due')
                        <p class="mt-2 text-orange-500">Your subscription is past due. Please update your payment information to avoid cancellation.</p>
                        <a data-toggle="modal" data-target="#updatePaymentModal" class="btn btn-warning mt-3">Update Payment Information</a>
                    @elseif ($subscription->stripe_status === 'canceled')
                        <p class="mt-2 text-red-500">Your subscription is canceled.</p>
                        <a href="{{ route('checkout', ['plan' => 'price_1PdD6kRvNlBWfmPI6CWSZVOW']) }}" class="btn btn-success mt-3">Create New Subscription</a>
                    @else
                        <p class="mt-2 text-red-500">Your subscription is in an unsupported status. Please contact support.</p>
                    @endif
                @else
                    <p class="mt-2 text-red-500">Your subscription information is not available. Please contact support.</p>
                @endif
            @else
                <a href="{{ route('checkout', ['plan' => 'price_1PhvFqRvNlBWfmPIgzjliXnB']) }}" class="btn btn-indigo mt-5">Sign Up</a>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h3>Payment Information</h3>
            <table class="table aiz-table mb-0">
                <thead>
                    <tr style="background-color: #f8f8f8;">
                        <th>{{ translate('Brand') }}</th>
                        <th>{{ translate('Last 4 Digits') }}</th>
                        <th>{{ translate('Expires') }}</th>
                        <th>{{ translate('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($paymentMethod)
                        <tr>
                            <td>{{ $paymentMethod->card->brand }}</td>
                            <td>{{ $paymentMethod->card->last4 }}</td>
                            <td>{{ $paymentMethod->card->exp_month }}/{{ $paymentMethod->card->exp_year }}</td>
                            <td class="text-center remove-top-padding">
                                <a class="btn btn-sm" data-toggle="modal" data-target="#updatePaymentModal" title="Update Payment Information">
                                    <img src="{{ asset('public/Edit.svg') }}" alt="Edit">
                                </a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="text-center">No payment method found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Modal Structure -->
        <div class="modal fade" id="updatePaymentModal" tabindex="-1" role="dialog" aria-labelledby="updatePaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updatePaymentModalLabel">Update Payment Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('subscription.update') }}" method="POST" id="payment-form">
                            @csrf
                            <div class="mb-4">
                                <label for="card-element" class="block text-sm font-medium text-gray-700">Credit or debit card</label>
                                <div id="card-element" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></div>
                                <div id="card-errors" role="alert" class="text-red-500 mt-2"></div>
                            </div>
                            <input type="hidden" name="payment_method" id="payment-method">
                            <button type="submit" class="btn btn-primary">Update Payment Information</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const { paymentMethod, error } = await stripe.createPaymentMethod('card', cardElement);

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            document.getElementById('payment-method').value = paymentMethod.id;
            form.submit();
        }
    });
</script>
@endsection
