<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $is_provider = optional($this->provider)->role == null ? true : false;
        $is_observor = auth()->user()->role == 'chat_review';
        $name = $is_provider ? $this->user->username : optional($this->provider)->username;

        // if($this->maintenance_request_order_id)
        // return (new OrderMaintenanceRequestResource($this->maintenance_request_order))->info($this->id, $name, $this->created_at);

        $payment_method    = ($this->product_payment_id && $this->product_payment->is_paid) || optional($this->payment)->paid == 1
                                ? 'تم الدفع عبر بطاقة بنكية'
                                : 'الدفع عند الاستلام';
        $payment_method_en = ($this->product_payment_id && $this->product_payment->is_paid) || optional($this->payment)->paid == 1
                                ? 'Payment was made by bank card'
                                : 'cash on delivery';

        $sum_before_discount = $this->carts->where('is_exists', true)->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return [
            "id"                => $this->id,
            "user_id"           => $this->user_id,
            "provider_id"       => $this->provider_id,
            "offer_id"          => $this->offer_id,
            "service_id"        => $this->provider_service->id,
            "status"            => $this->status,
            "created_at"        => Change_Format($this->created_at),
            "updated_at"        => Change_Format($this->updated_at),
            'name'              => $name,
            'number_phone'      => $is_provider ? optional($this->user->country)->country_code . $this->user->number_phone : optional($this->provider->country)->country_code . $this->provider->number_phone,
            "who_canceled"      => $this->canceled_by == $this->user_id ? 'user' : ($this->canceled_by == $this->provider_id ? 'provider' : ($this->canceled_by != NULL ?  'admin' : NULL)),
            "canceled_reason"   => $this->canceled_reason,
            "is_carts" => $this->carts && count($this->carts)>0,
            $this->mergeWhen($is_observor, function () use ($name) {
                $order_item =  [
                    'test' => 'ddd',
                    'provider_name'     => $this->provider->username . ($this->provider->deleted_at? ' (حساب محذوف)' : ''),
                    'deleted_at'        => $this->provider->deleted_at,
                    'user_name'         => optional($this->user)->username.  ($this->user->deleted_at? ' (حساب محذوف)' : ''),
                    'phone_provider'    => optional($this->provider->country)->country_code . $this->provider->number_phone,
                    'phone_user'        => optional(optional($this->user)->country)->country_code . optional($this->user)->number_phone,
                    "log"               => $this->log_providers,
                ];
                if ($this->status == 'CANCELED' || $this->status == 'ONE_SIDED_CANCELED') {
                    $order_item += ['who_canceled'      => $this->canceled_by == $this->user_id ? 'user' : ($this->canceled_by == $this->provider_id ? 'provider' : ($this->canceled_by != NULL ?  'admin' : NULL))];
                    $order_item += ['canceled_reason'   => $this->canceled_reason];
                }
                return $order_item;

            }),
            'info'          => [
                $is_observor? [] : ['icon' => 'user_faw',          'text_ar' => $name,                                     'text_en'   =>   $name],
                ['icon' => 'info_ent',          'text_ar' => string_value(425, $request) . $this->id,   'text_en'   =>   string_value(425, $request, true) . $this->id],
                ['icon' => 'calendar_faw',      'text_ar' => Change_Format($this->created_at),          'text_en'   =>   Change_Format($this->created_at)],
            ],

            $this->mergeWhen($this->product_id, function () use ($name, $payment_method, $payment_method_en) {
                return (new OrderProductResource($this->product))
                    ->info($this->id, $name, $this->created_at, $payment_method, $payment_method_en, $this->address, $this->other_phone, $this->order_fees);
            }),

            $this->mergeWhen($this->maintenance_request_order_id, function () use ($name, $is_observor) {
                return (new OrderMaintenanceRequestResource($this->maintenance_request_order))
                    ->info($this->id, $name, $this->created_at, $this->order_fees, $this->order_payment_option, $this, $is_observor);
            }),
            // 'coupon' => $this->coupon,
            $this->mergeWhen(count($this->carts)>0, function () use ($request, $sum_before_discount) {
                $components = $this->carts->map(function($item) {
                    return [
                            'text_ar' => $item->product->name . ($item->is_exists? '' : ' (غير موجود)'),
                            'text_en' => $item->product->name_en . ($item->is_exists? '' : ' (not exists)'),
                            'number'  => $item->is_exists
                                          ? $this->number_format_rtrim($item->price) . ' X ' . $item->quantity
                                          : '0',
                        ];
                })->toArray();
                if($this->coupon){
                    $components[] = [
                        "text_ar" => "قيمة الكوبون",
                        "text_en" => "Total",
                        'number' => $this->number_format_rtrim($this->coupon->value) . ($this->coupon->type == 'discount'? '' : '%')
                    ];
                    $components[] = [
                        "text_ar" => "المجموع قبل الخصم",
                        "text_en" => "Total before discount",
                        'number' => $this->number_format_rtrim($sum_before_discount)
                    ];
                    $components[] = [
                        "text_ar" => "المجموع بعد الخصم",
                        "text_en" => "Total after discount",
                        'number' => $this->number_format_rtrim(($sum_before_discount - ($this->coupon->type == 'discount'? $this->coupon->value : ($this->coupon->value/100 * $sum_before_discount))))
                    ];
                } else {
                    $components[] = [
                        'text_ar' => string_value(455, $request),
                        'text_en' => string_value(455, $request, true),
                        // 'number'  => $this->price,
                        'number'  => $sum_before_discount//$this->price //+ $this->delivery_fee + $fees->sum('number')
                    ];
                }
                return [
                    "service_name"   =>  'طلب شراء سلة '. ' #' . $this->id,
                    "service_name_en"=>  'order pay cart' . ' #' . $this->id,
                    'invoice'     => [
                        ...$components,
                        // [
                        //     'text_ar' => string_value(454, $request),
                        //     'text_en' => string_value(454, $request, true),
                        //     'number'  => $this->delivery_fee,
                        // ],
                        // $this->product_payment_id && $this->product_payment->is_paid? [] :
                    ],
                ];
            }),


            "service_icon"      => count($this->carts)>0? url("/images/carts.png") : (($this->provider_service->thumbnail) ?? default_image()),
            "description"       => string_value(257, $request) . ': ' . optional($this->offer)->description,
            "description_en"    => string_value(257, $request, true) . ': ' . optional($this->offer)->description,
            'invoice'           => NULL,
            "payment_method"    => Null,
            "service_name"      => ($this->provider_service->title === Null ? get_title_improve(6, $this->provider_service)->name : $this->provider_service->title)  . ' #' . $this->id,
            "service_name_en"   => ($this->provider_service->title === Null ? get_title_improve(6, $this->provider_service)->name : $this->provider_service->title)  . ' #' . $this->id,

            "price"             => $this->product_id
                                    ? ($this->price + $this->order_fees->sum('fee_value') + $this->product->delivery_fee)
                                    : ($this->carts && count($this->carts)>0
                                        ? ($this->coupon
                                            ?$this->number_format_rtrim($sum_before_discount - ($this->coupon->type == 'discount'? $this->coupon->value : $this->coupon->value/100 * $sum_before_discount))
                                            :$this->carts->where('is_exists', true)->sum(function($item) {
                                            return $item->price * $item->quantity;
                                          }))
                                        : $this->price), // + ($this->product_payment_id && $this->product_payment->is_paid? 0 : 12)
            "service_target"    => $this->product_id ? null : optional($this->provider_service->service_full)->target,

            'details_info'  => [
                $is_observor? [] :[
                    'icon'      => 'user_faw',
                    'text_ar'   => $name,
                    'text_en'   => $name
                ],
                [
                    'icon'      => 'info_ent',
                    'text_ar'   => string_value(425, $request) . $this->id,
                    'text_en'   => string_value(425, $request, true) . $this->id
                ],
                [
                    'icon'      => 'calendar_faw',
                    'text_ar'   => Change_Format($this->created_at),
                    'text_en'   => Change_Format($this->created_at)
                ],
                count($this->carts)>0? [] : [
                    'icon'      => 'description_mdi',
                    'text_ar'   => string_value(257, $request) . ': ' . optional($this->offer)->description,
                    'text_en'   => string_value(257, $request, true) . ': ' . optional($this->offer)->description
                ],
                count($this->carts)>0? [
                    'icon'      => 'description_mdi',
                    'text_ar'   => 'طريقة الدفع'. ': ' . $payment_method,
                    'text_en'   => 'payment method'. ': ' . $payment_method_en,
                ] :[],
                count($this->carts)>0? [
                    'icon'      => 'description_mdi',
                    'text_ar'   => 'العنوان'. ': ' . $this->address,
                    'text_en'   => 'Address'. ': ' . $this->address,
                ] :[],
                count($this->carts)>0? [
                    'icon'      => 'description_mdi',
                    'text_ar'   => 'رقم هاتف أخر للتواصل'. ': ' . $this->other_phone,
                    'text_en'   => 'Another phone number to contact'. ': ' . $this->other_phone,
                ] :[],

            ],


        ];
    }
    function number_format_rtrim($number) {
        return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }
}


