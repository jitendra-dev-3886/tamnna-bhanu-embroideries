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
    Route::post('check-user-logged-in-or-not', '\App\Http\Controllers\API\LoginAPIController@checkUserLoggedInORNot');

    Route::post('app-register', '\App\Http\Controllers\API\UserAPIController@register');
    Route::post('app-login', '\App\Http\Controllers\API\LoginAPIController@appLogin'); // RM
    Route::post('app-login-verify', '\App\Http\Controllers\API\LoginAPIController@appLoginVerify'); // RM

    // Route::put('users/{user}', '\App\Http\Controllers\API\UserAPIController@update'); RM
    Route::resource('users', '\App\Http\Controllers\API\UserAPIController', ['only' => ['store']]);
    /* Creating a resource route for the `users` endpoint in the API, which maps to the
    `UserAPIController` controller. However, it only allows the `store` method to be accessed for
    this endpoint. This means that only the functionality to create a new user is available through
    this endpoint. */
    Route::post('users-delete-multiple', '\App\Http\Controllers\API\UserAPIController@deleteAll');
    Route::get('users-export', '\App\Http\Controllers\API\UserAPIController@export');
    Route::get('customers-export', '\App\Http\Controllers\API\UserAPIController@customersExport');
    Route::post('users-import-bulk', '\App\Http\Controllers\API\UserAPIController@importBulk');

    Route::get('homebanners-export', '\App\Http\Controllers\API\HomeBannerAPIController@export');

    // Refreshing Token
    Route::post('refreshing-tokens', '\App\Http\Controllers\API\LoginAPIController@refreshingTokens'); // RM



    Route::group([
        'middleware' => ['auth:api', 'check.permission'],
    ], function () {

        Route::resource('users', '\App\Http\Controllers\API\UserAPIController');
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

        Route::resource('categories', '\App\Http\Controllers\API\CategoryAPIController');
        Route::post('categories-delete-multiple', '\App\Http\Controllers\API\CategoryAPIController@deleteAll');
        Route::get('categories-export', '\App\Http\Controllers\API\CategoryAPIController@export');
        Route::post('categories-import-bulk', '\App\Http\Controllers\API\CategoryAPIController@importBulk');


        Route::post('products/{product}', '\App\Http\Controllers\API\ProductAPIController@update');
        Route::resource('products', '\App\Http\Controllers\API\ProductAPIController');
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

        // Route::resource('carts', '\App\Http\Controllers\API\CartAPIController');
        // Route::post('carts-delete-multiple', '\App\Http\Controllers\API\CartAPIController@deleteAll');
        Route::get('carts-export', '\App\Http\Controllers\API\CartAPIController@export');
        Route::post('carts-import-bulk', '\App\Http\Controllers\API\CartAPIController@importBulk');

        Route::post('homebanners-update-image/{homebanner}', '\App\Http\Controllers\API\HomeBannerAPIController@updateBannerImage'); // RM
        Route::resource('homebanners', '\App\Http\Controllers\API\HomeBannerAPIController'); // RM
        Route::post('homebanners-delete-multiple', '\App\Http\Controllers\API\HomeBannerAPIController@deleteAll'); // RM

        // Route::post('homebanners-import-bulk', '\App\Http\Controllers\API\HomeBannerAPIController@importBulk');

        //Update single image
        Route::post('categories-update-image/{category}', '\App\Http\Controllers\API\CategoryAPIController@updateCategoryImage');
        Route::post('homebanners-update-image/{homebanner}', '\App\Http\Controllers\API\HomeBannerAPIController@updateBannerImage'); // RM
        Route::post('product-update-image/{product}', '\App\Http\Controllers\API\ProductAPIController@updateProductImage');
        Route::post('product-update-gallery/{product}', '\App\Http\Controllers\API\ProductAPIController@updateProductGallery');

        //Delete single image
        Route::post('categories-delete-image/{category}', '\App\Http\Controllers\API\CategoryAPIController@deleteCategoryImage');
        Route::post('homebanners-delete-image/{homebanner}', '\App\Http\Controllers\API\HomeBannerAPIController@deleteBannerImage');
        Route::post('product-delete-image/{product}', '\App\Http\Controllers\API\ProductAPIController@deleteProductImage');
    });

    Route::group([
        'middleware' => ['auth:api'],
    ], function () {

        Route::get('users', '\App\Http\Controllers\API\UserAPIController@index');
        Route::post('products-upload-zip', '\App\Http\Controllers\API\ProductAPIController@uploadZipFile');
        Route::post('orders-upload-zip', '\App\Http\Controllers\API\OrderAPIController@uploadZipFile');

        Route::post('user_status/verify', '\App\Http\Controllers\API\UserStatusUpdateAPIController@verify'); // RM
        Route::get('categories', '\App\Http\Controllers\API\CategoryAPIController@index'); // RM
        Route::get('products', '\App\Http\Controllers\API\ProductAPIController@index'); // RM
        Route::get('products/{product}', '\App\Http\Controllers\API\ProductAPIController@show'); // RM

        Route::get('orders/{order}', '\App\Http\Controllers\API\OrderAPIController@show'); // RM
        Route::get('order-history', '\App\Http\Controllers\API\OrderAPIController@orderHistory'); // RM
        Route::resource('orders', '\App\Http\Controllers\API\OrderAPIController', ['except' => ['update']]); // RM

        Route::resource('carts', '\App\Http\Controllers\API\CartAPIController'); // RM
        Route::post('carts-delete-multiple', '\App\Http\Controllers\API\CartAPIController@deleteAll'); // RM

        Route::get('customers', '\App\Http\Controllers\API\UserAPIController@customers'); // RM

        Route::get('dashboards', '\App\Http\Controllers\API\DashboardAPIController@index'); // RM

        Route::get('homebanners', '\App\Http\Controllers\API\HomeBannerAPIController@index'); // RM

        Route::get('categories-list', '\App\Http\Controllers\API\CategoryAPIController@catagoriesList'); // RM
        Route::get('products-list/{product}', '\App\Http\Controllers\API\ProductAPIController@productList');
        Route::get('get-all-modules-counts', '\App\Http\Controllers\API\CartAPIController@getCartsCounts');

        Route::post('subcategories', '\App\Http\Controllers\API\CategoryAPIController@subcategories');
        Route::get('parent-categories', '\App\Http\Controllers\API\CategoryAPIController@parentCategories');

    });
});
