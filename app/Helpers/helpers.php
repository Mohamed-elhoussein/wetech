<?php

use App\Enum\OrderStatus;
use App\Http\Services\PaypalServices;
use App\Libraries\PaymentMyfatoorahApiV2;
use App\Models\AdminNotification;
use App\Models\Countries;
use App\Models\Fee;
use App\Models\MaintenanceRequestOrder;
use App\Models\MaintenanceRequestOrderCoupon;
use App\Models\MaintenanceType;
use App\Models\Order;
use App\Models\PaymentOption;
use App\Models\Service;
use App\Models\ServiceCategories;
use App\Models\ServiceSubcategories;
use App\Models\ServiceSub2;
use App\Models\ServiceSub3;
use App\Models\ServiceSub4;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

if (!function_exists('rules')) {
    function rules($key)
    {
        return config('validationRules')[$key] ?? '';
    }
}

if (!function_exists('rules_messages')) {
    function rules_messages($key)
    {
        return config('ValidationMessages')[$key] ?? '';
    }
}


if (!function_exists('handle_exception')) {
    function handle_exception($request, $exception)
    {

        return (new \App\Helpers\Exceptions($request, $exception))->report();
    }
}

if (!function_exists('upload_picture')) {

    function upload_picture($picture, String $path)
    {
        $file_name = time();
        $file_name .= rand();
        $file_name = sha1($file_name);


        if ($picture) {
            $file_name = $file_name . '.' . $picture->getClientOriginalExtension();

            $picture->move(public_path() . $path, $file_name);
            $local_url = $file_name;

            $picture_url = $path . '/' . $local_url;
            return $picture_url;
        }
        return NULL;
    }
}
if (!function_exists('config_index')) {

    function config_index($index, $localization = 'ar,SA')
    {
        $array = include public_path($localization == 'en,US' ? 'config/eng.php' : 'config/ar.php');

        return  $array[$index] ??  '';
    }
}
if (!function_exists('config_value_index')) {

    function config_value_index($value)
    {
        $array = include public_path('config/eng.php');

        $key = array_keys($array, $value);

        return  $key[0]  ??  -1;
    }
}
if (!function_exists('string_value')) {

    function string_value($index, Request $request, $is_en = false)
    {
        $localization = $request->header("x-user-localization");

        $is_new_version = is_version_updated($request);

        $array = include public_path(
            $localization == 'en,US' || $is_en
                ? ($is_new_version ? 'config/eng-5031.php' : 'config/eng.php')
                : ($is_new_version ? 'config/ar-5031.php'  : 'config/ar.php')
        );

        return  $array[$index] ??  '';
    }
}
if (!function_exists('get_logo')) {

    function get_logo(Request $request)
    {
        $is_new_version = is_version_updated($request);

        return  $is_new_version ? '/images/logos/new_circle_logo.png' : '/images/logos/circle_logo.png';
    }
}
if (!function_exists('is_version_updated')) {

    function is_version_updated(Request $request)
    {
        return (
            ((($request->header("x-os")    == "ios"     &&  ((int) str_replace('.', '', $request->header("x-build-number")) > (int) str_replace('.', '', "5.0.35")))
                ||
                ($request->header("x-os")      == "Android" &&  ((int) str_replace('.', '', $request->header("x-app-version"))  > (int) str_replace('.', '', "5.0.30"))))
                &&
                $request->header('x-app-type') == 'CLIENT_APP')
            ||
            ((($request->header("x-os")    == "ios"     &&  ((int) str_replace('.', '', $request->header("x-build-number")) > (int) str_replace('.', '', "0.0.13")))
                ||
                ($request->header("x-os")      == "Android" &&  ((int) str_replace('.', '', $request->header("x-app-version"))  > (int) str_replace('.', '', "0.0.16"))))
                &&
                $request->header('x-app-type') == 'PROVIDER_APP')
            ||
            $request->header('x-app-type') == 'MONITOR_APP'
        );
    }
}
if (!function_exists('check_version_updated')) {

    function check_version_updated(Request $request, $app_type, $version_android, $version_ios)
    {
        return (
            ((($request->header("x-os")    == "ios"     &&  ((int) str_replace('.', '', $request->header("x-build-number")) > (int) str_replace('.', '', $version_ios)))
                ||
                ($request->header("x-os")      == "Android" &&  ((int) str_replace('.', '', $request->header("x-app-version"))  > (int) str_replace('.', '', $version_android))))
                &&
                $request->header('x-app-type') == $app_type)
        );
    }
}
if (!function_exists('Change_Format')) {

    function Change_Format($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}

if (!function_exists('convertArabicNumber')) {

    function convertArabicNumber($input)
    {
        $arabic = ['٠' => 0, '١' => 1, '٢' => 2, '٣' => 3, '٤' => 4, '٥' => 5, '٦' => 6, '٧' => 7, '٨' => 8, '٩' => 9];

        return strtr($input, $arabic);
    }
}

if (!function_exists('menu')) {

    function menu($app = 'dashboard')
    {
        if ($app == 'dashboard') $menu  = config('sidebare'); else $menu = config('main-sidebare');

        return $menu;
    }
}
if (!function_exists('policies')) {

    function policies()
    {
        $menu  = config('sidebare');
        $policies  = collect($menu)->map(function ($policy) {
            return collect($policy)->only('policy', 'name');
        })->unique()->filter()->values()->toArray();

        return $policies;
    }
}

if (!function_exists('default_image')) {

    function default_image()
    {
        return url("/images/default.png");
    }
}

if (!function_exists('keys')) {

    function keys($value = null)
    {
        return config($value? 'app.'.$value : 'app.firbase_key');
    }
}
if (!function_exists('get_con')) {

    function get_con($value, $fields)
    {
        switch ($value) {

            case 0:
                return null;
            case 1:
                return isset($fields['service_id']) ? Service::where('id', $fields['service_id'])->firstOrFail() : null;
            case 2:
                return isset($fields['service_categories_id'])    && \Str::upper($fields['service_categories_id'])    !== 'NULL'
                    ? ServiceCategories::where('id', $fields['service_categories_id'])->firstOrFail() : null;
            case 3:
                return isset($fields['service_subcategories_id']) && \Str::upper($fields['service_subcategories_id']) !== 'NULL'
                    ? ServiceSubcategories::where('id', $fields['service_subcategories_id'])->firstOrFail() : null;
            case 4:
                return isset($fields['sub2_id'])                  && \Str::upper($fields['sub2_id'])                  !== 'NULL'
                    ? ServiceSub2::where('id', $fields['sub2_id'])->firstOrFail() : null;
            case 5:
                return isset($fields['sub3_id'])                  && \Str::upper($fields['sub3_id'])                  !== 'NULL'
                    ? ServiceSub3::where('id', $fields['sub3_id'])->firstOrFail() : null;
            case 6:
                return isset($fields['sub4_id'])                  && \Str::upper($fields['sub4_id'])                  !== 'NULL'
                    ? ServiceSub4::where('id', $fields['sub4_id'])->firstOrFail() : null;
            default:
                return null;
        }
    }
}

if (!function_exists('get_title')) {

    function get_title($value, $fields)
    {
        switch ($value) {

            case 0:
                return null;
            case 1:
                return isset($fields['service_id']) ? Service::where('id', $fields['service_id'])->firstOrFail() : null;
            case 2:
                return isset($fields['service_categories_id'])    && \Str::upper($fields['service_categories_id'])    !== 'NULL'
                    ? ServiceCategories::where('id', $fields['service_categories_id'])->first() ?? get_title($value - 1, $fields) : get_title($value - 1, $fields);
            case 3:
                return isset($fields['service_subcategories_id']) && \Str::upper($fields['service_subcategories_id']) !== 'NULL'
                    ? ServiceSubcategories::where('id', $fields['service_subcategories_id'])->first() ?? get_title($value - 1, $fields) : get_title($value - 1, $fields);
            case 4:
                return isset($fields['sub2_id'])                  && \Str::upper($fields['sub2_id'])                  !== 'NULL'
                    ? ServiceSub2::where('id', $fields['sub2_id'])->first() ?? get_title($value - 1, $fields) : get_title($value - 1, $fields);
            case 5:
                return isset($fields['sub3_id'])                  && \Str::upper($fields['sub3_id'])                  !== 'NULL'
                    ? ServiceSub3::where('id', $fields['sub3_id'])->first() ?? get_title($value - 1, $fields) : get_title($value - 1, $fields);
            case 6:
                return isset($fields['sub4_id'])                  && \Str::upper($fields['sub4_id'])                  !== 'NULL'
                    ? ServiceSub4::where('id', $fields['sub4_id'])->first() ?? get_title($value - 1, $fields) : get_title($value - 1, $fields);
            default:
                return null;
        }
    }
}

if (!function_exists('get_title_improve')) {

    function get_title_improve($value, $provider_service)
    {
        switch ($value) {

            case 0:
                return null;
            case 1:
                return isset($provider_service['service_id']) ? $provider_service->service_full : null;
            case 2:
                return isset($provider_service['service_categories_id'])    && \Str::upper($provider_service['service_categories_id'])    !== 'NULL'
                    ? $provider_service->service_category ?? get_title_improve($value - 1, $provider_service) : get_title_improve($value - 1, $provider_service);
            case 3:
                return isset($provider_service['service_subcategories_id']) && \Str::upper($provider_service['service_subcategories_id']) !== 'NULL'
                    ? $provider_service->service_subcategories ?? get_title_improve($value - 1, $provider_service) : get_title_improve($value - 1, $provider_service);
            case 4:
                return isset($provider_service['sub2_id'])                  && \Str::upper($provider_service['sub2_id'])                  !== 'NULL'
                    ? $provider_service->service_sub2 ?? get_title_improve($value - 1, $provider_service) : get_title_improve($value - 1, $provider_service);
            case 5:
                return isset($provider_service['sub3_id'])                  && \Str::upper($provider_service['sub3_id'])                  !== 'NULL'
                    ? $provider_service->service_sub3 ?? get_title_improve($value - 1, $provider_service) : get_title_improve($value - 1, $provider_service);
            case 6:
                return isset($provider_service['sub4_id'])                  && \Str::upper($provider_service['sub4_id'])                  !== 'NULL'
                    ? $provider_service->service_sub4 ?? get_title_improve($value - 1, $provider_service) : get_title_improve($value - 1, $provider_service);
            default:
                return null;
        }
    }
}

if (!function_exists('getEnumValues')) {

    function getEnumValues($table, $column, $arr)
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '$column'"))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        $index = 0;
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            array_push($enum, ['id' => $index, 'type' => $v, 'name' => $arr[$index]]);
            $index++;
        }
        // collect($enum)->map(function($item){
        //     $item = '1';
        //     return  $item;
        //  });
        return $enum;
    }
}


if (!function_exists('badgeColorFromStatus')) {
    function badgeColorFromStatus($status)
    {
        switch ($status) {
            case OrderStatus::COMPLETED:
                return 'badge-soft-success';
            case OrderStatus::PENDING:
                return 'badge-soft-warning';
            case OrderStatus::CANCELED:
                return 'badge-soft-danger';
            default:
                return 'badge-soft-primary';
        }
    }
}


if (!function_exists('clean_csv_input')) {
    function clean_csv_input($input)
    {
        return strtolower(trim($input));
    }
}


if (!function_exists('ar_cities')) {
    function ar_cities()
    {
        return config('ar-cities');
    }
}


if (!function_exists('starsFromNumber')) {
    function starsFromNumber($number)
    {
        $number = floor($number);
        $stars = [];

        for ($i = 0; $i < $number; $i++) {
            $stars[] = "<span class='bx bxs-star text-warning fs-4'></span>";
        }

        for ($i = 0; $i < 5 - $number; $i++) {
            $stars[] = "<span class='bx bxs-star fs-4'></span>";
        }

        return implode(" ", $stars);
    }
}


if (!function_exists('showRates')) {
    function showRates(array $stars)
    {
        $html = "";
        for ($i = 5; $i >= 1; $i--) {

            $html .= '<div class="mb-1 d-flex align-items-center flex-row-reverse">
                    <div class="row__rate">
                        <div style="float: left; height: 100%; width: ' . $stars["$i stars"] . '%; background-color: #ffc100">
                        </div>
                    </div>

                    <div class="d-flex align-items-center me-2 flex-row-reverse">
                        ' . starsFromNumber($i) . '
                    </div>
                    <span style="display: block" class="me-1 fs-4">
                        ' . $stars["$i stars"] . '%
                    </span>
                </div>
            ';
        }

        return $html;
    }
}


if (!function_exists('pluralize')) {
    function pluralize(string $word, int $count = 0, ?string $plural = null)
    {
        if ($count >= 0 && $count <= 1) {
            return $word;
        }

        if ($plural) {
            return $plural;
        }

        return $word . 's';
    }
}


if (!function_exists('chatReviewsPermission')) {
    function chatReviewsPermission()
    {
        return config('chat_reviews.permissions');
    }
}


if (!function_exists('userCanAddMoreRecord')) {
    function userCanAddMoreRecord($tableName)
    {
        $max = config('system.max_database_record_per_hour');
        $recordCount = DB::table($tableName)->where('created_at', '>=', \Carbon\Carbon::now()->subHour())->count();

        return $recordCount < $max;
    }
}


if (!function_exists('userNotifications')) {
    function userNotifications()
    {
        $notifications = AdminNotification::all();
        $html = <<<HTML
            <div>Hello world</div>
        HTML;

        $html = $notifications->map(function ($notification) use ($html) {
            return <<<HTML
                <a class="dropdown-item" href="{$notification->link}">
                    <span>{$notification->description}</span>
                </a>
            HTML;
        })->toArray();

        return implode(" ", $html);
    }
}


if (!function_exists('userCountry')) {
    function userCountry(): ?array
    {
        $country = null;
        try {
            $userIp = request()->ip();
            $repsonse = Http::get("http://ip-api.com/json/$userIp");
            $countryCode = $repsonse->json()['countryCode'];
            $country = Countries::where('code', $countryCode)->first();

            if ($country->status == 'UNACTIVE')
                $country = Countries::where('code', 'SA')->first();
        }
        catch (\Exception $e) {
            $country = Countries::where('code', 'SA')->first();
        }

        return $country->toArray();
    }
}


if (!function_exists("getUserUnit")) {
    function getUserUnit($user)
    {
        $units = [
            "SAR",
            "KD",
            "BD",
            "AED",
            "QR",
            "OR",
            "JD",
            "USD",
            "EUR"
        ];

        if (is_null($user->country) || !in_array(optional($user->country)->unit_en, $units)) {
            return $units[0];
        }

        return $user->country->unit_en;
    }
}

if (!function_exists('cartCount')) {
    function cartCount() {
        return auth()->user()->carts()->count();
    }
}

if (!function_exists('is_provider_app')) {
    function is_provider_app() {
        return request()->header('x-app-type') === 'PROVIDER_APP';
    }
}

if (!function_exists('paypalCheckout'))
{
    function paypalCheckout($data, $callback_url, $with_fees = true, $coupon = null, $order_data = null)
    {
        try {
            $user = auth()->user();

            $items = collect();

            if ($with_fees) {
                $items = Fee::query()->online()->active()->forMaintenance()->get()->map(function ($fee) {
                    return [
                        'ItemName' => $fee->name,
                        'Quantity' => 1,
                        'UnitPrice' => $fee->value
                    ];
                });
            }

            $amount = $data['price'] + $items->sum('UnitPrice');

            if ($amount <= 0) {
                return create_maintenance_order($order_data, $coupon);
            }

            /**
             * @var PaypalServices
             */
            $paypal = app(PaypalServices::class);

            $payment = $paypal->buildMaintenanceRequestPayment($data, $callback_url, $with_fees)->sendPayment();

            $PaymentState = $payment->payment->getState();
            $payment_id = $payment->payment->getId();

            if ($PaymentState == 'created') {
                return $payment->redirectLink();
            }

            return $PaymentState;
        }
        catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('myFatoorahCheckout')) {
    /**
     * @param array $data
     */
    function myFatoorahCheckout($data, $callback_url, $with_fees = true, $coupon = null, $order_data = null)
    {
        $user = auth()->user();

        $items = collect();

        if ($with_fees) {
            $items = Fee::query()->online()->active()->forMaintenance()->get()->map(function ($fee) {
                return [
                    'ItemName' => $fee->name,
                    'Quantity' => 1,
                    'UnitPrice' => $fee->value
                ];
            });
        }

        $currency = getUserUnit($user);

        // $amount = $data['amount'];
        $amount = $data['amount'] + $items->sum('UnitPrice');

        if ($amount <= 0) {
            return create_maintenance_order($order_data, $coupon);
        }

        $paymentMethodId = 0;
        $postFields = [
            'InvoiceValue' => $amount,
            'CustomerName' => $user->username,
            'CustomerEmail' => filter_var($user->email, FILTER_VALIDATE_EMAIL) ? $user->email : null,
            'CustomerMobile' => $user->number_phone,
            'MobileCountryCode' => optional($user->country)->country_code,
            "DisplayCurrencyIso" =>  $currency,
            'CallBackUrl' => $callback_url,
            'InvoiceItems' => [
                ...$data['items'],
                ...$items->toArray(),
            ]
        ];

        try {
            $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', env("MYFATOORAH_TEST"));
            $data      = $mfPayment->getInvoiceURL($postFields, $paymentMethodId);

            $invoiceId   = $data['invoiceId'];
            $paymentLink = $data['invoiceURL'];

            return $paymentLink;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
}


if (!function_exists('create_maintenance_order')) {
    function create_maintenance_order(array $data, $coupon = null)
    {
        $order = MaintenanceRequestOrder::create([
            'provider_id' => $data['provider_id'],
            'maintenance_type_id' => $data['maintenance_id'],
            'note' => $data['note'],
            'payment_method' => $data['payment_way']
        ]);

        $base_order = Order::create([
            'user_id' => auth()->id(),
            'price' => MaintenanceType::query()->find($data['maintenance_id'])->price,
            'status' => 'PENDING',
            'provider_id' => $data['provider_id'],
            'maintenance_request_order_id' => $order->id
        ]);

        if ($coupon) {
            MaintenanceRequestOrderCoupon::create([
                'maintenance_request_order_id' => $order->id,
                'maintenance_request_coupon_id' => $coupon->id,
            ]);
        }

        $payment_option = PaymentOption::find($data['payment_option']);

        $data['payment_way'] = ($data['payment_way'] == 'epay' || $data['payment_way'] == 'paypal') ? 'online' : 'cash';

        $base_order->savePaymentOption($payment_option)->saveFees($data['payment_way']);

        return [
            $order,
            $base_order
        ];
    }
}
