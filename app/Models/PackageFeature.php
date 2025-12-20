<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    protected $table = 'package_features';

    protected $fillable = [
        'package_tier', 'simultaneous_deals', 'inventory_cap_per_deal',
        'ai_scoring_enabled', 'analytics_access', 'priority_placement',
        'featured_placement', 'api_access', 'white_label', 'custom_branding',
        'auto_approval', 'support_level', 'monthly_price',
        'commission_rate', 'monthly_voucher_limit', 'monthly_deal_limit'
    ];

    protected $casts = [
        'simultaneous_deals' => 'integer',
        'inventory_cap_per_deal' => 'integer',
        'ai_scoring_enabled' => 'boolean',
        'analytics_access' => 'boolean',
        'priority_placement' => 'boolean',
        'featured_placement' => 'boolean',
        'api_access' => 'boolean',
        'white_label' => 'boolean',
        'custom_branding' => 'boolean',
        'auto_approval' => 'boolean',
        'monthly_price' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'monthly_voucher_limit' => 'integer',
        'monthly_deal_limit' => 'integer',
    ];

    public static function getByTier(string $tier): ?self
    {
        return self::where('package_tier', $tier)->first();
    }
}

