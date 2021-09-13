<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\OrderDetail;
use App\Models\Product;
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

        $cart=Cart::where('device_id', $request->device_id)
            ->where('user_id', $user->id??null)
            ->where('product_id', $request->product_id)
            ->first();

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
            $quantity = $product->min_qty;
        else
            $quantity = $cart->quantity + 1;


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

        $user=$request->user;

        if($user){
            //die;

            $items=Cart::with(['product'])
                ->where('user_id', $user->id)
                ->where('device_id', $request->device_id)
                ->get();

            if($items){
                $next_slot=TimeSlot::getNextDeliverySlot();
                Cart::updateManyItemTimeSlot($items, $next_slot);
            }

        }

        $deliveryaddress=CustomerAddress::with('area')
            ->where('delivery_active',1)
            ->where('user_id',$user->id)
            ->first();

        $cartitems=Cart::with(['product','days', 'timeslot'])
            ->where('user_id', $user->id)
            ->get();

        $delivery=Configuration::where('param', 'delivery_charge')->first();


        $walletdetails=Wallet::walletdetails($user->id);
        $balance = $walletdetails['balance'];

        $club_membersip=10;
        $delivery_charge=0;
        $total=0;
        $quantity=0;
        $price_total=0;
        $price_total_discount=0;
        $item_type_count=0;
        $cartitem=array();
        $cartitem['subscriptions']=[];
        $cartitem['once']=[];
        $out_of_stock=0;
        $eligible_goldcash=0;
        $daywise_delivery_total=[];
        foreach($cartitems as $c){
            if(!$c->product->isactive){
                $c->days()->sync([]);
                $c->delete();
                continue;
            }
            $item_type_count++;

            $total=$total+($c->product->price??0)*$c->total_quantity;
            $quantity=$quantity+$c->total_quantity;
            $price_total=$price_total+($c->product->price??0)*$c->total_quantity;
            $price_total_discount=$price_total_discount+(($c->product->cut_price??0)-($c->product->price??0))*$c->total_quantity;
            $eligible_goldcash=$eligible_goldcash+($c->product->price*$c->product->eligible_goldcash/100)*$c->total_quantity;

            if($c->type=='subscription'){

                $cartitem['subscriptions'][]=array(
                    'id'=>$c->id,
                    'name'=>$c->product->name??'',
                    'company'=>$c->product->company??'',
                    'image'=>$c->product->image,
                    'product_id'=>$c->product->id??'',
                    'unit'=>$c->product->unit??'',
                    'quantity'=>$c->quantity,
                    'type'=>$c->type,
                    'start_date'=>$c->start_date,
                    'time_slot'=>$c->time_slot_id,
                    'no_of_days'=>$c->no_of_days,
                    'price'=>$c->product->price,
                    'cut_price'=>$c->product->cut_price,
                    'days'=>$c->days,
                    'timeslot'=>$c->timeslot,
                    'stock'=>$c->product->stock,
                    'date_text'=>date('d M', strtotime($c->start_date)).' By'.' 7PM',
                );

                if($user->membership_expiry>=$c->start_date){
                    $subscription_days=$c->days->map(function($element){
                        return $element->id;
                    })->toArray();
                    $count_free_days=calculateDaysCountBetweenDate($c->start_date, $user->membership_expiry, $subscription_days);
                    $delivery_charge=$delivery_charge+($c->product->delivery_charge*$c->total_quantity)-$c->quantity*$c->product->delivery_charge*$count_free_days;
                }else{
                    $delivery_charge=$delivery_charge+($c->product->delivery_charge*$c->total_quantity);
                }


            }else{

                $cartitem['once'][]=array(
                    'id'=>$c->id,
                    'name'=>$c->product->name??'',
                    'company'=>$c->product->company??'',
                    'image'=>$c->product->image,
                    'product_id'=>$c->product->id??'',
                    'unit'=>$c->product->unit??'',
                    'quantity'=>$c->quantity,
                    'type'=>$c->type,
                    'start_date'=>$c->start_date,
                    'time_slot'=>$c->time_slot_id,
                    'no_of_days'=>$c->no_of_days,
                    'price'=>$c->product->price,
                    'cut_price'=>$c->product->cut_price,
                    'days'=>$c->days,
                    'timeslot'=>$c->timeslot,
                    'stock'=>$c->product->stock,
                    'date_text'=>date('d M', strtotime($c->start_date)).' By'.' 7PM',
                );


                if(!isset($daywise_delivery_total[$c->start_date]))
                    $daywise_delivery_total[$c->start_date]=0;
                $daywise_delivery_total[$c->start_date]=$daywise_delivery_total[$c->start_date]+$c->product->price*$c->quantity;
            }

            if(!$c->product->stock)
                $out_of_stock=1;
        }

        if(!empty($daywise_delivery_total)){
            foreach($daywise_delivery_total as $key=>$val){
                if($user->membership_expiry < $key && $val< config('myconfig.delivery_charges_min_order')['non_member']){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }else if($user->membership_expiry >= $key && $val < config('myconfig.delivery_charges_min_order')['member']){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }
            }
        }

        $cashbackpoints=($eligible_goldcash < $walletdetails['cashback'])?$eligible_goldcash:$walletdetails['cashback'];

        return [
            'status'=>'success',
            'deliveryaddress'=>$deliveryaddress,
            'cartitem'=>$cartitem,
            'total'=>round($total),
            'price_total'=>round($price_total),
            'price_total_discount'=>round($price_total_discount),
            'balance'=>round($balance, 2),
            'cashbackpoints'=>round($cashbackpoints, 2),
            'club_membersip'=>round($club_membersip),
            'delivery_charge'=>round($delivery_charge),
            'quantity'=>$item_type_count,
            'coupon_discount'=>0,
            'payble_amount'=>round($price_total),
            //'coupons'=>$coupon,
            'out_of_stock'=>$out_of_stock,
        ];

    }
}
