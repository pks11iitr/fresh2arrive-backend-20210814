<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'order_id'=>'required|integer',
            'on_time'=>'required|in:yes,no',
            'on_doorstep'=>'required|in:yes,no',
            'review'=>'required',
            'rating'=>'required|integer|in:1,2,3,4,5'
        ]);

        $user=$request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $order = Order::where('status', 'delivered')
            ->where('is_reviewed', false)
            ->where('user_id', $user->id)
            ->find($request->order_id);
        if(!$order)
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'No Such order Exists',
                'data'=>[]
            ];

        $order->update($request->only('on_time', 'on_doorstep', 'review'));
        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Order has been reviewed',
            'data'=>[]
        ];
    }
}
