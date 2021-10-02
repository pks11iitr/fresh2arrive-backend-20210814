<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orders_list(Request $request){
        $user = $request->user;
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 days'));
        //$today = '2021-09-27';
        //$tomorrow = '2021-09-28';


        $today_orders = Order::where('delivery_partner', $user->id)
            ->with(['customer'=>function($customer){
                $customer->select('id', 'name', 'mobile', 'house_no', 'building', 'area', 'street', 'city', 'state', 'pincode');
            }])
            ->withCount('details')
            ->whereIn('status', ['confirmed', 'processing', 'dispatched'])
            ->where('delivery_date', $today)
//            ->select('id', 'order_total', 'user_id', 'delivery_partner', 'refid', 'created_at')
            ->orderBy('id', 'desc')
            ->get();

        $today_count=0;
        $today_date=date('d M y');
        $today_total=0;
        foreach($today_orders as $o){
            $today_count++;
            $today_total+=$o->order_total;
        }

        $tommorow_orders = Order::where('delivery_partner', $user->id)
            ->with(['customer'=>function($customer){
                $customer->select('id', 'name', 'mobile', 'house_no', 'building', 'area', 'street', 'city', 'state', 'pincode');
            }])
            ->withCount('details')
            ->whereIn('status', ['confirmed', 'processing', 'dispatched'])
            ->where('delivery_date', $tomorrow)
//            ->select('id', 'order_total', 'user_id', 'delivery_partner', 'refid', 'created_at')
            ->orderBy('id', 'desc')
            ->get();
        $tomorrow_count=0;
        $tomorrow_date=date('d M y', strtotime('+1 days'));
        $tomorrow_total=0;

        foreach($today_orders as $o){
            $tomorrow_count++;
            $tomorrow_total+=$o->order_total;
        }


        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('today_orders', 'tommorow_orders', 'today_count', 'today_total', 'today_date', 'tomorrow_count', 'tomorrow_date', 'today_total')
        ];

    }


    public function deliverOrder(Request $request){

        $request->validate([
            'order_id'=>'required',
            'map_address'=>'required',
            'delivery_image'=>'required'
        ]);

        $user = $request->user;

        $order=Order::where('delivery_partner', $user->id)
                ->whereIn('status', ['confirmed', 'processing', 'dispatched'])
                ->where('delivery_date', date('Y-m-d'))
                ->find($request->order_id);

        if(!$order){
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Invalid Delivery Request',
                'data'=>[]
            ];
        }

        $order->saveImage('delivery_image', $request->delivery_image, 'delivery-images');

        $order->delivery_partner_location = $request->map_address;
        $order->status = 'delivered';
        $order->save();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Order has been delivered',
            'data'=>[]
        ];


    }


    public function orderDetail(Request $request, $id){

        $user = $request->user;

        $details = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            //->where('delivery_partner', $user->id)
            ->select('name', 'image', 'company', 'packet_count')
            ->get();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('details')
        ];

    }

}
