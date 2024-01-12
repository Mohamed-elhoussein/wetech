<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribePackes extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'name_en','days', 'price_sar', 'price_usd', 'active', 'apple_id'];
}
