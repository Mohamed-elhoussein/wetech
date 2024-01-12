<?php

namespace App\Exports;

use App\Models\Countries;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CountriesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Countries::orderBy('status')->get();
    }

    public function map($country): array
    {
        return [
            $country->name,
            $country->unit,
            $country->unit_en,
            $country->code,
            $country->country_code,
            $country->countryStatus,
            optional($country->created_at)->format('M d, Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'الإسم',
            'العملة',
            'العملة بالإنجليزية',
            'الاسم الدولي',
            'الرقم الدولي',
            'الحالة',
            'وقت الانشاء',
        ];
    }
}
