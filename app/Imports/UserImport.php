<?php

namespace App\Imports;

use App\Models\User;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreatedbyUpdatedby;

class UserImport implements ToCollection, WithStartRow
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
            'email'=>'required|max:191|email', 
            'password'=>'required|min:6|max:191', 
            'role_id'=>'required|exists:roles,id,deleted_at,NULL'
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required'=>trans('The email is required.'), 
            '0.max'=>trans('The email may not be greater than 191 characters.'), 
            '0.email'=>trans('The email is invalid.'), 
            '1.required'=>trans('The password is required.'), 
            '1.min'=>trans('The password must be at least 6 characters.'), 
            '1.max'=>trans('The password may not be greater than 191 characters.'), 
            '2.required'=>trans('The role_id is required.'), 
            '2.exists'=>trans('The selected role_id is invalid.')
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
                $user = User::create([
                   'email'=>$col[1], 
            'password'=>$col[2], 
            'role_id'=>$col[3]
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

