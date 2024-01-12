<?php

namespace App\Http\Controllers\api;

use App\Helpers\FCM;
use App\Http\Controllers\Controller;
use App\Models\ServiceQuickOffer;
use App\Models\ProviderServices;
use App\Models\QuickOffers;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferCanceled;
use App\Models\OfferSetting;
use Illuminate\Support\Collection;

class OfferApiController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, rules('offer'));


        $offer     =  Offer::create([
            'provider_id'         =>    $request->provider_id,
            'provider_service_id' =>    $request->provider_service_id,
            'description'         =>    $request->description,
            'price'               =>    convertArabicNumber($request->price)
        ]);

        $data      =  $offer;
        $message   = 'the offer was create';
        return       response()->data($data, $message);
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, rules('offer'));
        $fields = $request->all();

        $offer  = Offer::where('id', $id)->first();

        isset($fields['description']) ?  $offer->description = $fields['description']  :  false;
        isset($fields['price'])       ?  $offer->price = convertArabicNumber($fields['price'])              :  false;

        $offer->save();

        $data      =  $offer;
        $message   = 'the offer was updated';
        return     response()->data($data, $message);
    }

    public function status(Request $request, $id)
    {

        $this->validate($request, rules('offer.status'));
        $fields = $request->all();
        $authenticated = auth()->user();

        $offer  = Offer::where('id', $id)->first();

        $offer->status   =     $fields['status'];
        $offer->save();

        $user_send_to     = optional(User::find($request->send_to));
        $device_token     = $user_send_to->device_token;

        if ($device_token) {

            $fcm                =    new FCM();

            $offer->type        =    'offer';

            $offer->message_id  =    $request->message_id;

            $message_txt        =    $offer->status == 'CANCELED' ? 'ألغى العرض' : 'رفض عرضك';

            $offer->send_by     =    $authenticated->role == 'chat_review' ? $offer->provider->id       : $authenticated->id;

            $un                 =    $authenticated->role == 'chat_review' ? $offer->provider->username : $authenticated->username;

            $fcm->to($device_token)->message_payload($offer)->message($message_txt, $un)->data($offer->send_by, 'info', $message_txt, $un, 'LiveChat')->send();

            $observers_token  =   User::where('role', 'chat_review')->whereNotIn('id', [auth()->user()->id])->pluck('device_token')->filter()->toArray();

            if ($authenticated->role == 'chat_review')
                $observers_token = Arr::prepend($observers_token, $offer->provider->device_token);

            foreach ($observers_token as $token) $fcm->to($token)->message_payload($offer)->message($message_txt, $un . '  =>  ' . $user_send_to->username)->data($offer->send_by, 'info', $message_txt, $un . '  =>  ' . $user_send_to->username, 'LiveChat')->send();
        }
        $ms = 'قام الزبون ' . auth()->user()->username . 'بإلغاء عرضك المرجوا الرجوع للمحادثة لمعرفة السبب';
        Mail::to($offer->provider)->send(new OfferCanceled($ms));

        $data      =  $offer;
        $message   = 'the offer was updated';
        return     response()->data($data, $message);
    }
    public function delete($id)
    {
        Offer::where('id', $id)->delete();

        $message = 'the offer was deleted';
        return   response()->message($message);
    }
    public function allOffersActive($provider_service_id)
    {
        $Offers                  =   Offer::where('provider_service_id', $provider_service_id)
            ->where('target', 'all')
            ->where('status', 'ACTIVE')->get();

        $Offers->map(function ($item) {
            $item->service_target = $item->service->service_full->target;
            unset($item->service);
            return $item;
        });

        return response()->data($Offers);
    }
    public function allOffers($provider_service_id)
    {
        $Offers                  =   Offer::where('provider_service_id', $provider_service_id)
            ->where('target', 'all')->get();

        $Offers->map(function ($item) {
            $item->service_target = $item->service->service_full->target;
            unset($item->service);
            return $item;
        });

        return response()->data($Offers);
    }
    public function editOffer(Request $request, $offer_id)
    {
        $offer = Offer::where('id', $offer_id)->update([
            'description'           =>  $request['description'],
            'price'                 =>  convertArabicNumber($request['price']),
            'status'                =>  $request['status'],
        ]);


        $providerService    =    Offer::where('provider_service_id', $request['provider_service_id'])->where('target', 'all')->get();

        $message            =  'the offer was edited successfully';

        return response()->data($providerService, $message);
    }
    public function serviceOffersUpdate(Request $request, $provider_service_id)
    {

        $providerService    =    ProviderServices::where('id', $provider_service_id)->with('offers')->firstOrFail();

        $request_added_offers       =    json_decode($request->added_offers,   true);
        $request_removed_offers     =    json_decode($request->removed_offers, true);

        $this->validate($request, rules('providerservices.update'));

        $offers             =      [];

        /* if request has offer to current provider service service */

        if ($request->has('removed_offers') && $providerService) {
            foreach ($request_removed_offers as $offer) {
                Offer::where('id', $offer['id'])->delete();
            };
        }

        if ($request->has('added_offers') && $providerService) {
            foreach ($request_added_offers as $offer) {
                $offers[]           =   Offer::create([
                    'provider_id'           =>  $providerService->provider_id,
                    'provider_service_id'   =>  $providerService->id,
                    'description'           =>  $offer['description'],
                    'price'                 =>  convertArabicNumber($offer['price']),
                    'target'                =>  'ALL',
                    'status'                =>  $offer['status'],
                ]);
            };
        }

        $providerService    =    Offer::where('provider_service_id', $provider_service_id)->where('target', 'all')->get();

        $providerService     =  collect($providerService)->map(function ($item) {
            return  [
                "id"                   => $item->id,
                "provider_id"          => $item->provider_id,
                "provider_service_id"  => $item->provider_service_id,
                "target"               => $item->target,
                "description"          => $item->description,
                "price"                => $item->price,
                "status"               => $item->status,
                'service_target'       => $item->provider_service->service_full->target,
            ];
        });

        $message            =  'the offers was updated successfully';

        return response()->data($providerService, $message);
    }

    public function quickOffers(Request $request, $service_id)
    {
        $fields = $request->all();

        $settings = OfferSetting::first();

        $serviceQuickOffer = ServiceQuickOffer::with([
            'service',
            'quick_offer' => function ($q) use ($settings) {
                if ($settings) {
                    $q->orderBy($settings->order_name, $settings->order_type);
                }
            },
            'service.provider:id,username',
            'service.city:id,name',
            'service.country:id,name'
        ])
            ->whereHas('service', function ($query) use ($service_id) {
                return $query->where('service_id', $service_id);
            });

        if (isset($fields['city_id'])) {
            $serviceQuickOffer = $serviceQuickOffer->whereHas('service', function ($query) use ($fields) {
                return $query->where('city_id', $fields['city_id']);
            });
        } else if (isset($fields['country_id_with_null']) && !isset($fields['isOnline'])) {
            $serviceQuickOffer =  $serviceQuickOffer->whereHas('service', function ($query) use ($fields) {
                return $query->where('country_id', $fields['country_id_with_null'])->orWhereNull('country_id');
            });
        }

        // dd($serviceQuickOffer->get()->toArray());


        $serviceQuickOffer = $serviceQuickOffer->get();


        $cities =  collect($serviceQuickOffer)->map(function ($item) {
            if ($item->service->city_id != null)
                return  [
                    "id"               => $item->service->city_id,
                    "name"             => optional($item->service->city)->name,
                ];
        })->unique();

        $serviceQuickOffer     =  collect($serviceQuickOffer)->map(function ($item) use ($request, $fields) {
            return  [
                "quick_offer_id"                    => $item->quick_offer_id,
                "price"                             => $item->quick_offer->price,
                "created_at"                        => $item->quick_offer->created_at,
                "provider_service_id"               => $item->service->id,
                "service_categories_id"             => $item->service->service_categories_id,
                "provider_id"                       => $item->service->provider_id,
                "provider_name"                     => $item->service->provider->username,
                "country"                           => optional($item->service->country)->name,
                "city"                              => optional($item->service->city)->name,
                "street"                            => optional($item->service->street)->name,
                "body"                              => $request->header("x-user-localization") == 'ar,SA' || $request->header("x-user-localization") == NULL ? optional($item->quick_offer)->body : optional($item->quick_offer)->body_en,
                "image"                             => $item->quick_offer->image,
                "country_city_street"               => $item->service->country_city_street,
                "show_location"                     => !isset($fields['isOnline']),
            ];
        });


        $cities =  $cities->filter()->values()->toArray();

        array_unshift($cities, [
            "id" => "0",
            "name" => "الكل",
        ]);

        $data2 = [
            'cities'       => $cities,
            'quick_offers' => collect($serviceQuickOffer)
                ->sort(function ($p, $n) use ($settings) {
                    // dd($p, $n);
                    if ($settings) {
                        // asc
                        $order = $p[$settings->order_name] > $n[$settings->order_name];

                        if ($settings->order_type === 'desc') {
                            $order = $p[$settings->order_name] < $n[$settings->order_name];
                        }
                        return $order;
                    }
                })->values()->toArray()
        ];

        return response()->data($data2);
    }
}
