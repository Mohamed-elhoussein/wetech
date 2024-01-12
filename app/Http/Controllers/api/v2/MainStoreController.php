<?php

namespace App\Http\Controllers\api\V2;

use App\Libraries\PaymentMyfatoorahApiV2;
use App\Http\Filters\MaintenanceFilter;
use App\Http\Resources\MaintenanceRequestResource;
use App\Http\Resources\ProvidersMaintenanceResource;
use App\Models\Brand;
use App\Models\Cities;
use App\Models\Color;
use App\Models\Countries;
use App\Models\Issues;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceRequestType;
use App\Models\MaintenanceRequestCoupon;
use App\Models\MaintenanceRequestOrder;
use App\Models\MaintenanceRequestOrderCoupon;
use App\Models\CityMaintenanceRequest;
use App\Models\StreetMaintenanceRequest;
use App\Models\MaintenanceType;
use App\Models\Models;
use App\Models\Order;
use App\Models\ProviderCommission;
use App\Models\Setting;
use App\Models\PaymentOption;
use App\Models\ProviderServices;
use App\Models\Street;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\FCM;
use App\Models\ColorMaintenanceRequest;
use App\Models\Notification;
use App\Models\Service;
use App\Models\MaintenanceOrderPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class MainStoreController
{
    public function index(MaintenanceFilter $filter)
    {
        $data = MaintenanceRequest::query()
            ->filter($filter)
            ->with([
                'cities',
                'colors',
                'cities.streets',
                'cities.streets.street',
                'cities.city',
                'cities.city.country',
                'service:id,name',
                'brand:id,name',
                'model:id,name',
                'color:id,name',
                'issue:id,name',
                'types',
                'types.type',
            ])
            ->latest('id')
            ->get();

        return response()->data(
            MaintenanceRequestResource::collection($data),
        );
    }

    public function create()
    {
        return response()->data([
            'brands' => Brand::all([
                'id',
                'name'
            ]),
            'models' => Models::all([
                'id',
                'name',
                'brand_id'
            ]),
            'colors' => Color::all([
                'id',
                'name'
            ]),
            'issues' => Issues::all([
                'id',
                'name'
            ]),
            'countries' => Countries::query()->active()->get([
                'id',
                'name'
            ]),
            'cities' => Cities::query()->active()->with('street')->get([
                'id',
                'name',
                'country_id'
            ]),
            'streets' => Street::all([
                'id',
                'name',
                'city_id'
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "service_id" => "required|numeric|exists:services,id",
            "brand_id" => "required|numeric|exists:brands,id",
            "models_id" => "required|numeric|exists:models,id",
            "issues_id" => "required|numeric|exists:issues,id",
            "country_id" => "required|array|exists:countries,id",
            // "city_id" => "required|array|exists:cities,id",
            // "street_id" => "required|array|exists:streets,id",
            "cities" => "required|array",
            "colors" => "required|array",
            "meta" => "required|array",
        ]);

        $request = MaintenanceRequest::create(
            Arr::except($data, 'colors')
        );

        $request->colors()->sync($data['colors']);

        $maintenance_type = collect($data['meta'])->map(function ($meta) use ($request) {
            return [
                'price' => $meta['price'],
                'type_id' => $meta['type_id'],
                'maintenance_request_id' => $request->id
            ];
        })->toArray();

        MaintenanceType::insert($maintenance_type);

        $request->load([
            'types',
            'colors',
            'types.type'
        ]);

        $request->update_location($data);

        return response()->data([
            'success' => $request
        ]);
    }

    public function update(MaintenanceRequest $request)
    {
        $data = request()->validate([
            "service_id" => "required|numeric|exists:services,id",
            "brand_id" => "required|numeric|exists:brands,id",
            "models_id" => "required|numeric|exists:models,id",
            "issues_id" => "required|numeric|exists:issues,id",
            "country_id" => "required|array|exists:countries,id",
            // "city_id" => "required|array|exists:cities,id",
            // "street_id" => "required|array|exists:streets,id",
            "cities" => "required|array",
            "colors" => "required|array",
            "meta" => "required|array",
        ]);

        $request->update(Arr::except($data, 'colors'));
        $request->update_types($data);
        $request->update_location($data);
        $request->colors()->sync($data['colors']);

        return response()->data([
            'success' => true
        ]);
    }

    public function delete(MaintenanceRequest $request)
    {
        $request->delete();
        $request->countries()->delete();
        $request->streets()->delete();
        $request->cities()->delete();
        $request->types()->delete();

        return response()->data([
            'success' => true
        ]);
    }

    public function get_params()
    {
        $resource = request()->get('resource');

        $data = null;

        switch ($resource) {
            case "services":
                $data = Service::all();
                break;
            case "brand":
                $ids = MaintenanceRequest::query()->where('service_id', request()->get('service_id'))->get()->pluck('brand_id')->unique();
                $data = Brand::query()->whereIn('id', $ids)->get();
                break;
            case "model":
                $models = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('brand_id', request()->get('brand_id'))
                    ->get()
                    ->pluck('models_id')
                    ->unique();
                $data = Models::query()->whereIn('id', $models)->get();
                break;
            case "color":
                $ids = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('models_id', request()->get('model_id'))
                    ->with('colors')
                    ->get()
                    ->pluck('id')
                    ->unique();

                $ids = ColorMaintenanceRequest::query()->whereIn('maintenance_request_id', $ids->toArray())->get()->pluck('color_id')->unique();
                $data = Color::query()->whereIn('id', $ids)->get();
                break;
            case "issue":
                $ids = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('models_id', request()->get('model_id'))
                    ->whereHas('colors', function ($query) {
                        $query->where('colors.id', request()->get('color_id'));
                    })
                    ->get()
                    ->pluck('issues_id')
                    ->unique();
                $data = Issues::query()->whereIn('id', $ids)->get();
                break;
            case "city":
                $request = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('models_id', request()->get('model_id'))
                    // ->where('color_id', request()->get('color_id'))
                    ->where('issues_id', request()->get('issue_id'))
                    ->get();
                // ->pluck('city_id')
                // ->unique();

                return response()->data($request->map(function ($rq) {
                    return $rq->cities->map(function ($city_maintenance_request) {
                        return [
                            'id' => $city_maintenance_request->id,
                            'country_name' => optional(optional($city_maintenance_request->city)->country)->name,
                            'name' => $name = optional($city_maintenance_request->city)->name,
                            'name_en' => optional($city_maintenance_request->city)->name_en,
                            'status' => optional($city_maintenance_request->city)->status,
                            'created_at' => optional($city_maintenance_request->city)->created_at,
                            // 'street' => optional($city_maintenance_request)->streets->map(function ($street) use ($name) {
                            //     return [
                            //         'id' => $street->street->id,
                            //         'street_name' => $street->street->name . ' ' . $name,
                            //     ];
                            // }),
                        ];
                    });
                })->flatten(1)->toArray());
                // $data = Cities::query()->whereIn('id', $ids)->get();
                break;
            case "street":
                $data = StreetMaintenanceRequest::query()->where('city_maintenance_request_id', request()->get('city_id'))->get()->map(function ($item) {
                    $item->street->id = $item->id;
                    return $item->street;
                });
                break;
            case "price":
                $request = MaintenanceRequest::query()
                    ->where('service_id', request()->get('service_id'))
                    ->where('brand_id', request()->get('brand_id'))
                    ->where('models_id', request()->get('model_id'))
                    // ->where('color_id', request()->get('color_id'))
                    ->where('issues_id', request()->get('issue_id'))
                    ->whereHas('cities', function ($query) {
                        $query->where('city_maintenance_requests.id', request()->get('city_id'));
                    })
                    ->whereHas('streets', function ($query) {
                        $query->where('street_maintenance_requests.id', request()->get('street_id'));
                    })
                    ->with('cities', 'cities.streets')
                    ->latest('id')
                    ->first();

                if (!$request) {
                    return response()->data([
                        'message' => 'not_found'
                    ], null, 404);
                }

                $data = array_map('intval', request()->only([
                    'service_id',
                    'brand_id',
                    'model_id',
                    'color_id',
                    'issue_id',
                    'city_id',
                    'street_id',
                ]));

                $data['maintenance_offers'] = $request->types->map(fn ($type) => [
                    'id' => $type->id,
                    'name' => $type->type->name,
                    'price' => (float)$type->price,
                    'created_at' => $request->created_at,
                    'payWay' => $this->getPaymentOptions(null, (float)$type->price)
                ]);

                $ids = ProviderServices::query()
                    ->where('status', 'ACCEPTED')
                    ->where('service_id', request()->service_id)
                    ->where('city_id', request()->city_id)
                    ->where(function ($query) {
                        $query->where('street_id', request()->street_id)
                            ->orWhere('country_city_street', 'LIKE', '%-1%');
                    })
                    ->get()
                    ->pluck('provider_id')
                    ->unique();

                $ids = [[426]];// CityMaintenanceRequest::query()->where('id', request()->get('city_id'))->first()->city->id == 2 ? [108, 106] :

                $data['all_zeros']  = true;//sizeof($request->types_priced) == 0 || $ids == [[426]] ? true : false;

                $providers = User::query()->where('role', 'provider')->whereIn('id', $ids)->orderByRaw('`active` DESC, -`order` DESC')->with('rate')->get([
                    'id',
                    'first_name',
                    'second_name',
                    'last_name',
                    'username',
                    'active',
                    'order',
                ]);

                $data['providers'] = ProvidersMaintenanceResource::collection($providers);
                // $data['payWay'] = $this->getPaymentOptions();
                break;
        }

        return response()->data($data);
    }

    private function getPaymentOptions($id = null, $price = 0)
    {
        if ($id) return PaymentOption::find($id);

        if ($price <= 0) {
            $option = PaymentOption::find(4);
            return [
                [
                    'id' => $option->id,
                    'type' => $option->type,
                    'amountValue' => $option->value,
                    'name' => $option->label,
                    'valueForServer' => $option->payment_type,
                    'underText' => $option->sub_text,
                    'url' => $option->id === 2 ? 'https://wetech.drtech-api.com/images/im_mcmv.png' : '',
                    'isActive' => true,
                    'label' => $option->label
                ]
            ];
        }

        return PaymentOption::orderBy('id', 'DESC')->where('id', '!=', 4)->get()->map(function ($option) {
            return [
                'id' => $option->id,
                'type' => $option->type,
                'amountValue' => $option->value,
                'name' => $option->label,
                'valueForServer' => $option->payment_type,
                'underText' => $option->sub_text,
                'url' => $option->id === 2 ? 'https://wetech.drtech-api.com/images/im_mcmv.png' : '',
                'isActive' => true,
                'label' => $option->label
            ];
        });
    }

    public function get_providers()
    {
        $ids = ProviderServices::query()
            ->where('service_id', request()->service_id)
            ->where('city_id', request()->city_id)
            ->where('street_id', request()->street_id)
            ->get()
            ->pluck('provider_id')
            ->unique();

        $providers = User::query()->where('role', 'provider')->whereIn('id', $ids)->orderByRaw('-`order` DESC')->with('rate')->get([
            'id',
            'first_name',
            'second_name',
            'last_name',
            'username',
            'active',
            'order',
        ]);

        return response()->data(
            ProvidersMaintenanceResource::collection($providers)
        );
    }

    public function store_new_order_from_web(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'maintenance_id' => 'required|numeric|exists:maintenance_types,id',
            'provider_id' => 'required|numeric|exists:users,id',
            'notes' => 'nullable|string',
            'city_id' => 'required|numeric',
            'street_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->error(422, ['errors' => $errors], 422);
        }

        $notes = null;

        if (isset($data['notes'])) {
            $notes = $data['notes'];
        }

        $data['notes'] = $request->name . "\n" . $request->email . "\n" . $request->phone . "\n";
        $data['notes'] .= $notes;

        $order = MaintenanceRequestOrder::create([
            'provider_id' => $data['provider_id'],
            'maintenance_type_id' => $data['maintenance_id'],
            'note' => isset($data['notes']) ? $data['notes'] : null,
            'city_id' => $data['city_id'],
            'street_id' => $data['street_id'],
        ]);

        // Order::create([
        //     'user_id' => null,
        //     'price' => MaintenanceType::query()->find($data['maintenance_id'])->price,
        //     'status' => 'PENDING',
        //     'provider_id' => $data['provider_id'],
        //     'maintenance_request_order_id' => $order->id,
        // ]);

        return response()->noContent();
    }

    public function store_new_order(Request $request)
    {
        $data = $request->validate([
            'maintenance_id' => "required|numeric|exists:maintenance_types,id",
            'provider_id' => "required|numeric|exists:users,id",
            'note' => "nullable|string",
            'cobon_code' => "nullable|string|exists:maintenance_request_coupons,code",
            "payment_option" => "required|numeric",
            "city_id" => "required|numeric",
            "street_id" => "required|numeric",
            "color_id" => "nullable|numeric",
        ]);

        // Get the current user orders
        $order = Order::query()->where('user_id', auth()->id())->whereHas('maintenance_request_order', function ($query) use ($request) {
            $query->whereHas('maintenance_type', function ($query) use ($request) {
                $query->whereHas('maintenance_request', function ($query) use ($request) {
                    $query->where('brand_id', $request->brand_id)
                        ->where('models_id', $request->model_id)
                        ->where('issues_id', $request->issue_id);
                });
            });
        })->pending()->first();

        if ($order) return response()->error(401, 'لديك طلب سابق لنفس الخدمة\nيمكنك الإطلاع عليها في قسم طلباتي الحالية');

        $coupon = null;

        if (isset($data['cobon_code'])) {
            $coupon = MaintenanceRequestCoupon::query()->code($data['cobon_code'])->whereIn('belong_to' , ['m', 'm,p'])->first();
            // $coupon = MaintenanceRequestCoupon::query()->code($data['cobon_code'])->first();
        }

        $payment_option = PaymentOption::find($data['payment_option']);

        $data['payment_way'] = $payment_option->payment_gateway;

        // For online payment
        if ($data['payment_way'] == 'epay' || $data['payment_way'] == 'paypal') {
            $checkout_url = $this->online_checkout($data, $coupon);

            if (is_array($checkout_url)) return response()->data([
                'success' => true,
                'message' => 'تم إنشاء الطلب'
            ]);

            return response()->data([
                'checkout_url' => $checkout_url
            ]);
        }

        // For cash payment
        $order = MaintenanceRequestOrder::create([
            'provider_id' => $data['provider_id'],
            'maintenance_type_id' => $data['maintenance_id'],
            'note' => array_key_exists('note', $data) ? $data['note'] : null,
            'payment_method' => $data['payment_way'],
            'payment_option_id' => $payment_option?->id ?? NULL,
            'city_id' => $data['city_id'],
            'street_id' => $data['street_id'],
            'color_id' => $data['color_id'] ?? NULL,
        ]);

        if ($coupon) {
            MaintenanceRequestOrderCoupon::create([
                'maintenance_request_order_id' => $order->id,
                'maintenance_request_coupon_id' => $coupon->id,
            ]);
        }

        $price = $data['provider_id'] == 426? 0 : MaintenanceType::query()->find($data['maintenance_id'])->price;
        $commission_row   =   ProviderCommission::where('provider_id', $data['provider_id'])->first();

            if ($commission_row)
                $commission       =  $commission_row->percentage == 1 ? ($price * $commission_row->commission / 100) : $commission_row->commission;
            else
                $commission       =  Setting::get('default_commission')[0];

        $base_order = Order::create([
            'user_id' => auth()->id(),
            'price' => $price,
            'status' => 'PENDING',
            'provider_id' => $data['provider_id'],
            'maintenance_request_order_id' => $order->id,
            'commission' => $commission,
        ]);

        $payment_option = $this->getPaymentOptions($data['payment_option']);

        $payment_type = ($data['payment_way'] == 'epay' || $data['payment_way'] == 'paypal') ? 'online' : 'cash';

        if ($payment_option->payment_type === $payment_type) {
            $base_order->savePaymentOption($payment_option);
        }

        $base_order->saveFees($data['payment_way']);

        //$this->send_notifications($base_order);

        return response()->data([
            'success' => true,
        ], $this->send_notifications($base_order));
    }

    public function new_update(Request $request)
    {
        $data = $request->validate([
            'models' => 'required|array',
            'models.*' => 'required|numeric',
            'issues' => 'required|array',
            'issues.*' => 'required|numeric',
            'providers' => 'required|array',
            'providers.*' => 'required|numeric',
            'maintenance_request_types_id' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        /**
         * @var Builder $query
         */
        $query = MaintenanceRequest::query();

        collect($data['models'])->map(function ($model_id) use ($data, &$query) {
            return collect($data['issues'])->map(function ($issue_id) use ($model_id, &$query) {
                $query->orWhere(function ($query) use ($issue_id, $model_id) {
                    $query->where('models_id', $model_id)->where('issues_id', $issue_id);
                });
            });
        })->collapse()->toArray();

        $requests = $query->get();

        if(count($requests) == 0)
        {
            return response()->json([
                'state' => false,
                'message' => 'يرجى إضافة عرض واحد على الأقل بالمشكلة والموديل ثم بعد ذلك يمكنك إضافة الأسعار والمزودين لهذا العرض'
            ], 201);
        }

        $requests->map(function (MaintenanceRequest $maintenanceRequest) use ($data) {
            $types = collect($data['providers'])->map(function ($provider_id) use ($data) {
                return [
                    'provider_id' => $provider_id,
                    'price' => $data['price'],
                    'type_id' => $data['maintenance_request_types_id'],
                ];
            })->toArray();

            $maintenanceRequest->types()->createMany($types);
        });

        return response()->data([
            'success' => true
        ]);
    }

    private function send_notifications($order)
    {

        $title_service = $order->maintenance_request_order->maintenance_type->maintenance_request->brand->name . ' ' .
            $order->maintenance_request_order->maintenance_type->maintenance_request->issue->name . ' ' .
            'في ' . $order->maintenance_request_order->city->name;

        $model = $order->maintenance_request_order->maintenance_type->maintenance_request->model->name;

        $notifi_provider         =  Notification::create([
            'user_id'              => $order->provider_id,
            'order_id'             => $order->id,
            'icon'                 => 'bell_outline_mco',
            'title'                => 'طلب ' . $order->user->username . ' صيانة ' . $title_service . '.',
            'message'              => 'الموديل ' . $model,
        ]);

        $notifi_user         =  Notification::create([
            'user_id'              => $order->user_id,
            'order_id'             => $order->id,
            'icon'                 => 'bell_outline_mco',
            'title'                => 'تم طلب من ' . $order->provider->username . ' صيانة ' . $title_service . '.',
            'message'              => 'الموديل ' . $model,
        ]);

        $fcm                 =    new FCM();

        if ($order->user->device_token)
            $fcm->to($order->user->device_token)
                ->message($notifi_user->message, $notifi_user->title)
                ->data('', 'order', $notifi_user->message, $notifi_user->title, 'Notifications')
                ->send();

        if ($order->provider->device_token)
            $fcm->to($order->provider->device_token)
                ->message($notifi_provider->message, $notifi_provider->title)
                ->data('', 'order', $notifi_provider->message, $notifi_provider->title, 'Notifications')
                ->send();

        $observers_token  =   User::where('role', 'chat_review')->get();

        if ($observers_token) {
            foreach ($observers_token as $observer) {
                $notifi_observer         =  Notification::create([
                    'user_id'              => $observer->id,
                    'order_id'             => $order->id,
                    'icon'                 => 'bell_outline_mco',
                    'title'                => 'طلب ' . $order->user->username .  ' => ' . $order->provider->username . ' صيانة ' . $title_service . '.',
                    'message'              => 'الموديل ' . $model,
                ]);
                $fcm->to($observer->device_token)
                    ->message($notifi_observer->message, $notifi_observer->title)
                    ->data('', 'order', $notifi_observer->message, $notifi_observer->title, 'Notifications')
                    ->send();
            }
        }

        return [
            'order' => $order,
            'user' => $order->user,
            'provider' => $order->provider,
            'brand' => $order->maintenance_request_order->maintenance_type->maintenance_request->brand->name,
            'city' => $order->maintenance_request_order->city->name,
        ];
    }

    private function get_order_price($maintenance_type, $payment_option = null, $coupon = null)
    {
        $price = $maintenance_type->price;

        if ($payment_option) {
            if ($payment_option->type == 'sub') {
                $price = $payment_option->value;
                return $price;
            }

            if ($payment_option->type == 'plus') $price = $price + $payment_option->value;
        }

        if ($coupon) {
            $price = $price - $coupon->value;
        }

        return $price;
    }

    private function online_checkout($data, $coupon = null)
    {
        $maintenance_type = MaintenanceType::query()->with([
            'maintenance_request',
            'maintenance_request.issue',
        ])->find($data['maintenance_id']);

        $payment_option = PaymentOption::query()->find($data['payment_option']);

        $price = $this->get_order_price($maintenance_type, $payment_option, $coupon);

        $data['user_id'] = auth()->id();

        if ($coupon) {
            $callback_url = route('main-store.order.store', array_merge($data, ['coupon' => $coupon->id]));
        } else {
            $callback_url = route('main-store.order.store', $data);
        }

        $title = $payment_option->type == 'sub' ? 'دفع مقدم ل' . $maintenance_type->maintenance_request->issue->name : $maintenance_type->maintenance_request->issue->name;

        switch ($data['payment_way']) {
            case 'epay':
                return myFatoorahCheckout([
                    'amount' => $price,
                    'items' => [
                        [
                            'ItemName' => $title,
                            'Quantity' => 1,
                            'UnitPrice' => $price
                        ]
                    ]
                ], $callback_url, $payment_option->type != 'sub', $coupon, $data);
            case 'paypal':
                $callback_url = route('paypal.main-store.order.store', array_merge($data, ['coupon' => $coupon->id]));

                return paypalCheckout([
                    'title' => $title,
                    'price' => $price,
                ], $callback_url, $payment_option->type != 'sub', $coupon, $data);
        }
    }

    public function info(Request $request){

        $data['issues']     = issues::get(['id' , 'name']);
        $data['types']      = MaintenanceRequestType::get(['id' , 'name']);
        $data['providers']  = User::where('role' , 'provider')->get(['id' , 'username as name']);

        return response()->data($data);
    }

    public function check_exist_order(Request $request)
    {
        $request->validate([
            'maintenance_id' => "required|exists:maintenance_types,id",
            'provider_id' => "required|exists:users,id",
            'note' => "nullable|string",
            'cobon_code' => "nullable|string|exists:maintenance_request_coupons,code",
            "payment_option" => "required",
            "city_id" => "required",
            "street_id" => "required",
            "color_id" => "nullable",
        ]);

        // Get the current user orders
        $order = Order::query()->where('user_id', auth()->id())->whereHas('maintenance_request_order', function ($query) use ($request) {
            $query->whereHas('maintenance_type', function ($query) use ($request) {
                $query->whereHas('maintenance_request', function ($query) use ($request) {
                    $query->where('brand_id', $request->brand_id)
                        ->where('models_id', $request->model_id)
                        ->where('issues_id', $request->issue_id);
                });
            });
        })->pending()->first();

        if ($order) return response()->error(401, 'لديك طلب سابق لنفس الخدمة\nيمكنك الإطلاع عليها في قسم طلباتي الحالية');
        return response()->data(['is_not_exists' => true]);
    }

    public function store_new_order_paid(Request $request)
    {
        $data = $request->validate([
            'payment_id' => ['required', 'unique:payments,payment_id'],
            'maintenance_id' => "required|numeric|exists:maintenance_types,id",
            'provider_id' => "required|numeric|exists:users,id",
            'note' => "nullable|string",
            'cobon_code' => "nullable|string|exists:maintenance_request_coupons,code",
            "payment_option" => "required|numeric",
            "city_id" => "required|numeric",
            "street_id" => "required|numeric",
            "color_id" => "nullable|numeric",
        ]);

        // Get the current user orders
        $order = Order::query()->where('user_id', auth()->id())->whereHas('maintenance_request_order', function ($query) use ($request) {
            $query->whereHas('maintenance_type', function ($query) use ($request) {
                $query->whereHas('maintenance_request', function ($query) use ($request) {
                    $query->where('brand_id', $request->brand_id)
                        ->where('models_id', $request->model_id)
                        ->where('issues_id', $request->issue_id);
                });
            });
        })->pending()->first();

        if ($order) return response()->error(401, 'لديك طلب سابق لنفس الخدمة\nيمكنك الإطلاع عليها في قسم طلباتي الحالية');

        $paymentId = request()->get('payment_id');
        if (!$paymentId) {
            return response()->error(404, 'رقم الفاتورة غير صحيح');
        }
        $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY_TEST"), 'SAU', true);
        // $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', false);
        $dataPayment      = $mfPayment->getPaymentStatus($paymentId, "paymentId");

        if ($dataPayment->InvoiceStatus != 'Paid')
        {
            return response()->error(201, 'لم يتم الدفع بشكل صحيح');
        }

        $coupon = null;
        if (isset($data['cobon_code']))
        {
            $coupon = MaintenanceRequestCoupon::query()->code($data['cobon_code'])->whereIn('belong_to' , ['m', 'm,p'])->first();
        }

        $payment_option = PaymentOption::find($data['payment_option']);

        $payment_way = $payment_option->payment_gateway;

        // For payment order
        $order = MaintenanceRequestOrder::create([
            'provider_id' => $data['provider_id'],
            'maintenance_type_id' => $data['maintenance_id'],
            'note' => array_key_exists('note', $data) ? $data['note'] : null,
            'payment_method' => $payment_way,
            'payment_option_id' => $payment_option?->id ?? NULL,
            'city_id' => $data['city_id'],
            'street_id' => $data['street_id'],
            'color_id' => $data['color_id'] ?? NULL,
        ]);
        MaintenanceOrderPayment::create([
            'payment_id' => $data['payment_id'],
            'maintenance_order_id' => $order->id
        ]);

        if ($coupon) {
            MaintenanceRequestOrderCoupon::create([
                'maintenance_request_order_id' => $order->id,
                'maintenance_request_coupon_id' => $coupon->id,
            ]);
        }

        $commission_row   =   ProviderCommission::where('provider_id', $data['provider_id'])->first();

            if ($commission_row)
                $commission       =  $commission_row->percentage == 1 ? ($dataPayment->InvoiceValue * $commission_row->commission / 100) : $commission_row->commission;
            else
                $commission       =  Setting::get('default_commission')[0];

        $base_order = Order::create([
            'user_id' => auth()->id(),
            'price' => $dataPayment->InvoiceValue,
            'status' => 'PENDING',
            'provider_id' => $data['provider_id'],
            'maintenance_request_order_id' => $order->id,
            'commission' => $commission,
        ]);

        $payment_option = $this->getPaymentOptions($data['payment_option']);

        $payment_type = ($payment_way == 'epay' || $payment_way == 'paypal') ? 'online' : 'cash';

        if ($payment_option->payment_type === $payment_type) {
            $base_order->savePaymentOption($payment_option);
        }

        $base_order->saveFees($payment_way);

        //$this->send_notifications($base_order);

        return response()->data([
            'success' => true,
        ], $this->send_notifications($base_order));//
    }
}
