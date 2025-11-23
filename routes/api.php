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


Route::group([ 'middleware' => 'api','namespace' => 'Api'], function () {

    ### Setting ###
    Route::get('setting', 'SettingController@index');

    ### Contact ###
    Route::post('contact_us', 'SettingController@contact_us');

    ### delivery_times ###
    Route::get('delivery_times', 'SettingController@delivery_times');


});

Route::group([ 'middleware' => 'api','namespace' => 'Api\Client'], function () {

    ### Auth ###
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::get('getProfile', 'AuthController@getProfile');
    Route::POST('editProfile', 'AuthController@editProfile');
    Route::POST('storeToken', 'AuthController@storeToken');
    Route::POST('logout', 'AuthController@logout');
    ### address ###
    Route::get('address', 'AuthController@address');
    Route::post('storeAddress', 'AuthController@storeAddress');
    ### List ###
    Route::get('myList', 'ProductController@myList');
    Route::post('storeToList', 'ProductController@storeToList');

    ### HomePage ###
    Route::get('home_search', 'HomePageController@search')->name('home_search');
    Route::get('sliders', 'HomePageController@sliders');
    Route::get('main_categories', 'HomePageController@main_categories');
    Route::get('latest_products', 'HomePageController@latest_products');
    Route::get('mostSales', 'HomePageController@mostSales');

    ### Products ###
    Route::get('all_products', 'ProductController@all_products');
    Route::get('product_details', 'ProductController@product_details');

    ### Categories ###
    Route::get('sub_categories', 'CategoryController@sub_categories');
    Route::get('search', 'CategoryController@search');


    ### Order ###
    Route::post('storeOrder', 'OrderController@storeOrder');
    Route::post('updateOrderStatus', 'OrderController@updateOrderStatus');
    Route::get('getOrders', 'OrderController@getOrders');
    Route::get('orderDetails', 'OrderController@orderDetails');
    ### order offer ###
    Route::get('offerDetails', 'OrderController@offerDetails');
    Route::post('updateOfferStatus', 'OrderController@updateOfferStatus');

    ### Rate ###
    Route::post('storeRate', 'OrderController@storeRate');

    ### chat ###
    Route::get('getChat', 'ChatController@getChat');
    Route::post('storeMessage', 'ChatController@storeMessage');


    ### notifications ###
    Route::get('getNotifications', 'AuthController@getNotifications');



});
Route::fallback(function () {
    return response()->json(['data'=>null,'message' => 'Not Found!','status'=>404], 200);
});
