@extends('admin.admin_app')

@section('content')
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Vendors</h1>
        <h2 class="h5 text-white-op animated zoomIn">Create and manage vendor accounts</h2>
    </div>
</div>

<div class="content content-narrow">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('generated_password'))
        <div class="alert alert-info">
            <strong>Generated Password:</strong> {{ session('generated_password') }}<br>
            Show this once to the admin. The vendor also received it by email.
        </div>
    @endif

    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Filters</h3>
            <div class="block-options">
                <a href="{{ route('admin.vendors.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Create New Vendor</a>
            </div>
        </div>
        <div class="block-content">
            <form method="GET" action="{{ route('admin.vendors.index') }}" class="row">
                <div class="col-md-2">
                    <label class="css-input switch switch-sm switch-primary push-10-t">
                        <input type="checkbox" name="founders" value="1" {{ request('founders') ? 'checked' : '' }}><span></span> Founders only
                    </label>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tier</label>
                        <select name="tier" class="form-control">
                            <option value="">All tiers</option>
                            @foreach($tiers as $value => $label)
                                <option value="{{ $value }}" {{ request('tier') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="">All categories</option>
                            @foreach($categories as $value => $label)
                                <option value="{{ $value }}" {{ request('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Business or email" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Vendors ({{ $vendors->total() }})</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-hover table-vcenter">
                    <thead>
                        <tr>
                            <th>Business</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Subscription</th>
                            <th>Vouchers (used/limit)</th>
                            <th>Onboarding</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            @php
                                $used = $vendor->vouchers_used_this_month ?? 0;
                                $limit = $vendor->monthly_voucher_limit ?: 0;
                                $pct = $limit > 0 ? min(100, round(($used / $limit) * 100)) : 0;
                                $tierLabel = $tiers[$vendor->subscription_tier] ?? ucfirst($vendor->subscription_tier);
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $vendor->business_name }}</strong><br>
                                    <small>{{ $vendor->business_city }}, {{ $vendor->business_state }}</small>
                                </td>
                                <td>{{ $vendor->user->email ?? 'N/A' }}</td>
                                <td>{{ $categories[$vendor->business_category] ?? ucfirst($vendor->business_category) }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $tierLabel }}</span>
                                    @if($vendor->is_founder)
                                        <span class="badge badge-warning">Founder</span>
                                    @endif
                                </td>
                                <td style="min-width:180px;">
                                    <div class="clearfix">
                                        <span class="pull-left">{{ $used }}</span>
                                        <span class="pull-right">{{ $limit ?: '∞' }}</span>
                                    </div>
                                    <div class="progress progress-mini">
                                        <div class="progress-bar progress-bar-success" style="width: {{ $pct }}%;"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($vendor->is_onboarded)
                                        <span class="label label-success">Onboarded</span>
                                    @else
                                        <span class="label label-default">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
                                    <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Deactivate this vendor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No vendors found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $vendors->links() }}
        </div>
    </div>
</div>
@endsection