<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequestCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "code",
        "value",
        "type",
        "is_active",
        "expired_at",
        "belong_to",
    ];

    protected $casts = [
        "expired_at" => "date",
        "is_active" => "boolean"
    ];

    public function expired()
    {
        return today()->greaterThan($this->expired_at);
    }
    public function scopeCode($query, $code = null)
    {
        return $query->where('code', $code);
    }
    public function forProduct($id)
    {
        $cop  = $this->where('id' , $id)
          ->whereIn('belong_to' , ['p', 'm,p'])
          ->where('is_active' , 1)
          ->first();

        if(isset($cop))
            if($cop->expired())
               return Null;
        return $cop;
    }
    public function forBoth()
    {
        return $this->belong_to == 'm,p';
    }
    public function forMaintenance()
    {
        return $this->belong_to == 'm' || $this->belong_to == 'm,p';
    }
}
