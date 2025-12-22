@extends('app')

@section('head_title', 'Create Deal | ' . getcong('site_name'))
@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', 'Create Deal')
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => 'Create Deal', 'url' => '']]))

@section("content")

@include('common.page-hero-header') 

<!-- ================================
    Start Create Deal Area
================================= -->
<section class="add-listing-area bg-gray section_item_padding">
    <div class="container">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul style="list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vendor.deals.store') }}" method="POST" enctype="multipart/form-data" id="deal-form">
            @csrf

            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Basic Information</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Deal Title *</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required maxlength="255" placeholder="e.g. Yearly Lawn Care Package">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Category *</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Description *</label>
                                <textarea name="description" class="form-control elm1_editor" rows="8" required minlength="50">{{ old('description') }}</textarea>
                                <small class="text-muted">Minimum 50 characters. Describe what's included in this deal.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Images</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Featured Image *</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*" required>
                                <small class="text-muted">Max 2MB. Will be resized to 1200x800px.</small>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Gallery Images (Optional, Max 5)</label>
                                <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">Max 5 images, 2MB each.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Pricing</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Regular Price *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" name="regular_price" class="form-control" step="0.01" min="0.01" value="{{ old('regular_price') }}" required id="regular_price">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Deal Price *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" name="deal_price" class="form-control" step="0.01" min="0.01" value="{{ old('deal_price') }}" required id="deal_price">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="alert alert-info" id="pricing-preview">
                                <strong>Discount:</strong> <span id="discount-percent">0</span>% | 
                                <strong>Savings:</strong> $<span id="savings-amount">0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Inventory</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Total Spots Available *</label>
                                <input type="number" name="inventory_total" class="form-control" min="1" value="{{ old('inventory_total') }}" required id="inventory_total">
                                @if($packageFeatures)
                                    <small class="text-muted">
                                        Your plan allows max 
                                        @if($packageFeatures->inventory_cap_per_deal == -1)
                                            <strong>unlimited</strong> spots per deal.
                                        @else
                                            <strong>{{ $packageFeatures->inventory_cap_per_deal }}</strong> spots per deal.
                                        @endif
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Duration -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Duration</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Start Date/Time *</label>
                                <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}" required id="starts_at">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>End Date/Time *</label>
                                <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}" required id="expires_at">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="btn-group mb-3" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDuration(7)">7 Days</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDuration(14)">14 Days</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDuration(30)">30 Days</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDuration(60)">60 Days</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Payment</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Stripe Payment Link *</label>
                                <input type="url" name="stripe_payment_link" class="form-control" value="{{ old('stripe_payment_link') }}" required placeholder="https://buy.stripe.com/..." pattern="https://buy\.stripe\.com/.*">
                                <small class="text-muted">
                                    Create a Payment Link in your <a href="https://dashboard.stripe.com/payment_links" target="_blank">Stripe dashboard</a> for $<span id="payment-link-amount">0.00</span>. Paste the URL here.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Location</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Address (Optional)</label>
                                <input type="text" name="location_address" class="form-control" value="{{ old('location_address') }}" placeholder="123 Main St">
                                <small class="text-muted">Will be geocoded to get coordinates for map display.</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>City *</label>
                                <select name="location_city" class="form-control" required>
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ old('location_city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ZIP Code *</label>
                                <input type="text" name="location_zip" class="form-control" value="{{ old('location_zip') }}" required pattern="\d{5}" placeholder="34731" maxlength="5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Terms & Conditions</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Terms & Conditions (Optional)</label>
                                <textarea name="terms_conditions" class="form-control" rows="4">{{ old('terms_conditions') }}</textarea>
                                <small class="text-muted">Leave blank to use default terms.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Deal Quality Analyzer -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    @include('vendor.deals.partials.ai-analyzer-widget')
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center mb-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa fa-save"></i> Create Deal
                </button>
                <a href="{{ route('vendor.deals.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</section>

<script>
// Calculate discount and savings in real-time
document.addEventListener('DOMContentLoaded', function() {
    const regularPrice = document.getElementById('regular_price');
    const dealPrice = document.getElementById('deal_price');
    const discountPercent = document.getElementById('discount-percent');
    const savingsAmount = document.getElementById('savings-amount');
    const paymentLinkAmount = document.getElementById('payment-link-amount');
    
    function updatePricing() {
        const regular = parseFloat(regularPrice.value) || 0;
        const deal = parseFloat(dealPrice.value) || 0;
        
        if (regular > 0 && deal > 0 && deal < regular) {
            const discount = Math.round(((regular - deal) / regular) * 100);
            const savings = (regular - deal).toFixed(2);
            
            discountPercent.textContent = discount;
            savingsAmount.textContent = savings;
            paymentLinkAmount.textContent = deal.toFixed(2);
        } else {
            discountPercent.textContent = '0';
            savingsAmount.textContent = '0.00';
            paymentLinkAmount.textContent = '0.00';
        }
    }
    
    regularPrice.addEventListener('input', updatePricing);
    dealPrice.addEventListener('input', updatePricing);
    updatePricing();
});

// Quick duration buttons
function setDuration(days) {
    const now = new Date();
    const startDate = new Date(now.getTime() + (60 * 60 * 1000)); // 1 hour from now
    const endDate = new Date(startDate.getTime() + (days * 24 * 60 * 60 * 1000));
    
    // Format for datetime-local input
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    };
    
    document.getElementById('starts_at').value = formatDate(startDate);
    document.getElementById('expires_at').value = formatDate(endDate);
}

// Form validation
document.getElementById('deal-form').addEventListener('submit', function(e) {
    const regularPrice = parseFloat(document.getElementById('regular_price').value);
    const dealPrice = parseFloat(document.getElementById('deal_price').value);
    
    if (dealPrice >= regularPrice) {
        e.preventDefault();
        alert('Deal price must be less than regular price.');
        return false;
    }
    
    const startsAt = new Date(document.getElementById('starts_at').value);
    const expiresAt = new Date(document.getElementById('expires_at').value);
    
    if (expiresAt <= startsAt) {
        e.preventDefault();
        alert('End date must be after start date.');
        return false;
    }
});
</script>

@endsection


