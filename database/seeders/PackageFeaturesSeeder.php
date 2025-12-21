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
        
        // FREE (Discovery) - Maps to 'starter'
        PackageFeature::create([
            'package_tier' => 'starter',
            'simultaneous_deals' => 1,
            'inventory_cap_per_deal' => -1,
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
            'commission_rate' => 15.00,
            'monthly_voucher_limit' => 50,
            'monthly_deal_limit' => 1,
        ]);
        
        // GROWTH - Maps to 'basic'
        PackageFeature::create([
            'package_tier' => 'basic',
            'simultaneous_deals' => 5,
            'inventory_cap_per_deal' => -1,
            'ai_scoring_enabled' => false,
            'analytics_access' => true,
            'priority_placement' => true,
            'featured_placement' => false,
            'api_access' => false,
            'white_label' => false,
            'custom_branding' => false,
            'auto_approval' => false,
            'support_level' => 'email',
            'monthly_price' => 49.00,
            'commission_rate' => 10.00,
            'monthly_voucher_limit' => 150,
            'monthly_deal_limit' => 5,
        ]);
        
        // PRO - Maps to 'pro'
        PackageFeature::create([
            'package_tier' => 'pro',
            'simultaneous_deals' => 15,
            'inventory_cap_per_deal' => -1,
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
            'commission_rate' => 5.00,
            'monthly_voucher_limit' => 300,
            'monthly_deal_limit' => 15,
        ]);
    }
}
