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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware'=>['auth', 'acl']], function(){

Route::get('/role-check', 'Admin\HomeController@check_n_redirect')->name('user.role.check');

});

Route::group(['middleware'=>['auth', 'acl'], 'is'=>'admin'], function(){

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    Route::get('/','Admin\DashboardController@index')->name('dashboard');

    Route::group(['prefix'=>'banners'], function(){

            Route::get('/','Admin\BannerController@index')->name('banners.list');
            Route::get('create','Admin\BannerController@create')->name('banners.create');
            Route::post('store', 'Admin\BannerController@store')->name('banners.store');
            Route::get('edit/{id}','Admin\BannerController@edit')->name('banners.edit');
            Route::post('update/{id}', 'Admin\BannerController@update')->name('banners.update');

    });


    Route::group(['prefix'=>'category'], function(){

        Route::get('/','Admin\CategoryController@index')->name('category.list');
        Route::get('create','Admin\CategoryController@create')->name('category.create');
        Route::post('store', 'Admin\CategoryController@store')->name('category.store');
        Route::get('edit/{id}','Admin\CategoryController@edit')->name('category.edit');
        Route::post('update/{id}', 'Admin\CategoryController@update')->name('category.update');

    });

    Route::group(['prefix'=>'inventory'], function(){

        Route::get('/','Admin\InventoryController@index')->name('inventory.list');
        Route::get('create','Admin\InventoryController@create')->name('inventory.create');
        Route::post('store', 'Admin\InventoryController@store')->name('inventory.store');
        Route::get('edit/{id}','Admin\InventoryController@edit')->name('inventory.edit');
        Route::post('update/{id}', 'Admin\InventoryController@update')->name('inventory.update');

    });


    Route::group(['prefix'=>'partners'],function (){

        Route::get('/','Admin\PartnerController@index')->name('partners.list');
        Route::get('create','Admin\PartnerController@create')->name('partners.create');
        Route::post('store', 'Admin\PartnerController@store')->name('partners.store');
        Route::get('edit/{id}','Admin\PartnerController@edit')->name('partners.edit');
        Route::post('update/{id}', 'Admin\PartnerController@update')->name('partners.update');


    });


    Route::group(['prefix'=>'customers'],function(){
       Route::get('/','Admin\CustomerController@index')->name('customers.list');
       Route::get('create','Admin\CustomerController@create')->name('customers.create');
        Route::post('store', 'Admin\CustomerController@store')->name('customers.store');
        Route::get('edit/{id}','Admin\CustomerController@edit')->name('customers.edit');
        Route::post('update/{id}', 'Admin\CustomerController@update')->name('customers.update');
    });




    Route::group(['prefix'=>'orders'],function(){
        Route::get('/','Admin\OrderController@index')->name('orders.list');
        Route::get('create','Admin\OrderController@create')->name('orders.create');
        Route::post('store', 'Admin\OrderController@store')->name('orders.store');
        Route::get('edit/{id}','Admin\OrderController@edit')->name('orders.edit');
        Route::post('update/{id}', 'Admin\OrderController@update')->name('orders.update');
    });



    Route::group(['prefix'=>'products'],function(){
        Route::get('/','Admin\ProductController@index')->name('products.list');
        Route::get('create','Admin\ProductController@create')->name('products.create');
        Route::post('store', 'Admin\ProductController@store')->name('products.store');
        Route::get('edit/{id}','Admin\ProductController@edit')->name('products.edit');
        Route::post('update/{id}', 'Admin\ProductController@update')->name('products.update');
    });


    Route::group(['prefix'=>'inventory'],function(){
        Route::get('/','Admin\InventoryController@index')->name('inventory.list');
        Route::get('create','Admin\InventoryController@create')->name('inventory.create');
        Route::post('store', 'Admin\InventoryController@store')->name('inventory.store');
        Route::get('edit/{id}','Admin\InventoryController@edit')->name('inventory.edit');
        Route::post('update/{id}', 'Admin\InventoryController@update')->name('inventory.update');
    });

    Route::group(['prefix'=>'coupon'],function(){

        Route::get('/','Admin\CouponController@index')->name('coupon.list');
        Route::get('create','Admin\CouponController@create')->name('coupon.create');
        Route::post('store', 'Admin\CouponController@store')->name('coupon.store');
        Route::get('edit/{id}','Admin\CouponController@edit')->name('coupon.edit');
        Route::post('update/{id}', 'Admin\CouponController@update')->name('coupon.update');

    });




});

require __DIR__.'/auth.php';
