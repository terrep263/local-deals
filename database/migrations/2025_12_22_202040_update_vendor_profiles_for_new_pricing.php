<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            // Subscription tier enum update - add new plan types
            DB::statement("ALTER TABLE vendor_profiles MODIFY COLUMN subscription_tier ENUM('founder', 'founder_upgrade', 'starter', 'pro', 'enterprise') DEFAULT 'founder'");
            
            // Pricing - add if not exists
            if (!Schema::hasColumn('vendor_profiles', 'monthly_price')) {
                $table->decimal('monthly_price', 10, 2)->default(0)->after('subscription_tier');
            }
            
            // Active deals limit - add if not exists
            if (!Schema::hasColumn('vendor_profiles', 'active_deals_limit')) {
                $table->integer('active_deals_limit')->default(1)->after('monthly_voucher_limit');
            }
            if (!Schema::hasColumn('vendor_profiles', 'active_deals_count')) {
                $table->integer('active_deals_count')->default(0)->after('active_deals_limit');
            }
            
            // Founder-specific tracking - add if not exists
            if (!Schema::hasColumn('vendor_profiles', 'founder_number')) {
                $table->integer('founder_number')->nullable()->after('is_founder')->comment('1-25 for original founders');
            }
            if (!Schema::hasColumn('vendor_profiles', 'founder_claimed_at')) {
                $table->timestamp('founder_claimed_at')->nullable()->after('founder_number');
            }
            if (!Schema::hasColumn('vendor_profiles', 'consecutive_inactive_months')) {
                $table->integer('consecutive_inactive_months')->default(0)->after('founder_claimed_at');
            }
            if (!Schema::hasColumn('vendor_profiles', 'last_voucher_redeemed_at')) {
                $table->timestamp('last_voucher_redeemed_at')->nullable()->after('consecutive_inactive_months');
            }
            
            // Stripe billing for subscriptions - add if not exists
            if (!Schema::hasColumn('vendor_profiles', 'stripe_subscription_id')) {
                $table->string('stripe_subscription_id')->nullable()->after('stripe_account_id');
            }
            if (!Schema::hasColumn('vendor_profiles', 'stripe_customer_id')) {
                $table->string('stripe_customer_id')->nullable()->after('stripe_subscription_id');
            }
            if (!Schema::hasColumn('vendor_profiles', 'stripe_payment_method_id')) {
                $table->string('stripe_payment_method_id')->nullable()->after('stripe_customer_id');
            }
            if (!Schema::hasColumn('vendor_profiles', 'subscription_started_at')) {
                $table->timestamp('subscription_started_at')->nullable()->after('stripe_payment_method_id');
            }
            if (!Schema::hasColumn('vendor_profiles', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('subscription_started_at');
            }
        });
        
        // Add indexes if they don't exist
        if (!Schema::hasIndex('vendor_profiles', 'vendor_profiles_founder_number_index')) {
            Schema::table('vendor_profiles', function (Blueprint $table) {
                $table->index('founder_number');
            });
        }
        
        if (!Schema::hasIndex('vendor_profiles', 'vendor_profiles_subscription_tier_index')) {
            Schema::table('vendor_profiles', function (Blueprint $table) {
                $table->index('subscription_tier');
            });
        }
    }

    public function down(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->dropIndex(['founder_number']);
            $table->dropIndex(['subscription_tier']);
            
            $table->dropColumn([
                'monthly_price',
                'active_deals_limit',
                'active_deals_count',
                'founder_number',
                'founder_claimed_at',
                'consecutive_inactive_months',
                'last_voucher_redeemed_at',
                'stripe_subscription_id',
                'stripe_customer_id',
                'stripe_payment_method_id',
                'subscription_started_at',
                'subscription_ends_at'
            ]);
        });
    }
};
