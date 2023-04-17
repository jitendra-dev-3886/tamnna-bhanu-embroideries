<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CategoryUpdateRequest extends FormRequest
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

        $rules = [
            'name'           => 'required|max:191',
            'description'    => 'required',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024'
        ];
        // dd($rules);
        return $rules;
    }
}
