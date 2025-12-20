@extends('app')

@section('head_title', $vendor->first_name . ' ' . $vendor->last_name . ' - Vendor Profile')
@section('content')

<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="h3 mb-3">{{ $vendor->first_name }} {{ $vendor->last_name }}</h2>
                        @if($vendor->phone)
                        <p class="mb-2"><i class="fa fa-phone"></i> {{ $vendor->phone }}</p>
                        @endif
                        @if($vendor->email)
                        <p class="mb-2"><i class="fa fa-envelope"></i> {{ $vendor->email }}</p>
                        @endif
                        <p class="text-muted small mb-0">Member since {{ $memberSince->format('F Y') }}</p>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Statistics</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><strong>Total Deals:</strong> {{ $totalDeals }}</li>
                            <li class="mb-2"><strong>Active Deals:</strong> {{ $activeDeals->count() }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <h2 class="mb-4">Active Deals</h2>
                
                @if($activeDeals->count() > 0)
                <div class="row">
                    @foreach($activeDeals as $deal)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 hover-y">
                            <a href="{{ route('deals.show', $deal->slug) }}" class="card-image position-relative">
                                <img src="{{ $deal->getFeaturedImageThumbUrl() ?? asset('assets/images/img-loading.jpg') }}" 
                                     class="card-img-top lazy" 
                                     alt="{{ $deal->title }}"
                                     loading="lazy">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-danger">{{ $deal->discount_percentage }}% OFF</span>
                                </div>
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('deals.show', $deal->slug) }}" class="text-decoration-none">{{ Str::limit($deal->title, 50) }}</a>
                                </h5>
                                <p class="text-muted small mb-2">
                                    <i class="fal fa-map-marker-alt"></i> {{ $deal->location_city }}, FL
                                </p>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-decoration-line-through text-muted me-2">${{ number_format($deal->regular_price, 2) }}</span>
                                    <span class="h5 text-primary mb-0">${{ number_format($deal->deal_price, 2) }}</span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('deals.show', $deal->slug) }}" class="btn btn-primary w-100">View Deal</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info">
                    <h5>No active deals</h5>
                    <p>This vendor doesn't have any active deals at this time. Check back soon!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection


