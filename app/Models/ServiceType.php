<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model
{
    use HasFactory, Filterable, HasBulkAction;

    protected $fillable = ['name', 'name_en'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
