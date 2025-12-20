@extends('app')

@section('head_title', 'Advanced Analytics - ' . getcong('site_name'))
@section('content')

<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-3">Advanced Analytics Dashboard</h2>
                
                <!-- Time Range Selector -->
                <form method="GET" action="{{ route('analytics.index') }}" class="form-inline mb-3">
                    <select name="time_range" class="form-control mr-2" onchange="this.form.submit()">
                        <option value="7" {{ $timeRange == '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30" {{ $timeRange == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ $timeRange == '90' ? 'selected' : '' }}>Last 90 days</option>
                        <option value="custom" {{ $timeRange == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                    @if($timeRange == 'custom')
                    <input type="date" name="date_from" class="form-control mr-2" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="date" name="date_to" class="form-control mr-2" value="{{ $endDate->format('Y-m-d') }}">
                    @endif
                    <button type="submit" class="btn btn-primary">Apply</button>
                </form>
            </div>
        </div>

        <!-- Enhanced Metrics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">${{ number_format($totalRevenue, 2) }}</h3>
                        <p class="text-muted mb-0">Total Revenue</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">${{ number_format($avgDealValue, 2) }}</h3>
                        <p class="text-muted mb-0">Avg Deal Value</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">{{ number_format($avgDiscount, 1) }}%</h3>
                        <p class="text-muted mb-0">Avg Discount</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ number_format($inventoryUtilization, 1) }}%</h3>
                        <p class="text-muted mb-0">Inventory Utilization</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">${{ number_format($revenuePerDeal, 2) }}</h3>
                        <p class="text-muted mb-0">Revenue per Deal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ number_format($totalViews) }}</h3>
                        <p class="text-muted mb-0">Views</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">{{ number_format($totalClicks) }}</h3>
                        <p class="text-muted mb-0">Clicks</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-danger">{{ number_format($totalPurchases) }}</h3>
                        <p class="text-muted mb-0">Conversions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daily Metrics</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="dailyMetricsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Deals by Status</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Revenue Over Time</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Funnel Analysis -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Conversion Funnel</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="funnel-visual">
                            <div class="funnel-step mb-3">
                                <h4>{{ number_format($funnelViews) }}</h4>
                                <p class="mb-0">Views</p>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-arrow-down fa-2x text-primary"></i>
                                <p class="mb-0"><strong>CTR: {{ number_format($funnelCTR, 2) }}%</strong></p>
                            </div>
                            <div class="funnel-step mb-3">
                                <h4>{{ number_format($funnelClicks) }}</h4>
                                <p class="mb-0">Clicks</p>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-arrow-down fa-2x text-success"></i>
                                <p class="mb-0"><strong>Conversion: {{ number_format($funnelConversion, 2) }}%</strong></p>
                            </div>
                            <div class="funnel-step">
                                <h4 class="text-success">{{ number_format($funnelPurchases) }}</h4>
                                <p class="mb-0">Purchases</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deal Comparison -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Deal Comparison Tool</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Select deals to compare performance side-by-side</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Deal Title</th>
                                        <th>Views</th>
                                        <th>Clicks</th>
                                        <th>Purchases</th>
                                        <th>Revenue</th>
                                        <th>CTR</th>
                                        <th>Conversion Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allDeals->take(10) as $deal)
                                    <tr>
                                        <td>{{ Str::limit($deal->title, 40) }}</td>
                                        <td>{{ number_format($deal->view_count) }}</td>
                                        <td>{{ number_format($deal->click_count) }}</td>
                                        <td>{{ $deal->purchases_count }}</td>
                                        <td>${{ number_format($deal->revenue, 2) }}</td>
                                        <td>{{ number_format($deal->ctr, 2) }}%</td>
                                        <td>{{ number_format($deal->conversion_rate, 2) }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Insights -->
        @if(count($insights) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-info text-white">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-lightbulb"></i> AI-Powered Insights</h5>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            @foreach($insights as $insight)
                            <li>{{ $insight }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Export Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="mb-3">Export Analytics</h5>
                        <a href="{{ route('analytics.export.csv') }}" class="btn btn-primary mr-2">
                            <i class="fa fa-download"></i> Export CSV
                        </a>
                        <a href="{{ route('analytics.export.pdf') }}" class="btn btn-danger">
                            <i class="fa fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Daily Metrics Chart
const dailyCtx = document.getElementById('dailyMetricsChart');
new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: @json($dates),
        datasets: [
            {
                label: 'Views',
                data: @json($views),
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                fill: false
            },
            {
                label: 'Clicks',
                data: @json($clicks),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                fill: false
            },
            {
                label: 'Purchases',
                data: @json($purchases),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Status Pie Chart
const statusCtx = document.getElementById('statusChart');
new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: @json(array_keys($dealsByStatus->toArray())),
        datasets: [{
            data: @json(array_values($dealsByStatus->toArray())),
            backgroundColor: [
                'rgb(54, 162, 235)',
                'rgb(255, 99, 132)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)'
            ]
        }]
    },
    options: {
        responsive: true
    }
});

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($dates),
        datasets: [{
            label: 'Revenue',
            data: @json($revenue),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<style>
.funnel-step {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 0 auto;
    max-width: 300px;
}
</style>

@endsection


