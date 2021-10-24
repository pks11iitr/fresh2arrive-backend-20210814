<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $commissions = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where('order_details.status', '=', 'delivered')
            ->where('orders.delivery_date', $today)
            ->where('orders.delivery_partner', $user->id)
            ->sum(DB::raw('order_details.packet_count*order_details.packet_price*order_details.commissions'));

        $today_earnings = round($commissions/100);

        $new_order=Order::with(['details', 'customer'])
            ->where('is_accepted', false)
            ->where('delivery_partner', $user->id)
            ->where('status', 'confirmed')
            ->orderBy('id', 'asc')
            ->first();
        if($new_order){
            $new_order =[
                'id'=>$new_order->id,
                'customer'=>$new_order->customer->name??'Not Mentioned',
                'customer_mobile'=>$new_order->customer->mobile??'Not Mentioned',
                'lat'=>$new_order->customer->lat??0.0,
                'lang'=>$new_order->customer->lang??0.0,
                'delivery_text'=>'You can accept this within 2 hrs',
                'address'=>$new_order->customer->map_address??''
            ];
        }else{
            $new_order=[
			        'id'=>0,
                	'customer'=>'',
                	'customer_mobile'=>'',
                	'lat'=>0.0,
                	'lang'=>0.0,
                	'delivery_text'=>'',
                	'address'=>''
		];
        }

        $orders=[
            'tomorrow_orders'=>$tomorrow_orders,
            'today_deliveries'=>$today_delivered,
            'today_delivered'=>$today_deliveries,
            'todays_earning'=>$today_earnings
        ];


        $earnings=[
            'percentage'=>'15%',
            'quantity'=>'all'
        ];

        $top_skus = OrderDetail::join('products', 'products.id', '=', 'order_details.product_id')
            //->where('order_details.status', '=', 'delivered')
            ->groupBy('order_details.product_id')
            ->select(DB::raw('sum(order_details.packet_count*order_details.packet_price)  as sum'), 'products.id')
            ->orderBy('sum', 'desc')
            ->skip(0)
            ->take(10)
            ->get();

        $top_sku_ids = $top_skus->map(function($element){
            return $element->id;
        })->toArray();

        $products = Product::active()
            ->whereIn('id', $top_sku_ids)
            ->get();

        $top_skus = compact('products');

        $category_earnings = Category::active()
            ->get();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('banners', 'name', 'top_skus', 'earnings', 'orders', 'category_earnings', 'new_order')
        ];

    }
}
