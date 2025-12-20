<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureVendorOnboarded
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->isVendor()) {
            return redirect()->route('login');
        }

        $vendorProfile = $user->vendorProfile;

        if (!$vendorProfile || !$vendorProfile->onboarding_completed) {
            return redirect()->route('vendor.onboarding.index')->with('error', 'Please complete onboarding to continue.');
        }

        return $next($request);
    }
}

