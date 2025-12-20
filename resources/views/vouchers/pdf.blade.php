<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Voucher - {{ $purchase->confirmation_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .confirmation-code {
            font-size: 48px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border: 2px dashed #007bff;
        }
        .details {
            margin: 20px 0;
        }
        .details p {
            margin: 5px 0;
        }
        .vendor-info {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lake County Local Deals</h1>
        <h2>Deal Voucher</h2>
    </div>
    
    <div class="details">
        <h3>{{ $purchase->deal->title }}</h3>
        <p><strong>Vendor:</strong> {{ $purchase->deal->vendor->first_name }} {{ $purchase->deal->vendor->last_name }}</p>
    </div>
    
    <div class="confirmation-code">
        {{ $purchase->confirmation_code }}
    </div>
    
    <div class="details">
        <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date->format('F d, Y') }}</p>
        <p><strong>Purchase Amount:</strong> ${{ number_format($purchase->purchase_amount, 2) }}</p>
        <p><strong>Expires:</strong> {{ $purchase->deal->expires_at->format('F d, Y') }}</p>
        <p><strong>Email:</strong> {{ $purchase->consumer_email }}</p>
    </div>
    
    <div class="vendor-info">
        <h4>Vendor Information</h4>
        <p><strong>{{ $purchase->deal->vendor->first_name }} {{ $purchase->deal->vendor->last_name }}</strong></p>
        @if($purchase->deal->location_address)
        <p>{{ $purchase->deal->location_address }}</p>
        @endif
        <p>{{ $purchase->deal->location_city }}, FL {{ $purchase->deal->location_zip }}</p>
        @if($purchase->deal->vendor->phone)
        <p><strong>Phone:</strong> {{ $purchase->deal->vendor->phone }}</p>
        @endif
    </div>
    
    @if($purchase->deal->terms_conditions)
    <div class="details">
        <h4>Terms & Conditions</h4>
        <p>{!! nl2br(e($purchase->deal->terms_conditions)) !!}</p>
    </div>
    @endif
    
    <div class="footer">
        <p>This voucher is valid until {{ $purchase->deal->expires_at->format('F d, Y') }}.</p>
        <p>Please present this voucher to the vendor when redeeming your deal.</p>
        <p>Confirmation Code: <strong>{{ $purchase->confirmation_code }}</strong></p>
    </div>
</body>
</html>


