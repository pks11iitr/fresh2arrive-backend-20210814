<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use App\Models\Customer;
use App\Models\Partner;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

            if(empty($request->pan_image) || empty($request->aadhaar_image || !$request->hasFile('pan_image') || !$request->hasFile('aadhaar_image')))
                return [
                    'status'=>'failed',
                    'action'=>'',
                    'display_message'=>'Please upload pan & aadhaar images',
                    'data'=>[],
                ];
        }


        $pan_url = $this->getImagePath($request->pan_image, 'user/'.$user->id.'/pan');
        $aadhaar_url = $this->getImagePath($request->aadhaar_image, 'user/'.$user->id.'/aadhaar');

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

        $customer = Customer::where('mobile', $user->mobile)->first();

        $data= [

            'name'=>$user->bank_account_holder??'',
            'brand'=>$user->store_name??'',
            'link'=>!empty($customer)?$customer->getDynamicLink():'https://play.google.com/store/apps/details?id=com.fresh.arrive',
            'qr_image'=>route('qr.code', ['id'=>$user->id])
            //'qr_image'=>'https://images.freekaamaal.com/featured_images/174550_beereebn.png'
        ];

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Preferences have been updated',
            'data'=>$data,
        ];


    }


    public function qrcodeInfo(Request $request){
        //return view('qrcode');

        $user=Partner::find($request->id);

        $customer = Customer::where('mobile', $user->mobile)->first();

        $link=!empty($customer)?$customer->getDynamicLink():'https://play.google.com/store/apps/details?id=com.fresh.arrive';

//        $link = 'https://google.com';

        //header("Content-Type: image/png");
        return QrCode::size(100)->generate($link);

//        echo '<pre>';
//        print_r(get_included_files());

        //die;


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


    public function completeProfile(Request $request ){

        //$user = $request->user;

        $request->validate([
            'occupation'=>'required',
            'referred_by'=>'nullable',
            'store_name'=>'required',
            'house_no'=>'required',
            'landmark'=>'required',
            'map_address'=>'nullable',
            'support_mobile'=>'required',
            'name'=>'required'
        ]);

        $partner = Partner::where('mobile', $request->support_mobile)
            ->first();

        if($partner){
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Already registered. Please login to continue',
                'data'=>[],
            ];
        }

        $partner = Partner::create(array_merge($request->only('occupation',
            'referred_by',
            'store_name',
            'house_no',
            'landmark',
            'map_address',
            'support_mobile',
            'name'
        ), ['mobile'=>$request->support_mobile]));


        $customer = Customer::where('mobile', $partner->mobile)
            ->first();

        if(!$customer)
        {
            Customer::create([
                'mobile'=>$partner->mobile
            ]);
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Profile has been created. Please login to continue',
            'data'=>[],
        ];

    }

}
