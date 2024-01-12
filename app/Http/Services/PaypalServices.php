<?php

namespace App\Http\Services;

use App\Models\Fee;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use stdClass;

class PaypalServices
{
    private $api_context;
    public $payment;

    public function __construct()
    {
        $this->api_context = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            )
        );
        $this->api_context->setConfig(config('paypal.settings'));
    }


    public function buildPayment($data = [])
    {
        $data =  (object)$data;


        $item              =  new Item();
        $item->setName($data->offer['provider_service']['title'])
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($data->offer['price']);


        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items =   new ItemList();
        $items->setItems(array($item));


        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($data->offer['price']);
        $redirect_urls = new RedirectUrls;

        $redirect_urls->setReturnUrl(URL::route('payment.status'))
            ->setCancelUrl(URL::route('payment.status'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($items);


        $this->payment = new Payment();
        $this->payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        return $this;
    }
    public function buildSubscribePayment($data)
    {



        $item              =  new Item();
        $item->setName('دفع الإشتراك لدكتور تك')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($data->price);


        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items =   new ItemList();
        $items->setItems(array($item));


        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($data->price);
        $redirect_urls = new RedirectUrls;

        $redirect_urls->setReturnUrl(URL::route('subscribe.status'))
            ->setCancelUrl(URL::route('subscribe.status'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($items);


        $this->payment = new Payment();
        $this->payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        return $this;
    }

    public function buildMaintenanceRequestPayment($data, $callback_url, $with_fees = true)
    {
        try {
            $total = 0;

            $_items = [];

            if ($with_fees) {
                $total = Fee::query()->online()->active()->forMaintenance()->get()->map(function ($fee) use (&$_items) {
                    $item =  new Item();
                    $item->setName($fee->name)
                        ->setCurrency('USD')
                        ->setQuantity(1)
                        ->setPrice(number_format(floatval($fee->value) * 0.27, 2));

                    $_items[] = $item;
                    return $fee->value;
                })->sum();
            }

            $price = floatval($data['price']) * 0.27;

            $item =  new Item();
            $item->setName($data['title'])
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($price);
            $_items[] = $item;

            $data['price'] = $data['price'] + $total;
            $price = floatval($data['price']) * 0.27;

            $items =   new ItemList();
            $items->setItems($_items);

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $amount = new Amount();
            $amount->setCurrency('USD')->setTotal($price);

            $redirect_urls = new RedirectUrls;
            $redirect_urls
                ->setReturnUrl($callback_url)
                ->setCancelUrl($callback_url);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($items);

            $this->payment = new Payment();
            $this->payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
        }
        catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $this;
    }

     public function buildUserPayment($data)
    {


        $item              =  new Item();
        $item->setName('رفع رصيدك')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($data->price);


        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items =   new ItemList();
        $items->setItems(array($item));


        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($data->price);
        $redirect_urls = new RedirectUrls;

        $redirect_urls->setReturnUrl(URL::route('user.payement.status'))
            ->setCancelUrl(URL::route('user.payement.status'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($items);


        $this->payment = new Payment();
        $this->payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        return $this;
    }
    public function sendPayment()
    {
        try {

            $this->payment->create($this->api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (config('app.debug')) {
                session('error', 'Connection timeout');
                return $this;
            } else {
                session('error', 'Some error occur, sorry for inconvenient');
                return $this;
            }
        }
        return $this;
    }
    public function executePayment($paymentId, $PayerID)
    {
        $payment = Payment::get($paymentId, $this->api_context);

        $execution = new PaymentExecution();
        $execution->setPayerId($PayerID);

        $result = $payment->execute($execution, $this->api_context);

        return $result;
    }
    public function redirectLink()
    {

        foreach ($this->payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                return $link->getHref();
            }
        }
    }
}
