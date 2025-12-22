<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #007bff;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .deal-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border: 2px solid #007bff;
        }
        .voucher-code {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            border-radius: 5px;
            margin: 20px 0;
            font-family: monospace;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .instructions {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Your Voucher is Ready!</h1>
            <p>Thank you for your purchase</p>
        </div>
        
        <div class="content">
            <h2>Deal Details</h2>
            <div class="deal-box">
                <h3>{{ $deal->title }}</h3>
                <p><strong>Vendor:</strong> {{ $vendor->business_name }}</p>
                <p><strong>You Paid:</strong> ${{ number_format($voucher->purchase->purchase_amount, 2) }}</p>
                <p><strong>Original Price:</strong> <s>${{ number_format($deal->original_price, 2) }}</s></p>
                <p><strong>You Saved:</strong> ${{ number_format($deal->original_price - $voucher->purchase->purchase_amount, 2) }}</p>
            </div>
            
            <h2>Your Voucher Code</h2>
            <div class="voucher-code">
                {{ $voucher->getFormattedCode() }}
            </div>
            
            <div class="qr-code">
                <img src="{{ $voucher->getQrCodeUrl() }}" alt="QR Code" width="200" height="200">
                <p><em>Show this QR code to the merchant</em></p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('customer.vouchers.show', $voucher) }}" class="button">
                    View Voucher Online
                </a>
                <a href="{{ $voucher->getPdfUrl() }}" class="button" style="background: #007bff;">
                    Download PDF
                </a>
            </div>
            
            <div class="instructions">
                <h3>How to Redeem:</h3>
                <ol>
                    <li>Visit <strong>{{ $vendor->business_name }}</strong></li>
                    <li>Show your QR code or voucher code to staff</li>
                    <li>Staff will scan/enter the code to validate</li>
                    <li>Enjoy your deal!</li>
                </ol>
                <p><strong>⚠️ Expires:</strong> {{ $voucher->expiration_date->format('F j, Y') }}</p>
            </div>
            
            @if($deal->fine_print)
            <div style="margin: 20px 0; padding: 15px; background: white; border-radius: 5px;">
                <h4>Terms & Conditions:</h4>
                <p style="font-size: 13px;">{{ $deal->fine_print }}</p>
            </div>
            @endif
        </div>
        
        <div class="footer">
            <p>Questions? Contact us at support@lakecountydeals.com</p>
            <p>Lake County Local Deals - Discover Amazing Deals in Your Area</p>
        </div>
    </div>
</body>
</html>
