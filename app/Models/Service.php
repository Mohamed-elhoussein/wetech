<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, Filterable, HasBulkAction;

    protected $fillable = ['id', "name", "name_en", "order_index", "description", "title_from", "specializ_from", "brand_from", "image", "active", "join_option", "is_country_city_street"];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($service) {
            $service->category()->delete();
        });
    }
    public function categories()
    {
        return $this->hasMany(ServiceCategories::class)->where('active', 1)->with('subcategories');
    }
    public function category()
    {
        return $this->hasMany(ServiceCategories::class);
    }

    public function subcategories()
    {
        return $this->hasMany(ServiceCategories::class, ServiceSubcategories::class);
    }

    public function providers()
    {
        return $this->belongsToMany(User::class, 'provider_services', 'service_id', 'provider_id')->with('rate:rate,rated_by', 'street:id,name', 'city:id,name');
    }

    public function provider_services()
    {
        return $this->hasMany(ProviderServices::class, 'service_id')->with('provider:id,username,active');
    }       
    
    public function provider_services_accepted()
    {
        return $this->hasMany(ProviderServices::class, 'service_id')->where('status', 'ACCEPTED')->with('provider:id,username,active');
    }    
    
    public function has_service_quick_offers()
    {
        return $this->hasMany(ProviderServices::class, 'service_id')->whereHas('service_quick_offers.quick_offer')->with('service_quick_offers.quick_offer');
    }

}
