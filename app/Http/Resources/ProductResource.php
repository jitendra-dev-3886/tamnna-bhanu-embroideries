<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name'=>$this->name,
            'price'=>$this->price,
            'description'=>(string)$this->description,
            'item_code'=>$this->item_code,
            // 'category_id'=>$this->category_id,
            'category_id' => $this->categories->pluck('id'),
            'category'=> $this->categories,
            'available_status'=>$this->available_status,
            'available_status_text'=>config('constants.product.available_status.'.$this->available_status),
            'stock'=>$this->stock,
            'featured_image'=>$this->featured_image,
            'product_galleries'=>ProductGalleryResource::collection($this->product_galleries)

        ];
    }
}
