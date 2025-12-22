<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackageFeature;
use Illuminate\Support\Facades\DB;

class PackageFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing data
        DB::table('package_features')->truncate();
        
        // FREE PLAN - Entry level
        PackageFeature::create([
            'package_tier' => 'free',
            'simultaneous_deals' => 1,
            'inventory_cap_per_deal' => 25,
            'ai_scoring_enabled' => false,
            'analytics_access' => false,
            'priority_placement' => false,
            'featured_placement' => false,
            'api_access' => false,
            'white_label' => false,
            'custom_branding' => false,
            'auto_approval' => false,
            'support_level' => 'community',
            'monthly_price' => 0.00,
            'commission_rate' => 20.00,
            'monthly_voucher_limit' => 25,
            'monthly_deal_limit' => 1,
        ]);
        
        // STARTER PLAN - $49/month
        PackageFeature::create([
            'package_tier' => 'starter',
            'simultaneous_deals' => 3,
            'inventory_cap_per_deal' => 100,
            'ai_scoring_enabled' => false,
            'analytics_access' => true,
            'priority_placement' => false,
            'featured_placement' => false,
            'api_access' => false,
            'white_label' => false,
            'custom_branding' => false,
            'auto_approval' => true,
            'support_level' => 'email',
            'monthly_price' => 49.00,
            'commission_rate' => 15.00,
            'monthly_voucher_limit' => 100,
            'monthly_deal_limit' => 3,
        ]);
        
        // BASIC PLAN - $99/month
        PackageFeature::create([
            'package_tier' => 'basic',
            'simultaneous_deals' => 10,
            'inventory_cap_per_deal' => -1, // Unlimited
            'ai_scoring_enabled' => true,
            'analytics_access' => true,
            'priority_placement' => true,
            'featured_placement' => true,
            'api_access' => false,
            'white_label' => false,
            'custom_branding' => false,
            'auto_approval' => true,
            'support_level' => 'priority',
            'monthly_price' => 99.00,
            'commission_rate' => 10.00,
            'monthly_voucher_limit' => -1, // Unlimited
            'monthly_deal_limit' => 10,
        ]);
        
        // PRO PLAN - $199/month
        PackageFeature::create([
            'package_tier' => 'pro',
            'simultaneous_deals' => -1, // Unlimited
            'inventory_cap_per_deal' => -1, // Unlimited
            'ai_scoring_enabled' => true,
            'analytics_access' => true,
            'priority_placement' => true,
            'featured_placement' => true,
            'api_access' => true,
            'white_label' => true,
            'custom_branding' => true,
            'auto_approval' => true,
            'support_level' => 'dedicated',
            'monthly_price' => 199.00,
            'commission_rate' => 5.00,
            'monthly_voucher_limit' => -1, // Unlimited
            'monthly_deal_limit' => -1, // Unlimited
        ]);
    }
}
