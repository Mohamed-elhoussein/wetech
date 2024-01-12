<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\Street;
use App\Models\Countries;
use App\Models\Faq;
use App\Models\Service;
use App\Models\ServiceCategories;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Welcome;
use Illuminate\Http\Request;

class ConfigApiController extends Controller
{
    public function config(Request $request)
    {

        $settings = Setting::all(['key', 'value'])
            ->keyBy('key')
            ->map(function ($item) {
                return $item->value;
            })
            ->toArray();

        $config     = [
            'services'   =>    Service::where('active', 1)->orderBy('order_index')->get()->map(function ($service) {
                return [
                    'id'            => $service->id,
                    'name'          => $service->name,
                    'name_en'       => $service->name_en,
                    'description'   => $service->description,
                    'target'        => $service->target ,
                    'join_option'   => $service->join_option,
                    'icon'          => $service->image ? url('') . $service->image : default_image(),
                    'order_index'   => $service->order_index,
                    'status'        => 'Active',
                    'active'        => $service->active
                ];
            }),
            'countries'  =>    Countries::where('status', 'ACTIVE')->with('cities')->get(),
            'welcome'    =>    Welcome  ::where('target', $request->header('x-app-type') === 'PROVIDER_APP' ? 'PROVIDER_APP' : 'CLIENT_APP')->get()->map(function ($item) {
                                            $item->image = url($item->image);
                                            return $item;
                                        }),
            'slider'      =>    Slider::where('active', 1)->where('target', 'HOME')->with('slider_urls')->get()->map(function ($slider) {
                return
                    [
                        'id'                        => $slider->id,
                        'image'                     => url('') . $slider->image,
                        "text"                      => "إتصل بالمعلن",
                        "text_en"                   => "Call advertiser",
                        'url'                       => $slider->phone ? 'tel:' . $slider->phone  : $slider->url ?? "",
                        'urls'                      => $slider->slider_urls,
                        "icon"                      => "phone_faw",
                        'btn_color'                 => '#344f64',
                        'text_color'                => '#ffffff',
                        'icon_color'                => '#ffffff',
                        'btn_shadow_white_or_black' => 0,
                        'visitableBtn'              => $slider->visitableBtn ? true : false,
                        'target'                    => $slider->target,
                        'created_at'                => $slider->created_at
                    ];
            }),
            'provider_store_app_link' => [
                'url_ios'       => $settings['provider_apple_store'] ?? '',
                'url_android'   => $settings['provider_play_store'] ?? '',
            ],
            'client_store_app_link' => [
                'url_ios'       => $settings['client_apple_store'] ?? '',
                'url_android'   => $settings['client_play_store'] ?? ''
            ],

            'monitor_store_app_link' => [
                'url_ios'       => $settings['monitor_apple_store'] ?? '',
                'url_android'   => $settings['monitor_play_store'] ?? ''
            ],
            'settings'   =>    [
                [
                    "id" => "19",
                    "name" => "active_subscribe",
                    "type" => "bool",
                    "value" => $settings["active_subscribe"] ?? '0',
                    "options" => ""
                ],
            ],
            'contects'   => [
                [
                    "id" => "1",
                    "contect" => $settings['contact_email'] ?? '',
                    "name" => "7",
                    "icon" => "email_mco"
                ],
                [
                    "id" => "2",
                    "contect" => $settings['phone'] ?? '',
                    "name" => "6",
                    "icon" => "mobile_fou"
                ]
            ],
            'verification_code'      =>[
                "id" => "2",
                "contect" => $settings['whatsapp_provider_url'] ?? '',
                "name" => "6",
                "icon" => "whatsapp_faw"
            ],
            'about'              => $settings['description'] ?? '',
            'privacy_policy'     => $settings['privacy_policy'] ?? '',
            'sharing'   => collect([
                isset($settings['facebook_url']) ?
                    [
                        'url' => $settings['facebook_url'],
                        'icon' => isset($settings['facebook_logo']) ? url($settings['facebook_logo']) : '',
                    ]
                    : null,
                isset($settings['twiter_url']) ?
                    [
                        'url' => $settings['twiter_url'],
                        'icon' => isset($settings['twiter_logo']) ? url($settings['twiter_logo']) : '',
                    ] :
                    null,
                isset($settings['instagram_url']) ?
                    [
                        'url' => $settings['instagram_url'],
                        'icon' => isset($settings['instagram_logo']) ? url($settings['instagram_logo']) : '',
                    ]
                    : null,
                isset($settings['linkden_url']) ? [
                    'url' => $settings['linkden_url'],
                    'icon' => isset($settings['linkden_logo']) ? url($settings['linkden_logo']) : '',
                ] : null,
                isset($settings['youtobe_url']) ? [
                    'url' => $settings['youtobe_url'],
                    'icon' => isset($settings['youtobe_logo']) ? url($settings['youtobe_logo']) : '',
                ] : null,
                isset($settings['whatsapp_url']) ? [
                    'url' => $settings['whatsapp_url'],
                    'icon' => isset($settings['whatsapp_logo']) ? url($settings['whatsapp_logo']) : '',
                ] : null,
            ])
                ->filter()
                ->values(),

            "header" => [
                "Cookie"          => "PHPSESSID=05c482071b05b1c3321b804e380c5336",
                "Connection"      => "keep-alive",
                "Accept-Encoding" => "gzip, deflate, br",
                "Host"            => "server.drtechapp.com",
                "Postman-Token"   => "04a4b86a-9fc5-4734-8899-f1bdc6530e5e",
                "Accept"          => "*/*",
                "User-Agent"      => "PostmanRuntime/7.28.4"
            ],
            "os" => "",
            "build_number" => "",
            "app_version" => "",

        ];

        $localisation       =   [
            'languages_names'   => [
                'ar,SA'  => 'العربية',
                'en,US'  => 'English'
            ],
            'data'       => [
                'ar,SA'  =>       include public_path('config/ar.php'),
                'en,US'  =>       include public_path('config/eng.php'),
            ],
            'default'   =>  $settings['lang'] ?? 'ar,SA',
        ];

        if($request->header('x-app-type') == 'PROVIDER_APP')
        {
            array_push($config['settings'], [
                "id" => "2",
                "name" => "provider_under_maintenance_show_webview",
                "type" => "BOOL",
                "value" => $settings['provider_under_maintenance_show_webview'] ?? 'false',
                "options" => ""
            ]);  
            array_push($config['settings'], [
                "id" => "5",
                "name" => "webview_url_provider",
                "type" => "TEXT",
                "value" => $settings['webview_url_provider'],
                "options" => ""
            ]); 
            array_push($config['settings'], [
                "id" => "10",
                "name" => "provider_last_version_android",
                "type" => "TEXT",
                "value" => $settings["provider_last_version_android"] ?? "1.0.3",
                "options" => ""
            ]); 
            array_push($config['settings'], [
                "id" => "11",
                "name" => "provider_last_version_ios",
                "type" => "TEXT",
                "value" => $settings["provider_last_version_ios"] ?? "75",
                "options" => ""
            ]); 
            array_push($config['settings'], [
                "id" => "15",
                "name" => "is_force_update_provider",
                "type" => "int",
                "value" => $settings["is_force_update_provider"] ?? 0,
                "options" => ""
            ]); 
        }
        if($request->header('x-app-type') == 'CLIENT_APP')
        {
            array_push($config['settings'], [
                "id" => "3",
                "name" => "client_under_maintenance_show_webview",
                "type" => "BOOL",
                "value" => $settings["client_under_maintenance_show_webview"] ?? "false",
                "options" => ""
            ]);
            array_push($config['settings'], [
                "id" => "6",
                "name" => "webview_url_client",
                "type" => "TEXT",
                "value" => $settings['webview_url_client'],
                "options" => ""
            ]); 
            array_push($config['settings'], [
                "id" => "8",
                "name" => "client_last_version_android",
                "type" => "TEXT",
                "value" => $settings["client_last_version_android"] ?? "1.0.3",
                "options" => ""
            ]); 
            array_push($config['settings'], [
                "id" => "9",
                "name" => "client_last_version_ios",
                "type" => "TEXT",
                "value" => $settings["client_last_version_ios"] ?? "75",
                "options" => ""
            ]); 
            array_push($config['settings'], [
                "id" => "16",
                "name" => "is_force_update_client",
                "type" => "int",
                "value" => $settings["is_force_update_client"] ?? 0,
                "options" => ""
            ]);
            array_push($config['settings'], [
                "id" => "18",
                "name" => "not_original",
                "type" => "bool",
                "value" => $request->header('x-os') === 'ios' ? ($settings["not_original"] ?? 'false') : 'false',
                "options" => ""
            ]);
            array_push($config['settings'], [
                "id" => "20",
                "name" => "show_record_as_provider",
                "type" => "bool",
                "value" => $settings["show_record_as_provider"] ?? '0',
                "options" => ""
            ]);        
            array_push($config['settings'], [
                "id" => "21",
                "name" => "show_favourite_services",
                "type" => "bool",
                "value" => $settings["show_favourite_services"] ?? '0',
                "options" => ""
            ]);
        }

        if($request->header('x-app-type') !== 'PROVIDER_APP' && $request->header('x-app-type') !== 'CLIENT_APP'){
            array_push($config['settings'], [
                "id" => "4",
                "name" => "monitor_under_maintenance_show_webview",
                "type" => "BOOL",
                "value" => $settings["monitor_under_maintenance_show_webview"] ?? "false",
                "options" => ""
            ]);
            array_push($config['settings'], [
                "id" => "7",
                "name" => "webview_url_monitor",
                "type" => "TEXT",
                "value" => $settings['webview_url_monitor'],
                "options" => ""
            ]);
            array_push($config['settings'], [
                "id" => "12",
                "name" => "monitor_last_version_android",
                "type" => "TEXT",
                "value" => $settings["monitor_last_version_android"] ?? "1.0.3",
                "options" => ""
            ]);
            array_push($config['settings'], [
                "id" => "13",
                "name" => "monitor_last_version_ios",
                "type" => "TEXT",
                "value" => $settings["monitor_last_version_ios"] ?? "75",
                "options" => ""
            ]);
            array_push($config['settings'], [
                "id" => "17",
                "name" => "is_force_update_monitor",
                "type" => "int",
                "value" => $settings["is_force_update_monitor"] ?? 0,
                "options" => ""
            ]);
        }



        return response()->data(['config' => $config, 'localisation' => $localisation]);
    }

    public function countyDetails($id)
    {
        $country    =   Countries::where('id', $id)->first();

        return response()->json($country);
    }
    public function countyCities($id)
    {
        $cities   =   Cities::where('country_id', $id)->get(['id', 'name']);

        return response()->json($cities);
    }
    public function citystreets($id){
        $street   =   Street::where('city_id', $id)->get(['id', 'name']);

        return response()->json($street);
    }
    public function flag($country_code)
    {
        $filePath = public_path('storage/flags/' . strtolower($country_code) . '.png');
        return response()->download($filePath);
    }
    public function faq()
    {
        $data =  Faq::all();

        return response()->data($data);
    }
    public function privacy_policy()
    {
        $settings =   Setting::all(['key', 'value'])
            ->keyBy('key')
            ->map(function ($item) {
                return $item->value;
            })
            ->toArray();

        $data     =   $settings['privacy_policy'] ?? '';

        return response()->data($data);
    }
    public function terms_of_use()
    {
        $settings =   Setting::all(['key', 'value'])
            ->keyBy('key')
            ->map(function ($item) {
                return $item->value;
            })
            ->toArray();

        $data     =   $settings['terms_of_use'] ?? '';

        return response()->data($data);
    }
    public function about()
    {
        $settings =   Setting::all(['key', 'value'])
            ->keyBy('key')
            ->map(function ($item) {
                return $item->value;
            })
            ->toArray();
        $data     =   $settings['description'] ?? '';
        return response()->data($data);
    }
}
