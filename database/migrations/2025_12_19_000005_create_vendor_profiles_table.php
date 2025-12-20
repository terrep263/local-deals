<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('business_name', 255);
            $table->text('business_address');
            $table->string('business_city')->default('Lake County');
            $table->string('business_state')->default('FL');
            $table->string('business_zip')->nullable();
            $table->string('business_phone');
            $table->enum('business_category', ['restaurant', 'auto', 'health', 'home', 'entertainment']);
            $table->text('business_description')->nullable();
            $table->string('business_logo')->nullable();
            $table->json('business_hours')->nullable();
            $table->string('stripe_account_id')->nullable()->unique();
            $table->timestamp('stripe_connected_at')->nullable();
            $table->string('subscription_tier')->default('founder_free');
            $table->integer('monthly_voucher_limit')->default(100);
            $table->integer('vouchers_used_this_month')->default(0);
            $table->date('billing_period_start')->nullable();
            $table->boolean('is_founder')->default(false);
            $table->boolean('onboarding_completed')->default(false);
            $table->boolean('profile_completed')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('is_founder');
            $table->index('subscription_tier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_profiles');
    }
};

