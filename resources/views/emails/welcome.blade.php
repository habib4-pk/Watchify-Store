<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to WatchifyStore</title>

  
    <link rel="stylesheet" href="{{ asset('css/email.css') }}">
</head>
<body class="email-body">

    <div class="email-container">

        <h2 class="email-title">Welcome to WatchifyStore ⌚</h2>

        <p class="email-text">
            Hello <b>{{ $mailData['name'] ?? 'User' }}</b>,
        </p>

        <p class="email-text">
            Thank you for registering on <b>WatchifyStore</b>.
            We are excited to have you with us!
        </p>

        <p class="email-text">
            You can now explore our latest watch collections,
            place orders, and track your order status easily.
        </p>

        <p class="email-happy">
            Happy Shopping 😊
        </p>

        <hr class="email-divider">

        <p class="email-footer">
            © {{ date('Y') }} WatchifyStore. All rights reserved.
        </p>

    </div>

</body>
</html>
