<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ClaudeAIService;
use App\Models\AIDealAnalysis;
use Illuminate\Support\Facades\Validator;

class AIDealAnalyzerController extends Controller
{
    private ClaudeAIService $aiService;
    
    public function __construct(ClaudeAIService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth');
        // Assuming vendor middleware exists; if not, remove this line
        // $this->middleware('vendor');
    }
    
    /**
     * Analyze a deal (AJAX endpoint)
     */
    public function analyze(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'deal_id' => 'nullable|exists:deals,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Validate pricing logic
        if ($request->input('sale_price') >= $request->input('original_price')) {
            return response()->json([
                'success' => false,
                'error' => 'Sale price must be less than original price.'
            ], 422);
        }
        
        // Get deal data
        $dealData = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'original_price' => (float) $request->input('original_price'),
            'sale_price' => (float) $request->input('sale_price'),
            'category' => $request->input('category')
        ];
        
        // Analyze with Claude AI
        $analysis = $this->aiService->analyzeDeal($dealData, auth()->id());
        
        // If successful, save to database
        if ($analysis['success']) {
            $aiAnalysis = AIDealAnalysis::create([
                'deal_id' => $request->input('deal_id'),
                'user_id' => auth()->id(),
                'title' => $dealData['title'],
                'description' => $dealData['description'],
                'original_price' => $dealData['original_price'],
                'sale_price' => $dealData['sale_price'],
                'title_score' => $analysis['title_score'],
                'description_score' => $analysis['description_score'],
                'pricing_score' => $analysis['pricing_score'],
                'overall_score' => $analysis['overall_score'],
                'suggestions' => json_encode($analysis['suggestions']),
                'improved_title' => $analysis['improved_title'],
                'improved_description' => $analysis['improved_description'],
                'ai_model' => 'claude-sonnet-4',
                'tokens_used' => $analysis['tokens_used'],
                'processing_time_ms' => $analysis['processing_time_ms']
            ]);
            
            $analysis['analysis_id'] = $aiAnalysis->id;
        }
        
        return response()->json($analysis);
    }
    
    /**
     * Get remaining AI usage for today
     */
    public function remaining()
    {
        $remaining = $this->aiService->getRemainingUsage(auth()->id(), 'deal_writer');
        
        return response()->json([
            'remaining' => $remaining,
            'max' => 10
        ]);
    }
    
    /**
     * Mark analysis as accepted (vendor used suggestions)
     */
    public function accept(Request $request, AIDealAnalysis $analysis)
    {
        // Verify ownership
        if ($analysis->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $analysis->update(['was_accepted' => true]);
        
        return response()->json(['success' => true]);
    }
}
