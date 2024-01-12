<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderMaintenanceRequestResource extends JsonResource
{
    protected $id;
    protected $name;
    protected $created_at;
    protected $order_fees;
    protected $order_payment_option;
    protected $base_order;
    protected $is_observor;

    public function info($id, $name, $created_at, $order_fees, $order_payment_option, $base_order = null, $is_observor = false){
        $this->id = $id;
        $this->name = $name;
        $this->created_at = $created_at;
        $this->order_fees = $order_fees;
        $this->order_payment_option = $order_payment_option;
        $this->base_order = $base_order;
        $this->is_observor = $is_observor;
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
                'text_en' => $item->fee_name_en,
                'number'  => $item->fee_value,
            ];
        });

        $order_payment_option = $this->order_payment_option;
        $payment_option_data = [];

        if ($order_payment_option && $order_payment_option->type !== 'default') {
            $payment_option_data = [
                'text_ar' => optional($order_payment_option->payment_option)->label,
                'text_en' => optional($order_payment_option->payment_option)->label_en,
                'number' => $order_payment_option->value,
            ];
        }

        $payment_method    = $this->payment_method != 'cash'? 'تم الدفع عبر بطاقة بنكية': 'الدفع عند الاستلام';
        $payment_method_en = $this->payment_method != 'cash'? 'Payment was made by bank card' : 'cash on delivery';

        $base_price = optional($this->maintenance_type)->price;
        $price = $base_price + $fees->sum('number');

        $rest = 0;

        if ($order_payment_option && $order_payment_option->type == 'sub') {
            $rest = $price - $order_payment_option->value;
            // $price = $order_payment_option->value;
        }
        else if ($order_payment_option && $order_payment_option->type == 'plus') {
            $price = $price + $order_payment_option->value;
        }

        // في حال كان الطلب جاري أو ملغى وكان السعر 0
        $base_order_status = strtoupper($this->base_order->status);

        $__price = 0;

        if($base_price == 0 && $base_order_status === 'COMPLETED' && $this->maintenance_request_order_coupon){
            $invoice = [
                [
                    'text_ar' => string_value(486, request()),// طلب الصيانة"
                    'text_en' => string_value(486, request(), true),
                    'number'  => $this->base_order->price,
                ],
                ...$fees->toArray(),
                [
                    'text_ar' => 'تم إستخدام كوبون كود: ' . $this->maintenance_request_order_coupon->coupon->code,
                    'text_en' => 'تم إستخدام كوبون كود: ' . $this->maintenance_request_order_coupon->coupon->code,
                    'number' => $this->maintenance_request_order_coupon->coupon->value,
                ],
                [
                    'text_ar' => string_value(455, $request), // المجموع
                    'text_en' => string_value(455, $request, true),
                    'number'  => $this->base_order->price - $this->maintenance_request_order_coupon->coupon->value
                ]
            ];
            $__price = $this->base_order->price - $this->maintenance_request_order_coupon->coupon->value;
        } else if ($base_price == 0) { // && ($base_order_status === 'CANCELED' || $base_order_status === 'PENDING')
            $invoice = [];
        }
        else {
            $invoice = [
                [
                    'text_ar' => string_value(486, request()), // طلب الصيانة"
                    'text_en' => string_value(486, request(), true),
                    'number'  => $base_price,
                ],
                ...$fees->toArray(),
            ];

            $__price = 0;

            if ($this->payment_method == 'free') {
                $__price = 0;
            }
            // هذا النوع يعني ان العميل قام بدفع هذه القيمة
            else if ($order_payment_option && $order_payment_option->type == 'sub') {
                $__price = $base_price;
                // $__price = $order_payment_option->value;
            }
            // نضيف قيمة النوع لمجموع الطلب
            else if ($order_payment_option && $order_payment_option->type == 'plus') {
                $__price = $base_price + $fees->sum('number') + $order_payment_option->value;
            }
            else {
                $__price = $base_price + $fees->sum('number');
            }

            if ($this->maintenance_request_order_coupon) {
                $invoice[] = [
                    'text_ar' => 'تم إستخدام كوبون كود: ' . $this->maintenance_request_order_coupon->coupon->code,
                    'text_en' => 'تم إستخدام كوبون كود: ' . $this->maintenance_request_order_coupon->coupon->code,
                    'number' => $this->maintenance_request_order_coupon->coupon->value,
                ];

                $__price = $__price - $this->maintenance_request_order_coupon->coupon->value;

                $rest = $rest - $this->maintenance_request_order_coupon->coupon->value;
                // $text = "تم دفع " . ($this->payment_method != 'cash'? 'عبر بطاقة بنكية': 'عند الاستلام') . "";

                // $invoice[] = [
                //     'text_ar' => $text,
                //     'text_en' => $text,
                //     'number' => $this->maintenance_request_order_coupon->coupon->value,
                // ];

                // $price = $price - $this->maintenance_request_order_coupon->coupon->value;
            }

            if (count($payment_option_data) > 0) {
                $invoice[] = $payment_option_data;
            }

            // المجموع
            $invoice[] = [
                'text_ar' => string_value(455, $request),
                'text_en' => string_value(455, $request, true),
                'number'  => $__price
            ];

            // المتبقي
            if ($rest > 0) {
                $invoice[] = [
                    'text_ar' => string_value(534, $request),
                    'text_en' => string_value(534, $request, true),
                    'number'  => $rest
                ];
            }
        }

        $response_data = [
            "service_name"    => (optional(optional(optional($this->maintenance_type)->maintenance_request)->service)->name) . ' #' . $this->id,
            "service_name_en" => (optional(optional(optional($this->maintenance_type)->maintenance_request)->service)->name_en) . ' #' . $this->id,
            "description"     => string_value(257, $request).       ': ' . (optional(optional(optional($this->maintenance_type)->maintenance_request)->issue)->name),
            "description_en"  => string_value(257, $request, true). ': ' .(optional(optional(optional($this->maintenance_type)->maintenance_request)->issue)->name),
            "price"           => $__price,

            'invoice'     => $invoice,

            'details_info'  =>[
                $this->is_observor? [] : [
                    'icon'      => 'user_faw',
                    'text_ar'   => $this->name,
                    'text_en'   => $this->name
                ],
                [
                    'icon'      => 'info_ent',
                    'text_ar'   => string_value(425, $request) . $this->id,
                    'text_en'   => string_value(425, $request, true) . $this->id
                ],
                [
                    'icon'      => 'info_ent',
                    'text_ar'   => 'الماركة: ' . (optional(optional(optional($this->maintenance_type)->maintenance_request)->brand)->name),
                    'text_en'   => 'brand: '. (optional(optional(optional($this->maintenance_type)->maintenance_request)->brand)->name)
                ],
                [
                    'icon'      => 'info_ent',
                    'text_ar'   => 'الموديل: ' . (optional(optional(optional($this->maintenance_type)->maintenance_request)->model)->name),
                    'text_en'   => 'model: '. (optional(optional(optional($this->maintenance_type)->maintenance_request)->model)->name)
                ],
                [
                    'icon'      => 'info_ent',
                    'text_ar'   => 'اللون: ' . optional($this->color)->name,
                    'text_en'   => 'color: '. optional($this->color)->name
                ],
                [
                    'icon'      => 'info_ent',
                    'text_ar'   => 'المكان: '
                    . (optional(optional(optional($this->city)->city)->country)->name)
                    .', '.(optional(optional($this->city)->city)->name)
                    .', '.(optional(optional($this->street)->street)->name),
                    'text_en'   => 'Place: '. (optional(optional(optional($this->city)->city)->country)->name_en)
                    .', '.(optional(optional($this->city)->city)->name_en)
                    .', '.(optional(optional($this->street)->street)->name_en),
                ],
                [
                    'icon'      => 'calendar_faw',
                    'text_ar'   => Change_Format($this->created_at),
                    'text_en'   => Change_Format($this->created_at)
                ],
                [
                    'icon'      => 'description_mdi',
                    'text_ar'   => string_value(257, $request) . ': ' . (optional(optional(optional($this->maintenance_type)->maintenance_request)->issue)->name),
                    'text_en'   => string_value(257, $request, true). ': ' .(optional(optional(optional($this->maintenance_type)->maintenance_request)->issue)->name)
                ],
                [
                    'icon'      => 'calendar_faw',
                    'text_ar'   => 'ملاحظة: ' . $this->note,
                    'text_en'   => 'note: ' . $this->note
                ],
                [
                    'icon'      => 'cash_mco',
                    'text_ar'   => $this->payment_option? ($this->payment_option->label . ' ' . $this->payment_option->sub_text) : $payment_method,
                    'text_en'   => $this->payment_option? ($this->payment_option->label_en . ' ' . $this->payment_option->sub_text) : $payment_method_en
                ],
                $this->is_observor && $this->maintenance_request_order_coupon? [
                    'icon'      => 'calculator_variant_mco',
                    'text_ar' => 'تم إستخدام كوبون كود: ' . $this->maintenance_request_order_coupon->coupon->code,
                    'text_en' => 'تم إستخدام كوبون كود: ' . $this->maintenance_request_order_coupon->coupon->code,
                    'number' => $this->maintenance_request_order_coupon->coupon->value,
                ] : [],
                $this->maintenance_request_order_coupon && $base_price == 0 && $base_order_status !== 'COMPLETED'? [
                    'text_ar' => 'تم إستخدام كوبون كود: ' . $this->maintenance_request_order_coupon->coupon->code,
                    'text_en' => 'تم إستخدام كوبون كود: ' . $this->maintenance_request_order_coupon->coupon->code,
                    'number' => $this->maintenance_request_order_coupon->coupon->value,
                ] : []

            ],
        ];

        if (count($response_data['invoice']) == 0 || $this->payment_method == 'free') {
            unset($response_data['invoice']);
        }

        return array_filter($response_data);
    }
}
