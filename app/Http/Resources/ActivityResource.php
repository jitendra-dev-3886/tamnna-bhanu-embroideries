<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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

        $subjectType = $this->subject_type;
        $causer = $this->causer;

        return [
            'id'=>$this->id,
            'log_name'=>$this->log_name,
            'description'=>$this->description,
            'ip_address'=>$this->ip_address,
            'subject_type'=>$this->subject_type,
            'subject_id'=>$this->subject_id,
            'subject'=>is_null($this->subject_id)?null:$subjectType::where('id',$this->subject_id)->first(),
            'causer_type'=>$this->causer_type,
            'causer_id'=>$this->causer_id,
            'causer'=>$causer,
            'response_type'=>$this->response_type,
            'response_type_text'=>config('constants.activity_log.response_type.'.$this->response_type),
            'properties'=>json_decode($this->properties,true),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'causer_name'=>is_null($causer)?'':$causer->,
        ];
    }
}
