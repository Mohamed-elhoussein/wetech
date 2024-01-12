<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable  = ['product_id', 'user_id', 'quantity', 'order_id', 'price', 'coupon_id', 'note', 'is_exists'];

    protected $casts = [
        'is_exists'         => 'boolean',
        'created_at'        => 'datetime:Y-m-d H:i:s',
        'updated_at'        => 'datetime:Y-m-d H:i:s',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', "id");
    }

    protected static function booted()
    {
        static::addGlobalScope('user_cart', function ($query) {
            if (auth('sanctum')->check() && auth('sanctum')->user()->role != 'chat_review') {
                $query->whereUserId(auth('sanctum')->id());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, "id");
    }

    public function order()
    {
        return $this->belongsTo(Order::class, "id");
    }

    public function scopeAuthUser($query, $id = null)
    {
        if (!is_null($id)) {
            return $query->where('user_id', $id)->InCart();
        }

        return $query->where('user_id', Auth::user()?->id)->InCart();
    }

    public function scopeMyCart($query)
    {
        return $query->where('user_id', Auth::user()->id)->InCart();
    }

    public function scopeInCart($query)
    {
        return $query->whereNull('order_id');
    }

}
