<?php

namespace App\Http\Controllers\MobileApps\Customers\Auth;

use App\Events\SendOtp;
use App\Models\Customer;
use App\Models\OTPModel;
use App\Services\SMS\Msg91;
use App\Services\SMS\Nimbusit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string'
        ], ['mobile.*'=>'Please enter mobile number']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login_with_otp(Request $request)
    {
        $this->validateLogin($request);
        $user=Customer::where('mobile', $request->mobile)
            ->first();

        if(!$user){

            $user=Customer::create([
                'mobile'=>$request->mobile
            ]);

        }

        if($user->status==2){
            return [
                'status'=>'failed',
                'action'=>'log_out',
                'display_message'=>'This account has been suspended',
                'data'=>[]
            ];
        }

        $otp=OTPModel::createOTP('customer', $user->id, 'login');
        $msg=str_replace('{{otp}}', $otp, config('sms-templates.login'));

        event(new SendOtp($user->mobile, $msg, 'login'));

        return [
            'status'=>'success',
            'action'=>'otp_verify',
            'display_message'=>'Please verify otp to continue',
            'data'=>[]
        ];

    }

    /**
     * Handle a login request to the application with otp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function googleLogin(Request $request){

        $request->validate([
            'google_token'=>'required',
            'notification_token'=>'required',
        ]);

        $client= new \Google_Client(['client_id'=>env('GOOGLE_WEB_CLIENT_ID')]);
        $payload = $client->verifyIdToken($request->google_token);
        if (!isset($payload['email'])) {
            return [
                'status'=>'failed',
                'action'=>'invalid_token',
                'display_message'=>'Invalid Token Request',
                'data'=>[]
            ];
        }
        $email=$payload['email'];
        $name=$payload['name']??'';
        $picture=$payload['picture']??'';

        $user=Customer::where('email', $email)->first();
        if(!$user){
            $user=Customer::create([
                'name'=>$name,
                'email'=>$email,
                'email_verified_at'=>date('Y-m-d H:i:s'),
                'username'=>'TTK'.time(),
                'password'=>'none',
                'status'=>1
            ]);
        }

        if(!in_array($user->status, [0,1]))
            return [
                'status'=>'failed',
                'action'=>'account_blocked',
                'display_message'=>'This account has been blocked',
                'data'=>[]
            ];


        $user->notification_token=$request->notification_token;
        $user->save();

        $token=Auth::guard('customerapi')->fromUser($user);

        return [
            'status'=>'success',
            'action'=>'otp_verified',
            'display_message'=>'Login Successful',
            'data'=>compact('token')
        ];


    }

    public function facebookLogin(Request $request){

        $request->validate([
            'facebook_token'=>'required',
            'notification_token'=>'required',
        ]);

        $user=Socialite::driver('facebook')->userFromToken($request->facebook_token);

        if (!isset($user->email)) {
            return [
                'status'=>'failed',
                'action'=>'invalid_token',
                'display_message'=>'Invalid Token Request',
                'data'=>[]
            ];
        }
        $email=$user->email;
        $name=$user->name??'';
        //$picture=$payload['picture']??'';

        $user=Customer::where('email', $email)->first();
        if(!$user){
            $user=Customer::create([
                'name'=>$name,
                'email'=>$email,
                'email_verified_at'=>date('Y-m-d H:i:s'),
                'username'=>'TTK'.time(),
                'password'=>'none',
                'status'=>1
            ]);
        }

        if(!in_array($user->status, [0,1]))
            return [
                'status'=>'failed',
                'action'=>'account_blocked',
                'display_message'=>'This account has been blocked',
                'data'=>[]
            ];


        $user->notification_token=$request->notification_token;
        $user->save();

        $token=Auth::guard('customerapi')->fromUser($user);

        return [
            'status'=>'success',
            'action'=>'otp_verified',
            'display_message'=>'Login Successful',
            'data'=>compact('token')
        ];


    }

    public function loginCheck(Request $request){
        $user=auth()->guard('customer-api')->user();
        if($user){

            if(!$user->status==2)
                return [
                    'status'=>'failed',
                    'action'=>'log_out',
                    'display_message'=>'This account has been suspended',
                    'data'=>[]
                ];

            if(empty($user->map_json)){
                //send to address fill screen
                return [
                    'status'=>'success',
                    'action'=>'fill_address',
                    'display_message'=>'',
                    'data'=>[]
                ];
            }else{
                //send to home screen
                return [
                    'status'=>'success',
                    'action'=>'',
                    'display_message'=>'',
                    'data'=>[]
                ];
            }
        }

        //send to login page
        return [
            'status'=>'failed',
            'action'=>'log_in',
            'display_message'=>'',
            'data'=>[]
        ];

    }


    public function logout(Request $request){
        $user=$request->user;
        $user->notification_token=null;
        $user->save();
        return [
            'status'=>'success',
            'action'=>'logout_success',
            'display_message'=>'User has been logged out',
            'data'=>[]
        ];
    }

}
