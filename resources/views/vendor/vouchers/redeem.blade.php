@extends('app')

@section('head_title', 'Redeem Voucher | ' . getcong('site_name'))
@section('head_url', Request::url())

@section('content')

<!-- Breadcrumb -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">Redeem Voucher</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('vendor.vouchers.index') }}">Vouchers</a></li>
                <li>Redeem</li>
            </ul>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="dashboard-area bg-gray section_item_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ Session::get('error') }}
                    </div>
                @endif

                @if(Session::has('flash_message'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ Session::get('flash_message') }}
                    </div>
                @endif

                <!-- Lookup Form -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-search"></i> Look Up Voucher</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('vendor.vouchers.lookup') }}">
                            @csrf
                            <div class="form-group">
                                <label for="code"><strong>Enter Confirmation Code</strong></label>
                                <div class="input-group input-group-lg">
                                    <input type="text" 
                                           name="code" 
                                           id="code" 
                                           class="form-control text-center" 
                                           placeholder="e.g. ABC12345"
                                           style="font-size: 24px; letter-spacing: 3px; font-weight: bold; text-transform: uppercase;"
                                           value="{{ $code ?? old('code') }}"
                                           maxlength="12"
                                           autofocus
                                           required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Look Up
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Enter the 8-character confirmation code from the customer's voucher</small>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Voucher Details (if found) -->
                @isset($voucher)
                <div class="card">
                    <div class="card-header {{ $voucher->isRedeemed() ? 'bg-secondary' : 'bg-success' }} text-white">
                        <h5 class="mb-0">
                            @if($voucher->isRedeemed())
                                <i class="fa fa-check-circle"></i> Voucher Already Redeemed
                            @else
                                <i class="fa fa-ticket-alt"></i> Voucher Found - Ready to Redeem
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">VOUCHER DETAILS</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Code:</strong></td>
                                        <td><code style="font-size: 18px;">{{ $voucher->confirmation_code }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Deal:</strong></td>
                                        <td>{{ $voucher->deal->title }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Amount Paid:</strong></td>
                                        <td><span class="text-success font-weight-bold">${{ number_format($voucher->purchase_amount, 2) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Original Value:</strong></td>
                                        <td>${{ number_format($voucher->deal->regular_price, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">CUSTOMER INFO</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ $voucher->consumer_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $voucher->consumer_email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Purchased:</strong></td>
                                        <td>{{ $voucher->purchase_date->format('M d, Y \a\t g:i A') }}</td>
                                    </tr>
                                    @if($voucher->isRedeemed())
                                    <tr>
                                        <td><strong>Redeemed:</strong></td>
                                        <td class="text-success">{{ $voucher->redeemed_at->format('M d, Y \a\t g:i A') }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        @if(!$voucher->isRedeemed())
                        <hr>
                        <form method="POST" action="{{ route('vendor.vouchers.mark-redeemed', $voucher->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="notes">Notes (optional)</label>
                                <input type="text" name="notes" id="notes" class="form-control" placeholder="Add any notes about this redemption...">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('vendor.vouchers.redeem') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-arrow-left"></i> Look Up Another
                                </a>
                                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Confirm redemption of voucher {{ $voucher->confirmation_code }}?');">
                                    <i class="fa fa-check-circle"></i> Mark as Redeemed
                                </button>
                            </div>
                        </form>
                        @else
                        <hr>
                        <div class="text-center">
                            <p class="text-muted">This voucher was already redeemed and cannot be used again.</p>
                            <a href="{{ route('vendor.vouchers.redeem') }}" class="btn btn-primary">
                                <i class="fa fa-search"></i> Look Up Another Voucher
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endisset

                <!-- Quick Tips -->
                @if(!isset($voucher))
                <div class="card">
                    <div class="card-body">
                        <h6><i class="fa fa-lightbulb text-warning"></i> Quick Tips</h6>
                        <ul class="mb-0">
                            <li>Ask the customer for their <strong>8-character confirmation code</strong></li>
                            <li>The code is on their voucher email or PDF</li>
                            <li>Verify the customer's name matches before redeeming</li>
                            <li>Once redeemed, a voucher cannot be used again</li>
                        </ul>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

<style>
#code {
    font-family: 'Courier New', monospace;
}
#code::placeholder {
    letter-spacing: normal;
    font-size: 18px;
}
</style>

@endsection
