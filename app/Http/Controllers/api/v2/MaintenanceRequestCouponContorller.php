<?php

namespace App\Http\Controllers\api\V2;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequestCoupon;
use App\Models\MaintenanceRequestOrderCoupon;
use App\Models\Order;
use App\Models\Cart;
use \stdClass;
use App\Http\Resources\CouponResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceRequestCouponContorller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->data(MaintenanceRequestCoupon::query()->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string|unique:maintenance_request_coupons,name",
            "code" => "required|string|unique:maintenance_request_coupons,code",
            "value" => "required|numeric",
            "type" => "required|string|in:discount,percentage",
            "is_active" => "required|boolean",
            "expired_at" => "required|date",
            "belong_to" => "nullable|string",
        ], [
            'name.unique' => "تم إستخدام كود الكوبون بالفعل"
        ]);
        if(!$request->has('belong_to'))
            $data['belong_to'] = 'm';

        $coupon = MaintenanceRequestCoupon::create($data);

        return response()->data($coupon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaintenanceRequestCoupon  $coupn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaintenanceRequestCoupon $coupon)
    {
        $data = $request->validate([
            "name" => "required|string|unique:maintenance_request_coupons,name," . $coupon->id . ",id",
            "code" => "required|string|unique:maintenance_request_coupons,code," . $coupon->id . ",id",
            "value" => "required|numeric",
            "type" => "required|string|in:discount,percentage",
            "is_active" => "required|boolean",
            "expired_at" => "required|date",
            "belong_to" => "nullable|string",
        ], [
            'name.unique' => "تم إستخدام كود الكوبون بالفعل"
        ]);

        if(!$request->has('belong_to'))
            $data['belong_to'] = 'm';
        $coupon->update($data);

        return response()->data($coupon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaintenanceRequestCoupon  $coupn
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaintenanceRequestCoupon $coupon)
    {
        $coupon->delete();

        return response()->data([
            'success' => true
        ]);
    }

    public function check_coupon()
    {
        $code = request()->code;

        $belong_to = request()->has('belong_to')? request()->belong_to : 'm';
        $coupon = MaintenanceRequestCoupon::query()->where('code', $code)->whereIn('belong_to' , [$belong_to, 'm,p'])->first();

        if (!$coupon) {
            return response()->data([
                "name" => null,
                "code" => null,
                "value" => null,
                "type" => null,
                "is_active" => false,
                'is_coupon_exists' => false,
                'is_expired' => false
            ]);
        }

        return response()->data([
            "name" => $coupon->name,
            "code" => $coupon->code,
            "value" => $coupon->value,
            "type" => $coupon->type,
            "is_active" => $coupon->is_active,
            'is_coupon_exists' => true,
            'is_expired' => $coupon->expired()
        ]);
    }


    public function orders_without_copun(Request $request)
    {
    }

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
               $copuns_statistics_all[$key] = $TEMP ;
               $copuns_statistics['total_orders']      += $TEMP['total_orders'];
               $copuns_statistics['Completed_orders']  += $TEMP['Completed_orders'];
               $copuns_statistics['pended_orders']     += $TEMP['pended_orders'];
               $copuns_statistics['cancled_orders']    += $TEMP['cancled_orders'];
               $copuns_statistics['amount']            += $TEMP['amount'];

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
               }
            }

            $copuns_statistics['cobons'] = $copuns_statistics_all;
            $copuns_statistics['brands'] = array_values($copuns_statistics['brands']);
            $copuns_statistics['issues'] = array_values($copuns_statistics['issues']);

            return response()->data(($copuns_statistics));}}
        }
    }

    public function check_product_coupon(Request $request){

        $code = request()->code;
        $belong_to = request()->has('belong_to')? request()->belong_to : 'm';
        $coupon = MaintenanceRequestCoupon::query()->where('code', $code)->whereIn('belong_to' , [$belong_to, 'm,p'])->first();
        $user_id = $request->has('user_id')?  $request->get('user_id') : auth()->user()->id;
        $total_prices = Cart::where('user_id', $user_id)->whereNull('order_id')->where('is_exists', true)->sum(DB::raw('price * quantity'));

        if(!$coupon){
            $coupon = new MaintenanceRequestCoupon();
            $coupon->is_valid = false;
            $coupon->message = 'الكوبون غير موجود';
        }else if($coupon->is_active != 1){
            $coupon->is_valid = false;
            $coupon->message = 'الكوبون غير مفعل';
        }else if($coupon->expired()){
            $coupon->is_valid = false;
            $coupon->message = 'الكوبون منتهي الصلاحية';
        }else if($coupon){
            $coupon->is_valid = true;
            $coupon->message = 'الكوبون صالح للاستخدام';
        }
        $coupon->total_prices = $total_prices;

        return response()->data(new CouponResource($coupon));
    }
}
