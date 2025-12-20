<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Enhance categories table
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'color')) {
                    $table->string('color', 7)->nullable()->default('#f97316')->after('category_icon');
                }
                if (!Schema::hasColumn('categories', 'is_featured')) {
                    $table->boolean('is_featured')->default(false)->after('category_slug');
                }
                if (!Schema::hasColumn('categories', 'deals_count')) {
                    $table->integer('deals_count')->default(0)->after('is_featured');
                }
                if (!Schema::hasColumn('categories', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('deals_count');
                }
            });
        }

        // Create or enhance cities table
        if (!Schema::hasTable('cities')) {
            Schema::create('cities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('county')->nullable()->default('Lake County');
                $table->string('state', 2)->nullable()->default('FL');
                $table->text('zip_codes')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->integer('population')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->integer('deals_count')->default(0);
                $table->integer('sort_order')->default(0);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('cities', function (Blueprint $table) {
                if (!Schema::hasColumn('cities', 'county')) {
                    $table->string('county')->nullable()->default('Lake County')->after('name');
                }
                if (!Schema::hasColumn('cities', 'state')) {
                    $table->string('state', 2)->nullable()->default('FL')->after('county');
                }
                if (!Schema::hasColumn('cities', 'zip_codes')) {
                    $table->text('zip_codes')->nullable()->after('state');
                }
                if (!Schema::hasColumn('cities', 'latitude')) {
                    $table->decimal('latitude', 10, 8)->nullable()->after('zip_codes');
                }
                if (!Schema::hasColumn('cities', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
                }
                if (!Schema::hasColumn('cities', 'population')) {
                    $table->integer('population')->nullable()->after('longitude');
                }
                if (!Schema::hasColumn('cities', 'description')) {
                    $table->text('description')->nullable()->after('population');
                }
                if (!Schema::hasColumn('cities', 'is_featured')) {
                    $table->boolean('is_featured')->default(false)->after('description');
                }
                if (!Schema::hasColumn('cities', 'deals_count')) {
                    $table->integer('deals_count')->default(0)->after('is_featured');
                }
                if (!Schema::hasColumn('cities', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('deals_count');
                }
                if (!Schema::hasColumn('cities', 'status')) {
                    $table->boolean('status')->default(true)->after('sort_order');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $cols = ['color', 'is_featured', 'deals_count', 'sort_order'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('categories', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('cities')) {
            Schema::table('cities', function (Blueprint $table) {
                $cols = ['county', 'state', 'zip_codes', 'latitude', 'longitude', 'population', 'description', 'is_featured', 'deals_count', 'sort_order', 'status'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('cities', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};

