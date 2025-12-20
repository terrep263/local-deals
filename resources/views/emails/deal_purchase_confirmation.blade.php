<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Deal Voucher</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #28a745;">Thank You for Your Purchase!</h1>
        
        <p>Hello{{ $purchase->consumer_name ? ' ' . $purchase->consumer_name : '' }},</p>
        
        <p>Your purchase has been confirmed. Here are your voucher details:</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-left: 4px solid #007bff; margin: 20px 0;">
            <h2 style="margin-top: 0;">{{ $deal->title }}</h2>
            <p><strong>Confirmation Code:</strong></p>
            <h1 style="color: #007bff; font-size: 36px; margin: 10px 0;">{{ $purchase->confirmation_code }}</h1>
            <p><strong>Purchase Amount:</strong> ${{ number_format($purchase->purchase_amount, 2) }}</p>
            <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date->format('F d, Y') }}</p>
            <p><strong>Expires:</strong> {{ $deal->expires_at->format('F d, Y') }}</p>
        </div>
        
        <div style="background: #e7f3ff; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <h3 style="margin-top: 0;">Vendor Information</h3>
            <p><strong>{{ $deal->vendor->first_name }} {{ $deal->vendor->last_name }}</strong></p>
            @if($deal->location_address)
            <p>{{ $deal->location_address }}</p>
            @endif
            <p>{{ $deal->location_city }}, FL {{ $deal->location_zip }}</p>
            @if($deal->vendor->phone)
            <p><strong>Phone:</strong> {{ $deal->vendor->phone }}</p>
            @endif
        </div>
        
        <div style="margin: 20px 0;">
            <h3>How to Redeem:</h3>
            <ol>
                <li>Visit the vendor at the location shown above</li>
                <li>Show your confirmation code: <strong>{{ $purchase->confirmation_code }}</strong></li>
                <li>Vendor will verify and redeem your deal</li>
            </ol>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('vouchers.show', $purchase->confirmation_code) }}" 
               style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View Your Voucher Online
            </a>
        </div>
        
        @if($deal->terms_conditions)
        <div style="background: #fff3cd; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <h4>Terms & Conditions</h4>
            <p>{!! nl2br(e($deal->terms_conditions)) !!}</p>
        </div>
        @endif
        
        <p style="margin-top: 30px; color: #666; font-size: 12px;">
            This voucher is valid until {{ $deal->expires_at->format('F d, Y') }}.
        </p>
        
        <p>Thank you for supporting local businesses!</p>
        
        <p>Best regards,<br>
        {{ getcong('site_name') }}</p>
    </div>
</body>
</html>


