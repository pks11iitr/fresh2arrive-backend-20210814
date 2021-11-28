<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    use FileTransfer;

    public function index(Request $request){

        $products = Product::where('id', '>=', 1);

        if($request->search)
            $products = $products->where('name', 'LIKE', "%$request->search%");
        $products = $products->orderBy('id','desc')
            ->paginate(100);
            
        $pids=$products->map(function($element){
				return $element->id;
			})->toArray(); 
			
		$purchased_quantity=Inventory::purchased_quantity($pids);
		$consumed_stock=OrderDetail::consumed_quantity([$pids]);
        foreach($products as $p){

			if(($purchased_quantity[$p->id]??0)-($consumed_stock[$p->id]??0)<=0){
				$p->remaining_stock='Out of stock';
			}else{
				$p->remaining_stock=($purchased_quantity[$p->id]??0)-($consumed_stock[$p->id]??0);
			}
		}	   
            
        return view('admin.products.view',compact('products'));
    }

    public  function create(Request $request){
        $categories = Category::get();
        return view('admin.products.add', compact('categories'));
    }


    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'company'=>'required',
            'delivery_image'=>'required',
            'display_pack_size'=>'required',
            'price_per_unit'=>'required',
            'cut_price_per_unit'=>'required',
            'unit_name'=>'required',
            'packet_price'=>'required',
            'consumed_quantity'=>'required',
            'isactive'=>'required',
            //'tag'=>'required',
            'min_qty'=>'required',
            'max_qty'=>'required',
            'commissions'=>'required',
            'category_id'=>'required'
        ]);

        $product = Product::create(array_merge(

            $request->only('name', 'company', 'image', 'display_pack_size', 'price_per_unit', 'cut_price_per_unit', 'unit_name', 'packet_price', 'consumed_quantity', 'isactive', 'tag', 'min_qty', 'max_qty', 'commissions', 'category_id', 'is_hot'),

            [
                'image'=>$this->getImagePath($request->delivery_image, 'products')
            ]


        ));


        return redirect()->route('products.edit', ['id'=>$product->id])->with('success', 'Product has been added');
    }

    public function edit(Request $request, $id){
        $categories = Category::get();
        $product = Product::findOrFail($id);

        return view('admin.products.edit', compact('categories', 'product'));
    }

    public function update(Request $request, $id){


        $request->validate([
            'name'=>'required',
            'company'=>'required',
            'display_pack_size'=>'required',
            'price_per_unit'=>'required',
            'cut_price_per_unit'=>'required',
            'unit_name'=>'required',
            'packet_price'=>'required',
            'consumed_quantity'=>'required',
            'isactive'=>'required',
            //'tag'=>'required',
            'min_qty'=>'required',
            'max_qty'=>'required',
            'commissions'=>'required',
            'category_id'=>'required'
        ]);

        $product = Product::findOrFail($id);

        if($request->delivery_image){
            $path = $this->getImagePath($request->delivery_image, 'products');
        }else{
            $path = $product->getRawOriginal('image');
        }
       // return $path;
       // die;


        $product->update(array_merge(
           $request->only('name', 'company', 'display_pack_size', 'price_per_unit', 'cut_price_per_unit', 'unit_name', 'packet_price', 'consumed_quantity', 'isactive', 'tag', 'min_qty', 'max_qty', 'commissions', 'category_id', 'is_hot'),
           [
               'image'=>$path
           ]
        ));



        return redirect()->back()->with('success', 'Product has been updated');
    }

}
