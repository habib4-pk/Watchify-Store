<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Status Update</title>
</head>
<body style="font-family: Arial, sans-serif;">

    <h2>Hello {{ $mailData['name'] ?? 'Customer' }},</h2>

    <p>Your order with Order ID <b>{{ $mailData['order_id'] }}</b> status has been updated to:</p>

    <h3>{{ ucfirst($mailData['status']) }}</h3>

    <p>Thank you for shopping with us!</p>

    <p>Regards,<br>WatchifyStore Team</p>

</body>
</html>
