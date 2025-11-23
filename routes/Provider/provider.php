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


Route::group([ 'middleware' => 'api','namespace' => 'Api\Provider','prefix' => 'provider'], function () {

    ### Auth ###
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('register', 'AuthController@register');
    Route::post('update_profile', 'AuthController@update_profile');
    Route::get('partner_profile', 'AuthController@partner_profile');

    ### Reviews ###
    Route::get('reviews', 'HomeController@reviews');

    ## Suggestion ###
    Route::POST('suggest_product','HomeController@suggest_product');

    ## Control Products ###
    Route::GET('control_products','HomeController@control_products');
    Route::POST('edit_my_products','HomeController@edit_my_products');

    ## Statistics ###
    Route::GET('statistics','HomeController@statistics');

    ### Orders ###
    Route::get('orders', 'OrderController@orders');
    Route::get('getRejectedFromAllOrders', 'OrderController@getRejectedFromAllOrders');
    Route::get('order_details', 'OrderController@order_details');
    Route::post('pin_order', 'OrderController@pin_order');
    Route::post('hide_order', 'OrderController@hide_order');
    Route::post('add_offer', 'OrderController@add_offer');
    
    

});



Route::fallback(function () {
    return response()->json(['data'=>null,'message' => 'Not Found!','status'=>404], 200);
});
