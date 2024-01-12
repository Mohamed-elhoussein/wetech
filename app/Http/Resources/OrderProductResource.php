<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    protected $id;
    protected $username;
    protected $created_at;
    protected $payment_method;
    protected $payment_method_en;
    protected $address;
    protected $other_phone;
    protected $order_fees;

    public function info($id, $username, $created_at, $payment_method, $payment_method_en, $address, $other_phone, $order_fees = null){
        $this->id = $id;
        $this->username = $username;
        $this->created_at = $created_at;
        $this->payment_method = $payment_method;
        $this->payment_method_en = $payment_method_en;
        $this->address = $address;
        $this->other_phone = $other_phone;
        $this->order_fees = $order_fees;
        return $this;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fees = $this->order_fees->map(function ($item) {
            return [
                'text_ar' => $item->fee_name,
                'text_en' => $item->fee_name,
                'number'  => $item->fee_value,
            ];
        });

        return [
            "service_name"   =>  $this->name . ' #' . $this->id,
            "service_name_en"=>  $this->name_en . ' #' . $this->id,
            "service_icon"   => (!is_array($this->images) ? json_decode($this->images)[0] : $this->images) ?? default_image(),
            "description"    => string_value(451, $request).       ': ' . $this->description,
            "description_en" => string_value(451, $request, true). ': ' . $this->description,
            "payment_method" =>$this->payment_method,
            'invoice'     => [
                    [
                        'text_ar' => string_value(453, $request),
                        'text_en' => string_value(453, $request, true),
                        'number'  => $this->price,
                    ],
                    ...$fees->toArray(),
                    [
                        'text_ar' => string_value(454, $request),
                        'text_en' => string_value(454, $request, true),
                        'number'  => $this->delivery_fee,
                    ],
                    $this->product_payment_id && $this->product_payment->is_paid? [] :
                    [
                        'text_ar' => string_value(455, $request),
                        'text_en' => string_value(455, $request, true),
                        'number'  => $this->price + $this->delivery_fee + $fees->sum('number')
                    ],
                ],
            'details_info'  =>[
                    [
                        'icon'      => 'user_faw',
                        'text_ar'   => $this->username,
                        'text_en'   => $this->username
                    ],
                    [
                        'icon'      => 'info_ent',
                        'text_ar'   => string_value(425, $request) . $this->id,
                        'text_en'   => string_value(425, $request, true) . $this->id],
                    [
                        'icon'      => 'calendar_faw',
                        'text_ar'   => Change_Format($this->created_at),
                        'text_en'   => Change_Format($this->created_at)],
                    [
                        'icon'      => 'location_ent',
                        'text_ar'   => string_value(243, $request). ': ' .$this->address,
                        'text_en'   => string_value(243, $request, true). ': ' .$this->address
                    ],
                    [
                        'icon'      => 'call_mdi',
                        'text_ar'   => string_value(5, $request). ': ' .$this->other_phone,
                        'text_en'   => string_value(5, $request, true). ': ' .$this->other_phone
                    ],
                    [
                        'icon'      => 'description_mdi',
                        'text_ar'   => string_value(451, $request). ': ' . $this->description ,
                        'text_en'   => string_value(451, $request, true). ': ' . $this->description
                    ],
                    [
                        'icon'      => 'cash_mco',
                        'text_ar'   => $this->payment_method,
                        'text_en'   => $this->payment_method_en
                    ],

            ],
        ];
        // return parent::toArray($request);
    }
}
