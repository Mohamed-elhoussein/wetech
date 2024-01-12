<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable   =  ['provider_id', 'provider_service_id', 'description', 'price', 'status','target', 'deleted_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',

    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function provider_service()
    {
        return $this->belongsTo(ProviderServices::class, 'provider_service_id')->withTrashed();
    }    
    
    public function service()
    {
        return $this->belongsTo(ProviderServices::class, 'provider_service_id')->withTrashed();
    }
}
