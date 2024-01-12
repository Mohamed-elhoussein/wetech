<?php

namespace App\Http\Controllers\api\v3;

use App\Http\Resources\ProvidersMaintenanceResource;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Issues;
use App\Models\MaintenanceRequest;
use App\Models\CityMaintenanceRequest;
use App\Models\StreetMaintenanceRequest;
use App\Models\Models;
use App\Models\PaymentOption;
use App\Models\ProviderServices;
use App\Models\User;
use App\Models\ColorMaintenanceRequest;
use App\Models\Service;

class MainStoreController
{
    private function getPaymentOptions($id = null, $price = 0)
    {
        if ($id) return PaymentOption::find($id);

        if ($price <= 0) {
            $option = PaymentOption::find(4);
            return [
                [
                    'id' => $option->id,
                    'type' => $option->type,
                    'amountValue' => $option->value,
                    'name' => $option->label,
                    'valueForServer' => $option->payment_type,
                    'underText' => $option->sub_text,
                    'url' => $option->id === 2 ? 'https://wetech.drtech-api.com/images/im_mcmv.png' : '',
                    'isActive' => true,
                    'label' => $option->label
                ]
            ];
        }

        return PaymentOption::orderBy('id', 'DESC')->where('id', '!=', 4)->get()->map(function ($option) {
            return [
                'id' => $option->id,
                'type' => $option->type,
                'amountValue' => $option->value,
                'name' => $option->label,
                'valueForServer' => $option->payment_type,
                'underText' => $option->sub_text,
                'url' => $option->id === 2 ? 'https://wetech.drtech-api.com/images/im_mcmv.png' : '',
                'isActive' => true,
                'label' => $option->label
            ];
        });
    }

    public function get_params()
    {
        $resource = request()->get('resource');

        $data = null;

        switch ($resource) {
            case "services":
                $data = Service::all();
                break;
            case "brand":
                $ids = MaintenanceRequest::query()->where('service_id', request()->get('service_id'))->get()->pluck('brand_id')->unique();
                $data = Brand::query()->whereIn('id', $ids)->get();
                break;
            case "model":
                $models = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('brand_id', request()->get('brand_id'))
                    ->get()
                    ->pluck('models_id')
                    ->unique();
                $data = Models::query()->whereIn('id', $models)->get();
                break;
            case "color":
                $ids = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('models_id', request()->get('model_id'))
                    ->with('colors')
                    ->get()
                    ->pluck('id')
                    ->unique();
                $ids = ColorMaintenanceRequest::query()->whereIn('maintenance_request_id', $ids->toArray())->get()->pluck('color_id')->unique();
                $data = Color::query()->whereIn('id', $ids)->get();
                break;
            case "issue":
                $ids = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('models_id', request()->get('model_id'))
                    ->whereHas('colors', function ($query) {
                        $query->where('colors.id', request()->get('color_id'));
                    })
                    ->get()
                    ->pluck('issues_id')
                    ->unique();
                $data = Issues::query()->whereIn('id', $ids)->get();
                break;
            case "city":
                $request = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('models_id', request()->get('model_id'))
                    ->where('issues_id', request()->get('issue_id'))
                    ->get();

                return response()->data($request->map(function ($rq) {
                    return $rq->cities->sortBy('city_id')->map(function ($city_maintenance_request) {
                        return [
                            'id' => $city_maintenance_request->id,
                            'country_name' => optional(optional($city_maintenance_request->city)->country)->name,
                            'name' => $name = optional($city_maintenance_request->city)->name,
                            'name_en' => optional($city_maintenance_request->city)->name_en,
                            'status' => optional($city_maintenance_request->city)->status,
                            'created_at' => optional($city_maintenance_request->city)->created_at,
                        ];
                    });
                })->flatten(1)->toArray());
                break;
            case "street":
                $data = StreetMaintenanceRequest::query()->where('city_maintenance_request_id', request()->get('city_id'))->get()->map(function ($item) {
                    $item->street->id = $item->id;
                    return $item->street;
                });
                break;
            case "price":
                $provider_ids = User::provider()->whereHas('services_accepted', function ($query) {
                    $query->whereHas('city', function ($query) {
                        $query->whereHas('city_maintenance_requests', function ($query) {
                            $query->where('id', request()->get('city_id'));
                        });
                    });
                })->pluck('id');

                $request = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('brand_id', request()->get('brand_id'))
                    ->where('models_id', request()->get('model_id'))
                    ->where('issues_id', request()->get('issue_id'))
                    ->whereHas('cities', function ($query) {
                        $query->where('city_maintenance_requests.id', request()->get('city_id'));
                    })
                    ->whereHas('streets', function ($query) {
                        $query->where('street_maintenance_requests.id', request()->get('street_id'));
                    })
                    ->with([
                        'cities',
                        'cities.streets',
                        'types' => function ($query) use ($provider_ids) {
                            $query->whereHas('provider', function ($query) use ($provider_ids) {
                                $query->whereIn('id', $provider_ids);
                            });
                        }
                    ])
                    ->latest('id')
                    ->first();

                    $isPricedAndThereProvidersOffers = $request && sizeof($request->types) > 0;
                    $data = array_map('intval', request()->only([
                        'service_id',
                        'brand_id',
                        'model_id',
                        'color_id',
                        'issue_id',
                        'city_id',
                        'street_id',
                    ]));

                    $ids = [[426]]; // $isJeddah? [108, 106] :
                    $providers = User::query()->where('role', 'provider')->whereIn('id', $ids)->orderByRaw('`active` DESC, -`order` DESC')->with('rate')->get([
                        'id',
                        'first_name',
                        'second_name',
                        'last_name',
                        'username',
                        'active',
                        'order',
                    ]);

                    if($isPricedAndThereProvidersOffers)
                    {
                        $data['maintenance_offers'] = $request->types->map(fn ($type) => [
                            'maintenance_request_type_id' => $type->id,
                            'name' => $type->type->name,
                            'price' => (float)$type->price,
                            'provider_id' => $type->provider_id,
                            'provider' => $type->provider?->username,
                            'active' => $type->provider?->active,
                            'reviews' => (string) number_format($type->provider?->rate->avg('stars'), 1) ?? "0.0",
                            'created_at' => $request->created_at,
                            'payWay' => $this->getPaymentOptions(null, (float)$type->price)
                        ])->sortBy('price')->values();
                        $data['all_zeros'] = false;
                    }
                    else
                    {  // عدم عرض عروض الأسعار إذا كانت المدينة غير جدة وإظهار حساب وي تك فقط
                        $request = MaintenanceRequest::query()
                            ->where('service_id', request()->get('service_id'))
                            ->where('brand_id', request()->get('brand_id'))
                            ->where('models_id', request()->get('model_id'))
                            ->where('issues_id', request()->get('issue_id'))
                            ->whereHas('cities', function ($query) {
                                $query->where('city_maintenance_requests.id', request()->get('city_id'));
                            })
                            ->whereHas('streets', function ($query) {
                                $query->where('street_maintenance_requests.id', request()->get('street_id'));
                            })
                            ->with([
                                'cities',
                                'cities.streets',
                            ])
                            ->latest('id')
                            ->first();

                        $data['maintenance_offers'] = [$request->types->map(function ($type) use ($providers, $request) {
                            $provider = $providers[0];
                            return [
                                'maintenance_request_type_id' => $type->id,
                                'name' => 'أصلي السوق',//$type->type->name,
                                'price' => (float) 0.0, //$type->price,
                                'provider_id' => $provider->id, //$type->provider_id,
                                'provider' => $provider->username, //$type->provider?->username,
                                'active' => $provider->active, //$type->provider?->active,
                                'reviews' => (string) number_format($provider?->rate->avg('stars'), 1) ?? "0.0",
                                'created_at' => $request->created_at,
                                'payWay' => $this->getPaymentOptions(null, (float)$type->price)
                            ];
                        })->first()];
                        $data['all_zeros'] = true;
                    }
                    $data['providers'] = [];
                    break;
                }
        return response()->data($data);
    }

}
