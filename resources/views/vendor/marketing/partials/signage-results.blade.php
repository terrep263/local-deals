<div data-result-type="signage" style="display: none;">
    <h6 class="fw-bold mb-3">In-Store Signage Content</h6>
    
    <!-- Preview -->
    <div class="card bg-light mb-4">
        <div class="card-body text-center" style="border: 3px dashed #95a5a6; padding: 30px;">
            <h3 id="signageHeadlinePreview" class="fw-bold mb-2" style="font-size: 2.5rem; color: #e74c3c;"></h3>
            <h5 id="signageSubheadlinePreview" class="text-muted mb-3" style="font-size: 1.3rem;"></h5>
            <p id="signageBodyPreview" class="mb-3"></p>
            <small id="signageFinePreview" class="text-muted" style="font-size: 0.85rem;"></small>
        </div>
    </div>

    <!-- Content Fields -->
    <div class="mb-4">
        <label class="form-label fw-bold">Headline</label>
        <input type="text" id="signageHeadline" class="form-control" readonly>
        <button class="btn btn-outline-primary btn-sm mt-2" data-target="signageHeadline">
            <i class="fas fa-copy me-1"></i> Copy
        </button>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Subheadline</label>
        <input type="text" id="signageSubheadline" class="form-control" readonly>
        <button class="btn btn-outline-primary btn-sm mt-2" data-target="signageSubheadline">
            <i class="fas fa-copy me-1"></i> Copy
        </button>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Body Text</label>
        <textarea id="signageBody" class="form-control" rows="4" readonly></textarea>
        <button class="btn btn-outline-primary btn-sm mt-2" data-target="signageBody">
            <i class="fas fa-copy me-1"></i> Copy
        </button>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Fine Print</label>
        <textarea id="signageFine" class="form-control" rows="2" readonly></textarea>
        <button class="btn btn-outline-primary btn-sm mt-2" data-target="signageFine">
            <i class="fas fa-copy me-1"></i> Copy
        </button>
    </div>

    <div class="alert alert-info">
        <strong>Tip:</strong> Print this as a window cling, counter display, or poster for in-store promotion
    </div>

    <div class="mt-4">
        <button class="btn btn-success" data-action="mark-used">
            <i class="fas fa-check me-1"></i> Mark as Used
        </button>
    </div>
</div>
