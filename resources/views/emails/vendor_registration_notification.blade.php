<!-- resources/views/emails/vendor_registration_notification.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Vendor Registration Request</title>
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 200px;
            height: auto;
        }
        .message {
            margin-bottom: 20px;
        }
        .signature {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ $logo }}" alt="Logo">
        </div>
        <div class="message">

            <p>Dear Admin,</p>

            <p>A new vendor registration has been submitted and requires your approval. Here are the details:</p>

            <ul>
                <li>Vendor Name: {{ $vendorName }}</li>
                <li>Vendor Phone Number: {{$vendorPhone}}</li>
                <li>Vendor Email: {{ $vendorEmail }}</li>
            </ul>
        </div>
        <div class="signature">
            <p>Thank you,<br>MawadOnline Team</p>

        </div>
    </div>
</body>
</html>
