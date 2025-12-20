<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Listings;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'name',
        'slug',
        'county',
        'state',
        'zip_codes',
        'latitude',
        'longitude',
        'population',
        'description',
        'image',
        'is_featured',
        'status',
        'deals_count',
        'sort_order',
    ];

    protected $casts = [
        'zip_codes' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($city) {
            if (empty($city->slug)) {
                $city->slug = Str::slug($city->name);
            }
        });

        static::updating(function ($city) {
            if ($city->isDirty('name')) {
                $city->slug = Str::slug($city->name);
            }
        });
    }

    // Relationships
    public function listings()
    {
        return $this->hasMany(Listings::class, 'location_id');
    }

    // Scopes
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
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getActiveDealsCountAttribute()
    {
        return $this->listings()
            ->whereNotNull('deal_price')
            ->where('deal_expires_at', '>', now())
            ->count();
    }

    public function getFullAddressAttribute()
    {
        return "{$this->name}, {$this->state}";
    }
}

