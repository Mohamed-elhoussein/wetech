<?php

namespace App\Http\Controllers;

use App\Enum\ProductStatus;
use App\Enum\RevisionProductStatus;
use App\Exports\ProductsExport;
use App\Helpers\FCM;
use App\Helpers\Loader;
use App\Helpers\SimpleCSV;
use App\Http\BulkActions\ProductBulkAction;
use App\Http\Filters\ProductFilter;
use App\Libraries\PaymentMyfatoorahApiV2;
use Illuminate\Http\Request;
use App\Models\Countries;
use App\Models\ProductCategories;
use App\Models\ProductTypes;
use App\Models\User;
use App\Models\Product;
use App\Models\Cities;
use App\Models\Fee;
use App\Models\Notification;
use App\Models\Street;
use App\Models\ProductBrand;
use App\Models\ProductPayment;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(ProductFilter $productFilter)
    {
        $products  = Product::filter($productFilter)->latest('id')->paginate(request()->get('limit', 15))->withQueryString()->withQueryString();

        $products->map(function ($product) {
            $product->images = is_array($product->images) ? $product->images : json_decode($product->images);
            return $product;
        });

        return view('products.index', compact('products'));
    }
    public function create()

    {
        $users = User::where('role', 'PROVIDER')->get(['username', 'id']);
        $product_categories  = ProductCategories::all();
        $product_types       = ProductTypes::all();
        $countries           = Countries::where('status', 'ACTIVE')->get(['id', 'name', 'code', 'country_code']);

        return   view('products.create', compact('users', 'countries', 'product_categories', 'product_types'));
    }

    public function store(Request $request)
    {
        if (!userCanAddMoreRecord('products')) {
            return redirect()->route('product.index')->with('deleted', 'لقد تجاوزت الحد المسموح به خلال ساعة المرجو الإنتظار والإعادة لاحقا !');
        }

        $this->validate($request, rules('product.create'));
        $fields = $request->all();

        $images = collect($request->images)->map(function ($image) {
            return upload_picture($image, '/images/product');
        })->toArray();


        // $images             =   collect($fields)->keys()
        //     ->map(function ($key) {
        //         return str_starts_with($key, 'image_') ? $key : Null;
        //     })
        //     ->whereNotNull()
        //     ->values()
        //     ->toArray();

        // $gallery            =   [];
        // foreach ($images as $image) {
        //     $gallery[]  =  upload_picture($fields[$image], '/images/product');
        // };
        // $gallery            =   implode('||', $gallery);

        $product = product::create([
            'user_id'               => $request->user_id,
            'city_id'               => $request->city_id,
            'street_id'             => $request->street_id,
            'product_category_id'   => $request->product_category_id,
            'product_type_id'       => $request->product_type_id,
            'product_brand_id'      => $request->product_brand_id,
            'name'                  => $request->name,
            'name_en'               => $request->name_en,
            'images'                => json_encode($images),
            'color'                 => $request->color,
            'disk_info'             => $request->disk_info,
            'duration_of_use'       => $request->duration_of_use,
            'guarantee'             => $request->guarantee,
            'status'                => $request->status,
            'price'                 => $request->price,
            'is_offer'              => $request->is_offer,
            'offer_price'           => $request->offer_price,
            'description'           => $request->description,
            'delivery_fee'          => $request->delivery_fee,
            'active'                => isset($request->active)
        ]);

        return redirect()->route('product.index')->with('created', 'تم إنشاء المنتج بنجاح');
    }

    public function edit($id)
    {
        $users = User::where('role', 'PROVIDER')->get(['username', 'id']);
        $product_categories  = ProductCategories::all();
        $product_types       = ProductTypes::all();
        $countries           = Countries::where('status', 'ACTIVE')->get(['id', 'name', 'code', 'country_code']);
        $product             = Product::where('id', $id)->firstOrFail();
        $cities              = Cities::where('country_id', optional($product->city)->country_id)->get();
        $streets             = Street::where('city_id', $product->city_id)->get();
        $product_brands      = ProductBrand::where('id', $product->product_type_id)->get();
        $country_id = optional($product->city)->country_id;
        return view('products.edit', compact('countries', 'product_categories', 'product_types', 'users', 'product', 'product_brands', 'cities', 'streets', 'country_id'));
    }

    public function update(Request $request, $id)
    {
        $fields              =   $request->all();
        $product             =   Product::where('id', $id)->firstOrFail();

        $fields = $request->all();
        // $removed_images     =    json_decode($request->removed_images, true);

        /*  get the exesting images and remove the removed images  */

        // $removed_images ?  $existingGallery            =      collect(explode('||', $product->images))
        //     ->map(function ($item) use ($removed_images) {
        //         return in_array(url($item), $removed_images) ?  Null : $item;
        //     })
        //     ->whereNotNull()
        //     ->values()
        //     ->toArray()


        //     :  $existingGallery            = explode('||', $product->images);

        /*  get the new images and upload it */

        $images = collect($request->images)->map(function ($image) {
            return upload_picture($image, '/images/product');
        })->toArray();

        // $images             =      collect($fields)->keys()
        //     ->map(function ($key) {
        //         return str_starts_with($key, 'image_') ? $key : Null;
        //     })
        //     ->whereNotNull()
        //     ->values()
        //     ->toArray();


        // $gallery            =       [];



        // foreach ($images as $image) {
        //     $gallery[]  =  upload_picture($fields[$image], '/images/product');
        // };

        // $gallery            =       array_merge($existingGallery, $gallery);
        // $gallery            =   implode('||', $gallery);



        isset($fields['user_id'])                   ?   $product->user_id = $fields['user_id']                        :   false;
        isset($fields['city_id'])                   ?   $product->city_id = $fields['city_id']                        :   false;
        isset($fields['street_id'])                 ?   $product->street_id = $fields['street_id']                    :   false;
        isset($fields['product_category_id'])       ?   $product->product_category_id = $fields['product_category_id'] :   false;
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
        $product->images = count($images) > 0 ?  $images : $product->images;
        $product->delivery_fee = $request->delivery_fee;
        $product->active = isset($request->active);

        $product->save();

        return redirect()->route('product.index')->with('updated', 'تم تحديث المنتج بنجاح');
    }

    public function delete($id)
    {
        Product::where('id', $id)->delete();
        return  redirect()->route('product.index')->with('deleted', 'تم حذف المنتج بنجاح');
    }


    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function bulkAction(ProductBulkAction $productBulkAction)
    {
        Product::bulkAction($productBulkAction);
    }

    public function import(Request $request)
    {
        $request->validate(rules('providers.import'), [
            'file.mimetypes' => 'يجب أن يكون الملف من النوع: text/csv،application/csv'
        ]);

        $file = $request->file('file');

        $rows = SimpleCSV::import($file);

        // dd($rows);

        $products = collect($rows)->skip(1)->map(function ($row) {
            if (count($row) === 11) {
                return [
                    'name' => $row[0],
                    'name_en' => $row[1],
                    'user_id' => Loader::getUserId(clean_csv_input($row[2])),
                    'city_id' => Loader::getCityId(clean_csv_input($row[3])),
                    'street_id' => Loader::getStreetId(clean_csv_input($row[4])),
                    'color' => $row[5],
                    'disk_info' => $row[6],
                    'duration_of_use' => $row[7],
                    'status' => ProductStatus::fromArabicStatus(clean_csv_input($row[8])),
                    'price' => $row[9],
                    'description' => $row[10],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        })->filter(function ($provider) {
            return $provider !== null;
        })->toArray();

        // dd($products, $rows);

        Product::insert($products);

        return redirect()->route('product.index')->with('created', 'تم رفع المنتوجات بنجاح');
    }

    public function createOrderInvoice(Request $request, $user_id)
    {
        $request->validate([
            'products' => 'required|array'
        ]);

        $user = User::with(['country' => function ($q) {
            $q->select('id', 'code', 'unit_en', 'country_code');
        }])->findOrFail($user_id);

        $products = Product::query()->whereIn('id', $request->products)->get();

        $items = [];
        $price = 0;

        $products->map(function ($product) use (&$items, &$price) {
            $price += $product->price + $product->delivery_fee;

            $items[] = [
                'ItemName' => $product->name,
                'Quantity' => 1,
                'UnitPrice' => $product->price
            ];
            $items[] = [
                'ItemName' => 'رسوم التوصيل ' . $product->name,
                'Quantity' => 1,
                'UnitPrice' => $product->delivery_fee
            ];
        });

        $fees = 0; //$price * 0.035;
        $amount = $price + $fees;
        $currency = getUserUnit($user);

        $items = Fee::query()->online()->get()->map(function ($fee) {
            return [
                'ItemName' => $fee->name,
                'Quantity' => 1,
                'UnitPrice' => $fee->value
            ];
        });

        $amount += $items->sum('UnitPrice');

        $paymentMethodId = 0; //to be redirect to MyFatoorah invoice page
        $postFields = [
            'InvoiceValue' => $amount,
            'CustomerName' => $user->username,
            'CustomerEmail' => $user->email,
            'CustomerMobile' => $user->number_phone,
            'MobileCountryCode' => optional($user->country)->country_code,
            "DisplayCurrencyIso" =>  $currency,
            'CallBackUrl' => route('products.invoice.order.success'),
            'InvoiceItems' => $items
        ];

        try {
            $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', env("MYFATOORAH_TEST"));
            $data      = $mfPayment->getInvoiceURL($postFields, $paymentMethodId);

            $invoiceId   = $data['invoiceId'];
            $paymentLink = $data['invoiceURL'];

            $payments = $products->map(function ($product) use ($invoiceId, $user, $amount, $fees, $currency) {
                return [
                    'paiment_id' => $invoiceId,
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'method' => 'myfatoorah',
                    'amount' => $amount,
                    'fees' => $fees,
                    'currency' => $currency
                ];
            })->toArray();

            ProductPayment::insert($payments);

            return redirect($paymentLink);

            echo "Click on <a href='$paymentLink' target='_blank'>$paymentLink</a> to pay with invoiceID $invoiceId.";
        } catch (\Exception $ex) {
            abort(500, $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function successInvoiceOrder(Request $request)
    {
        // Working on it
        try {
            $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', env("MYFATOORAH_TEST"));
            $data      = $mfPayment->GetPaymentStatus($request->paymentId, "PaymentId");

            $is_paid = $data->InvoiceStatus === 'Paid';

            ProductPayment::query()->where('paiment_id', $data->InvoiceId)->update([
                'is_paid' => $is_paid,
                'paiment_type' => $data->InvoiceTransactions[0]->PaymentGateway
            ]);

            if ($request->ajax()) {
                return new JsonResponse([
                    'status' => $is_paid
                ]);
            }

            auth()->user()->carts()->delete();

            return redirect()->route('store.cart')->with('success', 'تم الدفع بنجاح');
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function createInvoice(int $user_id, int $product_id)
    {
        $user = User::with(['country' => function ($q) {
            $q->select('id', 'code', 'unit_en', 'country_code');
        }])->findOrFail($user_id);

        $product = Product::findOrFail($product_id);

        $fees = 0; //$product->price * 0.035;
        if($product->is_offer === 1)
            $amount = $product->offer_price + $fees + $product->delivery_fee;
        else
            $amount = $product->price + $fees + $product->delivery_fee;
        $currency = getUserUnit($user);

        $items = Fee::query()->online()->get()->map(function ($fee) {
            return [
                'ItemName' => $fee->name,
                'Quantity' => 1,
                'UnitPrice' => $fee->value
            ];
        });

        $amount += $items->sum('UnitPrice');

        $invoiceItems = [
            [
                'ItemName' => $product->name,
                'Quantity' => 1,
                'UnitPrice' => $product->is_offer === 1? $product->offer_price : $product->price
            ],
            ...$items->toArray(),
        ];

        if ($product->delivery_fee && $product->delivery_fee > 0) {
            $invoiceItems[] = [
                'ItemName' => 'رسوم التوصيل',
                'Quantity' => 1,
                'UnitPrice' => $product->delivery_fee
            ];
        }

        $paymentMethodId = 0; //to be redirect to MyFatoorah invoice page
        $postFields = [
            'InvoiceValue' => $amount,
            'CustomerName' => $user->username,
            'CustomerEmail' => filter_var($user->email, FILTER_VALIDATE_EMAIL) ? $user-> email : null,
            'CustomerMobile' => $user->number_phone,
            'MobileCountryCode' => optional($user->country)->country_code,
            "DisplayCurrencyIso" =>  $currency,
            'CallBackUrl' => route('products.invoice.order.success'),
            'InvoiceItems' => $invoiceItems
        ];


        try {
            $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', env("MYFATOORAH_TEST"));
            $data      = $mfPayment->getInvoiceURL($postFields, $paymentMethodId);

            $invoiceId   = $data['invoiceId'];
            $paymentLink = $data['invoiceURL'];

            ProductPayment::create([
                'paiment_id' => $invoiceId,
                'product_id' => $product->id,
                'user_id' => $user->id,
                'method' => 'myfatoorah',
                'amount' => $amount,
                'fees' => $fees,
                'currency' => $currency
            ]);

            return redirect($paymentLink);

            echo "Click on <a href='$paymentLink' target='_blank'>$paymentLink</a> to pay with invoiceID $invoiceId.";
        } catch (\Exception $ex) {
            // abort(500, $ex->getMessage());
            echo $ex->getMessage();
        }
    }

    public function successInvoice(Request $request)
    {
        try {
            $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', env("MYFATOORAH_TEST"));
            dd($mfPayment);
            $data      = $mfPayment->GetPaymentStatus($request->paymentId, "PaymentId");

            $is_paid = $data->InvoiceStatus === 'Paid';

            ProductPayment::where([
                'paiment_id' => $data->InvoiceId
            ])->firstOrFail()->update([
                'is_paid' => $is_paid,
                'paiment_type' => $data->InvoiceTransactions[0]->PaymentGateway
            ]);

            return new JsonResponse([
                'status' => $is_paid
            ]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function updateRevisionStatus(Request $request)
    {
        $product            =   Product::where('id', $request->product_id)->with('provider:id,device_token')->first();
        $product->revision_status    =   in_array($request->status, RevisionProductStatus::all()) ? $request->status : RevisionProductStatus::all()[0];
        $product->save();

        if ($request->status == 'accepted' ||  $request->status == 'denied') {
            if ($product->provider) {
                $notification   =  Notification::create([
                    'user_id'       => $product->provider->id,
                    'icon'          => 'bell_outline_mco',
                    'title'         => ($request->status == 'accepted' ? 'مبروك! تم قبول المنتج ' : 'للأسف، تم رفض المنتج  ') . $product->name,
                    'message'       => '',
                ]);
                $device_token     =   $product->provider->makeVisible(['device_token'])->device_token;
                if ($device_token) {
                    $fcm                =    new FCM();
                    $title              =    $notification->title;
                    $fcm->to($device_token)->message('', $title)->data(Null, 'services_status', '', $title, 'Notifications')->send();
                }
            }
        }

        return new JsonResponse([
            'success' => true
        ]);
    }

    public function productsCategory(ProductCategories $category)
    {
        return response()->data($category->products()->get());
    }
}
