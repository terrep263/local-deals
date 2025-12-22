<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the ENUM column to add 'marketing' feature type
        DB::statement("ALTER TABLE ai_usage_tracking MODIFY COLUMN feature_type ENUM('deal_writer', 'marketing') DEFAULT 'deal_writer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert ENUM back to original
        DB::statement("ALTER TABLE ai_usage_tracking MODIFY COLUMN feature_type ENUM('deal_writer') DEFAULT 'deal_writer'");
    }
};
