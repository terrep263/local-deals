@extends('app')

@section('head_title', $deal->title . ' - ' . $deal->discount_percentage . '% Off | ' . getcong('site_name'))
@section('head_description', 'Save $' . number_format($deal->savings_amount, 2) . ' on ' . $deal->title . '. Only ' . $inventoryRemaining . ' spots left! Expires ' . $deal->expires_at->format('M d, Y') . '.')
@section('head_url', Request::url())
@section('head_image', $deal->getFeaturedImageUrl())

@push('head')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Offer",
  "name": "{{ $deal->title }}",
  "price": "{{ $deal->deal_price }}",
  "priceCurrency": "USD",
  "availability": "{{ $deal->is_sold_out ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock' }}",
  "seller": {
    "@type": "Organization",
    "name": "{{ $deal->vendor->first_name }} {{ $deal->vendor->last_name }}"
  },
  "validThrough": "{{ $deal->expires_at->toIso8601String() }}",
  "description": "{{ strip_tags($deal->description) }}",
  "url": "{{ route('deals.show', $deal->slug) }}"
}
</script>
@endpush

@section("content")

<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">{{ $deal->title }}</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li><a href="{{ route('deals.show', $deal->slug) }}">Deal</a></li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area
================================= --> 

<!-- ================================
    Start Deal Detail Area
================================= -->
<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Featured Image -->
                @if($deal->featured_image)
                <div class="deal-featured-image mb-4">
                    <img src="{{ asset('storage/deals/' . $deal->featured_image) }}" alt="{{ $deal->title }}" class="img-fluid rounded">
                </div>
                @endif

                <!-- Deal Title & Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h1 class="h2 mb-3">{{ $deal->title }}</h1>
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge badge-primary mr-2">{{ $deal->category->category_name ?? 'Uncategorized' }}</span>
                            <span class="text-muted">
                                <i class="fa fa-map-marker-alt"></i> {{ $deal->location_city }}, FL {{ $deal->location_zip }}
                            </span>
                        </div>
                        <p class="text-muted mb-0">
                            By <a href="#">{{ $deal->vendor->first_name }} {{ $deal->vendor->last_name }}</a>
                        </p>
                    </div>
                </div>

                <!-- Description -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="h4 mb-3">What's Included</h3>
                        <div class="deal-description">
                            {!! nl2br(e($deal->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Gallery -->
                @if($deal->gallery_images && count($deal->gallery_images) > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="h4 mb-3">Gallery</h3>
                        <div class="row">
                            @foreach($deal->gallery_images as $image)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/deals/gallery/' . $image) }}" alt="Gallery Image" class="img-fluid rounded" style="cursor: pointer;" onclick="openLightbox(this.src)">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Terms & Conditions -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="h4 mb-3">
                            <a data-toggle="collapse" href="#termsCollapse" role="button">Terms & Conditions <i class="fa fa-chevron-down"></i></a>
                        </h3>
                        <div class="collapse" id="termsCollapse">
                            <div class="mt-3">
                                {!! nl2br(e($deal->terms_conditions ?? 'Standard terms apply. Non-refundable.')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                @if($deal->location_address || ($deal->location_latitude && $deal->location_longitude))
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="h4 mb-3">Location</h3>
                        @if($deal->location_address)
                        <p><strong>Address:</strong> {{ $deal->location_address }}</p>
                        @endif
                        <p><strong>City:</strong> {{ $deal->location_city }}, FL {{ $deal->location_zip }}</p>
                        @if($deal->location_latitude && $deal->location_longitude)
                        <div class="mt-3">
                            <iframe 
                                width="100%" 
                                height="300" 
                                frameborder="0" 
                                style="border:0" 
                                src="https://www.google.com/maps/embed/v1/place?key={{ env('GOOGLE_MAPS_API_KEY') }}&q={{ $deal->location_latitude }},{{ $deal->location_longitude }}" 
                                allowfullscreen>
                            </iframe>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Vendor Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="h4 mb-3">About the Vendor</h3>
                        <p><strong>{{ $deal->vendor->first_name }} {{ $deal->vendor->last_name }}</strong></p>
                        @if($deal->vendor->email)
                        <p><a href="mailto:{{ $deal->vendor->email }}">{{ $deal->vendor->email }}</a></p>
                        @endif
                        <a href="{{ route('vendor.show', $deal->vendor_id) }}" class="btn btn-sm btn-outline-primary">View All Deals from {{ $deal->vendor->first_name }}</a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    <!-- Deal Highlights Box -->
                    <div class="card mb-4" style="border: 2px solid #28a745;">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <span style="text-decoration: line-through; color: #999; font-size: 18px;">${{ number_format($deal->regular_price, 2) }}</span>
                                <h2 class="text-success mb-0" style="font-size: 36px; font-weight: bold;">${{ number_format($deal->deal_price, 2) }}</h2>
                                <p class="text-danger mb-3"><strong>Save ${{ number_format($deal->savings_amount, 2) }} ({{ $deal->discount_percentage }}%)</strong></p>
                            </div>

                            <!-- Inventory Status -->
                            <div class="mb-3">
                                @if($inventoryPercent > 25)
                                    <p class="mb-1"><strong>{{ $inventoryRemaining }} spots available</strong></p>
                                @elseif($inventoryPercent > 10)
                                    <p class="mb-1 text-warning"><strong>Only {{ $inventoryRemaining }} spots left!</strong></p>
                                @else
                                    <p class="mb-1 text-danger" style="font-size: 18px;"><strong>Hurry! Only {{ $inventoryRemaining }} spots left!</strong></p>
                                @endif
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar {{ $inventoryPercent < 10 ? 'bg-danger' : ($inventoryPercent < 25 ? 'bg-warning' : 'bg-success') }}" 
                                         role="progressbar" 
                                         style="width: {{ 100 - $inventoryPercent }}%">
                                        {{ number_format(100 - $inventoryPercent, 0) }}% sold
                                    </div>
                                </div>
                            </div>

                            <!-- Countdown Timer -->
                            @if($daysUntilExpiry !== null)
                            <div class="mb-3">
                                <p class="mb-1"><strong>Expires in:</strong></p>
                                <div id="countdown" class="h4 text-danger"></div>
                            </div>
                            @endif

                            @if($deal->auto_paused && $deal->pause_reason === 'capacity_reached')
                                <div class="alert alert-warning text-left">
                                    <strong>Sold out for this month.</strong><br>
                                    This deal will be available again on the 1st of next month.
                                    <div class="mt-2">
                                        <a href="#" class="btn btn-sm btn-primary disabled">Notify Me When Available</a>
                                    </div>
                                </div>
                                <button class="btn btn-secondary btn-lg btn-block mb-2" disabled>Temporarily Unavailable</button>
                            @else
                                <!-- Buy Now Button -->
                                <a href="{{ $deal->stripe_payment_link }}" 
                                   target="_blank" 
                                   class="btn btn-success btn-lg btn-block mb-2"
                                   onclick="trackClick('{{ route('deals.track-click', $deal->slug) }}')">
                                    <i class="fa fa-shopping-cart"></i> BUY NOW - ${{ number_format($deal->deal_price, 2) }}
                                </a>
                                
                                <!-- Claim Purchase Button -->
                                <a href="{{ route('deals.claim-purchase', $deal->slug) }}" 
                                   class="btn btn-outline-primary btn-block mb-2">
                                    <i class="fa fa-check-circle"></i> I've Purchased This Deal
                                </a>
                            @endif

                            <!-- Social Sharing -->
                            <div class="mt-3">
                                <p class="mb-2"><strong>Share this deal:</strong></p>
                                <div class="btn-group btn-group-sm">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/deals/' . $deal->slug)) }}" 
                                       target="_blank" 
                                       class="btn btn-primary">
                                        <i class="fa fa-facebook"></i> Facebook
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/deals/' . $deal->slug)) }}&text={{ urlencode($deal->title) }}" 
                                       target="_blank" 
                                       class="btn btn-info">
                                        <i class="fa fa-twitter"></i> Twitter
                                    </a>
                                    <button onclick="copyLink()" class="btn btn-secondary">
                                        <i class="fa fa-link"></i> Copy Link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================================
    End Deal Detail Area
================================= -->

<script>
// Get or create session ID
function getSessionId() {
    let sessionId = localStorage.getItem('sessionId');
    if (!sessionId) {
        sessionId = Math.random().toString(36).substring(2) + Date.now();
        localStorage.setItem('sessionId', sessionId);
    }
    return sessionId;
}

// Track view on page load
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/track/view', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            deal_id: {{ $deal->id }},
            session_id: getSessionId()
        })
    }).catch(function(error) {
        console.log('View tracking failed:', error);
    });
});

// Countdown timer
@if($daysUntilExpiry !== null)
function updateCountdown() {
    const expiresAt = new Date('{{ $deal->expires_at->toIso8601String() }}').getTime();
    const now = new Date().getTime();
    const distance = expiresAt - now;
    
    if (distance < 0) {
        document.getElementById('countdown').innerHTML = 'EXPIRED';
        return;
    }
    
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    
    document.getElementById('countdown').innerHTML = days + 'd ' + hours + 'h ' + minutes + 'm';
}

updateCountdown();
setInterval(updateCountdown, 60000); // Update every minute
@endif

// Track click
function trackClick(url) {
    // Track via API
    fetch('/api/track/click', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            deal_id: {{ $deal->id }},
            session_id: getSessionId()
        })
    }).catch(function(error) {
        console.log('Click tracking failed:', error);
    });
    
    // Also track via existing route
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
}

// Copy link
function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(function() {
        alert('Link copied to clipboard!');
    });
}

// Lightbox for gallery images
function openLightbox(src) {
    window.open(src, '_blank');
}
</script>

@endsection

