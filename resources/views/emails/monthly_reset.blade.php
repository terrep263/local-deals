<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Voucher Counter Reset</title>
</head>
<body>
    <p>Hello {{ $vendor->business_name }},</p>

    <p>Your voucher counter has been reset for {{ $resetMonth }}!</p>

    <ul>
        <li>Last month you sold <strong>{{ number_format($vouchersUsedLastMonth) }}</strong> vouchers.</li>
        <li>You now have <strong>{{ number_format($vendor->monthly_voucher_limit) }}</strong> fresh vouchers available.</li>
        <li>Your deals have been automatically resumed and are live.</li>
        <li>Current tier: {{ ucfirst(str_replace('_', ' ', $vendor->subscription_tier)) }}</li>
    </ul>

    <p>Last month’s performance:</p>
    <ul>
        <li>Vouchers sold: {{ number_format($vouchersUsedLastMonth) }}</li>
        <li>Redemption rate: N/A</li>
        <li>Revenue generated: N/A</li>
        <li>Most popular deal: N/A</li>
    </ul>

    <p>
        <a href="{{ $dashboardUrl }}" style="background:#16a34a;color:#fff;padding:10px 16px;text-decoration:none;border-radius:4px;">View Dashboard</a>
        &nbsp;
        <a href="{{ $upgradeUrl }}" style="background:#2563eb;color:#fff;padding:10px 16px;text-decoration:none;border-radius:4px;">Upgrade Tier</a>
    </p>

    <p>Questions? Contact <a href="mailto:support@lakecountydeals.com">support@lakecountydeals.com</a>.</p>

    <p>— Lake County Local Deals Team</p>
</body>
</html>

