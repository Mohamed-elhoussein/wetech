<?php

namespace App\Http\Controllers;

use App\Http\BulkActions\BulkAction;
use App\Http\BulkActions\ServiceBulkAction;
use App\Http\Filters\ServiceFilter;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request, ServiceFilter $filter)
    {
        $services = Service::filter($filter)->where(function ($query) use ($request) {
            if ($request->key_search) {
                $query->where('name', 'LIKE', $request->key_search . '%');
            }
        })->paginate(request()->get('limit', 15))->withQueryString();

        return view('services.index', compact('services'));
    }


    public function create()
    {

        return view('services.create');
    }


    public function store(Request  $request)
    {

        $this->validate($request, rules('service.create'));

        Service::create([
            'name'                   =>  $request->name,
            'name_en'                =>  $request->name_en,
            'description'            =>  $request->description,
            'active'                 =>  $request->active ? true : false,
            'join_option'            =>  $request->join_option ? true : false,
            'order_index'            =>  $request->order_index,
            'is_country_city_street' => $request->is_country_city_street,
            'image'                  =>  upload_picture($request->image, '/images/app/services'),
        ]);
        return redirect()->route('services.index')->with('created', 'The service was created ');
    }
    public function edit($id)
    {

        $service = Service::where('id', $id)->first();
        return view('services.edit', compact('service'));
    }
    public function update(Request $request, $id)
    {
        $fields   =   $request->all();

        $service  =    Service::where('id', $id)->first();

        $service->name                      =  $fields['name'];
        $service->name_en                   =  $fields['name_en'];
        $service->description               =  $fields['description'];
        $service->order_index               =  $fields['order_index'];
        $service->is_country_city_street    =  $fields['is_country_city_street'];
        isset($fields['join_option'])   ?   $service->join_option         =  true  : $service->join_option         =  false;
        isset($fields['image'])         ?   $service->image               =  upload_picture($request->image, '/images/app/services') : false;
        isset($fields['active'])        ?   $service->active              =  true                                                   : $service->active              =  false;


        $service->save();
        return redirect()->route('services.index')->with('updated', 'The service was updated ');
    }
    public function delete($id)
    {
        $service   =    Service::FindOrFail($id)->delete();
        return redirect()->back()->with('deleted', 'The service was deleted ');
    }
    public function block($id)
    {
        $service  =   Service::findOrFail($id);

        $service->active   = $service->active   ?   0   :   1;

        $service->save();

        return   redirect()->back();
    }

    public function bulkAction(ServiceBulkAction $serviceBulkAction)
    {
        Service::bulkAction($serviceBulkAction);
    }

    public function getProviderServices(Service $service)
    {
        $data = $service->provider_services()->with('provider')->get()->toArray();
        return response()->data($data);
    }
}
