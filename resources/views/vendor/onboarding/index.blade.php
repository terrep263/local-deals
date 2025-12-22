@extends('app')

@section('head_title', 'Vendor Onboarding | ' . getcong('site_name'))
@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', 'Vendor Onboarding')
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => 'Onboarding', 'url' => '']]))

@section('content')

@include('common.page-hero-header', ['title' => 'Vendor Onboarding'])

<section class="dashboard-area pt-40 pb-60">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Onboarding Checklist</h4>
                        <div class="progress mb-3" style="height: 18px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $progress }}%;">
                                {{ $progress }}%
                            </div>
                        </div>

                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Step 1: Connect Stripe Account</strong>
                                    <p class="mb-0 text-muted">Connect to Stripe to accept payments and receive payouts.</p>
                                </div>
                                <div>
                                    @if($stripeConnected)
                                        <span class="badge badge-success">Connected {{ optional($vendor->stripe_connected_at)->format('M d, Y') }}</span>
                                    @else
                                        <a href="{{ route('vendor.onboarding.stripe.connect') }}" class="btn btn-primary">Connect Stripe</a>
                                    @endif
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Step 2: Complete Business Profile</strong>
                                    <p class="mb-0 text-muted">Add your description, logo, and business hours.</p>
                                </div>
                                <div>
                                    @if($profileCompleted)
                                        <span class="badge badge-success">Profile Complete</span>
                                    @else
                                        <a href="{{ route('vendor.onboarding.profile') }}" class="btn btn-secondary">Complete Profile</a>
                                    @endif
                                </div>
                            </li>
                        </ul>

                        <div class="alert alert-info mt-3 mb-0">
                            You must complete both steps before creating deals.
                        </div>

                        @if($stripeConnected && $profileCompleted)
                            <div class="alert alert-success mt-3 mb-0">
                                Onboarding complete! Ready to create deals.
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('vendor.deals.index') }}" class="btn btn-success">Go to Dashboard</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

