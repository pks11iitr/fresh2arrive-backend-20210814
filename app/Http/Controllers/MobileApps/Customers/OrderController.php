<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\DeliveryPartner;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\TimeSlot;
use App\Models\Wallet;
use Illuminate\Http\Request;
use DB;
class OrderController extends Controller
{
    public function create(Request $request){

        $request->validate([
            'timeslot'=>'required'
        ]);

        $user=$request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $coupon_discount = 0;
        $echo_charges = 0;
        $coupon_applied = null;

        $items=Cart::with(['product'])
            ->whereHas('product', function($product){
                $product->where('products.isactive', true);
            })
            ->where('user_id', $user->id)
            ->get();

        if(count($items)<=0){
            if(empty($request->cart)){
                return [
                    'status'=>'failed',
                    'action'=>'open_home',
                    'display_message'=>'Shopping cart is empty',
                    'data'=>[]
                ];
            }
        }

        $details=[];

        $cost=0;
        $count=0;
        foreach($items as $detail){
            $cost=$cost+$detail->product->packet_price*$detail->quantity;
            $count++;
            $details[]=new OrderDetail([
                'product_id'=>$detail->product_id,
                'image'=>$detail->product->getRawOriginal('image'),
                'name'=>$detail->product->name,
                'company'=>$detail->product->company,
                'display_pack_size'=>$detail->product->display_pack_size,
                'price'=>$detail->product->price_per_unit,
                'cut_price'=>$detail->product->cut_price_per_unit,
                'unit_name'=>$detail->product->unit_name,
                'packet_price'=>$detail->product->packet_price,
                'quantity'=>$detail->product->consumed_quantity,
                'packet_count'=>$detail->quantity,
                'commissions'=>$detail->product->commissions
            ]);
        }

        if($request->coupon){
            $coupon=Coupon::where(DB::raw('BINARY code'), $request->coupon??null)
                ->first();

            if(!$coupon){
                return [
                    'status'=>'failed',
                    'action'=>'',
                    'display_message'=>'Invalid Coupon Applied',
                    'data'=>[],
                ];
            }

            if($coupon->isactive==false || !$coupon->getUserEligibility($user)){
                return [
                    'status'=>'failed',
                    'action'=>'',
                    'display_message'=>'Coupon Has Been Expired',
                    'data'=>[],
                ];
            }
            //echo $cost;
            $coupon_discount=$coupon->getCouponDiscount($cost)??0;
            $coupon_applied = $coupon->code;
            //die;
            if($coupon_discount<=0){
                return [
                    'status'=>'failed',
                    'action'=>'',
                    'display_message'=>'Coupon is not valid',
                    'data'=>[],
                ];
            }
        }

        if($request->echo_pack){
            $echo_charges = Configuration::where('param', 'eco_friendly_charge')->first();
            $echo_charges = $echo_charges->value??0;
        }

        $balance=Wallet::balance($user->id);

        if($balance < $cost-$coupon_discount+$echo_charges){
            return [
                'status'=>'failed',
                'action'=>'recharge_wallet',
                'display_message'=>'Wallet balance is low',
                'data'=>[],
            ];
        }


        $refid=rand(0,9).date('YmdHis');

        $deliveryslot=explode('**', $request->timeslot)[1];
        $ts=TimeSlot::find($deliveryslot);
        if(!$ts)
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Delivery Slot is not valid',
                'data'=>[],
            ];


        $order = Order::create([
            'refid'=>$refid,
            'user_id'=>$request->user->id,
            'order_total'=>$cost,
            'coupon_applied'=>$coupon_applied,
            'coupon_discount'=>$coupon_discount,
            'echo_charges'=>$echo_charges,
            'delivery_date'=>explode('**', $request->timeslot)[0],
            'delivery_slot'=>explode('**', $request->timeslot)[1],
            'delivery_time'=>$ts->name,
            'delivery_partner'=>$user->assigned_partner
        ]);

        Wallet::updatewallet($user->id, 'Paid for Order: '.$refid, 'Debit', round($cost-$coupon_discount+$echo_charges), 'CASH', $order->id);

        $order->details()->saveMany($details);

        $order->is_paid=1;
        $order->status='confirmed';
        $order->save();

//        Cart::where('user_id', $user->id)
//            ->delete();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Order Placed Successfully',
            'data'=>[
                'order_id'=>$order->id
            ],
        ];

    }

    public function list(Request $request, $type){
        $user=$request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        if($type=='active'){
            $orders = Order::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->where('is_paid',true)
                ->whereIn('status', ['confirmed', 'processing', 'dispatched'])
                ->withCount('details')
                //->select('id', 'refid', 'order_total', 'delivery_date', 'delivery_time')
                ->paginate(10);
        }else if($type == 'cancelled'){
            $orders = Order::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->where('is_paid',true)
                ->where('status', 'cancelled')
                ->withCount('details')
               // ->select('id', 'refid', 'order_total', 'delivery_date', 'delivery_time')
                ->paginate(10);
        }else if($type == 'delivered'){
            $orders = Order::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->where('is_paid',true)
                ->withCount('details')
                ->where('status', 'delivered')
                //->select('id', 'refid', 'order_total', 'delivery_date', 'delivery_time')
                ->paginate(10);
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('orders')
        ];
    }


    public function details(Request $request, $id){

        $user=$request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $order = Order::with('details')
            ->withCount('details')
            ->where('user_id', $user->id)
            ->where('is_paid', true)
            ->find($id);
        //return $order;
        if(!$order){
            return [
                'status'=>'failed',
                'action'=>'open_home',
                'display_message'=>'No such order found',
                'data'=>[]
            ];
        }

        $cost=0;
        $count=0;
        foreach($order->details as $detail){
            $cost=$cost+$detail->packet_price*$detail->packet_count;
            $count++;
            $items[] =[
                'id'=>$detail->product_id,
                'name'=>$detail->name,
                'company'=>$detail->company,
                'image'=>$detail->image,
                'display_pack_size'=>$detail->display_pack_size,
                'price_per_unit'=>$detail->price,
                'cut_price_per_unit'=>$detail->cut_price,
                'unit_name'=>$detail->unit_name,
                'packet_price'=>$detail->packet_price,
                'percent'=>$detail->percent,
            ];
        }

        $delivery_partner='';
        if($user){
            $delivery_partner = DeliveryPartner::select('name', 'mobile')->find($order->delivery_partner);
        }

        $delivery_address = '';
        if($user){
            $delivery_address = [
                'name' => $user->name,
                'mobile' => $user->mobile,
                'address' => ($user->house_no??'').', '.($user->building??'').', '.($user->street??'').', '.($user->area??'').', '.($user->city??'').', '.($user->state??'').'-'.($user->pincode??'')
            ];
        }

        $prices=[
            'item_count'=>$count,
            'item_total'=>round($cost,2),
            'echo-packing'=>$order->echo_charges??0,
            'coupon_discount'=>$order->coupon_discount,
            'total_payble'=>round($cost,2)+($order->echo_charges??0)-$order->coupon_discount,
        ];

        $data=[
            'id'=>$order->id,
            'refid'=>$order->refid,
            'details_count'=>$order->details_count,
            'delivery_schedule'=>$order->delivery_schedule,
            'delivery_partner'=>$delivery_partner,
            'delivery_address'=>$delivery_address,
            'status'=>$order->status,
            'items'=>$items,
            'prices'=>$prices,
            'show_cancel'=>($order->status=='confirmed')?1:0
        ];


        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>$data
        ];

    }

    public function cancel(Request $request, $id){
        $user=$request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $order = Order::where('user_id', $user->id)
            ->where('is_paid', true)
            ->find($id);

        if(!$order){
            return [
                'status'=>'failed',
                'action'=>'open_home',
                'display_message'=>'No such order found',
                'data'=>[]
            ];
        }

        if($order->status!='confirmed'){
            return [
                'status'=>'failed',
                'action'=>'open_home',
                'display_message'=>'Order cannot be cancelled',
                'data'=>[]
            ];
        }

        $balance = $order->order_total+$order->echo_charges-$order->coupon_discount;

        $order->status='cancelled';
        $order->save();


        Wallet::updatewallet($user->id, 'Refund for order: '.$order->refid, 'Credit', $balance, 'CASH', $order->id);

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Order has been cancelled',
            'data'=>[]
        ];

    }


}
