<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Deal;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Show vendor profile page
     */
    public function show($id)
    {
        $vendor = User::findOrFail($id);
        
        $activeDeals = Deal::active()
            ->where('vendor_id', $id)
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalDeals = Deal::where('vendor_id', $id)->count();
        $memberSince = $vendor->created_at;
        
        return view('vendor.profile', compact('vendor', 'activeDeals', 'totalDeals', 'memberSince'));
    }
}


