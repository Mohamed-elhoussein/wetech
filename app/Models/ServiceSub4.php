<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSub4 extends Model
{
    use HasFactory, SoftDeletes, Filterable, HasBulkAction;

    protected $fillable     =   ['name', 'name_en', 'image', 'service_sub3_id', 'active', 'deleted_at'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    function service_sub_3()
    {
        return $this->belongsTo(ServiceSub3::class, 'service_sub3_id');
    }
    public function providerServices()
    {
        return $this->belongsTo(providerServices::class, 'sub3_id');
    }
}
