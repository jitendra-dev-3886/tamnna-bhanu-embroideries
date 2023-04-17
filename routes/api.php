<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::group([
    'prefix' => 'v1',
    'namespace' => 'API',
], function () {
    // Route::get('email/verify/{id}', '\App\Http\Controllers\API\VerificationAPIController@verify')->name('verification.verify');
    // Route::get('email/resend', '\App\Http\Controllers\API\VerificationAPIController@resend')->name('verification.resend');

    // Route::post('forgot-password', '\App\Http\Controllers\API\ForgotPasswordAPIController@sendResetLinkEmail');

    Route::get('batch-request', '\App\Http\Controllers\API\UserAPIController@batchRequest');
    Route::post('login', '\App\Http\Controllers\API\LoginAPIController@login');

    Route::post('app-login', '\App\Http\Controllers\API\LoginAPIController@appLogin'); // RM
    Route::post('app-login-verify', '\App\Http\Controllers\API\LoginAPIController@appLoginVerify'); // RM

    // Route::put('users/{user}', '\App\Http\Controllers\API\UserAPIController@update');
    Route::resource('users', '\App\Http\Controllers\API\UserAPIController');
    Route::post('users-delete-multiple', '\App\Http\Controllers\API\UserAPIController@deleteAll');
    Route::get('users-export', '\App\Http\Controllers\API\UserAPIController@export');
    Route::post('users-import-bulk', '\App\Http\Controllers\API\UserAPIController@importBulk');

    Route::group([
        'middleware' => ['auth:api', 'check.permission'],
    ], function () {

        Route::get('auth-batch-request', '\App\Http\Controllers\API\UserAPIController@batchRequest');
        Route::get('get-activities', '\App\Http\Controllers\API\UserAPIController@getActivities');
        Route::post('check-email', '\App\Http\Controllers\API\UserAPIController@checkEmail');
        Route::post('change-password', '\App\Http\Controllers\API\LoginAPIController@changePassword');
        Route::get('logout', '\App\Http\Controllers\API\LoginAPIController@logout');
        Route::resource('import-csv-log', '\App\Http\Controllers\API\ImportCsvLogsAPIController', ['only' => ['show', 'index']]);

        Route::resource('permissions', '\App\Http\Controllers\API\PermissionAPIController');
        Route::post('permissions-delete-multiple', '\App\Http\Controllers\API\PermissionAPIController@deleteAll');
        Route::get('permissions-export', '\App\Http\Controllers\API\PermissionAPIController@export');
        Route::post('permissions-import-bulk', '\App\Http\Controllers\API\PermissionAPIController@importBulk');
        Route::post('set-unset-permission-to-role', '\App\Http\Controllers\API\PermissionAPIController@setUnsetPermissionToRole');

        Route::resource('roles', '\App\Http\Controllers\API\RoleAPIController');
        Route::post('roles-delete-multiple', '\App\Http\Controllers\API\RoleAPIController@deleteAll');
        Route::get('roles-export', '\App\Http\Controllers\API\RoleAPIController@export');
        Route::post('roles-import-bulk', '\App\Http\Controllers\API\RoleAPIController@importBulk');
        Route::get('get-role-by-permissions/{id}', '\App\Http\Controllers\API\RoleAPIController@getPermissionsByRole');

        Route::post('categories/{category}', '\App\Http\Controllers\API\CategoryAPIController@update');
        Route::resource('categories', '\App\Http\Controllers\API\CategoryAPIController', ['except' => ['update']]);
        Route::post('categories-delete-multiple', '\App\Http\Controllers\API\CategoryAPIController@deleteAll');
        Route::get('categories-export', '\App\Http\Controllers\API\CategoryAPIController@export');
        Route::post('categories-import-bulk', '\App\Http\Controllers\API\CategoryAPIController@importBulk');

        Route::post('products/{product}', '\App\Http\Controllers\API\ProductAPIController@update');
        Route::resource('products', '\App\Http\Controllers\API\ProductAPIController', ['except' => ['update']]);
        Route::post('products-delete-multiple', '\App\Http\Controllers\API\ProductAPIController@deleteAll');
        Route::get('products-export', '\App\Http\Controllers\API\ProductAPIController@export');
        Route::post('products-import-bulk', '\App\Http\Controllers\API\ProductAPIController@importBulk');
        Route::delete('products-gallery/{productGallery}', '\App\Http\Controllers\API\ProductAPIController@deleteGallery');

        Route::post('orders/{order}', '\App\Http\Controllers\API\OrderAPIController@update');
        Route::resource('orders', '\App\Http\Controllers\API\OrderAPIController', ['except' => ['update']]);
        Route::post('orders-delete-multiple', '\App\Http\Controllers\API\OrderAPIController@deleteAll');
        Route::get('orders-export', '\App\Http\Controllers\API\OrderAPIController@export');
        Route::post('orders-import-bulk', '\App\Http\Controllers\API\OrderAPIController@importBulk');
        Route::delete('orders-product-id/{orderProduct}', '\App\Http\Controllers\API\OrderAPIController@deleteProductId');

        Route::resource('carts', '\App\Http\Controllers\API\CartAPIController');
        Route::post('carts-delete-multiple', '\App\Http\Controllers\API\CartAPIController@deleteAll');
        Route::get('carts-export', '\App\Http\Controllers\API\CartAPIController@export');
        Route::post('carts-import-bulk', '\App\Http\Controllers\API\CartAPIController@importBulk');

        Route::post('homebanners/{homebanner}', '\App\Http\Controllers\API\HomeBannerAPIController@update'); // RM
        Route::resource('homebanners', '\App\Http\Controllers\API\HomeBannerAPIController', ['except' => ['update']]); // RM
        Route::post('homebanners-delete-multiple', '\App\Http\Controllers\API\HomeBannerAPIController@deleteAll'); // RM
        // Route::get('homebanners-export', '\App\Http\Controllers\API\HomeBannerAPIController@export');
        // Route::post('homebanners-import-bulk', '\App\Http\Controllers\API\HomeBannerAPIController@importBulk');
    });

    Route::group([
        'middleware' => ['auth:api'],
    ], function () {

        Route::post('products-upload-zip', '\App\Http\Controllers\API\ProductAPIController@uploadZipFile');
        Route::post('orders-upload-zip', '\App\Http\Controllers\API\OrderAPIController@uploadZipFile');

        Route::post('user_status/verify', '\App\Http\Controllers\API\UserStatusUpdateAPIController@verify'); // RM
    });
});