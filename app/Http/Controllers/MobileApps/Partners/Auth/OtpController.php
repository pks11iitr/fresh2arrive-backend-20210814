<?php

namespace App\Http\Controllers\MobileApps\Partners\Auth;

use App\Events\SendOtp;
use App\Models\OTPModel;
use App\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{

    /**
     * Handle a login request to the application with otp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function verify(Request $request){
        $request->validate([
            'type'=>'required|string|max:15',
            'otp'=>'required|digits:6',
            'mobile'=>'required|digits:10'
        ]);

        switch($request->type){
            case 'login': return $this->verifyLogin($request);
            //case 'reset': return $this->verifyResetPassword($request);
        }

        return [
            'status'=>'failed',
            'action'=>'invalid_request',
            'display_message'=>'Invalid Request',
            'data'=>[]
        ];
    }


    protected function verifyLogin(Request $request){
        $user=Partner::where('mobile', $request->mobile)
            ->first();

        if(!$user){
            return [
                'status'=>'failed',
                'action'=>'not_registered',
                'display_message'=>'This account is not registered with us',
                'data'=>[]
            ];
        }

        if($user->status==2){
            return [
                'status'=>'failed',
                'action'=>'log_out',
                'display_message'=>'This account has been suspended',
                'data'=>[]
            ];
        }

        if(OTPModel::verifyOTP('partner',$user->id, $request->type, $request->otp)){

            $user->notification_token=$request->notification_token;
            $user->status=1;
            $user->save();

            $token=Auth::guard('partner-api')->fromUser($user);

            if(empty($user->map_address)){
                return [
                    'status'=>'success',
                    'action'=>'fill_address',
                    'display_message'=>'',
                    'data'=>compact('token')
                ];
            }else{
                return [
                    'status'=>'success',
                    'action'=>'logged_in',
                    'display_message'=>'',
                    'data'=>compact('token')
                ];
            }
        }

        return [
            'status'=>'failed',
            'action'=>'incorrect_otp',
            'display_message'=>'Entered OTP is not correct',
            'data'=>[]
        ];

    }


    public function resend(Request $request){
        $request->validate([
            'type'=>'required|string|max:15',
            'mobile'=>'required|digits:10'
        ]);

        $user=Partner::where('mobile', $request->mobile)->first();

        if(!$user){
            return [
                'status'=>'failed',
                'action'=>'otp_verify',
                'display_message'=>'This account is not registered with us',
                'data'=>[]
            ];
        }

        if($user->status==2){
            return [
                'status'=>'failed',
                'action'=>'log_out',
                'display_message'=>'This account has been suspended',
                'data'=>[]
            ];
        }

        if(in_array($user->status, [0,1])){
                $otp=OTPModel::createOTP('partner', $user->id, $request->type);
                $msg=str_replace('{{otp}}', $otp, config('sms-templates.'.$request->type));
                event(new SendOtp($user->mobile, $msg, $request->type));

            return [
                'status'=>'success',
                'action'=>'',
                'display_message'=>'OTP Has Been Resent',
                'data'=>[]
            ];
        }

        return [
            'status'=>'failed',
            'action'=>'account_blocked',
            'display_message'=>'This account has been blocked',
            'data'=>[]
        ];

    }

}
