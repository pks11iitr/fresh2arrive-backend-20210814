<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateAddress(Request $request){

        $user=auth()->guard('customer-api')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $request->validate([
            'name'=>'required',
            'house_no'=>'required',
            'street'=>'required',
            'area'=>'required',
            'city'=>'required',
            'state'=>'required',
            'pincode'=>'required'
        ]);

        $user->update($request->only('name', 'building', 'house_no', 'area', 'street', 'city', 'state', 'pincode', 'map_address', 'lat', 'lang', 'map_json'));

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Location has been changed',
            'data'=>[]
        ];

    }
}
