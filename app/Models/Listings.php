<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class Listings extends Model
{
    protected $table = 'listings';

    protected $fillable = [
        'user_id',
        'cat_id',
        'sub_cat_id',
        'location_id',
        'featured_listing',
        'title',
        'listing_slug',
        'description',
        'address',
        'video',
        'google_map_code',
        'amenities',
        'working_hours_mon',
        'working_hours_tue',
        'working_hours_wed',
        'working_hours_thurs',
        'working_hours_fri',
        'working_hours_sat',
        'working_hours_sun',
        'featured_image',
        'review_avg',
        'status',
        // Deal-focused fields
        'deal_price',
        'deal_original_price',
        'deal_discount_percentage',
        'deal_expires_at',
        'deal_quantity_total',
        'deal_quantity_sold',
        'deal_terms',
        'deal_redemption_instructions',
        'stripe_payment_link',
    ];

    protected $casts = [
        'deal_price' => 'decimal:2',
        'deal_original_price' => 'decimal:2',
        'deal_expires_at' => 'datetime',
    ];


    public static function getListingname($id) 
    { 
        $listings=Listings::find($id);

        return $listings->title;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'cat_id');
    }

    public function location()
    {
        return $this->belongsTo(City::class, 'location_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'location_id');
    }

    public function gallery()
    {
        return $this->hasMany(ListingGallery::class, 'listing_id');
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'listing_id');
    }

    // Accessors
    public function getDealsRemainingAttribute()
    {
        return max(0, ($this->deal_quantity_total ?? 0) - ($this->deal_quantity_sold ?? 0));
    }

    public function getIsDealActiveAttribute()
    {
        if (!$this->deal_price || !$this->deal_expires_at) {
            return false;
        }

        return $this->deal_expires_at->isFuture() && $this->deals_remaining > 0;
    }

    public function getDealSavingsAttribute()
    {
        if (!$this->deal_original_price || !$this->deal_price) {
            return 0;
        }
        return $this->deal_original_price - $this->deal_price;
    }

    // Scopes
    public function scopeActiveDeals($query)
    {
        return $query->whereNotNull('deal_price')
            ->whereNotNull('deal_expires_at')
            ->where('deal_expires_at', '>', now())
            ->whereRaw('deal_quantity_sold < deal_quantity_total');
    }
 
	//public $timestamps = false;
   
   public function scopeSearchByKeyword($query, $keyword,$location)
    {
        if ($keyword!='' and $location!='') {
            $query->where(function ($query) use ($keyword,$location) {
                $query->where("title", "LIKE","%$keyword%")
                    ->where("location_id", "$location")
                    ->where("status", "1");                     
            });
        }
        else
        {
        	 
        	$query->where(function ($query) use ($keyword) {
                $query->where("title", "LIKE","%$keyword%")
                ->where("status", "1");
                                    
            });
        }
        return $query;
    }

    public function scopeSearchByFilter($query, $category,$rating)
    {
        if ($category!='' and $rating!='') {
            $query->where(function ($query) use ($category,$rating) {
                $query->where("cat_id", "$category")
                    ->where("review_avg", "$rating")
                    ->where("status", "1");                     
            });
        }
        elseif ($category!='') {
            $query->where(function ($query) use ($category) {
                $query->where("cat_id", "$category")
                ->where("status", "1");
                                    
            });
        }
        else
        {
             
            $query->where(function ($query) use ($rating) {
                $query->where("review_avg", "$rating")->where("status", "1");
                                    
            });
        }
        return $query;
    }
}
