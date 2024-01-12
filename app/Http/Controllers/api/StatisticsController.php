<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Order;
use App\Models\MaintenanceRequestOrderCoupon;
use \stdClass;
use App\Models\Coupons;
use App\Models\MaintenanceRequestCoupon;



class StatisticsController extends Controller
{
    
public static function copun_statistics($code){
    
        $copun_id    = MaintenanceRequestCoupon::where('code' , $code)->first('id');
        
        if(isset($copun_id)){
    
        $copun_id = $copun_id->id;
        $id_with_cop = MaintenanceRequestOrderCoupon::where('maintenance_request_coupon_id' , $copun_id)->get('maintenance_request_order_id');
        
        
        $Completed_orders      =  Order::whereIn('maintenance_request_order_id' , $id_with_cop)
        ->where('status' , 'COMPLETED')
        ->with([
            'maintenance_request_order.maintenance_type.maintenance_request.issue:id,name',
            'maintenance_request_order.maintenance_type.maintenance_request.brand',
            'maintenance_request_order.maintenance_type.maintenance_request.model'
        ])
        ->get();


    
        $pended_orders = Order::whereIn('maintenance_request_order_id' , $id_with_cop)
        ->where('status' , 'PENDING')->get();
    
        $statistics = new stdClass;
        $br = new stdClass;
        // $brands = [ 1=>'iphone' , 2=>'samsung'  , 3=>"hwaui" , 4=>"xiomi" , 5=> "sony" , 6=> "others" ];
        $statistics->$code['name'] = $code;
        $statistics->$code['total_orders'] = count($id_with_cop);
        $statistics->$code['Completed_orders'] = count($Completed_orders);
        $statistics->$code['pended_orders'] = count($pended_orders) ;
        $statistics->$code['cancled_orders'] = count($id_with_cop) - count($Completed_orders) - count($pended_orders);
        $statistics->$code['amount'] = 0;
        $statistics->$code['barands_info'] = [];        
        if(count($Completed_orders) !=0){
        
        $Completed_orders->map(function ($order ) use ($statistics , $code  , $br) {
    
            $brand = $order->maintenance_request_order->maintenance_type->maintenance_request->brand->name;
            $model = $order->maintenance_request_order->maintenance_type->maintenance_request->model->name;
            $issue = $order->maintenance_request_order->maintenance_type->maintenance_request->issue->name;
            $price = $order->price;
    
    
            
            if(isset($br->b))
                $br->b[count($br->b)]=$brand;
            else
                $br->b=[$brand];
    
            if(isset($statistics->$code['amount'])){
                $statistics->$code['amount']+=$order->price;
            }
            else{
               $statistics->$code['amount']=$order->price;
            }
            if(isset($statistics->$code['barands_info'][$brand])){
                $statistics->$code['barands_info'][$brand]['name']  = $brand;
                $statistics->$code['barands_info'][$brand]['count'] += 1;
                $statistics->$code['barands_info'][$brand]['amount'] += $order->price;
    
                if(isset($statistics->$code['barands_info'][$brand]['issues'][$issue])){
                   $statistics->$code['barands_info'][$brand]['issues'][$issue]['count'] += 1;
                   $statistics->$code['barands_info'][$brand]['issues'][$issue]['amount']+= $price;
                }
                else{
                    $statistics->$code['barands_info'][$brand]['issues'][$issue]['name']= $issue;
                    $statistics->$code['barands_info'][$brand]['issues'][$issue]['count']= 1;
                    $statistics->$code['barands_info'][$brand]['issues'][$issue]['amount']= $price;              
                }
    
                if(isset($statistics->$code['barands_info'][$brand]['models'][$model]))
                    $statistics->$code['barands_info'][$brand]['models'][$model]['count'] += 1;
                else
                {
                    $statistics->$code['barands_info'][$brand]['models'][$model]['name'] = $model;
                    $statistics->$code['barands_info'][$brand]['models'][$model]['count'] = 1;
                }
            }
            else{
                $statistics->$code['barands_info'][$brand]['name'] = $brand;
                $statistics->$code['barands_info'][$brand]['amount'] = $order->price;
                $statistics->$code['barands_info'][$brand]['count'] = 1;
                $statistics->$code['barands_info'][$brand]['models'][$model]['name'] = $model;
                $statistics->$code['barands_info'][$brand]['models'][$model]['count'] = 1;
                $statistics->$code['barands_info'][$brand]['issues'][$issue]['name'] = $issue;
                $statistics->$code['barands_info'][$brand]['issues'][$issue]['count']= 1;
                $statistics->$code['barands_info'][$brand]['issues'][$issue]['amount']= $price; 
                  
            }
        });
        
        foreach($br->b as $brand){
            $statistics->$code['barands_info'][$brand]['issues'] = array_values($statistics->$code['barands_info'][$brand]['issues']);
            $statistics->$code['barands_info'][$brand]['models'] = array_values($statistics->$code['barands_info'][$brand]['models']);
        }
    
        
        $statistics->$code['barands_info'] = array_values($statistics->$code['barands_info']);
    
        }
        return $statistics->$code;

    }
    
        
    }
    
public static function orders_without_copun(){
   

    $req_with_copuns = MaintenanceRequestOrderCoupon::get('maintenance_request_order_id');
    $total_orders = order::whereNotIn('maintenance_request_order_id' , $req_with_copuns )
    ->whereNotNull('maintenance_request_order_id')
    ->get();

    $cancled_orders = order::whereNotIn('maintenance_request_order_id' , $req_with_copuns )
    ->whereNotNull('maintenance_request_order_id')
    ->where('status' , 'CANCELED')
    ->get();

    $completed_orders = order::whereNotIn('maintenance_request_order_id' , $req_with_copuns )
    ->whereNotNull('maintenance_request_order_id')
    ->where('status' , 'COMPLETED')
    ->with([
        'maintenance_request_order.maintenance_type.maintenance_request.issue:id,name',
        'maintenance_request_order.maintenance_type.maintenance_request.brand',
        'maintenance_request_order.maintenance_type.maintenance_request.model',
    ])
    ->get();

    $statistics = new stdClass;
    $br = new stdClass;
    $statistics->s['total_orders'] = count($total_orders);
    $statistics->s['completed_orders'] = count($completed_orders);
    $statistics->s['cancled_orders'] = count($cancled_orders);
    $statistics->s['pended_orders'] = count($total_orders) - count($completed_orders) - count($cancled_orders);
    $statistics->s['amount'] = 0;
    $statistics->s['brands'] = [];
    $statistics->s['issues'] = [];

    if(count($completed_orders) > 0){

        $completed_orders->map(function ($order ) use ($statistics  , $br) {

            if(isset($order->maintenance_request_order->maintenance_type)){
            $brand = $order->maintenance_request_order->maintenance_type->maintenance_request->brand->name;
            $model = $order->maintenance_request_order->maintenance_type->maintenance_request->model->name;
            $issue = $order->maintenance_request_order->maintenance_type->maintenance_request->issue->name;
            $price = $order->price;

            $statistics->s['amount']+=$price;

            if(isset($statistics->s['brands'][$brand])){
                $statistics->s['brands'][$brand]['count'] += 1;

                if(isset($statistics->s['brands'][$brand]['models'][$model])){
                    $statistics->s['brands'][$brand]['models'][$model]['count'] +=1; 
                }
                else{
                    $statistics->s['brands'][$brand]['models'][$model]['name'] = $model;
                    $statistics->s['brands'][$brand]['models'][$model]['count'] = 1;
                }
            }
            else{
                $statistics->s['brands'][$brand]['name']  = $brand;
                $statistics->s['brands'][$brand]['count'] = 1;
                $statistics->s['brands'][$brand]['models'][$model]['name'] = $model;
                $statistics->s['brands'][$brand]['models'][$model]['count'] = 1;
            }

            if(isset($statistics->s['issues'][$issue])){
                $statistics->s['issues'][$issue]['count'] += 1;
            }
            else{
                $statistics->s['issues'][$issue]['name'] = $issue;
                $statistics->s['issues'][$issue]['count'] = 1;
            }

            if(isset($br->b))
                $br->b[count($br->b)]=$brand;
            else
                $br->b=[$brand];
        }
        });
        foreach($br->b as $brand){

            $statistics->s['brands'][$brand]['models'] = array_values( $statistics->s['brands'][$brand]['models']);
        }
        $statistics->s['brands'] = array_values($statistics->s['brands']);
        $statistics->s['issues'] = array_values($statistics->s['issues']);         

        return $statistics->s;

    }


}

public function allcoupons(Request $request){
    
    if ($request->method() === 'POST')
            {
            
                $res = $this->copun_statistics($request->code);
                if($res == false)
                    return  response()->message("الكود المدخل غير صحيح");
                else
                    return response()->data($res);

            }
    else{

        $copuns = MaintenanceRequestCoupon::get('code');
        if(count($copuns) == 0){

            return response()->message('! لايوجد كوبونات متاحة  ');

        }
        else{
        $copuns_statistics_all = [];
        $copuns_statistics = ["total_orders"=>0 , "Completed_orders"=>0 , "pended_orders"=>0 , "cancled_orders"=>0 , "amount"=>0 ,'brands'=>[] , 'issues'=>[] ];

        foreach($copuns as $key=>$value){

           $TEMP =  $this->copun_statistics($value->code);
           

           if($TEMP && $value->code){

           $copuns_statistics_all[count($copuns_statistics_all)] = $TEMP ;
           $copuns_statistics['total_orders']      += $TEMP['total_orders'];
           $copuns_statistics['Completed_orders']  += $TEMP['Completed_orders'];
           $copuns_statistics['pended_orders']     += $TEMP['pended_orders'];
           $copuns_statistics['cancled_orders']    += $TEMP['cancled_orders'];
           $copuns_statistics['amount']            += $TEMP['amount'];

           if(isset($TEMP['barands_info'])){

           foreach($TEMP['barands_info'] as $brands){

            if(isset($copuns_statistics['brands'][$brands['name']])){
                 $copuns_statistics['brands'][$brands['name']]['count'] += $brands['count']; 
            }
            else{
                 $copuns_statistics['brands'][$brands['name']]['name'] = $brands['name'];
                 $copuns_statistics['brands'][$brands['name']]['count'] = $brands['count'];
                 $copuns_statistics['brands'][$brands['name']]['model']= [];
                
            }

            foreach($brands['issues'] as $issue){
                
            if(isset($copuns_statistics['issues'][$issue['name']]))
                $copuns_statistics['issues'][$issue['name']]['count'] += $issue['count']; 

             else{
                 $copuns_statistics['issues'][$issue['name']]['count']  = $issue['count'];
                 $copuns_statistics['issues'][$issue['name']]['name']  = $issue['name'];

            }}

            foreach($brands['models'] as $model){

                if(isset($copuns_statistics['brands'][$brands['name']]['model'][$model['name']]))
                    $copuns_statistics['brands'][$brands['name']]['model'][$model['name']]['count'] += $model['count'];
                else{

                  $copuns_statistics['brands'][$brands['name']]['model'][$model['name']]['name'] = $model['name'];
                  $copuns_statistics['brands'][$brands['name']]['model'][$model['name']]['count'] = $model['count'];
                  
                }
          
           }

           $copuns_statistics['brands'][$brands['name']]['model'] = array_values($copuns_statistics['brands'][$brands['name']]['model']);



           }           }
        }
                  
   
    }
    $copuns_statistics['cobons'] = $copuns_statistics_all;
    if(isset($copuns_statistics['brands'])){
        $copuns_statistics['brands'] = array_values($copuns_statistics['brands']);
         $copuns_statistics['issues'] = array_values($copuns_statistics['issues']);}

    return response()->data(['Orders_With_Copuns'=>$copuns_statistics , 'Orders_Without_Copuns' => $this->orders_without_copun()]);
}

    }
    
}

    public  function providers_statistics(){  


        $result = order::whereNotNull('provider_service_id')
        
        ->selectRaw('COUNT(*) AS total')
        ->selectRaw('COUNT(CASE WHEN status = "COMPLETED" THEN 1 END) AS COMPLETED')
        ->selectRaw('COUNT(CASE WHEN status = "CANCELED" THEN 1 END) AS CANCELED')
        ->selectRaw('SUM(CASE WHEN status = "COMPLETED" THEN price END) AS total_amount')
        ->first();
    
        $Provider_orders = order::where('status' , 'COMPLETED')
        ->whereNotNull('provider_service_id')
        
        ->with('provider_service.service_full:id,name')
        ->with('provider:id,username')
        ->get();
     
    
        $info = new stdClass();
        $info->s = [];
        
        
        if(count($Provider_orders) > 0){
    
    
        $Provider_orders->map(function($order , $key) use ($info){
    
            $service_name = $order->provider_service->service_full->name;;
            $service_title = $order->provider_service->title;
            $price = $order->price;
            $provider_name = $order->provider->username;
    
            if(isset($info->s[$provider_name])){
    
                
                $info->s[$provider_name]['orders_count'] += 1;
                $info->s[$provider_name]['amount'] += $price;
    
                if(isset($info->s[$provider_name]['services'][$service_name]['name'])){
    
                    $info->s[$provider_name]['services'][$service_name]['count'] += 1;
                    $info->s[$provider_name]['services'][$service_name]['amount'] += $price;
    
                    if(isset($info->s[$provider_name]['services'][$service_name]['titles'][$service_title])){
                        $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['count']+=1;
                        $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['amount'] += $price;
                    }
                    else{
                        
                        $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['name'] = $service_title;
                        $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['count'] = 1;
                        $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['amount'] = $price;
    
                    }
                    
                }
                else{   
                    $info->s[$provider_name]['services'][$service_name]['name'] = $service_name;
                    $info->s[$provider_name]['services'][$service_name]['count'] = 1;
                    $info->s[$provider_name]['services'][$service_name]['amount'] = $price;
                    $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['name'] = $service_title;
                    $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['count'] = 1;
                    $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['amount'] = $price;
                }
            }
            else{
                $info->s[$provider_name]['provider_name'] = $provider_name;
                $info->s[$provider_name]['orders_count'] = 1;
                $info->s[$provider_name]['amount'] = $price;
                $info->s[$provider_name]['services'][$service_name]['name'] = $service_name;
                $info->s[$provider_name]['services'][$service_name]['count'] = 1;
                $info->s[$provider_name]['services'][$service_name]['amount'] = $price;
                $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['name'] = $service_title;
                $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['count'] = 1;
                $info->s[$provider_name]['services'][$service_name]['titles'][$service_title]['amount'] = $price;
    
                }
        });
        
        $info->s  = array_values($info->s);
    
        foreach($info->s as $index=>$value){
    
            $info->s[$index]['services'] = array_values($info->s[$index]['services']);
    
            foreach($info->s[$index]['services'] as $i=>$value)
                  $info->s[$index]['services'][$i]['titles'] = array_values($info->s[$index]['services'][$i]['titles']);
        }
        $data = ['total_orders'=>$result->total , 
                'completed_orders'=>$result->COMPLETED ,
                'cancled_orders'=>$result->CANCELED ,
                'pended_orders'=> $result->total - $result->COMPLETED - $result->CANCELED,
                'total_amount'=>isset($result->total_amount) ? $result->total_amount  : 0,
                'providers_info' =>$info->s
        ];
         
        return response()->data($data);   }
    
    
        return response()->data(['total_orders'=>$result->total , 
        'completed_orders'=>$result->COMPLETED ,
        'cancled_orders'=>$result->CANCELED ,
        'pended_orders'=> $result->total - $result->COMPLETED - $result->CANCELED]);
    }
     
    public function products_statistics(){
    
    
            $result = order::whereNotNull('product_id')
            ->selectRaw('COUNT(*) AS total')
            ->selectRaw('COUNT(CASE WHEN status = "COMPLETED" THEN 1 END) AS COMPLETED')
            ->selectRaw('COUNT(CASE WHEN status = "CANCELED" THEN 1 END) AS CANCELED')
            ->selectRaw('SUM(CASE WHEN status = "COMPLETED" THEN price END) AS total_amount')
        ->first();
    
        $orders = order::whereNotNull('product_id')
        ->where('status' , 'COMPLETED' )
        ->with('product.category')->get();
    
        
    
        $obj    = new stdClass();
    
        $data = ['total_orders' =>$result->total ,
                'Completed_orders'=>$result->COMPLETED ,
                 'Canceled_orders'=>$result->CANCELED,
                 'pended_orders'=> $result->total - $result->COMPLETED - $result->CANCELED,
                 'total_amount'=>isset($result->total_amount) ? $result->total_amount : 0
                             ];
        
        
        if(count($orders) > 0){
    
        $orders->map(function($order) use($obj){
    
            $product_name = $order->product->name;
            $product_cat = $order->product->category->name; 
            $price        = $order->price;
    
    
            if(isset($obj->s['products'][$product_cat])){
                $obj->s['products'][$product_cat]['count'] += 1 ;
                $obj->s['products'][$product_cat]['amount'] += $price ;
    
                if(isset($obj->s['products'][$product_cat]['details'][$product_name])){
                    $obj->s['products'][$product_cat]['details'][$product_name]['count'] += 1 ;
                    $obj->s['products'][$product_cat]['details'][$product_name]['amount'] += $price ;
                }
                else{
                    $obj->s['products'][$product_cat]['details'][$product_name]['name']  = $product_name ;
                    $obj->s['products'][$product_cat]['details'][$product_name]['count'] = 1 ;
                    $obj->s['products'][$product_cat]['details'][$product_name]['amount'] = $price ;
                }
            }
            else{
    
               $obj->s['products'][$product_cat]['name'] = $product_cat;
               $obj->s['products'][$product_cat]['count'] = 1 ;
               $obj->s['products'][$product_cat]['amount'] = $price ;
               $obj->s['products'][$product_cat]['details'][$product_name]['name']  = $product_name ;
               $obj->s['products'][$product_cat]['details'][$product_name]['count'] = 1 ;
               $obj->s['products'][$product_cat]['details'][$product_name]['amount'] = $price ;
            }
    
           
    
    
        });
    
        foreach($obj->s['products'] as $index=>$value){
            $obj->s['products'][$index]['details'] = array_values($obj->s['products'][$index]['details']);
        }
    
        $data['products'] = array_values($obj->s['products']);
        
        return Response()->data($data);}
        
        return $data;
        
    
    }

    public function monthly_statistics(Request $request){

        isset($request->year) ? $year  = strval($request->year) : $year = date('Y');
    
        $year == date('Y') ? $last_month = date('m') : $last_month = 12 ;
        $data   = [];
        
        
        $req_with_coupons = MaintenanceRequestOrderCoupon::get('maintenance_request_order_id');
        $amount_in_year = ['products_orders_amount'=> 0 , 'provider_orders_amount'=>0 , 'coupons_orders_amount'=>0 ,'non_coupons_orders_amount'=>0  ];
    
        $years  = Order::distinct()
        ->selectRaw('year(created_at) as year')
        ->orderBy('created_at')
        ->pluck('year')
        ->toArray();
    
        foreach (range(1 ,(int)$last_month) as $key=>$m) {
    
            $month = strval($m) ;
    
    
            $data[$key]['year']      = $year;
            $data[$key]['month']     = $month;
            $data[$key]['total_amount_monthly']    = 0;
            
            
            
    
            $product_orders    = Order::ProductStatisticsMonthly($month , $year)->first()->total_amount;
            $provider_orders   = Order::ProviderStatisticsMonthly($month , $year)->first()->total_amount;
            $coupons_orders    = Order::CouponStatisticsMonthly($month , $year , $req_with_coupons)->first()->total_amount;
            $non_coupons_order = Order::NonCouponStatisticsMonthly($month , $year , $req_with_coupons)->first()->total_amount; 
    
            $data[$key]['total_amount_monthly'] = (isset($product_orders) ? $product_orders : 0) +
                                                  (isset($provider_orders) ? $provider_orders : 0) + 
                                                  (isset($coupons_orders) ? $coupons_orders : 0) +
                                                  (isset($non_coupons_order) ? $non_coupons_order : 0);
            
           
             $amount_in_year['products_orders_amount']+= (isset($product_orders) ? $product_orders : 0);
             $amount_in_year['provider_orders_amount']+= (isset($provider_orders) ? $provider_orders : 0);
             $amount_in_year['coupons_orders_amount']+= (isset($coupons_orders) ? $coupons_orders : 0);
             $amount_in_year['non_coupons_orders_amount']+= (isset($non_coupons_order) ? $non_coupons_order : 0);
    
    
            if($data[$key]['total_amount_monthly'] !=0 ){
    
                      $data[$key]['products_orders_amount_rate']    = round( ($product_orders / $data[$key]['total_amount_monthly'] )*100,0,PHP_ROUND_HALF_DOWN);
                      $data[$key]['providers_orders_amount_rate']   = round( ($provider_orders / $data[$key]['total_amount_monthly'])*100,0,PHP_ROUND_HALF_DOWN);
                      $data[$key]['coupons_orders_amount_rate']     = round( ($coupons_orders / $data[$key]['total_amount_monthly'])*100,0,PHP_ROUND_HALF_DOWN);
                      $data[$key]['Non_coupons_orders_amount_rate'] = round( ($non_coupons_order / $data[$key]['total_amount_monthly'])*100,0,PHP_ROUND_HALF_DOWN);
                    }
            else{
                      $data[$key]['products_orders_amount_rate']    = 0;
                      $data[$key]['providers_orders_amount_rate']   =0 ;
                      $data[$key]['coupons_orders_amount_rate']     = 0;
                      $data[$key]['Non_coupons_orders_amount_rate'] = 0;
    
            }
    
    
        }
    
        return response()->data(['statistics'=>$data ,'amount_in_year'=>$amount_in_year, 'previous_years'=>array_values(array_filter($years))]);
    
    }
    
    

}


