<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategories extends Model
{
    use HasFactory, SoftDeletes, Filterable, HasBulkAction;

    protected $fillable = ['name', 'name_en', 'image', 'active', 'service_id', 'deleted_at'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function services()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function subcategories()
    {
        return $this->hasMany(ServiceSubcategories::class)->where('active', 1);
    }
    public function subcategory()
    {
        return $this->hasMany(ServiceSubcategories::class);
    }
    public function providers()
    {
        return $this->services()->with('provider');
    }
}
