<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeVendorEmail;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VendorManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $tiers = [
            'founder_free' => 'Founder Free',
            'founder_growth' => 'Founder Growth',
            'basic' => 'Basic',
            'pro' => 'Pro',
            'enterprise' => 'Enterprise',
        ];

        $categories = [
            'restaurant' => 'Restaurant',
            'auto' => 'Auto Service',
            'health' => 'Health & Beauty',
            'home' => 'Home Services',
            'entertainment' => 'Entertainment',
        ];

        $query = VendorProfile::with('user');

        if ($request->boolean('founders')) {
            $query->where('is_founder', true);
        }

        if ($tier = $request->input('tier')) {
            $query->where('subscription_tier', $tier);
        }

        if ($category = $request->input('category')) {
            $query->where('business_category', $category);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $vendors = $query->paginate(15)->withQueryString();

        return view('admin.vendors.index', compact('vendors', 'tiers', 'categories'));
    }

    public function create()
    {
        $categories = [
            'restaurant' => 'Restaurant',
            'auto' => 'Auto Service',
            'health' => 'Health & Beauty',
            'home' => 'Home Services',
            'entertainment' => 'Entertainment',
        ];

        $founderTiers = [
            'founder_free' => 'Founder Free (100/mo)',
            'founder_growth' => 'Founder Growth ($35/mo, 300/mo)',
        ];

        $standardTiers = [
            'basic' => 'Basic ($49/mo, 600/mo)',
            'pro' => 'Pro ($99/mo, 2000/mo)',
            'enterprise' => 'Enterprise ($199/mo, Unlimited)',
        ];

        return view('admin.vendors.create', compact('categories', 'founderTiers', 'standardTiers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'business_name' => 'required|string|max:255',
            'business_phone' => ['required', 'regex:/^(\\+1\\s?)?\\(?\\d{3}\\)?[\\s.-]?\\d{3}[\\s.-]?\\d{4}$/'],
            'business_address' => 'required|string',
            'business_city' => 'required|string|max:255',
            'business_state' => 'required|string|max:255',
            'business_zip' => 'nullable|string|max:20',
            'business_category' => 'required|in:restaurant,auto,health,home,entertainment',
            'is_founder' => 'sometimes|boolean',
            'subscription_tier' => 'required|string',
        ]);

        $isFounder = (bool) $request->boolean('is_founder');
        $subscriptionTier = $this->validatedTier($data['subscription_tier'], $isFounder);
        $voucherLimit = $this->voucherLimitForTier($subscriptionTier);
        $generatedPassword = Str::random(12);

        DB::transaction(function () use ($data, $isFounder, $subscriptionTier, $voucherLimit, $generatedPassword) {
            $user = User::create([
                'usertype' => 'Vendor',
                'first_name' => $data['business_name'],
                'last_name' => '',
                'email' => $data['email'],
                'password' => Hash::make($generatedPassword),
                'account_status' => 'active',
            ]);

            VendorProfile::create([
                'user_id' => $user->id,
                'business_name' => $data['business_name'],
                'business_address' => $data['business_address'],
                'business_city' => $data['business_city'] ?: 'Lake County',
                'business_state' => $data['business_state'] ?: 'FL',
                'business_zip' => $data['business_zip'] ?? null,
                'business_phone' => $data['business_phone'],
                'business_category' => $data['business_category'],
                'subscription_tier' => $subscriptionTier,
                'monthly_voucher_limit' => $voucherLimit,
                'vouchers_used_this_month' => 0,
                'is_founder' => $isFounder,
                'onboarding_completed' => false,
                'profile_completed' => false,
            ]);

            Mail::to($user->email)->send(new WelcomeVendorEmail($user, $generatedPassword));

            session()->flash('generated_password', $generatedPassword);
        });

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor created and welcome email sent.');
    }

    public function edit($id)
    {
        $vendor = VendorProfile::with('user')->findOrFail($id);

        $categories = [
            'restaurant' => 'Restaurant',
            'auto' => 'Auto Service',
            'health' => 'Health & Beauty',
            'home' => 'Home Services',
            'entertainment' => 'Entertainment',
        ];

        $founderTiers = [
            'founder_free' => 'Founder Free (100/mo)',
            'founder_growth' => 'Founder Growth ($35/mo, 300/mo)',
        ];

        $standardTiers = [
            'basic' => 'Basic ($49/mo, 600/mo)',
            'pro' => 'Pro ($99/mo, 2000/mo)',
            'enterprise' => 'Enterprise ($199/mo, Unlimited)',
        ];

        return view('admin.vendors.edit', compact('vendor', 'categories', 'founderTiers', 'standardTiers'));
    }

    public function show($id)
    {
        return redirect()->route('admin.vendors.edit', $id);
    }

    public function update(Request $request, $id)
    {
        $vendor = VendorProfile::with('user')->findOrFail($id);

        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_phone' => ['required', 'regex:/^(\\+1\\s?)?\\(?\\d{3}\\)?[\\s.-]?\\d{3}[\\s.-]?\\d{4}$/'],
            'business_address' => 'required|string',
            'business_city' => 'required|string|max:255',
            'business_state' => 'required|string|max:255',
            'business_zip' => 'nullable|string|max:20',
            'business_category' => 'required|in:restaurant,auto,health,home,entertainment',
            'is_founder' => 'sometimes|boolean',
            'subscription_tier' => 'required|string',
            'reset_password' => 'sometimes|boolean',
        ]);

        $isFounder = (bool) $request->boolean('is_founder');
        $subscriptionTier = $this->validatedTier($data['subscription_tier'], $isFounder);
        $voucherLimit = $this->voucherLimitForTier($subscriptionTier);
        $resetPassword = $request->boolean('reset_password');

        $newPassword = null;

        DB::transaction(function () use ($data, $vendor, $isFounder, $subscriptionTier, $voucherLimit, &$newPassword, $resetPassword) {
            $vendor->update([
                'business_name' => $data['business_name'],
                'business_address' => $data['business_address'],
                'business_city' => $data['business_city'],
                'business_state' => $data['business_state'],
                'business_zip' => $data['business_zip'] ?? null,
                'business_phone' => $data['business_phone'],
                'business_category' => $data['business_category'],
                'subscription_tier' => $subscriptionTier,
                'monthly_voucher_limit' => $voucherLimit,
                'is_founder' => $isFounder,
            ]);

            $vendor->user->update([
                'first_name' => $data['business_name'],
            ]);

            if ($resetPassword) {
                $newPassword = Str::random(12);
                $vendor->user->update([
                    'password' => Hash::make($newPassword),
                ]);
                Mail::to($vendor->user->email)->send(new WelcomeVendorEmail($vendor->user, $newPassword));
                session()->flash('generated_password', $newPassword);
            }
        });

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy($id)
    {
        $vendor = VendorProfile::with('user')->findOrFail($id);
        $vendor->delete();

        if ($vendor->user) {
            $vendor->user->account_status = 'banned';
            $vendor->user->save();
        }

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor deactivated.');
    }

    private function validatedTier(string $tier, bool $isFounder): string
    {
        $founderTiers = ['founder_free', 'founder_growth'];
        $standardTiers = ['basic', 'pro', 'enterprise'];

        if ($isFounder && in_array($tier, $founderTiers, true)) {
            return $tier;
        }

        if (!$isFounder && in_array($tier, $standardTiers, true)) {
            return $tier;
        }

        throw ValidationException::withMessages([
            'subscription_tier' => 'Invalid subscription tier for the selected founder status.',
        ]);
    }

    private function voucherLimitForTier(string $tier): int
    {
        return match ($tier) {
            'founder_free' => 100,
            'founder_growth' => 300,
            'basic' => 600,
            'pro' => 2000,
            'enterprise' => 999999,
            default => 100,
        };
    }
}

