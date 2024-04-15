<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vendor Profile Changes</title>
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
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 5px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        img {
            max-width: 200px;
            display: block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('public/uploads/all/CxeI3PF3NMzjzHp6Ct3xf8dPS1q2pFYmwAwbHQii.png') }}" alt="Logo">
        <h2>Vendor Profile Changes Pending Approval</h2>
        <p>There are pending changes in the vendor profile that require your approval:</p>
        <ul>
            <li><strong>Vendor Name:</strong> {{ $vendor->name }}</li>
            <li><strong>Vendor Email:</strong> {{ $vendor->email }}</li>
        </ul>
        <p>
            <a href="{{ $vendorProfileUrl }}">View Vendor Profile</a>
        </p>
    </div>
</body>
</html>
