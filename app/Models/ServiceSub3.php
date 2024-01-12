<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSub3 extends Model
{
    use HasFactory, SoftDeletes, Filterable, HasBulkAction;

    protected $fillable     =   ['name', 'name_en', 'service_sub2_id', 'image',  'active', 'deleted_at'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    function service_sub_3()
    {
        return $this->belongsTo(ServiceSub3::class, 'service_subcategories_id');
    }
    function service_sub_2()
    {
        return $this->belongsTo(ServiceSub2::class, 'service_sub2_id');
    }

    public function service_sub_4()
    {
        return $this->hasMany(ServiceSub4::class)->where('active', 1);
    }
    public function providerServices()
    {
        return $this->belongsTo(providerServices::class, 'sub3_id');
    }
}
