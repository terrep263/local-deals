<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Voucher - {{ $voucher->voucher_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            background: white;
        }
        
        .voucher {
            width: 8.5in;
            height: 11in;
            padding: 0.75in;
            position: relative;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 36pt;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14pt;
            color: #666;
        }
        
        .deal-info {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .deal-title {
            font-size: 24pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        
        .vendor-name {
            font-size: 18pt;
            color: #007bff;
            margin-bottom: 20px;
        }
        
        .price-info {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        
        .price-row {
            display: table-row;
        }
        
        .price-label {
            display: table-cell;
            font-size: 14pt;
            padding: 5px 0;
        }
        
        .price-value {
            display: table-cell;
            font-size: 14pt;
            text-align: right;
            font-weight: bold;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #999;
        }
        
        .sale-price {
            color: #28a745;
            font-size: 24pt;
        }
        
        .savings {
            color: #dc3545;
        }
        
        .qr-section {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: white;
            border: 2px dashed #007bff;
            border-radius: 10px;
        }
        
        .qr-code {
            margin: 20px 0;
        }
        
        .qr-code img {
            width: 250px;
            height: 250px;
        }
        
        .voucher-code {
            font-size: 24pt;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 2px;
            margin-top: 15px;
            font-family: 'Courier New', monospace;
        }
        
        .instructions {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .instructions h3 {
            color: #856404;
            font-size: 14pt;
            margin-bottom: 10px;
        }
        
        .instructions ol {
            margin-left: 20px;
            color: #856404;
        }
        
        .instructions li {
            margin: 8px 0;
            font-size: 11pt;
        }
        
        .fine-print {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 9pt;
            color: #666;
            line-height: 1.4;
        }
        
        .expiration {
            background: #dc3545;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-top: 20px;
        }
        
        .footer {
            position: absolute;
            bottom: 0.75in;
            left: 0.75in;
            right: 0.75in;
            text-align: center;
            font-size: 9pt;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="voucher">
        <div class="header">
            <h1>DEAL VOUCHER</h1>
            <p>Lake County Local Deals</p>
        </div>
        
        <div class="deal-info">
            <div class="deal-title">{{ $deal->title }}</div>
            <div class="vendor-name">{{ $vendor->business_name }}</div>
            
            <div class="price-info">
                <div class="price-row">
                    <div class="price-label">Original Price:</div>
                    <div class="price-value original-price">${{ number_format($deal->original_price, 2) }}</div>
                </div>
                <div class="price-row">
                    <div class="price-label">Your Price:</div>
                    <div class="price-value sale-price">${{ number_format($deal->sale_price, 2) }}</div>
                </div>
                <div class="price-row">
                    <div class="price-label">You Saved:</div>
                    <div class="price-value savings">${{ number_format($deal->original_price - $deal->sale_price, 2) }}</div>
                </div>
            </div>
        </div>
        
        <div class="qr-section">
            <h2 style="color: #007bff; font-size: 18pt; margin-bottom: 10px;">Scan to Redeem</h2>
            <div class="qr-code">
                <img src="{{ $qr_code_url }}" alt="QR Code">
            </div>
            <div class="voucher-code">{{ $formatted_code }}</div>
            <p style="margin-top: 10px; color: #666; font-size: 10pt;">Show this QR code or code to the merchant</p>
        </div>
        
        <div class="instructions">
            <h3>How to Redeem:</h3>
            <ol>
                <li>Visit {{ $vendor->business_name }} during business hours</li>
                <li>Show this voucher (QR code or printed page) to staff</li>
                <li>Staff will scan or enter the voucher code</li>
                <li>Enjoy your deal!</li>
            </ol>
        </div>
        
        <div class="expiration">
            EXPIRES: {{ $voucher->expiration_date->format('F j, Y') }}
        </div>
        
        @if($deal->fine_print)
        <div class="fine-print">
            <strong>Terms & Conditions:</strong><br>
            {{ $deal->fine_print }}
        </div>
        @endif
        
        <div class="fine-print" style="margin-top: 15px;">
            <strong>Voucher Details:</strong><br>
            Purchased: {{ $voucher->purchase_date->format('F j, Y g:i A') }}<br>
            Customer: {{ $customer->name }} ({{ $customer->email }})<br>
            Voucher ID: {{ $voucher->voucher_code }}
        </div>
        
        <div class="footer">
            Lake County Local Deals | Questions? Contact support@lakecountydeals.com<br>
            This voucher is non-transferable and cannot be redeemed for cash.
        </div>
    </div>
</body>
</html>
