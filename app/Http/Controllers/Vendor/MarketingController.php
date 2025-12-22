<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\AIMarketingContent;
use App\Models\Deal;
use App\Models\User;
use App\Services\ClaudeAIService;
use App\Services\MarketingContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MarketingController extends Controller
{
    protected ClaudeAIService $claudeService;
    protected MarketingContentService $marketingService;

    public function __construct(ClaudeAIService $claudeService, MarketingContentService $marketingService)
    {
        $this->claudeService = $claudeService;
        $this->marketingService = $marketingService;
        $this->middleware('auth');
        $this->middleware('vendor');
    }

    /**
     * Display marketing dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        $deals = Deal::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->get();

        $recentContent = $this->marketingService->getRecentContent($user, 5);
        $usageStats = $this->marketingService->getUsageStats($user);

        return view('vendor.marketing.index', [
            'deals' => $deals,
            'recentContent' => $recentContent,
            'usageStats' => $usageStats,
            'contentTypes' => [
                'email' => 'Email Campaigns',
                'social' => 'Social Media Posts',
                'ads' => 'Ad Copy',
                'signage' => 'In-Store Signage'
            ]
        ]);
    }

    /**
     * Generate email campaign content
     */
    public function generateEmail(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'deal_id' => 'required|exists:deals,id'
        ]);

        $deal = Deal::find($validated['deal_id']);
        
        // Verify ownership
        if ($deal->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 403);
        }

        // Check usage limits
        if (!$this->marketingService->canGenerate($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Daily marketing content limit reached',
                'remaining' => $user->getRemainingUsage('marketing')
            ], 429);
        }

        try {
            $dealData = $this->marketingService->prepareDealData($deal);
            $result = $this->claudeService->generateEmailCampaign($dealData, $user->id);

            if ($result['success']) {
                // Save to database
                $this->marketingService->saveContent($user, $deal, 'email', $result);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'subject_lines' => $result['subject_lines'],
                        'body_content' => $result['body_content'],
                        'call_to_action' => $result['call_to_action'],
                    ],
                    'remaining' => $result['remaining'],
                    'tokens_used' => $result['tokens_used'],
                    'processing_time_ms' => $result['processing_time_ms']
                ]);
            }

            return response()->json($result, 400);

        } catch (\Exception $e) {
            Log::error('Marketing Email Generation Error', [
                'user_id' => $user->id,
                'deal_id' => $deal->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to generate email content'
            ], 500);
        }
    }

    /**
     * Generate social media content
     */
    public function generateSocial(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'platform' => 'required|in:facebook,instagram,twitter'
        ]);

        $deal = Deal::find($validated['deal_id']);
        
        // Verify ownership
        if ($deal->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 403);
        }

        // Check usage limits
        if (!$this->marketingService->canGenerate($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Daily marketing content limit reached',
                'remaining' => $user->getRemainingUsage('marketing')
            ], 429);
        }

        try {
            $dealData = $this->marketingService->prepareDealData($deal);
            $result = $this->claudeService->generateSocialContent($dealData, $validated['platform'], $user->id);

            if ($result['success']) {
                // Save to database
                $contentData = array_merge($result, [
                    'platform' => $validated['platform']
                ]);
                $this->marketingService->saveContent($user, $deal, 'social', $contentData);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'post_content' => $result['post_content'],
                        'hashtags' => $result['hashtags'],
                        'call_to_action' => $result['call_to_action'],
                        'character_count' => $result['character_count'],
                    ],
                    'remaining' => $result['remaining'],
                    'tokens_used' => $result['tokens_used'],
                    'processing_time_ms' => $result['processing_time_ms']
                ]);
            }

            return response()->json($result, 400);

        } catch (\Exception $e) {
            Log::error('Marketing Social Generation Error', [
                'user_id' => $user->id,
                'deal_id' => $deal->id,
                'platform' => $validated['platform'],
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to generate social media content'
            ], 500);
        }
    }

    /**
     * Generate ad copy
     */
    public function generateAds(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'platform' => 'required|in:google_ads,facebook_ads'
        ]);

        $deal = Deal::find($validated['deal_id']);
        
        // Verify ownership
        if ($deal->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 403);
        }

        // Check usage limits
        if (!$this->marketingService->canGenerate($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Daily marketing content limit reached',
                'remaining' => $user->getRemainingUsage('marketing')
            ], 429);
        }

        try {
            $dealData = $this->marketingService->prepareDealData($deal);
            $result = $this->claudeService->generateAdCopy($dealData, $validated['platform'], $user->id);

            if ($result['success']) {
                // Save to database
                $contentData = array_merge($result, [
                    'platform' => $validated['platform']
                ]);
                $this->marketingService->saveContent($user, $deal, 'ads', $contentData);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'headlines' => $result['headlines'],
                        'descriptions' => $result['descriptions'],
                        'call_to_action' => $result['call_to_action'],
                    ],
                    'remaining' => $result['remaining'],
                    'tokens_used' => $result['tokens_used'],
                    'processing_time_ms' => $result['processing_time_ms']
                ]);
            }

            return response()->json($result, 400);

        } catch (\Exception $e) {
            Log::error('Marketing Ads Generation Error', [
                'user_id' => $user->id,
                'deal_id' => $deal->id,
                'platform' => $validated['platform'],
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to generate ad copy'
            ], 500);
        }
    }

    /**
     * Generate in-store signage content
     */
    public function generateSignage(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'deal_id' => 'required|exists:deals,id'
        ]);

        $deal = Deal::find($validated['deal_id']);
        
        // Verify ownership
        if ($deal->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 403);
        }

        // Check usage limits
        if (!$this->marketingService->canGenerate($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Daily marketing content limit reached',
                'remaining' => $user->getRemainingUsage('marketing')
            ], 429);
        }

        try {
            $dealData = $this->marketingService->prepareDealData($deal);
            $result = $this->claudeService->generateSignageContent($dealData, $user->id);

            if ($result['success']) {
                // Save to database
                $this->marketingService->saveContent($user, $deal, 'signage', $result);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'headline' => $result['headline'],
                        'subheadline' => $result['subheadline'],
                        'body_text' => $result['body_text'],
                        'fine_print' => $result['fine_print'],
                    ],
                    'remaining' => $result['remaining'],
                    'tokens_used' => $result['tokens_used'],
                    'processing_time_ms' => $result['processing_time_ms']
                ]);
            }

            return response()->json($result, 400);

        } catch (\Exception $e) {
            Log::error('Marketing Signage Generation Error', [
                'user_id' => $user->id,
                'deal_id' => $deal->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to generate signage content'
            ], 500);
        }
    }

    /**
     * Mark content as used
     */
    public function markAsUsed(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'content_id' => 'required|exists:ai_marketing_content,id'
        ]);

        $content = AIMarketingContent::find($validated['content_id']);

        // Verify ownership
        if ($content->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 403);
        }

        try {
            $this->marketingService->markAsUsed($content);

            return response()->json([
                'success' => true,
                'message' => 'Content marked as used'
            ]);

        } catch (\Exception $e) {
            Log::error('Mark Content As Used Error', [
                'user_id' => $user->id,
                'content_id' => $validated['content_id'],
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to mark content as used'
            ], 500);
        }
    }

    /**
     * Rate generated content
     */
    public function rateContent(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'content_id' => 'required|exists:ai_marketing_content,id',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $content = AIMarketingContent::find($validated['content_id']);

        // Verify ownership
        if ($content->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 403);
        }

        try {
            $this->marketingService->rateContent($content, $validated['rating']);

            return response()->json([
                'success' => true,
                'message' => 'Content rated successfully',
                'rating' => $validated['rating']
            ]);

        } catch (\Exception $e) {
            Log::error('Rate Content Error', [
                'user_id' => $user->id,
                'content_id' => $validated['content_id'],
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to rate content'
            ], 500);
        }
    }
}
