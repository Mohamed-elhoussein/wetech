<?php

namespace App\Http\Controllers;

use App\Libraries\PaymentMyfatoorahApiV2;
use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use App\Helpers\FCM;

class CartController
{
    public function payment_order(int $user_id)
    {
        $paymentId = request()->get('paymentId');

        if (!$paymentId) {
            abort(404);
        }

        $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', false);
        $data      = $mfPayment->getPaymentStatus($paymentId, "paymentId");


        if ($data->InvoiceStatus == 'Paid') {
            $user = User::where('id', $user_id)->first();
            $total_prices = $data->InvoiceValue;
            $order                =  Order::create([
                'user_id'              =>  $user_id,
                'provider_id'          =>  1,
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


            return redirect('/payment/success?order_id=' . $order->id);// redirect('orders/' . $order->id);
        }

        return redirect('/payment/error?user_id='. $user_id);
    }

    public function fatoorah_success()
    {
        return view('payment.myfatoorah_success');
    }

    public function fatoorah_error()
    {
        return view('payment.myfatoorah_error');
    }

    function number_format_rtrim($number) {
        return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }
}
