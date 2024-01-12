<?php

namespace App\Http\Controllers;

use App\Exports\StreetsExport;
use App\Http\BulkActions\StreetBulkAction;
use App\Http\Filters\StreetFilter;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\Street;
use App\Services\CsvExporterService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StreetController extends Controller
{
    private $exporterService;

    public function __construct(
        CsvExporterService $exporterService
    ) {
        $this->exporterService = $exporterService;
    }

    public function index(Request $request, StreetFilter $filter)
    {
        $streets = Street::filter($filter)->with('cities:id,name,country_id', 'cities.country:id,name')->paginate($request->get('limit', 15))->withQueryString();

        return view('street.index', compact('streets'));
    }


    public function create()
    {
        $countries  =   Countries::all(['id', 'name']);
        return view('street.create', compact('countries'));
    }


    public function store(Request  $request)
    {
        $this->validate($request, rules('street.create'));

        Street::create([
            'name'          =>  $request->name,
            'city_id'       =>  $request->city_id,
        ]);

        if (request()->url) {
            return redirect(request()->url)->with('status', 'The city was created ');
        }

        return redirect()->route('street.index')->with('created', 'The city was created ');
    }
    public function edit($id)
    {
        $countries      =   Countries::all(['id', 'name']);
        $street         =   street::with('cities:id,country_id', 'cities.country:id')->findOrFail($id);
        $cities         =   Cities::where('country_id', $street->cities->country->id)->get(['id', 'name']);

        return view('street.edit', compact('countries', 'cities', 'street'));
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, rules('street.create'));
        $fields   =   $request->all();

        $street  =    Street::findOrFail($id);

        $street->name                 =  $fields['name'];
        $street->city_id              =  $fields['city_id'];

        $street->save();

        return redirect()->route('street.index')->with('updated', 'The street was updated ');
    }
    public function delete($id)
    {
        $street   =    Street::findOrFail($id)->delete();
        return redirect()->back()->with('deleted', 'The Street was deleted ');
    }

    public function export()
    {
        return Excel::download(new StreetsExport, 'streets.xlsx');
    }

    public function bulkAction(StreetBulkAction $bulkaction)
    {
        Street::bulkAction($bulkaction);
    }

    public function create_ajax()
    {
        $countries  =   Countries::all(['id', 'name']);
        return view('street.modal', compact('countries'));
    }
}
