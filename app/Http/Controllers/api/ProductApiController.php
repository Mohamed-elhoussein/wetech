<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Slider;
use App\Models\Product;
use App\Models\PayMethodes;
use App\Models\ProductBrand;
use App\Models\ProductTypes;
use App\Models\UserLikedProducts;
use App\Models\ProductCategories;
use App\Models\Notification;
use App\Helpers\FCM;
use App\Models\Fee;

class ProductApiController extends Controller
{
    public function products(Request  $request)
    {
        $fields = $request->all();

        $pay_methodes = PayMethodes::all();

        $product  =   Product::where('active', 1)->where('revision_status', 'accepted')
        ->with('user:id,username,number_phone,country_id', 'user.country', 'city:id,country_id,name,name_en', 'brand', 'type')
        ->when(isset($fields['user_id']), function ($query) use ($fields){
            $query->with(['favorite' => function ($hasMany)  use ($fields) {
                $hasMany->where('user_id', $fields['user_id']);
            }]);
        });

        if(isset($fields['catigory_id']) && $fields['catigory_id'] != 10)
            $product  = $product->where('product_category_id', $fields['catigory_id']);

        if(isset($fields['product_type_id']) && $fields['product_type_id'] != '_ALL')
            if ($fields['product_type_id'] == '_OFFERS')
                $product  = $product->where('is_offer', 1);
            else
                $product  = $product->where('product_type_id', $fields['product_type_id']);

        $product  = $product->orderBy('order_index', 'desc')->orderBy('created_at', 'desc')->get();

        $fees = Fee::query()->active()->get();

        $online_fees = $fees->filter(function ($item) {
            return $item->payment_method == 'online' || $item->payment_method == 'both';
        })->map(function ($item) {
            return ['text_ar' => $item->name, 'text_en' => $item->name, 'number' => $item->value];
        });

        $cash_fees = $fees->filter(function ($item) {
            return $item->payment_method == 'cash' || $item->payment_method == 'both';
        })->map(function ($item) {
            return ['text_ar' => $item->name, 'text_en' => $item->name, 'number' => $item->value];
        });

        $produc  =  collect($product)->map(function ($item) use ($fields, $pay_methodes, $request, $online_fees, $cash_fees) {

            if (!is_array($item->images))
                $item->images = json_decode($item->images);

            $item->json_last_error = json_last_error_msg();

            $price = $item->is_offer == 1? $item->offer_price : $item->price;

            return [
                'id'            => $item->id,
                'name'          => $item->name,
                'name_en'       => $item->name_en,
                'price'         => $item->price,
                'delivery_fee'  => $item->delivery_fee,
                'is_offer'      => $item->is_offer,
                'offer_price'   => $item->offer_price,
                'status'        => $item->status,
                'description'   => $item->description,
                'user_id'       => $item->user->id,
                'username'      => 'البائع: '.  $item->user->username,
                'phone'         => $item->user->country->country_code . $item->user->number_phone,
                'images'        => $item->images ?? ["/images/avatars/default.png"]	,
                'is_best_seller'=> $item->is_best_seller,
                'is_i_Liked'    => isset($fields['user_id']) && $item->favorite? true : false,
                "stars"         => $item->rating->avg('stars') ?: 5,

                'payment_method'=> collect($item->payment_method)->map(function ($item) use ($pay_methodes) {
                    return $pay_methodes->filter(function($pay) use ($item){
                        return $pay->method == $item;
                    })->values()[0];
                }),


                'info'          =>[
                    // ['icon' => 'md_color_palette_ion',  'text_ar' => $item->color,                 'text_en'   =>   $item->color],
                    // ['icon' => 'star_ant',              'text_ar' => optional($item->type)->name, 'text_en'   =>   optional($item->type)->name_en],
                    // ['icon' => 'location_on_mdi',       'text_ar' => optional($item->city)->name,  'text_en'   =>   optional($item->city)->name_en],
                    // ['icon' => 'user_faw',              'text_ar' => $item->user->username,        'text_en'   =>   $item->user->username],
                ],

                'details_info'  =>[
                    ['icon' => 'calendar_faw',          'text_ar' => $item->created_at,            'text_en'   => $item->created_at],
                    ['icon' => 'location_on_mdi',       'text_ar' => optional($item->city)->name,  'text_en'   => optional($item->city)->name_en],
                    ['icon' => 'user_faw',              'text_ar' => $item->user->username,        'text_en'   => $item->user->username],
                ],

                'product_specifications'  => [
                        ['icon' => 'md_color_palette_ion',  'text_ar' => $item->color,                 'text_en'   =>   $item->color],
$item->type           ? ['icon' => 'star_ant',              'text_ar' => optional($item->type)->name,  'text_en'   =>   optional($item->type)->name_en] : [],
                        ['icon' => 'box_ent',               'text_ar' => $item->status == 'USED' ? 'مستعمل' : 'جديد',  'text_en'   =>   $item->status],
$item->guarantee == 1 ? ['icon' => 'check_all_mco',         'text_ar' => 'عليه ضمان' ,                 'text_en'   =>   'guarantee'] : [],
$item->disk_info      ? ['icon' => 'chip_mco',              'text_ar' => $item->disk_info,              'text_en'   =>   $item->disk_info] : [],
                ],


                'online_invoice_info'  =>  [
                    ['text_ar' => string_value(453, $request),  'text_en'   =>   string_value(453, $request, true),     'number' => $price,],
                    // ['text_ar' => string_value(454, $request),  'text_en'   =>   string_value(454, $request, true),     'number' => $item->delivery_fee,],
                    ...$online_fees->toArray(),
                    // ['text_ar' => string_value(472, $request),  'text_en'   =>   string_value(472, $request, true),     'number' => ($price + $item->delivery_fee) * 0.035,],
                    ['text_ar' => string_value(455, $request),  'text_en'   =>   string_value(455, $request, true),     'number' => $price + $online_fees->sum('number') + $item->delivery_fee ,],
                ],

                'cash_invoice_info'  =>  [
                    ['text_ar' => string_value(453, $request),  'text_en'   =>   string_value(453, $request, true),     'number' => $price,],
                    // ['text_ar' => string_value(454, $request),  'text_en'   =>   string_value(454, $request, true),     'number' => $item->delivery_fee,],
                    ...$cash_fees->toArray(),
                    // ['text_ar' => string_value(472, $request),  'text_en'   =>   string_value(472, $request, true),     'number' => 12,],
                    ['text_ar' => string_value(455, $request),  'text_en'   =>   string_value(455, $request, true),     'number' => $price + $cash_fees->sum('number') + $item->delivery_fee], //  + 12,
                ],

            ];

        });

        return response()->data($produc);
    }
    public function details()
    {
        $product  =   Product::firstOrFail();
        return response()->data($product);
    }
    public function categories(Request  $request)
    {
        $sliders = Slider::where('target', 'STORE')->get();

        $categories  =   ProductCategories::with('ProductTypes')->orderBy('order_index')->get();

        $categories  =  $categories->map(function ($item) {

            $item->ProductType      =  $item->ProductTypes->map(function ($item) {

                $item->children =[];

                return $item;
            });

            return [
                'id'        =>  $item->id,
                'name'      =>  $item->name,
                'name_en'   =>  $item->name_en,
                'icon'      =>  $item->icon,
                'children'  =>  $item->ProductType,
            ];

        });

        // $provider_citis  =   ;

        $data = [
            'provider_citis'    => optional(optional(User::where('id', $request->provider_id)->with('country.cities')->first())->country)->cities,
            'sliders'           => $sliders,
            'catigories'        => $categories
        ];

        return response()->data($data);
    }
    public function create(Request  $request)
    {
        // $this->validate($request, rules('product.api.create'));
        $fields = $request->all();
          $images             =   collect($fields)->keys()
            ->map(function ($key) {
                return str_starts_with($key, 'image_') ? $key : Null;
            })
            ->whereNotNull()
            ->values()
            ->toArray();

        $gallery            =   [];
        foreach ($images as $image) {
            $gallery[]  =  upload_picture($fields[$image], '/images/product');
        };
        $gallery            =   '["' . implode('","', $gallery) . '"]';
        $product = product::create([
            'user_id'               =>$request->user_id,
            'city_id'               =>$request->city,
            'street_id'             =>isset($fields['street'])? $request->street_id : NULL,
            'product_category_id'   =>$request->catigory,
            'product_type_id'       =>$request->type,
            'product_brand_id'      =>isset($fields['brand'])? $request->brand : NULL,
            'name'                  =>$request->title,
            'name_en'               =>$request->title_en,
            'images'                =>$gallery,
            'color'                 =>$request->color,
            'disk_info'             =>isset($fields['disk_info'])? $request->disk_info : NULL,
            'duration_of_use'       =>isset($fields['duration_of_use'])? $request->duration_of_use : NULL,
            'guarantee'             =>isset($fields['guarantee'])? $request->guarantee : 0,
            'status'                =>isset($fields['status'])? $request->status : 'NEW',
            'price'                 =>$request->price,
            'is_offer'              =>isset($fields['isOffer'])? $request->isOffer : 0,
            'offer_price'           =>isset($fields['offer_price'])? $request->offer_price : NULL,
            'description'           =>$request->description,

        ]);

        $notification       =  Notification::create([
            'user_id'       => $request->user_id,
            'icon'          => 'bell_outline_mco',
            'title'         => 'منتجك بانتظار المراجعة حالياً',
            'message'       => 'سيصلك إشعار حين إتمام مراجعته من قبل فريقنا',
        ]);

        $device_token     =   $product->provider->makeVisible(['device_token'])->device_token;

        if ($device_token) {

            $fcm                =    new FCM();

            $title              =    $notification->title;

            $message            =    $notification->message;

            $fcm->to($device_token)->message($message, $title)->data('', 'info', $message, $title, 'Notifications')->send();
        }


        if($product->provider->role == 'provider'){
            $observers_token = User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();
            $observers_id    = User::where('role', 'chat_review')->pluck('id')->filter()->toArray();
            $fcm             = new FCM();

            $title = 'أرسل المزود ' . $product->provider->username . ' طلب إضافة منتج ' . $product->name. ' للمراجعة';

            foreach ($observers_id as $user_id)
            $notification         =  Notification::create([
                'user_id'              =>  $user_id,
                'icon'                 =>  'bell_outline_mco',
                'title'                =>  $title,
                'message'              =>  'يرجى معالجة الطلب بأقرب وقت',
            ]);

            foreach ($observers_token as $token) {
                $fcm->to($token)
                    ->message($notification->message, $title)
                    ->data(0, 'service_status', '', $notification->message,  $title,  '')
                    ->send();
            }
        }

        return response()->data($product, 'product was added successfly');
    }

    public function update(Request $request, $id)
    {
        $fields   = $request->all();
        $product  = Product::where('id', $id)->first();
        $user     = auth()->user();

        // $removed_images     =    json_decode($request->removed_images, true);

        // /*  get the exesting images and remove the removed images  */

        // $removed_images ?  $existingGallery            =      collect(explode('.', $product->images))
        //     ->map(function ($item) use ($removed_images) {
        //         return in_array(url($item), $removed_images) ?  Null : $item;
        //     })
        //     ->whereNotNull()
        //     ->values()
        //     ->toArray()

        //     :  $existingGallery            = explode('.', $product->images);

        /*  get the new images and upload it */

        $images             =      collect($fields)->keys()
            ->map(function ($key) {
                return str_starts_with($key, 'image_') ? $key : Null;
            })
            ->whereNotNull()
            ->values()
            ->toArray();


        $gallery            =       [];
        foreach ($images as $image) {
            $gallery[]  =  upload_picture($fields[$image], '/images/product');
        };
        // $gallery            =   array_merge($existingGallery, $gallery);


        isset($fields['user_id'])           ?   $product->user_id = $fields['user_id']                     :   false;
        isset($fields['city'])              ?   $product->city_id = $fields['city']                        :   false;
        isset($fields['street'])            ?   $product->street_id = $fields['street']                    :   false;
        isset($fields['catigory'])          ?   $product->product_category_id = $fields['catigory']        :   false;
        isset($fields['type'])              ?   $product->product_type_id = $fields['type']                :   false;
        isset($fields['brand'])             ?   $product->product_brand_id = $fields['brand']              :   false;
        isset($fields['title'])             ?   $product->name = $fields['title']                          :   false;
        isset($fields['title_en'])          ?   $product->name_en = $fields['title_en']                    :   false;
        isset($fields['color'])             ?   $product->color = $fields['color']                         :   false;
        isset($fields['disk_info'])         ?   $product->disk_info = $fields['disk_info']                 :   false;
        isset($fields['duration_of_use'])   ?   $product->duration_of_use = $fields['duration_of_use']     :   false;
        isset($fields['guarantee'])         ?   $product->guarantee = $fields['guarantee']                 :   false;
        isset($fields['status'])            ?   $product->status = $fields['status']                       :   false;
        isset($fields['price'])             ?   $product->price = $fields['price']                         :   false;
        isset($fields['isOffer'])           ?   $product->is_offer = $fields['isOffer']                    :   false;
        isset($fields['offer_price'])       ?   $product->offer_price = $fields['offer_price']             :   false;
        isset($fields['description'])       ?   $product->description = $fields['description']             :   false;
        isset($fields['active'])            ?   $product->active = $fields['active']                       :   false;
        isset($fields['payment_method'])    ?   $product->payment_method = json_decode($fields['payment_method']):   false;
        count($gallery) > 0                 ?   $product->images = json_encode($gallery)                   :   false;

        \Str::upper($product->revision_status) !== 'PENDING' ? $product->in_update = 1 : false;

        if($user->role == 'provider' && \Str::upper($product->revision_status) !== 'PENDING' ){
            $observers_token = User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();
            $observers_id    = User::where('role', 'chat_review')->pluck('id')->filter()->toArray();
            $fcm             = new FCM();

            $title = 'أرسل المزود ' . $user->username . ' طلب تعديل المنتج ' . $product->name. ' للمراجعة';

            foreach ($observers_id as $user_id)
            $notification         =  Notification::create([
                'user_id'              =>  $user_id,
                'icon'                 =>  'bell_outline_mco',
                'title'                =>  $title,
                'message'              =>  'يرجى معالجة الطلب بأقرب وقت',
            ]);

            foreach ($observers_token as $token) {
                $fcm->to($token)
                    ->message($notification->message, $title)
                    ->data(0, 'service_status', '', $notification->message,  $title,  '')
                    ->send();
            }
        }

        if($user->role != 'chat_review')
            $product->revision_status = 'pending';

        $product->save();


        return response()->data($product, 'product was updated successfully');
    }
    public function activeStatus($id)
    {
        $product  = Product::where('id', $id)->first();
        $product->active = ($product->active == 0 ? 1 : 0 );
        $product->save();
        return response()->data($product, 'Product was updated active successfully');
    }
    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        return response()->message( 'Product was deleted successfully');
    }
    public function productBrands($type_id)
    {
        $bran  =  ProductBrand::findOrFail($type_id)->all();
        return response()->json( $bran);
    }

    public function providerProducts(Request  $request)
    {

        $provider_id              =  $request->provider_id != Null ? $request->provider_id : auth()->id();

        $fields = $request->all();

        $product  =   Product::where('user_id', $provider_id)->with('user:id,username,number_phone,country_id', 'user.country', 'city:id,country_id,name,name_en', 'brand', 'type', 'category');

        if(isset($fields['product_type_id']) && $fields['product_type_id'] != '_ALL')
            if ($fields['product_type_id'] == '_OFFERS')
                $product  = $product->where('is_offer', 1);
            else
                $product  = $product->where('product_type_id', $fields['product_type_id']);

        $product  = $product->get();


        $produc  =  collect($product)->map(function ($item) {

            if (!is_array($item->images))
                $item->images = json_decode($item->images) ?? 	["/images/avatars/default.png"]	;

            $item->json_last_error = json_last_error_msg();

            $item->info =   [
                    ['icon' => 'md_color_palette_ion',  'text_ar' => $item->color,                 'text_en'   =>   $item->color],
                    ['icon' => 'star_ant',              'text_ar' => optional($item->type)->name, 'text_en'   =>   optional($item->type)->name_en],
                    ['icon' => 'location_on_mdi',       'text_ar' => optional($item->city)->name,  'text_en'   =>   optional($item->city)->name_en],
                    ['icon' => 'user_faw',              'text_ar' => $item->user->username,        'text_en'   =>   $item->user->username],
            ];

            $item->details_info =   [
                    ['icon' => 'calendar_faw',          'text_ar' => $item->created_at,            'text_en'   => $item->created_at],
                    ['icon' => 'location_on_mdi',       'text_ar' => optional($item->city)->name,  'text_en'   =>  optional($item->city)->name_en],
                    ['icon' => 'user_faw',              'text_ar' => $item->user->username,        'text_en'   =>   $item->user->username],
            ];

            $item->product_specifications   =   [
                    ['icon' => 'md_color_palette_ion',  'text_ar' => $item->color,                 'text_en'   =>   $item->color],
                    ['icon' => 'star_ant',              'text_ar' => optional($item->type)->name, 'text_en'   =>   optional($item->type)->name_en],
                    ['icon' => 'box_ent',               'text_ar' => $item->status == 'USED' ? 'مستعمل' : 'جديد',  'text_en'   =>   $item->status],
$item->guarantee == 1 ? ['icon' => 'check_all_mco',         'text_ar' => 'عليه ضمان' ,                 'text_en'   =>   'guarantee'] : [],
$item->disk_info?       ['icon' => 'chip_mco',              'text_ar' => $item->disk_info,              'text_en'   =>   $item->disk_info] : [],
            ];

            return $item;

        });

        return response()->data($produc);
    }

    public function myFavourite($user_id)
    {
        $likedProducts   = UserLikedProducts::where('user_id', $user_id)->with('Products')->has('Products')->orderBy('created_at', 'desc')->get();


        $likedProducts  =  collect($likedProducts)->map(function ($item) {

            if (!is_array($item->images))
                $item->images = json_decode($item->images);

            $item->json_last_error = json_last_error_msg();


            return [
                'id'            => $item->products->id,
                'name'          => $item->products->name,
                'name_en'       => $item->products->name_en,
                'price'         => $item->products->price,
                'delivery_fee'  => $item->products->delivery_fee,
                'is_offer'      => $item->products->is_offer,
                'offer_price'   => $item->products->offer_price,
                'status'        => $item->products->status,
                'description'   => $item->products->description,
                'user_id'       => $item->products->user->id,
                'username'      => $item->products->user->username,
                'phone'         => $item->products->user->country->country_code . $item->products->user->number_phone,
                'images'        => json_decode($item->products->images),
                'is_best_seller'=> $item->products->is_best_seller,
                "is_i_Liked"    => true, // isset($fields['user_id']) && $item->favorite? true : false
                "stars"         => optional($item->rating)->avg('stars') ?: 5,


                'info'          =>[
                    // ['icon' => 'md_color_palette_ion',  'text_ar' => $item->color,                 'text_en'   =>   $item->color],
                    // ['icon' => 'star_ant',              'text_ar' => optional($item->type)->name, 'text_en'   =>   optional($item->type)->name_en],
                    // ['icon' => 'location_on_mdi',       'text_ar' => optional($item->city)->name,  'text_en'   =>   optional($item->city)->name_en],
                    // ['icon' => 'user_faw',              'text_ar' => $item->user->username,        'text_en'   =>   $item->user->username],
                ],

                'details_info'  =>[
                    ['icon' => 'calendar_faw',          'text_ar' => $item->products->created_at,            'text_en'   => $item->products->created_at],
                    ['icon' => 'location_on_mdi',       'text_ar' => optional($item->products->city)->name,  'text_en'   =>  optional($item->products->city)->name_en],
                    ['icon' => 'user_faw',              'text_ar' => $item->products->user->username,        'text_en'   =>   $item->products->user->username],
                ],

                'product_specifications'  => [
                    ['icon' => 'md_color_palette_ion',  'text_ar' => $item->products->color,                 'text_en'   =>   $item->products->color],
       $item->products->type? ['icon' => 'star_ant',              'text_ar' => optional($item->products->type)->name, 'text_en'   =>   optional($item->products->type)->name_en] : [],
                    ['icon' => 'box_ent',               'text_ar' => $item->products->status == 'USED' ? 'مستعمل' : 'جديد',  'text_en'   =>   $item->products->status],
$item->products->guarantee == 1 ? ['icon' => 'check_all_mco',         'text_ar' => 'عليه ضمان' ,                 'text_en'   =>   'guarantee'] : [],
$item->products->disk_info?       ['icon' => 'chip_mco',              'text_ar' => $item->products->disk_info,              'text_en'   =>   $item->products->disk_info] : [],
                ],

            ];

        });

        return response()->data($likedProducts);
    }

    public function createFavourite(Request  $request){

        $data = UserLikedProducts::where('user_id', $request->user_id)->where('product_id', $request->product_id)->first();

        if($data)
            $message = 'it\'s already added';
        else
        {
            $message = 'product was added to favourite';

            $created    =   UserLikedProducts::create([
                'user_id'     => $request->user_id,
                'product_id'  => $request->product_id,
            ]);
        }

        $data = [
            'product_status' => 'added',
        ];

        return response()->data($data, $message);
    }
    public function deleteFavourite(Request  $request)
    {
        $isdeleted   = UserLikedProducts::where('user_id', $request->user_id)->where('product_id', $request->product_id)->delete();

        $message        =   'product was deleted from favourite';

        $data = [
            'product_status' => 'deleted',
        ];

        return response()->data($data, $message);
    }
}
