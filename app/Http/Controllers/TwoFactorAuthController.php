<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show 2FA setup page
     */
    public function showSetup()
    {
        $user = Auth::user();

        if ($user->two_factor_enabled) {
            return redirect()->route('admin.index')
                ->with('info', 'Two-factor authentication is already enabled.');
        }

        // Generate secret
        $secret = $this->google2fa->generateSecretKey();
        
        // Generate QR code URL
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Generate recovery codes
        $recoveryCodes = $this->generateRecoveryCodes();

        return view('auth.2fa-setup', [
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Enable 2FA
     */
    public function enable(Request $request)
    {
        $request->validate([
            'secret' => 'required',
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $valid = $this->google2fa->verifyKey($request->secret, $request->code);

        if (!$valid) {
            return back()->with('error', 'Invalid authentication code.');
        }

        // Encrypt and save secret
        $user->two_factor_secret = Crypt::encryptString($request->secret);
        $user->two_factor_recovery_codes = Crypt::encryptString($request->recovery_codes);
        $user->two_factor_enabled = true;
        $user->two_factor_confirmed_at = now();
        $user->save();

        // Mark as verified in session
        $request->session()->put('2fa_verified', true);

        return redirect()->route('admin.index')
            ->with('success', 'Two-factor authentication has been enabled successfully.');
    }

    /**
     * Show 2FA verification page
     */
    public function showVerify()
    {
        return view('auth.2fa-verify');
    }

    /**
     * Verify 2FA code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = Auth::user();

        // Try regular code first
        if (strlen($request->code) === 6) {
            $secret = Crypt::decryptString($user->two_factor_secret);
            $valid = $this->google2fa->verifyKey($secret, $request->code);

            if ($valid) {
                $request->session()->put('2fa_verified', true);
                return redirect()->intended(route('admin.index'));
            }
        }

        // Try recovery code
        $recoveryCodes = json_decode(Crypt::decryptString($user->two_factor_recovery_codes), true);
        
        if (in_array($request->code, $recoveryCodes)) {
            // Remove used recovery code
            $recoveryCodes = array_diff($recoveryCodes, [$request->code]);
            $user->two_factor_recovery_codes = Crypt::encryptString(json_encode(array_values($recoveryCodes)));
            $user->save();

            $request->session()->put('2fa_verified', true);
            
            return redirect()->intended(route('admin.index'))
                ->with('warning', 'You used a recovery code. You have ' . count($recoveryCodes) . ' recovery codes remaining.');
        }

        return back()->with('error', 'Invalid authentication code.');
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return back()->with('success', 'Two-factor authentication has been disabled.');
    }

    /**
     * Show recovery codes
     */
    public function showRecoveryCodes()
    {
        $user = Auth::user();

        if (!$user->two_factor_enabled) {
            return redirect()->route('admin.index')
                ->with('error', 'Two-factor authentication is not enabled.');
        }

        $recoveryCodes = json_decode(Crypt::decryptString($user->two_factor_recovery_codes), true);

        return view('auth.2fa-recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        $recoveryCodes = $this->generateRecoveryCodes();
        $user->two_factor_recovery_codes = Crypt::encryptString(json_encode($recoveryCodes));
        $user->save();

        return back()->with('success', 'Recovery codes have been regenerated.');
    }

    /**
     * Generate recovery codes
     */
    private function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(md5(random_bytes(16)), 0, 10));
        }
        return $codes;
    }
}
