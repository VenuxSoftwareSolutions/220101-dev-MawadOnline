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
            margin: auto;
            display: block
        }
        body {
            font-family: Arial, sans-serif;
            /* background-color: #f5f5f5; */
            padding: 20px;
        }
        .suspension-details {
            /* background-color: #f8f9fa; */
            /* border: 1px solid #ced4da; */
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
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .signature {
            margin-top: 20px;
            font-style: italic;
            color: #666;
        }

    </style>
</head>
<body>
    <div>
        <img src="{{ $logo }}" alt="Mawad Online Logo">
        {{-- <h1>Mawad Online</h1> --}}
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
        {{-- <div class="container"> --}}
            <!-- Display suspension reason and details -->
            <p>Dear {{$vendorName}},</p>
            <p>Thank you for registering with MawadOnline. Your account is currently under review for approval. We will notify you once the review process is complete.</p>
            <p>Please check your email regularly, including your spam folder, for updates.</p>
            <p>Best regards,<br>MawadOnline Team<p>

        {{-- </div> --}}

        </div>
        @elseif ($newStatus == 'Closed')
        <p>Dear {{$vendorName}},</p>

        <p>We regret to inform you that your vendor account has been closed. If you have questions or need further information, please do not hesitate to contact our support team: <a href="#">Support</a></p>

        <p>Thank you for having been part of MawadOnline.</p>

        <p>Best regards,<br>MawadOnline Team</p>

        @elseif ($newStatus == 'Pending Closure')
        <p>Dear {{$vendorName}},</p>

        <p>Your account is pending closure and will be deleted in [number] days. Please contact our support team as soon as possible for more details: <a href="#">Support</a></p>

        <p>Thank you for using MawadOnline.</p>

        <p>Best regards,<br>MawadOnline Team</p>

        @elseif ($newStatus == 'Enabled')
            <p>Dear {{$vendorName}},</p>

            <p>Congratulations! Your vendor account has been approved. You are now able to log in and start selling your products on MawadOnline.</p>

            <p>Log in to your account <a href="{{route('seller.login')}}">here</a>.</p>

            <p>Thank you for joining our community.</p>

            <p>Best regards,<br>MawadOnline Team</p>

        @elseif ($newStatus == 'Rejected')
            {{-- <!-- Display message for rejected status -->
            <p>Your vendor registration has been rejected. You can update your registration form and resubmit it for review. Upon resubmission, your status will change to "Pending Approval". Please ensure to resubmit your registration within 30 days, otherwise, your account will be automatically deleted.</p>
             <!-- Display suspension details if available -->
            @if (!empty($reason))
            <!-- Display the reason -->
            <p>Reason for rejection: {!! $reason !!}</p>
           @endif
            <!-- Add Font Awesome icon for rejected status -->
            <i class="fas fa-times-circle fa-3x text-danger"></i>
            Vendor Email: {{ $vendorEmail }} --}}

            <p>Dear {{$vendorName}},</p>

            <p>We regret to inform you that your application for a vendor account has been rejected. Please review the reasons below and update your registration as needed:</p>

            <p>{!! $reason !!}</p>

            <p>Please update the information <a href="{{route('seller.login')}}">here</a> and submit your profile for review.</p>

            <p>You may resubmit your application within 30 days. If not resubmitted within this timeframe, your account will be automatically deleted.</p>

            <p>We are looking forward to welcoming you to MawadOnline.</p>

            <p>Best regards,<br>MawadOnline Team</p>
        @endif
        {{-- <p>Your vendor status has been changed from {{ $oldStatus }} to {{ $newStatus }}.</p> --}}
        {{-- <p>{{ __('messages.thank_you') }}</p> --}}
    </div>
</body>
</html>
