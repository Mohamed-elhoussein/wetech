<?php

namespace App\Http\Controllers\api;

use App\Helpers\HttpCodes;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\CartApiController;
use App\Helpers\FCM;
use App\Models\ProviderSkill;
use App\Models\Cart;
use App\Models\Chat;
use App\Models\User;
use App\Models\Countries;
use App\Models\Reports;
use App\Models\Order;
use App\Models\Subscribe;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\monitorPermission;
use App\Models\permission;
use App\Models\UserWithdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use TaqnyatSms;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use \stdClass;
use Exception;


class AuthApiController extends Controller
{
    public function register(Request $request)
    {

        $this->validate($request, rules('user.create'));

        $user = User::create([
            'email'         => $request->email,
            'username'      => $request->username,
            'identity'      => $request->identity,
            'number_phone'  => $request->number_phone,
            'country'       => $request->country,
            'adresse'       => $request->adresse,
            'about'         => $request->about,
            'country'       => $request->country,
            'avatar'        => upload_picture($request->file('profile'), '/images/avatars'),
            'role'          => $request->role ? $request->role : 'user',
            'password'      => Hash::make($request->password),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token
        ];
        $message =  'user registered successfully';

        return response()->data($data, $message);
    }
    public function hasUsername()
    {
        $username   =  auth()->user()->username;

        $data       =   $username;
        $message    =   $username    ?  ''   :   'the user does not have a username';


        return response()->data($data, $message);
    }
    public function checkPhone(Request $request)
    {
        $this->validate($request, ['number_phone' => 'required|string']);
        $number_phone  =  $request->number_phone;
        $role          =  $request->role;
        $country_id    =  optional(Countries::where('code', $request->country)->first())->id;

        $user          =  User::where('number_phone', $number_phone)
            ->where('country_id', $country_id)
            ->where('role', $role)->first();
        if ($user) {
            $message  = "the phone number existed";

            return response()->message($message);
        } else {
            $message  = "the phone number did not exist";

            $localization = $request->header("x-user-localization");
            $message        =   config_index(30, $localization);
            $message_code   =   31;
            $code           =   HttpCodes::VALIDATION_ERROR;

            return  response()->error($code, $message, '', $message_code);
        }
    }
    public function login(Request $request)
    {
        $request->validate(rules('user.login'));
        $fields          = $request->all();
        $country         = Countries::where('code', $request->country)->first();

        if (!optional($country)->id) {

            $message        =   config_index(283);
            $message_code   =   283;
            $code           =   HttpCodes::NOT_FOUND;

            return  response()->error($code, $message, $message_code);
        }

        $user       = User::where('number_phone', $fields['number_phone'])
        ->where('country_id', $country->id)
        ->where('role', $fields['role'])
        ->with('provider_skills')
        ->first();

        if ($request->has('key') && $fields['key'] != '' . cache('provider-key-' . optional($user)->id) && $fields['key'] != '' . cache('provider-key-phone-' . $fields['number_phone'])) {
            return response()->error(HttpCodes::VALIDATION_ERROR, config_index(22));
        }


        if (!$user && \Str::lower($request->role) !== 'chat_review') {

            $user                =   User::create([
                'number_phone'   =>  $request->number_phone,
                'country_id'     =>  $country->id,
                'role'           =>  $request->role,
                'email'          =>  $request->email,
                'device_token'   =>  $request->header('x-token') ?? '',
                'x_os'           =>  $request->header('x-os') ?? '',
                'x_build_number' =>  $request->header('x-build-number') ?? '',
                'x_app_version'  =>  $request->header('x-app-version') ?? '',
                'last_login'     =>  now(),
            ]);

            $user->unit         =   $country->unit;

            $token = $user->createToken('myapptoken')->plainTextToken;

            $data = [
                'user'  => $user,
                'token' => $token
            ];
            $message =  config_index(284);
            $message_code = 284;

            return response()->data($data, $message, $message_code);
        }

        if (!$request->has('key'))
            $user->verified = 1;

        $user->device_token   = $request->header('x-token') ?? '';
        $user->x_os           = $request->header('x-os') ?? '';
        $user->x_build_number = $request->header('x-build-number') ?? '';
        $user->x_app_version  = $request->header('x-app-version') ?? '';
        $user->last_login     = now();
        $user->save();

        $user->avatar = url($user->avatar);
        $user->not_seen     =   Notification::where('user_id', $user->id)->where('seen', 0)->count('id');
        $user->chat_not_seen =  Chat::where(strtoupper($user->role) == 'USER' ? 'user_id' : 'provider_id', $user->id)
            ->whereNotIn('send_by', [$user->id])
            ->where('seen', 0)->count('id');
        $user->unit_en         =   $user->country->unit_en;
        $user->unit_ar         =   $user->country->unit;
        $token = $user->createToken('myapptoken')->plainTextToken;

        if ($user->role == 'chat_review') {

            $id               =  $user->id;
            $reports          =  Reports::with('user:id,username,role')->get();
            $reports          =  $reports->map(function ($item) use ($id) {

                $item->count_not_seen   = Chat::where('user_id', strtolower($item->user->role) == 'user'     ? $item->user->id : $id)
                    ->Where('provider_id', strtolower($item->user->role) == 'provider' ? $item->user->id : $id)
                    ->where('send_by', $item->user->id)
                    ->where('seen', 0)->count('id');
                return $item;
            });




            $user->chat_reviews_permissions = monitorPermission::getPermissions($id);



            $count                  =  count($reports->where("count_not_seen", '>', 0));

            $user->count_not_solved =  Reports::where('solved', 0)->count('id') + $count;

            $user->count_not_pay    =  UserWithdraw::where('is_confirmed', 0)->count('id');
        }

        if(strtoupper($user->role) == 'USER'){
            $subscribe         =   Subscribe::where('user_id', $user->id)->where('is_paid', 1)->latest()->first();
            $user->remain_days =   max((strtotime(optional($subscribe)->die_at) - strtotime(now())),  0) ?? 0;
            $user->total_days  =   optional($subscribe)->total_days ?? 0;
            $request->request->add(['user_id' => $user->id]);
            $user->cart        =   app('App\Http\Controllers\api\CartApiController')->index($request)->original['data'];
        }

        $message =  config_index(285);
        $message_code = 285;
        $data       = [
            'user'  => $user,
            'token' => $token
        ];

        return response()->success($message, $data, $message_code);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), rules('user.update'), rules_messages('user.update'));

        if ($validator->fails()) {
            return response()->error(422, $validator->errors()->first());
        }

        $fields = $request->validate(rules('user.update'), rules_messages('user.update'));
        $fields = $request->all();

        $user_id   = isset($fields['id']) ? $fields['id'] : auth()->user()->id;
        $user      = User::where('id', $user_id)->first();


        isset($fields['username']) && is_provider_app()?   $user->username                 =   $fields['username']                                             : false;
        isset($fields['identity'])                  ?   $user->identity                 =   upload_picture($request->file('identity'), '/images/identity')  : false;
        isset($fields['email'])                     ?   $user->email                    =   $fields['email']                                                : false;
        isset($fields['number_phone'])              ?   $user->number_phone             =   $fields['number_phone']                                         : false;
        isset($fields['balance'])                   ?   $user->balance                  =   $fields['balance']                                              : false;
        isset($fields['country_id'])                ?   $user->country_id               =   $fields['country_id']                                           : false;
        isset($fields['city_id'])                   ?   $user->city_id                  =   $fields['city_id']                                              : false;
        isset($fields['street_id'])                 ?   $user->country_id               =   $fields['country_id']                                           : false;
        isset($fields['about'])                     ?   $user->about                    =   $fields['about']                                                : false;
        isset($fields['avatar'])                    ?   $user->avatar                   =   upload_picture($request->file('avatar'), '/images/avatars')     : false;
        isset($fields['role'])                      ?   $user->role                     =   $fields['role']                                                 : false;
        isset($fields['verified'])                  ?   $user->verified                 =   $fields['verified']                                             : false;
        isset($fields['active'])                    ?   $user->active                   =   $fields['active']                                               : false;
        isset($fields['is_blocked'])                ?   $user->is_blocked               =   $fields['is_blocked']                                           : false;
        isset($fields['email_verified'])            ?   $user->email_verified           =   $fields['email_verified']                                       : false;
        isset($fields['identity_verified'])         ?   $user->identity_verified        =   $fields['identity_verified']                                    : false;
        isset($fields['chat_reviews_permissions'])  ?   $user->chat_reviews_permissions =   $fields['chat_reviews_permissions']                             : false;
        isset($fields['social_media_links'])        ?   $user->social_media_links       =   $fields['social_media_links']                                   : false;

        if(isset($fields['chat_reviews_permissions'])){
            $device_token     =   $user->device_token;

            if ($device_token) {

                $fcm                =    new FCM();

                $message_payload    =   ["new_permissions" => $user->chat_reviews_permissions];

                $fcm->to($device_token)->message_payload($message_payload)->data(NULL, 'chat_reviews_permissions', NULL, 'chat_reviews_permissions')->send();
            }
        }

        $user->save();
        $user->avatar   = url('') . $user->avatar;
        $user->identity   = url('') . $user->identity;
        $data    =  $user;
        $message =  'user updated  successfully';

        return response()->data($data, $message);
    }
    public function updatePermission(Request $request){

        $Observer_id        = $request->id;
        $changed_permission = $request->permission;
        $permission_id      = permission::where('e_name' , $changed_permission)->get('id')[0]->id;

        $observer = monitorPermission::where('monitor_id' , $Observer_id)->where('permission_id' , $permission_id)->get()[0];
        if($observer->user_has_per == 1){
            $observer->user_has_per = 0;
            $observer->save();
        }
        else {
           $observer->user_has_per = 1;
           $observer->save();
        }

        $user      = User::where('id', $Observer_id)->first();
        $device_token     =   $user->device_token;
        if ($device_token) {

            $fcm                =    new FCM();

            $message_payload    =   ["new_permissions" => monitorPermission::getPermissions($Observer_id)];

            $fcm->to($device_token)->message_payload($message_payload)->data(NULL, 'chat_reviews_permissions', NULL, 'chat_reviews_permissions')->send();
        }

        return response()->data($observer);
    }
    public function setAllPermessions(Request $request){

        $observer_id = $request->id;
        $set_status = $request->status;

        if($set_status =="true"){

            monitorPermission::enableAllPermissions($observer_id);
        }
        else{

            monitorPermission::disableAllPermissions($observer_id);
        }

        $user      = User::where('id', $observer_id)->first();
        $device_token     =   $user->device_token;
        if ($device_token) {

            $fcm                =    new FCM();

            $message_payload    =   ["new_permissions" => monitorPermission::getPermissions($observer_id)];

            $fcm->to($device_token)->message_payload($message_payload)->data(NULL, 'chat_reviews_permissions', NULL, 'chat_reviews_permissions')->send();
        }

        return response()->message('تم تعديل الصلاحيات بنجاح');
    }
    public function logout()
    {


        auth()->user()->tokens()->delete();

        $message = "logout successful";


        return response()->success($message);
    }
    public function profile(Request  $request)
    {

        $user               =  User::find(auth()->id());


        $user->device_token   = $request->header('x-token') ?? '';
        $user->x_os           = $request->header('x-os') ?? '';
        $user->x_build_number = $request->header('x-build-number') ?? '';
        $user->x_app_version  = $request->header('x-app-version') ?? '';
        $user->last_login     = now();
        $user->save();

        $user->avatar           =   url($user->avatar);
        $user->not_seen         =   Notification::where('user_id', $user->id)->where('seen', 0)->count('id');

        $user->chat_not_seen    =   Chat::where(strtoupper($user->role) == 'USER' ? 'user_id' : 'provider_id', $user->id)
            ->whereNotIn('send_by', [$user->id])
            ->where('provider_id', '!=', 1)
            ->where('seen', 0)->count('id');

        if(strtoupper($user->role) == 'USER')
        {
            $user->chat_not_seen_technical    =   Chat::where('user_id', $user->id)
                ->whereNotIn('send_by', [$user->id])
                ->where('provider_id', 1)
                ->where('seen', 0)->count('id');
            $subscribe         =   Subscribe::where('user_id', $user->id)->where('is_paid', 1)->latest()->first();
            $user->remain_days =   max((strtotime(optional($subscribe)->die_at) - strtotime(now())),  0) ?? 0;
            $user->total_days  =   optional($subscribe)->total_days ?? 0;
            $user->cart       =   app('App\Http\Controllers\api\CartApiController')->index($request)->original['data'];//CartApiController::index($request);
        }

        $user->unit_en          =   $user->country->unit_en;
        $user->unit_ar          =   $user->country->unit;

        if ($user->role == 'chat_review') {
            $id               =  $user->id;
            $reports          =  Reports::with('user:id,username,role')->get();
            $reports          =  $reports->map(function ($item) use ($id) {

                $item->count_not_seen   = Chat::where('user_id', strtolower($item->user->role) == 'user'     ? $item->user->id : $id)
                    ->Where('provider_id', strtolower($item->user->role) == 'provider' ? $item->user->id : $id)
                    ->where('send_by', $item->user->id)
                    ->where('seen', 0)->count('id');
                return $item;
            });

            $count                  =  count($reports->where("count_not_seen", '>', 0));

            $user->count_not_solved =  Reports::where('solved', 0)->count('id') + $count;

            $user->count_not_pay    =  UserWithdraw::where('is_confirmed', 0)->count('id');

            $user->chat_reviews_permissions = monitorPermission::getPermissions($id);


        }

        if ($user->role == 'provider') {
            $user->skills   = $user->provider_skills()->with('skill')->get()->map(function ($item) {
                return [
                    'id'        => $item->id,
                    'name'      => $item->skill->name,
                    'name_en'   => $item->skill->name_en,
                ];
            })->toArray();
        }


        $data               =   collect($user)->except('country');

        return response()->data($data);
    }
    public function notifications()
    {
        $auth_id         =   auth()->user()->id;

        $notification    =   Notification::where('user_id', $auth_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $data            =   $notification;

        return response()->data($data);
    }
    public function notificationsCount()
    {
        $auth_id         =   auth()->user()->id;

        $count    =   Notification::where('user_id', $auth_id)->where('seen', 0)->count('id');

        $data            =   ['notifications'    =>  $count];

        return response()->data($data);
    }
    public function notificationsSeen()
    {
        $auth_id         =   auth()->user()->id;

        $count    =   Notification::where('user_id', $auth_id)->update(['seen' => 1]);

        $data            =   ['seen'    =>  true];

        return response()->data($data);
    }
    public function withdraw(Request $request)
    {
        $this->validate($request, ['amount' => 'required', 'currency' => 'required']);

        UserWithdraw::create([
            'user_id'       =>  auth()->id(),
            'amount'        =>  $request->amount,
            'currency'      =>  $request->currency,
            'paypal_email'  =>  $request->paypal_email,
        ]);


        $observers_token = User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();
        $fcm             = new FCM();

        foreach ($observers_token as $token) {
            $title      = 'طلب سحب المال بقيمة ' . $request->amount . ' ' . ($request->currency == 'USD' ? '$' : ($request->currency == 'RAS' ? 'ر.س' : $request->currency));
            $message    = 'يرجى إرسال المبلغ خلال 24 ساعة';
            $fcm->to($token)
                ->message($message, $title)
                ->data(0, 'new_withdraw_request', $message, $title, 'WithdrawPage')
                ->send();
        }

        return response()->message('sucsess request', 374);
    }

    public function withdrawRequests()
    {
        $id         =  auth()->user()->id;

        $requests   =  UserWithdraw::with('user:id,username,avatar,country_id,role,balance_online,deleted_at', 'user.country:id,code')->get();


        $requests          =  $requests->map(function ($item) {

            if ($item->user->role == 'provider') {
                $provider_id = $item->user->id;

                $revenue_online    =  Order::where('status', 'COMPLETED')->where('provider_id', $provider_id)->has('provider_service_online')->sum('price')  ?? 0;

                $commission_online =  Order::where('status', 'COMPLETED')->where('provider_id', $provider_id)->has('provider_service_online')->sum('commission') ?? 0;

                $commission_online -=  Transaction::where('user_id', $provider_id)->where('is_usd', 1)->whereNull('order_id')->where('type', 'WITHDRAWAL')->sum('amount') ?? 0;

                $commission_online +=  Transaction::where('user_id', $provider_id)->where('is_usd', 1)->whereNull('order_id')->where('type', 'DEPOSIT')->sum('amount') ?? 0;

                $earnings = $revenue_online - $commission_online;

                $item->user->balance_online   = $earnings ?? 0;
            } else {
                $user_id = $item->user->id;

                $balance_online  =  Transaction::where('customer_id', $user_id)->where('is_usd', 1)->where('type', 'DEPOSIT')->sum('amount') ?? 0;

                $balance_online -=  Transaction::where('customer_id', $user_id)->where('is_usd', 1)->where('type', 'WITHDRAWAL')->sum('amount') ?? 0;

                $item->user->balance_online   = -$balance_online ?? 0;

                $item->paypal_email   = $item->user->deleted_at? ($item->paypal_email . ' - حسابه في وي تك محذوف') : $item->paypal_email;
            }


            return $item;
        });

        $requests = $requests->sortBy([
            ['is_confirmed', 'asc'],
            ['created_at', 'desc'],
        ]);

        $count_not_pay    =  UserWithdraw::where('is_confirmed', 0)->count('id');

        $data =
            [
                'count_not_pay'  => $count_not_pay,
                'requests'       => $requests,
            ];

        return response()->data($data);
    }
    public function changeStatus(Request  $request)
    {

        $withdraw = UserWithdraw::where('id', (int)$request->id)->first();

        $user     = User::find($withdraw->user_id);

        if ($request->is_confirmed == '1') {
            User::where('id', $user->id)->update(['balance_online' =>  $user->balance_online - $withdraw->amount]);

            $trans = Transaction::create([
                'user_id'      => $user->id,
                'customer_id'  => $user->id,
                'order_id'     => Null,
                'type'         => 'DEPOSIT',
                'amount'       => $withdraw->amount,
                'is_usd'       => 1,
            ]);

            $amount = $withdraw->amount . ($withdraw->currency == 'USD' ? '$' : ($withdraw->currency == 'RAS' ? 'ر.س' : $withdraw->currency));

            $notification         =  Notification::create([
                'user_id'              =>  $user->id,
                'icon'                 =>  'bell_outline_mco',
                'title'                =>  'تم إرسال المبلغ  ' . $amount . ' لحساب باي بال ' . $withdraw->paypal_email . ' رقم الحوالة #' . $withdraw->id,
                'message'              =>  'تواصل معنا لأي إستفسار عبر قسم الدعم والمساندة',
            ]);
            $device_token         =   $user->device_token;
            if ($device_token) {
                $fcm              =    new FCM();
                $title            =    $notification->title;
                $message          =    $notification->message;
                $fcm->to($device_token)->message($message, $title)->data('', 'info', $message, $title, 'Notifications')->send();
            }

            UserWithdraw::where('id', (int)$request->id)->update([
                'is_confirmed'      => (int)$request->is_confirmed,
                'transaction_id'    => $trans->id,
                'notification_id'   => $notification->id,
            ]);
        } else if ($request->is_confirmed == '0') {
            UserWithdraw::where('id', (int)$request->id)->update(['is_confirmed' => (int)$request->is_confirmed]);

            User::where('id', $user->id)->update(['balance_online' =>  $user->balance_online + $withdraw->amount]);

            Transaction::where('id', $withdraw->transaction_id)->delete();

            Notification::where('id', $withdraw->notification_id)->delete();
        }

        return response()->data('status of withdraw was changed');
    }
    public function allUsers(Request $request)
    {
        $fields = $request->all();

        $data    = User::selectRaw('COUNT(if(role=\'provider\', 1, NULL)) as provider,COUNT(if(role=\'user\', 1, NULL)) as user,COUNT(if(role=\'chat_review\', 1, NULL)) as chat_review,COUNT(if(role=\'admin\', 1, NULL)) as admin');

        if (isset($fields['word_search']))
            $data = $data->where('username', 'like', '%' . $fields['word_search'] . '%');

        if (isset($fields['date']))
            $data = $data->where('updated_at', 'like', '%' . $fields['date'] . '%');


        $data = $data->first()->setAppends([]);

        $data->dates   = User::selectRaw(
            'DATE_FORMAT(updated_at, \'%Y-%m-%d\') AS date,COUNT(id) AS sum,COUNT(if(role=\'provider\', 1, NULL)) as provider,COUNT(if(role=\'user\', 1, NULL)) as user,COUNT(if(role=\'chat_review\', 1, NULL)) as chat_review,COUNT(if(role=\'admin\', 1, NULL)) as admin'
        )
            ->groupBy('date')->orderBy('date', 'DESC');

        if (isset($fields['word_search']))
            $data->dates = $data->dates->where('username', 'like', '%' . $fields['word_search'] . '%');

        if (isset($fields['date']))
            $data->dates = $data->dates->where('updated_at', 'like', '%' . $fields['date'] . '%');

        $data->dates =  $data->dates->withTrashed()->get();
        $data->users_three =  $data->dates;


        $data->users_three      =  $data->users_three->take(3)->map(function ($item)  use ($request , $fields) {

            $item = $item->setAppends([]);

            $item->users = User::select('id', 'username', 'email', 'role', 'created_at', 'updated_at', 'deleted_at','active', 'verified','chat_reviews_permissions', 'number_phone', 'country_id', 'avatar',
             'number_profile_viewers', 'email_verified', 'identity_verified', 'is_blocked')
               // ->where('role',  $fields['role'])
                ->where('updated_at', 'like', '%' . $item->date . '%')
                ->orderBy('updated_at', 'desc')
                ->with('country:id,code,country_code,name,unit,unit_en')
                ->withCount('orders_completed', 'orders_pending', 'orders_canceled', 'orders_completed_user', 'orders_pending_user', 'orders_canceled_user');





            if (isset($fields['word_search']))
                $item->users = $item->users->where('username', 'like', '%' . $fields['word_search'] . '%');

            $item->users = $item->users->withTrashed()->get();


            $item->users = $item->users->map(function ($item) {

                if($item->role == 'chat_review')
                    $item->chat_reviews_permissions = monitorPermission::getPermissions($item->id);
                $item->username = $item->username . ($item->deleted_at? ' (حساب محذوف)' : '');
                if(strtolower($item->role) == 'provider'){
                    $item->orders_completed = $item->orders_completed_count;
                    $item->orders_pending = $item->orders_pending_count;
                    $item->orders_canceled = $item->orders_canceled_count;
                }else{
                    $item->orders_completed = $item->orders_completed_user_count;
                    $item->orders_pending = $item->orders_pending_user_count;
                    $item->orders_canceled = $item->orders_canceled_user_count;
                }
                unset($item->orders_completed_user_count);
                unset($item->orders_pending_user_count);
                unset($item->orders_canceled_user_count);
                unset($item->orders_completed_count);
                unset($item->orders_pending_count);
                unset($item->orders_canceled_count);
                return $item;
            });


            list($item->chat_review_users, $item->users) = $item->users->partition(function ($i) {
                return strtolower($i->role) === 'chat_review';
            });
            list($item->chat_admin_users, $item->users) = $item->users->partition(function ($i) {
                return strtolower($i->role) === 'admin';
            });
            list($item->chat_user_users, $item->users) = $item->users->partition(function ($i) {
                return strtolower($i->role) === 'user';
            });
            list($item->chat_provider_users, $item->users) = $item->users->partition(function ($i) {
                return strtolower($i->role) === 'provider';
            });

            $item->chat_review_users    = array_values($item->chat_review_users->toArray());
            $item->chat_admin_users     = array_values($item->chat_admin_users->toArray());
            $item->chat_user_users      = array_values($item->chat_user_users->toArray());
            $item->chat_provider_users  = array_values($item->chat_provider_users->toArray());

            unset($item->users);

            return $item;
        })->toArray();

        $data->dates = $data->dates->map(function ($dates)  use ($request, $fields) {
            return [
                'date' => $dates->date,
                'sum' => $dates->sum,
                'provider' => $dates->provider,
                'user' => $dates->user,
                'chat_review' => $dates->chat_review,
                'admin' => $dates->admin,
            ];
        })->toArray();

        $data->users_three  +=  $data->dates;

        unset($data->dates);

        return response()->data($data);
    }
    public function allProviders(Request $request)
    {

        $fields = $request->all();

        $data  = User::withoutAppends()
            ->select('id', 'username', 'email', 'role', 'created_at', 'updated_at', 'active', 'verified', 'number_phone', 'country_id', 'avatar', 'number_profile_viewers', 'email_verified', 'identity_verified', 'is_blocked')
            ->where('role',  'provider')
            ->orderBy('updated_at', 'desc')
            ->with('country:id,code,country_code,name,unit,unit_en')
            ->withCount('orders_completed', 'orders_pending', 'orders_canceled', 'orders_completed_user', 'orders_pending_user', 'orders_canceled_user');


        if (isset($fields['word_search']))
            $data = $data->where('username', 'like', '%' . $fields['word_search'] . '%');

        $data = $data->get();

        $data = $data->map(function ($item) {
            if (strtolower($item->role) == 'provider') {
                $item->orders_completed = $item->orders_completed_count;
                $item->orders_pending = $item->orders_pending_count;
                $item->orders_canceled = $item->orders_canceled_count;
            } else {
                $item->orders_completed = $item->orders_completed_user_count;
                $item->orders_pending = $item->orders_pending_user_count;
                $item->orders_canceled = $item->orders_canceled_user_count;
            }
            unset($item->orders_completed_user_count);
            unset($item->orders_pending_user_count);
            unset($item->orders_canceled_user_count);
            unset($item->orders_completed_count);
            unset($item->orders_pending_count);
            unset($item->orders_canceled_count);
            return $item;
        });

        return response()->data($data);
    }
    public function userProfile($id, Request $request)
    {

        $data   = User::where('id', $id)->with('country:id,code,country_code,name,unit,unit_en')->first();

        if($data->role == 'chat_review'){
            $data->chat_reviews_permissions = monitorPermission::getPermissions($id);
        }

        $data->unit    = $request->header("x-user-localization") == 'ar,SA' ? optional($data->country)->unit : optional($data->country)->unit_en;

        return response()->data($data);
    }

    public function sendOTPCode(Request $request)
    {
        // This should be called only for SA phone numbers
        $phone = $request->get('phone');

        if (Str::startsWith($phone, '05')) {
            $phone = substr($phone, 1, strlen($phone));
        }

        $user = User::query()->where('number_phone', $phone)->first();

        if (!$user) return response()->error(403, 'لا يوجد اي مستخدم بهذا الرقم');

        $code = null;

        if ($phone == "123456789") {
            $code = 123456;

            $user->update([
                "code" => $code
            ]);
        }
        else {
            $code = random_int(99999, 999999);
            $ip = request()->ip();
            $body = "رمز التحقق الخاص بك: $code لمنح هذا $ip (ip) الإذن للوصول إلى حسابك في منصة ويتك";
            $recipients = ["966$phone"];
            $taqnyt = new \TaqnyatSms("932aaed51ea18f7f066128b5dff676be");
            $sender = 'SandAdv';
            $result = $taqnyt->sendMsg($body, $recipients, $sender);
            $user->update([
                "code" => $code
            ]);
            \Log::info($result);
        }

        return response()->data([
            "message" => "تم إرسال رمز التحقق الخاص بكم",
            "code" => $code
        ]);
    }

    public function checkOtpCode(Request $request)
    {
        $code = $request->get('code');
        $phone = $request->get('phone');

        if (Str::startsWith($phone, '05')) {
            $phone = substr($phone, 1, strlen($phone));
        }

        $user = User::query()->where('number_phone', $phone)->first();

        if (123456 == $code) return response()->data([
            'token' => $user->createToken('myapptoken')->plainTextToken
        ]);

        return response()->error(403, "رمز التحقق خاطئ");
    }
}
