<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('vendor.support.index', compact('tickets'));
    }

    public function create()
    {
        return view('vendor.support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|in:account,deal_issue,payment,feature_request,other',
            'priority' => 'required|in:low,normal,high',
            'message' => 'required|string|min:10',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal' => false,
        ]);

        ActivityLog::log(
            'support.ticket_created',
            "Vendor created support ticket: {$request->subject}",
            Auth::id(),
            'vendor',
            ['ticket_id' => $ticket->id]
        );

        return redirect()->route('vendor.support.show', $ticket->id)
            ->with('success', 'Support ticket created successfully');
    }

    public function show($id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())
            ->with(['messages.user', 'assignedTo'])
            ->findOrFail($id);

        return view('vendor.support.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:10',
        ]);

        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_internal' => false,
        ]);

        // Update ticket status if it was resolved/closed
        if ($ticket->status === 'resolved' || $ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        ActivityLog::log(
            'support.message_added',
            "Vendor replied to ticket: {$ticket->subject}",
            Auth::id(),
            'vendor',
            ['ticket_id' => $ticket->id]
        );

        return redirect()->back()->with('success', 'Reply sent');
    }
}


