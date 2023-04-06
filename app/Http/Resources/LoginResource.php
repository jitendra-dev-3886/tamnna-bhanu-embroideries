<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'permissions' => $this->permissions,
            'authorization' => $this->authorization,
            'id'=>$this->id, 
            'email'=>$this->email, 
            'email_verified_at'=>$this->email_verified_at, 
            'role_id'=>$this->role_id, 
            'role'=>$this->role,
            'sample_excels'=>array([
                'sample_user' => asset('samples/user.csv'),
                'sample_category' => asset('samples/category.csv'),
                'sample_product' => asset('samples/product.csv'),
                'sample_order' => asset('samples/order.csv'),
                'sample_cart' => asset('samples/cart.csv'),
            ]),
        ];
    }
}
