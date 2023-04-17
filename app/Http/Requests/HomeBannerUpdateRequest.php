<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class HomeBannerUpdateRequest extends FormRequest
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
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'banner_status'  => 'required|in:0,1',
        ];
        return $rules;
    }
}
