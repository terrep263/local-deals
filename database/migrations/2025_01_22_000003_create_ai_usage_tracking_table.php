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
        Schema::create('ai_usage_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('feature')->default('deal_scoring'); // deal_scoring, etc.
            $table->integer('tokens_used')->default(0);
            $table->decimal('cost_estimate', 10, 6)->default(0);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('feature');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_usage_tracking');
    }
};


