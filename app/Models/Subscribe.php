<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    use HasFactory, Filterable;
    protected $fillable = ['user_id', 'amount', 'currency', 'payment_id', 'die_at', 'method', 'is_paid', 'total_days'];

    protected $casts = [
        'die_at' => 'datetime'
    ];
    protected $dates = ['die_at'];

    function user()
    {
        return $this->belongsTo(user::class);
    }
}
