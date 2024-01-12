<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWithdraw extends Model
{
    use HasFactory, Filterable, HasBulkAction;

    protected $fillable =['user_id','amount','currency','is_confirmed', 'paypal_email'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

}
