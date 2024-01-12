<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\permission;
use \stdClass;

class monitorPermission extends Model
{
    use HasFactory;
    protected $fillable =['permission_id' , 'monitor_id' , 'user_has_per'] ;

    public function permission()
    {
    return $this->belongsTo(permission::class ,'permission_id');

    } 

    public static function  getPermissions($id){

        
        $observer_permission         =      monitorPermission::with('permission:id,name,e_name')->where('monitor_id' , $id )->get();

        if(count($observer_permission) == 0){

            foreach(range(1 , count(permission::all())) as $key=>$value){
            monitorPermission::create(['permission_id'=>$value , 'monitor_id'=>$id , 'user_has_per'=>false]);
            }

            $observer_permission         =      monitorPermission::with('permission:id,name,e_name')->where('monitor_id' , $id )->get();
            
        }

        
        $chat_permissions = new StdClass();

        $observer_permission->map(function ($item ) use ($chat_permissions) {
            $k = $item->permission->e_name;
            $chat_permissions->$k = boolval($item->user_has_per);
        });

        return json_encode($chat_permissions );

    
    }

    public static function enableAllPermissions($id){

        $observer_permission         =      monitorPermission::with('permission:id,name,e_name')->where('monitor_id' , $id )->get();

        foreach($observer_permission as $key=>$value){

            $value->user_has_per = 1;
            $value->save();

        }

        return ('the permissions are enabled !');
    }

    public static function disableAllPermissions($id){

        $observer_permission         =      monitorPermission::with('permission:id,name,e_name')->where('monitor_id' , $id )->get();

        foreach($observer_permission as $key=>$value){

            $value->user_has_per = 0;
            $value->save();

        }

        return ('the permissions are disabled !');
        }
}
