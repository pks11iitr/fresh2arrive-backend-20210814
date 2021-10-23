<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Google\Service\DisplayVideo\Resource\Partners;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateAddress(Request $request){

        $user=$request->user;
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
            'pincode'=>'required',
            'map_json'=>'required',
            'lat'=>'required',
            'lang'=>'required'
        ]);

        $map_json = json_decode($request->map_json, true);
        $map_address = $map_json['results'][0]['formatted_address'];

        if(empty($user->assigned_partner)){
            $partner = Partners::getAvailablePartner($request->area, []);
            if(!$partner)
                $assigned_partner = 1;
            else
                $assigned_partner=$partner;
        }else{
            $partner = Partner::whereHas('areas', function($areas) use($request){

                $areas->where('area_list.name', $request->area);

            })
                ->find($user->assigned_partner);
            if(!$partner){
                $partner = Partners::getAvailablePartner($request->area, []);
                if(!$partner)
                    $assigned_partner = 1;
                else
                    $assigned_partner=$partner;
            }
        }

        $user->update(array_merge($request->only('name', 'building', 'house_no', 'area', 'street', 'city', 'state', 'pincode', 'lat', 'lang', 'map_json'), compact('map_address', 'assigned_partner')));

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Location has been changed',
            'data'=>[]
        ];

    }
}
