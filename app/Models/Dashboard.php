<?php

namespace App\Models;

use App\Http\Resources\DataTrueResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Models\User;
use App\Traits\Scopes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Dashboard extends Model
{
    use Notifiable, Scopes, HasApiTokens;

    /**
     * This method is used to get All Dashboard listing
     * @param
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public static function getAllData()
    {

        /* Products */
        $products = [];
        // get total products from products table
        $products['total_products'] = (string)Product::all()->count();
        // get total available products from products table
        $products['total_available_products'] = (string)Product::where('available_status', config('constants.products.available_status_code.available'))->count();
        // get total out of stock products from products table
        $products['total_out_of_stock_products'] = (string)Product::where('available_status', config('constants.products.available_status_code.not_available'))->count();

        /* Categories */
        $categories = [];
        // get total products from products table
        $categories['total_categories'] = (string)Category::all()->count();

        /*Orders*/
        $orders = [];
        // get total orders from orders table
        $orders['total_orders'] = (string)Order::all()->count();
        // get total Pending orders from orders table
        $orders['total_pending_orders'] = (string)Order::where('order_status', config('constants.order.status_text.pending'))->count();
        // get total Delivered orders from orders table
        $orders['total_delivered_orders'] = (string)Order::where('order_status', config('constants.order.status_text.completed'))->count();

        /*Users*/
        $users = [];
        // get total front-end users from users table
        $user_role_id = config('constants.user.user_type_code.customer');
        $users['total_users'] = (string)User::where('role_id', $user_role_id)->count();

        // Data stores in an $response array.
        $response['products'] = $products;
        $response['categories'] = $categories;
        $response['users'] = $users;
        $response['orders'] = $orders;

        return $response;
    }

    /**
     * Common code for get the months list between teo date range.
     *
     * @param $startDate        - start date 'Y-m-d' format.
     * @param $endDate          - end date 'Y-m-d' format.
     * @return array
     */
    // public static function getMonthListFromDate($startDate, $endDate)
    // {
    //     $periods = CarbonPeriod::create($startDate, '1 month', $endDate);
    //     $months = [];
    //     foreach ($periods as $dt) {
    //         $months[] = $dt->format("M Y");
    //     }

    //     return $months;
    // }

    /**
     * Common code for get the Order Status By count between to date range.
     *
     * @param $order_start_Date   - start date 'Y-m-d' format.
     * @param $order_end_Date     - end date 'Y-m-d' format.
     * @param $OrderStatus        - order status.
     * @return array
     */

    // public static function getOrderStatusByCount($order_start_Date, $order_end_Date, $OrderStatus)
    // {

    //     if (Carbon::createFromFormat('Y-m-d', trim($order_start_Date)) !== false) {
    //         $dates[0] = $order_start_Date . ' 00:00:00';
    //     }
    //     if (Carbon::createFromFormat('Y-m-d', trim($order_end_Date)) !== false) {
    //         $dates[1] = $order_end_Date . ' 23:59:59';
    //     }
    //     $orders_count = Order::where('order_status', $OrderStatus)->whereBetween('created_at', $dates)->count();
    //     return $orders_count;
    // }

    /**
     * get Users Count From Activity Log Table
     *
     * @param $month
     * @return array
     */

    // public static function getUsersCountFromActivityLog($month)
    // {

    //     $usersCount = [];
    //     foreach ($month as $item) {
    //         $startdate = Carbon::parse($item)->format('Y-m-d');
    //         $enddate = Carbon::parse($startdate)->endOfMonth()->format('Y-m-d');
    //         $dates[0] = $startdate . ' 00:00:00';
    //         $dates[1] = $enddate . ' 23:59:59';
    //         $usersCount[] = ActivityLog::whereBetween('created_at', $dates)->count();
    //     }

    //     return $usersCount;
    // }
}