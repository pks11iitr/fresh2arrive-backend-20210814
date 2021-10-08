<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request){
        $configurationsobj = Configuration::select('param', 'value')->whereIn('param',['partner_email', 'partner_mobile', 'partner_whatsapp'])->get();

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
