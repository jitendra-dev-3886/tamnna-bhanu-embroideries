<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return  \App\Models\User::commonFunctionMethod(
            Product::select(
                'products.id',
                'products.name',
                'products.price',
                'products.description',
                'products.item_code',
                \Illuminate\Support\Facades\DB::raw('(SELECT name from categories WHERE id = products.category_id) AS category_name'),
                \Illuminate\Support\Facades\DB::raw('(CASE WHEN available_status = "' . config('constants.products.available_status_code.not-available') . '" THEN "' . config('constants.products.available_status.0') . '" WHEN available_status = "' . config('constants.products.available_status_code.available') . '" THEN "' . config('constants.products.available_status.1') . '" ELSE ""  END) AS available_status'),
                \Illuminate\Support\Facades\DB::raw('(CASE WHEN status = "' . config('constants.products.status_code.inactive') . '" THEN "' . config('constants.products.status.0') . '" WHEN status = "' . config('constants.products.status_code.active') . '" THEN "' . config('constants.products.status.1') . '" ELSE ""  END) AS status'),
                'products.stock',
                'products.featured_image'
            ),
            $this->request,
            true,
            null,
            null,
            true
        );
    }

    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'Price',
            'Description',
            'Item_code',
            'Category name',
            'Available_status',
            'Status',
            'Stock',
            'Featured_image'
        ];
    }
}
