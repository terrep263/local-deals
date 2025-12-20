<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'description')) {
                    $table->text('description')->nullable()->after('category_slug');
                }
                if (!Schema::hasColumn('categories', 'status')) {
                    $table->boolean('status')->default(true)->after('description');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (Schema::hasColumn('categories', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('categories', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }
    }
};

