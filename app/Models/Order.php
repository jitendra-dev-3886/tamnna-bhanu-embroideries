<?php

namespace App\Models;

use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\OrderResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'quantity', 'gst', 'payment_amount', 'order_status', 'order_status_remark', 'user_remark'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = ['id', 'quantity', 'gst', 'payment_amount', 'order_products->product_id'];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = ['order_products'];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = ['id', 'user_id'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = [];

    /**
     * @var array
     */
    public $sortable = ['orders.created_at', 'orders.id', 'quantity', 'gst', 'payment_amount'];

    /**
     * @var array
     */
    public $foreign_sortable = ['user_id'];

    /**
     * @var array
     */
    public $foreign_table = ['users'];

    /**
     * @var array
     */
    public $foreign_key = ['name'];

    /**
     * @var array
     */
    public $foreign_method = ['user'];

    /**
     * @var array
     */
    public $type_sortable = ['order_status'];

    /**
     * @var array
     */
    public $type_enum = [
        ['constants.order.order_status_enum.pending', 'constants.order.order_status_enum.inprocess', 'constants.order.order_status_enum.cancel', 'constants.order.order_status_enum.completed', 'constants.order.order_status_enum.return']
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
        ['constants.order.order_status.0', 'constants.order.order_status.1', 'constants.order.order_status.2', 'constants.order.order_status.3', 'constants.order.order_status.4']
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
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

        'id' => 'string',
        'user_id' => 'string',
        'quantity' => 'string',
        'gst' => 'string',
        'order_status' => 'string',
        'order_status_remark' => 'string',
        'user_remark' => 'string'

    ];




    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class)->with(['role']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_products()
    {
        return $this->hasMany(\App\Models\OrderProduct::class, 'order_id')->select([
            'order_id',
            'product_id',
            'product_name',
            'price',
            'featured_image',
            'quantity',
            'created_by',
            'created_by',
            'updated_by',
            'deleted_by',
            'created_at',
            'updated_at',
            'deleted_at'

        ]);
    }


    /**
     * Add Order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateOrder($query, $request)
    {
        $order = Order::create($request->all());


        if ($request->hasFile('order_products')) {
            $realPath = 'order/' . $order->id . '/order_products';

            foreach ($request->file('order_products') as $vImgs) {
                $resizeImages = $order->resizeImages($vImgs, $realPath, 100, 100);
                \App\Models\OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $resizeImages['image'],
                    'product_id_original' => $resizeImages['original'],
                    'product_id_thumbnail' => $resizeImages['thumbnail']
                ]);
            }
        }

        $cartProducts = Cart::where('user_id', $order->user_id)->where('deleted_at', null)->get();

        foreach ($cartProducts as $cartProduct) {
            $outOfStockProduct = Product::where('id', $cartProduct->product_id)->where('available_status', config('constants.products.available_status_code.not_available'))->first();
            if (!is_null($outOfStockProduct)) {
                $productIds[] = $outOfStockProduct->name;
            }
            $products = Product::where('id', $cartProduct->product_id)
                ->where('available_status', config('constants.products.available_status_code.available'))
                ->get();
        }

        if (!empty($productIds)) {
            $ProductArray = implode(', ', $productIds);
            return response()->json([
                'error' => $ProductArray . ' is out of stock.'
            ], config('constants.validation_codes.unprocessable_entity'));
        }

        foreach ($cartProducts as $cartProduct) {
            $product = Product::where('id', $cartProduct->product_id)->first();

            $productData['order_id'] =  $order->id;
            $productData['product_id'] = $cartProduct->product_id;
            $productData['product_name'] = $product->name;
            $productData['price'] = $product->price;
            $productData['featured_image'] =  $product->featured_image;
            $productData['quantity']  = $cartProduct->quantity;

            OrderProduct::create($productData); //Insert order products into order_products table
            $product['stock'] = $product->stock - $cartProduct->quantity;
            $product->update();
            Cart::where('user_id', $order->user_id)->where('product_id', $cartProduct->product_id)->delete(); //Delete products from cart
        }

        return \App\Models\User::GetMessage(new OrderResource($order), config('constants.messages.create_success'));
    }

    /**
     * Update Order
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateOrder($query, $request, $order)
    {
        $data = $request->all();


        if ($request->hasFile('order_products')) {
            \App\Models\OrderProduct::where(['order_id' => $order->id])->delete();

            $realPath = 'order/' . $order->id . '/order_products';
            \Illuminate\Support\Facades\Storage::deleteDirectory('/public/' . $realPath);

            foreach ($request->file('order_products') as $v_imgs) {
                $resizeImages = $order->resizeImages($v_imgs, $realPath, 100, 100);
                \App\Models\OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $resizeImages['image'],
                    'product_id_original' => $resizeImages['original'],
                    'product_id_thumbnail' => $resizeImages['thumbnail']
                ]);
            }
        }

        $order->update($data);

        return \App\Models\User::GetMessage(new OrderResource($order), config('constants.messages.update_success'));
    }

    /**
     * Delete Order
     *
     * @param Request $request
     * @param Order $order
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteOrder($query, $request, $order)
    {


        $this->singleImageDelete($order, "order/"); // Delete image

        $order->delete();

        return new DataTrueResource($order, config('constants.messages.delete_success'));
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

            Order::whereIn('id', $request->id)->get()->each(function ($order) {


                $this->singleImageDelete($order, "order/"); // Delete image

                $order->delete();
            });



            return new DataTrueResource(true, config('constants.messages.delete_success'));
        } else {
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }

    public function newQuery()
    {
        $user = Auth::guard('api')->user();
        if ($user)
            return parent::newQuery()->where('user_id', '=', $user->id);

        return parent::newQuery();
    }
}
