<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Purchase Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #28a745;">New Purchase for Your Deal!</h1>
        
        <p>Hello {{ $deal->vendor->first_name }},</p>
        
        <p>Someone has purchased your deal. Here are the details:</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-left: 4px solid #007bff; margin: 20px 0;">
            <h2 style="margin-top: 0;">{{ $deal->title }}</h2>
            <p><strong>Consumer Email:</strong> {{ $purchase->consumer_email }}</p>
            @if($purchase->consumer_name)
            <p><strong>Consumer Name:</strong> {{ $purchase->consumer_name }}</p>
            @endif
            <p><strong>Confirmation Code:</strong> <span style="font-size: 24px; color: #007bff; font-weight: bold;">{{ $purchase->confirmation_code }}</span></p>
            <p><strong>Purchase Amount:</strong> ${{ number_format($purchase->purchase_amount, 2) }}</p>
            <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date->format('F d, Y g:i A') }}</p>
        </div>
        
        <div style="background: #e7f3ff; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <h3 style="margin-top: 0;">Inventory Status</h3>
            <p><strong>Total Inventory:</strong> {{ $deal->inventory_total }}</p>
            <p><strong>Sold:</strong> {{ $deal->inventory_sold }}</p>
            <p><strong>Remaining:</strong> {{ $deal->inventory_remaining }}</p>
        </div>
        
        <div style="margin: 20px 0;">
            <h3>What to Do:</h3>
            <ol>
                <li>When the customer visits, ask for their confirmation code</li>
                <li>Verify the code matches: <strong>{{ $purchase->confirmation_code }}</strong></li>
                <li>Redeem the deal and mark it as used in your system</li>
            </ol>
        </div>
        
        <p style="margin-top: 30px;">
            <a href="{{ route('vendor.deals.index') }}" 
               style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View All Deals
            </a>
        </p>
        
        <p>Thank you for using {{ getcong('site_name') }}!</p>
    </div>
</body>
</html>


