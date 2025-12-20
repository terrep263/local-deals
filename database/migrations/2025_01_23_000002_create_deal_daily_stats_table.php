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
        Schema::create('deal_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade');
            $table->date('date');
            $table->integer('views')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('purchases')->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['deal_id', 'date']);
            $table->index('deal_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_daily_stats');
    }
};


