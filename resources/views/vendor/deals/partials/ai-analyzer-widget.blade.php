<!-- AI Deal Analyzer Widget -->
<div class="card border-primary mb-4" id="ai-analyzer-widget" x-data="dealAnalyzer()">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-magic"></i> Deal Quality Analyzer
            </h5>
            <span class="badge badge-light" x-text="`${remaining}/10 analyses left today`"></span>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Initial State -->
        <div x-show="!analyzing && !results" x-transition>
            <p class="mb-3">
                Get instant feedback on your deal quality. Our analyzer checks your title, 
                description, and pricing to help you create deals that convert.
            </p>
            <button 
                type="button" 
                class="btn btn-primary btn-block"
                @click="analyze()"
                :disabled="remaining <= 0"
            >
                <i class="fas fa-search"></i> Analyze Deal Quality
            </button>
            <small class="text-muted d-block mt-2">
                <i class="fas fa-info-circle"></i> Free for all vendors • Instant results • No obligations
            </small>
        </div>
        
        <!-- Loading State -->
        <div x-show="analyzing" x-transition class="text-center py-4">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="sr-only">Analyzing...</span>
            </div>
            <p class="mb-0 font-weight-bold">Analyzing your deal quality...</p>
            <small class="text-muted">This usually takes 3-5 seconds</small>
        </div>
        
        <!-- Results -->
        <div x-show="results && !analyzing" x-transition>
            <!-- Overall Score -->
            <div class="text-center mb-4 p-4 bg-light rounded">
                <h2 class="display-3 mb-2" :class="{
                    'text-danger': results?.overall_score < 60,
                    'text-warning': results?.overall_score >= 60 && results?.overall_score < 80,
                    'text-success': results?.overall_score >= 80
                }">
                    <span x-text="results?.overall_score || 0"></span>
                    <small class="text-muted" style="font-size: 0.4em;">/100</small>
                </h2>
                <div class="progress mb-3" style="height: 15px;">
                    <div 
                        class="progress-bar progress-bar-striped progress-bar-animated"
                        :class="{
                            'bg-danger': results?.overall_score < 60,
                            'bg-warning': results?.overall_score >= 60 && results?.overall_score < 80,
                            'bg-success': results?.overall_score >= 80
                        }"
                        :style="`width: ${results?.overall_score || 0}%`"
                    ></div>
                </div>
                <p class="mb-0 font-weight-bold h5" x-text="getScoreLabel(results?.overall_score)"></p>
                <small class="text-muted">Based on title, description, and pricing analysis</small>
            </div>
            
            <!-- Detailed Scores -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="text-center p-3 border rounded">
                        <h3 class="mb-1" x-text="results?.title_score || 0"></h3>
                        <small class="text-muted d-block">Title Quality</small>
                        <i class="fas fa-heading text-primary mt-2" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 border rounded">
                        <h3 class="mb-1" x-text="results?.description_score || 0"></h3>
                        <small class="text-muted d-block">Description</small>
                        <i class="fas fa-align-left text-primary mt-2" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 border rounded">
                        <h3 class="mb-1" x-text="results?.pricing_score || 0"></h3>
                        <small class="text-muted d-block">Pricing</small>
                        <i class="fas fa-dollar-sign text-primary mt-2" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
            
            <!-- Suggestions -->
            <div x-show="results?.suggestions && results.suggestions.length > 0">
                <h6 class="mb-3 font-weight-bold">
                    <i class="fas fa-lightbulb text-warning"></i> Suggestions for Improvement
                </h6>
                
                <template x-for="(suggestion, index) in results?.suggestions" :key="index">
                    <div class="alert mb-3" 
                         :class="{
                             'alert-danger': suggestion.severity === 'critical',
                             'alert-warning': suggestion.severity === 'important',
                             'alert-info': suggestion.severity === 'minor'
                         }">
                        <div class="d-flex">
                            <div class="mr-3">
                                <i class="fas fa-2x" 
                                   :class="{
                                       'fa-exclamation-circle': suggestion.severity === 'critical',
                                       'fa-exclamation-triangle': suggestion.severity === 'important',
                                       'fa-info-circle': suggestion.severity === 'minor'
                                   }"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">
                                    <strong x-text="suggestion.issue"></strong>
                                    <span class="badge ml-2" 
                                          :class="{
                                              'badge-danger': suggestion.severity === 'critical',
                                              'badge-warning': suggestion.severity === 'important',
                                              'badge-info': suggestion.severity === 'minor'
                                          }"
                                          x-text="suggestion.severity.toUpperCase()">
                                    </span>
                                </h6>
                                <p class="mb-0" x-text="suggestion.suggestion"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Perfect Score Message -->
            <div x-show="!results?.suggestions || results.suggestions.length === 0" class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <strong>Great job!</strong> Your deal looks professional and complete. No improvements needed!
            </div>
            
            <!-- Improved Title Suggestion -->
            <div x-show="results?.improved_title" class="mt-4">
                <h6 class="mb-2 font-weight-bold">
                    <i class="fas fa-star text-success"></i> Suggested Improved Title
                </h6>
                <div class="card">
                    <div class="card-body bg-light">
                        <p class="mb-3 font-weight-bold" style="font-size: 1.1rem;" x-text="results?.improved_title"></p>
                        <button 
                            type="button" 
                            class="btn btn-success btn-sm"
                            @click="applyTitle()"
                        >
                            <i class="fas fa-check"></i> Use This Title
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Improved Description Suggestion -->
            <div x-show="results?.improved_description" class="mt-4">
                <h6 class="mb-2 font-weight-bold">
                    <i class="fas fa-star text-success"></i> Suggested Improved Description
                </h6>
                <div class="card">
                    <div class="card-body bg-light">
                        <p class="mb-3" style="white-space: pre-line;" x-text="results?.improved_description"></p>
                        <button 
                            type="button" 
                            class="btn btn-success btn-sm"
                            @click="applyDescription()"
                        >
                            <i class="fas fa-check"></i> Use This Description
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-4 d-flex justify-content-between">
                <button 
                    type="button" 
                    class="btn btn-outline-secondary"
                    @click="reset()"
                    :disabled="remaining <= 0"
                >
                    <i class="fas fa-redo"></i> Analyze Again
                </button>
                
                <button 
                    type="button" 
                    class="btn btn-success"
                    @click="markAsAccepted()"
                    x-show="results?.overall_score >= 60 && !wasAccepted"
                >
                    <i class="fas fa-thumbs-up"></i> Looks Good!
                </button>
                
                <span x-show="wasAccepted" class="text-success">
                    <i class="fas fa-check-circle"></i> Accepted
                </span>
            </div>
        </div>
        
        <!-- Error State -->
        <div x-show="error" x-transition class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Error:</strong> <span x-text="error"></span>
        </div>
    </div>
</div>

@push('scripts')
<script>
function dealAnalyzer() {
    return {
        analyzing: false,
        results: null,
        error: null,
        remaining: 10,
        wasAccepted: false,
        
        init() {
            // Load remaining count on init
            this.loadRemaining();
        },
        
        async loadRemaining() {
            try {
                const response = await fetch('{{ route("vendor.ai.remaining") }}');
                const data = await response.json();
                this.remaining = data.remaining;
            } catch (e) {
                console.error('Failed to load remaining count', e);
            }
        },
        
        async analyze() {
            // Reset state
            this.error = null;
            this.results = null;
            this.analyzing = true;
            
            // Get form data
            const title = document.getElementById('title')?.value || '';
            const description = document.getElementById('description')?.value || '';
            const originalPrice = document.getElementById('original_price')?.value || 0;
            const salePrice = document.getElementById('sale_price')?.value || 0;
            const category = document.getElementById('category')?.selectedOptions[0]?.text || 'General';
            const dealId = document.getElementById('deal_id')?.value || null;
            
            // Basic validation
            if (!title || title.length < 5) {
                this.error = 'Please enter a deal title (at least 5 characters)';
                this.analyzing = false;
                return;
            }
            
            if (!description || description.length < 50) {
                this.error = 'Please enter a deal description (at least 50 characters)';
                this.analyzing = false;
                return;
            }
            
            if (parseFloat(salePrice) >= parseFloat(originalPrice)) {
                this.error = 'Sale price must be less than original price';
                this.analyzing = false;
                return;
            }
            
            try {
                const response = await fetch('{{ route("vendor.ai.analyze") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        title,
                        description,
                        original_price: parseFloat(originalPrice),
                        sale_price: parseFloat(salePrice),
                        category,
                        deal_id: dealId
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.results = data;
                    this.remaining = data.remaining;
                } else {
                    this.error = data.error || 'Analysis failed. Please try again.';
                }
            } catch (e) {
                this.error = 'Network error. Please check your connection and try again.';
                console.error('Analysis error:', e);
            } finally {
                this.analyzing = false;
            }
        },
        
        applyTitle() {
            if (this.results?.improved_title) {
                const titleField = document.getElementById('title');
                if (titleField) {
                    titleField.value = this.results.improved_title;
                    // Trigger change event for any listeners
                    titleField.dispatchEvent(new Event('input', { bubbles: true }));
                    
                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast('Title updated!', 'success');
                    }
                }
            }
        },
        
        applyDescription() {
            if (this.results?.improved_description) {
                const descField = document.getElementById('description');
                if (descField) {
                    descField.value = this.results.improved_description;
                    // Trigger change event
                    descField.dispatchEvent(new Event('input', { bubbles: true }));
                    
                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast('Description updated!', 'success');
                    }
                }
            }
        },
        
        async markAsAccepted() {
            if (!this.results?.analysis_id) return;
            
            try {
                const response = await fetch(`/vendor/ai/analysis/${this.results.analysis_id}/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    this.wasAccepted = true;
                }
            } catch (e) {
                console.error('Failed to mark as accepted', e);
            }
        },
        
        reset() {
            this.results = null;
            this.error = null;
            this.wasAccepted = false;
        },
        
        getScoreLabel(score) {
            if (!score) return '';
            if (score >= 90) return 'Excellent';
            if (score >= 80) return 'Very Good';
            if (score >= 70) return 'Good';
            if (score >= 60) return 'Fair';
            return 'Needs Improvement';
        }
    }
}
</script>
@endpush
