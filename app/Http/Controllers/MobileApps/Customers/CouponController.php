<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function list(Request $request){
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];
        $user=$request->user;

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

    public function applyCoupon(Request $request){

        $user= auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];

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

        $discount=Cart::getCouponDiscount($items, $coupon)??0;

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
            'total_payble'=>round($cost+$echo_charges-$discount,2)
        ];

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Discount of Rs. '.$discount.' Applied Successfully',
            'data'=>compact('prices'),
        ];
    }

}
