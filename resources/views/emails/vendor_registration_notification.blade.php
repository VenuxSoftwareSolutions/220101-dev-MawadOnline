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
            <p>Hello Admin,</p>
            <p>A new vendor registration request has been submitted.</p>
            <p>Vendor Name: {{ $vendorName }}</p>
            <p>Vendor Email: {{ $vendorEmail }}</p>
        </div>
        <div class="signature">
            <p>Regards,</p>
            <p>Your Application Team</p>
        </div>
    </div>
</body>
</html>
