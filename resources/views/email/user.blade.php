<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
    <style>
        /* Add your styles here. It's better to inline your CSS for email templates */
    </style>
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: auto;">
        <tr>
            <td align="left" style="background-color: #ffffff; padding: 20px;">
                <!-- Logo -->
                <img src="{{ asset('public/home_page/images/MawadLogo1.png') }}" alt="MawadOnline Logo" style="max-width: 100px;">
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px; background-color: #eee;">
                <!-- Banner Image -->
                <img src="{{ asset('public/banner.png') }}" alt="Banner" style="width: 100%; height: auto;">
            </td>
        </tr>
        <tr>
            <td style="background-color: #ffffff; padding: 20px;">
                <h1>Greetings from MawadOnline,</h1>
                <p>Thank you for joining the MawadOnline waiting list! We're thrilled to have you with us.</p>
                <p>You're now part of a community that prioritizes efficiency, reliability, and excellence in the construction materials, services, and equipment industry.</p>

                <h2>What's Next?</h2>

                <h3>Exclusive Access:</h3>
                <p>Stay tuned for your exclusive invitation to our platform, where you'll find everything you need to streamline your construction projects.</p>

                <h3>Stay Informed:</h3>
                <p>We'll keep you updated with the latest news, offers, and tips directly to your inbox.</p>

                <h3>Get Ready:</h3>
                <p>Prepare to explore a wide range of products, compare prices, and make informed decisions—all in one place.</p>

                <p>We're here to support your projects every step of the way.</p>

                <p>If you have any questions or need assistance, please don't hesitate to reach out.</p>

                <p>Welcome aboard,</p>
                <p style="color: #C9754A;">MawadOnline Team</p>
            </td>
        </tr>
        <tr>
            <td align="center" style="background-color: #FFF2EB; padding: 20px;">
                <p>Need help? <a style="color: #C9754A;" href="{{ url('path/to/support') }}">Contact our support team</a> or no longer interested in our newsletters? <a style="color: #C9754A;" href="{{ url('path/to/unsubscribe') }}">Unsubscribe here</a>. Want to give us feedback? Let us know what you think on our <a style="color: #C9754A;" href="{{ url('path/to/feedback') }}">Feedback site</a>.</p>
                <!-- Social Media Icons -->
                <!-- Add your social media links with icons here -->
            </td>
        </tr>
    </table>
</body>

</html>