<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating a vendor
     */
    public function start($vendorId)
    {
        // Ensure only admins can impersonate
        if (!Auth::user() || !Auth::user()->usertype === 'Admin') {
            abort(403, 'Unauthorized action.');
        }

        // Find the vendor
        $vendor = User::where('id', $vendorId)
                      ->where('usertype', 'Vendor')
                      ->firstOrFail();

        // Store original admin ID
        session([
            'impersonating' => true,
            'original_user_id' => Auth::id(),
            'impersonated_user_id' => $vendor->id,
            'impersonated_vendor_name' => $vendor->business_name ?? $vendor->name,
            'impersonated_vendor_email' => $vendor->email
        ]);

        // Log the impersonation
        Log::info('Admin impersonation started', [
            'admin_id' => Auth::id(),
            'admin_email' => Auth::user()->email,
            'vendor_id' => $vendor->id,
            'vendor_email' => $vendor->email
        ]);

        // Login as vendor
        Auth::login($vendor);

        return redirect()->route('vendor.dashboard')
                        ->with('success', 'Now viewing as: ' . $vendor->business_name);
    }

    /**
     * Stop impersonating and return to admin
     */
    public function stop()
    {
        if (!session('impersonating')) {
            return redirect()->route('admin.dashboard');
        }

        $originalUserId = session('original_user_id');
        $impersonatedUserId = session('impersonated_user_id');

        // Log the end of impersonation
        Log::info('Admin impersonation stopped', [
            'admin_id' => $originalUserId,
            'vendor_id' => $impersonatedUserId
        ]);

        // Clear impersonation data
        session()->forget(['impersonating', 'original_user_id', 'impersonated_user_id', 'impersonated_vendor_name', 'impersonated_vendor_email']);

        // Login back as admin
        $admin = User::findOrFail($originalUserId);
        Auth::login($admin);

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Returned to admin panel');
    }

    /**
     * Get list of vendors for dropdown (AJAX)
     */
    public function getVendors(Request $request)
    {
        // Ensure only admins can access
        if (!Auth::user() || Auth::user()->usertype !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = User::where('usertype', 'Vendor')
                    ->where('status', 1); // Only active vendors

        // If search term provided
        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $vendors = $query->select('id', 'business_name', 'name', 'email', 'subscription_tier')
                        ->orderBy('business_name')
                        ->limit(50)
                        ->get()
                        ->map(function($vendor) {
                            return [
                                'id' => $vendor->id,
                                'business_name' => $vendor->business_name ?? $vendor->name,
                                'email' => $vendor->email,
                                'subscription_tier' => $vendor->subscription_tier ?? 'Free'
                            ];
                        });

        return response()->json(['vendors' => $vendors]);
    }
}
