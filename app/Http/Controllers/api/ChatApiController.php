<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Helpers\FCM;
use Carbon\Carbon;
use App\Jobs\SendMessage;
use App\Models\Chat;
use App\Models\MessageReport;
use App\Models\UserWithdraw;
use App\Models\Offer;
use App\Models\Order;
use App\Models\User;
use App\Models\Reports;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\ProviderCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ChatApiController extends Controller
{
    use DispatchesJobs;

    public function convertations(Request $request)
    {
        $authenticated  =   auth()->user();
        $user_id        =   $request->user_id;
        $provider_id    =   $request->provider_id;
        $chat_with      =   $authenticated->id == $user_id    ?  $provider_id    :   $user_id;


        $balance = isset($provider_info['balance']) ? $provider_info['balance'] : 0;

        $provider_id = $authenticated->role === 'chat_review' && $authenticated->id ==  $user_id ? $authenticated->id : $provider_id ;
        $provider_info  = User::where('id', $provider_id)->select('id', 'balance', 'debt_ceiling', 'avatar', 'country_id')->first();
        $provider_info['commission']              =   ProviderCommission::where('provider_id', $provider_id)->first()->commission ?? Setting::get('default_commission')[0];
        $provider_info['commission_online']       =   ProviderCommission::where('provider_id', $provider_id)->where('is_online', 1)->first()->commission ?? Setting::get('default_commission_online')[0];
        $provider_info['percentage']              =   optional(ProviderCommission::select('percentage')->where('provider_id', $provider_id)->first())->percentage ? true : false;
        $provider_info['percentage_online']       =   optional(ProviderCommission::select('percentage')->where('provider_id', $provider_id)->where('is_online', 1)->first())->percentage ? true : false;
        $provider_info['balance']                 =   $balance + Transaction::where('user_id', $provider_id)->where('type', 'WITHDRAWAL')->sum('amount') ?? 0;
        $provider_info['not_allow_send_offer']    =   false; //  ($provider_info['balance'] <=  -$provider_info['debt_ceiling']) ??
        $provider_info['message']                 =   $provider_info['not_allow_send_offer'] ? 329 : null;


        if ($authenticated->role === 'chat_review' && $provider_id != '1') {
            $provider_info['unit_en']                 =   $provider_info->country->unit_en;
            $provider_info['unit_ar']                 =   $provider_info->country->unit;
        }

        if($authenticated->role === 'chat_review' && $provider_id != '1' && ($authenticated->id ==  $user_id || $authenticated->id == $provider_id)){
            $provider_info['avatar'] =  get_logo($request) ;
        }


        $chat_with          =  User::where('id', $chat_with)
            ->select('id', 'username', 'avatar', 'email', 'number_phone', 'created_at', 'country_id', 'role')->with('country:id,country_code')
            ->withTrashed()
            ->first();
        $chat_with->avatar        =  $chat_with->role === 'chat_review' ? get_logo($request)        : url($chat_with->avatar);
        $chat_with->username      =  $chat_with->role === 'chat_review' ? string_value(0, $request) : $chat_with->username;


        $chat               =  Chat::where('user_id', $user_id)
            ->where('provider_id', $request->provider_id)
            ->orderBy('created_at', 'asc')
            ->with('review')
            ->get();

        $chat               =  Chat::mapChatMessage($chat, $authenticated);

        $data               =  ['provider_info' => $provider_info, 'with' => $chat_with, 'convertation' => $chat];
        return response()->data($data);
    }
    public function paginate(Request $request)
    {
        $authenticated  =  auth()->user();
        $user_id        =  $request->user_id;
        $provider_id    =  $request->provider_id;


        if (!($authenticated->id == $user_id  ||   $authenticated->id == $provider_id   ||  $authenticated->role == 'admin')) {
            // return false;
            //TODO::return not allowed to get chat
        }

        $chat_with   =  $authenticated->id == $user_id    ?  $provider_id    :   $user_id;

        $chat_with   =  User::where('id', $chat_with)->get(['id', 'username', 'avatar', 'email', 'number_phone', 'created_at'])[0];
        $chat_with->avatar  =  url('') . $chat_with->avatar;
        $chat        =  Chat::where('user_id', $user_id)
            ->where('provider_id', $provider_id)
            ->orderBy('created_at', 'asc')
            ->paginate();

        $chat        =  Chat::mapChatMessage($chat, $authenticated);

        $data        =  ['with' => $chat_with, 'convertation' => $chat];
        return response()->data($data);
    }
    public function create(Request  $request)
    {

        $this->validate($request, rules('chat.create'));
        $fields = $request->all();

        $userSender = auth()->user();
        $user_id     =  $request->user_id;
        $provider_id =  $request->provider_id;

        if (!($userSender->id == $user_id  ||   $userSender->id == $provider_id   ||  $userSender->role == 'admin')) {
            // return false;
            //TODO::return not allowed to get chat
        }

        $last_data_chat = Chat::where('user_id',$user_id)->where('provider_id',$provider_id)->orderBy('id', 'desc')->first(); // --------

        $chat   = new Chat();

        $chat->user_id      =   $fields['user_id'];
        $chat->provider_id  =   $fields['provider_id'];
        $chat->send_by      =   $fields['send_by'];
        $chat->type         =   $fields['type'];

        if($request->sent_at != NULL)
            {
                $created_at = Carbon::parse($request->sent_at);
                $now = Carbon::now();
                $now->minute = $created_at->minute;
                $now->second = $created_at->second;
                $chat->created_at = $now;
            }



        switch ($fields['type']) {

            case 'audio':
                $chat->message =  $this->uploadfile($request->file('file'));
                $message       =  'أرسل لك رسالة صوتية';
                break;
            case 'file':
                $chat->message =  $this->uploadfile($request->file('file'));
                $message       =  'أرسل لك ملف';
                break;
            case 'image':
                $chat->message =  upload_picture($request->file('image'), '/images/chat/images');
                $message       =  'أرسل لك صورة';
                break;
            case 'offer':
                $this->validate($request, rules('offer'));
                $offer = Offer::create([
                    'provider_id'           =>  $request->provider_id,
                    'provider_service_id'   =>  $request->provider_service_id,
                    'description'           =>  $request->description,
                    'price'                 =>  convertArabicNumber($request->price),
                    'target'                =>  'one'
                ]);

                $chat->message  = (string)$offer->id;
                $message        =  'أرسل لك عرض';
                break;
            case 'text':
                $chat->message  =  $request->text;
                $message        =  $request->text;
                break;
            case 'provider':
                $chat->message  =  $request->provider;
                $message        =  optional(json_decode($request->provider))->description;
                break;
            case 'location':
                $chat->message  =  $request->location;
                $message        =  'أرسل لك موقع';
                break;
        }

        $chat->save();

        $chat        =  Chat::mapChatMessage(collect([$chat], $userSender));


        //  notify by firebase
        $to              =    $userSender->id == $user_id ? $provider_id  : $user_id;
        $recevier        =    User::find($to);
        $device_token    =    $recevier->device_token;
        $name_recevier   =    $recevier->username;



        $observers_token = User::where('role', 'chat_review')->whereNotIn('id', [$userSender->id])->pluck('device_token')->filter()->toArray();
        $devices_token =  isset($observers_token) ? Arr::prepend($observers_token, $device_token) : [$device_token];

        $un            =    $userSender->username;

        if($userSender->role == 'chat_review' && $userSender->id == $fields['send_by']) {
            $devices_token   = [$device_token];
            $observers_token = [];
            $un              = 'الدعم والمساندة';
        } else if ($recevier->role == 'chat_review') {
            $devices_token   = [$device_token];
            $observers_token = [];
            $un              = 'الدعم والمساندة: '.$un;
        } else if ($userSender->role == 'chat_review') {
            $provider_token  = User::find($provider_id)->device_token;
            $observers_token = Arr::prepend($observers_token, $provider_token);
            $un              = User::find($provider_id)->username;
        }

        if($userSender->id == 102 || $userSender->id == 3429)
            $observers_token = [];

        // if ($devices_token) {

            $fcm                =    new FCM();

            $chat[0]->type   == 'image' ? $image_url  = $chat[0]->message  : $image_url  =  '';

            $fcm->url($image_url);

            $convertation_id    =   $fields['send_by'];

            foreach ($observers_token as $token) {
                if ($userSender->id == $user_id && $token) // sender is user
                    $fcm->to($token)->message_payload($chat[0])->message($message, $un . '  =>  ' . $name_recevier)->data($convertation_id, "chat", $message, $un . '  =>  ' . $name_recevier, 'LiveChat')->send();
                else if($token) // sender is provider
                    $fcm->to($token)->message_payload($chat[0])->data($convertation_id, "chat", $message, $un . '  =>  ' . $name_recevier, 'LiveChat')->send();
            }

            if($device_token)
            $fcm->to($device_token)->message_payload($chat[0])->message($message, $un)->data($convertation_id, "chat", $message, $un, 'LiveChat')->send();
        // }

        // $data        =  Chat::mapChatMessage($chat, $userSender);

        if($request->sent_at != NULL)
            $chat[0]->sent_at = $request->sent_at;

        if($request->index_in_list != NULL)
            $chat[0]->index_in_list = $request->index_in_list;

        $data        =  $chat;
        $message     =  '';

        $chat        =  Chat::where('user_id', $user_id)->where('provider_id', $provider_id)->orderBy('created_at', 'desc')->get();


        $to   = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());
        $from = Carbon::createFromFormat('Y-m-d H:i:s', $last_data_chat['created_at'] ?? $to);


        if ( ($chat->count() == 1 || $to->diffInDays($from) >= 7)
             && $fields['user_id'] == $fields['send_by']
             && !($userSender->role == 'chat_review' && $userSender->id == $fields['send_by'])
             && $recevier->role != 'chat_review'
             ) {
                 if($recevier->role == 'technical_advisor'){
                    $message = 'مرحبا بك ' . $userSender->username .  ' معك المستشار التقني من وي تك كيف أستطيع خدمتك';
                    SendMessage::dispatch($fcm, $userSender->username, $fields, $message, $userSender->device_token);
                 } else {
                    $provider = User::select('username')->find($fields['provider_id']);
                    SendMessage::dispatch($fcm, $provider->username, $fields, $message, $userSender->device_token);
                 }
        }

        $time = Carbon::now();
        $morning = Carbon::create($time->year, $time->month, $time->day, 21, 0, 0)->subDay(); // 21:00 => 00:00
        $evening = Carbon::create($time->year, $time->month, $time->day, 6, 0, 0);// 06:00 => 09:00
        $from    = Carbon::create($from->year, $from->month, $from->day, $from->hour , $from->minute ,$from->second );
        if($time->between($morning, $evening, true) && (!$from->between($morning, $evening, true )) && $recevier->role == 'technical_advisor') {
            $message = 'عزيزي ' . $userSender->username .  ' المستشارون خارج الدوام اترك رسالتك او استفسارك وسيتم الرد عليك فى اقرب وقت ممكن, مستشار وي تك فى خدمتك';
            SendMessage::dispatch($fcm, $userSender->username, $fields, $message, $userSender->device_token);
        }


        if($time->between($morning, $evening, true)) {
            $message = 'yes between';
          } else {
            $message = 'not between';
          }

          if($from->between($morning, $evening, true)) {
          $message = $message . ', mssg yes between';
          } else {
          $message = $message . ', mssg not between';
          }

        //   $message = $message . ' -- time: ' . $time . ' -- evening: ' . $evening . ' -- morning: ' . $morning . ' -- from: ' . $from . ' -- last_data_chat: ' . $last_data_chat;

        //   sleep(1);

        // $finish_create_at = Carbon::now(); //Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());
        // 'start_create_at: ' . $start_create_at . ' , finish_create_at: ' . $finish_create_at
        return response()->data($data, );
    }

    public function seen(Request $request)
    {
        $userSender     =     auth()->user();
        $auth_id        =     auth()->user()->id;
        $provider_id    =     $request->provider_id;
        $user_id        =     $request->user_id;

        if ($auth_id == $user_id)
            chat::where('send_by', '<>', $user_id)
                ->where(function ($query) use ($user_id, $provider_id) {
                    $query->Where('provider_id', $provider_id);
                    $query->where('user_id', $user_id);
                })->update(['seen' =>  1]);
        else
            chat::where('send_by', '<>', $provider_id)
                ->where(function ($query) use ($user_id, $provider_id) {
                    $query->where('user_id', $user_id);
                    $query->Where('provider_id', $provider_id);
                })->update(['seen' =>  1]);


        $data        =  'success';
        $message     =  '';

        $send_by         =    $userSender->id != $user_id ? $provider_id  : $user_id;

        if ($auth_id != $user_id)
            $device_token     =   User::withTrashed()->find($user_id)->device_token;
        else {
            $device_token     =   User::withTrashed()->find($provider_id)->device_token;
            $observers_token  =   User::where('role', 'chat_review')->whereNotIn('id', [$userSender->id])->pluck('device_token')->filter()->toArray();
            $devices_token    =   isset($observers_token) ? Arr::prepend($observers_token, $device_token) : [$device_token];
        }

        $fcm                =    new FCM();

        $message_payload    =   ["type" => "seen"];

        if ($device_token) {

            if ($auth_id != $user_id)
                $fcm->to($device_token)->message_payload($message_payload)->data($send_by, "info")->send();
            else
                foreach ($devices_token as $token)
                       $fcm->to($token)->message_payload($message_payload)->data($send_by, "info")->send();

        } else if($provider_id == '1') {
            $message_payload    +=   ["user_id" => $send_by];
            $message_payload    +=   ["provider_id" => "1"];

            $devices_token = array_values(array_filter(User::where('role', 'chat_review')->whereNotIn('id', [$userSender->id])->pluck('device_token')->filter()->toArray()));

            foreach ($devices_token as $token)
                $fcm->to($token)->message_payload($message_payload)->data($send_by, "info")->send();
        }

        return response()->data($data);
    }
    public function delete($id)
    {
        Chat::findOrFail($id)->delete();

        $message = 'order was deleted successfully';

        return response()->message($message);
    }
    public function uploadfile($file)
    {
        if ($file) {
            $filepath   =  Storage::putFile('chat/files', $file);
            if($file->getClientOriginalExtension() != $file->extension())
                Storage::move($filepath, str_replace($file->extension(), $file->getClientOriginalExtension(), $filepath));
            $fileName = basename(str_replace($file->extension(), $file->getClientOriginalExtension(), $filepath));
            return $fileName;
        }
        return NULL;
    }
    public function typing(Request $request)
    {
        $user             =   User::find($request->send_to);
        $device_token     =   $user->device_token;

        if ($user->role === 'provider' || $request->send_to == '1') {
            $observers_token  =   User::where('role', 'chat_review')->whereNotIn('id', [auth()->user()->id])->pluck('device_token')->filter()->toArray();
            $devices_token    =   isset($observers_token) ? Arr::prepend($observers_token, $device_token) : [$device_token];
        }


        $fcm                =    new FCM();

        $message_payload    =   ["text" => "USER_TYPING"];

        if ($device_token) {

            if ($user->role === 'provider')
                foreach ($devices_token as $token)
                    $fcm->to($token)->message_payload($message_payload)->data($request->send_by)->send();
            else
                $fcm->to($device_token)->message_payload($message_payload)->data($request->send_by)->send();


            return response()->data("succes");

        } else if($request->send_to == '1') {
            $message_payload    +=   ["user_id" => $request->send_by];
            $message_payload    +=   ["provider_id" => "1"];

            $devices_token = array_values(array_filter($devices_token));

            foreach ($devices_token as $token)
                $fcm->to($token)->message_payload($message_payload)->data($request->send_by)->send();

            return response()->data("succes to technical");
        }

        return response()->data("error no device token");
    }
    public function message_review(Request $request)
    {
        $chats       = Chat::select('provider_id')
            ->observerChat()
            ->orderBy('created_at', 'desc')
            ->with('provider:id,username,avatar,country_id,active,balance,balance_online', 'provider.country:id,code,unit,unit_en')
            ->whereHas('provider.country', function ($query) {
                $query->whereIn('id', auth()->user()->permissions ?? []);
            })
            ->whereHas('provider', function ($query) {
                $query->where('role', '!=', 'chat_review');
            })
            ->get();

        $chats->map(function ($message) {

            $message->provider->avatar  = url($message->provider->avatar);

            $message->count_not_seen    = Chat::where('provider_id', $message->provider->id)
                                             ->whereNotIn('send_by', [$message->provider->id])
                                             ->where('seen', 0)->count('id');

            $message->last_chat_date    = Chat::select('created_at')->where('provider_id', $message->provider->id)
                                                            ->orderBy('created_at', 'desc')->first()['created_at'];

            $message->ordersCount       = Order::where('provider_id', $message->provider->id)->statistics()->first();

            $message->errors            = MessageReport::with('chat')->whereHas('chat', function ($query) use ($message){
                $query->where('provider_id', $message->provider->id);
            })->count('id');

            $message->commission        = -$message->provider->balance;

            $message->commission_online = $message->provider->balance_online;


            return $message;
        });

        if ($request->has('sort_by')) {
            in_array($request->sort_by, ['ordersCount.completed', 'ordersCount.canceled', 'ordersCount.pending', 'commission', 'commission_online' ,'errors'])
                ? $chats = $chats->sortByDesc($request->sort_by)->values() : false;
        }


        $id               =  auth()->user()->id;
        $reports          =  Reports::with('user:id,username,role')->get();
        $reports          =  $reports->map(function ($item) use ($id) {

            $item->count_not_seen   = Chat::where('user_id'     , strtolower($item->user->role) == 'user'     ? $item->user->id : $id)
                                          ->Where('provider_id' , strtolower($item->user->role) == 'provider' ? $item->user->id : $id)
                                          ->where('send_by'     , $item->user->id)
                                          ->where('seen'        , 0)->count('id');
            return $item;

        });

        $data   =  [ 'providers' => $chats ];

        return response()->data($data, 'All messages need to be read');
    }
    public function review_provider_messages($id, Request $request)
    {

        $chats                      = Chat::selectRaw('user_id')
                                          ->where('provider_id', $id)
                                          ->providerChat()
                                          ->orderBy('created_at', 'desc')
                                          ->with('user:id,username,avatar,country_id,role,deleted_at', 'user.country')
                                          ->get();

        $chats = $chats->map(function ($item) use ($id, $request) {

            $item->count_not_seen   = Chat::where('provider_id', $id)
                                          ->where('send_by', $item->user->id)
                                          ->where('seen', 0)->count('id');

            $item->last_chat_date   = Chat::select ('created_at')
                                          ->where  ('provider_id', $id)
                                          ->where  ('user_id', $item->user->id)
                                          ->orderBy('created_at', 'desc')
                                          ->first()['created_at'];

            $item->user->avatar     =  $item->user->role === 'chat_review' ? get_logo($request) : url($item->user->avatar);
            $item->user->username   =  $item->user->role === 'chat_review' ? string_value(0, $request) : $item->user->username;

            return $item;
        });


        return response()->data($chats);
    }
    public function messageRepote(Request $request)
    {

        $this->validate($request, ['message_id' => 'required', 'review' => 'required']);
        return  MessageReport::create([
            'monitor_id' => auth()->id(),
            'message_id'  => $request->message_id,
            'review' => $request->review
        ]);
    }
    public function deleteMessageRepote(Request $request)
    {
        $this->validate($request, ['message_id' => 'required']);

        MessageReport::where('message_id', $request->message_id)->delete();

        $message = 'the message repote was deleted';

        return   response()->message($message);
    }
}
