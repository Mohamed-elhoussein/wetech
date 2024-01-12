<?php

namespace App\Exports;

use App\Models\Reports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Reports::with('user:id,username')->latest()->get();
    }

    public function map($report): array
    {
        return [
            $report->user->username,
            $report->title,
            $report->content,
            $report->solvled ? 'ثم حله'  : 'لم يحل بعد',
        ];
    }

    public function headings(): array
    {
        return [
            'إسم المستخدم',
            'العنوان',
            'الاشكالية',
            'الحالة',
        ];
    }
}
