<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Pricing Plans
    |--------------------------------------------------------------------------
    |
    | Define all subscription tiers with their features and pricing.
    | This is the single source of truth for platform pricing.
    |
    */
    
    'plans' => [
        
        'founder' => [
            'name' => 'Founder Plan',
            'slug' => 'founder',
            'price' => 0,
            'stripe_price_id' => null, // Free plan, no Stripe
            'features' => [
                'active_deals' => 1,
                'vouchers_per_month' => 100,
                'unlimited_vouchers' => false,
                'unlimited_deals' => false,
                'top_tier_placement' => false,
            ],
            'limits' => [
                'max_founders' => 25,
                'consecutive_inactive_months' => 2,
            ],
            'description' => 'Free forever for the first 25 businesses',
            'public' => false, // Not shown on public pricing page
        ],
        
        'founder_upgrade' => [
            'name' => 'Founder Upgrade',
            'slug' => 'founder_upgrade',
            'price' => 35,
            'stripe_price_id' => env('STRIPE_FOUNDER_UPGRADE_PRICE_ID'),
            'features' => [
                'active_deals' => 2,
                'vouchers_per_month' => 300,
                'unlimited_vouchers' => false,
                'unlimited_deals' => false,
                'top_tier_placement' => false,
            ],
            'description' => 'Locked-in for life (founders only)',
            'public' => false, // Private offer, only visible to founders
            'founder_only' => true,
        ],
        
        'starter' => [
            'name' => 'Starter Plan',
            'slug' => 'starter',
            'price' => 49,
            'stripe_price_id' => env('STRIPE_STARTER_PRICE_ID'),
            'features' => [
                'active_deals' => 3,
                'vouchers_per_month' => 300,
                'unlimited_vouchers' => false,
                'unlimited_deals' => false,
                'top_tier_placement' => false,
            ],
            'rules' => [
                'vouchers_rollover' => false,
                'additional_vouchers' => false,
                'upgrade_path' => ['pro'],
            ],
            'description' => 'Perfect for small businesses getting started',
            'public' => true,
        ],
        
        'pro' => [
            'name' => 'Pro Plan',
            'slug' => 'pro',
            'price' => 99,
            'stripe_price_id' => env('STRIPE_PRO_PRICE_ID'),
            'features' => [
                'active_deals' => 10,
                'vouchers_per_month' => null, // Unlimited
                'unlimited_vouchers' => true,
                'unlimited_deals' => false,
                'top_tier_placement' => true,
            ],
            'rules' => [
                'vouchers_rollover' => false,
                'additional_vouchers' => false,
                'upgrade_path' => ['enterprise'],
            ],
            'description' => 'Unlimited vouchers for high-volume businesses',
            'public' => true,
        ],
        
        'enterprise' => [
            'name' => 'Enterprise Plan',
            'slug' => 'enterprise',
            'price' => 199,
            'stripe_price_id' => env('STRIPE_ENTERPRISE_PRICE_ID'),
            'features' => [
                'active_deals' => null, // Unlimited
                'vouchers_per_month' => null, // Unlimited
                'unlimited_vouchers' => true,
                'unlimited_deals' => true,
                'top_tier_placement' => true,
            ],
            'rules' => [
                'vouchers_rollover' => false,
                'additional_vouchers' => false,
                'upgrade_path' => [],
            ],
            'description' => 'Maximum flexibility for large operations',
            'public' => true,
        ],
        
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Platform Rules
    |--------------------------------------------------------------------------
    */
    
    'rules' => [
        'deals_require_ai_quality' => true,
        'inactive_deals_lose_visibility' => true,
        'vouchers_reset_monthly' => true,
        'auto_pause_at_capacity' => true,
        'upgrade_only_next_tier' => true,
        'no_support_exceptions' => true,
    ],
    
];
