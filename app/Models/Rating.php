<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rating extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['order_id', 'rated_by', 'stars', 'experience', 'performance', 'respect_the_time', 'comment'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class)->with('provider', 'user', 'service');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'rated_by')->withTrashed();
    }

    public function scopeAvgForProvider(Builder $query, int $provider_id)
    {
        return $query->select(DB::raw('FORMAT(avg(stars), 1) as stars, count(id) as total'))->whereHas('order', function ($q) use ($provider_id) {
            $q->whereProviderId($provider_id);
        });
    }

    public function scopeCount()
    {
    }
}
