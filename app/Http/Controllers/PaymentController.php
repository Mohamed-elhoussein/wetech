<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
use App\Http\Controllers\Controller;
use App\Http\Filters\PaymentFilter;
use App\Http\Services\MyfatoorahServices;
use App\Http\Services\PaypalServices;
use App\Models\Offer;
use App\Models\Payment;
use App\Models\User;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Models\ProviderCommission;
use Maatwebsite\Excel\Facades\Excel;


class PaymentController extends Controller
{
    private $myFatoorah;
    private $paypal;
    public function __construct(MyfatoorahServices  $myFatoorah, PaypalServices $paypal)
    {
        $this->myFatoorah = $myFatoorah;
        $this->paypal = $paypal;
    }

    public function index(PaymentFilter $filter)
    {
        $payments = Payment::filter($filter)->with('user')->paginate(request()->get('limit', 15))->withQueryString();
        return view('payments', compact('payments'));
    }

    public function payWithpaypal(Request $request)
    {
        if (!$request->user_d && !$request->offer_id) return view('payment.paypal_error');

        $offer       =   Offer::where('id', $request->offer_id)->with('provider_service:id,title')->firstOrFail();
        $user        =   User::where('id', $request->user_id)->firstOrFail();

        $data = ['offer' =>  $offer->toArray(), 'user' => $user->toArray()];


        $payment = $this->paypal->buildPayment($data)
            ->sendPayment();
        $PaymentState = $payment->payment->getState();
        $payment_id = $payment->payment->getId();
        if ($PaymentState == 'created') {


            $this->savePayment($user, $offer, null, $payment_id, 'paypal', $request->message_id);

            $redirect_url = $payment->redirectLink();
            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }
        }
        return false;
    }
    public function status(Request $request)
    {
        $result = $this->paypal->executePayment($request->paymentId, $request->PayerID);
        if ($result->getState() == 'approved') {

            $payment = $this->SavePaymentSuccess($request->PayerID, $request->paymentId, 'paypal');
            $this->createOrder($payment);
            return Redirect::route('payment.success');
        }

        return Redirect::route('payment.error');
    }

    public function paymentSuccess(Request $request)
    {
        return view('payment.paypal_success');
    }
    public function paymentError()
    {
        return view('payment.paypal_error');
    }
    public function payMyfatoraah(Request $request)
    {

        /* *
        define('MYFATOORAH_URL', 'https://api.myfatoorah.com');
        define("MYFATOORAH_KEY", "bbrcdiI-fXBMjGk8rCJ1pOtYfxShLOxe73jvjWkOVt16KMXcY9fIwo_nREBmLmkuzdnSBb1Aea6DJgZOr1esl4xGiuPF4a5Zqj82fXTGArjVgZsND6QRvvewSK_KPR-5xDxUhILn7C_88betTrcmNtNPlZsiMEWLUvZ1llMgIv_ff0xI8-ChRR1lzCQU4FAAuq59TLWaVk0LolTGT0RhprfM7QLHep33WmM6dzoGGemvoMlPYzRhNyFNxdABlhBlaOZaN2oxHyDSOJ2yheD25aQ9B4Qo9UuHfJWnHvQqSl0aJrkOm9meKhmF_0IDknd8n8lsNXqknaRHv738WYs7pyaZrKFJWDhC9bWUN31ZVVJrolP6SaUWx1H2anebwkmffFm6gs6lVPge0kMJ1dv-vEmxsvTM9VNXdnEAnmDEa0HNe6x9X4HN4ci1WkeWLXNIgHW451T1_jm_aXbPV73HNbtUG-6xxudKQqSpcgVyi6Efg9VbRFoqZBzVF_6pMJ6vfdIZ1lwxzMDVk26ov7OdMsa7_My9SkTizChQH1MYMSLWJDA9Vg9VQrcb-nSTJyAvvrTXgOTejurg-WPugDlpbVh09OI6vGcPv3ND0R61DEbHaLwdz25GH4u_3AIBcERx8fyNEreFvo5jm32Qw7yvwxVGOzSKt1QHsdBc1vwKoa7xf6tBAGJ9pjaaGqbwru74KZj4frRPzpNHWma-Efrkf8CH4Ug");

                'MYFATOORAH_URL', 'https://apitest.myfatoorah.com'
                "MYFATOORAH_KEY", "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL"
        /**/
        // card id  2
        // meda id  6

        /*
        viza test
        5453010000095539	12/25	300
        5453010000095489	12/28	212

        */
        if (!$request->user_d && !$request->offer_id && !$request->currency) return view('payment.myfatoorah_error');;
        $offer       =   Offer::where('id', $request->offer_id)->with('provider_service:id,title', 'provider:id')->firstOrFail();
        $user        =   User::where('id', $request->user_id)->with('country:id,country_code')->firstOrFail();




        $data = [
            'NotificationOption' => 'Lnk',
            'InvoiceValue'       => $offer->price,
            'CustomerName'       => $user->username,
            'DisplayCurrencyIso' => $request->currency,
            'CustomerReference'  => 'OFF_' . $offer->id,
            'UserDefinedField'   => 'USR_' . $user->id,
            'MobileCountryCode'  => $user->country->country_code,
            'CustomerMobile'     => $user->number_phone,
            'CustomerEmail'      => $user->email ?? '',
            'CallBackUrl'        => URL::route('myfatoorah.success'),
            'ErrorUrl'           => URL::route('myfatoorah.error'),
            'Language'           => 'ar',
            'InvoiceItems'       => [[
                'ItemName'  => $offer->provider_service->title,
                'Quantity'  => 1,
                'UnitPrice' => $offer->price

            ]]

        ];

        try {
            $fatoorah     = $this->myFatoorah->sendPayment($data);
        } catch (Exception $ex) {

            return view('payment.myfatoorah_error');
        }

        if ($fatoorah['IsSuccess']) {

            $this->savePayment($user, $offer, $fatoorah["Data"]['InvoiceId'], Null, 'myFatoorah', $request);

            $redirect_url   = $fatoorah["Data"]['InvoiceURL'];

            if (isset($redirect_url)) {

                return Redirect::away($redirect_url);
            }
        }
    }
    public function myfatoorahSuccess(Request $request)
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

        $payment = $this->SavePaymentSuccess($payment['Data']['InvoiceId'], $request->paymentId, 'myfatoorah');
        $this->createOrder($payment);
        return view('payment.myfatoorah_success');
    }
    public function myfatoorahError(Request $request)
    {
        return view('payment.myfatoorah_error');
    }
    private function savePayment($user, $offer, $transaction_id = null, $payment_id = null, $method, $request)
    {

        Payment::create([
            'payment_id' => $payment_id,
            'transaction_id' => $transaction_id,
            'user_id' => $user->id,
            'provider_id' => $offer->provider->id,
            'offer_id' => $offer->id,
            'method' => $method,
            'message_id' => $request->message_id ?? null,
            'amount' => $offer->price,
            'currency' => $request->currency ?? 'USD'

        ]);
    }
    private function SavePaymentSuccess($transaction_id, $paymentId, $method)
    {

        $payment = '';
        if ($method == 'myfatoorah') {
            $payment  =    Payment::where('transaction_id', $transaction_id)->firstOrFail();
            $payment->payment_id = $paymentId;
            $payment->paid = 1;
            $payment->save();
        } elseif ($method == 'paypal') {
            $payment  =  Payment::where('payment_id', $paymentId)->firstOrFail();
            $payment->payment_id = $paymentId;
            $payment->paid = 1;
            $payment->save();
        }
        return  $payment;
    }
    private function createOrder($payment)
    {

        $request = new \Illuminate\Http\Request();

        $request->request->add($payment->getAttributes());

        $request->setMethod('POST');


        $ss = app('App\Http\Controllers\api\OrderApiController')->create($request);

        //dd($ss);

    }


    public function export()
    {
        return Excel::download(new PaymentsExport, 'payments.xlsx');
    }
}
