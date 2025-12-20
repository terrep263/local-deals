<?php

namespace App\Models;
use App\Models\Listings;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'category_icon',
        'category_name',
        'category_slug',
        'description',
        'color',
        'is_featured',
        'status',
        'deals_count',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public $timestamps = false;

    public static function getCategoryInfo($id) 
    { 
        return Categories::find($id);
    }

    public static function countCategoryListings($id) 
    { 
        return Listings::where(['cat_id' => $id,'status' => '1'])->count();
    }
    
    public function deals()
    {
        return $this->hasMany(\App\Models\Deal::class, 'category_id');
    }

    public function listings()
    {
        return $this->hasMany(Listings::class, 'cat_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('category_name');
    }

    public function getActiveDealsCountAttribute()
    {
        return $this->listings()
            ->whereNotNull('deal_price')
            ->whereNotNull('deal_expires_at')
            ->where('deal_expires_at', '>', now())
            ->count();
    }
}
