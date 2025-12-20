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
        Schema::create('vendor_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Vendor user ID');
            $table->unsignedBigInteger('order_id')->nullable()->comment('Order/purchase ID');
            $table->unsignedBigInteger('deal_id')->nullable()->comment('Deal ID');
            $table->decimal('gross_sale_amount', 10, 2)->comment('Voucher price × quantity');
            $table->decimal('commission_rate', 5, 2)->comment('Commission rate at time of sale');
            $table->decimal('commission_amount', 10, 2)->comment('Calculated commission');
            $table->decimal('vendor_payout', 10, 2)->comment('Amount vendor keeps');
            $table->enum('status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('created_at');
            $table->index('status');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_commissions');
    }
};


