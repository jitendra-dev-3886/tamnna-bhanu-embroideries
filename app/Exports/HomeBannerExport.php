<?php

namespace App\Exports;


use App\Models\HomeBanner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HomeBannerExport implements FromCollection, WithHeadings
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
        return  \App\Models\User::commonFunctionMethod(HomeBanner::select('homebanners.id',
            'homebanners.name',
            'homebanners.featured_image'),
            $this->request, true, null, null, true);
    }


    public function headings():array
    {
        return[
            'Id',
            'Name',
            'Featured_image'
        ];
    }
}
