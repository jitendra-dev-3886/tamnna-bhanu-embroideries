<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($request->get('is_light',false))
            return array_merge($this->attributesToArray(), $this->relationsToArray());

        return [

            'id'=>$this->id, 
            'user_id'=>$this->user_id, 
            'user'=>new UserResource($this->user), 
            'product_id'=>$this->product_id, 
            'product'=>new ProductResource($this->product), 
            'quantity'=>$this->quantity

        ];
    }
}
