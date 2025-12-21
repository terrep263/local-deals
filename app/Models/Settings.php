<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'site_name', 'currency_symbol', 'site_email', 'site_logo', 'site_favicon',
        'site_description', 'styling', 'time_zone', 'currency_code', 'site_header_code',
        'site_footer_code', 'site_footer_logo', 'site_footer_text', 'site_copyright',
        'addthis_share_code', 'disqus_comment_code', 'facebook_comment_code',
        'home_slide_image1', 'home_slide_image2', 'home_slide_image3', 'home_slide_title',
        'home_slide_text', 'home_title', 'home_sub_title', 'home_categories_ids',
        'home_bg_image', 'page_bg_image', 'about_title', 'about_description',
        'contact_title', 'contact_address', 'contact_email', 'contact_number',
        'contact_lat', 'contact_long', 'terms_of_title', 'terms_of_description',
        'privacy_policy_title', 'privacy_policy_description', 'facebook_url',
        'twitter_url', 'gplus_url', 'linkedin_url', 'instagram_url', 'smtp_host',
        'smtp_port', 'smtp_email', 'smtp_password', 'smtp_encryption', 'google_login',
        'facebook_login', 'google_client_id', 'google_client_secret', 'google_redirect',
        'facebook_app_id', 'facebook_client_secret', 'facebook_redirect',
        // Homepage settings
        'hero_badge_text', 'hero_title', 'hero_title_highlight', 'hero_subtitle',
        'hero_disclaimer', 'hero_stats', 'promo_banner_items', 'sale_banner_enabled',
        'sale_banner_label', 'sale_banner_title', 'sale_banner_subtitle',
        'sale_banner_discount', 'sale_banner_button_text', 'how_it_works_label',
        'how_it_works_title', 'how_it_works_subtitle', 'how_it_works_steps',
        'testimonials_label', 'testimonials_title', 'testimonials_subtitle',
        'testimonials', 'cta_title', 'cta_subtitle', 'cta_button_text',
        'footer_description', 'footer_copyright'
    ];

    /**
     * Cast JSON fields to arrays automatically
     */
    protected $casts = [
        'hero_stats' => 'array',
        'promo_banner_items' => 'array',
        'how_it_works_steps' => 'array',
        'testimonials' => 'array',
        'sale_banner_enabled' => 'boolean',
    ];

    public $timestamps = false;
}
