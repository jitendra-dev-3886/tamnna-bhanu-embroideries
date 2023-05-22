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
        if ($request->get('is_light', false))
            return array_merge($this->attributesToArray(), $this->relationsToArray());

        return [

            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => (string)$this->description,
            'item_code' => $this->item_code,
            'category_id' => $this->categories->pluck('id'),
            // 'sub_category_id' => $this->sub_category_id,
            'category' => $this->categories,
            'available_status' => $this->available_status,
            'available_status_text' => config('constants.products.available_status.' . $this->available_status),
            'status' => $this->status,
            'status_text' => config('constants.products.status.' . $this->status),
            'stock' => $this->stock,
            'featured_image' => $this->featured_image,
            'available_color' => $this->available_color,
            'set_unit' => $this->set_unit,
            'unit_price' => $this->unit_price,
            'product_galleries' => ProductGalleryResource::collection($this->product_galleries)

        ];
    }
}
