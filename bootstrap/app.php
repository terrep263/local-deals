<?php

use App\Jobs\CalculateMonthlyVendorStats;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            // \App\Http\Middleware\TrustHosts::class,
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SessionTimeout::class . ':30',
        ]);

        $middleware->group('api', [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'subscription.feature' => \App\Http\Middleware\CheckSubscriptionFeature::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'vendor' => \App\Http\Middleware\EnsureVendor::class,
            'vendor.onboarded' => \App\Http\Middleware\EnsureVendorOnboarded::class,
            'vendor.capacity' => \App\Http\Middleware\CheckVoucherCapacity::class,
            '2fa' => \App\Http\Middleware\TwoFactorAuth::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('deals:update-statuses')->hourly();
        $schedule->command('analytics:aggregate')->dailyAt('00:30');
        $schedule->job(new CalculateMonthlyVendorStats())->monthlyOn(1, '00:00');
        $schedule->command('vendors:reset-monthly-counters')->monthlyOn(1, '00:01')->timezone('America/New_York');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
