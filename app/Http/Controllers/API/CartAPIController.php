<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DataTrueResource;
use App\Models\User;
use App\Models\Cart;
use App\Http\Requests\CsvRequest;
use App\Http\Requests\CartRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\CartExport;
use App\Imports\CartImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;



/*
   |--------------------------------------------------------------------------
   | carts Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the carts of
     index,
     show,
     store,
     update,
     destroy,
     export and
     importBulk Methods.
   |
   */

class CartAPIController extends Controller
{
    /**
     * list carts
     * @param Request $request
     * @return CartCollection
     */
    public function index(Request $request)
    {
        if ($request->get('is_light', false)) {

            return Cache::rememberForever('cart.all', function () use ($request) {
                // $cart = new Cart();
                // $query = \App\Models\User::commonFunctionMethod(Cart::select($cart->light),$request,true);
                $cart = Cart::where('id', $request->cart_id);
                $query = User::commonFunctionMethod($cart, $request, true);
                return new CartCollection(CartResource::collection($query), CartResource::class);
            });
        } else
            $query = User::commonFunctionMethod(Cart::with(['user', 'product']), $request, true);

        return new CartCollection(CartResource::collection($query), CartResource::class);
    }

    /**
     * Cart Detail
     * @param Cart $cart
     * @return CartResource
     */
    public function show(Cart $cart)
    {
        return new CartResource($cart->load([]));
    }

    /**
     * Add Cart
     * @param CartRequest $request
     * @return CartResource
     */
    public function store(CartRequest $request)
    {
        return Cart::createCart($request);
    }

    /**
     * Update Cart
     * @param CartRequest $request
     * @param Cart $cart
     * @return CartResource
     */
    public function update(CartUpdateRequest $request, Cart $cart)
    {
        return Cart::updateCart($request, $cart);
    }

    /**
     * Delete Cart
     *
     * @param Request $request
     * @param Cart $cart
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Cart $cart)
    {
        return Cart::deleteCart($request, $cart);
    }

    /**
     * Delete Cart multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Cart::deleteAll($request);
    }

    /**
     * Export Cart Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        $fileName = 'cart_' . config('constants.file.name') . '.csv';
        $filePath = 'export/cart/' . $fileName;
        $exportObj = new CartExport($request);
        Excel::store($exportObj, $filePath);

        return response()->download(storage_path("app/public/{$filePath}"));
    }

    /**
     * Import bulk
     * @param CsvRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(CsvRequest $request)
    {
        return \App\Models\User::importBulk($request, new CartImport(), 'cart', 'import/cart/');
    }

    /**
     * Get Cart Count.
     * @param Request $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function getCartsCounts()
    {
        $user = Auth::user();

        $cartTotal = Cart::where('user_id', $user->id)->sum('quantity');
        return response()->json(['cart_total' => (string) $cartTotal], config('constants.validation_codes.ok'));
    }
}
