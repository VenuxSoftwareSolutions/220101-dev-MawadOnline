
@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{__('lease.Leases')}}</h1>
		</div>
	</div>
</div>

<div id="step1" class="card">
    <!-- Pricing -->
{{-- <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto"> --}}
    <!-- Title -->
    <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
        <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">Subscription Plans</h2>
        <p class="mt-1 text-gray-600 dark:text-gray-400">Choose the plan that better fits your needs.</p>
    </div>
    <!-- End Title -->

    <!-- Grid -->
    <div class="mt-12 grid sm:grid-cols-1 lg:grid-cols-3 gap-6 lg:items-center">
        <!-- Card -->
        <div class="flex flex-col border border-gray-200 text-center rounded-xl p-8 dark:border-gray-700">
            <h4 class="font-medium text-lg text-gray-800 dark:text-gray-200">Daily</h4>
            <span class="mt-5 font-bold text-5xl text-gray-800 dark:text-gray-200">
                <span class="font-bold text-2xl -me-2">$</span>
                4.99
            </span>
            <p class="mt-2 text-sm text-gray-500">No commitments. Cancel anytime.</p>

            @if ($isSubscribedToDailyPlan)
                @if ($subscription)
                    @if ($subscription->stripe_status === 'active')
                    @php
                    $pauseCollection = json_decode($subscription->pause_collection, true);
                    $isPaused = is_array($pauseCollection) && isset($pauseCollection['behavior']) && $pauseCollection['behavior'] === 'keep_as_draft';
                    @endphp
                        @if ($subscription->ends_at && $subscription->ends_at > now())
                            <p class="mt-2 text-gray-500">Subscription will be cancelled on {{ $subscription->ends_at->format('Y-m-d H:i:s') }}</p>

                              <!-- Option to cancel the scheduled cancellation -->
                            <a href="{{ route('subscription.resume') }}" class="mt-3 py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-100 text-green-800 hover:bg-green-200 dark:hover:bg-green-700 dark:text-green-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                Cancel Scheduled Cancellation
                            </a>

                        @else

                            @if ($isPaused)
                            <p class="text-gray-500">Your subscription is currently paused.</p>
                            <a href="{{ route('subscription.unpause') }}" class="mt-3 py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-100 text-green-800 hover:bg-green-200 dark:hover:bg-green-700 dark:text-green-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                Unpause Subscription
                            </a>
                        @else
                            <p class="mt-2 text-gray-500">Active Subscription</p>
                            <a href="{{ route('subscription.pause') }}" class="mt-3 py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:hover:bg-yellow-700 dark:text-yellow-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                Pause Subscription
                            </a>
                        @endif
                            <a href="{{-- {{ route('subscription.update') }} --}}" class="mt-3 py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:hover:bg-yellow-700 dark:text-yellow-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                Update Payment Information
                            </a>
                            <a href="{{ route('subscription.cancel') }}" class="mt-3 py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-red-200 dark:hover:bg-red-700 dark:text-red-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                Cancel Subscription
                            </a>
                        @endif
                    @elseif ($subscription->stripe_status === 'past_due')
                        <p class="mt-2 text-orange-500">Your subscription is past due. Please update your payment information to avoid cancellation.</p>
                        <a href="{{ route('subscription.update') }}" class="mt-3 py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:hover:bg-yellow-700 dark:text-yellow-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Update Payment Information
                        </a>
                    @elseif ($subscription->stripe_status === 'canceled')
                        <p class="mt-2 text-red-500">Your subscription is canceled.</p>
                        <a href="{{ route('checkout', ['plan' => 'price_1PdD6kRvNlBWfmPI6CWSZVOW']) }}" class="mt-3 py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-100 text-green-800 hover:bg-green-200 dark:hover:bg-green-700 dark:text-green-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Create New Subscription
                        </a>
                    @else
                        <p class="mt-2 text-red-500">Your subscription is in an unsupported status. Please contact support.</p>
                    @endif
                @else
                    <p class="mt-2 text-red-500">Your subscription information is not available. Please contact support.</p>
                @endif
            @else
                <a href="{{ route('checkout', ['plan' => 'price_1Pff8tRvNlBWfmPIJs3W3bLd']) }}" class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-indigo-100 text-indigo-800 hover:bg-indigo-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-indigo-900 dark:text-indigo-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Sign up
                </a>
            @endif
        </div>
 {{-- <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Add Extra Amount to Subscription</h1>

        <!-- Display success or error messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 p-4 mb-4 rounded">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('subscription.add-extra') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="extra_amount" class="block text-gray-700 font-medium mb-2">Extra Amount (in USD):</label>
                <input type="number" name="extra_amount" id="extra_amount" step="0.01" min="0" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <button type="submit" class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">
                Add Extra Amount
            </button>
        </form>
    </div> --}}
        <!-- End Card -->

        <!-- Include other subscription options or remove if not needed -->
    </div>
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Update Payment Information</h1>
        <form action="{{ route('subscription.update') }}" method="POST" id="payment-form">
            @csrf
            <div class="mb-4">
                <label for="card-element" class="block text-sm font-medium text-gray-700">Credit or debit card</label>
                <div id="card-element" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></div>
                <div id="card-errors" role="alert" class="text-red-500 mt-2"></div>
            </div>
            <input type="hidden" name="payment_method" id="payment-method">
            <button type="submit" class="mt-4 py-2 px-4 bg-indigo-600 text-white rounded-md">Update Payment Information</button>
        </form>
    </div>
    <!-- End Grid -->
{{-- </div> --}}
<!-- End Pricing -->
    <div class="card-header">
        <h5 class="mb-0 h6 fw-700 fs-22"><div>{{__('lease.Current Lease Due')}}</div> <br>
            {{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->start_date)->isoFormat('DD-MMMM-YYYY')}} {{__('lease.to')}}
            {{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->end_date)->isoFormat('DD-MMMM-YYYY')}}
        </h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>{{__('lease.From Date')}}</th>
                    <th>{{__('lease.To Date')}}</th>
                    <th>{{__('lease.Charge for')}}</th>
                    <th>{{__('lease.Amount (AED)')}}</th>
                    <th>{{__('lease.Status')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{__('lease.e-Shop lease')}}</td>
                    <td>{{$current_lease->package->amount}}</td>
                    <td style="color: red;">{{__('lease.Unpaid')}}</td>
                </tr>
                @foreach($current_details as $key => $detail)
                    <tr>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>1 {{$detail->role->getTranslation('name')}} {{__('lease.Role')}}</td>
                        <td>{{$detail->amount}}</td>
                        <td style="color: red;">{{__('lease.Unpaid')}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><div>{{__('lease.Onboarding Discount')}}:</div> <br>
                        <div>{{__('lease.Sub Total')}}:</div> <br>
                        <div>{{__('lease.VAT')}}:</div> <br>
                        <div>{{__('lease.Paid Amount')}}:</div> <br>
                        <div class="fw-700">{{__('lease.Total Lease Due')}}:</div>
                    </td>
                    <td class="text-right"><div>-{{$current_lease->discount}} {{__('lease.AED')}}</div> <br>
                        <div>{{number_format($current_lease->total-$current_lease->discount,2)}} {{__('lease.AED')}}</div> <br>
                        <div>{{ number_format(($current_lease->total - $current_lease->discount) * 0.05, 2) }} {{__('lease.AED')}}</div> <br>
                        <div>0.00 {{__('lease.AED')}}</div> <br>
                        <div class="fw-700">{{number_format(($current_lease->total-$current_lease->discount)*0.05+($current_lease->total-$current_lease->discount),2)}} {{__('lease.AED')}}</div> <br>
                        <div>
                            <button class="btn btn-primary fw-600" disabled>{{__('lease.Pay Now')}}</button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@foreach ($leases as $lease)
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6 fw-700 fs-22"><div>{{__('lease.Lease History')}}</div> <br>
            {{Carbon\Carbon::createFromFormat('Y-m-d', $lease->start_date)->isoFormat('DD-MMMM-YYYY')}} {{__('lease.to')}}
            {{Carbon\Carbon::createFromFormat('Y-m-d', $lease->end_date)->isoFormat('DD-MMMM-YYYY')}}
        </h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>{{__('lease.From Date')}}</th>
                    <th>{{__('lease.To Date')}}</th>
                    <th>{{__('lease.Charge for')}}</th>
                    <th>{{__('lease.Amount (AED)')}}</th>
                    <th>{{__('lease.Status')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $lease->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $lease->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{__('lease.e-Shop lease')}}</td>
                    <td>{{$lease->package->amount}}</td>
                    <td style="color: red;">{{__('lease.Unpaid')}}</td>
                </tr>
                @php
                    $details=App\Models\SellerLeaseDetail::where('lease_id',$lease->id)->get();
                @endphp
                @foreach($details as $key => $detail)
                    <tr>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>1 {{$detail->role->getTranslation('name')}} {{__('lease.Role')}}</td>
                        <td>{{$detail->amount}}</td>
                        <td style="color: red;">{{__('lease.Unpaid')}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><div>{{__('lease.Onboarding Discount')}}:</div> <br>
                        <div>{{__('lease.Sub Total')}}:</div> <br>
                        <div>{{__('lease.VAT')}}:</div> <br>
                        <div>{{__('lease.Paid Amount')}}:</div> <br>
                        <div class="fw-700">{{__('lease.Total Lease Due')}}:</div>
                    </td>
                    <td class="text-right"><div>-{{$lease->discount}} {{__('lease.AED')}}</div> <br>
                        <div>{{number_format($lease->total-$lease->discount,2)}} {{__('lease.AED')}}</div> <br>
                        <div>{{ number_format(($lease->total - $lease->discount) * 0.05, 2) }} {{__('lease.AED')}}</div> <br>
                        <div>0.00 {{__('lease.AED')}}</div> <br>
                        <div class="fw-700">{{number_format(($lease->total-$lease->discount)*0.05+($lease->total-$lease->discount),2)}} {{__('lease.AED')}}</div> <br>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@endforeach
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}}) {
            return;
        }
        var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->getTranslation('title')}}',
                intro: "{{$step->getTranslation('description')}}",
                position: '{{ $step->getTranslation('lang') === 'en' ? 'right' : 'left' }}'
            },
            @endforeach
        ];
        var lang = '{{$tour_steps[0]->getTranslation('lang')}}';
        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
            nextLabel: lang == 'en' ? 'Next' : 'التالي',
            prevLabel: lang == 'en' ? 'Back' : 'رجوع',
            exitOnEsc : false ,
            exitOnOverlayClick : false ,
            disableInteraction : true ,
            overlayOpacity : 0.4 ,
            showStepNumbers : true ,
            hidePrev : true ,
            showProgress :true ,
        });


        tour.onexit(function() {
            $.ajax({
                url: "{{ route('seller.tour') }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' }, // Include CSRF token for Laravel
                success: function(response) {
                    // Handle success
                    console.log('User tour status updated successfully');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating user tour status:', error);
                }
            });
            setTimeout(function() {
                window.location.href = '{{ route("seller.dashboard") }}';
            }, 500);
        });

        tour.onbeforechange(function(targetElement) {
            if (this._direction === 'backward') {
            window.location.href = '{{ route("seller.staffs.index") }}'; // Redirect to another page
            sleep(60000);
            }
            step_number += 1 ;
            if (step_number == 3) {
            window.location.href = '{{ route("seller.sales.index") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(11);
    });
</script>
@endsection

