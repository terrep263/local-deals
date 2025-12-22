@extends('app')

@section('head_title', 'Edit Deal | ' . getcong('site_name'))
@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', 'Edit Deal')
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => 'Edit Deal', 'url' => '']]))

@section("content")

@include('common.page-hero-header', ['title' => 'Edit Deal']) 

<!-- ================================
    Start Edit Deal Area
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

        @php
            $canEditAll = in_array($deal->status, ['draft', 'pending_approval']);
        @endphp

        @if(!$canEditAll)
        <div class="alert alert-info">
            <strong>Note:</strong> This deal is active. You can only edit title, description, images, and terms. Pricing, inventory, and dates cannot be changed.
        </div>
        @endif

        <form action="{{ route('vendor.deals.update', $deal) }}" method="POST" enctype="multipart/form-data" id="deal-form">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Basic Information</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Deal Title *</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $deal->title) }}" required maxlength="255">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Category *</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $deal->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Description *</label>
                                <textarea name="description" class="form-control elm1_editor" rows="8" required minlength="50">{{ old('description', $deal->description) }}</textarea>
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
                                <label>Featured Image {{ $canEditAll ? '*' : '(Optional)' }}</label>
                                @if($deal->featured_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/deals/' . $deal->featured_image) }}" alt="Current" style="max-width: 200px; border: 1px solid #ddd; padding: 5px;">
                                </div>
                                @endif
                                <input type="file" name="featured_image" class="form-control" accept="image/*" {{ $canEditAll ? 'required' : '' }}>
                                <small class="text-muted">Max 2MB. Will be resized to 1200x800px.</small>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Gallery Images (Optional, Max 5)</label>
                                @if($deal->gallery_images && count($deal->gallery_images) > 0)
                                <div class="mb-2">
                                    @foreach($deal->gallery_images as $image)
                                    <img src="{{ asset('storage/deals/gallery/' . $image) }}" alt="Gallery" style="max-width: 100px; border: 1px solid #ddd; padding: 5px; margin-right: 5px;">
                                    @endforeach
                                </div>
                                @endif
                                <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">Max 5 images, 2MB each.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing (only if can edit all) -->
            @if($canEditAll)
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
                                    <input type="number" name="regular_price" class="form-control" step="0.01" min="0.01" value="{{ old('regular_price', $deal->regular_price) }}" required id="regular_price">
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
                                    <input type="number" name="deal_price" class="form-control" step="0.01" min="0.01" value="{{ old('deal_price', $deal->deal_price) }}" required id="deal_price">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="alert alert-info" id="pricing-preview">
                                <strong>Discount:</strong> <span id="discount-percent">{{ $deal->discount_percentage }}</span>% | 
                                <strong>Savings:</strong> $<span id="savings-amount">{{ number_format($deal->savings_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory (only if can edit all) -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Inventory</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Total Spots Available *</label>
                                <input type="number" name="inventory_total" class="form-control" min="1" value="{{ old('inventory_total', $deal->inventory_total) }}" required id="inventory_total">
                                <small class="text-muted">Currently sold: {{ $deal->inventory_sold }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Duration (only if can edit all) -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Duration</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Start Date/Time *</label>
                                <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', $deal->starts_at ? $deal->starts_at->format('Y-m-d\TH:i') : '') }}" required id="starts_at">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>End Date/Time *</label>
                                <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $deal->expires_at ? $deal->expires_at->format('Y-m-d\TH:i') : '') }}" required id="expires_at">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment (only if can edit all) -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Payment</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Stripe Payment Link *</label>
                                <input type="url" name="stripe_payment_link" class="form-control" value="{{ old('stripe_payment_link', $deal->stripe_payment_link) }}" required placeholder="https://buy.stripe.com/..." pattern="https://buy\.stripe\.com/.*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Show read-only pricing/inventory for active deals -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Pricing & Inventory (Cannot be edited for active deals)</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <p><strong>Regular Price:</strong> ${{ number_format($deal->regular_price, 2) }}</p>
                            <p><strong>Deal Price:</strong> ${{ number_format($deal->deal_price, 2) }}</p>
                        </div>
                        <div class="col-lg-6">
                            <p><strong>Inventory:</strong> {{ $deal->inventory_sold }} / {{ $deal->inventory_total }} sold</p>
                            <p><strong>Expires:</strong> {{ $deal->expires_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Location -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Location</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Address (Optional)</label>
                                <input type="text" name="location_address" class="form-control" value="{{ old('location_address', $deal->location_address) }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>City *</label>
                                <select name="location_city" class="form-control" required>
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ old('location_city', $deal->location_city) == $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ZIP Code *</label>
                                <input type="text" name="location_zip" class="form-control" value="{{ old('location_zip', $deal->location_zip) }}" required pattern="\d{5}" maxlength="5">
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
                                <textarea name="terms_conditions" class="form-control" rows="4">{{ old('terms_conditions', $deal->terms_conditions) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Deal Quality Analyzer -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <input type="hidden" id="deal_id" value="{{ $deal->id }}">
                    @include('vendor.deals.partials.ai-analyzer-widget')
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center mb-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa fa-save"></i> Update Deal
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
    
    if (regularPrice && dealPrice) {
        function updatePricing() {
            const regular = parseFloat(regularPrice.value) || 0;
            const deal = parseFloat(dealPrice.value) || 0;
            
            if (regular > 0 && deal > 0 && deal < regular) {
                const discount = Math.round(((regular - deal) / regular) * 100);
                const savings = (regular - deal).toFixed(2);
                
                discountPercent.textContent = discount;
                savingsAmount.textContent = savings;
            }
        }
        
        regularPrice.addEventListener('input', updatePricing);
        dealPrice.addEventListener('input', updatePricing);
    }
});
</script>

@endsection


