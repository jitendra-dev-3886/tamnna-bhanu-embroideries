<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AppLoginRequest extends FormRequest
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
        //TODO Should mobile be in Active state validation required ?


        // $requestPath = User::getPathWithoutId($request);
        // $realURI = str_replace('api/v1/', '', $requestPath);



        // if ($realURI == config('constants.login_api.app_login')) {
        return [
            'contact_number' => [
                'required',
                // 'integer',
                'digits:10',
                // 'starts_with:9,8,7,6',
                // 'exists:users,contact_number',
            ]
        ];
        // }
        // elseif ($realURI == config('constants.login_api.app_login_portal')) {

        //     return [
        //         'inflCode' => 'required|exists:ctmInfluncr,inflCode'
        //     ];
        // }

        // return $request;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'contact_number.exists' => 'Account does not exist, please try to create a new account.',
        ];
    }
}
