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


});

require __DIR__.'/auth.php';
