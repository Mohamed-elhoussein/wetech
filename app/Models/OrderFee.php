<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_name',
        'fee_name_en',
        'fee_value',
        'order_id',
    ];
}
