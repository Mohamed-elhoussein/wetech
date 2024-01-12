<?php

namespace App\Http\Controllers\api;


use App\Events\ProviderServicesCreate;
use App\Helpers\HttpCodes;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Transaction;
use App\Models\ProviderSkill;
use App\Models\Notification;
use App\Models\Offer;
use App\Helpers\FCM;
use App\Models\Order;
use App\Models\ProviderCommission;
use App\Models\UserLikedServices;
use App\Models\ProviderServices;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Mail\NewUser;
use App\Models\Rating;
use Illuminate\Support\Facades\Mail;

class ProviderApiController extends Controller
{
    public function create(Request  $request)
    {

        $this->validate($request, rules('provider.create'));
        $fields         =  $request->all();
        $images         =  collect($fields)->keys()
            ->map(function ($key) {
                return str_starts_with($key, 'image_') ? $key : Null;
            })
            ->whereNotNull()
            ->values();

        $provider_id    =  auth()->user()->id;


        $identity       =   [];
        foreach ($images as $image) {
            $identity[]     =  upload_picture($fields[$image], '/images/identity');
        };

        $identity       =   implode('||', $identity);

        $user           =   User::where('id', $provider_id)->firstOrFail();

        $user->first_name     =       $fields['first_name'];
        $user->second_name    =       $fields['second_name'];
        $user->last_name      =       $fields['last_name'];
        $user->username       =       $fields['first_name'] . ' ' . $fields['last_name'];
        if(isset($fields['friend_number']))
        $user->friend_number  =       $fields['friend_number'];
        $user->identity       =       $identity;
        $user->role           =       'provider';
        if(isset($fields['email']))
        $user->email          =       $fields['email'];


        $user->save();
        if (!$user) {
            $code       =   HttpCodes::ERROR_WITH_REASON;
            $message    =   'somthing werong';

            return response()->error($code, $message);
        }

        $user->identity = collect(explode('||', $user->identity))->map(function ($item) {
            return url('') . $item;
        })->toArray();

        $user->avatar = url('') . $user->avatar;

        $ms = 'مرحبا بك في دكتور تك لقد تم الإنضمام لمنصتنا بنجاح  ';

        Mail::to($user->email)->send(new NewUser($ms));

        $message        = 'your order to be proovider has been registered successfully';
        $data           =  $user;
        return response()->success($message, $data);
    }
    public function services($provider_id)
    {

        $provider    =  User::select('id')->where('id', $provider_id)->with('services')->firstOrFail();

        $services     =  collect($provider->services)->map(function ($item) {
            return  [
                'id'          => $item->id,
                'title'       => ($item->title === Null ? get_title(6, $item)->name : $item->title),
                'thumbnail'   => $item->thumbnail ? url('') . $item->thumbnail : default_image(),
                'stars'       => $item->rating->avg('stars') ?: 5,
                'status'      => $item->status,

            ];
        });


        $data        =  $services;

        return response()->data($data);
    }
    public function services_list_offer($provider_id, Request $request)
    {
        /* "x-build-number": "0.0.10",
        "x-os": "ios"
        "x-app-version": "0.0.13",
        "x-os": "Android" */

        $is_new_version = false;

        $provider    =  User::select('id')->where('id', $provider_id)->with('services')->firstOrFail();

        if(
            (($request->header("x-os") == "ios" &&  ((int) str_replace('.', '', $request->header("x-build-number"))  > (int) str_replace('.', '', "0.0.10")) )
            ||
            ($request->header("x-os") == "Android" &&  ((int) str_replace('.', '', $request->header("x-app-version"))  > (int) str_replace('.', '', "0.0.13")) ))
            &&
            $request->header("x-app-type") == 'PROVIDER_APP'
        )
        $is_new_version = true;

        $services     =  collect($is_new_version? $provider->services_accepted_online : $provider->services_accepted)->map(function ($item) {
            return  [
                'id'                => $item->id,
                'title'             => ($item->title === Null ? get_title(6, $item)->name : $item->title),
                'icon'              => $item->thumbnail ? url('') . $item->thumbnail : default_image(),
                'stars'             => $item->rating->avg('stars') ?: 5,
                'status'            => $item->status,
                'service_target'    => $item->service_full->target,

            ];
        });

        $data        =  $services;

        return response()->data($data);
    }
    public function services_(Request $request, $service_id)
    {

        $fields = $request->all();
        $providerServices    =  ProviderServices::where('service_id', $service_id)
            ->where('status', 'ACCEPTED')
            ->orderBy('created_at', 'desc')
            ->with('provider:id,number_phone,username,active,verified,country_id', 'rating', 'offers', 'serviceType');

        if (isset($fields['country_id'])) {
            $providerServices = $providerServices->where('country_id', $fields['country_id']);
        } else if (isset($fields['country_id_with_null']) && !isset($fields['target']) )
            $providerServices =  $providerServices->where(function ($query) use ($fields) {
                return $query->where('country_id', $fields['country_id_with_null'])->orWhereNull('country_id');
            });

        if (isset($fields['city_id']))
            $providerServices = $providerServices->where('city_id', $fields['city_id']);

        if (isset($fields['street_id']))
            $providerServices = $providerServices->where('street_id', $fields['street_id']);

        if (isset($fields['service_categories_id']))
            $providerServices = $providerServices->where('service_categories_id', $fields['service_categories_id']);

        if (isset($fields['service_subcategories_id']))
            $providerServices = $providerServices->where('service_subcategories_id', $fields['service_subcategories_id']);

        if (isset($fields['sub2_id']))
            $providerServices = $providerServices->where('sub2_id', $fields['sub2_id']);

        if (isset($fields['sub3_id']))
            $providerServices = $providerServices->where('sub3_id', $fields['sub3_id']);

        if (isset($fields['sub4_id']))
            $providerServices = $providerServices->where('sub4_id', $fields['sub4_id']);

        if (isset($fields['word_search']))
            $providerServices = $providerServices->where(function ($query) use ($fields) {
                return $query->where('specializ', 'like', '%' . $fields['word_search'] . '%')
                    ->orwhere('brand', 'like', '%' . $fields['word_search'] . '%')
                    ->orwhere('description', 'like', '%' . $fields['word_search'] . '%')
                    ->orwhere('title', 'like', '%' . $fields['word_search'] . '%')
                    ->orWhereHas('provider', function ($query) use ($fields) {
                        $query->where('username', 'like', '%' . $fields['word_search'] . '%');
                    });
            });

            $ratings[] = 0;

        if (isset($fields['ratings']))
        {
            $providerServices = $providerServices->where(function ($query) use ($fields, $ratings) {
                return $query->doesntHave('rating')->orWhereHas('rating', function ($query) use ($fields, $ratings) {
                        $query->havingRaw('AVG(stars) > '.$fields['ratings']);
                    });
            });
        }


        $providerServices = $providerServices->get();
        $services     =  collect($providerServices)->map(function ($providerService)  use ($fields, $request){

            $specializ_from = Service::where('id', $providerService->service_id)->firstOrFail()->specializ_from;

            return  [

                'id'                                => $providerService->id,
                'provider_id'                       => $providerService->provider->id,
                'provider_just_name'                => $providerService->provider->username,
                'provider_country'                  => $providerService->provider->country->name,
                'provider_country_en'               => $providerService->provider->country->name_en,
                'provider_skills'                   => $providerService->provider->provider_skills()->with('skill')->get()->pluck('skill')->map(function ($skill) {
                    return [
                        'name'      => $skill->name,
                        'name_en'   => $skill->name_en,
                ];})->toArray(),
                'thumbnail'                         => $providerService->thumbnail ? url('') . $providerService->thumbnail : default_image(),
                'phone'                             => $providerService->provider->country->country_code . $providerService->provider->number_phone,
                'provider_services_title'           => $providerService->title === Null ? get_title(6, $providerService)->name : $providerService->title,
                'provider_services_title_en'        => $providerService->title === Null ? get_title(6, $providerService)->name_en: $providerService->title,
                'specializ'                         => $providerService->specializ === NULL? optional(get_title($specializ_from, $providerService))->name : $providerService->specializ,
                'brand'                             => $providerService->brand,
                'stars'                             => $providerService->rating->avg('stars') ?: 5,
                "country_name"                      => (optional($providerService->country)->name)    ?? "",
                "country_name_en"                   => (optional($providerService->country)->name_en) ?? "",
                "city_name"                         => (optional($providerService->city)->name)       ?? "",
                "city_name_en"                      => (optional($providerService->city)->name_en)    ?? "",
                "street_name"                       => (optional($providerService->street)->name)     ?? "",
                "street_name_en"                    => (optional($providerService->street)->name_en)  ?? "",
                "active"                            => $providerService->provider->active     ?   true    :   false,
                "profile_verified"                  => $providerService->provider->verified   ?   true    :   false,
                "cat_subcat_sub1_sub2_sub3_sub4"    => $providerService->cat_subcat_sub1_sub2_sub3_sub4,
                "country_city_street"               => $providerService->country_city_street,
                "title_from"                        => Service::where('id', $providerService->service_id)->firstOrFail()->title_from,
                "specializ_from"                    => $specializ_from,
                "brand_from"                        => Service::where('id', $providerService->service_id)->firstOrFail()->brand_from,
                "offers"                            => sizeof($providerService->offers) ? true : false,
                "pin_top"                           => $providerService->pin_top,
                "is_i_Liked"                        => (isset($fields['user_id']) && UserLikedServices::where('user_id', $fields['user_id'])->where('provider_service_id', $providerService->id)->first()) ? true : false,
                'favourites'                        => $providerService->favourites->count('id') ?: 0,
                'type'                              => optional($providerService->serviceType)->name,
                'type_en'                           => optional($providerService->serviceType)->name_en,
            ];
        });

        $cities =  $providerServices->map(function ($item) use ($request){
            if($item->city != null)
            return  [
                "id"               => $item->city->id,
                "name"             => $item->city->name,
                "name_en"          => $item->city->name_en,
            ];
        })->unique();

        $cities =  $cities->filter()->values()->toArray();

        array_unshift($cities, [
            "id"        => "0",
            "name"      => "كل المدن",
            "name_en"   => "All Cities",
        ]);


        $services = $services->sortBy([
            ['pin_top', 'desc'],
            ['quick_offer', 'desc'],
            ['offers'     , 'desc'],
            ['active'     , 'desc'],
        ]);

        $data        =  $services;

        if(
            (($request->header("x-os") == "ios" &&  ((int) str_replace('.', '', $request->header("x-build-number"))  > (int) str_replace('.', '', "5.0.33")) )
            ||
            ($request->header("x-os") == "Android" &&  ((int) str_replace('.', '', $request->header("x-app-version"))  > (int) str_replace('.', '', "5.0.29")) ))
            &&
            $request->header("x-app-type") == 'CLIENT_APP'
        )
            $data   =   [
                            'cities'      =>  $cities,

                            'services'    =>  $services,
                        ];

        return response()->data($data);
    }
    public function serviceCreate(Request $request)
    {
        $fields             =   $request->all();

        $request_offers     =   json_decode($request->offers, true);
        $this->validate($request, rules('providerservices.create'));

        $fields             =   $request->all();
        $provider_id        =   auth()->user()->id;
        $user               =   auth()->user();

        $images             =   collect($fields)->keys()
            ->map(function ($key) {
                return str_starts_with($key, 'image_') ? $key : Null;
            })
            ->whereNotNull()
            ->values()
            ->toArray();
        $gallery            =   [];
        foreach ($images as $image) {
            $gallery[]  =  upload_picture($fields[$image], '/images/service/gallery');
        };


        $gallery            =   implode('||', $gallery);


        $service = Service::where('id', $fields['service_id'])->firstOrFail();

        $title = get_con($service->title_from, $fields)->name ?? Null;
        $title === 'أخرى' ? ($title = get_title(2, $fields)->name ?? Null) : false;

        $providerService   =   ProviderServices::create([
            'provider_id'                   =>      $provider_id,
            'service_id'                    =>      $fields['service_id'],
            'service_categories_id'         =>      $fields['service_categories_id'] ?? Null,
            'service_subcategories_id'      =>      $fields['service_subcategories_id'] ?? Null,

            'sub2_id'                       =>      $fields['sub2_id'] ?? Null,
            'sub3_id'                       =>      $fields['sub3_id'] ?? Null,
            'sub4_id'                       =>      $fields['sub4_id'] ?? Null,

            'title'                         =>      $title,
            'brand'                         =>      get_con($service->brand_from, $fields)->name ?? Null,
            'specializ'                     =>      get_con($service->specializ_from, $fields)->name ?? Null,
            'type'                          =>      $fields['type'] ?? Null,

            'country_id'                     =>      $fields['country_id'] ?? Null,
            'city_id'                        =>      $fields['city_id'] ?? Null,
            'street_id'                      =>      $fields['street_id'] ?? Null,

            'thumbnail'                      =>      $images  ?  explode('||', $gallery)[0]  : Null,
            'gallery'                        =>      $images  ?  $gallery    :   NuLL,
            'description'                    =>      $fields['description'],
            'cat_subcat_sub1_sub2_sub3_sub4' =>      $fields['cat_subcat_sub1_sub2_sub3_sub4'] ?? '0-0-0-0-0-0',
            'country_city_street'            =>      $fields['country_city_street'] ?? '0-0-0'
        ]);


        $offers             =      [];

        /* if request has offer to current provider service service */

        if ($request->has('offers') && $providerService) {

            foreach ($request_offers as $offer) {

                $offers[]           =   Offer::create([
                    'provider_id'           =>  $provider_id,
                    'provider_service_id'   =>  $providerService->id,
                    'description'           =>  $offer['details'],
                    'price'                 =>  convertArabicNumber($offer['price']),
                    'target'                 =>  'ALL'
                ]);
            };
        }
        /* Offer has been added to service Offers  */

        /* notify that service was added */


        $notification       =  Notification::create([
            'user_id'       => $provider_id,
            'icon'          => 'bell_outline_mco',
            'title'         => 'خدمتك بانتظار المراجعة حالياً',
            'message'       => 'سيصلك إشعار حين إتمام مراجعتها من قبل فريقنا',
        ]);

        $device_token     =   $providerService->provider->makeVisible(['device_token'])->device_token;

        if ($device_token) {

            $fcm                =    new FCM();

            $title              =    $notification->title;

            $message            =    $notification->message;

            $fcm->to($device_token)->message($message, $title)->data('', 'info', $message, $title, 'Notifications')->send();
        }


        if($user->role == 'provider'){
            $observers_token = User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();
            $observers_id    = User::where('role', 'chat_review')->pluck('id')->filter()->toArray();
            $fcm             = new FCM();

            $title = 'أرسل المزود ' . $user->username . ' طلب إضافة خدمة ' . $providerService->title. ' للمراجعة';

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

        optional($providerService)->gallery       ?       $providerService->gallery  =  collect(explode('||', $providerService->gallery))->map(function ($item) {
            return [
                'id'   => rand(2, 50),
                'name' => url('') . $item,
            ];
        })
            :     $providerService->gallery  =  "";


        $providerService->thumbnail     =     $images   ? url('') . $providerService->thumbnail   :  "";

        $data                   =   [
            'providerServices'           =>   $providerService,
            'offers'                     =>   $offers,
        ];
        $message                 =  'the service was added successfully';


        return response()->data($data, $message);
    }
    public function serviceDelete($id)
    {
       // Offer::where('provider_service_id', $id)->delete();
        ProviderServices::where('id', $id)->delete();
        return response()->data('the service was deleteded successfully');
    }
    public function serviceUpdate(Request $request, $provider_service_id)
    {

        $providerService    =    ProviderServices::where('id', $provider_service_id)->withTrashed()->first();
        
        if($providerService->deleted_at)
            return response()->error('','عذراً, هذه الخدمة محذوفة...');


        $in_update          =

        $request_offers     =    json_decode($request->offers, true);
        $removed_images     =    json_decode($request->removed_images, true);


        $this->validate($request, rules('providerservices.update'));

        $fields             =      $request->all();
        $provider_id        =      auth()->user()->id;
        $user               =      auth()->user();

        /*  get the exesting images and remove the removed images  */

        $removed_images ?  $existingGallery            =      collect(explode('||', $providerService->gallery))
            ->map(function ($item) use ($removed_images) {
                return in_array(url('') . $item, $removed_images) ?  Null : $item;
            })
            ->whereNotNull()
            ->values()
            ->toArray()


            :  $existingGallery            = explode('||', $providerService->gallery);

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
            $gallery[]  =  upload_picture($fields[$image], '/images/service/gallery');
        };

        $gallery            =       array_merge($existingGallery, $gallery);
        $gallery            =   implode('||', $gallery);

        $service = Service::where('id', $fields['service_id'])->firstOrFail();

        $title = get_con($service->title_from, $fields)->name ?? Null;
        $title === 'أخرى' ? ($title = get_title(2, $fields)->name ?? Null) : false;

        $providerService->title                          =   $title ;
        $providerService->brand                          =   get_con($service->brand_from,     $fields)->name ?? Null;
        $providerService->specializ                      =   get_con($service->specializ_from, $fields)->name ?? Null;
        isset($fields['service_id'])                     ?   $providerService->service_id                   =      $fields['service_id']                     : false;
        isset($fields['service_categories_id'])          ?   $providerService->service_categories_id        =      \Str::upper($fields['service_categories_id']) === 'NULL'     ? Null :  $fields['service_categories_id']          : false;
        isset($fields['service_subcategories_id'])       ?   $providerService->service_subcategories_id     =      \Str::upper($fields['service_subcategories_id']) === 'NULL'  ? Null :  $fields['service_subcategories_id']       : false;
        isset($fields['sub2_id'])                        ?   $providerService->sub2_id                      =      \Str::upper($fields['sub2_id']) === 'NULL' ? Null    :  $fields['sub2_id']                        : false;
        isset($fields['sub3_id'])                        ?   $providerService->sub3_id                      =      \Str::upper($fields['sub3_id']) === 'NULL' ? Null    :  $fields['sub3_id']                        : false;
        isset($fields['sub4_id'])                        ?   $providerService->sub4_id                      =      \Str::upper($fields['sub4_id']) === 'NULL' ? Null    :  $fields['sub4_id']                        : false;
        isset($fields['country_id'])                     ?   $providerService->country_id                   =      \Str::upper($fields['country_id']) === 'NULL' ? Null :  $fields['country_id']                     : false;
        isset($fields['city_id'])                        ?   $providerService->city_id                      =      \Str::upper($fields['city_id']) === 'NULL' ? Null    :  $fields['city_id']                        : false;
        isset($fields['street_id'])                      ?   $providerService->street_id                    =      \Str::upper($fields['street_id']) === 'NULL' ? Null  :  $fields['street_id']                      : false;
        isset($fields['cat_subcat_sub1_sub2_sub3_sub4']) ? $providerService->cat_subcat_sub1_sub2_sub3_sub4 =      $fields['cat_subcat_sub1_sub2_sub3_sub4'] : false;
        isset($fields['country_city_street'])            ?   $providerService->country_city_street          =      $fields['country_city_street']            : false;
        true                                             ?   $providerService->thumbnail                    =      explode('||', $gallery)[0]                : false;
        true                                             ?   $providerService->gallery                      =      $gallery                                  : false;
        isset($fields['description'])                    ?   $providerService->description                  =      $fields['description']                    : false;
        isset($fields['type'])                           ?   $providerService->type                         =      $fields['type']                           : false;

        \Str::upper($providerService->status) !== 'PENDING' ? $providerService->in_update = 1 : false;

        if($user->role == 'provider' && \Str::upper($providerService->status) !== 'PENDING' ){
            $observers_token = User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();
            $observers_id    = User::where('role', 'chat_review')->pluck('id')->filter()->toArray();
            $fcm             = new FCM();

            $title = 'أرسل المزود ' . $user->username . ' طلب تعديل الخدمة ' . $providerService->title. ' للمراجعة';

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
            $providerService->status = 'PENDING';

        $providerService->save();

        $providerService->gallery       ?       $providerService->gallery  =  collect(explode('||', $providerService->gallery))->map(function ($item) {
            return [
                'id'   => rand(2, 50),
                'name' => url('') . $item,
            ];
        })
            :     $providerService->gallery  =  "";


        $providerService->thumbnail     =     $providerService->thumbnail   ? url('') . $providerService->thumbnail : default_image();

        $data                   =   [
            'providerServices'           =>   $providerService,
        ];
        $message                 =  'the service was updated successfully';


        return response()->data($data, $message);
    }
    public function serviceDetails($provider_service_id)
    {
        $providerService                  =   ProviderServices::where('id', $provider_service_id)->with('ratings', 'offers', 'provider', 'serviceType')->withTrashed()->first();


        optional($providerService)->gallery         ??  $providerService->gallery  = default_image();


        $rate         =      $providerService->ratings->avg('stars');

        $ratings      =      Order::select('id','provider_service_id','user_id')
                                    ->where('provider_service_id', $provider_service_id)
                                    ->has('rating_user')
                                    ->with('user:id,username,avatar','rating_user')
                                    ->get()->map(function ($item) {

            return collect([
                "name"              => $item->user->username,
                "name_user_rated"   => $item->rating_user->user->username,
                "image"             => url('') . $item->user->avatar,
                "created_at"        => Change_Format($item->rating_user->created_at),
                "comment"           => $item->rating_user->comment ?? '',
                "stars"             => $item->rating_user->stars,
                "order_id"          => $item->rating_user->order_id,
                ]);
        });

        $ratings = $ratings->sortBy([
            ['created_at', 'desc'],
        ])->take(2);


        $offers     =   collect($providerService->offers)->map(function ($offer) {
            return [
                "id"                         => $offer->id,
                "description"                => $offer->description,
                "price"                      => $offer->price,
                "status"                     => $offer->status,
            ];
        });


        $service                          =   [

            "id"                => $providerService->id,
            "user_id"           => $providerService->provider_id,
            "provider_name"     => $providerService->provider->username,
            'phone'             => $providerService->provider->country->country_code . $providerService->provider->number_phone,
            "status"            => $providerService->status,
            "about"             => $providerService->description,
            "active"            => $providerService->provider->active     ?   true    :   false,
            "images"            => $providerService->gallery ? $providerService->gallery : default_image(),
            "name"              => $providerService->title === Null ? get_title(6, $providerService)->name : $providerService->title,
            "about_provider"    => $providerService->provider->about ?? '. . . .',
            "rate"              => $rate ?: 5,

            "service_id"                      => $providerService->service_id,
            "service_categories_id"           => $providerService->service_categories_id,
            "service_subcategories_id"        => $providerService->service_subcategories_id,
            "sub2_id"                         => $providerService->sub2_id,
            "sub3_id"                         => $providerService->sub3_id,
            "sub4_id"                         => $providerService->sub4_id,
            "country_id"                      => $providerService->country_id,
            "city_id"                         => $providerService->city_id,
            "street_id"                       => $providerService->street_id,
            "in_update"                       => $providerService->in_update,
            "cat_subcat_sub1_sub2_sub3_sub4"  => $providerService->cat_subcat_sub1_sub2_sub3_sub4,
            "country_city_street"             => $providerService->country_city_street,
            "is_country_city_street"          => Service::where('id', $providerService->service_id)->firstOrFail()->is_country_city_street,
            "service_target"                  => $providerService->service_full->target,
            "type"                            => $providerService->serviceType,

            'offers'                          => $offers,

            'ratings'                         => $ratings
        ];


        $data                      =   $service;

        return response()->data($data);
    }
    public function providerServiceRatings($provider_service_id)
    {
        $ratings        =    Order::select('id','provider_service_id','user_id')
                                  ->where('provider_service_id', $provider_service_id)
                                  ->has('rating_user')
                                  ->with('user:id,username,avatar','rating_user')
                                  ->get();

        $ratings        =    $ratings->map(function ($item) {
            return [
                "name"              => $item->user->username,
                "name_user_rated"   => $item->rating_user->user->username,
                "image"             => url('') . $item->user->avatar,
                "created_at"        => Change_Format($item->rating_user->created_at),
                "comment"           => $item->rating_user->comment ?? '',
                "stars"             => $item->rating_user->stars,
                "order_id"          => $item->rating_user->order_id,
            ];
        });

        $ratings = $ratings->sortBy([
            ['created_at', 'desc'],
        ]);

        $data         =    $ratings;

        return response()->data($data);
    }
    public function providers()
    {
        $provider = User::where('role', 'provider')->get();

        $data     = $provider;

        return response()->data($data);
    }

    public function rate(Request $request)
    {
        $provider_id =  $request->provider_id;
        $provider    =  User::where('id', $provider_id)->with('rates')->firstOrFail();

        $rates       =  $provider->rates->map(function ($item) {
            return $item->rating;
        })->collapse();

        $data        =  $rates;

        return response()->data($data);
    }
    public  function orders(Request $request, $provider_id)
    {
        $provider    =  User::where('id', $provider_id)->with('orders')->first();

        // $orders      =  $provider->orders;

        // $data        =  $orders;

        $orders = collect(
            [
                [
                    "id" => "6",
                    "user_id" => "25",
                    "offer_id" => "32",
                    "service_id" => "1",
                    "promo_id" => "0",
                    "status" => "CANCELED",
                    "created_at" => "2021-09-30 12:51:08",
                    "service_icon" => "https://server.drtechapp.com/storage/images/laptop.svg",
                    "service_name" => "خدمة تصميم ويب",
                    "unit" => "ر.س",
                    "price" => 500,
                    "name" => "مزود الخدمة",
                    "description" => "عرض جديد"
                ],
                [
                    "id" => "7",
                    "user_id" => "25",
                    "offer_id" => "33",
                    "service_id" => "1",
                    "promo_id" => "0",
                    "status" => "CANCELED",
                    "created_at" => "2021-09-30 12:59:21",
                    "service_icon" => "https://server.drtechapp.com/storage/images/laptop.svg",
                    "service_name" => "خدمة تصميم ويب",
                    "unit" => "ر.س",
                    "price" => 500,
                    "name" => "مزود الخدمة",
                    "description" => "هتااا"
                ],
                [
                    "id" => "8",
                    "user_id" => "1",
                    "offer_id" => "33",
                    "service_id" => "1",
                    "promo_id" => "0",
                    "status" => "PENDING",
                    "created_at" => "2021-09-30 13:01:35",
                    "service_icon" => "https://server.drtechapp.com/storage/images/laptop.svg",
                    "service_name" => "خدمة تصميم ويب",
                    "unit" => "ر.س",
                    "price" => 500,
                    "name" => "مزود الخدمة",
                    "description" => "هتااا"
                ],
                [
                    "id" => "9",
                    "user_id" => "1",
                    "offer_id" => "34",
                    "service_id" => "1",
                    "promo_id" => "0",
                    "status" => "PENDING",
                    "created_at" => "2021-09-30 13:02:16",
                    "service_icon" => "https://server.drtechapp.com/storage/images/laptop.svg",
                    "service_name" => "خدمة تصميم ويب",
                    "unit" => "ر.س",
                    "price" => 500,
                    "name" => "مزود الخدمة",
                    "description" => "وونم"
                ],
                [
                    "id" => "10",
                    "user_id" => "1",
                    "offer_id" => "35",
                    "service_id" => "1",
                    "promo_id" => "0",
                    "status" => "PENDING",
                    "created_at" => "2021-09-30 13:17:46",
                    "service_icon" => "https://server.drtechapp.com/storage/images/laptop.svg",
                    "service_name" => "خدمة تصميم ويب",
                    "unit" => "ر.س",
                    "price" => 500,
                    "name" => "مزود الخدمة",
                    "description" => "ز+تت"
                ],
                [
                    "id" => "11",
                    "user_id" => "1",
                    "offer_id" => "36",
                    "service_id" => "1",
                    "promo_id" => "0",
                    "status" => "PENDING",
                    "created_at" => "2021-09-30 13:18:15",
                    "service_icon" => "https://server.drtechapp.com/storage/images/laptop.svg",
                    "service_name" => "خدمة تصميم ويب",
                    "unit" => "ر.س",
                    "price" => 500,
                    "name" => "مزود الخدمة",
                    "description" => "ةتمم"
                ]
            ]
        );
        $status = $request->status;
        $status  ? $orders =  $orders->map(function ($item) use ($status) {
            $item['status'] = $status;

            return $item;
        }) : false;
        $data   =  [$orders];

        return response()->data($data);
    }
    public function convertations(Request $request)
    {
        $provider_id     = $request->id != Null ? $request->id : auth()->user()->id;
        $chatList        = Chat::where('provider_id', $provider_id)
            ->providerChat()
            ->orderBy('created_at', 'desc')
            ->get();

        Chat::mapChatMessage($chatList);
        $chatList = $chatList->map(function ($item) use ($request){
            $image        =  $item->user->role === 'chat_review'
                                ? get_logo($request)
                                : ($item->user->avatar ? url('') . $item->user->avatar : NULL);

            return  [
                "user_id"      => $item->user_id,
                "engineer_id"  => $item->provider_id,
                'user'         => [
                    "id"            => $item->user->id,
                    "email"         => $item->user->email,
                    "name"          => $item->user->role === 'chat_review' ? string_value(0, $request) : ($item->user->username . ($item->user->deleted_at? ' (حساب محذوف)' : '')),
                    "image"         => $image,
                    "type"          => $item->user->role,
                    "created_at"    => $item->user->created_at,
                ],
                'count_not_seen' => Chat::where('provider_id', $item->provider_id)
                    ->where('send_by', $item->user->id)
                    ->where('seen', 0)->count('id'),
                //   'message' =>$item->message,
                'last_chat_date' => $item->created_at,
            ];
        });

        $data      = $chatList;
        return response()->data($data);
    }
    public function profile($provider_id)
    {
        $provider = User::provider()->select(['id', 'username', 'number_phone', 'avatar', 'about', 'active', 'social_media_links', 'verified','created_at', 'email_verified', 'identity_verified', 'verified as phone_verified', 'country_id'])
            ->with('country:id,country_code,name')
            ->withCount('orders as clients_count')
            ->withCount('services_accepted as services_count')
            ->withCount('orders_completed as orders_completed')
            ->withCount('orders_pending as orders_pending')
            ->findOrFail($provider_id)
        ;
        $provider = tap($provider, function ($item) {
            $item->number_phone = $item->country->country_code . $item->number_phone;
            $item->country_name = $item->country->name;
            return $item;
        });
        unset($provider->country);

        $providerRatings = Rating::avgForProvider($provider_id)->first();

        $skills = $provider->provider_skills()->with('skill')->get()->pluck('skill')->map(function ($skill) {
            return [
                'name'      => $skill->name,
                'name_en'   => $skill->name_en,
        ];})->toArray();
        $data = [
            'user' => $provider,
            'rate' => [
                'stars' => doubleval($providerRatings->stars??5),
                'total' => '('.$providerRatings->total.')',
            ],
            'skills' => $skills
        ];

        return response()->data($data);
    }
    public function offers($provider_id)
    {
        $offers    =   Offer::where('provider_id', $provider_id)->where('target', 'ALL')->get();

        $data      =   $offers;
        return response()->data($data);
    }
    public function status(Request $request)
    {
        $this->validate($request, ['active' => "required|boolean"]);

        $provider_id              =   auth()->user()->id;
        $provider                 =   user::where('id', $provider_id)->update([
            'active'  => $request->active
        ]);

        $message                  =   'provider status was updated';
        return response()->data($message);
    }
    public function payments()
    {
        return [
            [
                'id'    => 1,
                'title' => '',
                'date'  => '2021-09-31 14:22:15',
                'unit'  => 'ر.س',
                'type'  => "+",
                'price' => 1030

            ],
            [
                'id'    => 2,
                'title' => '',
                'date'  => '2021-08-01 4:42:15',
                'unit'  => 'ر.س',
                'type'  => "-",
                'price' => 1300

            ],
            [
                'id'    => 3,
                'title' => '',
                'date'  => '2021-06-12 3:27:12',
                'unit'  => 'ر.س',
                'type'  => "-",
                'price' => 1990

            ],
            [
                'id'    => 4,
                'title' => '',
                'date'  => '2021-05-30 12:02:16',
                'unit'  => 'ر.س',
                'type'  => "+",
                'price' => 1500

            ]

        ];
    }
    public function ratings()
    {
        return response()->data(
            [
                "user" => [
                    "ratings" => [
                        [
                            "name" => "هاني القحطاني",
                            "image" => "https://server.drtechapp.com/storage/images/default.jpg",
                            "created_at" => "2021-07-27 09:56:28",
                            "comment" => "dvsdvsdv",
                            "stars" => "5"
                        ],
                        [
                            "name" => "hani",
                            "image" => "https://server.drtechapp.com/storage/images/612929053654d.jpg",
                            "created_at" => "2021-08-27 22:26:30",
                            "comment" => "ممتاز جدا",
                            "stars" => "5"
                        ]
                    ]
                ]
            ]
        );
    }
    public function orderStatistics(Request $request)
    {

        $provider_id              =  $request->id != Null ? $request->id : auth()->id();

        $role = 'provider';
        if ($request->id != Null){
            $provider_info        =  User::where('id', $provider_id)->select('id', 'country_id', 'role')->first();
            $role = strtolower($provider_info->role);
        }

        $ordersCount              =  Order::where($role == 'user'? 'user_id': 'provider_id', $provider_id)->with('product')->statistics()->first();

        $ordersCount->revenue     =  Order::where('status', 'COMPLETED')->where('provider_id', $provider_id)->doesntHave('provider_service_online')->sum('price');

        $ordersCount->commission  =  Order::where('status', 'COMPLETED')->where('provider_id', $provider_id)->doesntHave('provider_service_online')->sum('commission') ?? 0;

        $ordersCount->commission -=  Transaction::where('user_id', $provider_id)->where('is_usd', 0)->whereNull('order_id')->where('type', 'WITHDRAWAL')->sum('amount') ?? 0;

        $ordersCount->commission +=  Transaction::where('user_id', $provider_id)->where('is_usd', 0)->whereNull('order_id')->where('type', 'DEPOSIT')   ->sum('amount') ?? 0;

        $ordersCount->revenue_online    =  Order::where('status', 'COMPLETED')->where('provider_id', $provider_id)->has('provider_service_online')->sum('price')  ?? 0;

        $ordersCount->commission_online =  Order::where('status', 'COMPLETED')->where('provider_id', $provider_id)->has('provider_service_online')->sum('commission') ?? 0;

        $ordersCount->commission_online -=  Transaction::where('user_id', $provider_id)->where('is_usd', 1)->whereNull('order_id')->where('type', 'WITHDRAWAL')->sum('amount') ?? 0;

        $ordersCount->commission_online +=  Transaction::where('user_id', $provider_id)->where('is_usd', 1)->whereNull('order_id')->where('type', 'DEPOSIT')   ->sum('amount') ?? 0;

        $ordersCount->earnings = $ordersCount->revenue_online - $ordersCount->commission_online;



        if ($request->id != Null)
            $ordersCount->unit    =  $localization = $request->header("x-user-localization") === 'ar,SA' ? $provider_info->country->unit : $provider_info->country->unit_en;

        $trans =  Transaction::where('user_id', $provider_id)->with('order')->orderBy('created_at', 'desc')->take(2)->get();

        $ordersCount->transaction    =  collect($trans)->map(function ($item) use ($request){
            return  [
                'id'          => $item->id,
                'order_id'    => $item->order_id ?: 0,
                'type'        => $item->type,
                'amount'      => $item->amount,
                'commission'  => $item->order->commission ?? 0,
                'created_at'  => Change_Format($item->created_at),
                'title'       => optional($item->order)->product_id? string_value(456, $request).' '.$item->order->product->name : ($item->order_id != Null ?
                    ($item->order->provider_service->title === Null ?
                        get_title(6,  $item->order->provider_service)->name :
                        $item->order->provider_service->title) : ' تسديد العمولات'),
                'title_en'       => optional($item->order)->product_id? string_value(456, $request, true).' '.$item->order->product->name : ($item->order_id != Null ?
                    ($item->order->provider_service->title === Null ?
                        get_title(6,  $item->order->provider_service)->name :
                        $item->order->provider_service->title) : ' تسديد العمولات'),
                'service_target'    => optional(optional(optional($item->order)->provider_service)->service_full)->target,
                'is_usd'      => $item->is_usd,

            ];
        });


        $data             =  $ordersCount;

        return response()->data($data);
    }
    public function allTransactions(Request $request)
    {

        $provider_id   =  $request->id != Null ? $request->id : auth()->id();

        $trans         =  Transaction::where('user_id', $provider_id)->with('order:id,provider_service_id,commission', 'order.provider_service')->orderBy('created_at', 'desc')->get();
        $data          =  collect($trans)->map(function ($item) {
            return  [
                'id'          => $item->id,
                'order_id'    => $item->order_id ?: 0,
                'type'        => $item->type,
                'amount'      => $item->amount,
                'commission'  => $item->order->commission ?? 0,
                'created_at'  => Change_Format($item->created_at),
                'title'       => $item->order_id != Null
                                    ? ( $item->order->provider_service->title === Null
                                        ? get_title(6,  $item->order->provider_service)->name
                                        : $item->order->provider_service->title)
                                    : ' تسديد العمولات',
                'service_target' => optional(optional(optional($item->order)->provider_service)->service_full)->target,
                'is_usd'      => $item->is_usd,
            ];
        });


        return response()->data($data);
    }
    public function identity($id)
    {
        $provider       =   User::findOrFail($id);
        $identities     =   $provider->identity;

        $images = '';
        if ($identities) {
            foreach (explode('||', $identities) as  $image) {
                if (file_exists(public_path() . $image)) {
                    $images .= ' <img src="' . url($image) . '" alt="" style="width: 100%;height: 16rem;" >';
                };
            }
        }
        $images   ?:   $images = '<h3 > لم يرفع هويته بعد </h3>';

        $div            = '<div style="max-height: 300px;overflow-x: auto;margin-top:40px;">' . $images . '</div>';

        return response()->json($div);
    }
    public function verified($id)
    {
        User::where('id', $id)->update(['verified' => true]);
        return true;
    }
    public function unverified($id)
    {
        User::where('id', $id)->update(['verified' => false]);
        return true;
    }
    public function commissionCreate(Request $request)
    {

        if (!$request->provider_id) return false;

        $result  = ProviderCommission::where('provider_id', $request->provider_id)->delete();

        if ($request->commission) {

            $result  = ProviderCommission::create([
                'provider_id' => $request->provider_id,
                'commission'   => $request->commission,
                'percentage' => $request->percentage,
            ]);
        };
        return $result;
    }
    public function changeDebt_ceiling(Request $request)
    {
        return User::where('id', $request->provider_id)->update(['debt_ceiling' => $request->debt_ceiling]);
    }
    public function transactionCreate(Request $request)
    {
        $user =  User::findOrFail($request->user_id);

        $transaction =  Transaction::create([
            'user_id' => $request->user_id,
            'type'  => $request->type,
            'amount'   => $request->amount,
            'is_usd' => $request->is_usd
        ]);

        if ($transaction->type  == 'WITHDRAWAL') {

            $user->balance  = $user->balance + $transaction->amount;
            $user->save();
        }
        if ($transaction->type  == 'DEPOSIT') {

            $user->balance  = $user->balance - $transaction->amount;
            $user->save();
        }
        return response()->json(['balance' => $user->balance]);
    }
    public function generateKey($id)
    {
        Cache::add('provider-key-' . $id, rand(100000, 999999), now()->addHour());
        return  Cache::get('provider-key-' . $id);
    }
    public function generateKeyByphone($phone)
    {
        Cache::add('provider-key-phone-' . $phone, rand(100000, 999999), now()->addHour());
        return  Cache::get('provider-key-phone-' . $phone);
    }
    public function incrementProfileViewers($provider_id)
    {

        $provider                 =   user::where('id', $provider_id)->increment('number_profile_viewers' ,1);

        $message                  =   'Increase profile viewers successfull';
        return response()->data($provider, $message);
    }
    public function providerSkillCreate(Request $request)
    {
        ProviderSkill::create([
            'skill_id' => $request->skill_id,
            'user_id'  => $request->user_id,
        ]);

        $user               =  User::find($request->user_id);

        $skills =  $user->provider_skills()->with('skill')->get()->map(function ($item) {
            return [
                'id'        => $item->id,
                'name'      => $item->skill->name,
                'name_en'   => $item->skill->name_en,
        ];})->toArray();

        return response()->data($skills);
    }
    public function providerSkillRemove($provider_skill_id)
    {
        ProviderSkill::where('id', $provider_skill_id)->delete();

        $user               =  User::find(auth()->id());

        $skills =  $user->provider_skills()->with('skill')->get()->map(function ($item) {
            return [
                'id'        => $item->id,
                'name'      => $item->skill->name,
                'name_en'   => $item->skill->name_en,
        ];})->toArray();

        return response()->data($skills);
    }
    public function allProviderServices(Request $request)
    {
        if(
            ((int) str_replace('.', '', $request->header("x-build-number"))  > (int) str_replace('.', '', "4")
            &&
            $request->header('x-app-type') == 'MONITOR_APP')
        ){
            $fields = $request->all();

        $status      =  strtoupper($request->status);

        $counters    = ProviderServices::selectRaw('COUNT(if(status=\'PENDING\', 1, NULL)) as PENDING,COUNT(if(status=\'ACCEPTED\', 1, NULL)) as ACCEPTED,COUNT(if(status=\'REJECTED\', 1, NULL)) as REJECTED');
        if (isset($fields['word_search']))
        $counters = $counters->where(function ($query) use ($fields) {
            return $query->where('specializ', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('brand', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('description', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('title', 'like', '%' . $fields['word_search'] . '%')
                ->orWhereHas('provider', function ($query) use ($fields) {
                    $query->where('username', 'like', '%' . $fields['word_search'] . '%');
                });
        });

        if (isset($fields['date']))
        $counters = $counters->where('updated_at', 'like', '%' . $fields['date'] . '%');

        $counters = $counters->first();

        $provider_services   = ProviderServices::selectRaw(
            'DATE_FORMAT(updated_at, \'%Y-%m-%d\') AS date,COUNT(id) AS sum,COUNT(if(status=\'PENDING\', 1, NULL)) as PENDING,COUNT(if(status=\'ACCEPTED\', 1, NULL)) as ACCEPTED,COUNT(if(status=\'REJECTED\', 1, NULL)) as REJECTED')
        ->groupBy('date')->orderBy('date', 'DESC');

        if (isset($fields['word_search']))
        $provider_services = $provider_services->where(function ($query) use ($fields) {
            return $query->where('specializ', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('brand', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('description', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('title', 'like', '%' . $fields['word_search'] . '%')
                ->orWhereHas('provider', function ($query) use ($fields) {
                    $query->where('username', 'like', '%' . $fields['word_search'] . '%');
                });
        });

        if (isset($fields['date']))
        $provider_services = $provider_services->where('updated_at', 'like', '%' . $fields['date'] . '%');

        $provider_services =  $provider_services->withTrashed()->get();
        $dates =  $provider_services;


        $provider_services      =  $provider_services->take(3)->map(function ($provider_services)  use ($status, $request, $fields) {

            $provider_services->provider_services = ProviderServices::
            //where('status', $status)->
            where('updated_at','like', '%'.$provider_services->date.'%')
            ->orderBy('updated_at', 'desc')
            ->with('provider:id,username,number_phone,country_id,verified,active,deleted_at', 'all_offers:id,price,description', 'provider.country:id,code,country_code,unit,unit_en', 'ServiceType');

            if (isset($fields['word_search']))
            $provider_services->provider_services = $provider_services->provider_services->where(function ($query) use ($fields, $request) {
                return $query->where('specializ', 'like', '%' . $fields['word_search'] . '%')
                    ->orwhere('brand', 'like', '%' . $fields['word_search'] . '%')
                    ->orwhere('description', 'like', '%' . $fields['word_search'] . '%')
                    ->orwhere('title', 'like', '%' . $fields['word_search'] . '%')
                    ->orWhereHas('provider', function ($query) use ($fields) {
                        $query->where('username', 'like', '%' . $fields['word_search'] . '%');
                    });
            });

            $provider_services->provider_services = $provider_services->provider_services->withTrashed()->get();

            $provider_services->provider_services     =  collect($provider_services->provider_services)->take(3)->map(function ($providerService) use ($request) {

                $city = optional($providerService->city)->name ?? "";
                $city = $city != '' ? ' ( ' . (optional($providerService->city)->name ?? "") . ' ) ' : '';
                $account_deleted = ($providerService->provider->deleted_at? ' (حساب محذوف)' : '');
                $service_deleted = ($providerService->deleted_at? ' (خدمة محذوفة)' : '');

                return  [

                    'id'                                => $providerService->id,
                    'provider_id'                       => $providerService->provider->id,
                    'provider_name'                     => $providerService->provider->username . $city. $account_deleted . $service_deleted,
                    'provider_country'                  => $providerService->provider->country->name,
                    'deleted_at'                        => $providerService->provider->deleted_at,
                    'provider_skills'                   => $providerService->provider->provider_skills()->with('skill')->get()->pluck('skill')->map(function ($skill) {
                        return [
                            'name'      => $skill->name,
                            'name_en'   => $skill->name_en,
                    ];})->toArray(),
                    'thumbnail'                         => $providerService->thumbnail ? url('') . $providerService->thumbnail : default_image(),
                    'phone'                             => $providerService->provider->country->country_code . $providerService->provider->number_phone,
                    'provider_services_title'           => $providerService->title === Null ? get_title(6, $providerService)->name : $providerService->title,
                    'provider_services_title_en'        => $providerService->title === Null ? get_title(6, $providerService)->name_en: $providerService->title,
                    'specializ'                         => $providerService->specializ,
                    'brand'                             => $providerService->brand,
                    'stars'                             => $providerService->rating->avg('stars') ?: 5,
                    "country_name"                      => optional($providerService->country)->name ?? "",
                    "city_name"                         => optional($providerService->city)->name ?? "",
                    "street_name"                       => optional($providerService->street)->name ?? "",
                    "active"                            => $providerService->provider->active     ?   true    :   false,
                    "profile_verified"                  => $providerService->provider->verified   ?   true    :   false,
                    "cat_subcat_sub1_sub2_sub3_sub4"    => $providerService->cat_subcat_sub1_sub2_sub3_sub4,
                    "country_city_street"               => $providerService->country_city_street,
                    "title_from"                        => Service::where('id', $providerService->service_id)->firstOrFail()->title_from,
                    "specializ_from"                    => Service::where('id', $providerService->service_id)->firstOrFail()->specializ_from,
                    "brand_from"                        => Service::where('id', $providerService->service_id)->firstOrFail()->brand_from,
                    // "quick_offer"                       => $providerService->quickOffer,
                    "status"                            => $providerService->status,
                    "offers"                            => sizeof($providerService->offers) ? $providerService->offers : false,
                    "service_target"                    => $providerService->service_full->target,
                    "unit"                              => $request->header("x-user-localization") == 'ar,SA'? $providerService->provider->country->unit : $providerService->provider->country->unit_en,
                    "pin_top"                           => $providerService->pin_top,
                    "type"                              => $request->header("x-user-localization") == "ar,SA" ? optional($providerService->serviceType)->name : optional($providerService->serviceType)->name_en,
                    "order_index"                       => $providerService->service_full->order_index,
                ];
            });

            //return $provider_services;
            list($provider_services->accepted_items, $provider_services->provider_services) = $provider_services->provider_services->partition(function ($i) {
                return $i['status'] === 'ACCEPTED';
            });
            list($provider_services->pending_items, $provider_services->provider_services) = $provider_services->provider_services->partition(function ($i) {
                return $i['status'] === 'PENDING';
            });
            list($provider_services->rejected_items, $provider_services->provider_services) = $provider_services->provider_services->partition(function ($i) {
                return $i['status'] === 'REJECTED';
            });


            $provider_services->accepted_items    = array_values($provider_services->accepted_items->toArray());
            $provider_services->pending_items     = array_values($provider_services->pending_items->toArray());
            $provider_services->rejected_items    = array_values($provider_services->rejected_items->toArray());

            unset($provider_services->provider_services);

            return $provider_services;
        })->toArray();

        $dates = $dates->map(function ($dates) {

            return [
                'date' => $dates->date,
                'sum' => $dates->sum,
                'PENDING' => $dates->PENDING,
                'ACCEPTED' => $dates->ACCEPTED,
                'REJECTED' => $dates->REJECTED,
            ];

        })->toArray();

        $provider_services  +=  $dates;


        $data = [
            'PENDING'   => $counters->PENDING,
            'ACCEPTED'  => $counters->ACCEPTED,
            'REJECTED'  => $counters->REJECTED,
            'provider_services' => $provider_services,
        ];


        return response()->data($data);
        }

        //----------------------------------------------

        $fields = $request->all();

        $status      =  strtoupper($request->status);

        $counters    = ProviderServices::selectRaw('COUNT(if(status=\'PENDING\', 1, NULL)) as PENDING,COUNT(if(status=\'ACCEPTED\', 1, NULL)) as ACCEPTED,COUNT(if(status=\'REJECTED\', 1, NULL)) as REJECTED');
        if (isset($fields['word_search']))
        $counters = $counters->where(function ($query) use ($fields) {
            return $query->where('specializ', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('brand', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('description', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('title', 'like', '%' . $fields['word_search'] . '%')
                ->orWhereHas('provider', function ($query) use ($fields) {
                    $query->where('username', 'like', '%' . $fields['word_search'] . '%');
                });
        });
        $counters = $counters->first();

        $provider_services   = ProviderServices::selectRaw(
            'DATE_FORMAT(updated_at, \'%Y-%m-%d\') AS date,COUNT(id) AS sum,COUNT(if(status=\'PENDING\', 1, NULL)) as PENDING,COUNT(if(status=\'ACCEPTED\', 1, NULL)) as ACCEPTED,COUNT(if(status=\'REJECTED\', 1, NULL)) as REJECTED')
        ->groupBy('date')->orderBy('date', 'DESC');

        if (isset($fields['word_search']))
        $provider_services = $provider_services->where(function ($query) use ($fields) {
            return $query->where('specializ', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('brand', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('description', 'like', '%' . $fields['word_search'] . '%')
                ->orwhere('title', 'like', '%' . $fields['word_search'] . '%')
                ->orWhereHas('provider', function ($query) use ($fields) {
                    $query->where('username', 'like', '%' . $fields['word_search'] . '%');
                });
        });
        $provider_services =  $provider_services->get();


        $provider_services      =  $provider_services->map(function ($provider_services)  use ($status, $request, $fields) {

            $provider_services->provider_services = ProviderServices::where('status', $status)
            ->where('updated_at','like', '%'.$provider_services->date.'%')
            ->orderBy('updated_at', 'desc')
            ->with('provider:id,username,number_phone,country_id,verified,active', 'all_offers:id,price,description', 'provider.country:id,code,country_code,unit,unit_en', 'ServiceType');

            if (isset($fields['word_search']))
            $provider_services->provider_services = $provider_services->provider_services->where(function ($query) use ($fields, $request) {
                return $query->where('specializ', 'like', '%' . $fields['word_search'] . '%')
                    ->orwhere('brand', 'like', '%' . $fields['word_search'] . '%')
                    ->orwhere('description', 'like', '%' . $fields['word_search'] . '%')
                    ->orwhere('title', 'like', '%' . $fields['word_search'] . '%')
                    ->orWhereHas('provider', function ($query) use ($fields) {
                        $query->where('username', 'like', '%' . $fields['word_search'] . '%');
                    });
            });

            $provider_services->provider_services = $provider_services->provider_services->get();

            $provider_services->provider_services     =  collect($provider_services->provider_services)->map(function ($providerService) use ($request) {

                $city = optional($providerService->city)->name ?? "";
                $city = $city != '' ? ' ( ' . (optional($providerService->city)->name ?? "") . ' ) ' : '';

                return  [

                    'id'                                => $providerService->id,
                    'provider_id'                       => $providerService->provider->id,
                    'provider_name'                     => $providerService->provider->username . $city,
                    'provider_country'                  => $providerService->provider->country->name,
                    'provider_skills'                   => $providerService->provider->provider_skills()->with('skill')->get()->pluck('skill')->map(function ($skill) {
                        return [
                            'name'      => $skill->name,
                            'name_en'   => $skill->name_en,
                    ];})->toArray(),
                    'thumbnail'                         => $providerService->thumbnail ? url('') . $providerService->thumbnail : default_image(),
                    'phone'                             => $providerService->provider->country->country_code . $providerService->provider->number_phone,
                    'provider_services_title'           => $providerService->title === Null ? get_title(6, $providerService)->name : $providerService->title,
                    'provider_services_title_en'        => $providerService->title === Null ? get_title(6, $providerService)->name_en: $providerService->title,
                    'specializ'                         => $providerService->specializ,
                    'brand'                             => $providerService->brand,
                    'stars'                             => $providerService->rating->avg('stars') ?: 5,
                    "country_name"                      => optional($providerService->country)->name ?? "",
                    "city_name"                         => optional($providerService->city)->name ?? "",
                    "street_name"                       => optional($providerService->street)->name ?? "",
                    "active"                            => $providerService->provider->active     ?   true    :   false,
                    "profile_verified"                  => $providerService->provider->verified   ?   true    :   false,
                    "cat_subcat_sub1_sub2_sub3_sub4"    => $providerService->cat_subcat_sub1_sub2_sub3_sub4,
                    "country_city_street"               => $providerService->country_city_street,
                    "title_from"                        => Service::where('id', $providerService->service_id)->firstOrFail()->title_from,
                    "specializ_from"                    => Service::where('id', $providerService->service_id)->firstOrFail()->specializ_from,
                    "brand_from"                        => Service::where('id', $providerService->service_id)->firstOrFail()->brand_from,
                    // "quick_offer"                       => $providerService->quickOffer,
                    "offers"                            => sizeof($providerService->offers) ? $providerService->offers : false,
                    "service_target"                    => $providerService->service_full->target,
                    "unit"                              => $request->header("x-user-localization") == 'ar,SA'? $providerService->provider->country->unit : $providerService->provider->country->unit_en,
                    "pin_top"                           => $providerService->pin_top,
                    "type"                              => $request->header("x-user-localization") == "ar,SA" ? optional($providerService->serviceType)->name : optional($providerService->serviceType)->name_en,
                    "order_index"                       => $providerService->service_full->order_index,
                ];
            });

            return $provider_services;
        });



        $data = [
            'PENDING'   => $counters->PENDING,
            'ACCEPTED'  => $counters->ACCEPTED,
            'REJECTED'  => $counters->REJECTED,
            'provider_services' => $provider_services,
        ];


        return response()->data($data);
    }
    public function change_services_status(Request $request)
    {
        $fields = $request->all();

        $status      =  strtoupper($fields['status']);

        $providerServices            =   ProviderServices::where('id', $fields['id'])->with('provider:id,device_token')->withTrashed()->first();
        
        if($providerServices->deleted_at)
            return response()->error('','عذراً, هذه الخدمة محذوفة...');

        $providerServices->status    =   $status;
        $providerServices->save();

        if($status == 'ACCEPTED' || $status == 'REJECTED')
        {
            if($status == 'ACCEPTED')
            $notification                 =  Notification::create([
                'user_id'       => $providerServices->provider->id,
                'icon'          => 'bell_outline_mco',
                'title'         => 'مبروك! تم تفعيل خدمتك ' . ($providerServices->title === Null ? get_title(6, $providerServices)->name : $providerServices->title) . '',
                'message'       => '',
            ]);

            if($status == 'REJECTED')
            $notification                 =     Notification::create([
                'user_id'       => $providerServices->provider->id,
                'icon'          => 'bell_outline_mco',
                'title'         => 'للأسف، تم رفض الخدمة التي أرسلتها ' . ($providerServices->title === Null ? get_title(6, $providerServices)->name : $providerServices->title) . '',
                'message'       => '',
            ]);

            // notify FCM

            $device_token     =   $providerServices->provider->makeVisible(['device_token'])->device_token;

            if ($device_token) {

                $fcm                =    new FCM();

                $title              =    $notification->title;

                $fcm->to($device_token)->message('', $title)->data(Null, 'services_status', '', $title, 'Notifications')->send();
            }

        }

        return response()->data($providerServices);
    }
    public function change_services_pin_top(Request $request)
    {
        $fields = $request->all();

        $providerServices            =   ProviderServices::where('id', $fields['id'])->with('provider:id,device_token')->withTrashed()->first();

        if($providerServices->deleted_at)
            return response()->error('','عذراً, هذه الخدمة محذوفة...');

        $providerServices->pin_top    =   $fields['pin_top'];
        $providerServices->save();

        return response()->data($providerServices);
    }

}
