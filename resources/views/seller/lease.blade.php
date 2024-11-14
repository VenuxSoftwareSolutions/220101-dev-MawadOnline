
@extends('seller.layouts.app')

@section('panel_content')
@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{__('lease.Leases')}}</h1>
		</div>
	</div>
</div>

<div id="step1" class="card">
    <div class="card-header">
        <h5 class="mb-0 h6 fw-700 fs-22"><div>{{__('lease.Current Lease Due')}}</div> <br>
        @if ($current_lease)
            {{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->start_date)->isoFormat('DD-MMMM-YYYY')}} {{__('lease.to')}}
            {{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->end_date)->isoFormat('DD-MMMM-YYYY')}}
        @endif
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
                    <!-- <th>{{__('lease.Status')}}</th> -->
                </tr>
            </thead>
            <tbody>
                @if($current_lease)
                <tr>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{__('lease.e-Shop lease')}}</td>
                    <td>{{$current_lease->package->amount}}</td>
                    <!-- <td style="color: red;">{{__('lease.Unpaid')}}</td> -->
                </tr>
                @foreach($current_details as $key => $detail)
                    <tr>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>1 {{$detail->role->getTranslation('name')}} {{__('lease.Role')}}</td>
                        <td>{{$detail->amount}}</td>
                        <!-- <td style="color: red;">{{__('lease.Unpaid')}}</td> -->
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <!-- <td></td> -->
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
                        <!-- <div>
                            <a href="{{route('vendor.pay.plan',['plan'=>'prod_RA4L1fkvO8w7Y9'])}}" class="btn btn-primary fw-600" disabled>{{__('lease.pay_with_stripe')}}</a>
                        </div> -->
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@if($leases)
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
                    <!-- <th>{{__('lease.Status')}}</th> -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $lease->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $lease->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{__('lease.e-Shop lease')}}</td>
                    <td>{{$lease->package->amount}}</td>
                    <!-- <td style="color: red;">{{__('lease.Unpaid')}}</td> -->
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
                        <!-- <td style="color: red;">{{__('lease.Unpaid')}}</td> -->
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <!-- <td></td> -->
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
@endif
@section('script')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('startTourButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default anchor click behavior
        localStorage.setItem('guide_tour', '0'); // Set local storage as required
        window.location.href = '{{ route("seller.dashboard") }}'; // Redirect to the dashboard
    });
    if (localStorage.getItem('guide_tour') != '0') {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}} ) {
            return;
        }
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
            localStorage.setItem('guide_tour', '1'); // Set local storage as required

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

