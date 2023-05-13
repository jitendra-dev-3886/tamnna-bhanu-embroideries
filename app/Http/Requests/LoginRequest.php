<?php

namespace App\Http\Requests;

use Auth;
use App\Rules\ValidRecaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class LoginRequest extends FormRequest
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
        $rules = [
            'email' => 'required|email',
            'password'       => 'required|min:6|max:191',
        ];

        // if (App::environment(['production']))
        //     $rules['g_recaptcha_response'] = ['required', new ValidRecaptcha];

        return $rules;
    }

    /**
     * Get the validation messages apply to this request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'g_recaptcha_response.required' => 'Please ensure that you are a human!'
        ];
    }
}
