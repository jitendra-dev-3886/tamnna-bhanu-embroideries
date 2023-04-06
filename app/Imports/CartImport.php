<?php

namespace App\Imports;

use App\Models\Cart;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreatedbyUpdatedby;

class CartImport implements ToCollection, WithStartRow
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
            '0'=>'required|exists:users,id,deleted_at,NULL', 
            '1'=>'required|exists:products,id,deleted_at,NULL', 
            '2'=>'required'
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required'=>trans('The user_id is required.'), 
            '0.exists'=>trans('The selected user_id is invalid.'), 
            '1.required'=>trans('The product_id is required.'), 
            '1.exists'=>trans('The selected product_id is invalid.'), 
            '2.required'=>trans('The quantity is required.')
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
                $cart = Cart::create([
                   'user_id'=>$col[0], 
            'product_id'=>$col[1], 
            'quantity'=>$col[2]
                ]);
                
                
                
                $this->rows++;
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}

