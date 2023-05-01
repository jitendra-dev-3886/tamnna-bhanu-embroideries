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
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;

class Cart extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    public function newQuery()
    {
        $user = Auth::guard('api')->user();
        if ($user)
            return parent::newQuery()->where('user_id', '=', $user->id);

        return parent::newQuery();
    }

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'product_id', 'quantity'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = ['id', 'user->name', 'product->name', 'quantity'];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = ['user', 'product'];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = ['id', 'user_id', 'product_id'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = [];

    /**
     * @var array
     */
    public $sortable = ['carts.created_at', 'carts.id', 'quantity'];

    /**
     * @var array
     */
    public $foreign_sortable = ['user_id', 'product_id'];

    /**
     * @var array
     */
    public $foreign_table = ['users', 'products'];

    /**
     * @var array
     */
    public $foreign_key = ['name', 'name'];

    /**
     * @var array
     */
    public $foreign_method = ['user', 'product'];

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
        'user_id' => 'string',
        'product_id' => 'string',
        'quantity' => 'string'

    ];




    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->with(['role']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->with(['categories', 'product_galleries']);
    }


    /**
     * Add Cart
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateCart($query, $request)
    {
        $user = Auth::user();
        $data = $request->all();

        if ($user->id != $data['user_id']) {
            return response()->json([
                'error' => config('constants.messages.something_wrong')
            ], config('constants.validation_codes.unprocessable_entity'));
        }

        // Check product is out of stock
        $outOfStockProduct = Product::where('id', $data['product_id'])->where('available_status', config('constants.products.available_status_code.not_available'))->first();
        if (!is_null($outOfStockProduct)) {
            return response()->json([
                'error' => config('constants.messages.wishlist.out_of_stock')
            ], config('constants.validation_codes.unprocessable_entity'));
        }

        $cart = Cart::where('user_id', $user->id)->where('product_id', $data['product_id'])->first();
        if (!empty($cart)) {
            $data['quantity'] = $data['quantity'] + $cart->quantity;
            $cart->update($data);
        } else {
            $cart = Cart::create($data);
        }

        // $cart = Cart::create($request->all());

        return User::GetMessage(new CartResource($cart), config('constants.messages.create_success'));
    }

    /**
     * Update Cart
     * @param Request $request
     * @param Cart $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateCart($query, $request, $cart)
    {
        $user = Auth::user();
        $data = $request->all();

        if ($user->id != $request->get('user_id')) {
            return response()->json([
                'error' => config('constants.messages.something_wrong')
            ], config('constants.validation_codes.unprocessable_entity'));
        }

        $carts = Cart::where('id', $cart->id)->first();
        if (!empty($carts)) {
            $outOfStockProduct = Product::where('id', $carts->product_id)->where('available_status', config('constants.products.available_status_code.not_available'))->first();
            if (!is_null($outOfStockProduct)) {
                return response()->json([
                    'error' => config('constants.messages.wishlist.out_of_stock')
                ], config('constants.validation_codes.unprocessable_entity'));
            }
            $data['quantity'] = $request->get('quantity');
            $cart->update($data);
        } else {
            return response()->json([
                'error' => config('constants.messages.something_wrong')
            ], config('constants.validation_codes.unprocessable_entity'));
        }

        return User::GetMessage(new CartResource($cart), config('constants.messages.update_success'));
    }

    /**
     * Delete Cart
     *
     * @param Request $request
     * @param Cart $cart
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteCart($query, $request, $cart)
    {


        $cart->delete();

        return new DataTrueResource($cart, config('constants.messages.delete_success'));
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

            Cart::whereIn('id', $request->id)->get()->each(function ($cart) {


                $cart->delete();
            });



            return new DataTrueResource(true, config('constants.messages.delete_success'));
        } else {
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }
}
