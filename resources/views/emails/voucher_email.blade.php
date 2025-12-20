<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Deal Voucher</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #28a745;">Your Deal Voucher</h1>
        
        <p>Hello,</p>
        
        <p>As requested, here is your deal voucher:</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-left: 4px solid #007bff; margin: 20px 0;">
            <h2 style="margin-top: 0;">{{ $deal->title }}</h2>
            <p><strong>Confirmation Code:</strong></p>
            <h1 style="color: #007bff; font-size: 36px; margin: 10px 0;">{{ $purchase->confirmation_code }}</h1>
            <p><strong>Purchase Amount:</strong> ${{ number_format($purchase->purchase_amount, 2) }}</p>
            <p><strong>Expires:</strong> {{ $deal->expires_at->format('F d, Y') }}</p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('vouchers.show', $purchase->confirmation_code) }}" 
               style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View Full Voucher
            </a>
        </div>
        
        <p>Thank you for supporting local businesses!</p>
        
        <p>Best regards,<br>
        {{ getcong('site_name') }}</p>
    </div>
</body>
</html>


