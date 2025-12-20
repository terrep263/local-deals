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
        Schema::create('vendor_monthly_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('year');
            $table->integer('month');
            $table->string('subscription_tier')->comment('Snapshot of tier during month');
            $table->integer('vouchers_sold')->default(0);
            $table->decimal('gross_sales', 10, 2)->default(0.00);
            $table->decimal('base_subscription_fee', 10, 2)->default(0.00);
            $table->decimal('total_commissions', 10, 2)->default(0.00);
            $table->decimal('total_cost', 10, 2)->default(0.00)->comment('base_fee + commissions');
            $table->timestamps();

            $table->unique(['user_id', 'year', 'month']);
            $table->index(['user_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_monthly_stats');
    }
};


