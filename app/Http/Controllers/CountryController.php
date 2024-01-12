<?php

namespace App\Http\Controllers;

use App\Exports\CountriesExport;
use App\Http\BulkActions\CountryBulkAction;
use App\Models\Countries;
use App\Services\CsvExporterService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CountryController extends Controller
{
    private $exporterService;

    public function __construct(CsvExporterService $exporterService)
    {
        $this->exporterService = $exporterService;
    }

    public function index(Request $request)
    {
        $countries = Countries::where(function ($query) use ($request) {
            if ($request->key_search) {
                $query->where('name', 'LIKE', $request->key_search . '%');
                $query->orWhere('country_code', 'LIKE', '%' . $request->key_search . '%');
                $query->orWhere('unit', 'LIKE', '%' . $request->key_search . '%');
                $query->orWhere('unit_en', 'LIKE', '%' . $request->key_search . '%');
            }
            if ($request->country_status) {
                $query->where('status',  $request->country_status);
            }
        })->orderBy('status')->paginate($request->get('limit', 15))->withQueryString();

        return view('countries.index', compact('countries'));
    }


    public function create()
    {

        return view('countries.create');
    }


    public function store(Request  $request)
    {
        $this->validate($request, rules('country.create'));

        Countries::create([
            'name'          =>  $request->name,
            'unit'          =>  $request->unit,
            'unit'          =>  $request->unit_en,
            'code'          =>  $request->code,
            'country_code'  =>  $request->country_code,
            'status'        =>  $request->status,
            'message'       =>  $request->message,
            'pin'           => $request->pin,
        ]);

        if (request()->url) {
            return redirect(request()->url)->with('status', 'The country was created ');
        }

        return redirect()->route('countries.index')->with('created', 'The country was created ');
    }
    public function edit($id)
    {

        $country = Countries::findOrFail($id);
        return view('countries.edit', compact('country'));
    }
    public function update(Request $request, $id)
    {
        $fields   =   $request->all();

        $countries  =    countries::findOrFail($id);

        $countries->name                 =  $fields['name'];
        $countries->unit                 =  $fields['unit'];
        $countries->unit_en              =  $fields['unit_en'];
        $countries->code                 =  $fields['code'];
        $countries->country_code         =  $fields['country_code'];
        $countries->status               =  $fields['status'];
        $countries->message              =  $fields['message'];
        $countries->pin                  =  $fields['pin'];


        $countries->save();

        return redirect()->route('countries.index')->with('updated', 'The country was updated ');
    }
    public function delete($id)
    {
        $country   =    Countries::findOrFail($id)->delete();
        return redirect()->back()->with('deleted', 'The country was deleted ');
    }
    public function block($id)
    {
        $service  =   Countries::findOrFail($id);

        $service->status   = $service->status  == 'ACTIVE' ?   'UNACTIVE'   : 'ACTIVE';

        $service->save();

        return   redirect()->back();
    }

    public function export()
    {
        return Excel::download(new CountriesExport, 'countries.xlsx');
    }

    public function bulkAction(CountryBulkAction $countryBulkAction)
    {
        Countries::bulkAction($countryBulkAction);
    }

    public function create_ajax()
    {
        return view('countries.modal');
    }
}
