<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user' => new UserResource($this->user),
            'quantity' => $this->quantity,
            'gst' => $this->gst,
            'payment_amount' => $this->payment_amount,
            'order_status' => $this->order_status,
            'order_status_text' => config('constants.order.order_status.' . $this->order_status),
            'order_status_remark' => $this->order_status_remark,
            'user_remark' => $this->user_remark,
            'order_products' => $this->order_products,
            'created_at' => $this->created_at

        ];
    }
}
