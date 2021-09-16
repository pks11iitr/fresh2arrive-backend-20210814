<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function initiateOrder(Request $request){

        $user=$request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        if(empty($request->cart)){
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Shopping cart is empty',
                'data'=>[]
            ];
        }

        $product = Product::whereIn('id', array_key($request->cart))
            get()

    }


}
