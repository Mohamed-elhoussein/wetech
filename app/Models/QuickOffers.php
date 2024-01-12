<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickOffers extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['title', 'title_en', 'body', 'body_en', 'image', 'price'];
    protected $appends = ['image'];


    public function getImageAttribute()
    {
        return $this->attributes['image'] ? url($this->attributes['image']) : null;
    }

    public function service_quick_offers()
    {
        return $this->hasMany(ServiceQuickOffer::class, 'quick_offer_id');
    }
}
