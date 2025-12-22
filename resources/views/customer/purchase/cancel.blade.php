@extends('app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Payment Cancelled
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="fas fa-times-circle text-warning" style="font-size: 5rem;"></i>
                    <h3 class="mt-4">Payment Was Cancelled</h3>
                    <p class="lead">No charges were made to your account.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-search"></i> Browse Deals
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
