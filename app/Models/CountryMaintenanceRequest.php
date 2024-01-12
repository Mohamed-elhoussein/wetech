<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryMaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'maintenance_request_id',
    ];

    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }
}
