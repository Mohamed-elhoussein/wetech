<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CanceledBuyerRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'provider_id' => optional($this->provider)->id,
            'provider_name' => optional($this->provider)->username,
            'cancel_id' => $this->id,
            'cancel_at' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
