<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\SlidersUrl;


class Target extends Model
{
    use HasFactory;
    protected $fillable = ['name' , 'icon'];


public function sliders(): HasMany
{
    return $this->hasMany(slider::class);
}

public function target_urls(): HasManyThrough
{
    return $this->HasManyThrough( SlidersUrl::class , Slider::class)  ;
}

}