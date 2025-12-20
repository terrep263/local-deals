<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('listings', 'cat_id')) {
            return;
        }

        Schema::table('listings', function (Blueprint $table) {
            // Drop any existing FK on cat_id to avoid duplicate constraint errors
            try {
                $table->dropForeign(['cat_id']);
            } catch (\Throwable $e) {
                // ignore if not present
            }

            // Re-attach with restrictOnDelete
            $table->foreign('cat_id')
                ->references('id')
                ->on('categories')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('listings', 'cat_id')) {
            return;
        }

        Schema::table('listings', function (Blueprint $table) {
            try {
                $table->dropForeign(['cat_id']);
            } catch (\Throwable $e) {
                // ignore if not present
            }
        });
    }
};

