<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request){
        $configurationsobj = Configuration::select('param', 'value')->whereIn('param',['customer_email', 'customer_mobile', 'customer_whatsapp'])->get();

        $configurations=[];
        foreach($configurationsobj as $c){
            $configurations[$c->param]=$c->value;
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            "data"=>compact('configurations')
        ];
    }
}
