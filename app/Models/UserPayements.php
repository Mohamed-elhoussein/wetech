<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayements extends Model
{
    use HasFactory;

    protected $fillable= ['user_id','amount','fee','method','payment_id','currency','is_paid'];
}
