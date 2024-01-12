<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceOrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        "maintenance_order_id",
        "payment_id",
    ];
}
