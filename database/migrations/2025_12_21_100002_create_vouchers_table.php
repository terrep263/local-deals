<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_purchase_id')->constrained('deal_purchases')->onDelete('cascade');
            $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade');
            $table->string('code', 20)->unique();
            $table->string('qr_code_path')->nullable();
            $table->enum('status', ['active', 'redeemed', 'expired', 'cancelled'])->default('active');
            $table->timestamp('valid_until')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->foreignId('redeemed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('redemption_notes')->nullable();
            $table->timestamps();
            
            $table->index('code');
            $table->index('status');
            $table->index(['deal_purchase_id', 'deal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
