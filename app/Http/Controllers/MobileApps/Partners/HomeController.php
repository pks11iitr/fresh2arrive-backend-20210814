<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){

        $user = $request->user;

        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 days'));

        $name = $user->name??'Not Specified';

        $banners=Banner::active()
            ->select('id','image', 'type')
            ->orderBy('sequence_no', 'asc')
            ->get();

        $today_deliveries = Order::where('delivery_partner', $user->id)
            ->where('delivery_date', $today)
            ->count();
        $tomorrow_orders = Order::where('delivery_partner', $user->id)
            ->where('delivery_date', $tomorrow)
            ->count();
        $today_delivered = Order::where('delivery_partner', $user->id)
            ->where('delivery_date', $today)
            ->where('status', 'delivered')
            ->count();

        $orders=[

            'tomorrow_orders'=>$tomorrow_orders,
            'today_deliveries'=>$today_delivered,
            'today_delivered'=>$today_deliveries,
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
