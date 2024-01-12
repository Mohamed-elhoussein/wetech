<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Services\MyfatoorahServices;
use App\Http\Services\PaypalServices;
use App\Models\PayMethodes;
use App\Models\Slider;
use App\Models\Setting;
use App\Models\Subscribe;
use App\Models\SubscribePackes;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use stdClass;

class SubscribeApiController extends Controller
{
    private $myFatoorah;
    private $paypal;
    public function __construct(MyfatoorahServices  $myFatoorah, PaypalServices $paypal)
    {
        $this->myFatoorah = $myFatoorah;
        $this->paypal = $paypal;
    }
    public function subscribeInfo(Request $request)
    {
        $sliders = Slider::where('target', 'SUBSCRIBE_PAGE')->get();
        $sliders->each(function ($slider) {
            $slider->image  = url($slider->image);
        });
        $packes = SubscribePackes::orderBy('order_index')->get();
        $pay_methods = PayMethodes::where('online', 1)->get();

        $subscribe        =   Subscribe::where('user_id', $request->user_id)->where('is_paid', 1)->latest()->first();
        $subscribe        =  [
            'remain_days'     =>      max((strtotime(optional($subscribe)->die_at) - strtotime(now())),  0) ?? 0,
            'total_days'      =>      optional($subscribe)->total_days ?? 0,
        ];

        $data = [
            'subscribe'         => $subscribe,
            'sliders'           => $sliders,
            'packes'            => $packes,
            'pay_methods'       => $pay_methods,
            'Features'          => include public_path('config/subscribe.php'),
            'active_subscribe'  => Setting::where('key', 'active_subscribe')->first()->value ?? '0',
        ];
        return response()->data($data, '');
    }
    public function payNewSubscribe(Request $request)
    {
        if ($request->method == 'paypal') return $this->payWithpaypal($request);

        if ($request->method == 'myfatoorah') return  $this->payWithMyFatoorah($request);
    }
    public function payWithpaypal(Request $request)
    {
        $user        =   User::where('id', $request->user_id)->with('country:id,country_code')->firstOrFail();
        //$user        =  auth()->user();
        $data = $request;

        $payment = $this->paypal->buildSubscribePayment($data)
            ->sendPayment();

        $PaymentState = $payment->payment->getState();
        $payment_id = $payment->payment->getId();
        if ($PaymentState == 'created') {


            $this->savePayment($user, $data, $payment_id);

            $redirect_url = $payment->redirectLink();
            if (isset($redirect_url)) {

                return Redirect::away($redirect_url);
            }
        }
        return false;
    }
    public function status(Request $request)
    {

        try {
            $result = $this->paypal->executePayment($request->paymentId, $request->PayerID);
        } catch (Exception $ex) {
            return Redirect::route('payment.error');
        }
        $result = $this->paypal->executePayment($request->paymentId, $request->PayerID);

        if ($result->getState() == 'approved') {


            try {
                $subscribe =  Subscribe::where('payment_id', $request->paymentId)->latest()->firstOrFail();
                $subscribe->is_paid = 1;
                $subscribe->die_at = now()->addDays($subscribe->total_days);
                $subscribe->save();
            } catch (Exception $ex) {
                return view('payment.myfatoorah_error');
            }

            return Redirect::route('payment.success');
        }

        return Redirect::route('payment.error');
    }

    private function payWithMyFatoorah($data)
    {
        $user        =   User::where('id', $data->user_id)->with('country:id,country_code')->firstOrFail();
        //  $user        =  auth()->user();

        // $payment_info = [
        //     'NotificationOption' => 'Lnk',
        //     'InvoiceValue'       => $data->price,
        //     'CustomerName'       => $user->username,
        //     'DisplayCurrencyIso' => 'SAR',
        //     'UserDefinedField'   => 'USR_' . $user->id,
        //     'MobileCountryCode'  => $user->country->country_code,
        //     'CustomerMobile'     => $user->number_phone,
        //     'CustomerEmail'      => $user->email ?? '',
        //     'CallBackUrl'        => URL::route('subscribe.success'),
        //     'ErrorUrl'           => URL::route('subscribe.error'),
        //     'Language'           => 'ar',
        //     'InvoiceItems'       => [[
        //         'ItemName'  => 'دفع اشتراك لدكتور تك',
        //         'Quantity'  => 1,
        //         'UnitPrice' => $data->price

        //     ]]
        // ];

        $payment_info = [
            'NotificationOption' => 'Lnk',
            'InvoiceValue'       => $data->price,
            'CustomerName'       => $user->username,
            'DisplayCurrencyIso' => 'SAR',
            'UserDefinedField'   => 'USR_' . $user->id,
            'MobileCountryCode'  => $user->country->country_code,
            'CustomerMobile'     => $user->number_phone,
            'CallBackUrl'        => URL::route('subscribe.success'),
            'ErrorUrl'           => URL::route('subscribe.error'),
            'Language'           => 'ar',
            'InvoiceItems'       => [[
                'ItemName'  => 'دفع اشتراك ل وي تك',
                'Quantity'  => 1,
                'UnitPrice' => $data->price
            ]]
        ];

        if ($user->email) { // $user->hasValidEmail()
            $payment_info['CustomerEmail'] =  $user->email;
        }

        try {
            $fatoorah     = $this->myFatoorah->sendPayment($payment_info);
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }

        if ($fatoorah['IsSuccess']) {

            $this->savePayment($user, $data, $fatoorah["Data"]['InvoiceId']);

            $redirect_url   = $fatoorah["Data"]['InvoiceURL'];

            if (isset($redirect_url)) {

                return Redirect::away($redirect_url);
            }
        }
    }
    private function savePayment($user, $data, $payment_id)
    {
        //$die_at = $user->subscribe_finished_at ? Carbon::parse($user->subscribe_finished_at)->addDays($data->days) : now()->addDays($data->days);

        Subscribe::create([
            'user_id' => $user->id,
            'amount' => $data->price,
            'payment_id' => $payment_id,
            'die_at' => now()->addDays($data->days),
            'method' => $data->method,
            'total_days' => $data->days,
        ]);
    }
    public function subscribeSuccess(Request $request)
    {
        $data = [
            "Key" => $request->paymentId,
            "KeyType" => "PaymentId"
        ];
        try {
            $payment = $this->myFatoorah->getPaymentInfo($data);
        } catch (Exception $ex) {
            return view('payment.myfatoorah_error');
        }
        try {
            $subscribe =  Subscribe::where('payment_id', $payment['Data']['InvoiceId'])->latest()->firstOrFail();
            $subscribe->is_paid = 1;
            $subscribe->die_at = now()->addDays($subscribe->total_days);
            $subscribe->save();
        } catch (Exception $ex) {
            return view('payment.myfatoorah_error');
        }


        return view('payment.myfatoorah_success');
    }
    public function subscribeError(Request $request)
    {
        return view('payment.myfatoorah_error');
    }
    public function create(Request $request)
    {
        Subscribe::create([
            'user_id'       => $request->user_id,
            'amount'        => $request->amount,
            'currency'      => $request->currency,
            'method'        => $request->method,
            'payment_id'    => $request->payment_id,
            'is_paid'       => $request->is_paid,
            'total_days'    => $request->total_days,
            'die_at'        => now()->addDays($request->total_days),

        ]);

        $sliders = Slider::where('target', 'SUBSCRIBE_PAGE')->get();
        $sliders->each(function ($slider) {
            $slider->image  = url($slider->image);
        });
        $packes = SubscribePackes::orderBy('order_index')->get();
        $pay_methods = PayMethodes::all();

        $subscribe        =   Subscribe::where('user_id', $request->user_id)->where('is_paid', 1)->latest()->first();
        $subscribe        =  [
            'remain_days'     =>      max((strtotime(optional($subscribe)->die_at) - strtotime(now()))  ,  0 ) ?? 0 ,
            'total_days'      =>      optional($subscribe)->total_days ?? 0,
        ];

        $data = [
            'sliders'       => $sliders,
            'packes'        => $packes,
            'pay_methods'   => $pay_methods,
            'Features'      => include public_path('config/subscribe.php'),
            'subscribe'     => $subscribe,
        ];
        return response()->data($data, '');
    }

    public function statistics(){
        $SAR        =   Subscribe::where('is_paid', 1)->whereIn('method', ['myfatoorah', 'iosIAP'])
                                 ->where(function ($query) {
                                     $query->where('currency', '!=', 'USD')->orWhereNull('currency');
                                 })->sum('amount');

        $USD        =   Subscribe::where('is_paid', 1)->where('method', '!=', 'myfatoorah')
                                 ->where(function ($query) {
                                     $query->where('currency', '!=', 'SAR')->orWhereNull('currency');
                                  })->sum('amount');

        $ios        =   Subscribe::where('is_paid', 1)->where('method', 'iosIAP')->count();
        $android    =   Subscribe::where('is_paid', 1)->where('method', '!=', 'iosIAP')->count();

        $data = [
            'total' => $SAR + ($USD * 3.75),
            'ios' => $ios,
            'android' => $android
        ];
        return response()->data($data);
    }
}
