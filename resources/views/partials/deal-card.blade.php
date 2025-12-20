@php
    use Illuminate\Support\Str;
    $discountPercent = $deal->deal_discount_percentage ?? 0;
    $dealPrice = $deal->deal_price ?? 0;
    $originalPrice = $deal->deal_original_price ?? $deal->price ?? 0;
    $inventoryLeft = ($deal->deal_quantity_total ?? 0) - ($deal->deal_quantity_sold ?? 0);
    $showUrgency = $inventoryLeft > 0 && $inventoryLeft <= 10;
    $dealSlug = $deal->listing_slug ?? $deal->slug ?? Str::slug($deal->title ?? 'deal');
    $dealUrl = route('listing.show', [$dealSlug, $deal->id]);
    $imageUrl = $deal->featured_image ? asset('upload/listings/'.$deal->featured_image.'-b.jpg') : null;
@endphp

<div class="deal-card">
    <div class="deal-card-image">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $deal->title }}">
        @else
            <div class="no-image">🏷️</div>
        @endif
        
        @if($discountPercent > 0)
            <div class="discount-badge">{{ round($discountPercent) }}% OFF</div>
        @endif
        
        @if($showUrgency)
            <div class="urgency-badge">🔥 Only {{ $inventoryLeft }} left!</div>
        @endif
    </div>
    
    <div class="deal-card-body">
        <h3 class="deal-card-title">
            <a href="{{ $dealUrl }}">{{ $deal->title }}</a>
        </h3>
        
        @if($deal->business_name ?? null)
            <div class="deal-card-vendor">{{ $deal->business_name }}</div>
        @endif
        
        @if($deal->city_name ?? null)
            <div class="deal-card-location">
                <i>📍</i> {{ $deal->city_name }}
            </div>
        @endif
        
        @if(isset($showCountdown) && $showCountdown && $deal->deal_expires_at)
            <div class="countdown-timer" data-countdown="{{ $deal->deal_expires_at }}">
                <div class="countdown-unit"><div class="countdown-number">--</div><div class="countdown-label">Days</div></div>
                <div class="countdown-unit"><div class="countdown-number">--</div><div class="countdown-label">Hrs</div></div>
                <div class="countdown-unit"><div class="countdown-number">--</div><div class="countdown-label">Min</div></div>
            </div>
        @endif
        
        <div class="deal-card-pricing">
            <span class="deal-price">${{ number_format($dealPrice, 2) }}</span>
            @if($originalPrice > $dealPrice)
                <span class="original-price">${{ number_format($originalPrice, 2) }}</span>
            @endif
        </div>
        
        <a href="{{ $dealUrl }}" class="deal-card-cta">View Deal</a>
    </div>
</div>

