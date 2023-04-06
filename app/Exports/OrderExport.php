<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
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
        return  \App\Models\User::commonFunctionMethod(Order::select('orders.id', 
            'orders.quantity', 
            'orders.gst', 
            'orders.payment_amount', 
            \Illuminate\Support\Facades\DB::raw('(CASE WHEN order_status = "'.config('constants.order.order_status_enum.pending'). '" THEN "' . config('constants.order.order_status.0').'" WHEN order_status = "'.config('constants.order.order_status_enum.inprocess'). '" THEN "' . config('constants.order.order_status.1').'" WHEN order_status = "'.config('constants.order.order_status_enum.cancel'). '" THEN "' . config('constants.order.order_status.2').'" WHEN order_status = "'.config('constants.order.order_status_enum.completed'). '" THEN "' . config('constants.order.order_status.3').'" WHEN order_status = "'.config('constants.order.order_status_enum.return'). '" THEN "' . config('constants.order.order_status.4').'" ELSE ""  END) AS order_status')),
            $this->request, true, null, null, true);
    }

    public function headings():array
    {
        return[
            'Id', 
            'Quantity', 
            'Gst', 
            'Payment_amount', 
            'Order_status'
        ];
    }
}
