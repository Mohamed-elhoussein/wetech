<?php

namespace App\Bots;

class ErrorBots
{
    private $bot_id;
    private $channel_id;
    private $message;
    private $active;

    public function __construct($message)
    {
        $this->active       = env('TELEGRAM_TESTS_BOT');
        $this->bot_id       = env('TELEGRAM_TESTS_BOT_ID');
        $this->channel_id   = env('TELEGRAM_TESTS_CHANNEL');
        $this->message      = $message;
        if ($this->active) {
            $this->send();
        }
    }

    private function send()
    {
        $website        = "https://api.telegram.org/bot" . $this->bot_id;
        $params         = [
            'chat_id'   => $this->channel_id,
            'text'      => $this->message,
        ];
        $ch = curl_init($website . '/sendMessage');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
        curl_exec($ch);
        curl_close($ch);
    }
}
