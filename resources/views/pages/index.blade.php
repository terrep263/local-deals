@extends('app')

@section('title', 'Lake County Local Deals - Save Big on Local Businesses')

@section('content')
<style>
    /* ===== HERO SECTION ===== */
    .deals-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
        padding: 60px 0 80px;
        text-align: center;
        position: relative;
    }
    
    .deals-hero h1 {
        color: #ffffff;
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 12px;
    }
    
    .deals-hero .subtitle {
        color: #94a3b8;
        font-size: 20px;
        margin-bottom: 40px;
    }
    
    .hero-search-box {
        max-width: 700px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 12px;
        padding: 8px;
        display: flex;
        gap: 8px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    
    .hero-search-box input {
        flex: 1;
        border: none;
        padding: 16px 20px;
        font-size: 16px;
        border-radius: 8px;
        outline: none;
    }
    
    .hero-search-box select {
        width: 180px;
        border: 1px solid #e5e7eb;
        padding: 16px;
        font-size: 16px;
        border-radius: 8px;
        background: #f8fafc;
        cursor: pointer;
    }
    
    .hero-search-box button {
        background: #f97316;
        color: white;
        border: none;
        padding: 16px 32px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .hero-search-box button:hover {
        background: #ea580c;
    }
    
    /* ===== SECTION STYLES ===== */
    .deals-section {
        padding: 60px 0;
    }
    
    .deals-section.alt-bg {
        background: #f8fafc;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }
    
    .section-title {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    .section-title .emoji {
        margin-right: 10px;
    }
    
    .view-all-link {
        color: #f97316;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .view-all-link:hover {
        text-decoration: underline;
    }
    
    /* ===== DEAL CARDS GRID ===== */
    .deals-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }
    
    @media (max-width: 1200px) {
        .deals-grid { grid-template-columns: repeat(3, 1fr); }
    }
    
    @media (max-width: 900px) {
        .deals-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 600px) {
        .deals-grid { grid-template-columns: 1fr; }
        .hero-search-box { flex-direction: column; }
        .hero-search-box select { width: 100%; }
        .deals-hero h1 { font-size: 28px; }
    }
    
    /* ===== DEAL CARD ===== */
    .deal-card {
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
    }
    
    .deal-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    }
    
    .deal-card-image {
        position: relative;
        height: 180px;
        background: #e2e8f0;
        overflow: hidden;
    }
    
    .deal-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .deal-card-image .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        font-size: 48px;
    }
    
    .discount-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.4);
    }
    
    .urgency-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0,0,0,0.75);
        color: #fbbf24;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .deal-card-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .deal-card-title {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .deal-card-title a {
        color: inherit;
        text-decoration: none;
    }
    
    .deal-card-title a:hover {
        color: #f97316;
    }
    
    .deal-card-vendor {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 6px;
    }
    
    .deal-card-location {
        font-size: 13px;
        color: #94a3b8;
        margin-bottom: 16px;
    }
    
    .deal-card-location i {
        margin-right: 4px;
    }
    
    .deal-card-pricing {
        margin-top: auto;
        display: flex;
        align-items: baseline;
        gap: 10px;
        margin-bottom: 16px;
    }
    
    .deal-price {
        font-size: 24px;
        font-weight: 800;
        color: #16a34a;
    }
    
    .original-price {
        font-size: 16px;
        color: #94a3b8;
        text-decoration: line-through;
    }
    
    .deal-card-cta {
        background: #f97316;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: background 0.2s;
    }
    
    .deal-card-cta:hover {
        background: #ea580c;
        color: white;
    }
    
    /* ===== COUNTDOWN TIMER ===== */
    .countdown-timer {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
    }
    
    .countdown-unit {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 6px;
        padding: 6px 10px;
        text-align: center;
        min-width: 50px;
    }
    
    .countdown-number {
        font-size: 18px;
        font-weight: 700;
        color: #dc2626;
    }
    
    .countdown-label {
        font-size: 10px;
        color: #ef4444;
        text-transform: uppercase;
    }
    
    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }
    
    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }
    
    .empty-state h3 {
        font-size: 20px;
        color: #475569;
        margin-bottom: 8px;
    }
    
    /* ===== VENDOR CTA ===== */
    .vendor-cta-section {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        padding: 60px 0;
        text-align: center;
    }
    
    .vendor-cta-section h2 {
        color: white;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 12px;
    }
    
    .vendor-cta-section p {
        color: rgba(255,255,255,0.9);
        font-size: 18px;
        margin-bottom: 24px;
    }
    
    .vendor-cta-btn {
        display: inline-block;
        background: white;
        color: #f97316;
        padding: 16px 40px;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 700;
        text-decoration: none;
        transition: transform 0.2s;
    }
    
    .vendor-cta-btn:hover {
        transform: scale(1.05);
        color: #ea580c;
    }
    
    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 20px;
    }
</style>

{{-- ===== HERO SECTION ===== --}}
<section class="deals-hero">
    <div class="container">
        <h1>Lake County Local Deals</h1>
        <p class="subtitle">Save big on restaurants, spas, fitness, home services & more</p>
        
        <form action="{{ url('/deals') }}" method="GET" class="hero-search-box">
            <input type="text" name="q" placeholder="Search deals..." value="{{ request('q') }}">
            <select name="city">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->location_name ?? $city->name }}</option>
                @endforeach
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
</section>

{{-- ===== FEATURED DEALS ===== --}}
<section class="deals-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><span class="emoji">⭐</span> Featured Deals</h2>
            <a href="{{ url('/deals') }}" class="view-all-link">View All →</a>
        </div>
        
        @if($featuredDeals->count() > 0)
            <div class="deals-grid">
                @foreach($featuredDeals as $deal)
                    @include('partials.deal-card', ['deal' => $deal])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🏷️</div>
                <h3>Featured deals coming soon!</h3>
                <p>Check back shortly for amazing local deals.</p>
            </div>
        @endif
    </div>
</section>

{{-- ===== HOT DEALS ===== --}}
<section class="deals-section alt-bg">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><span class="emoji">🔥</span> Hot Deals</h2>
            <a href="{{ url('/deals?sort=discount') }}" class="view-all-link">View All →</a>
        </div>
        @if($hotDeals->count() > 0)
            <div class="deals-grid">
                @foreach($hotDeals as $deal)
                    @include('partials.deal-card', ['deal' => $deal])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🔥</div>
                <h3>Hot deals will appear here</h3>
                <p>Check back soon for the biggest discounts.</p>
            </div>
        @endif
    </div>
</section>

{{-- ===== ENDING SOON ===== --}}
<section class="deals-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><span class="emoji">⏰</span> Ending Soon</h2>
            <a href="{{ url('/deals?sort=ending') }}" class="view-all-link">View All →</a>
        </div>
        @if($endingSoon->count() > 0)
            <div class="deals-grid">
                @foreach($endingSoon as $deal)
                    @include('partials.deal-card', ['deal' => $deal, 'showCountdown' => true])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">⏰</div>
                <h3>No deals ending soon</h3>
                <p>Grab a featured deal while you wait.</p>
            </div>
        @endif
    </div>
</section>

{{-- ===== NEW DEALS ===== --}}
<section class="deals-section alt-bg">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><span class="emoji">✨</span> Just Added</h2>
            <a href="{{ url('/deals?sort=newest') }}" class="view-all-link">View All →</a>
        </div>
        @if($newDeals->count() > 0)
            <div class="deals-grid">
                @foreach($newDeals as $deal)
                    @include('partials.deal-card', ['deal' => $deal])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">✨</div>
                <h3>New deals coming soon</h3>
                <p>We’re adding fresh offers shortly.</p>
            </div>
        @endif
    </div>
</section>

{{-- ===== VENDOR CTA ===== --}}
<section class="vendor-cta-section">
    <div class="container">
        <h2>Own a Local Business?</h2>
        <p>List your deals and reach thousands of Lake County customers</p>
        <a href="{{ url('/register') }}" class="vendor-cta-btn">List Your Deals Free →</a>
    </div>
</section>

<script>
// Countdown timer for ending soon deals
document.querySelectorAll('[data-countdown]').forEach(function(el) {
    const endDate = new Date(el.dataset.countdown).getTime();
    
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = endDate - now;
        
        if (distance < 0) {
            el.innerHTML = '<span style="color:#dc2626;font-weight:600;">Expired</span>';
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const mins = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        
        el.innerHTML = `
            <div class="countdown-unit"><div class="countdown-number">${days}</div><div class="countdown-label">Days</div></div>
            <div class="countdown-unit"><div class="countdown-number">${hours}</div><div class="countdown-label">Hrs</div></div>
            <div class="countdown-unit"><div class="countdown-number">${mins}</div><div class="countdown-label">Min</div></div>
        `;
    }
    
    updateCountdown();
    setInterval(updateCountdown, 60000);
});
</script>
@endsection

