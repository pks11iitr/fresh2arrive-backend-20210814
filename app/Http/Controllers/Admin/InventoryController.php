<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Inventory;
class InventoryController extends Controller
{
    public  function index(Request $request){
        if($request->product_id){
        $inventory = Inventory:: whereBetween('created_at', [$request->fromdate,$request->todate])
                ->where('product_id',$request->product_id)
                ->with('product')
                ->paginate(10);
        }else{
            $inventory =Inventory::orderBy('id','desc')
                ->with('product')
                ->paginate(10);
        }
        $product = Product::get();
        return view('admin.inventory.view',compact('inventory','product'));
    }

    public  function create(Request $request){
        $products = Product::active()
            ->orderBy('name')
            ->get();
        return view('admin.inventory.add', compact('products'));
    }

    public function store(Request $request){
        $request->validate([
            'product_id'=>'required',
            'create_date'=>'required|date:Y-m-d',
            'price'=>'required|integer',
            'quantity'=>'required|integer',
            'vendor'=>'required'
        ]);

        $inventory=Inventory::create($request->only('product_id', 'create_date', 'price', 'quantity', 'vendor', 'remarks'));

        return redirect()->route('inventory.edit', ['id'=>$inventory->id])->with('success', 'Inventory Added');

    }

    public function edit(Request $request, $id){
        $products = Product::active()
            ->orderBy('name')
            ->get();
        $inventory = Inventory::findOrFail($id);

        return view('admin.inventory.edit', compact('products', 'inventory'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'product_id'=>'required',
            'create_date'=>'required|date:Y-m-d',
            'price'=>'required|integer',
            'quantity'=>'required|integer',
            'vendor'=>'required'
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->update($request->only('product_id', 'create_date', 'price', 'quantity', 'vendor', 'remarks'));

        return redirect()->back()->with('success', 'Inventory Updated');
    }



}
