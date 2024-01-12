<?php

namespace App\Http\Controllers;

use App\Http\BulkActions\CategoryBulkAction;
use App\Http\Filters\ServiceFilter;
use App\Models\Service;
use App\Models\ServiceCategories;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class ServiceCategoriesController extends Controller
{

    public function index(Request $request, ServiceFilter $filter)
    {
        $categoriesServices = ServiceCategories::filter($filter)->where(function ($query) use ($request) {
            if ($request->key_search) {
                $query->where('name', 'LIKE', $request->key_search . '%');
            }
        })->with('services:id,name')->paginate(request()->get('limit', 15))->withQueryString();
        return view('service_categories.index', compact('categoriesServices'));
    }

    public function create()
    {

        $services = Service::where('join_option', 1)->get(['id', 'name']);
        return view('service_categories.create', compact('services'));
    }

    public function store(Request  $request)
    {
        $this->validate($request, rules('service.category.create'));

        ServiceCategories::create([
            'name' => $request->name,
            'service_id' => $request->service_id,
            'active' => $request->active ? true : false,
            'image' => upload_picture($request->image, '/images/service/categories')
        ]);
        return redirect()->route('service_categories.index')->with('created', 'service category was created successefly');
    }
    public function edit($id)
    {

        $serviceCategory = ServiceCategories::findOrFail($id);
        $services = Service::where('join_option', 1)->get(['id', 'name']);


        return view('service_categories.edit', compact('services', 'serviceCategory'));
    }
    public function update(Request $request, $id)
    {
        $fields                  =     $request->all();

        $serviceCategories       =     ServiceCategories::where('id', $id)->firstOrFail();

        isset($fields['name'])              ?    $serviceCategories->name       = $fields['name']        : false;
        isset($fields['service_id'])        ?    $serviceCategories->service_id = $fields['service_id']  : false;
        isset($fields['image'])             ?    $serviceCategories->image      = upload_picture($fields['image'], '/images/service/categories')   : false;
        $serviceCategories->active          =    isset($fields['active']) ? 1 : 0;

        $serviceCategories->save();
        return redirect()->route('service_categories.index')->with('updated', 'service category was updated successefly');
    }
    public function delete($id)
    {
        ServiceCategories::findOrFail($id)->delete();
        return redirect()->back()->with('deleted', 'service category was deleted successefly');;
    }
    public function block($id)
    {
        $serviceCategories  =   ServiceCategories::where('id', $id)->first();

        $serviceCategories->active   = $serviceCategories->active   ?   0   :   1;

        $serviceCategories->save();
        return redirect()->back();
    }

    public function BulkAction(CategoryBulkAction $categoryBulkAction)
    {
        ServiceCategories::BulkAction($categoryBulkAction);
    }
}
