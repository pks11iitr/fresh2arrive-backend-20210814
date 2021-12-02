<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Partner;

class OrderController extends Controller
{
    public function index(Request $request){
        $search_type=$request->search_type=='1'?'refid':'name';
        if($request->search){
            $orders = Order::where($search_type,'Like',"%$request->search%");
        }else{
            $orders = Order ::orderBy('id','desc');
        }

        if($request->fromdate)
            $orders=$orders->where('delivery_date','>=', $request->fromdate);
        if($request->todate)
            $orders=$orders->where('delivery_date','<=', $request->todate);

        if($request->partner_id)
            $orders = $orders->where('delivery_partner', $request->partner_id);

        if($request->status)
            $orders = $orders->where('status', $request->status);

        $total_amount = $orders->sum('order_total');

        $orders=$orders->paginate(20);

        $partner = Partner::get();
        return view('admin.orders.view',compact('orders','partner', 'total_amount'));
    }

    public  function create(Request $request){
        return view('admin.orders.add');
    }


    public function store(Request $request){

    }

    public function edit(Request $request, $id){
        $order = Order::with(['details', 'customer', 'partner'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id){
        $order=Order::findOrFail($id);
        $order->update([
            'bags_no'=>$request->bags_no,
            'crate_no'=>$request->crate_no
        ]);

        return redirect()->back()->with('success', 'Status has been updated');
    }


    public  function update_status(Request $request, $user_id, $order_id,$book_status)
    {

        $order=Order::findOrFail($order_id);
        $order->status=$book_status;
        if($book_status == 'delivered'){
            $order->delivered_at=date('Y-m-d H:i:s');
        }
        $order->save();
        return redirect()->back()->with('success', 'Status has been updated');
    }




    public function orderWiseProductQuantity(Request $request){

        $quantities = [];

        $timeslots = TimeSlot::get();

        if($request->fromdate && $request->timeslots && $request->todate)
        {
            $quantities = OrderDetail::where('delivery_date', '>=', $request->fromdate)
                ->whereHas('orderss', function($order){
                    $order->whereNotIn('orders.status', ['pending', 'cancelled']);
                })
                ->with('product')
                ->where('delivery_date', '<=', $request->todate)
                ->whereIn('delivery_slot',$request->timeslots)
                ->groupBy('product_id')
                ->select(DB::raw('sum(packet_count) as packet_count'), 'product_id')
                ->paginate(100);
        }

        return view('admin.orders.summary', compact('quantities', 'timeslots'));

    }



}
