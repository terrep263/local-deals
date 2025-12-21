<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealCategory;
use App\Models\Page;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . url('/') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toW3cString() . '</lastmod>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>1.0</priority>';
        $sitemap .= '</url>';

        // Static pages
        $staticPages = [
            'pricing' => ['changefreq' => 'monthly', 'priority' => '0.8'],
            'about' => ['changefreq' => 'monthly', 'priority' => '0.7'],
            'contact' => ['changefreq' => 'monthly', 'priority' => '0.6'],
            'terms' => ['changefreq' => 'yearly', 'priority' => '0.5'],
            'privacy' => ['changefreq' => 'yearly', 'priority' => '0.5'],
        ];

        foreach ($staticPages as $page => $settings) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/' . $page) . '</loc>';
            $sitemap .= '<changefreq>' . $settings['changefreq'] . '</changefreq>';
            $sitemap .= '<priority>' . $settings['priority'] . '</priority>';
            $sitemap .= '</url>';
        }

        // Active deals
        $deals = Deal::where('status', 'active')
            ->where('end_date', '>', now())
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($deals as $deal) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/deals/' . $deal->slug) . '</loc>';
            $sitemap .= '<lastmod>' . $deal->updated_at->toW3cString() . '</lastmod>';
            $sitemap .= '<changefreq>daily</changefreq>';
            $sitemap .= '<priority>0.9</priority>';
            $sitemap .= '</url>';
        }

        // Categories
        $categories = DealCategory::all();
        foreach ($categories as $category) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/category/' . $category->slug) . '</loc>';
            $sitemap .= '<changefreq>daily</changefreq>';
            $sitemap .= '<priority>0.8</priority>';
            $sitemap .= '</url>';
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
}
