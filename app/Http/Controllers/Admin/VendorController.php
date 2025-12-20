<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deal;
use App\Models\DealPurchase;
use App\Models\Subscription;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('usertype', '!=', 'admin')
            ->with(['activeSubscription', 'deals']);

        // Filters
        if ($request->filled('tier')) {
            $query->whereHas('activeSubscription', function($q) use ($request) {
                $q->where('package_tier', $request->tier);
            });
        }

        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $vendors = $query->paginate(20);

        // Add stats to each vendor
        $vendors->getCollection()->transform(function($vendor) {
            $vendor->active_deals_count = $vendor->deals()->where('status', 'active')->count();
            $vendor->total_revenue = DealPurchase::whereIn('deal_id', $vendor->deals()->pluck('id'))
                ->sum('purchase_amount');
            return $vendor;
        });

        return view('admin.vendors.index', compact('vendors'));
    }

    public function show($id)
    {
        $vendor = User::with(['activeSubscription', 'deals', 'subscriptions'])
            ->findOrFail($id);

        $stats = [
            'total_deals' => $vendor->deals()->count(),
            'active_deals' => $vendor->deals()->where('status', 'active')->count(),
            'total_revenue' => DealPurchase::whereIn('deal_id', $vendor->deals()->pluck('id'))
                ->sum('purchase_amount'),
            'total_sales' => DealPurchase::whereIn('deal_id', $vendor->deals()->pluck('id'))->count(),
            'approval_rate' => $this->calculateApprovalRate($vendor),
        ];

        $recentDeals = $vendor->deals()->latest()->take(10)->get();
        $recentActivity = ActivityLog::where('user_id', $vendor->id)
            ->latest()
            ->take(20)
            ->get();

        return view('admin.vendors.show', compact('vendor', 'stats', 'recentDeals', 'recentActivity'));
    }

    public function suspend(Request $request, $id)
    {
        $vendor = User::findOrFail($id);
        
        $vendor->update([
            'account_status' => 'suspended',
            'suspended_at' => now(),
            'suspension_reason' => $request->reason,
        ]);

        ActivityLog::log(
            'vendor.suspended',
            "Admin suspended vendor {$vendor->first_name} {$vendor->last_name}",
            auth()->id(),
            'admin',
            ['vendor_id' => $vendor->id, 'reason' => $request->reason]
        );

        return redirect()->back()->with('success', 'Vendor suspended successfully');
    }

    public function ban(Request $request, $id)
    {
        $vendor = User::findOrFail($id);
        
        $vendor->update([
            'account_status' => 'banned',
            'suspended_at' => now(),
            'suspension_reason' => $request->reason,
        ]);

        ActivityLog::log(
            'vendor.banned',
            "Admin banned vendor {$vendor->first_name} {$vendor->last_name}",
            auth()->id(),
            'admin',
            ['vendor_id' => $vendor->id, 'reason' => $request->reason]
        );

        return redirect()->back()->with('success', 'Vendor banned successfully');
    }

    public function activate($id)
    {
        $vendor = User::findOrFail($id);
        
        $vendor->update([
            'account_status' => 'active',
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        ActivityLog::log(
            'vendor.activated',
            "Admin activated vendor {$vendor->first_name} {$vendor->last_name}",
            auth()->id(),
            'admin',
            ['vendor_id' => $vendor->id]
        );

        return redirect()->back()->with('success', 'Vendor activated successfully');
    }

    public function updateNotes(Request $request, $id)
    {
        $vendor = User::findOrFail($id);
        $vendor->update(['admin_notes' => $request->notes]);

        ActivityLog::log(
            'vendor.notes_updated',
            "Admin updated notes for vendor {$vendor->first_name} {$vendor->last_name}",
            auth()->id(),
            'admin',
            ['vendor_id' => $vendor->id]
        );

        return redirect()->back()->with('success', 'Notes updated');
    }

    private function calculateApprovalRate($vendor)
    {
        $total = $vendor->deals()->count();
        $approved = $vendor->deals()->where('status', 'active')->count();
        
        return $total > 0 ? round(($approved / $total) * 100, 1) : 0;
    }
}


