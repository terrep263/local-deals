@extends('app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle"></i> Payment Successful!
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    <h3 class="mt-4">Thank You for Your Purchase!</h3>
                    <p class="lead">Your voucher is being generated and will be emailed to you shortly.</p>
                    
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-envelope"></i>
                        Check your email for your voucher with QR code.
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-home"></i> Return Home
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-search"></i> Browse More Deals
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
