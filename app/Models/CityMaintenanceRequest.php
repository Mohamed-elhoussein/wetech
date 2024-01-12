<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityMaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'maintenance_request_id',
    ];

    public function city()
    {
        return $this->belongsTo(Cities::class, 'city_id')->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function streets()
    {
        return $this->hasMany(StreetMaintenanceRequest::class, 'city_maintenance_request_id');
    }
}
