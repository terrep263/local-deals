@extends('app')

@section('head_title', 'Subscription Success | ' . getcong('site_name'))
@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', 'Subscription Success')
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => 'Success', 'url' => '']]))

@section("content")

@include('common.page-hero-header') 

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


