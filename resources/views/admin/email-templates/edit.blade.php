@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Edit Email Template</h1>
        <h2 class="h5 text-white-op animated zoomIn">{{ $template->name }}</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Template: {{ $template->name }}</h3>
        </div>
        <div class="block-content">
            <form method="POST" action="{{ route('admin.email-templates.update', $template->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Subject Line</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject', $template->subject) }}" required>
                    <small class="text-muted">Available variables: 
                        @if($template->variables)
                            @foreach($template->variables as $var)
                                <code>{ {{ $var }} }</code>
                            @endforeach
                        @else
                            <code>{vendor_name}</code>, <code>{deal_title}</code>, <code>{deal_url}</code>
                        @endif
                    </small>
                </div>

                <div class="form-group">
                    <label>Email Body</label>
                    <textarea name="body" class="form-control elm1_editor" rows="15" required>{{ old('body', $template->body) }}</textarea>
                    <small class="text-muted">Use HTML formatting. Variables will be replaced when email is sent.</small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Template</button>
                    <a href="{{ route('admin.email-templates.index') }}" class="btn btn-secondary">Cancel</a>
                    <a href="{{ route('admin.email-templates.preview', $template->id) }}" class="btn btn-info" target="_blank">Preview</a>
                    
                    <!-- Test Email -->
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#testEmailModal">
                        <i class="fa fa-envelope"></i> Send Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.email-templates.test', $template->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Send Test Email</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="test@example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Test</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


