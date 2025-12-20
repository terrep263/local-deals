<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $vendor = Auth::user();
        $dealIds = Deal::where('user_id', $vendor->id)->pluck('id');
        
        $query = DealPurchase::whereIn('deal_id', $dealIds)
            ->with('deal')
            ->orderBy('created_at', 'desc');
        
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
                $q->where('confirmation_code', 'like', "%{$search}%")
                  ->orWhere('consumer_email', 'like', "%{$search}%")
                  ->orWhere('consumer_name', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }
        
        $vouchers = $query->paginate(20)->withQueryString();
        
        $deals = Deal::where('user_id', $vendor->id)
            ->orderBy('title')
            ->get(['id', 'title']);
        
        $stats = [
            'total' => DealPurchase::whereIn('deal_id', $dealIds)->count(),
            'pending' => DealPurchase::whereIn('deal_id', $dealIds)->whereNull('redeemed_at')->count(),
            'redeemed' => DealPurchase::whereIn('deal_id', $dealIds)->whereNotNull('redeemed_at')->count(),
            'today' => DealPurchase::whereIn('deal_id', $dealIds)->whereDate('purchase_date', today())->count(),
        ];
        
        return view('vendor.vouchers.index', compact('vouchers', 'deals', 'stats'));
    }
    
    public function showRedeemForm()
    {
        return view('vendor.vouchers.redeem');
    }
    
    public function lookup(Request $request)
    {
        $request->validate(['code' => 'required|string|min:6|max:10']);
        
        $code = strtoupper(trim($request->code));
        $vendor = Auth::user();
        $dealIds = Deal::where('user_id', $vendor->id)->pluck('id');
        
        $purchase = DealPurchase::whereIn('deal_id', $dealIds)
            ->where('confirmation_code', $code)
            ->with('deal')
            ->first();
        
        if (!$purchase) {
            return back()->with('error', 'Voucher not found. Please check the code and try again.');
        }
        
        return view('vendor.vouchers.show', compact('purchase'));
    }
    
    public function redeem(Request $request, $code)
    {
        $vendor = Auth::user();
        $code = strtoupper(trim($code));
        $dealIds = Deal::where('user_id', $vendor->id)->pluck('id');
        
        $purchase = DealPurchase::whereIn('deal_id', $dealIds)
            ->where('confirmation_code', $code)
            ->first();
        
        if (!$purchase) {
            return back()->with('error', 'Voucher not found.');
        }
        
        if ($purchase->isRedeemed()) {
            return back()->with('error', 'This voucher has already been redeemed on ' . $purchase->redeemed_at->format('M d, Y \a\t g:i A'));
        }
        
        if ($request->filled('notes')) {
            $purchase->notes = $request->notes;
        }
        
        $purchase->markAsRedeemed();
        
        return redirect()->route('vendor.vouchers.index')
            ->with('flash_message', 'Voucher ' . $code . ' has been successfully redeemed!');
    }
    
    public function show($code)
    {
        $vendor = Auth::user();
        $code = strtoupper(trim($code));
        $dealIds = Deal::where('user_id', $vendor->id)->pluck('id');
        
        $purchase = DealPurchase::whereIn('deal_id', $dealIds)
            ->where('confirmation_code', $code)
            ->with('deal')
            ->first();
        
        if (!$purchase) {
            return redirect()->route('vendor.vouchers.index')->with('error', 'Voucher not found.');
        }
        
        return view('vendor.vouchers.show', compact('purchase'));
    }
}
