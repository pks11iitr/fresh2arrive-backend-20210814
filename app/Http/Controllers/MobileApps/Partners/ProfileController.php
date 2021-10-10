<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use FileTransfer;

    public function updateKYC(Request $request){

        $user = $request->user;

        $request->validate([
            'pan_no'=>'required',
            'aadhaar_no'=>'required'
        ]);

        if(empty($user->pan_url) || empty($user->aadhaar_url)){

            if(empty($request->pan_image) || empty($request->aadhaar_image))
                return [
                    'status'=>'failed',
                    'action'=>'',
                    'display_message'=>'Please upload pan & aadhaar images',
                    'data'=>[],
                ];
        }


        $pan_url = $this->getImagePath($request->pan_image, 'user/'.$user->id.'pan');
        $aadhaar_url = $this->getImagePath($request->pan_image, 'user/'.$user->id.'aadhaar');

        $user->update([
            'pan_no'=>$request->pan_no,
            'aadhaar_no'=>$request->aadhaar_no,
            'aadhaar_url'=>$aadhaar_url,
            'pan_url'=>$pan_url
        ]);

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'KYC Details Updated Successfully',
            'data'=>[],
        ];



    }

    public function getKYC(Request $request){
        $user = $request->user;

        $kyc = $user->only('aadhaar_no', 'pan_no', 'aadhaar_url', 'pan_url');

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('kyc'),
        ];

    }


    public function updateBankDetails(Request $request){
        $user = $request->user;

        $request->validate([
            'bank_account_holder'=>'required',
            'bank_account_no'=> 'required',
            'bank_ifsc'     =>  'required'
        ]);


        $user->update($request->only('bank_account_holder', 'bank_account_no', 'bank_ifsc'));

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Bank Details Updated Successfully',
            'data'=>[],
        ];

    }


    public function getBankDetails(Request $request){

        $user = $request->user;

        $bank = $user->only('bank_account_holder', 'bank_account_no', 'bank_ifsc');

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('bank'),
        ];
    }

}
