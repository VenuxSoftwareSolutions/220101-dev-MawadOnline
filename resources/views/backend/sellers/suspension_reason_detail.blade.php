@extends('backend.layouts.app')
@section('css')
<style>
        .suspension-card {
    border: 1px solid #ddd; /* Border color */
    border-radius: 5px; /* Rounded corners */
    padding: 20px; /* Padding inside the card */
    background-color: #f9f9f9; /* Background color */
    margin-bottom: 20px; /* Spacing at the bottom */
}

.suspension-card .card-title {
    font-weight: bold; /* Make the title bold */
    margin-bottom: 10px; /* Spacing below the title */
}

.suspension-card .card-text {
    color: #555; /* Text color */
    font-size: 16px; /* Text size */
}
.suspended-heading {
    color: #ff0000; /* Red color */
}

img {
    max-width: 100% !important;
}
</style>
@endsection
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            @if ($suspensionReasonDetail->status == "Rejected")
                 <h1 class="h3">{{ translate('Vendor Rejected Reason Detail') }}</h1>
            @else
                 <h1 class="h3">{{ translate('Vendor Suspension Reason Detail') }}</h1>
            @endif

        </div>
    </div>
</div>


<div class="card suspension-card">
    <div class="card-body">
        @if ($suspensionReasonDetail->status == "Suspended")
            <h5 class="card-title">{{ __('messages.reason') }}</h5>
            <p class="card-text">{{ $suspensionReasonDetail->suspension_reason }}</p>
        @endif
        <h5 class="card-title">{{ __('messages.details') }} </h5>
        <p class="card-text">{!! $suspensionReasonDetail->details !!}</p>
    </div>
</div>

@endsection
@section('script')

@endsection
