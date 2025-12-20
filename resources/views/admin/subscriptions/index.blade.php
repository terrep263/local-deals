@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Subscription Management</h1>
        <h2 class="h5 text-white-op animated zoomIn">Manage all vendor subscriptions</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    @if(Session::has('flash_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('flash_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(Session::has('error_flash_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error_flash_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="block block-link-hover1 text-center">
                <div class="block-content block-content-full bg-primary">
                    <div class="font-size-h3 font-w600 text-white">{{ $stats['active'] }}</div>
                    <div class="font-size-sm font-w600 text-white-op">Active Subscriptions</div>
                </div>
                <div class="block-content block-content-full block-content-mini">
                    <strong>${{ number_format($stats['mrr'], 2) }}</strong> Monthly Recurring Revenue
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="block block-link-hover1 text-center">
                <div class="block-content block-content-full bg-danger">
                    <div class="font-size-h3 font-w600 text-white">{{ $stats['churn'] }}</div>
                    <div class="font-size-sm font-w600 text-white-op">Canceled This Month</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="block block-link-hover1 text-center">
                <div class="block-content block-content-full bg-warning">
                    <div class="font-size-h3 font-w600 text-white">{{ $stats['past_due'] ?? 0 }}</div>
                    <div class="font-size-sm font-w600 text-white-op">Past Due</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="block block-link-hover1 text-center">
                <div class="block-content block-content-full bg-success">
                    <div class="font-size-h3 font-w600 text-white">{{ $stats['new_this_month'] }}</div>
                    <div class="font-size-sm font-w600 text-white-op">New This Month</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Subscriptions</h3>
        </div>
        <div class="block-content">
            <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="row mb-4">
                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                    <label class="sr-only" for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Past Due</option>
                        <option value="trialing" {{ request('status') == 'trialing' ? 'selected' : '' }}>Trialing</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                    <label class="sr-only" for="tier">Tier</label>
                    <select id="tier" name="tier" class="form-control">
                        <option value="">All Tiers</option>
                        <option value="starter" {{ request('tier') == 'starter' ? 'selected' : '' }}>Starter</option>
                        <option value="basic" {{ request('tier') == 'basic' ? 'selected' : '' }}>Basic</option>
                        <option value="pro" {{ request('tier') == 'pro' ? 'selected' : '' }}>Pro</option>
                        <option value="enterprise" {{ request('tier') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                    <label class="sr-only" for="search">Search</label>
                    <input id="search" type="text" name="search" class="form-control" placeholder="Search by email or name" value="{{ request('search') }}">
                </div>
                <div class="col-lg-2 col-md-12 d-flex align-items-center mb-2">
                    <button type="submit" class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
            <table class="table table-bordered table-striped table-vcenter">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Package Tier</th>
                        <th>Status</th>
                        <th>Current Period End</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        @php
                            $statusBadge = [
                                'active' => ['class' => 'badge-success', 'text' => 'Active'],
                                'canceled' => ['class' => 'badge-danger', 'text' => 'Canceled'],
                                'past_due' => ['class' => 'badge-warning', 'text' => 'Past Due'],
                                'trialing' => ['class' => 'badge-info', 'text' => 'Trialing'],
                            ];
                            $status = $statusBadge[$subscription->status] ?? ['class' => 'badge-secondary', 'text' => ucfirst($subscription->status)];
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $subscription->user->first_name }} {{ $subscription->user->last_name }}</strong>
                            </td>
                            <td>{{ $subscription->user->email }}</td>
                            <td>
                                <span class="badge badge-primary">{{ ucfirst($subscription->package_tier) }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                                @if($subscription->cancel_at_period_end)
                                    <br><small class="text-muted">Cancels at period end</small>
                                @endif
                            </td>
                            <td>
                                @if($subscription->current_period_end)
                                    {{ $subscription->current_period_end->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i> View
                                </a>
                                <a href="https://dashboard.stripe.com/subscriptions/{{ $subscription->stripe_subscription_id }}" target="_blank" class="btn btn-sm btn-secondary">
                                    <i class="fa fa-external-link"></i> Stripe
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No subscriptions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

