@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Platform Analytics</h1>
        <h2 class="h5 text-white-op animated zoomIn">Comprehensive platform metrics and insights</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    <!-- Platform Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ number_format($totalActiveDeals) }}</h3>
                    <p class="text-muted mb-0">Active Deals</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ number_format($totalDealsThisMonth) }}</h3>
                    <p class="text-muted mb-0">Deals This Month</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">${{ number_format($totalGMV, 2) }}</h3>
                    <p class="text-muted mb-0">Total GMV</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">{{ number_format($totalVendors) }}</h3>
                    <p class="text-muted mb-0">Total Vendors</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ number_format($activeSubscriptions) }}</h3>
                    <p class="text-muted mb-0">Active Subscriptions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">${{ number_format($mrr, 2) }}</h3>
                    <p class="text-muted mb-0">Monthly Recurring Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">${{ number_format($revenueThisMonth, 2) }}</h3>
                    <p class="text-muted mb-0">Revenue This Month</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">${{ number_format($avgDealPrice, 2) }}</h3>
                    <p class="text-muted mb-0">Avg Deal Price</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Growth Charts -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">New Vendors per Month</h5>
                </div>
                <div class="card-body">
                    <canvas id="vendorsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Deals Created per Month</h5>
                </div>
                <div class="card-body">
                    <canvas id="dealsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">GMV per Month</h5>
                </div>
                <div class="card-body">
                    <canvas id="gmvChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Subscription Revenue per Month</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Category Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Active Deals</th>
                                    <th>Total Sales</th>
                                    <th>Avg Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryPerformance as $category)
                                <tr>
                                    <td>{{ $category['name'] }}</td>
                                    <td>{{ $category['active_deals'] }}</td>
                                    <td>{{ $category['total_sales'] }}</td>
                                    <td>{{ $category['avg_discount'] }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Leaderboards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Vendors by Revenue</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>Revenue</th>
                                    <th>Active Deals</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topVendorsByRevenue as $vendor)
                                <tr>
                                    <td>{{ $vendor['user']->first_name }} {{ $vendor['user']->last_name }}</td>
                                    <td>${{ number_format($vendor['revenue'], 2) }}</td>
                                    <td>{{ $vendor['active_deals'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Vendors by Sales</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>Sales</th>
                                    <th>Active Deals</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topVendorsBySales as $vendor)
                                <tr>
                                    <td>{{ $vendor['user']->first_name }} {{ $vendor['user']->last_name }}</td>
                                    <td>{{ $vendor['sales'] }}</td>
                                    <td>{{ $vendor['active_deals'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Geographic Insights -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Deals by City</h5>
                </div>
                <div class="card-body">
                    <canvas id="cityChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top ZIP Codes by Sales</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ZIP Code</th>
                                    <th>Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesByZip as $zip)
                                <tr>
                                    <td>{{ $zip->location_zip }}</td>
                                    <td>{{ $zip->count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Metrics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Subscription Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tier</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptionBreakdown as $breakdown)
                                <tr>
                                    <td>{{ ucfirst($breakdown->package_tier) }}</td>
                                    <td>{{ $breakdown->count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <a href="{{ route('admin.analytics.export.csv') }}" class="btn btn-primary">
                        <i class="fa fa-download"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// New Vendors Chart
const vendorsCtx = document.getElementById('vendorsChart');
new Chart(vendorsCtx, {
    type: 'bar',
    data: {
        labels: @json($monthLabels),
        datasets: [{
            label: 'New Vendors',
            data: @json($newVendorsPerMonth->toArray()),
            backgroundColor: 'rgba(54, 162, 235, 0.5)'
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

// Deals Created Chart
const dealsCtx = document.getElementById('dealsChart');
new Chart(dealsCtx, {
    type: 'bar',
    data: {
        labels: @json($monthLabels),
        datasets: [{
            label: 'Deals Created',
            data: @json($dealsCreatedPerMonth->toArray()),
            backgroundColor: 'rgba(75, 192, 192, 0.5)'
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

// GMV Chart
const gmvCtx = document.getElementById('gmvChart');
new Chart(gmvCtx, {
    type: 'line',
    data: {
        labels: @json($monthLabels),
        datasets: [{
            label: 'GMV',
            data: @json($gmvPerMonth->toArray()),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
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

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($monthLabels),
        datasets: [{
            label: 'Subscription Revenue',
            data: @json($subscriptionRevenuePerMonth->toArray()),
            borderColor: 'rgb(153, 102, 255)',
            backgroundColor: 'rgba(153, 102, 255, 0.1)',
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

// City Chart
const cityCtx = document.getElementById('cityChart');
new Chart(cityCtx, {
    type: 'bar',
    data: {
        labels: @json($dealsByCity->pluck('location_city')->toArray()),
        datasets: [{
            label: 'Deals',
            data: @json($dealsByCity->pluck('count')->toArray()),
            backgroundColor: 'rgba(255, 159, 64, 0.5)'
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

@endsection


