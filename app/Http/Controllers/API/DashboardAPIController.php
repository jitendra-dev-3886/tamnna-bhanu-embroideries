<?php

namespace App\Http\Controllers\API;


use App\Models\Dashboard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/*
   |--------------------------------------------------------------------------
   | email_formats Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the email_formats of
     index,
     update Methods.
   |
   */

class DashboardAPIController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // return response()->json(['data' => []],config('constants.validation_codes.ok'));
        $data = Dashboard::getAllData();
        return response()->json(['data' => $data]);
    }
}