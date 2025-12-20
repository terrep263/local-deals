@extends('app')

@section('head_title', 'Pricing Plans | ' . getcong('site_name'))
@section('head_url', Request::url())

@section("content")

<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">Choose Your Plan</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li>Pricing</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area
================================= --> 

<!-- ================================
    Start Pricing Area
================================= -->
<section class="pricing-area section_item_padding bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="item_sec_title">Pricing Plans</h2>
                    <p class="item_sec_desc">Choose the perfect plan for your business needs</p>
                </div>
            </div>
        </div>

        @if(Session::has('flash_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('flash_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(Session::has('error_flash_message'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('error_flash_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row pricing-row">
            @foreach($features as $feature)
                @php
                    $isPro = $feature->package_tier === 'pro';
                    $isStarter = $feature->package_tier === 'starter';
                    $price = $isStarter ? 'FREE' : '$' . number_format($feature->monthly_price, 0) . '/mo';
                    $simultaneous = $feature->simultaneous_deals == -1 ? 'Unlimited' : $feature->simultaneous_deals;
                    $inventory = $feature->inventory_cap_per_deal == -1 ? 'Unlimited' : $feature->inventory_cap_per_deal;
                @endphp
                
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="pricing-card {{ $isPro ? 'pricing-card-featured' : '' }}" style="height: 100%;">
                        @if($isPro)
                            <div class="pricing-badge">MOST POPULAR</div>
                        @endif
                        
                        <div class="pricing-header">
                            <h3 class="pricing-title">{{ ucfirst($feature->package_tier) }}</h3>
                            <div class="pricing-price">
                                <span class="price-amount">{{ $price }}</span>
                            </div>
                        </div>

                        <div class="pricing-body">
                            <ul class="pricing-features">
                                <li>
                                    <i class="fa fa-check text-success"></i>
                                    <strong>{{ $simultaneous }}</strong> {{ $simultaneous == 1 ? 'deal' : 'simultaneous deals' }}
                                </li>
                                <li>
                                    <i class="fa fa-check text-success"></i>
                                    Max <strong>{{ $inventory }}</strong> spots per deal
                                </li>
                                
                                @if($feature->auto_approval)
                                    <li><i class="fa fa-check text-success"></i> Instant auto-approval</li>
                                @else
                                    <li><i class="fa fa-times text-danger"></i> Manual approval (1-2 day delay)</li>
                                @endif

                                @if($feature->analytics_access)
                                    <li><i class="fa fa-check text-success"></i> {{ $feature->package_tier == 'basic' ? 'Basic' : 'Advanced' }} analytics</li>
                                @else
                                    <li><i class="fa fa-times text-danger"></i> No analytics</li>
                                @endif

                                @if($feature->ai_scoring_enabled)
                                    <li><i class="fa fa-check text-success"></i> AI deal quality scoring</li>
                                @else
                                    <li><i class="fa fa-times text-danger"></i> No AI scoring</li>
                                @endif

                                @if($feature->priority_placement)
                                    <li><i class="fa fa-check text-success"></i> Priority search placement</li>
                                @else
                                    <li><i class="fa fa-times text-danger"></i> No priority placement</li>
                                @endif

                                @if($feature->featured_placement)
                                    <li><i class="fa fa-check text-success"></i> Featured homepage placement</li>
                                @else
                                    <li><i class="fa fa-times text-danger"></i> No featured placement</li>
                                @endif

                                @if($feature->custom_branding)
                                    <li><i class="fa fa-check text-success"></i> Custom branding (logo, colors)</li>
                                @else
                                    <li><i class="fa fa-times text-danger"></i> No custom branding</li>
                                @endif

                                @if($feature->api_access)
                                    <li><i class="fa fa-check text-success"></i> Full API access</li>
                                @else
                                    <li><i class="fa fa-times text-danger"></i> No API access</li>
                                @endif

                                @if($feature->white_label)
                                    <li><i class="fa fa-check text-success"></i> White-label (remove branding)</li>
                                @else
                                    <li><i class="fa fa-times text-danger"></i> Platform branding</li>
                                @endif

                                <li>
                                    <i class="fa fa-check text-success"></i>
                                    {{ ucfirst($feature->support_level) }} support
                                </li>
                            </ul>
                        </div>

                        <div class="pricing-footer">
                            @if($isStarter)
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-block">Get Started Free</a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary btn-block">Get Started Free</a>
                                @endauth
                            @else
                                @auth
                                    <form action="{{ route('subscription.checkout') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="tier" value="{{ $feature->package_tier }}">
                                        <button type="submit" class="btn {{ $isPro ? 'btn-success' : 'btn-primary' }} btn-block">
                                            Subscribe Now - {{ $price }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}?redirect={{ urlencode(route('pricing')) }}" class="btn {{ $isPro ? 'btn-success' : 'btn-primary' }} btn-block">
                                        Subscribe Now - {{ $price }}
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <p class="text-muted">
                    <i class="fa fa-users"></i> Join 50+ Lake County businesses | 
                    <i class="fa fa-tag"></i> 500+ deals sold through our platform
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
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
}

.pricing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.pricing-card-featured {
    border-color: #28a745;
    border-width: 3px;
    transform: scale(1.05);
}

.pricing-badge {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: #28a745;
    color: white;
    padding: 5px 20px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.pricing-header {
    margin-bottom: 30px;
}

.pricing-title {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
}

.pricing-price {
    margin-bottom: 20px;
}

.price-amount {
    font-size: 36px;
    font-weight: bold;
    color: #28a745;
}

.pricing-features {
    list-style: none;
    padding: 0;
    margin: 0 0 30px 0;
    text-align: left;
}

.pricing-features li {
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
}

.pricing-features li:last-child {
    border-bottom: none;
}

.pricing-features li i {
    margin-right: 10px;
    width: 20px;
}

.pricing-footer {
    margin-top: auto;
}

@media (max-width: 768px) {
    .pricing-card-featured {
        transform: scale(1);
    }
}
</style>

@endsection


