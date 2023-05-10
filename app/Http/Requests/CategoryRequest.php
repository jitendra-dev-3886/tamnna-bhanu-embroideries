<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CategoryRequest extends FormRequest
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
            'name'           => 'required|max:191',
            'description'    => 'max:255',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'category_status'  => 'required|in:0,1',
        ];
    }
}
