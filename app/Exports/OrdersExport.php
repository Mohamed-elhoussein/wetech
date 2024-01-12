<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $orders = Order::with('provider:id,username,country_id', 'user:id,username', 'provider_service:id,title', 'provider.country')
            ->latest()->get();

        $orders->transform(function ($item) {
            $item->provider_service->title = ($item->provider_service->title === Null ? optional(get_title(6, $item->provider_service))->name : $item->provider_service->title);
            return $item;
        });

        return $orders;
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->user->username,
            $order->provider->username,
            $order->provider_service->title,
            __($order->status),
            $order->provider->country->unit ? $order->price . '  ' . $order->provider->country->unit : $order->price . '  ر.س ',
            optional($order->created_at)->format('M d, Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'رقم الطلب',
            'المستفيد من الخدمة',
            'مقدم الخدمة',
            'الخدمة',
            'حالة الطلب',
            'الثمن',
            'في',
        ];
    }
}
