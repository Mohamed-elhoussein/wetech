<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BuyerRequestResource extends JsonResource
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
            'id' => $this->id,
            'status' => $this->status,
            'type' => $this->service_type->name,
            'service' => $this->service->name,
            'city' => $this->city->name,
            'street' => optional($this->street)->name,
            'product_type' => optional($this->product_type)->name,
            'date' => $this->created_at->format('d-m-Y H:i'),
            'image' => $this->image ? asset('/storage/' . $this->image) : null,
            'description' => $this->description,
        ];
    }
}
