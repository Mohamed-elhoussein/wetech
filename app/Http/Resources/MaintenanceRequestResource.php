<?php

namespace App\Http\Resources;

use App\Models\Cities;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceRequestResource extends JsonResource
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
            'service' => $this->service->name,
            'service_id' => $this->service_id,
            'brand' => $this->brand->name,
            'brand_id' => $this->brand_id,
            'model' => $this->model->name,
            'model_id' => $this->models_id,
            'color' => $this->color->name,
            'color_id' => $this->color_id,
            'issue' => $this->issue->name,
            'issue_id' => $this->issues_id,
            // 'country' => $this->country->name,
            // 'country_id' => $this->country_id,
            // 'city' => $this->city->name,
            // 'city_id' => $this->city_id,
            // 'street' => $this->street->name,
            // 'street_id' => $this->street_id,
            'types' => $this->types->map(function ($type) {
                return [
                    'id' => $type->id,
                    'type_id' => $type->type->id,
                    'provider_id' => $type->provider_id,
                    'provider' => $type->provider->username,
                    'name' => $type->type->name,
                    'price' => $type->price,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'cities' => $this->cities->map(function ($city_maintenance_request) {
                return [
                    'id' => optional($city_maintenance_request->city)->id,
                    'country_name' => optional(optional($city_maintenance_request->city)->country)->name,
                    'name' => $name = optional($city_maintenance_request->city)->name,
                    'name_en' => optional($city_maintenance_request->city)->name_en,
                    'status' => optional($city_maintenance_request->city)->status,
                    'created_at' => optional($city_maintenance_request->city)->created_at,
                    'street' => optional($city_maintenance_request)->streets->map(function ($street) {
                        return [
                            'id' => optional($street->street)->id,
                            'street_name' => optional($street->street)->name,
                            // 'street' => optional($street)->street,
                        ];
                    }),
                ];
            }),
            'colors' => $this->colors->map(function ($color) {
                return [
                    "id" => optional($color)->id,
                    "name" => optional($color)->name,
                ];
            })
        ];
    }
}
