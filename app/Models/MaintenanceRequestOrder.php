<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class MaintenanceRequestOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_type_id',
        'provider_id',
        'note',
        'payment_method',
        'payment_option_id',
        'color_id',
        'city_id',
        'street_id',
    ];


    public function maintenance_type()
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id');
    }

    public function maintenance_request()
    {
        return optional($this->maintenance_type)->maintenance_request;
    }

    public function maintenance_request_order_coupon()
    {
        return $this->hasOne(MaintenanceRequestOrderCoupon::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function city()
    {
        return $this->belongsTo(CityMaintenanceRequest::class, 'city_id');
    }

    public function street()
    {
        return $this->belongsTo(StreetMaintenanceRequest::class, 'street_id');
    }

    public function payment_option()
    {
        return $this->belongsTo(PaymentOption::class, 'payment_option_id');
    }

    public function shouldCheckoutOnline()
    {
        return in_array($this->payment_method, ['epay', 'paypal']);
    }

    public function getPriceAttribute()
    {
        $base_order = Order::query()->where('maintenance_request_order_id', $this->id)->first();

        $price = $this->maintenance_type->price;

        // Get payment option
        $payment_option = OrderPaymentOption::query()->where('order_id', $base_order->id)->first();

        if ($payment_option) {
            if ($payment_option->type == 'sub') return $payment_option->value;

            if ($payment_option->type == 'plus') return $price + $payment_option->value;
        }

        return $price;
    }

}
