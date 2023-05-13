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
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'company_name' => (string) $this->company_name,
            'city' => (string) $this->city,
            'otp_verified_at' => (string) $this->otp_verified_at,
            'contact_number' => (string) $this->contact_number,
            'permissions' => $this->permissions,
            'authorization' => (string) $this->authorization,
            'refresh_token' => (string) $this->refresh_token,
            'role_id' => (string) $this->role_id,
            'role' => $this->role,
            'device_token' => (string)$this->device_token,
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
