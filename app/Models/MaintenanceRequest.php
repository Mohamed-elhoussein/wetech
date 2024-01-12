<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'service_id',
        'brand_id',
        'models_id',
        'color_id',
        'issues_id',
        'maintenance_request_type_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class)->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function model()
    {
        return $this->belongsTo(Models::class, 'models_id')->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'color_maintenance_requests');
    }

    public function issue()
    {
        return $this->belongsTo(Issues::class, 'issues_id')->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id')->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function city()
    {
        return $this->belongsTo(Cities::class, 'city_id')->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function street()
    {
        return $this->belongsTo(Street::class)->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function countries()
    {
        return $this->hasMany(CountryMaintenanceRequest::class);
    }

    public function cities()
    {
        return $this->hasMany(CityMaintenanceRequest::class);
    }

    public function streets()
    {
        return $this->hasManyThrough(StreetMaintenanceRequest::class, CityMaintenanceRequest::class);
    }

    /**
     * @deprecated
     */
    public function type()
    {
        return $this->belongsTo(MaintenanceRequestType::class, 'maintenance_request_type_id')->withDefault([
            'name' => 'غير محدد'
        ]);
    }

    public function types()
    {
        return $this->hasMany(MaintenanceType::class);
    }


    public function req_order()
    {
        return $this->hasOneThrough(MaintenanceRequestOrder::class  , MaintenanceType::class);
    }

    public function types_priced()
    {
        return $this->hasMany(MaintenanceType::class)->where('price', '>', '0');
    }

    public function types_providers_not_null()
    {
        return $this->hasMany(MaintenanceType::class)->where(function($q) {
            $q->whereNotNull('provider_id')
              ->Where('provider_id', '!=', '0');
        });
    }

    public function types_priced_and_providers_not_null()
    {
        return $this->hasMany(MaintenanceType::class)->where(function($q) {
            $q->whereNotNull('provider_id')
              ->Where('provider_id', '!=', '0');
        })->where('price', '>', '0');
    }

    public function getCountryIdsAttribute()
    {
        return $this->countries->pluck('country_id');
    }

    public function getCityIdsAttribute()
    {
        return $this->cities->pluck('city_id');
    }

    public function getStreetIdsAttribute()
    {
        return $this->streets->pluck('street_id');
    }

    public function getColorNamesAttribute()
    {
        return $this->colors->pluck('name')->implode(" | ");
    }

    public function getCountryNamesAttribute()
    {
        return $this->countries->pluck('country.name')->implode(" | ");
    }

    public function getCityNamesAttribute()
    {
        return $this->cities->pluck('city.name')->implode(" | ");
    }

    public function getStreetNamesAttribute()
    {
        return $this->streets->filter(function ($street) {
            return !is_null($street->street);
        })->pluck('street.name')->implode(" | ");
    }

    public function update_location($data, $web = false)
    {
        if ($web) {
            $tmp = [];

            foreach ($data['street_id'] as $item) {

                if (isset($item['street_id'])) {
                    $index = collect($tmp)->map(function ($i, $key) use ($item) {
                        return $i['city_id'] == $item['city_id'] ? $key : null;
                    })->first();

                    if (!is_null($index)) {
                        $tmp[$index]['street_id'][] = $item['street_id'];
                    }
                    else {
                        $tmp[] = [
                            'city_id' => $item['city_id'],
                            'street_id' => [
                                $item['street_id']
                            ],
                        ];
                    }
                }
            }

            $data['cities'] = $tmp;
        }

        $this->countries()->delete();
        $this->streets()->delete();
        $this->cities()->delete();

        CountryMaintenanceRequest::insert(
            collect($data['country_id'])->map(function ($country_id) {
                return [
                    'country_id' => $country_id,
                    'maintenance_request_id' => $this->id,
                ];
            })->toArray()
        );

        collect($data['cities'])->map(function ($city) {
            $city_maintenance_request = CityMaintenanceRequest::create([
                'city_id' => $city['city_id'],
                'maintenance_request_id' => $this->id,
            ]);

            collect($city['street_id'])->map(function ($street_id) use ($city_maintenance_request) {
                StreetMaintenanceRequest::create([
                    'street_id' => $street_id,
                    'city_maintenance_request_id' => $city_maintenance_request->id
                ]);
            });
        });
    }

    public function update_types($data)
    {
        collect($data['meta'])->map(function ($item) {
            // Update the maintenance request type
            if (array_key_exists('id', $item)) {
                MaintenanceType::query()->find($item['id'])?->update($item);
            }
            else {
                $this->types()->create([
                    'price' => $item['price'],
                    'provider_id' => $item['provider_id'] === -1? null : $item['provider_id'],
                    'type_id' => $item['type_id'],
                ]);
            }

            // Update type names
            if (array_key_exists('name', $item)) {
                MaintenanceRequestType::query()->where('id', $item['type_id'])->update([
                    'name' => $item['name']
                ]);
            }

            if (array_key_exists('deleted', $item)) {
                MaintenanceType::query()->find($item['id'])?->delete();
            }
        });
    }
}
