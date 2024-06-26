<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EarningController extends Controller
{
    public function index(Request $request){

        $user = $request->user;

        $start1=date("Y-m-d", strtotime("last week monday"));
        $end1=date("Y-m-d", strtotime("last week sunday"));

        $st_en_1=date('d M', strtotime($start1)).'-'.date('d M', strtotime($end1));

        $start0=date('Y-m-d', strtotime('+1 days', strtotime($end1)));
        $end0=date('Y-m-d', strtotime('+6 days', strtotime($start0)));

        $st_en_0=date('d M', strtotime($start0)).'-'.date('d M', strtotime($end0));

        $start2=date('Y-m-d', strtotime('-7 days', strtotime($start1)));
        $end2=date('Y-m-d', strtotime('+6 days', strtotime($start2)));

        $st_en_2=date('d M', strtotime($start2)).'-'.date('d M', strtotime($end2));

        $start3=date('Y-m-d', strtotime('-7 days', strtotime($start2)));
        $end3=date('Y-m-d', strtotime('+6 days', strtotime($start3)));

        $st_en_3=date('d M', strtotime($start3)).'-'.date('d M', strtotime($end3));

        $earnings0 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start0)
            ->where('orders.delivery_date', '<=', $end0)
            ->sum(DB::raw('round(order_details.commissions*order_details.packet_price*order_details.packet_count/100)'));

        $earnings0 = round($earnings0);

        $earnings1 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start1)
            ->where('orders.delivery_date', '<=', $end1)
            ->sum(DB::raw('round(order_details.commissions*order_details.packet_price*order_details.packet_count/100)'));

        $earnings1 = round($earnings1);

        $earnings2 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start2)
            ->where('orders.delivery_date', '<=', $end2)
            ->sum(DB::raw('round(order_details.commissions*order_details.packet_price*order_details.packet_count/100)'));

        $earnings2 = round($earnings2);

        $earnings3 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start3)
            ->where('orders.delivery_date', '<=', $end3)
            ->sum(DB::raw('round(order_details.commissions*order_details.packet_price*order_details.packet_count/100)'));

        $earnings3 = round($earnings3);

        if(!empty($request->start_date) && !empty($request->end_date)){
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }else{
            $start_date = $start0;
            $end_date = $end0;
        }

        $top_display = date('d M', strtotime($start_date)).'-'.date('d M', strtotime($end_date));
        if($start_date == $start0)
            $weekly_earnings = $earnings0;
        else if($start_date == $start1)
            $weekly_earnings = $earnings1;
        else if($start_date == $start2)
            $weekly_earnings = $earnings2;
        else if($start_date == $start3)
            $weekly_earnings = $earnings3;


        $deliveries = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            //->join('products', 'products.id', '=', 'order_details.product_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start_date)
            ->where('orders.delivery_date', '<=', $end_date)
            ->groupBY('delivery_date')
            ->select(DB::raw('count(distinct(orders.refid)) as count'), DB::raw('sum(packet_price*packet_count) as total'), DB::raw('sum(round(order_details.packet_price*order_details.commissions*order_details.packet_count/100)) as earnings'),  DB::raw('DAY(delivery_date) as date'), DB::raw('MONTHNAME(delivery_date) as month'), 'delivery_date')
            ->get();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('start0', 'end0', 'start1', 'end1', 'start2', 'end2', 'start3', 'end3', 'earnings0', 'earnings1', 'earnings2', 'earnings3', 'deliveries', 'weekly_earnings', 'top_display', 'st_en_0', 'st_en_1', 'st_en_2', 'st_en_3')
        ];


    }


    public function datewiseDetails(Request $request ){

        $user = $request->user;

        $date = $request->delivery_date;

        $categories = Category::select('id', 'name')->get();
        $category_list=[];
        foreach($categories as $c){
            $category_list[$c->id]=$c->name;
        }

        $deliveries = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', $date)
            ->groupBY('products.category_id')
            ->select(DB::raw('count(*) as count'), DB::raw('sum(order_details.packet_price*order_details.packet_count) as total'), DB::raw('sum(round(order_details.packet_price*order_details.commissions*order_details.packet_count/100)) as earnings'), 'products.category_id')
            ->get();

        $total_earnings = 0;
        foreach($deliveries as $d){
            $d->category = $category_list[$d->category_id]??'Miscelleneous';
            $total_earnings=$total_earnings+$d->earnings;
        }

        $total_order = Order::where('delivery_partner', $user->id)
            ->where('status', 'delivered')
            ->where('delivery_date', $date)
            ->count();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('deliveries', 'total_earnings', 'total_order')
        ];


    }


    public function dateCategorywiseDetails(Request $request){
        $user = $request->user;

        $date = $request->delivery_date;
        $category_id = $request->category_id;

        $category = Category::findOrFail($category_id);

        $items_list = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where('products.category_id', $category_id)
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', $date)
            ->select('products.name', 'order_details.packet_count', DB::raw('order_details.packet_count*order_details.packet_price as sale_value'), DB::raw('round(order_details.packet_price*order_details.commissions*order_details.packet_count/100) as earnings'))
            ->get();

        $total_earnings = 0;
        foreach($items_list as $d){
            $total_earnings=$total_earnings+$d->earnings;
        }


        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('items_list', 'total_earnings', 'category')
        ];

    }

}
