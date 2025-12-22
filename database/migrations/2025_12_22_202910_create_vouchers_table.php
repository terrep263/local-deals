<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing table if it exists
        if (Schema::hasTable('vouchers')) {
            Schema::drop('vouchers');
        }
        
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('deal_id');
            $table->bigInteger('user_id');
            $table->bigInteger('deal_purchase_id');
            
            // Voucher Details
            $table->string('voucher_code', 20)->unique();
            $table->string('qr_code_path')->nullable();
            $table->string('pdf_path')->nullable();
            
            // Dates
            $table->timestamp('purchase_date');
            $table->timestamp('expiration_date');
            
            // Status
            $table->enum('status', ['active', 'redeemed', 'expired', 'cancelled'])->default('active');
            $table->timestamp('redeemed_at')->nullable();
            $table->bigInteger('redeemed_by_vendor_user_id')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('voucher_code');
            $table->index(['user_id', 'status']);
            $table->index('expiration_date');
            $table->index('deal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
