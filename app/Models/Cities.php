<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory, Filterable, HasBulkAction;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    protected $fillable = ['name', 'name_en', 'country_id', 'status'];


    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }

    public function street()
    {
        return $this->hasMany(Street::class, 'city_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function city_maintenance_requests()
    {
        return $this->hasMany(CityMaintenanceRequest::class, 'city_id');
    }

    public function provider_service()
    {
        return $this->hasMany(ProviderServices::class, 'city_id')->where('status', 'ACCEPTED')->with('rating:stars');;
    }
}
