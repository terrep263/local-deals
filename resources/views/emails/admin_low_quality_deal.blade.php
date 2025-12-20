<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Low Quality Deal Flagged</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #dc3545;">Low Quality Deal Flagged</h1>
        
        <p>Hello Admin,</p>
        
        <p>A deal has been flagged for review due to a low AI quality score.</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-left: 4px solid #dc3545; margin: 20px 0;">
            <h2 style="margin-top: 0;">{{ $deal->title }}</h2>
            <p><strong>Vendor:</strong> {{ $deal->vendor->first_name }} {{ $deal->vendor->last_name }}</p>
            <p><strong>AI Score:</strong> {{ $deal->ai_quality_score ?? 'N/A' }}/100</p>
            <p><strong>Status:</strong> {{ $deal->status }}</p>
            <p><strong>Review Reason:</strong> {{ $deal->admin_review_reason }}</p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.deals.show', $deal) }}" 
               style="background: #dc3545; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Review Deal
            </a>
        </div>
        
        <p>Please review this deal and take appropriate action.</p>
    </div>
</body>
</html>


