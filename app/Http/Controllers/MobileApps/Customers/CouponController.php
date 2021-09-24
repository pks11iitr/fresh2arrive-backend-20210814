<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\Wallet;
use Illuminate\Http\Request;
use DB;

class CouponController extends Controller
{
    public function list(Request $request){
        $user = $request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $coupons=Coupon::active()->where('expiry_date', '>',date('Y-m-d'))
            ->select('id','code','description', 'expiry_date')
            ->get();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            "data"=>compact('coupons')
        ];
    }

    public function apply(Request $request){

        $user = $request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        if($user){
            $balance=Wallet::balance($user->id);
        }else{
            $balance = 0;
        }

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

        $items=Cart::with(['product'])
            ->where('user_id', $user->id)
            ->get();

        if(!$items)
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Please add some products to apply coupons',
                'data'=>[],
            ];

        $cost=0;
        $count=0;
        foreach($items as $detail){
            $cost=$cost+$detail->product->packet_price*$detail->quantity;
            $count++;
        }

        $discount=Coupon::getCouponDiscount($cost)??0;

        if($discount <= 0 || $discount > $cost)
        {
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Coupon Cannot Be Applied',
                'data'=>[],
            ];

        }

        if($request->echo_pack){
            $echo_charges = Configuration::where('param', 'eco_friendly_charge')->first();
        }
        $echo_charges = $echo_charges->value??0;

        $prices=[
            'item_count'=>$count,
            'item_total'=>round($cost,2),
            'echo-packing'=>$echo_charges,
            'coupon_discount'=>round($discount,2),
            'total_payble'=>round($cost+$echo_charges-$discount,2),
            'wallet_balance' => $balance
        ];

        if(!$user)
            $bottom_button_text='Login to continue';
        else{
            if(round($cost,2) <= $balance)
                $bottom_button_text = 'Place Order';
            else
                $bottom_button_text = 'Add Rs.'.(round($cost,2) - $balance).' to wallet';
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Discount of Rs. '.$discount.' Applied Successfully',
            'data'=>compact('prices', 'bottom_button_text'),
        ];
    }

}
