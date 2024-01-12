<?php

namespace App\Http\Controllers;

use App\Http\BulkActions\CategoryBulkAction;
use App\Http\Filters\ServiceFilter;
use App\Models\Service;
use App\Models\ServiceCategories;
use App\Models\ServiceSub2;
use App\Models\ServiceSub3;
use App\Models\ServiceSubcategories;
use Illuminate\Http\Request;

class ServiceSub3Controller extends Controller
{

    public function index(Request  $request, ServiceFilter $filter)
    {
        $serviceSub3s = ServiceSub3::filter($filter)->where(function ($query) use ($request) {
            if ($request->key_search) {
                $query->where('name', 'LIKE', $request->key_search . '%');
            }
        })->with('service_sub_2', 'service_sub_2.service_subcategories',)->paginate($request->get('limit', 15))->withQueryString();

        return view('service_sub3.index', compact('serviceSub3s'));
    }
    public function create(Request  $request)
    {

        $services = Service::where('join_option', 1)->has('category')->get(['id', 'name']);
        return view('service_sub3.create', compact('services'));
    }

    public function store(Request $request)
    {

        $this->validate($request, rules('service.sub3.create'));
        ServiceSub3::create([
            'name'                      =>  $request->name,
            'service_sub2_id'          =>  $request->service_sub2_id,
            'image'                     =>  upload_picture($request->image, '/images/services/sub3'),
            'active'                    =>  $request->active ? true : false,
        ]);

        return redirect()->route('service.sub3.index')->with('created', 'service subcategory 3 was created successefly');
    }
    public function edit($id)
    {
        $services                =   Service::where('join_option', 1)->has('category')->get(['id', 'name']);
        $serviceSub3    =   ServiceSub3::findOrFail($id)
            ->with('service_sub_2:id,service_subcategories_id', 'service_sub_2.service_subcategories:id,service_categories_id', 'service_sub_2.service_subcategories.service_categories:id,service_id', 'service_sub_2.service_subcategories.service_categories.services:id')
            ->first();

        $ids = [
            'service_id'                 =>  $serviceSub3->service_sub_2->service_subcategories->service_categories->services->id,
            'service_categories_id'      =>  $serviceSub3->service_sub_2->service_subcategories->service_categories->id,
            'service_subcategories_id'   =>  $serviceSub3->service_sub_2->service_subcategories->id,
            'service_sub2_id'            =>  $serviceSub3->service_sub_2->id,


        ];
        $services                  =   Service::where('join_option', 1)->has('category')->get(['id', 'name']);
        $service_category          =   ServiceCategories::where('service_id', $ids['service_id'])->get(['id', 'name']);
        $service_subcategory       =   ServiceSubcategories::where('service_categories_id', $ids['service_categories_id'])->get(['id', 'name']);
        $service_sub2              =   ServiceSub2::where('service_subcategories_id', $ids['service_subcategories_id'])->get(['id', 'name']);

        return view('service_sub3.edit', compact('services', 'serviceSub3', 'ids', 'services', 'service_category', 'service_subcategory', 'service_sub2',));
    }
    public function update(Request $request, $id)
    {

        $fields     =     $request->all();

        $service_sub3          =    ServiceSub3::where('id', $id)->first();

        isset($fields['name'])                    ?   $service_sub3->name                      =   $fields['name']                                                     :   false;
        isset($fields['service_sub2_id'])         ?   $service_sub3->service_sub2_id           =   $fields['service_sub2_id']                                          :   false;
        isset($fields['image'])                   ?   $service_sub3->image                     =   upload_picture($request->image, '/images/services/sub3')            :   false;
        $service_sub3->active                    =   isset($fields['active']) ? 1   : 0;

        $service_sub3->save();
        return redirect()->route('service.sub3.index')->with('updated', 'service subcategory 3 was updated successefly');
    }
    public function delete($id)
    {
        ServiceSub3::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'service subcategory 3 was deleted successefly');
    }
    public function block($id)
    {
        $serviceSub3  =   ServiceSub3::where('id', $id)->first();

        $serviceSub3->active   = $serviceSub3->active   ?   0   :   1;

        $serviceSub3->save();

        return redirect()->back();
    }

    public function bulkAction(CategoryBulkAction $categoryBulkAction)
    {
        ServiceSub3::bulkAction($categoryBulkAction);
    }
}
