<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }

    public function map($user): array
    {
        return [
            $user->username,
            $user->email,
            $user->created_at ? $user->created_at->format('M d, Y') : '',
            $user->user_status,
        ];
    }

    public function headings(): array
    {
        return [
            'إسم المستخدم',
            'البريد الإلكتروني',
            'وقت الإنشاء',
            'الحالة',
        ];
    }
}
