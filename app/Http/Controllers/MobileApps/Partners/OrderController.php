<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Order;
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


        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('today_orders', 'tommorow_orders')
        ];

    }

}
