<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanceledBuyerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buyer_request_id',
    ];

    public function buyer_request()
    {
        return $this->belongsTo(BuyerRequest::class, 'buyer_request_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
