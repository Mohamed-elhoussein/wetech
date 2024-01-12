<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ServiceSubcategories;
use Illuminate\Http\Request;

class ServiceSubcategoriesApiController extends Controller
{
    public function index()
    {
        $serviceCategories  =   ServiceSubcategories::all();

        $data               =   $serviceCategories;

        return response()->data($data);
    }

    public function create(Request  $request)
    {
        $this->validate($request, ['service_categories_id' => 'required|int']);

        $serviceSubcategories = ServiceSubcategories::create([
            'name'                     =>  $request->name,
            'service_categories_id'    => $request->service_categories_id,
            'image'                    =>  upload_picture($request->image, '/images/subcategories'),
            'active'                   =>  $request->active  ? $request->active  : true
        ]);


        $data = $serviceSubcategories;
        $message =  'Subcategorie was created successfully';

        return response()->data($data, $message);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->all();

        $serviceSubcategories   =   ServiceSubcategories::where('id', $id)->first();

        isset($fields['title'])                   ?   $serviceSubcategories->title = $fields['title']                                                 :   false;
        isset($fields['image'])                   ?   $serviceSubcategories->image = upload_picture($fields['image'], '/images/subcategories')        :   false;
        isset($fields['active'])                  ?   $serviceSubcategories->active = $fields['active'] ? 1   : 0                                    :   false;


        $serviceSubcategories->save();

        $data                 =    $serviceSubcategories;
        $message              =    'subcategorie was updated successfully';

        return response()->data($data, $message);
    }
    public function delete($id)
    {
        ServiceSubcategories::findOrFail($id)->delete();

        $message = 'subcategorie was deleted successfully';

        return response()->message($message);
    }
}
