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

    Route::get('/','Admin\DashboardController@index')->name('dashboard');

    Route::group(['prefix'=>'banners'], function(){

            Route::get('/','Admin\BannerController@index')->name('banners.list');
            Route::get('create','Admin\BannerController@create')->name('banners.create');
            Route::post('store', 'Admin\BannerController@store')->name('banners.store');
            Route::get('edit/{id}','Admin\BannerController@edit')->name('banners.edit');
            Route::post('update/{id}', 'Admin\BannerController@update')->name('banners.update');

    });

});

require __DIR__.'/auth.php';
