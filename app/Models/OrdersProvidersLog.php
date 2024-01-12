<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class OrdersProvidersLog extends Model
{
    use HasFactory;

    protected $fillable   =  ['order_id', 'provider_id','provider_service_id', 'offer_id', 'price', 'commission', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',

    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id')->withTrashed()->select('id', 'username');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->withTrashed();
    }    
}
