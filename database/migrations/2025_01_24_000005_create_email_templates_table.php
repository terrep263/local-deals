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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'deal_approved', 'welcome_vendor'
            $table->string('name'); // Human-readable name
            $table->string('category'); // vendor, consumer, admin
            $table->string('subject');
            $table->text('body');
            $table->json('variables')->nullable(); // Available variables like {vendor_name}
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('key');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
};


