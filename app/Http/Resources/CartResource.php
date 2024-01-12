<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'note' => $this->note,
            'is_favourite' => !is_null(optional($this->favourite)->first()) && auth('sanctum')->check(),
            'favourite_product_id' => !is_null(optional($this->favourite)->first()) && auth('sanctum')->check() ? optional($this->favourite)->first()->id : null,
        ];
    }
}
