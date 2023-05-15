<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryExport implements FromCollection, WithHeadings
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
        return  \App\Models\User::commonFunctionMethod(Category::select('categories.id',
            'categories.name',
            'categories.description',
            'categories.featured_image'),
            $this->request, true, null, null, true);
    }

    public function headings():array
    {
        return[
            'Id',
            'Name',
            'Remarks',
            'Featured Image'
        ];
    }
}
