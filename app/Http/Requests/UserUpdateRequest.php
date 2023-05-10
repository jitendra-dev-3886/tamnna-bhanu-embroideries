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
                'contact_number' => 'required|digits:10',
                'name' => 'required|max:191',
                'company_name' => 'max:191',
                'city' => 'max:191',
                'email' =>  'max:255|email',
                'role_id' => 'exists:roles,id,deleted_at,NULL'
        ];
    }
}
