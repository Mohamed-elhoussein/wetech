<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequestOrderCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_request_order_id',
        'maintenance_request_coupon_id',
    ];

    public function coupon()
    {
        return $this->belongsTo(MaintenanceRequestCoupon::class, 'maintenance_request_coupon_id');
    }
}
