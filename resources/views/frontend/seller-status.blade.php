@extends('frontend.layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .text-primary {
            color: #007bff !important;
        }
        .suspension-reason {
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 5px;
        background-color: #f8d7da; /* Light red background */
        border: 1px solid #f5c6cb; /* Border color */
    }

    .suspension-reason i {
        margin-right: 10px; /* Spacing between icon and text */
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
                        <h1 class="fw-600 h4">{{ __('messages.pending_closure') }}</h1>
                        <!-- Add Font Awesome icon for pending closure -->
                        <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                    @elseif (Auth::user()->status == "Closed")
                        <h1 class="fw-600 h4">{{ __('messages.vendor_closed') }}</h1>
                        <!-- Add Font Awesome icon for closed status -->
                        <i class="fas fa-lock fa-3x text-danger"></i>
                    @elseif (Auth::user()->status == "Suspended")
                    <h1 class="fw-600 h4">{{ __('messages.suspended') }}</h1>
                    @php
                        $suspendedStatusHistory = Auth::user()->getSuspendedStatusHistory();
                    @endphp
                        @if ($suspendedStatusHistory)
                        <!-- Display suspension reason and details -->
                        <div class="suspension-reason">
                            <p>{{ __('messages.reason') . $suspendedStatusHistory->suspension_reason }}</p>
                            <p>{{ __('messages.details') . $suspendedStatusHistory->details }}</p>
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
