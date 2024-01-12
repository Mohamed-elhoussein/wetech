<?php

namespace App\Http\Controllers\api;

// use App\Http\Resources\OrderObserverMaintenanceRequestResource;
use App\Http\Resources\OrderBuyerRequestResource;
use App\Http\Resources\OrderResource;
use App\Helpers\FCM;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\QuickOffers;
use App\Models\ProductPayment;
use App\Models\ProviderServices;
use App\Models\ProviderCommission;
use App\Models\OrdersProvidersLog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\OfferAccepted;
use App\Models\Fee;
use App\Models\OrderFee;
use Illuminate\Support\Facades\Mail;
use DB;


class OrderApiController extends Controller
{
    public function orders(Request $request)
    {
        $auth_id     =  $request->id != Null ? $request->id : auth()->user()->id;

        $status      =  strtoupper($request->status);

        $status  == 'PENDING' ? $status = ['PENDING', 'WAITING', 'ONE_SIDED_CANCELED'] : $status = [$status];

        $is_user = strtoupper(auth()->user()->role) == 'USER' || ($request->id != Null && strtoupper(User::find($auth_id)->role) == 'USER');

        // Revert this to USER

        $orders      =  Order::whereIn('status', $status)
            ->where($is_user? 'user_id' : 'provider_id', $auth_id)
            ->orderBy('updated_at', 'desc')
            ->with([
                'carts.product',
                'order_payment_option',
                'order_payment_option.payment_option',
                'order_fees',
                'provider_service',
                ($is_user? 'user' : 'provider') . ':id,username,number_phone,country_id',
                'offer:id,price,description',
                'product',
                'payment',
                'user',
                'coupon',
                'provider',
                'product_payment',
                'buyer_request',
                'maintenance_request_order',
                'maintenance_request_order.maintenance_request_order_coupon',
                'maintenance_request_order.maintenance_request_order_coupon.coupon',
                'maintenance_request_order.maintenance_type.maintenance_request',
                'maintenance_request_order.maintenance_type.maintenance_request.service:id,name,name_en',
                'maintenance_request_order.maintenance_type.maintenance_request.brand:id,name',
                'maintenance_request_order.maintenance_type.maintenance_request.model:id,name',
                'maintenance_request_order.color:id,name',
                'maintenance_request_order.maintenance_type.maintenance_request.issue:id,name',
            ])
            ->get();

        $data        =  OrderResource::collection($orders, $request);

        return response()->data($data);
    }

    public function details($order_id)
    {
        $order =  Order::where('id', $order_id)->with([
            'carts.product',
            'order_payment_option',
            'order_payment_option.payment_option',
            'order_fees',
            'provider_service',
            'provider:id,username,number_phone,country_id',
            'offer:id,price,description',
            'product',
            'payment',
            'user',
            'coupon',
            'product_payment',
            'buyer_request',
            'maintenance_request_order',
            'maintenance_request_order.maintenance_request_order_coupon',
            'maintenance_request_order.maintenance_request_order_coupon.coupon',
            'maintenance_request_order.maintenance_type.maintenance_request',
            'maintenance_request_order.maintenance_type.maintenance_request.service:id,name,name_en',
            'maintenance_request_order.maintenance_type.maintenance_request.brand:id,name',
            'maintenance_request_order.maintenance_type.maintenance_request.model:id,name',
            'maintenance_request_order.color:id,name',
            'maintenance_request_order.maintenance_type.maintenance_request.issue:id,name',
            'log_providers.provider'
        ])->first();

        if(!$order)
            return response()->error(201, 'يا عاطف هذا ليس ب أوردير');

        $order->who_canceled = $order->canceled_by == $order->user_id ? 'user' : ($order->canceled_by == $order->provider_id ? 'provider' : 'admin');

        $order->price = $order->price + $order->order_fees->sum('fee_value');

        return response()->data(new OrderResource($order));
    }
    public function create(Request  $request)
    {

        $this->validate($request, rules('orders.create'));

        $fields = $request->all();

        $user_id = isset($fields['user_id']) ? $request->user_id : auth()->user()->id;

        $offer            =   Offer::where('id', $request->offer_id)->with('provider', 'provider_service')->firstOrFail();

        $user     = User::find($user_id);
        $provider = User::find($offer->provider_id);

        //$pending_balance = Order::where('user_id', $user_id)->whereIn('status',['PENDING','WAITING'])->has('provider_service_online')->sum('price');

        $is_online_services = $offer->service->service_full->target === 'online_services';

        if ($is_online_services) {
            if ($user->balance_online < $offer->price)
                return response()->error(0, 'لايوجد لديك رصيد كافي:' . '\nسعر العرض:  ' . $offer->price . '$\nرصيدك:  ' . $user->balance_online . '$');
            else
                User::where('id', $user_id)->update(['balance_online' =>  $user->balance_online - $offer->price]);

            $commission_row   =   ProviderCommission::where('provider_id', $offer->provider_id)->where('is_online', 1)->first();
        } else{
            $commission_row   =   ProviderCommission::where('provider_id', $offer->provider_id)->first();
        }



        if ($commission_row)
            $commission       =  $commission_row->percentage == 1 ? ($offer->price * $commission_row->commission / 100) : $commission_row->commission;
        else
            $commission       =  Setting::get($is_online_services? 'default_commission_online' : 'default_commission')[0];


        $order            =   Order::create([
            'user_id'              =>  $user_id,
            'provider_id'          =>  $offer->provider_id,
            'offer_id'             =>  $offer->id,
            'provider_service_id'  =>  $offer->provider_service_id,
            'price'                =>  $offer->price,
            'commission'           =>  $commission,
        ])->with('provider_service:id,title', 'user:id,username')->first();

        $offer->status   =  'ACCEPTED';

        $offer->save();

        $ms = 'قام الزبون ' . auth()->user()->username . 'بقبول عرضك المرجوا الرجوع للمحادثة لمعرفة التفاصيل';
        Mail::to($offer->provider)->send(new OfferAccepted($ms));


        $payload     =   [
            'type'       =>     'offer',
            'message_id' =>     $request->message_id,
            'status'     =>     $offer->status,
            'message'    =>     $offer,
        ];

        //$to_user = '';

        $device_token        =   $user->device_token;

        if ($device_token) {

            $fcm                =   new FCM();

            $payload1           =   $payload + ['send_by' => $provider->id];

            $un                 =   $provider->username;

            $fcm->to($device_token)->message_payload($payload1)->data($provider->id, 'info', 'لقد قبلت العرض', $un, 'LiveChat')->send();
        }



        $device_token     =   $provider->device_token;

        // if ($device_token) {

            $fcm                     =      new FCM();

            $payload += ['send_by'   =>     $user->id];

            $un                      =      $user->username;

            $fcm->to($device_token)->message_payload($payload)->message('تم قبول عرضك', $un)->data($user->id, 'info', 'تم قبول عرضك', $un, 'LiveChat')->send();

            $observers_token  =   User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();

            foreach ($observers_token as $token)
                $fcm->to($token)->message_payload($payload)->message('تم قبول عرضك', $un . '  =>  ' . $provider->username)->data($user->id, 'info', 'تم قبول عرضك', $un . '  =>  ' . $provider->username, 'LiveChat')->send();
        // }


        $title_service = ($offer->provider_service->title === Null ? get_title(6, $offer->provider_service)->name : $offer->provider_service->title);

        /* Notify provider that user accept offer  */
        $notification                 =    Notification::create([
            'user_id'       => $user->id,
            'order_id'      => $order->id,
            'icon'          => 'bell_outline_mco',
            'title'         => 'تم طلب من ' . $provider->username . ' الطلب: ' . $title_service . '.',
            'message'       => '',
        ]);


        $device_token        =   $user->device_token;

        if ($device_token) {

            $fcm                =   new FCM();

            $title            =    $notification->title;

            $fcm->to($device_token)->message('', $title)->data('', 'info', '', $title, 'Notifications')->send();
        }


        /* Notify provider that user accept offer  */
        $notification                 =    Notification::create([
            'user_id'       => $provider->id,
            'order_id'      => $order->id,
            'icon'          => 'bell_outline_mco',
            'title'         => 'طلب خدمتك ' . $user->username . ' الطلب: ' . $title_service . '.',
            'message'       => '',
        ]);

        $device_token     =   $provider->device_token;

        if ($device_token) {

            $fcm              =      new FCM();

            $title            =    $notification->title;

            $fcm->to($device_token)->message('', $title)->data('', 'info', '', $title, 'Notifications')->send();
        }


        $data    = ['status'  => 'ACCEPTED', 'offer' => $offer];
        $message =  'order was created successfully';

        return response()->data($data, $message);
    }

    public function status(Request $request, $id)
    {
        $this->validate($request, rules('orders.update'));
        $fields = $request->all();

        $order = order::where('id', $id)->with('provider_service', 'provider:id,username,device_token,balance,balance_online', 'user:id,username,device_token,balance_online', 'product')->first();

        if($fields['status']  == 'CANCELED' && auth()->user()->role !== 'chat_review'){
            $title_service =  $order->product_id? $order->product->name : ($order->provider_service->title === Null ? get_title(6, $order->provider_service)->name : $order->provider_service->title);

            $notification         =  Notification::create([
                'user_id'              => $order->provider_id,
                'order_id'             => $order->id,
                'icon'                 => 'bell_outline_mco',
                'title'                => 'حاول الزبون ' . $order->user->username . ' إلغاء الطلب: ' . $title_service .'.',
                'message'              => 'كتب السبب التالي: '. ($fields['canceled_reason'] ?? ''),
            ]);
            $device_token     =  auth()->user()->id != $order->user_id ? $order->user->makeVisible(['device_token'])->device_token : $order->provider->makeVisible(['device_token'])->device_token;

            if ($device_token) {
                $fcm              =    new FCM();
                $title            =    $notification->title;
                $message            =    $notification->message;
                $fcm->to($device_token)->message($message, $title)->data('', 'info', $message, $title, 'Notifications')->send();
            }

            // if ($device_token) {
                $fcm                     =      new FCM();
                $un                      =      $order->user->username;
                $observers_token  =   User::where('role', 'chat_review')->get();
                foreach ($observers_token as $token)
                {
                    $notification         =  Notification::create([
                        'user_id'              => $token->id,
                        'order_id'             => $order->id,
                        'icon'                 => 'bell_outline_mco',
                        'title'                => 'حاول الزبون ' . $order->user->username . ' إلغاء الطلب: ' . $title_service . ' من المزود ' . $order->provider->username . '.',
                        'message'              => 'كتب السبب التالي: '. ($fields['canceled_reason'] ?? ''),
                    ]);
                    $title            =    $notification->title;
                    $message            =    $notification->message;
                    $fcm->to($token->device_token)->message($message, $title)->data('', 'info', $message, $title, 'Notifications')->send();
                }
            // }

            return response()->error(0, 'لايمكنك الإلغاء سيتم التواصل بك من قبل الفني أو الاداره في أقرب وقت لمعرفة الاسباب');
        }


        $old_status = $order->status;
        $new_status = $fields['status'];

        isset($fields['status'])               ?   $order->status            = $fields['status']             :   false;
        isset($fields['canceled_by'])          ?   $order->canceled_by       = $fields['canceled_by']        :   false;
        isset($fields['canceled_reason'])      ?   $order->canceled_reason   = $fields['canceled_reason']    :   false;

        $is_online_services = false; //$order->product_id? false : $order->provider_service->service_full->target === "online_services";

        if (isset($fields['price'])) {
            $commission_row   =     $is_online_services
                                    ? ProviderCommission::where('provider_id', $order->provider->id)->where('is_online', 1)->first()
                                    : ProviderCommission::where('provider_id', $order->provider->id)->first();

            if ($commission_row)
                $commission       =  $commission_row->percentage == 1 ? (convertArabicNumber($fields['price']) * $commission_row->commission / 100) : $commission_row->commission;
            else
                $commission       =  Setting::get($is_online_services? 'default_commission_online' : 'default_commission')[0];

            $order->commission    =  $commission;

            $order->price         =  convertArabicNumber($fields['price']);
        }

        if (isset($fields['description']) && $order->offer_id) {
            $offer    = Offer::where('id', $order->offer_id)->firstOrFail();
            $offer->description = $fields['description'];
            $offer->save();
        }

      	if (auth()->user()->role != 'user') {
	        $order->save();
        }

        $title_service =  $order->product_id? $order->product->name : ($order->provider_service->title === Null ? get_title(6, $order->provider_service)->name : $order->provider_service->title);

        if ($fields['status']  == 'COMPLETED'  && $old_status != $new_status) {
            $notification               =      Notification::create([
                'user_id'       => $order->provider_id,
                'order_id'      => $order->id,
                'icon'          => 'bell_outline_mco',
                'title'         => 'مبروك!  استلم ' . $order->user->username . ' الطلب: ' . $title_service . '.',
                'message'       => '',
            ]);


            if ($is_online_services)
                User::where('id', $order->provider->id)->update(['balance_online'     => $order->provider->balance_online + $order->commission]);
            else
                User::where('id', $order->provider->id)->update(['balance'            => $order->provider->balance        - $order->commission]);

            Transaction::create([
                'user_id'      => $order->provider_id,
                'customer_id'  => $is_online_services ? ($order->user_id) : Null,
                'order_id'     => $order->id,
                'type'         => 'DEPOSIT',
                'amount'       => $order->price,
                'is_usd'       => $is_online_services ? 1 : 0,
            ]);

            $device_token     =   $order->provider->makeVisible(['device_token'])->device_token;

            if ($device_token) {

                $fcm                =    new FCM();

                $title            =    $notification->title;

                $fcm->to($device_token)->message('', $title)->data(NULL, 'order', '', $title, 'Notifications')->send();
            };
        };



        if ($fields['status']  == 'CANCELED'  && $old_status != $new_status) {

            if ($is_online_services)
                User::where('id', $order->user_id)->update(['balance_online' =>  $order->user->balance_online + $order->price]);

            $provider_name = auth()->user()->username;

            if (auth()->user()->role === 'chat_review') {
                $provider_name = $order->provider->username;
            }

            $notification                 =    Notification::create([
                'user_id'       => auth()->user()->id != $order->user_id ? $order->user_id : $order->provider_id,
                'order_id'      => $order->id,
                'icon'          => 'cancel_mdi',
                'icon_color'    => '#FF0000',
                'title'         => '' . $provider_name . ' ألغى الطلب ' . $title_service . '',
                'message'       => '',
            ]);
            // notify FCM

            $device_token     =  auth()->user()->id != $order->user_id ? $order->user->makeVisible(['device_token'])->device_token : $order->provider->makeVisible(['device_token'])->device_token;

            if ($device_token) {

                $fcm                =    new FCM();

                $title            =    $notification->title;

                $fcm->to($device_token)->message('', $title)->data(NULL, 'order', '', $title, 'Notifications')->send();
            };
        }



        if ($fields['status']  == 'WAITING') {

            $notification     =      Notification::create([
                'user_id'       => $order->user_id,
                'order_id'      => $order->id,
                'icon'          => 'bell_outline_mco',
                'title'         => 'مزود الخدمة ' . $order->provider->username . ( $order->product_id? (' يطلب منك تسليم الطلب بعد إستلام ' . $title_service . '.'):(' أتمم خدمة ' . $title_service . ' بنجاح')),
                'message'       => $order->product_id? ('المنتج: ' . $title_service . '.' ) : 'وينتظر منك تسلمها',
            ]);
            // notify FCM

            $device_token     =   $order->user->makeVisible(['device_token'])->device_token;

            if ($device_token) {

                $fcm              =    new FCM();

                $title            =    $notification->title;

                $message          =    $notification->message;

                $fcm->to($device_token)->message($message, $title)->data(NULL, 'order', $message, $title, 'Notifications')->send();
            };
        }

        if ($fields['status']  == 'PENDING') {
            if (auth()->user()->role === 'provider') {
                $user_id = $order->user_id;
                $title   = 'مزود الخدمة ' .  $order->provider->username . ' تراجع عن إلغاء الخدمة ' . $title_service;
                $message = 'ويكمل العمل عليها';
            } else // user
            {
                $user_id = $order->provider_id;
                $title   = $order->user->username . ' تراجع عن إلغاء الخدمة ' . $title_service;
                $message = 'وينتظر منك إتمام العمل  عليها';
            }
            $notification     =      Notification::create([
                'user_id'       => $user_id,
                'order_id'      => $order->id,
                'icon'          => 'play_faw',
                'icon_color'    => '#00FF00',
                'title'         => $title,
                'message'       => $message,
            ]);

            $device_token     =  auth()->user()->id != $order->user_id ? $order->user->makeVisible(['device_token'])->device_token : $order->provider->makeVisible(['device_token'])->device_token;

            if ($device_token) {

                $fcm              =    new FCM();

                $title            =    $notification->title;

                $message          =    $notification->message;

                $fcm->to($device_token)->message($message, $title)->data(NULL, 'order', $message, $title, 'Notifications')->send();
            };
        }

        if ($fields['status']  == 'ONE_SIDED_CANCELED') {
            if (auth()->user()->role === 'provider') {
                $user_id = $order->user_id;
                $title   = 'مزود الخدمة ' .  $order->provider->username . ' طلب إلغاء الخدمة ' . $title_service;
            } else // user
            {
                $user_id = $order->provider_id;
                $title   = $order->user->username . ' طلب إلغاء الخدمة ' . $title_service;
            }

            $notification     =      Notification::create([
                'user_id'       => $user_id,
                'order_id'      => $order->id,
                'icon'          => 'marker_cancel_mco',
                'icon_color'    => '#FF0000',
                'title'         => $title,
                'message'       => 'وينتظر منك قبول الإلغاء',
            ]);
            // notify FCM

            $device_token     =  auth()->user()->id != $order->user_id ? $order->user->makeVisible(['device_token'])->device_token : $order->provider->makeVisible(['device_token'])->device_token;

            if ($device_token) {

                $fcm              =    new FCM();

                $title            =    $notification->title;

                $message          =    $notification->message;

                $fcm->to($device_token)->message($message, $title)->data(NULL, 'order', $message, $title, 'Notifications')->send();
            };
        }


        $data =  collect($order)->except('provider_service', 'provider', 'user');
        $message =  auth()->user()->role != 'user' ? 'order was updated successfully' : ($fields['status'] == 'CANCELED' ? 'لم يتم الغاء الطلب, المرجو الإعادة لاحقا' : 'order was updated successfully');

        return response()->data($data, $message);
    }

    public function delete($id)
    {
        Order::where('id', $id)->delete();

        $message = 'order was deleted successfully';

        return response()->message($message);
    }
    public function createFromOffer(Request  $request)
    {

        $this->validate($request, rules('orders.create'));

        $fields   = $request->all();

        $user_id  = isset($fields['user_id']) ? $request->user_id : auth()->user()->id;

        $offer    = Offer::where('id', $request->offer_id)->with('provider', 'provider_service')->firstOrFail();

        $user     = User::find($user_id);
        $provider = User::find($offer->provider_id);


        if ($provider->active == 0)
            return response()->error(349, 'المزود الذي إخترته مشغول حالياً مع عميل اّخر, يمكنك التعامل مع باقي المزودين المتاحين أو المعاودة لاحقاً إلا في حال كان لديك طلب مسبق فيمكنك التواصل معه من صفحة طلباتي', 349, 349);

        $is_online_services = $offer->service->service_full->target === 'online_services';

        $order = providerServices::leftjoin('orders', 'orders.provider_service_id', '=', 'provider_services.id')
            ->selectRaw($is_online_services? 'COUNT(orders.id) as orders_count, provider_services.service_categories_id as service_categories_id' : 'COUNT(orders.id) as orders_count, provider_services.service_id as service_id')
            ->where('orders.status', 'PENDING')
            ->where('orders.user_id', $user_id)
            ->where(  $is_online_services? 'provider_services.service_categories_id' : 'provider_services.service_id', $is_online_services? $offer->provider_service->service_categories_id : $offer->provider_service->service_id)
            ->groupBy($is_online_services? 'provider_services.service_categories_id' : 'provider_services.service_id')
            ->first();


        if ($order)
            return response()->error(420, "لديك طلب سابق لنفس الخدمة\nيمكنك الإطلاع عليها في قسم طلباتي الحالية", 420);


        if ($is_online_services) {
            if ($user->balance_online < $offer->price)
                return response()->error(0, 'لايوجد لديك رصيد كافي:' . '\n-سعر العرض:  ' . $offer->price . '$\n-رصيدك:  ' . $user->balance_online . '$', 0);
            else
                User::where('id', $user_id)->update(['balance_online' =>  $user->balance_online - $offer->price]);

            $commission_row   =   ProviderCommission::where('provider_id', $offer->provider_id)->where('is_online', 1)->first();
        } else{
            $commission_row   =   ProviderCommission::where('provider_id', $offer->provider_id)->first();
        }


        if ($commission_row)
            $commission       =  $commission_row->percentage == 1 ? ($offer->price * $commission_row->commission / 100) : $commission_row->commission;
        else
            $commission       =  Setting::get($is_online_services? 'default_commission_online' : 'default_commission')[0];


        $new_order                =  Order::create([
            'user_id'              =>  $user_id,
            'provider_id'          =>  $offer->provider_id,
            'offer_id'             =>  $offer->id,
            'provider_service_id'  =>  $offer->provider_service_id,
            'price'                =>  $offer->price,
            'commission'           =>  $commission,
        ])->with('provider_service:id,title', 'user:id,username')->first();



        $notification         =  Notification::create([
            'user_id'              =>  $user->id,
            'order_id'             =>  $new_order->id,
            'icon'                 =>  'bell_outline_mco',
            'title'                =>  'تم طلب من ' . $provider->username . ' العرض: ' . $offer->description . '.',
            'message'              =>  '',
        ]);
        $device_token         =   $user->device_token;
        if ($device_token) {
            $fcm              =    new FCM();
            $title            =    $notification->title;
            $fcm->to($device_token)->message('', $title)->data('', 'info', '', $title, 'Notifications')->send();
        }


        $notification         =  Notification::create([
            'user_id'              => $provider->id,
            'order_id'             => $new_order->id,
            'icon'                 => 'bell_outline_mco',
            'title'                => 'طلب ' . $user->username . ' العرض: ' . $offer->description . '.',
            'message'              => '',
        ]);
        $device_token         =    $provider->device_token;
        if ($device_token) {
            $fcm              =    new FCM();
            $title            =    $notification->title;
            $fcm->to($device_token)->message('', $title)->data('', 'info', '', $title, 'Notifications')->send();
        }


        // if ($device_token) {

            $fcm                     =      new FCM();

            $un                      =      $user->username;

            $observers_token  =   User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();

            foreach ($observers_token as $token)
                $fcm->to($token)->message('تم طلب عرضك', $un . '  =>  ' . $provider->username)->data($user->id, 'info', 'تم طلب عرضك: ' . $offer->description, $un . '  =>  ' . $provider->username, 'LiveChat')->send();
        // }


        $data    = ['status'  => 'ACCEPTED', 'offer' => $offer];
        $message =  'order was created successfully';

        return response()->data($data, $message);
    }
    public function createDirectOrder(Request  $request) // without online_services
    {

        $fields   = $request->all();

        $user_id  = isset($fields['user_id']) ? $request->user_id : auth()->user()->id;

        $user     = User::find($user_id);
        $provider = User::find($request->provider_id);


        if ($provider->active == 0)
            return response()->error(349, 'المزود الذي إخترته مشغول حالياً مع عميل اّخر, يمكنك التعامل مع باقي المزودين المتاحين أو المعاودة لاحقاً إلا في حال كان لديك طلب مسبق فيمكنك التواصل معه من صفحة طلباتي', 349, 349);

        $is_online_services = isset($fields['is_online_services']);

        $order = providerServices::leftjoin('orders', 'orders.provider_service_id', '=', 'provider_services.id')
            ->selectRaw($is_online_services? 'COUNT(orders.id) as orders_count, provider_services.service_categories_id as service_categories_id' : 'COUNT(orders.id) as orders_count, provider_services.service_id as service_id')
            ->where('orders.status', 'PENDING')
            ->where('orders.user_id', $user_id)
            ->where(  $is_online_services? 'provider_services.service_categories_id' : 'provider_services.service_id', $is_online_services? $request->service_categories_id : $request->service_id)
            ->groupBy($is_online_services? 'provider_services.service_categories_id' : 'provider_services.service_id')
            ->first();



        if ($order)
            return response()->data(420, 'لديك طلب سابق لنفس الخدمة');

        $isQuickOffer       = isset($fields['quick_offer_id']);

        if ($isQuickOffer) {
            $quick_offer = QuickOffers::where('id', $fields['quick_offer_id'])->first();
            $commission_row   =   $is_online_services
                                    ? ProviderCommission::where('provider_id', $provider->id)->where('is_online', 1)->first()
                                    : ProviderCommission::where('provider_id', $provider->id)->first();

            if ($commission_row)
                $commission       =  $commission_row->percentage == 1 ? ($quick_offer->price * $commission_row->commission / 100) : $commission_row->commission;
            else
                $commission       =  Setting::get($is_online_services? 'default_commission_online' : 'default_commission')[0];

            $title_notification  = ' العرض: ' . $quick_offer->body . '.';
        }

        $offer     =  Offer::create([
            'provider_id'         =>    $provider->id,
            'provider_service_id' =>    $request->provider_service_id,
            'description'         =>    $isQuickOffer ? $quick_offer->body  : 'طلب مباشر',
            'target'              =>    $isQuickOffer ? 'DIRECT_QUICK_OFFER' : 'DIRECT',
            'price'               =>    $isQuickOffer ? $quick_offer->price : 0,
        ]);



        $order                =  Order::create([
            'user_id'              =>  $user->id,
            'provider_id'          =>  $provider->id,
            'offer_id'             =>  $offer->id,
            'provider_service_id'  =>  $request->provider_service_id,
            'price'                =>  $isQuickOffer ? $quick_offer->price : 0,
            'commission'           =>  $isQuickOffer ? $commission : 0,
        ]);

        $order = Order::where('id', $order->id)->with('provider_service', 'user:id,username')->first();

        if ($order->provider_service->title == NULL)
            $order->provider_service->title =  get_title(6, $order->provider_service)->name;

        if (!$isQuickOffer) {
            $title_notification  = ' الخدمة: ' . $order->provider_service->title . '.';
        }

        $notification         =  Notification::create([
            'user_id'              =>  $user->id,
            'order_id'             =>  $order->id,
            'icon'                 =>  'bell_outline_mco',
            'title'                =>  'تم طلب من ' . $provider->username . $title_notification,
            'message'              =>  '',
        ]);
        $device_token         =   $user->device_token;
        if ($device_token) {
            $fcm              =    new FCM();
            $title            =    $notification->title;
            $fcm->to($device_token)->message('', $title)->data('', 'order', '', $title, 'Notifications')->send();
        }


        $notification         =  Notification::create([
            'user_id'              => $provider->id,
            'order_id'             => $order->id,
            'icon'                 => 'bell_outline_mco',
            'title'                => 'طلب ' . $user->username . $title_notification,
            'message'              => '',
        ]);
        $device_token         =    $provider->device_token;
        $title            =    $notification->title;
        if ($device_token) {
            $fcm              =    new FCM();            
            $fcm->to($device_token)->message('', $title)->data('', 'order', '', $title, 'Notifications')->send();
        }

        $fcm                     =      new FCM();

        $un                      =      $user->username;

        $observers_token  =   User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();

        foreach ($observers_token as $token)
            $fcm->to($token)->message('تم طلب الخدمة', $un . '  =>  ' . $provider->username)->data($user->id, 'info',  $title, $un . '  =>  ' . $provider->username, 'orders')->send();


        $data    = ['order'  => 'created'];
        $message =  'order was created successfully';

        return response()->data($data, $message);
    }
    public function allOrders(Request $request) //  allOrdersImprove
    {
        $status      =  strtoupper($request->status);
        $isCanceled = $status  == 'CANCELED';
        $status  == 'PENDING' ? $status = ['PENDING', 'WAITING', 'ONE_SIDED_CANCELED'] : $status = [$status];
        $counters    = Order::selectRaw('COUNT(if(status=\'COMPLETED\', 1, NULL)) as COMPLETED,COUNT(if(status=\'CANCELED\', 1, NULL)) as CANCELED,COUNT(if(status=\'PENDING\'||status=\'WAITING\'||status=\'ONE_SIDED_CANCELED\', 1, NULL)) as PENDING')->first();

        $orders_day = Order::selectRaw(
            'DATE_FORMAT('. ($isCanceled? 'created_at' : 'updated_at') .', \'%Y-%m-%d\') AS date,COUNT(id) AS sum,COUNT(if(status=\'COMPLETED\', 1, NULL)) as COMPLETED,COUNT(if(status=\'CANCELED\', 1, NULL)) as CANCELED,COUNT(if(status=\'PENDING\'||status=\'WAITING\'||status=\'ONE_SIDED_CANCELED\', 1, NULL)) as PENDING, orders.*'
        )
            ->whereIn('status', $status)
            ->groupBy($isCanceled? 'created_at' : 'updated_at')
            ->orderBy($isCanceled? 'created_at' : 'updated_at', 'desc')
            ->with('provider_service',
                'provider_service.service_full',
                'provider_service.service_category',
                'provider_service.service_subcategories',
                'provider_service.service_sub2',
                'provider_service.service_sub3',
                'provider_service.service_sub4',
                'provider:id,username,number_phone,country_id,deleted_at',
                'user:id,username,number_phone,country_id,deleted_at',
                'offer:id,price,description',
                'user.country:id,code,country_code,unit,unit_en',
                'provider.country:id,code,country_code,unit,unit_en',
                'product',
                'coupon',
                'product_payment',
                'order_fees',
                'order_payment_option.payment_option',
                'log_providers.provider',
                'maintenance_request_order',
                'maintenance_request_order.city.city.country',
                'maintenance_request_order.street.street',
                'maintenance_request_order.maintenance_request_order_coupon',
                'maintenance_request_order.maintenance_request_order_coupon.coupon',
                'maintenance_request_order.maintenance_type.maintenance_request',
                'maintenance_request_order.maintenance_type.maintenance_request.service:id,name,name_en',
                'maintenance_request_order.maintenance_type.maintenance_request.brand:id,name',
                'maintenance_request_order.maintenance_type.maintenance_request.model:id,name',
                'maintenance_request_order.color:id,name',
                'maintenance_request_order.maintenance_type.maintenance_request.issue:id,name',
                )
            ->get()
            ->groupBy('date');

            // $orders_day_statistic   = Order::selectRaw(
            //     'DATE_FORMAT('. ($isCanceled? 'created_at' : 'updated_at') .', \'%Y-%m-%d\') AS date,'.
            //     'COUNT(id) AS sum,'.
            //     'COUNT(if(status=\'COMPLETED\', 1, NULL)) as COMPLETED,'.
            //     'COUNT(if(status=\'CANCELED\', 1, NULL)) as CANCELED,'.
            //     'COUNT(if(status=\'PENDING\'||status=\'WAITING\'||status=\'ONE_SIDED_CANCELED\', 1, NULL)) as PENDING'
            // )->groupBy('date')
            // ->orderBy('date', 'DESC')
            // ->get();

            $ods_created_at   = Order::selectRaw( // isCanceled
                'DATE_FORMAT( created_at, \'%Y-%m-%d\') AS date,'.
                'COUNT(id) AS sum,'.
                'COUNT(if(status=\'COMPLETED\', 1, NULL)) as COMPLETED,'.
                'COUNT(if(status=\'CANCELED\', 1, NULL)) as CANCELED,'.
                'COUNT(if(status=\'PENDING\'||status=\'WAITING\'||status=\'ONE_SIDED_CANCELED\', 1, NULL)) as PENDING'
            )->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get();

            $ods_updated_at   = Order::selectRaw(
                'DATE_FORMAT(updated_at, \'%Y-%m-%d\') AS date,'.
                'COUNT(id) AS sum,'.
                'COUNT(if(status=\'COMPLETED\', 1, NULL)) as COMPLETED,'.
                'COUNT(if(status=\'CANCELED\', 1, NULL)) as CANCELED,'.
                'COUNT(if(status=\'PENDING\'||status=\'WAITING\'||status=\'ONE_SIDED_CANCELED\', 1, NULL)) as PENDING'
            )->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get();

            $orders_day = $orders_day->map(function ($items, $key) use ($request, $ods_created_at, $ods_updated_at ) {
                $ods_ca = $ods_created_at->where('date', $key)->first();
                $ods_ua = $ods_updated_at->where('date', $key)->first();
                return  array_merge([
                    'date' => $key,
                    // "sum" => $ods->sum,
                    "COMPLETED" => optional($ods_ua)->COMPLETED ?? 0,
                    "CANCELED" => optional($ods_ca)->CANCELED ?? 0,
                    "PENDING" => optional($ods_ua)->PENDING ?? 0,
                ],
                [
                    'orders' => OrderResource::collection($items, $request)
                ]);
            });

            $orders_day = $orders_day->values();

            $data = [
                // 'orders_day_'  => $orders_day_statistic->where('date', '2023-05-09')->first(),
                'COMPLETED'  => $counters->COMPLETED,
                'CANCELED'   => $counters->CANCELED,
                'PENDING'    => $counters->PENDING,
                'orders_day' => $orders_day,
            ];

        return response()->data($data, sizeof($orders_day));
    }


    public function allOrders_(Request $request) // allOrdersImprove
    {
        $status      =  strtoupper($request->status);
        $isCanceled = $status  == 'CANCELED';
        $status  == 'PENDING' ? $status = ['PENDING', 'WAITING', 'ONE_SIDED_CANCELED'] : $status = [$status];
        $counters    = Order::selectRaw('COUNT(if(status=\'COMPLETED\', 1, NULL)) as COMPLETED,COUNT(if(status=\'CANCELED\', 1, NULL)) as CANCELED,COUNT(if(status=\'PENDING\'||status=\'WAITING\'||status=\'ONE_SIDED_CANCELED\', 1, NULL)) as PENDING')->first();

        $orders_day = Order::selectRaw(
            'DATE_FORMAT('. ($isCanceled? 'created_at' : 'updated_at') .', \'%Y-%m-%d\') AS date,COUNT(id) AS sum,COUNT(if(status=\'COMPLETED\', 1, NULL)) as COMPLETED,COUNT(if(status=\'CANCELED\', 1, NULL)) as CANCELED,COUNT(if(status=\'PENDING\'||status=\'WAITING\'||status=\'ONE_SIDED_CANCELED\', 1, NULL)) as PENDING, orders.*'
        )
            ->whereIn('status', $status)
            ->groupBy($isCanceled? 'created_at' : 'updated_at')
            ->orderBy($isCanceled? 'created_at' : 'updated_at', 'desc')
            ->with('provider_service',
                'provider_service.service_full',
                'provider_service.service_category',
                'provider_service.service_subcategories',
                'provider_service.service_sub2',
                'provider_service.service_sub3',
                'provider_service.service_sub4',
                'provider:id,username,number_phone,country_id,deleted_at',
                'user:id,username,number_phone,country_id,deleted_at',
                'offer:id,price,description',
                'user.country:id,code,country_code,unit,unit_en',
                'provider.country:id,code,country_code,unit,unit_en',
                'product',
                'product_payment',
                'order_fees',
                'order_payment_option.payment_option',
                'log_providers.provider',
                'maintenance_request_order',
                'maintenance_request_order.city.city.country',
                'maintenance_request_order.street.street',
                'maintenance_request_order.maintenance_request_order_coupon',
                'maintenance_request_order.maintenance_request_order_coupon.coupon',
                'maintenance_request_order.maintenance_type.maintenance_request',
                'maintenance_request_order.maintenance_type.maintenance_request.service:id,name,name_en',
                'maintenance_request_order.maintenance_type.maintenance_request.brand:id,name',
                'maintenance_request_order.maintenance_type.maintenance_request.model:id,name',
                'maintenance_request_order.color:id,name',
                'maintenance_request_order.maintenance_type.maintenance_request.issue:id,name',
                )
            ->get()
            ->groupBy('date');

            $orders_day_statistic   = Order::selectRaw(
                'DATE_FORMAT('. ($isCanceled? 'created_at' : 'updated_at') .', \'%Y-%m-%d\') AS date,COUNT(id) AS sum,COUNT(if(status=\'COMPLETED\', 1, NULL)) as COMPLETED,COUNT(if(status=\'CANCELED\', 1, NULL)) as CANCELED,COUNT(if(status=\'PENDING\'||status=\'WAITING\'||status=\'ONE_SIDED_CANCELED\', 1, NULL)) as PENDING'
            )->groupBy('date')->orderBy('date', 'DESC')->get();

            $orders_day = $orders_day->map(function ($items, $key) use ($request, $orders_day_statistic ) {
                $ods = $orders_day_statistic->where('date', $key)->first();
                return  array_merge([
                    'date' => $key,
                    "sum" => $ods->sum,
                    "COMPLETED" => $ods->COMPLETED,
                    "CANCELED" => $ods->CANCELED,
                    "PENDING" => $ods->PENDING,
                ],
                [
                    'orders' => OrderResource::collection($items, $request)
                ]);
            });

            $orders_day = $orders_day->values();

            $data = [
                //'orders_day_'  => $orders_day_statistic->where('date', '2023-05-09')->first(),
                'COMPLETED'  => $counters->COMPLETED,
                'CANCELED'   => $counters->CANCELED,
                'PENDING'    => $counters->PENDING,
                'orders_day' => $orders_day,
            ];

        return response()->data($data, sizeof($orders_day));
    }

    public function createOrderProduct(Request  $request)
    {

        $fields   = $request->all();

        $user_id  = isset($fields['user_id']) ? $request->user_id : auth()->user()->id;

        $product  = Product::find($request->product_id);
        $provider = User::find($product->user_id);
        $user     = User::find($user_id);

        $price = $product->is_offer ? $product->offer_price : $product->price;

        $commission_row   =   ProviderCommission::where('provider_id', $provider->id)->where('is_product', 1)->first();

        if ($commission_row)
            $commission       =  $commission_row->percentage == 1 ? ($price * $commission_row->commission / 100) : $commission_row->commission;
        else
            $commission       =  Setting::get('default_commission_product')[0];


        $product_payment = ProductPayment::where('product_id', $product->id)->where('user_id', $user->id)->where('is_paid', '1')->first();


        $order                =  Order::create([
            'user_id'              =>  $user->id,
            'provider_id'          =>  $provider->id,
            'product_id'           =>  $product->id,
            'product_payment_id'   =>  $product_payment->id ?? NULL,
            'price'                =>  $price,
            'commission'           =>  $commission,
            'address'              =>  $request->address,
            'other_phone'          =>  convertArabicNumber($request->other_phone),
        ]);

        // Create the fee for the order
        $order->saveFees($request->selected_payment_method);



        $title_notification  = ' المنتج: ' . $product->name . '.';
        //  user
        $notification         =  Notification::create([
            'user_id'              =>  $user->id,
            'order_id'             =>  $order->id,
            'icon'                 =>  'bell_outline_mco',
            'title'                =>  'تم طلب من ' . $provider->username . $title_notification,
            'message'              =>  '',
        ]);
        $device_token         =   $user->device_token;
        if ($device_token) {
            $fcm              =    new FCM();
            $title            =    $notification->title;
            $fcm->to($device_token)->message('', $title)->data('', 'order', '', $title, 'Notifications')->send();
        }
        // provider
        $notification         =  Notification::create([
            'user_id'              => $provider->id,
            'order_id'             => $order->id,
            'icon'                 => 'bell_outline_mco',
            'title'                => 'طلب ' . $user->username . $title_notification,
            'message'              => '',
        ]);
        $device_token         =    $provider->device_token;
        if ($device_token) {
            $fcm              =    new FCM();
            $title            =    $notification->title;
            $fcm->to($device_token)->message('', $title)->data('', 'order', '', $title, 'Notifications')->send();
        }
        // monitor
        // if ($device_token) {

            $fcm                     =      new FCM();

            $un                      =      $user->username;

            $observers_token  =   User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();

            foreach ($observers_token as $token)
                $fcm->to($token)->message('تم طلب' . $title_notification, $un . '  =>  ' . $provider->username)->data($user->id, 'info',  $title, $un . '  =>  ' . $provider->username, 'LiveChat')->send();
        // }


        $data    = ['order'  => 'created', 'o' => $order ];
        $message =  'order product was created successfully';

        return response()->data($data, $product_payment);
    }

    public function changeProviderOrder(Request  $request){
        OrdersProvidersLog::create([
            'order_id'    => $request->order_id,
            'provider_id' => $request->old_provider_id,
        ]);
        Order::where('id', $request->order_id)->update(['provider_id'   => $request->new_provider_id]);
        return response()->data('done');
    }


    public function updateToQuickOffer(Request  $request){
        $this->addToLogOrder($request);

        $fields   = $request->all();
        $user     = User::find($fields['user_id']);
        $provider = User::find($fields['new_provider_id']);
        $quick_offer     = QuickOffers::where('id', $fields['quick_offer_id'])->first();
        $commission_row  = ProviderCommission::where('provider_id', $provider->id)->first();
        if ($commission_row)
            $commission       =  $commission_row->percentage == 1 ? ($quick_offer->price * $commission_row->commission / 100) : $commission_row->commission;
        else
            $commission       =  Setting::get('default_commission')[0];

        $title_notification  = ' العرض: ' . $quick_offer->body . '.';

        $offer     =  Offer::create([
            'provider_id'         =>    $provider->id,
            'provider_service_id' =>    $fields['new_provider_service_id'],
            'description'         =>    $quick_offer->body,
            'target'              =>    'DIRECT_QUICK_OFFER',
            'price'               =>    $quick_offer->price,
        ]);
        $order                =  Order::where('id', $fields['order_id'])->update([
            'provider_id'          =>  $provider->id,
            'offer_id'             =>  $offer->id,
            'provider_service_id'  =>  $fields['new_provider_service_id'],
            'price'                =>  $quick_offer->price,
            'commission'           =>  $commission,
        ]);

        $order = Order::where('id', $fields['order_id'])->with(
                    'provider_service',
                    'provider:id,username,number_phone,country_id,deleted_at',
                    'user:id,username,number_phone,country_id,deleted_at',
                    'offer:id,price,description',
                    'provider.country:id,code,country_code,unit,unit_en',
                    'product',
                    'log_providers.provider'
                )->get();

        if ($order[0]->provider_service->title == NULL)
            $order[0]->provider_service->title =  get_title(6, $order[0]->provider_service)->name;

        $notification         =  Notification::create([
            'user_id'              => $provider->id,
            'order_id'             => $order[0]->id,
            'icon'                 => 'bell_outline_mco',
            'title'                => 'طلب ' . $user->username . $title_notification,
            'message'              => '',
        ]);
        $device_token         =    $provider->device_token;
        if ($device_token) {
            $fcm              =    new FCM();
            $title            =    $notification->title;
            $fcm->to($device_token)->message('', $title)->data('', 'order', '', $title, 'Notifications')->send();
        }

        Notification::where('user_id', $fields['old_provider_id'])->where('order_id', $fields['order_id'])->delete();
        Notification::where('user_id', $fields['user_id'        ])->where('order_id', $fields['order_id'])->delete();

        $notification         =  Notification::create([
            'user_id'              =>  $user->id,
            'order_id'             =>  $order[0]->id,
            'icon'                 =>  'bell_outline_mco',
            'title'                =>  'تم طلب من ' . $provider->username . $title_notification,
            'message'              =>  '',
        ]);

        $order      =  $order->map(function ($order) use ($request) {

            $description       =   $order->product_id? (string_value(451, $request) . ': ' . optional($order->product)->description ) : ( string_value(257, $request). ': ' . optional($order->offer)->description);
            $description_en    =   $order->product_id? string_value(451, $request, true). ': ' . optional($order->product)->description :  string_value(257, $request, true). ': ' . optional($order->offer)->description;

            $order_item =  [
                "id"                => $order->id,
                "user_id"           => $order->user_id,
                "provider_id"       => $order->provider_id,
                "offer_id"          => $order->offer_id,
                "service_id"        => $order->provider_service->id,
                "status"            => $order->status,
                "created_at"        => Change_Format($order->created_at),
                "updated_at"        => Change_Format($order->updated_at),
                "service_icon"      => ($order->product_id? !is_array(optional($order->product)->images)? (json_decode(optional($order->product)->images)[0] ?? default_image()) : $order->product->images : $order->provider_service->thumbnail) ?? default_image(),
                "service_name"      => ($order->product_id? optional($order->product)->name : ($order->provider_service->title === Null ? get_title(6, $order->provider_service)->name : $order->provider_service->title)),
                "service_name_en"   => ($order->product_id? optional($order->product)->name_en : ($order->provider_service->title === Null ? get_title(6, $order->provider_service)->name : $order->provider_service->title)),
                'provider_name'     => $order->provider->username . ($order->provider->deleted_at? ' (حساب محذوف)' : ''),
                'deleted_at'        => $order->provider->deleted_at,
                'user_name'         => optional($order->user)->username.  ($order->user->deleted_at? ' (حساب محذوف)' : ''),
                'phone_provider'    => $order->provider->country->country_code . $order->provider->number_phone,
                'phone_user'        => optional(optional($order->user)->country)->country_code . optional($order->user)->number_phone,
                "price"             => $order->price,
                "commission"        => $order->commission,
                "unit"              => $request->header("x-user-localization") == 'ar,SA' ? $order->provider->country->unit : $order->provider->country->unit_en,
                "description"       => $description,
                "description_en"    => $description_en,
                "service_target"    => optional($order->provider_service->service_full)->target,
                "log"               => $order->log_providers,

            ];
            if ($order->status == 'CANCELED' || $order->status == 'ONE_SIDED_CANCELED') {
                $order_item += ['who_canceled'      => $order->canceled_by == $order->user_id ? 'user' : ($order->canceled_by == $order->provider_id ? 'provider' : ($order->canceled_by != NULL ?  'admin' : NULL))];
                $order_item += ['canceled_reason'   => $order->canceled_reason];
            }
            return $order_item;
        })[0];

        $message =  'order was updated successfully';

        return response()->data($order, $message);
    }

    public function updateToProviderOffer(Request  $request){
        $this->addToLogOrder($request);
        $this->validate($request, rules('orders.create'));
        $fields   = $request->all();
        $offer    = Offer::where('id', $fields['offer_id'])->with('provider', 'provider_service')->firstOrFail();
        $provider = User::find($offer->provider_id);
        $user     = User::find($fields['user_id']);
        $commission_row   =   ProviderCommission::where('provider_id', $offer->provider_id)->first();

        if ($commission_row)
            $commission       =  $commission_row->percentage == 1 ? ($offer->price * $commission_row->commission / 100) : $commission_row->commission;
        else
            $commission       =  Setting::get('default_commission')[0];

        $order                =  Order::where('id', $fields['order_id'])->update([
            'provider_id'          =>  $offer->provider_id,
            'offer_id'             =>  $offer->id,
            'provider_service_id'  =>  $offer->provider_service_id,
            'price'                =>  $offer->price,
            'commission'           =>  $commission,
        ])->with('provider_service:id,title', 'user:id,username')->first();

        $notification         =  Notification::create([
            'user_id'              => $provider->id,
            'order_id'             => $order->id,
            'icon'                 => 'bell_outline_mco',
            'title'                => 'طلب ' . $user->username . ' العرض: ' . $offer->description . '.',
            'message'              => '',
        ]);
        $device_token         =    $provider->device_token;
        $title            =    $notification->title;
        if ($device_token) {
            $fcm              =    new FCM();            
            $fcm->to($device_token)->message('', $title)->data('', 'info', '', $title, 'Notifications')->send();
        }
        Notification::where('user_id', $fields['old_provider_id'])->where('order_id', $fields['order_id'])->delete();
        Notification::where('user_id', $fields['user_id'        ])->where('order_id', $fields['order_id'])->delete();

        $notification         =  Notification::create([
            'user_id'              =>  $user->id,
            'order_id'             =>  $order->id,
            'icon'                 =>  'bell_outline_mco',
            'title'                =>  'تم طلب من ' . $provider->username . $title,
            'message'              =>  '',
        ]);

        $data    = ['status'  => 'ACCEPTED', 'offer' => $offer];
        $message =  'order was updated successfully';

        return response()->data($data, $message);
    }

    public function updateToDirectOrder(Request  $request){
        $this->addToLogOrder($request);
        $fields   = $request->all();
        $user     = User::find($fields['user_id']);
        $provider = User::find($fields['new_provider_id']);

        $offer     =  Offer::create([
            'provider_id'         =>    $provider->id,
            'provider_service_id' =>    $fields['new_provider_service_id'],
            'description'         =>    'طلب مباشر',
            'target'              =>    'DIRECT',
            'price'               =>    0,
        ]);

        $order                =  Order::where('id', $fields['order_id'])->update([
            'provider_id'          =>  $provider->id,
            'offer_id'             =>  $offer->id,
            'provider_service_id'  =>  $fields['new_provider_service_id'],
            'price'                =>  0,
            'commission'           =>  0,
        ]);

        $order = Order::where('id', $fields['order_id'])->with(
                    'provider_service',
                    'provider:id,username,number_phone,country_id,deleted_at',
                    'user:id,username,number_phone,country_id,deleted_at',
                    'offer:id,price,description',
                    'provider.country:id,code,country_code,unit,unit_en',
                    'product',
                    'log_providers.provider'
                )->get();

        if ($order[0]->provider_service->title == NULL)
            $order[0]->provider_service->title =  get_title(6, $order[0]->provider_service)->name;
        $title_notification  = ' الخدمة: ' . $order[0]->provider_service->title . '.';

        $notification         =  Notification::create([
            'user_id'              => $provider->id,
            'order_id'             => $order[0]->id,
            'icon'                 => 'bell_outline_mco',
            'title'                => 'طلب ' . $user->username . $title_notification,
            'message'              => '',
        ]);
        $device_token         =    $provider->device_token;
        if ($device_token) {
            $fcm              =    new FCM();
            $title            =    $notification->title;
            $fcm->to($device_token)->message('', $title)->data('', 'order', '', $title, 'Notifications')->send();
        }

        Notification::where('user_id', $fields['old_provider_id'])->where('order_id', $fields['order_id'])->delete();
        Notification::where('user_id', $fields['user_id'        ])->where('order_id', $fields['order_id'])->delete();

        $notification         =  Notification::create([
            'user_id'              =>  $user->id,
            'order_id'             =>  $order[0]->id,
            'icon'                 =>  'bell_outline_mco',
            'title'                =>  'تم طلب من ' . $provider->username . $title_notification,
            'message'              =>  '',
        ]);

        $order      =  $order->map(function ($order) use ($request) {

            $description       =   $order->product_id? (string_value(451, $request) . ': ' . optional($order->product)->description ) : ( string_value(257, $request). ': ' . optional($order->offer)->description);
            $description_en    =   $order->product_id? string_value(451, $request, true). ': ' . optional($order->product)->description :  string_value(257, $request, true). ': ' . optional($order->offer)->description;

            $order_item =  [
                "id"                => $order->id,
                "user_id"           => $order->user_id,
                "provider_id"       => $order->provider_id,
                "offer_id"          => $order->offer_id,
                "service_id"        => $order->provider_service->id,
                "status"            => $order->status,
                "created_at"        => Change_Format($order->created_at),
                "updated_at"        => Change_Format($order->updated_at),
                "service_icon"      => ($order->product_id? !is_array(optional($order->product)->images)? (json_decode(optional($order->product)->images)[0] ?? default_image()) : $order->product->images : $order->provider_service->thumbnail) ?? default_image(),
                "service_name"      => ($order->product_id? optional($order->product)->name : ($order->provider_service->title === Null ? get_title(6, $order->provider_service)->name : $order->provider_service->title)),
                "service_name_en"   => ($order->product_id? optional($order->product)->name_en : ($order->provider_service->title === Null ? get_title(6, $order->provider_service)->name : $order->provider_service->title)),
                'provider_name'     => $order->provider->username . ($order->provider->deleted_at? ' (حساب محذوف)' : ''),
                'deleted_at'        => $order->provider->deleted_at,
                'user_name'         => optional($order->user)->username.  ($order->user->deleted_at? ' (حساب محذوف)' : ''),
                'phone_provider'    => $order->provider->country->country_code . $order->provider->number_phone,
                'phone_user'        => optional(optional($order->user)->country)->country_code . optional($order->user)->number_phone,
                "price"             => $order->price,
                "commission"        => $order->commission,
                "unit"              => $request->header("x-user-localization") == 'ar,SA' ? $order->provider->country->unit : $order->provider->country->unit_en,
                "description"       => $description,
                "description_en"    => $description_en,
                "service_target"    => optional($order->provider_service->service_full)->target,
                "log"               => $order->log_providers,

            ];
            if ($order->status == 'CANCELED' || $order->status == 'ONE_SIDED_CANCELED') {
                $order_item += ['who_canceled'      => $order->canceled_by == $order->user_id ? 'user' : ($order->canceled_by == $order->provider_id ? 'provider' : ($order->canceled_by != NULL ?  'admin' : NULL))];
                $order_item += ['canceled_reason'   => $order->canceled_reason];
            }
            return $order_item;
        })[0];


        $message =  'order was updated successfully';

        return response()->data($order, $message);
    }

    public function addToLogOrder(Request  $request){
        OrdersProvidersLog::create([
            'order_id'             => $request->order_id,
            'provider_id'          => $request->old_provider_id,
            'provider_service_id'  => $request->old_provider_service_id ,
            'offer_id'             => $request->old_offer_id,
            'price'                => $request->old_price,
            'commission'           => $request->old_commission,
        ]);
    }

}
