<div data-result-type="email" style="display: none;">
    <h6 class="fw-bold mb-3">Email Campaign Content</h6>
    
    <div class="mb-4">
        <label class="form-label fw-bold">Subject Lines (Select one)</label>
        <div id="emailSubjects" class="space-y-2"></div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Email Body</label>
        <textarea id="emailBody" class="form-control" rows="8" readonly></textarea>
        <button class="btn btn-outline-primary btn-sm mt-2" data-target="emailBody">
            <i class="fas fa-copy me-1"></i> Copy Body
        </button>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Call to Action</label>
        <input type="text" id="emailCTA" class="form-control" readonly>
        <button class="btn btn-outline-primary btn-sm mt-2" data-target="emailCTA">
            <i class="fas fa-copy me-1"></i> Copy CTA
        </button>
    </div>

    <div class="alert alert-info">
        <strong>Tip:</strong> Copy the subject line and body into your email service provider (Resend, Mailchimp, etc.)
    </div>

    <div class="mt-4">
        <button class="btn btn-success" data-action="mark-used">
            <i class="fas fa-check me-1"></i> Mark as Used
        </button>
    </div>
</div>
