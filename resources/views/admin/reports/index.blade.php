@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Reports & Analytics</h1>
        <h2 class="h5 text-white-op animated zoomIn">Platform-wide reports and insights</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    <div class="row">
        <!-- Vendor Growth Report -->
        <div class="col-md-6 mb-4">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Vendor Growth</h3>
                </div>
                <div class="block-content">
                    <canvas id="vendorGrowthChart"></canvas>
                    <p class="text-center mt-3">
                        <a href="{{ route('admin.reports.vendor-growth') }}" class="btn btn-sm btn-primary" target="_blank">View Data</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Revenue Report -->
        <div class="col-md-6 mb-4">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Revenue Trends</h3>
                </div>
                <div class="block-content">
                    <canvas id="revenueChart"></canvas>
                    <p class="text-center mt-3">
                        <a href="{{ route('admin.reports.revenue') }}" class="btn btn-sm btn-primary" target="_blank">View Data</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Deal Performance -->
        <div class="col-md-6 mb-4">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Deal Performance by Category</h3>
                </div>
                <div class="block-content">
                    <div id="dealPerformanceChart"></div>
                    <p class="text-center mt-3">
                        <a href="{{ route('admin.reports.deal-performance') }}" class="btn btn-sm btn-primary" target="_blank">View Data</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="col-md-6 mb-4">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Top Performers</h3>
                </div>
                <div class="block-content">
                    <div id="topPerformers"></div>
                    <p class="text-center mt-3">
                        <a href="{{ route('admin.reports.top-performers') }}" class="btn btn-sm btn-primary" target="_blank">View Full List</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Export Reports</h3>
        </div>
        <div class="block-content text-center">
            <a href="{{ route('admin.reports.export', ['type' => 'csv', 'report' => 'vendor-growth']) }}" class="btn btn-primary mr-2">
                <i class="fa fa-download"></i> Export Vendor Growth (CSV)
            </a>
            <a href="{{ route('admin.reports.export', ['type' => 'csv', 'report' => 'revenue']) }}" class="btn btn-primary mr-2">
                <i class="fa fa-download"></i> Export Revenue (CSV)
            </a>
            <a href="{{ route('admin.reports.export', ['type' => 'csv', 'report' => 'deal-performance']) }}" class="btn btn-primary">
                <i class="fa fa-download"></i> Export Deal Performance (CSV)
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Load reports data via AJAX and render charts
fetch('{{ route("admin.reports.vendor-growth") }}')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('vendorGrowthChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.period),
                datasets: [{
                    label: 'New Vendors',
                    data: data.map(d => d.new_vendors),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });

fetch('{{ route("admin.reports.revenue") }}')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('revenueChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.period),
                datasets: [
                    {
                        label: 'GMV',
                        data: data.map(d => d.gmv),
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        fill: true
                    },
                    {
                        label: 'MRR',
                        data: data.map(d => d.mrr),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>

@endsection


