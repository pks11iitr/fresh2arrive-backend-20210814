<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;

class CustomerApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=auth()->guard('customer-api')->user();

        if($user && !$user->status==2)
            return [
                'status'=>'failed',
                'action'=>'log_out',
                'display_message'=>'This account has been suspended',
                'data'=>[]
            ];

        $cart=[];
        $cart_total_quantity=0;
        if($user){
            $cart_items = Cart::where('user_id', $user->id)->get();
            $cart_total_quantity=0;
            foreach($cart_items as $c){
                $cart_total_quantity++;
                $cart[$c->product_id]=$c->quantity;
            }
        }else if($request->device_id){
            $cart_items = Cart::where('device_id', $request->device_id)->get();
            $cart_total_quantity=0;
            foreach($cart_items as $c){
                $cart_total_quantity++;
                $cart[$c->product_id]=$c->quantity;
            }
        }

        $request->merge(compact('user', 'cart', 'cart_total_quantity'));
        return $next($request);
    }
}
