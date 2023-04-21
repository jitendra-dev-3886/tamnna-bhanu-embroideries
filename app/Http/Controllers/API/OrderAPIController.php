<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DataTrueResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderHistoryResource;
use App\Models\User;
use App\Models\Order;
use App\Http\Requests\CsvRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\OrderExport;
use App\Imports\OrderImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;



/*
   |--------------------------------------------------------------------------
   | orders Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the orders of
     index,
     show,
     store,
     update,
     destroy,
     export and
     importBulk Methods.
   |
   */

class OrderAPIController extends Controller
{
    /**
     * list orders
     * @param Request $request
     * @return OrderCollection
     */
    public function index(Request $request)
    {
        if ($request->get('is_light', false)) {

            return Cache::rememberForever('order.all', function () use ($request) {
                $order = new Order();
                $query = \App\Models\User::commonFunctionMethod(Order::select($order->light), $request, true);
                return new OrderCollection(OrderResource::collection($query), OrderResource::class);
            });
        } else
            $query = \App\Models\User::commonFunctionMethod(Order::with(['user', 'order_products']), $request, true);

        return new OrderCollection(OrderResource::collection($query), OrderResource::class);
    }

    /**
     * Order Detail
     * @param Order $order
     * @return OrderResource
     */
    public function show(Order $order)
    {
        return new OrderResource($order->load([]));
    }

    /**
     * Add Order
     * @param OrderRequest $request
     * @return OrderResource
     */
    public function store(OrderRequest $request)
    {
        return Order::createOrder($request);
    }

    /**
     * Update Order
     * @param OrderRequest $request
     * @param Order $order
     * @return OrderResource
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        return Order::updateOrder($request, $order);
    }

    /**
     * Delete Order
     *
     * @param Request $request
     * @param Order $order
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Order $order)
    {
        return Order::deleteOrder($request, $order);
    }

    /**
     * Delete Order multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Order::deleteAll($request);
    }

    /**
     * Export Order Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        $fileName = 'order_' . config('constants.file.name') . '.csv';
        $filePath = 'export/order/' . $fileName;
        $exportObj = new OrderExport($request);
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
        return \App\Models\User::importBulk($request, new OrderImport(), 'order', 'import/order/');
    }


    /**
     * Delete product_id
     * @param Request $request
     * @param \App\Models\OrderProduct $product_id
     * @return DataTrueResource
     * @throws \Exception
     */
    public function deleteProductId(Request $request, \App\Models\OrderProduct $orderProduct)
    {
        $orderProduct->deleteOne('order/' . $orderProduct->order_id . '/order_products/' . basename($orderProduct->product_id));
        $orderProduct->deleteOne('order/' . $orderProduct->order_id . '/order_products/thumbs/' . basename($orderProduct->product_id_thumbnail));
        $orderProduct->delete();

        return new DataTrueResource($orderProduct, config('constants.messages.delete_success'));
    }

    /**
     *  Upload zip for import function
     *
     * \App\Http\Requests\ZipFileRequest $request
     * @return mixed
     */
    public function uploadZipFile(\App\Http\Requests\ZipFileRequest $request)
    {
        return Order::uploadZipFile($request);
    }

    public function orderHistory(Request $request)
    {
        $query = User::commonFunctionMethod(Order::where('order_status', '!=', config('constants.order.status_code.embedded_order')), $request, true);
        return new OrderCollection(OrderHistoryResource::collection($query), OrderHistoryResource::class);
    }
}
