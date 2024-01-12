<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Welcome extends Model
{
    use HasFactory;
    protected  $fillable = ['titel', 'titel_en', 'body', 'body_en', 'image'];
}
