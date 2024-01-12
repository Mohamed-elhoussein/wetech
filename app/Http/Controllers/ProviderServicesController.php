<?php

namespace App\Http\Controllers;

use App\Helpers\FCM;
use App\Http\Filters\ProviderServiceFilter;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Rating;
use App\Models\ProviderServices;
use App\Models\ServiceQuickOffer;
use Illuminate\Http\Request;

class ProviderServicesController extends Controller
{

    public function index(Request $request, ProviderServiceFilter $filter)
    {
        $providersServices   = ProviderServices::filter($filter)->with('provider:id,username')->orderBy('status', 'desc')->paginate($request->get('limit', 15))->withQueryString();

        $providersServices->getCollection()->transform(function ($item) {
            $item->title = ($item->title === Null ? get_title(6, $item)->name : $item->title);
            return $item;
        });

        return view('providers.services.index', compact('providersServices'));
    }

    public function accept($id)
    {
        $providerServices            =   ProviderServices::where('id', $id)->with('provider:id,device_token')->first();
        $providerServices->status    =   'ACCEPTED';
        $providerServices->save();


        $notification                 =  Notification::create([
            'user_id'       => $providerServices->provider->id,
            'icon'          => 'bell_outline_mco',
            'title'         => 'مبروك! تم تفعيل خدمتك ' . ($providerServices->title === Null ? get_title(6, $providerServices)->name : $providerServices->title) . '',
            'message'       => '',
        ]);

        // notify FCM

        $device_token     =   $providerServices->provider->makeVisible(['device_token'])->device_token;

        if ($device_token) {

            $fcm                =    new FCM();

            $title              =    $notification->title;

            $fcm->to($device_token)->message('', $title)->data(Null, 'services_status', '', $title, 'Notifications')->send();


            return redirect()->route('providers.services.index');
        }
    }
    public function reject($id)
    {
        $providerServices            =   ProviderServices::where('id', $id)->with('provider:id,device_token')->first();
        $providerServices->status    =   'REJECTED';
        $providerServices->save();


        $notification                 =     Notification::create([
            'user_id'       => $providerServices->provider->id,
            'icon'          => 'bell_outline_mco',
            'title'         => 'للأسف، تم رفض الخدمة التي أرسلتها ' . ($providerServices->title === Null ? get_title(6, $providerServices)->name : $providerServices->title) . '',
            'message'       => '',
        ]);
        // notify FCM

        $device_token     =   $providerServices->provider->makeVisible(['device_token'])->device_token;

        if ($device_token) {

            $fcm                =    new FCM();

            $title            =    $notification->title;

            $fcm->to($device_token)->message('', $title)->data(Null, 'services_status', '', $title, 'Notifications')->send();
        }
        return redirect()->route('providers.services.index');
    }
    public function delete($id)
    {
        Offer::where('provider_service_id', $id)->delete();
        ProviderServices::where('id', $id)->delete();
        return  redirect()->route('providers.services.index');
    }

    public function quickOffers(Request $request, $id)
    {
        if ($request->ids_remove_offer !== []) {
            ServiceQuickOffer::where('quick_offer_id', $id)->delete();
            // ProviderServices::whereIn('id', $request->ids_remove_offer)->update(['quick_offer_id' => NULL]);
        }
        if ($request->ids_offer !== []) {
            ServiceQuickOffer::insert(
                collect($request->ids_offer)->map(function ($_id) use ($id) {
                    return [
                        'service_id' => $_id,
                        'quick_offer_id' => $id,
                    ];
                })->toArray()
            );
            // ProviderServices::whereIn('id', $request->ids_offer)->update(['quick_offer_id' => $id]);
        }
        return true;
    }
}
