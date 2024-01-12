<?php

namespace App\Http\Controllers;

use App\Http\Filters\MaintenanceFilter;
use App\Models\Brand;
use App\Models\Cities;
use App\Models\Color;
use App\Models\Countries;
use App\Models\Issues;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceRequestOrder;
use App\Models\MaintenanceRequestType;
use App\Models\MaintenanceType;
use App\Models\Models;
use App\Models\Service;
use App\Models\Street;
use App\Models\User;
use Illuminate\Http\Request;

class MaintenanceShopController extends Controller
{

    public function index(MaintenanceFilter $filter)
    {
        $maintenance_requests = MaintenanceRequest::query()
            ->filter($filter)
            ->latest('id')
            ->with([
                'service',
                'brand',
                'model',
                'color',
                'issue',
                'countries',
                'cities',
                'streets',
                'countries.country',
                'cities.city',
                'streets.street',
            ])
            ->paginate();

        return view('main.index', [
            'requests' => $maintenance_requests
        ]);
    }

    public function create()
    {
        $services = Service::all();
        $brands = Brand::all();
        $models = Models::all();
        $colors = Color::all();
        $issues = Issues::all();
        $countries = Countries::active()->get();
        $cities = Cities::all();
        $streets = Street::all();
        $types = MaintenanceRequestType::all();
        $providers = User::provider()->get();

        return view('main.create', [
            'services'  => $services,
            'brands'    => $brands,
            'models'    => $models,
            'colors'    => $colors,
            'issues'    => $issues,
            'countries' => $countries,
            'cities'    => $cities,
            'streets'   => $streets,
            'types'     => $types,
            'providers' => $providers
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "service_id" => "required|numeric|exists:services,id",
            "brand_id" => "required|numeric|exists:brands,id",
            "models_id" => "required|numeric|exists:models,id",
            // "color_id" => "required|numeric|exists:colors,id",
            "issues_id" => "required|numeric|exists:issues,id",
            "country_id" => "required|array|exists:countries,id",
            "city_id" => "required|array|exists:cities,id",
            "street_id" => "required|array",
            "meta" => "required|array",
            "colors" => "required|array",
        ]);

        $maintenance_request = MaintenanceRequest::create($data);

        $maintenance_type = collect($data['meta'])->map(function ($meta) use ($maintenance_request) {
            return [
                'price' => $meta['price'],
                'type_id' => $meta['type_id'],
                'provider_id' => $meta['provider_id'],
                'maintenance_request_id' => $maintenance_request->id
            ];
        })->toArray();

        MaintenanceType::insert($maintenance_type);

        $maintenance_request->update_location($data, true);

        $maintenance_request->colors()->sync($data['colors']);

        return redirect()->route('main.index')->with('status', 'تم إضافة عرض الصيانة بنجاح');
    }

    public function edit(MaintenanceRequest $request)
    {
        $request->load('streets', 'streets.street');
        $services = Service::all();
        $brands = Brand::all();
        $models = Models::all();
        $colors = Color::all();
        $issues = Issues::all();
        $countries = Countries::active()->get();
        $cities = Cities::all();
        $streets = Street::all();
        $types = MaintenanceRequestType::all();
        $providers = User::provider()->get();

        return view('main.edit', [
            'request'   => $request,
            'services'  => $services,
            'brands'    => $brands,
            'models'    => $models,
            'colors'    => $colors,
            'issues'    => $issues,
            'countries' => $countries,
            'cities'    => $cities,
            'streets'   => $streets,
            'types'     => $types,
            'providers' => $providers,
        ]);
    }

    public function update(Request $httpRequest, MaintenanceRequest $request)
    {
        $data = $httpRequest->validate([
            "service_id" => "required|numeric|exists:services,id",
            "brand_id" => "required|numeric|exists:brands,id",
            "models_id" => "required|numeric|exists:models,id",
            "issues_id" => "required|numeric|exists:issues,id",
            "country_id" => "required|array|exists:countries,id",
            "city_id" => "required|array|exists:cities,id",
            "street_id" => "required|array",
            "meta" => "required|array",
            "colors" => "required|array",
        ]);

        $request->update($data);
        $request->update_types($data);
        $request->update_location($data, true);
        $request->colors()->sync($data['colors']);

        return redirect()->route('main.index')->with('status', 'تم تعديل عرض الصيانة بنجاح');
    }

    public function destroy(MaintenanceRequest $request)
    {
        $request->delete();

        return redirect()->route('main.index')->with('status', 'تم حذف عرض الصيانة بنجاح');
    }

    public function get_orders()
    {
        $orders = MaintenanceRequestOrder::query()
            ->latest('id')
            ->whereHas('maintenance_type')
            ->with([
                'maintenance_type',
                'maintenance_type.maintenance_request',
                'maintenance_type.maintenance_request.issue',
                'provider:id,email,username',
            ])
            ->paginate()
        ;

        // dd($orders->toArray());

        return view('main.orders', compact('orders'));
    }
}

