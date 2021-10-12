<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use App\Models\TimeSlot;
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


        $pan_url = $this->getImagePath($request->pan_image, 'user/'.$user->id.'/pan');
        $aadhaar_url = $this->getImagePath($request->pan_image, 'user/'.$user->id.'/aadhaar');

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


    public function getPreferences(Request $request){
        $user=$request->user;

        $user_slots = $user->preferedTimeSlots;

        $uts=$user_slots->map(function($element){
            return $element->id;
        })->toArray();

        $timeslots = TimeSlot::active()->get();

        foreach ($timeslots as $ts){
            $ts->is_selected=0;
            if(in_array($ts->id, $uts))
                $ts->is_seleted=1;
        }

        $user = $user->only('delivery_personal_name', 'delivery_personal_mobile', 'delivery_alternate_contact');

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('timeslots', 'user'),
        ];

    }


    public function updatePreferences(Request $request){
        $user=$request->user;

        $request->validate([
            'delivery_personal_name'=>'required',
            'delivery_personal_mobile'=>'required',
            'delivery_alternate_contact'=>'required',
            'slot_ids'=>'array'
        ]);

        $user->preferedTimeSlots()->syncWithoutDetaching($request->slot_ids);

        $user->update($request->only('delivery_personal_name', 'delivery_personal_mobile', 'delivery_alternate_contact'));

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>[],
        ];


    }


    public function getSidebarInfo(Request $request){
        $user=$request->user;

        $data= [

            'name'=>'Pankaj Sengar',
            'brand'=>'Apno Ki Dukan',
            'link'=>'https://google.com',
            //'qr_image'=>route('qr.code', ['id'=>$user->id])
            'qr_image'=>'https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg'
        ];

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Preferences have been updated',
            'data'=>$data,
        ];


    }


    public function qrcodeInfo(Request $request){
        return view('qrcode');
    }


    public function inviteNewCustomer(Request $request){
        $user = $request->user;

        $request->validate([
            'name'=>'required',
            'mobile'=>'required'
        ]);


        //send message here


        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Invite has been sent',
            'data'=>[],
        ];


    }

}
