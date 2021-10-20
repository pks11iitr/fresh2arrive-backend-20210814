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
$api->get('support', 'MobileApps\Customers\SupportController@index');


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

    $api->post('raise-ticket', 'MobileApps\Customers\TicketController@raiseTicket');
    $api->get('ticket-details/{id}', 'MobileApps\Customers\TicketController@ticketDetails');
    $api->get('tickets/{type}', 'MobileApps\Customers\TicketController@ticketList');

    $api->post('support/{id}', 'MobileApps\Customers\SidebarController@index');
    $api->get('service-areas', 'MobileApps\Customers\SidebarController@serviceAreas');
    $api->get('refer-details', 'MobileApps\Customers\SidebarController@referDetails');
    $api->post('post-review', 'MobileApps\Customers\ReviewController@store');

    $api->get('search-home', 'MobileApps\Customers\SearchController@home');
    $api->post('search-suggestions', 'MobileApps\Customers\SearchController@suggestions');
});


$api->group(['prefix' => 'partner'], function ($api) {

    $api->get('app-version', 'MobileApps\VersionController@version');
    $api->post('login-with-otp', 'MobileApps\Partners\Auth\LoginController@login_with_otp');
    $api->post('verify-otp', 'MobileApps\Partners\Auth\OtpController@verify');
    $api->post('resend-otp', 'MobileApps\Partners\Auth\OtpController@resend');
    $api->get('check-login-status', 'MobileApps\Partners\Auth\LoginController@loginCheck');
    $api->get('support', 'MobileApps\Partners\SupportController@index');

    //therapist apis
    $api->group(['middleware' => ['partner-auth']], function ($api) {
        $api->get('home', 'MobileApps\Partners\HomeController@index');
        $api->get('catelogue', 'MobileApps\Partners\CatelogueController@index');

        $api->post('catelogue-share', 'MobileApps\Partners\CatelogueController@shareCateglogue');

        $api->get('earnings', 'MobileApps\Partners\EarningController@index');
        $api->get('date-earnings', 'MobileApps\Partners\EarningController@datewiseDetails');
        $api->get('date-category-earnings', 'MobileApps\Partners\EarningController@dateCategorywiseDetails');
        $api->get('customers', 'MobileApps\Partners\CustomerController@index');
        $api->get('orders-list', 'MobileApps\Partners\OrderController@orders_list');
        $api->get('orders-details/{id}', 'MobileApps\Partners\OrderController@orderDetail');
        $api->post('deliver-order', 'MobileApps\Partners\OrderController@deliverOrder');
        $api->get('deliveries', 'MobileApps\Partners\OrderController@deliveries');

        $api->get('tickets/{type}', 'MobileApps\Partners\TicketController@ticketList');
        $api->get('ticket-details/{id}', 'MobileApps\Partners\TicketController@details');
        $api->post('update-ticket', 'MobileApps\Partners\TicketController@update');

        $api->post('update-bank-details', 'MobileApps\Partners\ProfileController@updateBankDetails');
        $api->get('get-bank-details', 'MobileApps\Partners\ProfileController@getBankDetails');

        $api->post('update-kyc', 'MobileApps\Partners\ProfileController@updateKYC');
        $api->get('get-kyc', 'MobileApps\Partners\ProfileController@getKYC');

        $api->get('get-preferences', 'MobileApps\Partners\ProfileController@getPreferences');
        $api->post('update-preferences', 'MobileApps\Partners\ProfileController@updatePreferences');

        $api->get('sidebar-info', 'MobileApps\Partners\ProfileController@getSidebarInfo');

        $api->post('invite-customer', 'MobileApps\Partners\ProfileController@inviteNewCustomer');



    });
    $api->get('qrcode', ['as' => 'qr.code', 'uses' => 'MobileApps\Partners\ProfileController@qrcodeInfo']);

});

