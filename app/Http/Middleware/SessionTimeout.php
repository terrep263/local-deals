<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $timeout  Timeout in minutes (default: 30)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $timeout = 30)
    {
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity_time');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity > $timeout * 60)) {
                Auth::logout();
                Session::flush();
                
                return redirect()->route('login')
                    ->with('error', 'Your session has expired due to inactivity. Please login again.');
            }

            Session::put('last_activity_time', $currentTime);
        }

        return $next($request);
    }
}
