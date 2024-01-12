<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlidersUrl extends Model
{
    use HasFactory;

    protected $fillable = ['slider_id', 'text', 'text_en', 'icon_name_or_url', 'icon_color', 'url', 'active'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
