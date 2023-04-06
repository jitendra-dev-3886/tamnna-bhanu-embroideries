<?php

namespace App\Models;
use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\OrderStatusResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class OrderStatus extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = [ 'id', 'order_id', 'order_status' ];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = [  ];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = [  ];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = [ 'id', 'order_id' ];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = [  ];

    /**
     * @var array
     */
    public $sortable = [ 'order_statuses.created_at', 'order_statuses.id', 'order_id' ];

    /**
     * @var array
     */
    public $foreign_sortable = [  ];

    /**
     * @var array
     */
    public $foreign_table = [  ];

    /**
     * @var array
     */
    public $foreign_key = [  ];

    /**
     * @var array
     */
    public $foreign_method = [  ];

    /**
     * @var array
     */
    public $type_sortable = [ 'order_status' ];

    /**
     * @var array
     */
    public $type_enum = [
            ['constants.order_status.order_status_enum.pending','constants.order_status.order_status_enum.inprocess','constants.order_status.order_status_enum.cancel','constants.order_status.order_status_enum.completed','constants.order_status.order_status_enum.return']
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
            ['constants.order_status.order_status.0','constants.order_status.order_status.1','constants.order_status.order_status.2','constants.order_status.order_status.3','constants.order_status.order_status.4']
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [  ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

            'id'=>'string', 
            'order_id'=>'string', 
            'order_status'=>'string'

    ];

    

    

    /**
     * Add OrderStatus
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateOrderStatus($query, $request){
        $orderStatus = OrderStatus::create($request->all());
        
        
        
        return \App\Models\User::GetMessage(new OrderStatusResource($orderStatus), config('constants.messages.create_success'));
    }

    /**
     * Update OrderStatus
     * @param Request $request
     * @param OrderStatus $orderStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateOrderStatus($query, $request, $orderStatus){
        $data = $request->all();
        
        
        $orderStatus->update($data);
        
        return \App\Models\User::GetMessage(new OrderStatusResource($orderStatus), config('constants.messages.update_success'));
    }

    /**
     * Delete OrderStatus
     *
     * @param Request $request
     * @param OrderStatus $orderStatus
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteOrderStatus($query, $request, $orderStatus){
       
       
       $orderStatus->delete();
       
       return new DataTrueResource($orderStatus,config('constants.messages.delete_success'));
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query,$request){
        if(!empty($request->id)) {

            OrderStatus::whereIn('id', $request->id)->get()->each(function($orderStatus) {
                
                
                $orderStatus->delete();
            });

            

            return new DataTrueResource(true,config('constants.messages.delete_success'));
        }
        else{
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }

    
}
