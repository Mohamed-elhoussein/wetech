<?php

namespace App\Http\Controllers;

use App\Http\BulkActions\CategoryBulkAction;
use App\Http\Filters\ServiceFilter;
use App\Models\Service;
use App\Models\ServiceCategories;
use App\Models\ServiceSub2;
use App\Models\ServiceSubcategories;
use Illuminate\Http\Request;

class ServiceSub2Controller extends Controller
{

    public function index(Request $request, ServiceFilter $filter)
    {
        $serviceSub2 = ServiceSub2::filter($filter)->where(function ($query) use ($request) {
            if ($request->key_search) {
                $query->where('name', 'LIKE', $request->key_search . '%');
            }
        })->with('service_subcategories', 'service_subcategories.service_categories')->paginate($request->get('limit', 15))->withQueryString();

        return view('service_sub2.index', compact('serviceSub2'));
    }
    public function create(Request  $request)
    {

        $services = Service::where('join_option', 1)->has('category')->get(['id', 'name']);
        return view('service_sub2.create', compact('services'));
    }

    public function store(Request $request)
    {

        $this->validate($request, rules('service.sub2.create'));

        ServiceSub2::create([
            'name'                         =>  $request->name,
            'service_subcategories_id'     =>  $request->service_subcategories_id,
            'image'                        =>  upload_picture($request->image, '/images/services/sub2'),
            'active'                       =>  $request->active ? true : false,

        ]);

        return redirect()->route('service.sub2.index')->with('created', 'service subcategory 2 was created successefly');
    }
    public function edit($id)
    {
        $serviceSub2    =   ServiceSub2::findOrFail($id)
            ->with('service_subcategories:id,service_categories_id', 'service_subcategories.service_categories:id,service_id', 'service_subcategories.service_categories.services:id')
            ->first();

        $ids = [
            'service_id'                 =>  $serviceSub2->service_subcategories->service_categories->services->id,
            'service_categories_id'      =>  $serviceSub2->service_subcategories->service_categories->id,
            'service_subcategories_id'   =>  $serviceSub2->service_subcategories->id,
        ];
        $services                  =   Service::where('join_option', 1)->has('category')->get(['id', 'name']);
        $service_category          =   ServiceCategories::where('service_id', $ids['service_id'])->get(['id', 'name']);
        $service_subcategory       =   ServiceSubcategories::where('service_categories_id', $ids['service_categories_id'])->get(['id', 'name']);


        return view('service_sub2.edit', compact('services', 'serviceSub2', 'ids', 'services', 'service_category', 'service_subcategory'));
    }
    public function update(Request $request, $id)
    {

        $fields     =     $request->all();

        $service_sub2          =    ServiceSub2::where('id', $id)->first();

        isset($fields['name'])                       ?   $service_sub2->name                      =   $fields['name']                                                     :   false;
        isset($fields['service_subcategories_id'])   ?   $service_sub2->service_subcategories_id  =   $fields['service_subcategories_id']                                    :   false;
        isset($fields['image'])                      ?   $service_sub2->image                     =   upload_picture($request->image, '/images/services/sub2')    :   false;
        isset($fields['active'])                     ?   $service_sub2->active                    =   $fields['active'] ? 1   : 0                                         :   false;

        $service_sub2->save();
        return redirect()->route('service.sub2.index')->with('updated', 'service subcategory 2 was updated successefly');
    }
    public function delete($id)
    {
        ServiceSub2::findOrFail($id)->delete();
        return redirect()->back()->with('deleted', 'service subcategory 2 was deleted successefly');
    }
    public function block($id)
    {
        $serviceSub2  =   ServiceSub2::findOrFail($id);

        $serviceSub2->active   = $serviceSub2->active   ?   0   :   1;

        $serviceSub2->save();

        return redirect()->back();
    }

    public function bulkAction(CategoryBulkAction $categoryBulkAction)
    {
        ServiceSub2::bulkAction($categoryBulkAction);
    }
}
