<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserUpdateRequest extends FormRequest
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

        $urlArr = explode("/", $request->path());
        $id = end($urlArr);

        return [
                'contact_number' => 'max:255',
                'name' => 'required|max:191',
                'company_name' => 'max:191',
                'city' => 'max:191',
                'password' => 'required|min:8|max:191|email',
                'email' =>  'max:255',
                'role_id' => 'exists:roles,id,deleted_at,NULL'

        ];
    }
}
