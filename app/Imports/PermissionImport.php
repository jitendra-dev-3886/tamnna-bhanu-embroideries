<?php

namespace App\Imports;

use App\Models\Permission;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreatedbyUpdatedby;

class PermissionImport implements ToCollection, WithStartRow
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
            'name'=>'required|max:191|unique:permissions,name,NULL,id,deleted_at,NULL', 
            'guard_name'=>'required|max:191', 
            'label'=>'required|max:191'
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required'=>trans('The name is required.'), 
            '0.max'=>trans('The name may not be greater than 191 characters.'), 
            '0.unique'=>trans('The name has already been taken.'), 
            '1.required'=>trans('The guard_name is required.'), 
            '1.max'=>trans('The guard_name may not be greater than 191 characters.'), 
            '2.required'=>trans('The label is required.'), 
            '2.max'=>trans('The label may not be greater than 191 characters.')
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
                $permission = Permission::create([
                   'name'=>$col[0], 
            'guard_name'=>$col[1], 
            'label'=>$col[2], 
            'created_by'=>$col[3], 
            'updated_by'=>$col[4], 
            'deleted_by'=>$col[5]
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

