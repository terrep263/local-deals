@extends('app')

@section('title', $listing->title . ' - Lake County Local Deals')

@section('content')
<style>
    /* ============================================
       VENDOR PAGE STYLES - PROFESSIONAL LAYOUT
       ============================================ */
    
    /* Page Container */
    .vendor-page {
        background-color: #f8fafc;
        min-height: 100vh;
        padding-bottom: 60px;
    }
    
    /* Hero Header */
    .vendor-hero {
        position: relative;
        background: linear-gradient(180deg, rgba(15,23,42,0.72) 0%, rgba(15,23,42,0.72) 100%), var(--header-banner-img, url('{{ asset('upload/'.(getcong('page_bg_image') ?? 'assets/images/hero-bg.jpg')) }}')) center/cover no-repeat;
        padding: calc(var(--content-top, var(--header-h, 80px)) + 60px) 0 60px;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .vendor-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        pointer-events: none;
    }
    
    .vendor-hero-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

.vendor-breadcrumb {
    max-width: 1200px;
    margin: 0 auto 16px;
    padding: 0 20px;
    position: relative;
    z-index: 1;
    color: #ffffff;
}

.vendor-breadcrumb ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
    font-weight: 600;
    font-size: 14px;
}

.vendor-breadcrumb ul li {
    color: #ffffff;
    opacity: 0.85;
}

.vendor-breadcrumb ul li a {
    color: #ffffff !important;
    text-decoration: none;
}

.vendor-breadcrumb ul li::after {
    content: "›";
    margin: 0 6px;
    opacity: 0.6;
}

.vendor-breadcrumb ul li:last-child::after {
    display: none;
}
    
    .vendor-logo {
        width: 100px;
        height: 100px;
        border-radius: 16px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .vendor-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .vendor-logo-placeholder {
        font-size: 40px;
        color: #f97316;
    }
    
    .vendor-info {
        flex: 1;
        color: white;
    }
    
    .vendor-badges {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }
    
    .badge-category {
        background: #f97316;
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .badge-featured {
        background: #fbbf24;
        color: #78350f;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .vendor-title {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 10px 0;
        color: white;
    }
    
    .vendor-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        color: #cbd5e1;
        font-size: 14px;
    }
    
    .vendor-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .vendor-meta-item svg {
        width: 16px;
        height: 16px;
    }
    
    /* Main Content Area */
    .vendor-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .vendor-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
        align-items: start;
    }
    
    @media (max-width: 992px) {
        .vendor-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Card Styling */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 24px;
        overflow: hidden;
    }
    
    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    .card-header svg {
        width: 22px;
        height: 22px;
        color: #f97316;
    }
    
    .card-body {
        padding: 24px;
    }
    
    /* Deal Status Card */
    .deal-status-card {
        text-align: center;
        padding: 40px 24px;
    }
    
    .deal-status-icon {
        width: 80px;
        height: 80px;
        background: #fef3c7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .deal-status-icon svg {
        width: 40px;
        height: 40px;
        color: #f59e0b;
    }
    
    .deal-status-title {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 8px 0;
    }
    
    .deal-status-text {
        color: #64748b;
        font-size: 15px;
        margin: 0 0 20px 0;
    }
    
    .btn-notify {
        background: #f97316;
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .btn-notify:hover {
        background: #ea580c;
    }
    
    /* Active Deal Card */
    .deal-active-card {
        position: relative;
    }
    
    .discount-badge {
        position: absolute;
        top: -12px;
        right: 20px;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        font-size: 18px;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        z-index: 10;
    }
    
    .deal-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        padding: 24px;
    }
    
    @media (max-width: 768px) {
        .deal-content {
            grid-template-columns: 1fr;
        }
    }
    
    .deal-image {
        aspect-ratio: 16/10;
        border-radius: 12px;
        overflow: hidden;
        background: #f1f5f9;
    }
    
    .deal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .deal-details h3 {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 16px 0;
    }
    
    .price-display {
        margin-bottom: 20px;
    }
    
    .price-original {
        font-size: 18px;
        color: #94a3b8;
        text-decoration: line-through;
        display: block;
        margin-bottom: 4px;
    }
    
    .price-deal {
        font-size: 42px;
        font-weight: 800;
        color: #16a34a;
        line-height: 1;
    }
    
    .price-savings {
        color: #16a34a;
        font-size: 15px;
        font-weight: 600;
        margin-top: 6px;
    }
    
    /* Countdown Timer */
    .countdown-container {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
    }
    
    .countdown-label {
        font-size: 14px;
        font-weight: 600;
        color: #92400e;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .countdown-timer {
        display: flex;
        gap: 8px;
    }
    
    .countdown-segment {
        background: white;
        border-radius: 8px;
        padding: 10px 14px;
        text-align: center;
        min-width: 60px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .countdown-number {
        font-size: 28px;
        font-weight: 800;
        color: #b45309;
        line-height: 1;
    }
    
    .countdown-unit {
        font-size: 11px;
        text-transform: uppercase;
        color: #92400e;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }
    
    /* Inventory Bar */
    .inventory-display {
        margin-bottom: 0;
    }
    
    .inventory-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .inventory-remaining {
        font-weight: 600;
        color: #1e293b;
    }
    
    .inventory-claimed {
        color: #64748b;
    }
    
    .inventory-bar {
        height: 10px;
        background: #e5e7eb;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .inventory-fill {
        height: 100%;
        border-radius: 5px;
        transition: width 0.5s ease;
    }
    
    .inventory-fill.high {
        background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
    }
    
    .inventory-fill.medium {
        background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
    }
    
    .inventory-fill.low {
        background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
    }
    
    .inventory-urgent {
        color: #dc2626;
        font-size: 13px;
        font-weight: 600;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    /* Description Content */
    .description-content {
        font-size: 15px;
        line-height: 1.7;
        color: #475569;
    }
    
    .description-content p {
        margin: 0 0 16px 0;
    }
    
    .description-content p:last-child {
        margin-bottom: 0;
    }
    
    /* Photo Gallery */
    .photo-gallery {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    
    @media (max-width: 768px) {
        .photo-gallery {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .photo-item {
        aspect-ratio: 1;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        background: #f1f5f9;
    }
    
    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .photo-item:hover img {
        transform: scale(1.08);
    }
    
    /* Location Section */
    .location-map {
        border-radius: 12px;
        overflow: hidden;
        height: 280px;
        margin-bottom: 16px;
    }
    
    .location-map iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .location-address {
        font-size: 15px;
        color: #475569;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }
    
    .location-address svg {
        width: 18px;
        height: 18px;
        color: #f97316;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    /* Reviews Section */
    .reviews-empty {
        text-align: center;
        padding: 30px;
        color: #64748b;
    }
    
    .review-item {
        padding: 20px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .review-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .review-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    
    .review-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #fed7aa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #c2410c;
        font-size: 18px;
    }
    
    .review-meta {
        flex: 1;
    }
    
    .review-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }
    
    .review-stars {
        display: flex;
        gap: 2px;
    }
    
    .review-stars svg {
        width: 16px;
        height: 16px;
    }
    
    .review-stars svg.filled {
        color: #fbbf24;
    }
    
    .review-stars svg.empty {
        color: #d1d5db;
    }
    
    .review-date {
        font-size: 13px;
        color: #94a3b8;
    }
    
    .review-text {
        font-size: 15px;
        color: #475569;
        line-height: 1.6;
        margin: 0;
    }
    
    /* ============================================
       SIDEBAR STYLES
       ============================================ */
    
    .sidebar {
        position: sticky;
        top: 20px;
    }
    
    /* Purchase Box */
    .purchase-box {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .purchase-box-header {
        background: #f8fafc;
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .purchase-price-label {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 4px;
    }
    
    .purchase-price-original {
        font-size: 16px;
        color: #94a3b8;
        text-decoration: line-through;
    }
    
    .purchase-price-deal {
        font-size: 38px;
        font-weight: 800;
        color: #16a34a;
        line-height: 1.1;
    }
    
    .purchase-box-body {
        padding: 20px;
    }
    
    .quantity-selector {
        margin-bottom: 16px;
    }
    
    .quantity-label {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 10px;
        display: block;
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
    }
    
    .quantity-btn {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        background: white;
        font-size: 22px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .quantity-btn:hover {
        border-color: #f97316;
        color: #f97316;
    }
    
    .quantity-input {
        width: 60px;
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        border: none;
        color: #1e293b;
    }
    
    .quantity-input:focus {
        outline: none;
    }
    
    .quantity-max {
        text-align: center;
        font-size: 13px;
        color: #64748b;
        margin-top: 8px;
    }
    
    .purchase-total {
        background: #f8fafc;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    
    .purchase-total-label {
        font-weight: 600;
        color: #1e293b;
    }
    
    .purchase-total-amount {
        font-size: 26px;
        font-weight: 800;
        color: #16a34a;
    }
    
    .btn-buy {
        display: block;
        width: 100%;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        border: none;
        padding: 18px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(249, 115, 22, 0.35);
    }
    
    .btn-buy:hover {
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.45);
        color: white;
    }
    
    .btn-buy:disabled, .btn-buy.disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }
    
    .purchase-secure {
        text-align: center;
        font-size: 12px;
        color: #64748b;
        margin-top: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    
    .purchase-secure svg {
        width: 14px;
        height: 14px;
    }
    
    /* Vendor Info Sidebar */
    .vendor-sidebar-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 24px;
        margin-bottom: 20px;
    }
    
    .vendor-sidebar-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 20px 0;
    }
    
    .vendor-sidebar-profile {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
    }
    
    .vendor-sidebar-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #fed7aa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 700;
        color: #c2410c;
        overflow: hidden;
    }
    
    .vendor-sidebar-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .vendor-sidebar-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }
    
    .vendor-sidebar-member {
        font-size: 13px;
        color: #64748b;
    }
    
    .vendor-sidebar-contacts {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .vendor-sidebar-contact {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #475569;
        font-size: 14px;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .vendor-sidebar-contact:hover {
        color: #f97316;
    }
    
    .vendor-sidebar-contact svg {
        width: 18px;
        height: 18px;
        color: #94a3b8;
    }
    
    .vendor-sidebar-hours {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }
    
    .vendor-sidebar-hours h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 10px 0;
    }
    
    .vendor-sidebar-hours p {
        font-size: 14px;
        color: #64748b;
        margin: 0;
        line-height: 1.6;
    }
    
    /* Trust Badges */
    .trust-badges {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 20px;
    }
    
    .trust-badges-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .trust-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 6px;
    }
    
    .trust-badge svg {
        width: 28px;
        height: 28px;
    }
    
    .trust-badge span {
        font-size: 12px;
        color: #64748b;
    }
    
    /* Unavailable State */
    .purchase-unavailable {
        padding: 30px 20px;
        text-align: center;
    }
    
    .purchase-unavailable-icon {
        font-size: 48px;
        margin-bottom: 12px;
    }
    
    .purchase-unavailable-title {
        font-size: 18px;
        font-weight: 600;
        color: #475569;
        margin: 0 0 6px 0;
    }
    
    .purchase-unavailable-text {
        font-size: 14px;
        color: #94a3b8;
        margin: 0;
    }
    
    /* Lightbox */
    .lightbox {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .lightbox img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }
</style>

<div class="vendor-page">
    
    <!-- ============================================
         HERO HEADER
         ============================================ -->
    <div class="vendor-hero">
        <div class="overlay"></div>
        <div class="container">
            <div class="vendor-breadcrumb">
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('listings') }}">Listings</a></li>
                    <li>{{ $listing->title }}</li>
                </ul>
            </div>
        </div>
        <div class="vendor-hero-content">
            <div class="vendor-logo">
                @if($listing->featured_image)
                    <img src="{{ asset('upload/listings/'.$listing->featured_image.'-b.jpg') }}" alt="{{ $listing->title }}">
                @else
                    <span class="vendor-logo-placeholder">🏢</span>
                @endif
            </div>
            
            <div class="vendor-info">
                <div class="vendor-badges">
                    @if($listing->category)
                        <span class="badge-category">{{ $listing->category->name ?? 'Business' }}</span>
                    @endif
                    @if($listing->featured_listing)
                        <span class="badge-featured">⭐ Featured</span>
                    @endif
                </div>
                
                <h1 class="vendor-title">{{ $listing->title }}</h1>
                
                <div class="vendor-meta">
                    @if($listing->address)
                        <span class="vendor-meta-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $listing->address }}@if($listing->city), {{ $listing->city->name ?? '' }}@endif
                        </span>
                    @endif
                    
                    <span class="vendor-meta-item">
                        <svg fill="currentColor" viewBox="0 0 20 20" style="color: #fbbf24;">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ number_format($listing->avg_rating ?? 0, 1) }} ({{ $listing->reviews->count() ?? 0 }} reviews)
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ============================================
         MAIN CONTENT
         ============================================ -->
    <div class="vendor-content">
        <div class="vendor-grid">
            
            <!-- LEFT COLUMN -->
            <div class="main-column">
                
                <!-- DEAL STATUS CARD -->
                @php
                    $hasDeal = $listing->deal_price && $listing->deal_expires_at && $listing->deal_expires_at->isFuture();
                    $dealsRemaining = max(0, ($listing->deal_quantity_total ?? 0) - ($listing->deal_quantity_sold ?? 0));
                    $hasInventory = $dealsRemaining > 0;
                    $isDealActive = $hasDeal && $hasInventory;
                @endphp
                
                @if($isDealActive)
                    <!-- ACTIVE DEAL -->
                    <div class="card deal-active-card">
                        @if($listing->deal_discount_percentage)
                            <div class="discount-badge">{{ $listing->deal_discount_percentage }}% OFF</div>
                        @endif
                        
                        <div class="deal-content">
                            <div class="deal-image">
                                @if($listing->featured_image)
                                    <img src="{{ asset('upload/listings/'.$listing->featured_image.'-b.jpg') }}" alt="{{ $listing->title }}">
                                @else
                                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #cbd5e1;">
                                        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="deal-details">
                                <h3>{{ $listing->title }}</h3>
                                
                                <div class="price-display">
                                    @if($listing->deal_original_price)
                                        <span class="price-original">${{ number_format($listing->deal_original_price, 2) }}</span>
                                    @endif
                                    <div class="price-deal">${{ number_format($listing->deal_price, 2) }}</div>
                                    @if($listing->deal_original_price && $listing->deal_price)
                                        <div class="price-savings">You save ${{ number_format($listing->deal_original_price - $listing->deal_price, 2) }}</div>
                                    @endif
                                </div>
                                
                                <!-- Countdown -->
                                <div class="countdown-container">
                                    <div class="countdown-label">
                                        <span>⏰</span> Deal Ends In:
                                    </div>
                                    <div class="countdown-timer" id="countdown-timer">
                                        <div class="countdown-segment">
                                            <div class="countdown-number" id="cd-days">--</div>
                                            <div class="countdown-unit">Days</div>
                                        </div>
                                        <div class="countdown-segment">
                                            <div class="countdown-number" id="cd-hours">--</div>
                                            <div class="countdown-unit">Hours</div>
                                        </div>
                                        <div class="countdown-segment">
                                            <div class="countdown-number" id="cd-mins">--</div>
                                            <div class="countdown-unit">Mins</div>
                                        </div>
                                        <div class="countdown-segment">
                                            <div class="countdown-number" id="cd-secs">--</div>
                                            <div class="countdown-unit">Secs</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Inventory -->
                                <div class="inventory-display">
                                    <div class="inventory-header">
                                        <span class="inventory-remaining">🎟️ {{ $dealsRemaining }} of {{ $listing->deal_quantity_total }} remaining</span>
                                        <span class="inventory-claimed">{{ $listing->deal_quantity_sold ?? 0 }} claimed</span>
                                    </div>
                                    @php
                                        $pct = ($listing->deal_quantity_total > 0) ? ($dealsRemaining / $listing->deal_quantity_total) * 100 : 0;
                                        $barClass = $pct > 30 ? 'high' : ($pct > 10 ? 'medium' : 'low');
                                    @endphp
                                    <div class="inventory-bar">
                                        <div class="inventory-fill {{ $barClass }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                    @if($pct <= 10)
                                        <div class="inventory-urgent">🔥 Almost sold out!</div>
                                    @elseif($pct <= 30)
                                        <div class="inventory-urgent" style="color: #d97706;">⚡ Selling fast!</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- NO ACTIVE DEAL -->
                    <div class="card">
                        <div class="deal-status-card">
                            <div class="deal-status-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="deal-status-title">No Active Deals</h2>
                            <p class="deal-status-text">Check back soon for new deals from this vendor!</p>
                            <button class="btn-notify">🔔 Notify Me of New Deals</button>
                        </div>
                    </div>
                @endif
                
                <!-- ABOUT THIS DEAL -->
                <div class="card">
                    <div class="card-header">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h2>About This Deal</h2>
                    </div>
                    <div class="card-body">
                        <div class="description-content">
                            {!! nl2br(strip_tags($listing->description)) !!}
                        </div>
                    </div>
                </div>
                
                <!-- PHOTOS -->
                @if($listing->gallery && $listing->gallery->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h2>Photos</h2>
                    </div>
                    <div class="card-body">
                        <div class="photo-gallery">
                            @foreach($listing->gallery->take(8) as $image)
                                <div class="photo-item" onclick="openLightbox('{{ asset('upload/gallery/'.$image->image_name) }}')">
                                    <img src="{{ asset('upload/gallery/'.$image->image_name) }}" alt="Gallery photo">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- LOCATION -->
                @if($listing->latitude && $listing->longitude)
                <div class="card">
                    <div class="card-header">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h2>Location</h2>
                    </div>
                    <div class="card-body">
                        <div class="location-map">
                            <iframe 
                                src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps_key', env('GOOGLE_MAPS_KEY', '')) }}&q={{ $listing->latitude }},{{ $listing->longitude }}&zoom=15" 
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="location-address">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>{{ $listing->address }}@if($listing->city), {{ $listing->city->name ?? '' }}@endif</span>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- REVIEWS -->
                <div class="card">
                    <div class="card-header">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <h2>Reviews ({{ $listing->reviews->count() ?? 0 }})</h2>
                    </div>
                    <div class="card-body">
                        @if($listing->reviews && $listing->reviews->count() > 0)
                            @foreach($listing->reviews->take(5) as $review)
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-avatar">
                                            {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div class="review-meta">
                                            <div class="review-name">{{ $review->user->name ?? 'Anonymous' }}</div>
                                            <div class="review-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="{{ $i <= $review->rating ? 'filled' : 'empty' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="review-date">{{ $review->created_at ? \Carbon\Carbon::parse($review->created_at)->diffForHumans() : '' }}</span>
                                    </div>
                                    <p class="review-text">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="reviews-empty">No reviews yet.</div>
                        @endif
                    </div>
                </div>
                
            </div>
            
            <!-- RIGHT COLUMN - SIDEBAR -->
            <div class="sidebar">
                
                <!-- PURCHASE BOX -->
                <div class="purchase-box">
                    @if($isDealActive)
                        <div class="purchase-box-header">
                            <div class="purchase-price-label">Deal Price</div>
                            @if($listing->deal_original_price)
                                <div class="purchase-price-original">${{ number_format($listing->deal_original_price, 2) }}</div>
                            @endif
                            <div class="purchase-price-deal">${{ number_format($listing->deal_price, 2) }}</div>
                        </div>
                        
                        <div class="purchase-box-body">
                            <div class="quantity-selector">
                                <label class="quantity-label">Quantity</label>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn" onclick="changeQty(-1)">−</button>
                                    <input type="number" id="qty-input" class="quantity-input" value="1" min="1" max="{{ $dealsRemaining }}" readonly>
                                    <button type="button" class="quantity-btn" onclick="changeQty(1)">+</button>
                                </div>
                                <div class="quantity-max">Max {{ $dealsRemaining }} available</div>
                            </div>
                            
                            <div class="purchase-total">
                                <span class="purchase-total-label">Total</span>
                                <span class="purchase-total-amount" id="total-display">${{ number_format($listing->deal_price, 2) }}</span>
                            </div>
                            
                            @if($listing->stripe_payment_link)
                                <a href="{{ $listing->stripe_payment_link }}" target="_blank" class="btn-buy">
                                    🛒 Buy This Deal
                                </a>
                            @else
                                <button class="btn-buy disabled" disabled>
                                    Coming Soon
                                </button>
                            @endif
                            
                            <div class="purchase-secure">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Secure checkout via Stripe
                            </div>
                        </div>
                    @else
                        <div class="purchase-unavailable">
                            <div class="purchase-unavailable-icon">😢</div>
                            <h3 class="purchase-unavailable-title">Deal Not Available</h3>
                            <p class="purchase-unavailable-text">This deal has expired or sold out</p>
                        </div>
                    @endif
                </div>
                
                <!-- VENDOR INFO -->
                <div class="vendor-sidebar-card">
                    <h3 class="vendor-sidebar-title">About the Vendor</h3>
                    
                    <div class="vendor-sidebar-profile">
                        <div class="vendor-sidebar-avatar">
                            @if($listing->user && $listing->user->avatar)
                                <img src="{{ asset('storage/'.$listing->user->avatar) }}" alt="">
                            @else
                                {{ strtoupper(substr($listing->user->name ?? 'V', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="vendor-sidebar-name">{{ $listing->user->name ?? 'Vendor' }}</div>
                            <div class="vendor-sidebar-member">Member since {{ optional($listing->user)->created_at ? $listing->user->created_at->format('M Y') : 'N/A' }}</div>
                        </div>
                    </div>
                    
                    <div class="vendor-sidebar-contacts">
                        @if($listing->phone)
                            <a href="tel:{{ $listing->phone }}" class="vendor-sidebar-contact">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $listing->phone }}
                            </a>
                        @endif
                        
                        @if($listing->email)
                            <a href="mailto:{{ $listing->email }}" class="vendor-sidebar-contact">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $listing->email }}
                            </a>
                        @endif
                        
                        @if($listing->website)
                            <a href="{{ $listing->website }}" target="_blank" class="vendor-sidebar-contact">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                Visit Website
                            </a>
                        @endif
                    </div>
                    
                    @if($listing->business_hours)
                        <div class="vendor-sidebar-hours">
                            <h4>Business Hours</h4>
                            <p>{!! nl2br(e($listing->business_hours)) !!}</p>
                        </div>
                    @endif
                </div>
                
                <!-- TRUST BADGES -->
                <div class="trust-badges">
                    <div class="trust-badges-grid">
                        <div class="trust-badge">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Verified Vendor</span>
                        </div>
                        <div class="trust-badge">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #3b82f6;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Secure Payment</span>
                        </div>
                        <div class="trust-badge">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #8b5cf6;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            <span>Instant Voucher</span>
                        </div>
                        <div class="trust-badge">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #f97316;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Local Support</span>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
    
</div>

<script>
(function() {
    // Configuration
    const dealPrice = {{ $listing->deal_price ?? 0 }};
    const maxQty = {{ $dealsRemaining ?? 0 }};
    const expiresAt = @json($listing->deal_expires_at ? $listing->deal_expires_at->toIso8601String() : null);
    
    // Quantity Controls
    window.changeQty = function(delta) {
        const input = document.getElementById('qty-input');
        if (!input) return;
        
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > maxQty) val = maxQty;
        
        input.value = val;
        updateTotal();
    };
    
    function updateTotal() {
        const input = document.getElementById('qty-input');
        const display = document.getElementById('total-display');
        if (!input || !display) return;
        
        const qty = parseInt(input.value) || 1;
        const total = (dealPrice * qty).toFixed(2);
        display.textContent = '$' + total;
    }
    
    // Countdown Timer
    function startCountdown() {
        if (!expiresAt) return;
        
        const endTime = new Date(expiresAt).getTime();
        
        function update() {
            const now = Date.now();
            const diff = endTime - now;
            
            if (diff <= 0) {
                document.getElementById('cd-days').textContent = '0';
                document.getElementById('cd-hours').textContent = '0';
                document.getElementById('cd-mins').textContent = '0';
                document.getElementById('cd-secs').textContent = '0';
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((diff % (1000 * 60)) / 1000);
            
            document.getElementById('cd-days').textContent = days;
            document.getElementById('cd-hours').textContent = hours;
            document.getElementById('cd-mins').textContent = mins;
            document.getElementById('cd-secs').textContent = secs;
        }
        
        update();
        setInterval(update, 1000);
    }
    
    // Lightbox
    window.openLightbox = function(src) {
        const lb = document.createElement('div');
        lb.className = 'lightbox';
        lb.innerHTML = '<img src="' + src + '" alt="Photo">';
        lb.onclick = function() { lb.remove(); };
        document.body.appendChild(lb);
    };
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        startCountdown();
    });
})();
</script>

@endsection

