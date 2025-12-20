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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('category_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('terms_conditions')->nullable();
            $table->decimal('regular_price', 10, 2);
            $table->decimal('deal_price', 10, 2);
            $table->integer('discount_percentage')->default(0);
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('stripe_payment_link')->nullable();
            $table->integer('inventory_total')->default(0);
            $table->integer('inventory_sold')->default(0);
            $table->string('location_city')->nullable();
            $table->string('location_zip')->nullable();
            $table->text('location_address')->nullable();
            $table->boolean('featured_placement')->default(false);
            $table->boolean('priority_placement')->default(false);
            $table->integer('view_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->enum('status', ['draft', 'pending_approval', 'active', 'paused', 'expired', 'sold_out', 'rejected'])->default('draft');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('category_id');
            $table->index('expires_at');
            $table->index('location_city');
            $table->index('location_zip');
            $table->index('created_at');
            $table->index(['status', 'expires_at']);
            
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
    }
};
