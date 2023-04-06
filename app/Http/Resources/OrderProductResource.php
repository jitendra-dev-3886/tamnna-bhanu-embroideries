<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
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
            'order_id'=>$this->order_id, 
            'product_id'=>$this->product_id, 
            'product_name'=>$this->product_name, 
            'category_name'=>$this->category_name, 
            'featured_image'=>$this->featured_image, 
            'quantity'=>$this->quantity

        ];
    }
}
