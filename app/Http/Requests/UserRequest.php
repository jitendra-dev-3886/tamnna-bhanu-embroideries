<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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

        return [
            'contact_number' => 'required|max:255|unique:users,contact_number,NULL,id,deleted_at,NULL',
            'name' => 'required|max:191',
            'company_name' => 'required|max:191',
            'city' => 'required|max:191',
            'password' => 'required|min:8|max:191',
            'role_id' => 'required|exists:roles,id,deleted_at,NULL',
        ];
    }
}
