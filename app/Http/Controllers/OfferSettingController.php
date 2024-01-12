<?php

namespace App\Http\Controllers;

use App\Models\OfferSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferSettingController extends Controller
{
    public function __invoke(Request $request)
    {
        /**
         * @var OfferSetting | null $setting
         */
        $setting = OfferSetting::first();

        if ($request->setting) {
            $data = $request->setting;
            $field = explode('_', $data)[0];
            $order = explode('_', $data)[1];
            $field = str_replace('-', '_', $field);
            $data = [
                'order_name' => $field,
                'order_type' => $order
            ];

            if ($setting) {
                $setting->update($data);
                return new JsonResponse([
                    'success' => true
                ]);
            }

            OfferSetting::create($data);
        }
        else {
            $setting->delete();
        }

        return new JsonResponse([
            'success' => true
        ]);
    }
}
