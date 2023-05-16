<?php

namespace App\Imports;

use App\Models\Category;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreatedbyUpdatedby;

class CategoryImport implements ToCollection, WithStartRow
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
            '2'=>'required|max:500'
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required'=>trans('The name is required.'),
            '0.max'=>trans('The name may not be greater than 191 characters.'),
            '1.required'=>trans('The description is required.'),
            '2.required'=>trans('The featured_image is required.'),
            '2.max'=>trans('The featured_image may not be greater than 500 characters.')
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
                $category = Category::create([
                   'name'=>$col[0],
                   'description'=>$col[1],
                   'featured_image'=>$col[2]
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

