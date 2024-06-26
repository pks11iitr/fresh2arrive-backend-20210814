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

        $discount =0;

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

        }

        $items=Cart::with(['product'])
            ->whereHas('product', function($product){
                $product->where('products.isactive', true);
            })
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

        if($request->coupon){
            $discount=$coupon->getCouponDiscount($cost)??0;

            if($discount <= 0 || $discount > $cost)
            {
                return [
                    'status'=>'failed',
                    'action'=>'',
                    'display_message'=>'Coupon Cannot Be Applied',
                    'data'=>[],
                ];

            }

            $user->applied_coupon = $coupon->code;
            $coupon_applied = $coupon->code;
        }else{
            $user->applied_coupon = null;
            $coupon_applied = '';
        }

        if($request->echo_pack){
            $echo_charges = Configuration::where('param', 'eco_friendly_charge')->first();
            $echo_charges = intval($echo_charges->value??0);
            $user->echo_selected = true;
            $echo_selected=1;
        }else{
            $echo_charges = 0;
            $user->echo_selected = false;
            $echo_selected = 0;
        }


        $prices=[
            'item_count'=>$count,
            'item_total'=>round($cost,2),
            'echo-packing'=>$echo_charges,
            'coupon_discount'=>round($discount,2),
            'total_payble'=>round($cost+$echo_charges-$discount,2),
            'wallet_balance' => $balance
        ];

        $user->save();

        $min_order_value = Configuration::where('param', 'min_order_value')
            ->first();
        $min_order_value = $min_order_value->value??0;

        if(!$user)
            $bottom_button_text='Login to continue';
        else{
            if($prices['item_total'] < $min_order_value){
                $bottom_button_text = 'Minimum order value is Rs.'.$min_order_value;
            }else{
                if($prices['total_payble'] <= $prices['wallet_balance'])
                    $bottom_button_text = 'Place Order';
                else
                    $bottom_button_text = 'Add Rs.'.($prices['total_payble'] - $prices['wallet_balance']).' to wallet';
            }

        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>$discount>0?('Discount of Rs. '.$discount.' Applied Successfully'):'',
            'data'=>compact('prices', 'bottom_button_text', 'echo_selected', 'coupon_applied'),
        ];
    }

}
