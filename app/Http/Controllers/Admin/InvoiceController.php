<?php

namespace App\Http\Controllers\Admin;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request,$orderid){
        $order_detail=OrderDetail::where('order_id',$orderid)
        ->get();

        $joinquery=OrderDetail:: join('orders','orders.id','=','order_details.order_id')
            ->join('partners', 'partners.id', '=', 'orders.delivery_partner')
            ->join('customers', 'customers.id', '=', 'orders.user_id')
            ->select('orders.id as oid','orders.echo_charges','orders.created_at','orders.delivery_date','orders.bags_no','orders.crate_no','partners.name as pname','partners.address','partners.mobile as pmobile','partners.house_no as phouse_no','partners.city as pcity','partners.pincode as ppincode','partners.state as pstate',

                'customers.name','customers.mobile','customers.city','customers.house_no','customers.building','customers.street','customers.area','customers.city','customers.state','customers.pincode')
            ->where('order_id',$orderid)
            ->first();

        return view('admin.invoice',['order_data'=>$joinquery],compact('order_detail'));
    }
}
