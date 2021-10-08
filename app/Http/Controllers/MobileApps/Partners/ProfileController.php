<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateKYC(Request $request){

        $user = $request->user;

    }

    public function updateBankDetails(Request $request){
        $user = $request->user;

        $request->validate([
            'bank_account_holder'=>'required',
            'bank_account_no'=> 'required',
            'bank_ifsc'     =>  'required'
        ]);


        $user->update($request->accepts());

    }

}
