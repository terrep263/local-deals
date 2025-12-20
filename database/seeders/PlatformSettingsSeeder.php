<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlatformSetting;

class PlatformSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'Lake County Local Deals', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_tagline', 'value' => 'Discover amazing local deals', 'group' => 'general', 'type' => 'string'],
            ['key' => 'contact_email', 'value' => 'contact@lakecountydeals.com', 'group' => 'general', 'type' => 'string'],
            ['key' => 'support_email', 'value' => 'support@lakecountydeals.com', 'group' => 'general', 'type' => 'string'],
            
            // Deal Settings
            ['key' => 'max_deals_free', 'value' => '1', 'group' => 'deal', 'type' => 'integer'],
            ['key' => 'max_inventory_free', 'value' => '100', 'group' => 'deal', 'type' => 'integer'],
            ['key' => 'default_deal_duration', 'value' => '30', 'group' => 'deal', 'type' => 'integer'],
            ['key' => 'manual_approval_required', 'value' => '1', 'group' => 'deal', 'type' => 'boolean'],
            ['key' => 'auto_close_sold_out', 'value' => '1', 'group' => 'deal', 'type' => 'boolean'],
            
            // Email Settings
            ['key' => 'email_from_name', 'value' => 'Lake County Local Deals', 'group' => 'email', 'type' => 'string'],
            ['key' => 'email_from_email', 'value' => 'noreply@lakecountydeals.com', 'group' => 'email', 'type' => 'string'],
            ['key' => 'email_signature', 'value' => 'Best regards,\nLake County Local Deals Team', 'group' => 'email', 'type' => 'text'],
            
            // Payment Settings
            ['key' => 'stripe_public_key', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'stripe_secret_key', 'value' => '', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'platform_fee_percentage', 'value' => '5', 'group' => 'payment', 'type' => 'decimal'],
            
            // SEO Settings
            ['key' => 'meta_title_template', 'value' => '{title} - Lake County Local Deals', 'group' => 'seo', 'type' => 'string'],
            ['key' => 'meta_description_template', 'value' => 'Discover amazing local deals in Lake County', 'group' => 'seo', 'type' => 'string'],
            ['key' => 'google_analytics_id', 'value' => '', 'group' => 'seo', 'type' => 'string'],
            ['key' => 'facebook_pixel_id', 'value' => '', 'group' => 'seo', 'type' => 'string'],
            
            // Maintenance Settings
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'maintenance', 'type' => 'boolean'],
            ['key' => 'maintenance_message', 'value' => 'We are currently performing maintenance. Please check back soon.', 'group' => 'maintenance', 'type' => 'text'],
            ['key' => 'maintenance_allowed_ips', 'value' => '', 'group' => 'maintenance', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            PlatformSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}


