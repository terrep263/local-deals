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
        Schema::table('package_features', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->default(15.00)->after('monthly_price')->comment('Commission percentage (15.00 = 15%)');
            $table->integer('monthly_voucher_limit')->nullable()->after('commission_rate')->comment('Max vouchers per month, null = unlimited');
            $table->integer('monthly_deal_limit')->nullable()->after('monthly_voucher_limit')->comment('Max active deals, null = unlimited');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_features', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'monthly_voucher_limit', 'monthly_deal_limit']);
        });
    }
};


