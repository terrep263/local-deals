@extends('app')

@section('head_title', 'Support Tickets - ' . getcong('site_name'))
@section('content')

<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-3">Support Tickets</h2>
                <a href="{{ route('vendor.support.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Create New Ticket
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Your Tickets</h5>
                    </div>
                    <div class="card-body">
                        @if($tickets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Subject</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                    <tr>
                                        <td>#{{ $ticket->id }}</td>
                                        <td>{{ Str::limit($ticket->subject, 50) }}</td>
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
                                        <td>{{ $ticket->updated_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('vendor.support.show', $ticket->id) }}" class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $tickets->links() }}
                        @else
                        <p class="text-muted text-center">No support tickets yet. <a href="{{ route('vendor.support.create') }}">Create your first ticket</a>.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


