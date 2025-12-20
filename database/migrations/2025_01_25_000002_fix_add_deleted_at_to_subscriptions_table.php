<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('subscriptions')) {
            // Check if column exists using raw SQL
            $columns = DB::select("SHOW COLUMNS FROM subscriptions LIKE 'deleted_at'");
            
            if (empty($columns)) {
                DB::statement('ALTER TABLE subscriptions ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at');
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('subscriptions')) {
            $columns = DB::select("SHOW COLUMNS FROM subscriptions LIKE 'deleted_at'");
            
            if (!empty($columns)) {
                DB::statement('ALTER TABLE subscriptions DROP COLUMN deleted_at');
            }
        }
    }
};


