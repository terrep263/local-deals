<?php

namespace App\Http\Middleware;

use App\Models\Deal;
use Closure;
use Illuminate\Http\Request;

class CheckVoucherCapacity
{
    public function handle(Request $request, Closure $next)
    {
        $dealParam = $request->route('deal') ?? $request->route('id') ?? $request->route('slug');

        $deal = null;
        if ($dealParam instanceof Deal) {
            $deal = $dealParam;
        } elseif (is_numeric($dealParam)) {
            $deal = Deal::find($dealParam);
        } elseif (is_string($dealParam)) {
            $deal = Deal::where('slug', $dealParam)->first();
        }

        if (!$deal) {
            return $next($request);
        }

        $vendor = $deal->vendor?->vendorProfile;

        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor profile not found');
        }

        if ($vendor->hasReachedCapacity()) {
            $vendor->pauseAllDeals();

            return redirect()->back()->with('error', 'This vendor has reached their monthly voucher limit. Deal is temporarily unavailable.');
        }

        return $next($request);
    }
}

