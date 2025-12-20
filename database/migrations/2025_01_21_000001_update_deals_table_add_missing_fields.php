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
            // Change vendor_id to bigint if needed
            if (Schema::hasColumn('deals', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->change();
            }
            
            // Add missing fields
            if (!Schema::hasColumn('deals', 'savings_amount')) {
                $table->decimal('savings_amount', 10, 2)->nullable()->after('discount_percentage');
            }
            
            if (!Schema::hasColumn('deals', 'inventory_remaining')) {
                $table->integer('inventory_remaining')->nullable()->after('inventory_sold');
            }
            
            if (!Schema::hasColumn('deals', 'location_latitude')) {
                $table->decimal('location_latitude', 10, 8)->nullable()->after('location_address');
            }
            
            if (!Schema::hasColumn('deals', 'location_longitude')) {
                $table->decimal('location_longitude', 11, 8)->nullable()->after('location_latitude');
            }
            
            if (!Schema::hasColumn('deals', 'auto_approved')) {
                $table->boolean('auto_approved')->default(false)->after('expires_at');
            }
            
            if (!Schema::hasColumn('deals', 'admin_approved_at')) {
                $table->timestamp('admin_approved_at')->nullable()->after('auto_approved');
            }
            
            if (!Schema::hasColumn('deals', 'admin_approved_by')) {
                $table->unsignedBigInteger('admin_approved_by')->nullable()->after('admin_approved_at');
                $table->foreign('admin_approved_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('deals', 'admin_rejection_reason')) {
                $table->text('admin_rejection_reason')->nullable()->after('admin_approved_by');
            }
            
            if (!Schema::hasColumn('deals', 'ai_quality_score')) {
                $table->integer('ai_quality_score')->nullable()->after('click_count');
            }
            
            // Update status enum to include rejected and pending_approval
            // Note: Laravel doesn't support modifying enum directly, so we'll handle this in the model
        });
        
        // Add index on vendor_id if not exists
        Schema::table('deals', function (Blueprint $table) {
            if (!$this->hasIndex('deals', 'deals_vendor_id_index')) {
                $table->index('vendor_id');
            }
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
            $columns = [
                'savings_amount',
                'inventory_remaining',
                'location_latitude',
                'location_longitude',
                'auto_approved',
                'admin_approved_at',
                'admin_approved_by',
                'admin_rejection_reason',
                'ai_quality_score',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('deals', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
    
    private function hasIndex($table, $indexName)
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count FROM information_schema.statistics 
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$databaseName, $table, $indexName]
        );
        
        return $result[0]->count > 0;
    }
};


