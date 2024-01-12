<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOffers extends Model
{
    use HasFactory;
    protected $fillable =  ['service_id','offer_id','active'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    public function offer()
    {
        return $this->belongsTo(Offer::class,'offer_id');
    }
}
