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
        Schema::create('vendor_email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ai_marketing_content_id')->nullable()->constrained('ai_marketing_content')->onDelete('set null');
            
            // Email content
            $table->string('subject');
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            
            // Recipient
            $table->string('recipient_email')->nullable();
            
            // Status tracking
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'failed'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Engagement metrics
            $table->timestamp('opened_at')->nullable();
            $table->integer('open_count')->default(0);
            $table->integer('click_count')->default(0);
            
            // Error tracking
            $table->string('bounce_reason')->nullable();
            
            // External API reference
            $table->string('resend_message_id')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('ai_marketing_content_id');
            $table->index('status');
            $table->index('sent_at');
            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_email_campaigns');
    }
};
