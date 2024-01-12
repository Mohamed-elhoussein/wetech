<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProviderServices extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $fillable     =   ['provider_id', 'service_id', 'service_categories_id', 'service_subcategories_id', 'sub2_id', 'sub3_id', 'sub4_id', 'country_id', 'city_id', 'street_id', 'quick_offer_id', 'title', 'specializ', 'brand', 'type', 'thumbnail', 'gallery', 'description', 'status', 'in_update', 'country_city_street', 'cat_subcat_sub1_sub2_sub3_sub4', 'pin_top', 'deleted_at'];

    protected $casts = [
        'quick_offer_id' => 'array',
        'created_at'     => 'datetime:Y-m-d H:i:s',
        'updated_at'     => 'datetime:Y-m-d H:i:s',
    ];

    public function service_quick_offers()
    {
        return $this->hasMany(ServiceQuickOffer::class, 'service_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id')->withTrashed();
    }
    public function offers()
    {
        return $this->hasMany(Offer::class, 'provider_service_id')->where('target', 'all')->where('status', 'ACTIVE');
    }
    public function all_offers()
    {
        return $this->hasMany(Offer::class, 'provider_service_id')->where('target', 'all');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id')->pluck('name');
    }
    public function service_full()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }    
    public function service_category()
    {
        return $this->belongsTo(ServiceCategories::class, 'service_categories_id');
    }    
    public function service_subcategories()
    {
        return $this->belongsTo(ServiceSubcategories::class, 'service_subcategories_id');
    }    
    public function service_sub2()
    {
        return $this->belongsTo(ServiceSub2::class, 'sub2_id');
    }    
    public function service_sub3()
    {
        return $this->belongsTo(ServiceSub3::class, 'sub3_id');
    }    
    public function service_sub4()
    {
        return $this->belongsTo(ServiceSub4::class, 'sub4_id');
    }
    public function ratings()
    {
        return $this->hasManyThrough(Rating::class, Order::class, 'provider_service_id')->with('user:id,avatar,username,created_at');
    }
    public function rating()
    {
        return $this->hasManyThrough(Rating::class, Order::class, 'provider_service_id');
    }
    public function favourites()
    {
        return $this->hasMany(UserLikedServices::class, 'provider_service_id');
    }
    public function street()
    {
        return $this->belongsTo(Street::class);
    }
    public function city()
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }
    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }
    public function quickOffer()
    {
        return $this->belongsTo(QuickOffers::class);
    }
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class, 'type');
    }
}
