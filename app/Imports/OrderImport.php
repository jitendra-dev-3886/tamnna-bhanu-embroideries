<?php

namespace App\Imports;

use App\Models\Order;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreatedbyUpdatedby;

class OrderImport implements ToCollection, WithStartRow
{
    use Scopes,CreatedbyUpdatedby;
    private $errors = [];
    private $rows = 0;

    public function startRow(): int
    {
        return 2;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function rules(): array
    {
        return [
            '0'=>'nullable', 
            '1'=>'nullable', 
            '2'=>'nullable', 
            '3'=>'required|in:0,1,2,3,4'
        ];
    }

    public function validationMessages()
    {
        return [
            '3.required'=>trans('The order_status is required.'), 
            '3.in'=>trans('The order_status is invalid.')
        ];
    }

    public function validateBulk($collection){
        $i=1;
        foreach ($collection as $col) {
            $i++;
            $errors[$i] = ['row' => $i];

            $validator = Validator::make($col->toArray(), $this->rules(), $this->validationMessages());
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                         $errors[$i]['error'][] = $error;
                    }
                }

                $this->errors[] = (object) $errors[$i];
            }

        }
        return $this->getErrors();
    }

    public function collection(Collection $collection)
    {
        $error = $this->validateBulk($collection);
        if($error){
            return;
        }else {
            foreach ($collection as $col) {
                $order = Order::create([
                   'quantity'=>$col[0], 
            'gst'=>$col[1], 
            'payment_amount'=>$col[2], 
            'order_status'=>$col[3]
                ]);
                
                
                
                $multipleFile = explode('|', $col[4]);
                if($multipleFile) {
                    foreach ($multipleFile as $file) {
                        $realPath = 'order/' . $order->id . '/' .'order_products/' . $file;
                        \App\Models\OrderProduct::create([
                            'order_id' => $order->id,
                            'product_id' => $realPath,
                            'product_id_original' => $file,
                            'product_id_thumbnail' => $realPath
                        ]);
                    }
                }
                $this->rows++;
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}

