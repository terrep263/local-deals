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
        Schema::create('package_features', function (Blueprint $table) {
            $table->id();
            $table->enum('package_tier', ['starter', 'basic', 'pro', 'enterprise'])->unique();
            $table->integer('simultaneous_deals')->comment('Max deals allowed active at once. -1 for unlimited');
            $table->integer('inventory_cap_per_deal')->comment('Max inventory per deal. -1 for unlimited');
            $table->boolean('ai_scoring_enabled')->default(false);
            $table->boolean('analytics_access')->default(false);
            $table->boolean('priority_placement')->default(false);
            $table->boolean('featured_placement')->default(false);
            $table->boolean('api_access')->default(false);
            $table->boolean('white_label')->default(false);
            $table->boolean('custom_branding')->default(false);
            $table->boolean('auto_approval')->default(false);
            $table->enum('support_level', ['community', 'email', 'priority', 'dedicated'])->default('community');
            $table->decimal('monthly_price', 8, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_features');
    }
};


