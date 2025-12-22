<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_usage_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('feature_type', ['deal_writer', 'marketing'])->default('deal_writer');
            $table->date('usage_date');
            $table->integer('count')->default(1);
            $table->timestamps();
            
            // Unique constraint to prevent duplicate entries
            $table->unique(['user_id', 'feature_type', 'usage_date'], 'user_feature_date_unique');
            
            // Indexes
            $table->index(['user_id', 'usage_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage_tracking');
    }
};
