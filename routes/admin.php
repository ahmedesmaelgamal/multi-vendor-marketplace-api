<?php

use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'admin'], function () {
    Route::get('login', 'AuthController@index')->name('admin.login');
    Route::POST('login', 'AuthController@login')->name('admin.login');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('adminHome');

    #### users ####
    #### users ####
    Route::get('index', action: 'UserController@index')->name('users.index');
    Route::get('showOrdersUser/{id}', 'UserController@showOrdersUser')->name('showOrdersUser');

    #### representatives ####
    Route::get('representatives', 'RepresentativeController@index')->name('representatives.index');
    Route::get('representativesShow/{id}', 'RepresentativeController@show')->name('representatives.show');
    #### Admins ####
    Route::resource('admins', 'AdminController');
    Route::POST('delete_admin', 'AdminController@delete')->name('delete_admin');
    Route::get('my_profile', 'AdminController@myProfile')->name('myProfile');

    ###providers ###
    Route::get('providers', action: 'ProviderController@index')->name('providers.index');
    Route::get('/providers/categories/{id}', action: 'ProviderController@getCategories')->name('providers.categories');
    Route::get('categoryFilterProviders', action: 'ProviderController@getCategoryFilterProviders')->name('providers.getSubCategories');
    Route::get('ProviderShow/{id}', 'ProviderController@show')->name('ProviderShow');
    Route::post('provider.updateColumnSelected', 'ProviderController@updateColumnSelected')->name("provider.updateColumnSelected");




    #### Contact ###
    Route::group(['prefix' => 'contacts'], function () {
        Route::get('/', 'ContactUsController@index')->name('contact.index');
        Route::post('delete', 'ContactUsController@delete')->name('delete_contact');
    });

    #### Settings ###
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'SettingsController@index')->name('settings.index');
        Route::resource('deliveryTimes', 'DeliveryTimeController');
        Route::POST('deliveryTimes.delete', 'DeliveryTimeController@delete')->name('deliveryTimes.delete');
    });

    ### Locations ###
    Route::group(['prefix' => 'locations'], function () {
        Route::resource('nationalities', 'NationalityController');
        Route::POST('nationalities.delete', 'NationalityController@delete')->name('nationalities.delete');
        // ____________________________________________________________________________________________________________________

        Route::resource('towns', controller: 'TownController');
        Route::POST('towns.delete', action: 'TownController@delete')->name('towns.delete');
        // ____________________________________________________________________________________________________________________
    });

    #### suggestions ###
    Route::group(['prefix' => 'suggestions'], function () {
        Route::get('/', 'SuggestionController@index')->name('suggestion.index');
        Route::post('delete', 'SuggestionController@delete')->name('delete_suggestion');
    });

    #### Sliders ####
    Route::resource('sliders', 'SlidersController');
    Route::POST('slider.delete', 'SlidersController@delete')->name('slider.delete');

    #### Categories ####
    Route::resource('categories', 'CategoryController');
    Route::POST('category.delete', 'CategoryController@delete')->name('category.delete');

    #### SubCategories ####
    Route::resource('subcategories', 'SubCategoryController');
    Route::POST('subcategory.delete', 'SubCategoryController@delete')->name('subcategory.delete');

    #### Products ####
    Route::resource('products', 'ProductController');
    Route::POST('products.delete', 'ProductController@delete')->name('products.delete');


    #### Product Images ####
    Route::GET('showProductImages/{id}', 'ProductController@showProductImages')->name('showProductImages');
    Route::POST('deleteProductImage', 'ProductController@deleteProductImage')->name('deleteProductImage');
    Route::POST('addProductPhoto', 'ProductController@addProductPhoto')->name('addProductPhoto');


    #### orders ####
    Route::get('newOrders', 'OrderController@newOrders')->name('newOrders');
    Route::get('currentOrders', 'OrderController@currentOrders')->name('currentOrders');
    Route::get('endedOrders', 'OrderController@endedOrders')->name('endedOrders');
    Route::POST('orders.delete', 'OrderController@delete')->name('orders.delete');
    Route::get('orderDetails/{id}', 'OrderController@orderDetails')->name('orderDetails');
    Route::get('orderOffers/{id}', 'OrderController@orderOffers')->name('orderOffers');
    Route::get('offer_details/{id}', 'OrderController@offer_details')->name('offer_details');

    #### clients ####
    Route::get('clientProfile/{id}', 'ClientController@clientProfile')->name('clientProfile');
    Route::get('clients', 'ClientController@index')->name('clients.index');
    Route::POST('delete_client', 'ClientController@delete')->name('delete_client');

    #### Reviews ####
    Route::group(['prefix' => 'reviews'], function () {
        Route::get('/', 'ReviewController@index')->name('reviews.index');
        Route::post('delete', 'ReviewController@delete')->name('delete_review');
    });


    #### Auth ####
    Route::get('logout', 'AuthController@logout')->name('admin.logout');

    #============================ Setting ==================================
    Route::get('setting', [SettingController::class, 'index'])->name('settings.index');
    Route::POST('setting/store', [SettingController::class, 'store'])->name('admin.settings.store');
    Route::POST('setting/update/{id}/', [SettingController::class, 'update'])->name('settingUpdate');
});



Route::get('/clear/route', function () {
    \Artisan::call('optimize:clear');
    return 'done';
});
