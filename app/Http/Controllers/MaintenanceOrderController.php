<?php

namespace App\Http\Controllers;

use App\Bots\ErrorBots;
use App\Libraries\PaymentMyfatoorahApiV2;
use App\Models\MaintenanceOrderPayment;
use App\Models\MaintenanceRequestCoupon;
use App\Models\MaintenanceRequestOrder;
use App\Models\MaintenanceRequestOrderCoupon;
use App\Models\MaintenanceType;
use App\Models\Order;
use App\Models\User;
use App\Helpers\FCM;
use App\Models\Notification;
use App\Models\PaymentOption;

class MaintenanceOrderController extends Controller
{
    public function store_fatoorah()
    {
        $data = request()->all();

        $coupon = null;

        if(isset($data['coupon'])) {
            $coupon = MaintenanceRequestCoupon::find($data['coupon']);
        }

        if (!array_key_exists('paymentId', $data)) {
            return redirect('/');
        }

        try {
            $payment = MaintenanceOrderPayment::query()->where([
                'payment_id' => $data['paymentId']
            ])->first();

            $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', false);

            $mfdata      = $mfPayment->GetPaymentStatus(request()->paymentId, "PaymentId");

            $is_paid = $mfdata->InvoiceStatus === 'Paid';

            if ($is_paid) {
                $observers_token  =   User::where('role', 'chat_review')->get();
                if ($observers_token) {
                    $fcm                 =    new FCM();
                    foreach ($observers_token as $observer) {
                        $notifi_observer         =  Notification::create([
                            'user_id'              => $observer->id,
                            'order_id'             => NULL,
                            'icon'                 => 'bell_outline_mco',
                            'title'                => 'قام ' . $mfdata->CustomerName .  ' بدفع مبلغ' . $mfdata->InvoiceDisplayValue . '.',
                            'message'              => 'رقم العميل ' . $mfdata->CustomerMobile,
                        ]);
                        $fcm->to($observer->device_token)
                            ->message($notifi_observer->message, $notifi_observer->title)
                            ->data('', 'order', $notifi_observer->message, $notifi_observer->title, 'Notifications')
                            ->send();
                    }
                }
            }

            if (!$is_paid) return redirect('/order/payment-error');

            if ($payment) return redirect('/order/success');

            if ($is_paid) {
                // new ErrorBots('if ($is_paid)');
                $order = MaintenanceRequestOrder::create([
                    'provider_id' => $data['provider_id'],
                    'maintenance_type_id' => $data['maintenance_id'],
                    'note' => array_key_exists('note', $data) ? $data['note'] : null,
                    'payment_method' => $data['payment_way'],
                    'city_id' => $data['city_id'],
                    'street_id' => $data['street_id'],
                ]);
                // new ErrorBots('MaintenanceRequestOrder created');
                MaintenanceOrderPayment::create([
                    'payment_id' => $data['paymentId'],
                    'maintenance_order_id' => $order->id
                ]);
                // new ErrorBots('MaintenanceOrderPayment created');
                if ($coupon) {
                    MaintenanceRequestOrderCoupon::create([
                        'maintenance_request_order_id' => $order->id,
                        'maintenance_request_coupon_id' => $coupon->id,
                    ]);
                }

                $price = MaintenanceType::query()->find($data['maintenance_id'])->price;
                // new ErrorBots('price: ' . $price);
                // if ($coupon) {
                //     $price = $price - $coupon->value;
                // }

                $base_order = Order::create([
                    'user_id' => $data['user_id']?? NULL,
                    'price' => $price?? NULL,
                    'status' => 'PENDING',
                    'provider_id' => $data['provider_id']?? NULL,
                    'maintenance_request_order_id' => $order->id?? NULL,
                    // 'provider_service_id' => $data['provider_service_id']?? NULL
                ]);
                $this->send_notifications($base_order);
                // new ErrorBots('base_order: created');
                $payment_option = $this->getPaymentOptions($data['payment_option']);

                $payment_type = ($data['payment_way'] == 'epay' || $data['payment_way'] == 'paypal') ? 'online' : 'cash';

                if ($payment_option->payment_type === $payment_type) {
                    $base_order->savePaymentOption($payment_option);
                }

                $base_order->saveFees($data['payment_way']);
            }

            return redirect('/order/success');
        } catch (\Exception $ex) {
            // return redirect('/order/success');
            new ErrorBots('exeption pay maintenance order my fatorrah');
            new ErrorBots(json_encode($data));
            new ErrorBots(json_encode($mfdata));
            new ErrorBots($ex);
        }
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

    public function store_paypal()
    {
        $data = request()->all();

        $coupon = null;

        if(isset($data['coupon'])) {
            $coupon = MaintenanceRequestCoupon::find($data['coupon']);
        }

        if (!array_key_exists('paymentId', $data)) {
            return redirect('/');
        }

        try {
            $payment = MaintenanceOrderPayment::query()->where([
                'payment_id' => $data['paymentId']
            ])->first();

            // $mfPayment = new PaymentMyfatoorahApiV2(env("MYFATOORAH_KEY"), 'SAU', true);
            // $mfdata      = $mfPayment->GetPaymentStatus(request()->paymentId, "PaymentId");
            // $is_paid = $mfdata->InvoiceStatus === 'Paid';

            $is_paid = true;

            if ($payment) return redirect('/order/success');

            if ($is_paid) {
                $order = MaintenanceRequestOrder::create([
                    'provider_id' => $data['provider_id'],
                    'maintenance_type_id' => $data['maintenance_id'],
                    'note' => array_key_exists('note', $data) ? $data['note'] : null,
                    'payment_method' => $data['payment_way'],
                    'city_id' => $data['city_id'],
                    'street_id' => $data['street_id'],
                ]);

                MaintenanceOrderPayment::create([
                    'payment_id' => $data['paymentId'],
                    'maintenance_order_id' => $order->id
                ]);

                if ($coupon) {
                    MaintenanceRequestOrderCoupon::create([
                        'maintenance_request_order_id' => $order->id,
                        'maintenance_request_coupon_id' => $coupon->id,
                    ]);
                }

                $price = MaintenanceType::query()->find($data['maintenance_id'])->price;

                // if ($coupon) {
                //     $price = $price - $coupon->value;
                // }

                $base_order = Order::create([
                    'user_id' => $data['user_id'],
                    'price' => $price,
                    'status' => 'PENDING',
                    'provider_id' => $data['provider_id'],
                    'maintenance_request_order_id' => $order->id,
                    'provider_service_id' => $data['provider_service_id']
                ]);

                $payment_option = $this->getPaymentOptions($data['payment_option']);

                $payment_type = ($data['payment_way'] == 'epay' || $data['payment_way'] == 'paypal') ? 'online' : 'cash';

                if ($payment_option->payment_type === $payment_type) {
                    $base_order->savePaymentOption($payment_option);
                }

                $base_order->saveFees($data['payment_way']);
            }

            return redirect('/order/success');
        } catch (\Exception $ex) {
            // return redirect('/order/success');
        }
    }

    public function fatoorah_success()
    {
        return view('myFatoorah.success');
    }

    public function fatoorah_failed()
    {
        return view('myFatoorah.error');
    }

    private function getPaymentOptions($id = null)
    {
        if ($id) return PaymentOption::find($id);

        return PaymentOption::orderBy('id', 'DESC')->get()->map(function ($option) {
            return [
                'id' => $option->id,
                'type' => $option->type,
                'amountValue' => $option->value,
                'name' => $option->label,
                'valueForServer' => $option->payment_type,
                'underText' => $option->sub_text,
                'url' => $option->id === 2? 'https://wetech.drtech-api.com/images/im_mcmv.png' : '',
                'isActive' => true,
                'label' => $option->label
            ];
        });
    }
}
