<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['id', 'name', 'name_en', 'icon', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function ProductTypes()
    {
        return $this->hasMany(ProductTypes::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }    
    public function products_accepted_and_active()
    {
        return $this->hasMany(Product::class, 'product_category_id')->where('revision_status', 'accepted')->where('active', '1');
    }
}
