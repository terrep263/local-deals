@extends('layouts.app')

@section('title', 'Marketing Assistant')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold">AI Marketing Assistant</h1>
            <p class="text-muted">Generate compelling marketing content for your deals</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <small class="text-muted d-block">Daily Limit</small>
                    <h4 class="mb-0">
                        <span class="text-success fw-bold">{{ $usageStats['remaining'] }}</span>
                        / {{ $usageStats['daily_limit'] }}
                    </h4>
                    <small class="text-muted">Content remaining today</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Type Selection -->
    <div class="row mb-4">
        @foreach($contentTypes as $type => $label)
        <div class="col-md-3 mb-3">
            <a href="#" class="card text-decoration-none h-100 content-type-btn" data-type="{{ $type }}">
                <div class="card-body text-center">
                    <i class="fas fa-2x mb-3" 
                       style="color: {{ match($type) {
                           'email' => '#0066cc',
                           'social' => '#25a4e8',
                           'ads' => '#ff6b35',
                           'signage' => '#2ecc71',
                           default => '#95a5a6'
                       } }}">
                        {{ match($type) {
                            'email' => '📧',
                            'social' => '👥',
                            'ads' => '📢',
                            'signage' => '🏷️',
                            default => '⚙️'
                        } }}
                    </i>
                    <h5 class="card-title">{{ $label }}</h5>
                    <small class="text-muted d-block">Generate {{ strtolower($label) }}</small>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Main Content Area -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <span id="contentTypeTitle">Select Content Type</span>
                        <span class="badge bg-secondary ms-2" id="selectedTypeLabel" style="display: none;"></span>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Deal Selection -->
                    <div id="dealSelection" class="mb-4">
                        <label class="form-label fw-bold">1. Select a Deal</label>
                        <select id="dealSelect" class="form-select" disabled>
                            <option value="">Choose a deal to create marketing content for...</option>
                            @forelse($deals as $deal)
                            <option value="{{ $deal->id }}" data-title="{{ $deal->title }}">
                                {{ $deal->title }} - ${{ $deal->sale_price }}
                            </option>
                            @empty
                            <option value="" disabled>No active deals available</option>
                            @endforelse
                        </select>
                        <small class="text-muted d-block mt-2">
                            You have {{ count($deals) }} active deal(s)
                        </small>
                    </div>

                    <!-- Type-Specific Options -->
                    <div id="typeOptions" style="display: none;">
                        <!-- Social Media Platform Selection -->
                        <div id="socialOptions" style="display: none;" class="mb-4">
                            <label class="form-label fw-bold">2. Select Platform</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="platform" id="facebook" value="facebook">
                                <label class="btn btn-outline-primary" for="facebook">Facebook</label>

                                <input type="radio" class="btn-check" name="platform" id="instagram" value="instagram">
                                <label class="btn btn-outline-primary" for="instagram">Instagram</label>

                                <input type="radio" class="btn-check" name="platform" id="twitter" value="twitter">
                                <label class="btn btn-outline-primary" for="twitter">Twitter/X</label>
                            </div>
                        </div>

                        <!-- Ad Platform Selection -->
                        <div id="adOptions" style="display: none;" class="mb-4">
                            <label class="form-label fw-bold">2. Select Ad Platform</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="ad_platform" id="google_ads" value="google_ads">
                                <label class="btn btn-outline-primary" for="google_ads">Google Ads</label>

                                <input type="radio" class="btn-check" name="ad_platform" id="facebook_ads" value="facebook_ads">
                                <label class="btn btn-outline-primary" for="facebook_ads">Facebook Ads</label>
                            </div>
                        </div>
                    </div>

                    <!-- Generate Button -->
                    <div class="mt-4">
                        <button id="generateBtn" class="btn btn-primary btn-lg w-100" disabled>
                            <i class="fas fa-magic me-2"></i> Generate Content
                        </button>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" style="display: none;" class="mt-4">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm me-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div>
                                <p class="mb-1">Generating your content...</p>
                                <small class="text-muted">This typically takes 10-15 seconds</small>
                            </div>
                        </div>
                    </div>

                    <!-- Error Alert -->
                    <div id="errorAlert" class="alert alert-danger mt-4" style="display: none;"></div>

                    <!-- Results Container -->
                    <div id="resultsContainer" style="display: none;">
                        @include('vendor.marketing.partials.email-results')
                        @include('vendor.marketing.partials.social-results')
                        @include('vendor.marketing.partials.ads-results')
                        @include('vendor.marketing.partials.signage-results')
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Stats Card -->
            <div class="card mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">Usage Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Generated</span>
                            <strong>{{ $usageStats['total_generated'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Used</span>
                            <strong>{{ $usageStats['total_used'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Usage Rate</span>
                            <strong>{{ $usageStats['usage_rate'] }}%</strong>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted d-block">By Type</small>
                    @forelse($usageStats['by_type'] as $type => $count)
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">{{ ucfirst($type) }}</span>
                        <strong>{{ $count }}</strong>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No content generated yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Content -->
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">Recent Content</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($recentContent as $content)
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <small class="badge bg-info">{{ $content->getContentTypeLabel() }}</small>
                                @if($content->platform)
                                <small class="badge bg-secondary">{{ $content->getPlatformLabel() }}</small>
                                @endif
                            </div>
                            <small class="text-muted">{{ $content->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="text-muted mb-2 small">
                            Deal: <strong>{{ $content->deal->title ?? 'Deleted' }}</strong>
                        </p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary copy-content" data-content-id="{{ $content->id }}">
                                Copy
                            </button>
                            @if(!$content->is_used)
                            <button class="btn btn-sm btn-outline-success mark-used" data-content-id="{{ $content->id }}">
                                Mark Used
                            </button>
                            @else
                            <span class="badge bg-success mt-1">Used</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted">
                        <p class="mb-0">No content generated yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dealSelect = document.getElementById('dealSelect');
    const generateBtn = document.getElementById('generateBtn');
    const typeOptions = document.getElementById('typeOptions');
    const socialOptions = document.getElementById('socialOptions');
    const adOptions = document.getElementById('adOptions');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const errorAlert = document.getElementById('errorAlert');
    const resultsContainer = document.getElementById('resultsContainer');
    const contentTypeTitle = document.getElementById('contentTypeTitle');
    const selectedTypeLabel = document.getElementById('selectedTypeLabel');

    let selectedContentType = null;

    // Content type selection
    document.querySelectorAll('.content-type-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            selectedContentType = this.dataset.type;
            
            // Update UI
            document.querySelectorAll('.content-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update titles
            const typeLabels = {
                'email': 'Email Campaigns',
                'social': 'Social Media Posts',
                'ads': 'Ad Copy',
                'signage': 'In-Store Signage'
            };
            contentTypeTitle.textContent = typeLabels[selectedContentType];
            selectedTypeLabel.textContent = typeLabels[selectedContentType];
            selectedTypeLabel.style.display = 'inline-block';
            
            // Show/hide type options
            typeOptions.style.display = 'block';
            socialOptions.style.display = selectedContentType === 'social' ? 'block' : 'none';
            adOptions.style.display = selectedContentType === 'ads' ? 'block' : 'none';
            
            // Enable deal select
            dealSelect.disabled = false;
            checkGenerateEnabled();
        });
    });

    // Deal selection
    dealSelect.addEventListener('change', function() {
        checkGenerateEnabled();
    });

    // Platform selection
    document.querySelectorAll('input[name="platform"], input[name="ad_platform"]').forEach(radio => {
        radio.addEventListener('change', checkGenerateEnabled);
    });

    function checkGenerateEnabled() {
        let enabled = selectedContentType && dealSelect.value;
        
        if (selectedContentType === 'social') {
            enabled = enabled && document.querySelector('input[name="platform"]:checked');
        } else if (selectedContentType === 'ads') {
            enabled = enabled && document.querySelector('input[name="ad_platform"]:checked');
        }
        
        generateBtn.disabled = !enabled;
    }

    // Generate button
    generateBtn.addEventListener('click', async function() {
        const dealId = dealSelect.value;
        if (!dealId) return;

        loadingIndicator.style.display = 'block';
        errorAlert.style.display = 'none';
        resultsContainer.style.display = 'none';

        try {
            let endpoint = `/vendor/marketing/generate-${selectedContentType}`;
            let payload = { deal_id: parseInt(dealId) };

            if (selectedContentType === 'social') {
                payload.platform = document.querySelector('input[name="platform"]:checked').value;
            } else if (selectedContentType === 'ads') {
                payload.platform = document.querySelector('input[name="ad_platform"]:checked').value;
            }

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Failed to generate content');
            }

            // Display results
            displayResults(result.data, selectedContentType);
            
            // Update remaining counter
            if (result.remaining !== undefined) {
                document.querySelector('[id="selectedTypeLabel"]').textContent = 
                    `${result.remaining} remaining`;
            }

        } catch (error) {
            errorAlert.textContent = error.message;
            errorAlert.style.display = 'block';
        } finally {
            loadingIndicator.style.display = 'none';
        }
    });

    function displayResults(data, type) {
        resultsContainer.style.display = 'block';
        
        // Hide all result types
        document.querySelectorAll('[data-result-type]').forEach(el => {
            el.style.display = 'none';
        });
        
        // Show selected type
        const resultEl = document.querySelector(`[data-result-type="${type}"]`);
        if (resultEl) {
            resultEl.style.display = 'block';
            populateResults(resultEl, data, type);
        }
    }

    function populateResults(container, data, type) {
        // This will be populated by specific result partials
        if (type === 'email') {
            document.querySelector('#emailSubjects').innerHTML = data.subject_lines
                .map((subject, i) => `
                    <div class="mb-2">
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-text="${subject}">
                            <i class="fas fa-copy me-1"></i> Copy
                        </button>
                        <span class="ms-2">${subject}</span>
                    </div>
                `).join('');
            document.querySelector('#emailBody').value = data.body_content;
        }
    }
});
</script>
@endpush

@push('styles')
<style>
    .content-type-btn {
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .content-type-btn:hover {
        border-color: #0066cc;
        box-shadow: 0 2px 8px rgba(0, 102, 204, 0.1);
    }

    .content-type-btn.active {
        border-color: #0066cc;
        background-color: #f0f7ff;
    }

    .content-type-btn.active .card-body {
        color: #0066cc;
    }
</style>
@endpush
