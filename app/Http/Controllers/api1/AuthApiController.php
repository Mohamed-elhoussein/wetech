<?php

namespace App\Http\Controllers\api;

use App\Helpers\HttpCodes;
use App\Http\Controllers\Controller;
use App\Helpers\FCM;
use App\Models\ProviderSkill;
use App\Models\Chat;
use App\Models\User;
use App\Models\Countries;
use App\Models\Reports;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\UserWithdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

            $count                  =  count($reports->where("count_not_seen", '>', 0));

            $user->count_not_solved =  Reports::where('solved', 0)->count('id') + $count;

            $user->count_not_pay    =  UserWithdraw::where('is_confirmed', 0)->count('id');
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

        $fields = $request->validate(rules('user.update'), rules_messages('user.update'));
        $fields = $request->all();

        $user_id   = isset($fields['id']) ? $fields['id'] : auth()->user()->id;
        $user      = User::where('id', $user_id)->first();


        isset($fields['username'])           ?   $user->username            =   $fields['username']                                             : false;
        isset($fields['identity'])           ?   $user->identity            =   upload_picture($request->file('identity'), '/images/identity')  : false;
        isset($fields['email'])              ?   $user->email               =   $fields['email']                                                : false;
        isset($fields['number_phone'])       ?   $user->number_phone        =   $fields['number_phone']                                         : false;
        isset($fields['balance'])            ?   $user->balance             =   $fields['balance']                                              : false;
        isset($fields['country_id'])         ?   $user->country_id          =   $fields['country_id']                                           : false;
        isset($fields['city_id'])            ?   $user->city_id             =   $fields['city_id']                                              : false;
        isset($fields['street_id'])          ?   $user->country_id          =   $fields['country_id']                                           : false;
        isset($fields['about'])              ?   $user->about               =   $fields['about']                                                : false;
        isset($fields['avatar'])             ?   $user->avatar              =   upload_picture($request->file('avatar'), '/images/avatars')     : false;
        isset($fields['role'])               ?   $user->role                =   $fields['role']                                                 : false;
        isset($fields['verified'])           ?   $user->verified            =   $fields['verified']                                             : false;
        isset($fields['active'])             ?   $user->active              =   $fields['active']                                             : false;
        isset($fields['is_blocked'])         ?   $user->is_blocked          =   $fields['is_blocked']                                           : false;
        isset($fields['email_verified'])     ?   $user->email_verified      =   $fields['email_verified']                                       : false;
        isset($fields['identity_verified'])  ?   $user->identity_verified   =   $fields['identity_verified']                                    : false;
        isset($fields['social_media_links']) ?   $user->social_media_links  =   $fields['social_media_links']                                   : false;

        $user->save();
        $user->avatar   = url('') . $user->avatar;
        $user->identity   = url('') . $user->identity;
        $data    =  $user;
        $message =  'user updated  successfully';

        return response()->data($data, $message);
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
            ->where('seen', 0)->count('id');
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

        $requests   =  UserWithdraw::with('user:id,username,avatar,country_id,role,balance_online', 'user.country:id,code')->get();


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
        $data = $data->first()->setAppends([]);

        $data->users   = User::selectRaw(
            'DATE_FORMAT(updated_at, \'%Y-%m-%d\') AS date,COUNT(id) AS sum,COUNT(if(role=\'provider\', 1, NULL)) as provider,COUNT(if(role=\'user\', 1, NULL)) as user,COUNT(if(role=\'chat_review\', 1, NULL)) as chat_review,COUNT(if(role=\'admin\', 1, NULL)) as admin'
        )
            ->groupBy('date')->orderBy('date', 'DESC');

        if (isset($fields['word_search']))
            $data->users = $data->users->where('username', 'like', '%' . $fields['word_search'] . '%');
        $data->users =  $data->users->get();


        $data->users      =  $data->users->map(function ($users)  use ($request, $fields) {

            $users = $users->setAppends([]);

            $users->users = User::select('id', 'username', 'email', 'role', 'created_at', 'updated_at', 'active', 'verified', 'number_phone', 'country_id', 'avatar', 'number_profile_viewers', 'email_verified', 'identity_verified', 'is_blocked')
                ->where('role',  $fields['role'])
                ->where('updated_at', 'like', '%' . $users->date . '%')
                ->orderBy('updated_at', 'desc')
                ->with('country:id,code,country_code,name');

            if (isset($fields['word_search']))
                $users->users = $users->users->where('username', 'like', '%' . $fields['word_search'] . '%');
            $users->users = $users->users->get();

            return $users;
        });

        return response()->data($data);
    }
    public function userProfile($id, Request $request)
    {

        $data   = User::where('id', $id)->with('country:id,code,country_code,name,unit,unit_en')->first();

        $data->unit    = $request->header("x-user-localization") == 'ar,SA' ? $data->country->unit : $data->country->unit_en;

        return response()->data($data);
    }
}
