<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CartRequest extends FormRequest
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
            'user_id'    => 'required|exists:users,id,deleted_at,NULL',
            'product_id' => 'required|exists:products,id,deleted_at,NULL',
            'quantity'   => 'required|integer|min:1',
        ];
    }
}
