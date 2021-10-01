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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


$api->get('app-version', 'MobileApps\VersionController@version');
//$api->post('register', 'MobileApps\Auth\RegisterController@register');
$api->post('login-with-otp', 'MobileApps\Customers\Auth\LoginController@login_with_otp');
$api->post('verify-otp', 'MobileApps\Customers\Auth\OtpController@verify');
$api->post('resend-otp', 'MobileApps\Customers\Auth\OtpController@resend');
//$api->post('reset-password', 'MobileApps\Auth\ForgotPasswordController@reset');
//$api->post('update-password', 'MobileApps\Auth\ForgotPasswordController@update');
//$api->post('login', 'MobileApps\Auth\LoginController@login');
//$api->post('fb-login', 'MobileApps\Auth\LoginController@facebookLogin');
///$api->post('google-login', 'MobileApps\Auth\LoginController@googleLogin');
//test comment again
$api->get('check-login-status', 'MobileApps\Customers\Auth\LoginController@loginCheck');

$api->post('check-location', 'MobileApps\Customers\LocationController@getLocation');



$api->group(['middleware' => ['customer-auth']], function ($api) {
    $api->post('update-address', 'MobileApps\Customers\ProfileController@updateAddress');
    $api->get('home', 'MobileApps\Customers\HomeController@index');
    $api->get('banner-details/{id}', 'MobileApps\Customers\HomeController@banner_details');
    $api->post('update-cart', 'MobileApps\Customers\CartController@updateCart');
    $api->get('cart-details', 'MobileApps\Customers\CartController@getCartDetails');

    $api->post('apply-coupon', 'MobileApps\Customers\CouponController@apply');
    $api->get('coupons-list', 'MobileApps\Customers\CouponController@list');

    $api->get('wallet-details', 'MobileApps\Customers\WalletController@index');
    $api->post('add-money', 'MobileApps\Customers\WalletController@addMoney');
    $api->post('verify-payment', 'MobileApps\Customers\WalletController@verifyRecharge');

    $api->post('put-order', 'MobileApps\Customers\OrderController@create');
    $api->get('orders/{type}', 'MobileApps\Customers\OrderController@list');
    $api->get('order-details/{id}', 'MobileApps\Customers\OrderController@details');
    $api->get('cancel-order/{id}', 'MobileApps\Customers\OrderController@cancel');

    $api->post('support/{id}', 'MobileApps\Customers\SidebarController@index');
    $api->get('service-areas', 'MobileApps\Customers\SidebarController@serviceAreas');
    $api->get('refer-details', 'MobileApps\Customers\SidebarController@referDetails');
});


$api->group(['prefix' => 'partner'], function ($api) {

    $api->get('app-version', 'MobileApps\VersionController@version');
    $api->post('login-with-otp', 'MobileApps\Partners\Auth\LoginController@login_with_otp');
    $api->post('verify-otp', 'MobileApps\Partners\Auth\OtpController@verify');
    $api->post('resend-otp', 'MobileApps\Partners\Auth\OtpController@resend');
    $api->get('check-login-status', 'MobileApps\Partners\Auth\LoginController@loginCheck');

    //therapist apis
    $api->group(['middleware' => ['partner-auth']], function ($api) {
        $api->get('home', 'MobileApps\Partners\HomeController@index');
        $api->get('catelogue', 'MobileApps\Partners\CatelogueController@index');
        $api->get('earnings', 'MobileApps\Partners\EarningController@index');
        $api->get('customers', 'MobileApps\Partners\CustomerController@index');
    });

});

