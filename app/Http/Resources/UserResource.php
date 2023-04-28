<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'contact_number' => $this->contact_number,
            'name' => $this->name,
            'company_name' => $this->company_name,
            'city' => $this->city,
            'email' => $this->email,
            'user_status' => $this->user_status,
            'user_status_text' => config('constants.user.status.' . $this->user_status),
            'role_id' => $this->role_id,
            'role' => $this->role,
        ];
    }
}
