@extends('app')

@section('head_title', 'Support Ticket #' . $ticket->id . ' - ' . getcong('site_name'))
@section('content')

<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Ticket #{{ $ticket->id }}: {{ $ticket->subject }}</h3>
                    </div>
                    <div class="card-body">
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
                        @if($ticket->assignedTo)
                        <p><strong>Assigned To:</strong> {{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}</p>
                        @endif
                    </div>
                </div>

                <!-- Messages -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Conversation</h5>
                    </div>
                    <div class="card-body">
                        @foreach($ticket->publicMessages->sortBy('created_at') as $message)
                        <div class="mb-3 p-3 border rounded {{ $message->user_id == auth()->id() ? 'bg-light' : '' }}">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>
                                    @if($message->user_id == auth()->id())
                                        You
                                    @else
                                        Support Team
                                    @endif
                                </strong>
                                <span class="text-muted">{{ $message->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div>{!! nl2br(e($message->message)) !!}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Reply Form -->
                @if($ticket->status != 'closed')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Reply</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('vendor.support.reply', $ticket->id) }}">
                            @csrf
                            <div class="form-group">
                                <textarea name="message" class="form-control" rows="5" required minlength="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Reply</button>
                            <a href="{{ route('vendor.support.index') }}" class="btn btn-secondary">Back to Tickets</a>
                        </form>
                    </div>
                </div>
                @else
                <div class="alert alert-info">
                    This ticket is closed. If you need further assistance, please create a new ticket.
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection


