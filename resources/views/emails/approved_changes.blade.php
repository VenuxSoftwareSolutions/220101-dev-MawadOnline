<!-- resources/views/emails/approved_changes.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposed Changes Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333333;
        }
        p {
            color: #666666;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        img {
            max-width: 200px;
            display: block;
            margin: auto;
            margin-bottom: 20px; /* Adjust the value as needed */

        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('public/uploads/all/CxeI3PF3NMzjzHp6Ct3xf8dPS1q2pFYmwAwbHQii.png') }}" alt="Logo">
                  <!-- Display suspension reason and details -->
                  <p>Dear {{$name}},</p>
                  <p>We are pleased to inform you that the changes you requested to your profile have been approved. You can view your updated profile here: <a href="{{ route('seller.profile.index') }}">View Your Vendor Profile</a></p>
                  <p>Best regards,<br>MawadOnline Team</p>
    </div>
</body>
</html>
