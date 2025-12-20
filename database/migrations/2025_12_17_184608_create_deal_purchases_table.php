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
        Schema::create('deal_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade');
            $table->string('consumer_email');
            $table->string('consumer_name')->nullable();
            $table->decimal('purchase_amount', 10, 2);
            $table->string('confirmation_code', 8)->unique();
            $table->timestamp('purchase_date');
            $table->timestamp('redeemed_at')->nullable();
            $table->boolean('vendor_notified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('deal_id');
            $table->index('consumer_email');
            $table->index('confirmation_code');
            $table->index('purchase_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_purchases');
    }
};
