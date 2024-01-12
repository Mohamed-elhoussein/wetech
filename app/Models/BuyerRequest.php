<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $fillable = [
    //     'status',
    //     'date',
    //     'image',
    //     'description',
    //     'service_id',
    //     'service_type_id',
    //     'city_id',
    //     'provider_id',
    //     'user_id',
    //     'street_id',
    //     'product_type_id',
    // ];

    protected static function booted()
    {
        static::creating(fn (self $buyerRequest) => $buyerRequest->user_id = auth()->id());
        static::addGlobalScope(function ($query) {
            $user = auth()->user();
            if($user->role == 'provider') {
                $user_services = $user->provider_services()->select('service_id')->get()->pluck('service_id')->unique()->values()->toArray();

                $query->whereIn('service_id', $user_services)
                ->when($user->city_id, function ($query) use ($user) {
                    $query->where('city_id', $user->city_id);
                })->when($user->street_id, function ($query) use ($user) {
                    $query->where('street_id', $user->street_id);
                });
            }
        });
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function city()
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function product_type()
    {
        return $this->belongsTo(ProductTypes::class, 'product_type_id');
    }

    public function canceled_buyer_request()
    {
        return $this->hasMany(CanceledBuyerRequest::class)->with('provider:id,username');
    }
}
