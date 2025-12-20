@extends('app')

@section('head_title', 'Voucher Details | ' . getcong('site_name'))
@section('head_url', Request::url())

@section("content")

<!-- Breadcrumb -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">Voucher Details</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('vendor.vouchers.index') }}">Vouchers</a></li>
                <li>{{ $purchase->confirmation_code }}</li>
            </ul>
        </div>
    </div>    
</section>

<!-- Main Content -->
<section class="dashboard-area bg-gray section_item_padding">
    <div class="container">
        @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <!-- Voucher Details Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fa fa-ticket-alt"></i> Voucher 
                            <strong style="font-family: monospace;">{{ $purchase->confirmation_code }}</strong>
                        </h5>
                        @if($purchase->isRedeemed())
                            <span class="badge badge-success badge-lg" style="font-size: 1em; padding: 8px 16px;">
                                ✓ REDEEMED
                            </span>
                        @else
                            <span class="badge badge-warning badge-lg" style="font-size: 1em; padding: 8px 16px;">
                                PENDING
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">DEAL INFORMATION</h6>
                                <hr>
                                <p><strong>Deal:</strong> {{ $purchase->deal->title ?? 'N/A' }}</p>
                                <p><strong>Original Price:</strong> <span style="text-decoration: line-through;">${{ number_format($purchase->deal->regular_price ?? 0, 2) }}</span></p>
                                <p><strong>Deal Price:</strong> <span class="text-success">${{ number_format($purchase->purchase_amount, 2) }}</span></p>
                                <p><strong>Discount:</strong> <span class="text-danger">{{ $purchase->deal->discount_percentage ?? 0 }}% OFF</span></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">CUSTOMER INFORMATION</h6>
                                <hr>
                                <p><strong>Name:</strong> {{ $purchase->consumer_name ?? 'Not provided' }}</p>
                                <p><strong>Email:</strong> {{ $purchase->consumer_email }}</p>
                                <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date ? $purchase->purchase_date->format('M d, Y \a\t g:i A') : 'N/A' }}</p>
                                @if($purchase->isRedeemed())
                                    <p><strong>Redeemed:</strong> {{ $purchase->redeemed_at->format('M d, Y \a\t g:i A') }}</p>
                                @endif
                            </div>
                        </div>

                        @if($purchase->notes)
                            <hr>
                            <h6 class="text-muted">NOTES</h6>
                            <p>{{ $purchase->notes }}</p>
                        @endif
                    </div>
                </div>

                <!-- Deal Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-info-circle"></i> Deal Details</h5>
                    </div>
                    <div class="card-body">
                        @if($purchase->deal)
                            <div class="row">
                                <div class="col-md-4">
                                    @if($purchase->deal->featured_image)
                                        <img src="{{ asset('storage/deals/' . $purchase->deal->featured_image) }}" 
                                             alt="{{ $purchase->deal->title }}" 
                                             class="img-fluid rounded">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                            <i class="fa fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h5>{{ $purchase->deal->title }}</h5>
                                    <p class="text-muted">{{ Str::limit($purchase->deal->short_description ?? $purchase->deal->description, 150) }}</p>
                                    
                                    @if($purchase->deal->fine_print)
                                        <h6 class="text-muted mt-3">Fine Print</h6>
                                        <small>{{ $purchase->deal->fine_print }}</small>
                                    @endif
                                    
                                    @if($purchase->deal->redemption_instructions)
                                        <h6 class="text-muted mt-3">Redemption Instructions</h6>
                                        <small>{{ $purchase->deal->redemption_instructions }}</small>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Deal information not available.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Action Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-bolt"></i> Actions</h5>
                    </div>
                    <div class="card-body">
                        @if($purchase->isRedeemed())
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> <strong>Already Redeemed</strong><br>
                                <small>This voucher was redeemed on {{ $purchase->redeemed_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> <strong>Ready to Redeem</strong><br>
                                <small>Click the button below after providing the service to the customer.</small>
                            </div>
                            
                            <form action="{{ route('vendor.vouchers.redeem', $purchase->confirmation_code) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="notes">Notes (optional)</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="2" 
                                              placeholder="Add any notes about this redemption..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg btn-block" 
                                        onclick="return confirm('Are you sure you want to mark this voucher as redeemed? This action cannot be undone.');">
                                    <i class="fa fa-check"></i> Mark as Redeemed
                                </button>
                            </form>
                        @endif
                        
                        <hr>
                        
                        <a href="{{ route('vendor.vouchers.index') }}" class="btn btn-outline-secondary btn-block">
                            <i class="fa fa-arrow-left"></i> Back to Vouchers
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-chart-bar"></i> Deal Stats</h5>
                    </div>
                    <div class="card-body">
                        @if($purchase->deal)
                            <p><strong>Total Sold:</strong> {{ $purchase->deal->inventory_sold }} / {{ $purchase->deal->inventory_total }}</p>
                            <div class="progress mb-3">
                                @php
                                    $pct = $purchase->deal->inventory_total > 0 
                                        ? ($purchase->deal->inventory_sold / $purchase->deal->inventory_total) * 100 
                                        : 0;
                                @endphp
                                <div class="progress-bar" style="width: {{ $pct }}%">{{ number_format($pct) }}%</div>
                            </div>
                            <p><strong>Status:</strong> 
                                @php
                                    $statusColors = [
                                        'active' => 'success',
                                        'paused' => 'warning',
                                        'sold_out' => 'info',
                                        'expired' => 'secondary',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$purchase->deal->status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $purchase->deal->status)) }}
                                </span>
                            </p>
                            @if($purchase->deal->expires_at)
                                <p><strong>Expires:</strong> {{ $purchase->deal->expires_at->format('M d, Y') }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
