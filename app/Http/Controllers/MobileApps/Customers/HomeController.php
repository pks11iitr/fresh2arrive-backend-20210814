<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){

        $user = $request->user;

        if(empty($request->page) || $request->page==1){
            $categories=Category::active()
                ->select('name', 'image', 'id', 'font_color')
                ->get();

	   foreach($categories as $cat){

			if($request->category_id == $cat->id)
				$cat->is_selected=1;
			else
				$cat->is_selected=0;

		}

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
            ->select('id', 'company','name','image','display_pack_size', 'price_per_unit','cut_price_per_unit', 'unit_name', 'packet_price', 'tag', 'min_qty', 'max_qty');

        if(!empty($request->category_id) && is_numeric($request->category_id)){
            $products = $products->where('category_id', $request->category_id);
        }

        $products = $products->paginate(1000);

        Product::setCartQuantity($products, $request->cart);
        $cart_total_quantity=$request->cart_total_quantity;
        Product::setLimitedStock($products);


        $partner = $user->partner->name??'';
        $partner_whatsapp = $user->partner->whatsapp_group??'';

        $next_time_slot='Next Delivery Slot: '.(TimeSlot::getAvailableTimeSlotsList(date('H:i:s'))[0]['name']??'');

        $order = Order::where('user_id', $user->id??0)
            ->with('partner')
            ->where('status', 'delivered')
            ->where('is_reviewed', false)
            ->first();

        $pending_partner_name = $order->partner->name??'';
        $pending_order_id=$order->id??'';

        if($user)
            $user = $user->only('name','mobile');
        else
            $user = [
                'name'=>'Hi Guest',
                'mobile'=>'Fresh2Arrive'
            ];

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('categories', 'banners', 'products', 'cart_total_quantity', 'partner','next_time_slot', 'pending_partner_name', 'pending_order_id', 'partner_whatsapp', 'user')
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
                ->paginate(100);
        }else{
            $products=Product::join('banner_products', 'products.id', '=', 'banner_products.product_id')
                ->where('isactive', true)
                ->where('banner_products.banner_id', $id)
                ->select('products.id', 'company','name','image','display_pack_size', 'price_per_unit','cut_price_per_unit', 'unit_name', 'packet_price', 'tag', 'min_qty', 'max_qty')
                ->paginate(100);
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
