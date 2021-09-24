<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){

        $user = $request->user;

        $name = $user->name;

        $banners=Banner::active()
            ->select('id','image', 'type')
            ->orderBy('sequence_no', 'asc')
            ->get();

        $orders=[

            'tomorrow_orders'=>rand(5,20),
            'today_deliveries'=>rand(10,20),
            'today_delivered'=>rand(5,10),
            'todays_earning'=>rand(100, 500)

        ];


        $earnings=[
            'percentage'=>'15&',
            'quantity'=>'all'
        ];


        $products = Product::active()->skip(0)->limit(10);

        $top_skus = compact('products');



        return compact('banners', 'name', 'top_skus', 'earnings', 'orders');


    }
}
