@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Support Ticket #{{ $ticket->id }}</h1>
        <h2 class="h5 text-white-op animated zoomIn">{{ $ticket->subject }}</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    <div class="row">
        <div class="col-md-9">
            <!-- Ticket Info -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Ticket Information</h3>
                </div>
                <div class="block-content">
                    <p><strong>Vendor:</strong> {{ $ticket->user->first_name }} {{ $ticket->user->last_name }} ({{ $ticket->user->email }})</p>
                    <p><strong>Category:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</p>
                    <p><strong>Priority:</strong> 
                        <span class="badge badge-{{ $ticket->priority == 'high' ? 'danger' : ($ticket->priority == 'normal' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'resolved' ? 'success' : 'secondary') }}">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </p>
                    <p><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    @if($ticket->isOverdue())
                        <p class="text-danger"><strong>⚠️ OVERDUE</strong></p>
                    @endif
                </div>
            </div>

            <!-- Messages Thread -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Conversation</h3>
                </div>
                <div class="block-content">
                    @foreach($ticket->messages->sortBy('created_at') as $message)
                    <div class="mb-4 p-3 {{ $message->is_internal ? 'bg-light' : 'bg-white' }} border rounded">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>
                                @if($message->user)
                                    {{ $message->user->first_name }} {{ $message->user->last_name }}
                                @else
                                    System
                                @endif
                                @if($message->is_internal)
                                    <span class="badge badge-secondary">Internal Note</span>
                                @endif
                            </strong>
                            <span class="text-muted">{{ $message->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="message-content">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Reply Form -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Reply</h3>
                </div>
                <div class="block-content">
                    <form method="POST" action="{{ route('admin.support.reply', $ticket->id) }}">
                        @csrf
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_internal" value="1">
                                    Internal Note (not visible to vendor)
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <!-- Quick Actions -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Quick Actions</h3>
                </div>
                <div class="block-content">
                    <!-- Assign -->
                    <form method="POST" action="{{ route('admin.support.assign', $ticket->id) }}" class="mb-3">
                        @csrf
                        <div class="form-group">
                            <label>Assign To</label>
                            <select name="admin_id" class="form-control">
                                <option value="">Select Admin</option>
                                @foreach(\App\Models\User::where('usertype', 'admin')->get() as $admin)
                                <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->first_name }} {{ $admin->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary btn-block">Assign</button>
                    </form>

                    <!-- Update Status -->
                    <form method="POST" action="{{ route('admin.support.update-status', $ticket->id) }}" class="mb-3">
                        @csrf
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success btn-block">Update Status</button>
                    </form>

                    <a href="{{ route('admin.support.index') }}" class="btn btn-sm btn-secondary btn-block">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


