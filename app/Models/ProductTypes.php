<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTypes extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'name_en',
        'product_categories_id'
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategories::class, 'product_categories_id');
    }
}
