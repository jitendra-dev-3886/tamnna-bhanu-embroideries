<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductGalleryUpdateRequest extends FormRequest
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
            'product_galleries'=>'nullable|array|max:5',
            'product_galleries.*'=>'required|image|mimes:jpeg,png,jpg|max:5120'

        ];

    }

}
