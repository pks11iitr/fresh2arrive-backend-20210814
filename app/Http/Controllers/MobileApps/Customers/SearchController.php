<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function home(Request $request){
        $hot_products = Product::active()->where('is_hot', true)
            ->select('id', 'name')
            ->get();

        Product::setLimitedStock($hot_products);

//        $pids = $hot_products->map(function($element){
//            return $element->id;
//        })->toArray();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('hot_products')
        ];
    }


    public function suggestions(Request $request){
        $products = Product::active()
            ->where('name', 'like', "%$request->search%")
            ->get();

//        $pids = $hot_products->map(function($element){
//            return $element->id;
//        })->toArray();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('products')
        ];
    }

}
