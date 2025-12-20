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
        Schema::create('deal_ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade');
            $table->integer('score')->default(0); // 1-100
            $table->json('strengths')->nullable(); // Array of positive points
            $table->json('weaknesses')->nullable(); // Array of issues
            $table->json('suggestions')->nullable(); // Array of actionable tips
            $table->text('competitive_analysis')->nullable(); // Text analysis
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();
            
            $table->index('deal_id');
            $table->index('score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_ai_analyses');
    }
};


