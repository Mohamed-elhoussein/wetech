<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\target;
use App\Models\SlidersUrl;

class Slider extends Model
{
    use HasFactory, Filterable, HasBulkAction;

    protected $fillable = ['order_index' ,'name', 'text' , 'text_en', 'text_color','image', 'url', 'phone', 'target','visitableBtn', 'btn_color' , 'icon' , 'icon_color' , 'active' , 'created_at' , 'updated_at' , 'target_id'  , ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function slider_urls()
    {
        return $this->HasMany(SlidersUrl::class, 'slider_id')->where('active', 1);
    }
    
    public function target(){
        return $this->belongTo(target::class) ;
    }
    
    public function urls()
    {
        return $this->HasMany(SlidersUrl::class, 'slider_id');
    }

    

}
