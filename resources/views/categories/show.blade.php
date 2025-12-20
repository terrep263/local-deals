@extends('app')

@section('head_title', $category->category_name . ' Deals - ' . getcong('site_name'))
@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1>{{ $category->category_name }}</h1>
            <p class="text-muted">{{ $dealCount }} active deal(s) in this category</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('category.show', $category->category_slug) }}" method="GET">
                    
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <select class="form-select" name="city">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ZIP Code</label>
                        <input type="text" class="form-control" name="zip" value="{{ request('zip') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Price Range</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="number" class="form-control" name="price_min" value="{{ request('price_min') }}" placeholder="Min $" step="0.01">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" name="price_max" value="{{ request('price_max') }}" placeholder="Max $" step="0.01">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Discount</label>
                        <select class="form-select" name="discount">
                            <option value="">Any</option>
                            <option value="25" {{ request('discount') == '25' ? 'selected' : '' }}>25%+</option>
                            <option value="50" {{ request('discount') == '50' ? 'selected' : '' }}>50%+</option>
                            <option value="75" {{ request('discount') == '75' ? 'selected' : '' }}>75%+</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Sort By</label>
                        <select class="form-select" name="sort">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>Ending Soon</option>
                            <option value="best_discount" {{ request('sort') == 'best_discount' ? 'selected' : '' }}>Best Discount</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-2">Apply Filters</button>
                    <a href="{{ route('category.show', $category->category_slug) }}" class="btn btn-outline-secondary w-100">Clear</a>
                    
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            @if($deals->count() > 0)
            <div class="row">
                @foreach($deals as $deal)
                <div class="col-md-6 col-lg-4 mb-4">
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
            
            <div class="mt-4">
                {{ $deals->appends(request()->query())->links() }}
            </div>
            @else
            <div class="alert alert-info">
                <h5>No deals found</h5>
                <p>Try adjusting your filters or <a href="{{ route('deals.index') }}">browse all deals</a>.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection


