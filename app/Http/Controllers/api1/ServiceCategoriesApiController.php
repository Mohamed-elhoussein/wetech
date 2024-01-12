<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategories;
use Illuminate\Http\Request;

class ServiceCategoriesApiController extends Controller
{
    public function index()
    {
        $serviceCategories  = ServiceCategories::all();

        $data               = $serviceCategories;

        return response()->data($data);
    }

    public function providers($service_categories_id)
    {
        $serviceCategories  =    ServiceCategories::where('id', $service_categories_id)->first();

        $providers          =    $serviceCategories->providers->map(function ($item) {
            return $item->provider;
        });

        $data = ['providers' => $providers];
        return response()->data($data);
    }    
    public function category($service_categories_id)
    {
        $serviceCategories  =    ServiceCategories::where('service_id', $service_categories_id)->where('active',1)->get();

/*         $providers          =    $serviceCategories->providers->map(function ($item) {
            return $item->provider;
        });

        $data = ['providers' => $providers]; */
        return response()->data($serviceCategories);
    }
    public function subcategories($service_categories_id)
    {
        $serviceCategories =    ServiceCategories::where('id', $service_categories_id)->with('subcategories')->first();

        $data              =    $serviceCategories->subcategories;

        return response()->data($data);
    }
    public function create(Request  $request)
    {
        $this->validate($request, ['name' => 'required|unique:service_categories,name']);

        $ServiceCategories = ServiceCategories::create([
            'name'          =>  $request->name,
            'service_id'    =>  $request->service_id,
            'image'         =>  upload_picture($request->image, '/images/categories'),
            'active'        =>  $request->active  ? $request->active  : true
        ]);


        $data = $ServiceCategories;
        $message =  'Categorie was created successfully';

        return response()->data($data, $message);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['unique:service_categories,name']);
        $fields = $request->all();

        $service_categories = ServiceCategories::where('id', $id)->first();

        isset($fields['name'])                    ?   $service_categories->name = $fields['name']                                       :   false;
        isset($fields['image'])                   ?   $service_categories->image = upload_picture($request->image, '/images/categories')   :   false;
        isset($fields['active'])                  ?   $service_categories->active = $fields['active'] ? 1   : 0                          :   false;


        $service_categories->save();

        $data = $service_categories;
        $message =  'Categorie was updated successfully';

        return response()->data($data, $message);
    }
    public function delete($id)
    {
        ServiceCategories::findOrFail($id)->delete();

        $message = 'categorie was deleted successfully';

        return response()->message($message);
    }
}
