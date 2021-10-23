<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function referDetails(Request $request){
        $cities =[

            'Delhi',
            'Noida',
            'Faridabad',
            'Ghaziabad',
            'Gurgaon'

        ];

        $user_id=$request->user->id??'';

        $referral_amount = '50';

        $conditions=[
            'Your reffered customer .... jshdsd sjfd yuewiwe riueriere',
            'Your reffered customer .... jshdsd sjfd yuewiwe riueriere',
            'Your reffered customer .... jshdsd sjfd yuewiwe riueriere',
            'Your reffered customer .... jshdsd sjfd yuewiwe riueriere',
            'Your reffered customer .... jshdsd sjfd yuewiwe riueriere',
        ];

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
