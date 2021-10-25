<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Configuration;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function referDetails(Request $request){

        $areas = Area::distinct('city')->get();

        $cities = $areas->map(function($elem){
            return $elem->city;
        })->toArray();

        $user_id=$request->user->id??'';

        $config = Configuration::where('param', 'refer_amount')
            ->first();

        $referral_amount = $config->value??0;

        if($referral_amount>0)
            $conditions=[
                'Referral amount of Rs. '.($referral_amount/2).' will be credited on first order',
                'Referral amount of Rs. '.($referral_amount/2).' will be credited on second order',
            ];
        else{
            $conditions=[

            ];
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('conditions', 'referral_amount', 'user_id', 'cities')
        ];

    }


    public function serviceAreas(Request $request){

        $cities=Area::active()
            ->select('city')
            ->distinct('city')
            ->groupBy('city')
            ->get();

        $cities_arr=[];

        foreach($cities as $c){
            $cities_arr[strtoupper($c->city)]=[];
        }

        $arealist=Area::active()->get();

        foreach($arealist as $area){
            $cities_arr[strtoupper($area->city)][]=$area->name;
        }
        //return $cities_arr;
        return view('service-areas',compact('cities_arr'));

    }
}
