<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id', 'category_id', 'title', 'slug', 'description', 'terms_conditions',
        'regular_price', 'deal_price', 'discount_percentage', 'savings_amount', 'featured_image', 'gallery_images',
        'stripe_payment_link', 'inventory_total', 'inventory_sold', 'inventory_remaining', 'location_city',
        'location_zip', 'location_address', 'location_latitude', 'location_longitude', 'featured_placement', 'priority_placement',
        'view_count', 'click_count', 'ai_quality_score', 'status', 'starts_at', 'expires_at',
        'auto_approved', 'admin_approved_at', 'admin_approved_by', 'admin_rejection_reason',
        'requires_admin_review', 'admin_review_reason', 'auto_paused', 'pause_reason'
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'deal_price' => 'decimal:2',
        'discount_percentage' => 'integer',
        'savings_amount' => 'decimal:2',
        'inventory_total' => 'integer',
        'inventory_sold' => 'integer',
        'inventory_remaining' => 'integer',
        'location_latitude' => 'decimal:8',
        'location_longitude' => 'decimal:8',
        'featured_placement' => 'boolean',
        'priority_placement' => 'boolean',
        'view_count' => 'integer',
        'click_count' => 'integer',
        'ai_quality_score' => 'integer',
        'auto_approved' => 'boolean',
        'requires_admin_review' => 'boolean',
        'auto_paused' => 'boolean',
        'gallery_images' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'admin_approved_at' => 'datetime',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(DealPurchase::class);
    }
    
    public function aiAnalysis()
    {
        return $this->hasOne(\App\Models\DealAIAnalysis::class);
    }
    
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->where(function($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->whereRaw('inventory_sold < inventory_total');
    }
    
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }
    
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now())
            ->where('status', 'active');
    }
    
    public function scopeSoldOut($query)
    {
        return $query->whereRaw('inventory_sold >= inventory_total')
            ->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured_placement', true);
    }

    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('expires_at', '<=', now()->addDays($days))
            ->where('expires_at', '>', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByLocation($query, $city = null, $zip = null)
    {
        if ($city) {
            $query->where('location_city', $city);
        }
        if ($zip) {
            $query->where('location_zip', $zip);
        }
        return $query;
    }

    public function scopeByPriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('deal_price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('deal_price', '<=', $max);
        }
        return $query;
    }

    public function scopeByDiscount($query, $minDiscount)
    {
        return $query->where('discount_percentage', '>=', $minDiscount);
    }

    public function scopeTrending($query)
    {
        return $query->orderByRaw('(view_count + click_count) DESC');
    }

    // Accessors (computed fields)
    public function getDiscountPercentageAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        if ($this->regular_price > 0) {
            return round((($this->regular_price - $this->deal_price) / $this->regular_price) * 100);
        }
        return 0;
    }
    
    public function getSavingsAmountAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        return $this->regular_price - $this->deal_price;
    }
    
    public function getInventoryRemainingAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        return max(0, $this->inventory_total - $this->inventory_sold);
    }
    
    public function getIsSoldOutAttribute()
    {
        return $this->inventory_total > 0 && $this->inventory_sold >= $this->inventory_total;
    }
    
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && now() > $this->expires_at;
    }
    
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' 
            && !$this->is_sold_out
            && !$this->is_expired
            && ($this->starts_at === null || $this->starts_at <= now());
    }
    
    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isSoldOut(): bool
    {
        return $this->is_sold_out;
    }
    
    public function isExpired(): bool
    {
        return $this->is_expired;
    }

    public function getRemainingInventory(): int
    {
        if ($this->inventory_total <= 0) {
            return -1; // Unlimited
        }
        return max(0, $this->inventory_total - $this->inventory_sold);
    }

    public function getInventoryPercentage(): float
    {
        if ($this->inventory_total <= 0) {
            return 0;
        }
        return ($this->inventory_sold / $this->inventory_total) * 100;
    }

    public function incrementInventorySold(int $amount = 1): void
    {
        $this->increment('inventory_sold', $amount);
        $this->refresh();
        
        if ($this->isSoldOut()) {
            $this->markAsSoldOut();
        }
    }
    
    public function markAsSoldOut(): void
    {
        $this->update(['status' => 'sold_out']);
    }
    
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    public function getDaysUntilExpiry(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }
        return max(0, now()->diffInDays($this->expires_at, false));
    }

    public function autoPause(string $reason): void
    {
        $this->update([
            'auto_paused' => true,
            'pause_reason' => $reason
        ]);
    }

    public function autoResume(): void
    {
        $this->update([
            'auto_paused' => false,
            'pause_reason' => null
        ]);
    }

    public function canBePurchased(): bool
    {
        if ($this->auto_paused) {
            return false;
        }
        
        $vendor = $this->vendor?->vendorProfile;
        if (!$vendor) {
            return false;
        }
        
        return !$vendor->hasReachedCapacity();
    }

    public function getFeaturedImageUrl(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }
        if (strpos($this->featured_image, 'http') === 0) {
            return $this->featured_image;
        }
        return asset('storage/deals/' . $this->featured_image);
    }
    
    public function getFeaturedImageThumbUrl(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }
        if (strpos($this->featured_image, 'http') === 0) {
            return $this->featured_image;
        }
        $path = pathinfo($this->featured_image);
        return asset('storage/deals/thumbs/' . $path['filename'] . '-thumb.' . $path['extension']);
    }

    public function getGalleryImageUrls(): array
    {
        if (!$this->gallery_images || !is_array($this->gallery_images)) {
            return [];
        }
        return array_map(function($image) {
            if (strpos($image, 'http') === 0) {
                return $image;
            }
            return asset('storage/deals/gallery/' . $image);
        }, $this->gallery_images);
    }
    
    // Boot method to calculate computed fields on save
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($deal) {
            // Calculate discount percentage
            if ($deal->regular_price > 0) {
                $deal->discount_percentage = round((($deal->regular_price - $deal->deal_price) / $deal->regular_price) * 100);
            }
            
            // Calculate savings amount
            $deal->savings_amount = $deal->regular_price - $deal->deal_price;
            
            // Calculate inventory remaining
            $deal->inventory_remaining = max(0, $deal->inventory_total - $deal->inventory_sold);
        });
    }
}
