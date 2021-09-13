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


        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>[
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
