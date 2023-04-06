<?php

namespace App\Models;
use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\CartResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Cart extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = [ 'id', 'user_id', 'product_id', 'quantity' ];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = [ 'id', 'user->name', 'product->name', 'quantity' ];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = [ 'user', 'product' ];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = [ 'id', 'user_id', 'product_id' ];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = [  ];

    /**
     * @var array
     */
    public $sortable = [ 'carts.created_at', 'carts.id', 'quantity' ];

    /**
     * @var array
     */
    public $foreign_sortable = [ 'user_id', 'product_id' ];

    /**
     * @var array
     */
    public $foreign_table = [ 'users', 'products' ];

    /**
     * @var array
     */
    public $foreign_key = [ 'name', 'name' ];

    /**
     * @var array
     */
    public $foreign_method = [ 'user', 'product' ];

    /**
     * @var array
     */
    public $type_sortable = [  ];

    /**
     * @var array
     */
    public $type_enum = [
            
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
            
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
            'user_id'=>'string', 
            'product_id'=>'string', 
            'quantity'=>'string'

    ];

    

    
    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user() {
       return $this->belongsTo(\App\Models\User::class)->with(['role']);
    }
         
    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function product() {
       return $this->belongsTo(\App\Models\Product::class)->with(['category', 'product_galleries']);
    }
        

    /**
     * Add Cart
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateCart($query, $request){
        $cart = Cart::create($request->all());
        
        
        
        return \App\Models\User::GetMessage(new CartResource($cart), config('constants.messages.create_success'));
    }

    /**
     * Update Cart
     * @param Request $request
     * @param Cart $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateCart($query, $request, $cart){
        $data = $request->all();
        
        
        $cart->update($data);
        
        return \App\Models\User::GetMessage(new CartResource($cart), config('constants.messages.update_success'));
    }

    /**
     * Delete Cart
     *
     * @param Request $request
     * @param Cart $cart
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteCart($query, $request, $cart){
       
       
       $cart->delete();
       
       return new DataTrueResource($cart,config('constants.messages.delete_success'));
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query,$request){
        if(!empty($request->id)) {

            Cart::whereIn('id', $request->id)->get()->each(function($cart) {
                
                
                $cart->delete();
            });

            

            return new DataTrueResource(true,config('constants.messages.delete_success'));
        }
        else{
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }

    
}
