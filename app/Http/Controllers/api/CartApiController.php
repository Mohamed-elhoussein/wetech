<?php

namespace App\Http\Controllers\api;

use App\Libraries\PaymentMyfatoorahApiV2;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\MaintenanceRequestCoupon;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Fee;
use App\Models\PayMethodes;
use App\Models\Order;
use App\Models\Notification;
use App\Models\User;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\DB;
use App\Helpers\FCM;

class CartApiController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->has('user_id')?  $request->get('user_id') : auth()->user()->id;
        $order_id = $request->has('order_id')?  $request->get('order_id') : null;
        $total_prices = Cart::where('user_id', $user_id)->where('order_id', $order_id)->sum(DB::raw('price * quantity'));
        $count = Cart::where('user_id', $user_id)->where('order_id', $order_id)->count();
        $carts = Cart::where('user_id', $user_id)->where('order_id', $order_id)->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $carts)->with(['user:id,username,number_phone,country_id', 'user.country', 'city:id,country_id,name,name_en', 'brand', 'type', 'cartOrder' => function ($query) use ($order_id) {
            $query->where('order_id', $order_id);
        }])
        ->when($user_id, function ($query) use ($user_id){
            $query->with(['favorite' => function ($hasMany)  use ($user_id) {
                $hasMany->where('user_id', $user_id);
            }]);
        })->get();
        $fees = Fee::query()->active()->get();

        $online_fees = $fees->filter(function ($item) {
            return $item->payment_method == 'online' || $item->payment_method == 'both';
        })->map(function ($item) {
            return ['text_ar' => $item->name, 'text_en' => $item->name, 'number' => $item->value];
        });

        $cash_fees = $fees->filter(function ($item) {
            return $item->payment_method == 'cash' || $item->payment_method == 'both';
        })->map(function ($item) {
            return ['text_ar' => $item->name, 'text_en' => $item->name, 'number' => $item->value];
        });

        $pay_methodes = PayMethodes::where('id', '!=', 2)->get();

        $produc  =  collect($products)->map(function ($item) use ($user_id, $pay_methodes, $request, $online_fees, $cash_fees) {

            if (!is_array($item->images))
                $item->images = json_decode($item->images);

            $item->json_last_error = json_last_error_msg();

            $price = $item->is_offer == 1? $item->offer_price : $item->price;

            return [
                'id'            => $item->id,
                'name'          => $item->name,
                'name_en'       => $item->name_en,
                'price'         => $item->price,
                'quantity'      => $item->cartOrder->quantity,
                'delivery_fee'  => $item->delivery_fee,
                'total_price'   => ($item->cartOrder->quantity * $item->price) + $item->delivery_fee,
                'is_offer'      => $item->is_offer,
                'offer_price'   => $item->offer_price,
                'status'        => $item->status,
                'description'   => $item->description,
                'user_id'       => $item->user->id,
                'username'      => 'البائع: '.  $item->user->username,
                'phone'         => $item->user->country->country_code . $item->user->number_phone,
                'images'        => $item->images ?? ["/images/avatars/default.png"]	,
                'is_best_seller'=> $item->is_best_seller,
                'is_i_Liked'    => $user_id && $item->favorite? true : false,
                "stars"         => $item->rating->avg('stars') ?: 5,
                'is_exists'     => $item->cartOrder->is_exists,

                'payment_method'=> collect($item->payment_method)->map(function ($item) use ($pay_methodes) {
                    return $pay_methodes->filter(function($pay) use ($item){
                        return $pay->method == $item;
                    })->values()[0];
                }),


                'info'          =>[
                    // ['icon' => 'md_color_palette_ion',  'text_ar' => $item->color,                 'text_en'   =>   $item->color],
                    // ['icon' => 'star_ant',              'text_ar' => optional($item->type)->name, 'text_en'   =>   optional($item->type)->name_en],
                    // ['icon' => 'location_on_mdi',       'text_ar' => optional($item->city)->name,  'text_en'   =>   optional($item->city)->name_en],
                    // ['icon' => 'user_faw',              'text_ar' => $item->user->username,        'text_en'   =>   $item->user->username],
                ],

                'details_info'  =>[
                    ['icon' => 'calendar_faw',          'text_ar' => $item->created_at,            'text_en'   => $item->created_at],
                    ['icon' => 'location_on_mdi',       'text_ar' => optional($item->city)->name,  'text_en'   => optional($item->city)->name_en],
                    ['icon' => 'user_faw',              'text_ar' => $item->user->username,        'text_en'   => $item->user->username],
                ],

                'product_specifications'  => [
                        ['icon' => 'md_color_palette_ion',  'text_ar' => $item->color,                 'text_en'   =>   $item->color],
$item->type           ? ['icon' => 'star_ant',              'text_ar' => optional($item->type)->name,  'text_en'   =>   optional($item->type)->name_en] : [],
                        ['icon' => 'box_ent',               'text_ar' => $item->status == 'USED' ? 'مستعمل' : 'جديد',  'text_en'   =>   $item->status],
$item->guarantee == 1 ? ['icon' => 'check_all_mco',         'text_ar' => 'عليه ضمان' ,                 'text_en'   =>   'guarantee'] : [],
$item->disk_info      ? ['icon' => 'chip_mco',              'text_ar' => $item->disk_info,              'text_en'   =>   $item->disk_info] : [],
                ],


                'online_invoice_info'  =>  [
                    ['text_ar' => string_value(453, $request),  'text_en'   =>   string_value(453, $request, true),     'number' => $price,],
                    ...$online_fees->toArray(),
                    ['text_ar' => string_value(455, $request),  'text_en'   =>   string_value(455, $request, true),     'number' => $price + $online_fees->sum('number') + $item->delivery_fee ,],
                ],

                'cash_invoice_info'  =>  [
                    ['text_ar' => string_value(453, $request),  'text_en'   =>   string_value(453, $request, true),     'number' => $price,],
                    ...$cash_fees->toArray(),
                    ['text_ar' => string_value(455, $request),  'text_en'   =>   string_value(455, $request, true),     'number' => $price + $cash_fees->sum('number') + $item->delivery_fee], //  + 12,
                ],
            ];

        });

        $data = [
            'carts' => $produc,
            'carts_count' => count($carts),
            'total' => round($total_prices, 2),
            'count' => $count,
            'payment_method' => $pay_methodes,
            "invoice_info" => [],
        ];

        // foreach ($produc as $product) {
        //     $quantity = $product['quantity'];
        //     $product_price = $product['price'] ?? 0;

        //     $data['invoice_info'][] = [
        //         'text_ar' => $product['name'],
        //         'text_en' => $product['name_en'],
        //         'number' => $product_price * $quantity . ' = ' . $product_price  . ' x ' . $quantity,
        //     ];
        // }

        // $postFields['InvoiceItems'][] = $online_fees;

        $data['invoice_info'][] = [
            "text_ar" => "المجموع",
            "text_en" => "Total",
            'number' => $this->number_format_rtrim($total_prices) . ' ر.س'
        ];

        return response()->data($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => [
                'required',
            ],
            'quantity' => [
                'required',
                'numeric',
            ],
        ]);
        $user_id = $request->has('user_id')?  $request->get('user_id') : auth()->user()->id;
        $order_id = $request->has('order_id')?  $request->get('order_id') : null;

        $content = Cart::where('order_id', $order_id)
            ->updateOrCreate(
            [
                'product_id' => $request->product_id,
                'user_id' => $user_id
            ],
            [
                'quantity' => $request->quantity,
                'note' => $request->note,
                'price' => Product::where('id', $request->product_id)->first()->price,
                'is_exists' => true
            ]
        );

        $total_prices = Cart::where('user_id', $user_id)->where('order_id', $order_id)->sum(DB::raw('price * quantity'));
        $carts = Cart::where('user_id', $user_id)->where('order_id', $order_id)->pluck('product_id')->toArray();

        $data = [
            'data' => new CartResource($content),
            'carts_count' => count($carts),
            'total' => $total_prices,
            "invoice_info" => [],
        ];

        $data['invoice_info'][] = [
            "text_ar" => "المجموع",
            "text_en" => "Total",
            'number' => $this->number_format_rtrim($total_prices) . ' ر.س'
        ];

        return response()->data($data);
    }

    public function clear()
    {
        Cart::whereNull('order_id')->where(
            [
                'user_id' => auth()->user()->id
            ]
        )->delete();

        return response()->data(['status' => 'Succses']);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => [
                'required',
            ],
        ]);

        $user_id = $request->has('user_id')?  $request->get('user_id') : auth()->user()->id;
        $order_id = $request->has('order_id')?  $request->get('order_id') : null;

        if($request->has('user_id'))
            Cart::where('order_id', $order_id)
                ->updateOrCreate(
                [
                    'product_id' => $request->product_id,
                    'user_id' => $user_id
                ],
                [
                    'is_exists' => false,
                    'price' => 0,
                ]
            );
        else
        Cart::whereNull('order_id')->where(
            [
                'user_id' => $user_id,
                'product_id' => $request->product_id
            ]
        )->delete();
        $total_prices = Cart::where('user_id', $user_id)->where('order_id', $order_id)->sum(DB::raw('price * quantity'));
        $carts = Cart::where('user_id', $user_id)->where('order_id', $order_id)->pluck('product_id')->toArray();

        $data = [
            'carts_count' => count($carts),
            'total' => $total_prices,
            "invoice_info" => [],
        ];

        $data['invoice_info'][] = [
            "text_ar" => "المجموع",
            "text_en" => "Total",
            'number' => $this->number_format_rtrim($total_prices) . ' ر.س'
        ];

        return response()->data($data);
    }

    public function order(Request $request)
    {
        $user = auth()->user();

        $carts = Cart::where('user_id', $user->id)->where('order_id', null)->pluck('product_id')->toArray();
        $coupon = (new MaintenanceRequestCoupon())->forProduct($request->coupon_id);
        $coupon_id = $coupon? $coupon->id : Null;

        if(count($carts) == 0)
            return response()->error(201, 'لايوجد عناصر في السلة');


        $total_prices = Cart::MyCart()->where('is_exists', true)->sum(DB::raw('price * quantity'));
        // $commission = config('settings.commission');
        // $tax = ($total_prices / 100) * $commission;
        // $total = round($total_prices + $tax, 2);

        if ($total_prices > 0 && $request->selected_payment_method != 'cash') {
            $carts = Cart::MyCart()->with('product')->get();

            // Generate my fatoorah checkout page.
            $paymentMethodId = 0;

            $postFields = [
                'InvoiceValue' => $this->number_format_rtrim($total_prices),
                'CustomerName' => $user->username,
                'CustomerEmail' => null,
                'CustomerMobile' => $user->number_phone,
                'MobileCountryCode' => $user->country->country_code,
                'CallBackUrl' => route('my-fatoorah.payment', [
                    'user_id' => $user->id,
                    'address' => $request->address,
                    'other_phone' => convertArabicNumber($request->other_phone),
                    'coupon_id' => $coupon_id,
                ]),
                'ErrorUrl'    => route('my-fatoorah.error'),
                'InvoiceItems' => []
            ];

            foreach ($carts as $cart) {
                $product_name = $cart->product->name;
                $quantity = $cart->quantity;
                $product_price = $cart->product?->price ?? 0;

                $postFields['InvoiceItems'][] = [
                    'ItemName' => $product_name,
                    'Quantity' => $quantity,
                    'UnitPrice' => $cart->is_exists ? $this->number_format_rtrim($product_price) : 0
                ];
            }
            // dd($postFields);
            // $postFields['InvoiceItems'][] = [
            //     'ItemName' => "الضريبة",
            //     'Quantity' => 1,
            //     'UnitPrice' => number_format($tax, 0)
            // ];

            try {
                $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', false);
                $data = $mfPayment->getInvoiceURL($postFields, $paymentMethodId);
                $invoiceId   = $data['invoiceId'];
                $paymentLink = $data['invoiceURL'];

                return response()->data([
                    'url' => $paymentLink,
                ]);
            } catch (\Exception $ex) {
                return response()->error(201, $ex->getMessage());
            }
        }


        $order =  Order::create([
            'user_id'     =>  $user->id,
            'provider_id' =>  1,
            'coupon_id'   =>  $coupon_id,
            'price'       =>  $coupon? ($total_prices - ($coupon->type == 'discount'? $coupon->value : $coupon->value/100 * $total_prices)): $total_prices,
            // 'commission'  =>  $commission,
            'address'     =>  $request->address,
            'other_phone' =>  convertArabicNumber($request->other_phone),
        ]);


        $title = 'طلب' . ' ' . $user->username . ' ' . 'شراء سلة للطلب رقم ' . $order->id;
        $message = 'وسيلة الدفع المختارة كاش بقيمة ' . $this->number_format_rtrim($total_prices) . ' ر.س';

        Notification::create([
            'user_id'              =>  $user->id,
            'order_id'             =>  $order->id,
            'icon'                 =>  'opencart_faw',
            'title'                =>  'تم طلب شراء سلة برقم الطلب ' . $order->id,
            'message'              =>  $message,
        ]);
        $device_token         =   $user->device_token;
        if ($device_token) {
            $fcm              =    new FCM();
            $title_not            =    'تم طلب شراء سلة برقم الطلب ' . $order->id;
            $fcm->to($device_token)->message($message, $title_not )->data('', 'order', $message, $title_not, 'Notifications')->send();
        }

        $observers_token  =   User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();
        $observers_id     =   User::where('role', 'chat_review')->pluck('id')->filter()->toArray();

        foreach ($observers_id as $user_id){
            Notification::create([
                'user_id'              => $user_id,
                'order_id'             => $order->id,
                'icon'                 => 'opencart_faw',
                'title'                => $title,
                'message'              => $message,
            ]);
        }
        $fcm = new FCM();
        foreach ($observers_token as $token)
            $fcm->to($token)->message($message, $title)->data(NULL, 'order', $message, $title, 'Notifications')->send();

        // update cart
        Cart::where('user_id', auth()->user()->id)->InCart()
        ->update([
            'order_id' => $order->id
        ]);

        return response()->data($order);
    }

    public function payment_order(Request $request)
    {
        $request->validate([
            'payment_id' => ['required', 'unique:payments,payment_id'],
            'user_id' => ['required', 'exists:users,id'],
            'address' => ['required'],
            'other_phone' => ['required'],
            'coupon_id' => ['nullable'],
        ]);

        $paymentId = request()->get('payment_id');
        $user_id = request()->get('user_id');
        $coupon_id = request()->get('coupon_id');

        if (!$paymentId) {
            return response()->error(404, 'رقم الفاتورة غير صحيح');
        }

        // $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY_TEST"), 'SAU', true);
        $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', false);
        $data      = $mfPayment->getPaymentStatus($paymentId, "paymentId");


        if ($data->InvoiceStatus == 'Paid') {
            $user = User::where('id', $user_id)->first();
            $total_prices = $data->InvoiceValue;
            $order                =  Order::create([
                'user_id'              =>  $user_id,
                'provider_id'          =>  1,
                'coupon_id'            =>  $coupon_id,
                'price'                =>  $total_prices,
                'address'              =>  request()->get('address'),
                'other_phone'          =>  request()->get('other_phone'),
            ]);

            Payment::create([
                'payment_id' => $paymentId,
                'transaction_id' => $data->InvoiceId,
                'user_id' => $user_id,
                'provider_id' => 1,
                'order_id' => $order->id,
                'method' => 'myFatoorah',
                'amount' => $total_prices,
                'currency' => 'SAR',
                'paid' => true
            ]);

            Cart::where('user_id', $user_id)->InCart()
             ->update([
                 'order_id' => $order->id
             ]);


            $title = 'طلب' . ' ' . $user->username . ' ' . 'شراء سلة للطلب رقم ' . $order->id;
            $message = 'وسيلة الدفع المختارة بطاقة بنكية بقيمة ' . $this->number_format_rtrim($total_prices) . ' ر.س';

            $notification         =  Notification::create([
                'user_id'              =>  $user->id,
                'order_id'             =>  $order->id,
                'icon'                 =>  'opencart_faw',
                'title'                =>  'تم طلب شراء سلة برقم الطلب ' . $order->id,
                'message'              =>  $message,
            ]);
            $device_token         =   $user->device_token;
            if ($device_token) {
                $fcm              =    new FCM();
                $title_not            =    'تم طلب شراء سلة برقم الطلب ' . $order->id;
                $fcm->to($device_token)->message($message, $title_not )->data('', 'order', $message, $title_not, 'Notifications')->send();
            }

            $observers_token  =   User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();
            $observers_id     =   User::where('role', 'chat_review')->pluck('id')->filter()->toArray();

            foreach ($observers_id as $user_id){
                Notification::create([
                    'user_id'              => $user_id,
                    'order_id'             => $order->id,
                    'icon'                 => 'opencart_faw',
                    'title'                => $title,
                    'message'              => $message,
                ]);
            }
            $fcm = new FCM();
            foreach ($observers_token as $token)
                $fcm->to($token)->message($message, $title)->data(NULL, 'order', $message, $title, 'Notifications')->send();

             // update cart


             return response()->data('sucsses ' . $order->id);// redirect('orders/' . $order->id);
        }

        return response()->error(201, 'error');
    }

    function number_format_rtrim($number) {
        return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }
}
