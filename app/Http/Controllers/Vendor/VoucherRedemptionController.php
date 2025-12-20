<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherRedemptionController extends Controller
{
    /**
     * Display vendor's vouchers dashboard
     */
    public function index(Request $request)
    {
        $vendorId = Auth::id();
        
        // Get all deals belonging to this vendor
        $dealIds = Deal::where('user_id', $vendorId)->pluck('id');
        
        // Build query for vouchers
        $query = DealPurchase::whereIn('deal_id', $dealIds)
            ->with('deal')
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'redeemed') {
                $query->whereNotNull('redeemed_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('redeemed_at');
            }
        }
        
        if ($request->filled('deal_id')) {
            $query->where('deal_id', $request->deal_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('confirmation_code', 'LIKE', "%{$search}%")
                  ->orWhere('consumer_email', 'LIKE', "%{$search}%")
                  ->orWhere('consumer_name', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }
        
        $vouchers = $query->paginate(20)->withQueryString();
        
        // Get stats
        $stats = [
            'total' => DealPurchase::whereIn('deal_id', $dealIds)->count(),
            'redeemed' => DealPurchase::whereIn('deal_id', $dealIds)->whereNotNull('redeemed_at')->count(),
            'pending' => DealPurchase::whereIn('deal_id', $dealIds)->whereNull('redeemed_at')->count(),
            'today' => DealPurchase::whereIn('deal_id', $dealIds)->whereDate('purchase_date', today())->count(),
            'redeemed_today' => DealPurchase::whereIn('deal_id', $dealIds)->whereDate('redeemed_at', today())->count(),
        ];
        
        // Get vendor's deals for filter dropdown
        $deals = Deal::where('user_id', $vendorId)
            ->select('id', 'title')
            ->orderBy('title')
            ->get();
        
        return view('vendor.vouchers.index', compact('vouchers', 'stats', 'deals'));
    }
    
    /**
     * Show the redemption page
     */
    public function showRedeemForm()
    {
        return view('vendor.vouchers.redeem');
    }
    
    /**
     * Look up a voucher by confirmation code
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:6|max:12'
        ]);
        
        $code = strtoupper(trim($request->code));
        $vendorId = Auth::id();
        
        // Find the voucher
        $voucher = DealPurchase::where('confirmation_code', $code)
            ->with('deal')
            ->first();
        
        if (!$voucher) {
            return back()->with('error', 'Voucher not found. Please check the confirmation code and try again.');
        }
        
        // Verify this voucher belongs to the vendor's deal
        if ($voucher->deal->user_id !== $vendorId) {
            return back()->with('error', 'This voucher is not for one of your deals.');
        }
        
        return view('vendor.vouchers.redeem', [
            'voucher' => $voucher,
            'code' => $code
        ]);
    }
    
    /**
     * Mark a voucher as redeemed
     */
    public function markRedeemed(Request $request, $id)
    {
        $vendorId = Auth::id();
        
        $voucher = DealPurchase::with('deal')->findOrFail($id);
        
        // Verify ownership
        if ($voucher->deal->user_id !== $vendorId) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        // Check if already redeemed
        if ($voucher->isRedeemed()) {
            return back()->with('error', 'This voucher has already been redeemed on ' . $voucher->redeemed_at->format('M d, Y \a\t g:i A'));
        }
        
        // Mark as redeemed
        $voucher->markAsRedeemed();
        
        // Add redemption note if provided
        if ($request->filled('notes')) {
            $voucher->update(['notes' => $request->notes]);
        }
        
        return redirect()->route('vendor.vouchers.index')
            ->with('flash_message', 'Voucher ' . $voucher->confirmation_code . ' has been successfully redeemed!');
    }
    
    /**
     * Show single voucher details
     */
    public function show($id)
    {
        $vendorId = Auth::id();
        
        $voucher = DealPurchase::with('deal')->findOrFail($id);
        
        // Verify ownership
        if ($voucher->deal->user_id !== $vendorId) {
            abort(403, 'Unauthorized');
        }
        
        return view('vendor.vouchers.show', compact('voucher'));
    }
    
    /**
     * AJAX endpoint for quick lookup
     */
    public function ajaxLookup(Request $request)
    {
        $code = strtoupper(trim($request->code));
        $vendorId = Auth::id();
        
        $voucher = DealPurchase::where('confirmation_code', $code)
            ->with('deal')
            ->first();
        
        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher not found']);
        }
        
        if ($voucher->deal->user_id !== $vendorId) {
            return response()->json(['success' => false, 'message' => 'This voucher is not for your deal']);
        }
        
        return response()->json([
            'success' => true,
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->confirmation_code,
                'deal_title' => $voucher->deal->title,
                'customer_name' => $voucher->consumer_name,
                'customer_email' => $voucher->consumer_email,
                'amount' => number_format($voucher->purchase_amount, 2),
                'purchase_date' => $voucher->purchase_date->format('M d, Y'),
                'is_redeemed' => $voucher->isRedeemed(),
                'redeemed_at' => $voucher->redeemed_at ? $voucher->redeemed_at->format('M d, Y g:i A') : null,
            ]
        ]);
    }
}
