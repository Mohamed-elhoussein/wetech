<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChatReviewsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('role', 'chat_review')
        ->with('country:id,name,country_code')
        ->get();
    }

    public function map($chat): array
    {
        return [
            $chat->username,
            $chat->first_name,
            $chat->second_name,
            $chat->last_name,
            $chat->number_phone,
            optional($chat->country)->name,
            optional($chat->created_at)->format('M d, Y'),
            $chat->user_status,
        ];
    }

    public function headings(): array
    {
        return [
            'الإسم',
            'الإسم الأول',
            'الإسم الثاني',
            'الإسم الأخير',
            'رقم الهاتف',
            'البلد',
            'وقت الإنشاء',
            'الحالة',
        ];
    }
}
