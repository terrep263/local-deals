<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            if (!Schema::hasColumn('listings', 'deal_price')) {
                $table->decimal('deal_price', 10, 2)->nullable()->after('status');
            }
            if (!Schema::hasColumn('listings', 'deal_original_price')) {
                $table->decimal('deal_original_price', 10, 2)->nullable()->after('deal_price');
            }
            if (!Schema::hasColumn('listings', 'deal_discount_percentage')) {
                $table->integer('deal_discount_percentage')->nullable()->after('deal_original_price');
            }
            if (!Schema::hasColumn('listings', 'deal_expires_at')) {
                $table->timestamp('deal_expires_at')->nullable()->after('deal_discount_percentage');
            }
            if (!Schema::hasColumn('listings', 'deal_quantity_total')) {
                $table->integer('deal_quantity_total')->default(0)->after('deal_expires_at');
            }
            if (!Schema::hasColumn('listings', 'deal_quantity_sold')) {
                $table->integer('deal_quantity_sold')->default(0)->after('deal_quantity_total');
            }
            if (!Schema::hasColumn('listings', 'deal_terms')) {
                $table->text('deal_terms')->nullable()->after('deal_quantity_sold');
            }
            if (!Schema::hasColumn('listings', 'deal_redemption_instructions')) {
                $table->text('deal_redemption_instructions')->nullable()->after('deal_terms');
            }
            if (!Schema::hasColumn('listings', 'stripe_payment_link')) {
                $table->string('stripe_payment_link', 500)->nullable()->after('deal_redemption_instructions');
            }
        });
    }

    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'deal_price',
                'deal_original_price',
                'deal_discount_percentage',
                'deal_expires_at',
                'deal_quantity_total',
                'deal_quantity_sold',
                'deal_terms',
                'deal_redemption_instructions',
                'stripe_payment_link',
            ]);
        });
    }
};

