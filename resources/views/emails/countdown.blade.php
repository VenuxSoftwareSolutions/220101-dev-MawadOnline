<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder</title>
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
                  <p>Dear {{$order->seller->name}},</p>
                  <p>order ref {{$order->order->code}} received on {{$order->created_at->format("Y-m-d H:m:s")}} is pending confirmation. Please proceed to confirm and fulfill your order. <a href="{{ route('seller.orders.show', encrypt($order->order->id)) }}">View Order Details</a></p>
                  <p>Please feel free to contact our vendor support center for help.</p>
                  <p>Best regards,<br>MawadOnline Team</p>
    </div>
</body>
</html>
