<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckEmailExistsRequest extends FormRequest
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
        return [
            'id' => 'nullable',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            // 'contact_number' => 'required|max:255|unique:users,contact_number,NULL,id,deleted_at,NULL',
        ];
    }
}
