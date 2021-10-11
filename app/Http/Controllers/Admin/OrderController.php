<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request){
        $order = Order ::orderBy('id','desc')
            ->paginate(10);
        return view('admin.orders.view',compact('order'));
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
