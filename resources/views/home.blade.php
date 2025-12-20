@extends('app')

@section('head_title', 'Lake County Local Deals - Save Big on Local Businesses')
@section('hide_header', true)
@section('hide_footer', true)

@section('content')

<!-- ==================== HEADER ==================== -->
<header class="header">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-icon">🏷️</div>
            <div class="logo-text">Lake County <span>Deals</span></div>
        </a>
        <nav class="nav">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('deals.index') }}">Deals</a>
            <a href="{{ route('categories.index') }}">Categories</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('business.create') }}" class="nav-cta">List Your Business</a>
        </nav>
    </div>
</header>

<!-- ==================== HERO ==================== -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                New Deals Added Daily
            </div>
            <h1>Shop Local Deals<br>& <span>Save Big!</span></h1>
            <p class="hero-subtitle">Discover exclusive discounts from restaurants, spas, fitness centers, home services & more across Lake County, Florida.</p>
            
            <form method="GET" action="{{ route('deals.index') }}" class="hero-search">
                <input type="text" name="q" placeholder="Search for deals..." value="{{ request('q') }}">
                <select name="city">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->name }}">{{ $city->name }}</option>
                    @endforeach
                </select>
                <button type="submit">Search Deals</button>
            </form>
            
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-number">500+*</div>
                    <div class="hero-stat-label">Local Businesses</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-number">10K+*</div>
                    <div class="hero-stat-label">Happy Customers</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-number">$2M+*</div>
                    <div class="hero-stat-label">Total Savings</div>
                </div>
            </div>
            <p style="font-size: 10px; color: #4a4a68; margin-top: 16px; opacity: 0.7;">*Projected goals for the next 24 months</p>
        </div>
        
        <div class="hero-cards">
            <div class="hero-deal-card">
                <div class="deal-card-img">
                    <div class="deal-card-img-placeholder" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">💆‍♀️</div>
                    <div class="deal-badge">60% OFF</div>
                </div>
                <div class="deal-card-body">
                    <div class="deal-card-title">Luxury Spa Package</div>
                    <div class="deal-card-vendor">Serenity Day Spa • Clermont</div>
                    <div class="deal-card-price">
                        <span class="deal-price-current">$79</span>
                        <span class="deal-price-original">$199</span>
                    </div>
                </div>
            </div>
            
            <div class="hero-deal-card">
                <div class="deal-card-img">
                    <div class="deal-card-img-placeholder" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">🍕</div>
                    <div class="deal-badge">40% OFF</div>
                </div>
                <div class="deal-card-body">
                    <div class="deal-card-title">Family Dinner Deal</div>
                    <div class="deal-card-vendor">Mario's Kitchen • Leesburg</div>
                    <div class="deal-card-price">
                        <span class="deal-price-current">$45</span>
                        <span class="deal-price-original">$75</span>
                    </div>
                </div>
            </div>
            
            <div class="hero-deal-card">
                <div class="deal-card-img">
                    <div class="deal-card-img-placeholder" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">🏋️</div>
                    <div class="deal-badge">75% OFF</div>
                </div>
                <div class="deal-card-body">
                    <div class="deal-card-title">1 Month Membership</div>
                    <div class="deal-card-vendor">FitLife Gym • Mt Dora</div>
                    <div class="deal-card-price">
                        <span class="deal-price-current">$25</span>
                        <span class="deal-price-original">$99</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PROMO BANNER ==================== -->
@php
    $promoRaw = getcong('promo_banner_items');
    $promoItems = [];
    if ($promoRaw) {
        $decoded = json_decode($promoRaw, true);
        if (is_array($decoded)) {
            $promoItems = $decoded;
        }
    }
    if (empty($promoItems)) {
        $promoItems = [
            ['emoji' => '⚡', 'text' => 'LIMITED TIME OFFERS'],
            ['emoji' => '🎯', 'text' => 'UP TO 75% OFF'],
            ['emoji' => '🏆', 'text' => 'BEST LOCAL DEALS'],
            ['emoji' => '💰', 'text' => 'SAVE MORE TODAY'],
            ['emoji' => '🔥', 'text' => 'NEW DEALS DAILY'],
        ];
    }
@endphp
<style>
    /* Promo marquee fallback/override to ensure scrolling */
    .promo-banner-viewport {
        overflow: hidden;
        width: 100%;
    }
    .promo-banner-track {
        display: flex;
        align-items: center;
        gap: 80px;
        white-space: nowrap;
        animation: promoMarquee 20s linear infinite;
        will-change: transform;
    }
    .promo-item {
        flex: 0 0 auto;
        white-space: nowrap;
    }
    @keyframes promoMarquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    /* Force home header CTA text white */
    .header .nav-cta {
        color: #ffffff !important;
    }
</style>
<section class="promo-banner">
    <div class="promo-banner-viewport">
        <div class="promo-banner-track">
            @foreach($promoItems as $item)
                <div class="promo-item">{{ $item['emoji'] ?? '' }} {{ $item['text'] ?? '' }}</div>
            @endforeach
            {{-- Duplicate for seamless loop --}}
            @foreach($promoItems as $item)
                <div class="promo-item">{{ $item['emoji'] ?? '' }} {{ $item['text'] ?? '' }}</div>
            @endforeach
        </div>
    </div>
</section>

<!-- ==================== CATEGORIES ==================== -->
<section class="categories">
    <div class="section-inner">
        <div class="section-header">
            <div class="section-label">Browse Categories</div>
            <h2 class="section-title">Popular Categories</h2>
            <p class="section-subtitle">Find amazing deals in your favorite categories from local businesses you'll love</p>
        </div>
        
        <div class="categories-grid">
            <a href="{{ route('categories.index') }}" class="category-card" style="--cat-color: #ef4444; --cat-color-light: #fca5a5;">
                <div class="category-icon category-icon-img" style="background: rgba(239,68,68,0.1);">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=120&h=120&fit=crop" alt="Restaurants">
                </div>
                <div class="category-name">Restaurants</div>
                <div class="category-count">120+ Deals</div>
            </a>
            <a href="{{ route('categories.index') }}" class="category-card" style="--cat-color: #ec4899; --cat-color-light: #f9a8d4;">
                <div class="category-icon" style="background: rgba(236,72,153,0.1); color: #ec4899;">
                    <i class="fas fa-spa"></i>
                </div>
                <div class="category-name">Spa & Beauty</div>
                <div class="category-count">85+ Deals</div>
            </a>
            <a href="{{ route('categories.index') }}" class="category-card" style="--cat-color: #10b981; --cat-color-light: #6ee7b7;">
                <div class="category-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <div class="category-name">Health & Fitness</div>
                <div class="category-count">65+ Deals</div>
            </a>
            <a href="{{ route('categories.index') }}" class="category-card" style="--cat-color: #3b82f6; --cat-color-light: #93c5fd;">
                <div class="category-icon category-icon-img" style="background: rgba(59,130,246,0.1);">
                    <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=120&h=120&fit=crop" alt="Automotive">
                </div>
                <div class="category-name">Automotive</div>
                <div class="category-count">90+ Deals</div>
            </a>
            <a href="{{ route('categories.index') }}" class="category-card" style="--cat-color: #14b8a6; --cat-color-light: #5eead4;">
                <div class="category-icon" style="background: rgba(20,184,166,0.1); color: #14b8a6;">
                    <i class="fas fa-home"></i>
                </div>
                <div class="category-name">Home Services</div>
                <div class="category-count">110+ Deals</div>
            </a>
            <a href="{{ route('categories.index') }}" class="category-card" style="--cat-color: #f97316; --cat-color-light: #fdba74;">
                <div class="category-icon category-icon-text" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white;">
                    S
                </div>
                <div class="category-name">Shopping</div>
                <div class="category-count">75+ Deals</div>
            </a>
        </div>
    </div>
</section>

<!-- ==================== FEATURED DEALS ==================== -->
<section class="featured-deals">
    <div class="section-inner">
        <div class="section-header-row">
            <div class="section-header-left">
                <div class="section-label">🔥 Hot Right Now</div>
                <h2 class="section-title">Featured Deals</h2>
                <p class="section-subtitle">Handpicked deals from top-rated local businesses</p>
            </div>
            <a href="{{ route('deals.index') }}" class="view-all-btn">View All Deals →</a>
        </div>
        
        <div class="deals-grid">
            <div class="deal-card">
                <div class="deal-card-image">
                    <div class="deal-card-image-placeholder" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">💆‍♀️</div>
                    <div class="deal-discount-badge">60% OFF</div>
                    <div class="deal-urgency-badge">⏰ 2 days left</div>
                </div>
                <div class="deal-card-content">
                    <h3 class="deal-card-title">Full Relaxation Spa Package</h3>
                    <p class="deal-card-vendor">Serenity Day Spa</p>
                    <p class="deal-card-location">📍 Clermont</p>
                    <div class="deal-card-footer">
                        <div class="deal-card-pricing">
                            <span class="deal-card-price">$79</span>
                            <span class="deal-card-original">$199</span>
                        </div>
                        <a class="deal-card-btn" href="{{ route('deals.index') }}">Get Deal</a>
                    </div>
                </div>
            </div>
            
            <div class="deal-card">
                <div class="deal-card-image">
                    <div class="deal-card-image-placeholder" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">🍕</div>
                    <div class="deal-discount-badge">40% OFF</div>
                    <div class="deal-hot-badge">🔥 BEST SELLER</div>
                </div>
                <div class="deal-card-content">
                    <h3 class="deal-card-title">Family Pizza Night Special</h3>
                    <p class="deal-card-vendor">Mario's Italian Kitchen</p>
                    <p class="deal-card-location">📍 Leesburg</p>
                    <div class="deal-card-footer">
                        <div class="deal-card-pricing">
                            <span class="deal-card-price">$35</span>
                            <span class="deal-card-original">$58</span>
                        </div>
                        <a class="deal-card-btn" href="{{ route('deals.index') }}">Get Deal</a>
                    </div>
                </div>
            </div>
            
            <div class="deal-card">
                <div class="deal-card-image">
                    <div class="deal-card-image-placeholder" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">🏋️</div>
                    <div class="deal-discount-badge">75% OFF</div>
                    <div class="deal-urgency-badge">🔥 5 left!</div>
                </div>
                <div class="deal-card-content">
                    <h3 class="deal-card-title">1 Month Gym Membership</h3>
                    <p class="deal-card-vendor">FitLife Fitness Center</p>
                    <p class="deal-card-location">📍 Mount Dora</p>
                    <div class="deal-card-footer">
                        <div class="deal-card-pricing">
                            <span class="deal-card-price">$25</span>
                            <span class="deal-card-original">$99</span>
                        </div>
                        <a class="deal-card-btn" href="{{ route('deals.index') }}">Get Deal</a>
                    </div>
                </div>
            </div>
            
            <div class="deal-card">
                <div class="deal-card-image">
                    <div class="deal-card-image-placeholder" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">🚗</div>
                    <div class="deal-discount-badge">50% OFF</div>
                </div>
                <div class="deal-card-content">
                    <h3 class="deal-card-title">Complete Car Detail Package</h3>
                    <p class="deal-card-vendor">Shine Auto Detailing</p>
                    <p class="deal-card-location">📍 Tavares</p>
                    <div class="deal-card-footer">
                        <div class="deal-card-pricing">
                            <span class="deal-card-price">$89</span>
                            <span class="deal-card-original">$179</span>
                        </div>
                        <a class="deal-card-btn" href="{{ route('deals.index') }}">Get Deal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SALE BANNER ==================== -->
@if(getcong('sale_banner_enabled'))
<section class="sale-banner">
    <div class="section-inner">
        <div class="sale-banner-inner">
            <div class="sale-banner-content">
                <div class="sale-banner-label">{{ getcong('sale_banner_label') ?: '🎉 LIMITED TIME' }}</div>
                <h2 class="sale-banner-title">{{ getcong('sale_banner_title') ?: 'Holiday Deals Event' }}</h2>
                <p class="sale-banner-subtitle">{{ getcong('sale_banner_subtitle') ?: 'Exclusive savings from local businesses this season' }}</p>
            </div>
            <div class="sale-banner-discount">
                <div class="discount-box">
                    <div class="discount-up-to">Up To</div>
                    <div class="discount-number">{{ getcong('sale_banner_discount') ?: '75%' }}</div>
                    <div class="discount-off">OFF</div>
                </div>
                <a href="{{ route('deals.index') }}" class="sale-banner-btn">{{ getcong('sale_banner_button_text') ?: 'Shop All Deals →' }}</a>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ==================== HOW IT WORKS ==================== -->
<section class="how-it-works">
    <div class="section-inner">
        <div class="section-header">
            <div class="section-label">Simple Process</div>
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Save money on local businesses in three easy steps</p>
        </div>
        
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon">🔍</div>
                <h3 class="step-title">Browse Deals</h3>
                <p class="step-desc">Explore hundreds of exclusive deals from restaurants, spas, fitness centers and more across Lake County.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon">🛒</div>
                <h3 class="step-title">Purchase & Save</h3>
                <p class="step-desc">Buy deals at huge discounts. Pay securely online and receive your voucher instantly via email.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon">🎉</div>
                <h3 class="step-title">Redeem & Enjoy</h3>
                <p class="step-desc">Show your voucher at the business to redeem. Enjoy amazing experiences while supporting local!</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== TESTIMONIALS ==================== -->
<section class="testimonials">
    <div class="section-inner">
        <div class="section-header">
            <div class="section-label">Customer Love</div>
            <h2 class="section-title">What People Say</h2>
            <p class="section-subtitle">Join thousands of happy customers saving money locally</p>
        </div>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Found an amazing spa deal in Clermont. Saved $120 on a massage package! This site is my go-to for local deals now."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">JW</div>
                    <div class="testimonial-author-info">
                        <h4>Jennifer Wilson</h4>
                        <p>Clermont Resident</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Great way to discover new restaurants in Lake County. We've tried 5 new places this month and saved over $200!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">MR</div>
                    <div class="testimonial-author-info">
                        <h4>Michael Rodriguez</h4>
                        <p>Mount Dora Local</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"As a small business owner, this platform helped me reach new customers. Easy to use and great support team!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">ST</div>
                    <div class="testimonial-author-info">
                        <h4>Sarah Thompson</h4>
                        <p>Business Owner</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA SECTION ==================== -->
<section class="cta-section">
    <div class="section-inner">
        <div class="cta-inner">
            <h2 class="cta-title">Own a Local Business?</h2>
            <p class="cta-subtitle">List your deals and reach thousands of Lake County customers today</p>
            <a href="{{ route('business.create') }}" class="cta-btn">List Your Business Free →</a>
        </div>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer class="footer">
    <div class="section-inner">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">
                    <div class="footer-logo-icon">🏷️</div>
                    <div class="footer-logo-text">Lake County <span>Deals</span></div>
                </div>
                <p class="footer-desc">Connecting Lake County residents with the best local deals. Save money while supporting businesses in your community.</p>
                <div class="footer-social">
                    <a href="#">f</a>
                    <a href="#">t</a>
                    <a href="#">in</a>
                </div>
            </div>
            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('deals.index') }}">Browse Deals</a></li>
                    <li><a href="{{ route('categories.index') }}">Categories</a></li>
                    <li><a href="{{ route('home') }}#how-it-works">How It Works</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>For Business</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('business.create') }}">List Your Business</a></li>
                    <li><a href="{{ route('pricing') }}">Pricing Plans</a></li>
                    <li><a href="{{ route('business.create') }}">Success Stories</a></li>
                    <li><a href="{{ route('login') }}">Business Login</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Support</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('contact') }}">Help Center</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('contact') }}">FAQs</a></li>
                    <li><a href="{{ route('contact') }}">Report Issue</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="footer-copyright">© 2025 Lake County Local Deals. All rights reserved.</p>
            <div class="footer-legal">
                <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
                <a href="{{ url('terms-conditions') }}">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

@endsection

