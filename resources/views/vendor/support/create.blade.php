@extends('app')

@section('head_title', 'Create Support Ticket - ' . getcong('site_name'))
@section('content')

<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Create Support Ticket</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('vendor.support.store') }}">
                            @csrf

                            <div class="form-group">
                                <label>Subject *</label>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required maxlength="255">
                            </div>

                            <div class="form-group">
                                <label>Category *</label>
                                <select name="category" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <option value="account" {{ old('category') == 'account' ? 'selected' : '' }}>Account Issue</option>
                                    <option value="deal_issue" {{ old('category') == 'deal_issue' ? 'selected' : '' }}>Deal Issue</option>
                                    <option value="payment" {{ old('category') == 'payment' ? 'selected' : '' }}>Payment</option>
                                    <option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Priority *</label>
                                <select name="priority" class="form-control" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }} selected>Normal</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Message *</label>
                                <textarea name="message" class="form-control" rows="8" required minlength="10">{{ old('message') }}</textarea>
                                <small class="text-muted">Please provide as much detail as possible to help us assist you.</small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit Ticket</button>
                                <a href="{{ route('vendor.support.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


