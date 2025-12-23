<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif;">

    <h2>Thank you for your order, {{ $orderData['customer_name'] }}!</h2>

    <p>Your order has been successfully placed with the following details:</p>

    <ul>
        <li><strong>Order ID:</strong> {{ $orderData['order_id'] }}</li>
        <li><strong>Total Amount:</strong> PKR {{ number_format($orderData['total_amount'], 2) }}</li>
        <li><strong>Status:</strong> {{ $orderData['status'] }}</li>
    </ul>

    <p>We will notify you once your order is shipped.</p>

    <p>Thanks for shopping with WatchifyStore!</p>

</body>
</html>
