<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BuyerRequestCanceled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $buyer_request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($buyer_request)
    {
        $this->buyer_request = $buyer_request;
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->buyer_request->id
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("buyer-requests"); //.{$this->buyer_request->id}
    }
}
