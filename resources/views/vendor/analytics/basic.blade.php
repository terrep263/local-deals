@extends('app')

@section('head_title', 'Analytics Dashboard - ' . getcong('site_name'))
@section('content')

<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-3">Analytics Dashboard</h2>
                <p class="text-muted">Basic analytics for your deals</p>
            </div>
        </div>

        <!-- Metrics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ number_format($totalViews) }}</h3>
                        <p class="text-muted mb-0">Total Views</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">{{ number_format($totalClicks) }}</h3>
                        <p class="text-muted mb-0">Total Clicks</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">{{ number_format($totalPurchases) }}</h3>
                        <p class="text-muted mb-0">Total Purchases</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ number_format($ctr, 2) }}%</h3>
                        <p class="text-muted mb-0">Click-Through Rate</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-danger">{{ number_format($conversionRate, 2) }}%</h3>
                        <p class="text-muted mb-0">Purchase Conversion Rate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Deals -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Top Performing Deals</h5>
                    </div>
                    <div class="card-body">
                        @if($topDeals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Deal Title</th>
                                        <th>Views</th>
                                        <th>Clicks</th>
                                        <th>Purchases</th>
                                        <th>Revenue</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topDeals as $deal)
                                    <tr>
                                        <td>{{ Str::limit($deal->title, 50) }}</td>
                                        <td>{{ number_format($deal->view_count) }}</td>
                                        <td>{{ number_format($deal->click_count) }}</td>
                                        <td>{{ $deal->purchases_count }}</td>
                                        <td>${{ number_format($deal->revenue, 2) }}</td>
                                        <td>
                                            <a href="{{ route('deals.show', $deal->slug) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center">No deals yet. Create your first deal to see analytics!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        @if($recentPurchases->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Deal Title</th>
                                        <th>Consumer Email</th>
                                        <th>Purchase Date</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPurchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->deal->title ?? 'N/A' }}</td>
                                        <td>{{ $purchase->consumer_email }}</td>
                                        <td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
                                        <td>${{ number_format($purchase->purchase_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center">No sales yet. Share your deals to get started!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


