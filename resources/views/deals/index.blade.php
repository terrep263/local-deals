@extends('app')

@section('head_title', 'Browse Deals - ' . getcong('site_name'))
@section('head_description', 'Browse all local deals in Lake County, Florida')

@section('content')

<div class="container my-5">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('deals.index') }}" method="GET" id="filter-form">
                    
                    <!-- Category Filter -->
                    <div class="mb-4">
                        <h6 class="mb-3">Category</h6>
                        @foreach($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="category[]" value="{{ $category->id }}" 
                                id="cat-{{ $category->id }}"
                                {{ in_array($category->id, (array)request('category')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat-{{ $category->id }}">
                                {{ $category->category_name }} ({{ $category->deals_count ?? 0 }})
                            </label>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Location Filter -->
                    <div class="mb-4">
                        <h6 class="mb-3">City</h6>
                        <select class="form-select" name="city">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="mb-3">ZIP Code</h6>
                        <input type="text" class="form-control" name="zip" value="{{ request('zip') }}" placeholder="Enter ZIP">
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-4">
                        <h6 class="mb-3">Price Range</h6>
                        <div class="row">
                            <div class="col-6">
                                <input type="number" class="form-control" name="price_min" value="{{ request('price_min') }}" placeholder="Min $" step="0.01">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" name="price_max" value="{{ request('price_max') }}" placeholder="Max $" step="0.01">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Discount Filter -->
                    <div class="mb-4">
                        <h6 class="mb-3">Discount</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="discount" value="" id="discount-any" {{ !request('discount') ? 'checked' : '' }}>
                            <label class="form-check-label" for="discount-any">Any</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="discount" value="25" id="discount-25" {{ request('discount') == '25' ? 'checked' : '' }}>
                            <label class="form-check-label" for="discount-25">25%+</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="discount" value="50" id="discount-50" {{ request('discount') == '50' ? 'checked' : '' }}>
                            <label class="form-check-label" for="discount-50">50%+</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="discount" value="75" id="discount-75" {{ request('discount') == '75' ? 'checked' : '' }}>
                            <label class="form-check-label" for="discount-75">75%+</label>
                        </div>
                    </div>
                    
                    <!-- Availability Filter -->
                    <div class="mb-4">
                        <h6 class="mb-3">Availability</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="availability" value="" id="avail-any" {{ !request('availability') ? 'checked' : '' }}>
                            <label class="form-check-label" for="avail-any">Any</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="availability" value="high" id="avail-high" {{ request('availability') == 'high' ? 'checked' : '' }}>
                            <label class="form-check-label" for="avail-high">More than 50%</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="availability" value="low" id="avail-low" {{ request('availability') == 'low' ? 'checked' : '' }}>
                            <label class="form-check-label" for="avail-low">Less than 10 left</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-2">Apply Filters</button>
                    <a href="{{ route('deals.index') }}" class="btn btn-outline-secondary w-100">Clear Filters</a>
                    
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Deals Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">All Deals</h2>
                <div>
                    <select class="form-select" id="sort-select" onchange="updateSort(this.value)">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>Ending Soon</option>
                        <option value="best_discount" {{ request('sort') == 'best_discount' ? 'selected' : '' }}>Best Discount</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
            </div>
            
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
                            @if($deal->category)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-secondary">{{ $deal->category->category_name }}</span>
                            </div>
                            @endif
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('deals.show', $deal->slug) }}" class="text-decoration-none">{{ Str::limit($deal->title, 50) }}</a>
                            </h5>
                            <p class="text-muted small mb-2">
                                <i class="fal fa-store"></i> {{ $deal->vendor->first_name ?? 'Vendor' }}
                                <br>
                                <i class="fal fa-map-marker-alt"></i> {{ $deal->location_city }}, FL
                            </p>
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-decoration-line-through text-muted me-2">${{ number_format($deal->regular_price, 2) }}</span>
                                <span class="h5 text-primary mb-0">${{ number_format($deal->deal_price, 2) }}</span>
                            </div>
                            @if($deal->inventory_total > 0)
                            <p class="small mb-2">
                                @if($deal->inventory_remaining <= 10 && $deal->inventory_remaining > 0)
                                    <span class="badge bg-warning">Only {{ $deal->inventory_remaining }} left!</span>
                                @else
                                    <span class="text-muted">{{ $deal->inventory_sold }} / {{ $deal->inventory_total }} sold</span>
                                @endif
                            </p>
                            @endif
                            @if($deal->expires_at)
                            <p class="small text-muted mb-0">
                                <i class="fal fa-clock"></i> Ends in {{ $deal->getDaysUntilExpiry() }} days
                            </p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('deals.show', $deal->slug) }}" class="btn btn-primary w-100">View Deal</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
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

<script>
function updateSort(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', value);
    window.location.href = url.toString();
}
</script>

@endsection


