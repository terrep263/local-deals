<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Only enforce 2FA for admin users
        if ($user && $user->usertype === 'admin' && $user->two_factor_enabled) {
            // Check if user has completed 2FA verification in this session
            if (!$request->session()->get('2fa_verified', false)) {
                return redirect()->route('2fa.verify')
                    ->with('error', 'Please complete two-factor authentication.');
            }
        }

        return $next($request);
    }
}
