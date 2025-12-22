@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-credit-card"></i> Billing & Subscription</h2>
            <p class="text-muted">Manage your subscription plan and view usage</p>
        </div>
    </div>
    
    @if(session('upgrade') === 'success')
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i>
            <strong>Upgrade Successful!</strong> Your plan has been updated.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    
    <!-- Current Plan -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-star"></i> Current Plan
                    </h5>
                </div>
                <div class="card-body">
                    <h3>{{ $currentPlan['name'] }}</h3>
                    <h1 class="text-primary">
                        ${{ number_format($vendor->monthly_price, 0) }}
                        <small class="text-muted">/month</small>
                    </h1>
                    
                    @if($vendor->isFounder())
                        <div class="alert alert-warning mt-3">
                            <strong><i class="fas fa-star"></i> Founder #{{ $vendor->founder_number }}</strong><br>
                            You're one of the first 25 businesses! Free forever as long as you stay active.
                            <br><br>
                            <small class="text-muted">
                                <strong>Founder Rule:</strong> If no vouchers are redeemed for 2 consecutive months, 
                                founder status will be lost and a paid plan will be required.
                            </small>
                        </div>
                    @endif
                    
                    @if($vendor->hasFounderUpgrade())
                        <div class="alert alert-info mt-3">
                            <strong><i class="fas fa-lock"></i> Locked-In Pricing</strong><br>
                            Your founder upgrade rate of $35/month is locked in for life!
                        </div>
                    @endif
                    
                    <hr>
                    
                    <h6>Plan Features:</h6>
                    <ul class="list-unstyled">
                        <li>
                            <i class="fas fa-check text-success"></i>
                            <strong>Active Deals:</strong>
                            @if($vendor->hasUnlimitedDeals())
                                Unlimited
                            @else
                                {{ $currentPlan['features']['active_deals'] }}
                            @endif
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            <strong>Vouchers/Month:</strong>
                            @if($vendor->hasUnlimitedVouchers())
                                Unlimited
                            @else
                                {{ number_format($currentPlan['features']['vouchers_per_month']) }}
                            @endif
                        </li>
                        @if($currentPlan['features']['top_tier_placement'])
                            <li>
                                <i class="fas fa-check text-success"></i>
                                <strong>Top-Tier Placement Eligible</strong>
                            </li>
                        @endif
                    </ul>
                    
                    @if($vendor->subscription_started_at)
                        <p class="text-muted mb-0">
                            <small>Subscribed since {{ $vendor->subscription_started_at->format('M j, Y') }}</small>
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Usage Stats -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar"></i> Current Usage
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Voucher Usage -->
                    <h6>Vouchers This Month</h6>
                    @if($vendor->hasUnlimitedVouchers())
                        <div class="alert alert-success">
                            <i class="fas fa-infinity"></i> <strong>Unlimited Vouchers</strong><br>
                            <small>{{ $vendor->vouchers_used_this_month }} vouchers redeemed this month</small>
                        </div>
                    @else
                        <div class="progress mb-2" style="height: 30px;">
                            <div class="progress-bar {{ $voucherUsagePercent >= 90 ? 'bg-danger' : ($voucherUsagePercent >= 70 ? 'bg-warning' : 'bg-success') }}" 
                                 style="width: {{ min(100, $voucherUsagePercent) }}%">
                                {{ $vendor->vouchers_used_this_month }} / {{ $vendor->monthly_voucher_limit }}
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ max(0, $vendor->monthly_voucher_limit - $vendor->vouchers_used_this_month) }} remaining
                            · Resets {{ now()->endOfMonth()->addDay()->diffForHumans() }}
                        </small>
                        
                        @if($vendor->hasReachedCapacity())
                            <div class="alert alert-danger mt-3">
                                <strong>Capacity Reached!</strong><br>
                                Your deals are paused. Upgrade now to resume immediately, or wait until next month.
                            </div>
                        @endif
                    @endif
                    
                    <hr>
                    
                    <!-- Active Deals Usage -->
                    <h6>Active Deals</h6>
                    @if($vendor->hasUnlimitedDeals())
                        <div class="alert alert-success">
                            <i class="fas fa-infinity"></i> <strong>Unlimited Deals</strong><br>
                            <small>{{ $vendor->active_deals_count }} deals currently active</small>
                        </div>
                    @else
                        <div class="progress mb-2" style="height: 30px;">
                            <div class="progress-bar {{ $dealUsagePercent >= 90 ? 'bg-danger' : 'bg-primary' }}" 
                                 style="width: {{ min(100, $dealUsagePercent) }}%">
                                {{ $vendor->active_deals_count }} / {{ $vendor->active_deals_limit }}
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ max(0, $vendor->active_deals_limit - $vendor->active_deals_count) }} slots available
                        </small>
                        
                        @if($vendor->hasReachedDealLimit())
                            <div class="alert alert-warning mt-3">
                                <strong>Deal Limit Reached!</strong><br>
                                Deactivate an existing deal or upgrade to create more.
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Upgrade Options -->
    @if(count($upgradeOptions) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-arrow-up"></i> Upgrade Your Plan
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">Unlock more capacity and features by upgrading your subscription.</p>
                        
                        <div class="row">
                            @foreach($upgradeOptions as $slug => $option)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-success h-100">
                                        <div class="card-body">
                                            <h5>{{ $option['name'] }}</h5>
                                            <h3 class="text-success">
                                                ${{ $option['price'] }}
                                                <small class="text-muted">/month</small>
                                            </h3>
                                            
                                            <ul class="list-unstyled mt-3">
                                                <li>
                                                    <i class="fas fa-check text-success"></i>
                                                    {{ $option['features']['active_deals'] ?? '∞' }} Active Deals
                                                </li>
                                                <li>
                                                    <i class="fas fa-check text-success"></i>
                                                    @if($option['features']['unlimited_vouchers'])
                                                        Unlimited Vouchers
                                                    @else
                                                        {{ number_format($option['features']['vouchers_per_month']) }} Vouchers/Month
                                                    @endif
                                                </li>
                                                @if($option['features']['top_tier_placement'])
                                                    <li>
                                                        <i class="fas fa-check text-success"></i>
                                                        Top-Tier Placement
                                                    </li>
                                                @endif
                                            </ul>
                                            
                                            <a href="{{ route('vendor.billing.upgrade', $slug) }}" 
                                               class="btn btn-success btn-block mt-3">
                                                Upgrade to {{ $option['name'] }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
