<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Lake County Local Deals</title>
</head>
<body>
    <p>Hello {{ $user->first_name ?: 'there' }},</p>

    <p>Welcome to Lake County Local Deals! Your vendor account has been created. Here are your login credentials:</p>

    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>You can log in here: <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>

    <p>Next steps:</p>
    <ol>
        <li>Log in and change your password.</li>
        <li>Connect your Stripe account.</li>
        <li>Complete your business profile.</li>
        <li>Create your first deal.</li>
    </ol>

    <p>If you need any help, reach out to <a href="mailto:support@lakecountydeals.com">support@lakecountydeals.com</a>.</p>

    <p>Thank you,<br>Lake County Local Deals Team</p>
</body>
</html>

