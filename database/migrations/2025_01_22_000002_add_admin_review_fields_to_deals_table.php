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
        Schema::table('deals', function (Blueprint $table) {
            $table->boolean('requires_admin_review')->default(false)->after('admin_rejection_reason');
            $table->text('admin_review_reason')->nullable()->after('requires_admin_review');
            
            $table->index('requires_admin_review');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn(['requires_admin_review', 'admin_review_reason']);
        });
    }
};


