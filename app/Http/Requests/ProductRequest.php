<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductRequest extends FormRequest
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
            'name' => 'required|max:191',
            // 'price' => 'required',
            'description' => 'max:65000',
            'item_code' => 'required|max:191',
            'category_id' => 'required|array',
            'category_id.*' => 'required|exists:categories,id,deleted_at,NULL',
            'available_status' => 'required|in:0,1',
            'stock' => 'required|numeric|min:0',
            'featured_image' => 'image|mimes:jpeg,png,jpg|max:5120',

            'available_color' => 'required|max:191',
            'set_unit' => 'required|integer|between:1,10',
            'unit_price' => 'required|numeric|between:0,9999999999.99',

            'product_galleries' => 'array|max:5',
            'product_galleries.*' => 'image|mimes:jpeg,png,jpg|max:25600',

            'parent_id' => 'array',
            'parent_id.*' => 'nullable|exists:categories,id,deleted_at,NULL',

        ];
    }
}
