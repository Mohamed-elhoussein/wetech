<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class  UserLikedProducts extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'product_id'];

    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function Products()
    {
        return $this->belongsTo(Product::class, 'product_id')
        ->where('active', 1)
        ->where('revision_status', 'accepted')
        ->with('user:id,username,number_phone,country_id', 'user.country', 'city:id,country_id,name,name_en', 'brand', 'type');
    }

}
