<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProvidersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::latest('last_name', 'desc')
        ->with('country:id,name,country_code', 'commission')
        ->get();
    }

    public function map($provider): array
    {
        return [
            $provider->username,
            $provider->first_name,
            $provider->second_name,
            $provider->last_name,
            optional($provider->country)->name,
            optional($provider->created_at)->format('M d, Y'),
            $provider->user_status,
            optional($provider->commission)->commission,
            $provider->balance,
            $provider->verified ? 'تم التحقق' : 'ليس بعد',
        ];
    }

    public function headings(): array
    {
        return [
            'الإسم',
            'الإسم الأول',
            'الإسم الثاني',
            'الإسم الأخير',
            'البلد',
            'وقت الإنشاء',
            'الحالة',
            'العمولة',
            'الحساب',
            'التحقق',
        ];
    }
}
