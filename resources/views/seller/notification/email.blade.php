<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        img {
            width: 200px;
            height: auto;
        }
        .suspension-details {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            padding: 10px;
            margin-bottom: 20px;
        }

        .suspension-reason {
            font-weight: bold;
            color: #dc3545; /* Red color for emphasis */
        }

        .suspension-message {
            margin-top: 5px;
            color: #6c757d; /* Gray color for additional information */
        }

    </style>
</head>
<body>
    <div>
        <img src="{{ $logo }}" alt="Mawad Online Logo">
        <h1>Mawad Online</h1>
        @if ($newStatus == 'Suspended')
            <!-- Display suspension reason and details -->
            <div class="suspension-details">
                {{-- <p>{{ __('messages.reason') }}: {{ $suspendedStatusHistory->reason }}</p>
                <p>{{ __('messages.details') }}: {{ $suspendedStatusHistory->details }}</p>
                <h1 class="fw-600 h4">{{ __('messages.suspended') }}</h1> --}}
                @if (!empty($reason))
                    <span class="suspension-reason">{!! $reason !!}</span>
                @endif
                <p class="suspension-message">{{ __('messages.due_to_reason_above') }} {{ __('messages.suspended_email') }}</p>

            </div>
        @elseif ($newStatus == 'Pending Approval')
        <!-- Display suspension reason and details -->

            <p>{{ __('messages.registration_completed') }}</p>
            <i class="fas fa-hourglass fa-3x text-primary"></i>

        </div>
        @elseif ($newStatus == 'Closed')
            <!-- Display message for closed status -->
            <p>{{ __('messages.vendor_closed') }}</p>
            <!-- Add Font Awesome icon for closed status -->
            <i class="fas fa-lock fa-3x text-danger"></i>
        @elseif ($newStatus == 'Pending Closure')
            <!-- Display message for pending closure status -->
            <p>Your account is pending closure. Please contact the MawadOnline support team for further details.</p>
            <!-- Add Font Awesome icon for pending closure -->
            <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
        @elseif ($newStatus == 'Enabled')
            <!-- Display message for pending approval status -->
            <p>{{ __('messages.approved') }}</p>
            <!-- Add Font Awesome icon for pending approval -->
            <i class="fas fa-hourglass fa-3x text-primary"></i>

        @elseif ($newStatus == 'Rejected')
            <!-- Display message for rejected status -->
            <p>Your vendor registration has been rejected. You can update your registration form and resubmit it for review. Upon resubmission, your status will change to "Pending Approval". Please ensure to resubmit your registration within 30 days, otherwise, your account will be automatically deleted.</p>
             <!-- Display suspension details if available -->
            @if (!empty($reason))
            <!-- Display the reason -->
            <p>Reason for rejection: {!! $reason !!}</p>
           @endif
            <!-- Add Font Awesome icon for rejected status -->
            <i class="fas fa-times-circle fa-3x text-danger"></i>
            Vendor Email: {{ $vendorEmail }}
        @endif
        {{-- <p>Your vendor status has been changed from {{ $oldStatus }} to {{ $newStatus }}.</p> --}}
        <p>{{ __('messages.thank_you') }}</p>
    </div>
</body>
</html>
