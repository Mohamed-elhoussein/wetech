<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFullOrderAttribute()
    {
        return str_replace('_', '-', $this->order_name) . '_' . $this->order_type;
    }
}
