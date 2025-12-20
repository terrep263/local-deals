<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DealController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display deals management page
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        
        try {
            $query = Deal::with(['vendor', 'category']);
        } catch (\Exception $e) {
            // If deals table doesn't exist, return empty results
            return view('admin.deals.index', [
                'deals' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 50),
                'tab' => $tab,
                'counts' => [
                    'pending' => 0,
                    'ai_flagged' => 0,
                    'active' => 0,
                    'all' => 0,
                    'sold_out' => 0,
                    'expired' => 0,
                    'rejected' => 0,
                ],
                'categories' => collect([]),
            ]);
        }
        
        // Filter by tab
        switch ($tab) {
            case 'pending':
                $query->where('status', 'pending_approval');
                break;
            case 'ai_flagged':
                $query->where('requires_admin_review', true);
                break;
            case 'active':
                $query->where('status', 'active');
                break;
            case 'sold_out':
                $query->where('status', 'sold_out');
                break;
            case 'expired':
                $query->where('status', 'expired');
                break;
            case 'rejected':
                $query->where('status', 'rejected');
                break;
            // 'all' shows everything
        }
        
        // Additional filters
        if ($request->filled('vendor_search')) {
            $search = $request->vendor_search;
            try {
                $query->whereHas('vendor', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            } catch (\Exception $e) {
                // If vendor relationship doesn't exist, skip this filter
            }
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        try {
            $deals = $query->with(['aiAnalysis'])->orderBy('created_at', 'desc')->paginate(50);
        } catch (\Exception $e) {
            $deals = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 50);
        }
        
        // Counts for tabs
        try {
            $counts = [
                'pending' => Deal::where('status', 'pending_approval')->count(),
                'ai_flagged' => Deal::where('requires_admin_review', true)->count(),
                'active' => Deal::where('status', 'active')->count(),
                'all' => Deal::count(),
                'sold_out' => Deal::where('status', 'sold_out')->count(),
                'expired' => Deal::where('status', 'expired')->count(),
                'rejected' => Deal::where('status', 'rejected')->count(),
            ];
        } catch (\Exception $e) {
            $counts = [
                'pending' => 0,
                'ai_flagged' => 0,
                'active' => 0,
                'all' => 0,
                'sold_out' => 0,
                'expired' => 0,
                'rejected' => 0,
            ];
        }
        
        try {
            $categories = \App\Models\Categories::orderBy('category_name')->get();
        } catch (\Exception $e) {
            $categories = collect([]);
        }
        
        return view('admin.deals.index', compact('deals', 'tab', 'counts', 'categories'));
    }

    /**
     * Show deal details
     */
    public function show(Deal $deal)
    {
        $deal->load(['vendor', 'category', 'approvedBy', 'aiAnalysis']);
        return view('admin.deals.show', compact('deal'));
    }

    /**
     * Approve deal
     */
    public function approve(Deal $deal)
    {
        if ($deal->status !== 'pending_approval') {
            \Session::flash('error_flash_message', 'Only pending deals can be approved.');
            return redirect()->back();
        }
        
        $deal->update([
            'status' => 'active',
            'admin_approved_at' => now(),
            'admin_approved_by' => Auth::id(),
            'requires_admin_review' => false,
        ]);
        
        ActivityLog::log(
            'deal.approved',
            "Admin approved deal: {$deal->title}",
            Auth::id(),
            'admin',
            ['deal_id' => $deal->id]
        );
        
        // Send email to vendor
        try {
            Mail::send('emails.deal_approved', ['deal' => $deal, 'user' => $deal->vendor], function ($message) use ($deal) {
                $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                $message->to($deal->vendor->email)->subject('Deal Approved - ' . getcong('site_name'));
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }
        
        \Session::flash('flash_message', 'Deal approved successfully!');
        return redirect()->back();
    }

    /**
     * Reject deal
     */
    public function reject(Request $request, Deal $deal)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);
        
        if ($deal->status !== 'pending_approval') {
            \Session::flash('error_flash_message', 'Only pending deals can be rejected.');
            return redirect()->back();
        }
        
        $deal->update([
            'status' => 'rejected',
            'admin_rejection_reason' => $request->rejection_reason,
        ]);
        
        ActivityLog::log(
            'deal.rejected',
            "Admin rejected deal: {$deal->title}",
            Auth::id(),
            'admin',
            ['deal_id' => $deal->id, 'reason' => $request->rejection_reason]
        );
        
        // Send email to vendor
        try {
            Mail::send('emails.deal_rejected', [
                'deal' => $deal, 
                'user' => $deal->vendor,
                'reason' => $request->rejection_reason
            ], function ($message) use ($deal) {
                $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                $message->to($deal->vendor->email)->subject('Deal Rejected - ' . getcong('site_name'));
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }
        
        \Session::flash('flash_message', 'Deal rejected and vendor notified.');
        return redirect()->back();
    }

    /**
     * Pause deal
     */
    public function pause(Deal $deal)
    {
        if ($deal->status !== 'active') {
            \Session::flash('error_flash_message', 'Only active deals can be paused.');
            return redirect()->back();
        }
        
        $deal->update(['status' => 'paused']);
        
        ActivityLog::log(
            'deal.paused',
            "Admin paused deal: {$deal->title}",
            Auth::id(),
            'admin',
            ['deal_id' => $deal->id]
        );
        
        \Session::flash('flash_message', 'Deal paused successfully.');
        
        return redirect()->back();
    }

    /**
     * Delete deal
     */
    public function destroy(Deal $deal)
    {
        if ($deal->inventory_sold > 0) {
            \Session::flash('error_flash_message', 'Cannot delete deal with sales.');
            return redirect()->back();
        }
        
        $deal->delete();
        
        ActivityLog::log(
            'deal.deleted',
            "Admin deleted deal: {$deal->title}",
            Auth::id(),
            'admin',
            ['deal_id' => $deal->id]
        );
        
        \Session::flash('flash_message', 'Deal deleted successfully.');
        
        return redirect()->route('admin.deals.index');
    }

    public function bulkAction(Request $request, $action)
    {
        $request->validate([
            'deal_ids' => 'required|array',
            'deal_ids.*' => 'exists:deals,id',
        ]);
        
        if (!in_array($action, ['approve', 'reject', 'pause', 'feature', 'unfeature'])) {
            return redirect()->back()->with('error', 'Invalid action');
        }

        $deals = Deal::whereIn('id', $request->deal_ids)->get();
        $count = 0;

        foreach ($deals as $deal) {
            switch ($action) {
                case 'approve':
                    if ($deal->status === 'pending_approval') {
                        $deal->update([
                            'status' => 'active',
                            'admin_approved_at' => now(),
                            'admin_approved_by' => Auth::id(),
                            'requires_admin_review' => false,
                        ]);
                        $count++;
                    }
                    break;
                case 'reject':
                    if ($deal->status === 'pending_approval') {
                        $deal->update([
                            'status' => 'rejected',
                            'admin_rejection_reason' => $request->message ?? 'Bulk rejection',
                        ]);
                        $count++;
                    }
                    break;
                case 'pause':
                    if ($deal->status === 'active') {
                        $deal->update(['status' => 'paused']);
                        $count++;
                    }
                    break;
                case 'feature':
                    $deal->update(['is_featured' => true]);
                    $count++;
                    break;
                case 'unfeature':
                    $deal->update(['is_featured' => false]);
                    $count++;
                    break;
            }
        }

        ActivityLog::log(
            "deal.bulk_{$action}",
            "Admin performed bulk {$action} on {$count} deals",
            Auth::id(),
            'admin',
            ['count' => $count, 'deal_ids' => $request->deal_ids]
        );

        return redirect()->back()->with('success', "Bulk action completed: {$count} deals {$action}d");
    }

    public function requestChanges(Request $request, Deal $deal)
    {
        $request->validate([
            'feedback' => 'required|string|min:10',
        ]);

        $vendor = $deal->vendor;
        
        try {
            Mail::send('emails.deal_changes_requested', [
                'deal' => $deal,
                'feedback' => $request->feedback,
                'vendor_name' => $vendor->first_name . ' ' . $vendor->last_name,
            ], function($message) use ($vendor) {
                $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                $message->to($vendor->email)->subject('Changes Requested for Your Deal - ' . getcong('site_name'));
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send changes request email: ' . $e->getMessage());
        }

        ActivityLog::log(
            'deal.changes_requested',
            "Admin requested changes for deal: {$deal->title}",
            Auth::id(),
            'admin',
            ['deal_id' => $deal->id]
        );

        return redirect()->back()->with('success', 'Feedback sent to vendor');
    }

    public function feature(Deal $deal)
    {
        $deal->update(['is_featured' => true]);
        
        ActivityLog::log(
            'deal.featured',
            "Admin featured deal: {$deal->title}",
            Auth::id(),
            'admin',
            ['deal_id' => $deal->id]
        );

        return redirect()->back()->with('success', 'Deal featured on homepage');
    }

    public function unfeature(Deal $deal)
    {
        $deal->update(['is_featured' => false]);
        
        ActivityLog::log(
            'deal.unfeatured',
            "Admin unfeatured deal: {$deal->title}",
            Auth::id(),
            'admin',
            ['deal_id' => $deal->id]
        );

        return redirect()->back()->with('success', 'Deal removed from homepage');
    }
}

