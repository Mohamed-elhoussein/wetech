<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreetMaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'street_id',
        'city_maintenance_request_id',
    ];

    public function street()
    {
        return $this->belongsTo(Street::class)->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function city_maintenance_request()
    {
        return $this->belongsTo(CityMaintenanceRequest::class, 'city_maintenance_request_id');
    }
}
