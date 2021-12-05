<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Partner;
use Google\Service\DisplayVideo\Resource\Partners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Riverline\MultiPartParser\Part;

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
            //'street'=>'required',
            'area'=>'required',
            'city'=>'required',
            'state'=>'required',
            'pincode'=>'required',
            'map_json'=>'required',
            'lat'=>'required',
            'lang'=>'required'
        ]);

        $map_json = json_decode($request->map_json, true);
        $map_address = $map_json['results'][0]['formatted_address']??'';

//        if(empty($map_address))
//            return [
//                'status'=>'failed',
//                'action'=>'',
//                'display_message'=>'Location cannot be fetched',
//                'data'=>[]
//            ];


        $haversine = "(6371 * acos(cos(radians($request->lat))
                     * cos(radians(area_list.lat))
                     * cos(radians(area_list.lang)
                     - radians($request->lang))
                     + sin(radians($request->lat))
                     * sin(radians(area_list.lat))))";

        $area=Area::active()
            ->where('lat', '!=', null)
            ->where('lang', '!=', null)
            //->where('distance', '!=', null)
            ->select('area_list.*', DB::raw("$haversine as distance1"))
            ->where(DB::raw("$haversine"), '<=', DB::raw('distance'))
            ->orderBy('distance1', 'asc')
            ->where('name', $request->area)
            ->first();


        if(empty($user->assigned_partner)){
            //first time registration
            if(empty($user->reffered_by_partner)){
                // if no reffereal mentioned
                $partner = Partner::getAvailablePartner($area, []);
                if(!$partner)
                    $assigned_partner = config('constants.default_assign_partner');
                else
                    $assigned_partner=$partner;
            }else{
                // if refferred by partner
                $availability=Partner::checkPartnerAvailability($area, $user->reffered_by_partner);
                if($availability===true){
                    //assign refferer if available at location
                    $assigned_partner = $user->reffered_by_partner;
                }else{
                    // find suitable partner if reffereer not available
                    $partner = Partner::getAvailablePartner($area, []);
                    if(!$partner)
                        //$assigned_partner = config('constants.default_assign_partner');
                        return [
                            'status'=>'failed',
                            'action'=>'',
                            'display_message'=>'No delivery partner is available at this location',
                            'data'=>[]
                        ];
                    else
                        $assigned_partner=$partner;
                }
            }
        }else{
            $partner = Partner::whereHas('areas', function($areas) use($request){

                $areas->where('area_list.name', $request->area);

            })
                ->find($user->assigned_partner);
            if(!$partner){
                $partner = Partner::getAvailablePartner($area, []);
                if(!$partner)
                    //$assigned_partner = config('constants.default_assign_partner');
                    return [
                        'status'=>'failed',
                        'action'=>'',
                        'display_message'=>'No delivery partner is available at this location',
                        'data'=>[]
                    ];
                else
                    $assigned_partner=$partner;
            }else{
                $assigned_partner=$user->assigned_partner;
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
