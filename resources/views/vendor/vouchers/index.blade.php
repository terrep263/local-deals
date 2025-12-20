@extends('app')

@section('head_title', 'Voucher Management | ' . getcong('site_name'))
@section('head_url', Request::url())

@section("content")

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
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $stats['total'] }}</h3>
                        <p class="mb-0"><strong>Total Vouchers</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ $stats['pending'] }}</h3>
                        <p class="mb-0"><strong>Pending Redemption</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">{{ $stats['redeemed'] }}</h3>
                        <p class="mb-0"><strong>Redeemed</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">{{ $stats['today'] }}</h3>
                        <p class="mb-0"><strong>Sold Today</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Redeem Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-qrcode"></i> Quick Redeem</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vendor.vouchers.lookup') }}" method="POST" class="form-inline">
                    @csrf
                    <div class="form-group mr-3 mb-2">
                        <label for="code" class="sr-only">Voucher Code</label>
                        <input type="text" 
                               class="form-control form-control-lg" 
                               id="code" 
                               name="code" 
                               placeholder="Enter voucher code (e.g., ABC12345)"
                               style="min-width: 280px; text-transform: uppercase;"
                               maxlength="10"
                               required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg mb-2">
                        <i class="fa fa-search"></i> Look Up Voucher
                    </button>
                </form>
                <small class="text-muted">Enter the 8-character confirmation code from the customer's voucher</small>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('vendor.vouchers.index') }}">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search code, email, name..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="redeemed" {{ request('status') == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="deal_id" class="form-control">
                                <option value="">All Deals</option>
                                @foreach($deals as $deal)
                                    <option value="{{ $deal->id }}" {{ request('deal_id') == $deal->id ? 'selected' : '' }}>
                                        {{ Str::limit($deal->title, 25) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <input type="date" name="date_from" class="form-control" placeholder="From" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <input type="date" name="date_to" class="form-control" placeholder="To" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1 mb-2">
                            <button type="submit" class="btn btn-secondary btn-block">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vouchers Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Vouchers ({{ $vouchers->total() }})</h5>
            </div>
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
                                        <strong style="font-family: monospace; font-size: 1.1em;">
                                            {{ $voucher->confirmation_code }}
                                        </strong>
                                    </td>
                                    <td>
                                        <a href="{{ route('deals.show', $voucher->deal->slug ?? '#') }}" target="_blank">
                                            {{ Str::limit($voucher->deal->title ?? 'N/A', 30) }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>{{ $voucher->consumer_name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $voucher->consumer_email }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-success">${{ number_format($voucher->purchase_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        {{ $voucher->purchase_date ? $voucher->purchase_date->format('M d, Y') : 'N/A' }}<br>
                                        <small class="text-muted">{{ $voucher->purchase_date ? $voucher->purchase_date->format('g:i A') : '' }}</small>
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
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('vendor.vouchers.show', $voucher->confirmation_code) }}" 
                                               class="btn btn-info" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if(!$voucher->isRedeemed())
                                                <form action="{{ route('vendor.vouchers.redeem', $voucher->confirmation_code) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Mark this voucher as redeemed?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" title="Mark Redeemed">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No vouchers found.</p>
                                        @if(request()->hasAny(['search', 'status', 'deal_id', 'date_from', 'date_to']))
                                            <a href="{{ route('vendor.vouchers.index') }}">Clear filters</a>
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
