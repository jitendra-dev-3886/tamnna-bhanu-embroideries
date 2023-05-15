<?php

namespace App\Models;

use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\OrderProductResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class OrderProduct extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = ['id', 'order_id', 'product_id', 'product_name', 'category_name', 'price', 'featured_image', 'quantity'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = [];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = [];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = ['id', 'order_id', 'product_id', 'product_name', 'price', 'category_name', 'featured_image'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = ['orders'];

    /**
     * @var array
     */
    public $sortable = ['order_products.created_at', 'order_products.id', 'order_id', 'product_name', 'price', 'category_name', 'featured_image', 'quantity'];

    /**
     * @var array
     */
    public $foreign_sortable = ['product_id'];

    /**
     * @var array
     */
    public $foreign_table = ['products'];

    /**
     * @var array
     */
    public $foreign_key = ['name'];

    /**
     * @var array
     */
    public $foreign_method = ['product'];

    /**
     * @var array
     */
    public $type_sortable = [];

    /**
     * @var array
     */
    public $type_enum = [];

    /**
     * @var array
     */
    public $type_enum_text = [];

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
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'order_id' => 'string',
        'product_id' => 'string',
        'product_name' => 'string',
        'price' => 'string',
        'category_name' => 'string',
        'featured_image' => 'string',
        'quantity' => 'string'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id')->with(['categories']);
    }

    /**
     * Add OrderProduct
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateOrderProduct($query, $request)
    {
        $orderProduct = OrderProduct::create($request->all());
        return \App\Models\User::GetMessage(new OrderProductResource($orderProduct), config('constants.messages.create_success'));
    }

    /**
     * Update OrderProduct
     * @param Request $request
     * @param OrderProduct $orderProduct
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateOrderProduct($query, $request, $orderProduct)
    {
        $data = $request->all();
        $orderProduct->update($data);
        return \App\Models\User::GetMessage(new OrderProductResource($orderProduct), config('constants.messages.update_success'));
    }

    /**
     * Delete OrderProduct
     *
     * @param Request $request
     * @param OrderProduct $orderProduct
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteOrderProduct($query, $request, $orderProduct)
    {
        $orderProduct->delete();
        return new DataTrueResource($orderProduct, config('constants.messages.delete_success'));
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query, $request)
    {
        if (!empty($request->id)) {
            OrderProduct::whereIn('id', $request->id)->get()->each(function ($orderProduct) {
                $orderProduct->delete();
            });
            return new DataTrueResource(true, config('constants.messages.delete_success'));
        } else {
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }
}
