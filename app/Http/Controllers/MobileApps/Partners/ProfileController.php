<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateKYC(Request $request){

        $user = $request->user;

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
