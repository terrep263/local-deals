<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subscription Upgraded</title>
</head>
<body>
    <p>Hello {{ $vendor->business_name }},</p>

    <p>Congratulations! Your subscription has been upgraded.</p>

    <ul>
        <li><strong>New tier:</strong> {{ ucfirst(str_replace('_', ' ', $vendor->subscription_tier)) }}</li>
        <li><strong>New voucher limit:</strong> {{ number_format($voucherLimit) }} per month</li>
    </ul>

    <p>Your voucher counter has been reset and any auto-paused deals have been resumed.</p>

    <p>Thank you for growing with us!</p>

    <p>— Lake County Local Deals Team</p>
</body>
</html>

