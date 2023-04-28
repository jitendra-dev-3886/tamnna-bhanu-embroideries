<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $role_Id = Auth::user()->role_id;
        // dd($role_Id);
        if($role_Id == 1)
        {
            return [
            'contact_number' => 'required|max:255|unique:users,contact_number,NULL,id,deleted_at,NULL',
            'name' => 'required|max:191',
            'company_name' => 'max:191',
            'city' => 'max:191',
            'password' => 'min:8|max:191',
            'email' => 'max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'role_id' => 'required|exists:roles,id,deleted_at,NULL',
        ];

        }else{
            return [
                'contact_number' => 'required|max:255|unique:users,contact_number,NULL,id,deleted_at,NULL',
                'name' => 'required|max:191',
                'company_name' => 'required|max:191',
                'city' => 'required|max:191',
                'password' => 'required|min:8|max:191',
                'email' => 'required|max:255|unique:users,email,NULL,id,deleted_at,NULL',
                'role_id' => 'required|exists:roles,id,deleted_at,NULL',
            ];
        }


    }
}
