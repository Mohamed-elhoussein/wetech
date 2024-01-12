<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Models\Order;
use App\Models\PayMethodes;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\FCM;
use App\Models\Notification;

class UserApiController extends Controller
{
    public  function orders($user_id)
    {
        $user        =  User::where('id', $user_id)->with('user_orders')->firstOrFail();

        $orders      =  $user->user_orders;

        $data        =  $orders;
        return response()->data($data);
    }

    public function convertations(Request $request)
    {
        $user_id  = auth()->user()->id;
        $chatList = Chat::where('user_id', $user_id)
            ->userChat()
            ->orderBy('created_at', 'desc')
            ->get();


        $chatList = $chatList->map(function ($item) use ($request){
            $image  = $item->provider->role == 'chat_review'
                    ? get_logo($request)
                    : ($item->provider->avatar ? url('') . $item->provider->avatar : NULL);
            return  [
                "user_id"      => $item->user_id,
                "engineer_id"  => $item->provider_id,
                'user'         => [
                    "id"            => $item->provider->id,
                    "email"         => $item->provider->email,
                    "name"          => $item->provider->role == 'chat_review' ? string_value(0, $request) : $item->provider->username,
                    "image"         => $image,
                    "type"          => $item->provider->role,
                    "created_at"    => change_format($item->provider->created_at),
                ],
                'count_not_seen' => Chat::where('user_id', $item->user_id)
                    ->where('send_by', $item->provider->id)
                    ->where('seen', 0)->count('id'),
                //   'message' =>$item->message,
                'last_chat_date' => change_format($item->created_at),
            ];
        });

        $data      = $chatList;
        return response()->data($data);
    }

    public function onlineStatistics(Request $request)
    {

        $user_id    =  $request->id != Null ? $request->id : auth()->id();

        $data       =  User::where('id', $user_id)->select('id', 'balance_online')->first();

        $data->pay_methods  = PayMethodes::where('online', 1)->get();

        $trans      =  Transaction::where('customer_id', $user_id)->with(['user_payement'])->orderBy('created_at', 'desc')->take(4)->get();

        $data->transaction  =  collect($trans)->map(function ($item) {
            return  [
                'id'                 => $item->id,
                'order_id'           => $item->order_id ?: 0,
                'user_payment_id'    => $item->user_payement->id ?? 0,
                'type'               => $item->type,
                'amount'             => $item->amount,
                'commission'         => ($item->user_payment_id != Null) ? ($item->user_payement->fee ?? 0) : ($item->order->commission ?? 0),
                'created_at'         => Change_Format($item->created_at),
                'title'              => $item->order_id != Null ?
                    ($item->order->provider_service->title === Null ?
                        get_title(6,  $item->order->provider_service)->name :
                        $item->order->provider_service->title) : ' تسديد العمولات',
                'service_target'     => optional(optional(optional($item->order)->provider_service)->service_full)->target,
                'is_usd'             => $item->is_usd,

            ];
        });


        return response()->data($data);
    }

    public function allTransactions(Request $request)
    {

        $user_id   =  $request->id != Null ? $request->id : auth()->id();

        $trans         =  Transaction::where('customer_id', $user_id)->with('order:id,provider_service_id,commission', 'order.provider_service', 'user_payement')->orderBy('created_at', 'desc')->get();
        $data          =  collect($trans)->map(function ($item) {
            return  [
                'id'                => $item->id,
                'order_id'          => $item->order_id ?: 0,
                'user_payment_id'   => $item->user_payement->id ?? 0,
                'type'              => $item->type,
                'amount'            => $item->amount,
                'commission'        => ($item->user_payment_id != Null) ? ($item->user_payement->fee ?? 0) : ($item->order->commission ?? 0),
                'created_at'        => Change_Format($item->created_at),
                'title'             => $item->order_id != Null
                                    ? ( $item->order->provider_service->title === Null
                                        ? get_title(6,  $item->order->provider_service)->name
                                        : $item->order->provider_service->title)
                                    : ' تسديد العمولات',
                'service_target' => optional(optional(optional($item->order)->provider_service)->service_full)->target,
                'is_usd'            => $item->is_usd,
            ];
        });


        return response()->data($data);
    }

    public function deleteAccount() {
        $user = auth()->user();
        $user->services()->delete();
        $user->delete();
        $observers_token = User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();
        $observers_id    = User::where('role', 'chat_review')->pluck('id')->filter()->toArray();
        $fcm             = new FCM();

        $title = 'المستخدم ' . $user->username . ' قام بإقفال حسابه';

        foreach ($observers_id as $user_id)
        $notification         =  Notification::create([
            'user_id'              =>  $user_id,
            'icon'                 =>  'bell_outline_mco',
            'title'                =>  $title,
            'message'              =>  '',
        ]);

        foreach ($observers_token as $token) {
            $fcm->to($token)
                ->message($notification->message, $title)
                ->data(0, 'account_delete', '', $notification->message,  $title,  '')
                ->send();
        } 
        
        return response()->data(['success' => true, 'message' => 'deleted successfully']);
    }
}
