@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Support Tickets</h1>
        <h2 class="h5 text-white-op animated zoomIn">Manage vendor support requests</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $stats['open'] }}</h3>
                    <p class="text-muted mb-0">Open</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $stats['in_progress'] }}</h3>
                    <p class="text-muted mb-0">In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $stats['resolved'] }}</h3>
                    <p class="text-muted mb-0">Resolved</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger">{{ $stats['overdue'] }}</h3>
                    <p class="text-muted mb-0">Overdue</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Filters</h3>
        </div>
        <div class="block-content">
            <form method="GET" action="{{ route('admin.support.index') }}" class="form-horizontal">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Priority</label>
                            <select name="priority" class="form-control">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Assigned To</label>
                            <select name="assigned_to" class="form-control">
                                <option value="">All</option>
                                <option value="me" {{ request('assigned_to') == 'me' ? 'selected' : '' }}>Me</option>
                                <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Tickets ({{ $tickets->total() }})</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-hover table-vcenter">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Vendor</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr class="{{ $ticket->isOverdue() ? 'table-danger' : '' }}">
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ Str::limit($ticket->subject, 40) }}</td>
                            <td>{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</td>
                            <td>
                                <span class="badge badge-{{ $ticket->priority == 'high' ? 'danger' : ($ticket->priority == 'normal' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'resolved' ? 'success' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($ticket->assignedTo)
                                    {{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No tickets found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $tickets->links() }}
        </div>
    </div>
</div>

@endsection


