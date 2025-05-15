<!DOCTYPE html>
<html>
<head>
    <title>MawadOnline Vendor Status Notification</title>
    <style>
        :root {
            --primary-blue: #3D3D3B;
            --action-orange: #FF6B35;
            --success-green: #2A9D8F;
            --background-gray: #F8F9FA;
            --text-dark: #2D3748;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: var(--background-gray);
        }

        .header-bg {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #0F2347 100%);
            padding: 30px 0;
        }

        .status-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin: 20px;
            padding: 40px;
        }

        .status-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 20px;
        }

        a.action-button {
            background: var(--action-orange);
            color: white;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto;">
        <!-- Header -->
        <div class="header-bg">
            <center>
                @php
                    $header_logo = get_setting('header_logo');
                @endphp
                 @if ($header_logo != null)
                    <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                        class="mw-100 h-30px h-md-40px" height="40">
                @else
                    <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                        class="mw-100 h-30px h-md-40px" height="40">
                @endif                
                <h1 style="color: white; margin: 15px 0 0 0; font-size: 24px;">
                    @if($newStatus == 'Enabled') Account Approved
                    @elseif($newStatus == 'Rejected') Application Update
                    @elseif($newStatus == 'Closed') Account Closed
                    @else Status Notification @endif
                </h1>
            </center>
        </div>

        <!-- Status Content -->
        <div class="status-card">
            @if($newStatus == 'Suspended')
            <div style="border-left: 4px solid var(--action-orange); padding-left: 20px;">
                @if(!empty($reason))
                <h3 style="color: var(--primary-blue); margin-top: 0;">
                    <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    Account Suspension Notice
                </h3>
                <div style="color: var(--text-dark); line-height: 1.6;">
                    {!! $reason !!}
                    <p style="color: #6c757d; margin-top: 15px;">
                        {{ __('messages.due_to_reason_above') }} {{ __('messages.suspended_email') }}
                    </p>
                </div>
                @endif
            </div>

            @elseif($newStatus == 'Pending Approval')
            <div style="text-align: center;">
                <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <h2 style="color: var(--primary-blue);">Registration Received</h2>
                <p style="color: var(--text-dark); line-height: 1.6;">
                    Dear {{ $vendorName }},<br>
                    Thank you for registering with MawadOnline. Your account is currently under review.
                    We will notify you once the review process is complete.
                </p>
            </div>

            @elseif($newStatus == 'Enabled')
            <div style="text-align: center;">
                <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 style="color: var(--primary-blue);">Approval Complete!</h2>
                <div style="color: var(--text-dark); line-height: 1.6; text-align: left; margin: 0 15px;">
                    <p>Hi {{ $vendorName }},</p>
                    <p>We're delighted to welcome you to MawadOnline! Your application has been approved and you're now part of our vibrant vendor community. This is the perfect time to access your dashboard and start listing your products.</p>
                    
                    <p>Feeling a bit overwhelmed? Don't worry! We've got a library of step-by-step guides. Our comprehensive guides and resources are available to ensure you hit the ground running.</p>
                    
                    <p>Need help? A dedicated support team is ready to assist you at any time. Your success is our priority and we're here to help you achieve it.</p>
                    
                    <p>Jump into your dashboard today and start crafting your success story with us!</p>
                </div>
                <a href="{{ route('seller.login') }}" class="action-button" style="margin-top: 25px;">
                    Access Your Vendor Dashboard Now
                </a>
                <p style="color: var(--text-dark); margin-top: 25px;">
                    Best regards,<br>
                    The MawadOnline Team
                </p>
            </div>

            @elseif($newStatus == 'Rejected')
            <div style="border-left: 4px solid #dc3545; padding-left: 20px;">
                <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 style="color: var(--primary-blue); margin-top: 0;">Your MawadOnline Vendor Application Review Outcome</h3>
                <div style="color: var(--text-dark); line-height: 1.6; white-space: normal;">
                    <p>Dear {{ $vendorName }},</p>
                    <p>
                        Thank you for your recent application to become a vendor on MawadOnline. After a thorough review, we regret to inform you that your application does not currently align with our vendor criteria. While this is not the outcome we had hoped for, we hope there are still valuable business opportunities within our platform that may suit your offerings more closely.
                    </p>
                    <p>
                        To assist you further, we've provided specific feedback on the areas where your application did not meet our requirements:
                    </p>
                    <div style="background: #FFF9F7; padding: 15px; margin: 15px 0; border-radius: 4px;">
                        {!! $reason !!}
                    </div>
                    <p>
                        If you have any questions or need more detailed feedback, please don't hesitate to reach out to our vendor support team. We are here to help you explore tailored opportunities that match your business strengths.
                    </p>
                    <a href="mailto:vendor-support@mawadonline.com" class="action-button">
                        Contact Support Team
                    </a>
                    <p style="color: #6c757d; margin-top: 15px;">
                        We look forward to possibly working together in the future.
                    </p>
                    <p style="margin-top: 20px;">
                        Best regards,<br>
                        The MawadOnline Team
                    </p>
                </div>
            </div>
        @endif
        </div>

        <!-- Footer -->
        <div style="background: var(--primary-blue); padding: 25px; text-align: center; color: white; font-size: 12px;">
            <p style="margin: 5px 0; line-height: 1.5;">
                © {{ date('Y') }} MawadOnline - Building the Future of Construction Materials<br>
                Need help? <a href="mailto:vendor-support@mawadonline.com" 
                            style="color: var(--action-orange); text-decoration: none; font-weight: 500;">
                    Contact Vendor Support
                </a>
            </p>
        </div>
    </div>
</body>
</html>