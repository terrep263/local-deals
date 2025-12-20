<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Categories;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate sitemap.xml
     */
    public function index()
    {
        $urls = [];
        
        // Homepage
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];
        
        // Pricing page
        $urls[] = [
            'loc' => url('/pricing'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ];
        
        // Browse deals page
        $urls[] = [
            'loc' => url('/deals'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'hourly',
            'priority' => '0.9',
        ];
        
        // Category pages
        $categories = Categories::all();
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => url('/category/' . $category->category_slug),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.7',
            ];
        }
        
        // Active deal pages
        $deals = Deal::active()->get();
        foreach ($deals as $deal) {
            $urls[] = [
                'loc' => url('/deals/' . $deal->slug),
                'lastmod' => $deal->updated_at->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ];
        }
        
        $xml = view('sitemap.index', compact('urls'))->render();
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}


