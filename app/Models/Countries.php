<?php

namespace App\Models;

use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory, HasBulkAction;

    protected $fillable  =  ['id', 'name', 'name_en', 'code', 'country_code', 'unit', 'unit_en', 'status', 'message', 'pin'];

    protected $table = 'countries';

    const ACTIVE = 'ACTIVE';

    public function cities()
    {
        return $this->hasMany(Cities::class, 'country_id');
    }

    public function scopeActive($query) {
        return $query->where('status', self::ACTIVE);
    }

    public function getCountryStatusAttribute()
    {
        if ($this->status == self::ACTIVE) {
            return "مفعل";
        }

        return "غير مفعل";
    }
}
