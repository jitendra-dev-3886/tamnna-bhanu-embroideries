<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductUpdateRequest extends FormRequest
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
            'name'=>'required|max:191', 
            'price'=>'required', 
            'description'=>'required', 
            'item_code'=>'required|max:191', 
            'category_id'=>'required|exists:categories,id,deleted_at,NULL', 
            'available_status'=>'required|in:0,1', 
            'stock'=>'nullable', 
            'featured_image'=>'required|max:500', 
            'product_galleries'=>'nullable|array|max:5', 
            'product_galleries.*'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
            
        ];

    }
    
}
