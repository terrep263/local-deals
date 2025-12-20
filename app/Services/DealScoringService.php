<?php

namespace App\Services;

use App\Models\Deal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class DealScoringService
{
    protected $apiKey;
    protected $model;
    protected $maxTokens;
    protected $temperature;

    public function __construct()
    {
        $this->apiKey = config('anthropic.api_key');
        $this->model = config('anthropic.model');
        $this->maxTokens = config('anthropic.max_tokens', 1024);
        $this->temperature = config('anthropic.temperature', 0.7);
    }

    /**
     * Score a deal using Claude API
     */
    public function scoreDeal(Deal $deal): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Anthropic API key not configured');
            return $this->getDefaultResponse();
        }

        try {
            $competitiveDeals = $this->getCompetitiveDeals($deal);
            $competitiveContext = $this->formatCompetitiveDeals($competitiveDeals);
            
            $prompt = $this->buildPrompt($deal, $competitiveContext);
            
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => $this->model,
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'system' => 'You are an expert deal analyzer. Always respond with valid JSON only, no markdown formatting.'
            ]);

            if ($response->successful()) {
                $content = $response->json();
                $text = $content['content'][0]['text'] ?? '';
                
                // Parse JSON from response
                $json = $this->extractJson($text);
                
                if ($json) {
                    return [
                        'score' => (int)($json['score'] ?? 50),
                        'strengths' => $json['strengths'] ?? [],
                        'weaknesses' => $json['weaknesses'] ?? [],
                        'suggestions' => $json['suggestions'] ?? [],
                        'competitive_analysis' => $json['competitive_analysis'] ?? '',
                        'tokens_used' => $content['usage']['total_tokens'] ?? 0,
                    ];
                }
            }
            
            Log::error('Anthropic API error: ' . $response->body());
            return $this->getDefaultResponse();
            
        } catch (\Exception $e) {
            Log::error('Deal scoring failed: ' . $e->getMessage());
            return $this->getDefaultResponse();
        }
    }

    /**
     * Get competitive deals for analysis
     */
    public function getCompetitiveDeals(Deal $deal): Collection
    {
        return Deal::active()
            ->where('category_id', $deal->category_id)
            ->where('id', '!=', $deal->id)
            ->where('location_city', $deal->location_city)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Format competitive deals for prompt
     */
    public function formatCompetitiveDeals(Collection $deals): string
    {
        if ($deals->isEmpty()) {
            return "No competitive deals found in this category/location.";
        }

        return $deals->map(function($deal) {
            return "- {$deal->title}: \${$deal->deal_price} ({$deal->discount_percentage}% off), {$deal->inventory_total} spots, {$deal->inventory_sold} sold";
        })->join("\n");
    }

    /**
     * Build the prompt for Claude API
     */
    protected function buildPrompt(Deal $deal, string $competitiveContext): string
    {
        $duration = $deal->expires_at && $deal->starts_at 
            ? $deal->starts_at->diffInDays($deal->expires_at) . ' days'
            : 'Not specified';

        return "You are an expert deal analyzer for a Groupon-style local deals platform in Lake County, Florida.

Analyze this deal and provide a quality score from 1-100, along with actionable suggestions.

DEAL TO ANALYZE:
- Title: {$deal->title}
- Category: " . ($deal->category->category_name ?? 'Uncategorized') . "
- Description: {$deal->description}
- Regular Price: \${$deal->regular_price}
- Deal Price: \${$deal->deal_price}
- Discount: {$deal->discount_percentage}%
- Inventory: {$deal->inventory_total} spots
- Duration: {$duration}
- Location: {$deal->location_city}, FL
- Vendor: " . ($deal->vendor->first_name ?? 'Unknown') . " " . ($deal->vendor->last_name ?? '')

COMPETITIVE CONTEXT (Similar deals in same category):
{$competitiveContext}

SCORING CRITERIA:
1. Title Quality (15 points): Clear, compelling, benefit-focused
2. Description Quality (20 points): Detailed, specific, value proposition
3. Pricing Strategy (20 points): Competitive discount, psychological pricing
4. Inventory Size (10 points): Appropriate for market
5. Deal Duration (10 points): Optimal urgency without being too short
6. Terms Clarity (10 points): Clear, fair terms
7. Visual Appeal (5 points): Image quality if describable
8. Competitive Positioning (10 points): versus similar deals

Respond ONLY with valid JSON in this exact format:
{
  \"score\": 85,
  \"strengths\": [\"Clear title\", \"Strong discount\", \"Detailed description\"],
  \"weaknesses\": [\"Duration too long\", \"Could improve urgency\"],
  \"suggestions\": [
    \"Reduce duration from 60 to 30 days for more urgency\",
    \"Add specific details about what lawn care includes\",
    \"Consider \$599 instead of \$600 for psychological pricing\"
  ],
  \"competitive_analysis\": \"This deal offers 60% off vs market average of 45%. Well positioned.\"
}

Do NOT include markdown code fences. Return raw JSON only.";
    }

    /**
     * Extract JSON from response text
     */
    protected function extractJson(string $text): ?array
    {
        // Try to find JSON in the text
        $jsonStart = strpos($text, '{');
        $jsonEnd = strrpos($text, '}');
        
        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonString = substr($text, $jsonStart, $jsonEnd - $jsonStart + 1);
            $decoded = json_decode($jsonString, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        
        return null;
    }

    /**
     * Get default response when API fails
     */
    protected function getDefaultResponse(): array
    {
        return [
            'score' => 50,
            'strengths' => [],
            'weaknesses' => ['Unable to analyze - API error'],
            'suggestions' => ['Please try again later'],
            'competitive_analysis' => 'Analysis unavailable',
            'tokens_used' => 0,
        ];
    }

    /**
     * Analyze pricing competitiveness
     */
    public function analyzePricing(Deal $deal): array
    {
        $competitiveDeals = $this->getCompetitiveDeals($deal);
        
        if ($competitiveDeals->isEmpty()) {
            return [
                'your_discount' => $deal->discount_percentage,
                'market_avg_discount' => null,
                'your_price' => $deal->deal_price,
                'market_avg_price' => null,
                'market_range' => [null, null],
                'position' => 'no_competition',
            ];
        }

        $discounts = $competitiveDeals->pluck('discount_percentage')->filter();
        $prices = $competitiveDeals->pluck('deal_price')->filter();

        $avgDiscount = $discounts->avg();
        $avgPrice = $prices->avg();
        $minPrice = $prices->min();
        $maxPrice = $prices->max();

        // Determine position
        $position = 'competitive';
        if ($deal->discount_percentage > ($avgDiscount + 10)) {
            $position = 'very_competitive';
        } elseif ($deal->discount_percentage > $avgDiscount) {
            $position = 'strong_discount';
        } elseif ($deal->discount_percentage < ($avgDiscount - 10)) {
            $position = 'weak';
        }

        return [
            'your_discount' => $deal->discount_percentage,
            'market_avg_discount' => round($avgDiscount, 1),
            'your_price' => $deal->deal_price,
            'market_avg_price' => round($avgPrice, 2),
            'market_range' => [round($minPrice, 2), round($maxPrice, 2)],
            'position' => $position,
        ];
    }
}

