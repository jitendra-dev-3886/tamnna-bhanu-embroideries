<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'description'=>$this->description, 
            'featured_image'=>$this->featured_image

        ];
    }
}
