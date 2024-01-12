<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class  UserLikedServices extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'provider_service_id'];

    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function ProviderServices()
    {
        return $this->belongsTo(ProviderServices::class, 'provider_service_id')
        ->where('status', 'ACCEPTED')
        ->with('provider:id,number_phone,username,active,verified,country_id', 'rating',  'offers', 'service_full:id,name', 'serviceType'); // 'quickOffer',
    }

}
