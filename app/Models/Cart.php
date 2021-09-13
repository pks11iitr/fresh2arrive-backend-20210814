<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = ['user_id', 'product_id', 'device_id', 'quantity'];


    public static function cartSizeAndPrice($user_id, $device_id){

        $cart=Cart::join('products', 'products.id', '=', 'cart.product_id')
            ->where('user_id', $user_id)
            ->select('products.id', 'packet_price', 'quantity')
            ->get();

        $overall_cart_total=0;
        $overall_cart_quantity=0;

        foreach($cart as $c){
            $overall_cart_total+=$c->packet_price*$c->quantity;
            $overall_cart_quantity+=1;
        }

        return compact('overall_cart_quantity', 'overall_cart_total');

    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

}
