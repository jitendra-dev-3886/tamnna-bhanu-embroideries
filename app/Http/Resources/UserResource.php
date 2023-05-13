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
            'contact_number' => (string) $this->contact_number,
            'name' => (string) $this->name,
            'company_name' => (string) $this->company_name,
            'city' => (string) $this->city,
            'email' => (string) $this->email,
            'user_status' => (string) $this->user_status,
            'user_status_text' => config('constants.user.status.' . $this->user_status),
            'role_id' => (string) $this->role_id,
            'role' => $this->role,
        ];
    }
}
