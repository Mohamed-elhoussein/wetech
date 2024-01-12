<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identity extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'image',
        'user_id',
        'status'
    ];

    const STORAGE_PATH = 'identity';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
