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
            'id' => $this->id,
            'name' => $this->name,
            'company_name' => $this->company_name,
            'city' => $this->city,
            'otp_verified_at' => $this->otp_verified_at,
            'contact_number' => $this->contact_number,
            'permissions' => $this->permissions,
            // 'authorization' => $this->authorization,
            // 'refresh_token' => $this->refresh_token
            'role_id' => $this->role_id,
            'role' => $this->role,
            // 'device_token' => $this->device_token,
            'sample_excels' => array([
                'sample_user' => asset('samples/user.csv'),
                'sample_category' => asset('samples/category.csv'),
                'sample_product' => asset('samples/product.csv'),
                'sample_order' => asset('samples/order.csv'),
                'sample_cart' => asset('samples/cart.csv'),
            ]),
        ];
    }
}
