<?php

namespace App\Http\Controllers;

use App\Http\BulkActions\SliderBulkAction;
use App\Http\Filters\SliderFilter;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\ProviderServices;
use App\Models\Service;
use App\Models\ServiceQuickOffer;
use App\Models\Slider;
use App\Models\User;
use App\Models\Target;
use App\Models\SlidersUrl;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule as ValidationRule;
use Carbon\Carbon;
use App\Helpers\FCM;

class SliderController extends Controller
{
    public function index(Request $request, SliderFilter $filter)
    {
        $sliders = Slider::filter($filter)->where(function ($query) use ($request) {
            if ($request->key_search) {
                $query->where('name', 'LIKE', $request->key_search . '%')->orWhere('phone', 'LIKE', '%' . $request->key_search . '%');
            }
        })->paginate($request->get('limit', 20));
        return view('slider.index', compact('sliders'));
    }
    public function create(Request  $request)
    {
        $this->validate($request, ['name' => 'required']);
        Slider::create([
            'name'   => $request->name,
            'image'  => upload_picture($request->file('image'), '/image/sliders'),
            'phone'  => $request->phone,
            'target' => $request->target,
            'url'    => $request->url,
            'visitableBtn' => $request->visitableBtn ? true : false,
            'active' => $request->active ? '1' : '0'
        ]);
        $this->sendNotifiUpdate();
        return redirect()->route('slider.index')->with('created', 'تم إنشاء شريط التمرير');
    }
    public function edit($id)
    {

        $slider = Slider::where('id', $id)->first();
        $this->sendNotifiUpdate();
        return view('slider.edit', compact('slider'));
    }
    public function update(Request $request, $id)
    {

        $slider  = Slider::findOrFail($id);
        $fields   = $request->all();

        isset($fields['name'])   ?  $slider->name     =   $request->name : false;
        $slider->url      =   $request->url;
        $slider->phone    =   $request->phone;
        $slider->target   =   $request->target;
        isset($fields['image'])  ?  $slider->image    =   upload_picture($request->file('image'), '/image/sliders')   : false;
        isset($fields['visitableBtn']) ?  $slider->visitableBtn   =   1 :  $slider->visitableBtn   =   0;
        isset($fields['active'])       ?  $slider->active   =   1 :  $slider->active   =   0;

        $slider->save();

        if (!$slider->visitableBtn) {
            SlidersUrl::where('slider_id', $slider->id)->update([
                'active' => 0
            ]);
        }
        $this->sendNotifiUpdate();
        return redirect()->route('slider.index')->with('updated', 'تم تحديث شريط التمرير');
    }
    public function delete($id)
    {
        Slider::where('id', $id)->delete();
        $this->sendNotifiUpdate();
        return redirect()->back()->with('deleted', 'تم حذف شريط التمرير');
    }
    public function block($id)
    {
        $slider  =   Slider::where('id', $id)->first();
        $slider->active   = $slider->active   ?   0   :   1;
        $slider->save();
        $this->sendNotifiUpdate();
        return   redirect()->back();
    }
    public function addButtons($slider_id)
    {
        // products
        $productCategories = ProductCategories::all();
        $products = Product::all();

        // services
        $providerServices = ProviderServices::all();
        $services = Service::all();

        // Offers
        $serverQuickOffers = ServiceQuickOffer::all();
        $offers = Offer::all();
        $sliderButtons =  SlidersUrl::where('slider_id', $slider_id)->get();

        $this->sendNotifiUpdate();

        return view('slider.slider_buttons', compact(
            'sliderButtons',
            'slider_id',
            'sliderButtons',
            'slider_id',
            'productCategories',
            'products',
            'providerServices',
            'offers',
            'services'
        ));
    }

    public function active(SlidersUrl $button)
    {
        $button->update([
            'active' => !$button->active
        ]);

        $this->sendNotifiUpdate();

        if ($button->active)
            return back()->with('created', 'تم تفعيل الزر');

        return back()->with('deleted', 'تم إلغاء تفعيل الزر');
    }

    public function editButton($slider_id, SlidersUrl $button)
    {
        // products
        $productCategories = ProductCategories::all();
        $products = Product::all();

        // services
        $providerServices = ProviderServices::all();
        $services = Service::all();

        // Offers
        $serverQuickOffers = ServiceQuickOffer::all();
        $offers = Offer::all();

        $url = json_decode($button->url, true);

        $this->sendNotifiUpdate();

        return view('slider.button.edit', compact(
            'button',
            'url',
            'slider_id',
            'productCategories',
            'products',
            'providerServices',
            'offers',
            'services'
        ));
    }

    public function updateButton($slider_id, SlidersUrl $button, Request $request)
    {
        $this->validate($request, ['text' => 'required', 'text_en' => 'required']);
        $url = $this->setUpURL($request);
        $button->update([
            'slider_id' => $slider_id,
            'text' => $request->text,
            'text_en' => $request->text_en,
            'icon_name_or_url' => $request->has('image') ? upload_picture($request->file('image'), '/image/button/sliders') : $request->icon,
            'url' => $url,

        ]);

        $this->sendNotifiUpdate();

        return redirect()->route('slider.newBtn', ['slider_id' => $slider_id]);
    }

    public function createNewBotton(Request $request, $slider_id)
    {
        $this->validate($request, ['text' => 'required', 'text_en' => 'required']);

        $url = $this->setUpURL($request);

        SlidersUrl::create([
            'slider_id' => $slider_id,
            'text' => $request->text,
            'text_en' => $request->text_en,
            'icon_name_or_url' => $request->has('image') ? upload_picture($request->file('image'), '/image/button/sliders') : $request->icon,
            'url' => $url,
        ]);
        $this->sendNotifiUpdate();
        return back();
    }

    private function setUpURL($request)
    {
        switch ($request->go_to) {
            case "url":
                $this->validate($request, [
                    'url' => [
                        'required',
                        'string',
                    ],
                ]);

                return json_encode([
                    'go_to' => 'url',
                    'url' => $request->url,
                ]);
            case "product":
                $this->validate($request, [
                    'product_categories' => [
                        'required',
                        'numeric',
                    ],
                    'product_id' => [
                        'required',
                        'numeric',
                    ]
                ]);

                return json_encode([
                    'go_to' => 'product',
                    'product_categories' => $request->product_categories,
                    'product_id' => $request->product_id,
                ]);
            case "provider_service":
                $this->validate($request, [
                    'service_id' => [
                        'required',
                        'numeric',
                    ],
                    'provider_service_id' => [
                        'required',
                        'numeric',
                    ]
                ]);

                return json_encode([
                    'go_to' => 'provider_service',
                    'service_id' => $request->service_id,
                    'provider_service_id' => $request->provider_service_id,
                ]);
            case "provider_offers":
                $this->validate($request, [
                    'service_id' => [
                        'required',
                        'numeric',
                    ],
                    'offer_id' => [
                        'required',
                        'numeric',
                    ]
                ]);

                return json_encode([
                    'go_to' => 'provider_offers',
                    'service_id' => $request->service_id,
                    'offer_id' => $request->offer_id,
                ]);
        }
    }

    public function deleteBtn($id)
    {
        SlidersUrl::where('id', $id)->delete();
        $this->sendNotifiUpdate();
        return back()->with('deleted', 'تم حذف زر شريط التمرير');
    }

    public function bulkAction(SliderBulkAction $sliderBulkAction)
    {
        Slider::bulkAction($sliderBulkAction);
        Session::flash('update', 'The selected sliders has been updated');
    }

    public function silderWithBtn(Request $request,$target){
        $validator = Validator::make($request->all(), [
        'text_en' => 'required',
        'text' => 'required',
        'icon' => 'required',
        'btn_color' => 'required',
        'visitableBtn' => 'required',
        'icon_color'=>'required',
        'text_color'=>'required',
        'image' => 'image',
        'urls' => 'string',
        'urls..active' => 'sometimes|boolean',
        'urls..icon_name_or_url' => 'sometimes|string',
        'urls..text' => 'sometimes|string',
        'urls..text_en' => 'sometimes|string',
        'urls.*.url' => 'sometimes|string'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->error(200,'بعض الحقول ناقصة او غير صحيحة');
        }
        
        $targetId = Target::where('target',$target)->first();

        $newSlider = new Slider ;
        $newSlider->text_en = $request->text_en; 
        $newSlider->text = $request->text;
        $newSlider->icon = $request->icon;
        $newSlider->btn_color = $request->btn_color;
        $newSlider->visitableBtn = $request->visitableBtn;
        $newSlider->text_color = $request->text_color;
        $newSlider->icon_color = $request->icon_color;
        $newSlider->target_id=$targetId->id;
        $newSlider->target = $target;
    
        $path = upload_picture($request->file('image'), '/image/sliders');
        $newSlider->image = $path;
        $newSlider->save();
            
        $urlsString = $request->input('urls');
        if (!empty($urlsString)) {
            $urlsString = preg_replace('/(\w+)\s*:\s*([^,}]+)/', '"$1":"$2"', $urlsString);
            $urlsString = preg_replace("/('url'\s*:\s*)'(tel:[^']+)'/", '$1"$2"', $urlsString);
            $urlsString = preg_replace("/(\w+)\s*:\s*('[^']+')/", '"$1":$2', $urlsString);
            $urls = json_decode($urlsString);
            foreach ($urls as $url) {
                $btn = new SlidersUrl;
                $btn->active = $url->active;
                $btn->icon_name_or_url = $url->icon_name_or_url;
                $btn->text = $url->text;
                $btn->text_en = $url->text_en;
                $btn->url = $url->url;
                $btn->slider_id = $newSlider->id;
                $btn->save();
            }
        }
        $this->sendNotifiUpdate();
        return response()->data([
            'message' => 'تم إنشاء السلايدر بنحاح.',
            'slider' => $newSlider
        ]);
    }

    
    public function addBtnSlider(Request $request,$sliderId)
    {
        $newSlider = Slider::find($sliderId);
        $newBtn = new SlidersUrl; 
            $newBtn->active = $request->active;
            $newBtn->icon_name_or_url = $request->icon_name_or_url;
            $newBtn->text = $request->text;
            $newBtn->text_en = $request->text_en;
            $newBtn->url = $request->url;

            $newBtn->slider_id = $newSlider->sliderId;

            $newSlider->urls()->save($newBtn);
        
        $this->sendNotifiUpdate();

        return response()->data($newBtn);
    }

    public function editBtnSlider(Request $request,$sliderId,$btnId)
    {
        $newBtn = SlidersUrl::find($btnId);
        $data = $request->only([
            'active',
            'icon_name_or_url',
            'text',
            'url',
            'text_en',
            'active'
        ]);

        $newBtn->fill($data);
        $newBtn->save();
        $this->sendNotifiUpdate();
        return response()->data([
        'message' => 'تم تعديل الزر بنجاح.',
        'btn' => $newBtn]);
        
    }

    public function getSlider(Request $request,$sliderId){
        $slider = Slider::with('urls')->find($sliderId);
        // $btn = SlidersUrl::where('slider_id',$slider->id)->get();
        if(!$slider){
            return response()->error(200,'لا يوجد سلايدر لعرضه');
        }
        return response()->data($slider);
    } 

    public function editSlider(Request $request, $sliderId){
        $slider = Slider::find($sliderId);
        if (!$slider) {
            return response()->error(200,'السلايدر المحدد غير موجود');
        }
    
        $data = $request->only([
            'text_en',
            'text',
            'icon',
            'btn_color',
            'visitableBtn',
            'icon_color',
            'text_color'
        ]);
    
        $validator = Validator::make($data, [
            'text_en' => 'sometimes|required',
            'text' => 'sometimes|required',
            'icon' => 'sometimes|required',
            'btn_color' => 'sometimes|required',
            'visitableBtn' => 'sometimes|required',
            'icon_color'=>'sometimes|required',
            'text_color'=>'sometimes|required'
        ]);
    
        if ($validator->fails()) {
            return response()->error(200,'بعض الحقول فارغة أو غير صحيحة, لم تتم عملية التعديل');
        }
    
        if($request->hasFile('image')){
            $path = upload_picture($request->file('image'), '/image/sliders');
            $slider->image = $path;
        }
    
        $slider->fill($data);
        $slider->save();
    
        $this->sendNotifiUpdate();
        return response()->data([
            'message' => 'تم تعديل السلايدر بنجاح.',
            'slider' => $slider
        ]);
    }

    public function getTargets(Request $request){
        $targets = Target::all();
        return response()->data($targets);
    }

    public function deleteButton($sliderId,$btnId){
        SlidersUrl::where('id', $btnId)->delete();
        $this->sendNotifiUpdate();
        return response()->success('تم حذف زر من السلايدر');
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
