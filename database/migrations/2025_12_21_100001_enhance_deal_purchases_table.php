<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deal_purchases', function (Blueprint $table) {
            // Add missing fields for complete purchase flow
            $table->foreignId('user_id')->nullable()->after('deal_id')->constrained()->onDelete('set null');
            $table->integer('quantity')->default(1)->after('deal_id');
            $table->string('stripe_payment_intent_id')->nullable()->after('purchase_amount');
            $table->string('stripe_checkout_session_id')->nullable()->after('stripe_payment_intent_id');
            $table->enum('status', ['pending', 'completed', 'refunded', 'cancelled'])->default('pending')->after('stripe_checkout_session_id');
            $table->json('voucher_codes')->nullable()->after('confirmation_code');
            $table->timestamp('voucher_sent_at')->nullable()->after('voucher_codes');
            $table->string('customer_phone')->nullable()->after('consumer_email');
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('stripe_payment_intent_id');
        });
    }

    public function down(): void
    {
        Schema::table('deal_purchases', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'quantity',
                'stripe_payment_intent_id',
                'stripe_checkout_session_id',
                'status',
                'voucher_codes',
                'voucher_sent_at',
                'customer_phone',
            ]);
        });
    }
};
