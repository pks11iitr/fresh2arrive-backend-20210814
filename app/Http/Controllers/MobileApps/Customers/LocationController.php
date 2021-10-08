<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getLocation(Request $request){

        $request->validate([
            'location_data' => 'required'
        ]);

        $user=auth()->guard('customer-api')->user();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>[
                'name'=>$user->name??'',
                'house_no'=>$user->house_no??'',
                'building'=>$user->building??'',
                'street'=>$user->street??'',
                'area'=>'Sector 62',
                'city'=>'Noida',
                'state'=>'Uttar Pradesh',
                'pincode'=>'201301'
            ]
        ];


        return [
            'status'=>'failed',
            'action'=>'',
            'display_message'=>'Location is not servicable'
        ];

    }
}
