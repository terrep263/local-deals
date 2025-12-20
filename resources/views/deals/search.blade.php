@extends('app')

@section('head_title', 'Search Results for "' . $query . '" - ' . getcong('site_name'))
@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Search Results for "{{ $query }}"</h2>
            
            @if($deals->count() > 0)
            <p class="text-muted mb-4">Found {{ $deals->total() }} deal(s)</p>
            
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
                                <i class="fal fa-store"></i> {{ $deal->vendor->first_name ?? 'Vendor' }}
                                <br>
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
                {{ $deals->appends(['q' => $query])->links() }}
            </div>
            @else
            <div class="alert alert-info">
                <h5>No deals found</h5>
                <p>Try a different search term or <a href="{{ route('deals.index') }}">browse all deals</a>.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection


