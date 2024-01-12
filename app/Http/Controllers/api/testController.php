<?php
namespace App\Http\Controllers\api\V2;

use App\Http\Controllers\Controller;
use App\Models\Coupons;
use App\Models\MaintenanceRequestCoupon;
use App\Models\MaintenanceRequestOrderCoupon;
use App\Models\Order;
use \stdClass;
use Illuminate\Http\Request;


class testController extends Controller{

public function test(){

    $brands = [ 1=>'iphone' , 2=>'samsung'  , 3=>"hwaui" , 4=>"xiomi" , 5=> "sony" , 6=> "others" ];

    return response()->data([$brands]);

}

}


?>