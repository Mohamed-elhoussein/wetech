<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSub2 extends Model
{
    use HasFactory, SoftDeletes, Filterable, HasBulkAction;

    protected $fillable     =   ['name', 'name_en', 'image', 'service_subcategories_id', 'active', 'deleted_at'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    function service_subcategories()
    {
        return $this->belongsTo(ServiceSubcategories::class, 'service_subcategories_id');
    }


    public function service_sub_3()
    {
        return $this->hasMany(ServiceSub3::class)->where('active', 1);
    }
}
