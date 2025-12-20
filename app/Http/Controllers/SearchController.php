<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search deals
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('deals.index');
        }
        
        $deals = Deal::active()
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhereHas('vendor', function($q2) use ($query) {
                      $q2->where('first_name', 'LIKE', "%{$query}%")
                         ->orWhere('last_name', 'LIKE', "%{$query}%");
                  })
                  ->orWhereHas('category', function($q2) use ($query) {
                      $q2->where('category_name', 'LIKE', "%{$query}%");
                  })
                  ->orWhere('location_city', 'LIKE', "%{$query}%");
            })
            ->with(['vendor', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(24);
        
        return view('deals.search', compact('deals', 'query'));
    }
    
    /**
     * Autocomplete suggestions (AJAX)
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $suggestions = [];
        
        // Deal titles
        $dealTitles = Deal::active()
            ->where('title', 'LIKE', "%{$query}%")
            ->limit(5)
            ->pluck('title')
            ->map(function($title) {
                return ['type' => 'deal', 'text' => $title];
            });
        
        // Categories
        $categories = \App\Models\Categories::where('category_name', 'LIKE', "%{$query}%")
            ->limit(3)
            ->pluck('category_name')
            ->map(function($name) {
                return ['type' => 'category', 'text' => $name];
            });
        
        // Cities
        $cities = Deal::active()
            ->where('location_city', 'LIKE', "%{$query}%")
            ->distinct()
            ->limit(3)
            ->pluck('location_city')
            ->map(function($city) {
                return ['type' => 'city', 'text' => $city];
            });
        
        $suggestions = $dealTitles->merge($categories)->merge($cities)->take(10)->values();
        
        return response()->json($suggestions);
    }
}


