<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.request');
Route::post('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.reset');
Route::get('/{any}', '\App\Http\Controllers\SpaController@index')->where('any', '.*');