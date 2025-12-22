@extends('layouts.vendor')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-arrow-up"></i> Upgrade to {{ $targetPlan['name'] }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Current Plan</h5>
                            <p class="text-muted">{{ $vendor->subscription_tier }}</p>
                            <p class="text-muted mb-0">${{ $vendor->monthly_price }}/month</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Upgrade Plan</h5>
                            <p><strong>{{ $targetPlan['name'] }}</strong></p>
                            <p class="text-success mb-0"><strong>${{ $targetPlan['price'] }}/month</strong></p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">New Plan Features:</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <strong>Active Deals:</strong> 
                            {{ $targetPlan['features']['active_deals'] ?? 'Unlimited' }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            <strong>Vouchers/Month:</strong> 
                            @if($targetPlan['features']['unlimited_vouchers'])
                                Unlimited
                            @else
                                {{ number_format($targetPlan['features']['vouchers_per_month']) }}
                            @endif
                        </li>
                        @if($targetPlan['features']['top_tier_placement'])
                            <li>
                                <i class="fas fa-check text-success mr-2"></i>
                                <strong>Top-Tier Placement Eligible</strong>
                            </li>
                        @endif
                    </ul>
                    
                    <hr>
                    
                    <p class="text-muted small">
                        <i class="fas fa-info-circle"></i>
                        The upgrade will take effect immediately. Your billing will be adjusted pro-rata for this month.
                    </p>
                    
                    <form action="{{ route('vendor.billing.process-upgrade') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $targetPlan['slug'] }}">
                        <button type="submit" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-lock"></i> Proceed to Payment
                        </button>
                    </form>
                    
                    <a href="{{ route('vendor.billing.index') }}" class="btn btn-secondary btn-block mt-2">
                        Cancel
                    </a>
                </div>
            </div>
            
            <!-- Payment Info -->
            <div class="alert alert-info mt-4">
                <h5><i class="fas fa-shield-alt"></i> Secure Payment</h5>
                <p class="mb-0">Your payment is processed securely by Stripe. We never store your card information on our servers.</p>
            </div>
        </div>
    </div>
</div>
@endsection
