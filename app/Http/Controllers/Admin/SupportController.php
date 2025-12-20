<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo', 'messages']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'me') {
                $query->where('assigned_to', Auth::id());
            } elseif ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        $tickets = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'overdue' => SupportTicket::whereIn('status', ['open', 'in_progress'])
                ->get()
                ->filter(fn($ticket) => $ticket->isOverdue())
                ->count(),
        ];

        return view('admin.support.index', compact('tickets', 'stats'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'assignedTo', 'messages.user'])
            ->findOrFail($id);

        return view('admin.support.show', compact('ticket'));
    }

    public function assign(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['assigned_to' => $request->admin_id]);

        ActivityLog::log(
            'support.ticket_assigned',
            "Admin assigned ticket #{$ticket->id} to another admin",
            Auth::id(),
            'admin',
            ['ticket_id' => $ticket->id, 'assigned_to' => $request->admin_id]
        );

        return redirect()->back()->with('success', 'Ticket assigned');
    }

    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:10',
            'is_internal' => 'boolean',
        ]);

        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal' => $request->has('is_internal') && $request->is_internal,
        ]);

        // Update ticket status and timestamps
        if ($ticket->status === 'open' && !$request->has('is_internal')) {
            $ticket->update([
                'status' => 'in_progress',
                'first_response_at' => $ticket->first_response_at ?? now(),
            ]);
        }

        ActivityLog::log(
            'support.admin_replied',
            "Admin replied to ticket #{$ticket->id}",
            Auth::id(),
            'admin',
            ['ticket_id' => $ticket->id, 'internal' => $request->has('is_internal')]
        );

        return redirect()->back()->with('success', 'Reply sent');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->update([
            'status' => $request->status,
            'resolved_at' => in_array($request->status, ['resolved', 'closed']) ? now() : null,
        ]);

        ActivityLog::log(
            'support.status_updated',
            "Admin updated ticket #{$ticket->id} status to {$request->status}",
            Auth::id(),
            'admin',
            ['ticket_id' => $ticket->id, 'status' => $request->status]
        );

        return redirect()->back()->with('success', 'Status updated');
    }
}


