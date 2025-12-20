@extends('app')

@section('head_title', 'Voucher Management | ' . getcong('site_name'))
@section('head_url', Request::url())

@section('content')

<!-- Breadcrumb -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">Voucher Management</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li>Vouchers</li>
            </ul>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="dashboard-area bg-gray section_item_padding">
    <div class="container">
        @if(Session::has('flash_message'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('flash_message') }}
            </div>
        @endif

        @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('error') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-2 col-sm-4 col-6 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h3 class="text-primary mb-1">{{ $stats['total'] }}</h3>
                        <p class="mb-0 small"><strong>Total Vouchers</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-6 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h3 class="text-warning mb-1">{{ $stats['pending'] }}</h3>
                        <p class="mb-0 small"><strong>Pending</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-6 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h3 class="text-success mb-1">{{ $stats['redeemed'] }}</h3>
                        <p class="mb-0 small"><strong>Redeemed</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-6 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h3 class="text-info mb-1">{{ $stats['today'] }}</h3>
                        <p class="mb-0 small"><strong>Sold Today</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-6 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body py-3">
                        <h3 class="text-success mb-1">{{ $stats['redeemed_today'] }}</h3>
                        <p class="mb-0 small"><strong>Redeemed Today</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="mb-2">All Vouchers</h3>
                    <a href="{{ route('vendor.vouchers.redeem') }}" class="btn btn-primary mb-2">
                        <i class="fa fa-qrcode"></i> Redeem Voucher
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('vendor.vouchers.index') }}" class="row">
                    <div class="col-md-2 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search code/email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="redeemed" {{ request('status') == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="deal_id" class="form-control">
                            <option value="">All Deals</option>
                            @foreach($deals as $deal)
                                <option value="{{ $deal->id }}" {{ request('deal_id') == $deal->id ? 'selected' : '' }}>{{ Str::limit($deal->title, 25) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" name="date_from" class="form-control" placeholder="From" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" name="date_to" class="form-control" placeholder="To" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-secondary btn-block">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vouchers Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Deal</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Purchased</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vouchers as $voucher)
                                <tr>
                                    <td>
                                        <code style="font-size: 14px; font-weight: bold; background: #f0f0f0; padding: 4px 8px; border-radius: 4px;">
                                            {{ $voucher->confirmation_code }}
                                        </code>
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($voucher->deal->title ?? 'N/A', 30) }}</strong>
                                    </td>
                                    <td>
                                        {{ $voucher->consumer_name }}<br>
                                        <small class="text-muted">{{ $voucher->consumer_email }}</small>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($voucher->purchase_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        {{ $voucher->purchase_date->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $voucher->purchase_date->format('g:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($voucher->isRedeemed())
                                            <span class="badge badge-success">Redeemed</span><br>
                                            <small class="text-muted">{{ $voucher->redeemed_at->format('M d, Y') }}</small>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$voucher->isRedeemed())
                                            <form action="{{ route('vendor.vouchers.mark-redeemed', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this voucher as redeemed?');">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Mark Redeemed">
                                                    <i class="fa fa-check"></i> Redeem
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted"><i class="fa fa-check-circle"></i> Done</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No vouchers found.</p>
                                        @if(request()->hasAny(['search', 'status', 'deal_id', 'date_from', 'date_to']))
                                            <a href="{{ route('vendor.vouchers.index') }}" class="btn btn-outline-secondary btn-sm mt-2">Clear Filters</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $vouchers->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
