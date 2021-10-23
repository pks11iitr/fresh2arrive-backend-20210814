<?php

namespace App\Http\Controllers\Admin;
use App\Models\Area;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewServiceAreaController extends Controller
{

    public  function  index(Request $request){


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
        return view('admin.area.view_service_area',compact('cities_arr'));

            //->with('', $area)->with('', $cityname);;
    }

}
