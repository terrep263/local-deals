@php
    $user = Auth::user();
    $vendor = $user?->vendorProfile;
    if (!$vendor) {
        return;
    }

    $tierNames = [
        'founder_free' => 'Founder Free',
        'founder_growth' => 'Founder Growth',
        'basic' => 'Basic',
        'pro' => 'Pro',
        'enterprise' => 'Enterprise',
    ];

    $prices = [
        'founder_free' => 0,
        'founder_growth' => 35,
        'basic' => 49,
        'pro' => 99,
        'enterprise' => 199,
    ];

    $limit = $vendor->monthly_voucher_limit ?: 0;
    $used = $vendor->vouchers_used_this_month ?: 0;
    $pct = $limit > 0 ? min(100, round(($used / $limit) * 100)) : 0;
    $progressClass = $pct >= 100 ? 'bg-danger' : ($pct >= 80 ? 'bg-warning' : 'bg-success');
    $daysToReset = now()->endOfMonth()->diffInDays();
@endphp

<div class="card mb-3">
    <div class="card-body">
        <h4 class="card-title mb-2">Vendor Subscription</h4>
        <p class="mb-1"><strong>Tier:</strong> {{ $tierNames[$vendor->subscription_tier] ?? ucfirst($vendor->subscription_tier) }}</p>
        <p class="mb-1"><strong>Cost:</strong> ${{ number_format($prices[$vendor->subscription_tier] ?? 0, 0) }}/mo</p>
        <p class="mb-1"><strong>Vouchers:</strong> {{ number_format($used) }} of {{ $limit >= 999999 ? 'Unlimited' : number_format($limit) }} used this month</p>
        <div class="progress" style="height: 16px;">
            <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $pct }}%;">
                {{ $pct }}%
            </div>
        </div>
        @if($pct >= 100)
            <span class="badge badge-danger mt-2">Capacity reached!</span>
        @elseif($pct >= 80)
            <span class="badge badge-warning mt-2">Running low!</span>
        @endif
        <p class="mt-2 mb-0 text-muted">Resets in {{ $daysToReset }} days</p>
        <div class="mt-2">
            <a href="{{ route('vendor.subscription.index') }}" class="btn btn-sm btn-primary">Manage Subscription</a>
        </div>
    </div>
</div>

