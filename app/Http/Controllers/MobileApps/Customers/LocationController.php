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

        $map_json=json_decode($request->location_data, true);

        $location = $map_address = $map_json['results'][0]['address_components'];

        $json = array_reverse($json);
        $locality1 = $json[3]['long_name'] ?? '';
        $locality2 = $json[4]['long_name'] ?? '';
        $locality3 = $json[5]['long_name'] ?? '';
        $locality4 = $json[6]['long_name'] ?? '';
        $locality5 = $json[7]['long_name'] ?? '';

        $pincode = $json[0]['long_name'];


        $location = Area::active()
            ->where(function ($query) use ($locality1, $locality2, $locality3, $locality4, $locality5)
            {
                $query->where('name', $locality1)
                    ->orWhere('name', $locality2)
                    ->orWhere('name', $locality3)
                    ->orWhere('name', $locality4)
                    ->orWhere('name', $locality5);
            })
            ->where('pincode', $pincode)
            ->first();

        if ($location) {
            return [
                'status' => 'success',
                'message' => 'Location is serviceable'
            ];
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>[
                'name'=>$user->name??'',
                'house_no'=>$user->house_no??'',
                'building'=>$user->building??'',
                'street'=>$user->street??'',
                'area'=>$location->name,
                'city'=>$location->city,
                'state'=>$location->state,
                'pincode'=>$location->pincode
            ]
        ];


        return [
            'status'=>'failed',
            'action'=>'',
            'display_message'=>'Location is not servicable'
        ];

    }
}
