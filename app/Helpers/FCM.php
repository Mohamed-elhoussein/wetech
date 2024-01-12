<?php

/*
*   Developer : takidddine soulaimane
*   Email     : takiddine.job@gmail.com
*   whatsapp  :  +212658829307
*/

namespace App\Helpers;
use App\Models\User;
class FCM
{

    private $fcm_key;
    private $fcm_key_2;
    private $to;
    private $notification;
    private $data;
    private $response;
    private $device_token;
    private $message;
    private $title;
    private $url;
    private $msg_type;
    private $message_payload;
    private $conversation_id;



    public function __construct()
    {
        $this->fcm_key    =  keys('firbase_key');
        $this->fcm_key_2  =  keys('firbase_key_2');
    }





    public function message_payload($message_payload)
    {
        $this->message_payload  =  $message_payload;
        return $this;
    }

    public function to($to)
    {
        $this->to  =  $to;
        return $this;
    }
    public function url($url)
    {
        $this->url  =  $url;
        return $this;
    }
    public function device_token($device_token)
    {
        $this->device_token  =  $device_token;
        return $this;
    }


    public function title($title)
    {
        $this->title  =  $title;
        return $this;
    }

    public function message($message, $title = 'message')
    {
        $this->message  =  $message;


        $this->notification  =  [
            'title'     => $title,
            'body'      => $this->message,
            'icon'      => 'ic_logo_notifi',
            "sound"     => "special.caf",
            "priority"  => "high",
        ];
        return $this;
    }

    public function data($conversation_id, $type = "chat", $message_txt = '', $title = 'message', $screen = 'WelcomePage')
    {
		$this->message_payload["screen"] = $screen;

        $this->data  = [
            "title"                 =>  $title,
            "message_txt"           =>  $message_txt,
            "payload_target"        =>  $type,
            "priority"              =>  "high",
            "screen"                =>  $screen,
            "content_available"     =>  true,
            "click_action"          =>  "FLUTTER_NOTIFICATION_CLICK",
            "conversation_id"       =>  $conversation_id,
            "payload"               =>  $this->message_payload,
        ];
        return $this;
    }

    public function msg_type($msg_type)
    {
        $this->msg_type  =  $msg_type;
        return $this;
    }




    public function send($type = NULL)
    {

        $notification = $this->notification;

        $fcmNotification = [
            "to"                => $this->to,
            "contentAvailable" => true,
            "priority"          => "high",
            "apns-priority"     => 5,
            "url"               => $this->url ?? '',
            "title"             => "title",
            "body"              => "body",
            "message"           => $this->message,
            "type"              => "NOTIC",
            'data'              => $this->data,
        ];



        $this->notification != NULL    ?     $fcmNotification +=  ["notification" => $notification]    :     false;

        // $this->notification != NULL    ?    $fcmNotification +=  [
        //     "android" =>    [
        //         "notification" => [
        //             "icon"                =>  "ic_logo_notifi",
        //             "color"               =>  "#ffffff",
        //             "channel_id"          =>  "high_importance_channel",
        //             "contentAvailable"   =>  true,
        //             "priority"            =>  "high",
        //             "sound"               =>  "special",
        //         ]
        //     ]
        // ]    :         false;

        $user = User::where('device_token',$this->to)->first();
        if(!$user)
            return;

        $headers = [
            'Authorization: key=' . ($user->role === 'chat_review' || $user->role === 'provider'? $this->fcm_key : $this->fcm_key_2),
            'Content-Type: application/json'
        ];

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        $this->response = $result;
        $fcmNotificationString = json_encode($fcmNotification);
        \Illuminate\Support\Facades\Log::channel('fcm')->info('------------start--------------');
        \Illuminate\Support\Facades\Log::channel('fcm')->info("{$user->id} - {$user->role} - {$user->username} - {$user->number_phone}");
        \Illuminate\Support\Facades\Log::channel('fcm')->info($fcmNotificationString);
        \Illuminate\Support\Facades\Log::channel('fcm')->info($result);
        \Illuminate\Support\Facades\Log::channel('fcm')->info(json_encode($headers));
        \Illuminate\Support\Facades\Log::channel('fcm')->info('------------end--------------');
        return $this;
    }


    public function response()
    {
        return $this->response;
    }
}
