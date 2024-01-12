<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppRates extends Model
{
    use HasFactory, Filterable;
    
    protected $fillable  =  ['user_id','comment','stars'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
