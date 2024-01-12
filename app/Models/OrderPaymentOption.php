<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPaymentOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_option_id',
        'value',
        'type',
    ];

    public function payment_option()
    {
        return $this->belongsTo(PaymentOption::class, 'payment_option_id');
    }
}
