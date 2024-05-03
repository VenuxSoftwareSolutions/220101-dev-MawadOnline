
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MawadOnline</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

    </style>
</head>
<body>

    <p>Dear {{ $user->name }},</p>

    <p>We’re excited to welcome you to the MawadOnline team! Your account for Inventory Management has been set up by your manager, {{$vendor->name}}.</p>

    <h3>Access Details:</h3>
    <ul>
        <li><b>Email:</b> {{ $user['email'] }}</li>
        <li><b>Temporary Password: {{ $password }}</b> (You will be prompted to change this after your first login for security purposes.)</li>
    </ul>

    <p>To get started, simply click the link below to log in and familiarize yourself with your new dashboard:</p>
    <p><a href="{{ $url }}">Login Link</a></p>

    <p>As part of our team, your expertise in inventory management is crucial to our success. If you have any questions or need assistance as you settle in, our support team is here to help.</p>

    <p>Thank you for joining us, and here’s to a fruitful collaboration!</p>

    <p>Warm regards, The MawadOnline Team</p>

</body>
</html>
