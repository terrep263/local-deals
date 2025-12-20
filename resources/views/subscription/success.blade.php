@extends('app')

@section('head_title', 'Subscription Success | ' . getcong('site_name'))
@section('head_url', Request::url())

@section("content")

<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">Subscription Activated</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li>Subscription Success</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area
================================= --> 

<!-- ================================
    Start Success Area
================================= -->
<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card text-center">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="fa fa-check-circle text-success" style="font-size: 80px;"></i>
                        </div>
                        <h2 class="card-title mb-3">Your Subscription is Now Active!</h2>
                        <p class="card-text text-muted mb-4">
                            Thank you for subscribing. Your subscription has been activated and you now have access to all features included in your plan.
                        </p>
                        
                        @if(isset($session) && $session)
                            <div class="alert alert-info text-left">
                                <strong>Session ID:</strong> {{ $session->id }}<br>
                                <strong>Customer:</strong> {{ $session->customer_email ?? 'N/A' }}
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================================
    End Success Area
================================= -->

@endsection


