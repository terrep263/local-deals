<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Deal;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Show category page with deals
     */
    public function show($slug, Request $request)
    {
        $category = Categories::where('category_slug', $slug)->firstOrFail();
        
        $query = Deal::active()
            ->where('category_id', $category->id)
            ->with(['vendor', 'category']);
        
        // Filters
        if ($request->filled('city')) {
            $query->where('location_city', $request->city);
        }
        
        if ($request->filled('zip')) {
            $query->where('location_zip', $request->zip);
        }
        
        if ($request->filled('price_min')) {
            $query->where('deal_price', '>=', $request->price_min);
        }
        
        if ($request->filled('price_max')) {
            $query->where('deal_price', '<=', $request->price_max);
        }
        
        if ($request->filled('discount')) {
            $query->where('discount_percentage', '>=', $request->discount);
        }
        
        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'ending_soon':
                $query->orderBy('expires_at', 'asc');
                break;
            case 'best_discount':
                $query->orderBy('discount_percentage', 'desc');
                break;
            case 'price_low':
                $query->orderBy('deal_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('deal_price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $deals = $query->paginate(24);
        
        $dealCount = Deal::active()->where('category_id', $category->id)->count();
        
        $cities = [
            'Fruitland Park', 'Lady Lake', 'Leesburg', 'The Villages', 'Tavares',
            'Mount Dora', 'Eustis', 'Umatilla', 'Clermont', 'Minneola',
            'Groveland', 'Mascotte', 'Montverde', 'Howey-in-the-Hills', 'Astatula', 'Okahumpka',
        ];
        
        return view('categories.show', compact('category', 'deals', 'dealCount', 'cities'));
    }
}


