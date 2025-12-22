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
        Schema::create('ai_marketing_content', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('deal_id')->nullable()->constrained()->onDelete('cascade');
            
            // Content type and platform
            $table->enum('content_type', ['email', 'social', 'ads', 'signage']);
            $table->string('platform')->nullable(); // facebook, instagram, twitter, google_ads, facebook_ads
            
            // Email content fields
            $table->json('subject_lines')->nullable(); // Array of subject line options
            $table->longText('body_content')->nullable();
            
            // Ad content fields
            $table->json('headlines')->nullable(); // Array of headlines
            $table->json('descriptions')->nullable(); // Array of descriptions
            
            // Signage content fields
            $table->string('headline')->nullable();
            $table->string('subheadline')->nullable();
            $table->text('body_text')->nullable();
            $table->text('fine_print')->nullable();
            
            // Social media fields
            $table->longText('post_content')->nullable();
            $table->json('hashtags')->nullable(); // Array of hashtags
            
            // Common fields
            $table->string('call_to_action')->nullable();
            $table->integer('tokens_used')->default(0);
            $table->integer('processing_time_ms')->default(0);
            
            // Tracking
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->integer('rating')->nullable(); // 1-5 stars
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('deal_id');
            $table->index('content_type');
            $table->index('platform');
            $table->index('is_used');
            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'content_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_marketing_content');
    }
};
