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
        Schema::table('users', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('remember_token');
            $table->enum('account_status', ['active', 'suspended', 'banned'])->default('active')->after('admin_notes');
            $table->timestamp('suspended_at')->nullable()->after('account_status');
            $table->text('suspension_reason')->nullable()->after('suspended_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['admin_notes', 'account_status', 'suspended_at', 'suspension_reason']);
        });
    }
};


