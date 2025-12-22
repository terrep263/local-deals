<?php

namespace App\Services;

use App\Models\AIMarketingContent;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MarketingContentService
{
    const FEATURE_TYPE = 'marketing';
    
    // Daily limits by subscription tier
    const DAILY_LIMITS = [
        'free' => 5,
        'starter' => 10,
        'basic' => 25,
        'pro' => 100,
        'enterprise' => 9999
    ];
    
    /**
     * Get daily limit for user's subscription tier
     */
    public function getDailyLimit(User $user): int
    {
        $tier = $user->subscription_tier ?? 'free';
        return self::DAILY_LIMITS[$tier] ?? self::DAILY_LIMITS['free'];
    }
    
    /**
     * Check if user can generate more content today
     */
    public function canGenerate(User $user): bool
    {
        if ($user->subscription_tier === 'enterprise') {
            return true;
        }
        
        return $user->getRemainingUsage(self::FEATURE_TYPE) > 0;
    }
    
    /**
     * Prepare deal data for marketing AI
     */
    public function prepareDealData(Deal $deal): array
    {
        return [
            'id' => $deal->id,
            'business_name' => $deal->business->name ?? 'Local Business',
            'title' => $deal->title,
            'description' => $deal->description,
            'category' => $deal->category ?? 'General',
            'city' => $deal->city ?? 'Lake County',
            'original_price' => $deal->original_price ?? 0,
            'sale_price' => $deal->sale_price ?? 0,
            'restrictions' => $deal->restrictions,
            'expiration_date' => $deal->expiration_date?->format('M d, Y') ?? 'Soon',
            'highlight_points' => [
                'discount_percentage' => $deal->original_price > 0 
                    ? round((($deal->original_price - $deal->sale_price) / $deal->original_price) * 100)
                    : 0,
                'is_limited_time' => $deal->expiration_date && $deal->expiration_date->isPast() === false,
                'is_budget_friendly' => $deal->sale_price <= 25,
                'category_keywords' => $this->getCategoryKeywords($deal->category)
            ]
        ];
    }
    
    /**
     * Save generated marketing content to database
     */
    public function saveContent(User $user, Deal $deal, string $contentType, array $generatedContent): AIMarketingContent
    {
        return AIMarketingContent::create([
            'user_id' => $user->id,
            'deal_id' => $deal->id,
            'content_type' => $contentType, // 'email', 'social', 'ads', 'signage'
            'platform' => $generatedContent['platform'] ?? null,
            'subject_lines' => json_encode($generatedContent['subject_lines'] ?? []),
            'body_content' => $generatedContent['body_content'] ?? '',
            'headlines' => json_encode($generatedContent['headlines'] ?? []),
            'descriptions' => json_encode($generatedContent['descriptions'] ?? []),
            'headline' => $generatedContent['headline'] ?? '',
            'subheadline' => $generatedContent['subheadline'] ?? '',
            'body_text' => $generatedContent['body_text'] ?? '',
            'fine_print' => $generatedContent['fine_print'] ?? '',
            'post_content' => $generatedContent['post_content'] ?? '',
            'hashtags' => json_encode($generatedContent['hashtags'] ?? []),
            'call_to_action' => $generatedContent['call_to_action'] ?? '',
            'tokens_used' => $generatedContent['tokens_used'] ?? 0,
            'processing_time_ms' => $generatedContent['processing_time_ms'] ?? 0,
            'is_used' => false,
            'rating' => null
        ]);
    }
    
    /**
     * Get category-specific keywords for marketing
     */
    private function getCategoryKeywords(string $category = null): array
    {
        $keywords = [
            'restaurants' => ['delicious', 'taste', 'flavor', 'dining', 'cuisine', 'appetite'],
            'retail' => ['shop', 'save', 'find', 'style', 'quality', 'value'],
            'services' => ['professional', 'trusted', 'expert', 'quality', 'reliable', 'service'],
            'entertainment' => ['fun', 'enjoy', 'experience', 'adventure', 'entertainment', 'excitement'],
            'fitness' => ['health', 'strong', 'fit', 'wellness', 'vitality', 'transform'],
            'beauty' => ['glow', 'enhance', 'beautiful', 'care', 'style', 'refresh'],
            'travel' => ['escape', 'explore', 'adventure', 'getaway', 'discover', 'journey'],
        ];
        
        $normalized = strtolower($category ?? '');
        foreach ($keywords as $category => $words) {
            if (str_contains($normalized, strtolower($category))) {
                return $words;
            }
        }
        
        return ['save', 'value', 'quality', 'local', 'deals', 'amazing'];
    }
    
    /**
     * Get recent generated content for a user
     */
    public function getRecentContent(User $user, int $limit = 5)
    {
        return AIMarketingContent::where('user_id', $user->id)
            ->latest()
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get content by type for a user
     */
    public function getContentByType(User $user, string $contentType)
    {
        return AIMarketingContent::where('user_id', $user->id)
            ->where('content_type', $contentType)
            ->latest()
            ->paginate(10);
    }
    
    /**
     * Rate generated content
     */
    public function rateContent(AIMarketingContent $content, int $rating): void
    {
        if ($rating >= 1 && $rating <= 5) {
            $content->update(['rating' => $rating]);
        }
    }
    
    /**
     * Mark content as used
     */
    public function markAsUsed(AIMarketingContent $content): void
    {
        $content->update([
            'is_used' => true,
            'used_at' => now()
        ]);
    }
    
    /**
     * Get user's marketing content usage stats
     */
    public function getUsageStats(User $user): array
    {
        $totalGenerated = AIMarketingContent::where('user_id', $user->id)
            ->count();
        
        $used = AIMarketingContent::where('user_id', $user->id)
            ->where('is_used', true)
            ->count();
        
        $byType = AIMarketingContent::where('user_id', $user->id)
            ->groupBy('content_type')
            ->selectRaw('content_type, count(*) as count')
            ->pluck('count', 'content_type');
        
        return [
            'total_generated' => $totalGenerated,
            'total_used' => $used,
            'usage_rate' => $totalGenerated > 0 ? round(($used / $totalGenerated) * 100) : 0,
            'by_type' => $byType,
            'daily_limit' => $this->getDailyLimit($user),
            'remaining' => $user->getRemainingUsage(self::FEATURE_TYPE)
        ];
    }
}
