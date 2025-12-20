@extends('app')

@section('head_title', 'Claim Purchase - ' . $deal->title)
@section('content')

<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Claim Your Purchase</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>{{ $deal->title }}</h5>
                            <p class="text-muted mb-0">
                                <strong>Price:</strong> ${{ number_format($deal->deal_price, 2) }}
                            </p>
                        </div>

                        @if($deal->auto_paused && $deal->pause_reason === 'capacity_reached')
                            <div class="alert alert-warning">
                                <strong>Sold out for this month.</strong><br>
                                This deal will be available again on the 1st of next month.
                            </div>
                            <a href="{{ route('deals.show', $deal->slug) }}" class="btn btn-secondary btn-block">Back to Deal</a>
                        @else
                        
                        @if(session('error_flash_message'))
                        <div class="alert alert-danger">
                            {{ session('error_flash_message') }}
                        </div>
                        @endif
                        
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        
                        <form action="{{ route('deals.process-claim', $deal->slug) }}" method="POST">
                            @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="Enter the email you used at checkout">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Enter the same email address you used when purchasing through Stripe.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name (Optional)</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Your name">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('confirmed') is-invalid @enderror" 
                                       type="checkbox" 
                                       id="confirmed" 
                                       name="confirmed" 
                                       value="1" 
                                       required>
                                <label class="form-check-label" for="confirmed">
                                    I have completed payment through Stripe <span class="text-danger">*</span>
                                </label>
                                @error('confirmed')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Note:</strong> After completing your purchase on Stripe, return here to claim your deal. 
                            You'll receive a confirmation email with your voucher code.
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Claim My Purchase</button>
                            <a href="{{ route('deals.show', $deal->slug) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                        
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


