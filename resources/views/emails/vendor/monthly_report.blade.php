<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Performance Report</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2c3e50;">Your Lake County Local Deals Monthly Report</h2>
        
        <p>Hi {{ $vendor->first_name }},</p>
        
        <p>Here's how you did this month:</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0;">📊 Performance</h3>
            <ul>
                <li><strong>{{ $stats->vouchers_sold }}</strong> vouchers sold</li>
                <li><strong>${{ number_format($stats->gross_sales, 2) }}</strong> in gross sales</li>
                <li><strong>{{ $stats->vouchers_sold }}</strong> new customers acquired</li>
            </ul>
        </div>
        
        <div style="background: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0;">💰 Costs</h3>
            <ul>
                <li>Base subscription: <strong>${{ number_format($stats->base_subscription_fee, 2) }}</strong></li>
                <li>Commissions: <strong>${{ number_format($stats->total_commissions, 2) }}</strong></li>
                <li><strong>Total: ${{ number_format($stats->total_cost, 2) }}</strong></li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('vendor.upgrade.index') }}" style="background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View Full Dashboard →
            </a>
        </div>
        
        <p>Keep up the great work!</p>
        
        <p>- Vincent</p>
    </div>
</body>
</html>


