<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrderUpdateRequest extends FormRequest
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
            'user_id'=>'required|exists:users,id,deleted_at,NULL', 
            'quantity'=>'nullable', 
            'gst'=>'nullable', 
            'payment_amount'=>'nullable', 
            'order_status'=>'required|in:0,1,2,3,4', 
            'order_status_remark'=>'nullable', 
            'user_remark'=>'nullable', 
            'order_products'=>'nullable', 
            'order_products.*'=>'nullable'
            
        ];

    }
    
}
