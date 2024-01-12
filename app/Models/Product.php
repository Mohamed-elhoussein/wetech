<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use App\Enum\ProductPaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, Filterable, HasBulkAction, SoftDeletes;

    protected $fillable = [
        'order_index',
        'user_id',
        'name',
        'name_en',
        'city_id',
        'payment_method',
        'street_id',
        'product_category_id',
        'product_type_id',
        'product_brand_id',
        'images',
        'color',
        'disk_info',
        'duration_of_use',
        'guarantee',
        'status',
        'revision_status',
        'in_update',
        'price',
        'is_offer',
        'offer_price',
        'delivery_fee',
        'description',
        'is_best_seller',
        'active'
    ];

    protected $casts = [
        'images'            => 'array',
        'payment_method'    => 'array',
        'created_at'        => 'datetime:Y-m-d H:i:s',
        'updated_at'        => 'datetime:Y-m-d H:i:s',
    ];

    public function scopeAccepted($query)
    {
        $query->where('revision_status', 'accepted');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function city()
    {
        return $this->belongsTo(Cities::class);
    }
    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'product_brand_id');
    }
    public function type()
    {
        return $this->belongsTo(ProductTypes::class, 'product_type_id');
    }
    public function category()
    {
        return $this->belongsTo(ProductCategories::class, 'product_category_id');
    }
    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function favorite()
    {
        return $this->hasOne(UserLikedProducts::class, 'product_id');
    }
    public function cart()
    {
        return $this->hasOne(Cart::class, "product_id")->whereNull('order_id');

        // ['user_id',Auth::user()->id] دي علشان يجيب الكارت الخاصة باليوزر ده بس
        //['order_id',null] علشان ميجبش اللى بعت طلبهم كما
        // return $this->hasOne("App\Models\Cart", "product_id")->where([
        //     ['user_id', auth()->user()?->id],
        //     ['order_id', null]
        // ]);
    }
    public function cartOrder()
    {
        return $this->hasOne(Cart::class, "product_id");
    }

    public function acceptAnyPaymentMethod(): bool
    {
        return count($this->payment_method) > 1;
    }

    public function acceptCOD(): bool
    {
        return count($this->payment_method) == 1 && in_array(ProductPaymentMethod::COD, $this->payment_method);
    }

    public function acceptMyFatoorah(): bool
    {
        return count($this->payment_method) == 1 && in_array(ProductPaymentMethod::MY_FATOORAH, $this->payment_method);
    }

    public function getPaymentMethodName(): string
    {
        if ($this->acceptAnyPaymentMethod()) {
            return 'كلا الطريقتين';
        }
        elseif ($this->acceptCOD()) {
            return 'الدفع عند الإستلام';
        }
        else {
            return 'عبر مايفاتوره';
        }
    }
    public function rating()
    {
        return $this->hasManyThrough(Rating::class, Order::class, 'product_id');
    }

    public function getImageAttribute()
    {
        if (!is_array($this->images)) {
            return json_decode($this->images)[0];
        }

        return $this->images[0];
    }

    public function scopeCity($q, $city_id) {
        return $q->where('city_id', $city_id);
    }
}
