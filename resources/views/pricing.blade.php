@extends('app')

@section('head_title', 'Pricing Plans | ' . getcong('site_name'))
@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', 'Pricing Plans')
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => 'Pricing', 'url' => '']]))

@section("content")

@include('common.page-hero-header', ['title' => 'Pricing Page']) 

<!-- ================================
    Start Pricing Area
================================= -->
<section class="pricing-area section_item_padding bg-gray">
    <div class="container">
        <!-- Hero Section -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 mb-3">Straightforward Pricing</h2>
                <p class="lead text-muted mb-4">Usage-based pricing that scales as your business grows</p>
                
                @if($founderSlotsLeft > 0)
                    <div class="alert alert-warning d-inline-block">
                        <i class="fas fa-star"></i>
                        <strong>Only {{ $founderSlotsLeft }} Founder spots remaining!</strong>
                        <br>First 25 businesses get free forever access.
                    </div>
                @endif
            </div>
        </div>

        @if(Session::has('flash_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('flash_message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if(Session::has('error_flash_message'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('error_flash_message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <!-- Pricing Cards -->
        <div class="row pricing-row mb-5">
            @foreach($plans as $plan)
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card pricing-card h-100 {{ $plan['slug'] === 'pro' ? 'pricing-card-featured' : '' }}">
                        @if($plan['slug'] === 'pro')
                            <div class="card-header bg-success text-white text-center">
                                <small><i class="fas fa-star"></i> MOST POPULAR</small>
                            </div>
                        @endif
                        
                        <div class="card-body text-center d-flex flex-column">
                            <h3 class="card-title mb-2">{{ $plan['name'] }}</h3>
                            <div class="price-display mb-3">
                                <span class="h1 font-weight-bold">${{ $plan['price'] }}</span>
                                <span class="text-muted">/month</span>
                            </div>
                            
                            <p class="text-muted small">{{ $plan['description'] }}</p>
                            
                            <hr class="my-3">
                            
                            <!-- Features -->
                            <ul class="list-unstyled text-left flex-grow-1">
                                @foreach($plan['features'] as $feature => $value)
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success mr-2"></i>
                                        <strong>{{ $feature }}:</strong> 
                                        <span class="text-muted">{{ $value }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="card-footer bg-white p-0 border-0 mt-3">
                                @auth
                                    @if(auth()->user()->vendorProfile && auth()->user()->vendorProfile->subscription_tier === $plan['slug'])
                                        <button class="btn btn-secondary btn-block" disabled>
                                            <i class="fas fa-check"></i> Current Plan
                                        </button>
                                    @else
                                        <a href="{{ route('vendor.billing.upgrade', $plan['slug']) }}" 
                                           class="btn btn-primary btn-block">
                                            Choose Plan
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary btn-block">
                                        Get Started
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Deal Examples by Plan (No Founder deals) -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="text-center mb-5">
                    <i class="fas fa-shopping-bag"></i> Deal Examples by Plan
                </h3>
            </div>

            <!-- Starter Plan Examples -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="plan-deals-container">
                    <h5 class="plan-name text-center mb-3">Starter Plan Deals (3 active max)</h5>
                    
                    <!-- Deal 1 -->
                    <div class="deal-card">
                        <div class="deal-header">
                            <span class="badge badge-success">Active</span>
                        </div>
                        <h6 class="deal-title">25% Off Coffee & Pastries</h6>
                        <p class="deal-business text-muted small">Sweet Brew Cafe</p>
                        <div class="deal-details">
                            <small>
                                <i class="fas fa-users"></i> <strong>50</strong> vouchers<br>
                                <i class="fas fa-check"></i> <strong>32</strong> redeemed
                            </small>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 64%"></div>
                        </div>
                    </div>

                    <!-- Deal 2 -->
                    <div class="deal-card">
                        <div class="deal-header">
                            <span class="badge badge-success">Active</span>
                        </div>
                        <h6 class="deal-title">Buy 1 Get 1 Pizza</h6>
                        <p class="deal-business text-muted small">Tony's Pizza Place</p>
                        <div class="deal-details">
                            <small>
                                <i class="fas fa-users"></i> <strong>75</strong> vouchers<br>
                                <i class="fas fa-check"></i> <strong>45</strong> redeemed
                            </small>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 60%"></div>
                        </div>
                    </div>

                    <!-- Deal 3 -->
                    <div class="deal-card">
                        <div class="deal-header">
                            <span class="badge badge-success">Active</span>
                        </div>
                        <h6 class="deal-title">Free Haircut Consultation</h6>
                        <p class="deal-business text-muted small">Salon Studio</p>
                        <div class="deal-details">
                            <small>
                                <i class="fas fa-users"></i> <strong>40</strong> vouchers<br>
                                <i class="fas fa-check"></i> <strong>22</strong> redeemed
                            </small>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 55%"></div>
                        </div>
                    </div>

                    <p class="text-center text-muted small mt-3">Can create 3 active deals</p>
                </div>
            </div>

            <!-- Pro Plan Examples -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="plan-deals-container">
                    <h5 class="plan-name text-center mb-3">Pro Plan Deals (10 active max)</h5>
                    
                    <!-- Deal 1 -->
                    <div class="deal-card">
                        <div class="deal-header">
                            <span class="badge badge-success">Active</span>
                        </div>
                        <h6 class="deal-title">Spring Spring Sale - 40% Off</h6>
                        <p class="deal-business text-muted small">Fashion Boutique</p>
                        <div class="deal-details">
                            <small>
                                <i class="fas fa-infinity text-primary"></i> <strong>Unlimited</strong> vouchers
                            </small>
                        </div>
                    </div>

                    <!-- Deal 2 -->
                    <div class="deal-card">
                        <div class="deal-header">
                            <span class="badge badge-success">Active</span>
                        </div>
                        <h6 class="deal-title">Spa Day Special Package</h6>
                        <p class="deal-business text-muted small">Relax Spa & Wellness</p>
                        <div class="deal-details">
                            <small>
                                <i class="fas fa-infinity text-primary"></i> <strong>Unlimited</strong> vouchers
                            </small>
                        </div>
                    </div>

                    <!-- Deal 3 -->
                    <div class="deal-card">
                        <div class="deal-header">
                            <span class="badge badge-success">Active</span>
                        </div>
                        <h6 class="deal-title">Oil Change & Car Wash</h6>
                        <p class="deal-business text-muted small">Auto Care Center</p>
                        <div class="deal-details">
                            <small>
                                <i class="fas fa-infinity text-primary"></i> <strong>Unlimited</strong> vouchers
                            </small>
                        </div>
                    </div>

                    <p class="text-center text-muted small mt-3">
                        <i class="fas fa-star text-warning"></i> Top-tier placement eligible
                    </p>
                </div>
            </div>

            <!-- Enterprise Plan Examples -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="plan-deals-container">
                    <h5 class="plan-name text-center mb-3">Enterprise Plan (Unlimited deals)</h5>
                    
                    <!-- Deal 1 -->
                    <div class="deal-card">
                        <div class="deal-header">
                            <span class="badge badge-success">Active</span>
                        </div>
                        <h6 class="deal-title">January Clearance Sale</h6>
                        <p class="deal-business text-muted small">Enterprise Retail</p>
                        <div class="deal-details">
                            <small>
                                <i class="fas fa-infinity text-primary"></i> <strong>Unlimited</strong> vouchers
                            </small>
                        </div>
                    </div>

                    <!-- Deal 2 -->
                    <div class="deal-card">
                        <div class="deal-header">
                            <span class="badge badge-success">Active</span>
                        </div>
                        <h6 class="deal-title">Loyalty Member Exclusive</h6>
                        <p class="deal-business text-muted small">Multi-Location Retail</p>
                        <div class="deal-details">
                            <small>
                                <i class="fas fa-infinity text-primary"></i> <strong>Unlimited</strong> vouchers
                            </small>
                        </div>
                    </div>

                    <!-- Deal 3 -->
                    <div class="deal-card">
                        <div class="deal-header">
                            <span class="badge badge-success">Active</span>
                        </div>
                        <h6 class="deal-title">Seasonal Promotion Bundle</h6>
                        <p class="deal-business text-muted small">Chain Operations</p>
                        <div class="deal-details">
                            <small>
                                <i class="fas fa-infinity text-primary"></i> <strong>Unlimited</strong> vouchers
                            </small>
                        </div>
                    </div>

                    <p class="text-center text-muted small mt-3">
                        <i class="fas fa-crown text-danger"></i> Maximum operational flexibility
                    </p>
                </div>
            </div>

            <!-- Blank space for 4th column balance -->
            <div class="col-lg-3 col-md-6 mb-4"></div>
        </div>

        <!-- Platform Rules -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">
                            <i class="fas fa-shield-alt"></i> Platform Rules (Apply to All Plans)
                        </h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success"></i>
                                        Deals must meet AI quality criteria before publishing
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success"></i>
                                        Inactive deals lose visibility and fall out of search
                                    </li>
                                    <li>
                                        <i class="fas fa-check text-success"></i>
                                        Vouchers reset monthly and do not roll over
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success"></i>
                                        Deals auto-pause when voucher capacity is reached
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success"></i>
                                        Visibility is influenced by recent deal activity
                                    </li>
                                    <li>
                                        <i class="fas fa-check text-success"></i>
                                        Growth is achieved only through plan upgrades
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="row mt-5">
            <div class="col-lg-8 offset-lg-2">
                <h3 class="text-center mb-4">Frequently Asked Questions</h3>
                <div class="accordion" id="faqAccordion">
                    <!-- FAQ 1 -->
                    <div class="card border-0 mb-2">
                        <div class="card-header bg-white border-bottom p-0">
                            <button class="btn btn-link text-dark text-left w-100 p-3" type="button" data-toggle="collapse" data-target="#collapse1">
                                <strong>What happens when I reach my voucher limit?</strong>
                            </button>
                        </div>
                        <div id="collapse1" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body">
                                Your active deals will automatically pause when you reach capacity. They'll resume on the 1st of next month when your voucher counter resets. You can upgrade at any time to increase capacity immediately.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 2 -->
                    <div class="card border-0 mb-2">
                        <div class="card-header bg-white border-bottom p-0">
                            <button class="btn btn-link text-dark text-left w-100 p-3" type="button" data-toggle="collapse" data-target="#collapse2">
                                <strong>Can I upgrade mid-month?</strong>
                            </button>
                        </div>
                        <div id="collapse2" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body">
                                Yes! Upgrades take effect immediately. You'll be charged a prorated amount for the remainder of the month, then the full price on your next billing cycle.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 3 -->
                    <div class="card border-0 mb-2">
                        <div class="card-header bg-white border-bottom p-0">
                            <button class="btn btn-link text-dark text-left w-100 p-3" type="button" data-toggle="collapse" data-target="#collapse3">
                                <strong>What is the Founder Plan?</strong>
                            </button>
                        </div>
                        <div id="collapse3" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body">
                                The first 25 businesses to join get free forever access with 1 active deal and 100 vouchers/month. Founder status requires continuous use - if no vouchers are redeemed for 2 consecutive months, the status is lost and a paid plan is required.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 4 -->
                    <div class="card border-0">
                        <div class="card-header bg-white border-bottom p-0">
                            <button class="btn btn-link text-dark text-left w-100 p-3" type="button" data-toggle="collapse" data-target="#collapse4">
                                <strong>Do unused vouchers roll over?</strong>
                            </button>
                        </div>
                        <div id="collapse4" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body">
                                No. Vouchers reset on the 1st of each month. Pro and Enterprise plans have unlimited vouchers, so this doesn't apply to them.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <p class="text-muted">
                    <i class="fas fa-users"></i> Join Lake County's leading deal platform | 
                    <i class="fas fa-tag"></i> Help local businesses reach customers
                </p>
            </div>
        </div>
    </div>
</section>
<!-- ================================
    End Pricing Area
================================= -->

<style>
.pricing-card {
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.pricing-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-color: #007bff;
}

.pricing-card-featured {
    border-color: #28a745;
    border-width: 3px;
}

.pricing-card-featured:hover {
    border-color: #28a745;
}

.price-display {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
}

.plan-deals-container {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    background: #fff;
}

.plan-name {
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

.deal-card {
    background: #f9f9f9;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 10px;
    border-left: 4px solid #007bff;
    transition: all 0.2s ease;
}

.deal-card:hover {
    background: #f0f0f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.deal-header {
    text-align: right;
    margin-bottom: 8px;
}

.deal-title {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
    color: #333;
}

.deal-business {
    margin: 0 0 8px 0;
    font-size: 12px;
}

.deal-details {
    font-size: 12px;
    color: #555;
}

.progress {
    background: #e0e0e0;
}

@media (max-width: 768px) {
    .pricing-card-featured {
        transform: scale(1);
    }
    
    .plan-deals-container {
        margin-bottom: 20px;
    }
}
</style>

@endsection


