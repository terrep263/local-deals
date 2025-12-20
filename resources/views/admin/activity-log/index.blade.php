@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Activity Log</h1>
        <h2 class="h5 text-white-op animated zoomIn">Audit trail of all platform actions</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    <!-- Filters -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Filters</h3>
        </div>
        <div class="block-content">
            <form method="GET" action="{{ route('admin.activity-log.index') }}" class="form-horizontal">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>User</label>
                            <select name="user_id" class="form-control">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Action</label>
                            <input type="text" name="action" class="form-control" placeholder="e.g., deal.approved" value="{{ request('action') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Description..." value="{{ request('search') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.activity-log.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Activity Log ({{ $logs->total() }} entries)</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-hover table-vcenter">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                            <td>
                                @if($log->user)
                                    {{ $log->user->first_name }} {{ $log->user->last_name }}
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                @if($log->user_type)
                                    <span class="badge badge-{{ $log->user_type == 'admin' ? 'danger' : 'info' }}">
                                        {{ ucfirst($log->user_type) }}
                                    </span>
                                @endif
                            </td>
                            <td><code>{{ $log->action }}</code></td>
                            <td>{{ $log->description }}</td>
                            <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No activity found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $logs->links() }}
        </div>
    </div>
</div>

@endsection


