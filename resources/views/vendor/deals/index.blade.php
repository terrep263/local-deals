@extends('app')

@section('head_title', 'My Deals | ' . getcong('site_name'))
@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', 'My Deals')
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => 'My Deals', 'url' => '']]))

@section("content")

@include('common.page-hero-header', ['title' => 'My Deals']) 

<!-- ================================
    Start Deals Dashboard Area
================================= -->
<section class="dashboard-area bg-gray section_item_padding">
    <div class="container">
        @if(Session::has('flash_message'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('flash_message') }}
            </div>
        @endif

        @if(Session::has('error_flash_message'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('error_flash_message') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $activeDeals }}</h3>
                        <p class="mb-0">
                            <strong>Active Deals</strong><br>
                            @if($simultaneousLimit == -1)
                                <small>(Unlimited)</small>
                            @else
                                <small>of {{ $simultaneousLimit }} limit</small>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ $pendingDeals }}</h3>
                        <p class="mb-0"><strong>Pending Approval</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">${{ number_format($totalRevenue, 2) }}</h3>
                        <p class="mb-0"><strong>Total Revenue</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">{{ $totalSold }}</h3>
                        <p class="mb-0"><strong>Total Sold</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>My Deals</h3>
                    <div>
                        @php
                            $canCreate = $simultaneousLimit == -1 || $activeDeals < $simultaneousLimit;
                        @endphp
                        @if($canCreate)
                            <a href="{{ route('vendor.deals.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create New Deal
                            </a>
                        @else
                            <button class="btn btn-primary" disabled title="You've reached your limit. Upgrade your plan to create more deals.">
                                <i class="fa fa-plus"></i> Create New Deal
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-12">
                <form method="GET" action="{{ route('vendor.deals.index') }}" class="form-inline">
                    <select name="status" class="form-control mr-2 mb-2">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending</option>
                        <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                        <option value="auto_paused" {{ request('status') == 'auto_paused' ? 'selected' : '' }}>Auto-Paused</option>
                        <option value="sold_out" {{ request('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <select name="sort" class="form-control mr-2 mb-2">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="best_selling" {{ request('sort') == 'best_selling' ? 'selected' : '' }}>Best Selling</option>
                        <option value="expiring_soon" {{ request('sort') == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                    </select>
                    <button type="submit" class="btn btn-secondary mb-2">Filter</button>
                    <a href="{{ route('vendor.deals.index') }}" class="btn btn-outline-secondary mb-2">Clear</a>
                </form>
            </div>
        </div>

        <!-- Deals Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Inventory</th>
                                <th>Expires</th>
                                @if($packageFeatures && $packageFeatures->ai_scoring_enabled)
                                <th>AI Score</th>
                                @endif
                                @if($packageFeatures && $packageFeatures->analytics_access)
                                <th>Stats</th>
                                @endif
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deals as $deal)
                                @php
                                    $statusBadge = [
                                        'active' => ['class' => 'badge-success', 'text' => 'Active'],
                                        'pending_approval' => ['class' => 'badge-warning', 'text' => 'Pending'],
                                        'paused' => ['class' => 'badge-info', 'text' => 'Paused'],
                                        'sold_out' => ['class' => 'badge-primary', 'text' => 'Sold Out'],
                                        'expired' => ['class' => 'badge-secondary', 'text' => 'Expired'],
                                        'rejected' => ['class' => 'badge-danger', 'text' => 'Rejected'],
                                    ];
                                    $status = $statusBadge[$deal->status] ?? ['class' => 'badge-secondary', 'text' => ucfirst($deal->status)];
                                    $inventoryPercent = $deal->inventory_total > 0 ? ($deal->inventory_sold / $deal->inventory_total) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>
                                        @if($deal->featured_image)
                                            <img src="{{ asset('storage/deals/thumbs/' . pathinfo($deal->featured_image, PATHINFO_FILENAME) . '-thumb.' . pathinfo($deal->featured_image, PATHINFO_EXTENSION)) }}" 
                                                 alt="{{ $deal->title }}" 
                                                 style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <div style="width: 60px; height: 40px; background: #ddd; border-radius: 4px;"></div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $deal->title }}</strong><br>
                                        <small class="text-muted">{{ $deal->category->category_name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                                        @if($deal->auto_paused)
                                            <div class="mt-1">
                                                <span class="badge badge-warning">Auto-Paused ({{ ucfirst(str_replace('_',' ', $deal->pause_reason)) }})</span>
                                                <p class="text-muted mb-0">This deal will resume automatically on the 1st of next month.</p>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="text-decoration: line-through; color: #999;">${{ number_format($deal->regular_price, 2) }}</span><br>
                                        <strong class="text-success">${{ number_format($deal->deal_price, 2) }}</strong><br>
                                        <small class="text-danger">{{ $deal->discount_percentage }}% off</small>
                                    </td>
                                    <td>
                                        {{ $deal->inventory_sold }} / {{ $deal->inventory_total }} sold<br>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $inventoryPercent }}%">
                                                {{ number_format($inventoryPercent, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($deal->expires_at)
                                            @if($deal->expires_at > now())
                                                <small>{{ $deal->expires_at->diffForHumans() }}</small><br>
                                                <small class="text-muted">{{ $deal->expires_at->format('M d, Y') }}</small>
                                            @else
                                                <small class="text-danger">Expired</small>
                                            @endif
                                        @else
                                            <small class="text-muted">No expiration</small>
                                        @endif
                                    </td>
                                    @if($packageFeatures && $packageFeatures->ai_scoring_enabled)
                                    <td>
                                        @if($deal->ai_quality_score)
                                            @php
                                                $scoreColor = $deal->ai_quality_score >= 90 ? 'success' : ($deal->ai_quality_score >= 75 ? 'info' : ($deal->ai_quality_score >= 60 ? 'warning' : 'danger'));
                                            @endphp
                                            <span class="badge badge-{{ $scoreColor }}">{{ $deal->ai_quality_score }}/100</span><br>
                                            <a href="{{ route('vendor.deals.ai-insights', $deal) }}" class="btn btn-sm btn-outline-primary mt-1">
                                                <i class="fa fa-chart-line"></i> Insights
                                            </a>
                                        @else
                                            <span class="text-muted">Not scored</span>
                                        @endif
                                    </td>
                                    @endif
                                    @if($packageFeatures && $packageFeatures->analytics_access)
                                    <td>
                                        <small>Views: {{ $deal->view_count }}</small><br>
                                        <small>Clicks: {{ $deal->click_count }}</small><br>
                                        @if($deal->click_count > 0)
                                            <small>Conv: {{ number_format(($deal->inventory_sold / $deal->click_count) * 100, 1) }}%</small>
                                        @endif
                                    </td>
                                    @endif
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('deals.show', $deal->slug) }}" class="btn btn-info" target="_blank" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('vendor.deals.edit', $deal) }}" class="btn btn-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if($deal->status === 'active')
                                                <form action="{{ route('vendor.deals.pause', $deal) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning" title="Pause">
                                                        <i class="fa fa-pause"></i>
                                                    </button>
                                                </form>
                                            @elseif($deal->status === 'paused')
                                                <form action="{{ route('vendor.deals.resume', $deal) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" title="Resume">
                                                        <i class="fa fa-play"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if(in_array($deal->status, ['draft']) || $deal->inventory_sold == 0)
                                                <form action="{{ route('vendor.deals.destroy', $deal) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this deal?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $packageFeatures && $packageFeatures->analytics_access ? '8' : '7' }}" class="text-center">
                                        <p class="text-muted">No deals found. <a href="{{ route('vendor.deals.create') }}">Create your first deal!</a></p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $deals->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================================
    End Deals Dashboard Area
================================= -->

@endsection

