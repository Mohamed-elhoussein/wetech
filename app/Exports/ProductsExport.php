<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $products  = Product::get();

        $products->map(function($product){
            $product->images= collect(explode('||', $product->images))->values()->filter()->toArray();
            return $product;
        });

        return $products;
    }

    public function map($product): array
    {
        return [
            $product->images ? url($product->images[0]) : default_image(),
            $product->name,
            optional($product->user)->username ?? '',
            $product->description ?: 'لا يوجد',
            $product->status == "NEW" ? 'جديد': "مستعمل",
            optional($product->created_at)->format('M d, Y')
        ];
    }

    public function headings(): array
    {
        return [
            'رابط الصورة',
            'المنتوج',
            'الزبون',
            'الوصف',
            'الحالة',
            'وقت الانشاء',
        ];
    }
}
