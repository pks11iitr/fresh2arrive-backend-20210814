<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Banner;
use App\Models\Configuration;
use Illuminate\Http\Request;

class SidebarController extends Controller
{



    public function referDetails(Request $request){

        $user = $request->user;
        $areas = Area::select('city')->distinct()->get();

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
                'Your friends will be get Rs. 51 as a welcome bonus on registration.'
            ];
        else{
            $conditions=[

            ];
        }

        $share = Banner::where('type', 'share')
            ->first();




        $refer_link= [
            'link'=>!empty($user)?$user->getDynamicLink():'https://play.google.com/store/apps/details?id=com.fresh.arrive',
            'image'=>$share->image,
            'product_text'=>'I am using fresh2arrive app for online purchase fresh veggies & fruits. Use my refferal link & you will get Rs.51 in your wallet.',
            'app_text'=>'Order Now'
            //'qr_image'=>'https://images.freekaamaal.com/featured_images/174550_beereebn.png'
        ];

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('conditions', 'referral_amount', 'user_id', 'cities', 'refer_link')
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
