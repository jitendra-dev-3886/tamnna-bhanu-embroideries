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
            '0'=>'required|max:191|email',
            '1'=>'required|min:6|max:191',
            '2'=>'required|exists:roles,id,deleted_at,NULL',
            '3'=>'required | regex:/^[a-zA-Z_ ]*$/ | max:191',
            '4'=>''
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
            '2.exists'=>trans('The selected role_id is invalid.'),
            '3.required'=>trans('The name is required.')

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
                   'email'=>$col[0],
                   'password'=>bcrypt($col[1]),
                   'role_id'=>$col[2],
                   'name'=>$col[3],
                   'contact_number'=>$col[4]
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

