<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $fillable = ['id', 'user_id', 'customer_id','order_id', 'user_payment_id', 'type', 'amount', 'is_usd'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

    public function user_payement()
    {
        return  $this->belongsTo(UserPayements::class, 'user_payment_id');
    }
}
