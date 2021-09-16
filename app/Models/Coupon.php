<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';


    public function getUserEligibility($user){

        if($this->expiry_date<date('Y-m-d')){
            return false;
        }

        switch($this->usage_type){
            case 'single-singleuser':
                $order=Order::where('coupon_applied', $this->code)
                    ->where(function($order){
                        $order->where('payment_mode', 'COD')
                            ->orWhere(function($order){
                                $order->where('payment_mode', 'online')
                                    ->where('payment_status', 'paid');
                            });
                    })
                    ->first();
                if($order)
                    return false;
                break;
            case 'single-multipleuser':break;
                $order=Order::where('coupon_applied', $this->code)
                    ->where('user_id', $user->id)
                    ->where(function($order){
                        $order->where('payment_mode', 'COD')
                            ->orWhere(function($order){
                                $order->where('payment_mode', 'online')
                                    ->where('payment_status', 'paid');
                            });
                    })
                    ->first();
                if($order)
                    return false;
                break;
            case 'multiple-multipleuser':
                break;

        }

        return true;

    }

    public function getCouponDiscount($amount){

        if(!$amount)
            return 0;

        if($this->type=='Fixed'){
            $discount=$this->discount;
        }else{
            $discount=floor($amount*$this->discount/100);
        }

        if($amount<$this->minimum_order){
            return 0;
        }

        if($this->maximum_discount && $discount > $this->maximum_discount)
        {
            return $this->maximum_discount;
        }
        return $discount;
    }
}
