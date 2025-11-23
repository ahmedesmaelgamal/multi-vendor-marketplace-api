<?php

use Illuminate\Http\Request;
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


Route::group([ 'middleware' => 'api','namespace' => 'Api\Representative','prefix' => 'representative'], function () {

    ### Auth ###
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('register', 'AuthController@register');
    Route::get('nationalities', 'AuthController@nationalities');
    Route::post('changeStatus', 'AuthController@changeStatus');
    Route::post('update_profile', 'AuthController@update_profile');


    ### Representative ###
    Route::get('new_orders', 'OrderController@new_orders');
    Route::get('order_details', 'OrderController@order_details');
    Route::get('current_orders', 'OrderController@current_orders');
    Route::POST('updateOrderStatus', 'OrderController@updateOrderStatus');
    Route::get('last_orders', 'OrderController@last_orders');



});





Route::fallback(function () {
    return response()->json(['data'=>null,'message' => 'Not Found!','status'=>404], 200);
});
