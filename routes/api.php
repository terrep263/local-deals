<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Stripe Webhook (no auth middleware)
Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handleWebhook']);

// Analytics Tracking (no auth required)
Route::post('/track/view', [App\Http\Controllers\AnalyticsController::class, 'trackView']);
Route::post('/track/click', [App\Http\Controllers\AnalyticsController::class, 'trackClick']);

// Deal tracking routes
Route::post('/deals/{id}/track-click', [App\Http\Controllers\Api\DealTrackingController::class, 'trackClick']);
Route::post('/vendors/{id}/subscribe', [App\Http\Controllers\Api\VendorSubscriptionController::class, 'subscribe'])
    ->middleware('auth:sanctum');
