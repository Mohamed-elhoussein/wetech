<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'payment_method',
        'value',
        'active',
        'active_for',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeForMaintenance($query) {
        return $query
            ->where('active_for', 'maintenance')
            ->orWhere('active_for', 'both')
        ;
    }

    public function scopeForOrder($query) {
        return $query
            ->where('active_for', 'product')
            ->orWhere('active_for', 'both')
        ;
    }

    public function scopeOnline($query)
    {
        return $query->whereIn('payment_method', ['online', 'both']);
    }
}
