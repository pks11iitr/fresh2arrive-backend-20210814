<?php
namespace App\Http\Controllers\Admin;


use App\Exports\OrderReportExports;
use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Partner;
use App\Exports\OrderExports;
use App\Models\Customer;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{

        public function getid($mobile){
            return $Customer=Customer::where('mobile',$mobile)->first();
        }




    public function index(Request $request){
          $search_type=$request->search_type=='1'?'id':'user_id';
        if($request->search_type==2){
               $getval=$this->getid($request->search_value);
               $srch=$getval->id;
        }else{
             $srch=$request->search_value;
        }

        if($request->search_value){
            $orders = Order::with(['customer'=>function($customer){
                    $customer->withCount(['orders'=>function($orders){
                    $orders->where('orders.status', '!=', 'cancelled');
                }]);
            }])->where($search_type,'Like',"%$srch%");
        }else{
            $orders = Order ::with(['customer'=>function($customer){
                $customer->withCount(['orders'=>function($orders){
                    $orders->where('orders.status', '!=', 'cancelled');
                }]);
            }])->orderBy('id','desc');
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


        $partner = Partner::get();
        if($request->export == 1){
            $orders = $orders->get();
            return Excel::download(new OrderReportExports($orders, $partner), 'order-report.xlsx');
        }
        $orders=$orders->paginate(20);

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
            $quantities = OrderDetail::with('product')
                ->whereHas('orderss', function($order) use ($request){
                    $order->whereNotIn('orders.status', ['pending', 'cancelled'])
                        ->where('orders.delivery_date', '<=', $request->todate)
                        ->whereIn('orders.delivery_slot',$request->timeslots)
                        ->where('orders.delivery_date', '>=', $request->fromdate);
                })
                ->groupBy('product_id')
                ->select(DB::raw('sum(packet_count) as packet_count'), 'product_id')
                ->paginate(100);
        }
        if($request->export==1){
            return Excel::download(new OrderExports($quantities), 'report.xlsx');
        }else{
            return view('admin.orders.summary', compact('quantities', 'timeslots'));
        }


    }


    public function salesReport(Request $request){

        $quantities = [];

        $timeslots = TimeSlot::get();

        if($request->fromdate && $request->todate)
        {

             $quantities = OrderDetail::with('product')
                ->whereHas('orderss', function($order) use ($request){
                    $order->whereNotIn('orders.status', ['pending', 'cancelled'])
                        ->where('orders.created_at', '<=', $request->todate.' 23:59:59')
                        //->whereIn('orders.delivery_slot',$request->timeslots)
                        ->where('orders.created_at', '>=', $request->fromdate.' 00:00:00');
                })
                ->groupBy('product_id')
                ->select(DB::raw('sum(packet_count) as packet_count'), 'product_id')
                ->paginate(100);
        }

        return view('admin.orders.sales-summary', compact('quantities', 'timeslots'));

    }



}
