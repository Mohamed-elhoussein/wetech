<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProviderFilter;
use App\Http\Filters\ProviderServiceFilter;
use App\Http\Filters\QuickOfferFilter;
use App\Models\ProviderServices;
use App\Models\QuickOffers;
use App\Models\Service;
use App\Models\OfferSetting;
use App\Models\ServiceQuickOffer;
use Illuminate\Http\Request;

class QuickOffersController extends Controller
{
    public function index(QuickOfferFilter $filter)
    {
        $quickOffers = QuickOffers::filter($filter)->paginate(request()->get('limit', 15))->withQueryString();
        $setting = OfferSetting::first();
        return view('quick_offers.index', compact('quickOffers', 'setting'));
    }

    public function create()
    {
        return view('quick_offers.create');
    }

    public function store(Request  $request)
    {
        $this->validate($request, ['title' => 'required', 'string']);

        QuickOffers::create([
            'title'      => $request->title,
            'title_en'   => $request->title_en,
            'body'       => $request->body,
            'body_en'    => $request->body_en,
            'image'  => upload_picture($request->file('image'), '/image/quickOffers'),
            'price' => $request->get('price')
        ]);
        return redirect()->route('quick_offers.index')->with('created', 'The quick offers  was created ');
    }
    public function edit($id)
    {

        $quickOffers = QuickOffers::findOrFail($id);
        return view('quick_offers.edit', compact('quickOffers'));
    }
    public function update(Request $request, $id)
    {

        $quickOffers  = QuickOffers::findOrFail($id);
        $fields   = $request->all();

        isset($fields['title'])     ?  $quickOffers->title       =   $request->title    : false;
        isset($fields['title_en'])  ?  $quickOffers->title_en    =   $request->title_en : false;
        isset($fields['body'])      ?  $quickOffers->body        =   $request->body     : false;
        isset($fields['body_en'])   ?  $quickOffers->body_en     =   $request->body_en  : false;
        isset($fields['image'])     ?  $quickOffers->image       =   upload_picture($request->file('image'), '/image/quickOfferss')   : false;
        isset($fields['price'])   ?  $quickOffers->price     =   $request->price  : false;

        $quickOffers->save();

        return redirect()->route('quick_offers.index')->with('updated', 'The quickOffers message was updated ');
    }
    public function delete($id)
    {
        QuickOffers::findOrFail($id)->delete();
        return redirect()->back()->with('deleted', 'The quickOffers message was deleted ');
    }

    public function providerService(Request $request, $id, ProviderServiceFilter $filter)
    {
        $providerservices = ProviderServices::filter($filter)->where(function ($query) use ($request) {
            return  $request->service ? $query->where('service_id', $request->service) : $query;
        })->where('status', 'ACCEPTED')->with('provider:id,username,country_id,number_phone', 'provider.country:id,country_code', 'city:name,id')->get();
        $services = Service::where('join_option', 1)->get(['id', 'name']);

        $quickOffer = QuickOffers::with('service_quick_offers')->findOrFail($id);
        $serviceIds = $quickOffer->service_quick_offers->pluck('service_id')->toArray();

        return view('quick_offers.provider_services_offers', compact('providerservices', 'services', 'id', 'serviceIds', 'quickOffer'));
    }

    public function getServiceOffers(Service $service)
    {
        $providerServices = $service->provider_services()->get()->pluck('id')->unique()->toArray();

        return [
            'offers' => [...ServiceQuickOffer::whereIn('service_id', $providerServices)->with('quick_offer')->get()->pluck('quick_offer')->unique()->toArray()]
        ];
    }
}
