<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\Offer;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\ServiceCategories;
use App\Models\ServiceSubcategories;
use App\Models\UserLikedServices;
use App\Models\ServiceOffers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ServicesApiController extends Controller
{
    public function allServices()
    {
        $service      =  Service::where('active', 1)->with('categories')->orderBy('order_index')->get();
        $service       =   $service->whereNotIn('join_option', '0')->values();
        $service       =   $service->map(function ($service) {
            return   [
                'id'                    => $service->id,
                'name'                  => $service->name,
                'status'                => $service->active,
                'is_country_city_street' => $service->is_country_city_street,
                'categories' =>
                $service->categories->map(function ($categories) {
                    return [
                        'id'              => $categories->id,
                        'name'            => $categories->name,
                        'status'          => $categories->active,
                        'subcategories'   =>
                        $categories->subcategories->map(function ($subcategories) {
                            return [
                                'id'              => $subcategories->id,
                                'name'            => $subcategories->name,
                                'status'          => $subcategories->active,
                                'service_sub_2'   =>
                                $subcategories->service_sub_2->map(function ($service_sub_2) {
                                    return [
                                        'id'              => $service_sub_2->id,
                                        'name'            => $service_sub_2->name,
                                        'status'          => $service_sub_2->active,
                                        'service_sub_3'   =>
                                        $service_sub_2->service_sub_3->map(function ($service_sub_3) {
                                            return [
                                                'id'              => $service_sub_3->id,
                                                'name'            => $service_sub_3->name,
                                                'status'          => $service_sub_3->active,
                                                'service_sub_4'   =>
                                                $service_sub_3->service_sub_4->map(function ($service_sub_4) {
                                                    return [
                                                        'id'              => $service_sub_4->id,
                                                        'name'            => $service_sub_4->name,
                                                        'status'          => $service_sub_4->active,

                                                    ];
                                                })
                                            ];
                                        })
                                    ];
                                })
                            ];
                        })
                    ];
                })
            ];
        });
        $arry  = [
            'id'              => null,
            'name'            => 'الكل',
            'status'          => 1
        ];
        $service       =   collect($service)->map(function ($service) use ($arry) {


            $categories = collect($service['categories'])->map(function ($categories) use ($arry) {

                $subcategories = collect($categories['subcategories'])->map(function ($subcategories) use ($arry) {

                    $service_sub_2 = collect($subcategories['service_sub_2'])->map(function ($service_sub_2) use ($arry) {

                        $service_sub_3 = collect($service_sub_2['service_sub_3'])->map(function ($service_sub_3) use ($arry) {

                            $service_sub_4 = $service_sub_3['service_sub_4']->toArray();
                            $service_sub_4 ? array_unshift($service_sub_4, $arry) : false;
                            $service_sub_3['service_sub_4'] = $service_sub_4;
                            return $service_sub_3;
                        })->toArray();
                        $service_sub_3 ? array_unshift($service_sub_3, $arry) : false;
                        $service_sub_2['service_sub_3'] = $service_sub_3;
                        return $service_sub_2;
                    })->toArray();
                    $service_sub_2 ? array_unshift($service_sub_2, $arry) : false;
                    $subcategories['service_sub_2'] = $service_sub_2;
                    return $subcategories;
                })->toArray();

                $subcategories ? array_unshift($subcategories, $arry) : false;

                $categories['subcategories'] = $subcategories;
                return $categories;
            })->toArray();

            $service['id'] !== 6 ? array_unshift($categories, $arry) : false;

            $service['categories'] = $categories;

            return $service;
        });
        $countries = Countries::where('status', 'ACTIVE')->get()->map(function ($service) {
            return   [
                'id'        => $service->id,
                'name'      => $service->name,
                'cities'  => $service->cities->map(function ($service) {
                    return   [
                        'id'        => $service->id,
                        'name'      => $service->name,
                        'street'    => $service->street->map(function ($service) {
                            return   [
                                'id'        => $service->id,
                                'name'      => $service->name,
                            ];
                        })
                    ];
                })
            ];
        });
        $countries = collect($countries)->map(function ($Country) use ($arry) {

            $cities    =     collect($Country['cities'])->map(function ($city) use ($arry) {
                $street    =   $city['street']->toArray();
                array_unshift($street, $arry);
                $city['street'] = $street;
                return $city;
            })->toArray();
            array_unshift($cities, $arry);
            $Country['cities'] = $cities;
            return $Country;
        })->toArray();

        array_unshift($countries, $arry);


        
        $data       =       [
            'type'      => ServiceType::all(),
            'countries' => $countries,
            'service'   => $service,
        ];
        return response()->data($data);
    }
    public function create(Request  $request)
    {

        $this->validate($request, rules('services.create'));

        $service            =   Service::create([
            'name'         => $request->name,
            'description'   => $request->description,
            'image'         => upload_picture($request->image, '/images/services'),
        ]);

        $data               =   $service;
        $message            =   'service was created successfully';

        return response()->data($data, $message);
    }
    public function details($service_id)
    {


        return $serviceDetails                 =  Service::where('id', $service_id)->with('categories')->first();


        $data                                  =  $serviceDetails;

        return response()->data($data);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, rules('services.update'));
        $fields             =   $request->all();

        $service = Service::where('id', $id)->first();

        isset($fields['name'])                    ?   $service->name        =   $fields['name']                                     :   false;
        isset($fields['description'])             ?   $service->description =   $fields['description']                              :   false;
        isset($fields['image'])                   ?   $service->image       =   upload_picture($request->image, '/images/services')  :   false;
        isset($fields['active'])                  ?   $service->active      =   $fields['active'] ? '1'   : '0'                     :   false;

        $service->save();



        $data           =   $service;

        $message        =  'service was updated successfully';

        return response()->data($data, $message);
    }
    public function  categories($id)
    {
        $serviceCategories   =   ServiceCategories::where('service_id', $id)->get(['id', 'name']);

        return response()->json($serviceCategories);
    }
    public function delete($id)
    {
        Service::where('id', $id)->delete();

        $message        =   'service was deleted successfully';

        return response()->message($message);
    }
    public function filters(Request  $request)
    {

        for ($i = 1; $i < 5; $i++) {

            $ratings[] = [

                'id'        => "$i",

                'name'      => "اكثر من $i نجوم",

                'name_en'   => "More then $i stars"

            ];
        }



        $Categories       =   ServiceCategories::where('service_id', $request['service_id'])->where('active', 1)->get();
        $Categories       =   $Categories->map(function ($categories) {
            return [
                'id'              => $categories->id,
                'service_id'      => $categories->service_id,
                'name'            => $categories->name,
                'name_en'         => $categories->name_en,
                'status'          => $categories->active,
                'subcategories'   =>
                $categories->subcategories->map(function ($subcategories) {
                    return [
                        'id'              => $subcategories->id,
                        'name'            => $subcategories->name,
                        'name_en'         => $subcategories->name_en,
                        'status'          => $subcategories->active,
                        'service_sub_2'   =>
                        $subcategories->service_sub_2->map(function ($service_sub_2) {
                            return [
                                'id'              => $service_sub_2->id,
                                'name'            => $service_sub_2->name,
                                'name_en'         => $service_sub_2->name_en,
                                'status'          => $service_sub_2->active,
                                'service_sub_3'   =>
                                $service_sub_2->service_sub_3->map(function ($service_sub_3) {
                                    return [
                                        'id'              => $service_sub_3->id,
                                        'name'            => $service_sub_3->name,
                                        'name_en'         => $service_sub_3->name_en,
                                        'status'          => $service_sub_3->active,
                                        'service_sub_4'   =>
                                        $service_sub_3->service_sub_4->map(function ($service_sub_4) {
                                            return [
                                                'id'              => $service_sub_4->id,
                                                'name'            => $service_sub_4->name,
                                                'name_en'         => $service_sub_4->name_en,
                                                'status'          => $service_sub_4->active,

                                            ];
                                        })
                                    ];
                                })
                            ];
                        })
                    ];
                })
            ];
        });
        $arry  = [
            'id'              => null,
            'name'            => 'الكل',
            'name_en'         => 'All',
            'status'          => 1
        ];
        $Categories    =  collect($Categories)->map(function ($categories) use ($arry) {

            $subcategories = collect($categories['subcategories'])->map(function ($subcategories) use ($arry) {

                $service_sub_2 = collect($subcategories['service_sub_2'])->map(function ($service_sub_2) use ($arry) {

                    $service_sub_3 = collect($service_sub_2['service_sub_3'])->map(function ($service_sub_3) use ($arry) {
                        $service_sub_4 = $service_sub_3['service_sub_4']->toArray();
                        $service_sub_4 ? array_unshift($service_sub_4, $arry) : false;
                        $service_sub_3['service_sub_4'] = $service_sub_4;
                        return $service_sub_3;
                    })->toArray();
                    $service_sub_3 ? array_unshift($service_sub_3, $arry) : false;
                    $service_sub_2['service_sub_3'] = $service_sub_3;
                    return $service_sub_2;
                })->toArray();
                $service_sub_2 ? array_unshift($service_sub_2, $arry) : false;
                $subcategories['service_sub_2'] = $service_sub_2;
                return $subcategories;
            })->toArray();
            $subcategories ? array_unshift($subcategories, $arry) : false;
            $categories['subcategories'] = $subcategories;
            return $categories;
        })->toArray();

        $Categories ? array_unshift($Categories, $arry) : false;


        $countries =  Countries::where('status', 'ACTIVE')->get()->map(function ($Country) {
            return   [
                'id'        => $Country->id,
                'name'      => $Country->name,
                'name_en'   => $Country->name_en,
                'cities'    => $Country->cities->map(function ($city) {
                    return   [
                        'id'        => $city->id,
                        'name'      => $city->name,
                        'name_en'   => $city->name_en,
                        'street'    => $city->street->map(function ($street) {
                            return   [
                                'id'        => $street->id,
                                'name'      => $street->name,
                                'name_en'   => $street->name_en,
                            ];
                        })
                    ];
                })
            ];
        });
        $countries = collect($countries)->map(function ($Country) use ($arry) {

            $cities    =     collect($Country['cities'])->map(function ($city) use ($arry) {
                $street    =   $city['street']->toArray();
                array_unshift($street, $arry);
                $city['street'] = $street;
                return $city;
            })->toArray();
            array_unshift($cities, $arry);
            $Country['cities'] = $cities;
            return $Country;
        })->toArray();

        array_unshift($countries, $arry);

        array_unshift($ratings, $arry);

        $service       =  Service::where('id', $request['service_id'])->first();


        $data = [
            'is_country_city_street'    =>  $service->is_country_city_street,

            'countries'                 =>  $countries,

            'ratings'                   =>  $ratings,

            'Categories'                =>  $Categories,
        ];

        return response()->data($data);
    }
    public function filtersOnlineService(Request  $request)
    {

        for ($i = 1; $i < 5; $i++) {

            $ratings[] = [

                'id' => "$i",

                'name' => $request->header("x-user-localization") == "ar,SA" ? "اكثر من $i نجوم" : " More then $i stars"

            ];
        }



        $subcategories       =   ServiceSubcategories::where('service_categories_id', $request['service_categories_id'])->where('active', 1)->get();
        $subcategories       =   $subcategories->map(function ($subcategories) {
            return [
                        'id'              => $subcategories->id,
                        'name'            => $subcategories->name,
                        'status'          => $subcategories->active,
                        'service_sub_2'   =>
                        $subcategories->service_sub_2->map(function ($service_sub_2) {
                            return [
                                'id'              => $service_sub_2->id,
                                'name'            => $service_sub_2->name,
                                'status'          => $service_sub_2->active,
                                'service_sub_3'   =>
                                $service_sub_2->service_sub_3->map(function ($service_sub_3) {
                                    return [
                                        'id'              => $service_sub_3->id,
                                        'name'            => $service_sub_3->name,
                                        'status'          => $service_sub_3->active,
                                        'service_sub_4'   =>
                                        $service_sub_3->service_sub_4->map(function ($service_sub_4) {
                                            return [
                                                'id'              => $service_sub_4->id,
                                                'name'            => $service_sub_4->name,
                                                'status'          => $service_sub_4->active,

                                            ];
                                        })
                                    ];
                                })
                            ];
                        })
                    ];
        });
        $arry  = [
            'id'              => null,
            'name'            => 'الكل',
            'status'          => 1
        ];


        $subcategories = collect($subcategories)->map(function ($subcategories) use ($arry) {

            $service_sub_2 = collect($subcategories['service_sub_2'])->map(function ($service_sub_2) use ($arry) {

                $service_sub_3 = collect($service_sub_2['service_sub_3'])->map(function ($service_sub_3) use ($arry) {
                    $service_sub_4 = $service_sub_3['service_sub_4']->toArray();
                    $service_sub_4 ? array_unshift($service_sub_4, $arry) : false;
                    $service_sub_3['service_sub_4'] = $service_sub_4;
                    return $service_sub_3;
                })->toArray();
                $service_sub_3 ? array_unshift($service_sub_3, $arry) : false;
                $service_sub_2['service_sub_3'] = $service_sub_3;
                return $service_sub_2;
            })->toArray();
            $service_sub_2 ? array_unshift($service_sub_2, $arry) : false;
            $subcategories['service_sub_2'] = $service_sub_2;
            return $subcategories;
        })->toArray();

        $subcategories ? array_unshift($subcategories, $arry) : false;


        $countries =  Countries::where('status', 'ACTIVE')->get()->map(function ($Country) {
            return   [
                'id'        => $Country->id,
                'name'      => $Country->name,
                'cities'    => $Country->cities->map(function ($city) {
                    return   [
                        'id'        => $city->id,
                        'name'      => $city->name,
                        'street'    => $city->street->map(function ($street) {
                            return   [
                                'id'        => $street->id,
                                'name'      => $street->name,
                            ];
                        })
                    ];
                })
            ];
        });
        $countries = collect($countries)->map(function ($Country) use ($arry) {

            $cities    =     collect($Country['cities'])->map(function ($city) use ($arry) {
                $street    =   $city['street']->toArray();
                array_unshift($street, $arry);
                $city['street'] = $street;
                return $city;
            })->toArray();
            array_unshift($cities, $arry);
            $Country['cities'] = $cities;
            return $Country;
        })->toArray();

        array_unshift($countries, $arry);

        array_unshift($ratings, $arry);

        $service       =  Service::where('id', $request['service_id'])->first();

        $data = [
            'is_country_city_street'    =>  $service->is_country_city_street,

            'countries'                 =>  $countries,

            'ratings'                   =>  $ratings,

            'subcategories'             =>  $subcategories,
        ];

        return response()->data($data);
    }
    public function createFavourite(Request  $request){

        $data = UserLikedServices::where('user_id', $request->user_id)->where('provider_service_id', $request->provider_service_id)->first();

        if($data)
            $message = 'it\'s already added';
        else
        {
            $message = 'service was created successfully';

            $data    =   UserLikedServices::create([
                'user_id'               => $request->user_id,
                'provider_service_id'  => $request->provider_service_id,
            ]);
        }

        $data->count_liked = UserLikedServices::where('provider_service_id', $request->provider_service_id)->count();

        return response()->data($data, $message);
    }
    public function deleteFavourite(Request  $request)
    {
        $isdeleted   = UserLikedServices::where('user_id', $request->user_id)->where('provider_service_id', $request->provider_service_id)->delete();

        $count_liked = UserLikedServices::where('provider_service_id', $request->provider_service_id)->count();

        $data = ['isdeleted' => $isdeleted, 'count_liked' => $count_liked];

        $message        =   'service was deleted successfully';

        return response()->data($data, $message);
    }
    public function myFavourite(Request  $request)
    {
        $likedServices   = UserLikedServices::where('user_id', $request->user_id)->with('ProviderServices')->has('ProviderServices')->orderBy('created_at', 'desc')->get();

        $likedServices     =  collect($likedServices)->map(function ($item) use ($request) {
            $city = optional($item->providerServices->city)->name ?? "";
            $city = $city != '' ? ' ( ' . (optional($item->providerServices->city)->name ?? "") . ' ) ' : '';
            return  [

                'id'                                => $item->providerServices->id,
                'service_id'                        => $item->providerServices->service_id,
                'service_name'                      => $item->providerServices->service_full->name,
                'provider_id'                       => $item->providerServices->provider->id,
                'provider_name'                     => $item->providerServices->provider->username . $city,
                'provider_just_name'                => $item->providerServices->provider->username,
                'provider_country'                  => $item->providerServices->provider->country->name,
                'provider_skills'                   => $item->providerServices->provider->provider_skills()->with('skill')->get()->pluck('skill')->map(function ($skill) {
                    return [
                        'name'      => $skill->name,
                        'name_en'   => $skill->name_en,
                ];})->toArray(),
                'thumbnail'                         => $item->providerServices->thumbnail ? url('') . $item->providerServices->thumbnail : default_image(),
                'phone'                             => $item->providerServices->provider->country->country_code . $item->providerServices->provider->number_phone,
                'provider_services_title'           => $item->providerServices->title === Null ? get_title(6, $item->providerServices)->name : $item->providerServices->title,
                'specializ'                         => $item->providerServices->specializ,
                'brand'                             => $item->providerServices->brand,
                'stars'                             => $item->providerServices->rating->avg('stars') ?: 5,
                "country_name"                      => optional($item->providerServices->country)->name ?? "",
                "city_name"                         => optional($item->providerServices->city)->name ?? "",
                "street_name"                       => optional($item->providerServices->street)->name ?? "",
                "active"                            => $item->providerServices->provider->active     ?   true    :   false,
                "profile_verified"                  => $item->providerServices->provider->verified   ?   true    :   false,
                "cat_subcat_sub1_sub2_sub3_sub4"    => $item->providerServices->cat_subcat_sub1_sub2_sub3_sub4,
                "country_city_street"               => $item->providerServices->country_city_street,
                "title_from"                        => Service::where('id', $item->providerServices->service_id)->firstOrFail()->title_from,
                "specializ_from"                    => Service::where('id', $item->providerServices->service_id)->firstOrFail()->specializ_from,
                "brand_from"                        => Service::where('id', $item->providerServices->service_id)->firstOrFail()->brand_from,
                "quick_offer"                       => $item->providerServices->quickOffer,
                "offers"                            => sizeof($item->providerServices->offers) ? true : false,
                "pin_top"                           => $item->providerServices->pin_top,
                "is_i_Liked"                        => true,
                'favourites'                        => $item->providerServices->favourites->count('id') ?: 0, 
                'type'                              => $request->header("x-user-localization") == "ar,SA" ? optional($item->providerServices->serviceType)->name : optional($item->providerServices->serviceType)->name_en, 

            ];
        });

        return response()->data($likedServices);
    }
}
