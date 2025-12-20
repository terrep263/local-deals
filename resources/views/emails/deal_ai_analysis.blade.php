<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AI Analysis Complete</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #007bff;">AI Analysis Complete!</h1>
        
        <p>Hello {{ $deal->vendor->first_name }},</p>
        
        <p>Your deal "<strong>{{ $deal->title }}</strong>" has been analyzed by our AI system.</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-left: 4px solid #007bff; margin: 20px 0;">
            <h2 style="margin-top: 0; text-align: center;">
                Quality Score: <span style="color: #007bff; font-size: 48px;">{{ $result['score'] }}</span>/100
            </h2>
        </div>
        
        @if(count($result['strengths']) > 0)
        <div style="background: #d4edda; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <h3 style="margin-top: 0; color: #155724;">Strengths</h3>
            <ul>
                @foreach($result['strengths'] as $strength)
                <li>{{ $strength }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if(count($result['weaknesses']) > 0)
        <div style="background: #f8d7da; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <h3 style="margin-top: 0; color: #721c24;">Weaknesses</h3>
            <ul>
                @foreach($result['weaknesses'] as $weakness)
                <li>{{ $weakness }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if(count($result['suggestions']) > 0)
        <div style="background: #d1ecf1; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <h3 style="margin-top: 0; color: #0c5460;">Optimization Suggestions</h3>
            <ol>
                @foreach($result['suggestions'] as $suggestion)
                <li>{{ $suggestion }}</li>
                @endforeach
            </ol>
        </div>
        @endif
        
        @if($result['competitive_analysis'])
        <div style="background: #fff3cd; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <h3 style="margin-top: 0; color: #856404;">Competitive Analysis</h3>
            <p>{{ $result['competitive_analysis'] }}</p>
        </div>
        @endif
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('vendor.deals.ai-insights', $deal) }}" 
               style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View Full AI Insights
            </a>
        </div>
        
        <p>Thank you for using {{ getcong('site_name') }}!</p>
    </div>
</body>
</html>


