<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceQuickOffer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function service()
    {
        return $this->belongsTo(ProviderServices::class);
    }

    public function quick_offer()
    {
        return $this->belongsTo(QuickOffers::class);
    }
}
