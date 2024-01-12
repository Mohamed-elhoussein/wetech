<?php

namespace App\Jobs;

use App\Models\Chat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fcm;
    private $nameReceiver;
    private $fields;
    private $message;
    private $device_token;

    public function __construct($fcm, $nameReceiver, $fields, $message, $device_token)
    {
        $this->fcm = $fcm;
        $this->nameReceiver = $nameReceiver;
        $this->fields = $fields;
        $this->message = $message;
        $this->device_token = $device_token;
    }

    public function handle()
    {
        $replay               =   new Chat();
        $replay->user_id      =   $this->fields['user_id'];
        $replay->provider_id  =   $this->fields['provider_id'];
        $replay->send_by      =   $this->fields['provider_id'];
        $replay->type         =   'text';
        $replay->message      =   isset($this->message) && (!empty($this->message)) ? $this->message : 
                                  'مرحبا بك عزيزي انا مزود الخدمة ' . $this->nameReceiver . ' يمكنك شرح طلبك بالكامل مع جميع التفاصيل المطلوبه و ساقوم بالرد عليك مباشره فى حال الاطلاع عليها واتشرف بخدمتك';
        $replay->save();
        $replay               =   Chat::mapChatMessage(collect([$replay]));
        $device_token         =   $this->device_token;
        $this->fcm->to($device_token)->message_payload($replay[0])->message($replay[0]->message, $this->nameReceiver)->data($this->fields['provider_id'], "chat", $replay[0]->message, $this->nameReceiver, 'LiveChat')->send();
    }
}
