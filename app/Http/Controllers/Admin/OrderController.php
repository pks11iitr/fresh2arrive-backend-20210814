<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Partner;

class OrderController extends Controller
{
    public function index(Request $request){
        $search_type=$request->search_type=='1'?'refid':'name';
        if($request->search){
            $orders = Order::where($search_type,'Like',"%$request->search%")
                ->paginate(10);
        }else{
            $orders = Order ::orderBy('id','desc')
                ->paginate(10);
        }
        $partner = Partner::get();
        return view('admin.orders.view',compact('orders','partner'));
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
        $order->update([
            'status'=>$book_status,
        ]);


        return redirect()->back()->with('success', 'Status has been updated');
    }




}
