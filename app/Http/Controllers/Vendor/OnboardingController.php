<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorProfile;
use App\Services\StripeConnectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OnboardingController extends Controller
{
    protected StripeConnectService $stripeConnect;

    public function __construct(StripeConnectService $stripeConnect)
    {
        $this->stripeConnect = $stripeConnect;
    }

    public function index(Request $request)
    {
        $vendor = $this->getVendorProfile($request->user());

        $stripeConnected = !empty($vendor->stripe_account_id);
        $profileCompleted = (bool) $vendor->profile_completed;

        $progress = match (true) {
            $stripeConnected && $profileCompleted => 100,
            $stripeConnected || $profileCompleted => 50,
            default => 0,
        };

        return view('vendor.onboarding.index', compact('vendor', 'stripeConnected', 'profileCompleted', 'progress'));
    }

    public function connectStripe(Request $request)
    {
        $vendor = $this->getVendorProfile($request->user());

        if (!$this->hasStripeConfig()) {
            return redirect()->route('vendor.onboarding.index')->with('error', 'Stripe Connect is not configured. Please contact support.');
        }

        $url = $this->stripeConnect->generateConnectUrl($vendor);

        return Redirect::away($url);
    }

    public function stripeCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('vendor.onboarding.index')->with('error', 'Stripe connection was cancelled.');
        }

        $state = $request->input('state');
        $code = $request->input('code');

        if (!$state || !$code) {
            return redirect()->route('vendor.onboarding.index')->with('error', 'Invalid Stripe response.');
        }

        try {
            $payload = decrypt($state);
        } catch (\Throwable $e) {
            return redirect()->route('vendor.onboarding.index')->with('error', 'Invalid Stripe state parameter.');
        }

        $vendor = VendorProfile::with('user')->find($payload['vendor_id'] ?? null);

        if (!$vendor || $vendor->user_id !== $request->user()->id) {
            return redirect()->route('vendor.onboarding.index')->with('error', 'Stripe connection could not be verified.');
        }

        $result = $this->stripeConnect->handleCallback($code);

        $vendor->update([
            'stripe_account_id' => $result['stripe_user_id'],
            'stripe_connected_at' => now(),
            'onboarding_completed' => $vendor->profile_completed ? true : $vendor->onboarding_completed,
        ]);

        return redirect()->route('vendor.onboarding.index')->with('success', 'Stripe account connected successfully.');
    }

    public function showProfileForm(Request $request)
    {
        $vendor = $this->getVendorProfile($request->user());

        return view('vendor.onboarding.profile', compact('vendor'));
    }

    public function saveProfile(Request $request)
    {
        $vendor = $this->getVendorProfile($request->user());

        $data = $request->validate([
            'business_description' => 'required|string',
            'business_logo' => 'nullable|image|max:2048',
            'business_hours' => 'nullable|array',
            'business_hours.*.open' => 'nullable|string',
            'business_hours.*.close' => 'nullable|string',
        ]);

        $businessHours = [];
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        foreach ($days as $day) {
            $open = $data['business_hours'][$day]['open'] ?? null;
            $close = $data['business_hours'][$day]['close'] ?? null;
            if ($open || $close) {
                $businessHours[$day] = ['open' => $open, 'close' => $close];
            }
        }

        if ($request->hasFile('business_logo')) {
            $path = $request->file('business_logo')->store('vendor_logos', 'public');
            $vendor->business_logo = $path;
        }

        $vendor->business_description = $data['business_description'];
        $vendor->business_hours = $businessHours;
        $vendor->profile_completed = true;

        if ($vendor->stripe_account_id) {
            $vendor->onboarding_completed = true;
        }

        $vendor->save();

        if ($vendor->onboarding_completed) {
            return redirect()->route('vendor.deals.index')->with('success', 'Profile saved and onboarding completed.');
        }

        return redirect()->route('vendor.onboarding.index')->with('success', 'Profile saved. Please connect Stripe to finish onboarding.');
    }

    protected function getVendorProfile($user): VendorProfile
    {
        return VendorProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => $user->first_name ?? 'Business',
                'business_address' => $user->address ?? 'Address required',
                'business_city' => 'Lake County',
                'business_state' => 'FL',
                'business_phone' => $user->mobile ?? '',
                'business_category' => 'restaurant',
            ]
        );
    }

    protected function hasStripeConfig(): bool
    {
        return config('services.stripe.connect_client_id') && config('services.stripe.secret');
    }
}

