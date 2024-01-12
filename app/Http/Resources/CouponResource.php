<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "id" => $this->id,
            "name" =>$this->name,
            "value" => $this->is_valid? $this->value : 0,
            "type" => $this->type,
            "expired_at" => $this->expired_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "code" => $this->code,
            "belong_to" => $this->belong_to,
            "is_valid" => $this->is_valid,
            'message' => $this->message,
            "total" => $this->total_prices,
            "invoice_info" => [],
        ];

        if($this->is_valid)
        {
            $data['invoice_info'][] = [
                "text_ar" => "قيمة الكوبون",
                "text_en" => "Total",
                'number' => $this->number_format_rtrim($this->value) . ($this->type == 'discount'? ' ر.س' : '%')
            ];
            $data['invoice_info'][] = [
                "text_ar" => "المجموع قبل الخصم",
                "text_en" => "Total before discount",
                'number' => $this->number_format_rtrim($this->total_prices) . ' ر.س'
            ];
            $data['invoice_info'][] = [
                "text_ar" => "المجموع بعد الخصم",
                "text_en" => "Total after discount",
                'number' => $this->number_format_rtrim($this->total_prices - ($this->type == 'discount'? $this->value : $this->value/100 * $this->total_prices)) . ' ر.س'
            ];
        }
        else
        {
            $data['invoice_info'][] = [
                "text_ar" => "المجموع",
                "text_en" => "Total",
                'number' => $this->number_format_rtrim($this->total_prices) . ' ر.س'
            ];
        }

        return $data;
    }

    function number_format_rtrim($number) {
        return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }
}
