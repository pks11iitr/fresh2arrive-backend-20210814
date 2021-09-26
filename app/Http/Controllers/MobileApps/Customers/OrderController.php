<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
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
                'price'=>$detail->product->price_per_unit,
                'cut_price'=>$detail->product->cut_price_per_unit,
                'unit_name'=>$detail->product->unit_name,
                'packet_price'=>$detail->product->packet_price,
                'quantity'=>$detail->product->consumed_quantity,
                'packet_count'=>$detail->quantity
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

        $order = Order::create([
            'refid'=>$refid,
            'user_id'=>$request->user->id,
            'order_total'=>$cost,
            'coupon_applied'=>$coupon_applied,
            'coupon_discount'=>$coupon_discount,
            'echo_charges'=>$echo_charges,
            'delivery_date'=>explode('**', $request->timeslot)[0],
            'delivery_slot'=>explode('**', $request->timeslot)[1],
        ]);

        Wallet::updatewallet($user->id, 'Paid for Order: '.$refid, 'Debit', round($cost-$coupon_discount+$echo_charges), 'CASH', $order->id);

        $order->details()->saveMany($details);

        $order->is_paid=1;
        $order->save();

//        Cart::where('user_id', $user->id)
//            ->delete();

        return [
            'status'=>'successfully',
            'action'=>'',
            'display_message'=>'Order Placed Successfully',
            'data'=>[
                'order_id'=>$order->id
            ],
        ];

    }


    public function list(Request $request, $type){

    }


    public function details(Request $request, $id){

    }

    public function cancel(Request $request, $id){

    }


}
