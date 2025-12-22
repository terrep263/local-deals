<div data-result-type="social" style="display: none;">
    <h6 class="fw-bold mb-3">Social Media Content</h6>
    
    <div class="mb-4">
        <label class="form-label fw-bold">Post Content</label>
        <textarea id="socialPost" class="form-control" rows="6" readonly></textarea>
        <small class="text-muted d-block mt-2">
            <span id="charCount">0</span> characters
        </small>
        <button class="btn btn-outline-primary btn-sm mt-2" data-target="socialPost">
            <i class="fas fa-copy me-1"></i> Copy Post
        </button>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Hashtags</label>
        <div id="socialHashtags" class="mb-3"></div>
        <button class="btn btn-outline-primary btn-sm" data-action="copy-hashtags">
            <i class="fas fa-copy me-1"></i> Copy All Hashtags
        </button>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Call to Action</label>
        <input type="text" id="socialCTA" class="form-control" readonly>
        <button class="btn btn-outline-primary btn-sm mt-2" data-target="socialCTA">
            <i class="fas fa-copy me-1"></i> Copy CTA
        </button>
    </div>

    <div class="alert alert-info">
        <strong>Tip:</strong> Copy the post content and hashtags to your social media platform
    </div>

    <div class="mt-4">
        <button class="btn btn-success" data-action="mark-used">
            <i class="fas fa-check me-1"></i> Mark as Used
        </button>
    </div>
</div>
