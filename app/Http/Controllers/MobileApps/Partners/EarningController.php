<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EarningController extends Controller
{
    public function index(Request $request){

        $user = $request->user;

        $start1=date("Y-m-d", strtotime("last week monday"));
        $end1=date("Y-m-d", strtotime("last week sunday"));

        $start0=date('Y-m-d', strtotime('+1 days', strtotime($end1)));
        $end0=date('Y-m-d', strtotime('+6 days', strtotime($start0)));

        $start2=date('Y-m-d', strtotime('-7 days', strtotime($start1)));
        $end2=date('Y-m-d', strtotime('+6 days', strtotime($start2)));

        $start3=date('Y-m-d', strtotime('-7 days', strtotime($start2)));
        $end3=date('Y-m-d', strtotime('+6 days', strtotime($start3)));

        $earnings0 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start0)
            ->where('orders.delivery_date', '<=', $end0)
            ->sum('order_details.commissions*order_details.packet_price*products.packet_count');

        $earnings0 = round($earnings0/100);

        $earnings1 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start1)
            ->where('orders.delivery_date', '<=', $end1)
            ->sum('order_details.commissions*order_details.packet_price*products.packet_count');

        $earnings1 = round($earnings1/100);

        $earnings2 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start2)
            ->where('orders.delivery_date', '<=', $end2)
            ->sum('order_details.commissions*order_details.packet_price*products.packet_count');

        $earnings2 = round($earnings2/100);

        $earnings3 = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start3)
            ->where('orders.delivery_date', '<=', $end3)
            ->sum('order_details.commissions*order_details.packet_price*products.packet_count');

        $earnings3 = round($earnings3/100);

        if(!empty($request->start_date) && !empty($request->end_date)){
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }else{
            $start_date = $start0;
            $end_date = $start0;
        }

        $deliveries = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.products_id')
            ->where('orders.delivery_partner', $user->id)
            ->where('order_details.status', 'delivered')
            ->where('orders.delivery_date', '>=', $start_date)
            ->where('orders.delivery_date', '<=', $end_date)
            ->groupBY('order_id')
            ->select(DB::raw('count(*) as count'), DB::raw('count(*) as count'), DB::raw('sum() as count'), 'order_id')
            ->get();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('start0', 'end0', 'start1', 'end1', 'start2', 'end2', 'start3', 'end3', 'earnings0', 'earnings1', 'earnings2', 'earnings3', 'deliveries')
        ];


    }
}
