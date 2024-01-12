<?php

namespace App\Models;

use Dotenv\Parser\Value;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Setting extends Model
{
    use HasFactory;



 protected $guarded =['id'];


 protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
];



public static function get($key ){
   $settings =  Setting::where('key',$key)->get('value');

    if($settings==[]){
             return "";
    }
    else{
        return $settings->pluck('value');
    }
}



}
