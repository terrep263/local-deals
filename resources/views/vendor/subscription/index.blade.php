@extends('app')

@section('head_title', 'Manage Subscription | ' . getcong('site_name'))
@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', 'Subscription')
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => 'Subscription', 'url' => '']]))

@section('content')

@include('common.page-hero-header', ['title' => 'Subscription'])

<section class="dashboard-area pt-40 pb-60">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Current Tier</h4>
                        @php
                            $current = $tiers[$currentTier] ?? null;
                            $price = $current ? $current['price'] : 0;
                            $limit = $currentLimit;
                        @endphp
                        <h2 class="mb-0">{{ $current['name'] ?? ucfirst(str_replace('_',' ',$currentTier)) }}</h2>
                        <p class="text-muted mb-1">${{ number_format($price, 0) }}/mo</p>
                        <p class="mb-2">Voucher limit: {{ $limit >= 999999 ? 'Unlimited' : number_format($limit) }}</p>
                        <span class="badge badge-primary">Current Plan</span>
                        @if($isFounder)
                            <span class="badge badge-warning">Founder Pricing - Locked Forever</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Available Tiers</h4>
                        <p class="text-muted">All plans include unlimited active deals. Upgrades take effect immediately.</p>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tier</th>
                                        <th>Monthly Cost</th>
                                        <th>Voucher Limit</th>
                                        <th>Features</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $features = [
                                            'QR voucher system',
                                            'Email notifications',
                                            'Analytics dashboard',
                                            'Customer database',
                                            'Platform support',
                                        ];
                                    @endphp

                                    @foreach($tiers as $key => $tier)
                                        @php
                                            $isCurrent = $currentTier === $key;
                                            $cost = $tier['price'] > 0 ? '$' . number_format($tier['price'], 0) . '/mo' : '$0';
                                            $limitText = $tier['limit'] >= 999999 ? 'Unlimited' : number_format($tier['limit']);
                                        @endphp
                                        <tr>
                                            <td>{{ $tier['name'] }}</td>
                                            <td>{{ $cost }}</td>
                                            <td>{{ $limitText }}</td>
                                            <td>
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($features as $feat)
                                                        <li><i class="fa fa-check text-success"></i> {{ $feat }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                @if($isCurrent)
                                                    <span class="badge badge-primary">Current</span>
                                                @else
                                                    <form action="{{ route('vendor.subscription.upgrade') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="tier" value="{{ $key }}">
                                                        <button type="submit" class="btn btn-sm btn-primary">Upgrade</button>
                                                    </form>
                                                    @if($isFounder && $currentTier === 'founder_growth' && $key === 'founder_free')
                                                        <form action="{{ route('vendor.subscription.downgrade') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="tier" value="{{ $key }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Downgrade</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

