<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analytics Report - {{ getcong('site_name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .metric-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .metric-value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ getcong('site_name') }}</h1>
        <h2>Analytics Report</h2>
        <p>Generated: {{ now()->format('F d, Y g:i A') }}</p>
    </div>
    
    <div>
        <h3>Vendor: {{ $user->first_name }} {{ $user->last_name }}</h3>
        <p>Email: {{ $user->email }}</p>
    </div>
    
    <div class="metrics">
        <div class="metric-card">
            <div class="metric-value">{{ number_format($totalViews) }}</div>
            <div>Total Views</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ number_format($totalClicks) }}</div>
            <div>Total Clicks</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ number_format($totalPurchases) }}</div>
            <div>Total Purchases</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">${{ number_format($totalRevenue, 2) }}</div>
            <div>Total Revenue</div>
        </div>
    </div>
    
    <h3>Top Performing Deals</h3>
    <table>
        <thead>
            <tr>
                <th>Deal Title</th>
                <th>Views</th>
                <th>Clicks</th>
                <th>Purchases</th>
                <th>Revenue</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topDeals as $deal)
            <tr>
                <td>{{ $deal->title }}</td>
                <td>{{ number_format($deal->view_count) }}</td>
                <td>{{ number_format($deal->click_count) }}</td>
                <td>{{ $deal->purchases_count }}</td>
                <td>${{ number_format($deal->revenue ?? 0, 2) }}</td>
                <td>{{ ucfirst($deal->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>This report was generated on {{ now()->format('F d, Y') }}.</p>
        <p>{{ getcong('site_name') }} - Analytics Dashboard</p>
    </div>
</body>
</html>


