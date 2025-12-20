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
        Schema::create('upgrade_suggestions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('from_tier');
            $table->string('to_tier');
            $table->enum('reason', ['volume_limit', 'cost_savings', 'both'])->default('cost_savings');
            $table->decimal('current_monthly_cost', 10, 2);
            $table->decimal('suggested_monthly_cost', 10, 2);
            $table->decimal('monthly_savings', 10, 2);
            $table->timestamp('shown_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index(['user_id', 'dismissed_at', 'converted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upgrade_suggestions');
    }
};


