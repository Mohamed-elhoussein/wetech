<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Slider;
use App\Models\SlidersUrl;
use App\Models\Service;
use App\Models\Target;
use App\Models\ProductCategories;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Helpers\FCM;

class SliderApiController {



    public static function vald($arr , $model_name){

        if($model_name == 'slider'){

            $validator = Validator::make($arr ,  [
                
                'text' => 'required|string',
                'text_en' => 'required|string',
                'icon'=>'required|string',
                'btn_color'=>'required|string',
                'visitableBtn'=>'required'
    
            ]);

            return $validator->fails();
        }

        else {

            $create_url = true;
            for($i = 0 ; $i < count($arr) ; $i++){

            
                $validator = Validator::make($arr[$i] ,  [
    
    
                    'slider_id' => 'required',
                    'text' => 'required',
                    'text_en' => 'required',
                    'icon_color' => 'required',
                    'icon_name_or_url' => 'required',
                    'url' => 'required',
                    'active' => 'required'
        
                ]);
    
                $arr[$i]['slider_id'] = (int) $arr[$i]['slider_id']; 
    
                if ($validator->fails()) {
                    $create_url = false;
                }
    
            }
            return $create_url;
    

        }
    }

    public function firstTime() {   
       $Slider = Slider::create($request);
       return "message from laravel first time ";
    }

    public static function CheckFgk($model_name , $id , $value){
        $ckh = $model_name::where($id , $value)->get();
        if ($ckh->isempty())
            return false;
       else 
        return true;
    }
    public function AddSilder(Request $request)
    {
        $arr = $request->all();
        $arr['target_id'] = 1;
        $urls = $arr['urls'];

        if ($this->vald($arr , 'slider')) 
        {
            return Response::json(array('data'=>$arr , 'status' => false, 'message' => 'بعض الحقول فارغة أو غير صحيحة, لم تتم عملية الإنشاء '));
        }
        else
        {
            $create_slider = true;

            if(count($urls) != 0)
                $create_url = $this->vald($urls , 'slider_urls');
            else{
                Slider::create($arr);
                $this->sendNotifiUpdate();
                return Response::json(array('status' => true, 'message' => ' تم إنشاء السلايدر بنحاح'));
            }
                
            if($create_url && $create_slider){
                Slider::create($arr);
                foreach ($arr['urls'] as $url){
                    SlidersUrl::create($url);
                    }
                    $this->sendNotifiUpdate();
                    return Response::json(array('status' => true, 'message' => ' تم إنشاء السلايدر بنحاح'));
            }
            else 
            {
                return Response::json(array('status' => false, 'message' => 'بعض الحقول فارغة أو غير صحيحة, لم تتم عملية الإنشاء '));
            }
            
        }

    }

    public function addButton(Request $request)
    {
        $buttons= [
            0 =>['id'=> 4 , 'slider_id'=>13 , 'text'=>'الزر السادس' , 'text_en' =>'btn1' , 'icon_name_or_url' =>'url1' , 'icon_color'=>'#fff' , 'url'=>'u1' , 'active'=>0 ], 
            1 =>['id'=> 2 ,'slider_id'=>18  , 'text'=>'الزر السابع  ' , 'text_en' =>'btn2' , 'icon_name_or_url' =>'url1' , 'icon_color'=>'#fff' , 'url'=>'u1' ,'active'=>1 ]  
            
         ];
         if ($this->vald($buttons , 'slider_url')) 
         {
            $count = 0;
            foreach ($buttons as $btn){
                $chk = $this->CheckFgk(Slider::class , 'id' , $btn['slider_id']);
                if($chk){
                    $count ++ ; 
                   SlidersUrl::create($btn);
                }                
             }
             if ($count > 0 ) 
            {
                $this->sendNotifiUpdate();
                return Response::json(array('status' => true, 'message' => 'تم إنشاء '. $count.' من الأزرار' ));
            }
             else
                return Response::json(array('status' => false, 'message' => 'بعض الحقول فارغة أو تحوي قيما غير صحيحة, لم تتم عملية الإنشاء' ));
        }
        else
        {
            return Response::json(array('status' => false, 'message' => 'بعض الحقول فارغة أو تحوي قيما غير صحيحة, لم تتم عملية الإنشاء' ));
        }
    }

    public function store($request)
    {
        $Slider = Slider::create($request->validated());
        $this->sendNotifiUpdate();
        return response()->data($Slider);
    }

    public function update(Request $request, Slider $Slider)
    {
        
        $arr = ['id'=>13, 'order_index' => 2 ,'name'=> "slider8" ,'text'=> "....... هيا بنا" , 'text_en'=>"let's go" , 'text_color'=> "#ffffff" ,'image' =>'s1_img', 'url' =>"s2_url" , 'phone' =>'000' ,
        'target' =>'home' , 'visitableBtn'=> 1 ,'btn_color'=>'#ff0000' ,
        'icon'=> 'last_page_mdi' , 'icon_color'=>'#ffffff','active'=>1, 'target_id'];

        $target_id = target::where('name' , $arr['target'])->get('id');
        $arr['target_id'] = ($target_id[0]->id);
        $arr['target_id'] = (int) +$arr['target_id'];

        $arr['urls'] = [
            0 =>['id' => 95 ,  'slider_id'=>13  , 'text'=>'الزر الثالث' , 'text_en' =>'btn0' , 'icon_name_or_url' =>'url1' , 'icon_color'=>'#fff' , 'url'=>'u1' , 'active'=>0 ], 
            1 =>['id' => 96 , 'slider_id'=>13  , 'text'=>'الزر الرابع' , 'text_en' =>'btn1' , 'icon_name_or_url' =>'url1' , 'icon_color'=>'#fff' , 'url'=>'u1' ,'active'=>1 ]  
            
         ];

        if ($this->vald($arr , 'slider')) 
        {            
            return Response::json(array('status' => false, 'message' => 'لم تتم عملية التعديل '));
        }
        else{

            $create_slider = true;
            $create_url = true;
            $create_url = $this->vald($arr['urls'] , 'slider_urls');

            if($create_slider && $create_url){

                $slider = Slider::find($arr['id'] , 'id');
                $slider ->update($arr);

                foreach ($arr['urls'] as $url){                    
                    $slider_url = SlidersUrl::find($url['id'] , 'id');
                    $slider_url ->update($url);        
                 }
                $this->sendNotifiUpdate();
                return Response::json(array('status' => true, 'message' => 'تمت عملية التعديل بنجاح '));                
            }
        }
    }

    public function destroy(Slider $Slider)
    {
        $Slider->delete();
        $this->sendNotifiUpdate();
        return response()->message('تم حذف الشريحة بنجاح');
    }

    public function getAll()
    {
        $Sliders = Slider::all();
        return response()->data($Sliders);
    }
    
    public function getByTarget($target)
    {

        $Sliders = Slider::where('target', $target)->with('urls')->get();


        $service = Service::select('id','order_index','name','name_en')
                            ->where('active', 1)->where('join_option', 1)
                            ->with('provider_services_accepted:id,service_id,title,thumbnail,provider_id')
                            ->orderBy('order_index')->get();
                            

        $service = $service->map(function ($item) {
            $item->provider_services_accepted->map(function ($item) {
                $item->title = $item->provider->username;
                $item->image = $item->thumbnail;
                unset($item->provider);
                unset($item->thumbnail);
                return $item;
            });
            return $item;
        });


        $service_quick_offers = Service::select('id','order_index','name','name_en')
                            ->where('active', 1)->where('join_option', 1)
                            ->whereHas('has_service_quick_offers')
                            ->with('has_service_quick_offers')
                            ->orderBy('order_index')->get();


        $service_quick_offers = $service_quick_offers->map(function ($item) {
            $item->quick_offers = $item->has_service_quick_offers->map(function ($service) {
                return $service->service_quick_offers->map(function ($item) use ($service) {
                    $item->quick_offer['provider_id'] = $service->provider_id;
                    $item->quick_offer['provider_service_id'] = $item->service_id;
                    return $item->quick_offer;
                });
            })->collapse();
            unset($item->has_service_quick_offers);
            return $item;
        });


        $products = ProductCategories::with('products_accepted_and_active')->get();

        $products = $products->map(function ($item) {
            $item->product = $item->products_accepted_and_active->map(function ($item) {
                if (!is_array($item->images))
                    $item->images = json_decode($item->images);
                $image = $item->images[0] ?? "/images/avatars/default.png";
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'name_en' => $item->name_en,
                    'image' => $image
                ];
            });
            unset($item->products_accepted_and_active);
            return $item;
        });

        $data = [
            'Sliders'               => $Sliders,
            'config'                => [
                'service_quick_offers'  => $service_quick_offers,
                'provider_services'     => $service,
                'products'              => $products,
            ]
        ];

        return response()->data($data);
    }
    

    public function activeStatus($id)
    {
        $slider  = Slider::where('id', $id)->first();
        $slider->active = ($slider->active == 0 ? 1 : 0 );
        $slider->save();
        $this->sendNotifiUpdate();
        return response()->data($slider, 'slider was updated active successfully');
    }

    public function filterTarget()
    {
        $target = Slider::select('target')->orderBy('order_index')->groupBy('target')->get();
        return response()->data($target);
    }

    public function delete($id)
    {
        Slider::where('id', $id)->delete();
        $this->sendNotifiUpdate();
        return response()->message('slider was deleted successfully');
    }

    public function updateButton($button_id, Request $request)
    {
        $url = $this->setUpURL($request);
        SlidersUrl::where('id', $button_id)->update([
            'text' => $request->text,
            'text_en' => $request->text_en,
            'icon_name_or_url' => $request->has('image') ? upload_picture($request->file('image'), '/image/button/sliders') : $request->icon,
            'url' => $url,
        ]);
        $this->sendNotifiUpdate();
        return response()->message('slider was update button successfully');
    }

    private function setUpURL($request)
    {
        switch ($request->go_to) {
            case "url":
                return json_encode([
                    'go_to' => 'url',
                    'url' => $request->url,
                ]);
            case "product":
                return json_encode([
                    'go_to' => 'product',
                    'product_categories' => $request->product_categories,
                    'product_id' => $request->product_id,
                ]);
            case "provider_service":
                return json_encode([
                    'go_to' => 'provider_service',
                    'service_id' => $request->service_id,
                    'provider_service_id' => $request->provider_service_id,
                ]);
            case "provider_offers":
                return json_encode([
                    'go_to' => 'provider_offers',
                    'service_id' => $request->service_id,
                    'offer_id' => $request->offer_id,
                ]);
        }
    }

    private function sendNotifiUpdate()
    {
        $sliders = Slider::where('active', 1)
            ->whereIn('target', ['HOME', 'OfferOrProvider'])
            ->with('slider_urls')->get()->map(function ($slider) {
                return
                    [
                    'id'                        => $slider->id,
                    'image'                     => url('') . $slider->image,
                    "text"                      => $slider->text ?? "اطلب الآن",
                    "text_en"                   => $slider->text_en ?? "Call advertiser",
                    'url'                       => $slider->phone ? 'tel:' . $slider->phone  : $slider->url ?? "",
                    'urls'                      => $slider->slider_urls,
                    "icon"                      => $slider->icon ?? "last_page_mdi",
                    'btn_color'                 => $slider->btn_color ?? '#ff0000',//344f64
                    'text_color'                => $slider->text_color ?? '#ffffff',
                    'icon_color'                => $slider->icon_color ?? '#ffffff',
                    'btn_shadow_white_or_black' => 1,
                    'visitableBtn'              => $slider->visitableBtn ? true : false,
                    'target'                    => $slider->target,
                    'created_at'                => $slider->created_at
                ];
            });
    
        $observers_token = User::whereIn('role', ['user', 'USER', 'User'])
                            ->where('updated_at', '>', Carbon::now()->subHours(1))
                            ->pluck('device_token')->filter()->toArray();
        $sliders->tokens_count = count($observers_token);
        $fcm             = new FCM();
        foreach ($observers_token as $token) {
            $fcm->to($token)
            ->message_payload(["type" => "seen"])
            ->data(0, "slider", json_encode($sliders))
            ->send();
        }
    }

}
