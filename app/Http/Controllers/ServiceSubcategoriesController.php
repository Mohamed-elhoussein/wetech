<?php

namespace App\Http\Controllers;

use App\Http\BulkActions\CategoryBulkAction;
use App\Http\Filters\ServiceFilter;
use App\Models\Service;
use App\Models\ServiceCategories;
use App\Models\ServiceSubcategories;
use Illuminate\Http\Request;

class ServiceSubcategoriesController extends Controller
{

    public function index(Request  $request, ServiceFilter $filter)
    {
        $serviceSubcategories = ServiceSubcategories::filter($filter)->where(function ($query) use ($request) {
            if ($request->key_search) {
                $query->where('name', 'LIKE', $request->key_search . '%');
            }
        })->with('service_categories:id,name,service_id', 'service_categories.services:id,name')->paginate($request->get('limit', 15))->withQueryString();

        return view('service_subcategories.index', compact('serviceSubcategories'));
    }
    public function create(Request  $request)
    {

        $services = Service::where('join_option', 1)->has('category')->get(['id', 'name']);
        return view('service_subcategories.create', compact('services'));
    }

    public function store(Request $request)
    {
        $this->validate($request, rules('service.subcategory.create'));
        ServiceSubcategories::create([
            'name'                      =>  $request->name,
            'service_categories_id'     =>  $request->service_categories_id,
            'image'                     =>  upload_picture($request->image, '/images/services/subcategories'),
            'active'                    =>  $request->active ? true : false,

        ]);

        return redirect()->route('service.subcategories.index')->with('created', 'service subcategory was created successefly');
    }
    public function edit($id)
    {

        $serviceSubcategories    =   ServiceSubcategories::where('id', $id)
            ->with('service_categories:id,service_id', 'service_categories.services:id')
            ->first();

        $ids = [
            'service_id'                 =>  $serviceSubcategories->service_categories->services->id,
            'service_categories_id'      =>  $serviceSubcategories->service_categories->id,
        ];
        $services                  =   Service::where('join_option', 1)->has('category')->get(['id', 'name']);
        $service_category          =   ServiceCategories::where('service_id', $ids['service_id'])->get(['id', 'name']);

        return view('service_subcategories.edit', compact('services',  'ids', 'services', 'service_category', 'serviceSubcategories'));
    }
    public function update(Request $request, $id)
    {

        $fields     =     $request->all();

        $service_subcategories          =    ServiceSubcategories::where('id', $id)->first();

        isset($fields['name'])                    ?   $service_subcategories->name                      =   $fields['name']                                                     :   false;
        isset($fields['service_categories_id'])   ?   $service_subcategories->service_categories_id     =   $fields['service_categories_id']                                    :   false;
        isset($fields['image'])                   ?   $service_subcategories->image                     =   upload_picture($request->image, '/images/services/subcategories')    :   false;
        isset($fields['active'])                  ?   $service_subcategories->active                    =   $fields['active'] ? 1   : 0                                         :   false;

        $service_subcategories->save();
        return redirect()->route('service.subcategories.index')->with('updated', 'service subcategory was updated successefly');
    }
    public function delete($id)
    {
        ServiceSubcategories::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'service subcategory was deleted successefly');
    }
    public function block($id)
    {
        $serviceSubcategories  =   ServiceSubcategories::where('id', $id)->first();

        $serviceSubcategories->active   = $serviceSubcategories->active   ?   0   :   1;

        $serviceSubcategories->save();

        return redirect()->back();
    }

    public function bulkAction(CategoryBulkAction $categoryBulkAction)
    {
        ServiceSubcategories::bulkAction($categoryBulkAction);
    }
}
