<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){

        $user = $request->user;

        if(empty($request->page) || $request->page==1){
            $categories=Category::active()
                ->select('name', 'image', 'id')
                ->get();

            $banners=Banner::active()
                ->select('id','image', 'type')
                ->orderBy('sequence_no', 'asc')
                ->get();

        }else{
            $categories=[];
            $banners=[];
        }


        $products=Product::active()
            ->orderBy('name', 'asc')
            ->select('id', 'company','name','image','display_pack_size', 'price_per_unit','cut_price_per_unit', 'unit_name', 'packet_price', 'tag', 'min_qty', 'max_qty')
            ->paginate(20);

        Product::setCartQuantity($products, $request->cart);
        $cart_total_quantity=$request->cart_total_quantity;
        Product::setLimitedStock($products);


        $partner = $user->partner->name??'';

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('categories', 'banners', 'products', 'cart_total_quantity', 'partner')
        ];
    }

    public function banner_details(Request $request, $id){

        $banners=Banner::active()
            ->select('id','image', 'type')
            ->orderBy('sequence_no', 'asc')
            ->get();

        if(!empty($request->category_id)){
            $products=Product::join('banner_products', 'products.id', '=', 'banner_products.product_id')
                ->whereHas('category', function($category) use($request){
                    $category->where('categories.id', $request->category_id);
                })
                ->where('isactive', true)
                ->where('banner_products.banner_id', $id)
                ->select('products.id', 'company','name','image','display_pack_size', 'price_per_unit','cut_price_per_unit', 'unit_name', 'packet_price', 'tag', 'min_qty', 'max_qty')
                ->paginate(20);
        }else{
            $products=Product::join('banner_products', 'products.id', '=', 'banner_products.product_id')
                ->where('isactive', true)
                ->where('banner_products.banner_id', $id)
                ->select('products.id', 'company','name','image','display_pack_size', 'price_per_unit','cut_price_per_unit', 'unit_name', 'packet_price', 'tag', 'min_qty', 'max_qty')
                ->paginate(20);
        }


        Product::setCartQuantity($products, $request->cart);
        $cart_total_quantity=$request->cart_total_quantity;
        Product::setLimitedStock($products);

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('banners', 'products', 'cart_total_quantity')
        ];

    }
}
