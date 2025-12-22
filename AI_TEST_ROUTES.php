<?php

// This is a reference implementation file for testing the AI Deal Writer system
// You can add these routes temporarily for testing, then remove them

// Add to routes/web.php temporarily for testing:

/*
// Test Claude AI Service
Route::get('/test-ai-service', function() {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    try {
        $service = app(\App\Services\ClaudeAIService::class);
        
        $result = $service->analyzeDeal([
            'title' => 'Luxury Spa Package - 60% OFF',
            'description' => 'Enjoy a relaxing 90-minute full-body massage at Serenity Day Spa. Includes steam room and aromatherapy. First-time customers only. Valid through March 31, 2025. Must book online or call 407-555-0123.',
            'original_price' => 199,
            'sale_price' => 79,
            'category' => 'Spa & Beauty'
        ], auth()->id());
        
        return response()->json($result, 200, [], JSON_PRETTY_PRINT);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->middleware('auth');

// Test database models
Route::get('/test-ai-models', function() {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    return [
        'ai_deal_analyses_table' => \Illuminate\Support\Facades\Schema::hasTable('ai_deal_analyses'),
        'ai_usage_tracking_table' => \Illuminate\Support\Facades\Schema::hasTable('ai_usage_tracking'),
        'analyses_count' => \App\Models\AIDealAnalysis::count(),
        'usage_tracking_count' => \App\Models\AIUsageTracking::count(),
        'current_user_remaining' => app(\App\Services\ClaudeAIService::class)->getRemainingUsage(auth()->id(), 'deal_writer')
    ];
})->middleware('auth');

// Test remaining analyses
Route::get('/test-ai-remaining', function() {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $service = app(\App\Services\ClaudeAIService::class);
    
    return [
        'user_id' => auth()->id(),
        'remaining_today' => $service->getRemainingUsage(auth()->id(), 'deal_writer'),
        'max_per_day' => 10
    ];
})->middleware('auth');
*/

// Notes for developers:
// 1. The above routes are for testing only
// 2. Remove them before pushing to production
// 3. All functionality is available through the normal AJAX endpoints:
//    - POST /vendor/ai/analyze-deal
//    - GET /vendor/ai/remaining
//    - POST /vendor/ai/analysis/{analysis}/accept
// 4. The widget automatically handles all interactions
// 5. Check browser console (F12) for any JavaScript errors
// 6. Check Laravel logs at storage/logs/laravel.log for any server errors
