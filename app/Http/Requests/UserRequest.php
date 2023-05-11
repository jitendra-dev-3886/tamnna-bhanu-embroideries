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
    public function rules()
    {
        $user = Auth::user();

        $commonRule = [
            'name' => 'required|max:191',
            'contact_number' => 'required|digits:10|unique:users,contact_number,NULL,id,deleted_at,NULL'
        ];

        if (isset($user)) {
            $commonRule['role_id'] = 'required|exists:roles,id,deleted_at,NULL';
            $commonRule['email'] = 'max:255|unique:users,email,NULL,id,deleted_at,NULL';
            $commonRule['password'] = 'min:8|max:191';
        } else {
            $commonRule['company_name'] = 'required|max:191';
            $commonRule['city'] = 'required|max:191';
        }
        
        return $commonRule;
    }
}
