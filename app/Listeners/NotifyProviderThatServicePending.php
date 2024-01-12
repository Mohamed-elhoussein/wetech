<?php

namespace App\Listeners;

use App\Events\ProviderServicesCreate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyProviderThatServicePending
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
     
    }

    /**
     * Handle the event.
     *
     * @param  ProviderServicesCreate  $event
     * @return void
     */
    public function handle(ProviderServicesCreate $event)
    {
        dd('hhhh');
    }
}
