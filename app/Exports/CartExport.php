<?php

namespace App\Exports;

use App\Models\Cart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CartExport implements FromCollection, WithHeadings
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
        return  \App\Models\User::commonFunctionMethod(Cart::select('carts.id', 
            \Illuminate\Support\Facades\DB::raw('(SELECT name from users WHERE id = carts.user_id) AS user_name'), 
            \Illuminate\Support\Facades\DB::raw('(SELECT name from products WHERE id = carts.product_id) AS product_name'), 
            'carts.quantity'),
            $this->request, true, null, null, true);
    }

    public function headings():array
    {
        return[
            'Id', 
            'User name', 
            'Product name', 
            'Quantity'
        ];
    }
}
