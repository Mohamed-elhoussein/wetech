<?php

namespace App\Http\Controllers;

use App\Http\BulkActions\CategoryBulkAction;
use App\Http\Filters\ServiceFilter;
use App\Models\Service;
use App\Models\ServiceCategories;
use App\Models\ServiceSub2;
use App\Models\ServiceSub3;
use App\Models\ServiceSub4;
use App\Models\ServiceSubcategories;
use Illuminate\Http\Request;

class ServiceSub4Controller extends Controller


{

    public function index(Request  $request, ServiceFilter $filter)
    {
        $serviceSub4s = ServiceSub4::filter($filter)->where(function ($query) use ($request) {
            if ($request->key_search) {
                $query->where('name', 'LIKE', $request->key_search . '%');
            }
        })->with('service_sub_3', 'service_sub_3.service_sub_2', 'service_sub_3.service_sub_2.service_subcategories')->paginate($request->get('limit', 15))->withQueryString();

        return view('service_sub4.index', compact('serviceSub4s'));
    }
    public function create(Request  $request)
    {

        $services = Service::where('join_option', 1)->has('category')->get(['id', 'name']);
        return view('service_sub4.create', compact('services'));
    }

    public function store(Request $request)
    {
        $this->validate($request, rules('service.sub4.create'));


        ServiceSub4::create([
            'name'                      =>  $request->name,
            'service_sub3_id'           =>  $request->service_sub3_id,
            'image'                     =>  upload_picture($request->image, '/images/services/sub3'),
            'active'                    =>  $request->active ? true : false,

        ]);

        return redirect()->route('service.sub4.index')->with('created', 'service subcategory 2 was created successefly');
    }
    public function edit($id)
    {
        $serviceSub4             =   ServiceSub4::findOrFail($id)
            ->with('service_sub_3:id,service_sub2_id', 'service_sub_3.service_sub_2:id,service_subcategories_id', 'service_sub_3.service_sub_2.service_subcategories:id,service_categories_id', 'service_sub_3.service_sub_2.service_subcategories.service_categories:id,service_id', 'service_sub_3.service_sub_2.service_subcategories.service_categories.services:id')
            ->first();

        $ids = [
            'service_id'                 =>  $serviceSub4->service_sub_3->service_sub_2->service_subcategories->service_categories->services->id,
            'service_categories_id'      =>  $serviceSub4->service_sub_3->service_sub_2->service_subcategories->service_categories->id,
            'service_subcategories_id'   =>  $serviceSub4->service_sub_3->service_sub_2->service_subcategories->id,
            'service_sub2_id'            =>  $serviceSub4->service_sub_3->service_sub_2->id,
            'service_sub3_id'            =>  $serviceSub4->service_sub_3->id,

        ];
        $services                  =   Service::where('join_option', 1)->has('category')->get(['id', 'name']);
        $service_category          =   ServiceCategories::where('service_id', $ids['service_id'])->get(['id', 'name']);
        $service_subcategory       =   ServiceSubcategories::where('service_categories_id', $ids['service_categories_id'])->get(['id', 'name']);
        $service_sub2              =   ServiceSub2::where('service_subcategories_id', $ids['service_subcategories_id'])->get(['id', 'name']);
        $service_sub3              =   ServiceSub3::where('service_sub2_id', $ids['service_sub2_id'])->get(['id', 'name']);

        return view('service_sub4.edit', compact('services', 'serviceSub4', 'ids', 'services', 'service_category', 'service_subcategory', 'service_sub2', 'service_sub3',));
    }
    public function update(Request $request, $id)
    {

        $fields     =     $request->all();

        $service_sub4          =    ServiceSub4::where('id', $id)->first();

        isset($fields['name'])                    ?   $service_sub4->name                      =   $fields['name']                                                     :   false;
        isset($fields['service_sub3_id'])         ?   $service_sub4->service_sub3_id     =   $fields['service_categories_id']                                    :   false;
        isset($fields['image'])                   ?   $service_sub4->image                     =   upload_picture($request->image, '/images/services/sub4')    :   false;
        $service_sub4->active                    =  isset($fields['active'])   ? 1   : 0;

        $service_sub4->save();
        return redirect()->route('service.sub4.index')->with('updated', 'service subcategory 4 was updated successefly');;
    }
    public function delete($id)
    {
        ServiceSub4::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'service subcategory 4 was deleted successefly');
    }
    public function block($id)
    {
        $serviceSub4  =   ServiceSub4::where('id', $id)->first();

        $serviceSub4->active   = $serviceSub4->active   ?   0   :   1;

        $serviceSub4->save();

        return redirect()->back();
    }

    public function bulkAction(CategoryBulkAction $categoryBulkAction)
    {
        ServiceSub4::bulkAction($categoryBulkAction);
    }
}
