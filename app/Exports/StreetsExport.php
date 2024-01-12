<?php

namespace App\Exports;

use App\Models\Street;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StreetsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return  Street::with('cities:id,name,country_id', 'cities.country:id,name')
            ->get();
    }

    public function map($street): array
    {
        return [
            $street->name,
            optional($street->cities)->name,
            optional($street->cities->country)->name,
            optional($street->created_at)->format('M d, Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'الإسم',
            'المدينة',
            'الدولة',
            'وقت الانشاء'
        ];
    }
}
