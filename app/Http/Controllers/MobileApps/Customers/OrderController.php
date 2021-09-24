<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request){

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
                'action'=>'open_home',
                'display_message'=>'Shopping cart is empty',
                'data'=>[]
            ];
        }









    }


    public function list(Request $request, $type){

    }


    public function details(Request $request, $id){

    }

    public function cancel(Request $request, $id){

    }


}
