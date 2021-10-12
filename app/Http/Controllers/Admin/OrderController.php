<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Partner;

class OrderController extends Controller
{
    public function index(Request $request){
        $search_type=$request->search_type=='1'?'refid':'name';
        if($request->search){
            $order = Order::where($search_type,'Like',"%$request->search%")
                ->paginate(10);
        }else{
            $order = Order ::orderBy('id','desc')
                ->paginate(10);
        }
        $partner = Partner::get();
        return view('admin.orders.view',compact('order','partner'));
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

    }


}
