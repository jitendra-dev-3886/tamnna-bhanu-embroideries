<?php

namespace App\Imports;

use App\Models\Product;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreatedbyUpdatedby;

class ProductImport implements ToCollection, WithStartRow
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
            '0'=>'required|max:191', 
            '1'=>'required', 
            '2'=>'required', 
            '3'=>'required|max:191', 
            '4'=>'required|exists:categories,id,deleted_at,NULL', 
            '5'=>'required|in:0,1', 
            '6'=>'nullable', 
            '7'=>'required|max:500'
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required'=>trans('The name is required.'), 
            '0.max'=>trans('The name may not be greater than 191 characters.'), 
            '1.required'=>trans('The price is required.'), 
            '2.required'=>trans('The description is required.'), 
            '3.required'=>trans('The item_code is required.'), 
            '3.max'=>trans('The item_code may not be greater than 191 characters.'), 
            '4.required'=>trans('The category_id is required.'), 
            '4.exists'=>trans('The selected category_id is invalid.'), 
            '5.required'=>trans('The available_status is required.'), 
            '5.in'=>trans('The available_status is invalid.'), 
            '7.required'=>trans('The featured_image is required.'), 
            '7.max'=>trans('The featured_image may not be greater than 500 characters.')
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
                $product = Product::create([
                   'name'=>$col[0], 
            'price'=>$col[1], 
            'description'=>$col[2], 
            'item_code'=>$col[3], 
            'category_id'=>$col[4], 
            'available_status'=>$col[5], 
            'stock'=>$col[6], 
            'featured_image'=>$col[7]
                ]);
                
                
                
                $multipleFile = explode('|', $col[8]);
                if($multipleFile) {
                    foreach ($multipleFile as $file) {
                        $realPath = 'product/' . $product->id . '/' .'product_galleries/' . $file;
                        \App\Models\ProductGallery::create([
                            'product_id' => $product->id,
                            'gallery' => $realPath,
                            'gallery_original' => $file,
                            'gallery_thumbnail' => $realPath
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

