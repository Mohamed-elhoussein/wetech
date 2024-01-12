<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = ['user_id', 'provider_id', 'canceled_reason', 'offer_id', 'product_id', 'product_payment_id', 'buyer_request_id', 'provider_service_id', 'status', 'price', 'coupon_id', 'commission', 'address', 'other_phone', 'canceled_by', 'maintenance_request_order_id'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopestatistics($query)
    {
        $query->toBase()
            ->selectRaw("count(case when status = 'COMPLETED' then 1 end) as completed")
            ->selectRaw("count(case when status = 'CANCELED' then 1 end) as canceled")
            ->selectRaw("count(case when status in ('WAITING' , 'PENDING', 'ONE_SIDED_CANCELED') then 1 end) as pending");
    }
    public function scopeProductStatisticsMonthly($query , $month , $year){

        return $query->whereNotNull('product_id')
                     ->whereMonth('created_at', $month)
                     ->whereYear('created_at', $year)
                     ->selectRaw('SUM(CASE WHEN status = "COMPLETED" THEN price ELSE 0 END) AS total_amount');
    }


    public function scopeProviderStatisticsMonthly($query , $month , $year){

        return $query->whereNotNull('provider_service_id')
                      ->whereMonth('created_at', $month)
                     ->whereYear('created_at', $year)
                     ->selectRaw('SUM(CASE WHEN status = "COMPLETED" THEN price else 0 END) AS total_amount');
    }
    public function scopeCouponStatisticsMonthly($query , $month , $year , $ids){

        return $query->whereIn('maintenance_request_order_id' , $ids)
                      ->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year)
                     ->selectRaw('SUM(CASE WHEN status = "COMPLETED" THEN price else 0 END) AS total_amount');
    }
    public function scopeNonCouponStatisticsMonthly($query , $month , $year , $ids){

        return $query->whereNotIn('maintenance_request_order_id' , $ids)
                        ->whereNotNull('maintenance_request_order_id')
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->selectRaw('SUM(CASE WHEN status = "COMPLETED" THEN price else 0 END) AS total_amount');
    }


    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'username' => 'غير محدد'
        ])->withTrashed();
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($order) {
            $order->rating()->delete();
        });
    }
    public function rating()
    {
        return $this->hasMany(Rating::class);
    }
    public function rating_user()
    {
        return $this->hasOne(Rating::class)->with('user:id,avatar,username');
    }
    public function provider_service()
    {
        return $this->belongsTo(ProviderServices::class, 'provider_service_id')->withDefault([
            'title' => ''
        ])->withTrashed();
    }
    public function provider_service_online()
    {
        return $this->belongsTo(ProviderServices::class, 'provider_service_id')->where('service_id', 6)->withTrashed();
    }
    public function provider_service_not_online()
    {
        return $this->belongsTo(ProviderServices::class, 'provider_service_id')->where('service_id', '!=', 6)->withTrashed();
    }
    public function offer()
    {
        return $this->belongsTo(Offer::class)->withTrashed();
    }
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
    public function product_payment()
    {
        return $this->belongsTo(ProductPayment::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function log_providers()
    {
        return $this->hasMany(OrdersProvidersLog::class)->orderBy('created_at', 'desc');
    }
    public function buyer_request()
    {
        return $this->belongsTo(BuyerRequest::class);
    }
    public function maintenance_request_order()
    {
        return $this->belongsTo(MaintenanceRequestOrder::class, 'maintenance_request_order_id');
    }

    public function order_fees()
    {
        return $this->hasMany(OrderFee::class);
    }

    public function order_payment_option()
    {
        return $this->hasOne(OrderPaymentOption::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function coupon()
    {
        return $this->belongsTo(MaintenanceRequestCoupon::class);
    }

    public function saveFees($payment_method)
    {
        if ($payment_method != 'cash') {
            $fees = Fee::query()->active()->forMaintenance()->whereIn('payment_method', ['online', 'both'])->get();
        }
        else {
            $fees = Fee::query()->active()->forMaintenance()->whereIn('payment_method', ['cash', 'both'])->get();
        }

        $fees->map(function ($fee)
        {
            OrderFee::create([
                'fee_name' => $fee->name,
                'fee_name_en' => $fee->name_en,
                'fee_value' => $fee->value,
                'order_id' => $this->id
            ]);
        });
    }

    public function savePaymentOption($payment_option)
    {
        OrderPaymentOption::create([
            'order_id' => $this->id,
            'payment_option_id' => $payment_option['id'],
            'value' => $payment_option['value'],
            'type' => $payment_option['type'],
        ]);

        return $this;
    }
}
