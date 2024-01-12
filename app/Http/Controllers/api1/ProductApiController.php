<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductBrand;

class ProductApiController extends Controller
{
      public function products()
    {
        $product  =   Product::all();
        return response()->data($product);
    }
       public function details()
    {
        $product  =   Product::firstOrFail();
        return response()->data($product);
    }

    public function create(Request  $request)
    {
        $this->validate($request, rules('product.create'));
        $fields = $request->all();
          $images             =   collect($fields)->keys()
            ->map(function ($key) {
                return str_starts_with($key, 'image_') ? $key : Null;
            })
            ->whereNotNull()
            ->values()
            ->toArray();

        $gallery            =   [];
        foreach ($images as $image) {
            $gallery[]  =  upload_picture($fields[$image], '/images/product');
        };
         $gallery            =   implode('||', $gallery);
        $product = product::create([
            'user_id'               =>$request->user_id,
            'city_id'               =>$request->city_id,
            'street_id'             =>$request->street_id,
            'product_category_id'   =>$request->product_category_id,
            'product_type_id'       =>$request->product_type_id,
            'product_brand_id'      =>$request->product_brand_id,
            'name'                  =>$request->name,
            'name_en'               =>$request->name_en,
            'images'                =>$gallery,
            'color'                 =>$request->color,
            'disk_info'             =>$request->disk_info,
            'duration_of_use'       =>$request->duration_of_use,
            'guarantee'             =>$request->guarantee,
            'status'                =>$request->status,
            'price'                 =>$request->price,
            'is_offer'              =>$request->is_offer,
            'offer_price'           =>$request->offer_price,
            'description'           =>$request->description,

        ]);
        return response()->data($product, 'product was added successfly');
    }

    public function update(Request $request, $id)
    {
        $fields = $request->all();
        $product  =   Product::firstOrFail();
        $removed_images     =    json_decode($request->removed_images, true);

        /*  get the exesting images and remove the removed images  */

        $removed_images ?  $existingGallery            =      collect(explode('||', $product->images))
            ->map(function ($item) use ($removed_images) {
                return in_array(url($item), $removed_images) ?  Null : $item;
            })
            ->whereNotNull()
            ->values()
            ->toArray()


            :  $existingGallery            = explode('||', $product->images);

        /*  get the new images and upload it */

        $images             =      collect($fields)->keys()
            ->map(function ($key) {
                return str_starts_with($key, 'image_') ? $key : Null;
            })
            ->whereNotNull()
            ->values()
            ->toArray();


        $gallery            =       [];



        foreach ($images as $image) {
            $gallery[]  =  upload_picture($fields[$image], '/images/product');
        };

        $gallery            =       array_merge($existingGallery, $gallery);
        $gallery            =   implode('||', $gallery);

        $product   =   Product::where('id', $id)->first();

        isset($fields['user_id'])                   ?   $product->user_id = $fields['user_id']                        :   false;
        isset($fields['city_id'])                   ?   $product->city_id = $fields['city_id']                        :   false;
        isset($fields['street_id'])                 ?   $product->street_id = $fields['street_id']                    :   false;
        isset($fields['product_category_id'])       ?   $product->product_category_id = $fields['product_category_id']:   false;
        isset($fields['product_type_id'])           ?   $product->product_type_id = $fields['product_type_id']        :   false;
        isset($fields['product_brand_id'])          ?   $product->product_brand_id = $fields['product_brand_id']      :   false;
        isset($fields['name'])                      ?   $product->name = $fields['name']                              :   false;
        isset($fields['name_en'])                   ?   $product->name_en = $fields['name_en']                        :   false;
        isset($fields['color'])                     ?   $product->color = $fields['color']                            :   false;
        isset($fields['disk_info'])                 ?   $product->disk_info = $fields['disk_info']                    :   false;
        isset($fields['duration_of_use'])           ?   $product->duration_of_use = $fields['duration_of_use']        :   false;
        isset($fields['guarantee'])                 ?   $product->guarantee = $fields['guarantee']                    :   false;
        isset($fields['status'])                    ?   $product->status = $fields['status']                          :   false;
        isset($fields['price'])                     ?   $product->price = $fields['price']                            :   false;
        isset($fields['offer_price'])               ?   $product->offer_price = $fields['offer_price']                :   false;
        isset($fields['description'])               ?   $product->description = $fields['description']                :   false;
        $product->images = $gallery;



        $product->save();



        return response()->data($product, 'product was updated successfully');
    }
    public function delete($id)
    {
    Product::findOrFail($id)->delete();
    return response()->message( 'Product was deleted successfully');
    }
    public function productBrands($type_id)
    {
    $bran  =  ProductBrand::findOrFail($type_id)->all();
    return response()->json( $bran);
    }

}
