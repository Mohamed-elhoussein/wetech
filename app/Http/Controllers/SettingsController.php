<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\PayMethodes;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public static $logo = "";
    public static $icon = "";
    public function index()
    {
        $paymet_methods = PayMethodes::all();

        $settings = Setting::all(['key', 'value'])
            ->keyBy('key')
            ->map(function ($item) {
                return $item->value;
            })
            ->toArray();

        return view('settings',compact( 'settings' ,'paymet_methods'));
    }

    public function update(Request $request)
    {
        $files       =   collect($request->allFiles())->keys()->toArray();
        $settings    =  $this->sittings(collect($request), $files);

        Setting::upsert($settings, ['key'], ['value']);

        return redirect()->route('settings');
    }

    public function sittings($data, $files)
    {
        return $settings   =        $data->except('_token')
            ->keys()
            ->map(function ($keys) use ($data, $files) {
                return  in_array($keys, $files) ?
                    ['key' => $keys, 'value' => upload_picture($data[$keys], '/images/' . $keys . 's')]
                    :
                    ['key' => $keys, 'value' => $data[$keys]];
            })
            ->toArray();
    }
    public function payment(Request $request)
    {

            $paymet_methods = PayMethodes::where('method','paypal')->update(['active'=>$request->PAYPAL_ACTIVE]);
            $paymet_methods = PayMethodes::where('method','myfatoorah')->update(['active'=>$request->MYFATOORAH_ACTIVE]);

            $path = base_path('.env');

            $data = collect($request->all())->except('_token','PAYPAL_ACTIVE','MYFATOORAH_ACTIVE');

           foreach ($data as $key => $value) {

            file_put_contents(app()->environmentFilePath(), str_replace(
                $key . '=' . env($key),
                $key . '=' . $value,
                file_get_contents(app()->environmentFilePath())
            ));
            }

             return redirect()->back();
    }
       public function email(Request $request)
    {

            $paymet_methods = PayMethodes::where('method','paypal')->update(['active'=>$request->PAYPAL_ACTIVE]);
            $paymet_methods = PayMethodes::where('method','myfatoorah')->update(['active'=>$request->MYFATOORAH_ACTIVE]);

            $path = base_path('.env');

            $data = collect($request->all())->except('_token');

           foreach ($data as $key => $value) {

            file_put_contents(app()->environmentFilePath(), str_replace(
                $key . '=' . env($key),
                $key . '=' . $value,
                file_get_contents(app()->environmentFilePath())
            ));
            }

             return redirect()->back();
    }
}
