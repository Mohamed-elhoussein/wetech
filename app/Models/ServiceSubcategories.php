<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSubcategories extends Model
{
    use HasFactory, SoftDeletes, Filterable, HasBulkAction;

    protected $fillable     =   ['name', 'name_en', 'image', 'service_categories_id', 'active', 'deleted_at'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    function service_categories()
    {
        return $this->belongsTo(ServiceCategories::class, 'service_categories_id');
    }

    public function service_sub_2()
    {
        return $this->hasMany(ServiceSub2::class)->where('active', 1);
    }
}
