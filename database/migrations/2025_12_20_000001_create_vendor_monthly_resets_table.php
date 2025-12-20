<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_monthly_resets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_profile_id')->constrained('vendor_profiles')->onDelete('cascade');
            $table->string('month_year', 7)->index(); // e.g., 2025-01
            $table->integer('vouchers_sold_last_month')->default(0);
            $table->integer('vouchers_redeemed_last_month')->nullable();
            $table->timestamp('reset_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_monthly_resets');
    }
};

