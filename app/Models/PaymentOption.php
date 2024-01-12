<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOption extends Model
{
    use HasFactory;

    const PAYMENT_TYPES = [
        'online',
        'cash',
        'both',
    ];

    const PAYMENT_GATEWAYS = [
        'epay',
        'paypal',
    ];

    const TYPES = [
        'sub',
        'plus',
    ];

    protected $fillable = [
        "label",
        "label_en",
        "sub_text",
        "value",
        "type",
        "payment_type",
        "payment_gateway",
    ];
}
