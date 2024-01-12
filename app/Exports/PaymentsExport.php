<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Payment::with('user')->get();
    }

    public function map($payment): array
    {
        return [
            $payment->user->username,
            $payment->amount,
            $payment->currency,
            $payment->method,
            $payment->payment_id ?: $payment->transaction_id,
            $payment->paimentStatus,
            optional($payment->created_at)->format('M d, Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'المستخدم',
            'المبلغ',
            'العملة',
            'وسيلة الدفع',
            'رمز العملية',
            'نجحت العملية',
            'التاريخ'
        ];
    }
}
