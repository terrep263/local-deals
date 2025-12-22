<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_deal_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Input Data
            $table->string('title');
            $table->text('description');
            $table->decimal('original_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            // Quality Scores
            $table->integer('title_score')->default(0);
            $table->integer('description_score')->default(0);
            $table->integer('pricing_score')->default(0);
            $table->integer('overall_score')->default(0);
            
            // AI Feedback
            $table->json('suggestions');
            $table->string('improved_title')->nullable();
            $table->text('improved_description')->nullable();
            
            // Metadata
            $table->string('ai_model', 50)->default('claude-sonnet-4');
            $table->integer('tokens_used')->default(0);
            $table->integer('processing_time_ms')->default(0);
            $table->boolean('was_accepted')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index('deal_id');
            $table->index('overall_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_deal_analyses');
    }
};
