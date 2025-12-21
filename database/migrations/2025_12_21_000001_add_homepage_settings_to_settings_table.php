<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Hero Section
            if (!Schema::hasColumn('settings', 'hero_badge_text')) {
                $table->string('hero_badge_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_title')) {
                $table->string('hero_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_title_highlight')) {
                $table->string('hero_title_highlight')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_subtitle')) {
                $table->text('hero_subtitle')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_disclaimer')) {
                $table->string('hero_disclaimer')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_stats')) {
                $table->json('hero_stats')->nullable();
            }
            
            // Promo Banner
            if (!Schema::hasColumn('settings', 'promo_banner_items')) {
                $table->json('promo_banner_items')->nullable();
            }
            
            // Sale Banner
            if (!Schema::hasColumn('settings', 'sale_banner_enabled')) {
                $table->boolean('sale_banner_enabled')->default(false);
            }
            if (!Schema::hasColumn('settings', 'sale_banner_label')) {
                $table->string('sale_banner_label')->nullable();
            }
            if (!Schema::hasColumn('settings', 'sale_banner_title')) {
                $table->string('sale_banner_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'sale_banner_subtitle')) {
                $table->string('sale_banner_subtitle')->nullable();
            }
            if (!Schema::hasColumn('settings', 'sale_banner_discount')) {
                $table->string('sale_banner_discount')->nullable();
            }
            if (!Schema::hasColumn('settings', 'sale_banner_button_text')) {
                $table->string('sale_banner_button_text')->nullable();
            }
            
            // How It Works
            if (!Schema::hasColumn('settings', 'how_it_works_label')) {
                $table->string('how_it_works_label')->nullable();
            }
            if (!Schema::hasColumn('settings', 'how_it_works_title')) {
                $table->string('how_it_works_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'how_it_works_subtitle')) {
                $table->string('how_it_works_subtitle')->nullable();
            }
            if (!Schema::hasColumn('settings', 'how_it_works_steps')) {
                $table->json('how_it_works_steps')->nullable();
            }
            
            // Testimonials
            if (!Schema::hasColumn('settings', 'testimonials_label')) {
                $table->string('testimonials_label')->nullable();
            }
            if (!Schema::hasColumn('settings', 'testimonials_title')) {
                $table->string('testimonials_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'testimonials_subtitle')) {
                $table->string('testimonials_subtitle')->nullable();
            }
            if (!Schema::hasColumn('settings', 'testimonials')) {
                $table->json('testimonials')->nullable();
            }
            
            // CTA Section
            if (!Schema::hasColumn('settings', 'cta_title')) {
                $table->string('cta_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'cta_subtitle')) {
                $table->string('cta_subtitle')->nullable();
            }
            if (!Schema::hasColumn('settings', 'cta_button_text')) {
                $table->string('cta_button_text')->nullable();
            }
            
            // Footer
            if (!Schema::hasColumn('settings', 'footer_description')) {
                $table->text('footer_description')->nullable();
            }
            if (!Schema::hasColumn('settings', 'footer_copyright')) {
                $table->string('footer_copyright')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'hero_badge_text', 'hero_title', 'hero_title_highlight', 'hero_subtitle',
                'hero_disclaimer', 'hero_stats', 'promo_banner_items', 'sale_banner_enabled',
                'sale_banner_label', 'sale_banner_title', 'sale_banner_subtitle',
                'sale_banner_discount', 'sale_banner_button_text', 'how_it_works_label',
                'how_it_works_title', 'how_it_works_subtitle', 'how_it_works_steps',
                'testimonials_label', 'testimonials_title', 'testimonials_subtitle',
                'testimonials', 'cta_title', 'cta_subtitle', 'cta_button_text',
                'footer_description', 'footer_copyright'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
