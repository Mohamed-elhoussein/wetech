<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::with('user:id,username', 'order:id,provider_service_id', 'order.provider_service:id,title')->get();
    }

    public function map($transaction): array
    {
        return [
            $transaction->user->username,
            optional(optional($transaction->order)->provider_service)->title ?? 'غير محدد',
            $transaction->type,
            $transaction->amount . ' ريال  ',
            optional($transaction->created_at)->format('M d, Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'الزبون',
            'الخدمة',
            'النوع',
            'المبلغ',
            'وقت التحويل',
        ];
    }
}
