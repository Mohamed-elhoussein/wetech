<?php

namespace App\Events;

use App\Http\Resources\BuyerRequestResource;
use App\Models\BuyerRequest;
use App\Models\CanceledBuyerRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuyerRequestUpdates implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    private $buyer_request;

    public function __construct($buyer_request)
    {
        $this->buyer_request = $buyer_request;
    }

    public function broadcastWith()
    {
        return [
            $this->buyer_request->id
        ];
        // return [];
        // $canceled_buyer_requests = CanceledBuyerRequest::all()->pluck('buyer_request_id')->filter()->unique()->values()->toArray();
        // $buyer_requests = BuyerRequest::query()->with([
        //     'service_type',
        //     'service',
        //     'city',
        // ])->whereNull('provider_id')->latest('id')->get();
        // $current_user_buyer_requests = collect($buyer_requests)->whereNotIn('id', $canceled_buyer_requests);

        // return [
        //     'user_id' => auth()->id(),
        //     'current_user_buyer_requests' => BuyerRequestResource::collection(
        //         $current_user_buyer_requests
        //     ),
        //     'buyer-requests' => BuyerRequestResource::collection(
        //         $buyer_requests
        //     )
        // ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("buyer-requests");
    }
}
