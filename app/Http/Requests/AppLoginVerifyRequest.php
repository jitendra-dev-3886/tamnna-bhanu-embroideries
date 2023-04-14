<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AppLoginVerifyRequest extends FormRequest
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
        $rules = [
            'contact_number' => 'required|integer|digits:10|starts_with:9,8,7,6|exists:users,contact_number',
            'otp'            => 'required|integer|digits:6',
        ];
        return $rules;
    }
}
