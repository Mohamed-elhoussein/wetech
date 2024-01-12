<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('role', 'user')
        ->with('country:id,name,country_code')
        ->get();
    }

    public function map($user): array
    {
        return [
            $user->username,
            $user->number_phone,
            optional($user->country)->name,
            $user->email,
            $user->x_os ?? 'لم يسجل بعد' ,
            optional($user->created_at)->format('M d, Y'),
            $user->user_status,
        ];
    }

    public function headings(): array
    {
        return [
            'الإسم',
            'رقم الهاتف',
            'البلد',
            'البريد الإلكتروني',
            'نوع الهاتف',
            'وقت الإنشاء',
            'الحالة',
        ];
    }
}
