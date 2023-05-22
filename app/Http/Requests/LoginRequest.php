<?php

namespace App\Http\Requests;

use Auth;
use App\Rules\ValidRecaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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

    public function rules(Request $request)
    {
        $uri = $request->path();

        $rules = [
            'email' => 'required|max:191',
            'password' => 'required',
        ];

        if ($uri == 'api/v1/login') {
            if (App::environment(['production']) || App::environment(['uat']))
                $rules['g_recaptcha_response'] = ['required', new ValidRecaptcha];
        }


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
