<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Voucher Limit Reached</title>
</head>
<body>
    <p>Hello {{ $vendor->business_name }},</p>

    <p>You've reached your monthly voucher limit of {{ number_format($vendor->monthly_voucher_limit) }} vouchers. All your deals have been automatically paused.</p>

    <p>Customers will see: <em>"Sold out for this month."</em></p>

    <p>Your options:</p>
    <ol>
        <li>Wait until {{ $resetDate }} when your counter resets.</li>
        <li>Upgrade to {{ $nextTierName }} for {{ number_format($nextTierLimit) }} vouchers/month.</li>
    </ol>

    <p>
        <a href="{{ $upgradeUrl }}" style="background:#2563eb;color:#fff;padding:10px 16px;text-decoration:none;border-radius:4px;">Upgrade Now</a>
    </p>

    <p>Questions? Contact <a href="mailto:support@lakecountydeals.com">support@lakecountydeals.com</a>.</p>

    <p>— Lake County Local Deals Team</p>
</body>
</html>

