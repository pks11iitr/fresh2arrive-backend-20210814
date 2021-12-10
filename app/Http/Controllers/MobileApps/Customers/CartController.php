<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Configuration;
use App\Models\Coupon;
use App\Models\DeliveryPartner;
use App\Models\Inventory;
use App\Models\OrderDetail;
use App\Models\Partner;
use App\Models\Product;
use App\Models\TimeSlot;
use App\Models\Wallet;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function updateCart(Request $request){

        $request->validate([
            'action'=>'required|in:increase,decrease,delete',
            'product_id'=>'required',
            'device_id'=>'required'
        ]);

        $user=$request->user;
        $product=Product::active()
            ->findOrFail($request->product_id);

        if($user){
            $cart=Cart:://where('device_id', $request->device_id)
                where('user_id', $user->id??null)
                ->where('product_id', $request->product_id)
                ->first();
        }else{
            $cart=Cart::where('device_id', $request->device_id)
                //->where('user_id', $user->id??null)
                ->where('product_id', $request->product_id)
                ->first();
        }


        $available_stock=Product::getStock($request->product_id);

        switch($request->action){
            case 'increase':
                $result = $this->increaseCart($request->device_id, $user, $product, $cart,$available_stock);
                break;
            case 'decrease':
                $result = $this->decreaseCart($request->device_id, $user, $product, $cart);
                break;
            case 'delete':
                $result = $this->deleteCart($request->device_id, $user, $product, $cart);
                break;

        }

        if(is_array($result))
            return $result;

        $product_quantity = $result;
        $cart = Cart::cartSizeAndPrice($user->id??null, $request->device_id);

        return [
            'status'=>'success',
            'message'=>'success',
            'data'=>compact('cart', 'product_quantity')
        ];

    }
    private function increaseCart($device_id, $user, $product, $cart, $available_stock){

        if(!$cart)
            $quantity = $product->min_qty>0?$product->min_qty:1;
        else
            $quantity = $cart->quantity + 1;

        //echo $available_stock;die;
        if($quantity * $product->consumed_quantity > $available_stock)
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Product Is Out Of Stock',
                'data'=>[]
            ];

        if($quantity > $product->max_qty)
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Max Buy Quantity Is '.$product->max_qty,
                'data'=>[]
            ];

        Cart::updateOrCreate(
            [
                'user_id'=>$user->id??null,
                'device_id'=>$device_id,
                'product_id'=>$product->id
            ],
            [
                'quantity'=>$quantity
            ]
        );



        return $quantity;


    }

    private function decreaseCart($device_id, $user, $product, $cart){

        if(!$cart)
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Invalid Request',
                'data'=>[]
            ];

        $quantity = $cart->quantity - 1;

        if($quantity <= 0)
            $cart->delete();
        else
            Cart::updateOrCreate(
                [
                    'user_id'=>$user->id??null,
                    'device_id'=>$device_id,
                    'product_id'=>$product->id
                ],
                [
                    'quantity'=>$quantity
                ]
            );

        return $quantity<0?0:$quantity;

    }

    private function deleteCart($product, $user, $device_id, $cart){

        if(!$cart)
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Invalid Request',
                'data'=>[]
            ];

        $cart->delete();

        return 0;
    }

    public function getCartDetails(Request $request){

        $request->validate([
            'device_id'=>'required'
        ]);

        $user = $request->user;

        $delivery_partner=[
            'name'=>'',
            'mobile'=>'',
            'address'=>''
        ];
        if($user){
           $delivery_partner = Partner::select('name', 'mobile', 'house_no', 'landmark')->find($user->assigned_partner);
        }

        if($user){
            $itemsobj=Cart::with(['product'])
                ->whereHas('product', function($product){
                    $product->where('products.isactive', true);
                })
                ->where('user_id', $user->id)
                //->where('device_id', $request->device_id)
                ->get();
        }else{
            $itemsobj=Cart::with(['product'])
                ->whereHas('product', function($product){
                    $product->where('products.isactive', true);
                })
                //->where('user_id', $user->id)
                ->where('device_id', $request->device_id)
                ->get();
        }


        if($user){
            $balance=Wallet::balance($user->id);
            //$balance = $walletdetails['balance'];
        }else{
            $balance = 0;
        }

        $cost=0;
        $count=0;
        $items = [];
        foreach($itemsobj as $detail){
            $cost=$cost+$detail->product->packet_price*$detail->quantity;
            $count++;
            $items[] =[
                'id'=>$detail->product_id,
                'name'=>$detail->product->name,
                'company'=>$detail->product->company,
                'image'=>$detail->product->image,
                'display_pack_size'=>$detail->product->display_pack_size,
                'price_per_unit'=>$detail->product->price_per_unit,
                'cut_price_per_unit'=>$detail->product->cut_price_per_unit,
                'unit_name'=>$detail->product->unit_name,
                'packet_price'=>$detail->product->packet_price,
                'percent'=>$detail->product->percent,
                'quantity'=>$detail->quantity
            ];
        }


        //--------------Check Coupon Status-----------------
        if($user->applied_coupon){
            $coupon = Coupon::active()->where('code', $user->applied_coupon)->first();
            if(!$coupon){
                $user->applied_coupon = null;
                $user->save();
                $coupon_discount = 0;
                $coupon_applied = '';
            }else{

                $eligibility=$coupon->getUserEligibility($user);
                if(!$eligibility){
                    $user->applied_coupon = null;
                    $user->save();
                    $coupon_discount = 0;
                    $coupon_applied = '';
                }else{
                    $discount=$coupon->getCouponDiscount($cost);
                    if($discount <= 0){
                        $user->applied_coupon = null;
                        $user->save();
                        $coupon_discount = 0;
                        $coupon_applied = '';
                    }else{
                        $coupon_discount = $discount;
                        $coupon_applied = $user->coupon_applied;
                    }
                }
            }
        }else{
            $coupon_discount = 0;
            $coupon_applied = '';
        }


        //----------------check echo charges----------
        if($user->echo_selected){
            $echo_charges = Configuration::where('param', 'eco_friendly_charge')->first();
            $echo_charges = intval($echo_charges->value??0);
            $echo_selected = 1;
        }else{
            $echo_charges =0;
            $echo_selected = 0;
        }


        $prices=[
            'item_count'=>$count,
            'item_total'=>round($cost,2),
            'echo-packing'=>$echo_charges,
            'coupon_discount'=>$coupon_discount,
            'total_payble'=>round($cost,2) + $echo_charges - $coupon_discount,
            'wallet_balance'=>$balance
        ];

        $time_slots = TimeSlot::getAvailableTimeSlotsList(date('H:i:s'));

        $delivery_address = [
            'name' => '',
            'mobile' => '',
            'address' => ''
        ];
        if($user){
            $delivery_address = [
                'name' => $user->name,
                'mobile' => $user->mobile,
                'address' => ($user->house_no??'').', '.($user->building??'').', '.($user->street??'').', '.($user->area??'').', '.($user->city??'').', '.($user->state??'').'-'.($user->pincode??'')
            ];
        }

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

        $add_wallet_amount = $prices['total_payble'] - $prices['wallet_balance'];

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('items', 'prices', 'time_slots', 'delivery_partner', 'bottom_button_text', 'echo_charges', 'delivery_address', 'count', 'cost', 'echo_selected', 'coupon_applied', 'add_wallet_amount', 'min_order_value')
        ];


    }
}
