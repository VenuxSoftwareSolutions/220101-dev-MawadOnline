@extends('frontend.layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .text-primary {
            color: #007bff !important;
        }
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
    <section class="pt-4 mb-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    @if(Auth::user()->status == "Pending Approval")
                        <h1 class="fw-600 h4">{{ __('messages.registration_completed') }}</h1>
                        <!-- Display a nice icon here -->
                        <i class="fas fa-hourglass fa-3x text-primary"></i>
                    @elseif (Auth::user()->status == "Pending Closure")
                        {{-- <h1 class="fw-600 h4">{{ __('messages.pending_closure') }}</h1>
                        <!-- Add Font Awesome icon for pending closure -->
                        <i class="fas fa-exclamation-triangle fa-3x text-warning"></i> --}}
                              <!-- Display suspension reason and details -->
                              <div class="card suspension-card">
                                <div class="card-body">
                                    <h5 class="suspended-heading card-title">{{ __('messages.account_status'). ': '. __('messages.pending_closure_title') }}</h5>
                                    <p class="card-text">{{ __('messages.contact_support') }}</p>
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                                </div>
                            </div>

                    @elseif (Auth::user()->status == "Closed")
                        <h1 class="fw-600 h4">{{ __('messages.vendor_closed') }}</h1>
                        <!-- Add Font Awesome icon for closed status -->
                        <i class="fas fa-lock fa-3x text-danger"></i>
                    @elseif (Auth::user()->status == "Suspended")
                    <h1 class="fw-600 h4 suspended-heading">{{ __('messages.suspended') }}</h1>
                    @php
                        $suspendedStatusHistory = Auth::user()->getSuspendedStatusHistory();
                    @endphp
                    @if ($suspendedStatusHistory)
                        <!-- Display suspension reason and details -->
                        <div class="card suspension-card">
                            <div class="card-body">
                                <h5 class="card-title">{{ __('messages.reason') }}</h5>
                                <p class="card-text">{{ $suspendedStatusHistory->suspension_reason }}</p>
                                <h5 class="card-title">{{ __('messages.details') }} </h5>
                                <p class="card-text">{!! $suspendedStatusHistory->details !!}</p>
                            </div>
                        </div>

                        {{-- <!-- Map suspension reasons to icons -->
                        @php
                            $reasonsIcons = [
                                'Fraud' => 'fa-exclamation-triangle',
                                'Violation of Policies' => 'fa-ban',
                                'Non-compliance' => 'fa-times-circle',
                                'Legal Issues' => 'fa-gavel',
                                'Non-payment' => 'fa-hand-holding-usd',
                                'IT Security Concerns' => 'fa-lock',
                            ];
                        @endphp
                        <!-- Display suspension reason with icon -->
                        @if (array_key_exists($suspendedStatusHistory->reason, $reasonsIcons))
                            <div class="suspension-reason">
                                <i class="fas {{ $reasonsIcons[$suspendedStatusHistory->reason] }}"></i>
                                <span>{{ __('messages.reason') . $suspendedStatusHistory->reason }}</span>
                            </div>
                        @endif --}}
                    @endif
                @endif
                </div>
            </div>
        </div>
    </section>
@endsection
