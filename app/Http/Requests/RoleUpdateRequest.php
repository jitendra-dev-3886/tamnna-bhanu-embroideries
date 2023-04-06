<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RoleUpdateRequest extends FormRequest
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
            'name'=>'required|max:191|unique:roles,name,' . $id . ',id,deleted_at,NULL', 
            'guard_name'=>'nullable|max:191', 
            'landing_page'=>'nullable|max:191'
            
        ];

    }
    
}
