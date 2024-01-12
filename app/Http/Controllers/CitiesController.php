<?php

namespace App\Http\Controllers;

use App\Helpers\Loader;
use App\Http\BulkActions\CityBulkAction;
use App\Http\Filters\CityFilter;
use App\Models\Cities;
use App\Models\Countries;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(CityFilter $filter)
    {
        $cities = Cities::filter($filter)->with('country:id,name')->paginate(request()->get('limit', 15))->withQueryString();
        $hasLoadedCities = $cities->total() >= count(ar_cities());

        return view('cities.index', compact('cities', 'hasLoadedCities'));
    }

    public function loadCities()
    {
        $cities = collect(ar_cities())->map(function ($city) {
            return [
                'name' => $city,
                'country_id' => Loader::getCountryId('السعودية'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();


        Cities::insert($cities);

        return redirect('cities');
    }

    public function assignToCountry(Request $request)
    {
        $request->validate([
            'country_id' => [
                'required',
                'numeric',
                'exists:countries,id'
            ],
            'ids' => [
                'required',
                'string'
            ]
        ]);

        Cities::query()->whereIn('id', explode(',', $request->ids))->update([
            'country_id' => $request->country_id
        ]);

        return redirect('cities')->with('created', 'تم تحديث الدولة للمدن المختارة بنجاح');
    }


    public function create()
    {
        $countries  =   Countries::all(['id', 'name']);
        return view('cities.create', compact('countries'));
    }


    public function store(Request  $request)
    {
        $this->validate($request, rules('cities.create'));

        Cities::create([
            'name'          =>  $request->name,
            'name_en'       =>  $request->name_en,
            'country_id'    =>  $request->country_id,
        ]);

        if (request()->url) {
            return redirect(request()->url)->with('status', 'The city was created ');
        }

        return redirect()->route('cities.index')->with('created', 'The city was created ');
    }
    public function edit($id)
    {
        $countries  =   Countries::all(['id', 'name']);
        $city       =   cities::findOrFail($id);

        return view('cities.edit', compact('countries', 'city'));
    }
    public function update(Request $request, $id)
    {
        $fields   =   $request->all();

        $cities  =    cities::findOrFail($id);

        $cities->name                 =  $fields['name'];
        $cities->name_en              =  $fields['name_en'];
        $cities->country_id           =  $fields['country_id'];

        $cities->save();

        return redirect()->route('cities.index')->with('updated', 'The city was updated ');
    }
    public function delete($id)
    {
        $city   =    Cities::findOrFail($id)->delete();
        return redirect()->route('cities.index')->with('deleted', 'The city was deleted ');
    }

    public function bulkAction(CityBulkAction $cityBulkAction)
    {
        Cities::bulkAction($cityBulkAction);
    }

    public function create_ajax()
    {
        $countries  =   Countries::all(['id', 'name']);
        return view('cities.modal', compact('countries'));
    }
}
