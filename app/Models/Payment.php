<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['product_id', 'payment_id', 'transaction_id', 'user_id', 'provider_id', 'offer_id', 'order_id', 'method', 'message_id', 'amount', 'currency', 'paid'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPaimentStatusAttribute()
    {
        if ($this->paid) {
            return 'نعم';
        }

        return 'لا';
    }
}
