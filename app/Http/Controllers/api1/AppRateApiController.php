<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AppRates;
use Illuminate\Http\Request;

class AppRateApiController extends Controller
{
    public function create(Request $request )
    {
      $this->validate($request,rules('app.rates'));
      $user_id      =   auth()->user()->id;

      $appRates     =  AppRates::create([
                         'user_id'             =>    $user_id  ,
                         'stars'               =>    $request->stars,
                         'comment'             =>    $request->comment ,
                           ]);

      $data      =  $appRates;
      $message   = 'the application rate was create';
      return       response()->data($data,$message);
    }
    public function update(Request $request , $id)
    {

      $this->validate($request,rules('app.rates'));
      $fields = $request->all();

      $rate  = AppRates::where( 'id', $id )->first();

      isset($fields['comment']) ?  $rate->comment = $fields['comment']  :  false;
      isset($fields['stars'])   ?  $rate->stars = $fields['stars']      :  false;

      $rate->save();

      $data      =  $rate;
      $message   = 'the application rate was updated';
      return     response()->data($data,$message);
    }


    
    public function delete($id)
    {
      AppRates::where( 'id', $id )->delete();

      $message = 'the  app rate was deleted';
      return   response()->message($message);

    }
}
