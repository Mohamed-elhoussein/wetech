<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\ServiceOffers;
use Illuminate\Http\Request;

class ServiceOffersApiController extends Controller
{
    public function create(Request  $request)
    {
        $this->validate($request, rules('service.offer'));
        $this->validate($request, rules('offer'));

        $offer          =   Offer::create([
            'description'   =>   $request->description,
            'price'         =>   convertArabicNumber($request->price)
        ]);

        $service_offers =  ServiceOffers::create([
            'service_id' => $request->service_id,
            'offer_id'   => $offer->id,
            'active'     => $request->active   ? $request->active : true
        ]);

        $data           =  $service_offers;
        $message        =  'offer was created successfully';

        return response()->data($data, $message);
    }



    public function delete($id)
    {
        ServiceOffers::findOrFail($id)->delete();

        $message       = 'offer was deleted successfully';

        return response()->message($message);
    }
}
