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
    
    // ============================================================
    // MARKETING CONTENT GENERATION
    // ============================================================
    
    /**
     * Generate email marketing content
     */
    public function generateEmailCampaign(array $dealData, int $userId): array
    {
        if (!$this->canUseAI($userId, 'marketing')) {
            return [
                'success' => false,
                'error' => 'Daily marketing content limit reached. Upgrade your plan for more.',
                'remaining' => 0
            ];
        }
        
        $cacheKey = 'ai_marketing_email_' . md5(json_encode($dealData));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $startTime = microtime(true);
            
            $response = $this->client->messages()->create([
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 2048,
                'temperature' => 0.8,
                'system' => $this->getEmailMarketingPrompt(),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $this->buildEmailMarketingRequest($dealData)
                    ]
                ]
            ]);
            
            $processingTime = (microtime(true) - $startTime) * 1000;
            $content = $response->content[0]->text;
            $parsed = $this->parseClaudeResponse($content);
            
            $this->trackUsage($userId, 'marketing');
            
            $result = [
                'success' => true,
                'subject_lines' => $parsed['subject_lines'] ?? [],
                'body_content' => $parsed['body_content'] ?? '',
                'call_to_action' => $parsed['call_to_action'] ?? '',
                'tokens_used' => $response->usage->inputTokens + $response->usage->outputTokens,
                'processing_time_ms' => round($processingTime),
                'remaining' => $this->getRemainingUsage($userId, 'marketing')
            ];
            
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Claude Marketing Email Error', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => 'Failed to generate email content. Please try again.',
                'remaining' => $this->getRemainingUsage($userId, 'marketing')
            ];
        }
    }
    
    /**
     * Generate social media content
     */
    public function generateSocialContent(array $dealData, string $platform, int $userId): array
    {
        if (!$this->canUseAI($userId, 'marketing')) {
            return [
                'success' => false,
                'error' => 'Daily marketing content limit reached.',
                'remaining' => 0
            ];
        }
        
        $cacheKey = 'ai_marketing_social_' . $platform . '_' . md5(json_encode($dealData));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $startTime = microtime(true);
            
            $response = $this->client->messages()->create([
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 1024,
                'temperature' => 0.9,
                'system' => $this->getSocialMediaPrompt($platform),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $this->buildSocialMediaRequest($dealData, $platform)
                    ]
                ]
            ]);
            
            $processingTime = (microtime(true) - $startTime) * 1000;
            $content = $response->content[0]->text;
            $parsed = $this->parseClaudeResponse($content);
            
            $this->trackUsage($userId, 'marketing');
            
            $result = [
                'success' => true,
                'post_content' => $parsed['post_content'] ?? '',
                'hashtags' => $parsed['hashtags'] ?? [],
                'call_to_action' => $parsed['call_to_action'] ?? '',
                'character_count' => strlen($parsed['post_content'] ?? ''),
                'tokens_used' => $response->usage->inputTokens + $response->usage->outputTokens,
                'processing_time_ms' => round($processingTime),
                'remaining' => $this->getRemainingUsage($userId, 'marketing')
            ];
            
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Claude Marketing Social Error', [
                'user_id' => $userId,
                'platform' => $platform,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => 'Failed to generate social media content.',
                'remaining' => $this->getRemainingUsage($userId, 'marketing')
            ];
        }
    }
    
    /**
     * Generate ad copy (Google/Facebook)
     */
    public function generateAdCopy(array $dealData, string $adPlatform, int $userId): array
    {
        if (!$this->canUseAI($userId, 'marketing')) {
            return [
                'success' => false,
                'error' => 'Daily marketing content limit reached.',
                'remaining' => 0
            ];
        }
        
        $cacheKey = 'ai_marketing_ads_' . $adPlatform . '_' . md5(json_encode($dealData));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $startTime = microtime(true);
            
            $response = $this->client->messages()->create([
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 1024,
                'temperature' => 0.8,
                'system' => $this->getAdCopyPrompt($adPlatform),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $this->buildAdCopyRequest($dealData, $adPlatform)
                    ]
                ]
            ]);
            
            $processingTime = (microtime(true) - $startTime) * 1000;
            $content = $response->content[0]->text;
            $parsed = $this->parseClaudeResponse($content);
            
            $this->trackUsage($userId, 'marketing');
            
            $result = [
                'success' => true,
                'headlines' => $parsed['headlines'] ?? [],
                'descriptions' => $parsed['descriptions'] ?? [],
                'call_to_action' => $parsed['call_to_action'] ?? '',
                'tokens_used' => $response->usage->inputTokens + $response->usage->outputTokens,
                'processing_time_ms' => round($processingTime),
                'remaining' => $this->getRemainingUsage($userId, 'marketing')
            ];
            
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Claude Marketing Ads Error', [
                'user_id' => $userId,
                'platform' => $adPlatform,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => 'Failed to generate ad copy.',
                'remaining' => $this->getRemainingUsage($userId, 'marketing')
            ];
        }
    }
    
    /**
     * Generate in-store signage content
     */
    public function generateSignageContent(array $dealData, int $userId): array
    {
        if (!$this->canUseAI($userId, 'marketing')) {
            return [
                'success' => false,
                'error' => 'Daily marketing content limit reached.',
                'remaining' => 0
            ];
        }
        
        $cacheKey = 'ai_marketing_signage_' . md5(json_encode($dealData));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $startTime = microtime(true);
            
            $response = $this->client->messages()->create([
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 1024,
                'temperature' => 0.8,
                'system' => $this->getSignagePrompt(),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $this->buildSignageRequest($dealData)
                    ]
                ]
            ]);
            
            $processingTime = (microtime(true) - $startTime) * 1000;
            $content = $response->content[0]->text;
            $parsed = $this->parseClaudeResponse($content);
            
            $this->trackUsage($userId, 'marketing');
            
            $result = [
                'success' => true,
                'headline' => $parsed['headline'] ?? '',
                'subheadline' => $parsed['subheadline'] ?? '',
                'body_text' => $parsed['body_text'] ?? '',
                'fine_print' => $parsed['fine_print'] ?? '',
                'tokens_used' => $response->usage->inputTokens + $response->usage->outputTokens,
                'processing_time_ms' => round($processingTime),
                'remaining' => $this->getRemainingUsage($userId, 'marketing')
            ];
            
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Claude Marketing Signage Error', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => 'Failed to generate signage content.',
                'remaining' => $this->getRemainingUsage($userId, 'marketing')
            ];
        }
    }
    
    // ============================================================
    // MARKETING PROMPT TEMPLATES
    // ============================================================
    
    /**
     * Email marketing system prompt
     */
    private function getEmailMarketingPrompt(): string
    {
        return <<<'EOT'
You are a professional email marketing copywriter for a local deals platform in Lake County, Florida.

Your job is to create compelling email campaigns that:
- Grab attention with strong subject lines
- Drive urgency without being pushy
- Highlight the value proposition clearly
- Include a strong call-to-action
- Sound friendly and local, not corporate

Target audience: Lake County, Florida residents who love saving money on local businesses.

CRITICAL: Respond ONLY with valid JSON. No markdown, no preamble.

Use this exact structure:
{
  "subject_lines": [
    "Subject line option 1",
    "Subject line option 2",
    "Subject line option 3",
    "Subject line option 4",
    "Subject line option 5"
  ],
  "body_content": "Full email body (200-300 words, HTML-friendly)",
  "call_to_action": "Buy Now and Save!"
}

RULES:
- Subject lines must be under 60 characters
- Email body should be 200-300 words
- Use friendly, conversational tone
- Include urgency (limited time, limited spots)
- Mention Lake County or local area
- End with clear call-to-action
EOT;
    }
    
    /**
     * Build email marketing request
     */
    private function buildEmailMarketingRequest(array $dealData): string
    {
        $discount = 0;
        if ($dealData['original_price'] > 0) {
            $discount = round((($dealData['original_price'] - $dealData['sale_price']) / $dealData['original_price']) * 100);
        }
        
        return <<<EOT
Create an email marketing campaign for this local business deal:

BUSINESS: {$dealData['business_name']}
DEAL TITLE: {$dealData['title']}
DESCRIPTION: {$dealData['description']}
CATEGORY: {$dealData['category']}
ORIGINAL PRICE: \${$dealData['original_price']}
SALE PRICE: \${$dealData['sale_price']}
DISCOUNT: {$discount}%
LOCATION: {$dealData['city']}, Lake County, FL
EXPIRATION: {$dealData['expiration_date']}

Generate:
1. 5 compelling subject line options
2. Professional email body (200-300 words)
3. Strong call-to-action

Respond with JSON only.
EOT;
    }
    
    /**
     * Social media system prompt
     */
    private function getSocialMediaPrompt(string $platform): string
    {
        $platformGuidelines = match($platform) {
            'facebook' => '- Longer content okay (100-200 words)
- Conversational, community-focused tone
- Can include multiple sentences
- Emojis are good but don\'t overdo it',
            'instagram' => '- Shorter content (100-150 words)
- Very visual, engaging tone
- Strategic emoji use
- Hashtags are critical (10-15 relevant tags)',
            'twitter' => '- Very short (240 characters max)
- Punchy, direct
- Limited hashtags (2-3 max)
- Clear call-to-action',
            default => '- Engaging, platform-appropriate content'
        };
        
        return <<<EOT
You are a social media marketing expert for local businesses in Lake County, Florida.

Platform: {$platform}

Platform Guidelines:
{$platformGuidelines}

Create posts that:
- Grab attention in the feed
- Highlight the deal value
- Use relevant local hashtags
- Include clear call-to-action
- Sound authentic, not salesy

CRITICAL: Respond ONLY with valid JSON.

Structure:
{
  "post_content": "The social media post text",
  "hashtags": ["hashtag1", "hashtag2", "hashtag3"],
  "call_to_action": "Shop Now!"
}

RULES:
- Post must follow platform character limits
- Include local Lake County hashtags
- Add category-relevant hashtags
- Make it shareable and engaging
EOT;
    }
    
    /**
     * Build social media request
     */
    private function buildSocialMediaRequest(array $dealData, string $platform): string
    {
        $discount = round((($dealData['original_price'] - $dealData['sale_price']) / $dealData['original_price']) * 100);
        
        return <<<EOT
Create a {$platform} post for this local business deal:

BUSINESS: {$dealData['business_name']}
DEAL: {$dealData['title']}
DESCRIPTION: {$dealData['description']}
DISCOUNT: {$discount}%
PRICE: \${$dealData['sale_price']} (was \${$dealData['original_price']})
LOCATION: {$dealData['city']}, Lake County, FL
CATEGORY: {$dealData['category']}

Generate:
1. Engaging post content (follow {$platform} guidelines)
2. Relevant hashtags (local + category)
3. Clear call-to-action

Respond with JSON only.
EOT;
    }
    
    /**
     * Ad copy system prompt
     */
    private function getAdCopyPrompt(string $platform): string
    {
        $platformSpecs = match($platform) {
            'google_ads' => '- Headlines: 30 characters max
- Descriptions: 90 characters max
- Focus on keywords and benefits
- Multiple variations for A/B testing',
            'facebook_ads' => '- Headlines: 40 characters max
- Primary text: 125 characters ideal
- Emotional appeal important
- Visual-first mindset',
            default => '- Follow platform best practices'
        };
        
        return <<<EOT
You are a digital advertising copywriter for local businesses.

Platform: {$platform}

Platform Specs:
{$platformSpecs}

Create ad copy that:
- Stops the scroll
- Highlights the offer clearly
- Creates urgency
- Drives clicks
- Follows character limits strictly

CRITICAL: Respond ONLY with valid JSON.

Structure:
{
  "headlines": [
    "Headline option 1",
    "Headline option 2",
    "Headline option 3"
  ],
  "descriptions": [
    "Description option 1",
    "Description option 2",
    "Description option 3"
  ],
  "call_to_action": "Get Deal Now"
}

RULES:
- Strictly follow character limits
- Include discount percentage
- Mention location (Lake County)
- Create urgency
- Test multiple variations
EOT;
    }
    
    /**
     * Build ad copy request
     */
    private function buildAdCopyRequest(array $dealData, string $platform): string
    {
        $discount = round((($dealData['original_price'] - $dealData['sale_price']) / $dealData['original_price']) * 100);
        
        return <<<EOT
Create {$platform} ad copy for this deal:

BUSINESS: {$dealData['business_name']}
OFFER: {$dealData['title']}
DISCOUNT: {$discount}%
PRICE: \${$dealData['sale_price']}
LOCATION: {$dealData['city']}, Lake County, FL
CATEGORY: {$dealData['category']}

Generate:
1. 3 headline variations (follow character limits)
2. 3 description variations (follow character limits)
3. Compelling call-to-action

Respond with JSON only.
EOT;
    }
    
    /**
     * Signage system prompt
     */
    private function getSignagePrompt(): string
    {
        return <<<'EOT'
You are creating in-store promotional signage copy for local businesses.

Create content for:
- Window clings
- Counter displays
- Poster boards
- Table tents

The copy should:
- Be bold and eye-catching
- Clearly state the offer
- Create urgency
- Be easy to read from a distance
- Include fine print (expiration, restrictions)

CRITICAL: Respond ONLY with valid JSON.

Structure:
{
  "headline": "BOLD MAIN HEADLINE",
  "subheadline": "Supporting text with details",
  "body_text": "Brief explanation if needed",
  "fine_print": "Expiration and restrictions"
}

RULES:
- Headline should be SHORT and BOLD (3-7 words)
- Subheadline adds context
- Body text is optional and brief
- Fine print includes expiration and restrictions
- Think about visual hierarchy
EOT;
    }
    
    /**
     * Build signage request
     */
    private function buildSignageRequest(array $dealData): string
    {
        $discount = round((($dealData['original_price'] - $dealData['sale_price']) / $dealData['original_price']) * 100);
        
        return <<<EOT
Create in-store signage copy for this deal:

BUSINESS: {$dealData['business_name']}
OFFER: {$dealData['title']}
DISCOUNT: {$discount}%
SALE PRICE: \${$dealData['sale_price']}
ORIGINAL PRICE: \${$dealData['original_price']}
EXPIRATION: {$dealData['expiration_date']}
RESTRICTIONS: {$dealData['restrictions'] ?? 'Standard terms apply'}

Generate:
1. Bold headline (3-7 words)
2. Descriptive subheadline
3. Optional body text
4. Fine print (expiration, restrictions)

Respond with JSON only.
EOT;
    }
}
