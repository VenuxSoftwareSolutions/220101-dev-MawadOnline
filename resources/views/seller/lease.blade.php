
@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('Leases')}}</h1>
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6 fw-700 fs-22"><div>{{translate('Current Lease Due')}}</div> <br>
            {{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->start_date)->isoFormat('DD-MMMM-YYYY')}} to
            {{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->end_date)->isoFormat('DD-MMMM-YYYY')}}
        </h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Charge for</th>
                    <th>Amount (AED)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $current_lease->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>e-Shop lease</td>
                    <td>{{$current_lease->package->amount}}</td>
                </tr>
                @foreach($current_details as $key => $detail)
                    <tr>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>1 {{$detail->role->name}} Role</td>
                        <td>{{$detail->amount}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-right"><div>Onboarding Discount:</div> <br>
                        <div>Sub Total:</div> <br>
                        <div>VAT:</div> <br>
                        <div>Paid Amount:</div> <br>
                        <div class="fw-700">Total Lease Due:</div>
                    </td>
                    <td class="text-right"><div>-{{$current_lease->discount}} AED</div> <br>
                        <div>{{number_format($current_lease->total-$current_lease->discount,2)}} AED</div> <br>
                        <div>{{ number_format(($current_lease->total - $current_lease->discount) * 0.05, 2) }} AED</div> <br>
                        <div>0.00 AED</div> <br>
                        <div class="fw-700">{{number_format(($current_lease->total-$current_lease->discount)*0.05+($current_lease->total-$current_lease->discount),2)}} AED</div> <br>
                        <div>
                            <button class="btn btn-primary fw-600" disabled>Pay Now</button>
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
        <h5 class="mb-0 h6 fw-700 fs-22"><div>{{translate('Lease History')}}</div> <br>
            {{Carbon\Carbon::createFromFormat('Y-m-d', $lease->start_date)->isoFormat('DD-MMMM-YYYY')}} to
            {{Carbon\Carbon::createFromFormat('Y-m-d', $lease->end_date)->isoFormat('DD-MMMM-YYYY')}}
        </h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Charge for</th>
                    <th>Amount (AED)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $lease->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $lease->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                    <td>e-Shop lease</td>
                    <td>{{$lease->package->amount}}</td>
                </tr>
                @php
                    $details=App\Models\SellerLeaseDetail::where('lease_id',$lease->id)->get();
                @endphp
                @foreach($details as $key => $detail)
                    <tr>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->start_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $detail->end_date)->isoFormat('DD-MMMM-YYYY')}}</td>
                        <td>1 {{$detail->role->name}} Role</td>
                        <td>{{$detail->amount}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-right"><div>Onboarding Discount:</div> <br>
                        <div>Sub Total:</div> <br>
                        <div>VAT:</div> <br>
                        <div>Paid Amount:</div> <br>
                        <div class="fw-700">Total Lease Due:</div>
                    </td>
                    <td class="text-right"><div>-{{$lease->discount}} AED</div> <br>
                        <div>{{number_format($lease->total-$lease->discount,2)}} AED</div> <br>
                        <div>{{ number_format(($lease->total - $lease->discount) * 0.05, 2) }} AED</div> <br>
                        <div>0.00 AED</div> <br>
                        <div class="fw-700">{{number_format(($lease->total-$lease->discount)*0.05+($lease->total-$lease->discount),2)}} AED</div> <br>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@endforeach
@endsection

@section('script')

@endsection

