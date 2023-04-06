<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PermissionRequest extends FormRequest
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
            'name'=>'required|max:191|unique:permissions,name,NULL,id,deleted_at,NULL', 
            'guard_name'=>'required|max:191', 
            'label'=>'required|max:191',
            
        ];

    }
    
}
