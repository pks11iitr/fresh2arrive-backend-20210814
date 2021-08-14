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
