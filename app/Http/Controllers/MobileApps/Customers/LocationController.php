<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function getLocation(Request $request){

        $request->validate([
            'location_data' => 'required'
        ]);

        $user=auth()->guard('customer-api')->user();

        $map_json=json_decode($request->location_data, true);

        $json = $map_json['results'][0]['address_components'];

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
                $query->where(DB::raw("Locate(name, '$locality1')"), '!=', 0)
                ->orWhere(DB::raw("Locate(name, '$locality2')"), '!=', 0)
                ->orWhere(DB::raw("Locate(name, '$locality3')"), '!=', 0)
                ->orWhere(DB::raw("Locate(name, '$locality4')"), '!=', 0)
                ->orWhere(DB::raw("Locate(name, '$locality5')"), '!=', 0);
            })
            //->where('pincode', $pincode)
            ->first();

        if($location){
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
        }


        if($request->lat && $request->lang){

            $haversine = "(6371 * acos(cos(radians($request->lat))
                     * cos(radians(area_list.lat))
                     * cos(radians(area_list.lang)
                     - radians($request->lang))
                     + sin(radians($request->lat))
                     * sin(radians(area_list.lat))))";

            $location=Area::active()
                ->where('lat', '!=', null)
                ->where('lang', '!=', null)
                ->select('users.*', DB::raw("$haversine as distance"))
                ->where(DB::raw("$haversine"), '<=', 1.0)
                ->orderBy('distance', 'asc')
                ->first();

            if($location){
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
            }
        }


        return [
            'status'=>'failed',
            'action'=>'',
            'display_message'=>'Location is not servicable',
            'data'=>[]
        ];

    }
}
