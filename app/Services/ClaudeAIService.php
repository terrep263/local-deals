<?php

namespace App\Services;

use Anthropic\Anthropic;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\AIUsageTracking;
use Illuminate\Support\Facades\DB;

class ClaudeAIService
{
    private const MAX_DAILY_USAGE = 10;
    private const CACHE_TTL = 300; // 5 minutes
    private Anthropic $client;
    
    public function __construct()
    {
        $this->client = Anthropic::factory()
            ->withApiKey(config('services.anthropic.api_key'))
            ->withHttpClient(new \GuzzleHttp\Client(['timeout' => 60]))
            ->make();
    }
    
    /**
     * Analyze deal quality and provide suggestions
     */
    public function analyzeDeal(array $dealData, int $userId): array
    {
        // Check rate limit
        if (!$this->canUseAI($userId, 'deal_writer')) {
            return [
                'success' => false,
                'error' => 'Daily AI analysis limit reached (10/day). Try again tomorrow.',
                'remaining' => 0
            ];
        }
        
        // Check cache first
        $cacheKey = $this->getCacheKey($dealData);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $startTime = microtime(true);
            
            // Build prompt
            $prompt = $this->buildAnalysisPrompt($dealData);
            
            // Call Claude API
            $response = $this->client->messages()->create([
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 2048,
                'temperature' => 0.7,
                'system' => $this->getSystemPrompt(),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]);
            
            $processingTime = (microtime(true) - $startTime) * 1000;
            
            // Parse response - Claude returns text, we need to extract JSON
            $content = $response->content[0]->text;
            $analysis = $this->parseClaudeResponse($content);
            
            // Track usage
            $this->trackUsage($userId, 'deal_writer');
            
            // Build result
            $result = [
                'success' => true,
                'overall_score' => $analysis['overall_score'] ?? 50,
                'title_score' => $analysis['title_score'] ?? 50,
                'description_score' => $analysis['description_score'] ?? 50,
                'pricing_score' => $analysis['pricing_score'] ?? 100,
                'suggestions' => $analysis['suggestions'] ?? [],
                'improved_title' => $analysis['improved_title'] ?? null,
                'improved_description' => $analysis['improved_description'] ?? null,
                'tokens_used' => $response->usage->inputTokens + $response->usage->outputTokens,
                'processing_time_ms' => round($processingTime),
                'remaining' => $this->getRemainingUsage($userId, 'deal_writer')
            ];
            
            // Cache result
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Claude AI Deal Analysis Error', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'AI analysis failed. Please try again in a moment.',
                'remaining' => $this->getRemainingUsage($userId, 'deal_writer')
            ];
        }
    }
    
    /**
     * Parse Claude's response to extract JSON
     * Claude sometimes wraps JSON in markdown code blocks
     */
    private function parseClaudeResponse(string $content): array
    {
        // Remove markdown code blocks if present
        $content = preg_replace('/```json\s*|\s*```/', '', $content);
        $content = trim($content);
        
        // Try to decode JSON
        $decoded = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('Failed to parse Claude response as JSON', [
                'content' => substr($content, 0, 500),
                'error' => json_last_error_msg()
            ]);
            
            // Return default structure
            return [
                'overall_score' => 50,
                'title_score' => 50,
                'description_score' => 50,
                'pricing_score' => 100,
                'suggestions' => [
                    [
                        'type' => 'description',
                        'severity' => 'important',
                        'issue' => 'Unable to analyze deal',
                        'suggestion' => 'Please try again or contact support if the issue persists.'
                    ]
                ]
            ];
        }
        
        return $decoded;
    }
    
    /**
     * Check if user can use AI feature
     */
    private function canUseAI(int $userId, string $featureType): bool
    {
        $usage = AIUsageTracking::where('user_id', $userId)
            ->where('feature_type', $featureType)
            ->where('usage_date', today())
            ->first();
            
        return !$usage || $usage->count < self::MAX_DAILY_USAGE;
    }
    
    /**
     * Get remaining AI usage for today
     */
    public function getRemainingUsage(int $userId, string $featureType): int
    {
        $usage = AIUsageTracking::where('user_id', $userId)
            ->where('feature_type', $featureType)
            ->where('usage_date', today())
            ->first();
            
        $used = $usage ? $usage->count : 0;
        return max(0, self::MAX_DAILY_USAGE - $used);
    }
    
    /**
     * Track AI usage
     */
    private function trackUsage(int $userId, string $featureType): void
    {
        AIUsageTracking::updateOrCreate(
            [
                'user_id' => $userId,
                'feature_type' => $featureType,
                'usage_date' => today()
            ],
            [
                'count' => DB::raw('count + 1')
            ]
        );
    }
    
    /**
     * Generate cache key for deal data
     */
    private function getCacheKey(array $dealData): string
    {
        $hashInput = $dealData['title'] . 
                     $dealData['description'] . 
                     $dealData['original_price'] . 
                     $dealData['sale_price'];
        return 'ai_deal_analysis_' . md5($hashInput);
    }
    
    /**
     * Get system prompt for Claude
     */
    private function getSystemPrompt(): string
    {
        return <<<'EOT'
You are a deal quality expert for a local deals platform in Lake County, Florida.

Your job is to analyze deal listings and provide constructive feedback to help vendors create professional, high-converting deals.

Evaluate deals on three criteria:

1. TITLE QUALITY (0-100):
   - Clear and specific (not vague)
   - Includes key details (discount %, service/product)
   - Appropriate length (30-80 characters ideal)
   - Professional (no all-caps, excessive punctuation)
   - Action-oriented and appealing

2. DESCRIPTION QUALITY (0-100):
   - Complete information (what's included, value, restrictions)
   - Well-structured and easy to read
   - Professional tone
   - Highlights benefits clearly
   - Includes important details (duration, limitations, booking info)

3. PRICING CLARITY (0-100):
   - Discount math is correct
   - Savings are clear and accurate
   - Good value proposition
   - Price makes sense for the offering

OVERALL SCORE: Average of all three scores

Provide specific, actionable suggestions. Be encouraging but honest. Focus on helping the vendor succeed.

CRITICAL: You must respond ONLY with valid JSON. No explanations, no preamble, just the JSON object.

Use this exact structure:
{
  "overall_score": 75,
  "title_score": 70,
  "description_score": 80,
  "pricing_score": 75,
  "suggestions": [
    {
      "type": "title",
      "severity": "critical",
      "issue": "Brief description of the issue",
      "suggestion": "Specific recommendation to fix it"
    }
  ],
  "improved_title": "Optional: A better title suggestion (only if score < 70)",
  "improved_description": "Optional: A better description (only if major issues found)"
}

IMPORTANT RULES:
- Always include all required fields
- suggestions array can be empty if score is > 80
- improved_title and improved_description should be null if not needed
- Scores must be integers between 0-100
- severity must be one of: "critical", "important", "minor"
- type must be one of: "title", "description", "pricing"
EOT;
    }
    
    /**
     * Build analysis prompt from deal data
     */
    private function buildAnalysisPrompt(array $dealData): string
    {
        $discount = 0;
        if ($dealData['original_price'] > 0) {
            $discount = round((($dealData['original_price'] - $dealData['sale_price']) / $dealData['original_price']) * 100);
        }
        
        return <<<EOT
Analyze this deal listing and respond with JSON only:

TITLE: {$dealData['title']}

DESCRIPTION:
{$dealData['description']}

PRICING:
- Original Price: \${$dealData['original_price']}
- Sale Price: \${$dealData['sale_price']}
- Discount: {$discount}%

BUSINESS CATEGORY: {$dealData['category']}

Provide quality scores and specific suggestions for improvement in the JSON format specified in your system prompt.
EOT;
    }
}
