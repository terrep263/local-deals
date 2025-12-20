@extends('app')

@section('head_title', 'Your Voucher - ' . $purchase->deal->title)
@section('content')

<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h2 class="mb-0">Your Deal Voucher</h2>
                    </div>
                    <div class="card-body">
                        <!-- Deal Info -->
                        <div class="text-center mb-4">
                            <h3>{{ $purchase->deal->title }}</h3>
                            <p class="text-muted mb-0">
                                <strong>Vendor:</strong> {{ $purchase->deal->vendor->first_name }} {{ $purchase->deal->vendor->last_name }}
                            </p>
                        </div>
                        
                        <!-- Confirmation Code -->
                        <div class="text-center mb-4 p-4 bg-light rounded">
                            <p class="mb-2"><strong>Confirmation Code:</strong></p>
                            <h1 class="display-4 text-primary font-weight-bold">{{ $purchase->confirmation_code }}</h1>
                            <p class="text-muted small">Show this code to the vendor when redeeming</p>
                        </div>
                        
                        <!-- QR Code -->
                        <div class="text-center mb-4">
                            <div id="qrcode" class="d-inline-block p-3 bg-white border rounded"></div>
                        </div>
                        
                        <!-- Purchase Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date->format('F d, Y') }}</p>
                                <p><strong>Purchase Amount:</strong> ${{ number_format($purchase->purchase_amount, 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Expires:</strong> {{ $purchase->deal->expires_at->format('F d, Y') }}</p>
                                <p><strong>Email:</strong> {{ $purchase->consumer_email }}</p>
                            </div>
                        </div>
                        
                        <!-- Vendor Info -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Vendor Information</h5>
                                <p class="mb-1"><strong>{{ $purchase->deal->vendor->first_name }} {{ $purchase->deal->vendor->last_name }}</strong></p>
                                @if($purchase->deal->location_address)
                                <p class="mb-1">{{ $purchase->deal->location_address }}</p>
                                @endif
                                <p class="mb-0">{{ $purchase->deal->location_city }}, FL {{ $purchase->deal->location_zip }}</p>
                                @if($purchase->deal->vendor->phone)
                                <p class="mb-0"><strong>Phone:</strong> {{ $purchase->deal->vendor->phone }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Terms -->
                        @if($purchase->deal->terms_conditions)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Terms & Conditions</h5>
                                <div>{!! nl2br(e($purchase->deal->terms_conditions)) !!}</div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Instructions -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">How to Redeem:</h6>
                            <ol class="mb-0">
                                <li>Visit the vendor at the location shown above</li>
                                <li>Show this voucher (screenshot or print)</li>
                                <li>Provide your confirmation code: <strong>{{ $purchase->confirmation_code }}</strong></li>
                                <li>Vendor will verify and redeem your deal</li>
                            </ol>
                        </div>
                        
                        <!-- Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="{{ route('vouchers.pdf', $purchase->confirmation_code) }}" class="btn btn-primary btn-lg" target="_blank">
                                <i class="fa fa-download"></i> Download PDF
                            </a>
                            <button onclick="window.print()" class="btn btn-secondary btn-lg">
                                <i class="fa fa-print"></i> Print Voucher
                            </button>
                            <button type="button" class="btn btn-info btn-lg" data-bs-toggle="modal" data-bs-target="#emailModal">
                                <i class="fa fa-envelope"></i> Email to Me
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vouchers.email', $purchase->confirmation_code) }}" method="POST">
                @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $purchase->consumer_email) }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Send Email</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
// Generate QR Code
new QRCode(document.getElementById("qrcode"), {
    text: "{{ $purchase->confirmation_code }}",
    width: 200,
    height: 200
});
</script>

<style>
@media print {
    .btn, .modal, .alert-info { display: none !important; }
    .card { border: 2px solid #000 !important; }
}
</style>

@endsection


