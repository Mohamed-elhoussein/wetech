<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProvidersMaintenanceResource extends JsonResource
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
            'first_name' => $this->username,//$this->first_name,
            'second_name' => '',//$this->second_name,
            'last_name' => '',//$this->last_name,
            'username' => $this->username,
            'active' => boolval($this->active),
            'reviews' => number_format($this->rate->avg('stars'), 1),
            'order' => $this->order
        ];
    }
}
