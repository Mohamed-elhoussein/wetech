<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderBuyerRequestResource
{
    private $request;
    private $order;

    public function __construct($request, $order)
    {
    //    parent::__construct($collection);
       $this->request = $request;
       $this->order = $order;

       return ['order' => $this->order];
       
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ['order' => $this->order];

        $description       =   $order->product_id? string_value(451, $request). ': ' . $order->product->description :  string_value(257, $request). ': ' . optional($order->offer)->description;
        $description_en    =   $order->product_id? string_value(451, $request, true). ': ' . $order->product->description :  string_value(257, $request, true). ': ' . optional($order->offer)->description;

        $payment_method    = $order->product_payment_id && $order->product_payment->is_paid? 'تم الدفع عبر بطاقة بنكية': 'الدفع عند الاستلام';
        $payment_method_en = $order->product_payment_id && $order->product_payment->is_paid? 'Payment was made by bank card' : 'cash on delivery';

        return [
            "id"                => $order->id,
            "user_id"           => $order->user_id,
            "provider_id"       => $order->provider_id,
            "offer_id"          => $order->offer_id,
            "service_id"        => $order->provider_service->id,
            "buyer_request_id"  => $order->buyer_request_id,
            "promo_id"          => null,
            "status"            => $order->status,
            "created_at"        => Change_Format($order->created_at),
            "updated_at"        => Change_Format($order->updated_at),
            "service_icon"      => ($order->product_id? !is_array($order->product->images)? json_decode($order->product->images)[0] : $order->product->images : $order->provider_service->thumbnail) ?? default_image(),
            "service_name"      => ($order->product_id? $order->product->name : ($order->provider_service->title === Null ? get_title(6, $order->provider_service)->name : $order->provider_service->title))  . ' #' . $order->id,
            "service_name_en"   => ($order->product_id? $order->product->name_en : ($order->provider_service->title === Null ? get_title(6, $order->provider_service)->name : $order->provider_service->title))  . ' #' . $order->id,
            'name'              => optional($order->provider)->username,
            'number_phone'      => '', //$order->provider->country->country_code . $order->provider->number_phone,
            "price"             => $order->product_id? ($order->price + $order->product->delivery_fee) : $order->price,// + ($order->product_payment_id && $order->product_payment->is_paid? 0 : 12)
            "description"       => $description,
            "description_en"    => $description_en,
            "service_target"    => $order->product_id? null: optional($order->provider_service->service_full)->target,
            "who_canceled"      => $order->canceled_by == $order->user_id ? 'user' : ($order->canceled_by == $order->provider_id ? 'provider' : ($order->canceled_by != NULL?  'admin' : NULL)),
            "canceled_reason"   => $order->canceled_reason,
            "payment_method"    => optional($order->product)->payment_method,

            'info'          =>[
                ['icon' => 'user_faw',          'text_ar' => optional($order->provider)->username,       'text_en'   =>   optional($order->provider)->username],
                ['icon' => 'info_ent',          'text_ar' => string_value(425, $request) . $order->id,  'text_en'   =>   string_value(425, $request, true) . $order->id],
                ['icon' => 'calendar_faw',      'text_ar' => Change_Format($order->created_at),         'text_en'   =>   Change_Format($order->created_at)],
            ],

            'invoice'          => $order->product_id ? [
                ['text_ar' => string_value(453, $request),  'text_en'   =>   string_value(453, $request, true),     'number' =>  $order->price,],
                ['text_ar' => string_value(454, $request),  'text_en'   =>   string_value(454, $request, true),     'number' =>  $order->product->delivery_fee,],
                $order->product_payment_id && $order->product_payment->is_paid? [] :
                // ['text_ar' => string_value(472, $request),  'text_en'   =>   string_value(472, $request, true),     'number' =>  12],
                ['text_ar' => string_value(455, $request),  'text_en'   =>   string_value(455, $request, true),     'number' =>  $order->price + $order->product->delivery_fee], //  + ($order->product_payment_id && $order->product_payment->is_paid? 0 : 12),
                ] : NULL,

            'details_info'  =>[
                    ['icon' => 'user_faw',          'text_ar' => optional($order->provider)->username,                  'text_en'   =>   optional($order->provider)->username],
                    ['icon' => 'info_ent',          'text_ar' => string_value(425, $request) . $order->id,              'text_en'   =>   string_value(425, $request, true) . $order->id],
                    ['icon' => 'calendar_faw',      'text_ar' => Change_Format($order->created_at),                     'text_en'   =>   Change_Format($order->created_at)],
$order->product_id? ['icon' => 'location_ent',      'text_ar' => string_value(243, $request). ': ' .$order->address,    'text_en'   =>   string_value(243, $request, true). ': ' .$order->address]    :   [],
$order->product_id? ['icon' => 'call_mdi',          'text_ar' => string_value(5, $request). ': ' .$order->other_phone,  'text_en'   =>   string_value(5, $request, true). ': ' .$order->other_phone] :   [],
                    ['icon' => 'description_mdi',   'text_ar' => $description,                                          'text_en'   =>   $description_en],
$order->product_id? ['icon' => 'cash_mco',   'text_ar' => $payment_method,                                       'text_en'   =>   $payment_method_en] :   [],

            ],
        ];
    }
}
