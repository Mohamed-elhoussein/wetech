<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            // if (!is_null($this->additional['code'])) {
            //     $types = $item->types->filter(function ($type) {
            //         return strtolower(optional($type->country)->code) == strtolower($this->additional['code']);
            //     });
            // } else {
            //     $types = $item->types;
            // }

            return [
                'id' => $item->id,
                'product_number' => $item->product_number,
                'name' => $item->name,
                'name_en' => $item->name_en,
                'tax_status' => $item->tax_status,
                'price_section' => $item->price_section,
                'featured' => $item->featured,
                'images'        => $item->images,
                // 'image' => count($item->images) >  0 ? url(Storage::url($item->images->first()['image'])) : 'null',     //mo7
                'cart' =>  $item->cart,
                // 'is_favourite' => !is_null($item->favourite->first()) && auth('sanctum')->check(),
                // 'favourite_product_id' => !is_null($item->favourite->first()) && auth('sanctum')->check() ? $item->favourite->first()->id : null,
            ];
        });
    }
}
