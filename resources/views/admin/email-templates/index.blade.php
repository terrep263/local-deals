@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Email Templates</h1>
        <h2 class="h5 text-white-op animated zoomIn">Manage email templates for all notifications</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Vendor Templates -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Vendor Email Templates</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Key</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories['vendor'] as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td><code>{{ $template->key }}</code></td>
                            <td>
                                @if($template->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.email-templates.edit', $template->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('admin.email-templates.preview', $template->id) }}" class="btn btn-sm btn-info" target="_blank">
                                    <i class="fa fa-eye"></i> Preview
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No vendor templates found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Consumer Templates -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Consumer Email Templates</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Key</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories['consumer'] as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td><code>{{ $template->key }}</code></td>
                            <td>
                                @if($template->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.email-templates.edit', $template->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('admin.email-templates.preview', $template->id) }}" class="btn btn-sm btn-info" target="_blank">
                                    <i class="fa fa-eye"></i> Preview
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No consumer templates found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Admin Templates -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Admin Email Templates</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Key</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories['admin'] as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td><code>{{ $template->key }}</code></td>
                            <td>
                                @if($template->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.email-templates.edit', $template->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('admin.email-templates.preview', $template->id) }}" class="btn btn-sm btn-info" target="_blank">
                                    <i class="fa fa-eye"></i> Preview
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No admin templates found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


