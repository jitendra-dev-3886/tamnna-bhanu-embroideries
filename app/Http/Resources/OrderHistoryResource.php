<?php

namespace App\Http\Resources;

// namespace App\Http\Resources\Order;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryResource extends JsonResource
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
            'id'                => $this->id,
            'order_date'        => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y-m-d'),
            'quantity'          => $this->quantity,
            'gst'               => $this->gst,
            'order_status'      => $this->order_status,
            'order_status_text' => config('constants.order.status.' . $this->order_status),
        ];
    }
}
