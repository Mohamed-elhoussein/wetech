<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'price',
        'type_id',
        'provider_id',
        'maintenance_request_id',
    ];

    public function maintenance_request()
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id')->withDefault(['name' => 'غير محدد']);
    }

    public function city()
    {
        return $this->belongsTo(Cities::class, 'city_id')->withDefault(['name' => 'غير محدد']);
    }

    public function street()
    {
        return $this->belongsTo(Street::class, 'street_id')->withDefault(['name' => 'غير محدد']);
    }

    public function type()
    {
        return $this->belongsTo(MaintenanceRequestType::class, 'type_id')->withDefault(['name' => 'غير محدد']);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id')->withDefault(['username' => 'غير معروف']);
    }
}
