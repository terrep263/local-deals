<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\Deal;

class SlugHelper
{
    /**
     * Generate a unique slug for a deal
     * 
     * @param string $title
     * @param int|null $dealId Exclude this deal ID when checking uniqueness (for updates)
     * @return string
     */
    public static function generateDealSlug(string $title, ?int $dealId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (self::slugExists($slug, $dealId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if a slug already exists
     * 
     * @param string $slug
     * @param int|null $excludeDealId
     * @return bool
     */
    private static function slugExists(string $slug, ?int $excludeDealId = null): bool
    {
        $query = Deal::where('slug', $slug);
        
        if ($excludeDealId) {
            $query->where('id', '!=', $excludeDealId);
        }
        
        return $query->exists();
    }
}


