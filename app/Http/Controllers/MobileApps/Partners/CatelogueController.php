<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatelogueController extends Controller
{
    public function index(Request $request){
        $user = $request->user;

        $categories = Category::active()
            ->get();

        if(!empty($request->category_id)){
            foreach($categories as $c)
                if($c->id == $request->category_id){
			$c->is_selected=1;
			$earn_upto = $c->earn_upto;
		}else{
			$c->is_selected=0;
		}
                    //$earn_upto = $c->earn_upto;
        }else{
            $earn_upto = 0;
            foreach($categories as $c)
                if($c->earn_upto > $earn_upto){
			$c->is_selected=0;
			$earn_upto = $c->earn_upto;
		}else{
			$c->is_selected=0;
		}
                    //$earn_upto = $c->earn_upto;
        }

        $products=Product::active()
            ->select('id', 'company','name','image','display_pack_size', 'price_per_unit','cut_price_per_unit', 'unit_name', 'packet_price', 'tag', 'min_qty', 'max_qty');

        if(!empty($request->category_id)){
            $products = $products->where('category_id', $request->category_id);
        }

        if(!empty($request->sort_by=='commission')){
            $products = $products->orderBy(DB::raw('commissions*packet_price/100'), 'desc');
        }

        if(!empty($request->sort_by)){
            if($request->sort_by == 'top_earning'){
                $products=$products->orderBy('commissions', 'desc');
            }else if($request->sort_by == 'fast_selling'){
                $last_5_day_sales = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
                    ->groupBy('product_id')
                    ->select(DB::raw('sum(packet_count) as sum'), 'product_id')
                    ->orderBy('sum', 'desc')
                    ->where('orders.delivery_date', '>=', date('Y-m-d'))
                    ->get();

                $ids = $last_5_day_sales->map(function($element){
                    return $element->product_id;
                })->toArray();
                if($ids)
                    $products=$products->orderByRaw("FIELD(id, ".implode(',', $ids).")");
            }
        }

        $products = $products->paginate(20);

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('products', 'categories', 'earn_upto')
        ];

    }



    public function shareCateglogue(Request $request){
        $type = $request->type;

        if($type == 'product'){

            $product_id = $request->product_id;

            $product = Product::findOrFail($product_id);

            $data = [

                'image'=> $product->image,
                'product_text'=>$product->name.','.$product->company.',Rs.'.$product->packet_price.'/pack'.','.$product->display_pack_size,
                'app_text'=>'Download fresh2arrive app now',
                'link'=>'http://google.com'
            ];

            return [
                'status'=>'success',
                'action'=>'',
                'display_message'=>'',
                'data'=>$data,
            ];


        }else if($type == 'catelogue'){

            $category_id = $request->category_id;
            $sort_by = $request->sort_by;

            $products=Product::active()
                ->select('company','name','image','packet_price', 'display_pack_size');

            if(!empty($request->category_id)){
                $products = $products->where('category_id', $request->category_id);
            }

            if(!empty($request->sort_by)){
                if($request->sort_by == 'top_earning'){
                    $products=$products->orderBy('commissions', 'desc');
                }else if($request->sort_by == 'fast_selling'){
                    $last_5_day_sales = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
                        ->groupBy('product_id')
                        ->select(DB::raw('sum(packet_count) as sum'), 'product_id')
                        ->orderBy('sum', 'desc')
                        ->where('orders.delivery_date', '>=', date('Y-m-d'))
                        ->get();

                    $ids = $last_5_day_sales->map(function($element){
                        return $element->product_id;
                    })->toArray();
                    if($ids)
                        $products=$products->orderByRaw("FIELD(id, ".implode(',', $ids).")");
                }
            }

            $products=$products->get();

            $product_text=implode(',', $products->map(function($elem){
                return $elem->name.'-'.$elem->company.'-Rs.'.$elem->packet_price.'/pack'.'-'.$elem->display_pack_size;
            })->toArray());

            $data = [

                'image'=> $products[0]->image,
                'product_text'=>$product_text,
                'app_text'=>'Download fresh2arrive app now',
                'link'=>'http://google.com'
            ];

            return [
                'status'=>'success',
                'action'=>'',
                'display_message'=>'',
                'data'=>$data,
            ];
        }else if($type=='banner'){
		$banner = Banner::find($request->banner_id);
		$data=[
        	        'image'=> $banner->image,
                	'product_text'=>'Dummy Text',
                	'app_text'=>'Download fresh2arrive app now',
                	'link'=>'http://google.com'
	            ];
            return [
                'status'=>'success',
                'action'=>'',
                'display_message'=>'',
		'data'=>$data,
            ];

	}else{

            $data=[
                'image'=> 'https://images.freekaamaal.com/featured_images/174550_beereebn.png',
                'product_text'=>'Dummy Text',
                'app_text'=>'Download fresh2arrive app now',
                'link'=>'http://google.com'
            ];
            return [
                'status'=>'success',
                'action'=>'',
                'display_message'=>'',
                'data'=>$data,
            ];
        }
    }
}
